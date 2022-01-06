<?php

// Obtiene los parametros
$id_area_calificacion = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene el sareacalificacion
$sareacalificacion = $db->select('z.*')->from('cal_area_calificacion z')->where('z.id_area_calificacion', $id_area_calificacion)->fetch_first();

// Ejecuta un error 404 si no existe el sareacalificacion
if (!$sareacalificacion) { require_once not_found(); exit; }

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
		<strong>Ver area calificacion</strong>
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
					<li><a href="?/sareacalificacion/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar area calificacion</a></li>
					<?php endif ?>
					<?php if ($permiso_crear) : ?>
					<li><a href="?/sareacalificacion/crear"><span class="glyphicon glyphicon-plus"></span> Crear area calificacion</a></li>
					<?php endif ?>
					<?php if ($permiso_modificar) : ?>
					<li><a href="?/sareacalificacion/modificar/<?= $id_area_calificacion; ?>"><span class="glyphicon glyphicon-edit"></span> Modificar area calificacion</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<li><a href="?/sareacalificacion/eliminar/<?= $id_area_calificacion; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar area calificacion</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/sareacalificacion/imprimir/<?= $id_area_calificacion; ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir area calificacion</a></li>
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
					<label class="control-label">Descripcion:</label>
					<p class="form-control-static"><?= escape($sareacalificacion['descripcion']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Ponderado:</label>
					<p class="form-control-static"><?= escape($sareacalificacion['ponderado']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Gestion:</label>
					<p class="form-control-static"><?= escape($sareacalificacion['gestion_id']); ?></p>
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
					window.location = '?/sareacalificacion/crear';
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
		bootbox.confirm('¿Está seguro que desea eliminar el area calificacion?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-full'); ?>