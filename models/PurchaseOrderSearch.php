<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PurchaseOrder;

/**
 * PurchaseOrderSearch represents the model behind the search form about `app\models\PurchaseOrder`.
 */
class PurchaseOrderSearch extends PurchaseOrder
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['purchase_order_id', 'creditor_id', 'order_status_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['order_date', 'shipping_date', 'notes', 'record_status', 'create_date', 'update_date'], 'safe'],
            [['total_amount'], 'number'],
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
        $query = PurchaseOrder::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        
        if(isset($_GET['PurchaseOrderSearch']) && !($this->load($params) && $this->validate()))
        {
            return $dataProvider;
        }
        // if (!$this->validate()) {
        //     // uncomment the following line if you do not want to return any records when validation fails
        //     // $query->where('0=1');
        //     return $dataProvider;
        // }

        // grid filtering conditions
        $query->andFilterWhere([
            'purchase_order_id' => $this->purchase_order_id,
            'creditor_id' => $this->creditor_id,
            'order_date' => $this->order_date,
            'shipping_date' => $this->shipping_date,
            'total_amount' => $this->total_amount,
            'order_status_id' => $this->order_status_id,
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
