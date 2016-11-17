<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "purchase_order_receive_item".
 *
 * @property integer $purchase_order_receive_item_id
 * @property integer $purchase_order_line_item_id
 * @property integer $received_units
 * @property string $received_date
 * @property string $record_status
 * @property string $notes
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 */
class PurchaseOrderReceiveItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchase_order_receive_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['purchase_order_line_item_id', 'received_units', 'received_date', 'create_user_id', 'create_date'], 'required'],
            [['purchase_order_line_item_id', 'received_units', 'create_user_id', 'update_user_id'], 'integer'],
            [['received_date', 'create_date', 'update_date'], 'safe'],
            [['record_status', 'notes'], 'string'],
            [['purchase_order_line_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => PurchaseOrderLineItem::className(), 'targetAttribute' => ['purchase_order_line_item_id' => 'purchase_order_line_item_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'purchase_order_receive_item_id' => 'Purchase Order Receive Item ID',
            'purchase_order_line_item_id' => 'Purchase Order Line Item ID',
            'received_units' => 'Received Units',
            'received_date' => 'Received Date',
            'record_status' => 'Record Status',
            'notes' => 'Notes',
            'create_user_id' => 'Create User ID',
            'create_date' => 'Create Date',
            'update_user_id' => 'Update User ID',
            'update_date' => 'Update Date',
        ];
    }

    public function afterSave ($insert, $changedAttributes)
    {
        // $transaction = Yii::$app->db->beginTransaction();
        try
        {
            if ($insert) 
            {
                if (($purchase_order_line_item = PurchaseOrderLineItem::findOne($this->purchase_order_line_item_id)) !== null) 
                {
                    // Get params
                    $purchase_order_id = $purchase_order_line_item->purchase_order_id;
                    
                    $purchase_order_item_id = $purchase_order_line_item->purchase_order_item_id;

                    $billed_tax = (
                        $purchase_order_line_item->unit_cost 
                        * $this->received_units 
                        * $purchase_order_line_item->tax_rate);
                    
                    $total_received_bill = (
                        $purchase_order_line_item->unit_cost * $this->received_units );

                    $extended_billed_amount = (
                        $purchase_order_line_item->unit_cost 
                        * $this->received_units 
                        * $billed_tax) 
                        + $purchase_order_line_item->billed_amount;

                    // Update PurchaseOrder billed_amount
                    $PO_total_billed = PurchaseOrderLineItem::find()
                                        ->where(['purchase_order_id' => $purchase_order_id])
                                        ->groupBy(['purchase_order_id'])
                                        ->select([new Expression('SUM(billed_amount) as billed_amount')])
                                        ->one();
                    
                    $purchase_order = PurchaseOrder::findOne($purchase_order_id);
                    $purchase_order->billed_amount = $PO_total_billed->billed_amount + $total_received_bill;
                    $purchase_order->save();
                    
                    // Increase stock quantity purchase-order-item            
                    $purchase_order_item = PurchaseOrderItem::findOne($purchase_order_item_id);
                    $purchase_order_item->instock_count = ($purchase_order_item->instock_count + $this->received_units);
                    $purchase_order_item->stock_counter = ($purchase_order_item->stock_counter + $this->received_units);
                    $purchase_order_item->save();

                    // Update purchase order line item
                    $purchase_order_line_item->received_units = $purchase_order_line_item->received_units + $this->received_units;
                    $purchase_order_line_item->billed_amount = $purchase_order_line_item->billed_amount + $total_received_bill;
                    $purchase_order_line_item->save();
                        
                    // $transaction->commit();

                } else {
                    throw new NotFoundHttpException('The requested page does not exist.');
                }
            } 
            parent::afterSave($insert, $changedAttributes);

        } catch(\Exception $ex){
            // $transaction->rollBack();
            throw $ex;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrderLineItem()
    {
        return $this->hasOne(PurchaseOrderLineItem::className(), ['purchase_order_line_item_id' => 'purchase_order_line_item_id']);
    }
}
