<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bank_balance".
 *
 * @property integer $bank_balance_id
 * @property integer $bank_id
 * @property string $start_date
 * @property string $end_date
 * @property string $opening_balance
 * @property string $closing_balance
 * @property string $notes
 * @property string $record_status
 * @property integer $creator_id
 * @property string $create_date
 * @property integer $updater_id
 * @property string $update_date
 *
 * @property Bank $bank
 */
class BankBalance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bank_balance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bank_id', 'start_date', 'creator_id', 'create_date'], 'required'],
            [['bank_id', 'creator_id', 'updater_id'], 'integer'],
            [['start_date', 'end_date', 'create_date', 'update_date'], 'safe'],
            [['opening_balance', 'closing_balance'], 'number'],
            [['notes', 'record_status'], 'string'],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['bank_id' => 'bank_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bank_balance_id' => 'Bank Balance ID',
            'bank_id' => 'Bank ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'opening_balance' => 'Opening Balance',
            'closing_balance' => 'Closing Balance',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
            'creator_id' => 'Creator ID',
            'create_date' => 'Create Date',
            'updater_id' => 'Updater ID',
            'update_date' => 'Update Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBank()
    {
        return $this->hasOne(Bank::className(), ['bank_id' => 'bank_id']);
    }

    // Get accounts list
    public function getBankList() { // could be a static func as well
        $models = Bank::find()->asArray()->all();
        return ArrayHelper::map($models, 'bank_id', 'bank');
    }
}
