<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VehicleMake;

/**
 * VehicleMakeSearch represents the model behind the search form about `app\models\VehicleMake`.
 */
class VehicleMakeSearch extends VehicleMake
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_make_id'], 'integer'],
            [['make', 'notes', 'record_status'], 'safe'],
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
        $query = VehicleMake::find();

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
            'vehicle_make_id' => $this->vehicle_make_id,
        ]);

        $query->andFilterWhere(['like', 'make', $this->make])
            ->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['like', 'record_status', $this->record_status]);

        return $dataProvider;
    }
}
