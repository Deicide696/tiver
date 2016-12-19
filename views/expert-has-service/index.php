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

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nueva relación', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
