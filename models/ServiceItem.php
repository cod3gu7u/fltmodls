<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "service_item".
 *
 * @property integer $service_item_id
 * @property integer $service_id
 * @property integer $vehicle_id
 * @property integer $service_type_id
 * @property string $cost
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property Service $service
 * @property ServiceType $serviceType
 * @property Vehicle $vehicle
 */
class ServiceItem extends \yii\db\ActiveRecord
{
    const ACTIVE_STATUS = 'active';
    const COST_CATEGORY_ID = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_id', 'vehicle_id', 'service_type_id', 'create_user_id', 'create_date'], 'required'],
            [['service_id', 'vehicle_id', 'service_type_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['cost'], 'number'],
            [['notes', 'record_status'], 'string'],
            [['create_date', 'update_date'], 'safe'],
            [['service_id'], 'exist', 'skipOnError' => true, 'targetClass' => Service::className(), 'targetAttribute' => ['service_id' => 'service_id']],
            [['service_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ServiceType::className(), 'targetAttribute' => ['service_type_id' => 'service_type_id']],
            [['vehicle_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vehicle::className(), 'targetAttribute' => ['vehicle_id' => 'vehicle_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'service_item_id' => 'Service Item ID',
            'service_id' => 'Service ID',
            'vehicle_id' => 'Vehicle ID',
            'service_type_id' => 'Service Type ID',
            'cost' => 'Cost',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
            'create_user_id' => 'Create User ID',
            'create_date' => 'Create Date',
            'update_user_id' => 'Update User ID',
            'update_date' => 'Update Date',
        ];
    }

    public function afterSave($insert)
    {
        // if (parent::afterSave()) {
        parent::afterSave($insert);
        /*** Update master Service record ***/
        try
        {
            // Count service item cost
            $unit_count = ServiceItem::find()
                ->where([
                    'service_id'=>$this->service_id, 
                    'record_status'=>ServiceItem::ACTIVE_STATUS])
                ->count();

            // Get total sales item cost
            $total_cost = ServiceItem::find()
                ->where([
                    'service_id'=>$this->service_id, 
                    'record_status'=>ServiceItem::ACTIVE_STATUS])
                ->sum('cost');

            // Update service
            $service = Service::findOne($this->service_id);
            $service->units = $unit_count;
            $service->total_cost = $total_cost;
            if(!$service->save()){
                throw new \Exception( 'Service Master Record Update Error' );
            }

            // Update vehicle costing
            $vehicle_costing = new VehicleCosting();
            $vehicle_costing->creditor_id = $service->creditor_id;
            $vehicle_costing->vehicle_id = $this->vehicle_id;
            $vehicle_costing->cost_category_id = self::COST_CATEGORY_ID;
            $vehicle_costing->cost_date = $this->create_date;
            $vehicle_costing->total_amount = $this->cost;
            $vehicle_costing->record_status = $this->record_status;
            $vehicle_costing->notes = $this->notes;            
            $vehicle_costing->create_user_id = $this->create_user_id;
            $vehicle_costing->create_date = $this->create_date;
            if(!$vehicle_costing->save()){
                throw new \Exception( 'Error creating the corresponding VehicleCosting record' );
            }

            return true;
        } catch(\Exception $ex){
            throw $ex;
        }
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Service::className(), ['service_id' => 'service_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceType()
    {
        return $this->hasOne(ServiceType::className(), ['service_type_id' => 'service_type_id']);
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
        $models = Vehicle::find(['record_status'=>ServiceItem::ACTIVE_STATUS])->asArray()->all();
        return ArrayHelper::map($models, 'vehicle_id', 'reference_number');
    }

    // Get service type list
    public static function getServiceTypeList() { // could be a static func as well
        $models = ServiceType::find(['record_status'=>ServiceItem::ACTIVE_STATUS])->asArray()->all();
        return ArrayHelper::map($models, 'service_type_id', 'service_type');
    }
}
