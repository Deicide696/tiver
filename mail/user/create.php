<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\MmzUsers;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
//$insModelUser = new MmzUsers();
?>
<?php $form = ActiveForm::begin(['class'=>"contact-form",'id' => 'contact','action' => Yii::$app->urlManager->createUrl(['about/contact'])]); ?>
	<div style="margin:0!important;padding:0!important">
		<div style="font-size:16px;color:#666">
			<table align="center" style="width:40%;margin:0 auto;font-size:inherit;line-height:inherit;padding: 5px 10px 5px 10px;border:0;border: 2px solid blue;border-radius: 20px 20px 20px 20px;"cellpadding="2">
				<tbody>
					<tr>
						<td align="center">
								<div align="center" style="display:block">
								 <br><img src="<?php //echo Url::base();
                              print "http://54.85.119.195";?>/assets/tiver/logo_horizontal.png"alt="" style="display:block;text-align:center" class="CToWUd"><br>
							</div>
						</td>
					</tr>
					<tr>
						<td align="center" style="font-weight: bold;">¡Bienvenido a Tiver!</td>
					</tr>
					<tr>
						<td align="center"><span style="color: #243957;font-weight: lighter;"><?=$model->shortenName($model->first_name,$model->last_name)?></span></td>
					</tr>
					<tr>
						<td align="center">
							<p style="font-weight: bold;font-size:16px">Gracias por registrarte</p>
						</td>
					</tr>
					<tr>
						<td align="center">
							<!-- <p style="font-weight: bold;font-size:16px">¡Entra y empieza a reservar <a href="http://mimeza.com/" style="color: #5e2744;font-weight: lighter;" >Ya</a>!</p> -->
						</td>
					</tr>
					<tr>
						<td align="center">
							<br>
							
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
<?php ActiveForm::end(); ?>