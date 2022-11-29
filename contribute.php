<?php

function FormatName($Gare){
     $Gare = preg_replace("/\([^)]+\)/","",$Gare);
     $search = array("Saint", "Ville", " ", "-", "_", "/", "\\", "\'", "(", ")", "À", "Á", "Â", "Ã", "Ä", "Å", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï", "Ò", "Ó", "Ô", "Õ", "Ö", "Ù", "Ú", "Û", "Ü", "Ý", "à", "á", "â", "ã", "ä", "å", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï", "ð", "ò", "ó", "ô", "õ", "ö", "ù", "ú", "û", "ü", "ý", "ÿ");
     $replace = array("st", "", "",  "",  "",  "",         "",   "",   "",  "",  "A", "A", "A", "A", "A", "A", "C", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "Y", "a", "a", "a", "a", "a", "a", "c", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "o", "u", "u", "u", "u", "y", "y");
     $Gare = str_replace($search, $replace, $Gare);
     $Gare = strtolower($Gare);

     return $Gare;
 }

if (isset($_GET['get_report'])){
     $contribute = file_get_contents('contribute.json');
     $contribute = json_decode($contribute, true);

     $report = file_get_contents('report.json');
     $report = json_decode($report, true);



     echo file_get_contents('report.json');
     exit;
}

if (isset($_POST['name']) && isset($_POST['lat']) && isset($_POST['lon'])){
     
     if (!is_file('contribute.json')){
          file_put_contents('contribute.json', '[]');
     }
     
     $contribute = file_get_contents('contribute.json');
     $contribute = json_decode($contribute, true);

     $json = file_get_contents('geojson.json');
     $json = json_decode($json, true);

     $report = file_get_contents('report.json');
     $report = json_decode($report, true);

     if (!in_array($_POST['name'], $contribute)){
          $el = [];
          $el['stop_point'] = [];
          $el['stop_point']['uic_code'] = '105';
          $el['stop_point']['name'] = $_POST['name'];
          $el['stop_point']['coord'] = [];
          $el['stop_point']['coord']['lat'] = $_POST['lat'];
          $el['stop_point']['coord']['lon'] = $_POST['lon'];

          $contribute['stop_points'][] = $el;
          $json['stop_points'][] = $el;

          // --- supprimer de report.json
          if (($key = array_search($_POST['name'], $report)) !== false) {
               unset($report[$key]);
          }

          //

          $contribute = json_encode($contribute);
          file_put_contents('contribute.json', $contribute);

          unlink('geojson.json');

          echo 'OK';
          exit;
     }
     
     
     
     exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    
     <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" crossorigin=""/>
     <link rel="stylesheet" href="main.css">
     <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" crossorigin=""></script>
     <script  crossorigin=""></script>
</head>
<body>

     <div id="contribute" class="contribute">
          <h2>Contribuer</h2>
          <i>Voici la liste des gares où la position exacte est inconnue</i>
          <div id="list" class="list"></div>
     </div>

     <div id="thanks" class="contribute thanks" style="left: -440px;">
          <h1>Merci !</h1>
          <h1>😄</h1>
     </div>

     <div id="place" class="contribute place" style="display: none;">
          <h2 id="place_name"></h2>
          <span onclick="back_place()" class="link">🠔 Retour</span>
               <br><br>
          <span id="about" class="about"> Faites glisser le point sur la carte pour le placer sur le point central de la gare. Essayez d'être le plus précis possible 😉</span>
               <br>
          <b id="search"></b>
               <br><br>
               <div class="hr"></div>
               <br>
          <span>
               Position du point
                    <br>
               lat : <span class="pos" id="poslat" style="margin-left: 15px;">47</span>
                    <br>
               lon : <span class="pos" id="poslon">1</span>
          </span>
               <br><br>
               <div class="hr"></div>
               <br>
          <button style="background-color: #2674c6;" onclick="save_place()"><span>Sauvegarder la position</span></button>
     </div>

     <div id="map" class="map_contribute">
          
     </div>

    <script src="contribute.js"></script>
    
</body>
</html>