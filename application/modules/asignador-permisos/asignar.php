<?php

// Obtiene los parametros
$id_rol = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene el rol
$rol = $db->from('sys_roles')->where('id_rol', $id_rol)->fetch_first();

// Ejecuta un error 404 si no existe el rol
if (!$rol) { require_once not_found(); exit; }

// Obtiene los menus
$menus = $db->select('m.*, p.rol_id, p.archivos')->from('sys_menus m')->join('sys_permisos p', 'm.id_menu = p.menu_id and p.rol_id = ' . $id_rol, 'left')->order_by('m.menu', 'asc')->fetch();

// Ordena el modelo
$menus = ordenar_menu($menus);

// Obtiene los roles
$roles = $db->get('sys_roles');

?>
<?php require_once show_template('header-design'); ?>
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Asignación de Permisos </h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Desarrollo</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Asignador de Permisos</a></li>
                        <!-- <li class="breadcrumb-item active" aria-current="page">Listar</li> -->
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- end pageheader -->
<!-- ============================================================== -->
<div class="row">
    <!-- ============================================================== -->
    <!-- row -->
    <!-- ============================================================== -->
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <!-- <h5 class="card-header">Generador de menús</h5> -->
            <div class="card-header">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                        <div class="text-label hidden-xs">Seleccione el rol al que desea asignarle los permisos:</div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
                        <div class="btn-group">
                             <div class="input-group">
                                <div class="input-group-append be-addon">
                                    <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item">Seleccionar acción</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="?/permisos/listar"><span class="glyphicon glyphicon-list"></span> Listar roles</a>
										
										<?php foreach ($roles as $nro => $elemento) : ?>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="?/permisos/asignar/<?= $elemento['id_rol']; ?>"><span class="glyphicon glyphicon-star"></span> Asignar a <span class="text-lowercase"><?= escape($elemento['rol']); ?></span></a>
										<?php endforeach ?>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <!-- ============================================================== -->
                <!-- datos --> 
                <!-- ============================================================== -->
              
                <?php if ($message = get_notification()) : ?>
                <div class="alert alert-<?= $message['type']; ?>">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong><?= $message['title']; ?></strong>
                    <p><?= $message['content']; ?></p>
                </div>
                <?php endif ?>

				<?php if ($menus) : ?>
				<form method="post" action="?/asignador-permisos/guardar" autocomplete="off">
					<input type="hidden" name="<?= $csrf; ?>">
					<input type="hidden" value="<?= $id_rol; ?>" name="id_rol">
					<div>
						<table id="table" class="table table-bordered table-condensed table-striped table-hover">
							<thead>
								<tr class="active">
									<th>Menú</th>
									<th>Estado</th>
									<th>Permisos</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($menus as $menu) { ?>
								<tr class="treegrid-<?= $menu['id_menu']; ?> <?= ($menu['antecesor_id'] == 0) ? '' : 'treegrid-parent-' . $menu['antecesor_id']; ?>">
									<td class="text-nowrap text-middle">
										<span class="<?= $menu['icono']; ?>"></span>
										<span><?= escape($menu['menu']); ?></span>
									</td>
									<td class="text-middle text-center">
										<?php if ($menu['rol_id'] != null) : ?>
										<input type="checkbox" value="<?= $menu['id_menu']; ?>" name="estados[<?= $menu['id_menu']; ?>]" checked="checked" data-indice="<?= $menu['id_menu']; ?>" data-antecesor="<?= $menu['antecesor_id']; ?>">
										<?php else : ?>
										<input type="checkbox" value="<?= $menu['id_menu']; ?>" name="estados[<?= $menu['id_menu']; ?>]" data-indice="<?= $menu['id_menu']; ?>" data-antecesor="<?= $menu['antecesor_id']; ?>">
										<?php endif ?>
									</td>
									<td>
										<?php $files = (get_files($_modules . '/' . $menu['modulo']) == array()) ? '' : implode(', ', get_files($_modules . '/' . $menu['modulo'])); ?>
										<?php if ($menu['antecesor'] == 1 || $files == '') : ?>
										<input type="text" value="" name="archivos[<?= $menu['id_menu']; ?>]" class="form-control" tabindex="-1" readonly>
										<?php else : ?>
										<input type="text" value="<?= $menu['archivos']; ?>" name="archivos[<?= $menu['id_menu']; ?>]" class="form-control paste">
										<small><span class="text-danger">[<?= escape($menu['modulo']); ?>]</span> disponibles &mdash; <span class="text-success"><?= $files; ?></span></small>
										<?php endif ?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>

					<div class="row">
                    	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                        	<div class="form-group text-center">
								<button type="submit" class="btn btn-primary">
									<i class="icon-check"></i>
									<span>Guardar</span>
								</button>
								<button type="reset" class="btn btn-light">
									<i class="icon-arrow-left-circle"></i>
									<span>Restablecer</span>
								</button>
							</div>
						</div>
                    </div>

				</form>
				<?php else : ?>
				<div class="alert alert-info">
					<strong>Atención!</strong>
					<ul>
						<li>No existen menús registrados en la base de datos.</li>
						<li>En consecuencia no puede realizar la asignación de permisos.</li>
					</ul>
				</div>
				<?php endif ?>
                <!-- ============================================================== -->
                <!-- end datos -->
                <!-- ============================================================== -->
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- end row -->
<!-- ============================================================== --> 
<script src="<?= js; ?>/jquery.treegrid.min.js"></script>
<script src="<?= js; ?>/treegrid.bootstrap3.min.js"></script>
<script src="<?= js; ?>/jquery.dataTables.min.js"></script>
<script src="<?= js; ?>/dataTables.bootstrap.min.js"></script>
<script>
$(function () {
	<?php if ($menus) : ?>
	var $table = $('#table');

	$(':checkbox').on('change', function () {
		$this = $(this);
		$('[data-antecesor=' + $this.val() + ']').prop('checked', $this.is(':checked'));
	});

	$('.paste').on('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'r':
					e.preventDefault();
					var $this = $(this);
					var text = $this.next().find('span:last').text();
					text = text.replace(new RegExp('\\s+', 'g'), '');
					$this.val(text);
				break;
			}
		}
	});

	$table.treegrid().dataTable({
		sort: false,
		paging: false,
		info: false,
		stateSave: false
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-design'); ?>