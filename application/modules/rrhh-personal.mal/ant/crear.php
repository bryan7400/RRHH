<?php

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los formatos
$formato_textual = get_date_textual($_format);
$formato_numeral = get_date_numeral($_format);

// Obtiene el modelo procedencias
$procedencias = $db->from('gen_procedencias')->order_by('procedencia', 'asc')->fetch();

// Obtiene el modelo cargos
$cargos = $db->from('per_cargos')->order_by('cargo', 'asc')->fetch();

// Obtiene el modelo sucursales
$sucursales = $db->from('gen_sucursales')->order_by('sucursal', 'asc')->fetch();

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);

?>
<?php require_once show_template('header-full'); ?>
<div class="panel-heading">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Crear empleado</strong>
	</h3>
</div>
<div class="panel-body">
	<?php if ($permiso_listar) : ?>
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
					<li><a href="?/empleados/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar empleados</a></li>
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
					<input type="text" value="" name="nombres" id="nombres" class="form-control" autofocus="autofocus" data-validation="required letter length" data-validation-allowing=" " data-validation-length="max100">
				</div>
				<div class="form-group">
					<label for="paterno" class="control-label">Apellido paterno:</label>
					<input type="text" value="" name="paterno" id="paterno" class="form-control" data-validation="letter length" data-validation-allowing=" " data-validation-length="max100" data-validation-optional="true">
				</div>
				<div class="form-group">
					<label for="materno" class="control-label">Apellido materno:</label>
					<input type="text" value="" name="materno" id="materno" class="form-control" data-validation="required letter length" data-validation-allowing=" " data-validation-length="max100">
				</div>
				<div class="form-group">
					<label for="genero" class="control-label">Género:</label>
					<div class="radio">
						<label>
							<input type="radio" value="m" name="genero" id="genero" checked="checked">
							<span>Masculino</span>
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" value="f" name="genero">
							<span>Femenino</span>
						</label>
					</div>
				</div>
				<div class="form-group">
					<label for="fecha_nacimiento" class="control-label">Fecha de nacimiento:</label>
					<input type="text" value="" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
				</div>
				<div class="form-group">
					<label for="ci" class="control-label">Cédula de identidad:</label>
					<input type="text" value="" name="ci" id="ci" class="form-control" data-validation="required alphanumeric length" data-validation-allowing="-_ " data-validation-length="max20">
				</div>
				<div class="form-group">
					<label for="procedencia_id" class="control-label">Procedencia:</label>
					<select name="procedencia_id" id="procedencia_id" class="form-control" data-validation="required">
						<option value="" selected="selected">Seleccionar</option>
						<?php foreach ($procedencias as $elemento) : ?>
						<option value="<?= $elemento['id_procedencia']; ?>"><?= escape($elemento['procedencia']); ?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="form-group">
					<label for="direccion" class="control-label">Dirección:</label>
					<textarea name="direccion" id="direccion" class="form-control" data-validation="required letternumber" data-validation-allowing="-+/.,:;@#&'()_\n "></textarea>
				</div>
				<div class="form-group">
					<label for="telefono" class="control-label">Teléfono:</label>
					<input type="text" value="" name="telefono" id="telefono" class="form-control" data-validation="required alphanumeric length" data-validation-allowing="-+,() " data-validation-length="max100">
				</div>
				<div class="form-group">
					<label for="cargo_id" class="control-label">Cargo:</label>
					<select name="cargo_id" id="cargo_id" class="form-control" data-validation="required">
						<option value="" selected="selected">Seleccionar</option>
						<?php foreach ($cargos as $elemento) : ?>
						<option value="<?= $elemento['id_cargo']; ?>"><?= escape($elemento['cargo']); ?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="form-group">
					<label for="sucursal_id" class="control-label">Sucursal:</label>
					<select name="sucursal_id" id="sucursal_id" class="form-control" data-validation="required">
						<option value="" selected="selected">Seleccionar</option>
						<?php foreach ($sucursales as $elemento) : ?>
						<option value="<?= $elemento['id_sucursal']; ?>"><?= escape($elemento['sucursal']); ?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="form-group">
					<label for="observacion" class="control-label">Observación:</label>
					<textarea name="observacion" id="observacion" class="form-control" data-validation="letternumber" data-validation-allowing="-+/.,:;@#&'()_\n " data-validation-optional="true"></textarea>
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
});
</script>
<?php require_once show_template('footer-full'); ?>