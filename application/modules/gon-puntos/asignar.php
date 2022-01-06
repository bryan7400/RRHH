<?php

// Obtiene los parametros
$id_punto = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene el rutas
$punto = $db->select('*')->from('gon_puntos')->where('id_punto', $id_punto)->fetch_first();

//lista de estudiantes
$estudiantes = $db->query('SELECT a.id_inscripcion, a.punto_id, c.nombres, c.primer_apellido, c.segundo_apellido, c.numero_documento, e.descripcion, f.nombre_aula, g.nombre_turno
	FROM ins_inscripcion a
    LEFT JOIN ins_estudiante b ON a.estudiante_id = b.id_estudiante
    LEFT JOIN sys_persona c ON b.persona_id = c.id_persona
    LEFT JOIN ins_aula_paralelo d ON a.aula_paralelo_id = d.id_aula_paralelo
    LEFT JOIN ins_paralelo e ON d.paralelo_id = e.id_paralelo
    LEFT JOIN ins_aula f ON d.aula_id = f.id_aula
    LEFT JOIN ins_turno g ON d.turno_id = g.id_turno')->fetch();

//obbtiene los puntos
$puntos = $db->select('*')->from('gon_puntos')->where('ruta_id',$punto['ruta_id'])->where('id_punto!=',$id_punto)->fetch();
$coordenad = '';
$nombres = '';
$numeros = '';
foreach($puntos as $punto1){
    $coordenad = $coordenad.'*'.$punto1['latitud'].','.$punto1['longitud'];
    $nombres = $nombres.'*'.$punto1['nombre_lugar'];
}

// Ejecuta un error 404 si no existe el rutas
if (!$punto) { require_once not_found(); exit; }

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_crear = in_array('crear', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);
//$permiso_asignar = in_array('asignar', $_views);
$permiso_asignar = true;
$permiso_ver = in_array('ver', $_views);
$permiso_activar = in_array('activar', $_views);

?>
<?php require_once show_template('header-design'); ?>
    <link rel="stylesheet" href="<?= css; ?>/leaflet.css">
    <link rel="stylesheet" href="<?= css; ?>/leaflet-routing-machine.css">
    <link rel="stylesheet" href="<?= css; ?>/site.css">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Asignar estudiante</h2>
                <p class="pageheader-text"></p>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Puntos</a></li>
                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Asignar estudiantes</a></li>
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
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                        <h4>Lista estudiantes</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <?php if ($estudiantes) : ?>
                        <input type="hidden" id="coords" value="<?= $coordenad; ?>"/>
                        <input type="hidden" id="nombres" value="<?= $nombres; ?>"/>

                        <table id="table" class="table table-bordered table-condensed table-striped table-hover">
                            <thead>
                            <tr class="active">
                                <th class="text-nowrap">#</th>
                                <th class="text-nowrap">Nombres</th>
                                <th class="text-nowrap">Apellidos</th>
                                <th class="text-nowrap">Documento</th>
                                <th class="text-nowrap">Aula</th>
                                <th class="text-nowrap">Paralelo</th>
                                <th class="text-nowrap">Turno</th>
                                <?php if ($permiso_activar) : ?>
                                    <th class="text-nowrap">Opciones</th>
                                <?php endif ?>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr class="active">
                                <th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
                                <th class="text-nowrap text-middle">Nombres</th>
                                <th class="text-nowrap text-middle">Apellidos</th>
                                <th class="text-nowrap text-middle">Documento</th>
                                <th class="text-nowrap text-middle">Aula</th>
                                <th class="text-nowrap text-middle">Paralelo</th>
                                <th class="text-nowrap text-middle">Turno</th>
                                <?php if ($permiso_activar) : ?>
                                    <th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
                                <?php endif ?>
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php foreach ($estudiantes as $nro => $estudiante) : ?>
                                <tr>
                                    <th class="text-nowrap"><?= $nro + 1; ?></th>
                                    <th class="text-nowrap"><?= $estudiante['nombres']; ?></th>
                                    <th class="text-nowrap"><?= $estudiante['primer_apellido'].' '.$estudiante['primer_apellido']; ?></th>
                                    <th class="text-nowrap"><?= $estudiante['numero_documento']; ?></th>
                                    <th class="text-nowrap"><?= $estudiante['descripcion']; ?></th>
                                    <th class="text-nowrap"><?= $estudiante['nombre_aula']; ?></th>
                                    <th class="text-nowrap"><?= $estudiante['nombre_turno']; ?></th>
                                    <?php if ($permiso_activar) : ?>
                                        <td class="text-nowrap">
                                            <?php 
                                            if ($estudiante['punto_id'] == $id_punto) { 
                                            ?>
                                                <a  data-toggle="tooltip" data-title="Ya asignado" class="btn btn-xs btn-success">
                                                    <span class="icon-check"></span>
                                                </a>
                                            <?php 
                                            }else{ 
                                            ?>
                                                <a onclick="activar(<?= $estudiante['id_inscripcion']; ?>, <?= $id_punto; ?>)" data-toggle="tooltip" data-title="Asignar al punto">
                                                    <span class="icon-check"></span>
                                                </a>
                                            <?php 
                                            } 
                                            if ($estudiante['punto_id'] == 0){ 
                                            ?>
                                                <a data-toggle="tooltip" data-title="No asignado" class="btn btn-xs btn-danger">
                                                    <span class="icon-check"></span>
                                                </a>
                                            <?php 
                                            }elseif($estudiante['punto_id'] != $id_punto){ 
                                            ?>
                                                <a data-toggle="tooltip" data-title="Asignado en otro punto" class="btn btn-xs btn-info"><span class="icon-check"></span></a>
                                            <?php 
                                            } 
                                            ?>
                                        </td>
                                    <?php endif ?>
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
    <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-body">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                    <div id="map" class="map col-sm-12 embed-responsive embed-responsive-16by9"></div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="<?= js; ?>/leaflet.js"></script>
    <script src="<?= js; ?>/leaflet-routing-machine.js"></script>
    <script src="<?= js; ?>/Leaflet.Icon.Glyph.js"></script>
    <script src="<?= js; ?>/Leaflet.Editable.js"></script>
    <script src="<?= js; ?>/leaflet_measure.js"></script>
<script>
$(function () {
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

//    console.log(coor);
//    console.log(nombres);

//    var sw = $("#table tbody tr").length;
//    console.log(sw);
//    $('.coordenadas').each(function (i) {
//        var latitud = $.trim($(this).find('.latitud').text());
//        var longitud = $.trim($(this).find('.longitud').text());
//        var numero = $.trim($(this).find('.numero').text());
//        var nombre = $.trim($(this).find('.nombre').text());
//
//        if (latitud != '0.0' && longitud != '0.0') {
//            latitudes.push(latitud);
//            longitudes.push(longitud);
//            numeros.push(numero);
//            nombres.push(nombre);
//            if(sw === 1){
//                latitudes.push(latitud);
//                longitudes.push(longitud);
//                numeros.push(numero);
//                nombres.push(nombre);
//                sw = 2;
//            }
//        }
//        waypoints1.push(L.latLng([latitud, longitud]));
//    });
//    console.log(waypoints1);

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


});
var dataTable = $('#table').DataTable({
    language: dataTableTraduccion,
    searching: true,
    paging: true,
    "lengthChange": true,
    "responsive": true
});
    function activar(alum,punto){
        console.log(alum);
        var formData = new FormData();
        formData.append('alum',alum);
        formData.append('punto',punto);
        $.ajax({ //datos que se envian a traves de ajax
            type:  'post', //método de envio
            dataType: 'json',
            url:   '?/gon-puntos/activar', //archivo que recibe la peticion
            data:  formData,
            contentType: false,
            processData: false
        }).done(function (ruta) {
            console.log(ruta);
            if (ruta.estado == 's') {
                $('#puntoform').trigger("reset");
                alertify.success('El estudiante fue asignado satisfactoriamente.');
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
</script>
<?php require_once show_template('footer-design'); ?>