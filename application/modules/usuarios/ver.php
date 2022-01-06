<?php
 
// Obtiene los parametros
$id_usuario = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene el usuario
$usuario = $db->select('u.*, r.rol')->from('sys_users u')->join('sys_roles r', 'u.rol_id = r.id_rol', 'left')->where(array('u.id_user' => $id_usuario, 'u.visible' => 's'))->fetch_first();

// Ejecuta un error 404 si no existe el usuario
if (!$usuario) { require_once not_found(); exit; }

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_crear = in_array('crear', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_bloquear = in_array('bloquear', $_views);
$permiso_desbloquear = in_array('desbloquear', $_views);
$permiso_subir = in_array('subir', $_views);
$permiso_suprimir = in_array('suprimir', $_views);
$permiso_cambiar = in_array('cambiar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);

?>
<?php require_once show_template('header-design'); ?>
<link rel="stylesheet" href="<?= css; ?>/jquery.guillotine.min.css">
<style>
@media (min-width: 768px) {
	.table-display > .tbody > .tr > .td,
	.table-display > .tbody > .tr > .th,
	.table-display > .tfoot > .tr > .td,
	.table-display > .tfoot > .tr > .th,
	.table-display > .thead > .tr > .td,
	.table-display > .thead > .tr > .th {
		padding-bottom: 15px;
		vertical-align: top;
	}
	.table-display > .tbody > .tr > .td:first-child,
	.table-display > .tbody > .tr > .th:first-child,
	.table-display > .tfoot > .tr > .td:first-child,
	.table-display > .tfoot > .tr > .th:first-child,
	.table-display > .thead > .tr > .td:first-child,
	.table-display > .thead > .tr > .th:first-child {
		padding-right: 15px;
	}
}
</style>
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row"> 
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"> 
        <div class="page-header">
            <h2 class="pageheader-title">Ver Usuario</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Administración</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Usuarios</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ver Usuario</li>
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
                                        <a href="?/usuarios/listar" class="dropdown-item">Listar</a>
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
	<?php if ($permiso_listar || $permiso_crear || $permiso_modificar || $permiso_eliminar || $permiso_bloquear || $permiso_imprimir) : ?>
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
					<li><a href="?/usuarios/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar usuarios</a></li>
					<?php endif ?>
					<?php if ($permiso_crear) : ?>
					<li><a href="?/usuarios/crear"><span class="glyphicon glyphicon-plus"></span> Crear usuario</a></li>
					<?php endif ?>
					<?php if ($permiso_modificar) : ?>
					<li><a href="?/usuarios/modificar/<?= $id_usuario; ?>"><span class="glyphicon glyphicon-edit"></span> Modificar usuario</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar && $usuario['rol_id'] != 1) : ?>
					<li><a href="?/usuarios/eliminar/<?= $id_usuario; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar usuario</a></li>
					<?php endif ?>
					<?php if ($permiso_bloquear && $usuario['rol_id'] != 1) : ?>
					<li><a href="?/usuarios/bloquear/<?= $id_usuario; ?>" data-bloquear="true"><span class="glyphicon glyphicon-remove-circle"></span> Bloquear usuario</a></li>
					<?php endif ?>
					<?php if ($permiso_desbloquear && $usuario['rol_id'] != 1) : ?>
					<li><a href="?/usuarios/desbloquear/<?= $id_usuario; ?>" data-desbloquear="true"><span class="glyphicon glyphicon-ok-circle"></span> Desbloquear usuario</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/usuarios/imprimir/<?= $id_usuario; ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir usuario</a></li>
					<?php endif ?>
				</ul>
			</div>
		</div>
	</div>
	<hr> -->
	<?php endif ?>

	<div class="row">
		<div class="col-sm-4 col-md-5">
		    <div class="card card-figure has-hoverable">
                <!-- .card-figure -->
                <figure class="figure">
			        <img src="<?= ($usuario['avatar'] == '') ? imgs . '/avatar.jpg' : files . '/profiles/' . $usuario['avatar']; ?>" class="img-responsive thumbnail cursor-pointer" data-toggle="modal" data-target="#modal_mostrar" data-modal-title="Avatar">
                    <!-- <img class="img-fluid" src="../assets/images/card-img.jpg" alt="Card image cap"> -->
                </figure>
                <!-- /.card-figure -->
            </div>
			<?php if ($permiso_subir || $permiso_cambiar) : ?>
			<div class="list-group">
				<?php if ($permiso_subir) : ?>
				<a href="#" class="list-group-item" onclick="subir()" data-toggle="modal" data-target="#modal_subir" data-backdrop="static" data-keyboard="false">
					<span class="glyphicon glyphicon-picture"></span>
					<span>Subir avatar</span>
				</a>
				<?php endif ?>
				<?php if ($permiso_suprimir) : ?>
				<a href="?/usuarios/suprimir/<?= $id_usuario; ?>" class="list-group-item text-ellipsis" data-suprimir="true">
					<span class="glyphicon glyphicon-eye-close"></span>
					<span>Eliminar avatar</span>
				</a>
				<?php endif ?>
				<?php if ($permiso_cambiar) : ?>
				<a href="#" class="list-group-item" onclick="cambiar()" data-toggle="modal" data-target="#modal_cambiar" data-backdrop="static" data-keyboard="false">
					<span class="glyphicon glyphicon-lock"></span>
					<span>Cambiar contraseña</span>
				</a>
				<?php endif ?>
			</div>
			<?php endif ?>
		</div>
		<div class="col-sm-8 col-md-7">
			<div class="well">
				<p class="lead"><strong>Información del usuario</strong></p>
				<hr>
				<div class="table-display">
					<div class="tbody">
						<div class="tr">
							<div class="th">Usuario:</div>
							<div class="td"><?= escape($usuario['username']); ?></div>
						</div>
						<div class="tr">
							<div class="th">Correo:</div>
							<div class="td"><?= ($usuario['email'] != '') ? escape($usuario['email']) : 'No asignado'; ?></div>
						</div>
						<div class="tr">
							<div class="th">Rol:</div>
							<div class="td"><?= escape($usuario['rol']); ?></div>
						</div>
						<div class="tr">
							<div class="th">Activo:</div>
							<div class="td"><?= ($_user['active'] == 's') ? 'Si' : 'No'; ?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal subir inicio -->
<?php if ($permiso_subir) : ?>
<div id="modal_subir" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/usuarios/subir" enctype="multipart/form-data" id="form_subir" class="modal-content loader-wrapper" autocomplete="off">
			<input type="hidden" name="<?= $csrf; ?>">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h4 class="modal-title">Subir avatar</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="avatar_subir" class="control-label">Avatar:</label>
					<input type="file" name="avatar" id="avatar_subir" class="form-control" data-validation="required mime size dimension" data-validation-allowing="jpg, png" data-validation-max-size="4M" data-validation-dimension="max1920">
					<input type="hidden" value="<?= $id_usuario; ?>" name="id_user" id="id_user_subir" class="translate" tabindex="-1" data-validation="required number" data-validation-error-msg="El campo no es válido">
					<input type="hidden" value="" name="data" id="data_subir" class="translate" tabindex="-1" data-validation="required" data-validation-error-msg="El campo no es válido">
				</div>
				<div class="row" data-guillotine-element="container">
					<div class="col-sm-7">
						<div class="thumbnail">
							<img id="image_subir" src="">
						</div>
					</div>
					<div class="col-sm-5">
						<div class="list-group margin-none">
							<a href="#" class="list-group-item" data-guillotine-action="fit">
								<span class="glyphicon glyphicon-fullscreen"></span>
								<span>Tamaño completo</span>
							</a>
							<a href="#" class="list-group-item" data-guillotine-action="center">
								<span class="glyphicon glyphicon-align-center"></span>
								<span>Centrar imagen</span>
							</a>
							<a href="#" class="list-group-item" data-guillotine-action="zoomIn">
								<span class="glyphicon glyphicon-zoom-in"></span>
								<span>Aumentar tamaño</span>
							</a>
							<a href="#" class="list-group-item" data-guillotine-action="zoomOut">
								<span class="glyphicon glyphicon-zoom-out"></span>
								<span>Reducir tamaño</span>
							</a>
							<a href="#" class="list-group-item" data-guillotine-action="rotateLeft">
								<span class="glyphicon glyphicon-menu-left"></span>
								<span>Girar a izquierda</span>
							</a>
							<a href="#" class="list-group-item" data-guillotine-action="rotateRight">
								<span class="glyphicon glyphicon-menu-right"></span>
								<span>Girar a derecha</span>
							</a>
							<a href="#" class="list-group-item active" data-guillotine-action="getData">
								<span class="glyphicon glyphicon-floppy-disk"></span>
								<span>Guardar cambios</span>
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default">
					<span class="glyphicon glyphicon-search"></span>
					<span>Visualizar</span>
				</button>
			</div>
			<div id="loader_subir" class="loader-wrapper-backdrop hidden">
				<span class="loader"></span>
			</div>
		</form>
	</div>
</div>
<?php endif ?>
<!-- Modal subir fin -->

<!-- Modal cambiar inicio -->
<?php if ($permiso_cambiar) : ?>
<div id="modal_cambiar" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/usuarios/cambiar" id="form_cambiar" class="modal-content loader-wrapper" autocomplete="off">
			<input type="hidden" name="<?= $csrf; ?>">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h4 class="modal-title">Cambiar contraseña</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="password_confirmation" class="control-label">Contraseña:</label>
					<input type="password" name="password_confirmation" id="password_confirmation" class="form-control" data-validation="required length strength" data-validation-length="5-30" data-validation-strength="2">
					<input type="hidden" value="<?= $id_usuario; ?>" name="id_user" id="id_user" class="translate" tabindex="-1" data-validation="required number" data-validation-error-msg="El campo no es válido">
				</div>
				<div class="form-group">
					<label for="password" class="control-label">Confirmar contraseña:</label>
					<input type="password" name="password" id="password" class="form-control" data-validation="confirmation">
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-danger">
					<span class="glyphicon glyphicon-floppy-disk"></span>
					<span>Guardar</span>
				</button>
				<button type="reset" class="btn btn-default">
					<span class="glyphicon glyphicon-refresh"></span>
					<span>Restablecer</span>
				</button>
			</div>
			<div id="loader_cambiar" class="loader-wrapper-backdrop hidden">
				<span class="loader"></span>
			</div>
		</form>
	</div>
</div>
<?php endif ?>
<!-- Modal cambiar fin -->

<!-- Modal mostrar inicio -->
<div id="modal_mostrar" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content loader-wrapper">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<img src="" class="img-responsive img-rounded" data-modal-image="">
			</div>
			<div id="loader_mostrar" class="loader-wrapper-backdrop">
				<span class="loader"></span>
			</div>
		</div>
	</div>
</div>
<!-- Modal mostrar fin -->

<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/jquery.guillotine.min.js"></script>
<script src="<?= js; ?>/bootbox.min.js"></script>
<script>
function subir(){
	$("#modal_subir").modal("show");
}
function cambiar(){
	$("#modal_cambiar").modal("show");
}
$(function () {
	<?php if ($permiso_eliminar && $usuario['rol_id'] != 1) : ?>
	$('[data-suprimir]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var csrf = '<?= $csrf; ?>';
		bootbox.confirm('Está seguro que desea eliminar el avatar?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
})
</script>
<?php require_once show_template('footer-design'); ?>