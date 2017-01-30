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
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-3"></div>
    <div class="col-lg-4 col-md-4 col-sm-6" style="margin-top: 4%;">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 text text-center" style="">
                <img src="<?php echo Url::base();?>/assets/tiver/logo_horizontal.png" alt="logo" class="">
            </div>
        </div>
        <div class="alert alert-warning text text-center hidden" role="alert"><i class="fa fa-exclamation-triangle"></i> las contraseñas no coinciden</div>
        <?php if (isset($model)): ?>
            <?php $form = ActiveForm::begin([
                'id' => 'reset-password-form',
//                'action' => Url::to(['users/password-change'], true),
                'options' => ['class' => '','style'=>'background-color: #ffffff;padding: 50px 50px 0px 50px;margin: 50px 0px 50px 0px;color:#000;padding-bottom: 70px;border-radius: 15px'],
            ]); ?>
                <div class="row center">

                </div>
                <div class="row">
                    <?= $form->field($model, 'password')->passwordInput([ 'id' => 'password' ,'maxlength' => 64,'tabindex' => '1'])->label('Nueva contraseña') ?>
                    <?= $form->field($model, 'password')->passwordInput([ 'id' => 'confirm-password' ,'maxlength' => 64,'tabindex' => '2'])->label('Confirmación nueva contraseña') ?>
                </div>
                <div class="row text-center">
                    <?= Html::submitButton('Cambiar contraseña', ['class' => 'btn btn-primary ', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        <?php endif ?>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-3"></div>
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