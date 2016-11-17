<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "vehicle_model".
 *
 * @property integer $model_id
 * @property integer $vehicle_make_id
 * @property string $model
 * @property string $year
 * @property string $notes
 * @property string $record_status
 *
 * @property Vehicle[] $vehicles
 * @property VehicleMake $vehicleMake
 */
class VehicleModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vehicle_model';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_make_id'], 'integer'],
            [['model', 'record_status'], 'required'],
            [['notes', 'record_status'], 'string'],
            [['model'], 'string', 'max' => 50],
            [['year'], 'string', 'max' => 40],
            [['vehicle_make_id'], 'exist', 'skipOnError' => true, 'targetClass' => VehicleMake::className(), 'targetAttribute' => ['vehicle_make_id' => 'vehicle_make_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'model_id' => 'Model ID',
            'vehicle_make_id' => 'Vehicle Make ID',
            'model' => 'Model',
            'year' => 'Year',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehicles()
    {
        return $this->hasMany(Vehicle::className(), ['model_id' => 'model_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehicleMake()
    {
        return $this->hasOne(VehicleMake::className(), ['vehicle_make_id' => 'vehicle_make_id']);
    }

    // Get vehicle make list
    public static function getVehicleModelList() { // could be a static func as well
        $models = VehicleModel::find()->asArray()->all();
        return ArrayHelper::map($models, 'model_id', 'model');
    }
}
