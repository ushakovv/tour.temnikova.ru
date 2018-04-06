<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\HeaderItem;

/**
 * HeaderItemSearch represents the model behind the search form about `app\models\HeaderItem`.
 */
class HeaderItemSearch extends HeaderItem
{
    public function rules()
    {
        return [
            [['id', 'id_group', 'enabled', 'ord'], 'integer'],
            [['name', 'title', 'logo', 'link'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = HeaderItem::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_group' => $this->id_group,
            'enabled' => $this->enabled,
            'ord' => $this->ord,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'link', $this->link]);

        return $dataProvider;
    }
}
