<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asset_type".
 *
 * @property integer $asset_type_id
 * @property string $asset_type
 *
 * @property Posting[] $postings
 */
class AssetType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'asset_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['asset_type'], 'required'],
            [['asset_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'asset_type_id' => 'Asset Type ID',
            'asset_type' => 'Asset Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostings()
    {
        return $this->hasMany(Posting::className(), ['asset_type_id' => 'asset_type_id']);
    }
}
