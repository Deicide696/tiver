<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CategoryService */

$this->title = 'Nueva categoria';
$this->params ['breadcrumbs'] [] = [ 
		'label' => 'Categorías de servicios',
		'url' => [ 
				'index' 
		] 
];
$this->params ['breadcrumbs'] [] = $this->title;
?>
<div class="category-service-create">

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
	<?= $form->field($model,'description'); ?>
	<?= $form->field($model, 'status')->checkbox(); ?>
	<?= $form->field($model, 'icon'); ?>

<?= Html::submitButton('Crear', ['class'=> 'btn btn-success']) ;?>

<?php 
ActiveForm::end ();
?>

</div>
