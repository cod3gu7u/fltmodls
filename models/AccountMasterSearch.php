<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AccountMaster;

/**
 * AccountMasterSearch represents the model behind the search form about `app\models\AccountMaster`.
 */
class AccountMasterSearch extends AccountMaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'root', 'lft', 'rgt', 'lvl', 'account_type_id', 'icon_type', 'active', 'selected', 'disabled', 'readonly', 'visible', 'collapsed', 'movable_u', 'movable_d', 'movable_l', 'movable_r', 'removable', 'removable_all'], 'integer'],
            [['name', 'icon'], 'safe'],
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
        $query = AccountMaster::find();

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
            'account_id' => $this->account_id,
            'root' => $this->root,
            'lft' => $this->lft,
            'rgt' => $this->rgt,
            'lvl' => $this->lvl,
            'account_type_id' => $this->account_type_id,
            'icon_type' => $this->icon_type,
            'active' => $this->active,
            'selected' => $this->selected,
            'disabled' => $this->disabled,
            'readonly' => $this->readonly,
            'visible' => $this->visible,
            'collapsed' => $this->collapsed,
            'movable_u' => $this->movable_u,
            'movable_d' => $this->movable_d,
            'movable_l' => $this->movable_l,
            'movable_r' => $this->movable_r,
            'removable' => $this->removable,
            'removable_all' => $this->removable_all,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'icon', $this->icon]);

        return $dataProvider;
    }
}
