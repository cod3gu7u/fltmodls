<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document_counters".
 *
 * @property string $prefix
 * @property integer $counter
 * @property string $notes
 * @property string $record_status
 */
class DocumentCounters extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document_counters';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prefix', 'counter'], 'required'],
            [['counter'], 'integer'],
            [['notes', 'record_status'], 'string'],
            [['prefix'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prefix' => 'Prefix',
            'counter' => 'Counter',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
        ];
    }

    public function nextCounters($prefix)
    {   
        $model = DocumentCounters::find(['prefix' => $prefix])->one();
        $model->updateCounters(['counter' => 1]);
        return $model->counter;
    }
}
