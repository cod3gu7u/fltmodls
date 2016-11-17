<?php
namespace app\models;

use Yii;

class Reporting extends \yii\base\Model
{
    public $start_date;
    public $end_date;
    public $param;
    public $controller_path;

    public function rules()
    {
        return [
            // define validation rules here
            [['start_date', 'end_date'], 'safe'],
            [['param'], 'string', 'max' => 50],
            [['controller_path'], 'string', 'max' => 250],

        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'param' => 'Parameter',
            'controller_path' => 'Controller Path',
        ];
    }
}

?>