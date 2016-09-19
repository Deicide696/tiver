<?php

use yii\helpers\Html;
use app\models\TypeIdentification;
use app\models\Gender;
use app\models\Rol;
use app\models\Zone;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;



/* @var $this yii\web\View */
/* @var $model app\models\Expert */

$this->title = 'Nuevo especialista';
$this->params['breadcrumbs'][] = ['label' => 'Especialistas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expert-create">

    <h1><?= Html::encode($this->title) ?></h1>

	<?php
		$form = ActiveForm::begin ( [ 
				'id' => 'active-form',
				'options' => [ 
					
						'enctype' => 'multipart/form-data' 
				]
		]);
	?>
	
	<?= $form->field($model, 'name'); ?>
	
	<?= $form->field($model, 'last_name'); ?>
	
	<?= $form->field($model, 'phone')->input('number'); ?>

	<?php
		$identification = TypeIdentification::find()->all(); 
		$listData = ArrayHelper::map($identification,'id','description');
		echo $form->field($model, 'type_identification_id')->dropDownList($listData);
	?>

	<?= $form->field($model,'identification')->input('number'); ?>
	
	<?= $form->field($model, 'email')->input('email'); ?>
			
	<?= $form->field($model, 'address'); ?>
	
	<?= $form->field($model, 'enable')->checkbox(); ?>

	<?= $form->field($model, 'rol_id')->input("hidden")->label(false); ?>

	<?php
		$gender = Gender::find()->all(); 
		$listData = ArrayHelper::map($gender,'id','gender');
		echo $form->field($model, 'gender_id')->dropDownList($listData);
	?>
	
	<?php 	
		$zone=Zone::find()->all(); 
		$listData=ArrayHelper::map($zone,'id','name');
		echo $form->field($model, 'zone_id')->dropDownList($listData);
	?>

	<?= $form->field($model, 'password')->input('password'); ?>
	<?= $form->field($model, 'password_repeat')->input('password'); ?>
	
	<?= Html::submitButton('Crear', ['class'=> 'btn btn-success']) ;?>

	<?php 
		ActiveForm::end ();
	?>
</div>
