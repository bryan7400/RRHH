<?php

// Obtiene los formatos
$formato_textual = get_date_textual($_format); 
$formato_numeral = get_date_numeral($_format);

// Obtiene el modelo sucursales
$sucursales = $db->from('sys_instituciones')->fetch();

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);

?>
<?php require_once show_template('header-design'); ?>
<div class="panel-heading">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Crear dosificación</strong>
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
					<li><a href="?/dosificaciones/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar dosificaciones</a></li>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	<?php endif ?>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<form method="post" action="?/dosificaciones/guardar" autocomplete="off">
				<div class="form-group">
					<label for="nro_tramite" class="control-label">Número de trámite:</label>
					<input type="text" value="" name="nro_tramite" id="nro_tramite" class="form-control" data-validation="required number length" data-validation-length="max50">
				</div>
				<div class="form-group">
					<label for="nro_autorizacion_confirmation" class="control-label">Número de autorización:</label>
					<input type="text" value="" name="nro_autorizacion_confirmation" id="nro_autorizacion_confirmation" class="form-control" data-validation="required number length" data-validation-length="max50">
				</div>
				<div class="form-group">
					<label for="nro_autorizacion" class="control-label">Repita número de autorización:</label>
					<input type="text" value="" name="nro_autorizacion" id="nro_autorizacion" class="form-control" data-validation="required number length confirmation" data-validation-length="max50">
				</div>
				<div class="form-group">
					<label for="llave_dosificacion_confirmation" class="control-label">Llave de dosificación:</label>
					<input type="text" value="" name="llave_dosificacion_confirmation" id="llave_dosificacion_confirmation" class="form-control" data-validation="required length" data-validation-length="max100">
				</div>
				<div class="form-group">
					<label for="llave_dosificacion" class="control-label">Repita llave de dosificación:</label>
					<input type="text" value="" name="llave_dosificacion" id="llave_dosificacion" class="form-control" data-validation="required length confirmation" data-validation-length="max100">
				</div>
				<div class="form-group">
					<label for="fecha_limite_confirmation" class="control-label">Fecha límite de emisión:</label>
					<input type="text" value="" name="fecha_limite_confirmation" id="fecha_limite_confirmation" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
				</div>
				<div class="form-group">
					<label for="fecha_limite" class="control-label">Repita fecha límite de emisión:</label>
					<input type="text" value="" name="fecha_limite" id="fecha_limite" class="form-control" data-validation="required date confirmation" data-validation-format="<?= $formato_textual; ?>">
				</div>
				<div class="form-group">
					<label for="leyenda_factura" class="control-label">Leyenda de la factura <font color="red">(Nota.- copiar sin Ley Nº 453: >>configuración por defecto<<):</font></label>
					<input type="text" value="" name="leyenda_factura" id="leyenda_factura" class="form-control" data-validation="required" data-validation-allowing="-/.,:;& ">
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
		modules: 'basic,security'
	});

	$('#fecha_limite_confirmation, #fecha_limite').mask('<?= $formato_numeral; ?>');
});
</script>
<?php require_once show_template('footer-design'); ?>