<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Gender;
use app\models\TypeIdentification;
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <div class="row">
        <?= $form->field($model, 'FK_id_type_identification', ['options' => ['class' => 'col-sm-6 col-md-6 col-lg-6']])->dropDownList(ArrayHelper::map(TypeIdentification::find()->all(), 'id', 'description')) ?>
        <?= $form->field($model, 'identification', ['options' => ['class' => 'col-sm-6 col-md-6 col-lg-6']])->textInput(['maxlength' => true]) ?>
    </div>
    <div class="row">
        
         <?= $form->field($model, 'FK_id_gender', ['options' => ['class' => 'col-sm-6 col-md-6 col-lg-6']])->dropDownList(ArrayHelper::map(Gender::find()->all(), 'id', 'gender')) ?>
            <?= $form->field($model, 'phone', ['options' => ['class' => 'col-sm-6 col-md-6 col-lg-6']])->textInput(['maxlength' => true]) ?>
    </div>
    <div class="row">
        <?= $form->field($model, 'birth_date', ['options' => ['class' => 'col-sm-6 col-md-6 col-lg-6']])->input("date") ?>
        <?= $form->field($model, 'receive_interest_info', ['options' => ['class' => 'col-sm-6 col-md-6 col-lg-6']])->dropDownList(ArrayHelper::map([['id' => '0', 'name' => 'No'], ['id' => '1', 'name' => 'Si']], 'id', 'name')); ?>
    </div>
    
    <div class="row">
         <?= $form->field($model, 'imei', ['options' => ['class' => 'col-sm-6 col-md-6 col-lg-6']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'password', ['options' => ['class' => 'col-sm-6 col-md-6 col-lg-6']])->passwordInput(['maxlength' => true]) ?>
    </div>
    <br>
    <div class="row col-sm-offset-3 col-sm-6 text-center">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success btn-block' : 'btn btn-primary btn-block']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
