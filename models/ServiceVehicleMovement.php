<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "service_vehicle_movement".
 *
 * @property integer $movement_id
 * @property integer $vehicle_id
 * @property integer $movement_type_id
 * @property string $movement_date
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property ServiceMovementType $movementType
 * @property Vehicle $vehicle
 */
class ServiceVehicleMovement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_vehicle_movement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_id', 'movement_type_id', 'movement_date', 'create_user_id', 'create_date'], 'required'],
            [['vehicle_id', 'movement_type_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['movement_date', 'create_date', 'update_date'], 'safe'],
            [['notes', 'record_status'], 'string'],
            [['movement_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ServiceMovementType::className(), 'targetAttribute' => ['movement_type_id' => 'movement_type_id']],
            [['vehicle_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vehicle::className(), 'targetAttribute' => ['vehicle_id' => 'vehicle_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'movement_id' => 'Movement ID',
            'vehicle_id' => 'Vehicle',
            'movement_type_id' => 'Movement Type',
            'movement_date' => 'Movement Date',
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
    public function getMovementType()
    {
        return $this->hasOne(ServiceMovementType::className(), ['movement_type_id' => 'movement_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehicle()
    {
        return $this->hasOne(Vehicle::className(), ['vehicle_id' => 'vehicle_id']);
    }

    // Get vehicle list
    public static function getVehicleList() { // could be a static func as well
        $models = Vehicle::find()
            ->where(['stock_status_id'=>Delivery::RESERVED_STOCK_STATUS_ID])
            ->asArray()
            ->all(); // reserved vehicle
        return ArrayHelper::map($models, 'vehicle_id', 'reference_number');
    }

    // Get vehicle list
    public static function getMovementTypeList() { // could be a static func as well
        $models = ServiceMovementType::find()
            ->asArray()
            ->all(); // reserved vehicle
        return ArrayHelper::map($models, 'movement_type_id', 'movement_type');
    }
}
