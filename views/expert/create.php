<?php

use yii\helpers\Html;
use app\models\TypeIdentification;
use app\models\Gender;
use app\models\Zone;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Expert */

$this->title = Yii::t('app', 'New'). ' '. Yii::t('app', 'Expert');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Experts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expert-create">
    <div class="row col-xs-12 col-sm-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php
        $form = ActiveForm::begin([
            'id' => 'active-form',
            'options' => [
                'enctype' => 'multipart/form-data',
            ]
        ]);
    ?>
        <div class="row form-group" >
            <div class="col-xs-12 col-sm-6 pull-right ">
                <input type="hidden" name="Expert[path]" value="">
                <?php
                    echo FileInput::widget([
                        'id' => 'expert-path',
                        'name' => 'Expert[path]',
                        'pluginOptions' => [
                            'showCaption' => false,
                            'showRemove' => false,
                            'showUpload' => false,
                            'browseClass' => 'btn btn-primary btn-block',
                            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                            'browseLabel' =>  'Foto de Perfil'
                        ],
                        'options' => ['accept' => 'image/*']
                    ]);
                ?>
            </div>
        </div>
        <?= $form->field($model, 'name'); ?>
        <?= $form->field($model, 'last_name'); ?>
        <?= $form->field($model, 'phone')->input('number'); ?>
        <?= $form->field($model, 'type_identification_id')->dropDownList(ArrayHelper::map(TypeIdentification::find()->all(), 'id', 'description')); ?>
        <?= $form->field($model, 'identification')->input('number'); ?>
        <?= $form->field($model, 'email')->input('email'); ?>
        <?= $form->field($model, 'address'); ?>
        <?= $form->field($model, 'enable')->checkbox(); ?>
        <?php // $form->field($model, 'rol_id')->input("hidden")->label(false); ?>
        <?= $form->field($model, 'gender_id')->dropDownList(ArrayHelper::map(Gender::find()->all(), 'id', 'gender')); ?>
        <?= $form->field($model, 'zone_id')->dropDownList(ArrayHelper::map(Zone::find()->all(), 'id', 'name')); ?>
        <?= $form->field($model, 'password')->input('password'); ?>
        <?= $form->field($model, 'password_repeat')->input('password'); ?>

        <?= Html::submitButton('Crear', ['class' => 'btn btn-success']); ?>

    <?php ActiveForm::end(); ?>
</div>
<?php

$scrip= <<< EOT
    var btnCust = '<button type="button" class="btn btn-default" title="Add picture tags" ' + 
    'onclick="alert(\'Call your custom code here.\')">' +
    '<i class="glyphicon glyphicon-tag"></i>' +
    '</button>'; 
$("#avatar-2").fileinput({
    overwriteInitial: true,
    maxFileSize: 1500,
    showClose: false,
    showCaption: false,
    showBrowse: false,
    browseOnZoneClick: true,
    removeLabel: '',
    removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
    removeTitle: 'Cancel or reset changes',
    elErrorContainer: '#kv-avatar-errors-2',
    msgErrorClass: 'alert alert-block alert-danger',
    defaultPreviewContent: '<img src="/uploads/default_avatar_male.jpg" alt="Your Avatar" style="width:160px"><h6 class="text-muted">Click to select</h6>',
    layoutTemplates: {main2: '{preview} ' +  btnCust + ' {remove} {browse}'},
    allowedFileExtensions: ["jpg", "png", "gif"]
});   
   
EOT;

$this->registerJs($scrip);
?>