<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CouponSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Coupons');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
    if (Yii::$app->user->can('super-admin')) {
        $columns = [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'code',
            [
                'attribute' => 'created_date',
                'format' => 'DateTime',
            ],
            [
                'attribute' => 'due_date',
                'format' => 'DateTime',
            ],
            [
                'attribute' => 'used',
                'format' => 'raw',
                'value' => function($searchModel) {
                    $valido = true;
                    if ($searchModel->enable == 0){$valido = false;}
                    if ($valido) {return Yii::$app->params['iconEnabled'];} 
                    else {return Yii::$app->params['iconDisabled'];}
                }
            ],
            [
                'attribute' => 'enable',
                'format' => 'raw',
                'value' => function($searchModel) {
                    $valido = true;
                    if ($searchModel->enable == 0){$valido = false;}
                    if ($valido) {return Yii::$app->params['iconEnabled'];} 
                    else {return Yii::$app->params['iconDisabled'];}
                }
            ],
             ['class' => 'yii\grid\ActionColumn'],
        ];
    } else {
        $columns = [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'code',
            [
                'attribute' => 'created_date',
                'format' => 'DateTime',
            ],
            [
                'attribute' => 'due_date',
                'format' => 'DateTime',
            ],
            [
                'attribute' => 'used',
                'format' => 'raw',
                'value' => function($searchModel) {
                    $valido = true;
                    if ($searchModel->enable == 0){$valido = false;}
                    if ($valido) {return Yii::$app->params['iconEnabled'];} 
                    else {return Yii::$app->params['iconDisabled'];}
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ];
    }

?>

<div class="coupon-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('app', 'New Coupon'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($searchModel) {
            if (($searchModel->used == 0) && ($searchModel->enable == 0)) {
                $class = ['class' => 'danger'];
            } elseif ($searchModel->used == 0) {
                $class = ['class' => 'info'];
            } else {
                $class = [];
            }
            return $class;
        },
        'columns' => $columns ])
    ?>
    <?php Pjax::end(); ?>
</div>
