<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "creditor_type".
 *
 * @property integer $creditor_type_id
 * @property string $creditor_type
 * @property string $notes
 * @property string $record_status
 */
class CreditorType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'creditor_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['creditor_type'], 'required'],
            [['notes', 'record_status'], 'string'],
            [['creditor_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'creditor_type_id' => 'Creditor Type ID',
            'creditor_type' => 'Creditor Type',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }
}
