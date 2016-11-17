<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "delivery_status".
 *
 * @property integer $delivery_status_id
 * @property string $delivery_status
 * @property string $notes
 * @property string $record_status
 *
 * @property Delivery[] $deliveries
 */
class DeliveryStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['delivery_status', 'record_status'], 'required'],
            [['notes', 'record_status'], 'string'],
            [['delivery_status'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'delivery_status_id' => 'Delivery Status ID',
            'delivery_status' => 'Delivery Status',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveries()
    {
        return $this->hasMany(Delivery::className(), ['delivery_status_id' => 'delivery_status_id']);
    }
}
