<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Coupon */

$this->title = 'Nuevo Cupon';
$this->params['breadcrumbs'][] = ['label' => 'Coupons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="coupon-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
