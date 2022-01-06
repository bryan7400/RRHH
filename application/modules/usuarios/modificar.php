<?php 

// Obtiene los parametros
$id_usuario = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();
 
// Obtiene el usuario
$usuario = $db->select('u.*, r.rol, pe.nombres, pe.primer_apellido, pe.segundo_apellido')
              ->from('sys_users u')
              ->join('sys_roles r', 'u.rol_id = r.id_rol', 'left')
              ->join('sys_persona pe', 'u.persona_id = pe.id_persona', 'left')
			  ->where(array('u.id_user' => $id_usuario, 'u.visible' => 's'))->fetch_first();

// Ejecuta un error 404 si no existe el usuario
if (!$usuario) { require_once not_found(); exit; }

// Obtiene los roles
$roles = $db->from('sys_roles')->order_by('rol', 'asc')->fetch();

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
            <h2 class="pageheader-title">Editar usuario</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Administración</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Usuario</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar usuario</li>
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
                                        <a href="?/usuarios/listar" class="dropdown-item">Listar usuarios</a>
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
					<li><a href="?/usuarios/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar usuarios</a></li>
					<?php endif ?>
					<?php if ($permiso_crear) : ?>
					<li><a href="?/usuarios/crear"><span class="glyphicon glyphicon-plus"></span> Crear usuario</a></li>
					<?php endif ?>
					<?php if ($permiso_ver) : ?>
					<li><a href="?/usuarios/ver/<?= $id_usuario; ?>"><span class="glyphicon glyphicon-search"></span> Ver usuario</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar && $usuario['rol_id'] != 1) : ?>
					<li><a href="?/usuarios/eliminar/<?= $id_usuario; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar usuario</a></li>
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
	<div class="col-sm-2 col-md-2 "></div>
		<div class="col-sm-8 col-md-8 ">
			<form method="post" action="?/usuarios/guardar" id="formulario" autocomplete="off">
				<input type="hidden" name="<?= $csrf; ?>">				
				<div class="form-group">
					<label for="empleado" class="control-label">Persona :</label>
					<?= $usuario['nombres'] . ' ' .$usuario['primer_apellido'] . ' ' . $usuario['segundo_apellido']; ?>
				</div>
				<div class="form-group">
					<label for="username" class="control-label">Usuario:</label>
					<input type="text" value="<?= $usuario['username']; ?>" name="username" id="username" class="form-control" autofocus="autofocus" data-validation="required alphanumeric length server" data-validation-allowing="_." data-validation-length="5-100"  data-validation-url="?/usuarios/validar-modificar/<?= $id_usuario; ?>">
					<input type="hidden" value="<?= $id_usuario; ?>" name="id_user" id="id_user" class="translate" tabindex="-1" data-validation="required number" data-validation-error-msg="El campo no es válido">
				</div>
				<div class="form-group">
					<label for="password_confirmation" class="control-label">Contraseña:</label>
					<input type="password" value="" name="password_confirmation" id="password_confirmation" class="form-control" data-validation-optional="true">
				</div>
				<div class="form-group">
					<label for="password" class="control-label">Confirmar contraseña:</label>
					<input type="password" value="" name="password" id="password" class="form-control" data-validation="confirmation">
				</div>
				<div class="form-group">
					<label for="email" class="control-label">Correo:</label>
					<input type="text" value="<?= $usuario['email']; ?>" name="email" id="email" class="form-control" data-validation="email" data-validation-optional="true">
				</div>
				<div class="form-group">
					<label for="rol_id" class="control-label">Rol:</label>
					<select name="rol_id" id="rol_id" class="form-control" data-validation="required number">
						<option value="">Seleccionar</option>
						<?php foreach ($roles as $rol) : ?>
							<?php if ($rol['id_rol'] == $usuario['rol_id']) : ?>
							<option value="<?= $rol['id_rol']; ?>" selected="selected"><?= escape($rol['rol']); ?></option>
							<?php else : ?>
							<option value="<?= $rol['id_rol']; ?>"><?= escape($rol['rol']); ?></option>
							<?php endif ?>
						<?php endforeach ?>
					</select>
				</div>
				<?php if ($usuario['rol_id'] != 1) : ?>
<!-- 				<div class="form-group">
					<label for="active" class="control-label">Estado:</label>
					<div class="radio">
						<label>
							<input type="radio" value="s" name="active" id="active"<?= ($usuario['active'] == 's') ? ' checked="checked"' : ''; ?>>
							<span>Activado</span>
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" value="n" name="active"<?= ($usuario['active'] == 'n') ? ' checked="checked"' : ''; ?>>
							<span>Bloqueado</span>
						</label>
					</div>
				</div> -->
				<div class="form-group">
					<label for="active" class="control-label">Estado:</label>
				</div>
                 <div class="form-group">
                    <label class="custom-control custom-radio">
                        <input type="radio" name="active"  id="active" value="s" <?= ($usuario['active'] == 's') ? ' checked="checked"' : ''; ?> class="custom-control-input"><span class="custom-control-label">Activado</span>
                    </label>
                    <label class="custom-control custom-radio custom-control-inline">
                        <input type="radio" name="active" value="n" <?= ($usuario['active'] == 'n') ? ' checked="checked"' : ''; ?> class="custom-control-input"><span class="custom-control-label">Bloqueado</span>
                    </label>
				</div>
				<?php endif ?>
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
	var $formulario = $('#formulario'), $username = $('#username');

	$.validate({
		modules: 'security'
	});

	$formulario.on('reset', function () {
		$username.trigger('keyup');
	});

	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/usuarios/crear';
				break;
			}
		}
	});
	<?php endif ?>
	
	<?php if ($permiso_eliminar && $usuario['rol_id'] != 1) : ?>
	$('[data-eliminar]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var csrf = '<?= $csrf; ?>';
		bootbox.confirm('Está seguro que desea eliminar el usuario?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-design'); ?>