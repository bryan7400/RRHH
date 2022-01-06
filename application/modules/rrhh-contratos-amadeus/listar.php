<?php

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los contratos
$contratos = $db->select('z.*')->from('rhh_contratos z')->order_by('z.id_contrato', 'asc')->fetch();

// Obtiene los permisos
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);

?>
<?php require_once show_template('header-full'); ?>
<div class="panel-heading">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Contratos</strong>
	</h3>
</div>
<div class="panel-body">
	<?php if ($permiso_crear || $permiso_imprimir) : ?>
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
					<?php if ($permiso_crear) : ?>
					<li><a href="?/contratos/crear"><span class="glyphicon glyphicon-plus"></span> Crear contratos</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/contratos/imprimir" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir contratos</a></li>
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
	<?php if ($contratos) : ?>
	<table id="table" class="table table-bordered table-condensed table-striped table-hover">
		<thead>
			<tr class="active">
				<th class="text-nowrap">#</th>
				<th class="text-nowrap">Tipo_contrato</th>
				<th class="text-nowrap">Horario</th>
				<th class="text-nowrap">Cargo</th>
				<th class="text-nowrap">Sueldo base</th>
				<th class="text-nowrap">Fecha inicio</th>
				<th class="text-nowrap">Fecha final</th>
				<th class="text-nowrap">Forma pago</th>
				<th class="text-nowrap">Entidad_financiera</th>
				<th class="text-nowrap">Concepto_pago</th>
				<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
				<th class="text-nowrap">Opciones</th>
				<?php endif ?>
			</tr>
		</thead>
		<tfoot>
			<tr class="active">
				<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
				<th class="text-nowrap text-middle">Tipo_contrato</th>
				<th class="text-nowrap text-middle">Horario</th>
				<th class="text-nowrap text-middle">Cargo</th>
				<th class="text-nowrap text-middle">Sueldo base</th>
				<th class="text-nowrap text-middle">Fecha inicio</th>
				<th class="text-nowrap text-middle">Fecha final</th>
				<th class="text-nowrap text-middle">Forma pago</th>
				<th class="text-nowrap text-middle">Entidad_financiera</th>
				<th class="text-nowrap text-middle">Concepto_pago</th>
				<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
				<th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
				<?php endif ?>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($contratos as $nro => $contratos) : ?>
			<tr>
				<th class="text-nowrap"><?= $nro + 1; ?></th>
				<td class="text-nowrap"><?= escape($contratos['tipo_contrato_id']); ?></td>
				<td class="text-nowrap"><?= escape($contratos['horario']); ?></td>
				<td class="text-nowrap"><?= escape($contratos['cargo_id']); ?></td>
				<td class="text-nowrap"><?= escape($contratos['sueldo_base']); ?></td>
				<td class="text-nowrap"><?= date_decode($contratos['fecha_inicio'], $_format); ?></td>
				<td class="text-nowrap"><?= escape($contratos['fecha_final']); ?></td>
				<td class="text-nowrap"><?= escape($contratos['forma_pago']); ?></td>
				<td class="text-nowrap"><?= escape($contratos['entidad_financiera_id']); ?></td>
				<td class="text-nowrap"><?= escape($contratos['concepto_pago_id']); ?></td>
				<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
				<td class="text-nowrap">
					<?php if ($permiso_ver) : ?>
					<a href="?/contratos/ver/<?= $contratos['id_contrato']; ?>" data-toggle="tooltip" data-title="Ver contratos"><span class="glyphicon glyphicon-search"></span></a>
					<?php endif ?>
					<?php if ($permiso_modificar) : ?>
					<a href="?/contratos/modificar/<?= $contratos['id_contrato']; ?>" data-toggle="tooltip" data-title="Modificar contratos"><span class="glyphicon glyphicon-edit"></span></a>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<a href="?/contratos/eliminar/<?= $contratos['id_contrato']; ?>" data-toggle="tooltip" data-title="Eliminar contratos" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span></a>
					<?php endif ?>
				</td>
				<?php endif ?>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	<?php else : ?>
	<div class="alert alert-info">
		<strong>Atención!</strong>
		<ul>
			<li>No existen contratos registrados en la base de datos.</li>
			<li>Para crear nuevos contratos debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
		</ul>
	</div>
	<?php endif ?>
</div>
<script src="<?= js; ?>/jquery.dataTables.min.js"></script>
<script src="<?= js; ?>/dataTables.bootstrap.min.js"></script>
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<script src="<?= js; ?>/jquery.dataFilters.min.js"></script>
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
	
	<?php if ($contratos) : ?>
	$('#table').DataFilter({
		filter: true,
		name: 'contratos',
		reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-full'); ?>