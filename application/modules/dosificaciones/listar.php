<?php
// Obtiene la cadena csrf
$csrf = set_csrf();
// Obtiene las dosificaciones
$dosificaciones = $db->select('d.*')->from('inv_dosificaciones d')->order_by('d.fecha_registro desc, d.hora_registro desc')->fetch();

// Obtiene los permisos
$permiso_crear      = in_array('crear', $_views);
$permiso_ver        = in_array('ver', $_views);
$permiso_modificar  = in_array('modificar', $_views);
$permiso_eliminar   = in_array('eliminar', $_views);
$permiso_imprimir   = in_array('imprimir', $_views);
$permiso_activar    = in_array('activar', $_views);
$permiso_desactivar = in_array('desactivar', $_views);

$dosificacion = $db->from('inv_dosificaciones')->where('fecha_registro <=', now())->where('fecha_limite >=', now())->where('activo', 's')->fetch_first();
?>
<?php require_once show_template('header-design'); ?>
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"> 
        <div class="page-header">
            <h2 class="pageheader-title">Dosificaciones</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Inicio de Gestión</a></li>
						<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión Escolar</a></li>
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

<!-- ============================================================== -->
<!-- row -->
<!-- ============================================================== -->
<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="card">
			<div class="card-header">
				<?php if ($permiso_crear || $permiso_imprimir) : ?>
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
										<a href="?/dosificaciones/crear"><span class="glyphicon glyphicon-plus"></span> Crear dosificación</a>
										<?php endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/dosificaciones/imprimir" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir dosificaciones</a>
										<?php endif ?>
									</div>
								</div>
							</div>
						</div> 
					</div>
				</div>
				<?php endif ?>
			</div>
			<!-- ============================================================== -->
			<!-- datos --> 
			<!-- ============================================================== -->
			<div class="card-body">
			    <?php if ($dosificaciones) : ?>
		        <div class="table-responsive">
					<table id="table" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
						<thead>
							<tr class="active">
								<th class="text-nowrap">#</th>
								<th class="text-nowrap" data-datafilter-content="html">Fecha de dosificación</th>
								<th class="text-nowrap">Número de trámite</th>
								<th class="text-nowrap">Número de autorización</th>
								<th class="text-nowrap">Llave de dosificación</th>
								<th class="text-nowrap">Fecha límite de emisión</th>
								<th class="text-nowrap" data-datafilter-width="*">Leyenda de la factura</th>
								<th class="text-nowrap" data-datafilter-width="*">Observación</th>
								<th class="text-nowrap">Estado</th>
								<th class="text-nowrap">Días restantes</th>
								<th class="text-nowrap">Facturas</th>
								<th class="text-nowrap">Activo</th>
								<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar || $permiso_activar || $permiso_desactivar) : ?>
								<th class="text-nowrap">Opciones</th>
								<?php endif ?>
							</tr>
						</thead>
						<tfoot>
							<tr class="active">
								<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
								<th class="text-nowrap text-middle">Fecha de dosificación</th>
								<th class="text-nowrap text-middle" data-datafilter-visible="false">Número de trámite</th>
								<th class="text-nowrap text-middle">Número de autorización</th>
								<th class="text-nowrap text-middle" data-datafilter-visible="false">Llave de dosificación</th>
								<th class="text-nowrap text-middle">Fecha límite de emisión</th>
								<th class="text-nowrap text-middle" data-datafilter-visible="false">Leyenda de la factura</th>
								<th class="text-nowrap text-middle" data-datafilter-visible="false">Observación</th>
								<th class="text-nowrap text-middle">Estado</th>
								<th class="text-nowrap text-middle">Días restantes</th>
								<th class="text-nowrap text-middle">Facturas</th>
								<th class="text-nowrap text-middle">Activo</th>
								<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar || $permiso_activar || $permiso_desactivar) : ?>
								<th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
								<?php endif ?>
							</tr>
						</tfoot>
						<tbody>
							<?php foreach ($dosificaciones as $nro => $dosificacion) : ?>
							<?php $vigencia = (now() > $dosificacion['fecha_limite']) ? 0 : intval(date_diff(date_create(now()), date_create($dosificacion['fecha_limite']))->format('%a')) + 1; ?>
							<tr>
								<th class="text-nowrap"><?= $nro + 1; ?></th>
								<td class="text-nowrap">
									<span><?= date_decode($dosificacion['fecha_registro'], $_format); ?></span><br>
									<span class="text-primary"><?= escape($dosificacion['hora_registro']); ?></span>
								</td>
								<td class="text-nowrap"><?= escape($dosificacion['nro_tramite']); ?></td>
								<td class="text-nowrap"><?= escape($dosificacion['nro_autorizacion']); ?></td>
								<td class="text-nowrap"><code><?= base64_decode($dosificacion['llave_dosificacion']); ?></code></td>
								<td class="text-nowrap"><?= date_decode($dosificacion['fecha_limite'], $_format); ?></td>
								<td class="width-md"><?= escape($dosificacion['leyenda']); ?></td>
								<td class="width-md"><?= escape($dosificacion['observacion']); ?></td>
								<?php if ($vigencia == 0) : ?>
								<td class="text-nowrap danger"><strong class="text-danger">Sin vigencia</strong></td>
								<?php else : ?>
								<td class="text-nowrap success"><strong class="text-success">En uso</strong></td>
								<?php endif ?>
								<td class="text-nowrap text-right"><?= $vigencia; ?></td>
								<td class="text-nowrap text-right"><?= escape($dosificacion['nro_facturas']); ?></td>
								<?php if ($dosificacion['activo'] == 'S') : ?>
								<td class="text-nowrap success"><strong class="text-success">Si</strong></td>
								<?php else : ?>
								<td class="text-nowrap danger"><strong class="text-danger">No</strong></td>
								<?php endif ?>
								<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar || $permiso_activar || $permiso_desactivar) : ?>
								<td class="text-nowrap">
									<?php if ($permiso_ver) : ?>
									<a href="?/dosificaciones/ver/<?= $dosificacion['id_dosificacion']; ?>" data-toggle="tooltip" data-title="Ver dosificación" class="btn btn-info btn-xs"><span class='icon-eye'></span></a>
									<?php endif ?>
									<?php if ($permiso_modificar) : ?>
									<a href="?/dosificaciones/modificar/<?= $dosificacion['id_dosificacion']; ?>" data-toggle="tooltip" data-title="Modificar dosificación" class="btn btn-warning btn-xs"><span class='icon-note'></span></a>
									<?php endif ?>
									<?php if ($permiso_eliminar) : ?>
									<a href="?/dosificaciones/eliminar/<?= $dosificacion['id_dosificacion']; ?>" data-toggle="tooltip" data-title="Eliminar dosificación" data-eliminar="true" class="btn btn-danger btn-xs"><span class="icon-trash"></span></a>
									<?php endif ?>
									<?php if ($permiso_activar) : ?>
									<a href="?/dosificaciones/activar/<?= $dosificacion['id_dosificacion']; ?>" data-toggle="tooltip" data-title="Activar dosificación" data-activar="true" class="btn btn-success btn-xs"><span class="icon-check"></span></a>
									<?php endif ?>
								</td>
								<?php endif ?>
							</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
				<?php else : ?>
				<div class="alert alert-info">
					<strong>Atención!</strong>
					<ul>
						<li>No existen dosificaciones registradas en la base de datos.</li>
						<li>Para crear nuevas dosificaciones debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
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
<!-- ============================================================== -->
<!-- row -->
<!-- ============================================================== -->
<script src="<?= js; ?>/jquery.dataTables.min.js"></script>
<script src="<?= js; ?>/dataTables.bootstrap.min.js"></script>
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<script src="<?= js; ?>/jquery.dataFilters.min.js"></script>

<script src="<?= js; ?>/vfs_fonts.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/educheck.js"></script>
<?php require_once show_template('footer-design'); ?>
<script>
$(function () {

	<?php if ($dosificaciones) : ?>
		var dataTable = $('#table').DataTable({
		language: dataTableTraduccion,
		searching: true,
		paging:true,
		"lengthChange": true, 
		"responsive": true
		});
	<?php endif ?>

	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/dosificaciones/crear';
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
		bootbox.confirm('¿Está seguro que desea eliminar la dosificación?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>

	<?php if ($permiso_activar) : ?>
	$('[data-activar]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var csrf = '';
		bootbox.confirm('¿Está seguro que desea activar la dosificación?', function (result) {
		    window.open(href, true);
// 			if (result) {
// 				$.request(href, csrf);
// 			}
		});
	});
	<?php endif ?>

	<?php if ($permiso_desactivar) : ?>
	$('[data-desactivar]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var csrf = '';
		bootbox.confirm('¿Está seguro que desea desactivar la dosificación?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
	
	<?php if ($dosificaciones) : ?>
	// $('#table').DataFilter({
	// 	filter: true,
	// 	name: 'dosificaciones',
	// 	reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>',
	// 	size: 8,
	// 	values: {
	// 		stateSave: true
	// 	}
	// });
	<?php endif ?>
});
</script>
