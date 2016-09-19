<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\City;


//GMAPS
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\overlays\MarkerOptions;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\Polygon;
use dosamigos\google\maps\overlays\PolygonOptions;



/* @var $this yii\web\View */
/* @var $model app\models\Zone */

$this->title = 'Update Zone: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Zones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="zone-update">

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




    /*  $polygonOptions = new PolygonOptions([
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
      $map->addOverlay($polygon); */

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
foreach ($vertex as $vert) {
	$script.="placeMarker( {lat: ".$vert['lat'].", lng: ".$vert['lng']."}, gmap0);";
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


$map->appendScript($script);



// Display the map -finally :)
echo $map->display();
print "<br>"
?>

<?= Html::submitButton('Actualizar', ['class'=> 'btn btn-success']) ;?>

<?php 
ActiveForm::end ();
?>

</div>
