<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Coupon */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Coupons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
    $valido = true;
    if($model->enable == 0){$valido=false;}
    if($valido){$icon= Yii::$app->params['iconEnabled-left'];}
    else{$icon= Yii::$app->params['iconDisabled-left'];}	
    
    $valido2 = true;
    if ($model->used == 0) {$valido2 = false;}
    if ($valido2) {$icon2 = Yii::$app->params['iconEnabled-left'];} 
    else {$icon2 = Yii::$app->params['iconDisabled-left'];}
?>
<div class="coupon-view">

    <h1> <?= Yii::t('app', 'Coupon').": <b>".Html::encode($this->title) ?></b></h1>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'code',
            'amount',
            [
                'label' => Yii::t('app', 'Type Coupon ID'),
                'attribute' => 'typeCoupon.description'
            ],
            'created_date',
            'updated_date',
            'due_date',
            [
                'attribute' => 'used',
                'format' => 'raw',
                'value' => $icon2
            ],
            [
                'attribute' => 'enable',
                'format' => 'raw',
                'value' => $icon
            ],
        ],
    ]) ?>
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    
</div>
