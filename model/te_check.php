<?php

$url['te'] = 'https://train-empire.com/fr/api/getAllStations.php?auth=xxx';

resetTEChecked();

$json = file_get_contents_curl($url['te']);
$json = json_decode($json);

$r_echo = [];
$r_echo['stop_points'] = [];

$list = [];

foreach($json as $pays){
    foreach($pays as $stop){
        $name = $stop->name;
        $code = FormatName($stop->name);

        if (setTEChecked($code)){
            //OK
        } else {
            //TO REPORT
            //echo $stop->name . '<br>';
            addReport($name, $code);
        }
    }
}