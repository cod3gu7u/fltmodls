<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VehicleCosting;

/**
 * VehicleCostingSearch represents the model behind the search form about `app\models\VehicleCosting`.
 */
class VehicleCostingSearch extends VehicleCosting
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_costing_id', 'creditor_id', 'vehicle_id', 'cost_category_id', 'currency_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['cost_date', 'notes', 'record_status', 'create_date', 'update_date'], 'safe'],
            [['transaction_amount', 'exchange_rate', 'total_amount'], 'number'],
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
        $query = VehicleCosting::find();

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
            'vehicle_costing_id' => $this->vehicle_costing_id,
            'creditor_id' => $this->creditor_id,
            'vehicle_id' => $this->vehicle_id,
            'cost_category_id' => $this->cost_category_id,
            'cost_date' => $this->cost_date,
            'currency_id' => $this->currency_id,
            'transaction_amount' => $this->transaction_amount,
            'exchange_rate' => $this->exchange_rate,
            'total_amount' => $this->total_amount,
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
