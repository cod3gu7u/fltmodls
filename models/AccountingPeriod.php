<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "accounting_period".
 *
 * @property integer $accounting_period_id
 * @property string $accounting_period
 * @property string $start_date
 * @property string $end_date
 * @property integer $status
 * @property string $record_status
 *
 * @property Cashbook[] $cashbooks
 * @property Posting[] $postings
 */
class AccountingPeriod extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accounting_period';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accounting_period', 'start_date', 'end_date'], 'required'],
            [['start_date', 'end_date'], 'safe'],
            [['status'], 'integer'],
            [['record_status'], 'string'],
            [['accounting_period'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'accounting_period_id' => 'Accounting Period ID',
            'accounting_period' => 'Accounting Period',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'status' => 'Status',
            'record_status' => 'Record Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCashbooks()
    {
        return $this->hasMany(Cashbook::className(), ['accounting_period_id' => 'accounting_period_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostings()
    {
        return $this->hasMany(Posting::className(), ['accounting_period_id' => 'accounting_period_id']);
    }

    public static function getCurrentAccountingPeriod() { 
        return AccountingPeriod::findOne(['status'=>1, 'record_status'=>'active']);
    }

    public static function getAccountingPeriodList() { 
        $models = AccountingPeriod::find()->asArray()->all();
        return ArrayHelper::map($models, 'accounting_period_id', 'accounting_period');
    }
}
