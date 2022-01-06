
<?php

//var_dump($clientes);
// Obtiene los formatos para la fecha
$formato_textual = get_date_textual($_institution['formato']);
$formato_numeral = get_date_numeral($_institution['formato']);

// Obtiene el rango de fechas
//$gestion = date('Y');
//$gestion_base = date('Y-m-d');
//$gestion_base = ($gestion - 16) . date('-m-d');
//$gestion_limite = ($gestion + 16) . date('-m-d');

// Obtiene fecha inicial
/*$fecha_inicial = (isset($params[0])) ? $params[0] : $gestion_base;
$fecha_inicial = (is_date($fecha_inicial)) ? $fecha_inicial : $gestion_base;
$fecha_inicial = date_encode($fecha_inicial);

// Obtiene fecha final
$fecha_final = (isset($params[1])) ? $params[1] : $gestion_limite;
$fecha_final = (is_date($fecha_final)) ? $fecha_final : $gestion_limite;
$fecha_final = date_encode($fecha_final);*/

// Obtiene los clientes
//$clientes = $db->select('*')->from('inv_clientes')->fetch();
//$puntos = $db->query('SELECT a.*  FROM gon_puntos a')->fetch();

$puntos = $db->query("SELECT 
  (select count(ins.id_inscripcion) from ins_inscripcion ins  where pun.id_punto = ins.punto_id)AS asig_count
  ,pun.*
  FROM gon_puntos pun  where pun.estado='1'")->fetch();
//$clientes = $db->select('a.*, GROUP_CONCAT(DISTINCT c.cargo SEPARATOR "|") as empresa')->from('inv_clientes a')->join('inv_egresos b','a.id_cliente = b.cliente_id')->join('sys_empleados c','b.empleado_id = c.id_empleado')->where('a.id_cliente<',100)->group_by('a.id_cliente')->fetch();
 
$n_clientes = '';
$empresa = '';
$t_clientes = '';$nombres = '';//$total = 0;
foreach($puntos as $row){
    //$aux2 = explode(',',$cliente['ubicacion']);
    $aux3 = $row['latitud'];//$aux2[0] + 0.00005;
    $aux4 = $row['longitud'];//$aux2[1] - 0.00003;
    //$point = $aux3.' '.$aux4;
    //$punto = $pointLocation->pointInPolygon($point, $polygon);
    //if($punto == 'dentro'){
        //$coordenad = $t_clientes.'*'.$aux3.','.$aux4;
		//$coordenad = $coordenad.'*'.$punto1['latitud'].','.$punto1['longitud'];
		//array_push($puntosDentro,$row);
		$t_clientes = $t_clientes.'*'.$aux3.'||'.$aux4.'||'.$row['nombre_lugar'].'||'.$row['id_punto'].'||'.$row['asig_count'];
		$nombres = $nombres.'*'.$row['nombre_lugar'];
        //$total = $total + 1;
    //}
}
  
//obtener las rutas
$rutas = $db->select('*')->from('gon_rutas')->where('estado',1)->fetch();

// Obtiene la moneda oficial
//$moneda = $db->from('inv_monedas')->where('oficial', 'S')->fetch_first();
//$moneda = ($moneda) ? '(' . $moneda['sigla'] . ')' : '';

// Obtiene los permisos
//$permisos = explode(',', permisos);

// Almacena los permisos en variables
//$permiso_ver = in_array('proformas_ver', $permisos);
//$permiso_eliminar = in_array('proformas_eliminar', $permisos);
//$permiso_imprimir = in_array('imprimir', $permisos);
//$permiso_facturar = in_array('proformas_facturar', $permisos);
//$permiso_cambiar = true;

?>
<?php require_once show_template('header-design'); ?>
<link rel="stylesheet" href="<?= css; ?>/leaflet.css">
<link rel="stylesheet" href="<?= css; ?>/leaflet-routing-machine.css">
<link rel="stylesheet" href="<?= css; ?>/site.css">

<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css">

<style>
.table-xs tbody {
	font-size: 12px;
}
.width-sm {
	min-width: 150px;
}
.width-md {
	min-width: 200px;
}
.width-lg {
	min-width: 250px;
}
.leaflet-control-attribution,
.leaflet-routing-container {
	display: none;
}
</style>
<div class="panel-heading" data-formato="<?= strtoupper($formato_textual); ?>" data-mascara="<?= $formato_numeral; ?>" data-gestion="<?= date_decode($gestion_base, $_institution['formato']); ?>">
	<h3 class="panel-title">
		<a href="?/gon-rutas/listar" type="button" id="listar" class="btn btn-primary" >Listar</a>
		<span class="glyphicon glyphicon-option-vertical"></span>
		<b>Lista de todas las proformas <?= $_institution['nombre'] ?></b>
	</h3>
</div>
<div class="panel-body">

	<div class="row">
        <div class="col-sm-9 hidden-xs">
            <form method="post" class="form-horizontal">
                <div class="row">
                    
                        <label for="nombre" class="col-12 control-label">Nombre de la ruta:</label>
                        <div class="col-12">
                            <input type="text" name="nombre" id="nombre" class="form-control" />
                            <input type="hidden" name="cliente" id="cliente" value="<?= $t_clientes ?>"/>
                            <input type="hidden" name="empresa" id="empresa" value="<?= $empresa ?>"/>
                            <input type="hidden" name="nombre2" id="nombre2" value="<?= $n_clientes ?>"/>
                        </div>
                        <label for="nombre" class="col-12 control-label">Descripcion de  ruta:</label>
                        <div class="col-12">
                            <input type="text" name="descripcion" id="descripcion" class="form-control" /> 
                        </div>
                        <div class="col-12" >
                            <input type="button" value="Guardar ruta" id="botonenviar" class="btn btn-info  "/>
           					 <a href="?/gon-rutas/listar" type="button" id="listar" class="btn btn-primary" >Cancelar</a>
                        </div>
                   

                </div>
            </form>
        </div>
        <div class="col-xs-12 col-sm-3 text-right">
            
           <!-- <a href="?/control/crear2" type="button" id="crear2" class="btn btn-success" >Ir <?//= $_institution['nombre'] ?></a>-->
        </div>
	</div>

    <div>
        <table id="coord" class="d-none">
            <tbody >
                <?php foreach($rutas as $ruta){ ?>
                <tr><td><?= $ruta['nombre'] ?></td>
                    <td><?= $ruta['coordenadas'] ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
	<div class="row">
		<div class="col-sm-12">
            <div id="map" class="map col-sm-12 embed-responsive embed-responsive-16by9"></div>
		</div>
	</div>
    <div id="lassoResult"></div>
</div>

<!-- Inicio modal fecha -->
<?php //if ($permiso_cambiar) { ?>
<div id="modal_fecha" class="modal fade">
	<div class="modal-dialog">
		<form id="form_fecha" class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Cambiar fecha</h4>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<label for="inicial_fecha">Fecha inicial:</label>
							<input type="text" name="inicial" value="<?= ($fecha_inicial != $gestion_base) ? date_decode($fecha_inicial, $_institution['formato']) : ''; ?>" id="inicial_fecha" class="form-control" autocomplete="off" data-validation="date" data-validation-format="<?= $formato_textual; ?>" data-validation-optional="true">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<label for="final_fecha">Fecha final:</label>
							<input type="text" name="final" value="<?= ($fecha_final != $gestion_limite) ? date_decode($fecha_final, $_institution['formato']) : ''; ?>" id="final_fecha" class="form-control" autocomplete="off" data-validation="date" data-validation-format="<?= $formato_textual; ?>" data-validation-optional="true">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-aceptar="true">
					<span class="glyphicon glyphicon-ok"></span>
					<span>Aceptar</span>
				</button>
				<button type="button" class="btn btn-default" data-cancelar="true">
					<span class="glyphicon glyphicon-remove"></span>
					<span>Cancelar</span>
				</button>
			</div>
		</form>
	</div>
</div>
<?php// } ?>
<!-- Fin modal fecha -->

<script src="<?= js; ?>/jquery.dataTables.min.js"></script>
<script src="<?= js; ?>/dataTables.bootstrap.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/jquery.maskedinput.min.js"></script>
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<script src="<?= js; ?>/jquery.dataFilters.min.js"></script>
<script src="<?= js; ?>/moment.min.js"></script>
<script src="<?= js; ?>/moment.es.js"></script>
<script src="<?= js; ?>/bootstrap-datetimepicker.min.js"></script>
<script src="<?= js; ?>/leaflet.js"></script>
<script src="<?= js; ?>/leaflet-routing-machine.js"></script>
<script src="<?= js; ?>/Leaflet.Icon.Glyph.js"></script>
<script src="<?= js; ?>/Leaflet.Editable.js"></script>
<script src="<?= js; ?>/leaflet_measure.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
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
/*    var lime1Icon = new LeafIcon({iconUrl: '<?= files .'/puntero/lime1.png' ?>'}),
        lime2Icon = new LeafIcon({iconUrl: '<?= files .'/puntero/lime2.png' ?>'}),
        lime3Icon = new LeafIcon({iconUrl: '<?= files .'/puntero/lime3.png' ?>'}),
        blueIcon = new LeafIcon({iconUrl: '<?= files .'/puntero/blue.png' ?>'});*/

    window.LRM = {
        apiToken: 'pk.eyJ1IjoibGllZG1hbiIsImEiOiJjamR3dW5zODgwNXN3MndqcmFiODdraTlvIn0.g_YeCZxrdh3vkzrsNN-Diw'
    };
    var coord = $('#cliente').val();
    var emp = $('#empresa').val();
    var nomb = $('#nombre2').val();
//    console.log(nomb);
    var porciones = coord.split('*');
    var porciones2 = emp.split('*');
    var porciones3 = nomb.split('*');
    porciones.shift();
    porciones2.shift();
    porciones3.shift();
//    console.log(nomb);

    var waypoints1 = new Array();

    var centerPoint = [-16.507354, -68.162908];

    // Create leaflet map.
    var map = L.map('map').setView(centerPoint, 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        minZoom: 10,
	    maxZoom: 19,
    }).addTo(map);
	
/*    console.log(porciones2);
    for (var i=1; porciones.length > i; i++) {
        var parte = porciones[i].split(',');
        if(porciones2[i] == 1){
            L.marker([parte[0], parte[1]], {icon: lime1Icon}).bindPopup("EXDIM <br>"+porciones3[i]).addTo(map);
        }
		else if(porciones2[i] == 2){
            L.marker([parte[0], parte[1]], {icon: lime3Icon}).bindPopup("CETRIX <br>"+porciones3[i]).addTo(map);
        }else if(porciones2[i] == 3){
            L.marker([parte[0], parte[1]], {icon: lime2Icon}).bindPopup("EXDIM|CETRIX <br>"+porciones3[i]).addTo(map);
        }else{
            L.marker([parte[0], parte[1]], {icon: blueIcon}).bindPopup("NINGUNO <br>"+porciones3[i]).addTo(map);
        }

        //L.marker([parte[0], parte[1]]).addTo(map);
    }*/

    // Create custom measere tools instances.
    var measure = L.measureBase(map, {});
    measure.circleBaseTool.startMeasure()

    function afterRender(result) {
        return result;
    }

    function afterExport(result) {
        return result;
    }

    $(function () {
        $c1 = 1;
		//debugger;ç
		//MARCAR PUNTOS AREAS
        $("#coord tbody tr").each(function (i) {
            var rutas = $.trim($(this).find("td").text());
            $rutas1 = new Array();
            var ruta = rutas.split('*');
            for (var i=1; ruta.length > i; i++) {
                var parte1 = ruta[i].split(',');
                $rutas1.push([parte1[0],parte1[1]]);
            }

            L.polygon($rutas1).addTo(map).bindPopup(ruta[0]);

        });

        measure.polygonBaseTool.startMeasure();
/*
        map.on('measure.polygonBaseTool.startMeasure', event => {
                setSelectedLayers(event.layers);
        });*/

     /*   function setSelectedLayers(layers) {
            console.log('entro');
                resetSelectedState();

                layers.forEach(layer => {
                    if (layer instanceof L.Marker) {
                    layer.setIcon(new L.Icon.Default({ className: 'selected '}));
                } else if (layer instanceof L.Path) {
                    layer.setStyle({ color: '#ff4620' });
                }
            });

            lassoResult.innerHTML = layers.length ? 'SELECCIONADO ${layers.length} PUNTOS' : '';
        }*/
	/*	  function setSelectedLayers(layers) {
            console.log('entro');
                resetSelectedState();

                layers.forEach(layer => {
                    //if (layer instanceof L.Marker) {
                   // layer.setIcon(new L.Icon.Default({ className: 'selected '}));
               // } else if (layer instanceof L.Path) {
                    layer.setStyle({ color: '#ff4620' });
               // }
            });

            lassoResult.innerHTML = layers.length ? 'SELECCIONADO ${layers.length} PUNTOS' : '';
        }*/

        $("#botonenviar").click(
            function() {
//debugger;
                if(validaForm()){

                    //console.log(measure.polygonBaseTool.measureLayer._latlngs);
                                     // Primero validará el formulario.
                    //var wayt = new Array();
                    var wayt='', lati, long;
                    for(var i=0; measure.polygonBaseTool.measureLayer._latlngs[0].length>i;i++){
                        //wayt.push(ar.getWaypoints()[i].latLng);
                        lati = measure.polygonBaseTool.measureLayer._latlngs[0][i].lat;
                        long = measure.polygonBaseTool.measureLayer._latlngs[0][i].lng;
                        wayt = wayt + '*' + lati + ',' + long;
                    }
                    //var way = JSON.stringify(wayt);
                    console.log(wayt);
                    var aa = $('#nombre').val();
                    var dd = $('#descripcion').val();
                     if(wayt != ""){
                    
                    $.ajax({ //datos que se envian a traves de ajax
                        type:  'post', //método de envio
                        dataType: 'json',
                        url:   '?/gon-rutas/guardar', //archivo que recibe la peticion
                        data:   {'wayt': wayt, 'nombre':aa, 'descripcion':dd}
                    }).done(function (ruta) {
                        if (ruta=='1') {
							alertify.alert('La ruta fue registrada satisfactoriamente.');
                           /* $.notify({
                                message: 'La ruta fue registrada satisfactoriamente.'
                            }, {
                                type: 'success'
                            });*/
                            setTimeout("location.href='?/gon-rutas/listar'", 0);
                        } else {
                            $('#loader').fadeOut(100);
                            $.notify({
                                message: 'Ocurrió un problema en el proceso, no se puedo guardar los datos, verifique si la se guardó parcialmente..........'
                            }, {
                                type: 'danger'
                            });
                        }
                    }).fail(function () {
                        $('#loader').fadeOut(100);
						alertify.alert('Ocurrió un problema en el proceso, no se puedo guardar los datos, verifique si la se guardó parcialmente.');
                        /*$.notify({
                            message: 'Ocurrió un problema en el proceso, no se puedo guardar los datos, verifique si la se guardó parcialmente.'
                        }, {
                            type: 'danger'
                        });*/
                    });
                    
                    
                    }else{
						alertify.alert('Debe trazar un area obligatoriamente.');
                       /* $.notify({
                            message: 'Debe trazar un area obligatoriamente.'
                        }, {
                            type: 'danger'
                        });*/
                        
                    }

                }

            });

    });
	debugger;
		var t_puntos ='<?= $t_clientes ?>';
		var puntos = t_puntos.split('*');
	cargarTodosPuntos();
 function cargarTodosPuntos(){
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
            }).addTo(map);
		
		markersArr[i]=markers;*/
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
        if($("#nombre").val() == ""){
            alert("El campo Nombre no puede estar vacío.");
            $("#nombre").focus();       // Esta función coloca el foco de escritura del usuario en el campo Nombre directamente.
            return false;
        }
        return true;
    }

	
</script>
 