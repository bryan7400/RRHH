<?php

// Obtiene los parametros
$tabla = (isset($_params[0])) ? str_replace('-', '_', $_params[0]) : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene las tablas prohibidas
$prohibidos = json_decode(@file_get_contents(storages . '/modulos.json'), true);

// Obtiene el modulo
$modulo = $db->query("show tables like '$tabla'")->fetch();

// Ejecuta un error 404 si no existe el modulo
if (!$modulo || in_array($tabla, $prohibidos)) { require_once not_found(); exit; }

// Obtiene las tablas permitidas
$tablas = $db->query('show tables from ' . database)->fetch();

// Define el conjunto de modulos
$modulos = array();

// Obtiene todos los modulos
foreach ($tablas as $nro => $elemento) { array_push($modulos, $elemento['Tables_in_' . database]); }

// Obtiene los modulos disponibles
$tablas = array_diff($modulos, $prohibidos);

// Obtiene los nombres de los campos de la tabla
$campos = $db->query("show columns from $tabla")->fetch();

// Retorna el tipo y el valor de un campo
function obtener_tamano($tipo) {
	$valor = 0;
	if (substr($tipo, -1) == ')') {
		$tipo = substr($tipo, 0, -1);
		$tipo = explode('(', $tipo);
		$valor = $tipo[1];
		$tipo = $tipo[0];
	}
	return array($tipo, $valor);
}

?>
<?php require_once show_template('header-full'); ?>
<div class="panel-heading">
	<h3 class="panel-title">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Generar módulo</strong>
	</h3>
</div>
<div class="panel-body">
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
					<li><a href="?/generador-modulos/principal"><span class="glyphicon glyphicon-folder-close"></span> Seleccionar módulo</a></li>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	<div class="hidden">
		<?php foreach ($tablas as $nro => $elemento) : ?>
		<?php $columnas = $db->query("show columns from $elemento")->fetch(); ?>
		<select id="<?= $elemento; ?>">
			<?php foreach ($columnas as $nro => $columna) : ?>
			<option value="<?= $columna['Field']; ?>"><?= escape($columna['Field']); ?></option>
			<?php endforeach ?>
		</select>
		<?php endforeach ?>
	</div>
	<form method="post" action="?/generador-modulos/crear" autocomplete="off">
		<input type="hidden" name="<?= $csrf; ?>">
		<p><strong>Personalización del formulario:</strong></p>
		<div class="table-responsive">
			<table id="table" class="table table-bordered table-condensed">
				<thead>
					<tr class="active">
						<th class="text-center"><span class="glyphicon glyphicon-move" data-toggle="tooltip" data-title="Ordenar campos" data-placement="right"></span></th>
						<th>Campo</th>
						<th>Tipo</th>
						<th>Componente</th>
						<th>Tabla</th>
						<th>Clave</th>
						<th>Valor</th>
						<th>Validación</th>
						<th>Opciones</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($campos as $nro => $campo) : ?>
					<?php list($tipo, $valor) = obtener_tamano($campo['Type']); ?>
					<tr>
						<?php if ($campo['Extra'] != 'auto_increment') : ?>
						<td class="text-center cursor-move table-index">
							<div class="text-label table-index"><span class="glyphicon glyphicon-move table-index"></span></div>
						</td>
						<?php else : ?>
						<td></td>
						<?php endif ?>
						<td>
							<input type="text" value="<?= $campo['Field']; ?>" name="campos[<?= $campo['Field']; ?>]" class="translate" tabindex="-1" data-validation="required" data-validation-error-msg="El campo no es válido">	
							<?php if ($campo['Extra'] != 'auto_increment') : ?>
								<?php if (substr($campo['Field'], -3) == '_id'): ?>
								<input type="text" value="<?= ucfirst(str_replace('_id', '', $campo['Field'])); ?>" name="etiquetas[<?= $campo['Field']; ?>]" class="form-control" data-validation="required">
								<?php else : ?>
								<input type="text" value="<?= ucfirst(str_replace('_', ' ', $campo['Field'])); ?>" name="etiquetas[<?= $campo['Field']; ?>]" class="form-control" data-validation="required">
								<?php endif ?>
							<?php else : ?>
							<input type="text" value="#" name="etiquetas[<?= $campo['Field']; ?>]" class="form-control" data-validation="required">
							<?php endif ?>
						</td>
						<td>
							<input type="text" value="<?= $tipo; ?>" name="tipos[<?= $campo['Field']; ?>]" class="translate" tabindex="-1" data-validation="required" data-validation-error-msg="El campo no es válido">	
							<input type="text" value="<?= $valor; ?>" name="tamanos[<?= $campo['Field']; ?>]" class="translate" tabindex="-1" data-validation="required" data-validation-error-msg="El campo no es válido">	
							<span class="text-label text-info"><?= $campo['Type']; ?></span>
						</td>
						<td>
							<?php if ($campo['Extra'] != 'auto_increment') : ?>
							<select name="formularios[<?= $campo['Field']; ?>]" class="form-control" data-validation="required">
								<option value="">Seleccionar</option>
								<?php if ($tipo == 'int') : ?>
								<option value="select-table">&lt;select-table&gt;</option>
								<option value="text-number" selected="selected">&lt;text-number&gt;</option>
								<?php elseif ($tipo == 'bigint') : ?>
								<option value="text-number" selected="selected">&lt;text-number&gt;</option>
								<?php elseif ($tipo == 'decimal') : ?>
								<option value="text-float" selected="selected">&lt;text-float&gt;</option>
								<?php elseif ($tipo == 'longtext' || $tipo == 'mediumtext' || $tipo == 'tinytext' || $tipo == 'text') : ?>
								<option value="textarea-address">&lt;textarea-address&gt;</option>
								<option value="textarea-all">&lt;textarea-all&gt;</option>
								<option value="textarea-alphanumeric">&lt;textarea-alphanumeric&gt;</option>
								<option value="textarea-letter">&lt;textarea-letter&gt;</option>
								<option value="textarea-letternumber" selected="selected">&lt;textarea-letternumber&gt;</option>
								<option value="textarea-phone">&lt;textarea-phone&gt;</option>
								<?php elseif ($tipo == 'char') : ?>
								<option value="text-alphanumeric" selected="selected">&lt;text-alphanumeric&gt;</option>
								<option value="text-number">&lt;text-number&gt;</option>
								<?php elseif ($tipo == 'date') : ?>
								<option value="text-datemask">&lt;text-datemask&gt;</option>
								<option value="text-datepicker" selected="selected">&lt;text-datepicker&gt;</option>
								<?php elseif ($tipo == 'time') : ?>
								<option value="text-timemask" selected="selected">&lt;text-timemask&gt;</option>
								<?php elseif ($tipo == 'datetime') : ?>
								<option value="text-datetimemask" selected="selected">&lt;text-datetimemask&gt;</option>
								<?php elseif ($tipo == 'year') : ?>
								<option value="text-yearmask" selected="selected">&lt;text-yearmask&gt;</option>
								<?php elseif ($tipo == 'enum') : ?>
								<option value="select-collection" selected="selected">&lt;select-collection&gt;</option>
								<option value="radio-collection">&lt;radio-collection&gt;</option>
								<?php else : ?>
								<option value="text-address">&lt;text-address&gt;</option>
								<option value="text-all">&lt;text-all&gt;</option>
								<option value="text-alphanumeric">&lt;text-alphanumeric&gt;</option>
								<option value="text-email">&lt;text-email&gt;</option>
								<option value="text-float">&lt;text-float&gt;</option>
								<option value="text-letter">&lt;text-letter&gt;</option>
								<option value="text-letternumber" selected="selected">&lt;text-letternumber&gt;</option>
								<option value="text-number">&lt;text-number&gt;</option>
								<option value="text-phone">&lt;text-phone&gt;</option>
								<option value="text-regexp">&lt;text-regexp&gt;</option>
								<option value="text-url">&lt;text-url&gt;</option>
								<option value="textarea-address">&lt;textarea-address&gt;</option>
								<option value="textarea-all">&lt;textarea-all&gt;</option>
								<option value="textarea-alphanumeric">&lt;textarea-alphanumeric&gt;</option>
								<option value="textarea-letter">&lt;textarea-letter&gt;</option>
								<option value="textarea-letternumber">&lt;textarea-letternumber&gt;</option>
								<option value="textarea-phone">&lt;textarea-phone&gt;</option>
								<?php endif ?>
							</select>
							<?php endif ?>
						</td>
						<td>
							<?php if ($campo['Extra'] != 'auto_increment') : ?>
							<select name="tablas[<?= $campo['Field']; ?>]" class="form-control" style="display: none;" data-validation="required">
								<option value="" selected="selected">Seleccionar</option>
								<?php foreach ($tablas as $nro => $elemento) : ?>
								<option value="<?= $elemento; ?>"><?= substr($elemento, 4); ?></option>
								<?php endforeach ?>
							</select>
							<?php endif ?>
						</td>
						<td></td>
						<td></td>
						<td>
							<?php if ($campo['Extra'] != 'auto_increment') : ?>
							<div class="text-label text-nowrap">
								<label class="radio-inline">
									<input type="radio" value="required" name="validaciones[<?= $campo['Field']; ?>]" checked="checked"> Obligatorio
								</label>
								<label class="radio-inline">
									<input type="radio" value="" name="validaciones[<?= $campo['Field']; ?>]"> Opcional
								</label>
							</div>
							<?php endif ?>
						</td>
						<td>
							<?php if ($campo['Extra'] != 'auto_increment') : ?>
							<button type="button" class="btn btn-danger" data-toggle="tooltip" data-title="Eliminar campo" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span></button>
							<?php endif ?>
						</td>
					</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<p><strong>Personalización de la sintaxis:</strong></p>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="tabla_plural" class="control-label">Variable en plural de la cadena:</label>
					<input type="text" value="<?= substr($tabla, 4); ?>" name="tabla_plural" id="tabla_plural" class="form-control" data-validation="required alphanumeric" data-validation-allowing="_">
					<input type="text" value="<?= $tabla ?>" name="tabla" id="tabla" class="translate" tabindex="-1" data-validation="required" data-validation-error-msg="El campo no es válido">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="alias_plural" class="control-label">Texto en plural de la cadena:</label>
					<input type="text" value="<?= str_replace('_', ' ', substr($tabla, 4)); ?>" name="alias_plural" id="alias_plural" class="form-control" data-validation="required letternumber" data-validation-allowing=" ">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="tabla_singular" class="control-label">Variable en singular de la cadena:</label>
					<input type="text" value="<?= substr($tabla, 4); ?>" name="tabla_singular" id="tabla_singular" class="form-control" data-validation="required alphanumeric" data-validation-allowing="_">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="alias_singular" class="control-label">Texto en singular de la cadena:</label>
					<input type="text" value="<?= str_replace('_', ' ', substr($tabla, 4)); ?>" name="alias_singular" id="alias_singular" class="form-control" data-validation="required letternumber" data-validation-allowing=" ">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="sintaxis" class="control-label">Sintaxis de escritura:</label>
					<div class="radio">
						<label>
							<input type="radio" value="masculino" name="sintaxis" id="sintaxis" checked="checked">
							<span>Escritura del texto en formato masculino</span>
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" value="femenino" name="sintaxis">
							<span>Escritura del texto en formato femenino</span>
						</label>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="archivos" class="control-label">Archivos a generar:</label>
					<div class="checkbox">
						<label>
							<input type="checkbox" value="listar" name="archivos[]" id="archivos" checked="checked" data-validation="checkbox_group" data-validation-qty="min1">
							<span>listar.php</span>
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" value="crear" name="archivos[]" checked="checked">
							<span>crear.php</span>
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" value="modificar" name="archivos[]" checked="checked">
							<span>modificar.php</span>
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" value="ver" name="archivos[]" checked="checked">
							<span>ver.php</span>
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" value="eliminar" name="archivos[]" checked="checked">
							<span>eliminar.php</span>
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" value="imprimir" name="archivos[]" checked="checked">
							<span>imprimir.php</span>
						</label>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 col-sm-offset-6">
				<button type="submit" class="btn btn-danger">
					<span class="glyphicon glyphicon-fire"></span>
					<span>Generar</span>
				</button>
				<button type="reset" class="btn btn-default">
					<span class="glyphicon glyphicon-refresh"></span>
					<span>Restablecer</span>
				</button>
			</div>
		</div>
	</form>
</div>
<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/RowSorter.min.js"></script>
<script>
$(function () {
	$.validate({
		modules: 'basic'
	});

	$('#table').rowSorter({
		handler: '.table-index',
		dragClass: 'info'
	});

	$('[name*=formularios]').on('change', function () {
		var $this, $tabla;
		$this = $(this);
		$tabla = $this.parent().next().find('[name*=tablas]:first');
		if ($this.val() == 'select-table') {
			$tabla.show();
			$tabla.next().show();
		} else {
			$tabla.val('').trigger('change');
			$tabla.hide();
			$tabla.next().hide();
		}
	});

	$('[name*=tablas]').on('change', function () {
		var $this, $clave, $valor, campos, llave;
		$this = $(this);
		campos = $('#' + $this.val()).html();
		$clave = $this.parent().next();
		$valor = $this.parent().next().next();
		llave = $this.attr('name');
		llave = llave.split('[')[1];
		llave = llave.split(']')[0];
		if ($this.val() == '') {
			$clave.html('');
			$valor.html('');
		} else {
			$clave.html('<select name="claves[' + llave + ']" class="form-control" data-validation="required">' + campos + '</select>');
			$valor.html('<select name="valores[' + llave + ']" class="form-control" data-validation="required">' + campos + '</select>');
		}
	});

	$('[data-eliminar]').on('click', function () {
		var $boton = $(this);
		bootbox.confirm('Esta seguro que desea eliminar el campo?', function (respuesta) {
			if (respuesta) {
				if ($('[data-eliminar]').size() > 1) {
					$boton.parent().parent().remove();
				} else {
					bootbox.alert('Debe existir al menos un campo, en consecuencia no se pueden eliminar más campos.');
				}
			}
		});
	});
	
	$('form:first').on('reset', function (e) {
		e.preventDefault();
		window.location.reload();
	});
});
</script>
<?php require_once show_template('footer-full'); ?>