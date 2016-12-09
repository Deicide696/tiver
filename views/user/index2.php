<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'first_name',
            'last_name',
            'identification',
            'email:email',
            // 'password',
            // 'phone',
            // 'birth_date',
            // 'receive_interest_info',
            // 'enable',
            // 'last_login',
            // 'imei',
            // 'fb_id',
            // 'tpaga_id',
            // 'created_date',
            // 'updated_date',
            // 'FK_id_rol',
            // 'FK_id_gender',
            // 'FK_id_type_identification',
            // 'FK_id_city',
            // 'personal_code',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
