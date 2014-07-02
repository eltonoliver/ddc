<?php
include_once('googlemap.php');

?>

<html>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 
<style>
.teste {
	font-size:20px;
	color:#F00;
	font-family:Verdana, Geneva, sans-serif;
}
</style>
<body>
<div id="map" style="width:960px; height:530px;">
<?php
$map=new GOOGLE_API_3();
$map->center_lat='-3.072295'; // set latitude for center location
$map->center_lng='-59.973679'; // set langitude for center location
$map->zoom=12;
$map->instance=1;

//marker information

// marker 1
$lat='-3.133842'; // latitude
$lng='-60.020165'; // longitude
$isclickable='true';
$title="Adrianópolis";
$info="<b>Adrianópolis</b><br/><img src='airplane-sport.png' align='left' hspace='10'>O bairro mais xibata de Manaus. Voce concorda?<br/><a href='#'>Saiba mais</a>.";
$map->addMarker($lat,$lng,$isclickable,$title,$info);


// marker 2
$lat='-3.099464'; // latitude
$lng='-60.010071'; // longitude
$isclickable='true';
$title="Centro";
$info="<div class='teste'><b>This is example of custom icon for the marker.  <blink>User</blink>, enjoy this great way to add custom icon. if would like to get my professional service.contact me via my profile.</b></div>";
$icon='airplane-sport.png';
$map->addMarker($lat,$lng,$isclickable,$title,$info,$icon);

echo $map->showmap();
?>
</div>
</body>
</html>
