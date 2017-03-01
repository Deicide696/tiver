<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CreditCardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Medios de pago';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-card-index">

    <div class="row" style="padding-bottom: 15px;">
        <div class="col col-sm-5 pull-left">
            <h1 class="" style="margin: 0px;"><?= Html::encode($this->title) ?></h1>
        </div>
    </div>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'hash',
            [
                'attribute' => 'Activo',
                'format' => 'raw',
                'value' => function($searchModel) {
                    $valido = true;
                    if ($searchModel->enable == 0)
                        $valido = false;
                    if ($valido)
                        return Yii::$app->params['iconEnabled'];
                    else
                        return Yii::$app->params['iconDisabled'];
                }
            ],
            'created_date',
            'user.first_name',
            'user.last_name',
            ['class' => 'yii\grid\ActionColumn'],
        ],
        'options' => ['class' => 'table-responsive'],
    ]);
    ?>

</div>
