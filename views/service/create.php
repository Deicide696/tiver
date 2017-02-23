<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\CategoryService;

/* @var $this yii\web\View */
/* @var $model app\models\Service */

$this->title = Yii::t('app', 'New').' '.Yii::t('app', 'Service');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Services'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-create">

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
	<?= $form->field($model,'description'); ?>
		<?= $form->field($model,'icon'); ?>
	<?= $form->field($model,'duration'); ?>
	<?= $form->field($model, 'price'); ?>
	<?= $form->field($model, 'tax')->checkbox(); ?>
	<?= $form->field($model, 'status')->checkbox(); ?>

	<?php 	
	$categorias=CategoryService::find()->all(); 
	//use yii\helpers\ArrayHelper;
	$listData=ArrayHelper::map($categorias,'id','description');
	echo $form->field($model, 'category_service_id')->dropDownList($listData);
	?>
	

<?= Html::submitButton('Crear', ['class'=> 'btn btn-success']) ;?>

<?php 
ActiveForm::end ();
?>

</div>
