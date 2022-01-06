<?php

// Obtiene los parametros
$id_dosificacion = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la dosificacion
$dosificacion = $db->select('d.*')->from('inv_dosificaciones d')->where('d.id_dosificacion', $id_dosificacion)->fetch_first();

// Ejecuta un error 404 si no existe la dosificacion
if (!$dosificacion) { require_once not_found(); exit; }

// Obtiene los permisos
$permiso_listar     = in_array('listar', $_views);
$permiso_crear      = in_array('crear', $_views);
$permiso_modificar  = in_array('modificar', $_views);
$permiso_eliminar   = in_array('eliminar', $_views);
$permiso_imprimir   = in_array('imprimir', $_views);
$permiso_activar    = in_array('activar', $_views);
$permiso_desactivar = in_array('desactivar', $_views);

// Obtiene la vigencia
$vigencia = (now() > $dosificacion['fecha_limite']) ? 0 : intval(date_diff(date_create(now()), date_create($dosificacion['fecha_limite']))->format('%a')) + 1;

?>
<?php require_once show_template('header-design'); ?>
<div class="panel-heading">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Ver dosificación</strong>
	</h3>
</div>
<div class="panel-body">
	<?php if ($permiso_listar || $permiso_crear || $permiso_modificar || $permiso_eliminar || $permiso_imprimir || $permiso_activar || $permiso_desactivar) : ?>
	<div class="row">
		<div class="col-xs-6">
			<div class="text-label hidden-xs">Seleccionar acción:</div>
			<div class="text-label visible-xs-block">Acciones:</div>
		</div>
		<div class="col-xs-6 text-right">
			<div class="btn-group">
				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
					<span class="glyphicon glyphicon-menu-hamburger"></span>
					<span class="hidden-xs">Acciones</span>
				</button>
				<ul class="dropdown-menu dropdown-menu-right">
					<li class="dropdown-header visible-xs-block">Seleccionar acción</li>
					<?php if ($permiso_listar) : ?>
					<li><a href="?/dosificaciones/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar dosificaciones</a></li>
					<?php endif ?>
					<?php if ($permiso_crear) : ?>
					<li><a href="?/dosificaciones/crear"><span class="glyphicon glyphicon-plus"></span> Crear dosificación</a></li>
					<?php endif ?>
					<?php if ($permiso_modificar) : ?>
					<li><a href="?/dosificaciones/modificar/<?= $id_dosificacion; ?>"><span class="glyphicon glyphicon-edit"></span> Modificar dosificación</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<li><a href="?/dosificaciones/eliminar/<?= $id_dosificacion; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar dosificación</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/dosificaciones/imprimir/<?= $id_dosificacion; ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir dosificación</a></li>
					<?php endif ?>
					<?php if ($permiso_activar) : ?>
					<li><a href="?/dosificaciones/activar/<?= $id_dosificacion; ?>" data-activar="true"><span class="glyphicon glyphicon-ok-circle"></span> Activar dosificación</a></li>
					<?php endif ?>
					<?php if ($permiso_desactivar) : ?>
					<li><a href="?/dosificaciones/desactivar/<?= $id_dosificacion; ?>" data-desactivar="true"><span class="glyphicon glyphicon-remove-circle"></span> Desactivar dosificación</a></li>
					<?php endif ?>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	<?php endif ?>
	<?php if (isset($_SESSION[temporary])) { ?>
	<div class="alert alert-<?= $_SESSION[temporary]['alert']; ?>">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong><?= $_SESSION[temporary]['title']; ?></strong>
		<p><?= $_SESSION[temporary]['message']; ?></p>
	</div>
	<?php unset($_SESSION[temporary]); ?>
	<?php } ?>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<div class="margin-bottom">
				<div class="form-group">
					<label class="control-label">Fecha y hora de dosificación:</label>
					<p class="form-control-static"><?= date_decode($dosificacion['fecha_registro'], $_format) . ' ' . escape($dosificacion['hora_registro']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Número de trámite:</label>
					<p class="form-control-static"><?= escape($dosificacion['nro_tramite']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Número de autorización:</label>
					<p class="form-control-static"><?= escape($dosificacion['nro_autorizacion']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Llave de dosificación:</label>
					<p class="form-control-static"><code><?= base64_decode($dosificacion['llave_dosificacion']); ?></code></p>
				</div>
				<div class="form-group">
					<label class="control-label">Fecha límite de emisión:</label>
					<p class="form-control-static"><?= date_decode($dosificacion['fecha_limite'], $_format); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Leyenda de la factura:</label>
					<p class="form-control-static"><?= escape($dosificacion['leyenda']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Observación:</label>
					<p class="form-control-static"><?= ($dosificacion['observacion'] != '') ? escape($dosificacion['observacion']) : 'No asignado'; ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Estado:</label>
					<p class="form-control-static"><?= ($vigencia == 0) ? 'Sin vigencia' : 'En uso'; ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Días restantes:</label>
					<p class="form-control-static"><?= $vigencia; ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Facturas emitidas:</label>
					<p class="form-control-static"><?= escape($dosificacion['nro_facturas']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Activo:</label>
					<p class="form-control-static"><?= ($dosificacion['activo'] == 's') ? 'Si' : 'No'; ?></p>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(function () {
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
		var csrf = '';
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
			if (result) {
				$.request(href, csrf);
			}
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
});
</script>
<?php require_once show_template('footer-design'); ?>