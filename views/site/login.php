<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

?>
<br>
<br>
<br>
<div class="site-login col-sm-4 col-lg-4">
    <div class="row col-xs-12 col-sm-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
        ],
    ]); 
    ?>
        <?= $form->field($model, 'username') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'rememberMe')->checkbox([
        ]) ?>

        <div class="form-group text-center">
            <?= Html::submitButton('Login', ['class' => 'col-xs-12 col-sm-6 btn btn-primary', 'name' => 'login-button']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
