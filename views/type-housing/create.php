<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TypeHousing */

$this->title = 'Create Housing Type';
$this->params['breadcrumbs'][] = ['label' => 'Housing Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="housing-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
