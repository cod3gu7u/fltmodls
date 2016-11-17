<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PurchaseOrderStatus;

/**
 * PurchaseOrderStatusSearch represents the model behind the search form about `app\models\PurchaseOrderStatus`.
 */
class PurchaseOrderStatusSearch extends PurchaseOrderStatus
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['purchase_order_status_id'], 'integer'],
            [['purchase_order_status', 'notes', 'record_status'], 'safe'],
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
        $query = PurchaseOrderStatus::find();

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
            'purchase_order_status_id' => $this->purchase_order_status_id,
        ]);

        $query->andFilterWhere(['like', 'purchase_order_status', $this->purchase_order_status])
            ->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['like', 'record_status', $this->record_status]);

        return $dataProvider;
    }
}
