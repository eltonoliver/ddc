Hi Viewer,

Thanks to download my class. Well i have written this for all web developers who want to 
integrate google map. moslty we get requirement for every website like contact us page,
map directions.

This class is very easy to customized but plz ask me before any modification or you can 
hire me to make your google map successful.

You need not to get any google map api if you use this class. so its easy to transfer files for 
one domain to other.

How to Use This Class:

download the zip and archives on a folder.

View source: 

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

Please take a look on examplemap.php and markerexample.php.

You can take support on this class at php.sandeepkumar@gmail.com

Thanks a lot.
Enjoy Coding.