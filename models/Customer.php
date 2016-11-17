<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "customer".
 *
 * @property integer $customer_id
 * @property string $customer_name
 * @property integer $customer_title_id
 * @property integer $customer_type_id
 * @property string $telephone
 * @property string $cellphone
 * @property string $address
 * @property string $vat_registration_number
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property CustomerType $customerType
 * @property CustomerTitle $customerTitle
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_name', 'customer_type_id', 'create_user_id', 'create_date'], 'required'],
            [['customer_title_id', 'customer_type_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['notes', 'record_status'], 'string'],
            [['create_date', 'update_date'], 'safe'],
            [['customer_name', 'address'], 'string', 'max' => 250],
            [['telephone', 'cellphone'], 'string', 'max' => 50],
            [['vat_registration_number'], 'string', 'max' => 20],
            [['customer_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CustomerType::className(), 'targetAttribute' => ['customer_type_id' => 'customer_type_id']],
            [['customer_title_id'], 'exist', 'skipOnError' => true, 'targetClass' => CustomerTitle::className(), 'targetAttribute' => ['customer_title_id' => 'customer_title_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_id' => 'Customer ID',
            'customer_name' => 'Customer Name',
            'customer_title_id' => 'Title',
            'customer_type_id' => 'Type',
            'telephone' => 'Telephone',
            'cellphone' => 'Cellphone',
            'address' => 'Address',
            'vat_registration_number' => 'Vat Registration Number',
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
    public function getCustomerType()
    {
        return $this->hasOne(CustomerType::className(), ['customer_type_id' => 'customer_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerTitle()
    {
        return $this->hasOne(CustomerTitle::className(), ['customer_title_id' => 'customer_title_id']);
    }

    // Get location list
    public function getCustomerTypeList() { // could be a static func as well
        $models = CustomerType::find()->asArray()->all();
        return ArrayHelper::map($models, 'customer_type_id', 'customer_type');
    }

    // Get location list
    public function getCustomerTitleList() { // could be a static func as well
        $models = CustomerTitle::find()->asArray()->all();
        return ArrayHelper::map($models, 'customer_title_id', 'customer_title');
    }
}
