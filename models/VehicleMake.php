<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "vehicle_make".
 *
 * @property integer $vehicle_make_id
 * @property string $make
 * @property string $notes
 * @property string $record_status
 *
 * @property Vehicle[] $vehicles
 */
class VehicleMake extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vehicle_make';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['make', 'record_status'], 'required'],
            [['notes', 'record_status'], 'string'],
            [['make'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vehicle_make_id' => 'Vehicle Make ID',
            'make' => 'Make',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehicles()
    {
        return $this->hasMany(Vehicle::className(), ['make_id' => 'vehicle_make_id']);
    }

    // Get vehicle make list
    public static function getVehicleMakeList() { // could be a static func as well
        $models = VehicleMake::find()->asArray()->all();
        return ArrayHelper::map($models, 'vehicle_make_id', 'make');
    }
}
