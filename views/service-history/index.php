<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ServiceHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'History');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-history-index">

    <div class="row" style="padding-bottom: 15px;">
        <div class="col col-sm-2 pull-left">
            <h1 class="" style="margin: 0px;"><?= Html::encode($this->title) ?></h1>
        </div>
    </div>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($searchModel) {
            ($searchModel->getPayStatus() == 0 && $searchModel->state == 1) ? $class = ['class' => 'danger'] : $class = [];
            return $class;
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'Fecha',
                'format' => 'raw',
                'value' => 'date',
                'format' => 'Date',
            ],
            [
                'attribute' => 'Hora',
                'format' => 'raw',
                'value' => 'time',
                'format' => 'Time',
            ],
            'address',
            'expert.zone.name',
            [
                'attribute' => 'Finalizado',
                'format' => 'raw',
                'value' => function($searchModel) {
                    $valido = true;
                    ($searchModel->state == 0) ? $icon = Yii::$app->params['iconDisabled'] : $icon = Yii::$app->params['iconEnabled'];
                    return $icon;
                }
            ],
            [
                'attribute' => 'Fecha finalizado',
                'format' => 'raw',
                'value' => 'created_date',
                'format' => 'DateTime',
            ],
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
            [
                'attribute' => 'Pagado',
                'format' => 'raw',
                'value' => function($searchModel) {
                    $valido = true;
                    ($searchModel->getPayStatus() == 0) ? $icon = Yii::$app->params['iconDisabled'] : $icon = Yii::$app->params['iconEnabled'];
                    return $icon;
                }
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ],
        'options' => ['class' => 'table-responsive'],
    ]);
    ?>

</div>
