<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TypeModifier */

$this->title = 'Update Modifier Type: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Modifier Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="modifier-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
