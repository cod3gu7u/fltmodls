<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\Vehicle;
use app\models\ServiceType;

/**
 * This is the model class for table "service".
 *
 * @property integer $service_id
 * @property integer $creditor_id
 * @property string $service
 * @property string $service_date
 * @property integer $service_status_id
 * @property integer $units
 * @property string $discount
 * @property string $total_cost
 * @property string $notes
 * @property string $record_status
 * @property string $create_date
 * @property integer $create_user_id
 * @property string $update_date
 * @property integer $update_user_id
 *
 * @property ServiceStatus $serviceStatus
 * @property Creditor $creditor
 * @property Service-item[] $service-items
 */
class Service extends \yii\db\ActiveRecord
{
    public $service_status;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['creditor_id', 'service', 'service_date', 'create_date', 'create_user_id'], 'required'],
            [['creditor_id', 'service_status_id', 'units', 'create_user_id', 'update_user_id'], 'integer'],
            [['service_status', 'service_date', 'create_date', 'update_date'], 'safe'],
            [['discount', 'total_cost'], 'number'],
            [['notes', 'record_status'], 'string'],
            [['service'], 'string', 'max' => 250],
            [['service_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => ServiceStatus::className(), 'targetAttribute' => ['service_status_id' => 'service_status_id']],
            [['creditor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Creditor::className(), 'targetAttribute' => ['creditor_id' => 'creditor_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'service_id' => 'Service ID',
            'creditor_id' => 'Creditor',
            'service' => 'Service',
            'service_date' => 'Service Date',
            'service_status_id' => 'Service Status',
            'service_status' => 'Service Status',
            'units' => 'Units',
            'discount' => 'Discount',
            'total_cost' => 'Total Cost',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
            'create_date' => 'Create Date',
            'create_user_id' => 'Create User ID',
            'update_date' => 'Update Date',
            'update_user_id' => 'Update User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceStatus()
    {
        return $this->hasOne(ServiceStatus::className(), ['service_status_id' => 'service_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreditor()
    {
        return $this->hasOne(Creditor::className(), ['creditor_id' => 'creditor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceItems()
    {
        return $this->hasMany(ServiceItem::className(), ['service_id' => 'service_id']);
    }

    // Get creditor list
    public static function getCreditorList() { // could be a static func as well
        $models = Creditor::find()->asArray()->all();
        return ArrayHelper::map($models, 'creditor_id', 'creditor');
    }

    // Get reference list
    public static function getServiceStatusList() { // could be a static func as well
        $models = ServiceStatus::find()->asArray()->all();
        return ArrayHelper::map($models, 'service_status_id', 'service_status');
    }

    // Get vehicle list
    public static function getVehicleList() { // could be a static func as well
        $models = Vehicle::find()->asArray()->all();
        return ArrayHelper::map($models, 'vehicle_id', 'reference_number');
    }

    // Get vehicle list
    public static function getServiceTypeList() { // could be a static func as well
        $models = ServiceType::find()->asArray()->all();
        return ArrayHelper::map($models, 'service_type_id', 'service_type');
    }
}
