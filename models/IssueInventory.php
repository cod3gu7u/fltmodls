<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "issue_inventory".
 *
 * @property integer $issue_inventory_id
 * @property integer $vehicle_id
 * @property string $issue_date
 * @property string $notes
 * @property string $record_status
 * @property integer $create_user_id
 * @property string $create_date
 * @property integer $update_user_id
 * @property string $update_date
 *
 * @property Vehicle $vehicle
 * @property IssueInventoryLineItem[] $issueInventoryLineItems
 */
class IssueInventory extends \yii\db\ActiveRecord
{
    public $vehicle_details;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'issue_inventory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_id', 'issue_date', 'create_user_id', 'create_date'], 'required'],
            [['vehicle_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['issue_date', 'create_date', 'update_date'], 'safe'],
            [['vehicle_details', 'notes', 'record_status'], 'string'],
            [['vehicle_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vehicle::className(), 'targetAttribute' => ['vehicle_id' => 'vehicle_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'issue_inventory_id' => 'Issue Inventory ID',
            'vehicle_id' => 'Vehicle',
            'issue_date' => 'Issue Date',
            'vehicle_details' => 'Vehicle Details',
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
    public function getVehicle()
    {
        return $this->hasOne(Vehicle::className(), ['vehicle_id' => 'vehicle_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssueInventoryLineItems()
    {
        return $this->hasMany(IssueInventoryLineItem::className(), ['issue_inventory_id' => 'issue_inventory_id']);
    }
}
