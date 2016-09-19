<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'last_name') ?>

    <?= $form->field($model, 'identification') ?>

    <?= $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'password') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'birth_date') ?>

    <?php // echo $form->field($model, 'receive_interest_info') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'last_login') ?>

    <?php // echo $form->field($model, 'imei') ?>

    <?php // echo $form->field($model, 'created_data') ?>

    <?php // echo $form->field($model, 'rol_idrol') ?>

    <?php // echo $form->field($model, 'gender_id') ?>

    <?php // echo $form->field($model, 'type_identification_id') ?>

    <?php // echo $form->field($model, 'city_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
