<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "vehicle_costing".
 *
 * @property integer $vehicle_costing_id
 * @property integer $creditor_id
 * @property integer $vehicle_id
 * @property integer $cost_category_id
 * @property string $cost_date
 * @property integer $currency_id
 * @property string $transaction_amount
 * @property double $exchange_rate
 * @property string $total_amount
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property CostCategory $costCategory
 * @property Creditor $creditor
 * @property Vehicle $vehicle
 */
class VehicleCosting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vehicle_costing';
    }

    public function init()
    {
        parent::init();
        $this->currency_id = 1;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['creditor_id', 'vehicle_id', 'cost_category_id', 'cost_date', 'create_user_id', 'create_date'], 'required'],
            [['creditor_id', 'vehicle_id', 'cost_category_id', 'currency_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['cost_date', 'create_date', 'update_date'], 'safe'],
            [['transaction_amount', 'exchange_rate', 'total_amount'], 'number'],
            [['notes', 'record_status'], 'string'],
            [['cost_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => CostCategory::className(), 'targetAttribute' => ['cost_category_id' => 'cost_category_id']],
            [['creditor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Creditor::className(), 'targetAttribute' => ['creditor_id' => 'creditor_id']],
            [['vehicle_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vehicle::className(), 'targetAttribute' => ['vehicle_id' => 'vehicle_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vehicle_costing_id' => 'Vehicle Costing',
            'creditor_id' => 'Creditor',
            'vehicle_id' => 'Vehicle',
            'cost_category_id' => 'Cost Category',
            'cost_date' => 'Costing Date',
            'currency_id' => 'Currency',
            'transaction_amount' => 'Transaction Amount',
            'exchange_rate' => 'Exchange Rate',
            'total_amount' => 'Total Amount',
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
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['currency_id' => 'currency_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCostCategory()
    {
        return $this->hasOne(CostCategory::className(), ['cost_category_id' => 'cost_category_id']);
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
    public function getVehicle()
    {
        return $this->hasOne(Vehicle::className(), ['vehicle_id' => 'vehicle_id']);
    }

    // Get CostCategory list
    public function getCostCategoryList() { // could be a static func as well
        $models = CostCategory::find()
            ->where(['record_status'=>'active'])
            ->asArray()
            ->all();
        return ArrayHelper::map($models, 'cost_category_id', 'cost_category');
    }

    // Get creditor list
    public function getCreditorList() { // could be a static func as well
        $models = Creditor::find()
            ->where(['record_status'=>'active'])
            ->asArray()
            ->all();
        return ArrayHelper::map($models, 'creditor_id', 'creditor');
    }

    // Get currency list
    public function getCurrencyList() { // could be a static func as well
        $models = Currency::find()->asArray()->all();
        return ArrayHelper::map($models, 'currency_id', 'currency_short');
    }

    public static function totalCost($provider, $fieldName)
    {
        $total = 0;
        foreach($provider as $item){
            $total += $item[$fieldName];
        }
        return $total;
    }
}
