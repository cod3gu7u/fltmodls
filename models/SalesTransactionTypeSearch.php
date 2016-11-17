<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SalesTransactionType;

/**
 * SalesTransactionTypeSearch represents the model behind the search form about `app\models\SalesTransactionType`.
 */
class SalesTransactionTypeSearch extends SalesTransactionType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sales_transaction_type_id', 'debit_account_id', 'credit_account_id'], 'integer'],
            [['sales_transaction_type', 'notes', 'record_status'], 'safe'],
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
        $query = SalesTransactionType::find();

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
            'sales_transaction_type_id' => $this->sales_transaction_type_id,
            'debit_account_id' => $this->debit_account_id,
            'credit_account_id' => $this->credit_account_id,
        ]);

        $query->andFilterWhere(['like', 'sales_transaction_type', $this->sales_transaction_type])
            ->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['like', 'record_status', $this->record_status]);

        return $dataProvider;
    }
}
