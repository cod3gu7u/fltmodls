<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vehicle_photo".
 *
 * @property integer $vehicle_photo_id
 * @property integer $vehicle_id
 * @property string $photograph
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property Vehicle $vehicle
 */
class VehiclePhoto extends \yii\db\ActiveRecord
{
    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vehicle_photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_id', 'photograph', 'create_user_id', 'create_date'], 'required'],
            [['vehicle_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['notes', 'record_status'], 'string'],
            [['create_date', 'update_date'], 'safe'],
            [['photograph'], 'string', 'max' => 255],
            [['vehicle_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vehicle::className(), 'targetAttribute' => ['vehicle_id' => 'vehicle_id']],
            [['file'], 'file'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vehicle_photo_id' => 'Vehicle Photo ID',
            'vehicle_id' => 'Vehicle ID',
            'photograph' => 'Photograph',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
            'create_user_id' => 'Create User ID',
            'create_date' => 'Create Date',
            'update_user_id' => 'Update User ID',
            'update_date' => 'Update Date',
            'file' => 'Photo File',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehicle()
    {
        return $this->hasOne(Vehicle::className(), ['vehicle_id' => 'vehicle_id']);
    }
}
