<?php

use yii\helpers\Html;

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Service;
use app\models\Expert;

/* @var $this yii\web\View */
/* @var $model app\models\ExpertHasService */

$this->title = 'Modificar relación';
$this->params['breadcrumbs'][] = ['label' => 'Especialistas & servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Relación', 'url' => ['view', 'expert_id' => $model->expert_id, 'service_id' => $model->service_id]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="expert-has-service-update">

    <h1><?= Html::encode($this->title) ?></h1>

 <?php
$form = ActiveForm::begin ( [ 
		'id' => 'active-form',
		'options' => [ 
			
				'enctype' => 'multipart/form-data' 
		]
]
 );
?>
		
	<?php 	
	$expert=Expert::find()->orderBy('last_name')->all(); 
	//use yii\helpers\ArrayHelper;
	$listData=ArrayHelper::map($expert,'id','name','last_name');
	echo $form->field($model, 'expert_id')->dropDownList($listData);
	?>

	<?php 	
	$service=Service::find()->all(); 
	//use yii\helpers\ArrayHelper;
	$listData=ArrayHelper::map($service,'id','name');
	echo $form->field($model, 'service_id')->dropDownList($listData);?>
	
	<?= $form->field($model, 'qualification')->input("number"); ?>
	

<?= Html::submitButton('Modificar', ['class'=> 'btn btn-success']) ;?>

<?php 
ActiveForm::end ();
?>	

</div>
