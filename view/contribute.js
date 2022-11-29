function FormatName(gare){
	const search =["saint" , "-ville", " ", "-", "_", "/", "\\", "\'", "(", ")", "À", "Á", "Â", "Ã", "Ä", "Å", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï", "Ò", "Ó", "Ô", "Õ", "Ö", "Ù", "Ú", "Û", "Ü", "Ý", "à", "á", "â", "ã", "ä", "å", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï", "ð", "ò", "ó", "ô", "õ", "ö", "ù", "ú", "û", "ü", "ý", "ÿ", "✈"];
    const replace = ["st", "",  "",  "",  "",  "",  "", "",  "",    "",  "A", "A", "A", "A", "A", "A", "C", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "Y", "a", "a", "a", "a", "a", "a", "c", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "o", "u", "u", "u", "u", "y", "y", ""];
    
    gare = replaceAllArray( gare.toLowerCase().replace(/ *\([^)]*\) */g, "") , search, replace);

	return gare;
}

async function get_report(){
    let url = '?ctrl=getReport'; 
    let echo = '';

    let response = await fetch(url);	
    if (response.status === 200) {
        let text = await response.json();

        for (i = 0; i < text.length; i++){
            console.log(text[i].name);
            echo = echo + '<a class="overmouse" onclick="place(\'' + text[i].name.replace('\'', '’') + '\')"><div>' + text[i].name + '</div></a>';
        }

        document.getElementById('list').innerHTML = echo;
    } 
}

async function getStops(){
    let url = '?ctrl=getStop'; 

    let response = await fetch(url);	
    if (response.status === 200) {
        let text = await response.json();

        return text;
    } 
}

async function place(point){
    let name = document.getElementById('place_name');
    let place = document.getElementById('place');
    let search = document.getElementById('search');

    let url = '?ctrl=getPos&q=' + point; 
    let echo = '';

    place.style.display = 'block';
    name.innerHTML = point;
    search.innerHTML = 'Chargement...';

    //Place le point (le point change de position si trouve qqch)
    marker = new L.marker([48, 2], {draggable:'true'});
    marker.on('dragend', function(event){
        var marker = event.target;
        var position = marker.getLatLng();
        marker.setLatLng(new L.LatLng(position.lat, position.lng),{draggable:'true'});
        map.panTo(new L.LatLng(position.lat, position.lng));
        document.getElementById('poslat').innerHTML = map.getCenter().lat
        document.getElementById('poslon').innerHTML = map.getCenter().lng
    });
    map.addLayer(marker);

    try{
        let response = await fetch(url);	
        if (response.status === 200) {
            let text = await response.json();

            if (text.items[0]){
                var lat = text.items[0].position.lat;
                var lon = text.items[0].position.lng;
        
                map.setView([lat, lon], 17);
                marker.setLatLng([lat, lon]);
                document.getElementById('poslat').innerHTML = lat
                document.getElementById('poslon').innerHTML = lon
        
                search.innerHTML = 'Une localisation à été trouvé !';
            } else {
                search.innerHTML = 'Aucun résultat';
            }
        }
    }
    catch(e){
        search.innerHTML = 'Aucun résultat';
    }
}


function back_place(){
    let place = document.getElementById('place');
    place.style.display = 'none';
    map.removeLayer(marker)
}

async function sub_place(){
    back_place();
    await get_report();
    await getStops();
}

async function save_place(){
    let name = document.getElementById('place_name').innerHTML;
    let poslat = document.getElementById('poslat').innerHTML;
    let poslon = document.getElementById('poslon').innerHTML;

    let url = '?ctrl=setContribute'; 

    let formData = new FormData();
    formData.append('name', name);
    formData.append('lat', poslat);
    formData.append('lon', poslon);

    let response = await fetch(url, {
                                        method: 'post',
                                        body: formData
                                    });	
    if (response.status === 200) {
        let text = await response.text();

        if (text == 'OK'){
            setTimeout(sub_place, 1000);
            document.getElementById('thanks').style.animation = 'thanks 2s ease';
        }
    } 
}

async function init(){
    // Create Map

    var defaultcoords = '?47,5,7';

    var queryString = defaultcoords;
    queryString = queryString.substring(1);
    var coords = queryString.split(",");

    map = L.map('map').setView([coords[0], coords[1]], coords[2]);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | &copy; <a href="https://www.here.com/">HERE</a> | <a href="https://train-empire.com">Train Empire</a> | <a href="https://github.com/MaisClement/MyLinesMap">Code Source</a>'
    }).addTo(map);

    await get_report();
}

init();