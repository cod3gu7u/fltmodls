<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bankbook;

/**
 * BankbookSearch represents the model behind the search form about `app\models\Bankbook`.
 */
class BankbookSearch extends Bankbook
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bankbook_entry_id', 'bank_id', 'accounting_period_id', 'transaction_type_id', 'account_id', 'payer_payee_id', 'tax_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['transaction_date', 'reference_number', 'notes', 'record_status', 'create_date', 'update_date'], 'safe'],
            [['exclusive_amount', 'tax_rate', 'tax_amount', 'total_amount'], 'number'],
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
        $query = Bankbook::find();

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
            'bankbook_entry_id' => $this->bankbook_entry_id,
            'bank_id' => $this->bank_id,
            'accounting_period_id' => $this->accounting_period_id,
            'transaction_date' => $this->transaction_date,
            'transaction_type_id' => $this->transaction_type_id,
            'account_id' => $this->account_id,
            'payer_payee_id' => $this->payer_payee_id,
            'exclusive_amount' => $this->exclusive_amount,
            'tax_id' => $this->tax_id,
            'tax_rate' => $this->tax_rate,
            'tax_amount' => $this->tax_amount,
            'total_amount' => $this->total_amount,
            'create_user_id' => $this->create_user_id,
            'create_date' => $this->create_date,
            'update_user_id' => $this->update_user_id,
            'update_date' => $this->update_date,
        ]);

        $query->andFilterWhere(['like', 'reference_number', $this->reference_number])
            ->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['like', 'record_status', $this->record_status]);

        return $dataProvider;
    }
}
