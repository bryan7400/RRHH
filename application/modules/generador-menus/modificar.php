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
            <h2 class="pageheader-title">Editar Menú </h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Desarrollo</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Generador de Menús</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar Menú</li>
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
                                        <a href="?/generador-menus/ver/<?= $menu['id_menu']; ?>" class="dropdown-item">Ver menú</a>
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
                <form class="cmxform" id="form-menu" method="post" action="?/generador-menus/guardar" autocomplete="off">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                        	<div class="form-group">
                                <label for="menu">Nombre</label>
                                <input type="text" value="<?= $menu['menu']; ?>" id="menu" name="menu" class="form-control" placeholder="">
                                <input type="hidden" value="<?= $menu['id_menu']; ?>" name="id_menu" id="id_menu" class="form-control">
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="form-group">
                                <label for="icono">Ícono</label>
                                <input type="text" value="<?= $menu['icono']; ?>" id="icono" name="icono" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="form-group">
                                <label for="ruta">Ruta</label>
                                <input type="text" value="<?= $menu['ruta']; ?>" id="ruta" name="ruta" class="form-control" placeholder="?/nombre-modulo/nombre-archivo">
                            </div>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
							<div class="form-group">
								<label for="antecesor_id">Antecesor</label>
								<select name="antecesor_id" id="antecesor_id" class="form-control">
									<!-- <option value="" selected="selected">Seleccionar</option> -->
									<?php foreach ($menus as $elemento) { ?>
										<?php if ($elemento['id_menu'] == $menu['antecesor_id']) : ?>
										<option value="<?= $elemento['id_menu']; ?>" selected="selected"><?= escape($elemento['id_menu']) . ' &mdash; ' . escape($elemento['menu']); ?></option>
										<?php else : ?>
										<option value="<?= $elemento['id_menu']; ?>"><?= escape($elemento['id_menu']) . ' &mdash; ' . escape($elemento['menu']); ?></option>
										<?php endif ?>
									 <!-- <option value="<?= $elemento['id_menu']; ?>"><?= escape($elemento['id_menu']) . ' &mdash; ' . escape($elemento['menu']); ?></option> -->
									<?php } ?>
								</select>
							</div>
					    </div>
                    </div>
                    <div class="row">
                    	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                        	<div class="form-group text-center">
								<button type="submit" class="btn btn-primary">
									<i class="icon-check"></i>
									<span>Editar</span>
								</button>
								<button type="reset" class="btn btn-light">
									<i class="icon-arrow-left-circle"></i>
									<span>Restablecer</span>
								</button>
							</div>
						</div>
                    </div>
                </form> 
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end validation form -->
    <!-- ============================================================== -->
</div>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/js/jquery.validate.js"></script>
<script src="<?= js; ?>/js-config/generador-menu.js"></script>
<script>
$(function () {
	var $antecesor_id = $('#antecesor_id');

	$.validate({
		modules: 'basic'
	});

	$antecesor_id.selectize({
		maxOptions: 6,
		onInitialize: function () {
			$antecesor_id.show().addClass('selectize-translate');
		},
		onChange: function () {
			$antecesor_id.trigger('blur');
		},
		onBlur: function () {
			$antecesor_id.trigger('blur');
		}
	});

	$('form:first').on('reset', function () {
		$antecesor_id.get(0).selectize.setValue($antecesor_id.attr('data-selectize'));
	});

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