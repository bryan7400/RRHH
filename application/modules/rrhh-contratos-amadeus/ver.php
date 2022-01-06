<?php

// Obtiene los parametros
$id_contrato = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene el contratos
$contratos = $db->select('z.*')->from('rhh_contratos z')->where('z.id_contrato', $id_contrato)->fetch_first();

// Ejecuta un error 404 si no existe el contratos
if (!$contratos) { require_once not_found(); exit; }

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
		<strong>Ver contratos</strong>
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
					<li><a href="?/contratos/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar contratos</a></li>
					<?php endif ?>
					<?php if ($permiso_crear) : ?>
					<li><a href="?/contratos/crear"><span class="glyphicon glyphicon-plus"></span> Crear contratos</a></li>
					<?php endif ?>
					<?php if ($permiso_modificar) : ?>
					<li><a href="?/contratos/modificar/<?= $id_contrato; ?>"><span class="glyphicon glyphicon-edit"></span> Modificar contratos</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<li><a href="?/contratos/eliminar/<?= $id_contrato; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar contratos</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/contratos/imprimir/<?= $id_contrato; ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir contratos</a></li>
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
					<label class="control-label">Tipo_contrato:</label>
					<p class="form-control-static"><?= escape($contratos['tipo_contrato_id']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Horario:</label>
					<p class="form-control-static"><?= escape($contratos['horario']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Cargo:</label>
					<p class="form-control-static"><?= escape($contratos['cargo_id']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Sueldo base:</label>
					<p class="form-control-static"><?= escape($contratos['sueldo_base']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Fecha inicio:</label>
					<p class="form-control-static"><?= date_decode($contratos['fecha_inicio'], $_format); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Fecha final:</label>
					<p class="form-control-static"><?= escape($contratos['fecha_final']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Forma pago:</label>
					<p class="form-control-static"><?= escape($contratos['forma_pago']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Entidad_financiera:</label>
					<p class="form-control-static"><?= escape($contratos['entidad_financiera_id']); ?></p>
				</div>
				<div class="form-group">
					<label class="control-label">Concepto_pago:</label>
					<p class="form-control-static"><?= escape($contratos['concepto_pago_id']); ?></p>
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
					window.location = '?/contratos/crear';
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
		bootbox.confirm('¿Está seguro que desea eliminar el contratos?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-full'); ?>