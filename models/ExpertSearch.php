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
            [['id', 'phone', 'enable', 'zone_id', 'type_identification_id', 'rol_id', 'gender_id'], 'integer'],
            [['identification', 'name', 'last_name', 'email', 'password', 'address', 'created_date'], 'safe'],
       
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

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
 
        		'sort'=> ['defaultOrder' => ['enable'=>SORT_DESC,'zone_id'=>SORT_ASC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'phone' => $this->phone,
            'enable' => $this->enable,
            'created_date' => $this->created_date,
            'zone_id' => $this->zone_id,
            'type_identification_id' => $this->type_identification_id,
            'rol_id' => $this->rol_id,
            'gender_id' => $this->gender_id,
        ]);

        $query->andFilterWhere(['like', 'Identity', $this->identification])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
