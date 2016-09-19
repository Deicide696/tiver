<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UserHasAddress */

$this->title = 'Create User Has Address';
$this->params['breadcrumbs'][] = ['label' => 'User Has Addresses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-has-address-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
