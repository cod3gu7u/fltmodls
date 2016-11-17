<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer_type".
 *
 * @property integer $customer_type_id
 * @property string $customer_type
 * @property string $notes
 * @property string $record_status
 *
 * @property Customer[] $customers
 */
class CustomerType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_type'], 'required'],
            [['notes', 'record_status'], 'string'],
            [['customer_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_type_id' => 'Customer Type ID',
            'customer_type' => 'Customer Type',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['customer_type_id' => 'customer_type_id']);
    }
}
