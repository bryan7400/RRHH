<?php

// Obtiene los parametros
$id_comunidado = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene el comunidados
$comunidados = $db->select('z.*')->from('ins_comunidados z')->where('z.id_comunidado', $id_comunidado)->fetch_first();

// Ejecuta un error 404 si no existe el comunidados
if (!$comunidados) { require_once not_found(); exit; }

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
		<strong>Ver comunidados</strong>
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
					<li><a href="?/comunidados/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar comunidados</a></li>
					<?php endif ?>
					<?php if ($permiso_crear) : ?>
					<li><a href="?/comunidados/crear"><span class="glyphicon glyphicon-plus"></span> Crear comunidados</a></li>
					<?php endif ?>
					<?php if ($permiso_modificar) : ?>
					<li><a href="?/comunidados/modificar/<?= $id_comunidado; ?>"><span class="glyphicon glyphicon-edit"></span> Modificar comunidados</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<li><a href="?/comunidados/eliminar/<?= $id_comunidado; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar comunidados</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/comunidados/imprimir/<?= $id_comunidado; ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir comunidados</a></li>
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
					<label class="control-label">Fecha inicio:</label>
					<p class="form-control-static"><?= escape($comunidados['fecha_inicio']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Fecha final:</label>
					<p class="form-control-static"><?= escape($comunidados['fecha_final']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Nombre evento:</label>
					<p class="form-control-static"><?= escape($comunidados['nombre_evento']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Descripcion:</label>
					<p class="form-control-static"><?= escape($comunidados['descripcion']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Color:</label>
					<p class="form-control-static"><?= escape($comunidados['color']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Usuarios:</label>
					<p class="form-control-static"><?= escape($comunidados['usuarios']); ?></p>
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
					window.location = '?/comunidados/crear';
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
		bootbox.confirm('¿Está seguro que desea eliminar el comunidados?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-full'); ?>