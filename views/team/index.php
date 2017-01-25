<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TeamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Teams');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Team'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
<?php
    if (Yii::$app->user->can('super-admin')) {
        $columns =  [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'description',
            'created_date',
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
        $columns =  [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'description',
            'created_date',

            ['class' => 'yii\grid\ActionColumn'],
        ];
    }
    ?>
    
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($searchModel) {
            ($searchModel->enable == 0) ? $class = ['class' => 'danger'] : $class = [];
            return $class;
        },
        'columns' => $columns,
    ]); ?>
<?php Pjax::end(); ?></div>
