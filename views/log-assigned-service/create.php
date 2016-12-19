<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LogAssignedService */

$this->title = 'Create Log Assigned Service';
$this->params['breadcrumbs'][] = ['label' => 'Log Assigned Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-assigned-service-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
