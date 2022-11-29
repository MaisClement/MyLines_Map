<?php

require_once('function.php');
require_once('request.php');

// Connexion à la base de données 

try {
    $dsn = "mysql:dbname=mylinesmap;host=127.0.0.1;charset=utf8";
    $usr = "php";
    $psw = "URpGkrT3XoI66EMN";
    $db = new pdo($dsn, $usr, $psw);
    
    $GLOBALS["db"] = $db;

} catch (Exception $error) {
    include('view/maintenance.php');
    exit;
}