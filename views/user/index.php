<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a('Nuevo usuario', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

        //    'id',
            'first_name',
            'last_name',
           // 'identification',
            'email:email',
            // 'password',
             'phone',
            // 'birth_date',
            // 'receive_interest_info',
              [
        		'attribute' => 'Activo',
        		'format' => 'raw',
        		'value' =>function($searchModel){
        		$valido=true;
        		if($searchModel->enable==0)
        			$valido=false;
        		if($valido)
        			return  Yii::$app->params['iconEnabled'];
        		else
        			return Yii::$app->params['iconDisabled'];
        		}
        		],
            // 'last_login',
             //'imei',
             //'fb_id',
        	//'tpaga_id',
               [
        		'attribute' => 'Fecha de creación',
        		'format' => 'raw',
        		'value'=>'created_date',
        		'format' => 'DateTime',
        		],
              [
        		'attribute' => 'Último ingreso',
        		'format' => 'raw',
        		'value'=>'last_login',
        		'format' => 'DateTime',
        		],
            // 'FK_id_rol',
            // 'FK_id_gender',
            // 'FK_id_type_identification',
            // 'FK_id_city',
        		[
        		'attribute' => 'Contraseña',
        		'format' => 'raw',
        		'value' =>function($searchModel){
        			return Html::a('Recuperar contraseña', ['request-password-reset?email='.$searchModel->email], ['class' => 'btn btn-danger', 
        			'data' => [
                'confirm' => '¿Desea recuperar la contraseña de este usuario?',
               ]]);
        		}
        		],

            ['class' => 'yii\grid\ActionColumn','template' => '{view} {update}'],
        ],
    ]); ?>

</div>
