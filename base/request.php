<?php

function resetTEChecked(){
    $db = $GLOBALS["db"];

    $db->exec("UPDATE stop_points SET in_te = 0");
    $db->exec("DELETE FROM report");

    return 1;
}

function addStopPoint($uic_code, $name, $name_formated, $coord_lat, $coord_lon, $source){
    $db = $GLOBALS["db"];

    $req = $db->prepare("INSERT INTO stop_points(uic_code, name, name_formated, coord_lat, coord_lon, source) VALUES (?, ?, ?, ?, ?, ?)");
    $req->execute( array($uic_code, $name, $name_formated, $coord_lat, $coord_lon, $source));
    return $req;
}
function addReport($name, $name_formated){
    $db = $GLOBALS["db"];

    $req = $db->prepare("INSERT INTO report(name, name_formated) VALUES (?, ?)");
    $req->execute( array($name, $name_formated));
    return $req;
}
function addLines($epoque, $origine, $destination, $distance, $tension, $gabarit, $commentaire){
    $db = $GLOBALS["db"];

    $req = $db->prepare("INSERT INTO routes(epoque, origine, destination, origine_formated, destination_formated, distance, tension, gabarit, commentaire) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $req->execute( array($epoque, $origine, $destination, FormatName($origine), FormatName($destination), $distance, $tension, $gabarit, $commentaire));
    return $req;
}
function removeReport($name, $name_formated){
    $db = $GLOBALS["db"];

    $req = $db->prepare("DELETE FROM report WHERE name = ? OR name_formated = ?");
    $req->execute( array($name, $name_formated));
    return $req;
}
function removeLine($epoque){
    $db = $GLOBALS["db"];

    $req = $db->prepare("DELETE FROM routes WHERE epoque = ?");
    $req->execute( array($epoque));
    return $req;
}

function setTEChecked($name_formated){
    $db = $GLOBALS["db"];
    return $db->exec("UPDATE stop_points SET in_te = 1 WHERE name_formated = '$name_formated'");
}


function getTEStop(){
    $db = $GLOBALS["db"];

    $req = $db->prepare("SELECT * FROM stop_points WHERE in_te = 1");
    $req->execute( );
    return $req;
}
function getContribute(){
    $db = $GLOBALS["db"];

    $req = $db->prepare("SELECT * FROM stop_points WHERE source = 'contribute'");
    $req->execute( );
    return $req;
}
function getReport(){
    $db = $GLOBALS["db"];

    $req = $db->prepare("SELECT * FROM report");
    $req->execute( );
    return $req;
}