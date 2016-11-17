<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AccountType;

/**
 * AccountTypeSearch represents the model behind the search form about `app\models\AccountType`.
 */
class AccountTypeSearch extends AccountType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_type_id'], 'integer'],
            [['account_type'], 'safe'],
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
        $query = AccountType::find();

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
            'account_type_id' => $this->account_type_id,
        ]);

        $query->andFilterWhere(['like', 'account_type', $this->account_type]);

        return $dataProvider;
    }
}
