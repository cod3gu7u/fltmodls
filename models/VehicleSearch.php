<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Vehicle;

/**
 * VehicleSearch represents the model behind the search form about `app\models\Vehicle`.
 */
class VehicleSearch extends Vehicle
{
    // data search element
    public $stock_status;
    public $vehicle_make;
    public $global_search;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_id', 'location_id', 'vehicle_type_id', 'model_id', 'color_id', 'capacity', 'arrival_mileage', 'create_user_id', 'update_user_id'], 'integer'],
            [['global_search', 'vehicle_make', 'reference_number', 'model_year', 'chassis', 'engine', 'arrival_date', 'stock_status', 'notes', 'record_status', 'create_date', 'update_date'], 'safe'],
            [['asking_price'], 'number'],
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
        $query = Vehicle::find();

        // add conditions that should always apply here
        $query->joinWith(['stockStatus']);
        $query->joinWith(['vehicleMake']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['stock_status'] = [
                'asc' =>  ['stockStatus.stock_status' => SORT_ASC],
                'desc' =>  ['stockStatus.stock_status' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['vehicle_make'] = [
                'asc' =>  ['vehicleMake.vehicle_make' => SORT_ASC],
                'desc' =>  ['vehicleMake.vehicle_make' => SORT_DESC],
        ];

        if(!($this->load($params) && $this->validate()))
        {
            return $dataProvider;
        }

        // if(isset($_GET['VehicleSearch']) && !($this->load($params) && $this->validate()))
        // {
        //     return $dataProvider;
        // }
        // $this->load($params);

        // if (!$this->validate()) {
        //     // uncomment the following line if you do not want to return any records when validation fails
        //     // $query->where('0=1');
        //     return $dataProvider;
        // }

        // grid filtering conditions
        $query->andFilterWhere([
            'vehicle_id' => $this->vehicle_id,
            'location_id' => $this->location_id,
            'make_id' => $this->make_id,
            'vehicle_type_id' => $this->vehicle_type_id,
            'model_id' => $this->model_id,
            'color_id' => $this->color_id,
            'capacity' => $this->capacity,
            'arrival_date' => $this->arrival_date,
            'arrival_mileage' => $this->arrival_mileage,
            // 'stock_status_id' => $this->stock_status_id,
            'asking_price' => $this->asking_price,
            'create_user_id' => $this->create_user_id,
            'create_date' => $this->create_date,
            'update_user_id' => $this->update_user_id,
            'update_date' => $this->update_date,
        ]);

        $query->andFilterWhere(['like', 'reference_number', $this->reference_number])
            ->andFilterWhere(['like', 'model_year', $this->model_year])
            ->andFilterWhere(['like', 'chassis', $this->chassis])
            ->andFilterWhere(['like', 'engine', $this->engine])
            ->andFilterWhere(['like', 'stockStatus.stock_status_id', $this->stock_status_id])
            ->andFilterWhere(['like', 'vehicleMake.vehicle_make_id', $this->make_id])
            ->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['like', 'record_status', $this->record_status]);

        $query->orFilterWhere(['like', 'reference_number', $this->global_search])
            ->orFilterWhere(['like', 'model_year', $this->global_search])
            ->orFilterWhere(['like', 'chassis', $this->global_search])
            ->orFilterWhere(['like', 'engine', $this->global_search]);

        return $dataProvider;
    }
}
