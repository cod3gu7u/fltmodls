<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "account_type".
 *
 * @property integer $account_type_id
 * @property string $account_type
 *
 * @property Account[] $accounts
 */
class AccountType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'account_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_type'], 'required'],
            [['account_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'account_type_id' => 'Account Type ID',
            'account_type' => 'Account Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccounts()
    {
        return $this->hasMany(Account::className(), ['account_type_id' => 'account_type_id']);
    }

    // Get Accounts list
    public function getAccountTypeList() { // could be a static func as well
        $models = AccountType::find()->asArray()->all();
        return ArrayHelper::map($models, 'account_type_id', 'account_type');
    }
}
