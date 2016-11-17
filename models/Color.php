<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "color".
 *
 * @property integer $color_id
 * @property string $color
 * @property string $notes
 * @property string $record_status
 *
 * @property Vehicle[] $vehicles
 */
class Color extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'color';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['color', 'record_status'], 'required'],
            [['notes', 'record_status'], 'string'],
            [['color'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'color_id' => 'Color ID',
            'color' => 'Color',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehicles()
    {
        return $this->hasMany(Vehicle::className(), ['color_id' => 'color_id']);
    }
}
