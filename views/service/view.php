<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;



/* @var $this yii\web\View */
/* @var $model app\models\Service */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php /*print Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Desea realmente eliminar este elemento?',
                'method' => 'post',
            ],
        ]) */?>
    </p>
<?php
$valido=true;
if($model->status==0)
	$valido=false;
if($valido)
	$icon= Yii::$app->params['iconEnabled'];
else
	$icon= Yii::$app->params['iconDisabled'];

	
	$iva=true;
	if($model->tax==0)
		$iva=false;
		if($iva)
			$icon_iva= Yii::$app->params['iconEnabled'];
			else
				$icon_iva= Yii::$app->params['iconDisabled'];
	
?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
        		'description',
        		'icon',
            'duration',
            	[
        		'attribute' => 'Precio',
        		'format' => 'raw',
        		'value'=>$model->price,
        		'format' => 'Currency',
        		],
            	[
        		'attribute' => 'I.V.A.',
        		'format' => 'raw',
        		'value' =>$icon_iva
        		],
        		'categoryService.description',
        		[
        		'attribute' => 'Activo',
        		'format' => 'raw',
        		'value' =>$icon
        		],
        		
            //'exclude_modifiers',
          
        ],
    ]) ?>
    
     <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
        		'attribute' => 'Modificador',
        		'format' => 'raw',
        		'value' => function ($searchModel) {
        		return '<div><a target=_blank href="../modifier/'.$searchModel->id.'">'.$searchModel->name.'</a></div>';
        		},
        		],
            'description',
        	
        		[
        		'attribute' => 'Precio adicional',
        		'format' => 'raw',
        		'value'=>'price',
        		'format' => 'Currency',
        		],
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
        	//	'serviceHasModifier.service.name',
          //  'type_modifier_id',

           //  ['class' => 'yii\grid\ActionColumn','template' => '{view} {update}'],
        ],
    ]); ?>
    

</div>
