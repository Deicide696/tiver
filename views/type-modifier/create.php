<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TypeModifier */

$this->title = 'Create Modifier Type';
$this->params['breadcrumbs'][] = ['label' => 'Modifier Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modifier-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
