<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ServiceMovementType;

/**
 * ServiceMovementTypeSearch represents the model behind the search form about `app\models\ServiceMovementType`.
 */
class ServiceMovementTypeSearch extends ServiceMovementType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['movement_type_id', 'movement_type'], 'integer'],
            [['notes', 'record_status'], 'safe'],
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
        $query = ServiceMovementType::find();

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
            'movement_type_id' => $this->movement_type_id,
            'movement_type' => $this->movement_type,
        ]);

        $query->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['like', 'record_status', $this->record_status]);

        return $dataProvider;
    }
}
