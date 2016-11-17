<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "service_movement_type".
 *
 * @property integer $movement_type_id
 * @property integer $movement_type
 * @property string $notes
 * @property string $record_status
 *
 * @property ServiceVehicleMovement[] $serviceVehicleMovements
 */
class ServiceMovementType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_movement_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['movement_type', 'record_status'], 'required'],
            // [['movement_type'], 'integer'],
            [['movement_type', 'notes', 'record_status'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'movement_type_id' => 'Movement Type ID',
            'movement_type' => 'Movement Type',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceVehicleMovements()
    {
        return $this->hasMany(ServiceVehicleMovement::className(), ['movement_type_id' => 'movement_type_id']);
    }
}
