<?php

// Obtiene los parametros
$id_dosificacion = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene los formatos
$formato_textual = get_date_textual($_format);
$formato_numeral = get_date_numeral($_format);

// Obtiene la dosificacion
$dosificacion = $db->select('d.*')->from('inv_dosificaciones d')->where('d.id_dosificacion', $id_dosificacion)->fetch_first();

// Ejecuta un error 404 si no existe la dosificacion
if (!$dosificacion) { require_once not_found(); exit; }

// Obtiene los permisos
$permiso_listar     = in_array('listar', $_views);
$permiso_crear      = in_array('crear', $_views);
$permiso_ver        = in_array('ver', $_views);
$permiso_eliminar   = in_array('eliminar', $_views);
$permiso_imprimir   = in_array('imprimir', $_views);
$permiso_activar    = in_array('activar', $_views);
$permiso_desactivar = in_array('desactivar', $_views);

?>
<?php require_once show_template('header-design'); ?>
<div class="panel-heading">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Modificar dosificación</strong>
	</h3>
</div>
<div class="panel-body">
	<?php if ($permiso_listar || $permiso_crear || $permiso_ver || $permiso_eliminar || $permiso_imprimir || $permiso_activar || $permiso_desactivar) : ?>
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
					<?php if ($permiso_ver) : ?>
					<li><a href="?/dosificaciones/ver/<?= $id_dosificacion; ?>"><span class="glyphicon glyphicon-search"></span> Ver dosificación</a></li>
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
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6">
			<form method="post" action="?/dosificaciones/guardar" autocomplete="off">
				<div class="form-group">
					<label for="nro_tramite" class="control-label">Número de trámite:</label>
					<input type="text" value="<?= $dosificacion['nro_tramite']; ?>" name="nro_tramite" id="nro_tramite" class="form-control" data-validation="required number length" data-validation-length="max50">
					<input type="hidden" name="id_dosificacion" value="<?= $id_dosificacion; ?>">
				</div>
				<div class="form-group">
					<label for="nro_autorizacion_confirmation" class="control-label">Número de autorización:</label>
					<input type="text" value="<?= $dosificacion['nro_autorizacion']; ?>" name="nro_autorizacion_confirmation" id="nro_autorizacion_confirmation" class="form-control" data-validation="required number length" data-validation-length="max50">
				</div>
				<div class="form-group">
					<label for="nro_autorizacion" class="control-label">Repita número de autorización:</label>
					<input type="text" value="<?= $dosificacion['nro_autorizacion']; ?>" name="nro_autorizacion" id="nro_autorizacion" class="form-control" data-validation="required number length confirmation" data-validation-length="max50">
				</div>
				<div class="form-group">
					<label for="llave_dosificacion_confirmation" class="control-label">Llave de dosificación:</label>
					<input type="text" value="<?= base64_decode($dosificacion['llave_dosificacion']); ?>" name="llave_dosificacion_confirmation" id="llave_dosificacion_confirmation" class="form-control" data-validation="required length" data-validation-length="max100">
				</div>
				<div class="form-group">
					<label for="llave_dosificacion" class="control-label">Repita llave de dosificación:</label>
					<input type="text" value="<?= base64_decode($dosificacion['llave_dosificacion']); ?>" name="llave_dosificacion" id="llave_dosificacion" class="form-control" data-validation="required length confirmation" data-validation-length="max100">
				</div>
				<div class="form-group">
					<label for="fecha_limite_confirmation" class="control-label">Fecha límite de emisión:</label>
					<input type="text" value="<?= date_decode($dosificacion['fecha_limite'], $_format); ?>" name="fecha_limite_confirmation" id="fecha_limite_confirmation" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
				</div>
				<div class="form-group">
					<label for="fecha_limite" class="control-label">Repita fecha límite de emisión:</label>
					<input type="text" value="<?= date_decode($dosificacion['fecha_limite'], $_format); ?>" name="fecha_limite" id="fecha_limite" class="form-control" data-validation="required date confirmation" data-validation-format="<?= $formato_textual; ?>">
				</div>
				<div class="form-group">
					<label for="leyenda_factura" class="control-label">Leyenda de la factura: <font color="red">(Nota.- copiar sin Ley Nº 453: >>configuración por defecto<<):</font></label>
					<input type="text" value="<?= $dosificacion['leyenda']; ?>" name="leyenda" id="leyenda_factura" class="form-control" data-validation="required letternumber" data-validation-allowing="-/.,:;& ">
				</div>
				<div class="form-group">
					<label for="observacion" class="control-label">Observación:</label>
					<textarea name="observacion" id="observacion" class="form-control" data-validation="letternumber" data-validation-allowing="-+/.,:;@#&'()_\n " data-validation-optional="true"><?= $dosificacion['observacion']; ?></textarea>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary">
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
<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/jquery.maskedinput.min.js"></script>
<script>
$(function () {
	$.validate({
		modules: 'basic,security'
	});

	$('#fecha_limite_confirmation, #fecha_limite').mask('<?= $formato_numeral; ?>');
	
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