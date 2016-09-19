<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExpertHasService;

/**
 * ExpertHasServiceSearch represents the model behind the search form about `app\models\ExpertHasService`.
 */
class ExpertHasServiceSearch extends ExpertHasService
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['expert_id', 'service_id'], 'integer'],
            [['qualification'], 'number'],
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
        $query = ExpertHasService::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'expert_id' => $this->expert_id,
            'service_id' => $this->service_id,
            'qualification' => $this->qualification,
        ]);

        return $dataProvider;
    }
}
