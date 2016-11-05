<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\widgets\Menu;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use kartik\sidenav\SideNav;
use yii\helpers\Url;

AppAsset::register ( $this );
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta charset="<?= Yii::$app->charset ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags()?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head()?>
</head>
<body>
<?php $this->beginBody()?>

<div class="wrap">
    <?php
				NavBar::begin ( [ 
						'brandLabel' => "<img src= ".Url::to("@web/img/logo_horizontal_w.png")." class='logo_tiver'>",
						'brandUrl' => Yii::$app->homeUrl,
						
						'options' => [ 
								'class' => 'navbar-inverse navbar-fixed-top' 
						] 
				] );
				echo Nav::widget ( [ 
						'options' => [ 
								'class' => 'navbar-nav navbar-right' 
						],
						'encodeLabels' => false,
						'items' => Yii::$app->user->isGuest ? [ 
								[ 
										'label' => 'Login',
										'url' => [ 
												'/site/login' 
										] 
								] 
						] : [ 
								[ 
										'label' => '<span class="glyphicon glyphicon-calendar"></span> Servicios asignados',
										'items' => [ 
												[ 
														'label' => 'Servicios actuales',
														'url' => [ 
																'/assigned-service' 
														] 
												],
												[
												'label' => 'Historial de servicios',
												'url' => [
														'/service-history'
												]
												],
										] 
								],
								[ 
										'label' => '<span class="glyphicon glyphicon-leaf"></span> Servicios',
										'items' => [ 
												[ 
														'label' => 'Categorías',
														'url' => [ 
																'/category-service' 
														] 
												],
												[ 
														'label' => 'Servicios',
														'url' => [ 
																'/service' 
														] 
												],
												[ 
														'label' => 'Modificadores',
														'url' => [ 
																'/modifier/' 
														] 
												],
												[ 
														'label' => 'Modificadores & servicios',
														'url' => [ 
																'/service-has-modifier' 
														] 
												] 
										] 
								],
								[ 
										'label' => '<span class="glyphicon glyphicon-briefcase"></span> Especialistas',
										'items' => [ 
												[ 
														'label' => 'Especialistas',
														'url' => [ 
																'/expert' 
														] 
												],
												[ 
														'label' => 'Disponibilidad',
														'url' => [ 
																'/schedule' 
														] 
												],
												
												[ 
														'label' => 'Especialistas & servicios',
														'url' => [ 
																'/expert-has-service' 
														] 
												] 
										] 
								]
								,
								[ 
										'label' => '<span class="glyphicon glyphicon-user"></span> Usuarios',
										'items' => [ 
												[ 
														'label' => 'Usuarios',
														'url' => [ 
																'/user' 
														] 
												],
												[ 
														'label' => 'Direcciones',
														'url' => [ 
																'/address' 
														] 
												],
												/*[ 
														'label' => 'Medios de pago',
														'url' => [ 
																'/credit-card' 
														] 
												],*/
												// [ 
												// 		'label' => 'Usuarios & direcciones',
												// 		'url' => [ 
												// 				'/user-has-address' 
												// 		] 
												// ] 
										] 
								]
								,
								[ 
										'label' => '<span class="glyphicon glyphicon-cog"></span> Parámetros',
										'items' => [
												[
												'label' => 'Cupones',
												'url' => [
														'/coupon'
												]
												],
												[ 
														'label' => 'Tipos de dirección',
														'url' => [ 
																'/type-housing' 
														] 
												],
												[ 
														'label' => 'Niveles de modificadores',
														'url' => [ 
																'/type-modifier' 
														] 
												],
												[ 
														'label' => 'Tipos de identificación',
														'url' => [ 
																'/type-identification' 
														] 
												],
												[ 
														'label' => 'Roles',
														'url' => [ 
																'/rol' 
														] 
												],
												[ 
														'label' => 'Zonas',
														'url' => [ 
																'/zone' 
														] 
												],
												[ 
														'label' => 'Ciudades',
														'url' => [ 
																'/city' 
														] 
												] 
										] 
								]
								,
								[ 
										'label' => Yii::$app->user->identity->first_name,
										'items' => [ 
												[ 
														'label' => '<span class="glyphicon glyphicon-off"></span> Cerrar sesión',
														'url' => [ 
																'/site/logout' 
														],
														'linkOptions' => [ 
																'data-method' => 'post' 
														] 
												] 
										] 
								] 
						]
						 
				]
				 );
				
				NavBar::end ();
				
				/*
				 * echo SideNav::widget([
				 * 'type' => SideNav::TYPE_DEFAULT,
				 * 'heading' => 'Menú de opciones',
				 *
				 * 'items' => [
				 * ['label' => 'Servicios asignados','icon' => 'shopping-cart', 'url' => ['/assigned-service']],
				 * ['label' => 'Servicios', 'icon' => 'th','items'=>[['label' => 'Categorías', 'url' => ['/category-service']],['label' => 'Servicios', 'url' => ['/service']],
				 * ['label' => 'Modificadores', 'url' => ['/modifier/']],['label' => 'Modificadores & servicios', 'url' => ['/service-has-modifier']],]],
				 * ['label' => 'Especialistas', 'icon' => 'certificate','items'=>[['label' => 'Especialistas', 'url' => ['/expert']], ['label' => 'Disponibilidad', 'url' => ['/schedule']],['label' => 'Servicios asignados', 'url' => ['/assigned-service']],['label' => 'Especialistas & servicios', 'url' => ['/expert-has-service']],]],
				 * ['label' => 'Usuarios', 'icon' => 'user','items'=>[ ['label' => 'Usuarios', 'url' => ['/user']],['label' => 'Direcciones', 'url' => ['/address']],['label' => 'Medios de pago', 'url' => ['/credit-card']],['label' => 'Servicios asignados', 'url' => ['/assigned-service']],]],
				 *
				 *
				 *
				 *
				 * ],
				 * ]);
				 */
				
				?>

    <div class="container">
    <?php
				
				?>
    
        <?=Breadcrumbs::widget ( [ 'links' => isset ( $this->params ['breadcrumbs'] ) ? $this->params ['breadcrumbs'] : [ ] ] )?>
        <?= $content?>
    </div>
	</div>

	<footer class="footer">
		<div class="container">
			<p class="pull-left">&copy; Tiver <?= date('Y') ?></p>

			<p class="pull-right"><?= Yii::powered() ?></p>
		</div>
	</footer>

<?php $this->endBody()?>
</body>
</html>
<?php $this->endPage()?>
