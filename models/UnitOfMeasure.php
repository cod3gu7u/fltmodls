<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "unit_of_measure".
 *
 * @property integer $unit_of_measure_id
 * @property string $unit_of_measure
 * @property string $notes
 * @property string $record_status
 *
 * @property PurchaseOrderItem[] $purchaseOrderItems
 */
class UnitOfMeasure extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unit_of_measure';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unit_of_measure'], 'required'],
            [['notes', 'record_status'], 'string'],
            [['unit_of_measure'], 'string', 'max' => 12],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'unit_of_measure_id' => 'Unit Of Measure ID',
            'unit_of_measure' => 'Unit Of Measure',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::className(), ['unit_of_measure_id' => 'unit_of_measure_id']);
    }

    // Get UnitOfMeasure list
    public static function getUnitOfMeasureList() { 
        $models = UnitOfMeasure::find()->where(['record_status'=>'active'])->asArray()->all();
        return ArrayHelper::map($models, 'unit_of_measure_id', 'unit_of_measure');
    }
}
