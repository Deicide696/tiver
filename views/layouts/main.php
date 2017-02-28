<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>

        <div class="wrap">
            <?php
            NavBar::begin([
                'brandLabel' => "<img src= " . Url::to("@web/img/logo_horizontal_w.png") . " class='logo_tiver'>",
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top'
                ]
            ]);
                if(Yii::$app->user->isGuest){
                    $menuItems[] = ['label' => 'Iniciar sesión', 'url' => ['/site/login']];
                } else {
                    if(Yii::$app->user->can('admin')) {
                        $menuItems[] = [
                            'label' => '<span class="glyphicon glyphicon-calendar"></span> '.Yii::t('app','Current Services'),
                            'items' => [
                                ['label' => Yii::t('app','Assigned'), 'url' => ['/assigned-service']],
                                ['label' => Yii::t('app','History'),'url' => ['/service-history']],
                            ]
                        ];
                        $menuItems[] = [
                            'label' => '<span class="glyphicon glyphicon-leaf"></span> Servicios',
                            'items' => [
                                ['label' => Yii::t('app','Categories'), 'url' => ['/category-service']],
                                ['label' => Yii::t('app','Services'), 'url' => ['/service']],
                                ['label' => Yii::t('app','Modifiers'), 'url' => ['/modifier/']],
                                ['label' => Yii::t('app','Modifiers & Services'), 'url' => ['/service-has-modifier']]
                            ]
                        ];
                        $menuItems[] = [
                            'label' => '<span class="glyphicon glyphicon-briefcase"></span> '.Yii::t('app', 'Experts'),
                            'items' => [
                                ['label' => Yii::t('app','Experts'), 'url' => ['/expert']],
                                ['label' => Yii::t('app','Availability'), 'url' => ['/schedule']],
                                ['label' => Yii::t('app','Experts & Services'), 'url' => ['/expert-has-service']]
                            ]
                        ];
                        $menuItems[] = [
                            'label' => '<span class="glyphicon glyphicon-cog"></span> '.Yii::t('app', 'Configuration'),
                            'items' => [
                                ['label' => Yii::t('app', 'Coupons'), 'url' => ['/coupon']],
                                ['label' => Yii::t('app', 'Zones'), 'url' => ['/zone']],
                                ['label' => Yii::t('app', 'Cities'), 'url' => ['/city']],
                                ['label' => Yii::t('app', 'Addresses'), 'url' => ['/address']],
                                ['label' => Yii::t('app', 'Housing Types'), 'url' => ['/type-housing']],
                                ['label' => Yii::t('app', 'Types Identification'), 'url' => ['/type-identification']],
                                ['label' => Yii::t('app', 'Modifier Types'), 'url' => ['/type-modifier']],
                            ]
                        ];
                        if(Yii::$app->user->can('super-admin')) {
                            $menuItems[] = [
                                'label' => '<span class="glyphicon glyphicon-lock"></span> '.Yii::t('app', 'Administration'), 
                                    'items' => [
                                        ['label' => Yii::t('app', 'Users'), 'url' => ['/user']],
                                        ['label' => Yii::t('app', 'Teams'), 'url' => ['/team']],
                                        ['label' => Yii::t('rbac-admin', 'Assignments'), 'url' => ['/admin']],
                                        ['label' => Yii::t('rbac-admin', 'Roles'), 'url' => ['/admin/role']],
                                        ['label' => Yii::t('rbac-admin', 'Permissions'), 'url' => ['/admin/permission']],
                                    ]
                            ];
                        } else {
                            $menuItems[] = [
                                'label' => '<span class="glyphicon glyphicon-lock"></span> Administración',
                                'items' => [
                                    ['label' => Yii::t('app', 'Users'), 'url' => ['/user']],
                                    ['label' => Yii::t('app', 'Teams'), 'url' => ['/team']],
                                ]
                            ];
                        }
                    }
                    
                    $menuItems[] = [
                        'label' => '<span class="glyphicon glyphicon-user"></span> <b>'.ucfirst(Yii::$app->user->identity->first_name).'</b>',
                        'items' => [
                            ['label' => '<span class="glyphicon glyphicon-off"></span> Cerrar sesión', 
                                'url' => ['/site/logout'],
                                'linkOptions' => [
                                    'data-method' => 'post'
                                ]
                            ]
                        ]
                    ];
                }
                echo Nav::widget([
                    'options' => ['class' => 'nav navbar-nav navbar-right'],
                    'items' => $menuItems,
                    'encodeLabels' => false,
                ]);
            NavBar::end();
            ?>

            <div class="container" style="padding-top: 70px">
                <?= Yii::$app->user->isGuest ? '' : Breadcrumbs::widget(['links' => isset($this->params ['breadcrumbs']) ? $this->params ['breadcrumbs'] : []]) ?>
                <?= $content ?>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p class="text-center">&copy; Tiver <?= date('Y') ?></p>
            </div>
        </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
