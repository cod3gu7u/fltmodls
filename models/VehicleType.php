<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vehicle_type".
 *
 * @property integer $vehicle_type_id
 * @property string $vehicle_type
 * @property string $notes
 * @property string $record_status
 *
 * @property Vehicle[] $vehicles
 */
class VehicleType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vehicle_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_type', 'notes', 'record_status'], 'required'],
            [['notes', 'record_status'], 'string'],
            [['vehicle_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vehicle_type_id' => 'Vehicle Type ID',
            'vehicle_type' => 'Vehicle Type',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehicles()
    {
        return $this->hasMany(Vehicle::className(), ['vehicle_type_id' => 'vehicle_type_id']);
    }
}
