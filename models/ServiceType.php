<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "service_type".
 *
 * @property integer $service_type_id
 * @property string $service_type
 * @property string $notes
 * @property string $record_status
 *
 * @property ServiceItem[] $serviceItems
 */
class ServiceType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_type'], 'required'],
            [['notes', 'record_status'], 'string'],
            [['service_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'service_type_id' => 'Service Type ID',
            'service_type' => 'Service Type',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceItems()
    {
        return $this->hasMany(ServiceItem::className(), ['service_type_id' => 'service_type_id']);
    }
}
