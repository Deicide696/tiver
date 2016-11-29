<?php

use yii\helpers\Html;
use app\models\TypeIdentification;
use app\models\Gender;
use app\models\Rol;
use app\models\Zone;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Expert */

$this->title = 'Nuevo especialista';
$this->params['breadcrumbs'][] = ['label' => 'Especialistas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expert-create">
    <?php
    $form = ActiveForm::begin([
                'id' => 'active-form',
                'options' => [
                    'enctype' => 'multipart/form-data',
//                    'class' => 'text-center',
                ]
            ]);
    ?>
    <div class="container-fluid" >
        <h1><?= Html::encode($this->title) ?></h1>
        <div class="col-sm-6 pull-left">
         <?php
//    echo FileInput::widget([
//    'name' => 'attachment_53',
//    'pluginOptions' => [
//        'showCaption' => false,
//        'showRemove' => false,
//        'showUpload' => false,
//        'browseClass' => 'btn btn-primary btn-block',
//        'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
//        'browseLabel' =>  'Select Photo'
//    ],
//    'options' => ['accept' => 'image/*']
//]);
    
    
    ?>
    </div>
    </div>
    

    
    
   
    
     <?=  $form->field($model, 'path')->fileInput() ?>
    
    

    
<!--        <div class="kv-avatar center-block" style="width:200px">
            <input id="avatar-2" name="avatar-2" type="file" class="file-loading">
        </div>-->
        <?= $form->field($model, 'name'); ?>
        <?= $form->field($model, 'last_name'); ?>
        <?= $form->field($model, 'phone')->input('number'); ?>
        <?= $form->field($model, 'type_identification_id')->dropDownList(ArrayHelper::map(TypeIdentification::find()->all(), 'id', 'description')); ?>
        <?= $form->field($model, 'identification')->input('number'); ?>
        <?= $form->field($model, 'email')->input('email'); ?>
        <?= $form->field($model, 'address'); ?>
        <?= $form->field($model, 'enable')->checkbox(); ?>
        <?= $form->field($model, 'rol_id')->input("hidden")->label(false); ?>
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