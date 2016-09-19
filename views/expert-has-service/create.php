<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Service;
use app\models\Expert;


/* @var $this yii\web\View */
/* @var $model app\models\ExpertHasService */

$this->title = "Asignar servicio a " . $expert->name;
$this->params['breadcrumbs'][] = ['label' => $expert->name, 'url' => ['expert/'.$expert->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expert-has-service-create">

    <h1><?= $this->title ?></h1>

<?php
	$form = ActiveForm::begin ( [ 
			'id' => 'active-form',
			'options' => [ 
				
					'enctype' => 'multipart/form-data' 
			]
	]);
?>

<?= $form->field($model, 'expert_id')->hiddenInput(['value'=> $expert->id ])->label(false); ?>

<?php
	$service=Service::find()->all(); 
	$listData=ArrayHelper::map($service,'id','name','categoryService.description');
	echo $form->field($model, 'service_id')->dropDownList($listData);
?>
	
<?= $form->field($model, 'qualification')->input("number"); ?>	

<?= Html::submitButton('Crear', ['class'=> 'btn btn-success']) ;?>

<?php 
ActiveForm::end ();
?>	

</div>
