<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AssignedService */

$this->title = 'Create Assigned Service';
$this->params['breadcrumbs'][] = ['label' => 'Assigned Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assigned-service-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
