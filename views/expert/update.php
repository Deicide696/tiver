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

$this->title = 'Modificar especialista: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Especialistas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="expert-update">

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
<?= $form->field($model, 'rol_id')->input("hidden")->label(false); ?>
	<?= $form->field($model,'identification'); ?>
	<?= $form->field($model, 'name'); ?>
		<?= $form->field($model, 'last_name'); ?>
			<?= $form->field($model, 'phone'); ?>
				<?= $form->field($model, 'email'); ?>
					<?= $form->field($model, 'address'); ?>
						<?= $form->field($model, 'enable')->checkbox(); ?>
						
	<?php 	
	$identification=TypeIdentification::find()->all(); 
	//use yii\helpers\ArrayHelper;
	$listData=ArrayHelper::map($identification,'id','description');
	echo $form->field($model, 'type_identification_id')->dropDownList($listData);
	?>

	<?php 	
	$gender=Gender::find()->all(); 
	//use yii\helpers\ArrayHelper;
	$listData=ArrayHelper::map($gender,'id','gender');
	echo $form->field($model, 'gender_id')->dropDownList($listData);
	?>
	
	<?php 	
	$zone=Zone::find()->all(); 
	//use yii\helpers\ArrayHelper;
	$listData=ArrayHelper::map($zone,'id','name');
	echo $form->field($model, 'zone_id')->dropDownList($listData);
	?>
	

<?= Html::submitButton('Modificar', ['class'=> 'btn btn-success']) ;?>

<?php 
ActiveForm::end ();
?>

</div>
