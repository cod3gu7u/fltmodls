<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "creditor".
 *
 * @property integer $creditor_id
 * @property string $creditor
 * @property integer $creditor_type_id
 * @property string $address
 * @property string $telephone
 * @property string $email
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property CreditorType $creditorType
 * @property Expense[] $expenses 
 * @property Service[] $services 
 * @property Vehicle[] $vehicles 
 * @property VehicleCosting[] $vehicleCostings 
 */
class Creditor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'creditor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['creditor', 'creditor_type_id', 'create_user_id', 'create_date'], 'required'],
            [['creditor_type_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['notes', 'record_status'], 'string'],
            [['create_date', 'update_date'], 'safe'],
            [['creditor', 'address'], 'string', 'max' => 250],
            [['telephone'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 100],
            [['creditor_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CreditorType::className(), 'targetAttribute' => ['creditor_type_id' => 'creditor_type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'creditor_id' => 'Creditor ID',
            'creditor' => 'Creditor',
            'creditor_type_id' => 'Creditor Type',
            'address' => 'Address',
            'telephone' => 'Telephone',
            'email' => 'Email',
            'notes' => 'Notes',
            'record_status' => 'Record Status',
            'create_user_id' => 'Create User ID',
            'create_date' => 'Create Date',
            'update_user_id' => 'Update User ID',
            'update_date' => 'Update Date',
        ];
    }

       /**
    * @return \yii\db\ActiveQuery
    */
   public function getExpenses()
   {
       return $this->hasMany(Expense::className(), ['creditor_id' => 'creditor_id']);
   }
   /**
    * @return \yii\db\ActiveQuery
    */
   public function getServices()
   {
       return $this->hasMany(Service::className(), ['creditor_id' => 'creditor_id']);
   }
   /**
    * @return \yii\db\ActiveQuery
    */
   public function getVehicles()
   {
       return $this->hasMany(Vehicle::className(), ['supplier_id' => 'creditor_id']);
   }
   /**
    * @return \yii\db\ActiveQuery
    */
   public function getVehicleCostings()
   {
       return $this->hasMany(VehicleCosting::className(), ['creditor_id' => 'creditor_id']);
   }
   
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreditorType()
    {
        return $this->hasOne(CreditorType::className(), ['creditor_type_id' => 'creditor_type_id']);
    }

    // Get location list
    public function getCreditorList() { // could be a static func as well
        $models = Creditor::find()->asArray()->all();
        return ArrayHelper::map($models, 'creditor_id', 'creditor');
    }

    // Get location list
    public function getCreditorTypeList() { // could be a static func as well
        $models = CreditorType::find()->asArray()->all();
        return ArrayHelper::map($models, 'creditor_type_id', 'creditor_type');
    }
}
