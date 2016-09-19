<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ModifierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Modificadores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modifier-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nuevo modificador', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'description',
        	
        		[
        		'attribute' => 'Precio adicional',
        		'format' => 'raw',
        		'value'=>'price',
        		'format' => 'Currency',
        		],
        		'duration',
        		[
        		'attribute' => 'Activo',
        		'format' => 'raw',
        		'value' =>function($searchModel){
        			$valido=true;
        			if($searchModel->status==0)
        				$valido=false;
        			if($valido)
        				return Yii::$app->params['iconEnabled'];
        			else
        				return Yii::$app->params['iconDisabled'];
        		}
        		],
        		'serviceHasModifier.service.name',
        	//	'serviceHasModifier.service.name',
          //  'type_modifier_id',

             ['class' => 'yii\grid\ActionColumn','template' => '{view} {update}'],
        ],
    ]); ?>

</div>
