<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TypeHousingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Housing Types');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="housing-type-index">

    <div class="row" style="padding-bottom: 15px;">
        <div class="col col-sm-4 pull-left">
            <h1 class="" style="margin: 0px;"><?= Html::encode($this->title) ?></h1>
        </div>

        <div class="col col-sm-2 pull-right text-right">
            <?= yii::$app->user->can('create-user') ? Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('app', 'New'), ['create'], ['class' => 'btn btn-success']) : '' ?>
        </div>
    </div>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'housing_type',
            ['class' => 'yii\grid\ActionColumn'],
        ],
        'options' => ['class' => 'table-responsive'],
    ]);
    ?>

</div>
