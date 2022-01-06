<link rel="stylesheet" href="<?= css; ?>/leaflet.css">
<link rel="stylesheet" href="<?= css; ?>/leaflet-routing-machine.css">
<?php
$id_ruta = $_POST['id_ruta'];

$sql = "SELECT p.descripcion, GROUP_CONCAT(p.latitud,',', p.longitud SEPARATOR '*') as coordenadas , p.nombre_lugar, p.descripcion, p.imagen_lugar
    FROM gon_rutas AS r
    LEFT JOIN gon_puntos AS p ON p.ruta_id = r.punto_id
    WHERE r.id_ruta = " . $id_ruta . " AND r.estado = 1";
$puntos = $db->query($sql)->fetch_first();
//var_dump($puntos);  

?>

<div id="map" class="map embed-responsive embed-responsive-16by9"></div>

<input type="hidden" id="coordenadas" value="<?= $puntos['coordenadas'] ?>">
<script>
    function handleError(e) {
        if (e.error.status === -1) {
            // HTTP error, show our error banner
            document.querySelector('#osrm-error').style.display = 'block';
            L.DomEvent.on(document.querySelector('#osrm-error-close'), 'click', function(e) {
                document.querySelector('#osrm-error').style.display = 'none';
                L.DomEvent.preventDefault(e);
            });
        }
    }


    var LeafIcon = L.Icon.extend({
        options: {
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41],
            // 		iconUrl: 'glyph-marker-icon.png',
            // 		iconSize: [35, 45],
            // 		iconAnchor:   [17, 42],
            // 		popupAnchor: [1, -32],
            // 		shadowAnchor: [10, 12],
            // 		shadowSize: [36, 16],
            // 		bgPos: (Point)
            className: '',
            prefix: '',
            glyph: 'home',
            glyphColor: 'white',
            glyphSize: '11px', // in CSS units
            glyphAnchor: [0, -7]
        }
    });
    var lime1Icon = new LeafIcon({
            iconUrl: '<?= files . '/puntero/lime1.png' ?>'
        }),
        lime2Icon = new LeafIcon({
            iconUrl: '<?= files . '/puntero/lime2.png' ?>'
        }),
        lime3Icon = new LeafIcon({
            iconUrl: '<?= files . '/puntero/lime3.png' ?>'
        }),
        blueIcon = new LeafIcon({
            iconUrl: '<?= files . '/puntero/blue.png' ?>'
        });

    window.LRM = {
        apiToken: 'pk.eyJ1IjoibGllZG1hbiIsImEiOiJjamR3dW5zODgwNXN3MndqcmFiODdraTlvIn0.g_YeCZxrdh3vkzrsNN-Diw'
    };

    var waypoints1 = new Array();

    //var centerPoint = [-16.52451140447856, -68.15931912332275];

    var coordenadas = $("#coordenadas").val();
    var aCoordenadas = coordenadas.split("*");

    var mitadCoor = Math.trunc(aCoordenadas.length / 2) - 1;
    var tamCoor = aCoordenadas.length;

    for (let index = 0; index < aCoordenadas.length; index++) {
        const element = aCoordenadas[index].split(",");
        waypoints1.push(L.latLng(element[0], element[1]));
        if (mitadCoor == index) {
            var centerPoint = [element[0], element[1]];
        }
    }

    //console.log(waypoints1);
    var greenIcon = new LeafIcon({
            iconUrl: '<?= files . '/puntero/green.png' ?>'
        }),
        redIcon = new LeafIcon({
            iconUrl: '<?= files . '/puntero/red.png' ?>'
        }),
        blueIcon = new LeafIcon({
            iconUrl: '<?= files . '/puntero/blue.png' ?>'
        });
    //Create leaflet map.
    //console.log(tamCoor--);
    var map = L.map('map', {
            editable: true,
            printable: true,
            downloadable: true
        }).setView(centerPoint, 7),
        waypoints = waypoints1;

    L.Routing.control({
        waypoints: waypoints,
        createMarker: function(i, waypoints, n) {
            if (tamCoor == i) {
                return L.marker(waypoints.latLng, {
                    icon: greenIcon
                });
            } else if (i == 0) {
                return L.marker(waypoints.latLng, {
                    icon: redIcon
                });
            } else {
                return L.marker(waypoints.latLng, {
                    icon: blueIcon
                });
            }
        }
    }).addTo(map);

    //L.Routing.errorControl(control).addTo(map);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?access_token=' + LRM.apiToken, {}).addTo(map);
    // Create custom measere tools instances.
    //var measure = L.measureBase(map, {});
    //measure.circleBaseTool.startMeasure()

    function afterRender(result) {
        return result;
    }

    function afterExport(result) {
        return result;
    }
</script>

<script src="<?= js; ?>/leaflet.js"></script>
<script src="<?= js; ?>/leaflet-routing-machine.js"></script>
<script src="<?= js; ?>/Leaflet.Icon.Glyph.js"></script>