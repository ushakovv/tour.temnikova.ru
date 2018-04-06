<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VkAlbum;

/**
 * VkAlbumSearch represents the model behind the search form about `app\models\VkAlbum`.
 */
class VkAlbumSearch extends VkAlbum
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'album_id'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = VkAlbum::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'album_id', $this->album_id]);

        return $dataProvider;
    }
}
