<?php
// Obtiene la cadena csrf
$csrf = set_csrf();

//ruta estatica del js
$ruta ="application/modules/s-gestion-escolar";
// Obtiene los gestiones
$gestiones = $db->select('z.*')->from('ins_gestion z')->order_by('z.id_gestion', 'asc')->fetch();

// Obtiene los permisos
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_modificar = in_array('editar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);
?>
<?php require_once show_template('header-full'); ?>
<div class="panel-heading">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Gestiones</strong>
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
					<li><a href="?/s-gestion-escolar/crear"><span class="glyphicon glyphicon-plus"></span> Crear gestion</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/s-gestion-escolar/imprimir" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir gestiones</a></li>
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
	<?php if ($gestiones) : ?>
	<table id="table" class="table table-bordered table-condensed table-striped table-hover">
		<thead>
			<tr class="active">
				<th class="text-nowrap">#</th>
				<th class="text-nowrap">Gestion</th>
				<th class="text-nowrap">Inicio gestion</th>
				<th class="text-nowrap">Final gestion</th>
				<th class="text-nowrap">Inicio vacaciones</th>
				<th class="text-nowrap">Final vacaciones</th>
				<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
				<th class="text-nowrap">Opciones</th>
				<?php endif ?>
			</tr>
		</thead>
		<tfoot>
			<tr class="active">
				<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
				<th class="text-nowrap text-middle">Gestion</th>
				<th class="text-nowrap text-middle">Inicio gestion</th>
				<th class="text-nowrap text-middle">Final gestion</th>
				<th class="text-nowrap text-middle">Inicio vacaciones</th>
				<th class="text-nowrap text-middle">Final vacaciones</th>
				<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
				<th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
				<?php endif ?>
			</tr>
		</tfoot>
		<?php $prueba_php=11; ?>
		<tbody>
			<?php foreach ($gestiones as $nro => $gestion) : ?>
			<tr>
				<th class="text-nowrap"><?= $nro + 1; ?></th>
				<td class="text-nowrap"><?= escape($gestion['gestion']); ?></td>
				<td class="text-nowrap"><?= date_decode($gestion['inicio_gestion'], $_format); ?></td>
				<td class="text-nowrap"><?= date_decode($gestion['final_gestion'], $_format); ?></td>
				<td class="text-nowrap"><?= date_decode($gestion['inicio_vacaciones'], $_format); ?></td>
				<td class="text-nowrap"><?= date_decode($gestion['final_vacaciones'], $_format); ?></td>
				<?php
					$contenido = $gestion['id_gestion']. "*" . $gestion['gestion']. "*" . $gestion['inicio_gestion']. "*" . $gestion['final_gestion']. "*" . $gestion['inicio_vacaciones']. "*" . $gestion['final_vacaciones'];
				?>
				<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
				<td class="text-nowrap">
					<?php if ($permiso_ver) : ?>
					<a href="?/gestiones/ver/<?= $gestion['id_gestion']; ?>" data-toggle="tooltip" data-title="Ver gestion"><span class="glyphicon glyphicon-search"></span></a>
					<?php endif ?>
					<?php if ($permiso_modificar) : ?>
					<a href="#" onclick="abrir_editar('<?= $contenido;?>')" data-title="Modificar gestion"><span class="glyphicon glyphicon-edit"></span></a>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<a href="?/gestiones/eliminar/<?= $gestion['id_gestion']; ?>" data-toggle="tooltip" data-title="Eliminar gestion" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span></a>
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
			<li>No existen gestiones registrados en la base de datos.</li>
			<li>Para crear nuevos gestiones debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
		</ul>
	</div>
	<?php endif ?>

	<input type="text" id="asd">
	
</div>

<script src="<?= js; ?>/jquery.dataTables.min.js"></script>
<script src="<?= js; ?>/dataTables.bootstrap.min.js"></script>
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<script src="<?= js; ?>/jquery.dataFilters.min.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/educheck.js"></script>
<script src="<?= $ruta?>/s-gestion-escolar.js"></script>
<?php require_once show_template('footer-full'); ?>
<?php 
	if($permiso_modificar){
		require_once ("editar.php");
	}
?>
<script>
$(function () {
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/gestiones/crear';
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
		bootbox.confirm('¿Está seguro que desea eliminar el gestion?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
	
	<?php if ($gestiones) : ?>
	$('#table').DataFilter({
		filter: true,
		name: 'gestiones',
		reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
	});
	<?php endif ?>
});
<?php if ($permiso_modificar) : ?>
let prueba=<?= $prueba_php; ?>;
function abrir_editar(contenido){
	$("#modal_gestion").modal("show");
	var d = contenido.split("*");
	console.log(d[4]);
	$("#id_gestion").val(d[0]);
	$("#nombre_gestion").val(d[1]);
	$("#inicio_gestion").val(moment(d[2]).format('YYYY-MM-DD'));
	$("#final_gestion").val(moment(d[3]).format('YYYY-MM-DD'));
	$("#inicio_vacaciones").val(moment(d[4]).format('YYYY-MM-DD'));
	$("#final_vacaciones").val(moment(d[5]).format('YYYY-MM-DD'));
	$("#btn_nuevo").hide(); 
}
<?php endif ?>

</script>
