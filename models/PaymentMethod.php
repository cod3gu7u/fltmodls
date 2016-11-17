<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "payment_method".
 *
 * @property integer $payment_method_id
 * @property string $payment_method
 * @property string $notes
 * @property string $record_status
 */
class PaymentMethod extends \yii\db\ActiveRecord
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_method';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_method', 'record_status'], 'required'],
            [['notes', 'record_status'], 'string'],
            [['payment_method'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'payment_method_id' => 'Payment Method ID',
            'payment_method' => 'Payment Method',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    // Get accounts list
    public function getPaymentMethodList() { // could be a static func as well
        $models = PaymentMethod::find()->asArray()->all();
        return ArrayHelper::map($models, 'payment_method_id', 'payment_method');
    }
}
