<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ExpertHasService */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="expert-has-service-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'expert_id')->textInput() ?>

    <?= $form->field($model, 'service_id')->textInput() ?>

    <?= $form->field($model, 'qualification')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
