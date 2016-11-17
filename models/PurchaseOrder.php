<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "purchase_order".
 *
 * @property integer $purchase_order_id
 * @property integer $creditor_id
 * @property string $order_date
 * @property string $shipping_date
 * @property string $total_amount
 * @property string $taxed_amount
 * @property string $billed_amount
 * @property string $extended_billed_amount
 * @property integer $order_status_id
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property PurchaseOrderStatus $orderStatus
 * @property Creditor $creditor
 * @property PurchaseOrderLineItem[] $purchaseOrderLineItems
 */
class PurchaseOrder extends \yii\db\ActiveRecord
{

    // PurchaseOrderStatus
    const POS_OPEN = 1; // Open
    const POS_RECEIVED = 2; // Good Received
    const POS_PO2PV = 3; // Converted to Payment Voucher
    const POS_FINAL = 4; // Awaiting Payment
    const POS_CLOSED = 5; // Closed

    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchase_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['creditor_id', 'order_date', 'order_status_id', 'create_user_id', 'create_date'], 'required'],
            [['creditor_id', 'order_status_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['order_date', 'shipping_date', 'create_date', 'update_date'], 'safe'],
            [['total_amount', 'taxed_amount', 'billed_amount', 'extended_billed_amount'], 'number'],
            [['notes', 'record_status'], 'string'],
            [['order_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => PurchaseOrderStatus::className(), 'targetAttribute' => ['order_status_id' => 'purchase_order_status_id']],
            [['creditor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Creditor::className(), 'targetAttribute' => ['creditor_id' => 'creditor_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'purchase_order_id' => 'Purchase Order ID',
            'creditor_id' => 'Creditor',
            'order_date' => 'Order Date',
            'shipping_date' => 'Shipping Date',
            'total_amount' => 'Total Amount',
            'taxed_amount' => 'Taxed Amount',
            'billed_amount' => 'Billed Amount',
            'extended_billed_amount' => 'Ext Billed Amount',
            'order_status_id' => 'Order Status',
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
    public function getOrderStatus()
    {
        return $this->hasOne(PurchaseOrderStatus::className(), ['purchase_order_status_id' => 'order_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreditor()
    {
        return $this->hasOne(Creditor::className(), ['creditor_id' => 'creditor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrderLineItems()
    {
        return $this->hasMany(PurchaseOrderLineItem::className(), ['purchase_order_id' => 'purchase_order_id']);
    }
}
