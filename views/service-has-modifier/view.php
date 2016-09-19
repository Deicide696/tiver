<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ServiceHasModifier */

$this->title = "Relación";
$this->params['breadcrumbs'][] = ['label' => 'Modificadores & servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-has-modifier-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'service_id' => $model->service_id, 'modifier_id' => $model->modifier_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'service_id' => $model->service_id, 'modifier_id' => $model->modifier_id], [
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
            'service_id',
            'modifier_id',
        ],
    ]) ?>

</div>
