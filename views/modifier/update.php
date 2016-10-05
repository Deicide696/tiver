<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\TypeModifier;
use app\models\Service;


/* @var $this yii\web\View */
/* @var $model app\models\Modifier */

$this->title = 'Modificar modificador: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Modificadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="modifier-update">

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
	<?= $form->field($model,'name'); ?>
	<?= $form->field($model, 'description'); ?>
		<?= $form->field($model, 'price')->input('number'); ?>
	<?= $form->field($model, 'tax')->checkbox();; ?>
	<?= $form->field($model, 'duration')->input('number'); ?>
	<?= $form->field($model, 'status')->checkbox(); ?>
	
	<?php 
	$service=Service::find()->all(); 
	//use yii\helpers\ArrayHelper;
	$listData=ArrayHelper::map($service,'id','name','categoryService.description');
	echo $form->field($model, 'service_id')->dropDownList($listData);
	?>
	
	
	<?php 	
	$tipo_mod=TypeModifier::find()->all(); 
	//use yii\helpers\ArrayHelper;
	$listData=ArrayHelper::map($tipo_mod,'id','name');
	echo $form->field($model, 'type_modifier_id')->dropDownList($listData);
	?>
	

<?= Html::submitButton('Modificar', ['class'=> 'btn btn-success']) ;?>

<?php 
ActiveForm::end ();
?>

</div>
