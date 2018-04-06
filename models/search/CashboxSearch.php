<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cashbox;

/**
 * CashboxSearch represents the model behind the search form about `app\models\Cashbox`.
 */
class CashboxSearch extends Cashbox
{
    public function rules()
    {
        return [
            [['id', 'logo'], 'integer'],
            [['name', 'front_name', 'link', 'phone'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Cashbox::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'logo' => $this->logo,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'front_name', $this->front_name])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }
}
