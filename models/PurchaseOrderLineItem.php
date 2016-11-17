<?php

namespace app\models;

use Yii;
use yii\db\Query;
/**
 * This is the model class for table "purchase_order_line_item".
 *
 * @property integer $purchase_order_line_item_id
 * @property integer $purchase_order_id
 * @property integer $purchase_order_item_id
 * @property string $unit_cost
 * @property integer $units
 * @property integer $received_units
 * @property double $tax_rate
 * @property string $tax_amount
 * @property string $total_amount
 * @property string $billed_amount
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property PurchaseOrderLineItem $purchaseOrderItem
 * @property PurchaseOrderLineItem[] $purchaseOrderLineItems
 * @property PurchaseOrder $purchaseOrder
 */
class PurchaseOrderLineItem extends \yii\db\ActiveRecord
{
    const ACTIVE = 'active';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchase_order_line_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['purchase_order_id', 'purchase_order_item_id', 'create_user_id', 'create_date'], 'required'],
            [['purchase_order_id', 'purchase_order_item_id', 'units', 'received_units', 'create_user_id', 'update_user_id'], 'integer'],
            [['unit_cost', 'tax_rate', 'tax_amount', 'total_amount', 'billed_amount'], 'number'],
            [['notes', 'record_status'], 'string'],
            [['create_date', 'update_date'], 'safe'],
            [['purchase_order_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => PurchaseOrderItem::className(), 'targetAttribute' => ['purchase_order_item_id' => 'purchase_order_item_id']],
            [['purchase_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => PurchaseOrder::className(), 'targetAttribute' => ['purchase_order_id' => 'purchase_order_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'purchase_order_line_item_id' => 'Purchase Order Line Item ID',
            'purchase_order_id' => 'Purchase Order ID',
            'purchase_order_item_id' => 'Purchase Order Item ID',
            'unit_cost' => 'Unit Cost',
            'units' => 'Units',
            'received_units' => 'Received Units',
            'tax_rate' => 'Tax Rate',
            'tax_amount' => 'Tax Amount',
            'total_amount' => 'Total Amount',
            'billed_amount' => 'Billed Amount',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
            'create_user_id' => 'Create User ID',
            'create_date' => 'Create Date',
            'update_user_id' => 'Update User ID',
            'update_date' => 'Update Date',
        ];
    }

    public function afterSave($insert)
    {
        try{
            $connection = \Yii::$app->db;

            $model = $connection->createCommand("SELECT SUM(total_amount) FROM purchase_order_line_item WHERE purchase_order_id=:purchase_order_id AND record_status=:record_status")
                ->bindValue(':purchase_order_id', $this->purchase_order_id)
                ->bindValue(':record_status', self::ACTIVE);
                
            $total = $model->queryScalar();

            // Update PurchaseOrder total
            $purchase_order = PurchaseOrder::findOne($this->purchase_order_id);
            $purchase_order->total_amount = $total;
            $purchase_order->save();

            // print_r($total); die();

            return true;
        } catch(\Exception $ex){
            throw $ex;
        }
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
    public function getPurchaseOrderLineItems()
    {
        return $this->hasMany(PurchaseOrderLineItem::className(), ['purchase_order_item_id' => 'purchase_order_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrderReceiveItems()
    {
        return $this->hasMany(PurchaseOrderReceiveItem::className(), ['purchase_order_line_item_id' => 'purchase_order_line_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrder()
    {
        return $this->hasOne(PurchaseOrder::className(), ['purchase_order_id' => 'purchase_order_id']);
    }
}
