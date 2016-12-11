<?php

use yii\helpers\Url;
use kartik\growl\Growl;
/* @var $this yii\web\View */

$this->title = 'Tiver';

if (isset($_GET['success']) && $_GET['success'] == true) {
    echo Growl::widget([
        'type' => Growl::TYPE_SUCCESS,
        'icon' => 'glyphicon glyphicon-ok-sign',
        'title' => '¡Recuperación exitosa!',
        'showSeparator' => true,
        'body' => 'EL correo de Recuperación de contraseña fue enviado exitosamente.'
    ]);
}
?>
<div class="site-index">

    <div class="jumbotron">
        <br><br>
        <h1>Tiver Backend</h1> 
        <br><br><br><br>
            <img src= "<?= Url::to('@web/img/logo_vertical.png') ?>" alt="">
        <br>
        <br>
    </div><?php ?>
</div>
