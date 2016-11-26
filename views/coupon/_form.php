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

    <?php $form = ActiveForm::begin(); ?>
    
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'type_coupon_id')->dropDownList(ArrayHelper::map(TypeCoupon::find()->asArray()->all(), 'id', 'description')) ?>
        <div class="form-group required">
            <label class="control-label" for="asignar1">Asignar (Categoria/Servicio)</label>
            <select id="asignar1" class="form-control" name="asignar1">
                <option value="0">Seleccione</option>
                <option value="1">Categoria de Servicio</option>
                <option value="2">Servicio</option>
            </select>
            <div class="help-block"></div>
        </div>
        <div class="form-group required">
            <select id="asignar2" class="form-control" name="asignar2" disabled>
                <option value = "0">Seleccione</option>
            </select>
            <div class="help-block"></div>
        </div>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    
    <?php ActiveForm::end(); ?>
</div>
<?php
$this->registerJs('
 $("#asignar1").change(function(){
    var e=$("#asignar1").val();
    if(e == 0){
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