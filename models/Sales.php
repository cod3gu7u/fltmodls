<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\Batch;
use app\models\Journal;
use app\models\Posting;
use app\models\AccountingPeriod;
use app\models\SalesTransactionType;
use app\models\Vehicle;

/**
 * This is the model class for table "sales".
 *
 * @property integer $sales_id
 * @property integer $customer_id
 * @property integer $vehicle_id
 * @property integer $sales_person_id
 * @property string $sales_date
 * @property string $original_sales_amount
 * @property string $discount_amount
 * @property string $final_sales_amount
 * @property string $paid_amount
 * @property boolean $void
 * @property integer $sales_status_id
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property SalesStatus $salesStatus
 * @property Customer $customer
 * @property Vehicle $vehicle
 * @property SalesPerson $salesPerson
 * @property SalesTransaction[] $salesTransactions
 */
class Sales extends \yii\db\ActiveRecord
{
    const RETURN_JOURNAL_TYPE_ID = 3; // Return Journal
    const SALES_JOURNAL_TYPE_ID = 4; // Sales Journal
    const INVOICE_TRNX_TYPE_ID = 2; // Invoice
    const RETURN_TRNX_TYPE_ID = 3; // Return
    const QUANTITY = 1; // Default quantity
    const POSTING_STATUS = 0; // Unposted journal
    const ASSET_TYPE = 2; // Pula account
    const INSTOCK = 2;
    const VEHICLE_RESERVED_STATUS_ID = 5;
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';

    // const STATUS_ACTIVE = 'active';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sales';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'vehicle_id', 'sales_person_id', 'sales_date', 'create_user_id', 'create_date'], 'required'],
            [['vehicle_id', 'sales_person_id', 'sales_status_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['customer_id', 'sales_date', 'create_date', 'update_date'], 'safe'],
            [['original_sales_amount', 'discount_amount', 'final_sales_amount', 'paid_amount'], 'number'],
            [['void'], 'boolean'],
            [['notes', 'record_status'], 'string'],
            [['sales_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => SalesStatus::className(), 'targetAttribute' => ['sales_status_id' => 'sales_status_id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['vehicle_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vehicle::className(), 'targetAttribute' => ['vehicle_id' => 'vehicle_id']],
            [['sales_person_id'], 'exist', 'skipOnError' => true, 'targetClass' => SalesPerson::className(), 'targetAttribute' => ['sales_person_id' => 'sales_person_id']],
            // [['create_date', 'sales_date'], 'default', 'value'=>date('Y-m-d')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sales_id' => 'Sales ID',
            'customer_id' => 'Customer',
            'vehicle_id' => 'Vehicle',
            'sales_person_id' => 'Sales Person',
            'sales_date' => 'Sales Date',
            'original_sales_amount' => 'Original Sales Amount',
            'discount_amount' => 'Discount Amount',
            'final_sales_amount' => 'Total Sales Amount',
            'paid_amount' => 'Paid Amount',
            'balance' => 'Balance',
            'void' => 'Void',
            'sales_status_id' => 'Sales Status ID',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
            'create_user_id' => 'Create User ID',
            'create_date' => 'Create Date',
            'update_user_id' => 'Update User ID',
            'update_date' => 'Update Date',
        ];
    }

    public function reverseSales($model)
    {
        if (parent::beforeSave($insert)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
              /*****
                1. create batch
                2. create journal
                3. get posting accounts (Return, CrNote)
                4. get current financial period
                5. get debit and credit amount (Return, CrNote)
                6. populate journal
                7. deactive sales
                8. refund transaction
                9. instock vehicle
              *****/
              $user_id = \Yii::$app->user->identity->id;
              $tranx = new SalesTransaction();
              // Create batch
              if($batch_id = $tranx->createBatch('RETURN')){
                  $params = [
                      'batch_id'=>$batch_id,
                      'journal_type_id'=>Journal::RETURN_JOURNAL_TYPE
                      ];

                  // Create journal
                  if($journal_id = $tranx->createJournal($params)){
                      // Get related return and credit-note transaction accounts
                      $return_accounts = $tranx->getDebitCreditAccounts(SalesTransactionType::RETURN_TRNX_TYPE);
                      $crnote_accounts = $tranx->getDebitCreditAccounts(SalesTransactionType::CRNOTE_TRNX_TYPE);
                      // Get current accounting period
                      $accounting_period = AccountingPeriod::findOne([
                          'status'=>1,
                          'record_status'=>'active']);

                      // Return Transaction
                      $return_params_debit = [
                          'journal_id' => $journal_id,
                          'unit_amount' => ($model->final_sales_amount * -1),
                          'account_id' => $return_accounts->debit_account_id,
                          'accounting_period_id' => $accounting_period->accounting_period_id,
                          'create_user_id' => $user_id,
                          ];
                      $return_params_credit = [
                          'journal_id' => $journal_id,
                          'unit_amount' => $model->final_sales_amount,
                          'account_id' => $return_accounts->credit_account_id,
                          'accounting_period_id' => $accounting_period->accounting_period_id,
                          'create_user_id' => $user_id,
                          ];

                      // Pass a Credit-Note Transaction to reverse paid amount
                      $crnote_params_debit = [
                          'journal_id' => $journal_id,
                          'unit_amount' => ($model->paid_amount * -1),
                          'account_id' => $crnote_accounts->debit_account_id,
                          'accounting_period_id' => $accounting_period->accounting_period_id,
                          'create_user_id' => $user_id,
                          ];
                      $crnote_params_credit = [
                          'journal_id' => $journal_id,
                          'unit_amount' => $model->paid_amount,
                          'account_id' => $crnote_accounts->credit_account_id,
                          'accounting_period_id' => $accounting_period->accounting_period_id,
                          'create_user_id' => $user_id,
                          ];
                      // Populate journal
                      $rtn_success = ($tranx->populateJournal($return_params_debit) && $tranx->populateJournal($return_params_credit));
                      $crnote_success = ($tranx->populateJournal($crnote_params_debit) && $tranx->populateJournal($crnote_params_credit));

                      if($rtn_success && $crnote_success){
                              // Return vehicle to stock
                              $params_vehicle = [
                                  'vehicle_id' => $model->vehicle_id,
                                  'stock_status_id' => Vehicle::INSTOCK_STATUS,
                                  'update_user_id' => $user_id,
                                  'update_date' => date('Y-m-d'),
                                  'record_status' => Vehicle::ACTIVE
                                  ];
                              $tranx->vehicleStockStatus($params_vehicle);

                              // Create a Refund transactions
                              // $params_refund = [
                              //   'sales_id' => $model->sales_id,
                              //   'paid_amount' => $model->paid_amount,
                              //   'user_id' => $user_id
                              // ];
                              // $tranx->createRefund($params_refund);

                              // Deactivate Sales
                              $model->paid_amount = 0.00;
                              $model->notes = date('Y-m-d') . ' : Return Transaction <br/> ' . $model->notes;
                              $model->record_status = self::INACTIVE;
                              $model->save();

                      } else {
                        throw new ErrorException('Operation failed to complete.');
                      }
                  }
              }
              $transaction->commit();
              return true;
            } catch(\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
        return false;
    }

    // public function afterSave($insert)
    // {
    //     if ($this->isNewRecord) { // === false even we insert a new record
    //         // code here ...
    //     }
    //     parent::afterSave($insert);
    // }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesStatus()
    {
        return $this->hasOne(SalesStatus::className(), ['sales_status_id' => 'sales_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehicle()
    {
        return $this->hasOne(Vehicle::className(), ['vehicle_id' => 'vehicle_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesPerson()
    {
        return $this->hasOne(SalesPerson::className(), ['sales_person_id' => 'sales_person_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesTransactions()
    {
        return $this->hasMany(SalesTransaction::className(), ['sales_id' => 'sales_id']);
    }

    // Get accounts list
    public function getSalesPersonList() { // could be a static func as well
        $models = SalesPerson::find()->where(['record_status'=>'active'])->asArray()->all();
        return ArrayHelper::map($models, 'sales_person_id', 'sales_person');
    }

    // Get reference_number
    public function getReferenceNumber($id) { // could be a static func as well
        $models = Vehicle::find()->where(['vehicle_id'=>$id])->asArray()->all();
        return ArrayHelper::map($models, 'vehicle_id', 'reference_number');
    }

    // Get Vehicle List
    public function getVehicleList() { // could be a static func as well
        $models = Vehicle::find()
            ->where(['record_status'=>'active', 'stock_status_id'=>self::INSTOCK])
            ->asArray()->all();
        return ArrayHelper::map($models, 'vehicle_id', 'reference_number');
    }
    // Get accounts list
    public function getCustomerList() { // could be a static func as well
        $models = Customer::find()->where(['record_status'=>'active'])
          ->orderBy('customer_id desc')
          ->asArray()
          ->all();
        return ArrayHelper::map($models, 'customer_id', 'customer_name');
    }

    // Get accounts list
    public function getSalesTransactionTypeList() { // could be a static func as well
        $models = SalesTransactionType::find()->where(['record_status'=>'active'])->asArray()->all();
        return ArrayHelper::map($models, 'sales_transaction_type_id', 'sales_transaction_type');
    }
}
