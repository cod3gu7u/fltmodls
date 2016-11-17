<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AccountingPeriod;

/**
 * AccountingPeriodSearch represents the model behind the search form about `app\models\AccountingPeriod`.
 */
class AccountingPeriodSearch extends AccountingPeriod
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accounting_period_id', 'status'], 'integer'],
            [['accounting_period', 'start_date', 'end_date', 'record_status'], 'safe'],
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
        $query = AccountingPeriod::find();

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
            'accounting_period_id' => $this->accounting_period_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'accounting_period', $this->accounting_period])
            ->andFilterWhere(['like', 'record_status', $this->record_status]);

        return $dataProvider;
    }
}
