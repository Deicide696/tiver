<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CreditCard */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Credit Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-card-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'hash',
            'enable',
            'created_date',
            'user_id',
        ],
    ]) ?>

</div>
