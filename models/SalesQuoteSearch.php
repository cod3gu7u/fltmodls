<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SalesQuote;

/**
 * SalesQuoteSearch represents the model behind the search form about `app\models\SalesQuote`.
 */
class SalesQuoteSearch extends SalesQuote
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sales_quote_id', 'vehicle_id', 'customer_id', 'sales_person_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['quote_issue_date', 'quote_expiry_date', 'notes', 'record_status', 'create_date', 'update_date'], 'safe'],
            [['quoted_amount'], 'number'],
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
        $query = SalesQuote::find();

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
            'sales_quote_id' => $this->sales_quote_id,
            'vehicle_id' => $this->vehicle_id,
            'customer_id' => $this->customer_id,
            'sales_person_id' => $this->sales_person_id,
            'quote_issue_date' => $this->quote_issue_date,
            'quote_expiry_date' => $this->quote_expiry_date,
            'quoted_amount' => $this->quoted_amount,
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
