<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "service_status".
 *
 * @property integer $service_status_id
 * @property string $service_status
 * @property string $notes
 * @property string $record_status
 *
 * @property Service[] $services
 */
class ServiceStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_status'], 'required'],
            [['notes', 'record_status'], 'string'],
            [['service_status'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'service_status_id' => 'Service Status ID',
            'service_status' => 'Service Status',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServices()
    {
        return $this->hasMany(Service::className(), ['service_status_id' => 'service_status_id']);
    }
}
