<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CreditCard;

/**
 * CreditCardSearch represents the model behind the search form about `app\models\CreditCard`.
 */
class CreditCardSearch extends CreditCard
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'enable', 'user_id'], 'integer'],
            [['hash', 'created_date'], 'safe'],
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
        $query = CreditCard::find();

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
            'id' => $this->id,
            'enable' => $this->enable,
            'created_date' => $this->created_date,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'hash', $this->hash]);

        return $dataProvider;
    }
}
