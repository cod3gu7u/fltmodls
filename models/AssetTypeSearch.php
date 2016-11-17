<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AssetType;

/**
 * AssetTypeSearch represents the model behind the search form about `app\models\AssetType`.
 */
class AssetTypeSearch extends AssetType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['asset_type_id'], 'integer'],
            [['asset_type'], 'safe'],
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
        $query = AssetType::find();

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
            'asset_type_id' => $this->asset_type_id,
        ]);

        $query->andFilterWhere(['like', 'asset_type', $this->asset_type]);

        return $dataProvider;
    }
}
