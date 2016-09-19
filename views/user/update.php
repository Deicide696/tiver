<?php

use yii\helpers\Html;
use app\models\TypeIdentification;
use app\models\Gender;
use app\models\Rol;
use app\models\City;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Modificar usuario: ' . ' ' . $model->first_name;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->first_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="user-update">

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
<?= $form->field($model, 'id')->input("hidden")->label(false); ?>
<?= $form->field($model, 'imei')->input("hidden")->label(false); ?>
<?= $form->field($model, 'FK_id_rol')->input("hidden")->label(false); ?>
	<?= $form->field($model, 'updated_date')->input("hidden")->label(false); ?>
	<?= $form->field($model,'identification')->input("number"); ?>
	<?= $form->field($model, 'first_name'); ?>
	<?= $form->field($model, 'last_name'); ?>
	<?= $form->field($model, 'phone')->input("number"); ?>
	<?= $form->field($model, 'email')->input("email"); ?>
	<?= $form->field($model, 'birth_date')->input("date"); ?>
	<?= $form->field($model, 'enable')->checkbox(); ?>
	<?= $form->field($model, 'receive_interest_info')->checkbox(); ?>

	<?php 	
	$identification=TypeIdentification::find()->all(); 
	//use yii\helpers\ArrayHelper;
	$listData=ArrayHelper::map($identification,'id','description');
	echo $form->field($model, 'FK_id_type_identification')->dropDownList($listData);
	?>

	<?php 	
	$gender=Gender::find()->all(); 
	//use yii\helpers\ArrayHelper;
	$listData=ArrayHelper::map($gender,'id','gender');
	echo $form->field($model, 'FK_id_gender')->dropDownList($listData);
	?>
	
	
	<?php 	
	$city=City::find()->all(); 
	//use yii\helpers\ArrayHelper;
	$listData=ArrayHelper::map($city,'id','name');
	echo $form->field($model, 'FK_id_city')->dropDownList($listData);
	?>
	

<?= Html::submitButton('Modificar', ['class'=> 'btn btn-success']) ;?>

<?php 
ActiveForm::end ();
?>

</div>
