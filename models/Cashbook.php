<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cashbook".
 *
 * @property integer $cashbook_entry_id
 * @property integer $bank_id
 * @property integer $accounting_period_id
 * @property string $transaction_date
 * @property integer $transaction_type_id
 * @property integer $account_id
 * @property integer $payer_payee_id 
 * @property string $reference_number
 * @property string $notes
 * @property string $exclusive_amount
 * @property integer $tax_id
 * @property string $tax_amount
 * @property string $total_amount
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property Tax $tax
 * @property Bank $bank
 * @property AccountingPeriod $accountingPeriod
 * @property TransactionType $transactionType
 * @property Account $account
 */
class Cashbook extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cashbook';
    }

    public function init()
    {
        parent::init();
        $this->create_date = date('Y-m-d');
        // $this->accounting_period_id = AccountingPeriod::getCurrentAccountingPeriod();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bank_id', 'accounting_period_id', 'transaction_date', 'transaction_type_id', 'account_id', 'reference_number', 'exclusive_amount', 'total_amount', 'create_user_id', 'create_date'], 'required'],
            [['bank_id', 'accounting_period_id', 'transaction_type_id', 'account_id', 'tax_id', 'payer_payee_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['transaction_date', 'create_date', 'update_date'], 'safe'],
            [['notes'], 'string'],
            [['exclusive_amount', 'tax_id', 'tax_amount', 'total_amount'], 'number'],
            [['reference_number'], 'string', 'max' => 50],
            // [['tax_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tax::className(), 'targetAttribute' => ['tax_id' => 'tax_id']],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['bank_id' => 'bank_id']],
            [['accounting_period_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccountingPeriod::className(), 'targetAttribute' => ['accounting_period_id' => 'accounting_period_id']],
            [['transaction_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => TransactionType::className(), 'targetAttribute' => ['transaction_type_id' => 'transaction_id']],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['account_id' => 'account_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cashbook_entry_id' => 'Cashbook Entry ID',
            'bank_id' => 'Bank',
            'accounting_period_id' => 'Accounting Period',
            'transaction_date' => 'Transaction Date',
            'transaction_type_id' => 'Transaction Type',
            'account_id' => 'Account',
            'payer_payee_id' => 'Payer/Payee',
            'reference_number' => 'Reference Number',
            'notes' => 'Notes',
            'exclusive_amount' => 'Exclusive Amount',
            'tax_id' => 'Tax',
            'tax_amount' => 'Tax Amount',
            'total_amount' => 'Total Amount',
        ];
    }

    /**
    * Populate Cashbook
    * $sales_transaction_type_id debit_account_id/credit_account_id
    * @param [$params[]]
    * @return boolean success
    */
    public static function cashbookEntry($params)
    {
        try{
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
                $cashbook->save();
                return;
        }
        catch(\Exception $ex){
            throw $ex;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTax()
    {
        return $this->hasOne(Tax::className(), ['tax_id' => 'tax_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'payer_payee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreditor()
    {
        return $this->hasOne(Creditor::className(), ['creditor_id' => 'payer_payee_id']);
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
    public function getAccountingPeriod()
    {
        return $this->hasOne(AccountingPeriod::className(), ['accounting_period_id' => 'accounting_period_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionType()
    {
        return $this->hasOne(TransactionType::className(), ['transaction_id' => 'transaction_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['account_id' => 'account_id']);
    }

    // Get accounts list
    public function getAccountList() { // could be a static func as well
        $models = Account::find()->asArray()->all();
        return ArrayHelper::map($models, 'account_id', 'account_name');
    }

    // Get accounts list
    public function getBankList() { // could be a static func as well
        $models = Bank::find()->asArray()->all();
        return ArrayHelper::map($models, 'bank_id', 'bank');
    }

    // Get accounts list
    public function getAccountingPeriodList() { // could be a static func as well
        $models = AccountingPeriod::find()->asArray()->all();
        return ArrayHelper::map($models, 'accounting_period_id', 'accounting_period');
    }

    // Get accounts list
    public static function getTransactionTypeList() { 
        $models = TransactionType::find()->asArray()->all();
        return ArrayHelper::map($models, 'transaction_id', 'transaction_type');
    }

    // Get accounts list
    public function getTaxList() { // could be a static func as well
        $models = Tax::find()->asArray()->all();
        return ArrayHelper::map($models, 'tax_id', 'tax_rate');
    }

    // Get creditor list
    public static function getCreditorList() {
        $creditor = Creditor::find()
            ->select(['creditor_id AS id','creditor as name'])
            ->with('expenses')
            ->asArray()
            ->all();
        return $creditor;
    }

    // Get accounts list
    public static function getCustomerList() {
        $data = Customer::find()
            ->select(['customer_id AS id','customer_name as name'])
            ->asArray()
            ->all();
        return $data;
    }

    // Get bank list
    public static function getTransferBank() {
        $data = Bank::find()
            ->select(['bank_id AS id','bank as name'])
            ->asArray()
            ->all();
        return $data;
    }
}
