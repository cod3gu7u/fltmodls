<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "purchase_order_item".
 *
 * @property integer $purchase_order_item_id
 * @property string $purchase_order_item
 * @property string $barcode
 * @property string $stock_counter
 * @property integer $instock_count
 * @property integer $reorder_level
 * @property integer $unit_of_measure_id
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property UnitOfMeasure $unitOfMeasure
 * @property StockIssuance[] $stockIssuances
 */
class PurchaseOrderItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchase_order_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['purchase_order_item', 'create_user_id', 'create_date'], 'required'],
            [['instock_count', 'stock_counter', 'reorder_level', 'unit_of_measure_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['barcode', 'notes', 'record_status'], 'string'],
            ['barcode', 'unique'],
            [['create_date', 'update_date'], 'safe'],
            [['purchase_order_item'], 'string', 'max' => 150],
            [['unit_of_measure_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnitOfMeasure::className(), 'targetAttribute' => ['unit_of_measure_id' => 'unit_of_measure_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'purchase_order_item_id' => 'Product ID',
            'purchase_order_item' => 'Product',
            'barcode' => 'Barcode',
            'stock_counter' => 'Stock Counter',
            'instock_count' => 'In-stock Quantity',
            'reorder_level' => 'Reorder Level',
            'unit_of_measure_id' => 'Unit Of Measure',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
            'create_user_id' => 'Create User ID',
            'create_date' => 'Create Date',
            'update_user_id' => 'Update User ID',
            'update_date' => 'Update Date',
        ];
    }

    // public function afterSave($insert, $changedAttributes)
    // { 
    //     // Update stock_counter
    //     $insert->stock_counter = $insert->stock_counter + $insert->stock_counter 

    //     parent::afterSave ($insert, $changedAttributes);
    // }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnitOfMeasure()
    {
        return $this->hasOne(UnitOfMeasure::className(), ['unit_of_measure_id' => 'unit_of_measure_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockIssuances()
    {
        return $this->hasMany(StockIssuance::className(), ['purchase_order_item_id' => 'purchase_order_item_id']);
    }

    // Get PurchaseOrderItem list
    public static function getPurchaseOrderItemList() { 
        $models = PurchaseOrderItem::find()->where(['record_status'=>'active'])->asArray()->all();
        return ArrayHelper::map($models, 'purchase_order_item_id', 'purchase_order_item');
    }
}
