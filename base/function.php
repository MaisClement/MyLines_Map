<?php

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
	$search = array("Saint", "Ville", " ", "-", "_", "/", "\\", "\'", "'", "(", ")", "À", "Á", "Â", "Ã", "Ä", "Å", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï", "Ò", "Ó", "Ô", "Õ", "Ö", "Ù", "Ú", "Û", "Ü", "Ý", "à", "á", "â", "ã", "ä", "å", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï", "ð", "ò", "ó", "ô", "õ", "ö", "ù", "ú", "û", "ü", "ý", "ÿ");
	$replace = array("st",   "",      "",  "",  "",  "",  "",   "",   "",  "",  "",  "A", "A", "A", "A", "A", "A", "C", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "Y", "a", "a", "a", "a", "a", "a", "c", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "o", "u", "u", "u", "u", "y", "y");
	$Gare = str_replace($search, $replace, $Gare);
	$Gare = strtolower($Gare);

	return $Gare;
}

function csvRead($csv){
    $file = fopen($csv, 'r');
    while (!feof($file) ) {
        $line[] = fgetcsv($file, 0, ";");
    }
    fclose($file);
    return $line;
}
function rTime($str){
    $h = (int) substr($str, 0, 2);
    $m = (int) substr($str, 3, 2);
    $h = $h * 60;
    $time = $h + $m;
    return $time;
}