<?php

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los menus
$menus = $db->get('sys_menus');
 
?>
<?php require_once show_template('header-design'); ?>
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Crear Menú </h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Desarrollo</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Generador de Menús</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Crear Menú</li>
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
            <h5 class="card-header">Generador de menús</h5>

            <div class="card-body">
                <form class="" id="form-menu" method="post" action="?/generador-menus/guardar" autocomplete="off">
                    <input type="hidden" name="<?= $csrf; ?>">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                        	<div class="form-group">
                                <label for="menu">Nombre</label>
                                <input type="text" class="form-control" id="menu" name="menu" placeholder="">
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="form-group">
                                <label for="icono">Ícono</label>
                                <input type="text" id="icono" name="icono" value="glyphicon glyphicon-dashboard" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="form-group">
                                <label for="ruta">Ruta</label> 
                                <input type="text" id="ruta" name="ruta" class="form-control" placeholder="?/nombre-modulo/nombre-archivo">
                            </div>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
							<div class="form-group">
								<label for="antecesor_id">Antecesor</label>
								<select name="antecesor_id" id="antecesor_id" class="form-control">
									<option value="" selected="selected">Seleccionar</option>
									<?php foreach ($menus as $elemento) { ?>
									<option value="<?= $elemento['id_menu']; ?>"><?= escape($elemento['id_menu']) . ' &mdash; ' . escape($elemento['menu']); ?></option>
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
									<span>Registrar</span>
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
<!-- <script src="<?= js; ?>/js/jquery.validate.js"></script> -->
<!-- <script src="application/modules/generador-menus/generador-menu.js"></script> -->
<script>
$(function () {

    $('#antecesor_id').selectize({
        persist: false,
        createOnBlur: true,
        create: false,
        onInitialize: function () {
            $('#antecesor_id').css({
                display: 'block',
                left: '-10000px',
                opacity: '0',
                position: 'absolute',
                top: '-10000px'
            });
        },
        onChange: function () {
            $('#antecesor_id').trigger('blur');
        },
        onBlur: function () {
            $('#antecesor_id').trigger('blur');
        }
    });

	// $('form:first').on('reset', function () {
	// 	$('#antecesor_id').get(0).selectize.clear();
	// });
});
</script>