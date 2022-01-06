<?php

// Obtiene el id_terminal
$id_terminal = (sizeof($_params) > 0) ? $_params[0] : 0;

// Obtiene la terminal
$terminal = $db->select('z.*')->from('inv_terminales z')->where('z.id_terminal', $id_terminal)->fetch_first();

// Verifica si existe la terminal
if (!$terminal) {
	// Error 404
	require_once not_found();
	exit;
}

// Almacena los permisos en variables
$permiso_crear    = in_array('crear', $_views);
$permiso_ver      = in_array('ver', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_listar   = in_array('listar', $_views);

?>
<?php require_once show_template('header-design'); ?>
<div class="panel-heading">
	<h3 class="panel-title">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<b>Modificar terminal</b>
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
			<a href="?/terminales/crear" class="btn btn-success">
				<span class="glyphicon glyphicon-plus"></span>
				<span class="hidden-xs hidden-sm">Nuevo</span>
			</a>
			<?php } ?>
			<?php if ($permiso_ver) { ?>
			<a href="?/terminales/ver/<?= $terminal['id_terminal']; ?>" class="btn btn-warning">
				<span class="glyphicon glyphicon-search"></span>
				<span class="hidden-xs hidden-sm">Ver</span>
			</a>
			<?php } ?>
			<?php if ($permiso_eliminar) { ?>
			<a href="?/terminales/eliminar/<?= $terminal['id_terminal']; ?>" class="btn btn-danger" data-eliminar="true">
				<span class="glyphicon glyphicon-trash"></span>
				<span class="hidden-xs hidden-sm">Eliminar</span>
			</a>
			<?php } ?>
			<?php if ($permiso_listar) { ?>
			<a href="?/terminales/listar" class="btn btn-primary">
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
			<form method="post" action="?/terminales/guardar" class="form-horizontal">
				<div class="form-group">
					<label for="terminal" class="col-md-3 control-label">Terminal:</label>
					<div class="col-md-9">
						<input type="hidden" value="<?= $terminal['id_terminal']; ?>" name="id_terminal" data-validation="required">
						<input type="text" value="<?= $terminal['terminal']; ?>" name="terminal" id="terminal" class="form-control" autocomplete="off" data-validation="required alphanumeric length" data-validation-allowing="-/.()_ " data-validation-length="max100">
					</div>
				</div>
				<div class="form-group">
					<label for="impresora" class="col-md-3 control-label">Impresora:</label>
					<div class="col-md-9">
						<input type="text" value="<?= $terminal['impresora']; ?>" name="impresora" id="impresora" class="form-control" autocomplete="off" data-validation="required letternumber length" data-validation-allowing="-()_ " data-validation-length="max100">
					</div>
				</div>
				<div class="form-group">
					<label for="descripcion" class="col-md-3 control-label">Descripción:</label>
					<div class="col-md-9">
						<textarea name="descripcion" id="descripcion" class="form-control" autocomplete="off" data-validation="letternumber" data-validation-allowing="+-/.,:;@#()_\n " data-validation-optional="true"><?= escape($terminal['descripcion']); ?></textarea>
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
<script>
$(function () {
	$.validate({
		modules: 'basic'
	});
	
	$('.form-control:first').select();
	
	<?php if ($permiso_eliminar) { ?>
	$('[data-eliminar]').on('click', function (e) {
		e.preventDefault();
		var url = $(this).attr('href');
		bootbox.confirm('¿Está seguro que desea eliminar la terminal?', function (result) {
			if(result){
				window.location = url;
			}
		});
	});
	<?php } ?>
});
</script>
<?php require_once show_template('footer-design'); ?>