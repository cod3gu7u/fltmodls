<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "currency".
 *
 * @property integer $currency_id
 * @property string $currency_short
 * @property string $currency_long
 * @property string $notes
 * @property string $record_status
 *
 * @property SalesTransaction[] $salesTransactions
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currency_short', 'currency_long'], 'required'],
            [['notes', 'record_status'], 'string'],
            [['currency_short'], 'string', 'max' => 4],
            [['currency_long'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'currency_id' => 'Currency ID',
            'currency_short' => 'Currency Short',
            'currency_long' => 'Currency Long',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesTransactions()
    {
        return $this->hasMany(SalesTransaction::className(), ['currency_id' => 'currency_id']);
    }

    // Get currency list
    public function getCurrencyList() { // could be a static func as well
        $models = Currency::find()->asArray()->all();
        return ArrayHelper::map($models, 'currency_id', 'currency_short');
    }
}
