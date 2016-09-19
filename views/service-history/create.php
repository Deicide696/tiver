<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ServiceHistory */

$this->title = 'Create Service History';
$this->params['breadcrumbs'][] = ['label' => 'Service Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
