<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sales;

/**
 * SalesSearch represents the model behind the search form about `app\models\Sales`.
 */
class SalesSearch extends Sales
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sales_id', 'sales_person_id', 'sales_status_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['vehicle_id', 'customer_id', 'sales_date', 'notes', 'record_status', 'create_date', 'update_date'], 'safe'],
            [['original_sales_amount', 'discount_amount', 'final_sales_amount', 'paid_amount'], 'number'],
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
        $query = Sales::find();

        // add conditions that should always apply here
        $this->sales_date = date('Y-m-d');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ( ! is_null($this->sales_date) && strpos($this->sales_date, ' - ') !== false ) {
            list($start_date, $end_date) = explode(' - ', $this->sales_date);
            $query->andFilterWhere(['between', 'sales_date', $start_date, $end_date]);
            $this->sales_date = null;
        }

        $query->joinWith('customer');
        $query->joinWith('vehicle');

        // grid filtering conditions
        $query->andFilterWhere([
            'sales_id' => $this->sales_id,
            // 'vehicle_id' => $this->vehicle_id,
            'sales_person_id' => $this->sales_person_id,
            // 'sales_date' => $this->sales_date,
            'original_sales_amount' => $this->original_sales_amount,
            'discount_amount' => $this->discount_amount,
            'final_sales_amount' => $this->final_sales_amount,
            'paid_amount' => $this->paid_amount,
            'void' => $this->void,
            'sales_status_id' => $this->sales_status_id,
            'create_user_id' => $this->create_user_id,
            'create_date' => $this->create_date,
            'update_user_id' => $this->update_user_id,
            'update_date' => $this->update_date,
        ]);

        $query->andFilterWhere(['like', 'notes', $this->notes])
          ->andFilterWhere(['like', 'customer.customer_name', $this->customer_id])
          ->andFilterWhere(['like', 'vehicle.reference_number', $this->vehicle_id]);
        // ->andFilterWhere(['like', 'record_status', $this->record_status]);
        //   if(isset($this->sales_date) && $this->sales_date!=''){
        //     $date_explode = explode("TO", $this->sales_date);
        //     $date1 = trim($date_explode[0]);
        //     $date2= trim($date_explode[1]);
        //     $query->andFilterWhere(['between', 'sales_date', $date1,$date2]);
        // }

        return $dataProvider;
    }
}
