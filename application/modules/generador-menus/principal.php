<?php

// Obtiene la cadena csrf 
$csrf = set_csrf();

// Obtiene los menus
$menus = $db->from('sys_menus')->order_by('menu', 'asc')->fetch();

// Ordena los menus
$menus = ordenar_menu($menus);

?>
<?php require_once show_template('header-design'); ?>
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Generador de Menú </h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Desarrollo</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Generador de Menús</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Listar Menú</li>
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
                        <div class="text-label hidden-xs">Seleccionar acción:</div>
                        <!-- <div class="text-label visible-xs-block">Acciones:</div> -->
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
                        <div class="btn-group">
                             <div class="input-group">
                                <div class="input-group-append be-addon">
                                    <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item">Seleccionar acción</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="?/generador-menus/crear" class="dropdown-item">Crear menú</a>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
            <?php if ($menus) : ?>
            <div class="card-body">
                <!-- ============================================================== -->
                <!-- data table  -->
                <!-- ============================================================== -->
              
                <?php if ($message = get_notification()) : ?>
                <div class="alert alert-<?= $message['type']; ?>">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong><?= $message['title']; ?></strong>
                    <p><?= $message['content']; ?></p>
                </div>
                <?php endif ?>
                
                <?php if ($menus) : ?>
                <table id="table" class="table table-bordered table-condensed table-striped table-hover">
                    <thead>
                        <tr class="active">
                            <th class="text-nowrap">Menú</th>
                            <th class="text-nowrap">Ícono</th>
                            <th class="text-nowrap">Ruta</th>
                            <th class="text-nowrap">Módulo</th>
                            <th class="text-nowrap">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($menus as $nro => $menu) : ?>
                        <tr class="treegrid-<?= $menu['id_menu']; ?> <?= ($menu['antecesor_id'] == 0) ? '' : 'treegrid-parent-' . $menu['antecesor_id']; ?>">
                            <td class="text-nowrap"><?= escape($menu['menu']); ?></td>
                            <td class="text-nowrap">
                                <span class="<?= $menu['icono']; ?>"></span>
                                <span><?= escape($menu['icono']); ?></span>
                            </td class="text-nowrap">
                            <td class="text-nowrap"><?= escape($menu['ruta']); ?></td>
                            <td class="text-nowrap"><?= escape($menu['modulo']); ?></td>
                            <td class="text-nowrap">
                                <a href="?/generador-menus/ver/<?= $menu['id_menu']; ?>" data-toggle="tooltip" data-title="Ver menú" class="btn btn-info btn-xs"><span class="icon-magnifier"></span></a>
                                <a href="?/generador-menus/modificar/<?= $menu['id_menu']; ?>" data-toggle="tooltip" data-title="Modificar menú"style="color:white" class="btn btn-warning btn-xs"><span class="icon-note"></span></a>
                                <?php if ($menu['antecesor'] == 0) : ?>
                                <a href="?/generador-menus/eliminar/<?= $menu['id_menu']; ?>" data-toggle="tooltip" data-title="Eliminar menú" data-eliminar="true"class="btn btn-danger btn-xs"><span class="icon-trash"></span></a>
                                <?php endif ?>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <?php else : ?>
                <div class="alert alert-info">
                    <strong>Atención!</strong>
                    <ul>
                        <li>No existen menús registrados en la base de datos.</li>
                        <li>Para crear nuevos menús debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
                    </ul>
                </div>
                <?php endif ?>
                <!-- ============================================================== -->
                <!-- end data table  -->
                <!-- ============================================================== -->
            </div>
            <?php else : ?>
            <div class="alert alert-info">
                <strong>Atención!</strong>
                <ul>
                    <li>No existen menús registrados en la base de datos.</li>
                    <li>Para crear nuevos menús debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
                </ul>
            </div>
            <?php endif ?>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- end row -->
<!-- ============================================================== -->
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.maskedinput.min.js"></script>
<script src="<?= js; ?>/jquery.base64.js"></script>
<script>
$(function () {
	$(window).on('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/generador-menus/crear';
				break;
			}
		}
	});

	$('[data-eliminar]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var csrf = '<?= $csrf; ?>';
		bootbox.confirm('¿Está seguro que desea eliminar el menú?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	
	<?php if ($menus) : ?>
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
