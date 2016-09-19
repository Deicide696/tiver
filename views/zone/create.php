<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\City;


//GMAPS
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\Polygon;


/* @var $this yii\web\View */
/* @var $model app\models\Zone */

$this->title = 'Create Zone';
$this->params['breadcrumbs'][] = ['label' => 'Zones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zone-create">

    <h1><?= Html::encode($this->title) ?></h1>

<?php
$form = ActiveForm::begin ( [ 
		'id' => 'active-form',
		'options' => [ 
			
				'enctype' => 'multipart/form-data' 
		]
]
 );
?>
	<?= $form->field($model,'name'); ?>
	
	<?php 	
	$ciudades=City::find()->all(); 
	//use yii\helpers\ArrayHelper;
	$listData=ArrayHelper::map($ciudades,'id','name');
	echo $form->field($model, 'city_id')->dropDownList($listData);
	?>
	
	<?= Html::hiddenInput('vortex', '', ['id' => 'vortex']); ?>
<?php 


$div=" <a class='btn btn-danger' onClick='new initialize().removePolygon()'>Reiniciar mapa</a><br><br>";

    print $div;


$coord = new LatLng(['lat' => 4.624335, 'lng' => -74.063644]);
$map = new Map([
    'center' => $coord,
    'zoom' => 12,
]);
 
$script="
var polygon=new google.maps.Polygon();
var i_markers=0;
var collection = new Array();

	// This event listener calls addMarker() when the map is clicked.
  google.maps.event.addListener(gmap0, 'click', function(e) {
    placeMarker(e.latLng, gmap0);
  });

  function placeMarker(position, map) {
  	collection[i_markers] = position;
  	drawPolygon();
    var marker = new google.maps.Marker({
      position: position,
      draggable:true,
       icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png',
    
      map: map
    });
    var j=i_markers;  
   marker.addListener('drag',function(event) {
      
    });

    marker.addListener('dragend',function(event) {
    	collection[j] = event.latLng;
       drawPolygon();
    });

i_markers++;
  }

  function drawPolygon(){
	removePolygon();
	polygon = new google.maps.Polygon({
    paths: collection,
    strokeColor: '#3c5e8e',
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: '#5fa7df',
  	fillOpacity: 0.35
	});
	polygon.setMap(gmap0);
	document.getElementById('vortex').value = JSON.stringify(collection);
  }

function removePolygon(){
	polygon.setMap(null);
}
";
$map->appendScript($script);


 
// Display the map -finally :)
echo $map->display();
print "<br>"
?>

<?= Html::submitButton('Crear', ['class'=> 'btn btn-success']) ;?>

<?php 
ActiveForm::end ();
?>

</div>
