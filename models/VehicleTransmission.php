<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vehicle_transmission".
 *
 * @property integer $id
 * @property string $transmission
 * @property string $notes
 * @property string $record_status
 */
class VehicleTransmission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vehicle_transmission';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transmission'], 'required'],
            [['notes', 'record_status'], 'string'],
            [['transmission'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'transmission' => 'Transmission',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }
}
