<?php

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);

?>
<?php require_once show_template('header-full'); ?>
<div class="panel-heading">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Crear módulo</strong>
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
				<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
					<span class="glyphicon glyphicon-menu-hamburger"></span>
					<span class="hidden-xs">Acciones</span>
				</button>
				<ul class="dropdown-menu dropdown-menu-right">
					<li class="dropdown-header visible-xs-block">Seleccionar acción</li>
					<li><a href="?/modulos/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar modulos</a></li>
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
					<input type="text" value="" name="modulo" id="modulo" class="form-control" autofocus="autofocus" data-validation="required letternumber length" data-validation-allowing="-+/.,:;@#&'()_\n " data-validation-length="max200">
				</div>
				<div class="form-group">
					<label for="visible" class="control-label">Visible:</label>
					<div class="radio">
						<label>
							<input type="radio" value="s" name="visible" id="visible" checked="checked">
							<span>s</span>
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" value="n" name="visible">
							<span>n</span>
						</label>
					</div>
				</div>
				<div class="form-group">
					<label for="descripcion" class="control-label">Descripción:</label>
					<textarea name="descripcion" id="descripcion" class="form-control" data-validation="required letternumber" data-validation-allowing="-+/.,:;@#&'()_\n "></textarea>
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
});
</script>
<?php require_once show_template('footer-full'); ?>