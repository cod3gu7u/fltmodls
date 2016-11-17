<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sales_quote".
 *
 * @property integer $sales_quote_id
 * @property integer $vehicle_id
 * @property integer $customer_id
 * @property integer $sales_person_id
 * @property string $quote_issue_date
 * @property string $quote_expiry_date
 * @property string $quoted_amount
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property SalesPerson $salesPerson
 * @property Vehicle $vehicle
 * @property Customer $customer
 */
class SalesQuote extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sales_quote';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_id', 'customer_id', 'sales_person_id', 'quote_issue_date', 'quoted_amount', 'create_user_id', 'create_date'], 'required'],
            [['vehicle_id', 'customer_id', 'sales_person_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['quote_issue_date', 'quote_expiry_date', 'create_date', 'update_date'], 'safe'],
            [['quoted_amount'], 'number'],
            [['notes', 'record_status'], 'string'],
            [['sales_person_id'], 'exist', 'skipOnError' => true, 'targetClass' => SalesPerson::className(), 'targetAttribute' => ['sales_person_id' => 'sales_person_id']],
            [['vehicle_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vehicle::className(), 'targetAttribute' => ['vehicle_id' => 'vehicle_id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sales_quote_id' => 'Sales Quote ID',
            'vehicle_id' => 'Vehicle ID',
            'customer_id' => 'Customer ID',
            'sales_person_id' => 'Sales Person ID',
            'quote_issue_date' => 'Quote Issue Date',
            'quote_expiry_date' => 'Quote Expiry Date',
            'quoted_amount' => 'Quoted Amount',
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
    public function getSalesPerson()
    {
        return $this->hasOne(SalesPerson::className(), ['sales_person_id' => 'sales_person_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVehicle()
    {
        return $this->hasOne(Vehicle::className(), ['vehicle_id' => 'vehicle_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }
}
