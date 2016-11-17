<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sales_person".
 *
 * @property integer $sales_person_id
 * @property string $sales_person
 * @property string $notes
 * @property string $record_status
 *
 * @property Sales[] $sales
 */
class SalesPerson extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sales_person';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sales_person'], 'required'],
            [['notes', 'record_status'], 'string'],
            [['sales_person'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sales_person_id' => 'Sales Person ID',
            'sales_person' => 'Sales Person',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasMany(Sales::className(), ['sales_person_id' => 'sales_person_id']);
    }
}
