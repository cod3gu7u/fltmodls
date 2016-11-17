<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cashbook;

/**
 * CashbookSearch represents the model behind the search form about `app\models\Cashbook`.
 */
class CashbookSearch extends Cashbook
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cashbook_entry_id', 'bank_id', 'accounting_period_id', 'transaction_type_id', 'account_id', 'tax_id'], 'integer'],
            [['transaction_date', 'reference_number', 'notes'], 'safe'],
            [['exclusive_amount', 'tax_amount', 'total_amount'], 'number'],
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
        $query = Cashbook::find();

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
            'cashbook_entry_id' => $this->cashbook_entry_id,
            'bank_id' => $this->bank_id,
            'accounting_period_id' => $this->accounting_period_id,
            'transaction_date' => $this->transaction_date,
            'transaction_type_id' => $this->transaction_type_id,
            'account_id' => $this->account_id,
            'exclusive_amount' => $this->exclusive_amount,
            'tax_id' => $this->tax_id,
            'tax_amount' => $this->tax_amount,
            'total_amount' => $this->total_amount,
        ]);

        $query->andFilterWhere(['like', 'reference_number', $this->reference_number])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}
