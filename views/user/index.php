<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\growl\Growl;
use kartik\dialog\Dialog;
use kartik\dialog\DialogAsset;
 DialogAsset::register($this);
// widget with default options
echo Dialog::widget([
    'options' =>[
        'title' => "<div class='text-center'><b>Confimación</h3></b>",
        'type' => Dialog::TYPE_PRIMARY,
        'btnOKClass' => 'btn-danger',
        'btnCancelLabel' => 'Cancelar'
    ]
    
]);
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
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

<div class="user-index">
    <div class="row" style="padding-bottom: 15px;">
        <div class="col col-sm-2 pull-left">
            <h1 class="" style="margin: 0px;"><?= Html::encode($this->title) ?></h1>
        </div>
        
        <div class="col col-sm-2 pull-right text-right">
            <?= yii::$app->user->can('create-user') ? Html::a('<span class="glyphicon glyphicon-plus"></span> '.Yii::t('app', 'New'), ['create'], ['class' => 'btn btn-success']): '' ?>
        </div>
    </div>
    
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'first_name',
            'last_name',
            'email:email',
            'phone',
            [
                'attribute' => 'Activo',
                'format' => 'raw',
                'value' => function($searchModel) {
                    $valido = true;
                    if ($searchModel->enable == 0){
                        $valido = false;
                    }   
                    if($valido){
                        return Yii::$app->params['iconEnabled'];
                    } else {
                        return Yii::$app->params['iconDisabled'];
                    }
                }
            ],
            [
                'attribute' => 'Fecha de creación',
                'format' => 'raw',
                'value' => 'created_date',
                'format' => 'DateTime',
            ],
            [
                'attribute' => 'Último ingreso',
                'format' => 'raw',
                'value' => 'last_login',
                'format' => 'DateTime',
            ],
            [
                'attribute' => 'Contraseña',
                'format' => 'raw',
                'value' => function($searchModel) {
                    return Html::a('<span class="glyphicon glyphicon-refresh"></span> Restablecer', ['request-password-reset?email=' . $searchModel->email], ['class' => 'btn btn-danger btn-confirm',
                    ]);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn', 
                'headerOptions' => ['class' => 'button_h_center'],
                'visibleButtons' => [
                    'update' => \Yii::$app->user->can('edit-user'),
                    'delete' => \Yii::$app->user->can('delete-user'),
                ],
            ],
        ],
        'options' => ['class' => 'table-responsive'],
    ]);
    ?>
</div>
<?php
$js = <<< JS

$(".btn-confirm").on("click", function(e) {
var link = this;
 e.preventDefault();
    krajeeDialog.confirm("¿Seguro de querer restablecer la contraseña?", function (result) {
        if (result) {
            top.location=link.href;
        } else {
            return true;
        }
    });
});

JS;
// register your javascript
$this->registerJs($js);
?>