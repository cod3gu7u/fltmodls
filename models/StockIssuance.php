<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stock_issuance".
 *
 * @property integer $stock_issuance_id
 * @property integer $purchase_order_item_id
 * @property double $units
 * @property string $issuance_date
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property PurchaseOrderItem $purchaseOrderItem
 * @property StockIssuanceDetail[] $stockIssuanceDetails
 */
class StockIssuance extends \yii\db\ActiveRecord
{

    public $barcode;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stock_issuance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['purchase_order_item_id', 'issuance_date', 'create_user_id', 'create_date'], 'required'],
            [['purchase_order_item_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['units'], 'number'],
            [['issuance_date', 'create_date', 'update_date'], 'safe'],
            [['notes', 'record_status'], 'string'],
            [['purchase_order_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => PurchaseOrderItem::className(), 'targetAttribute' => ['purchase_order_item_id' => 'purchase_order_item_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'stock_issuance_id' => 'Stock Issuance',
            'barcode' => 'Barcode',
            'purchase_order_item_id' => 'Item',
            'units' => 'Quantity',
            'issuance_date' => 'Issuance Date',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
            'create_user_id' => 'Create User ID',
            'create_date' => 'Create Date',
            'update_user_id' => 'Update User ID',
            'update_date' => 'Update Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrderItem()
    {
        return $this->hasOne(PurchaseOrderItem::className(), ['purchase_order_item_id' => 'purchase_order_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockIssuanceDetails()
    {
        return $this->hasMany(StockIssuanceDetail::className(), ['stock_issuance_id' => 'stock_issuance_id']);
    }
}
