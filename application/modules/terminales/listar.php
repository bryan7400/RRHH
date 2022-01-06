<?php 

// Obtiene las terminales
$terminales = $db->select('z.*')->from('inv_terminales z')->order_by('z.id_terminal')->fetch();

// Almacena los permisos en variables
$permiso_crear     = in_array('crear', $_views);
$permiso_editar    = in_array('editar', $_views);
$permiso_ver       = in_array('ver', $_views);
$permiso_eliminar  = in_array('eliminar', $_views);
$permiso_imprimir  = in_array('imprimir', $_views);
$permiso_descargar = in_array('descargar', $_views);

?>
<?php require_once show_template('header-design'); ?>
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"> 
        <div class="page-header">
            <h2 class="pageheader-title">Terminales</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Inicio de Gestión</a></li>
						<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión Escolar</a></li>
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
				<?php if (($permiso_crear || $permiso_imprimir) && ($permiso_crear || $terminales)): ?>
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
										<a href="?/terminales/crear"class="dropdown-item">Crear terminal</a>
										<?php endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/terminales/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
										<?php endif ?>
									</div>
								</div>
							</div>
						</div> 
					</div>
				</div>
				<?php endif ?>
			</div>
			<!-- ============================================================== -->
			<!-- datos --> 
			<!-- ============================================================== -->
			<div class="card-body">
				<?php if ($terminales): ?>
				<div class="table-responsive">
				<table id="table" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
							<thead>
								<tr class="active">
									<th class="text-nowrap">#</th>
									<th class="text-nowrap">Terminal</th>
									<th class="text-nowrap">Impresora</th>
									<th class="text-nowrap">Descripción</th>
									<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar || $permiso_descargar): ?>
									<th class="text-nowrap">Opciones</th>
									<?php endif ?>
								</tr>
							</thead>
							<tfoot>
								<tr class="active">
									<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
									<th class="text-nowrap text-middle" data-datafilter-filter="true">Terminal</th>
									<th class="text-nowrap text-middle" data-datafilter-filter="true">Impresora</th>
									<th class="text-nowrap text-middle" data-datafilter-filter="true">Descripción</th>
									<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar || $permiso_descargar): ?>
									<th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
									<?php endif ?>
								</tr>
							</tfoot>
							<tbody>
								<?php foreach ($terminales as $nro => $terminal): ?>
								<tr>
									<th class="text-nowrap"><?= $nro + 1; ?></th>
									<td class="text-nowrap"><?= escape($terminal['terminal']); ?></td>
									<td class="text-nowrap"><?= escape($terminal['impresora']); ?></td>
									<td class="text-nowrap"><?= escape($terminal['descripcion']); ?></td>
									<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar || $permiso_descargar): ?>
									<td class="text-nowrap">
										<?php if ($permiso_ver): ?>
											<a href="?/terminales/ver/<?= $terminal['id_terminal']; ?>" data-toggle="tooltip" data-title="Ver terminal" class="btn btn-info btn-xs">
												<span class='icon-eye'></span>
											</a>
										<?php endif ?>

										<?php if ($permiso_editar): ?>
										    <a href="?/terminales/editar/<?= $terminal['id_terminal']; ?>" data-toggle="tooltip" data-title="Modificar terminal" class="btn btn-warning btn-xs">
											<span class='icon-note'></span></a>
										<?php endif ?>
										
										<?php if ($permiso_eliminar): ?>
										    <a href="?/terminales/eliminar/<?= $terminal['id_terminal']; ?>" data-toggle="tooltip" data-title="Eliminar terminal" data-eliminar="true" class="btn btn-danger btn-xs">
											<span class="icon-trash"></span></a>
										<?php endif ?>
										
										<a href="?/terminales/descargar/<?= $terminal['id_terminal']; ?>" data-toggle="tooltip" data-title="Descargar archivo remoto"  class="btn btn-primary btn-xs"><span class="fas fa-arrow-down"></span></a>
										
									</td>
									<?php endif ?>
								</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
				<?php else: ?>
					<div class="alert alert-danger">
						<strong>Advertencia!</strong>
						<p>No existen terminales registradas en la base de datos, para crear nuevas terminales hacer clic en el botón nuevo o presionar las teclas <kbd>alt + n</kbd>.</p>
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
<script src="<?= js; ?>/jquery.dataTables.min.js"></script>
<script src="<?= js; ?>/dataTables.bootstrap.min.js"></script>
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<script src="<?= js; ?>/jquery.dataFilters.min.js"></script>
<script src="<?= js; ?>/FileSaver.min.js"></script>

<script>
$(function () {
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
	
	<?php if ($permiso_crear) { ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/terminales/crear';
				break;
			}
		}
	});
	<?php } ?>
	
	<?php if ($terminales) { ?>
		// var table = $('#table').DataFilter({
		// 	filter: true,
		// 	name: 'terminales',
		// 	reports: 'xls|doc|pdf|html'
		// });

		var dataTable = $('#table').DataTable({
		language: dataTableTraduccion,
		searching: true,
		paging:true,
		"lengthChange": true, 
		"responsive": true
		});
	<?php } ?>
});
</script>
<?php require_once show_template('footer-design'); ?>