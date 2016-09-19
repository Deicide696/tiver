<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Weekday;
use app\models\Expert;

/* @var $this yii\web\View */
/* @var $model app\models\Schedule */

$this->title = 'Modificar disponibilidad';
$this->params['breadcrumbs'][] = ['label' => 'Disponibilidad', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Disponibilidad', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="schedule-update">

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
	<?= $form->field($model,'start_time')->input("time"); ?>
	<?= $form->field($model, 'finish_time')->input("time"); ?>
		
	<?php 	
	$expert=Expert::find()->orderBy('last_name')->all(); 
	//use yii\helpers\ArrayHelper;
	$listData=ArrayHelper::map($expert,'id','name','last_name');
	echo $form->field($model, 'expert_id')->dropDownList($listData);
	?>

	<?php 	
	$weekday=Weekday::find()->all(); 
	//use yii\helpers\ArrayHelper;
	$listData=ArrayHelper::map($weekday,'id','weekdays');
	echo $form->field($model, 'weekday_id')->dropDownList($listData);
	?>
	
	

<?= Html::submitButton('Modificar', ['class'=> 'btn btn-success']) ;?>

<?php 
ActiveForm::end ();
?>	
</div>
