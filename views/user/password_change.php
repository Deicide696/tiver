<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\assets\AppAsset;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Cambiar contraseña';
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<br>
<br>
<div class="user-form col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-3 col-lg-6">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 text text-center" style="padding-top: 3px;">
            <img src="<?php echo Url::base(); ?>/assets/tiver/logo_horizontal.png" alt="logo" class="">
        </div>
    </div>
    <br>
    <br>
    <div class="alert alert-warning text text-center hidden" role="alert"><i class="fa fa-exclamation-triangle"></i> las contraseñas no coinciden</div>
    <?php if (isset($model)): ?>
        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

            <?= $form->field($model, 'password')->passwordInput(['id' => 'password', 'maxlength' => 64, 'tabindex' => '1', 'placeholder' => 'Nueva Contraseña'])->label(FALSE) ?>
            <?= $form->field($model, 'password')->passwordInput(['id' => 'confirm-password', 'maxlength' => 64, 'tabindex' => '2', 'placeholder' => 'Confirmar Constraseña'])->label(FALSE) ?>
            <br>
            <div class="row col-sm-offset-3 col-sm-6 text-center">
            <?= Html::submitButton('Cambiar contraseña', ['class' => 'btn btn-success btn-block', 'name' => 'login-button']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    <?php endif ?>

</div>
<?php
$this->registerJs("
   
    $('form').submit(function(e) {
        $('.alert-warning').addClass('hidden');
        if ($('#password').val() != $('#confirm-password').val()) {
            $('.alert-warning').removeClass('hidden');
            e.preventDefault();
            return false;
        }
    });
    ", View::POS_END);
?>