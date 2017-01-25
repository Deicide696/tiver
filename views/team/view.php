<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Team */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Teams'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
    $valido = true;
    if($model->enable == 0){$valido=false;}
    if($valido){$icon= Yii::$app->params['iconEnabled-left'];}
    else{$icon= Yii::$app->params['iconDisabled-left'];}	
if (Yii::$app->user->can('super-admin')) {
        $columns =  [
            'id',
            'name',
            'description',
            'created_date',
            'updated_date',
            [
                'attribute' => 'enable',
                'format' => 'raw',
                'value' => $icon
            ],
        ];
    } else {
        $columns =  [
            'id',
            'name',
            'description',
            'created_date',
            'updated_date',
        ];
    }
?>
<div class="team-view">

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
        'attributes' => $columns,
    ]) ?>

</div>
