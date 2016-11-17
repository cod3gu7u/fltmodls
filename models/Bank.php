<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bank".
 *
 * @property integer $bank_id
 * @property integer $account_id
 * @property string $bank
 * @property integer $currency_id
 * @property string $record_status
 *
 * @property Account $account
 */
class Bank extends \yii\db\ActiveRecord
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'bank'], 'required'],
            [['account_id'], 'integer'],
            [['currency_id'], 'integer'],
            [['record_status'], 'string'],
            [['bank'], 'string', 'max' => 50],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['account_id' => 'account_id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'currency_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bank_id' => 'Bank ID',
            'account_id' => 'GL Account',
            'bank' => 'Bank',
            'currency_id' => 'Currency',
            'record_status' => 'Record Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['account_id' => 'account_id']);
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
    public function getBankBalance()
    {
        return $this->hasOne(BankBalance::className(), ['bank_id' => 'bank_id']);
    }

    // Get accounts list
    public function getAccountList() { // could be a static func as well
        $models = Account::find()->asArray()->all();
        return ArrayHelper::map($models, 'account_id', 'account_name');
    }

    // Get accounts list
    public function getBankList() { // could be a static func as well
        $models = Bank::find()->where(['record_status' => self::ACTIVE])->asArray()->all();
        return ArrayHelper::map($models, 'bank_id', 'bank');
    }
}
