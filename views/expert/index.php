<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ExpertSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Experts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expert-index">
    
    <div class="row" style="padding-bottom: 15px;">
        <div class="col col-sm-2 pull-left">
            <h1 class="" style="margin: 0px;"><?= Html::encode($this->title) ?></h1>
        </div>
        
        <div class="col col-sm-2 pull-right text-right">
            <?= yii::$app->user->can('create-user') ? Html::a('<span class="glyphicon glyphicon-plus"></span> '.Yii::t('app', 'New'), ['create'], ['class' => 'btn btn-success']): '' ?>
        </div>
    </div>

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
