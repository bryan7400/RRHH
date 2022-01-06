<?php

// Obtiene los parametros
$id_pensiones = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los formatos
$formato_textual = get_date_textual($_format);
$formato_numeral = get_date_numeral($_format);

// Obtiene el spensiones
$spensiones = $db->select('z.*')->from('pen_pensiones z')->where('z.id_pensiones', $id_pensiones)->fetch_first();

// Ejecuta un error 404 si no existe el spensiones
if (!$spensiones) { require_once not_found(); exit; }

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);

?>
<?php require_once show_template('header-full'); ?>
<div class="panel-heading">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Modificar pensiones</strong>
	</h3>
</div>
<div class="panel-body">
	<?php if ($permiso_listar || $permiso_crear || $permiso_ver || $permiso_eliminar || $permiso_imprimir) : ?>
	<div class="row">
		<div class="col-xs-6">
			<div class="text-label hidden-xs">Seleccionar acción:</div>
			<div class="text-label visible-xs-block">Acciones:</div>
		</div>
		<div class="col-xs-6 text-right">
			<div class="btn-group">
				<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
					<span class="glyphicon glyphicon-menu-hamburger"></span>
					<span class="hidden-xs">Acciones</span>
				</button>
				<ul class="dropdown-menu dropdown-menu-right">
					<li class="dropdown-header visible-xs-block">Seleccionar acción</li>
					<?php if ($permiso_listar) : ?>
					<li><a href="?/spensiones/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar pensiones</a></li>
					<?php endif ?>
					<?php if ($permiso_crear) : ?>
					<li><a href="?/spensiones/crear"><span class="glyphicon glyphicon-plus"></span> Crear pensiones</a></li>
					<?php endif ?>
					<?php if ($permiso_ver) : ?>
					<li><a href="?/spensiones/ver/<?= $id_pensiones; ?>"><span class="glyphicon glyphicon-search"></span> Ver pensiones</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<li><a href="?/spensiones/eliminar/<?= $id_pensiones; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar pensiones</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/spensiones/imprimir/<?= $id_pensiones; ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir pensiones</a></li>
					<?php endif ?>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	<?php endif ?>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<form method="post" action="?/spensiones/guardar" autocomplete="off">
				<input type="hidden" name="<?= $csrf; ?>">
				<div class="form-group">
					<label for="nombre_pension" class="control-label">Nombre pension:</label>
					<input type="text" value="<?= $spensiones['nombre_pension']; ?>" name="nombre_pension" id="nombre_pension" class="form-control" autofocus="autofocus" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max50">
					<input type="text" value="<?= $id_pensiones; ?>" name="id_pensiones" id="id_pensiones" class="translate" tabindex="-1" data-validation="required number" data-validation-error-msg="El campo no es válido">
				</div>
				<div class="form-group">
					<label for="descripcion" class="control-label">Descripcion:</label>
					<input type="text" value="<?= $spensiones['descripcion']; ?>" name="descripcion" id="descripcion" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max100">
				</div>
				<div class="form-group">
					<label for="monto" class="control-label">Monto:</label>
					<input type="text" value="<?= $spensiones['monto']; ?>" name="monto" id="monto" class="form-control" data-validation="required number" data-validation-allowing="float">
				</div>
				<div class="form-group">
					<label for="mora_dia" class="control-label">Mora dia:</label>
					<input type="text" value="<?= $spensiones['mora_dia']; ?>" name="mora_dia" id="mora_dia" class="form-control" data-validation="required number" data-validation-allowing="float">
				</div>
				<div class="form-group">
					<label for="fecha_inicio" class="control-label">Fecha inicio:</label>
					<div class="row">
						<div class="col-xs-12">
							<input type="text" value="<?= date_decode($spensiones['fecha_inicio'], $_format); ?>" name="fecha_inicio" id="fecha_inicio" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="fecha_final" class="control-label">Fecha final:</label>
					<div class="row">
						<div class="col-xs-12">
							<input type="text" value="<?= date_decode($spensiones['fecha_final'], $_format); ?>" name="fecha_final" id="fecha_final" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="tipo_estudiante_id" class="control-label">Tipo_estudiante:</label>
					<input type="text" value="<?= $spensiones['tipo_estudiante_id']; ?>" name="tipo_estudiante_id" id="tipo_estudiante_id" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="nivel_academico_id" class="control-label">Nivel_academico:</label>
					<input type="text" value="<?= $spensiones['nivel_academico_id']; ?>" name="nivel_academico_id" id="nivel_academico_id" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="gestion_id" class="control-label">Gestion:</label>
					<input type="text" value="<?= $spensiones['gestion_id']; ?>" name="gestion_id" id="gestion_id" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-danger">
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
<script src="<?= js; ?>/bootstrap-datetimepicker.min.js"></script>
<script>
$(function () {
	$.validate({
		modules: 'basic'
	});

	$('#fecha_inicio').datetimepicker({
		format: '<?= strtoupper($formato_textual); ?>'
	});

	$('#fecha_final').datetimepicker({
		format: '<?= strtoupper($formato_textual); ?>'
	});
	
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/spensiones/crear';
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
		bootbox.confirm('¿Está seguro que desea eliminar el pensiones?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-full'); ?>