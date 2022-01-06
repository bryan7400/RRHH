<?php

// Obtiene la fecha
$fecha = str_replace('/', '-', now());

// Obtiene los formatos
$formato_textual = get_date_textual($_institution['formato']);
$formato_numeral = get_date_numeral($_institution['formato']);


// var_dump(sizeof($_params));
// exit();

// Verifica si existe el parametro
if (sizeof($_params) == 1) {
	// Verifica el tipo del parametro
	if (!is_date($_params[0])) {
		// Redirecciona la pagina
		redirect('?/rrhh-asistencia/listar/' . $fecha);
	}
} else {
	// Redirecciona la pagina
	redirect('?/rrhh-asistencia/listar/' . $fecha);
}

// Obtiene el parametro
$fecha = date_encode($_params[0]);

// Obtiene los empleados
// $empleados = $db->query("select e.*, c.cargo 
// 				from sys_empleados e 
// 				left join per_cargos c on e.cargo_id = c.id_cargo 
// 				where e.activo = 's' and e.id_empleado in (select empleado_id 
// 															from per_asistencias 
// 															where fecha_asistencia = '$fecha' 
// 															group by empleado_id) order by e.nombres")->fetch();

$sqlEstudiantes = "SELECT	a.*, p.*, c.* 
					FROM per_asignaciones AS a
					INNER JOIN sys_persona AS p ON p.id_persona = a.persona_id
					INNER JOIN per_cargos AS c ON c.id_cargo = a.cargo_id
					WHERE a.estado = 'A' AND a.id_asignacion IN (SELECT pa.asignacion_id  
															FROM per_asistencias AS pa
															WHERE pa.fecha_asistencia = '$fecha')
															ORDER BY p.id_persona";
$empleados = $db->query($sqlEstudiantes)->fetch();
// echo "<pre><br><br><br><br><br><br><br><br>";															
// var_dump($sqlEstudiantes);
// echo "</pre>";

// Retorna las asistencias de un empleado en una fecha
function asistencias($db, $id_asignacion, $fecha) {

	// echo "-> ". $id_empleado ." - ".$fecha;
	// echo "<hr>";
	$asistencias = $db->from('per_asistencias')->where('asignacion_id', $id_asignacion)->where('fecha_asistencia', $fecha)->order_by('entrada', 'asc')->fetch();
	// var_dump($asistencias);
	//exit();
	return $asistencias;
}

?>

<?php require_once show_template('header-design'); ?>
<!--link rel="stylesheet" href="assets/themes/concept/assets/vendor/cropper/dist/cropper.min.css"-->
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/cropper-mazter/css/cropper.css">
<link href="assets/themes/concept/assets/vendor/datepicker/css/datepicker.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/bootstrap-fileinput-master/css/fileinput.css" rel="stylesheet">
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">

<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="page-header">			
				<h2 class="pageheader-title" data-idtutor="">Asistencias Diarias</h2>
				<p class="pageheader-text"></p>
				<div class="page-breadcrumb">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">RRHH</a></li>
							<li class="breadcrumb-item active" aria-current="page">Asistencias diarias</li>
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
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
						<div class="text-label hidden-xs">Seleccione:</div>
					</div>
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
						<div class="btn-group">
							<div class="input-group">
								<div class="input-group-append be-addon">
									<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
									<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item">Seleccionar acción</a>
										<div class="dropdown-divider"></div>
										<a href="#" data-toggle="modal" data-target="#modal_cambiar" data-backdrop="static" data-keyboard="false" class="dropdown-item"><span class="glyphicon glyphicon-calendar"></span> Cambiar fecha</a>										
									</div>
								</div>
							</div>
						</div> 
					</div>
				</div>
			</div>
			<!-- ============================================================== -->
			<!-- datos --> 
			<!-- ============================================================== -->
			<div class="card-body">
				<?php if ($message = get_notification()) : ?>
				<div class="alert alert-<?= $message['type']; ?>">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong><?= $message['title']; ?></strong>
					<p><?= $message['content']; ?></p>
				</div>
				<?php endif ?>
				<?php if ($empleados) : ?>
				<div class="table-responsive">
				<table id="table" class="table table-bordered table-condensed table-striped table-hover" width="100%">
					<thead>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<th class="text-nowrap">Foto</th>
							<th class="text-nowrap">Nombres</th>
							<th class="text-nowrap">Primer Apellido</th>
							<th class="text-nowrap">Segundo Apellido</th>
							<th class="text-nowrap">Cargo</th>
							<th class="text-nowrap">Asistencia</th>
							<th class="text-nowrap">Finalizado</th>
							<th class="text-nowrap">Turno</th>
						</tr>
					</thead>
					<tfoot>
						<tr class="active">
							<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
							<th class="text-nowrap text-middle">Foto</th>
							<th class="text-nowrap text-middle">Nombres</th>
							<th class="text-nowrap text-middle">Primer Apellido</th>
							<th class="text-nowrap text-middle">Segundo Apellido</th>
							<th class="text-nowrap text-middle">Cargo</th>
							<th class="text-nowrap text-middle">Asistencia</th>
							<th class="text-nowrap text-middle">Finalizado</th>
							<th class="text-nowrap text-middle" data-datafilter-filter="false">Turno</th>							
						</tr>
					</tfoot>
					<tbody>
						<?php foreach ($empleados as $nro => $empleado) : ?>
						<?php $asistencias = asistencias($db, $empleado['id_asignacion'], $fecha) ?>
						<?php $nro_asistencias = sizeof($asistencias); ?>
						<?php //$sucursales = $db->query("SELECT nombre from sys_empleado_instituciones ei LEFT JOIN sys_instituciones i ON i.id_institucion=ei.institucion_id WHERE empleado_id=".$empleado['id_empleado'])->fetch();?>
						<tr>
							<th class="text-nowrap"><?= $nro + 1; ?></th>
							<td class="text-nowrap"><img src="<?= ($empleado['foto'] == '') ? files . '/profiles/personal/avatar.jpg' :  files . '/profiles/personal/'. $empleado['foto'].'.jpg'; ?>" width="60" height="60" class="img-circle"></td>
							<td class="text-nowrap"><?= escape($empleado['nombres']); ?></td>
							<td class="text-nowrap"><?= escape($empleado['primer_apellido']); ?></td>
							<td class="text-nowrap"><?= escape($empleado['segundo_apellido']); ?></td>
							<td class="text-nowrap"><?= escape($empleado['cargo']); ?></td>
							<td class="text-nowrap">
								<?php foreach ($asistencias as $nro => $asistencia) : ?>
								<p class="margin-none">
									<span><?= $nro + 1; ?>° Entrada</span>
									<strong class="text-primary"><?= substr($asistencia['entrada'], 11, -3); ?></strong>
									<span>/</span>
									<span><?= $nro + 1; ?>° Salida</span>
									<strong class="text-primary"><?= substr($asistencia['salida'], 11, -3); ?></strong>
								</p>
								<?php endforeach ?>
							</td>
							<td class="text-nowrap">
								<?php if ($asistencia['salida'] == '0000-00-00 00:00:00') : ?>
								<strong class="text-danger">No</strong>
								<?php else : ?>
								<strong class="text-success">Si</strong>
								<?php endif ?>
							</td>
							<td class="text-nowrap text-right"><?= escape($nro_asistencias); ?></td>
						</tr>
						<?php endforeach ?>
					</tbody>
				</table>
				</div>
				<?php else : ?>
				<div class="alert alert-info margin-none">
					<strong>Atención!</strong>
					<ul>
						<li>No se registraron asistencias de empleados en esta fecha.</li>
						<li>Verifique que la fecha se válida.</li>
					</ul>
				</div>
				<?php endif ?>
			</div>
			<!-- ============================================================== -->
			<!-- end datos -->
			<!-- ============================================================== -->
		</div>
	</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal_cambiar">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-body">
						<form method="post" action="?/rrhh-asistencia/listar" id="form_cambiar" class="modal-content loader-wrapper" autocomplete="off">
							<div class="modal-body">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Cambiar fecha</h4>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<label for="fecha_cambiar" class="control-label">Fecha:</label>
									<input type="date" value="<?= date_decode($fecha, $_institution['formato']); ?>" name="fecha" id="fecha_cambiar" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
								</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-primary">
									<span class="glyphicon glyphicon-share-alt"></span>
									<span>Cambiar</span>
								</button>
								<button type="reset" class="btn btn-default">
									<span class="glyphicon glyphicon-refresh"></span>
									<span>Restablecer</span>
								</button>
							</div>
							<div id="loader_cambiar" class="loader-wrapper-backdrop hidden">
								<span class="loader"></span>
							</div>
						</form>
						</div>						
					</div>
				</div>
</div>
<!-- Modal cambiar fin -->

<!-- <script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/bootstrap-datetimepicker.min.js"></script>
<script src="<?= js; ?>/jquery.dataTables.min.js"></script>
<script src="<?= js; ?>/dataTables.bootstrap.min.js"></script>
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<script src="<?= js; ?>/jquery.dataFilters.min.js"></script> -->

<script src="<?= themes; ?>/concept/assets/vendor/cropper-mazter/js/cropper.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/cropper-mazter/js/imagenes.js"></script>
<!--script src="<?= themes; ?>/concept/assets/vendor/cropper-mazter/js/main.js"></script-->
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.es.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-fileinput-master/js/fileinput.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-fileinput-master/js/es.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/educheck.js"></script>

<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<script src="<?= js; ?>/jquery.dataFilters.min.js"></script>

<?php require_once show_template('footer-design'); ?>

<script>
$(function () {
	var $modal_cambiar = $('#modal_cambiar'), $form_cambiar = $('#form_cambiar'), $loader_cambiar = $('#loader_cambiar'), $fecha_cambiar = $('#fecha_cambiar');
	
	$("#form_cambiar").on('submit',function(e){
		e.preventDefault();
	});

	$("#form_cambiar").validate({
					rules: {},
					errorClass: "help-inline",
					errorElement: "span",
					highlight: highlight,
					unhighlight: unhighlight,
					messages: {},
					//una ves validado guardamos los datos en la DB
					submitHandler: function(form) {
						//console.log("Hola Vic")
						var direccion_cambiar = $.trim($form_cambiar.attr('action')), fecha_cambiar = $.trim($fecha_cambiar.val());
						fecha_cambiar = fecha_cambiar.replace(new RegExp('/', 'g'), '-');
						window.location = direccion_cambiar + '/' + fecha_cambiar;
					}
				});

	<?php if (true) : ?>
    /*	$('#table').on('search.dt order.dt page.dt length.dt', function () {
		$('[data-grupo-seleccionar]').prop('checked', false).trigger('change');
	}).DataFilter({
		name: 'usuarios',
		reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
	});*/
		var dataTable = $('#table').DataTable({
	language: dataTableTraduccion,
	searching: true,
	paging:true,
	"lengthChange": true, 
	"responsive": true
	});
	<?php endif ?>
});
</script>
