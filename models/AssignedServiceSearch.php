<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AssignedService;

/**
 * AssignedServiceSearch represents the model behind the search form about `app\models\AssignedService`.
 */
class AssignedServiceSearch extends AssignedService
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'service_id', 'user_id', 'city_id', 'expert_id'], 'integer'],
            [['address', 'date', 'time', 'created_date'], 'safe'],
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
        $query = AssignedService::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['date'=>SORT_ASC,'time'=>SORT_ASC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        if(Yii::$app->user->can('super-admin')) {
            $query->andFilterWhere([
                'id' => $this->id,
                'state' => $this->state,
                'date' => $this->date,
                'time' => $this->time,
                'created_date' => $this->created_date,
                'service_id' => $this->service_id,
                'user_id' => $this->user_id,
                'city_id' => $this->city_id,
                'expert_id' => $this->expert_id,
            ]);
            
        }else{
            $query->andFilterWhere([
                'id' => $this->id,
                'state' => $this->state,
                'date' => $this->date,
                'time' => $this->time,
                'created_date' => $this->created_date,
                'service_id' => $this->service_id,
                'user_id' => $this->user_id,
                'city_id' => $this->city_id,
                'expert_id' => $this->expert_id,
            ]);
        }

        $query->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
