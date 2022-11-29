<?php

$url = [];
$url['sncf'] = 'https://data.sncf.com/explore/dataset/referentiel-gares-voyageurs/download/?format=json&timezone=Europe/Berlin&lang=fr';
$url['sncb'] = 'https://opendata.bruxelles.be/explore/dataset/gares-sncb/download/?format=json&timezone=Europe/Berlin&lang=fr';
$url['te'] = 'https://train-empire.com/fr/api/getAllStations.php?auth=xxx';

    // SNCF
    $json = file_get_contents_curl($url['sncf']);
    $json = json_decode($json);

    foreach($json as $stop){
        try{
            $uic_code = substr($stop->fields->uic_code, 2);
            $name = $stop->fields->gare_alias_libelle_fronton;
            $name_formated = FormatName($stop->fields->gare_alias_libelle_fronton);

            $coord_lat = $stop->fields->latitude_entreeprincipale_wgs84;
            $coord_lon = $stop->fields->longitude_entreeprincipale_wgs84;
            $source = 'sncf';

            addStopPoint($uic_code, $name, $name_formated, $coord_lat, $coord_lon, $source);
        } catch (Exception $e){
            echo 'pas content' . $e . '<br>';

        }
    }

    //SNCB
    $json = file_get_contents_curl($url['sncb']);
    $json = json_decode($json);

    foreach($json as $stop){
        try{
            $uic_code = substr($stop->fields->id, 10);
            $name = $stop->fields->name;
            $name_formated = FormatName($stop->fields->name);

            $coord_lat = $stop->fields->latitude;
            $coord_lon = $stop->fields->longitude;
            $source = 'sncb';

            addStopPoint($uic_code, $name, $name_formated, $coord_lat, $coord_lon, $source);
        } catch (Exception $e){
            echo 'pas content' . $e . '<br>';

        }
    }

    //DE
    $json = file_get_contents('data/de_geojson.json');
    $json = json_decode($json);

    foreach($json as $stop){
        try{
            $uic_code = '';
            $name = $stop->BEZEICHNUNG;
            $name_formated = FormatName($stop->BEZEICHNUNG);

            $coord_lat = $stop->GEOGR_BREITE;
            $coord_lon = $stop->GEOGR_LAENGE;
            $source = 'de';

            addStopPoint($uic_code, $name, $name_formated, $coord_lat, $coord_lon, $source);
        } catch (Exception $e){
            echo 'pas content' . $e . '<br>';

        }
    }

    //CH
    $json = file_get_contents('data/ch_geojson.json');
    $json = json_decode($json);

    foreach($json as $stop){
        try{
            $uic_code = '';
            $name = $stop->Remark;
            $name_formated = FormatName($stop->Remark);
            
            $coord_lat = $stop->Latitude;
            $coord_lon = $stop->Longitude;
            $source = 'ch';

            addStopPoint($uic_code, $name, $name_formated, $coord_lat, $coord_lon, $source);
        } catch (Exception $e){
            echo 'pas content' . $e . '<br>';

        }
    }

    //Contribute
    $json = file_get_contents('data/contribute.json');
    $json = json_decode($json);

    foreach($json->stop_points as $stop){
        try{
            $uic_code = '';
            $name = $stop->stop_point->name;
            $name_formated = FormatName($stop->stop_point->name);
            
            $coord_lat = $stop->stop_point->coord->lat;
            $coord_lon = $stop->stop_point->coord->lon;
            $source = 'contribute';

            addStopPoint($uic_code, $name, $name_formated, $coord_lat, $coord_lon, $source);
        } catch (Exception $e){
            echo 'pas content' . $e . '<br>';
        }
    }

require('te_check.php');
require('update_line.php');