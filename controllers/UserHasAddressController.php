<?php

namespace app\controllers;

use Yii;
use app\models\UserHasAddress;
use app\models\UserHasAddressSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserHasAddressController implements the CRUD actions for UserHasAddress model.
 */
class UserHasAddressController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all UserHasAddress models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserHasAddressSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserHasAddress model.
     * @param integer $user_id
     * @param integer $address_id
     * @return mixed
     */
    public function actionView($user_id, $address_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($user_id, $address_id),
        ]);
    }

    /**
     * Creates a new UserHasAddress model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserHasAddress();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'user_id' => $model->user_id, 'address_id' => $model->address_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing UserHasAddress model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $user_id
     * @param integer $address_id
     * @return mixed
     */
    public function actionUpdate($user_id, $address_id)
    {
        $model = $this->findModel($user_id, $address_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'user_id' => $model->user_id, 'address_id' => $model->address_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing UserHasAddress model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $user_id
     * @param integer $address_id
     * @return mixed
     */
    public function actionDelete($user_id, $address_id)
    {
        $this->findModel($user_id, $address_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserHasAddress model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $user_id
     * @param integer $address_id
     * @return UserHasAddress the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($user_id, $address_id)
    {
        if (($model = UserHasAddress::findOne(['user_id' => $user_id, 'address_id' => $address_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
