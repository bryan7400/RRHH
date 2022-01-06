<?php

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los profesor_materia
$profesor_materia = $db->select('z.*, a.nombre_materia as materia')->from('vista_profesor_materia z')->join('pro_materia a', 'z.materia_id = a.id_materia', 'left')->order_by('z.id_profesor_materia', 'asc')->fetch();

// Obtiene los permisos
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);

?>
<?php require_once show_template('header-design'); ?>
<div class="panel-heading">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>A profesor materia</strong>
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
					<li><a href="?/profesor_materia/crear"><span class="glyphicon glyphicon-plus"></span> Crear a profesor materia</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/profesor_materia/imprimir" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir a profesor materia</a></li>
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
	<?php if ($profesor_materia) : ?>
	<table id="table" class="table table-bordered table-condensed table-striped table-hover">
		<thead>
			<tr class="active">
				<th class="text-nowrap">Id profesor materia</th>
				<th class="text-nowrap">Id profesor</th>
				<th class="text-nowrap">Codigo profesor</th>
				<th class="text-nowrap">Nombre profesor</th>
				<th class="text-nowrap">Materia</th>
				<th class="text-nowrap">Nombre materia</th>
				<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
				<th class="text-nowrap">Opciones</th>
				<?php endif ?>
			</tr>
		</thead>
		<tfoot>
			<tr class="active">
				<th class="text-nowrap text-middle">Id profesor materia</th>
				<th class="text-nowrap text-middle">Id profesor</th>
				<th class="text-nowrap text-middle">Codigo profesor</th>
				<th class="text-nowrap text-middle">Nombre profesor</th>
				<th class="text-nowrap text-middle">Materia</th>
				<th class="text-nowrap text-middle">Nombre materia</th>
				<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
				<th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
				<?php endif ?>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($profesor_materia as $nro => $profesor_materia) : ?>
			<tr>
				<th class="text-nowrap"><?= $nro + 1; ?></th>
				<td class="text-nowrap"><?= escape($profesor_materia['id_profesor']); ?></td>
				<td class="text-nowrap"><?= escape($profesor_materia['codigo_profesor']); ?></td>
				<td class="text-nowrap"><?= escape($profesor_materia['nombre_profesor']); ?></td>
				<td class="text-nowrap"><?= escape($profesor_materia['materia']); ?></td>
				<td class="text-nowrap"><?= escape($profesor_materia['nombre_materia']); ?></td>
				<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
				<td class="text-nowrap">
					<?php if ($permiso_ver) : ?>
					<a href="?/profesor_materia/ver/<?= $profesor_materia['id_profesor_materia']; ?>" data-toggle="tooltip" data-title="Ver a profesor materia"><span class="glyphicon glyphicon-search"></span></a>
					<?php endif ?>
					<?php if ($permiso_modificar) : ?>
					<a href="?/profesor_materia/modificar/<?= $profesor_materia['id_profesor_materia']; ?>" data-toggle="tooltip" data-title="Modificar a profesor materia"><span class="glyphicon glyphicon-edit"></span></a>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<a href="?/profesor_materia/eliminar/<?= $profesor_materia['id_profesor_materia']; ?>" data-toggle="tooltip" data-title="Eliminar a profesor materia" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span></a>
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
			<li>No existen a profesor materia registrados en la base de datos.</li>
			<li>Para crear nuevos a profesor materia debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
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
					window.location = '?/profesor_materia/crear';
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
		bootbox.confirm('¿Está seguro que desea eliminar el a profesor materia?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
	
	<?php if ($profesor_materia) : ?>
	$('#table').DataFilter({
		filter: true,
		name: 'profesor_materia',
		reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-design'); ?>