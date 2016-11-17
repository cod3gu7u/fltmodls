<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "report_definition".
 *
 * @property integer $id
 * @property string $name
 * @property integer $report_header_id
 * @property integer $account_type_id
 * @property integer $parent_id
 * @property string $side
 * @property integer $order_id
 *
 * @property ReportDefinition $parent
 * @property ReportDefinition[] $reportDefinitions
 * @property ReportHeader $reportHeader
 * @property AccountType $accountType
 */
class ReportDefinition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report_definition';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'report_header_id', 'account_type_id'], 'required'],
            [['report_header_id', 'account_type_id', 'parent_id', 'order_id'], 'integer'],
            [['side'], 'string'],
            [['name'], 'string', 'max' => 50],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReportDefinition::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['report_header_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReportHeader::className(), 'targetAttribute' => ['report_header_id' => 'id']],
            [['account_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccountType::className(), 'targetAttribute' => ['account_type_id' => 'account_type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'report_header_id' => 'Report Header ID',
            'account_type_id' => 'Account Type ID',
            'parent_id' => 'Parent ID',
            'side' => 'Side',
            'order_id' => 'Order ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(ReportDefinition::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReportDefinitions()
    {
        return $this->hasMany(ReportDefinition::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReportHeader()
    {
        return $this->hasOne(ReportHeader::className(), ['id' => 'report_header_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountType()
    {
        return $this->hasOne(AccountType::className(), ['account_type_id' => 'account_type_id']);
    }

    // Get color list
    public function getParentList($id) { // could be a static func as well
        $models = ReportDefinition::find()
            ->where(['parent_id'=>$id])
            ->asArray()->all();
        return ArrayHelper::map($models, 'parent_id', 'name');
    }
}
