<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "transaction_type".
 *
 * @property integer $transaction_id
 * @property string $transaction_type
 * @property string $record_status
 */
class TransactionType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaction_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transaction_type'], 'required'],
            [['record_status'], 'string'],
            [['transaction_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transaction_id' => 'Transaction ID',
            'transaction_type' => 'Transaction Type',
            'record_status' => 'Record Status',
        ];
    }

    public static function getTransactionTypeList() { 
        $models = TransactionType::find()->asArray()->all();
        return ArrayHelper::map($models, 'transaction_id', 'transaction_type');
    }
}
