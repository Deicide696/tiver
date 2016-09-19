<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ServiceHistorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="service-history-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'address') ?>

    <?= $form->field($model, 'state') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'time') ?>

    <?php // echo $form->field($model, 'lat') ?>

    <?php // echo $form->field($model, 'lng') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'qualification') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'observations') ?>

    <?php // echo $form->field($model, 'created_date') ?>

    <?php // echo $form->field($model, 'expert_id') ?>

    <?php // echo $form->field($model, 'cancel_reason_id') ?>

    <?php // echo $form->field($model, 'problem_type_id') ?>

    <?php // echo $form->field($model, 'service_id') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'coupon_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
