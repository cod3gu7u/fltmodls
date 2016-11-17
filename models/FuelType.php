<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fuel_type".
 *
 * @property integer $id
 * @property string $fule_type
 * @property string $notes
 * @property string $record_status
 */
class FuelType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fuel_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fule_type', 'record_status'], 'required'],
            [['notes', 'record_status'], 'string'],
            [['fule_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fule_type' => 'Fule Type',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }
}
