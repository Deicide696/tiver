<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScheduleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Availability');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-index">

    <div class="row" style="padding-bottom: 15px;">
        <div class="col col-sm-2 pull-left">
            <h1 class="" style="margin: 0px;"><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col col-sm-2 pull-right text-right">
            <?= yii::$app->user->can('create-expert') ? Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('app', 'New'), ['create'], ['class' => 'btn btn-success']) : '' ?>
        </div>
    </div>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'Hora Inicio',
                'format' => 'raw',
                'value' => 'start_time',
                'format' => 'Time',
            ],
            [
                'attribute' => 'Hora Fin',
                'format' => 'raw',
                'value' => 'finish_time',
                'format' => 'Time',
            ],
            [
                'attribute' => 'expert_name',
                'label' => 'Nombre Especialista',
                'value' => 'expert.name'
            ],
            [
                'attribute' => 'Apellido especialista',
                'format' => 'raw',
                'value' => 'expert.last_name'
            ],
            'weekday.weekdays',
            ['class' => 'yii\grid\ActionColumn'],
        ],
        'options' => ['class' => 'table-responsive'],
    ]);
    ?>

</div>
