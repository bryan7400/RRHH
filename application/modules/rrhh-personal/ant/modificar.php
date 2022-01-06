<?php

// Obtiene los parametros
$id_empleado = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los formatos
$formato_textual = get_date_textual($_format);
$formato_numeral = get_date_numeral($_format);

// Obtiene el empleado
$empleado = $db->select('z.*, a.procedencia as procedencia, b.cargo as cargo, c.sucursal')->from('per_empleados z')->join('gen_procedencias a', 'z.procedencia_id = a.id_procedencia', 'left')->join('per_cargos b', 'z.cargo_id = b.id_cargo', 'left')->join('gen_sucursales c', 'z.sucursal_id = c.id_sucursal', 'left')->where('z.id_empleado', $id_empleado)->fetch_first();

// Ejecuta un error 404 si no existe el empleado
if (!$empleado) { require_once not_found(); exit; }

// Obtiene el modelo procedencias
$procedencias = $db->from('gen_procedencias')->order_by('procedencia', 'asc')->fetch();

// Obtiene el modelo cargos
$cargos = $db->from('per_cargos')->order_by('cargo', 'asc')->fetch();

// Obtiene el modelo sucursales
$sucursales = $db->from('gen_sucursales')->order_by('sucursal', 'asc')->fetch();

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
		<strong>Modificar empleado</strong>
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
				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
					<span class="glyphicon glyphicon-menu-hamburger"></span>
					<span class="hidden-xs">Acciones</span>
				</button>
				<ul class="dropdown-menu dropdown-menu-right">
					<li class="dropdown-header visible-xs-block">Seleccionar acción</li>
					<?php if ($permiso_listar) : ?>
					<li><a href="?/empleados/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar empleados</a></li>
					<?php endif ?>
					<?php if ($permiso_crear) : ?>
					<li><a href="?/empleados/crear"><span class="glyphicon glyphicon-plus"></span> Crear empleado</a></li>
					<?php endif ?>
					<?php if ($permiso_ver) : ?>
					<li><a href="?/empleados/ver/<?= $id_empleado; ?>"><span class="glyphicon glyphicon-search"></span> Ver empleado</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<li><a href="?/empleados/eliminar/<?= $id_empleado; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar empleado</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/empleados/imprimir/<?= $id_empleado; ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir empleado</a></li>
					<?php endif ?>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	<?php endif ?>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<form method="post" action="?/empleados/guardar" autocomplete="off">
				<input type="hidden" name="<?= $csrf; ?>">
				<div class="form-group">
					<label for="nombres" class="control-label">Nombres:</label>
					<input type="text" value="<?= $empleado['nombres']; ?>" name="nombres" id="nombres" class="form-control" autofocus="autofocus" data-validation="required letter length" data-validation-allowing=" " data-validation-length="max100">
					<input type="text" value="<?= $id_empleado; ?>" name="id_empleado" id="id_empleado" class="translate" tabindex="-1" data-validation="required number" data-validation-error-msg="El campo no es válido">
				</div>
				<div class="form-group">
					<label for="paterno" class="control-label">Apellido paterno:</label>
					<input type="text" value="<?= $empleado['paterno']; ?>" name="paterno" id="paterno" class="form-control" data-validation="letter length" data-validation-allowing=" " data-validation-length="max100" data-validation-optional="true">
				</div>
				<div class="form-group">
					<label for="materno" class="control-label">Apellido materno:</label>
					<input type="text" value="<?= $empleado['materno']; ?>" name="materno" id="materno" class="form-control" data-validation="required letter length" data-validation-allowing=" " data-validation-length="max100">
				</div>
				<div class="form-group">
					<label for="genero" class="control-label">Género:</label>
					<div class="radio">
						<label>
							<input type="radio" value="m" name="genero"<?= ($empleado['genero'] == 'm') ? ' checked="checked"' : ''; ?>>
							<span>m</span>
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" value="f" name="genero"<?= ($empleado['genero'] == 'f') ? ' checked="checked"' : ''; ?>>
							<span>f</span>
						</label>
					</div>
				</div>
				<div class="form-group">
					<label for="fecha_nacimiento" class="control-label">Fecha de nacimiento:</label>
					<input type="text" value="<?= date_decode($empleado['fecha_nacimiento'], $_format); ?>" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
				</div>
				<div class="form-group">
					<label for="ci" class="control-label">Cédula de identidad:</label>
					<input type="text" value="<?= $empleado['ci']; ?>" name="ci" id="ci" class="form-control" data-validation="required alphanumeric length" data-validation-allowing="-_ " data-validation-length="max20">
				</div>
				<div class="form-group">
					<label for="procedencia_id" class="control-label">Procedencia:</label>
					<select name="procedencia_id" id="procedencia_id" class="form-control" data-validation="required">
						<option value="">Seleccionar</option>
						<?php foreach ($procedencias as $elemento) : ?>
						<?php if ($elemento['id_procedencia'] == $empleado['procedencia_id']) : ?>
						<option value="<?= $elemento['id_procedencia']; ?>" selected="selected"><?= escape($elemento['procedencia']); ?></option>
						<?php else : ?>
						<option value="<?= $elemento['id_procedencia']; ?>"><?= escape($elemento['procedencia']); ?></option>
						<?php endif ?>
						<?php endforeach ?>
					</select>
				</div>
				<div class="form-group">
					<label for="direccion" class="control-label">Dirección:</label>
					<textarea name="direccion" id="direccion" class="form-control" data-validation="required letternumber" data-validation-allowing="-+/.,:;@#&'()_\n "><?= $empleado['direccion']; ?></textarea>
				</div>
				<div class="form-group">
					<label for="telefono" class="control-label">Teléfono:</label>
					<input type="text" value="<?= $empleado['telefono']; ?>" name="telefono" id="telefono" class="form-control" data-validation="required alphanumeric length" data-validation-allowing="-+,() " data-validation-length="max100">
				</div>
				<div class="form-group">
					<label for="cargo_id" class="control-label">Cargo:</label>
					<select name="cargo_id" id="cargo_id" class="form-control" data-validation="required">
						<option value="">Seleccionar</option>
						<?php foreach ($cargos as $elemento) : ?>
						<?php if ($elemento['id_cargo'] == $empleado['cargo_id']) : ?>
						<option value="<?= $elemento['id_cargo']; ?>" selected="selected"><?= escape($elemento['cargo']); ?></option>
						<?php else : ?>
						<option value="<?= $elemento['id_cargo']; ?>"><?= escape($elemento['cargo']); ?></option>
						<?php endif ?>
						<?php endforeach ?>
					</select>
				</div>
				<div class="form-group">
					<label for="sucursal_id" class="control-label">Sucursal:</label>
					<select name="sucursal_id" id="sucursal_id" class="form-control" data-validation="required">
						<option value="">Seleccionar</option>
						<?php foreach ($sucursales as $elemento) : ?>
						<?php if ($elemento['id_sucursal'] == $empleado['sucursal_id']) : ?>
						<option value="<?= $elemento['id_sucursal']; ?>" selected="selected"><?= escape($elemento['sucursal']); ?></option>
						<?php else : ?>
						<option value="<?= $elemento['id_sucursal']; ?>"><?= escape($elemento['sucursal']); ?></option>
						<?php endif ?>
						<?php endforeach ?>
					</select>
				</div>
				<div class="form-group">
					<label for="observacion" class="control-label">Observación:</label>
					<textarea name="observacion" id="observacion" class="form-control" data-validation="letternumber" data-validation-allowing="-+/.,:;@#&'()_\n " data-validation-optional="true"><?= $empleado['observacion']; ?></textarea>
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
		modules: 'basic'
	});

	$('#fecha_nacimiento').mask('<?= $formato_numeral; ?>');

	$('#fecha_contratacion').mask('<?= $formato_numeral; ?>');

	$('#fecha_finalizacion').mask('<?= $formato_numeral; ?>');
	
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/empleados/crear';
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
		bootbox.confirm('Está seguro que desea eliminar el empleado?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-full'); ?>