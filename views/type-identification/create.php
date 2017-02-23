<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TypeIdentification */

$this->title = Yii::t('app', 'New').' '.Yii::t('app', 'Type Identification');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Types Identification'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="type-identification-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
