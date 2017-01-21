<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\TypeCoupon;
use app\models\User;
use yii\helpers\ArrayHelper;
use kartik\growl\Growl;

if (isset($_POST['success']) && $_POST['success'] == 0) {
    echo Growl::widget([
        'type' => Growl::TYPE_DANGER,
        'title' => '¡Ocurrio un Error!',
        'icon' => 'glyphicon glyphicon-remove-sign',
        'body' => 'Verifique el formulario e intente crear un Cupón de nuevo.',
        'showSeparator' => true,
        'delay' => 0,
        'pluginOptions' => [
            'showProgressbar' => true,
            'placement' => [
                'from' => 'top',
                'align' => 'right',
            ]
        ]
    ]);
}

/* @var $this yii\web\View */
/* @var $model app\models\Coupon */
/* @var $form yii\widgets\ActiveForm */

?>
<br>
<div class="coupon-form">

    <?php $form = ActiveForm::begin(); ?>
    
        <input type="hidden" id="success" class="form-control" name="success" value="<?= isset($success)? $success: 1 ?>" maxlength="45">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
       
        <?= $form->field($model, 'type_coupon_id')
            ->dropDownList(ArrayHelper::map(TypeCoupon::find()->asArray()->all(), 'id', 'description'), ['prompt'=>'-- Seleccione --'])
            ->label("Crear por (Monto fijo/Categoria/Servicio)") ?>
        <?php 
//            $form->field($UserHasCoupon, 'user_id')->dropDownList(ArrayHelper::map(User::find()->orderBy("first_name,last_name")->asArray()->all(), 'id', 
//                function($model) {
//                    return $model['id'].'.- '.$model['first_name'].' '.$model['last_name'];
//                }), 
//                ['prompt'=>'-- Seleccione --'])->label("Asigne por");
        ?> 
        
        
       
        
        
        <div id="selectAsignar" class="form-group required" style="display: none;">
            <select id="asignar2" class="form-control" name="asignar2" disabled></select>
            <div class="help-block"></div>
        </div>
    
    
        <div id="amount" class="form-group field-amount" style="display: none;">
            <input type="text" id="amount" class="form-control" name="Coupon[amount]" placeholder="Ingrese un monto">

            <div class="help-block"></div>
        </div>
    
        <div id="percent" class="form-group field-coupon-discount" style="display: none;">
            <input type="text" id="coupon-discount" class="form-control" name="Coupon[discount]" placeholder="Porcentaje de descuento (1-100)%">
            <div class="help-block"></div>
        </div>
        
        <div class="form-group field-coupon-type_coupon_id required has-error">
            <label class="control-label" for="coupon-type_coupon_id">Asigne por (Usuario/Grupo/Todos)</label>
            <select id="coupon-type_coupon_id" class="form-control" name="Coupon[type_coupon_id]">
                <option value="">-- Seleccione --</option>
                <option value="1">Usuario</option>
                <option value="2">Grupo</option>
                <option value="3">Todos</option>
            </select>
            <div class="help-block">Tipo de Cupón no puede estar vacío.</div>
        </div>
        
        <?= $form->field($model, 'due_date')->input("date") ?>
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
    }else if(e == 1){
        $("#selectAsignar").hide();
        $("#amount").show();
        $("#percent").hide();
    }else{
        $.ajax({
            type: "POST",
            url: "'.Url::to(['coupon/getmodel']).'",
            data: { select: e },
            dataType: "json",
            success:function (data){
                $("#amount").hide();
                 $("#percent").show();
                $("#selectAsignar").show();
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