<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UserHasAddress */

$this->title = $model->user_id;
$this->params['breadcrumbs'][] = ['label' => 'User Has Addresses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-has-address-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'user_id' => $model->user_id, 'address_id' => $model->address_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'user_id' => $model->user_id, 'address_id' => $model->address_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Desea realmente eliminar este elemento?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'user_id',
            'address_id',
        ],
    ]) ?>

</div>
