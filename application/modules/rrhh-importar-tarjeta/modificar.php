<?php

// Obtiene los formatos para la fecha
$formato_textual = get_date_textual($_institution['formato']);
$formato_numeral = get_date_numeral($_institution['formato']);

// Obtiene el id_empleado
$id_empleado = (sizeof($params) > 0) ? $params[0] : 0;

// Obtiene el empleado
//$empleado = $db->select('z.*')->from('sys_empleados z')->where('z.id_empleado', $id_empleado)->fetch_first();
$empleado = $db->select('z.*, a.procedencia as procedencia, b.cargo as cargo')->from('sys_empleados z')->join('gen_procedencias a', 'z.procedencia_id = a.id_procedencia', 'left')->join('per_cargos b', 'z.cargo_id = b.id_cargo', 'left')->where('z.id_empleado', $id_empleado)->fetch_first();

// Verifica si existe el empleado
if (!$empleado) {
	// Error 404
	require_once not_found();
	exit;
}
$procedencias = $db->from('gen_procedencias')->order_by('procedencia', 'asc')->fetch();
// Obtiene el modelo cargos
$cargos = $db->from('per_cargos')->order_by('cargo', 'asc')->fetch();

// Almacena los permisos en variables
$permiso_crear    = in_array('crear', $_views);
$permiso_ver      = in_array('ver', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_listar   = in_array('listar', $_views);

?>
<?php require_once show_template('header-sidebar'); ?>
<div class="panel-heading">
	<h3 class="panel-title">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<b>Modificar empleado</b>
	</h3>
</div>
<div class="panel-body">
	<?php if ($permiso_crear || $permiso_ver || $permiso_eliminar || $permiso_listar) { ?>
	<div class="row">
		<div class="col-sm-7 col-md-6 hidden-xs">
			<div class="text-label">Para realizar una acción hacer clic en los botones:</div>
		</div>
		<div class="col-xs-12 col-sm-5 col-md-6 text-right">
			<?php if ($permiso_crear) { ?>
			<a href="?/empleados/crear" class="btn btn-success">
				<span class="glyphicon glyphicon-plus"></span>
				<span class="hidden-xs hidden-sm">Nuevo</span>
			</a>
			<?php } ?>
			<?php if ($permiso_ver) { ?>
			<a href="?/empleados/ver/<?= $empleado['id_empleado']; ?>" class="btn btn-warning">
				<span class="glyphicon glyphicon-search"></span>
				<span class="hidden-xs hidden-sm">Ver</span>
			</a>
			<?php } ?>
			<?php if ($permiso_eliminar) { ?>
			<a href="?/empleados/eliminar/<?= $empleado['id_empleado']; ?>" class="btn btn-danger" data-eliminar="true">
				<span class="glyphicon glyphicon-trash"></span>
				<span class="hidden-xs hidden-sm">Eliminar</span>
			</a>
			<?php } ?>
			<?php if ($permiso_listar) { ?>
			<a href="?/empleados/listar" class="btn btn-primary">
				<span class="glyphicon glyphicon-list-alt"></span>
				<span class="hidden-xs">Listado</span>
			</a>
			<?php } ?>
		</div>
	</div>
	<hr>
	<?php } ?>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2">
			<form method="post" action="?/empleados/guardar" class="form-horizontal">
				<div class="form-group">
					<label for="codigo" class="col-md-3 control-label">Código:</label>
					<div class="col-md-9">
						<input type="text" value="<?= $empleado['codigo']; ?>" name="codigo" id="codigo" class="form-control" autofocus="autofocus" data-validation="required" data-validation-allowing=" " data-validation-length="max100">
					</div>
				</div>
				<div class="form-group">
					<label for="nombres" class="col-md-3 control-label">Nombres:</label>
					<div class="col-md-9">
						<input type="hidden" value="<?= $empleado['id_empleado']; ?>" name="id_empleado" data-validation="required">
						<input type="text" value="<?= $empleado['nombres']; ?>" name="nombres" id="nombres" class="form-control" autocomplete="off" data-validation="required letternumber length" data-validation-allowing=" " data-validation-length="max100">
					</div>
				</div>
				<div class="form-group">
					<label for="paterno" class="col-md-3 control-label">Apellido paterno:</label>
					<div class="col-md-9">
						<input type="text" value="<?= $empleado['paterno']; ?>" name="paterno" id="paterno" class="form-control" autocomplete="off" data-validation="letternumber length" data-validation-allowing=" " data-validation-length="max100" data-validation-optional="true">
					</div>
				</div>
				<div class="form-group">
					<label for="materno" class="col-md-3 control-label">Apellido materno:</label>
					<div class="col-md-9">
						<input type="text" value="<?= $empleado['materno']; ?>" name="materno" id="materno" class="form-control" autocomplete="off" data-validation="letternumber length" data-validation-allowing=" " data-validation-length="max100" data-validation-optional="true">
					</div>
				</div>
				<div class="form-group">
					<label for="genero" class="col-md-3 control-label">Género:</label>
					<div class="col-md-9">
						<div class="radio">
							<label>
								<input type="radio" name="genero" value="Masculino" <?= ($empleado['genero'] == 'Masculino') ? 'checked' : ''; ?>>
								<span>Masculino</span>
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="genero" value="Femenino" <?= ($empleado['genero'] == 'Femenino') ? 'checked' : ''; ?>>
								<span>Femenino</span>
							</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="fecha_nacimiento" class="col-md-3 control-label">Fecha de nacimiento:</label>
					<div class="col-md-9">
						<input type="text" value="<?= date_decode($empleado['fecha_nacimiento'], $_institution['formato']); ?>" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" autocomplete="off" data-validation-optional="true">
					</div>
				</div>
				<div class="form-group">
					<label for="ci" class="col-md-3 control-label">Cédula de identidad:</label>
					<div class="col-md-9">
						<input type="text" value="<?= $empleado['ci']; ?>" name="ci" id="ci" class="form-control" data-validation="required alphanumeric length" data-validation-allowing="-_ " data-validation-length="max20">
					</div>
				</div>
				<div class="form-group">
					<label for="procedencia_id" class="col-md-3 control-label">Procedencia:</label>
					<div class="col-md-9">
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
				</div>
				<div class="form-group">
					<label for="direccion" class="col-md-3 control-label">Dirección:</label>
					<div class="col-md-9">
						<textarea name="direccion" id="direccion" class="form-control" data-validation="required letternumber" data-validation-allowing="-+/.,:;@#&'()_\n "><?= $empleado['direccion']; ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="telefono" class="col-md-3 control-label">Teléfono:</label>
					<div class="col-md-9">
						<input type="text" value="<?= $empleado['telefono']; ?>" name="telefono" id="telefono" class="form-control" autocomplete="off" data-validation="alphanumeric length" data-validation-allowing="-+,() " data-validation-length="max100" data-validation-optional="true">
					</div>
				</div>
				<div class="form-group">
					<label for="cargo_id" class="col-md-3 control-label">Cargo:</label>
					<div class="col-md-9">
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
				</div>
				<div class="form-group">
					<div class="col-md-9 col-md-offset-3">
						<button type="submit" class="btn btn-primary">
							<span class="glyphicon glyphicon-floppy-disk"></span>
							<span>Guardar</span>
						</button>
						<button type="reset" class="btn btn-default">
							<span class="glyphicon glyphicon-refresh"></span>
							<span>Restablecer</span>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/moment.min.js"></script>
<script src="<?= js; ?>/moment.es.js"></script>
<script src="<?= js; ?>/bootstrap-datetimepicker.min.js"></script>
<script src="<?= js; ?>/jquery.maskedinput.min.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script>
$(function () {
	$.validate({
		modules: 'basic,date'
	});

	$('#telefono').selectize({
		persist: false,
		createOnBlur: true,
		create: true,
		onInitialize: function () {
			$('#telefono').css({
				display: 'block',
				left: '-10000px',
				opacity: '0',
				position: 'absolute',
				top: '-10000px'
			});
		},
		onChange: function () {
			$('#telefono').trigger('blur');
		},
		onBlur: function () {
			$('#telefono').trigger('blur');
		}
	});

	$(':reset').on('click', function () {
		$('#telefono')[0].selectize.clear();
	});
	
	$('#fecha_nacimiento').mask('<?= $formato_numeral; ?>').datetimepicker({
		format: '<?= strtoupper($formato_textual); ?>'
	}).on('dp.change', function () {
		$(this).trigger('blur');
	});
	
	$('.form-control:first').select();
	
	<?php if ($permiso_eliminar) { ?>
	$('[data-eliminar]').on('click', function (e) {
		e.preventDefault();
		var url = $(this).attr('href');
		bootbox.confirm('¿Está seguro que desea eliminar el empleado?', function (result) {
			if(result){
				window.location = url;
			}
		});
	});
	<?php } ?>
});
</script>
<?php require_once show_template('footer-sidebar'); ?>