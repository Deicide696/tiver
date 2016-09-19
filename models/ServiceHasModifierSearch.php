<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ServiceHasModifier;

/**
 * ServiceHasModifierSearch represents the model behind the search form about `app\models\ServiceHasModifier`.
 */
class ServiceHasModifierSearch extends ServiceHasModifier
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_id', 'modifier_id'], 'integer'],
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
        $query = ServiceHasModifier::find();

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
            'service_id' => $this->service_id,
            'modifier_id' => $this->modifier_id,
        ]);

        return $dataProvider;
    }
}
