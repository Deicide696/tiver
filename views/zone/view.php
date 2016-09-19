<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

//GMAPS
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\overlays\MarkerOptions;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\Polygon;
use dosamigos\google\maps\overlays\PolygonOptions;


/* @var $this yii\web\View */
/* @var $model app\models\Zone */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Zones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zone-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
            'confirm' => '¿Desea realmente eliminar este elemento?',
            'method' => 'post',
            ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
            'name',
            'city.name',
            ],
            ]) ?>
        <?php 
      

        foreach ($vertex as $vert) {
            $coord = new LatLng(['lat' => $vert['lat'],'lng' => $vert['lng']]);   
            
        }
        if(!isset($coord)){
            $coord = new LatLng(['lat' => 4.624335, 'lng' => -74.063644]);
            $map = new Map([
                'center' => $coord,
                'zoom' => 12,
                ]);
          

        }else{
            $map = new Map([
                'center' => $coord,
                'zoom' => 13,
                ]);

        }



        foreach ($vertex as $vert) {
            $coord = new LatLng(['lat' => $vert['lat'],'lng' => $vert['lng']]);   
            $coords[]=$coord;
            $marker = new Marker(['position' => $coord]);
            $marker->setOptions(new MarkerOptions(['icon'=>'http://maps.google.com/mapfiles/ms/icons/blue-dot.png']));
            $map->addOverlay($marker);
        }




        if(isset($coords)){
           $polygonOptions = new PolygonOptions([
            'fillColor' => '#5fa7df',
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
       }



// Display the map -finally :)
       echo $map->display();
       ?>


   </div>
