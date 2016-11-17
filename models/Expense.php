<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "expense".
 *
 * @property integer $expense_id
 * @property integer $creditor_id
 * @property integer $cost_category_id
 * @property string $expense_date
 * @property integer $currency_id
 * @property string $transaction_amount
 * @property double $exchange_rate
 * @property string $total_amount
 * @property string $expense_status
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 */
class Expense extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'expense';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['creditor_id', 'cost_category_id', 'expense_date', 'create_user_id', 'create_date'], 'required'],
            [['creditor_id', 'cost_category_id', 'currency_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['expense_date', 'create_date', 'update_date'], 'safe'],
            [['transaction_amount', 'exchange_rate', 'total_amount'], 'number'],
            [['expense_status', 'notes', 'record_status'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'expense_id' => 'Expense ID',
            'creditor_id' => 'Creditor ID',
            'cost_category_id' => 'Cost Category ID',
            'expense_date' => 'Expense Date',
            'currency_id' => 'Currency ID',
            'transaction_amount' => 'Transaction Amount',
            'exchange_rate' => 'Exchange Rate',
            'total_amount' => 'Total Amount',
            'expense_status' => 'Expense Status',
            'notes' => 'Notes',
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
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['currency_id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCostCategory()
    {
        return $this->hasOne(CostCategory::className(), ['cost_category_id' => 'cost_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreditor()
    {
        return $this->hasOne(Creditor::className(), ['creditor_id' => 'creditor_id']);
    }
    
    // Get CostCategory list
    public function getCostCategoryList() { // could be a static func as well
        $models = CostCategory::find()
            ->where(['record_status'=>'active'])
            ->asArray()
            ->all();
        return ArrayHelper::map($models, 'cost_category_id', 'cost_category');
    }

    // Get creditor list
    public function getCreditorList() { // could be a static func as well
        $models = Creditor::find()
            ->where(['record_status'=>'active'])
            ->asArray()
            ->all();
        return ArrayHelper::map($models, 'creditor_id', 'creditor');
    }

    // Get currency list
    public function getCurrencyList() { // could be a static func as well
        $models = Currency::find()->asArray()->all();
        return ArrayHelper::map($models, 'currency_id', 'currency_short');
    }
}
