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

    <?php
        $columns = [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Usuario',
                'value' => function($searchModel) {
                    return$searchModel->getUserName();
                }
            ],
            [
                'label' => 'Especialista',
                'value' => function($searchModel) {
                    return$searchModel->getExpertName();
                }
            ],
            'comment',
            'address',
            'expert.zone.name',
            [
                'label' => 'Servicio',
                'value' => function($searchModel) {
                    return$searchModel->getServiceName();
                }
            ],
            [
                'label' => 'Precio',
                'format' => 'Currency',
                'value' => function($searchModel) {
                    return$searchModel->getPrice();
                }
            ],
            [
                'label' => 'Duración(mins)',
                'format' => 'html',
                'value' => function($searchModel) {

                    return$searchModel->getDuration();
                }
            ],
            'coupon.code',
            'time:time',
            'date:date',
            'created_date:datetime',
            [
                'attribute' => 'state',
                'format' => 'html',
                'value' => function($searchModel) {
                    $valido = true;
                    if ($searchModel->state == 0) {$valido = false;}
                    if ($valido) {return Yii::$app->params['iconEnabled'];} 
                    else {return Yii::$app->params['iconDisabled'];}
                }
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ];
 
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
