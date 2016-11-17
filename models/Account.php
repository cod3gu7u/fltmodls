<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "account".
 *
 * @property integer $account_id
 * @property integer $parent_id
 * @property string $account_name
 * @property integer $account_type_id
 *
 * @property Account $parent
 * @property Account[] $accounts
 * @property AccountType $accountType
 * @property Posting[] $postings
 */
class Account extends \yii\db\ActiveRecord 
{
    // use \kartik\tree\models\TreeTrait {
    //     isDisabled as parentIsDisabled; // note the alias
    // }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'account_type_id'], 'integer'],
            [['account_name', 'account_type_id'], 'required'],
            [['account_number'], 'string', 'max' => 8], 
            [['account_name'], 'string', 'max' => 50],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['parent_id' => 'account_id']],
            [['account_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccountType::className(), 'targetAttribute' => ['account_type_id' => 'account_type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'account_id' => 'Account ID',
            'parent_id' => 'Parent ID',
            'account_name' => 'Account Name',
            'account_number' => 'Account Number', 
            'account_type_id' => 'Account Type ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Account::className(), ['account_id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccounts()
    {
        return $this->hasMany(Account::className(), ['parent_id' => 'account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountType()
    {
        return $this->hasOne(AccountType::className(), ['account_type_id' => 'account_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostings()
    {
        return $this->hasMany(Posting::className(), ['account_id' => 'account_id']);
    }

    // Get accounts list
    public function getAccountList() { // could be a static func as well
        $models = Account::find()->asArray()->all();
        return ArrayHelper::map($models, 'account_id', 'account_name');
    }

    // Get accounts list
    public function getAccountTypes() { // could be a static func as well
        $models = AccountType::find()->asArray()->all();
        return ArrayHelper::map($models, 'account_type_id', 'account_type');
    }

}
