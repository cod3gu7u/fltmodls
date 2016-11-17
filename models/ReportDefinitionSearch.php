<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ReportDefinition;

/**
 * ReportDefinitionSearch represents the model behind the search form about `app\models\ReportDefinition`.
 */
class ReportDefinitionSearch extends ReportDefinition
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'report_header_id', 'account_type_id', 'parent_id', 'order_id'], 'integer'],
            [['name', 'side'], 'safe'],
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
        $query = ReportDefinition::find();

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
            'id' => $this->id,
            'report_header_id' => $this->report_header_id,
            'account_type_id' => $this->account_type_id,
            'parent_id' => $this->parent_id,
            'order_id' => $this->order_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'side', $this->side]);

        return $dataProvider;
    }
}
