<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Weekday;

/* @var $this yii\web\View */
/* @var $model app\models\Schedule */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="schedule-form">

    <?php $form = ActiveForm::begin(); ?>
    
        <?php //  $form->field($model, 'expert_id')->hiddenInput(['value'=> $expert->id ])->label(false); ?>
        <?= $form->field($model, 'start_time')->input("time") ?>
        <?= $form->field($model, 'finish_time')->input("time") ?>
        <?= $form->field($model, 'date_created')->textInput() ?>
        <?= $form->field($model, 'expert_id')->textInput() ?>
        <?= $form->field($model, 'weekday_id')->dropDownList(ArrayHelper::map(Weekday::find()->asArray()->all(),'id','weekdays')); ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
