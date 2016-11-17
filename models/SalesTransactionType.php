<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sales_transaction_type".
 *
 * @property integer $sales_transaction_type_id
 * @property string $sales_transaction_type
 * @property integer $debit_account_id
 * @property integer $credit_account_id
 * @property string $notes
 * @property string $record_status
 *
 * @property SalesTransaction[] $salesTransactions
 * @property Account $creditAccount
 * @property Account $debitAccount
 */
class SalesTransactionType extends \yii\db\ActiveRecord
{
    const RECEIPT_TRNX_TYPE = 1; // Receipt
    const INVOICE_TRNX_TYPE = 2; // Invoice
    const RETURN_TRNX_TYPE = 3; // Return
    const CRNOTE_TRNX_TYPE = 4; // Credit Note
    const DRNOTE_TRNX_TYPE = 5; // Debit Note
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sales_transaction_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sales_transaction_type', 'debit_account_id', 'credit_account_id'], 'required'],
            [['debit_account_id', 'credit_account_id'], 'integer'],
            [['notes', 'record_status'], 'string'],
            [['sales_transaction_type'], 'string', 'max' => 20],
            [['credit_account_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Account::className(), 'targetAttribute' => ['credit_account_id' => 'account_id']],
            [['debit_account_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Account::className(), 'targetAttribute' => ['debit_account_id' => 'account_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sales_transaction_type_id' => 'Sales Transaction Type ID',
            'sales_transaction_type' => 'Sales Transaction Type',
            'debit_account_id' => 'Debit Account ID',
            'credit_account_id' => 'Credit Account ID',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesTransactions()
    {
        return $this->hasMany(SalesTransaction::className(), ['sales_transaction_type_id' => 'sales_transaction_type_id']);
    }

   /**
    * @return \yii\db\ActiveQuery
    */
   public function getCreditAccount()
   {
       return $this->hasOne(Account::className(), ['account_id' => 'credit_account_id']);
   }
   /**
    * @return \yii\db\ActiveQuery
    */
   public function getDebitAccount()
   {
       return $this->hasOne(Account::className(), ['account_id' => 'debit_account_id']);
   }

    // Get accounts list
    public function getAccountList() { // could be a static func as well
        $models = Account::find()->asArray()->all();
        return ArrayHelper::map($models, 'account_id', 'account_name');
    }
}
