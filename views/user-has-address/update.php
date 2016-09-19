<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserHasAddress */

$this->title = 'Update User Has Address: ' . ' ' . $model->user_id;
$this->params['breadcrumbs'][] = ['label' => 'User Has Addresses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_id, 'url' => ['view', 'user_id' => $model->user_id, 'address_id' => $model->address_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-has-address-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
