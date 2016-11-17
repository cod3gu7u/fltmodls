<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PurchaseOrderReceiveItem;

/**
 * PurchaseOrderReceiveItemSearch represents the model behind the search form of `app\models\PurchaseOrderReceiveItem`.
 */
class PurchaseOrderReceiveItemSearch extends PurchaseOrderReceiveItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['purchase_order_receive_item_id', 'purchase_order_line_item_id', 'received_units', 'create_user_id', 'update_user_id'], 'integer'],
            [['received_date', 'record_status', 'notes', 'create_date', 'update_date'], 'safe'],
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
        $query = PurchaseOrderReceiveItem::find();

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
            'purchase_order_receive_item_id' => $this->purchase_order_receive_item_id,
            'purchase_order_line_item_id' => $this->purchase_order_line_item_id,
            'received_units' => $this->received_units,
            'received_date' => $this->received_date,
            'create_user_id' => $this->create_user_id,
            'create_date' => $this->create_date,
            'update_user_id' => $this->update_user_id,
            'update_date' => $this->update_date,
        ]);

        $query->andFilterWhere(['like', 'record_status', $this->record_status])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}
