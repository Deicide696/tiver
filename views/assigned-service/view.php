<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AssignedService */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Assigned Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assigned-service-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Desea realmente eliminar este elemento?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
<?php 

if($model->state==0)
	$icon= Yii::$app->params['iconDisabled'];
else
	$icon= Yii::$app->params['iconEnabled'];

	
	?>
	
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'id',
        		 [
        		'attribute' => 'Fecha',
        		
        		'value'=>$model->date,
        		'format' => 'Date',
        		],
        		  [
        		'attribute' => 'Hora',
        
        		'value'=>$model->time,
        		'format' => 'Time',
        		],
            'address',
        		'expert.zone.name',
            [
        		'attribute' => 'Activo',
        		'format' => 'raw',
        		'value' =>$icon
        		],
           
              [
        		'attribute' => 'Fecha asignación',
        	
        		'value'=>$model->created_date,
        		'format' => 'DateTime',
        		],
              [
        		'attribute' => 'Servicio',
        		'format' => 'raw',
        		'value'=>$model->getServiceName(),
  
        		],
        		[
        		'attribute' => 'Precio',
        		'format' => 'currency',
        		'value'=>$model->getPrice(),
        		
        		],
        		[
        		'attribute' => 'Duracion (mins)',
        		'format' => 'currency',
        		'value'=>$model->getDuration(),
        		
        		],
            'coupon.code',
             'comment',
        		 [
        		'attribute' => 'Usuario',
        		'format' => 'raw',
        		'value'=>$model->getUserName(),
  
        		],
        		 [
        		'attribute' => 'Especialista',
        		'format' => 'raw',
        		'value'=>$model->getExpertName(),
  
        		],
        		
      
        ],
    ]) ?>

</div>
