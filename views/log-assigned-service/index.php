<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LogAssignedServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Servicios confirmados/no confirmados';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-assigned-service-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php // Html::a('Create Log Assigned Service', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'expert_id',
                'value' => 'expert.name',
                'label' => 'Especialista',
            ],
            [
                'attribute' => 'assigned_service_id',
                'value' => 'serviceHistory.address',
                'label' => 'Dirección',
            ],
            [
                'attribute' => 'userFirstName',
                'value' => 'serviceHistory.user.first_name',
                'label' => 'Usuario',
            ],
            [
                'attribute' => 'rejected',
                'format' => 'html',
                'value' =>function($searchModel){
                    $valido=true;
                    if($searchModel->rejected==0)
                     $valido=false;
                    if($valido)
                     return  Yii::$app->params['iconEnabled'];
                    else
                     return Yii::$app->params['iconDisabled'];
                }
            ],
            [
                'attribute' => 'missed',
                'format' => 'html',
                'value' =>function($searchModel){
                    $valido=true;
                    if($searchModel->missed==0)
                     $valido=false;
                    if($valido)
                     return  Yii::$app->params['iconEnabled'];
                    else
                     return Yii::$app->params['iconDisabled'];
                }
            ],
            [
                'attribute' => 'accepted',
                'format' => 'html',
                'value' =>function($searchModel){
                    $valido=true;
                    if($searchModel->assigned==0)
                     $valido=false;
                    if($valido)
                     return  Yii::$app->params['iconEnabled'];
                    else
                     return Yii::$app->params['iconDisabled'];
                }
            ],
            // 'date',
            // 'time',
            // 'attempt',
            // 'created_date',
            // 'assigned_service_id',
            // 'expert_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
