<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "report_header".
 *
 * @property integer $id
 * @property string $name
 *
 * @property ReportDefinition[] $reportDefinitions
 */
class ReportHeader extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report_header';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReportDefinitions()
    {
        return $this->hasMany(ReportDefinition::className(), ['report_header_id' => 'id']);
    }
}
