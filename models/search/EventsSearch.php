<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Events;

/**
 * EventsSearch represents the model behind the search form about `app\models\Events`.
 */
class EventsSearch extends Events
{
    public function rules()
    {
        return [
            [['id', 'tour_id', 'city_id', 'was', 'status', 'sort'], 'integer'],
            [['tour_desc', 'tour_dt', 'image', 'map_x', 'map_y', 'vk_event', 'p_alias', 'p_dt', 'p_time', 'p_auth'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Events::find()->orderBy(['tour_dt' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        /**
         * Настройка параметров сортировки
         * Важно: должна быть выполнена раньше $this->load($params)
         * statement below
         */
        /*$dataProvider->setSort([
            'attributes' => [
                'id',
                'name' => [
                    'asc' => ['city.name' => SORT_ASC],
                    'desc' => ['city.name' => SORT_DESC],
                    'label' => 'Город'
                ]
            ]
        ]);*/

        if (!($this->load($params) && $this->validate())) {
            //$query->joinWith(['city']);
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'tour_id' => $this->tour_id,
            'city_id' => $this->city_id,
            'was' => $this->was,
            'status' => $this->status,
            'sort' => $this->sort,
            'p_dt' => $this->p_dt,
            'p_time' => $this->p_time,
        ]);

        $query->andFilterWhere(['like', 'tour_desc', $this->tour_desc])
            ->andFilterWhere(['like', 'tour_dt', $this->tour_dt])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'map_x', $this->map_x])
            ->andFilterWhere(['like', 'map_y', $this->map_y])
            ->andFilterWhere(['like', 'vk_event', $this->vk_event])
            ->andFilterWhere(['like', 'p_alias', $this->p_alias])
            ->andFilterWhere(['like', 'p_auth', $this->p_auth]);

        return $dataProvider;
    }
}
