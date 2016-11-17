<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BankTransfer;

/**
 * BankTransferSearch represents the model behind the search form about `app\models\BankTransfer`.
 */
class BankTransferSearch extends BankTransfer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bank_transfer_id', 'source_bank_id', 'destination_bank_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['amount'], 'number'],
            [['transfer_date', 'notes', 'record_status', 'create_date', 'update_date'], 'safe'],
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
        $query = BankTransfer::find();

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
            'bank_transfer_id' => $this->bank_transfer_id,
            'source_bank_id' => $this->source_bank_id,
            'destination_bank_id' => $this->destination_bank_id,
            'amount' => $this->amount,
            'transfer_date' => $this->transfer_date,
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
