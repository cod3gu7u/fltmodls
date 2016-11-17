<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "delivery".
 *
 * @property integer $delivery_id
 * @property integer $sales_id
 * @property integer $vehicle_id
 * @property string $delivery_date
 * @property string $registration_number
 * @property integer $mileage
 * @property integer $delivery_status_id
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property Vehicle $vehicle
 * @property DeliveryStatus $deliveryStatus
 * @property Sales $sales
 */
class Delivery extends \yii\db\ActiveRecord
{
    const RESERVED_STOCK_STATUS_ID = 5;
    const ACTIVE_RECORD_STATUS = 'active';
    const INACTIVE_RECORD_STATUS = 'inactive';
    const VEHICLE_DELIVERED_STATUS_ID = 8;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_id', 'delivery_date', 'create_user_id', 'create_date'], 'required'],
            [['sales_id', 'vehicle_id', 'mileage', 'delivery_status_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['delivery_date', 'create_date', 'update_date'], 'safe'],
            [['notes', 'record_status'], 'string'],
            [['registration_number'], 'string', 'max' => 10],
            [['vehicle_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vehicle::className(), 'targetAttribute' => ['vehicle_id' => 'vehicle_id']],
            [['delivery_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => DeliveryStatus::className(), 'targetAttribute' => ['delivery_status_id' => 'delivery_status_id']],
            [['sales_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sales::className(), 'targetAttribute' => ['sales_id' => 'sales_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'delivery_id' => 'Delivery ID',
            'sales_id' => 'Sales ID',
            'vehicle_id' => 'Vehicle ID',
            'delivery_date' => 'Delivery Date',
            'registration_number' => 'Registration Number',
            'mileage' => 'Mileage',
            'delivery_status_id' => 'Delivery Status',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
            'create_user_id' => 'Create User ID',
            'create_date' => 'Create Date',
            'update_user_id' => 'Update User ID',
            'update_date' => 'Update Date',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            /*********************
            * vehicle_record_status =
            *
            **********************/
            $transaction = Yii::$app->db->beginTransaction();

            try {
                    /*** Update Vehicle Status ***/
                    $vehicle = Vehicle::find()
                        ->where(['vehicle_id' => $this->vehicle_id])
                        ->one();
                    $vehicle->stock_status_id = Delivery::VEHICLE_DELIVERED_STATUS_ID;
                    $vehicle->record_status = Delivery::INACTIVE_RECORD_STATUS;
                    $vehicle->update();

                    $transaction->commit();

                    return true;

            }catch(\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        } else {
            return false;
        }
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehicle()
    {
        return $this->hasOne(Vehicle::className(), ['vehicle_id' => 'vehicle_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryStatus()
    {
        return $this->hasOne(DeliveryStatus::className(), ['delivery_status_id' => 'delivery_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasOne(Sales::className(), ['sales_id' => 'sales_id']);
    }

    // Get location list
    public static function getVehicleList() { // could be a static func as well
        $models = Vehicle::find()
            ->where(['stock_status_id'=>self::RESERVED_STOCK_STATUS_ID])
            ->asArray()
            ->all(); // reserved vehicle
        return ArrayHelper::map($models, 'vehicle_id', 'reference_number');
    }

    // Get sales list
    public static function getSalesList($vehicle_id) { // could be a static func as well
        $models = Sales::find()
            ->where(['record_status'=>self::ACTIVE_RECORD_STATUS, 'vehicle_id'=>$vehicle_id])
            // ->asArray()
            ->all(); // reserved vehicle
            return $models->sales_id;
        // return ArrayHelper::map($models, 'sales_id', 'sales_id');
    }

    // Get delivery status list
    public static function getDeliveryStatusList() { // could be a static func as well
        $models = DeliveryStatus::find()->asArray()->all(); // reserved vehicle
        return ArrayHelper::map($models, 'delivery_status_id', 'delivery_status');
    }
}
