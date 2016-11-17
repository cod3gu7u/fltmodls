<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sales_transaction".
 *
 * @property integer $sales_transaction_id
 * @property integer $sales_id
 * @property string $transaction_date
 * @property integer $sales_transaction_type_id
 * @property integer $currency_id
 * @property string $transaction_amount
 * @property double $exchange_rate
 * @property integer $payment_method_id
 * @property string $total_amount
 * @property boolean $void
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 * @property Bank $bank 
 * @property Sales $sales
 * @property SalesTransactionType $salesTransactionType
 * @property Currency $currency
 * @property PaymentMethod $paymentMethod 
 */
class SalesTransaction extends \yii\db\ActiveRecord
{
    const SALES_RECEIPT_TRANSACTION_TYPE_ID = 1;
    const INVOICE_TRANSACTION_TYPE = 2;
    const RETURN_TRANSACTION_TYPE = 3;
    const CREDITNOTE_TRANSACTION_TYPE = 4;
    const DEBITNOTE_TRANSACTION_TYPE = 5;
    const CREDIT_TRANSACTION_TYPE = 4;
    const DEFAULT_CURRENCY = 1;
    const DEFAULT_EXCHANGE_RATE = 1;
    const DEFAULT_PAYMENT_METHOD = 3; // Cheque
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sales_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sales_id', 'transaction_date', 'sales_transaction_type_id', 'create_user_id', 'create_date'], 'required'],
            [['sales_id', 'sales_transaction_type_id', 'bank_id', 'currency_id', 'payment_method_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['transaction_date', 'create_date', 'update_date'], 'safe'],
            [['transaction_amount', 'exchange_rate', 'total_amount'], 'number'],
            [['void'], 'boolean'],
            [['notes', 'record_status'], 'string'],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['bank_id' => 'bank_id']],
            [['sales_transaction_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SalesTransactionType::className(), 'targetAttribute' => ['sales_transaction_type_id' => 'sales_transaction_type_id']],
            [['sales_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sales::className(), 'targetAttribute' => ['sales_id' => 'sales_id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'currency_id']],
            [['payment_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentMethod::className(), 'targetAttribute' => ['payment_method_id' => 'payment_method_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sales_transaction_id' => 'Sales Transaction ID',
            'sales_id' => 'Sales ID',
            'transaction_date' => 'Transaction Date',
            'sales_transaction_type_id' => 'Transaction Type',
            'bank_id' => 'Bank',
            'currency_id' => 'Currency',
            'transaction_amount' => 'Transaction Amount',
            'exchange_rate' => 'Exchange Rate',
            'payment_method_id' => 'Payment Method',
            'total_amount' => 'Total Amount',
            'payment_method_id' => 'Payment Method',
            'void' => 'Void Status',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
            'create_user_id' => 'Create User ID',
            'create_date' => 'Create Date',
            'update_user_id' => 'Update User ID',
            'update_date' => 'Update Date',
        ];
    }

    // public function beforeSave($insert)
    // {
    //     print_r('here: '); die();
    //     $this->calculatePaidAmount();
    // }

    /**
    * After save, calculate total paid amount
    * applies on all create, update, void
    */
    // public function afterSave($insert)
    public function afterSave()
    {
        // Calculate total payments
        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            // Update sales
            $sales = Sales::findOne($this->sales_id);
            if($sales !== null && $this->sales_id !== 1){

                // Get total receipt amount
                $sales_trnx_model = SalesTransaction::find()
                    ->where([
                        'sales_id'=>$this->sales_id,
                        'sales_transaction_type_id'=>SalesTransactionType::RECEIPT_TRNX_TYPE,
                        'void'=>false,
                        'record_status'=> self::ACTIVE
                        ]);
                $total_receipt_amount = ($sales_trnx_model !== null ) ? $sales_trnx_model->sum('total_amount') : 0.00;

                // Get total return amount
                $sales_trnx_model = SalesTransaction::find()
                    ->where([
                        'sales_id'=>$this->sales_id,
                        'sales_transaction_type_id'=>self::RETURN_TRANSACTION_TYPE,
                        'void'=>false,
                        'record_status'=> self::ACTIVE
                        ]);
                $total_return_amount = ($sales_trnx_model !== null ) ? $sales_trnx_model->sum('total_amount') : 0.00;

                // Calculate paid amount
                $paid_amount = $total_receipt_amount - $total_return_amount;
                
                // Do an update
                $sales->paid_amount = $paid_amount;
                $sales->balance = ($sales->final_sales_amount - $paid_amount);
                $sales->update_user_id = \Yii::$app->user->identity->id;
                $sales->update_date = date('Y-m-d');

                if(!$sales->save()){
                    // throw new \Exception( 'Sales Paid Amount Update Error' );
                    print_r($this->sales_id . ' = ' . $sales->errors); die();
                }
                $transaction->commit();
            }
        } catch(\Exception $ex){
            $transaction->rollBack();
            throw $ex;
        }
    }

    /**
    * Create a new batch each day.
    * @return int batch_id
    */
    public function createBatch($batch_prefix = '')
    {
        // Today's batch
        $batch_name = $batch_prefix . date('Ymd') . '-' . \Yii::$app->user->identity->id;
        $batch = Batch::findOne(['batch_name' => $batch_name]);
        if($batch === null){
            $batch = new Batch();
            $batch->loadDefaultValues();
            $batch->batch_name = $batch_name;
            $batch->batch_date = date('Y-m-d');
            $batch->save();
        }
        return $batch->batch_id;
    }

    /**
    * Create a new journal
    * @param [$batch_id, $journal_type_id]
    * @return Journal
    * @throws NotFoundHttpException
    */
    public function createJournal($params)
    {
        if($params !== null){
            $journal = new Journal();
            $journal->loadDefaultValues();
            $journal->batch_id = $params['batch_id'];
            $journal->journal_type_id = $params['journal_type_id'];
            $journal->save();
            return $journal->journal_id;
        }else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
    *
    */
    public function getDebitCreditAccounts($sales_trnx_type)
    {
        //get current debit and credit account
        $sales_transaction_type = SalesTransactionType::findOne([
            'sales_transaction_type_id'=>$sales_trnx_type]);
        if($sales_transaction_type !== null){
            return $sales_transaction_type;
        }else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
    * Populate Journal
    * $sales_transaction_type_id debit_account_id/credit_account_id
    * @param [$journal_id. $trnx_type_id, $amount]
    * @return boolean success
    */
    public function populateJournal($params)
    {
        if($params){
            $posting = new Posting();
            $posting->loadDefaultValues();
            $posting->journal_id = $params['journal_id'];
            $posting->account_id = $params['account_id'];
            $posting->quantity = Posting::QUANTITY;
            $posting->unit_amount = $params['unit_amount'];
            $posting->total_amount = ($params['unit_amount'] * Posting::QUANTITY);
            $posting->posting_status = Posting::POSTING_STATUS;
            $posting->asset_type_id = Posting::ASSET_TYPE;
            $posting->accounting_period_id = $params['accounting_period_id'];
            $posting->journal_owner_id = $params['create_user_id'];
            return $posting->save();
        }else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
    * Set vehicle stock status
    * @param [vehicle_id, stock_status_id, record_status]
    * @return boolean success
    */
    public function vehicleStockStatus($params)
    {
        /*** Update Vehicle Status ***/
        if($params){
            $vehicle = Vehicle::findOne($params['vehicle_id']);
            $vehicle->stock_status_id = $params['stock_status_id'];
            $vehicle->update_user_id = $params['update_user_id'];
            $vehicle->update_date = $params['update_date'];
            $vehicle->record_status = $params['record_status'];
            $vehicle->save();
        }else {
            throw new NotFoundHttpException('The requested page does not exist.');
            die();
        }
    }

    /**
    * Create a new journal
    * @param [$sales_id, $paid_amount, user_id]
    * @return Journal
    * @throws NotFoundHttpException
    */
    public function createRefund($params)
    {
        if($params !== null){
            $refund_trnx = new SalesTransaction();
            $refund_trnx->sales_id = $params['sales_id'];
            $refund_trnx->transaction_date  = date('Y-m-d');
            $refund_trnx->sales_transaction_type_id = self::CREDIT_TRANSACTION_TYPE;
            $refund_trnx->currency_id = self::DEFAULT_CURRENCY;
            $refund_trnx->transaction_amount = $params['paid_amount'];
            $refund_trnx->exchange_rate = self::DEFAULT_EXCHANGE_RATE;
            $refund_trnx->payment_method_id = self::DEFAULT_PAYMENT_METHOD;
            $refund_trnx->total_amount  = (self::DEFAULT_EXCHANGE_RATE * $params['paid_amount']);
            $refund_trnx->void = false;
            $refund_trnx->notes = date('Y-m-d') . ' : Payment Refund.';
            $refund_trnx->record_status = self::ACTIVE;
            $refund_trnx->create_user_id = $params['user_id'];
            $refund_trnx->create_date = date('Y-m-d');
            $refund_trnx->save();

            return true;
        }else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getBank()
    {
        return $this->hasOne(Bank::className(), ['bank_id' => 'bank_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::className(), ['payment_method_id' => 'payment_method_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['currency_id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesTransactionType()
    {
        return $this->hasOne(SalesTransactionType::className(), ['sales_transaction_type_id' => 'sales_transaction_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasOne(Sales::className(), ['sales_id' => 'sales_id']);
    }

    // Get accounts list
    public function getSalesTransactionTypeList() { // could be a static func as well
        $models = SalesTransactionType::find()->where(['record_status'=>'active'])->asArray()->all();
        return ArrayHelper::map($models, 'sales_transaction_type_id', 'sales_transaction_type');
    }

    // Get currency list
    public function getCurrencyList() { // could be a static func as well
        $models = Currency::find()->asArray()->all();
        return ArrayHelper::map($models, 'currency_id', 'currency_short');
    }

    // Get currency list
    public function getPaymentMethodList() { // could be a static func as well
        $models = PaymentMethod::find()->asArray()->all();
        return ArrayHelper::map($models, 'payment_method_id', 'payment_method');
    }

    // Get bank list
    public static function getBankList() { // could be a static func as well
        $models = Bank::find()->where(['record_status'=>'active'])->asArray()->all();
        return ArrayHelper::map($models, 'bank_id', 'bank');
    }
}
