<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SocialGroup;

/**
 * SocialGroupSearch represents the model behind the search form about `app\models\SocialGroup`.
 */
class SocialGroupSearch extends SocialGroup
{
    public function rules()
    {
        return [
            [['id', 'status', 'sort'], 'integer'],
            [['name', 'link', 'logo', 'logo_active'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = SocialGroup::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'logo_active', $this->logo_active]);

        return $dataProvider;
    }
}
