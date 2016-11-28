<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ServiceHistory;

/**
 * ServiceHistorySearch represents the model behind the search form about `app\models\ServiceHistory`.
 */
class ServiceHistorySearch extends ServiceHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'state', 'expert_id', 'cancel_reason_id', 'problem_type_id', 'service_id', 'user_id', 'coupon_id'], 'integer'],
            [['address', 'date', 'time', 'comment', 'qualification', 'description', 'observations', 'created_date'], 'safe'],
            [['lat', 'lng'], 'number'],
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
//        $query = ServiceHistory::find()->where(['state'=>'1']);
        $query = ServiceHistory::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        		'sort'=> ['defaultOrder' => ['date'=>SORT_DESC,'time'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'state' => $this->state,
            'date' => $this->date,
            'time' => $this->time,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'created_date' => $this->created_date,
            'expert_id' => $this->expert_id,
            'cancel_reason_id' => $this->cancel_reason_id,
            'problem_type_id' => $this->problem_type_id,
            'service_id' => $this->service_id,
            'user_id' => $this->user_id,
            'coupon_id' => $this->coupon_id,
        ]);

        $query->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'qualification', $this->qualification])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'observations', $this->observations]);

        return $dataProvider;
    }
}
