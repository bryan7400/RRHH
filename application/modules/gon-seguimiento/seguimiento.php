<?php

// Obtiene la cadena csrf
//$csrf = set_csrf();

// Obtiene los rutas
//$id_gestion=$_gestion['id_gestion'];

/*$rutas = $db->query("SELECT r.id_ruta,r.nombre, r.descripcion,CONCAT(p.nombres,' ',p.primer_apellido) AS conductor, r.id_ruta
					 FROM gon_gondolas AS g 
					 INNER JOIN gon_conductor_gondola AS cg ON cg.gondola_id = g.id_gondola	
					 INNER JOIN gon_rutas AS r ON r.id_ruta = g.ruta_id 
					 INNER JOIN gon_conductor AS c ON c.id_conductor = cg.conductor_id 
					 INNER JOIN sys_persona AS p ON p.id_persona=c.persona_id 
					 WHERE g.estado = 1")->fetch();*/

/*$rutas = $db->query("SELECT est.id_estudiante, per.nombres,per.primer_apellido,per.segundo_apellido,per.genero,ins.punto_id FROM ins_inscripcion ins
		INNER JOIN ins_estudiante est ON est.id_estudiante=ins.estudiante_id
		INNER JOIN sys_persona per ON per.id_persona=est.persona_id
		WHERE  ins.gestion_id=$id_gestion AND ins.estado='A'")->fetch();*/
//::::::::::::::::::::::::::::
// ARMAR PUNTOS DE UNA RUTA
/*		$id_ruta = 1;//clear($_POST['id_ruta']);
			$puntos = $db->query('SELECT pun.*
				 FROM gon_puntos pun
				 INNER JOIN gon_rutas rut ON rut.id_ruta = pun.ruta_id
				 WHERE pun.ruta_id='.$id_ruta)->fetch();
			//$id_ruta
			$est = $db->query("SELECT est.id_estudiante,per.nombres,per.primer_apellido,per.segundo_apellido,per.genero,ins.punto_id
			,est.codigo_credencial ,ifnull(asi.gondola_id,0)AS gondola_id,ifnull(asi.conductor_id,0)AS conductor_id,ifnull(asi.json_asistencia,'')AS json_asistencia
			FROM ins_inscripcion ins
				INNER JOIN ins_estudiante est ON est.id_estudiante=ins.estudiante_id
				INNER JOIN sys_persona per ON per.id_persona=est.persona_id
			  left JOIN gon_asistencia_estudiante asi ON asi.estudiante_id=est.id_estudiante 
				WHERE  ins.gestion_id=1 AND ins.estado='A'
				")->fetch();//  AND  asi.json_asistencia LIKE '%2021-04-21%'
			
			
			//$t_clientes = '';$nombres = '';$total = 0;
			 
			 $puntosRe=array();
			foreach ($puntos as $i => $row1){
				$id_punto=$row1['id_punto'];
				
				$estudiantes=array();
				
				foreach ($est as $i => $row2) { 
					if($id_punto==$row2['punto_id']){
						if($row2['json_asistencia']!=''){
							$asistenciastr=$row2['json_asistencia'];
			 				//$jsonDesc=json_decode($asistenciastr,true);
							$row2['json_asistencia']=json_decode($asistenciastr,true);
							//var_dump($row2);exit();
						}
						 array_push($estudiantes,$row2);
					}
				}
				
				$row1['estudiantes']=$estudiantes;
				  array_push($puntosRe,$row1);
			}*/
			//$puntosRe;
//:::::::::::::::::::::::::::::::::::::::::::::::
 //echo('<pre>'.json_encode($puntosRe).'</pre>'); 
//echo('<script>console.log('.json_encode($puntosRe).')</script>');exit();

// Obtiene el rutas
//$rutas = $db->select('z.*')->from('gon_rutas z')->where('z.id_ruta', $id_ruta)->fetch_first();
 
 
// Obtiene los permisos
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);
//var_dump($_institution);exit();
$latlngColegio=$_institution['latlng'];
$porciones = explode(",", $latlngColegio);
$lat=$porciones[0];
$lng=$porciones[1];
?>

<?php require_once show_template('header-design'); ?>
<link rel="stylesheet" href="<?= css; ?>/leaflet.css">
<link rel="stylesheet" href="<?= css; ?>/leaflet-routing-machine.css">

<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="page-header">
			<h2 class="pageheader-title">Seguimiento en tiempo real a las Gondolas</h2>
			<p class="pageheader-text"></p>
			<div class="page-breadcrumb">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gondolas</a></li>
						<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Seguimiento</a></li>
						<li class="breadcrumb-item active" aria-current="page">Listar</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- end pageheader -->
<!-- ============================================================== -->


<div class="row">
	<!-- ============================================================== -->
	<!-- row -->
	<!-- ============================================================== -->
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="card">
			<div class="card-header">
				 <!--<div class="text-label hidden-xs display-7">Seguimiento en tiempo real a las gondolas</div>-->
				<!-- <input type="date" class="form-control">-->
					Fecha: <span class="text-fecha"></span> <br>
				 
				<div class="btn-group btns-rutas" role="group" aria-label="Basic example">
				  <button type="button" class="btn btn-secondary">Ruta 1</button>
				  <button type="button" class="btn btn-secondary">Ruta 2</button>
				  <button type="button" class="btn btn-secondary">Ruta 3</button>
				</div>
				<div class="row">
					<div class="col-sm-8 " style="overflow: auto;">
						<!--<div id="mapa_seguimiento" class="map embed-responsive embed-responsive-16by9"></div>-->
						<div id="map" class="map embed-responsive embed-responsive-16by9 h-100" ></div>
					</div>

					<div class="col-sm-4">
						<div class="connection-list w-100">
                                    <div class="row">
                                        <div class="nav-user-info col-sm-12 col-12">
                                            <div align="center">
                                                <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="../sistema/assets/imgs/avatar.jpg" width="100" height="100" alt="foto" style="width:100px;moz-border-radius:30%;khtml-border-radius:30%;o-border-radius:30%;webkit-border-radius:30%;ms-border-radius:50%;border-radius:50%;"> </a>
                                                <h5 class="mb-0 text-white cond-user-name">Nombre</h5>
                                                <span class="status"></span><span class="">Conductor</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <a class="dropdown-item" href="#"></a>
                                        <a class="dropdown-item" href=""><i class="fas fa-user mr-2"></i>Movilidad: <b class="text-movilidad"></b></a>
                                        <a class="dropdown-item" href=""><i class="fas fa-power-off mr-2"></i>Placa: <b class="text-placa"></b></a>
                                        <a class="dropdown-item" href="#"><i class="fas fa-cog mr-2"></i>Categoria: <b class="text-categoria"></b></a>
                                        <a class="dropdown-item" href=""><i class="fas fa-power-off mr-2"></i>Movil: <b class="text-celular"></b></a>
                                    </div>
                                </div>
						
						<div class="table-responsive"> 
						Seleccione una parada para ver estudiantes:
						<select name="" id="selpuntos" class="form-control" onchange="ver_parada()">
							<option value="">Seleccione ...</option>
							<!--<option value="">calle1</option>
							<option value="">plaza</option>-->
						</select>
							<table id="table" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
								<thead>
									<tr class="active">
										<th class="text-nowrap">#</th>
										<th class="text-nowrap">Estudiante</th>
										<th class="text-nowrap">Curso</th>
										<th class="text-nowrap">Genero</th>
										<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
										<th class="text-nowrap">Opciones</th>
										<?php endif ?>
									</tr>
								</thead>
							
							<tbody>
								<!--<tr><td>1</td>
								<td>marc</td>
								<td>4 A</td>
								<td>m</td><td><span class="badge badge-success">En el bus</span></td></tr>
								<tr><td>1</td>
								<td>marc</td>
								<td>4 A</td>
								<td>m</td><td><span class="badge badge-danger">No subio</span></td></tr>
								<tr><td>1</td>
								<td>marc</td>
								<td>4 A</td>
								<td>m</td><td><span class="badge badge-success">En el bus</span></td></tr>
								<tr><td>1</td>
								<td>marc</td>
								<td>4 A</td>
								<td>m</td><td><span class="badge badge-warning">Se quedo</span></td></tr>-->
							</tbody>
							</table>
						</div>
						<?php //else : ?>
					<!--	<div class="alert alert-info">
							<strong>Atención!</strong>
							<ul>
								<li>No existen rutas registrados en la base de datos.</li>
								<li>Para crear nuevos rutas debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
							</ul>
						</div>-->
						<?php //endif ?>
					</div>
				</div>
                <div class="mensajes_nuevos">
                  <hr>
                   <h4>Mensajes:</h4>
                    <table id="table_msg" class="table"><thead>
                        <tr><th>N</th>
                        <th>Mensaje</th>
                        <th>Descripcion</th>
                        <th>Fecha</th>
                        <th>Origen</th>
                        </tr>
                    </thead><tbody>
                        <!--<tr><td>1</td>
                        <td>msdf</td>
                        <td>sdf</td>
                        <td>ruta 2</td>
                        <td>angeles</td>
                        </tr>-->
                    </tbody></table>
                </div>
			</div><!-- fin del card -->
		</div>
		
 
	</div>
	<!-- Libreria para nuestro servidor node js -->
	<script src="<?= js; ?>/node-server/socket.io.min.js"></script>
 

	<style>
		.btn-text {
			color: white;
		}
	</style>
	<script src="<?= js; ?>/jquery.base64.js"></script>
	<script src="<?= js; ?>/pdfmake.min.js"></script>
	<script src="<?= js; ?>/vfs_fonts.js"></script>
 
	<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
	<script src="<?= js; ?>/selectize.min.js"></script>
	<script src="<?= js; ?>/jquery.validate.js"></script>
	<script src="<?= js; ?>/matrix.form_validation.js"></script>
	<script src="<?= js; ?>/educheck.js"></script>
	<script src="<?= js; ?>/leaflet.js"></script>
	<script src="<?= js; ?>/leaflet-routing-machine.js"></script>
	<script src="<?= js; ?>/Leaflet.Icon.Glyph.js"></script>
 
 <!-- Libreria para el uso de firebase -->
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/7.14.6/firebase-app.js"></script>
<!-- incluimos la base de datos -->
<script src="https://www.gstatic.com/firebasejs/7.14.6/firebase-database.js"></script>
<!-- Configuracion de Firebase -->
<script src="<?= js; ?>/firebaseGondola.js"></script>
	<script  >	//alert('asdasx');</script>
	<?php //require_once show_template('footer-design'); ?>
<script>
 
	
var dominio_this='<?=$_institution['nombre_dominio']?>';
    var commentsRef = firebase.database().ref(dominio_this);
    commentsRef.on('child_added', function(snapshot) {
		
			console.log(snapshot.val().dominio);//.convinacion_id);
			html = '';

			if (snapshot.val().dominio == dominio_this) {//geobus-comunicado
				var key = snapshot.key;
				var lat =snapshot.val().lat;
				var lng =snapshot.val().lng;
				carga_bus(lat,lng);
				//asignar posicion;

				borrar_mensaje(key);
			} else{
				console.log("convinacion web" + 1);
			}
	 });
	var comunc = firebase.database().ref(dominio_this+'-comunicados');
       comunc.on('child_added', function(snapshot) {
        console.log(snapshot.val().dominio);//.convinacion_id);
        html = ''; 
		   
		 //debugger;
         if (snapshot.val().nombre_dominio == dominio_this) {//geobus-comunicado
			
            var key = snapshot.key;
	  
			var desc =snapshot.val().desc;
			var fecha =snapshot.val().fecha;
			//var id_comunicado =snapshot.val().id_comunicado;
			var msgExtra =snapshot.val().msgExtra;
			var nombre_dominio =snapshot.val().nombre_dominio;
			var personas_id =snapshot.val().personas_id;
			var titulo =snapshot.val().titulo;
			
            $('#table_msg').prepend('<tr><td>0</td>'+
                            '<td><span class="badge badge-warning">Nuevo</span> '+titulo+'</td>'+
                            '<td>'+desc+'</td>'+
                            '<td>'+fecha+'</td>'+
                            '<td>'+msgExtra+'</td>'+
                            '</tr>');
            firebase.database().ref(dominio_this+'-comunicados').child(key).remove();
            
        } else{
            console.log("convinacion web" + 1);
        }
 
    });

    function borrar_mensaje(key) {
        firebase.database().ref(dominio_this).child(key).remove();
        //firebase.database().ref("ratreobus").child(key).remove();
    }
   
    function ver_comunicado(id_comunicado,id_rut){
         $.ajax({
		  url: '?/gon-seguimiento/procesos',
		  type: 'POST',
		  data: {'boton':'ver_comunicado',
				 id:id_comunicado
		  },
		  dataType: 'JSON',
		  success: function(resp){
			  //debugger;
			   $('.badge-ruta'+id_rut).html(' msg nuevos ');
			 jsonrutas_G
             for (var i = 0; i < jsonrutas_G.length; i++) {
                if(id_rut==jsonrutas_G[i].id_ruta){
                    var id_ruta=jsonrutas_G[i]['id_ruta'];
                    var conductor=jsonrutas_G[i]['nombres'];
                    var nombre=jsonrutas_G[i]['nombre'];//ruta
                    var nombre_gondola=jsonrutas_G[i]['nombre_gondola']
                    var placa=jsonrutas_G[i]['placa'];
                 $('#table_msg').prepend('<tr><td>0</td>'+
                            '<td><span class="badge badge-warning">Nuevo</span> '+resp.nombre_evento+'</td>'+
                            '<td>'+resp.descripcion+'</td>'+
                            '<td>'+nombre+'</td>'+
                            '<td>'+conductor+'</td>'+
                            '</tr>');
                    break;
                 }
             }
		  }
			
		}).done(function(){
			//carga_inimap_colegio();
	 		//cargarAreaRuta(0);
			//carga_bus(lat_bus,lng_bus);
		});
    }
    //cargar_comunicados();
    //function cargar_comunicados(){
    //    
    //}
 
	
 </script>

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
	//puntos area
	//var t_puntosarea ="*-16.521814941433973||-68.17850228210825||Restaurant Gabo, Avenida Panamericana, Municipio El Alto, Bolivia||4||4*-16.524274528567826||-68.1769570910572||cancha||30||0*-16.524768242872767||-68.18159459102732||desde ruta||31||0";'<?//= $t_clientes ?>';
	//var rutaimagen ='<?//= assets.'/imgs' ?>';
	//var puntos_area = t_puntosarea.split('*');
	 
/*	var coord ='*-16.516333530112483,-68.16705465316774*-16.515983832814715,-68.18325519561769*-16.51765003191006,-68.18336248397829*-16.51746489938681,-68.18823337554933*-16.51976875815476,-68.19042205810548*-16.531102520350267,-68.17784786224367*-16.52334791281201,-68.16930770874025';//'<?//= $rutas['coordenadas'] ?>';
	var coordnom ='Area';//'<?//= $rutas['nombre'] ?>';
	var puntosAreas = coord.split('*');
	
	var waypoints1 = new Array();
	var puntArea = new Array();
		
	for (var i=1; puntosAreas.length > i; i++) {
		var parte = puntosAreas[i].split(',');
		waypoints1.push(L.latLng([parte[0], parte[1]]));
		puntArea.push([parte[0], parte[1]]);
	}*/
 
	//todos los puntos en un arrar
	/*var puntonumber = new Array();
	for (var i=1; puntos.length > i; i++) {
		var parte = puntos[i].split('||');
		puntonumber.push(L.latLng([parte[0], parte[1]])); 
	}
	
	 
	const tilesProvider='';
	*/
	 window.LRM = {
            apiToken: 'pk.eyJ1IjoibGllZG1hbiIsImEiOiJjamR3dW5zODgwNXN3MndqcmFiODdraTlvIn0.g_YeCZxrdh3vkzrsNN-Diw'
        };

 	var map = L.map('map').setView(['-16.515983832814715', '-68.18325519561769'], 15);//punto colegio/gondola
    var titles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?access_token=' + LRM.apiToken, {
        }).addTo(map);
	var myRenderer = L.canvas({ padding: 1 });
	//añadir los puntos
	var markersArr=new Array(); 
		
		

	var lat_col='<?=$lat?>'; //'-16.515983832814720';
	var lng_col='<?=$lng?>'; //'-68.18325519561769';
		
	var lat_bus='<?=$lat?>';//'-16.531102520350267';//-16.516333530112483
	var lng_bus='<?=$lng?>'; //'-68.17784786224367'; //-68.16705465316774
 
	 var marcador_cole_img = L.icon({
			iconUrl: '<?= assets.'/imgs' ?>/cole.png',
			iconSize:     [68, 55],
			iconAnchor:   [31, 54],//22+22//44 -3-3
		 });
	var marcador_bus_img = L.icon({
			iconUrl: '<?= assets.'/imgs' ?>/bus.png',
			iconSize:     [40, 40],
			iconAnchor:   [31, 54],//22+22//44 -3-3
		 });
function carga_inimap_colegio(){
	
	map.remove();
	map = L.map('map').setView([lat_col, lng_col], 15);//punto colegio/gondola
    titles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?access_token=' + LRM.apiToken, { }).addTo(map);
	//cargamapa=false;
	//con imagen::::::::::::::::::
	var marker_cole = L.marker(([lat_col, lng_col]), {icon: marcador_cole_img}).addTo(map);
	 //markers.bindPopup('<a href="?/gon-puntos/asignar/'+parte[3]+'" title="Añadir estudiante" class="btn btn-info btn-xs"><span class="icon-user-follow"></span></a><b class="text-center">AQUI!</b><br>'+parte[2]+".").openPopup();
	
}
var marker_bus;	
function carga_bus(lat,lng){
	if (map.hasLayer(marker_bus)) {
		map.removeLayer(marker_bus);
	}
	marker_bus = L.marker(([lat, lng]), {icon: marcador_bus_img}).addTo(map);
	
	marker_bus1 = L.marker(([lng, lat]), {icon: marcador_bus_img}).addTo(map);


	var someFeatures = [{
    "type": "Feature",
    "properties": {
        "name": "Coors Field",
        "iconUrl": '<?= assets.'/imgs' ?>/bus.png',
        "show_on_map": true
    },
    "geometry": {
        "type": "Point",
        "coordinates": [-104.99404, 39.75621]
    }
}, {
    "type": "Feature",
    "properties": {
        "name": "Busch Field",
        "show_on_map": true
    },
    "geometry": {
        "type": "Point",
        "coordinates": [-104.98404, 39.74621]
    }
}];

L.geoJSON(someFeatures, {
    filter: function(feature, layer) {
        return feature.properties.show_on_map;
    }
}).addTo(map);


}
	 /*
	 var marcador = L.icon({
			iconUrl: rutaimagen+'/parada.png',
			iconSize:     [68, 55],
			iconAnchor:   [31, 54],//22+22//44 -3-3
		 });*/ 
 cargarrutas();
var f = new Date();
var fechaHoy=(f.getFullYear() + "-0"+(f.getMonth() +1)+ "-" +  f.getDate() );
	$('.text-fecha').text(fechaHoy);	
		
var jsonrutas_G;
 function cargarrutas(){
// debugger;
		$.ajax({
		  url: '?/gon-seguimiento/procesos',
		  type: 'POST',
		  data: {'boton':'listar_todo_rutas'
				 
		  },
		  dataType: 'JSON',
		  success: function(resp){
			  jsonrutas_G=resp;
			   $('.btns-rutas').html('');
			  for (var i = 0; i < resp.length; i++) {
				  $('.btns-rutas').append('<button type="button" class="btn btn-primary btn-ruta'+resp[i]['id_ruta']+'" onclick="cargarAreaRuta('+i+')" >'+resp[i]['nombre']+' <span class="badge badge-warning badge-ruta'+resp[i]['id_ruta']+'"></span></button>');
				
			  }
		  }
			
		}).done(function(resp){
			 
			if(resp.length){ 
	 		    cargarAreaRuta(0);
			}
			//carga_bus(lat_bus,lng_bus);
            //ver_comunicado(100,2);
		});
 }

		
function cargarAreaRuta(i){
	 	var id_ruta=jsonrutas_G[i]['id_ruta'];
	 	var id_user=jsonrutas_G[i]['id_user'];
	 	var conductor=jsonrutas_G[i]['nombres'];
	 	var categoria_licencia=jsonrutas_G[i]['categoria'];
	 	var fecha_nacimiento=jsonrutas_G[i]['fecha_nacimiento'];
	 	var lentes=jsonrutas_G[i]['lentes'];
	 	var foto=jsonrutas_G[i]['foto'];
	 	var nombre_gondola=jsonrutas_G[i]['nombre_gondola'];
	 	var placa=jsonrutas_G[i]['placa'];
	 	var contacto=jsonrutas_G[i]['contacto']; 
		$('.cond-user-name').text(conductor);
		$('.text-categoria').text(categoria_licencia);
		$('.text-movilidad').text(nombre_gondola);
		$('.text-placa').text(placa);
		$('.text-celular').text(contacto);
		   
 
		//map = L.map('map').setView(['-16.515983832814715', '-68.18325519561769'], 15);
		//titles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?access_token=' + LRM.apiToken, {  }).addTo(map);
		 var cargamapa=true;
		$.ajax({
		  url: '?/gon-seguimiento/procesos',
		  type: 'POST',
		  data: {'boton':'listar_area_ruta',
				'id_ruta':id_ruta, 
		  },
		  dataType: 'JSON',
		  success: function(resp){
			  
			  var coord = resp['coordenadas'];
				//='*-16.516333530112483,-68.16705465316774*-16.515983832814715,-68.18325519561769*-16.51765003191006,-68.18336248397829*-16.51746489938681,-68.18823337554933*-16.51976875815476,-68.19042205810548*-16.531102520350267,-68.17784786224367*-16.52334791281201,-68.16930770874025';//'<?//= $rutas['coordenadas'] ?>';
			 var coordnom = resp['nombre'];//'Area';//'<?//= $rutas['nombre'] ?>';
			 var puntosAreas = coord.split('*');
			  
		   //var waypoints1 = new Array();
			var puntArea = new Array();
			for (var i=1; puntosAreas.length > i; i++) {
				var parte = puntosAreas[i].split(',');
				//waypoints1.push(L.latLng([parte[0], parte[1]]));
				puntArea.push([parte[0], parte[1]]);
				
				if(cargamapa){
		 			map.remove();
				   map = L.map('map').setView([parte[0], parte[1]], 15);//punto colegio/gondola
    	 			titles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?access_token=' + LRM.apiToken, {  }).addTo(map);
				 	carga_inimap_colegio();
					carga_bus(lat_bus,lng_bus);
				 	cargamapa=false;
				   }
			}
			//  map = L.map('map').setView([parte[0], parte[1]], 15);
			L.polygon(puntArea).addTo(map).bindPopup(coordnom);
			  
			  /*
			  var coord ='*-16.516333530112483,-68.16705465316774*-16.515983832814715,-68.18325519561769*-16.51765003191006,-68.18336248397829*-16.51746489938681,-68.18823337554933*-16.51976875815476,-68.19042205810548*-16.531102520350267,-68.17784786224367*-16.52334791281201,-68.16930770874025';//'<?//= $rutas['coordenadas'] ?>';
			var coordnom ='Area';//'<?//= $rutas['nombre'] ?>';
			var puntosAreas = coord.split('*');
			  
		   //var waypoints1 = new Array();
			var puntArea = new Array();
			for (var i=1; puntosAreas.length > i; i++) {
				var parte = puntosAreas[i].split(',');
				//waypoints1.push(L.latLng([parte[0], parte[1]]));
				puntArea.push([parte[0], parte[1]]);
			}
			  
			L.polygon(puntArea).addTo(map).bindPopup(coordnom);*/
			  
		  }
		}).done(function(){
			
			cargarTodosPuntosdecarga(id_ruta);//debugger;	
			ver_comunicados_conductor(id_ruta,id_user);
			//carga_bus(lat_col,lng_col);
		});
	 }
	var puntos_G;
	//cargarTodosPuntosdecarga();	
	 function 	cargarTodosPuntosdecarga(id_ruta){
		$.ajax({
		  url: '?/gon-seguimiento/procesos',
		  type: 'POST',
		  data: {'boton':'listar_puntos_estud',
				'id_ruta':id_ruta, 
		  },
		  dataType: 'JSON',
		  success: function(resp){
			  puntos_G=resp
			  var tam=resp.length;
			  //debugger;
			  var fecha=fechaHoy;
			  $('#selpuntos').html('<option value="">Seleccione ...</option>');
			  for (var i = 0; i < tam; i++) {
				  //actualisar el lelect:::::::::::::::::::::::::::::::::::::::::::://resp[i]['id_punto']
				$('#selpuntos').append('<option value="'+i+'">'+resp[i]['nombre_lugar']+'</option>');
				  
				  
			  	//actualisar color de puntos::::::::::::::::::::::::::::::::::::::
				var tamEst=resp[i]['estudiantes'].length;
			  	var id_punto=resp[i]['id_punto']; 
				 resp[i]['n_estu']=tamEst; 
				  var tamA=0; var tamB=0; var tamC=0; var tamD=0;
				  
			  	for (var j = 0; j < tamEst; j++) {
					
					if(resp[i]['estudiantes'][j]['json_asistencia']!=''){
						var tamfechasAsi=resp[i]['estudiantes'][j]['json_asistencia'][fecha];
						if(tamfechasAsi){
							//ver si existe un indice
							 if (typeof(resp[i]['estudiantes'][j]['json_asistencia'][fecha].A) != "undefined")
								tamA++;
 
							//if(resp[i]['estudiantes'][j]['json_asistencia'][fecha].findIndex(mascota => mascota.nombre === 'A'))
							//	let indice = arreglo.findIndex(mascota => mascota.nombre === busqueda);
							//if(resp[i]['estudiantes'][j]['json_asistencia'][fecha].A.length>0)
							if(typeof(resp[i]['estudiantes'][j]['json_asistencia'][fecha].B) != "undefined")
							//if(resp[i]['estudiantes'][j]['json_asistencia'][fecha].B.length>0)
								tamB++;
							if(typeof(resp[i]['estudiantes'][j]['json_asistencia'][fecha].C) != "undefined")
								tamC++;
							if(typeof(resp[i]['estudiantes'][j]['json_asistencia'][fecha].D) != "undefined")
								tamD++;
						}

						
					}
				}
				  resp[i]['n_estu_A']=tamA;//subio al bus
				  resp[i]['n_estu_B']=tamB;//bajo al cole
				  resp[i]['n_estu_C']=tamC;//subio al bus
				  resp[i]['n_estu_D']=tamD;//bajo a casa
				  //estudiantes subieron al bus y todos bajaron
				  //debugger;
				  var color='#000';
				  if(tamA>=tamEst || tamD>=tamEst)
					  color='#c7c7c7';//plomo_completo
				  
				  if(tamA<tamEst)
					  color='#c431ce';//lila_falta oscuro #ffa242 //claro #f9d861
				  
				  //si no subio ninguno en parada
				   if(tamA<1)
					  color='#348acd';//'azul_ninguno';
				  
				  //bajaron parcialmente
				   if(tamD<tamEst && tamD>0)
					  color= '#ffa242';//'naranjabajo_falta';
					   
					   
				  //debugger;
				  //armar un punto
				  var lat=resp[i]['latitud'];var lng=resp[i]['longitud'];
				  //var n_estu=resp[i]['n_estu'];
				  var nombre_lugar=resp[i]['nombre_lugar'];var id_punto=resp[i]['id_punto'];
				  //var array= L.latLng([lat, lng]);//puntonumber
				  var markerscant = L.marker(([lat, lng]),  {
						//draggable: false,
						icon: L.icon.glyph({ glyph:tamEst+''}) 
					}).addTo(map);
					markerscant.bindPopup('<div><span class="fa fa-eye btn"  onclick="ver_parada('+i+')">Ver</span> <br>'+nombre_lugar+"</div>").openPopup();
					//markerscant.attr('valor','MARCO');
				  //markerscant.on('click',function(){
					 //  var valor=$(this).attr('valor');
					 //alert('pulsas te marcador'+id_punto); 
				   //});
				  //estado de los puntos
				  var markers = L.circleMarker(([lat, lng]), {
						renderer: myRenderer,
						radius: 20,
						fillColor: color,
						fillOpacity: 0.7,
						color: '#fff',
						weight: 3
					}).addTo(map);

					markersArr[i]=markers;
				  
				  
			  }
			  console.log('resp-------------------------');
			  console.log(resp);
			//$('#modal_fecha').modal('hide');
			//$('#tablaPersonal').find('tbody').html('');var html='';
			//tablaPersonal.clear();
		/*	var num = 0;
			for (var i = 0; i < resp['leidos'].length; i++) {
			num++;
			 tablaPersonal.row.add( [
			  num,      
			  resp['leidos'][i]['primer_apellido']+ ' ' + resp['leidos'][i]['segundo_apellido']+ ' '+ resp['leidos'][i]['nombres'],
			  '<span class="icon"><i class="fas fa-check" style="color:#00adff"></i><i class="fas fa-check" style="color:#00adff"></i></span>',
			  resp['leidos'][i]['leido_fecha']
			  ] ).draw( false );
			}
			for (var i = 0; i < resp['noleidos'].length; i++) {
			num++;
			 tablaPersonal.row.add( [
			  num,      
			  resp['noleidos'][i]['primer_apellido']+ ' ' + resp['noleidos'][i]['segundo_apellido']+ ' '+ resp['noleidos'][i]['nombres'],
			  '<span class="icon"><i class="fas fa-check" style="color:#c2c2c2"></i><i class="fas fa-check" style="color:#c2c2c2"></i></span>',
			  ''
			  ] ).draw( false );
			}*/
		  }, error: function(e){
			  console.log(e);
		  }
		}).done(function(){
			//ver_comunicados_conductor(id_ruta);
		});
	 }
		
function ver_comunicados_conductor(id_ruta,id_user){
	$.ajax({
		  url: '?/gon-seguimiento/procesos',
		  type: 'POST',
		  data: {'boton':'listar_comunicados',
				'id_ruta':id_ruta, 
				'id_user':id_user, 
		  },
		  dataType: 'JSON',
		  success: function(resp){
			  for (var i = 0; i < resp.length; i++) {
				  var arrDesc=resp[i].descripcion.split('||');
				  var desc=arrDesc[0];
				  var orig=arrDesc[1];
			   $('#table_msg').append('<tr><td>'+(i+1)+'</td>'+
                            '<td> '+resp[i].nombre_evento+'</td>'+
                            '<td>'+desc+'</td>'+
                            '<td>'+resp[i].fecha_final+'</td>'+
                            '<td>'+orig+'</td>'+
                            '</tr>');
			  //<span class="badge badge-warning">Nuevo</span>
			  }
			}, error: function(e){
			  console.log(e);
		  }
		});
}
		
		
function ver_parada(index){
  //debugger;
	if(index==undefined){
		index=$('#selpuntos').val();
	} 
	//var estudiantes=puntos_G[index];
	$('#table').find('tbody').html('');
	 var fecha=fechaHoy;
	for (var j = 0; j < puntos_G[index].estudiantes.length; j++) {
		//ver el estado actual
		var estado='<span class="badge badge-danger">No se presento</span>';
			if(puntos_G[index]['estudiantes'][j]['json_asistencia']!=''){
				var tamfechasAsi=puntos_G[index]['estudiantes'][j]['json_asistencia'][fecha];
				if(tamfechasAsi){
					//ver si existe un indice
					 if (typeof(puntos_G[index]['estudiantes'][j]['json_asistencia'][fecha].A) != "undefined"){
						 estado='<span class="badge badge-info">Subio bus camino al colegio</span>';// tamA++; //sbio al bus
						 }
					if(typeof(puntos_G[index]['estudiantes'][j]['json_asistencia'][fecha].B) != "undefined"){
							estado='<span class="badge badge-success">En el colegio</span>';// tamB++;//bajo cole
						}
					if(typeof(puntos_G[index]['estudiantes'][j]['json_asistencia'][fecha].C) != "undefined"){
							estado='<span class="badge badge-info">En el bus retorno</span>';// tamC++;//subio al bus
						}
					if(typeof(puntos_G[index]['estudiantes'][j]['json_asistencia'][fecha].D) != "undefined"){
							estado='<span class="badge badge-warning">Bajo en parada</span>'; //tamD++;//bajo a casa
						}
				}


			}
		///debugger;
		$('#table').find('tbody').append('<tr><td>'+(j+1)+'</td>'+
								'<td>'+puntos_G[index].estudiantes[j]['nombres']+'</td>'+
								'<td>-</td>'+
								'<td>'+puntos_G[index].estudiantes[j]['genero']+'</td><td>'+estado+'</td></tr>'); 
	} 
	var id_punto=index;//puntos_G[index].id_punto;
	$('#selpuntos').val(id_punto);
	
	//ver punto enfocado::::::::::::: 
	ver_rutaactual(puntos_G[index].latitud,puntos_G[index].longitud)
}
	 
		
		
		
//var t_puntos ="*-16.521814941433973||-68.17850228210825||Restaurant Gabo, Avenida Panamericana, Municipio El Alto, Bolivia||4||4*-16.524274528567826||-68.1769570910572||cancha||30||0*-16.524768242872767||-68.18159459102732||desde ruta||31||0"; //'<?//= $t_clientes ?>'; 
	//var puntos = t_puntos.split('*');
	 //cargarTodosPuntos();
		
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
		// var array= L.latLng([parte[0], parte[1]]);//puntonumber
		//marcamos cantidades en punto
 
		var markerscant = L.marker(([parte[0], parte[1]]),  {
						//draggable: false,
						icon: L.icon.glyph({ glyph:parte[4]}) 
					}).addTo(map);
		markerscant.bindPopup('<a href="?/gon-puntos/asignar/'+parte[3]+'" title="Añadir estudiante" class="btn btn-info btn-xs"><span class="icon-user-follow"></span></a><b class="text-center">AQUI!</b><br>'+parte[2]+".").openPopup();
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

		
	var ultimoMarker;
	var antesindice;
function ver_rutaactual(x,y){
 
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
	if(antesindice!=undefined){//antesindice!=0 && 
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
	for (var i=0; i<markersArr.length; i++) {
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
		
	 
		
		
		/*
		var columns = [{
				data: 'id_ruta'
			},

			{
				data: 'nombre'
			},

			{
				data: 'descripcion'
			},
			{
				data: 'conductor'
			}
		];
		var cont = 0;
		var dataTable = $('#table').DataTable({
			language: dataTableTraduccion,
			searching: true,
			paging: true,
			"lengthChange": true,
			"responsive": true,
			ajax: {
				url: '?/gon-rutas/busqueda',
				dataSrc: '',
				type: 'POST',
				dataType: 'json'
			},
			columns: columns,
			"columnDefs": [{
					"render": function(data, type, row) {
						var contenido = row['id_ruta'];
						var result = "<a href='#' class='btn btn-info btn-xs' onclick = 'ver(" + contenido + ")'><span class='icon-eye'></span></a>";

						result += "<?php if ($permiso_ver) : ?><a href='#' class='btn btn-info btn-xs' onclick = 'ver()'><span class='icon-eye'></span></a><?php endif ?> &nbsp" +
							"<?php if ($permiso_editar) : ?><a href='#' class='btn btn-warning btn-xs btn-text' style='color:white' onclick='abrir_editar(" + '"' + contenido + '"' + ")'><span class='icon-note'></span></a><?php endif ?> &nbsp" +
							"<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' onclick='abrir_eliminar(" + '"' + contenido + '"' + ")'><span class='icon-trash'></span></a><?php endif ?>";
						
						return result;
					},
					"targets": 4
				},
				{
					"render": function(data, type, row) {
						cont = cont + 1;
						return cont;
					},
					"targets": 0
				}
			]
		});

*/
/*		function ver(id_ruta) {
			$.ajax({
				data: {
					id_ruta: id_ruta
				},
				url: '?/gon-rutas/gonprocesos',
				type: 'post',
				beforeSend: function() {
					$("#resultado").html("Procesando, espere por favor...");
				},
				success: function(response) {
					$("#mapa").html(response);
				}
			});
		}
		
		const websocket = io('http://172.16.100.6:5000');*/
		
/*
		const map = L.map('mapa_seguimiento').setView([-16.50387460905155, -68.16102966172153], 17);
		//Mi socket que permite interectar con el servidor
		//const websocket = io();

		const tileURL = 'https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png';
		//const tileURL = 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}';


		L.tileLayer(tileURL).addTo(map);

		//Permitir ver la localisacion
		map.locate({
			enableHighAcurracy: true
		});
		map.on('locationfound', e => {
			console.log(e);
			const coords = [e.latlng.lat, e.latlng.lng]
			const marker = L.marker(coords);
			marker.bindPopup('Mi Poscicion de pc');
			map.addLayer(marker);
			websocket.emit('usuarioCoordenada', e.latlng);
		});

		var estilo = {'background-color': '#FF0000'};

		websocket.on('nuevoUsuarioCoordenadas', (coords) => {
			console.log("Nuevo usuario");
			const marker = L.marker([coords.lat, coords.lng]);

			marker.bindPopup("<h2>"+coords.usuario+"</h2> <p> Mensaje : " + coords.msg+"</p>",estilo);
			map.addLayer(marker);
		});
*/

 
	</script>