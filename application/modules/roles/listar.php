<?php 

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los roles
$roles = $db->query("SELECT * FROM sys_roles WHERE estado = 'A'")->fetch();

// Obtiene los permisos
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);

?>
<?php require_once show_template('header-design'); ?>

<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"> 
        <div class="page-header">
            <h2 class="pageheader-title">Roles</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Administración</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Roles</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Listar</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- end pageheader -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!-- row -->
<!-- ============================================================== -->
<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
						<div class="text-label hidden-xs">Seleccione:</div>
					</div>
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
						<div class="btn-group">
								<div class="input-group">
								<div class="input-group-append be-addon">
									<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
									<div class="dropdown-menu">
										<a class="dropdown-item">Seleccionar acción</a>
										<?php if ($permiso_crear) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/roles/crear"" class="dropdown-item">Crear Rol</a>
										<?php endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/roles/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Roles</a>
										<?php endif ?>
									</div>
								</div>
							</div>
						</div> 
					</div>
				</div>
			</div>
			<!-- ============================================================== -->
			<!-- datos --> 
			<!-- ============================================================== -->
			<div class="card-body">
				<?php if ($message = get_notification()) : ?>
				<div class="alert alert-<?= $message['type']; ?>">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong><?= $message['title']; ?></strong>
					<p><?= $message['content']; ?></p>
				</div>
				<?php endif ?>
				<?php if ($roles) : ?>
				<table id="table" class="table table-bordered table-condensed table-striped table-hover">
					<thead>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<th class="text-nowrap">Rol</th>
							<th class="text-nowrap">Descripción</th>
							<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
							<th class="text-nowrap text-center">Opciones</th>
							<?php endif ?>
						</tr>
					</thead>
					<tfoot>
						<tr class="active hidden">
							<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
							<th class="text-nowrap text-middle">Rol</th>
							<th class="text-nowrap text-middle">Descripción</th>
							<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
							<th class="text-nowrap text-middle text-center" data-datafilter-filter="false">Opciones</th>
							<?php endif ?>
						</tr>
					</tfoot>
					<tbody>
						<?php foreach ($roles as $nro => $rol) : ?>
						<tr>
							<th class="text-nowrap"><?= $nro + 1; ?></th>
							<td class="text-nowrap"><?= escape($rol['rol']); ?></td>
							<td class="text-nowrap"><?= escape($rol['descripcion']); ?></td>
							<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
							<td class="text-nowrap text-center">
								<?php if ($permiso_ver) : ?>
								<!--<a href="?/roles/ver/<?= $rol['id_rol']; ?>"  data-toggle="tooltip" data-title="Ver rol"  class="btn btn-info btn-sm"><span class="icon-magnifier"></span></a>-->
								<?php endif ?>
								<?php if ($permiso_modificar) : ?>
								<a href="?/roles/modificar/<?= $rol['id_rol']; ?>" data-toggle="tooltip" data-title="Modificar rol" style="color:white" class="btn btn-warning btn-sm"><span class="icon-note"></span></a>
								<?php endif ?>
								<?php if ($permiso_eliminar) : ?>
								<a href="?/roles/eliminar/<?= $rol['id_rol']; ?>" data-toggle="tooltip" data-title="Eliminar rol" data-eliminar="true" class="btn btn-danger btn-sm"><span class="icon-trash"></span></a>
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
						<li>No existen roles registrados en la base de datos.</li>
						<li>Para crear nuevos roles debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
					</ul>
				</div>
				<?php endif ?>
			</div>
			<!-- ============================================================== -->
			<!-- end datos -->
			<!-- ============================================================== -->
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- row -->
<!-- ============================================================== -->
<!-- <script src="<?= js; ?>/jquery.dataTables.min.js"></script>
 <script src="<?= js; ?>/dataTables.bootstrap.min.js"></script> -->
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
					window.location = '?/roles/crear';
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
		bootbox.confirm('¿Está seguro que desea eliminar el rol?', function (result) {
			if (result) {
				$.get(href, function(data,status){
				    
				    location.href ='?/roles/listar';
				});
			}
		});
	});
	<?php endif ?>
	
	<?php if ($roles) : ?>
/*	$('#table').DataFilter({
		name: 'roles',
		reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
	});*/
	var dataTable = $('#table').DataTable({
	language: dataTableTraduccion,
	searching: true,
	paging:true,
	"lengthChange": true,
	"responsive": true
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-design'); ?>