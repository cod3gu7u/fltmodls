<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "vehicle".
 *
 * @property integer $vehicle_id
 * @property string $reference_number
 * @property integer $supplier_id
 * @property integer $location_id
 * @property integer $make_id
 * @property integer $vehicle_type_id
 * @property integer $model_id
 * @property string $model_year
 * @property string $chassis
 * @property string $engine
 * @property integer $color_id
 * @property integer $transmission_id
 * @property integer $fuel_type_id
 * @property integer $capacity
 * @property string $arrival_date
 * @property integer $arrival_mileage
 * @property integer $stock_status_id
 * @property string $asking_price
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property Delivery[] $deliveries
 * @property Photograph[] $photographs
 * @property Sales[] $sales
 * @property ServiceItem[] $serviceItems
 * @property ServiceVehicleMovement[] $serviceVehicleMovements
 * @property FuelType $fuelType
 * @property Location $location
 * @property VehicleType $vehicleType
 * @property Color $color
 * @property StockStatus $stockStatus
 * @property VehicleMake $make
 * @property VehicleModel $model
 * @property Creditor $supplier
 * @property VehicleTransmission $transmission
 * @property VehicleCosting[] $vehicleCostings
 * @property VehicleExchange[] $vehicleExchanges
 * @property VehicleExchange[] $vehicleExchanges0
 * @property VehiclePhoto[] $vehiclePhotos
 * @property VehicleRegistration[] $vehicleRegistrations
 */
class Vehicle extends \yii\db\ActiveRecord
{

    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const INSTOCK_STATUS = 2;
    const RESERVED_STATUS = 5;
    const SOLD_STATUS = 8;
    const CreditorSupplier = 2; // Creditor Type for Supplier

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vehicle';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reference_number', 'location_id', 'chassis', 'arrival_date', 'record_status', 'create_user_id', 'create_date'], 'required'],
            [['supplier_id', 'location_id', 'make_id', 'vehicle_type_id', 'model_id', 'color_id', 'capacity', 'arrival_mileage', 'stock_status_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['stock_status_id', 'arrival_date', 'create_date', 'update_date'], 'safe'],
            [['asking_price'], 'number'],
            [['notes', 'record_status'], 'string'],
            [['reference_number'], 'string', 'max' => 20],
            [['model_year'], 'string', 'max' => 4],
            [['chassis', 'engine'], 'string', 'max' => 50],
            [['reference_number'], 'unique'],
            [['fuel_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => FuelType::className(), 'targetAttribute' => ['fuel_type_id' => 'id']],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => VehicleModel::className(), 'targetAttribute' => ['model_id' => 'model_id']],
            [['supplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Creditor::className(), 'targetAttribute' => ['supplier_id' => 'creditor_id']],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::className(), 'targetAttribute' => ['location_id' => 'location_id']],
            [['vehicle_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => VehicleType::className(), 'targetAttribute' => ['vehicle_type_id' => 'vehicle_type_id']],
            [['color_id'], 'exist', 'skipOnError' => true, 'targetClass' => Color::className(), 'targetAttribute' => ['color_id' => 'color_id']],
            [['stock_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => StockStatus::className(), 'targetAttribute' => ['stock_status_id' => 'stock_status_id']],
            [['make_id'], 'exist', 'skipOnError' => true, 'targetClass' => VehicleMake::className(), 'targetAttribute' => ['make_id' => 'vehicle_make_id']],
             [['transmission_id'], 'exist', 'skipOnError' => true, 'targetClass' => VehicleTransmission::className(), 'targetAttribute' => ['transmission_id' => 'id']],
            // ['arrival_date', 'default', 'value' => date('Y-m-d')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vehicle_id' => 'Vehicle',
            'reference_number' => 'Reference Number',
            'location_id' => 'Location',
            'make_id' => 'Make',
            'vehicle_type_id' => 'Vehicle Type',
            'model_id' => 'Model',
            'supplier_id' => 'Supplier',
            'model_year' => 'Model Year',
            'chassis' => 'Chassis',
            'engine' => 'Engine',
            'color_id' => 'Color',
            'transmission_id' => 'Transmission',
            'fuel_type_id' => 'Fuel Type',
            'capacity' => 'Capacity',
            'arrival_date' => 'Arrival Date',
            'arrival_mileage' => 'Arrival Mileage',
            'stock_status_id' => 'Stock Status',
            'asking_price' => 'Asking Price',
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
    public function getPhotographs()
    {
        return $this->hasMany(Photograph::className(), ['vehicle_id' => 'vehicle_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModel()
    {
        return $this->hasOne(VehicleModel::className(), ['model_id' => 'model_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['location_id' => 'location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehicleType()
    {
        return $this->hasOne(VehicleType::className(), ['vehicle_type_id' => 'vehicle_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehicleMake()
    {
        return $this->hasOne(VehicleMake::className(), ['vehicle_make_id' => 'make_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColor()
    {
        return $this->hasOne(Color::className(), ['color_id' => 'color_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFuelType()
    {
        return $this->hasOne(FuelType::className(), ['id' => 'fuel_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransmission()
    {
        return $this->hasOne(VehicleTransmission::className(), ['id' => 'transmission_id']);
    }

    /** 
    * @return \yii\db\ActiveQuery 
    */ 
    public function getSupplier() 
    { 
       return $this->hasOne(Creditor::className(), ['creditor_id' => 'supplier_id']); 
    } 

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockStatus()
    {
        return $this->hasOne(StockStatus::className(), ['stock_status_id' => 'stock_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMake()
    {
        return $this->hasOne(VehicleMake::className(), ['vehicle_make_id' => 'make_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehicleRegistrations()
    {
        return $this->hasMany(VehicleRegistration::className(), ['vehicle_id' => 'vehicle_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getDeliveries()
    {
       return $this->hasMany(Delivery::className(), ['vehicle_id' => 'vehicle_id']);
    }
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVehicleCostings()
    {
       return $this->hasMany(VehicleCosting::className(), ['vehicle_id' => 'vehicle_id']);
    }
    /**
    * @return \yii\db\ActiveQuery
    */
    public function getVehiclePhotos()
    {
       return $this->hasMany(VehiclePhoto::className(), ['vehicle_id' => 'vehicle_id']);
    }

    // Get location list
    public function getLocationList() { // could be a static func as well
        $models = Location::find()->asArray()->all();
        return ArrayHelper::map($models, 'location_id', 'location');
    }

    // Get location list
    public function getSupplierList() { // could be a static func as well
        $models = Creditor::find()
            ->where(['creditor_type_id'=>self::CreditorSupplier, 'record_status'=>'active'])
            ->asArray()
            ->all();
        return ArrayHelper::map($models, 'creditor_id', 'creditor');
    }

    // Get vehicle make list
    public function getVehicleMakeList() { // could be a static func as well
        $models = VehicleMake::find()->where(['record_status'=>'active'])->asArray()->all();
        return ArrayHelper::map($models, 'vehicle_make_id', 'make');
    }

    // Get vehicle list
    public function getVehicleList() { // could be a static func as well
        $models = Vehicle::find()->asArray()->all();
        return ArrayHelper::map($models, 'vehicle_id', 'reference_number');
    }

    // Get vehicle list
    public function getActiveVehicleList() { // could be a static func as well
        $models = Vehicle::find()->where(['record_status'=>'active'])->asArray()->all();
        return ArrayHelper::map($models, 'vehicle_id', 'reference_number');
    }

    // Get vehicle type list
    public function getVehicleTypeList() { // could be a static func as well
        $models = VehicleType::find()->asArray()->all();
        return ArrayHelper::map($models, 'vehicle_type_id', 'vehicle_type');
    }

    // Get vehicle model list
    public static function getVehicleModelList($make_id) {
        $data = VehicleModel::find()
            ->where(['vehicle_make_id'=>$make_id, 'record_status'=>'active'])
            ->select(['model_id AS id','model as name'])
            ->groupBy(['model'])
            ->asArray()
            ->all();

        return $data;
    }

    // Get color list
    public function getColorList() { // could be a static func as well
        $models = Color::find()->asArray()->all();
        return ArrayHelper::map($models, 'color_id', 'color');
    }

    // Get stock status list
    public function getStockStatusList() { // could be a static func as well
        $models = StockStatus::find()->asArray()->all();
        return ArrayHelper::map($models, 'stock_status_id', 'stock_status');
    }

    // Get reference list
    public function getReferenceList() { // could be a static func as well
        $models = ReferenceNumber::find()->select(['reference_prefix', 'concat(reference_prefix, reference_counter) as reference'])->asArray()->all();
        return ArrayHelper::map($models, 'reference_prefix', 'reference');
    }

    // Get vehicle photo
    public static function getTopPhoto($data) {
        $data = \app\models\VehiclePhoto::find()
          ->where(['vehicle_id' => $data->vehicle_id])
          ->one();
        return ($data !== null) ? "http://fleet21.dev//frontend/web/uploads/vehicles/" . $data->photograph : null;
    }

    // Get fuel type list
    public function getFuelTypeList() { // could be a static func as well
        $models = FuelType::find()->asArray()->all();
        return ArrayHelper::map($models, 'id', 'fule_type');
    }

    // Get color list
    public static function getTransmissionList() { 
        $models = VehicleTransmission::find()->asArray()->all();
        return ArrayHelper::map($models, 'id', 'transmission');
    }
}
