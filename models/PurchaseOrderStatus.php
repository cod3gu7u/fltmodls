<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "purchase_order_status".
 *
 * @property integer $purchase_order_status_id
 * @property string $purchase_order_status
 * @property string $notes
 * @property string $record_status
 *
 * @property PurchaseOrder[] $purchaseOrders
 */
class PurchaseOrderStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'purchase_order_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['purchase_order_status'], 'required'],
            [['notes', 'record_status'], 'string'],
            [['purchase_order_status'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'purchase_order_status_id' => 'Purchase Order Status ID',
            'purchase_order_status' => 'Purchase Order Status',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::className(), ['order_status_id' => 'purchase_order_status_id']);
    }

    // Get Purchase Order Status list
    public function getPurchaseOrderStatusList() { // could be a static func as well
        $models = PurchaseOrderStatus::find()->where(['record_status'=>'active'])->asArray()->all();
        return ArrayHelper::map($models, 'purchase_order_status_id', 'purchase_order_status');
    }
}
