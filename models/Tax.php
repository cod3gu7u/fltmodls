<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tax".
 *
 * @property integer $tax_id
 * @property string $tax
 * @property string $tax_rate
 * @property string $record_status
 */
class Tax extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tax';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tax'], 'required'],
            [['tax_rate'], 'number'],
            [['record_status'], 'string'],
            [['tax'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tax_id' => 'Tax ID',
            'tax' => 'Tax',
            'tax_rate' => 'Tax Rate',
            'record_status' => 'Record Status',
        ];
    }

    public function getTaxList() { // could be a static func as well
        $models = Tax::find()->asArray()->all();
        return ArrayHelper::map($models, 'tax_rate', 'tax_rate');
    }
}
