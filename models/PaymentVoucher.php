<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment_voucher".
 *
 * @property integer $payment_voucher_id
 * @property integer $bank_id
 * @property string $pv_number
 * @property integer $creditor_id
 * @property string $pv_date
 * @property integer $payment_method_id
 * @property string $amount
 * @property string $discount
 * @property double $tax_rate
 * @property string $final_amount
 * @property string $cheque_no
 * @property string $notes
 * @property string $posting_status
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property PaymentMethod $paymentMethod
 * @property Creditor $creditor
 */
class PaymentVoucher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_voucher';
    }

    public function init()
    {
        parent::init();
        // $this->create_date = date('Y-m-d');
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bank_id', 'pv_number', 'creditor_id', 'pv_date', 'payment_method_id', 'create_user_id', 'create_date'], 'required'],
            [['bank_id', 'creditor_id', 'payment_method_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['pv_date', 'create_date', 'update_date'], 'safe'],
            [['amount', 'discount', 'tax_rate', 'final_amount'], 'number'],
            [['notes', 'record_status', 'posting_status'], 'string'],
            [['pv_number'], 'string', 'max' => 20],
            [['cheque_no'], 'string', 'max' => 50],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['bank_id' => 'bank_id']],
            [['payment_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentMethod::className(), 'targetAttribute' => ['payment_method_id' => 'payment_method_id']],
            [['creditor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Creditor::className(), 'targetAttribute' => ['creditor_id' => 'creditor_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'payment_voucher_id' => 'Payment Voucher',
            'pv_number' => 'PV Number',
            'creditor_id' => 'Creditor',
            'pv_date' => 'Date',
            'bank_id' => 'Bank',
            'payment_method_id' => 'Payment Method',
            'amount' => 'Amount',
            'discount' => 'Discount',
            'tax_rate' => 'Tax Rate',
            'final_amount' => 'Final Amount',
            'cheque_no' => 'Cheque No',
            'notes' => 'Notes',
            'posting_status' => 'Posting Status',
            'record_status' => 'Record Status',
            'create_user_id' => 'Create User ID',
            'create_date' => 'Create Date',
            'update_user_id' => 'Update User ID',
            'update_date' => 'Update Date',
        ];
    }

    /**
    * Populate Journal
    * $sales_transaction_type_id debit_account_id/credit_account_id
    * @param [$journal_id. $trnx_type_id, $amount]
    * @return boolean success
    */
    public static function cashbookEntry($params)
    {
        try{
            // if($params){
                $cashbook = new Cashbook();
                $cashbook->loadDefaultValues();
                $cashbook->bank_id = $params['bank_id'];           
                $cashbook->accounting_period_id = $params['accounting_period_id'];           
                $cashbook->transaction_date = $params['transaction_date'];           
                $cashbook->transaction_type_id = $params['transaction_type_id'];           
                $cashbook->account_id = $params['account_id'];           
                $cashbook->payer_payee_id = $params['payer_payee_id'];           
                $cashbook->reference_number = $params['reference_number'];           
                $cashbook->exclusive_amount = $params['exclusive_amount'];           
                $cashbook->tax_id = $params['tax_id'];           
                $cashbook->tax_amount = $params['tax_amount'];           
                $cashbook->total_amount = $params['total_amount'];           
                $cashbook->create_user_id = $params['create_user_id'];
                $cashbook->create_date = date('Y-m-d');
                $cashbook->validate();
                // $cashbook->save();
                print_r($cashbook->errors . '<br>' . $cashbook->validate());

                // $cashbook->getErrors();

                // print_r($cashbook->save()); die();

                return;
            // }else {
            //     throw new NotFoundHttpException('The requested page does not exist.');
            // }
        }
        catch(\Exception $ex){
            throw $ex;
        }
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
    public function getCreditor()
    {
        return $this->hasOne(Creditor::className(), ['creditor_id' => 'creditor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBank()
    {
        return $this->hasOne(Bank::className(), ['bank_id' => 'bank_id']);
    }
}
