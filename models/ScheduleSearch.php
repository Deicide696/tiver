<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Schedule;

/**
 * ScheduleSearch represents the model behind the search form about `app\models\Schedule`.
 */
class ScheduleSearch extends Schedule
{
    
    public $expert_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'expert_id', 'weekday_id'], 'integer'],
            [['start_time', 'finish_time', 'date_created', 'expert_name'], 'safe'],
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
        $query = Schedule::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        		'sort'=> ['defaultOrder' => ['expert_id'=>SORT_ASC,'weekday_id'=>SORT_ASC]]
        ]);
        
        //$this->addCondition($query, 'expert_id');

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith('expert');
        $query->andFilterWhere([
            'id' => $this->id,
            'start_time' => $this->start_time,
            'finish_time' => $this->finish_time,
            'date_created' => $this->date_created,
            'expert_id' => $this->expert_id,
            'weekday_id' => $this->weekday_id,
        ]);
        $query->andFilterWhere(['like', 'expert.name', $this->expert_name]);

        return $dataProvider;
    }
}
