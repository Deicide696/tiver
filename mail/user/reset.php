<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\Users;
use app\models\RestaurantSearch;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>

<div style="margin:0!important;padding:0!important">
    <div style="font-size:16px;color:#666">
        <table align="center" style="width:40%;margin:0 auto;font-size:inherit;line-height:inherit;padding: 5px 10px 5px 10px;border:0;border: 2px solid blue;border-radius: 20px 20px 20px 20px;"cellpadding="2">
            <tbody>
                <tr>
                    <td colspan="4" align="center">
                        <div align="center" style="display:block">
                              <br><img src="<?php //echo Url::base();
                              print "http://54.85.119.195";?>/assets/tiver/logo_horizontal.png" alt="logo" style="display:block;text-align:center" class="CToWUd"><br>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" align="center" style="font-weight: bold;">¡Olvidaste tu contraseña!</td>
                </tr>
                <tr>
                    <td align="center" colspan="4"><span style="color: #3c5e8e;font-weight: lighter;"><?= $userName ?></span></td>
                </tr>
                <tr>
                    <td colspan="4" align="center">
                        <p style="font-weight: bold;font-size:16px">¿Deseas cambiar tu contraseña?</p>
                    </td>
                </tr>
                <tr>

                    <td align="center" colspan="4">
                        <a style="font-weight:bold;text-decoration:none;color:#243957;border: none;background-color: transparent;" href="<?= Url::toRoute(['user/reset-password', 'token' => $token],true) ?>">Haz click aqui</a>
<?php // $form = ActiveForm::begin([ 'class'=>"send_token"  , 'action' => \Yii::$app->urlManager->createAbsoluteUrl('users/password-reset') ]);  ?>
<!--
                        <input type="hidden" name="email" value="<?php // $email ?>" >
                        <input type="hidden" name="token" value="<?php // $token ?>" >
                        <span valign="top" style=""></span>&nbsp;
                        <input style="font-weight:bold;text-decoration:none;color:rgb(115,72,121);border: none;background-color: transparent;" type="submit" value="Haz click aqui">-->

<?php // ActiveForm::end();  ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="1"></td>
                    <td colspan="2">
                        <br>
                      
                    </td>
                    <td colspan="1"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
