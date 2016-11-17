<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Batch;

/**
 * BatchSearch represents the model behind the search form about `app\models\Batch`.
 */
class BatchSearch extends Batch
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['batch_id'], 'integer'],
            [['batch_name', 'batch_date'], 'safe'],
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
        $query = Batch::find();

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
            'batch_id' => $this->batch_id,
            'batch_date' => $this->batch_date,
        ]);

        $query->andFilterWhere(['like', 'batch_name', $this->batch_name]);

        return $dataProvider;
    }
}
