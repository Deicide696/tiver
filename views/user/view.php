<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->first_name;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php 
            //print Html::a('Delete', ['delete', 'id' => $model->id], [
            //'class' => 'btn btn-danger',
            //'data' => [
              //  'confirm' => '¿Desea realmente eliminar este elemento?',
                //'method' => 'post',
            //],
            //])
        ?>
    </p>

    <?php
        $valido = true;

        if($model->enable == 0)
        {
            $valido = false;
        }

        if($valido)
        {
        	$icon= Yii::$app->params['iconEnabled'];
        }
        
        else
        {
        	$icon= Yii::$app->params['iconDisabled'];
        }
        	
        $valido = true;

        if($model->receive_interest_info == 0)
        {
        	$valido = false;
        }
        
        if($valido)
        {
        	$icon2= Yii::$app->params['iconEnabled'];
        }

        else
        {
        	$icon2= Yii::$app->params['iconDisabled'];
        }
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
         //   'id',
            'first_name',
            'last_name',
            'identification',
            'email:email',
          //  'password',
            'phone',
            'birth_date',
             [
        		'attribute' => 'Recibir información de interés',
        		'format' => 'raw',
        		'value' =>$icon2
        		],
            [
        		'attribute' => 'Activo',
        		'format' => 'raw',
        		'value' =>$icon
        		],
            'last_login',
            'imei',
            'fb_id',
            'created_date',
            'updated_date',
            'rol.name',
            'gender.gender',
            'typeIdentification.description',
            'city.name',
        ],
    ]) ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
           	// 'id',
            'address',
            'tower_apartment',
     		'typeHousing.housing_type',
        	'custom_address',
            'lat',
            'lng',
        	//	'userHasAddress.user.first_name',
        		//'userHasAddress.user.last_name',
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
