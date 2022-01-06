<?php
require configuration . '/poligono.php';
// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los formatos
$formato_textual = get_date_textual($_format);
$formato_numeral = get_date_numeral($_format);

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);

$id_ruta = (isset($_params[0])) ? $_params[0] : 0;


// Obtiene el rutas
$rutas = $db->select('z.*')//, COUNT(a.id_punto) as puntos')
            ->from('gon_rutas z')
           // ->join('gon_puntos a','z.id_ruta = a.ruta_id')
            ->where('z.id_ruta', $id_ruta)
            //->group_by('id_ruta')
            ->fetch_first();
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//AREAS
$coordenadas=trim($rutas['coordenadas'],'*');
$polygon = explode('*',$coordenadas);
foreach ($polygon as $nro => $poly) {
    $aux = explode(',',$poly);
    $aux2 = (round($aux[0],6)-0.000044).','.(round($aux[1],6)+0.00003);
  	//var_dump($aux2);exit();
    $polygon[$nro] = str_replace(',', ' ', $aux2);
}
$polygon[0] = str_replace(',', ' ', $polygon[$nro]);
$pointLocation = new pointLocation();
//;:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// ARMAR PUNTOS
$puntos = $db->query(' SELECT 
  (select count(ins.id_inscripcion) from ins_inscripcion ins  where pun.id_punto = ins.punto_id)AS asig_count
  ,pun.*
  FROM gon_puntos pun')->fetch();
//$puntos = $db->select('*')->from('gon_puntos')->fetch();
$t_clientes = '';$nombres = '';$total = 0;

//var_dump($puntos);exit();
$puntosDentro=array();
foreach($puntos as $row){
    //$aux2 = explode(',',$cliente['ubicacion']);
    $aux3 = $row['latitud'];//$aux2[0] + 0.00005;
    $aux4 = $row['longitud'];//$aux2[1] - 0.00003;
    $point = $aux3.' '.$aux4;
    $punto = $pointLocation->pointInPolygon($point, $polygon);
    if($punto == 'dentro'){
        //$coordenad = $t_clientes.'*'.$aux3.','.$aux4;
		//$coordenad = $coordenad.'*'.$punto1['latitud'].','.$punto1['longitud'];
		array_push($puntosDentro,$row);
		$t_clientes = $t_clientes.'*'.$aux3.'||'.$aux4.'||'.$row['nombre_lugar'].'||'.$row['id_punto'].'||'.$row['asig_count'];
		$nombres = $nombres.'*'.$row['nombre_lugar'];
        $total = $total + 1;
    }
}
//buscamos los puntos de la ruta
//$puntos = $db->select('nombre_lugar, latitud, longitud')->from('gon_puntos')->where('ruta_id',$id_ruta)->fetch();

?>
<?php require_once show_template('header-design'); ?>
    <link rel="stylesheet" href="<?= css; ?>/leaflet.css">
    <link rel="stylesheet" href="<?= css; ?>/leaflet-routing-machine.css">
    <link rel="stylesheet" href="<?= css; ?>/site.css">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Crear paradas</h2>
                <p class="pageheader-text"></p>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Paradas</a></li>
                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Crear parada</a></li>
                            <!--                            <li class="breadcrumb-item active" aria-current="page">Listar</li>-->
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

<div class="row">
    <!-- ============================================================== -->
    <!-- row -->
    <!-- ============================================================== -->
    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <!-- <h5 class="card-header">Generador de menús</h5> -->
            <div class="card-header">
                <div class="card-header">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="" role="alert">
                                <b>Datos de parada</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="post" action="?/puntos/guardar" autocomplete="off" id="puntoform">
                    <input type="hidden" name="<?= $csrf; ?>">
                    <div class="form-group">
                        <label for="nombre_lugar" class="control-label">Nombre lugar:</label>
                        <input type="text" value="" name="nombre_lugar" id="nombre_lugar" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max250">
                    </div>
                    <div class="form-group">
                        <label for="descripcion" class="control-label">Descripcion:</label>
                        <input type="text" value="" name="descripcion" id="descripcion" class="form-control" autofocus="autofocus" >
                        <input type="hidden" value="<?= $id_ruta; ?>" id="id_ruta" name="id_ruta"/>
                        <input type="hidden" value="0" id="id_punto" name="id_punto"/>
                    </div>
                  <!--  <div class="form-group">
                        <label for="imagen" class="col-md-3 control-label">Imagen:</label>
                        <div class="card" >
                            <input type="file" class="form-control" name="imagen" id="imagen">
                        </div>
                    </div>-->
                    <div class="form-group">
                        <label for="estado" class="control-label">Estado:</label>
                        <select name="estado" id="estado" data-validation="required number" class="form-control">
                            <option value="1">ACTIVO</option>
                            <option value="0">INACTIVO</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="button" id="botonenviar" class="btn btn-danger">
                            <span class="glyphicon glyphicon-floppy-disk"></span>
                            <span>Guardar</span>
                        </button>
                        <button type="reset" class="btn btn-default">
                            <span class="glyphicon glyphicon-refresh"></span>
                            <span>Restablecer</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
        <!--<div>
            <table id="coord" class="hidden">
                <tbody >
                <?php //foreach($puntos as $punto){ ?>
                    <tr><td><?//= $punto['nombre_lugar']; ?></td>
                        <td><?//= '*'.$punto['latitud'].','.$punto['longitud'] ?></td>
                    </tr>
                <?php// } ?>
                </tbody>
            </table>
        </div>-->
        <div class="card">
            <!-- <h5 class="card-header">Generador de menús</h5> -->
            <div class="card-header">
                <div class="card-header">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="" role="alert">
                                <b>Punto un punto en el mapa, y arrastrelo para corregirlo</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="map" class="map col-sm-12 embed-responsive embed-responsive-16by9"></div>
            </div>
        </div>
    </div>
</div>

<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/jquery.maskedinput.min.js"></script>


    <script src="<?= js; ?>/leaflet.js"></script>
    <script src="<?= js; ?>/leaflet-routing-machine.js"></script>
    <script src="<?= js; ?>/Leaflet.Icon.Glyph.js"></script>
    <script src="<?= js; ?>/Leaflet.Editable.js"></script>
    <script src="<?= js; ?>/leaflet_measure.js"></script>
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
            iconAnchor:  [12, 41],
            popupAnchor: [1, -34],
            shadowSize:  [41, 41],
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
            glyphSize: '11px',	// in CSS units
            glyphAnchor: [0, -7]
        }
    });
 /*   var lime1Icon = new LeafIcon({iconUrl: '<?//= files .'/puntero/lime1.png' ?>'}),
        lime2Icon = new LeafIcon({iconUrl: '<?//= files .'/puntero/lime2.png' ?>'}),
        lime3Icon = new LeafIcon({iconUrl: '<?//= files .'/puntero/lime3.png' ?>'}),
        blueIcon = new LeafIcon({iconUrl: '<?//= files .'/puntero/blue.png' ?>'});*/

    window.LRM = {
        apiToken: 'pk.eyJ1IjoibGllZG1hbiIsImEiOiJjamR3dW5zODgwNXN3MndqcmFiODdraTlvIn0.g_YeCZxrdh3vkzrsNN-Diw'
    };

    //    console.log(nomb);

    var waypoints1 = new Array();

    var centerPoint = [-16.507354, -68.162908];


    // Create leaflet map.
    var map = L.map('map').setView(centerPoint, 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    }).addTo(map);

    // Create custom measere tools instances.
    var measure = L.measureBase(map, {});
    //measure.circleBaseTool.startMeasure()
	var coord ='<?= $rutas['coordenadas'] ?>';
	var puntosAreas = coord.split('*');
	var puntArea = new Array();
	for (var i=1; puntosAreas.length > i; i++) {
		var parte = puntosAreas[i].split(',');
		waypoints1.push(L.latLng([parte[0], parte[1]]));
		puntArea.push([parte[0], parte[1]]);
	}
	//marcar el area
	L.polygon(puntArea).addTo(map).bindPopup('Area actual');
	//puntos
	var t_puntos ='<?= $t_clientes ?>'; 
	var puntos = t_puntos.split('*');
	cargarTodosPuntos();
	
	
    function afterRender(result) {
        return result;
    }

    function afterExport(result) {
        return result;
    }


    $(function () {
       /* document.getElementById('coord').style.display = 'none';
        $.validate({
            modules: 'basic,date,file'
        });

        $c1 = 1;

        $("#coord tbody tr").each(function (i) {
            var rutas = $.trim($(this).find("td").text());
//            console.log(rutas);
            $rutas1 = new Array();
            var ruta = rutas.split('*');
            for (var i=1; ruta.length > i; i++) {
                var parte1 = ruta[i].split(',');
//                console.log(parte1);
                $rutas1.push([parte1[0],parte1[1]]);
                L.marker([parte1[0],parte1[1]], {icon: lime1Icon}).bindPopup(ruta[0]).addTo(map);
            }

//            L.polygon($rutas1).addTo(map).bindPopup(ruta[0]);

        });*/


        measure.markerBaseTool.startMeasure();//marcar un punto

        $("#botonenviar").click(
            function() {
                if(validaForm()){
  
                    var lat = measure.markerBaseTool.measureLayer._latlng.lat;
                    var lng = measure.markerBaseTool.measureLayer._latlng.lng;
                    //var way = JSON.stringify(wayt);
                    var wayt = lat + ',' + lng;

                    var estado = $("#estado option:selected").val();
                    var nombre_lugar = $("#nombre_lugar").val();

                    var descripcion = $("#descripcion").val();
                    var id_ruta = $("#id_ruta").val();
                    var id_punto = $("#id_punto").val();

                    var formData = new FormData();
                    //var files = $('#imagen')[0].files[0];
                    //formData.append('imagen',files);
                    formData.append('estado',estado);
                    formData.append('nombre_lugar',nombre_lugar);
                    formData.append('descripcion',descripcion);
                    formData.append('id_ruta',id_ruta);
                    formData.append('coordenadas',wayt);

                    $.ajax({ //datos que se envian a traves de ajax
                        type:  'post', //método de envio
                        dataType: 'json',
                        url:   '?/gon-puntos/guardar', //archivo que recibe la peticion
                        data:  formData,
                        contentType: false,
                        processData: false
                    }).done(function (ruta) {
                        console.log(ruta);
                        if (ruta.estado == 's') {
                            $('#puntoform').trigger("reset");
                            alertify.success('El punto fue registrado satisfactoriamente.');
							//window.location.href="?/gon-rutas/listar";
							location.reload();
                        } else if(ruta.estado == 'y'){
                            $('#loader').fadeOut(100);
                            alertify.error('Error, Ocurrió un problema en el proceso, el punto ya se encuentra registrado..........');
                        } else{
                            $('#loader').fadeOut(100);
                            alertify.error('Error, Ocurrió un problema en el proceso, no se puedo guardar los datos ..........');
                        }
                    }).fail(function () {
                        $('#loader').fadeOut(100);
                        alertify.error('Error, Ocurrió un problema en el proceso, no se puedo guardar los datos, verifique si la se guardó parcialmente.');
                    });

                }

            });
});
	 
function cargarTodosPuntos(){
	console.log(puntos);
	
	// debugger;
 //::::::::::::::MARCADOR CON NUMERO Y RUTA:::::::::::::::
/*	 var markers = L.Routing.control({
        //router: L.routing.mapbox(LRM.apiToken),
        plan: L.Routing.plan(puntonumber, {
            createMarker: function(i, wp) {
                return L.marker(wp.latLng, {
                    //draggable: false,
                    icon: L.icon.glyph({ glyph: 'HOLA'})
                });
            }
        }),
    
    }).addTo(map)
 
    L.Routing.errorControl(markers).addTo(map);*/
		//:::::::::::::::::::::::::::::
	//debugger;
	for (var i=1; puntos.length >i; i++) {
		//debugger;
		var parte = puntos[i].split('||');
		 var array= L.latLng([parte[0], parte[1]]);//puntonumber
		//marcamos cantidades en punto
 
		var markerscant = L.marker(([parte[0], parte[1]]),  {
						//draggable: false,
						icon: L.icon.glyph({ glyph:parte[4]}) 
					}).addTo(map);
		//markerscant.bindPopup('<a href="?/gon-puntos/asignar/'+parte[3]+'" title="Añadir estudiante" class="btn btn-info btn-xs"><span class="icon-user-follow"></span></a><b class="text-center">AQUI!</b><br>'+parte[2]+".").openPopup();
	 //L.Routing.errorControl(markers).addTo(map);
//marcamos estado y ruta::::::::::::::::::
/*		var markers2 = L.Routing.control({
			//router: L.routing.mapbox(LRM.apiToken),
			plan: L.Routing.plan(puntonumber, {
				createMarker: function(i, wp) {
					return L.circleMarker(([parte[0], parte[1]]), {
					renderer: myRenderer,
					radius: 20,
					fillColor: '#348acd',
					fillOpacity: 0.5,
					color: '#fff',
					weight: 3});
				}, 
			}),

		}).addTo(map)
		//marca la ruta
		L.Routing.errorControl(markers2).addTo(map);*/
		//:::::::::::::::
		
		/*var markers = L.circleMarker(([parte[0], parte[1]]), {
                renderer: myRenderer,
                radius: 20,
                fillColor: '#348acd',
				fillOpacity: 0.5,
				color: '#fff',
                weight: 3
            }).addTo(map);*/
		
		//markersArr[i]=markers;
		//var marker2 = L.marker([38.623811, -0.577812]).addTo(map); 
		
       // var parte = puntos[i].split(',');
		/*var markers = L.marker([parte[0], parte[1]],{
            createMarker: function(i, wp) {
                return L.marker(wp.latLng, {
                    //draggable: false,
                    icon: L.icon.glyph({ glyph: 'HOLA'})
                });
            }
        }, {icon: marcador}).addTo(map);*/
		
		
		//con imagen::::::::::::::::::
		//var markers = L.marker(([parte[0], parte[1]]), {icon: marcador}).addTo(map);
		//markers.bindPopup('<a href="?/gon-puntos/asignar/'+parte[3]+'" title="Añadir estudiante" class="btn btn-info btn-xs"><span class="icon-user-follow"></span></a><b class="text-center">AQUI!</b><br>'+parte[2]+".").openPopup();
		 
		//markersArr[i]=markers;
		//	marker.bindPopup('This is Aurora, CO.');
		
		//circleMarker.bindPopup("<b>Parada!</b><br>Aquí agrupar estudiantes.").openPopup();	
		/*	 
		//CIRCULO
		var circleMarker = L.circle(([parte[0], parte[1]]), {
                color: 'orange',
				  fillColor: '#d8d81f',
				  fillOpacity: 0.5,
				  radius: 30
				  
            }).addTo(map);*/
		/* 
		//DEFECTO var marker = L.marker(([parte[0], parte[1]])).addTo(map);*/
		
		/*  
		//ICONO
		var marcador = L.icon({
        iconUrl:'images/marcador.png',
        iconSize: [60, 85]
		});
		var marker = L.marker([37.1698, -3.965], {icon: marcador}).addTo(mymap);
		*/
		
		/* 
		//CIRCULO
		var circleMarker = L.circleMarker(([parte[0], parte[1]]), {
                renderer: myRenderer,
                radius: 8,
                fillColor: '#3352FF',
                fillOpacity: 1,
                color: '#fff',
                weight: 3
            }).addTo(map);*/
		
		/**/
		
		
		
		
			
            // L.marker([parte[0], parte[1]]).addTo(map);
    }
	
}
    function validaForm(){
        // Campos de texto
        /*if($("#descripcion").val() == ""){
            $("#descripcion").focus();       // Esta función coloca el foco de escritura del usuario en el campo Nombre directamente.
            alertify.error('Error, Debe introducir la descripción del punto');
            return false;
        }*/
        if($("#nombre_lugar").val() == ""){
            $("#nombre_lugar").focus();       // Esta función coloca el foco de escritura del usuario en el campo Nombre directamente.
            alertify.error('Error, Debe introducir el nombre del lugar');
            return false;
        }
        if(typeof measure.markerBaseTool.measureLayer.dragging == 'undefined'){
            alertify.error('Error, Debe seleccionar un punto en el mapa');
            return false;
        }
        return true;
    }
</script>
<?php require_once show_template('footer-design'); ?>