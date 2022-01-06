<?php

// Obtiene los parametros
$id_menu = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene el menu
$menu = $db->select('m.*, n.menu as antecesor')->from('sys_menus m')->join('sys_menus n', 'm.antecesor_id = n.id_menu', 'left')->where('m.id_menu', $id_menu)->fetch_first();

// Ejecuta un error 404 si no existe el menu
if (!$menu) { require_once not_found(); exit; }

// Obtiene los menus
$menus = $db->get('sys_menus');

// Obtiene estado
$estado = verificar_submenu($menus, $menu['id_menu']);

?>
<?php require_once show_template('header-design'); ?>
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Ver Menú </h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Desarrollo</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Generador de Menús</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ver Menú</li>
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
    <!-- validation form -->
    <!-- ============================================================== -->
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-header">
            	<!-- <h5 class="card-header">Generador de menús</h5> -->
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
                                        <a href="?/generador-menus/principal" class="dropdown-item">Generador de menús</a>
                                        <a href="?/generador-menus/crear" class="dropdown-item">Crear menú</a>
                                        <a href="?/generador-menus/modificar/<?= $menu['id_menu']; ?>" class="dropdown-item">Editar menú</a>
                                        <?php if (!$estado) : ?>
										<a href="?/generador-menus/eliminar/<?= $menu['id_menu']; ?>" class="dropdown-item" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar menú</a>
										<?php endif ?>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                        	<div class="form-group">
                                <label for="menu">Nombre: </label>
                                <?= $menu['menu']; ?>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="form-group">
                                <label for="icono">Ícono: <?= $menu['icono']; ?>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="form-group">
                                <label for="ruta">Ruta: <?= $menu['ruta']; ?>
                            </div>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
							<div class="form-group">
								<label for="antecesor_id">Antecesor: <?= escape($menu['id_menu']) . ' &mdash; ' . escape($menu['menu']); ?>
							</div>
					    </div>
                    </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end validation form -->
    <!-- ============================================================== -->
</div>
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

	<?php if (!$estado) : ?>
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
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-full'); ?>