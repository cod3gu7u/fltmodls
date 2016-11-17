<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "issue_inventory_line_item".
 *
 * @property integer $issue_inventory_line_item_id
 * @property integer $issue_inventory_id
 * @property integer $purchase_order_item_id
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property PurchaseOrderItem $purchaseOrderItem
 * @property IssueInventory $issueInventory
 */
class IssueInventoryLineItem extends \yii\db\ActiveRecord
{
    public $barcode;
    public $product_name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'issue_inventory_line_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['issue_inventory_id', 'purchase_order_item_id', 'create_user_id', 'create_date'], 'required'],
            [['issue_inventory_id', 'purchase_order_item_id', 'quantity', 'create_user_id', 'update_user_id'], 'integer'],
            [['barcode', 'product_name'], 'string'],
            [['notes', 'record_status'], 'string'],
            [['create_date', 'update_date'], 'safe'],
            [['purchase_order_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => PurchaseOrderItem::className(), 'targetAttribute' => ['purchase_order_item_id' => 'purchase_order_item_id']],
            [['issue_inventory_id'], 'exist', 'skipOnError' => true, 'targetClass' => IssueInventory::className(), 'targetAttribute' => ['issue_inventory_id' => 'issue_inventory_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'issue_inventory_line_item_id' => 'Issue Inventory Line Item ID',
            'issue_inventory_id' => 'Issue Inventory ID',
            'purchase_order_item_id' => 'Item',
            'quantity' => 'Quantity',
            'barcode' => 'Barcode',
            'product_name' => 'Product',
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
    public function getIssueInventory()
    {
        return $this->hasOne(IssueInventory::className(), ['issue_inventory_id' => 'issue_inventory_id']);
    }
}
