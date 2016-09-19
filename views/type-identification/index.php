<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TypeIdentificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Type Identifications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="type-identification-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Type Identification', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'description',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
