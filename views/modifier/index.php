<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ModifierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Modifiers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modifier-index">

    <div class="row" style="padding-bottom: 15px;">
        <div class="col col-sm-2 pull-left">
            <h1 class="" style="margin: 0px;"><?= Html::encode($this->title) ?></h1>
        </div>

        <div class="col col-sm-2 pull-right text-right">
            <?= yii::$app->user->can('create-user') ? Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('app', 'New'), ['create'], ['class' => 'btn btn-success']) : '' ?>
        </div>
    </div>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'description',
            [
                'attribute' => 'Precio adicional',
                'format' => 'raw',
                'value' => 'price',
                'format' => 'Currency',
            ],
            'duration',
            'serviceHasModifier.service.name',
            [
                'attribute' => 'Activo',
                'format' => 'raw',
                'value' => function($searchModel) {
                    $valido = true;
                    if ($searchModel->status == 0)
                        $valido = false;
                    if ($valido)
                        return Yii::$app->params['iconEnabled'];
                    else
                        return Yii::$app->params['iconDisabled'];
                }
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {update}'],
        ],
        'options' => ['class' => 'table-responsive'],
    ]);
    ?>

</div>
