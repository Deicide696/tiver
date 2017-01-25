<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Team;

/**
 * TeamSearch represents the model behind the search form about `app\models\Team`.
 */
class TeamSearch extends Team
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'enable'], 'integer'],
            [['name', 'description', 'created_date', 'updated_date'], 'safe'],
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
        $query = Team::find();

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
        if(Yii::$app->user->can('super-admin')) {
            $query->andFilterWhere([
                'id' => $this->id,
                'enable' => $this->enable,
                'created_date' => $this->created_date,
                'updated_date' => $this->updated_date,
            ]);
        }else{
            $query->andFilterWhere([
                'id' => $this->id,
                'enable' => $this->enable,
                'created_date' => $this->created_date,
                'updated_date' => $this->updated_date,
                'enable' => 1,
            ]);
        }
        
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
