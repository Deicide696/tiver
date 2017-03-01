<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AddressSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Addresses');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="address-index">

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
        'options' => ['class' => 'table-responsive'],
    ]); ?>

</div>
