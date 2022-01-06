<?php

// Obtiene los parametros
$id_rol = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene el rol
$rol = $db->from('sys_roles')->where('id_rol', $id_rol)->fetch_first();

// Ejecuta un error 404 si no existe el rol
if (!$rol) { require_once not_found(); exit; }

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
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
            <h2 class="pageheader-title">Crear Rol</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Administración</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Rol</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Crear Rol</li>
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
                                        <?php if ($permiso_listar) : ?> 
                                        <div class="dropdown-divider"></div>
                                        <a href="?/roles/listar" class="dropdown-item">Listar roles</a>
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
	<div class="row">
	<div class="col-sm-2  col-md-2"></div>
		<div class="col-sm-8  col-md-8">
	<?php if ($permiso_listar || $permiso_crear || $permiso_ver || $permiso_eliminar || $permiso_imprimir) : ?>
<!-- 	<div class="row">
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
					<?php if ($permiso_listar) : ?>
					<li><a href="?/roles/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar roles</a></li>
					<?php endif ?>
					<?php if ($permiso_crear) : ?>
					<li><a href="?/roles/crear"><span class="glyphicon glyphicon-plus"></span> Crear rol</a></li>
					<?php endif ?>
					<?php if ($permiso_ver) : ?>
					<li><a href="?/roles/ver/<?= $id_rol; ?>"><span class="glyphicon glyphicon-search"></span> Ver rol</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<li><a href="?/roles/eliminar/<?= $id_rol; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar rol</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/roles/imprimir/<?= $id_rol; ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir rol</a></li>
					<?php endif ?>
				</ul>
			</div>
		</div>
	</div>
	<hr> -->
	<?php endif ?>

			<form method="post" action="?/roles/guardar" autocomplete="off">
				<input type="hidden" name="<?= $csrf; ?>">
				<div class="form-group">
					<label for="rol" class="control-label">Rol:</label>
					<input type="text" value="<?= $rol['rol']; ?>" name="rol" id="rol" class="form-control" autofocus="autofocus" data-validation="required letternumber length" data-validation-allowing="-# " data-validation-length="max100">
					<input type="hidden" value="<?= $id_rol; ?>" name="id_rol" id="id_rol" class="translate" tabindex="-1" data-validation="required number" data-validation-error-msg="El campo no es válido">
				</div>
				<div class="form-group">
					<label for="descripcion" class="control-label">Descripción:</label>
					<textarea name="descripcion" id="descripcion" class="form-control" data-validation="letternumber" data-validation-allowing="-.,:;()\n " data-validation-optional="true"><?= escape($rol['descripcion']); ?></textarea>
				</div>
				<div class="form-group text-center">
					<button type="submit" class="btn btn-primary">
						<span class="glyphicon glyphicon-floppy-disk"></span>
						<span>Guardar</span>
					</button>
					<button type="reset" class="btn btn-default">
						<span class="glyphicon glyphicon-refresh"></span>
						<span>Restablecer</span>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script>
$(function () {
	$.validate({
		modules: 'basic'
	});

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
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-design'); ?>