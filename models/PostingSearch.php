<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Posting;

/**
 * PostingSearch represents the model behind the search form about `app\models\Posting`.
 */
class PostingSearch extends Posting
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['posting_sequence_id', 'account_id', 'journal_id', 'accounting_period_id', 'asset_type_id', 'quantity', 'posting_status', 'journal_owner_id', 'posting_user_id'], 'integer'],
            [['unit_amount', 'total_amount'], 'number'],
            [['posting_date'], 'safe'],
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
        $query = Posting::find();

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
            'posting_sequence_id' => $this->posting_sequence_id,
            'account_id' => $this->account_id,
            'journal_id' => $this->journal_id,
            'accounting_period_id' => $this->accounting_period_id,
            'asset_type_id' => $this->asset_type_id,
            'quantity' => $this->quantity,
            'unit_amount' => $this->unit_amount,
            'total_amount' => $this->total_amount,
            'posting_status' => $this->posting_status,
            'journal_owner_id' => $this->journal_owner_id,
            'posting_date' => $this->posting_date,
            'posting_user_id' => $this->posting_user_id,
        ]);

        return $dataProvider;
    }
}
