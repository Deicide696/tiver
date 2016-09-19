<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CategoryServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categorías de servicios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-service-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nueva categoría', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            
            'description',
        	
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
            'icon',

             ['class' => 'yii\grid\ActionColumn','template' => '{view} {update}'],
        ],
    ]); ?>

</div>
