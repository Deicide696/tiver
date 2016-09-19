<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Service;
use app\models\Modifier;

/* @var $this yii\web\View */
/* @var $model app\models\ServiceHasModifier */

$this->title = 'Nueva relación';
$this->params['breadcrumbs'][] = ['label' => 'Modificadores & servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-has-modifier-create">

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
	$service=Service::find()->all(); 
	//use yii\helpers\ArrayHelper;
	$listData=ArrayHelper::map($service,'id','name','categoryService.description');
	echo $form->field($model, 'service_id')->dropDownList($listData);
	?>
	
	<?php 	
	$modifier=Modifier::find()->all(); 
	//use yii\helpers\ArrayHelper;
	$listData=ArrayHelper::map($modifier,'id','description');
	echo $form->field($model, 'modifier_id')->dropDownList($listData);
	?>


<?= Html::submitButton('Crear', ['class'=> 'btn btn-success']) ;?>

<?php 
ActiveForm::end ();
?>

</div>
