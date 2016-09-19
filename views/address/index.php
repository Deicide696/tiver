<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AddressSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Direcciones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="address-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nueva dirección', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
           	// 'id',
            'address',
            'tower_apartment',
     		'typeHousing.housing_type',
        	'custom_address',
            'lat',
            'lng',
        		'userHasAddress.user.first_name',
        		'userHasAddress.user.last_name',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
