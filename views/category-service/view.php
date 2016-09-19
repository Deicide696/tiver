<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\CategoryService */

$this->title = $model->description;
$this->params['breadcrumbs'][] = ['label' => 'Categorías de Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-service-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php /*print Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Desea realmente eliminar este elemento?',
                'method' => 'post',
            ],
        ])*/ ?>
    </p>
<?php
$valido=true;
if($model->status==0)
	$valido=false;
if($valido)
	$icon= Yii::$app->params['iconEnabled'];
else
	$icon= Yii::$app->params['iconDisabled'];
?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        		'id',
   			'description',
        		
        		[
        		'attribute' => 'Activo',
        		'format' => 'raw',
        		'value' =>$icon
        		],
            'icon',
        		
        ],
    ]) ?>
    
        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            	[
        		'attribute' => 'Servcio',
        		'format' => 'raw',
        		'value' => function ($searchModel) {
        		return '<div><a target=_blank href="../service/'.$searchModel->id.'">'.$searchModel->name.'</a></div>';
        		},
        		],
            	[
        		'attribute' => 'Precio',
        		'format' => 'raw',
        		'value'=>'price',
        		'format' => 'Currency',
        		],
                'duration',
        		'description',
        	
        		[
        		'attribute' => 'Activo',
        		'format' => 'raw',
        		'value' =>function($searchModel){
        			$valido=true;
        			if($searchModel->status==0)
        				$valido=false;
        			if($valido)
        				return Yii::$app->params['iconEnabled'];
        			else
        				return Yii::$app->params['iconDisabled'];
        		}
        		],
            // 'category_service_id',
             //['class' => 'yii\grid\ActionColumn','template' => '{view} {update}'],
        ],
    ]); ?>
    

</div>
