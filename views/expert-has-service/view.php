<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ExpertHasService */

$this->title = 'Relación';
$this->params['breadcrumbs'][] = ['label' => 'Especialistas & servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expert-has-service-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'expert_id' => $model->expert_id, 'service_id' => $model->service_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'expert_id' => $model->expert_id, 'service_id' => $model->service_id], [
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
            'expert.name',
        	'expert.last_name',
            'service.name',
            'qualification',
        ],
    ]) ?>

</div>
