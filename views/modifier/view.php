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
        		'tax',
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
