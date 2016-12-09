<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'phone', 'receive_interest_info', 'FK_id_rol', 'FK_id_gender', 'FK_id_type_identification', 'FK_id_city'], 'integer'],
            [['first_name', 'last_name', 'identification', 'email', 'password', 'birth_date', 'last_login', 'imei', 'fb_id', 'tpaga_id', 'created_date', 'updated_date', 'personal_code'], 'safe'],
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
        $query = User::find();

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
        $query->andFilterWhere([
            'id' => $this->id,
            'phone' => $this->phone,
            'birth_date' => $this->birth_date,
            'receive_interest_info' => $this->receive_interest_info,
            'enable' => $this->enable,
            'last_login' => $this->last_login,
            'created_date' => $this->created_date,
            'updated_date' => $this->updated_date,
            'FK_id_rol' => $this->FK_id_rol,
            'FK_id_gender' => $this->FK_id_gender,
            'FK_id_type_identification' => $this->FK_id_type_identification,
            'FK_id_city' => $this->FK_id_city,
        ]);

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'identification', $this->identification])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'imei', $this->imei])
            ->andFilterWhere(['like', 'fb_id', $this->fb_id])
            ->andFilterWhere(['like', 'tpaga_id', $this->tpaga_id])
            ->andFilterWhere(['like', 'personal_code', $this->personal_code]);

        return $dataProvider;
    }
}
