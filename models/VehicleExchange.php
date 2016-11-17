<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vehicle_exchange".
 *
 * @property integer $vehicle_exchange_id
 * @property integer $original_sales_id
 * @property integer $original_vehicle_id
 * @property integer $new_vehicle_id
 * @property string $new_sales_amount
 * @property string $exchange_date
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property Vehicle $newVehicle
 * @property Sales $originalSales
 * @property Vehicle $originalVehicle
 */
class VehicleExchange extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vehicle_exchange';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['original_sales_id', 'new_vehicle_id', 'exchange_date', 'create_user_id', 'create_date'], 'required'],
            [['original_sales_id', 'original_vehicle_id', 'new_vehicle_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['new_sales_amount'], 'number'],
            [['exchange_date', 'create_date', 'update_date'], 'safe'],
            [['notes', 'record_status'], 'string'],
            [['new_vehicle_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vehicle::className(), 'targetAttribute' => ['new_vehicle_id' => 'vehicle_id']],
            [['original_sales_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sales::className(), 'targetAttribute' => ['original_sales_id' => 'sales_id']],
            [['original_vehicle_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vehicle::className(), 'targetAttribute' => ['original_vehicle_id' => 'vehicle_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vehicle_exchange_id' => 'Vehicle Exchange ID',
            'original_sales_id' => 'Original Sales ID',
            'original_vehicle_id' => 'Original Vehicle',
            'new_vehicle_id' => 'New Vehicle',
            'new_sales_amount' => 'New Sales Amount',
            'exchange_date' => 'Exchange Date',
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
    public function getNewVehicle()
    {
        return $this->hasOne(Vehicle::className(), ['vehicle_id' => 'new_vehicle_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOriginalSales()
    {
        return $this->hasOne(Sales::className(), ['sales_id' => 'original_sales_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOriginalVehicle()
    {
        return $this->hasOne(Vehicle::className(), ['vehicle_id' => 'original_vehicle_id']);
    }
}
