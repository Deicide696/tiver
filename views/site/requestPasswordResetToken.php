<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Recupere su contraseña';
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<br>
<br>
<div class="site-request-password-rese col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6">

    <h3 class="text-center"><?= Html::encode($this->title) ?></h3>
    <br>
    <p>Por favor, escriba su correo electrónico. Se enviará un enlace para restablecer la contraseña.</p>
    <br>
    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
        <br>
        <div class="row col-sm-offset-3 col-sm-6 text-center">
            <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary btn-block']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
