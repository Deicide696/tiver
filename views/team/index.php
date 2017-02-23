<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TeamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Teams');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-index">

   <div class="row" style="padding-bottom: 15px;">
        <div class="col col-sm-2 pull-left">
            <h1 class="" style="margin: 0px;"><?= Html::encode($this->title) ?></h1>
        </div>
        
        <div class="col col-sm-2 pull-right text-right">
            <?= yii::$app->user->can('create-user') ? Html::a('<span class="glyphicon glyphicon-plus"></span> '.Yii::t('app', 'New'), ['create'], ['class' => 'btn btn-success']): '' ?>
        </div>
    </div>
    
<?php
    if (Yii::$app->user->can('super-admin')) {
        $columns =  [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'description',
            'created_date',
            [
                'attribute' => 'enable',
                'format' => 'raw',
                'value' => function($searchModel) {
                    $valido = true;
                    if ($searchModel->enable == 0){$valido = false;}
                    if ($valido) {return Yii::$app->params['iconEnabled'];} 
                    else {return Yii::$app->params['iconDisabled'];}
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
            
        ];
    } else {
        $columns =  [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'description',
            'created_date',

            ['class' => 'yii\grid\ActionColumn'],
        ];
    }
    ?>
    
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($searchModel) {
            ($searchModel->enable == 0) ? $class = ['class' => 'danger'] : $class = [];
            return $class;
        },
        'columns' => $columns,
    ]); ?>
<?php Pjax::end(); ?></div>
