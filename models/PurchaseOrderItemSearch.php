<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PurchaseOrderItem;

/**
 * PurchaseOrderItemSearch represents the model behind the search form about `app\models\PurchaseOrderItem`.
 */
class PurchaseOrderItemSearch extends PurchaseOrderItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['purchase_order_item_id', 'instock_count', 'reorder_level', 'unit_of_measure_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['purchase_order_item', 'notes', 'record_status', 'create_date', 'update_date'], 'safe'],
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
        $query = PurchaseOrderItem::find();

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
            'purchase_order_item_id' => $this->purchase_order_item_id,
            'instock_count' => $this->instock_count,
            'reorder_level' => $this->reorder_level,
            'unit_of_measure_id' => $this->unit_of_measure_id,
            'create_user_id' => $this->create_user_id,
            'create_date' => $this->create_date,
            'update_user_id' => $this->update_user_id,
            'update_date' => $this->update_date,
        ]);

        $query->andFilterWhere(['like', 'purchase_order_item', $this->purchase_order_item])
            ->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['like', 'record_status', $this->record_status]);

        return $dataProvider;
    }
}
