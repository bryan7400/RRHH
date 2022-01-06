<?php

// Obtiene los parametros
$id_modulo = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene el modulo
$modulo = $db->select('z.*')->from('con_modulos z')->where('z.id_modulo', $id_modulo)->fetch_first();

// Ejecuta un error 404 si no existe el modulo
if (!$modulo) { require_once not_found(); exit; }

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
		<strong>Modificar módulo</strong>
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
					<li><a href="?/modulos/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar módulos</a></li>
					<?php endif ?>
					<?php if ($permiso_crear) : ?>
					<li><a href="?/modulos/crear"><span class="glyphicon glyphicon-plus"></span> Crear módulo</a></li>
					<?php endif ?>
					<?php if ($permiso_ver) : ?>
					<li><a href="?/modulos/ver/<?= $id_modulo; ?>"><span class="glyphicon glyphicon-search"></span> Ver módulo</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<li><a href="?/modulos/eliminar/<?= $id_modulo; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar módulo</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/modulos/imprimir/<?= $id_modulo; ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir módulo</a></li>
					<?php endif ?>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	<?php endif ?>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<form method="post" action="?/modulos/guardar" autocomplete="off">
				<input type="hidden" name="<?= $csrf; ?>">
				<div class="form-group">
					<label for="modulo" class="control-label">Módulo:</label>
					<input type="text" value="<?= $modulo['modulo']; ?>" name="modulo" id="modulo" class="form-control" autofocus="autofocus" data-validation="required letternumber length" data-validation-allowing="-+/.,:;@#&'()_\n " data-validation-length="max200">
					<input type="text" value="<?= $id_modulo; ?>" name="id_modulo" id="id_modulo" class="translate" tabindex="-1" data-validation="required number" data-validation-error-msg="El campo no es válido">
				</div>
				<div class="form-group">
					<label for="visible" class="control-label">Visible:</label>
					<div class="radio">
						<label>
							<input type="radio" value="s" name="visible"<?= ($modulo['visible'] == 's') ? ' checked="checked"' : ''; ?>>
							<span>s</span>
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" value="n" name="visible"<?= ($modulo['visible'] == 'n') ? ' checked="checked"' : ''; ?>>
							<span>n</span>
						</label>
					</div>
				</div>
				<div class="form-group">
					<label for="descripcion" class="control-label">Descripción:</label>
					<textarea name="descripcion" id="descripcion" class="form-control" data-validation="required letternumber" data-validation-allowing="-+/.,:;@#&'()_\n "><?= $modulo['descripcion']; ?></textarea>
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
<script>
$(function () {
	$.validate({
		modules: 'basic'
	});
	
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/modulos/crear';
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
		bootbox.confirm('¿Está seguro que desea eliminar el módulo?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-full'); ?>