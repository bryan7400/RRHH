<?php
require configuration . '/poligono.php';
// Obtiene los parametros
$id_ruta = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf  where pun.estado='1'
$csrf = set_csrf();



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
$puntos = $db->query("SELECT 
  (select count(ins.id_inscripcion) from ins_inscripcion ins  where pun.id_punto = ins.punto_id)AS asig_count
  ,pun.*
  FROM gon_puntos pun  where pun.estado='1'")->fetch();
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



//obbtiene los puntos
/*$puntos = $db->select('*')->from('gon_puntos')->where('ruta_id',$id_ruta)->fetch();
$coordenad = '';
$nombres = '';
$numeros = '';
foreach($puntos as $punto1){
    $coordenad = $coordenad.'*'.$punto1['latitud'].','.$punto1['longitud'];
    $nombres = $nombres.'*'.$punto1['nombre_lugar'];
}*/

// Ejecuta un error 404 si no existe el rutas
if (!$rutas){ 
    require_once not_found(); 
    exit; 
}

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_crear = in_array('crear', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);
//$permiso_asignar = in_array('asignar', $_views);
$permiso_asignar = true;
$permiso_ver = in_array('ver', $_views);

?>
<?php require_once show_template('header-design'); ?>
    <link rel="stylesheet" href="<?= css; ?>/leaflet.css">
    <link rel="stylesheet" href="<?= css; ?>/leaflet-routing-machine.css">
    <link rel="stylesheet" href="<?= css; ?>/site.css">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title"><?= escape($rutas['nombre']); ?></h2>
                <p class="pageheader-text"></p>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="?/gon-rutas/listar" class="breadcrumb-link">Rutas</a></li>
                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Ver  <?= escape($rutas['nombre']); ?></a></li>
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
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
        <!-- <h5 class="card-header">Generador de menús</h5> -->
            <div class="card-header">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                        <div class="text-label hidden-xs">Seleccione:</div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
                        <div class="btn-group">
                            <div class="input-group">
                                <?php if ($permiso_listar || $permiso_crear || $permiso_modificar || $permiso_eliminar || $permiso_imprimir) : ?>
                                    <div class="input-group-append be-addon">
                                        <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
                                        <div class="dropdown-menu">

                                            <li class="dropdown-header visible-xs-block">Seleccionar acción</li>
                                            <?php if ($permiso_listar) : ?>
                                                <li><a href="?/gon-rutas/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar rutas</a></li>
                                            <?php endif ?>
                                            <?php if ($permiso_crear) : ?>
                                                <li><a href="?/gon-rutas/crear"><span class="glyphicon glyphicon-plus"></span> Crear rutas</a></li>
                                            <?php endif ?>
                                            <?php if ($permiso_modificar) : ?>
                                                <li><a href="?/gon-rutas/modificar/<?= $id_ruta; ?>"><span class="glyphicon glyphicon-edit"></span> Modificar rutas</a></li>
                                            <?php endif ?>
                                            <?php if ($permiso_eliminar) : ?>
                                                <li><a href="?/gon-rutas/eliminar/<?= $id_ruta; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar rutas</a></li>
                                            <?php endif ?>
                                            <?php if ($permiso_imprimir) : ?>
                                                <li><a href="?/gon-rutas/imprimir/<?= $id_ruta; ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir rutas</a></li>
                                            <?php endif ?>

                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-6">
                        <div class="form-group">
                            <label class=""><b>Nombre:</b></label>
                            <span class=""><?= escape($rutas['nombre']); ?></span>
                        </div>
                        <div class="form-group">
                            <label class=""><b>Descripcion:</b></label>
                            <span class=""><?= escape($rutas['descripcion']); ?></span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-6 ">
                        <div class="form-group">
                            <label class=""><b>Puntos:</b></label>
                            <span class=""><?= escape($rutas['punto_id']); ?></span>
                        </div>
                        <div class="form-group">
                            <label class=""><b>Estado:</b></label>
                            <span class=""><?php if($rutas['estado'] == 1){echo 'ACTIVO';}else{echo 'INACTIVO';} ?></span>
                        </div>
<!--                        <div class="form-group">-->
<!--                            <label class=""><b>Usario registro:</b></label>-->
<!--                            <span class="">--><?//= escape($rutas['usario_registro']); ?><!--</span>-->
<!--                        </div>-->
<!--                        <div class="form-group">-->
<!--                            <label class=""><b>Fecha registro:</b></label>-->
<!--                            <span class="">--><?//= escape($rutas['fecha_registro']); ?><!--</span>-->
<!--                        </div>-->
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-6">
<!--                        <div class="form-group">-->
<!--                            <label class=""><b>Usario modificacion:</b></label>-->
<!--                            <span class="">--><?//= escape($rutas['usario_modificacion']); ?><!--</span>-->
<!--                        </div>-->
<!--                        <div class="form-group">-->
<!--                            <label class=""><b>Fecha modificacion:</b></label>-->
<!--                            <span class="">--><?//= escape($rutas['fecha_modificacion']); ?><!--</span>-->
<!--                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-body">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                    <div id="map" class="map col-sm-12 embed-responsive embed-responsive-16by9"></div>
                </div>
            </div>
        </div>
    </div>
    <style>
		.vertr:hover{
			background: #17c0dc29;
			cursor: pointer;
		}
		.vertr.active{
			background: #3fdff98c;
		}
	
	</style>
    <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-body">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 table-responsive">
                    <?php if ($puntos) : ?>
                        <input type="hidden" id="coords" value="<?= $coordenad; ?>"/>
                        <input type="hidden" id="nombres" value="<?= $nombres; ?>"/>

                        <table id="table" class="table table-bordered table-condensed table-stripedx table-hoverx">
                            <thead>
                            <tr class="active">
                                <th class="text-nowrap">#</th>
<!--                                <th class="text-nowrap">Descripcion</th>-->
                                <th class="text-nowrap">Nombre Parada bus</th>
                                <!--<th class="text-nowrap">Estudiantes</th>-->
                                <?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
                                    <th class="text-nowrap">Acciones</th>
                                <?php endif ?>
                            </tr>
                            </thead>
                        
                            <tbody>
                            <?php 
							 $nro=0;
								foreach ($puntosDentro  as $punto) : 
								//$punto = $pointLocation->pointInPolygon($point, $polygon);
    							//if($punto == 'dentro'){
								$nro++; 
								 //var_dump($punto);exit();
								?>
                                <tr class="vertr" onclick="ver_rutaactual(<?=$punto['latitud'];?>,<?=$punto['longitud']?>,this)" >
                                    <th class="text-nowrap"><?= $nro ?></th> 
                                    <td class="coordenadas text-center">
                                    <span class="badge badge-success">
                                     
                                  <!--  <td class="coordenadas">-->
                                     <?php

                                        
                                        $estudiantes = $db->query('SELECT COUNT(id_estudiante) as nro
                                            FROM ins_inscripcion a
                                            LEFT JOIN ins_estudiante b ON a.estudiante_id = b.id_estudiante
                                            LEFT JOIN sys_persona c ON b.persona_id = c.id_persona
                                            LEFT JOIN ins_aula_paralelo d ON a.aula_paralelo_id = d.id_aula_paralelo
                                            LEFT JOIN ins_paralelo e ON d.paralelo_id = e.id_paralelo
                                            LEFT JOIN ins_aula f ON d.aula_id = f.id_aula
                                            LEFT JOIN ins_turno g ON d.turno_id = g.id_turno

                                            WHERE a.punto_id="'.$punto['id_punto'].'"
                                            ')->fetch_first();
										if(($estudiantes['nro']*1)>0){
                                        	echo $estudiantes['nro'].' Estudiantes';
										}
											


                                    ?>
                                     </span><br>
                                    <?= ($punto['nombre_lugar']); ?>
                                    </td>
<!--               
                                    <?php //if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
                                            <?php //if ($permiso_asignar) : ?>
                                               
                                                <!--<span  class="btn btn-xs btn-info" onclick="ver_rutaactual(<?=$punto['latitud'];?>,<?=$punto['longitud']?>)"> <span class=" fas fa-eye "></span>  </span>-->
                                        <td class="text-nowrap">
                                                <span onclick="abrirmodalAddEst(<?= $punto['id_punto']; ?>)" class="btn btn-xs btn-info" title="Añadir un estudiantes"><span class="fas fa-plus-circle"></span></span>
                                                <span onclick="abrirmodalEliminarEst(<?= $punto['id_punto']; ?>)" class="btn btn-xs btn-warning" title="Quitar un estudiantes"><span class=" fas fa-minus-circle"></span></span>
                                                <!--<a href="?/gon-puntos/asignar/<?= $punto['id_punto']; ?>" data-toggle="tooltip" data-title="asignar" class="btn btn-xs btn-info"><span class=" icon-user-follow "></span></a>-->
                                                <div class="btn-group">
												  <button type="button" class="btn btn-secondary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													<i class="fas fa-cog"></i>
												  </button>
												  <div class="dropdown-menu">
													<a class="dropdown-item" href="?/gon-rutas/editar-punto/<?= $id_ruta ?>/<?= $punto['id_punto']; ?>">Modificar punto</a>
													<a class="dropdown-item" href="?/gon-rutas/eliminar-punto/<?= $punto['id_punto']; ?>/<?=$id_ruta;?>">Eliminar punto</a> 
												  </div>
												</div> 
                                            <?php// endif ?>
                                   
                                        </td>
                                    <?php //endif ?>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <div class="alert alert-info">
                            <strong>Atención!</strong>
                            <ul>
                                <li>No existen puntos registrados en la base de datos.</li>
                                <li>Para crear nuevos puntos debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
                            </ul>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>

</div>
  <style>
	  tbody input{
	  display: block!important;
	  }
	  .form-control.selectized{
		  display: none!important;
	  }
</style>
<div class="modal" tabindex="-1" role="dialog" id="modalasignar">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Asignar estudiante</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" id="formAddest">
      <div class="modal-body">
        <p>Seleccione un estudiante:</p>
        <div class="selectEst">
        <select name="selEstudaintes" id="selEstudaintes" class="form-control " name="selEstudaintes"> </select>
        </div>
        	
        
        <table class="table" id="tab_estudiantes"><thead><tr><th>n</th>
        <th>Nombre completo</th><th>Seleccione el que desee eliminar</th></tr></thead><tbody>
     
        </tbody></table>
        
        
      <input type="hidden" value="" id="id_punto_mod" name="id_punto">
      <input type="hidden"  value="" id="tipoaccion_mod" name="tipo">
      <input type="hidden"  value="asignar_estudiante" name="proceso" >
    
      </div>
      	
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="guardarPunto();">Guardar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>  

<!--<div class="modal" tabindex="-1" role="dialog" id="modaleliminar">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Estudiantes en punto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Seleccione a los estudiantes para eliminar del punto</p>
        <table class="table table-list-elim">
        <thead><tr><th>N</th>
        <th>Estudiante</th>
 
        <th>Acciones</th></tr></thead>
        <tbody>
        	
        </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Guardar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>-->
   <link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">
    <script src="<?= js; ?>/selectize.min.js"></script>
   
    <script src="<?= js; ?>/leaflet.js"></script>
    <script src="<?= js; ?>/leaflet-routing-machine.js"></script>
    <script src="<?= js; ?>/Leaflet.Icon.Glyph.js"></script>
    <script src="<?= js; ?>/Leaflet.Editable.js"></script>
    <script src="<?= js; ?>/leaflet_measure.js"></script>
    
<script>
	//debugger;
	
	console.log('hola');
 //debugger;
//$(function () {
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/rutas/crear';
				break;
			}
		}
	});
	<?php endif ?>
	
	<?php if ($permiso_eliminar) : ?>
	$('[data-eliminar]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var csrf = '<?= $csrf; ?>';
		bootbox.confirm('¿Está seguro que desea eliminar el rutas?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
	//errores
	
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
	//puntos
	var t_puntos ='<?= $t_clientes ?>';
	var rutaimagen ='<?= assets.'/imgs' ?>';
	var puntos = t_puntos.split('*');
	// debugger;
	//areas
	var coord ='<?= $rutas['coordenadas'] ?>';
	var coordnom ='Ruta demo';//'<?//= $rutas['nombre'] ?>';
	var puntosAreas = coord.split('*');
	
	var waypoints1 = new Array();
	var puntArea = new Array();
	
	for (var i=1; puntosAreas.length > i; i++) {
		var parte = puntosAreas[i].split(',');
		waypoints1.push(L.latLng([parte[0], parte[1]]));
		puntArea.push([parte[0], parte[1]]);
	}
	//todos los puntos en un arrar
	var puntonumber = new Array();
	for (var i=1; puntos.length > i; i++) {
		var parte = puntos[i].split('||');
		puntonumber.push(L.latLng([parte[0], parte[1]])); 
	}
	
	window.LRM = {
            apiToken: 'pk.eyJ1IjoibGllZG1hbiIsImEiOiJjamR3dW5zODgwNXN3MndqcmFiODdraTlvIn0.g_YeCZxrdh3vkzrsNN-Diw'
        };
	const tilesProvider='';
	
	var map = L.map('map').setView([parte[0], parte[1]], 15);

    var titles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?access_token=' + LRM.apiToken, {
        }).addTo(map);
	var myRenderer = L.canvas({ padding: 1 });
	//añadir los puntos
	var markersArr=new Array(); 
	 var marcador = L.icon({
			iconUrl: rutaimagen+'/parada.png',
			iconSize:     [68, 55],
			iconAnchor:   [31, 54],//22+22//44 -3-3
		 });
	
	cargarTodosPuntos();
	
	L.polygon(puntArea).addTo(map).bindPopup(coordnom);
	//dibujar areas
/*	var printer = L.easyPrint({
            tileLayer: titles,
            sizeModes: ['Current'],
            filename: 'myMap',
            exportOnly: true,
            hideControlContainer: true
    }).addTo(map);*/
	
	
    /*
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
    //las coordenadas de los puntos
    var latitudes = new Array(), longitudes = new Array(), estados = new Array(), nombres = new Array(), numeros = new Array(), waypoints1= new Array();

    var coor = $('#coords').val();
    var nombs = $('#nombres').val();

    coor = coor.split('*');
    coor.shift();
    nombres = nombs.split('*');
    nombres.shift();

    for (var i = 0; coor.length > i; i++) {
        var parte = coor[i].split(',');
        waypoints1.push(L.latLng([parte[0], parte[1]]));
        if(coor.length == 1)
        {
            waypoints1.push(L.latLng([parte[0], parte[1]]));
        }
    }

    window.LRM = {
        apiToken: 'pk.eyJ1IjoibGllZG1hbiIsImEiOiJjamR3dW5zODgwNXN3MndqcmFiODdraTlvIn0.g_YeCZxrdh3vkzrsNN-Diw'
    };

    var map = L.map('map', { scrollWheelZoom: false }),
        waypoints = waypoints1;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?access_token=' + LRM.apiToken, {
    }).addTo(map);

    var control = L.Routing.control({
        router: L.routing.mapbox(LRM.apiToken),
        plan: L.Routing.plan(waypoints, {
            createMarker: function(i, wp) {
                return L.marker(wp.latLng, {
                    draggable: false,
                    icon: L.icon.glyph({ glyph: String(i+1)})
                });
            }
        }),
        routeWhileDragging: true,
        routeDragTimeout: 250,
        showAlternatives: true,
        altLineOptions: {
            styles: [
                {color: 'black', opacity: 0.15, weight: 9},
                {color: 'white', opacity: 0.8, weight: 6},
                {color: 'blue', opacity: 0.5, weight: 2}
            ]
        }
    })
        .addTo(map)
        .on('routingerror', function(e) {
            try {
                map.getCenter();
            } catch (e) {
                map.fitBounds(L.latLngBounds(waypoints));
            }

            handleError(e);
        });

    L.Routing.errorControl(control).addTo(map);


});*/
//});
	var ultimoMarker=0;
	var antesindice=0;
function ver_rutaactual(x,y,obj){
	//fila active
	$(obj).addClass('active').siblings().removeClass('active');
	//debugger;
	/*var x=parseFloat(x); var y=parseFloat(y);
	for (var i=1; markersArr.length > i; i++) {
		var lat=parseFloat(markersArr[i]._latlng.lat);
		var lng=parseFloat(markersArr[i]._latlng.lng);
	 	//console.log(lat+' vs '+x);
		if(markersArr[i]._latlng.lat==x && markersArr[i]._latlng.lng==y){
			markersArr[i].bindPopup("<b>Parada!</b><br>Aquí. Lograr cambiar de icono al ver").openPopup();
	 }

	}*/
	
	// debugger;
	if(antesindice!=0 && antesindice!=undefined){
		map.removeLayer(markersArr[antesindice]);
		var lat=markersArr[antesindice]._latlng.lat;
		var lng=markersArr[antesindice]._latlng.lng;
		//var markers = L.marker(([lat, lng]),  {icon: marcador}).addTo(map); 
		var markers = L.circleMarker(([lat, lng]), {
                renderer: myRenderer,
                radius: 20,
                fillColor: '#348acd',
				fillOpacity: 0.5,
				color: '#fff',
                weight: 3
            }).addTo(map);
			
	 	//markers.bindPopup("<button>Ver!</button><br>Aqui.");
		markersArr[antesindice]=markers; 
	}
	// cargarTodosPuntos();
	// console.log('x:'+x);
	console.log( markersArr);
	//debugger;
	var x=parseFloat(x); var y=parseFloat(y);
	for (var i=1; markersArr.length > i; i++) {
		var lat=parseFloat(markersArr[i]._latlng.lat);
		var lng=parseFloat(markersArr[i]._latlng.lng);
	 	//console.log(lat+' vs '+x);
		if(markersArr[i]._latlng.lat==x && markersArr[i]._latlng.lng==y){
			//markersArr[i].bindPopup("<b>Parada!</b><br>Aquí. Lograr cambiar de icono al ver").openPopup();
		   map.removeLayer(markersArr[i]);
	 		//console.log(lng+' vs '+y); 
			
			//var marker = L.marker(([x,y])).addTo(map);
			var marker = L.circleMarker(([lat, lng]), {
                renderer: myRenderer,
                radius: 40,
                fillColor: '#25d5f2',
				fillOpacity: 0.5,
				color: '#fff',
                weight: 3
            }).addTo(map);
			//markers.bindPopup("<button>Ver!</button><br>Aqui.").openPopup();
			markersArr[i]=marker;
			antesindice=i;
			//ultimoMarker=marker;
		 }

	}
	console.log( markersArr);
	//map.removeLayer(markersArr[2]);
	//markers= new L.marker();
	//map.addLayer(markers);
	//var marker = L.marker(([x,y])).addTo(map);
	/*var circleMarker = L.circle(([x,y]), {
                color: 'orange',
				  fillColor: '#d8d81f',
				  fillOpacity: 0.5,
				  radius: 30
            }).addTo(map);*/
 
}
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
		markerscant.bindPopup('<span   title="Añadir estudiante" class="btn btn-info btn-xs" onclick="abrirmodalAddEst('+parte[3]+')"><span class="fas fa-plus-circle"></span></span><b class="text-center">AQUI!</b><br>'+parte[2]+".").openPopup();
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
		
		var markers = L.circleMarker(([parte[0], parte[1]]), {
                renderer: myRenderer,
                radius: 20,
                fillColor: '#348acd',
				fillOpacity: 0.5,
				color: '#fff',
                weight: 3
            }).addTo(map);
		
		markersArr[i]=markers;
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
var	listEst_G;
function listarEst(){
	$.ajax({
        url: '?/gon-rutas/procesos',
        type: 'POST',
        data: {
			proceso:'listar_estudiantes'
		},
        dataType: 'JSON',
        success: function(resp){
			listEst_G=resp;
			//debugger;
			console.log(resp);
			var htmla='<option value="">Seleccione inscritos...</option>';
			for (var i=0;i<resp.length  ; i++) {
				htmla+='<option value="'+resp[i]['id_inscripcion']+'">'+resp[i]['nombres']+' '+resp[i]['primer_apellido']+' '+resp[i]['segundo_apellido']+'</option>';
			}
			$('#selEstudaintes').html(htmla);
			 
			//debugger;
		}
	}).done(function(){
		try{
         $('#selEstudaintes').selectize();
        }catch{
        }
	});
 }
			
listarEst();
		
function abrirmodalEliminarEst(id_punto){ 
	$('#modalasignar').modal('show');
	$('#selEstudaintes').val('').hide(); 
	$('.selectEst').hide(); 
	
	$('#tab_estudiantes').show(); 
	$('#id_punto_mod').val(id_punto);
	$('#tipoaccion_mod').val('remove'); 
	$('#modalasignar .modal-title').text('Quitar estudiante');
	
	$('#tab_estudiantes').find('tbody').html('');
	var htmla='';
	for (var i=0;i<listEst_G.length  ; i++) {
		if(listEst_G[i]['punto_id']==id_punto){
		htmla+='<tr><td>1</td> <td>'+listEst_G[i]['nombres']+' '+listEst_G[i]['primer_apellido']+'</td>  <td><input type="radio"  name="radioEstudaintes"  value="'+listEst_G[i]['id_inscripcion']+'"></td></tr>';
		 }
	}
	$('#tab_estudiantes').find('tbody').html(htmla);
 //'<tr><td>1</td> <td>pepep</td>  <td><input type="radio"  name="radioEstudaintes"  value="be"></td></tr>'
}
function abrirmodalAddEst(id_punto){
	$('#modalasignar').modal('show');
	$('#selEstudaintes').val('').show(); 
	$('.selectEst').show(); 
	$('#tab_estudiantes').hide(); 
	$('#id_punto_mod').val(id_punto);
	$('#tipoaccion_mod').val('add');
	$('#modalasignar .modal-title').text('Asignar estudiante');
}

		
function guardarPunto(){
	//var id_punto=$('#id_punto_mod').val();
	//var tipo=$('#tipoaccion_mod').val(); 
	var est=$('#selEstudaintes').val(); 
	var rad=$('[name=radioEstudaintes]').val(); 
	var titulo=$('#modalasignar .modal-title').text();
	var datos=$('#formAddest').serialize();
	if(est=='' && rad==''){
		alertify.warning('Selecciona un estudiante');	
	}else{
		alertify.confirm(titulo, 'Esta seguro de realizar esta asignacion?', function(){ 
		//alert(est);
			$.ajax({
				url: '?/gon-rutas/procesos',
				type: 'POST',
				data: datos,
				/*{
					proceso:'asignar_estudiante',
					id_punto:id_punto,
					tipo:tipo,
					id_inscripcion:est
				},*/
				dataType: 'json',
				success: function(resp){
					console.log(resp);
					/*listEst_G=resp;
					var htmla='<option value="">Seleccione est...</option>';
					for (var i=0;i<resp.length  ; i++) {
						htmla+='<option value="'+resp[i]['id_estudiante']+'">'+resp[i]['nombres']+' '+resp[i]['primer_apellido']+' '+resp[i]['segundo_apellido']+'</option>';
					}
					$('#selEstudaintes').html(htmla);*/

					//debugger;
				}
			}).done(function (ruta) {
				console.log(ruta);
				if (ruta.estado == 's') {
					$('#modalasignar').trigger("reset");
					alertify.success('El estudiante fue asignado satisfactoriamente.');
					location.reload();
					//cargarTodosPuntos();
				}else if (ruta.estado == 'c') {
					$('#modalasignar').trigger("reset");
					alertify.success('El estudiante fue quitado.');
					location.reload();
					//listarEst();cargarTodosPuntos();
				} else if(ruta.estado == 'y'){
					$('#loader').fadeOut(100);
					alertify.error('Error, Ocurrió un problema en el proceso de asignación..........');
				} else{
					$('#loader').fadeOut(100);
					alertify.error('Error, Ocurrió un problema en el proceso, no se puedo guardar los datos ..........');
				}
			}).fail(function () {
				$('#loader').fadeOut(100);
				alertify.error('Error, Ocurrió un problema en el proceso, no se puedo guardar los datos, verifique si la se guardó parcialmente.');
			});
		}
                , function(){ alertify.error('Edicion cancelada')});
 
	}
	//ajax
	}
</script>


<?php // require_once show_template('footer-design'); ?>