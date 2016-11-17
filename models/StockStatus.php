<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stock_status".
 *
 * @property integer $stock_status_id
 * @property string $stock_status
 * @property string $notes
 * @property string $record_status
 *
 * @property Vehicle[] $vehicles
 */
class StockStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stock_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stock_status', 'record_status'], 'required'],
            [['notes', 'record_status'], 'string'],
            [['stock_status'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'stock_status_id' => 'Stock Status ID',
            'stock_status' => 'Stock Status',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehicles()
    {
        return $this->hasMany(Vehicle::className(), ['stock_status_id' => 'stock_status_id']);
    }
}
