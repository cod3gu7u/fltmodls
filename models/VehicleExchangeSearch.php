<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VehicleExchange;

/**
 * VehicleExchangeSearch represents the model behind the search form about `app\models\VehicleExchange`.
 */
class VehicleExchangeSearch extends VehicleExchange
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_exchange_id', 'original_sales_id', 'original_vehicle_id', 'new_vehicle_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['new_sales_amount'], 'number'],
            [['exchange_date', 'notes', 'record_status', 'create_date', 'update_date'], 'safe'],
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
        $query = VehicleExchange::find();

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
            'vehicle_exchange_id' => $this->vehicle_exchange_id,
            'original_sales_id' => $this->original_sales_id,
            'original_vehicle_id' => $this->original_vehicle_id,
            'new_vehicle_id' => $this->new_vehicle_id,
            'new_sales_amount' => $this->new_sales_amount,
            'exchange_date' => $this->exchange_date,
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
