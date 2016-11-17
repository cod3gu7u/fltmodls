<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\JournalType;

/**
 * JournalTypeSearch represents the model behind the search form about `app\models\JournalType`.
 */
class JournalTypeSearch extends JournalType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['journal_type_id'], 'integer'],
            [['journal_type'], 'safe'],
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
        $query = JournalType::find();

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
            'journal_type_id' => $this->journal_type_id,
        ]);

        $query->andFilterWhere(['like', 'journal_type', $this->journal_type]);

        return $dataProvider;
    }
}
