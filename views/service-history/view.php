<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ServiceHistory */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Historial de servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-history-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
      
    </p>
<?php if($model->state==0)
	$icon= Yii::$app->params['iconDisabled'];
else
	$icon= Yii::$app->params['iconEnabled'];

	($model->getPayStatus()==0)?$icon2= Yii::$app->params['iconDisabled']:$icon2= Yii::$app->params['iconEnabled'];
		
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
        		'attribute' => 'Finalizado',
        		'format' => 'raw',
        		'value' =>$icon
        		],
           
              [
        		'attribute' => 'Fecha finalizado',
        	
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
        		 [
        		'attribute' => 'Pagado',
        		'format' => 'raw',
        		'value' =>$icon2
        		],
        		'serviceHistoryHasPay.pay.message',
        ],
    ]) ?>


</div>
