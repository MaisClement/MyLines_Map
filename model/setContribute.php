<?php 

if (isset($_POST['name']) && isset($_POST['lat']) && isset($_POST['lon'])){
     
     $name = $_POST['name'];
     $name_formated = FormatName($name);

     $uic_code = 105;
     $coord_lat = $_POST['lat'];
     $coord_lon = $_POST['lon'];
     $source = 'contribute';
     
     removeReport($name, $name_formated);
     addStopPoint($uic_code, $name, $name_formated, $coord_lat, $coord_lon, $source);

     // On créer le contribute.json

     $el = [];
     $i = 0;
     if ($result = getContribute()){
          while($obj = $result->fetch()){

               $el['stop_points'][$i] = [];
               $el['stop_points'][$i]['stop_point'] = [];
               
               $el['stop_points'][$i]['stop_point']['uic_code'] = $obj['uic_code'];
               $el['stop_points'][$i]['stop_point']['name'] = $obj['name'];
               $el['stop_points'][$i]['stop_point']['coord'] = [];
               $el['stop_points'][$i]['stop_point']['coord']['lat'] = $obj['coord_lat'];
               $el['stop_points'][$i]['stop_point']['coord']['lon'] = $obj['coord_lon'];
               $el['stop_points'][$i]['stop_point']['source'] = $obj['source'];
               $i++;
          }
     }

     file_put_contents('data/contribute.json', json_encode($el));

     echo 'OK';

}
?>