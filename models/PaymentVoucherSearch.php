<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PaymentVoucher;

/**
 * PaymentVoucherSearch represents the model behind the search form about `app\models\PaymentVoucher`.
 */
class PaymentVoucherSearch extends PaymentVoucher
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_voucher_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['creditor_id', 'bank_id', 'payment_method_id', 'pv_number', 'pv_date', 'cheque_no', 'notes', 'record_status', 'create_date', 'update_date'], 'safe'],
            [['amount', 'tax_rate', 'final_amount'], 'number'],
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
        $query = PaymentVoucher::find();

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

        $query->joinWith('creditor');
        $query->joinWith('bank');
        $query->joinWith('paymentMethod');


        // grid filtering conditions
        $query->andFilterWhere([
            'payment_voucher_id' => $this->payment_voucher_id,
            // 'creditor_id' => $this->creditor_id,
            'pv_date' => $this->pv_date,
            // 'payment_method_id' => $this->payment_method_id,
            'amount' => $this->amount,
            'tax_rate' => $this->tax_rate,
            'final_amount' => $this->final_amount,
            'create_user_id' => $this->create_user_id,
            'create_date' => $this->create_date,
            'update_user_id' => $this->update_user_id,
            'update_date' => $this->update_date,
        ]);

        $query->andFilterWhere(['like', 'pv_number', $this->pv_number])
            ->andFilterWhere(['like', 'cheque_no', $this->cheque_no])
            // ->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['like', 'record_status', $this->record_status])
            ->andFilterWhere(['like', 'creditor.creditor', $this->creditor_id])
            ->andFilterWhere(['like', 'paymentMethod.payment_method', $this->payment_method_id])
            ->andFilterWhere(['like', 'bank.bank', $this->bank_id]);


        return $dataProvider;
    }
}
