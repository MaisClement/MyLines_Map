<?php

$epoques = ['3b', '4a', '4b', '5a', '5b', '6'];

foreach($epoques as $epoque){

    $file = 'data/' . $epoque . '_lines.csv';
    if (is_file($file)){
        echo $epoque;

        removeLine($epoque);
        $csv = csvRead($file);

        //---------------------------------------------------------
        //On va récuperer toutes les lignes de l'export
        foreach ($csv as $row){

            //verification des données, si vide on met ''
            for ( $n = 0; $n <= 6; $n++ ){
                if( isset($row[$n]) == false) $row[$n] = '';
                $row[$n] = html_entity_decode($row[$n]);        
            }

            if (FormatName($row[0]) != 'depart'){
                addLines($epoque, $row[0], $row[1], str_replace(" km", "", $row[2]), $row[3], $row[4], $row[5]);
            }
        }
    }
}
echo 'OK';
exit;