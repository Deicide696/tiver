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
    
    <div class="row" style="padding-bottom: 15px;">
        <div class="col col-sm-2 pull-left">
            <h1 class="" style="margin: 0px;"><?= Html::encode($this->title) ?></h1>
        </div>
        
        <div class="col col-sm-2 pull-right text-right">
            <?= yii::$app->user->can('create-user') ? Html::a('<span class="glyphicon glyphicon-plus"></span> '.Yii::t('app', 'New'), ['create'], ['class' => 'btn btn-success']): '' ?>
        </div>
    </div>
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
