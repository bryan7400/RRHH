<?php

// Obtiene los parametros
$id_aula = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los formatos
$formato_textual = get_date_textual($_format);
$formato_numeral = get_date_numeral($_format);

// Obtiene el aula
$aula = $db->select('z.*')->from('ins_aula z')->where('z.id_aula', $id_aula)->fetch_first();

// Ejecuta un error 404 si no existe el aula
if (!$aula) { require_once not_found(); exit; }

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
		<strong>Modificar aula</strong>
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
					<li><a href="?/aula/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar aula</a></li>
					<?php endif ?>
					<?php if ($permiso_crear) : ?>
					<li><a href="?/aula/crear"><span class="glyphicon glyphicon-plus"></span> Crear aula</a></li>
					<?php endif ?>
					<?php if ($permiso_ver) : ?>
					<li><a href="?/aula/ver/<?= $id_aula; ?>"><span class="glyphicon glyphicon-search"></span> Ver aula</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<li><a href="?/aula/eliminar/<?= $id_aula; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar aula</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/aula/imprimir/<?= $id_aula; ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir aula</a></li>
					<?php endif ?>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	<?php endif ?>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<form method="post" action="?/aula/guardar" autocomplete="off">
				<input type="hidden" name="<?= $csrf; ?>">
				<div class="form-group">
					<label for="nombre_aula" class="control-label">Nombre aula:</label>
					<input type="text" value="<?= $aula['nombre_aula']; ?>" name="nombre_aula" id="nombre_aula" class="form-control" autofocus="autofocus" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max10">
					<input type="text" value="<?= $id_aula; ?>" name="id_aula" id="id_aula" class="translate" tabindex="-1" data-validation="required number" data-validation-error-msg="El campo no es válido">
				</div>
				<div class="form-group">
					<label for="descripcion" class="control-label">Descripcion:</label>
					<input type="text" value="<?= $aula['descripcion']; ?>" name="descripcion" id="descripcion" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max100">
				</div>
				<div class="form-group">
					<label for="nivel_academico_id" class="control-label">Nivel_academico:</label>
					<input type="text" value="<?= $aula['nivel_academico_id']; ?>" name="nivel_academico_id" id="nivel_academico_id" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="estado" class="control-label">Estado:</label>
					<select name="estado" id="estado" class="form-control" data-validation="required">
						<option value="">Seleccionar</option>
						<option value="A"<?= ($aula['estado'] == 'A') ? ' selected="selected"' : ''; ?>>A</option>
						<option value="I"<?= ($aula['estado'] == 'I') ? ' selected="selected"' : ''; ?>>I</option>
					</select>
				</div>
				<div class="form-group">
					<label for="usuario_registro" class="control-label">Usuario registro:</label>
					<input type="text" value="<?= $aula['usuario_registro']; ?>" name="usuario_registro" id="usuario_registro" class="form-control" data-validation="number" data-validation-optional="true">
				</div>
				<div class="form-group">
					<label for="fecha_registro" class="control-label">Fecha registro:</label>
					<input type="text" value="<?= $aula['fecha_registro']; ?>" name="fecha_registro" id="fecha_registro" class="form-control" data-validation="custom" data-validation-regexp="^([12][0-9]{3})-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01]) (0[0-9]|1[0-9]|2[0123])\:([012345][0-9])\:([012345][0-9])$" data-validation-optional="true">
				</div>
				<div class="form-group">
					<label for="usuario_modificacion" class="control-label">Usuario modificacion:</label>
					<input type="text" value="<?= $aula['usuario_modificacion']; ?>" name="usuario_modificacion" id="usuario_modificacion" class="form-control" data-validation="number" data-validation-optional="true">
				</div>
				<div class="form-group">
					<label for="fecha_modificacion" class="control-label">Fecha modificacion:</label>
					<input type="text" value="<?= $aula['fecha_modificacion']; ?>" name="fecha_modificacion" id="fecha_modificacion" class="form-control" data-validation="custom" data-validation-regexp="^([12][0-9]{3})-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01]) (0[0-9]|1[0-9]|2[0123])\:([012345][0-9])\:([012345][0-9])$" data-validation-optional="true">
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
					window.location = '?/aula/crear';
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
		bootbox.confirm('¿Está seguro que desea eliminar el aula?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-full'); ?>