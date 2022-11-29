<?php

$el = [];
$i = 0;
if ($result = getTEStop()){
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

file_put_contents('data/geojson.json', json_encode($el));

echo json_encode($el);