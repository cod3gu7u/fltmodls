<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PurchaseOrderLineItem;

/**
 * PurchaseOrderLineItemSearch represents the model behind the search form about `app\models\PurchaseOrderLineItem`.
 */
class PurchaseOrderLineItemSearch extends PurchaseOrderLineItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['purchase_order_line_item_id', 'purchase_order_id', 'purchase_order_item_id', 'units', 'create_user_id', 'update_user_id'], 'integer'],
            [['unit_cost', 'tax_rate', 'tax_amount', 'total_amount'], 'number'],
            [['notes', 'record_status', 'create_date', 'update_date'], 'safe'],
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
        $query = PurchaseOrderLineItem::find();

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
            'purchase_order_line_item_id' => $this->purchase_order_line_item_id,
            'purchase_order_id' => $this->purchase_order_id,
            'purchase_order_item_id' => $this->purchase_order_item_id,
            'unit_cost' => $this->unit_cost,
            'units' => $this->units,
            'tax_rate' => $this->tax_rate,
            'tax_amount' => $this->tax_amount,
            'total_amount' => $this->total_amount,
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
