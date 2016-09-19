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
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //print Html::a('Create Assigned Service', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
    		
    		'rowOptions'=>function ($searchModel){
    		($searchModel->state==0)?$class= ['class'=>'danger']:$class= [];
    		return $class;
    		
    		},
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
           // 'id',
        		  [
        		'attribute' => 'Fecha',
        		'format' => 'raw',
        		'value'=>'date',
        		'format' => 'Date',
        		],
        		  [
        		'attribute' => 'Hora',
        		'format' => 'raw',
        		'value'=>'time',
        		'format' => 'Time',
        		],
            'address',
        		'expert.zone.name',
        		
   			[
        		'attribute' => 'Activo',
        		'format' => 'raw',
        		'value' =>function($searchModel){
        		$valido=true;
        		if($searchModel->state==0)
        			$valido=false;
        		if($valido)
        			return  Yii::$app->params['iconEnabled'];
        		else
        			return Yii::$app->params['iconDisabled'];
        		}
        		],
        		
             [
        		'attribute' => 'Fecha asignación',
        		'format' => 'raw',
        		'value'=>'created_date',
        		'format' => 'DateTime',
        		],
        		[
        		'attribute' => 'Servicio',
        		'format' => 'raw',
        		
        		'value' =>function($searchModel){
        		 
        		return$searchModel->getServiceName()  ;
        		}
        		],
        		[
        		'attribute' => 'Precio',
        		'format' => 'raw',
        				'format' => 'Currency',
        		'value' =>function($searchModel){
        		 
        		return$searchModel->getPrice()  ;
        		}
        		],
        		[
        		'attribute' => 'Duración (mins)',
        		'format' => 'raw',
        		'value' =>function($searchModel){
     				
        					return$searchModel->getDuration()  ;
        		}
        		],
             'coupon.code',
             'comment',
           
            	[
        		'attribute' => 'Usuario',
        		'format' => 'raw',
        		'value' =>function($searchModel){
     				
        					return$searchModel->getUserName()  ;
        		}
        		],
        	
            // 'city_id',
             [
        		'attribute' => 'Especialista',
        		'format' => 'raw',
        		'value' =>function($searchModel){
     				
        					return$searchModel->getExpertName()  ;
        		}
        		],
        		
        		
        		
             ['class' => 'yii\grid\ActionColumn','template' => '{view}'],
        ],
    ]); ?>

</div>
