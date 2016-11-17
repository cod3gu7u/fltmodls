<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "journal_type".
 *
 * @property integer $journal_type_id
 * @property string $journal_type
 *
 * @property Journal[] $journals
 */
class JournalType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'journal_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['journal_type'], 'required'],
            [['journal_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'journal_type_id' => 'Journal Type ID',
            'journal_type' => 'Journal Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJournals()
    {
        return $this->hasMany(Journal::className(), ['journal_type_id' => 'journal_type_id']);
    }
}
