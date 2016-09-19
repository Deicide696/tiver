<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ScheduleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="schedule-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'start_time') ?>

    <?= $form->field($model, 'finish_time') ?>

    <?= $form->field($model, 'date_created') ?>

    <?= $form->field($model, 'expert_id') ?>

    <?php // echo $form->field($model, 'weekday_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
