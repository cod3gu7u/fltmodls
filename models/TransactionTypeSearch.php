<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TransactionType;

/**
 * TransactionTypeSearch represents the model behind the search form about `app\models\TransactionType`.
 */
class TransactionTypeSearch extends TransactionType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transaction_id'], 'integer'],
            [['transaction_type', 'record_status'], 'safe'],
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
        $query = TransactionType::find();

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
            'transaction_id' => $this->transaction_id,
        ]);

        $query->andFilterWhere(['like', 'transaction_type', $this->transaction_type])
            ->andFilterWhere(['like', 'record_status', $this->record_status]);

        return $dataProvider;
    }
}
