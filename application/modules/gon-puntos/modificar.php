<?php

// Obtiene los parametros
$id_punto = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los formatos
$formato_textual = get_date_textual($_format);
$formato_numeral = get_date_numeral($_format);

// Obtiene el puntos
$puntos = $db->select('z.*')->from('gon_puntos z')->where('z.id_punto', $id_punto)->fetch_first();

// Ejecuta un error 404 si no existe el puntos
if (!$puntos) { require_once not_found(); exit; }

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
		<strong>Modificar puntos</strong>
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
					<li><a href="?/puntos/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar puntos</a></li>
					<?php endif ?>
					<?php if ($permiso_crear) : ?>
					<li><a href="?/puntos/crear"><span class="glyphicon glyphicon-plus"></span> Crear puntos</a></li>
					<?php endif ?>
					<?php if ($permiso_ver) : ?>
					<li><a href="?/puntos/ver/<?= $id_punto; ?>"><span class="glyphicon glyphicon-search"></span> Ver puntos</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<li><a href="?/puntos/eliminar/<?= $id_punto; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar puntos</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/puntos/imprimir/<?= $id_punto; ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir puntos</a></li>
					<?php endif ?>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	<?php endif ?>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<form method="post" action="?/puntos/guardar" autocomplete="off">
				<input type="hidden" name="<?= $csrf; ?>">
				<div class="form-group">
					<label for="descripcion" class="control-label">Descripcion:</label>
					<input type="text" value="<?= $puntos['descripcion']; ?>" name="descripcion" id="descripcion" class="form-control" autofocus="autofocus" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max250">
					<input type="text" value="<?= $id_punto; ?>" name="id_punto" id="id_punto" class="translate" tabindex="-1" data-validation="required number" data-validation-error-msg="El campo no es válido">
				</div>
				<div class="form-group">
					<label for="latitud" class="control-label">Latitud:</label>
					<input type="text" value="<?= $puntos['latitud']; ?>" name="latitud" id="latitud" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max200">
				</div>
				<div class="form-group">
					<label for="longitud" class="control-label">Longitud:</label>
					<input type="text" value="<?= $puntos['longitud']; ?>" name="longitud" id="longitud" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max200">
				</div>
				<div class="form-group">
					<label for="imagen_lugar" class="control-label">Imagen lugar:</label>
					<input type="text" value="<?= $puntos['imagen_lugar']; ?>" name="imagen_lugar" id="imagen_lugar" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max250">
				</div>
				<div class="form-group">
					<label for="nombre_lugar" class="control-label">Nombre lugar:</label>
					<input type="text" value="<?= $puntos['nombre_lugar']; ?>" name="nombre_lugar" id="nombre_lugar" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max250">
				</div>
				<div class="form-group">
					<label for="estado" class="control-label">Estado:</label>
					<input type="text" value="<?= $puntos['estado']; ?>" name="estado" id="estado" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="ruta_id" class="control-label">Ruta:</label>
					<input type="text" value="<?= $puntos['ruta_id']; ?>" name="ruta_id" id="ruta_id" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="usuario_registro" class="control-label">Usuario registro:</label>
					<input type="text" value="<?= $puntos['usuario_registro']; ?>" name="usuario_registro" id="usuario_registro" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="fecha_registro" class="control-label">Fecha registro:</label>
					<input type="text" value="<?= $puntos['fecha_registro']; ?>" name="fecha_registro" id="fecha_registro" class="form-control" data-validation="required custom" data-validation-regexp="^([12][0-9]{3})-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01]) (0[0-9]|1[0-9]|2[0123])\:([012345][0-9])\:([012345][0-9])$">
				</div>
				<div class="form-group">
					<label for="usuario_modificacion" class="control-label">Usuario modificacion:</label>
					<input type="text" value="<?= $puntos['usuario_modificacion']; ?>" name="usuario_modificacion" id="usuario_modificacion" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="fecha_modificacion" class="control-label">Fecha modificacion:</label>
					<input type="text" value="<?= $puntos['fecha_modificacion']; ?>" name="fecha_modificacion" id="fecha_modificacion" class="form-control" data-validation="required custom" data-validation-regexp="^([12][0-9]{3})-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01]) (0[0-9]|1[0-9]|2[0123])\:([012345][0-9])\:([012345][0-9])$">
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
<script src="<?= js; ?>/jquery.maskedinput.min.js"></script>
<script>
$(function () {
	$.validate({
		modules: 'basic'
	});

	$('#fecha_registro').mask('9999-99-99 99:99:99');

	$('#fecha_modificacion').mask('9999-99-99 99:99:99');
	
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/puntos/crear';
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
		bootbox.confirm('¿Está seguro que desea eliminar el puntos?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-full'); ?>