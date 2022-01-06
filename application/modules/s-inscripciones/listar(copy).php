<?php

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene el id de la gestion actual
$id_gestion = $_gestion['id_gestion'];

//Exclusivo de maranata listado de estudiante
$est_antiguos = $db->query("SELECT * FROM ins_datos_estudiante WHERE estado ='A'")->fetch();


// Obtiene los estudiantes
$estudiantes = $db->select('z.*')->from('vista_estudiantes z')->order_by('z.id_estudiante', 'asc')->fetch();

//Nro de Inscritos
$nroInscritos = $db->query("SELECT COUNT(estado_inscripcion) as inscritos FROM ins_inscripcion WHERE estado_inscripcion='INSCRITO'")->fetch();

//Nro de Reservas
$nroReservas = $db->query("SELECT COUNT(estado_inscripcion) as reservas FROM ins_inscripcion WHERE estado_inscripcion='RESERVA'")->fetch();

//Nro de inscritos hoy
$nroInsHoy = $db->query("SELECT COUNT(fecha_inscripcion)AS inscritos_hoy FROM ins_inscripcion WHERE DATE(fecha_inscripcion)=CURDATE()")->fetch();

//Nro de Varones y Mujeres
$nroVM = $db->query("SELECT IFNULL(SUM(p.genero= 'v'),0) AS nro_varones, IFNULL(SUM(p.genero= 'm'),0) AS nro_mujeres,  COUNT(i.id_inscripcion) AS inscritos, IFNULL(ap.capacidad,0) AS cupo_total
FROM ins_inscripcion AS i
INNER JOIN ins_estudiante e ON e.id_estudiante = i.estudiante_id
INNER JOIN sys_persona p ON p.id_persona = e.persona_id
INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo = i.aula_paralelo_id
WHERE i.gestion_id = $id_gestion AND i.estado = 'A'")->fetch();

//var_dump($estudiantes);exit();
// Obtiene los permisos  
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_editar = in_array('crear', $_views);
$permiso_editar_curso = in_array('editar_inscripcion', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);
$permiso_inscripcion = in_array('inscripcion-estudiante-tutor', $_views);
$permiso_pago = in_array('asignar-pago', $_views);

?>
<?php require_once show_template('header-design'); ?>
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="page-header">
			<h2 class="pageheader-title">Inscripción</h2>
			<p class="pageheader-text"></p>
			<div class="page-breadcrumb">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Secretaria</a></li>
						<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Inscripción</a></li>
						<li class="breadcrumb-item active" aria-current="page">Listar</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>

<div class="">
	<div class="dashboard-influence">
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
			<!-- ============================================================== -->
			<!-- widgets   -->
			<!-- ============================================================== -->
			<div class="row">
				<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
					<div class="card">
						<div class="card-body">
							<div class="d-inline-block">
								<h5 class="text-muted">Nro de Inscritos</h5>
								<h2 class="mb-0"> <?= $nroInscritos[0]['inscritos'] ?></h2>
							</div>
							<div class="float-right icon-circle-medium  icon-box-lg  bg-info-light mt-1">
								<i class="fas fa-clipboard-check fa-fw fa-sm text-info"></i>
							</div>
						</div>
					</div>
				</div>
				<!-- ============================================================== -->
				<!-- end total views   -->
				<!-- ============================================================== -->
				<!-- ============================================================== -->
				<!-- total followers   -->
				<!-- ============================================================== -->
				<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
					<div class="card">
						<div class="card-body">
							<div class="d-inline-block">
								<h5 class="text-muted">Nro de Reservas</h5>
								<h2 class="mb-0"> <?= $nroReservas[0]['reservas'] ?></h2>
							</div>
							<div class="float-right icon-circle-medium  icon-box-lg  bg-primary-light mt-1">
								<i class="fa fa-user fa-fw fa-sm text-primary"></i>
							</div>
						</div>
					</div>
				</div>
				<!-- ============================================================== -->
				<!-- end total followers   -->
				<!-- ============================================================== -->
				<!-- ============================================================== -->
				<!-- partnerships   -->
				<!-- ============================================================== -->
				<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
					<div class="card">
						<div class="card-body">
							<div class="d-inline-block">
								<h5 class="text-muted">Nro de Incritos Hoy</h5>
								<h2 class="mb-0"><?= $nroInsHoy[0]['inscritos_hoy'] ?></h2>
							</div>
							<div class="float-right icon-circle-medium  icon-box-lg  bg-secondary-light mt-1">
								<i class="fa fa-handshake fa-fw fa-sm text-secondary"></i>
							</div>
						</div>
					</div>
				</div>
				<!-- ============================================================== -->
				<!-- end partnerships   -->
				<!-- ============================================================== -->
				<!-- ============================================================== -->
				<!-- total earned   -->
				<!-- ============================================================== -->
				<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
					<div class="card">
						<div class="card-body">
							<div class="d-inline-block">
								<h5 class="text-muted">Nro de Varones <?= $nroVM[0]['nro_varones'] ?></h5>
								<h5 class="text-muted">Nro de Señoritas <?= $nroVM[0]['nro_mujeres'] ?></h5>
							</div>
							<div class="float-right icon-circle-medium  icon-box-lg  bg-brand-light mt-1">
								<i class="fa fa-money-bill-alt fa-fw fa-sm text-brand"></i>
							</div>
						</div>
					</div>
				</div>
				<!-- ============================================================== -->
				<!-- end total earned   -->
				<!-- ============================================================== -->
			</div>
		</div>
	</div>
</div>

<?php if ($permiso_crear) : ?>
<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="" style="margin-top:2%">
			<label class="control-label">Buscar estudiante: </label>
			<div class="controls control-group">
				<select name="est_antiguos" id="est_antiguos" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
					<option value="" selected="selected">Seleccionar</option>
					<?php foreach ($est_antiguos as $antiguo) : ?>
						<option value="<?= $antiguo['id_datos_estudiante']; ?>"><?= escape($antiguo['nombre_completo']); ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
	</div>
</div>

</br>
<div id="boton_inscripcion">
	<!-- <a href='?/s-inscripciones/crear/" + id_estudiante + "' class='btn btn-xs btn-warning' style='color:white'>Editar</a> -->
</div>
<div id="boton_pagado">
	<!-- <a href='?/s-inscripciones/crear/" + id_estudiante + "' class='btn btn-xs btn-warning' style='color:white'>Editar</a> -->
</div>
</br>
<?php endif ?>


<!-- ============================================================== -->
<!-- end pageheader -->
<!-- ============================================================== -->
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
								<div class="input-group-append be-addon">
									<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
									<div class="dropdown-menu">
										<a class="dropdown-item">Seleccionar acción</a>
										<?php if ($permiso_crear) : ?>
											<div class="dropdown-divider"></div>
											<a href="?/s-inscripciones/crear" class="dropdown-item">Registrar Estudiante</a>
										<?php endif ?>
										<?php if ($permiso_imprimir) : ?>
											<div class="dropdown-divider"></div>
											<a href="?/s-nivel-academico/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
										<?php endif ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="card-body">
				<!-- ============================================================== -->
				<!-- datos -->
				<!-- ============================================================== -->
				<?php if ($estudiantes) : ?>
					<div class="table-responsive">
						<table id="table" class="table table-bordered table-condensed table-striped table-hover">
							<thead>
								<tr class="active">
									<th class="text-nowrap">#</th>
									<th class="text-nowrap">Foto</th>
									<th class="text-nowrap">Código</th>
									<th class="text-nowrap">Apellidos y Nombres</th>
									<th class="text-nowrap">C.I.</th>
									<th class="text-nowrap">Curso</th>
									<th class="text-nowrap">Tipo Estudiante</th>
									<th class="text-nowrap">Género</th>
									<th class="text-nowrap">Tutor</th>
									<th class="text-nowrap">Usuario Registro</th>
									<!-- <th class="text-nowrap">Contacto</th> -->
									<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
										<th class="text-nowrap">Opciones</th>
									<?php endif ?>
								</tr>
							</thead>
							<tfoot>
								<tr class="active">
									<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
									<th class="text-nowrap text-middle">Foto</th>
									<th class="text-nowrap text-middle">Código</th>
									<th class="text-nowrap text-middle">Apellidos y Nombres</th>
									<th class="text-nowrap text-middle">C.I.</th>
									<th class="text-nowrap text-middle">Curso</th>
									<th class="text-nowrap text-middle">Tipo Estudiante</th>
									<th class="text-nowrap text-middle">Género</th>
									<th class="text-nowrap text-middle">Tutor</th>
									<th class="text-nowrap">Usuario Registro</th>
									<!-- <th class="text-nowrap text-middle">Contacto</th> -->
									<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
										<th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
									<?php endif ?>
								</tr>
							</tfoot>
							<tbody>
							</tbody>
						</table>
					</div>
				<?php else : ?>
					<div class="alert alert-info">
						<strong>Atención!</strong>
						<ul>
							<li>No existen inscripción registrados en la base de datos.</li>
							<li>Para crear nuevos inscripción debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
						</ul>
					</div>
				<?php endif ?>
				<!-- ============================================================== -->
				<!-- end datos -->
				<!-- ============================================================== -->
			</div>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- end row -->
<!-- ============================================================== -->
<!--modal para eliminar-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_eliminar">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<input type="hidden" id="id_estudiante">
				<p>¿Esta seguro de eliminar estudiante <span id="texto_estudiante"></span>?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary" id="btn_eliminar">Eliminar</button>
			</div>
		</div>
	</div>
</div>

<!--modal para confirmar pago-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_confirmar">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<input type="hidden" id="id_estudiante">
				<p>¿Esta seguro de habilitar al estudiante <span id="texto_estudiante"></span>?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary" id="btn_habilitar">Habilitar</button>
			</div>
		</div>
	</div>
</div>

<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<!-- <script src="<?= js; ?>/jquery.dataFilters.min.js"></script> -->
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/educheck.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<!--script src="<?= $ruta ?>/s-gestion-escolar.js"></script-->
<?php require_once show_template('footer-design'); ?>
<?php
if ($permiso_editar) {
	require_once("editar.php");
}
if ($permiso_ver) {
	//require_once ("ver.php");
}
?>
<script>
	$(function() {

		$("#boton_inscripcion").hide();
		<?php if ($permiso_crear) : ?>
			$(window).bind('keydown', function(e) {
				if (e.altKey || e.metaKey) {
					switch (String.fromCharCode(e.which).toLowerCase()) {
						case 'n':
							e.preventDefault();
							window.location = '?/gestiones/crear';
							break;
					}
				}
			});
		<?php endif ?>

		<?php if ($permiso_eliminar) : ?>
			$('[data-eliminar]').on('click', function(e) {
				e.preventDefault();
				var href = $(this).attr('href');
				var csrf = '<?= $csrf; ?>';
				bootbox.confirm('¿Está seguro que desea eliminar el gestion?', function(result) {
					if (result) {
						$.request(href, csrf);
					}
				});
			});
		<?php endif ?>

		<?php if ($estudiantes) : ?>
			// $('#nivel_academico').DataFilter({
			// 	filter: true,
			// 	name: 'niveles',
			// 	reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
			// });
		<?php endif ?>
		//carga toda la lista de grupo proyecto con DataTable


		$('#est_antiguos').selectize({
			persist: true,
			createOnBlur: true,
			create: true,
			onInitialize: function() {
				$('#est_antiguos').css({
					display: 'block',
					left: '-10000px',
					opacity: '0',
					position: 'absolute',
					top: '-10000px'
				});
			},
			onChange: function() {
				$('#est_antiguos').trigger('blur');
			},
			onBlur: function() {
				$('#est_antiguos').trigger('blur');
			}
		}).on('change', function(e) {
			var codigo = $(this).val();
			valor = 'codigo=' + codigo + '&boton=' + 'nro_cuotas';
			console.log(valor);
			$.ajax({
				type: 'POST',
				url: "?/s-inscripciones/procesos",
				data: valor,
				success: function(resp) {
					$("#boton_inscripcion").hide();
					if ((resp * 1) > 0) {
						var boton = "<h5> Tiene " + resp + " Deudas pendientes, su inscripcion no se podra realizar... Lo sentimos</h5>";
						boton += "<a href='#' class='btn btn-xs btn-primary' style='color:white' onclick='mensualidad_pagadas(" + '"' + codigo + '"' + ")'>Saldo Pagado</a>";
						$("#boton_inscripcion").show();
						$("#boton_inscripcion").html(boton);
						alertify.success('Tiene deudas pendientes');
					} else {
						var boton = "<a href='?/s-inscripciones/crear/0/" + codigo + "' class='btn btn-xs btn-primary' style='color:white'>Inscribir</a> <a href='#' class='btn btn-danger btn-xs' onclick='abrir_eliminar_ant(" + '"' + codigo + '"' + ")'><span class='icon-trash'></span></a>";
						$("#boton_inscripcion").show();
						$("#boton_inscripcion").html(boton);
					}
				}
			});
		});

	});

	<?php if ($permiso_editar) : ?>

		function abrir_editar(contenido) {
			$("#form_estudiante")[0].reset();
			$("#modal_estudiante").modal("show");
			var d = contenido.split("*");
			$("#id_estudiante").val(d[0]);
			$("#nombre_estudiante").val(d[1]);
			$("#primer_apellido").val(d[2]);
			$("#segundo_apellido").val(d[3]);
			$("#tipo_documento").val(d[4]);
			$("#numero_documento").val(d[5]);
			$("#complemento").val(d[6]);
			$("#genero").val(d[7]);
			$("#fecha_nacimiento").val(d[8]);
			$("#btn_nuevo").hide();
			$("#btn_editar").show();
		}
	<?php endif ?>

	<?php if ($permiso_crear) : ?>

		function abrir_crear() {
			$("#modal_estudiante").modal("show");
			$("#form_estudiante")[0].reset();
			$("#btn_editar").hide();
			$("#btn_nuevo").show();
		}
	<?php endif ?>

	var columns = [{
			data: 'id_estudiante'
		},
		{
			data: 'foto'
		},
		{
			data: 'codigo_estudiante'
		},
		{
			data: 'nombre_completo'
		},
		{
			data: 'numero_documento'
		},
		{
			data: 'curso'
		},
		{
			data: 'nombre_tipo_estudiante'
		},
		{
			data: 'genero'
		},
		{
			data: 'nombres_familiar'
		},
		{
			data: 'username'
		}
		//{data: 'contacto'}
	];
	var cont = 0;
	//function listarr(){
	var dataTable = $('#table').DataTable({
		language: dataTableTraduccion,
		searching: true,
		paging: true,
		"lengthChange": true,
		"responsive": true,
		ajax: {
			url: '?/s-inscripciones/busqueda',
			dataSrc: '',
			type: 'POST',
			dataType: 'json'
		},
		columns: columns,

		"columnDefs": [{
				"render": function(data, type, row) {
					var result = "";
					//var url = "?/s-inscripciones/ver/" + row['id_estudiante'];
					var contenido = row['estado_inscripcion'] + "*" + row['id_estudiante'] + "*" + row['foto'] + "*" + row['codigo_estudiante'] + "*" + row['primer_apellido'] + "*" + row['segundo_apellido'] + "*" + row['nombres'] + "*" + row['numero_documento'] + "*" + row['genero'] + "*" + row['fecha_nacimiento'];
					var id_estudiante = row['id_estudiante'];
					console.log(id_estudiante);
					/*result+="<?php if ($permiso_ver) : ?><a href='?/s-inscripciones/ver/"+ id_estudiante +"' class='btn btn-xs btn-info'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
							"<?php if ($permiso_editar) : ?><a href='?/s-inscripciones/crear/"+ id_estudiante +"' class='btn btn-xs btn-warning' style='color:white'><span class='icon-note'></span></a><?php endif ?> &nbsp"+
							"<?php if ($permiso_inscripcion) : ?><a href='?/s-inscripciones/inscripcion-estudiante-tutor/"+id_estudiante+"' class='btn btn-xs btn-primary'><span class='icon-login'></span></a><?php endif ?> &nbsp";*/

					// 						result += "<?php if ($permiso_ver) : ?><a href='?/s-inscripciones/ver/" + id_estudiante + "' class='btn btn-xs btn-info'><span class='icon-eye'></span></a><?php endif ?> &nbsp" +
					// "<?php if ($permiso_editar) : ?><a href='?/s-inscripciones/crear/" + id_estudiante + "' class='btn btn-xs btn-warning' style='color:white'>Editar</a><?php endif ?> &nbsp" +
					// "<?php if ($permiso_editar) : ?><a href='?/s-inscripciones/editar-inscripcion/" + id_estudiante + "' class='btn btn-xs btn-primary' style='color:white'>Editar Curso</a><?php endif ?> &nbsp" +
					// 		"<?php if ($permiso_pago) : ?><a href='?/s-inscripciones/asignar-pago/"+ id_estudiante +"' class='btn btn-xs btn-success' style='color:white'>Pago</a><?php endif ?> &nbsp"+	
					// "<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' onclick='abrir_eliminar(" + '"' + contenido + '"' + ")'><span class='icon-trash'></span></a><?php endif ?>";

					result += "<?php if ($permiso_ver) : ?><a href='?/s-inscripciones/ver/" + id_estudiante + "' class='btn btn-xs btn-info'><span class='icon-eye'></span></a><?php endif ?> &nbsp" +
						"<?php if ($permiso_editar) : ?><a href='?/s-inscripciones/crear/" + id_estudiante + "' class='btn btn-xs btn-warning' style='color:white'>Editar</a><?php endif ?> &nbsp" +
						"<?php if ($permiso_editar_curso) : ?><a href='#' disabled class='btn btn-xs btn-primary' style='color:white'>Editar Curso</a><?php endif ?> &nbsp" +
						"<?php if ($permiso_pago) : ?><a href='?/s-inscripciones/asignar-pago/" + id_estudiante + "' class='btn btn-xs btn-success' style='color:white'>Pago</a><?php endif ?> &nbsp" +
						"<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' onclick='abrir_eliminar(" + '"' + contenido + '"' + ")'><span class='icon-trash'></span></a><?php endif ?>";
					return result;
				},
				"targets": 10
			},
			{
				"render": function(data, type, row) {
					switch (row['genero']) {
						case 'v':
							return "Varón";
							break;
						case 'm':
							return "Mujer";
							break;
					}
				},
				"targets": 7
			},
			{
				"render": function(data, type, row) {
					var imagen = "";
					//var foto = "imgs . '/avatar.jpg'";
					if (row['foto'] == "") {
						foto = "assets/imgs/avatar.jpg";
					} else {
						foto = "files/profiles/estudiantes/" + row['foto'] + ".jpg";
					}
					imagen += "<img src='" + foto + "' class='img-rounded cursor-pointer' data-toggle='modal' data-target='#modal_mostrar' data-modal-size='modal-md' data-modal-title='Imagen' width='64' height='64'>";
					return imagen;
				},
				"targets": 1
			},
			{
				"render": function(data, type, row) {
					cont = cont + 1;
					return cont;
				},
				"targets": 0
			}
			// {
			// 	"render": function(data, type, row) {
			// 		var dato = "";
			// 		if (row['estado_inscripcion'] == "RESERVA") {
			// 			dato += "<h5> <span class='badge'>RESERVA</span> " + row['nombre_completo'] + "</h5>";
			// 		} else {
			// 			dato += "<h5>"+ row['nombre_completo'] +"</h5>";
			// 		}		
			// 		return dato;
			// 	},
			// 	"targets": 3
			// }
		]
	});
	//} 


	//Datos de los campos de la tabla ins_estudiante_antiguo
	var columns_ = [{
			data: 'codigo'
		},
		{
			data: 'nombre_completo'
		},
		{
			data: 'fecha_nacimiento'
		},
		{
			data: 'tutor'
		},
		{
			data: 'cuotas_pendiente'
		},
		{
			data: 'curso'
		},
		//{data: 'contacto'}
	];
	var cont = 0;
	//function listarr(){
	var dataTable_ = $('#table_ant').DataTable({
		language: dataTableTraduccion,
		searching: true,
		paging: true,
		"lengthChange": true,
		"responsive": true,
		ajax: {
			url: '?/s-inscripciones/busqueda-antiguos',
			dataSrc: '',
			type: 'POST',
			dataType: 'json'
		},
		columns: columns_,
		"columnDefs": [{
			"render": function(data, type, row) {
				cont = cont + 1;
				return cont;
			},
			"targets": 0
		}]
	});


	<?php if ($permiso_ver) : ?>

		function ver() {
			$('#table tbody').on('click', 'tr', function() {
				var data = dataTable.row(this).data();
				//alert( 'You clicked on '+data[0]+'\'s row' );
				$("#estudiante_ver").modal("show");
				$("#nombre_estudiante").text(data['nombres']);
				$("#tipo_documento").text(data['tipo_documento']);
				$("#numero_documento").text(data['numero_documento']);
				$("#complemento").text(data['complemento']);
				$("#genero").text(data['genero']);
				$("#fecha_nacimiento").text(data['fecha_nacimiento']);
			});
		}
	<?php endif ?>



	<?php if (true) : ?>

		function abrir_eliminar(contenido) {
			// $("#modal_eliminar").modal("show");
			// var d = contenido.split("*");
			// $("#id_estudiante").val(d[0]);
			// $("#texto_estudiante").text(d[5]);
		}

	<?php endif ?>

	<?php if (true) : ?>

	function abrir_eliminar_ant(contenido) {
		$("#modal_eliminar").modal("show");
		//var d = contenido.split("*");
		$("#id_estudiante").val(contenido);
		$("#texto_estudiante").text(d[5]);
	}

	<?php endif ?>	

	<?php if (true) : ?>

	function mensualidad_pagadas(contenido) {
		$("#modal_confirmar").modal("show");
		//var d = contenido.split("*");
		$("#id_estudiante").val(contenido);
		$("#texto_estudiante").text(d[5]);
	}

	<?php endif ?>
	

	$("#btn_eliminar").on('click', function() {
		//alert($("#id_estudiante").val())
		id_estudiante = $("#id_estudiante").val();
		$.ajax({
			url: '?/s-inscripciones/eliminar-e',
			type: 'POST',
			data: {
				'id_estudiante': id_estudiante
			},
			success: function(resp) {
				//alert(resp)
				switch (resp) {
					case '1':
						$("#modal_eliminar").modal("hide");
						//dataTable.ajax.reload();
						refrescarPagina()
						alertify.success('Se dio de baja el estudiante correctamente');
						break;
					case '2':
						$("#modal_eliminar").modal("hide");
						alertify.error('No se pudo eliminar ');
						break;
				}
			}
		})
	})

	$("#btn_habilitar").on('click', function() {
		//alert($("#id_estudiante").val())
		id_estudiante = $("#id_estudiante").val();
		$.ajax({
			url: '?/s-inscripciones/habilitar-e',
			type: 'POST',
			data: {
				'id_estudiante': id_estudiante
			},
			success: function(resp) {
				//alert(resp)		

					if(resp > 0){
						$("#modal_confirmar").modal("hide");
						//dataTable.ajax.reload();
						//refrescarPagina()
						alertify.success('Se habilito al estudiante correctamente');
						var boton = "<a href='?/s-inscripciones/crear/0/" + resp + "' class='btn btn-xs btn-primary' style='color:white'>Inscribir</a>";
						$("#boton_inscripcion").show();
						$("#boton_inscripcion").html(boton);	
					}else{
						$("#modal_confirmar").modal("hide");
						alertify.error('No se pudo eliminar ');
					}					
				
			}
		})
	})

	// var $est_antiguos = $('#est_antiguos');
	// 			$est_antiguos.selectize({
	// 				persist: false,
	// 				createOnBlur: true,
	// 				create: true,
	// 				onInitialize: function() {
	// 					$f_profesion.css({
	// 						display: 'block',
	// 						left: '-10000px',
	// 						opacity: '0',
	// 						position: 'absolute',
	// 						top: '-10000px'
	// 					});
	// 				}
	// 			});

	function refrescarPagina() {
		location.reload();
	}
</script>