<?php

// Obtiene los parametros
$id_tipo_descuento = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene el tipo_descuento
$tipo_descuento = $db->select('z.*')->from('pen_tipo_descuento z')->where('z.id_tipo_descuento', $id_tipo_descuento)->fetch_first();

// Ejecuta un error 404 si no existe el tipo_descuento
if (!$tipo_descuento) { require_once not_found(); exit; }

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_crear = in_array('crear', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);

?>
<?php require_once show_template('header-full'); ?>
<div class="panel-heading">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Ver tipo descuento</strong>
	</h3>
</div>
<div class="panel-body">
	<?php if ($permiso_listar || $permiso_crear || $permiso_modificar || $permiso_eliminar || $permiso_imprimir) : ?>
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
					<li><a href="?/tipo_descuento/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar tipo descuento</a></li>
					<?php endif ?>
					<?php if ($permiso_crear) : ?>
					<li><a href="?/tipo_descuento/crear"><span class="glyphicon glyphicon-plus"></span> Crear tipo descuento</a></li>
					<?php endif ?>
					<?php if ($permiso_modificar) : ?>
					<li><a href="?/tipo_descuento/modificar/<?= $id_tipo_descuento; ?>"><span class="glyphicon glyphicon-edit"></span> Modificar tipo descuento</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<li><a href="?/tipo_descuento/eliminar/<?= $id_tipo_descuento; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar tipo descuento</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/tipo_descuento/imprimir/<?= $id_tipo_descuento; ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir tipo descuento</a></li>
					<?php endif ?>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	<?php endif ?>
	<?php if ($message = get_notification()) : ?>
	<div class="alert alert-<?= $message['type']; ?>">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong><?= $message['title']; ?></strong>
		<p><?= $message['content']; ?></p>
	</div>
	<?php endif ?>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<div class="margin-bottom">
				<div class="form-group">
					<label class="control-label">Tipo descuento:</label>
					<p class="form-control-static"><?= escape($tipo_descuento['tipo_descuento']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Porcentaje:</label>
					<p class="form-control-static"><?= escape($tipo_descuento['porcentaje']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Descuento:</label>
					<p class="form-control-static"><?= ($tipo_descuento['descuento'] != '') ? escape($tipo_descuento['descuento']) : 'No asignado'; ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Gestion:</label>
					<p class="form-control-static"><?= ($tipo_descuento['gestion_id'] != '') ? escape($tipo_descuento['gestion_id']) : 'No asignado'; ?></p>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(function () {
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/tipo_descuento/crear';
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
		bootbox.confirm('¿Está seguro que desea eliminar el tipo descuento?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-full'); ?>