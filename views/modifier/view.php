<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Modifier */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Modificadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modifier-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    
    </p>
<?php
$valido=true;
if($model->status==0)
	$valido=false;
if($valido)
	$icon= Yii::$app->params['iconEnabled'];
else
	$icon= Yii::$app->params['iconDisabled'];
	

	$iva=true;
	if($model->tax==0)
		$iva=false;
		if($iva)
			$icon_iva= Yii::$app->params['iconEnabled'];
			else
				$icon_iva= Yii::$app->params['iconDisabled'];

?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description',
        			[
        		'attribute' => 'Precio adicional',
        		'format' => 'raw',
        		'value'=>$model->price,
        		'format' => 'Currency',
        		],
        		[
        		'attribute' => 'I.V.A.',
        		'format' => 'raw',
        		'value' =>$icon_iva
        		],
        		'duration',
        		[
        		'attribute' => 'Activo',
        		'format' => 'raw',
        		'value' =>$icon
        		],
        		'serviceHasModifier.service.name',
        		'typeModifier.name',
        		
        ],
    ]) ?>

</div>
