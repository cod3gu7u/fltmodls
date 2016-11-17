<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reference_number".
 *
 * @property string $reference_prefix
 * @property integer $reference_counter
 * @property integer $notes
 * @property string $record_status
 */
class ReferenceNumber extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reference_number';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reference_prefix', 'reference_counter', 'record_status'], 'required'],
            [['reference_counter', 'notes'], 'integer'],
            [['record_status'], 'string'],
            [['reference_prefix'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'reference_prefix' => 'Reference Prefix',
            'reference_counter' => 'Reference Counter',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    public function fields()
    {
    return [
        // field name is "reference_number", its value is defined by a PHP callback
        'reference_number' => function () {
            return $this->reference_prefix . ' ' . $this->reference_counter;
        },
    ];
    }
}
