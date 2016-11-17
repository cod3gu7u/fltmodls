<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cost_category".
 *
 * @property integer $cost_category_id
 * @property string $cost_category
 * @property string $notes
 * @property string $record_status
 *
 * @property VehicleCosting[] $vehicleCostings
 */
class CostCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cost_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cost_category'], 'required'],
            [['notes', 'record_status'], 'string'],
            [['cost_category'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cost_category_id' => 'Cost Category ID',
            'cost_category' => 'Cost Category',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehicleCostings()
    {
        return $this->hasMany(VehicleCosting::className(), ['cost_category_id' => 'cost_category_id']);
    }
}
