<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TypeHousingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Housing Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="housing-type-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Housing Type', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'housing_type',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
