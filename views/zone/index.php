<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ZoneSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//GMAPS
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\overlays\MarkerOptions;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\Polygon;
use dosamigos\google\maps\overlays\PolygonOptions;

$this->title = 'Zones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zone-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Zone', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],


        'name',
        [
        'attribute' => 'Ciudad',
        'format' => 'raw',
        'value' => 'city.name',
        ],

        ['class' => 'yii\grid\ActionColumn'],
        ],
        ]); ?>

    <?php 
    $coord = new LatLng(['lat' => 4.624335, 'lng' => -74.063644]);
    $map = new Map([
        'center' => $coord,
        'zoom' => 12,
        ]);

  
 $colors[]="#96c2e7";
  $colors[]="#205e92";
   $colors[]="#205e92";
    $colors[]="#5fa7df";
     $colors[]="#00CFFE";


foreach ($vertex as $verte) {
    # code...

    foreach ($verte as $vert) {
        $coord = new LatLng(['lat' => $vert['lat'],'lng' => $vert['lng']]);   
        $coords[]=$coord;
        $marker = new Marker(['position' => $coord]);
        $marker->setOptions(new MarkerOptions(['icon'=>'http://maps.google.com/mapfiles/ms/icons/blue-dot.png']));
        $map->addOverlay($marker);
      

   }
         $polygonOptions = new PolygonOptions([
            'fillColor' => $colors[rand(0,sizeof($colors)-1)],
            'fillOpacity' => '0.35',
            'strokeColor' => '#3c5e8e',
            'strokeOpacity' => '0.8',
            'strokeWeight' => '2',
            ]);

           $polygon = new Polygon([
            'paths' => $coords, 
            ]);

           $polygon->setOptions($polygonOptions);

            // Add it now to the map
           $map->addOverlay($polygon); 
           unset($coords);


}

// Display the map -finally :)
   echo $map->display();
   ?>

</div>
