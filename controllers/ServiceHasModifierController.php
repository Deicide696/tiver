<?php

namespace app\controllers;

use Yii;
use app\models\ServiceHasModifier;
use app\models\ServiceHasModifierSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ServiceHasModifierController implements the CRUD actions for ServiceHasModifier model.
 */
class ServiceHasModifierController extends Controller
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
     * Lists all ServiceHasModifier models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ServiceHasModifierSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ServiceHasModifier model.
     * @param integer $service_id
     * @param integer $modifier_id
     * @return mixed
     */
    public function actionView($service_id, $modifier_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($service_id, $modifier_id),
        ]);
    }

    /**
     * Creates a new ServiceHasModifier model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ServiceHasModifier();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'service_id' => $model->service_id, 'modifier_id' => $model->modifier_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ServiceHasModifier model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $service_id
     * @param integer $modifier_id
     * @return mixed
     */
    public function actionUpdate($service_id, $modifier_id)
    {
        $model = $this->findModel($service_id, $modifier_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'service_id' => $model->service_id, 'modifier_id' => $model->modifier_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ServiceHasModifier model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $service_id
     * @param integer $modifier_id
     * @return mixed
     */
    public function actionDelete($service_id, $modifier_id)
    {
        $this->findModel($service_id, $modifier_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ServiceHasModifier model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $service_id
     * @param integer $modifier_id
     * @return ServiceHasModifier the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($service_id, $modifier_id)
    {
        if (($model = ServiceHasModifier::findOne(['service_id' => $service_id, 'modifier_id' => $modifier_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
