<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LogAssignedService */

$this->title = 'Update Log Assigned Service: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Log Assigned Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="log-assigned-service-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
