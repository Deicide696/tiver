<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AssignedServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Servicios Asignados';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assigned-service-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //print Html::a('Create Assigned Service', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    if (Yii::$app->user->can('super-admin')) {
        $columns = [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'Usuario',
                'format' => 'raw',
                'value' => function($searchModel) {

                    return$searchModel->getUserName();
                }
            ],
            [
                'attribute' => 'Especialista',
                'format' => 'raw',
                'value' => function($searchModel) {

                    return$searchModel->getExpertName();
                }
            ],
            'comment',
            'address',
            'expert.zone.name',
            [
                'attribute' => 'Servicio',
                'format' => 'raw',
                'value' => function($searchModel) {

                    return$searchModel->getServiceName();
                }
            ],
            [
                'attribute' => 'Precio',
                'format' => 'raw',
                'format' => 'Currency',
                'value' => function($searchModel) {
                    return$searchModel->getPrice();
                }
            ],
            [
                'attribute' => 'Duración (mins)',
                'format' => 'raw',
                'value' => function($searchModel) {

                    return$searchModel->getDuration();
                }
            ],
            'coupon.code',
            [
                'attribute' => 'Hora',
                'format' => 'raw',
                'value' => 'time',
                'format' => 'Time',
            ],
            [
                'attribute' => 'Fecha Servicio',
                'format' => 'raw',
                'value' => 'date',
                'format' => 'Date',
            ],
            [
                'attribute' => 'Fecha asignación',
                'format' => 'raw',
                'value' => 'created_date',
                'format' => 'DateTime',
            ],
            [
                'attribute' => 'Cancelado',
                'format' => 'raw',
                'value' => function($searchModel) {
                    $valido = true;
                    if ($searchModel->state == 0) {$valido = false;}
                    if ($valido) {return Yii::$app->params['iconDisabled'];} 
                    else {return Yii::$app->params['iconEnabled'];}
                }
            ],
//            [
//                'attribute' => 'enable',
//                'format' => 'raw',
//                'value' => function($searchModel) {
//                    $valido = true;
//                    if ($searchModel->enable == 0){$valido = false;}
//                    if ($valido) {return Yii::$app->params['iconEnabled'];} 
//                    else {return Yii::$app->params['iconDisabled'];}
//                }
//            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ];
    } else {
        $columns = [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'Usuario',
                'format' => 'raw',
                'value' => function($searchModel) {

                    return$searchModel->getUserName();
                }
            ],
            [
                'attribute' => 'Especialista',
                'format' => 'raw',
                'value' => function($searchModel) {

                    return$searchModel->getExpertName();
                }
            ],
            'comment',
            'address',
            'expert.zone.name',
            [
                'attribute' => 'Servicio',
                'format' => 'raw',
                'value' => function($searchModel) {

                    return$searchModel->getServiceName();
                }
            ],
            [
                'attribute' => 'Precio',
                'format' => 'raw',
                'format' => 'Currency',
                'value' => function($searchModel) {
                    return$searchModel->getPrice();
                }
            ],
            [
                'attribute' => 'Duración (mins)',
                'format' => 'raw',
                'value' => function($searchModel) {

                    return$searchModel->getDuration();
                }
            ],
            'coupon.code',
            [
                'attribute' => 'Hora',
                'format' => 'raw',
                'value' => 'time',
                'format' => 'Time',
            ],
            [
                'attribute' => 'Fecha Servicio',
                'format' => 'raw',
                'value' => 'date',
                'format' => 'Date',
            ],
            [
                'attribute' => 'Fecha asignación',
                'format' => 'raw',
                'value' => 'created_date',
                'format' => 'DateTime',
            ],
            [
                'attribute' => 'Cancelado',
                'format' => 'raw',
                'value' => function($searchModel) {
                    $valido = true;
                    if ($searchModel->state == 0) {$valido = false;}
                    if ($valido) {return Yii::$app->params['iconDisabled'];} 
                    else {return Yii::$app->params['iconEnabled'];}
                }
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ];
    }
    ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($searchModel) {
            if ($searchModel->state == 0) {
                $class = ['class' => 'info'];
            } else {
                $class = [];
            }
            return $class;
        },
        'columns' => $columns,
    ]);
    ?>

</div>
