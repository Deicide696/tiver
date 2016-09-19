<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CreditCardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Medios de pago';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-card-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a('Create Credit Card', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

          //  'id',
            'hash',
           	[
        		'attribute' => 'Activo',
        		'format' => 'raw',
        		'value' =>function($searchModel){
        		$valido=true;
        		if($searchModel->enable==0)
        			$valido=false;
        		if($valido)
        			return  Yii::$app->params['iconEnabled'];
        		else
        			return Yii::$app->params['iconDisabled'];
        		}
        		],
            'created_date',
            'user.first_name',
            'user.last_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
