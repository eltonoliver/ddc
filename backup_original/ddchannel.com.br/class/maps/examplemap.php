<?php
include_once('googlemap.php');

?>
<html>
<body>
<div id="map" style="width:400px; height:400px;" >
<?php
$map=new GOOGLE_API_3();
$map->center_lat='28.022900221052016'; // set latitude for center location
$map->center_lng='73.3011245727539'; // set langitude for center location
$map->zoom=14;
echo $map->showmap();
?>
</div>
</body>
</html>
