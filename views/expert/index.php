<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ExpertSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Especialistas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expert-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nuevo especialista', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
    		'export'=>FALSE,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

          
            'identification',
            'name',
            'last_name',
            'phone',
             'email:email',
            // 'password',
             'address',
             'zone.name',
            // 'created_date',
            // 'rol_idrol',
            // 'type_identification_idtype_identification',
        	     [
        		'attribute' => 'Activo',
        		'format' => 'raw',
        		'value' =>function($searchModel){
        		$valido=true;
        		if($searchModel->enable==0)
        			$valido=false;
        		if($valido)
        			return Yii::$app->params['iconEnabled'];
        		else
        			return Yii::$app->params['iconDisabled'];
        		}
        		],
        	//'zone_id',
        		/*[
        		'attribute' => 'Disponibilidad',
        		'format' => 'raw',
        		'value' => function ($searchModel) {
        			return '<div><a target=_blank href="../schedule?ScheduleSearch[recurrent_eventscol]=&ScheduleSearch[expert_id]='.$searchModel->id.'">Ver</a></div>';
        		},
        		],*/
        			

            ['class' => 'yii\grid\ActionColumn','template' => '{view} {update}'],
        ],
    ]); ?>

</div>
