<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ExpertSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="expert-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'identification') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'last_name') ?>

    <?= $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'password') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'enable') ?>

    <?php // echo $form->field($model, 'lat') ?>

    <?php // echo $form->field($model, 'lng') ?>

    <?php // echo $form->field($model, 'created_date') ?>

    <?php // echo $form->field($model, 'zone_id') ?>

    <?php // echo $form->field($model, 'type_identification_id') ?>

    <?php // echo $form->field($model, 'rol_id') ?>

    <?php // echo $form->field($model, 'gender_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
