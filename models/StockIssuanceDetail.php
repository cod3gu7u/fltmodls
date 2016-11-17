<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stock_issuance_detail".
 *
 * @property integer $stock_issuance_detail_id
 * @property integer $stock_issuance_id
 * @property integer $vehicle_id
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property StockIssuance $stockIssuance
 */
class StockIssuanceDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stock_issuance_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stock_issuance_id', 'vehicle_id', 'create_user_id', 'create_date'], 'required'],
            [['stock_issuance_id', 'vehicle_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['notes', 'record_status'], 'string'],
            [['create_date', 'update_date'], 'safe'],
            [['stock_issuance_id'], 'exist', 'skipOnError' => true, 'targetClass' => StockIssuance::className(), 'targetAttribute' => ['stock_issuance_id' => 'stock_issuance_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'stock_issuance_detail_id' => 'Stock Issuance Detail ID',
            'stock_issuance_id' => 'Stock Issuance ID',
            'vehicle_id' => 'Vehicle ID',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
            'create_user_id' => 'Create User ID',
            'create_date' => 'Create Date',
            'update_user_id' => 'Update User ID',
            'update_date' => 'Update Date',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {

        // // Update vehicle costing
        // $vehicle_costing = new VehicleCosting();
        // $vehicle_costing->creditor_id = $service->creditor_id;
        // $vehicle_costing->vehicle_id = $this->vehicle_id;
        // $vehicle_costing->cost_category_id = self::COST_CATEGORY_ID;
        // $vehicle_costing->cost_date = $this->create_date;
        // $vehicle_costing->total_amount = $this->cost;
        // $vehicle_costing->record_status = $this->record_status;
        // $vehicle_costing->notes = $this->notes;            
        // $vehicle_costing->create_user_id = $this->create_user_id;
        // $vehicle_costing->create_date = $this->create_date;
        // if(!$vehicle_costing->save()){
        //     throw new \Exception( 'Error creating the corresponding VehicleCosting record' );
        // }
        
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockIssuance()
    {
        return $this->hasOne(StockIssuance::className(), ['stock_issuance_id' => 'stock_issuance_id']);
    }

        /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehicle()
    {
        return $this->hasOne(Vehicle::className(), ['vehicle_id' => 'vehicle_id']);
    }
}
