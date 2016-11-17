<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Journal;

/**
 * JournalSearch represents the model behind the search form about `app\models\Journal`.
 */
class JournalSearch extends Journal
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['journal_id', 'batch_id', 'journal_type_id'], 'integer'],
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
        $query = Journal::find();

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
            'journal_id' => $this->journal_id,
            'batch_id' => $this->batch_id,
            'journal_type_id' => $this->journal_type_id,
        ]);

        return $dataProvider;
    }
}
