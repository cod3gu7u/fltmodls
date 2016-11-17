<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "batch".
 *
 * @property integer $batch_id
 * @property string $batch_name
 * @property string $batch_date
 *
 * @property Journal[] $journals
 */
class Batch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'batch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['batch_name', 'batch_date'], 'required'],
            [['batch_date'], 'safe'],
            [['batch_name'], 'string', 'max' => 50],
            [['batch_date'], 'default', 'value'=>date('Y-m-d')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'batch_id' => 'Batch ID',
            'batch_name' => 'Batch Name',
            'batch_date' => 'Batch Date',
        ];
    }

    /**
    * Create a new batch each day.
    * @return int batch_id
    */
    public static function createBatch($batch_prefix = '')
    {
        // Today's batch
        $batch_name = $batch_prefix . date('Ymd') . '-' . \Yii::$app->user->identity->id;
        $batch = Batch::findOne(['batch_name' => $batch_name]);
        if($batch === null){
            $batch = new Batch();
            $batch->loadDefaultValues();
            $batch->batch_name = $batch_name;
            $batch->batch_date = date('Y-m-d');
            $batch->save();
        }
        return $batch->batch_id;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJournals()
    {
        return $this->hasMany(Journal::className(), ['batch_id' => 'batch_id']);
    }
}
