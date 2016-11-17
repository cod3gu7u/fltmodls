<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ReferenceNumber;

/**
 * ReferenceNumberSearch represents the model behind the search form about `app\models\ReferenceNumber`.
 */
class ReferenceNumberSearch extends ReferenceNumber
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reference_prefix', 'record_status'], 'safe'],
            [['reference_counter', 'notes'], 'integer'],
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
        $query = ReferenceNumber::find();

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
            'reference_counter' => $this->reference_counter,
            'notes' => $this->notes,
        ]);

        $query->andFilterWhere(['like', 'reference_prefix', $this->reference_prefix])
            ->andFilterWhere(['like', 'record_status', $this->record_status]);

        return $dataProvider;
    }
}
