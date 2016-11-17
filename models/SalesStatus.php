<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sales_status".
 *
 * @property integer $sales_status_id
 * @property string $sales_status
 * @property string $notes
 * @property string $record_status
 *
 * @property Sales[] $sales
 */
class SalesStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sales_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sales_status'], 'required'],
            [['notes', 'record_status'], 'string'],
            [['sales_status'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sales_status_id' => 'Sales Status ID',
            'sales_status' => 'Sales Status',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasMany(Sales::className(), ['sales_status_id' => 'sales_status_id']);
    }
}
