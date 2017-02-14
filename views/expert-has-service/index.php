<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ExpertHasServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Especialistas & servicios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expert-has-service-index">

    <div class="row" style="padding-bottom: 15px;">
        <div class="col col-sm-5 pull-left">
            <h1 class="" style="margin: 0px;"><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col col-sm-2 pull-right text-right">
            <?= yii::$app->user->can('create-expert') ? Html::a('<span class="glyphicon glyphicon-plus"></span> '.Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']): '' ?>
        </div>
    </div>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'expert.name',
            'expert.last_name',
            'service.name',
            'service.categoryService.description',
            'qualification',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
