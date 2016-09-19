<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Servicios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nuevo servicio', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

 
            'name',
            	[
        		'attribute' => 'Precio',
        		'format' => 'raw',
        		'value'=>'price',
        		'format' => 'Currency',
        		],
                'duration',
        		'icon',
        		'description',
        		[
        		'attribute' => 'Categoría',
        		'format' => 'raw',
        		'value' => 'categoryService.description',
        		],
        		
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
            // 'category_service_id',

             ['class' => 'yii\grid\ActionColumn','template' => '{view} {update}'],
        ],
    ]); ?>

</div>
