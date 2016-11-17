<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BankBalance;

/**
 * BankBalanceSearch represents the model behind the search form about `app\models\BankBalance`.
 */
class BankBalanceSearch extends BankBalance
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bank_balance_id', 'bank_id', 'creator_id', 'updater_id'], 'integer'],
            [['start_date', 'end_date', 'notes', 'create_date', 'update_date'], 'safe'],
            [['opening_balance', 'closing_balance'], 'number'],
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
        $query = BankBalance::find();

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
            'bank_balance_id' => $this->bank_balance_id,
            'bank_id' => $this->bank_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'opening_balance' => $this->opening_balance,
            'closing_balance' => $this->closing_balance,
            'creator_id' => $this->creator_id,
            'create_date' => $this->create_date,
            'updater_id' => $this->updater_id,
            'update_date' => $this->update_date,
        ]);

        $query->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}
