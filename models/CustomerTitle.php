<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer_title".
 *
 * @property integer $customer_title_id
 * @property string $customer_title
 * @property string $notes
 * @property string $record_status
 *
 * @property Customer[] $customers
 */
class CustomerTitle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_title';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_title'], 'required'],
            [['notes', 'record_status'], 'string'],
            [['customer_title'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_title_id' => 'Customer Title ID',
            'customer_title' => 'Customer Title',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['customer_title_id' => 'customer_title_id']);
    }
}
