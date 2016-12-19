<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LogAssignedService;

/**
 * LogAssignedServiceSearch represents the model behind the search form about `app\models\LogAssignedService`.
 */
class LogAssignedServiceSearch extends LogAssignedService
{
    /**
     * @inheritdoc
     */
    
    public $userFirstName;
    public function rules()
    {
        return [
            [['id', 'assigned', 'rejected', 'missed', 'accepted', 'attempt'], 'integer'],
            [['date', 'time', 'created_date', 'expert_id', 'assigned_service_id','userFirstName'], 'safe'],
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
        $query = LogAssignedService::find();
        $query->joinWith(['expert','serviceHistory']);
        $query->joinWith(['serviceHistory.user']);
//        var_dump($query);die();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
//        var_dump($query->expert);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'assigned' => $this->assigned,
            'rejected' => $this->rejected,
            'missed' => $this->missed,
            'accepted' => $this->accepted,
            'date' => $this->date,
            'time' => $this->time,
            'attempt' => $this->attempt,
            'created_date' => $this->created_date,
//            'assigned_service_id' => $this->assigned_service_id,
        ]);
        $query->andFilterWhere(['like', 'expert.name', $this->expert_id])
                ->andFilterWhere(['like', 'serviceHistory.address', $this->assigned_service_id])
                ->andFilterWhere(['like', 'user.first_name', $this->userFirstName]);

        return $dataProvider;
    }
}
