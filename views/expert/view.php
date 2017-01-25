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
    if($model->enable == 0){$valido=false;}
    if($valido){$icon= Yii::$app->params['iconDisabled-left'];}
    else{$icon= Yii::$app->params['iconEnabled-left'];}	

    if (Yii::$app->user->can('super-admin')) {
        $columns =  [
            'name',
            'last_name',
            'phone',
            'email:email',
            'typeIdentification.description',
            'identification',
            'address',
            'zone.name',
            'path',
            'gender.gender',
            [
                'attribute' => 'Fecha creación',
                'format' => 'raw',
                'value' => $model->created_date,
                'format' => 'DateTime',
            ],
            [
                'attribute' => 'Eliminado',
                'format' => 'raw',
                'value' => $icon
            ],
        ];
    } else {
        $columns =  [
            'name',
            'last_name',
            'phone',
            'email:email',
            'typeIdentification.description',
            'identification',
            'address',
            'zone.name',
            'path',
            'gender.gender',
            [
                'attribute' => 'Fecha creación',
                'format' => 'raw',
                'value' => $model->created_date,
                'format' => 'DateTime',
            ],
        ];
    }
    ?>

<div class="expert-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => $columns,
    ])
    ?>

    <h2>Servicios Asignados</h2>    
    <?= Html::a('Agregar Servicio', ['expert-has-service/create', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

    <br>
    <br>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider2,
        'filterModel' => $searchModel2,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'service.name',
            'qualification'
        ],
    ]);
    ?>

    <h2>Horario disponible</h2>    
    <?= Html::a('Agregar Disponibilidad', ['schedule/create', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

    <br>
    <br>

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
            'weekday.weekdays',
        ],
    ]);
    ?>
</div>
