<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ServiceVehicleMovement;

/**
 * ServiceVehicleMovementSearch represents the model behind the search form about `app\models\ServiceVehicleMovement`.
 */
class ServiceVehicleMovementSearch extends ServiceVehicleMovement
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['movement_id', 'vehicle_id', 'movement_type_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['movement_date', 'notes', 'record_status', 'create_date', 'update_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ServiceVehicleMovement::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'movement_id' => $this->movement_id,
            'vehicle_id' => $this->vehicle_id,
            'movement_type_id' => $this->movement_type_id,
            'movement_date' => $this->movement_date,
            'create_user_id' => $this->create_user_id,
            'create_date' => $this->create_date,
            'update_user_id' => $this->update_user_id,
            'update_date' => $this->update_date,
        ]);

        $query->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['like', 'record_status', $this->record_status]);

        return $dataProvider;
    }
}
