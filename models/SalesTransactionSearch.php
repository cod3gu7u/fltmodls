<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SalesTransaction;

/**
 * SalesTransactionSearch represents the model behind the search form about `app\models\SalesTransaction`.
 */
class SalesTransactionSearch extends SalesTransaction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sales_transaction_id', 'sales_id', 'sales_transaction_type_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['transaction_date', 'notes', 'record_status', 'create_date', 'update_date'], 'safe'],
            [['transaction_amount'], 'number'],
            [['void'], 'boolean'],
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
        $query = SalesTransaction::find();

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
            'sales_transaction_id' => $this->sales_transaction_id,
            'sales_id' => $this->sales_id,
            'transaction_date' => $this->transaction_date,
            'sales_transaction_type_id' => $this->sales_transaction_type_id,
            'transaction_amount' => $this->transaction_amount,
            'void' => $this->void,
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
