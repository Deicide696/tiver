<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Weekday */

$this->title = 'Create Weekday';
$this->params['breadcrumbs'][] = ['label' => 'Weekdays', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="weekday-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
