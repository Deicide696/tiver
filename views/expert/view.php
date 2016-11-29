<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Expert */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Especialistas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
    $valido = true;
    
    if($model->enable == 0)
	{
        $valido=false;
    }

    if($valido)
    {
        $icon= Yii::$app->params['iconEnabled'];
    }

    else
    {
        $icon= Yii::$app->params['iconDisabled'];
    }	
?>

<div class="expert-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php
            //print Html::a('Eliminar', ['delete', 'id' => $model->id], [
            //'class' => 'btn btn-danger',
            //'data' => [
              //  'confirm' => '¿Desea realmente eliminar este elemento?',
                //'method' => 'post',
            //],
            // ])
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
          //  'id',            
            'name',
            'last_name',
            'phone',
            'email:email',
            'typeIdentification.description',
            'identification',
            //'password',
            'address',
            [
        		'attribute' => 'Activo',
        		'format' => 'raw',
        		'value' =>$icon
    		],
            'zone.name',            
            'path',
            'gender.gender',
            [
                'attribute' => 'Fecha creación',
                'format' => 'raw',
                'value'=>$model->created_date,
                'format' => 'DateTime',
            ],
        ],
    ]) ?>
    
    <h2>Servicios Asignados</h2>    
    <?= Html::a('Agregar Servicio', ['expert-has-service/create', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

    <br>
    <br>

    <?= GridView::widget([
        'dataProvider' => $dataProvider2,
        'filterModel' => $searchModel2,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'service.name',
                'qualification'
      
           // 'date_created',
           // 'expert_id',  
        
            // 'weekday.weekdays',
                

           //['class' => 'yii\grid\ActionColumn','template' => '{delete} {update}'],
        ],
    ]); ?>

    <h2>Horario disponible</h2>    
    <?= Html::a('Agregar Disponibilidad', ['schedule/create', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

    <br>
    <br>

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
        
             'weekday.weekdays',
        		

           //['class' => 'yii\grid\ActionColumn','template' => '{delete} {update}'],
        ],
    ]); ?>
</div>
