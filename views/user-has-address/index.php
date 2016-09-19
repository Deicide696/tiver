<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserHasAddressSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Has Addresses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-has-address-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User Has Address', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'user.first_name',
        		'user.last_name',
            'address.address',
        		'address.tower_apartment',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
