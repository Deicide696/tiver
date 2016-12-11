<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Inicia sesión';
$this->params['breadcrumbs'][] = $this->title;

?>
<br>
<br>
<br>
<div class="user-form col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6">
    <h3 class="text-center">Inicia sesión para acceder a<br>TIVER</h3>
    <br>
    <br>
    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
    
        <?= $form->field($model, 'username', ['inputOptions' =>['placeholder' => 'Correo Electrónico']])->label(FALSE) ?>
        <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Contraseña'])->label(FALSE) ?>
        <?= $form->field($model, 'rememberMe')->checkbox() ?>
        <div style="color:#999;margin:1em 0">
            Si no recuerdas tu contraseña puedes <?= Html::a('restablecerla', ['site/request-password-reset']) ?>.
        </div>
        <div class="row col-sm-offset-3 col-sm-6 text-center">
            <?= Html::submitButton('Ingresar', ['class' => 'btn btn-success btn-block', 'name' => 'login-button']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>