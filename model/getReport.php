<?php

$el = [];
$i = 0;
if ($result = getReport()){
    while($obj = $result->fetch()){

        $el[$i] = [];
        $el[$i]['uic_code'] = $obj['uic_code'];
        $el[$i]['name'] = $obj['name'];
        $el[$i]['coord'] = [];
        $el[$i]['coord']['lat'] = $obj['coord_lat'];
        $el[$i]['coord']['lon'] = $obj['uic_code'];
        $el[$i]['source'] = $obj['source'];
        $i++;
    }
}

echo json_encode($el);