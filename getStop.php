<?php

error_reporting(0);
ini_set('display_errors', 0);

header('Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=utf-8');
header("Content-type:application/json");


function file_get_contents_curl($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$real = curl_exec($ch);

	return $real;
}
function FormatName($Gare){
    $Gare = preg_replace("/\([^)]+\)/","",$Gare);
	$search = array("Saint", "Ville", " ", "-", "_", "/", "\\", "\'", "(", ")", "À", "Á", "Â", "Ã", "Ä", "Å", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï", "Ò", "Ó", "Ô", "Õ", "Ö", "Ù", "Ú", "Û", "Ü", "Ý", "à", "á", "â", "ã", "ä", "å", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï", "ð", "ò", "ó", "ô", "õ", "ö", "ù", "ú", "û", "ü", "ý", "ÿ");
	$replace = array("st", "", "",  "",  "",  "",         "",   "",   "",  "",  "A", "A", "A", "A", "A", "A", "C", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "Y", "a", "a", "a", "a", "a", "a", "c", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "o", "u", "u", "u", "u", "y", "y");
	$Gare = str_replace($search, $replace, $Gare);
	$Gare = strtolower($Gare);

	return $Gare;
}
// ------------------
//----
$url = [];
$url['sncf'] = 'https://data.sncf.com/explore/dataset/referentiel-gares-voyageurs/download/?format=json&timezone=Europe/Berlin&lang=fr';
$url['sncb'] = 'https://opendata.bruxelles.be/explore/dataset/gares-sncb/download/?format=json&timezone=Europe/Berlin&lang=fr';
$url['te'] = 'https://train-empire.com/fr/api/getAllStations.php?auth=xxx';


if (is_file('geojson.json') && (filemtime('geojson.json') + 60) > filemtime('report.json')){

    $json = file_get_contents('geojson.json');
    echo $json;

} else {

    $echo = [];

    // SNCF
    $json = file_get_contents_curl($url['sncf']);
    $json = json_decode($json);

    foreach($json as $stop){
        $code = FormatName($stop->fields->gare_alias_libelle_fronton);
        $name = $stop->fields->gare_alias_libelle_fronton;

        if (isset($stop->fields->latitude_entreeprincipale_wgs84)){
            $echo['stop_points'][$code]['stop_point']['uic_code'] = substr($stop->fields->uic_code, 2);
            $echo['stop_points'][$code]['stop_point']['name'] = $name;
            
            $echo['stop_points'][$code]['stop_point']['coord']['lat'] = $stop->fields->latitude_entreeprincipale_wgs84;
            $echo['stop_points'][$code]['stop_point']['coord']['lon'] = $stop->fields->longitude_entreeprincipale_wgs84;
        }
    }

    //SNCB
    $json = file_get_contents_curl($url['sncb']);
    $json = json_decode($json);

    foreach($json as $stop){
        $code = FormatName($stop->fields->name);
        $name = $stop->fields->name;

        $echo['stop_points'][$code]['stop_point']['uic_code'] = substr($stop->fields->id, 10);
        $echo['stop_points'][$code]['stop_point']['name'] = $name;

        $echo['stop_points'][$code]['stop_point']['coord']['lat'] = $stop->fields->latitude;
        $echo['stop_points'][$code]['stop_point']['coord']['lon'] = $stop->fields->longitude;

        $i++;
    }

    //DE
    $json = file_get_contents('de_geojson.json');
    $json = json_decode($json);

    foreach($json as $stop){
        $code = FormatName($stop->BEZEICHNUNG);
        $name = $stop->BEZEICHNUNG;

        $echo['stop_points'][$code]['stop_point']['uic_code'] = "";
        $echo['stop_points'][$code]['stop_point']['name'] = $name;

        $echo['stop_points'][$code]['stop_point']['coord']['lat'] = $stop->GEOGR_BREITE;
        $echo['stop_points'][$code]['stop_point']['coord']['lon'] = $stop->GEOGR_LAENGE;

        $i++;
    }

    //CH
    $json = file_get_contents('ch_geojson.json');
    $json = json_decode($json);

    foreach($json as $stop){
        $code = FormatName($stop->Remark);
        $name = $stop->Remark;

        $echo['stop_points'][$code]['stop_point']['uic_code'] = "";
        $echo['stop_points'][$code]['stop_point']['name'] = $name;

        $echo['stop_points'][$code]['stop_point']['coord']['lat'] = $stop->Latitude;
        $echo['stop_points'][$code]['stop_point']['coord']['lon'] = $stop->Longitude;

        $i++;
    }

    //Contribute
    $json = file_get_contents('contribute.json');
    $json = json_decode($json);

    foreach($json->stop_points as $stop){

        $code = FormatName($stop->stop_point->name);
        $name = $stop->stop_point->name;

        $echo['stop_points'][$code]['stop_point']['uic_code'] = '';
        $echo['stop_points'][$code]['stop_point']['name'] = $name;

        $echo['stop_points'][$code]['stop_point']['coord']['lat'] = $stop->stop_point->coord->lat;
        $echo['stop_points'][$code]['stop_point']['coord']['lon'] = $stop->stop_point->coord->lon;
    }

    // Train Empire
    $json = file_get_contents_curl($url['te']);
    $json = json_decode($json);

    $r_echo = [];
    $r_echo['stop_points'] = [];

    $list = [];

    foreach($json as $pays){
        foreach($pays as $stop){
            $code = FormatName($stop->name);

            $list[$code] = $stop->name;
        }
    }

    foreach($json as $pays){
        foreach($pays as $stop){
            $code = FormatName($stop->name);

            if (isset($echo['stop_points'][$code])){
                $r_echo['stop_points'][] = $echo['stop_points'][$code];
                unset($list[$code]);
            }
        }
    }

    //Create Auto Report
    $r_list = [];

    foreach($list as $stop){
        $el = [];
        $el['name'] = $stop;
        $el['code'] = '';

        $r_list[] = $el;
    }



    $r_echo = json_encode($r_echo);
    file_put_contents('geojson.json', $r_echo);

    $r_list = json_encode($r_list);
    file_put_contents('report.json', $r_list);

    echo $r_echo;
}