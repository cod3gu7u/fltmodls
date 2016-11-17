<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bank_transfer".
 *
 * @property integer $bank_transfer_id
 * @property integer $source_bank_id
 * @property integer $destination_bank_id
 * @property string $amount
 * @property string $transfer_date
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property Bank $destinationBank
 * @property Bank $sourceBank
 */
class BankTransfer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bank_transfer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['source_bank_id', 'destination_bank_id', 'transfer_date', 'create_user_id', 'create_date'], 'required'],
            [['source_bank_id', 'destination_bank_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['amount'], 'number'],
            [['transfer_date', 'create_date', 'update_date'], 'safe'],
            [['notes', 'record_status'], 'string'],
            [['destination_bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['destination_bank_id' => 'bank_id']],
            [['source_bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['source_bank_id' => 'bank_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bank_transfer_id' => 'Bank Transfer',
            'source_bank_id' => 'Source Bank',
            'destination_bank_id' => 'Destination Bank',
            'amount' => 'Amount',
            'transfer_date' => 'Transfer Date',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
            'create_user_id' => 'Create User ID',
            'create_date' => 'Create Date',
            'update_user_id' => 'Update User ID',
            'update_date' => 'Update Date',
        ];
    }

    // Update Balances
    // public function afterSave($insert)
    // {
    //     // Update balances
    //     $transaction = Yii::$app->db->beginTransaction();
    //     try
    //     {
    //         // Update source bank balance
    //         $source_bank = BankBalance::find()
    //                 ->where(['bank_id' => $this->source_bank_id])
    //                 ->one();

    //         $source_bank->opening_balance = $source_bank->opening_balance - $this->amount;
    //         $model->end_date = date('Y-m-d');
    //         $model->update_date = date('Y-m-d');
    //         $source_bank->save();

    //         // Update destination bank balance
    //         $destination_bank = BankBalance::find()
    //                 ->where(['bank_id' => $this->destination_bank_id])
    //                 ->one();

    //         $destination_bank->opening_balance = $destination_bank->opening_balance + $this->amount;
    //         $destination_bank->end_date = date('Y-m-d');
    //         $destination_bank->update_date = date('Y-m-d');
    //         $destination_bank->save();

    //     } catch(\Exception $ex){
    //         $transaction->rollBack();
    //         throw $ex;
    //     }
    //     parent::afterSave($insert);
    // }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDestinationBank()
    {
        return $this->hasOne(Bank::className(), ['bank_id' => 'destination_bank_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourceBank()
    {
        return $this->hasOne(Bank::className(), ['bank_id' => 'source_bank_id']);
    }

    // Get bank list
    public function getBankList() { // could be a static func as well
        $models = Bank::find()->asArray()->all();
        return ArrayHelper::map($models, 'bank_id', 'bank');
    }
}
