<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;
use app\assets\AppDate;
use yii\filters\VerbFilter;
use app\models\City;
// use app\models\Gender;
use app\models\MmzUser;
use app\models\MmzUserSearch;
use app\models\TermTaxonomy;
use app\models\Rol;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\Users;
use app\models\UsersSearch;
use app\models\LogToken;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\CheckPreRegister;
use app\models\TypeToken;
use app\assets\EmailAsset;
use app\assets\Facebook\Facebook;
use app\assets\Facebook\FacebookRequest;
use app\assets\Facebook\FacebookApp;
use app\assets\Facebook\FacebookClient;

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
            [['id', 'phone', 'receive_interest_info', 'enable', 'imei', 'FK_id_rol', 'FK_id_gender', 'FK_id_type_identification', 'FK_id_city'], 'integer'],
            [['first_name', 'last_name', 'identification', 'email', 'password', 'birth_date', 'last_login', 'fb_id', 'created_date', 'updated_date'], 'safe'],
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
       // $query = User::find();
        $query = User::find()->where(['FK_id_rol'=>User::ROLE_USER]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
//        var_dump($params);die();
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'phone' => $this->phone,
            'birth_date' => $this->birth_date,
            'receive_interest_info' => $this->receive_interest_info,
            'enable' => $this->enable,
            'last_login' => $this->last_login,
            'imei' => $this->imei,
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
            ->andFilterWhere(['like', 'fb_id', $this->fb_id]);

        return $dataProvider;
    }
}
