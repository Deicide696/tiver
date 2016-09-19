<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScheduleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Disponibilidad';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nueva disponibilidad', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
           [
        		'attribute' => 'Hora Inicio',
        		'format' => 'raw',
        		'value'=>'start_time',
        		'format' => 'Time',
        		],
             [
        		'attribute' => 'Hora Fin',
        		'format' => 'raw',
        		'value'=>'finish_time',
        		'format' => 'Time',
        		],
           // 'date_created',
           // 'expert_id',	
        			[
        		'attribute' => 'Nombre especialista',
        		'format' => 'raw',
        		'value' =>'expert.name'
        		],
        		[
        		'attribute' => 'Apellido especialista',
        		'format' => 'raw',
        		'value' =>'expert.last_name'
        				],
             'weekday.weekdays',
        		

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
