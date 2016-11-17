<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "posting".
 *
 * @property integer $posting_sequence_id
 * @property integer $account_id
 * @property integer $journal_id
 * @property integer $accounting_period_id
 * @property integer $asset_type_id
 * @property integer $quantity
 * @property string $unit_amount
 * @property string $total_amount
 * @property integer $posting_status
 * @property integer $journal_owner_id
 * @property string $posting_date
 * @property integer $posting_user_id
 *
 * @property Account $account
 * @property Journal $journal
 * @property AccountingPeriod $accountingPeriod
 * @property AssetType $assetType
 */
class Posting extends \yii\db\ActiveRecord
{
    const QUANTITY = 1;
    const POSTING_STATUS = 0;
    const ASSET_TYPE = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posting';
    }

    public function init()
    {
        parent::init();
        // $this->posting_date = date('Y-m-d');
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'journal_id', 'accounting_period_id', 'asset_type_id', 'journal_owner_id'], 'required'],
            [['account_id', 'journal_id', 'accounting_period_id', 'asset_type_id', 'quantity', 'posting_status', 'journal_owner_id', 'posting_user_id'], 'integer'],
            [['unit_amount', 'total_amount'], 'number'],
            [['posting_date'], 'safe'],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['account_id' => 'account_id']],
            [['journal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Journal::className(), 'targetAttribute' => ['journal_id' => 'journal_id']],
            [['accounting_period_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccountingPeriod::className(), 'targetAttribute' => ['accounting_period_id' => 'accounting_period_id']],
            [['asset_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AssetType::className(), 'targetAttribute' => ['asset_type_id' => 'asset_type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'posting_sequence_id' => 'Posting Sequence ID',
            'account_id' => 'Account',
            'journal_id' => 'Journal',
            'accounting_period_id' => 'Accounting Period',
            'asset_type_id' => 'Asset Type ID',
            'quantity' => 'Quantity',
            'unit_amount' => 'Unit Amount',
            'total_amount' => 'Total Amount',
            'posting_status' => 'Posting Status',
            'journal_owner_id' => 'Journal Owner ID',
            'posting_date' => 'Posting Date',
            'posting_user_id' => 'Posting User ID',
        ];
    }

    /**
    * Populate Journal
    * $sales_transaction_type_id debit_account_id/credit_account_id
    * @param [$journal_id. $trnx_type_id, $amount]
    * @return boolean success
    */
    public function populateJournal($params)
    {
        if($params){
            $posting = new Posting();
            $posting->loadDefaultValues();
            $posting->journal_id = $params['journal_id'];
            $posting->account_id = $params['account_id'];
            $posting->quantity = Posting::QUANTITY;
            $posting->unit_amount = $params['unit_amount'];
            $posting->total_amount = ($params['unit_amount'] * Posting::QUANTITY);
            $posting->posting_status = Posting::POSTING_STATUS;
            $posting->asset_type_id = Posting::ASSET_TYPE;
            $posting->accounting_period_id = $params['accounting_period_id'];
            $posting->journal_owner_id = $params['create_user_id'];
            return $posting->save();
        }else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
    public function getJournal()
    {
        return $this->hasOne(Journal::className(), ['journal_id' => 'journal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountingPeriod()
    {
        return $this->hasOne(AccountingPeriod::className(), ['accounting_period_id' => 'accounting_period_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssetType()
    {
        return $this->hasOne(AssetType::className(), ['asset_type_id' => 'asset_type_id']);
    }

    // Get accounts list
    public function getAccountList() { // could be a static func as well
        $models = Account::find()->asArray()->all();
        return ArrayHelper::map($models, 'account_id', 'account_name');
    }

    // Get accounting period list
    public function getAccountingPeriodList() { // could be a static func as well
        $models = AccountingPeriod::find()->asArray()->all();
        return ArrayHelper::map($models, 'accounting_period_id', 'accounting_period');
    }

    // Get asset types list
    public function getAssetTypeList() { // could be a static func as well
        $models = AssetType::find()->asArray()->all();
        return ArrayHelper::map($models, 'asset_type_id', 'asset_type');
    }
}
