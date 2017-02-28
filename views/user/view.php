<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = ucfirst($model->first_name). ' '. ucfirst($model->last_name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'first_name',
            'last_name',
            'identification',
            'email:email',
            'password',
            'phone',
            'birth_date',
            'receive_interest_info',
            'enable',
            'last_login',
            'imei',
            'fb_id',
            'tpaga_id',
            'created_date',
            'updated_date',
//            'FK_id_rol',
            'FK_id_gender',
            'FK_id_type_identification',
            'FK_id_city',
            'personal_code',
        ],
    ]) ?>

</div>
