<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Expert;

/**
 * ExpertSearch represents the model behind the search form about `app\models\Expert`.
 */
class ExpertSearch extends Expert
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'zone_id', 'type_identification_id', 'gender_id', 'phone', 'enable'], 'integer'],
            [['identification', 'name', 'last_name', 'email', 'password', 'address', 'path', 'created_date', 'updated_date'], 'safe'],
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
        $query = Expert::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['enable' => SORT_DESC,'zone_id' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'zone_id' => $this->zone_id,
            'type_identification_id' => $this->type_identification_id,
            'gender_id' => $this->gender_id,
            'phone' => $this->phone,
            'created_date' => $this->created_date,
            'updated_date' => $this->updated_date,
            'enable' => $this->enable,
        ]);

        $query->andFilterWhere(['like', 'identification', $this->identification])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'path', $this->path]);

        return $dataProvider;
    }
}
