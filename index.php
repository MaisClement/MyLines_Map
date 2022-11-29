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
</head>
<body>

     <div id="map" class="map_main">
          <div class="start" id="start">
               <style>
                    .loader {
                         --color: #1F5EA0;
                         --size-mid: 6vmin;
                         --size-dot: 1.5vmin;
                         --size-bar: 0.4vmin;
                         --size-square: 3vmin;
                         position: absolute;
                         margin-left: -3vmin;
                    }
                    .loader::before,
                    .loader::after {
                         content: '';
                         box-sizing: border-box;
                         position: absolute;
                    }
                    .loader.--4::before {
                         height: var(--size-bar);
                         width: 6vmin;
                         background-color: var(--color);
                         animation: loader-4 0.8s cubic-bezier(0, 0, 0.03, 0.9) infinite;
                    }
                    @keyframes loader-4 {
                         0%, 44%, 88.1%, 100% {
                              transform-origin: left;
                         }	
                         0%, 100%, 88% {
                              transform: scaleX(0);
                         }	
                         44.1%, 88% {
                              transform-origin: right;
                         }
                         33%, 44% {
                              transform: scaleX(1);
                         }
                    }
                    .start {
                         width: 100vw;
                         height: 60vh;
                         padding-top: 40vh;     
                         background-size: cover;
                         background-position: center center;
                         font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                         background-color: #dddddd;
                         color: #297CD3;
                         text-align: center;
                         letter-spacing: 0.3px;
                    }
                    </style> 
               <h1> MyLines </h1>
               <div class="container" id="loader">
                    <i class="loader --4"></i>
               </div>
          </div>
    </div>


    <script src="main.js"></script>
    
</body>
</html>