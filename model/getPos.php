<?php

$q = $_GET['q'];

$search = array("&", "?", "=", '"');
$replace = array("", "", "",  "");
$q = str_replace($search, $replace, $q);

$apiKey = file_get_contents('config/.here_api_key.txt');

$url = 'https://geocode.search.hereapi.com/v1/geocode?q=' . $q . '&apiKey=' . $apiKey;

$json = file_get_contents_curl($url);
echo $json;