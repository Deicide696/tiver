<?php

namespace app\controllers;

use Yii;
use app\models\Expert;
use app\models\ExpertHasService;
use app\models\ExpertHasServiceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ExpertHasServiceController implements the CRUD actions for ExpertHasService model.
 */
class ExpertHasServiceController extends Controller
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
     * Lists all ExpertHasService models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExpertHasServiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExpertHasService model.
     * @param integer $expert_id
     * @param integer $service_id
     * @return mixed
     */
    public function actionView($expert_id, $service_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($expert_id, $service_id),
        ]);
    }

    /**
     * Creates a new ExpertHasService model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        $model = new ExpertHasService();        

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            // return $this->redirect(['view',
            //         'expert_id' => $model->expert_id,
            //         'service_id' => $model->service_id
            //     ]);

            return $this->redirect(['expert/' . $model->expert_id]);
        }

        else
        {
            $modelExpert = Expert::findOne($id);

            return $this->render('create', [
                'model' => $model,
                'expert' => $modelExpert
            ]);
        }
    }

    /**
     * Updates an existing ExpertHasService model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $expert_id
     * @param integer $service_id
     * @return mixed
     */
    public function actionUpdate($expert_id, $service_id)
    {
        $model = $this->findModel($expert_id, $service_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'expert_id' => $model->expert_id, 'service_id' => $model->service_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ExpertHasService model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $expert_id
     * @param integer $service_id
     * @return mixed
     */
    public function actionDelete($expert_id, $service_id)
    {
        $this->findModel($expert_id, $service_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ExpertHasService model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $expert_id
     * @param integer $service_id
     * @return ExpertHasService the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($expert_id, $service_id)
    {
        if (($model = ExpertHasService::findOne(['expert_id' => $expert_id, 'service_id' => $service_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
