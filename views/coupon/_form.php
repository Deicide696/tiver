<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\TypeCoupon;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Coupon */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="coupon-form">

    <?php $form = ActiveForm::begin([
//        'enableAjaxValidation' => true,
//        'enableClientValidation' => false,
    ]); ?>
    
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'type_coupon_id')->dropDownList(ArrayHelper::map(TypeCoupon::find()->asArray()->all(), 'id', 'description'), ['prompt'=>'--Seleccione--']) ?>
 
        <div class="form-group required">
            <label class="control-label" for="coupon-type_coupon_id"></label>
            <select id="asignar2" class="form-control" name="asignar2" disabled>
                <option value = "0">--Seleccione--</option>
            </select>
            <div class="help-block"></div>
        </div>
        <br>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    
    <?php ActiveForm::end(); ?>
</div>
<?php
$this->registerJs('
 $("#coupon-type_coupon_id").change(function(){
    var e = $("#coupon-type_coupon_id").val();
    if(e == ""){
        $("#asignar2").val("0");
        document.getElementById("asignar2").disabled=true;
    }else{
        $.ajax({
            type: "POST",
            url: "'.Url::to(['coupon/getmodel']).'",
            data: { select: e },
            dataType: "json",
            success:function (data){
                document.getElementById("asignar2").disabled=false;
                $("#asignar2").html(data.scri).fadeIn();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error");
            }
        }); 
    }
 }); 
');
?>