function FormatName(gare){
	const search =["saint" , "-ville", " ", "-", "_", "/", "\\", "\'", "(", ")", "À", "Á", "Â", "Ã", "Ä", "Å", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï", "Ò", "Ó", "Ô", "Õ", "Ö", "Ù", "Ú", "Û", "Ü", "Ý", "à", "á", "â", "ã", "ä", "å", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï", "ð", "ò", "ó", "ô", "õ", "ö", "ù", "ú", "û", "ü", "ý", "ÿ", "✈"];
    const replace = ["st", "",  "",  "",  "",  "",  "", "",  "",    "",  "A", "A", "A", "A", "A", "A", "C", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "Y", "a", "a", "a", "a", "a", "a", "c", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "o", "u", "u", "u", "u", "y", "y", ""];
    
    gare = replaceAllArray( gare.toLowerCase().replace(/ *\([^)]*\) */g, "") , search, replace);

	return gare;
}

function ChangeUrl(title, url) {
    if (typeof (history.pushState) != "undefined") {
        let obj = { 
            Title: title, Url: url
        };
        history.pushState(obj, obj.Title, obj.Url);
    } 
}

function updateUrl(e, map){
    lat = map.getCenter()
    let title = 'DOcument';
    let url = '?' + map.getCenter().lat + ',' + map.getCenter().lng + ',' + e.target._zoom;
    ChangeUrl(title, url);
}
	

async function getStops(cmp){
    let url = 'getStop.php'; 

    let response = await fetch(url);	
    if (response.status === 200) {
        let text = await response.json();

        return text;
    } 
}

async function init(){
    const stop = await getStops();

    // Create Map
    document.getElementById('start').innerHTML = '<h1> MyLines </h1>';

    var defaultcoords = '?47,5,7';

    var queryString = window.location.search || defaultcoords;
    queryString = queryString.substring(1);
    var coords = queryString.split(",");
    console.log(coords);    

    var map = L.map('map').setView([coords[0], coords[1]], coords[2]);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | &copy; <a href="https://www.here.com/">HERE</a> | <a href="https://train-empire.com">Train Empire</a> | <a href="https://mylines.fr">MyLines</a> | <a href="contribute.php">Contribuer</a>' 
    }).addTo(map);

    map.on('zoom', function(e) {
        updateUrl(e, map);
    });
    map.on('drag', function(e) {
        updateUrl(e, map);
    });

// Place les points + Repport si non trouvé

    let u = 0;
    let lat;
    let lon;
    let name;

    // Show Point
    for (var i = 0; i < stop.stop_points.length; i++){

        lat = stop.stop_points[i].stop_point.coord.lat;
        lon = stop.stop_points[i].stop_point.coord.lon;
        name = stop.stop_points[i].stop_point.name;

        console.log(name);

        L.marker([lat, lon]).addTo(map)
                    .bindPopup(name);
    }    
}

function escapeRegExp(str){
    return str.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
}
function replaceAll(str, term, replace) {
    return str.replace(new RegExp(escapeRegExp(term), 'g'), replace);
}
function replaceAllArray(str, term, replace) {
    for (var i = 0; i < term.length; i++){
        str = replaceAll(str, term[i], replace[i])
    }
    return str;
}

init();