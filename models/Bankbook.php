<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bankbook".
 *
 * @property integer $bankbook_entry_id
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
 * @property double $tax_rate
 * @property string $tax_amount
 * @property string $total_amount
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property Bank $bank
 * @property AccountingPeriod $accountingPeriod
 * @property TransactionType $transactionType
 * @property Account $account
 * @property Tax $tax
 */
class Bankbook extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bankbook';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bank_id', 'accounting_period_id', 'transaction_date', 'transaction_type_id', 'account_id', 'reference_number', 'exclusive_amount', 'total_amount', 'create_user_id', 'create_date'], 'required'],
            [['bank_id', 'accounting_period_id', 'transaction_type_id', 'account_id', 'payer_payee_id', 'tax_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['transaction_date', 'create_date', 'update_date'], 'safe'],
            [['notes', 'record_status'], 'string'],
            [['exclusive_amount', 'tax_rate', 'tax_amount', 'total_amount'], 'number'],
            [['reference_number'], 'string', 'max' => 50],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['bank_id' => 'bank_id']],
            [['accounting_period_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccountingPeriod::className(), 'targetAttribute' => ['accounting_period_id' => 'accounting_period_id']],
            [['transaction_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => TransactionType::className(), 'targetAttribute' => ['transaction_type_id' => 'transaction_id']],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['account_id' => 'account_id']],
            [['tax_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tax::className(), 'targetAttribute' => ['tax_id' => 'tax_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bankbook_entry_id' => 'Bankbook Entry ID',
            'bank_id' => 'Bank ID',
            'accounting_period_id' => 'Accounting Period ID',
            'transaction_date' => 'Transaction Date',
            'transaction_type_id' => 'Transaction Type ID',
            'account_id' => 'Account ID',
            'payer_payee_id' => 'Payer Payee ID',
            'reference_number' => 'Reference Number',
            'notes' => 'Notes',
            'exclusive_amount' => 'Exclusive Amount',
            'tax_id' => 'Tax ID',
            'tax_rate' => 'Tax Rate',
            'tax_amount' => 'Tax Amount',
            'total_amount' => 'Total Amount',
            'record_status' => 'Record Status',
            'create_user_id' => 'Create User ID',
            'create_date' => 'Create Date',
            'update_user_id' => 'Update User ID',
            'update_date' => 'Update Date',
        ];
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTax()
    {
        return $this->hasOne(Tax::className(), ['tax_id' => 'tax_id']);
    }
}
