<?php  

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los roles
$roles = $db->from('sys_roles')->order_by('rol', 'asc')->fetch();

// Obtiene los roles
$roles = $db->from('sys_roles')->order_by('rol', 'asc')->fetch();

// Obtiene de empleados
$empleados = $db->select('nombres, primer_apellido, segundo_apellido, id_persona')->from('sys_persona')->group_by('id_persona')->order_by('nombres', 'asc')->fetch();

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views); 

?>
<?php require_once show_template('header-design'); ?>
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row"> 
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"> 
        <div class="page-header">
            <h2 class="pageheader-title">Crear/Actualizar usuarios (Masivo)</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Administración</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Usuarios</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Crear/Actualizar usuario (Masivo)</li>
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
	            <div class="row">
				    <div class="col-sm-2 col-md-2 "></div>
					<div class="col-sm-8 col-md-8 ">
						<form method="post" action="?/usuarios/guardar-masivo" id="formulario" autocomplete="off">
							<input type="hidden" name="<?= $csrf; ?>">

							
                            <div class="form-group">
                                <label for="rol_id" class="control-label">Rol:</label>
                                <select name="rol_id" id="rol_id" class="form-control" data-validation="required number">
                                    <option value="" selected="selected">Seleccionar</option>
                                    <?php foreach ($roles as $rol) : ?>
                                    <option value="<?= $rol['id_rol']; ?>"><?= escape($rol['rol']); ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="accion_usuario" class="control-label">Acción:</label>
                                <select name="accion_usuario" id="accion_usuario" class="form-control" data-validation="required">
                                    <option value="" selected="selected">Seleccionar...</option>
                                    <option value="C">Crear Usuarios</option>
                                    <option value="A">Actualizar Contraseñas</option>                                    
                                    <option value="D">Desbloquear Usuarios</option>
                                    <option value="B">Bloquear Usuarios</option>
                                    <!-- <option value="LCS">Limpiar Código Sesión</option> -->
                                    <!-- <option value="E">Eliminar Usuarios</option> -->
                                </select>
                            </div>

                            <div class="form-group" id="tipo_contrasenia_div" style="display:none">
                                <label for="tipo_contrasenia" class="control-label">Tipo Contraseña:</label>
                                <select name="tipo_contrasenia" id="tipo_contrasenia" class="form-control">
                                    <option value="" selected="selected">Seleccionar</option>
                                    <option value="P">Programado</option>
                                    <option value="CG">Contraseña Genérico</option>
                                </select>
                            </div>

							<div class="form-group" id="uno" style="display:none">
								<label for="password_confirmation" class="control-label">Contraseña:</label>
								<input type="password" value="" name="password_confirmation" id="password_confirmation" class="form-control" data-validation="length strength" data-validation-length="5-100" data-validation-strength="2" data-validation-optional="true">
							</div>
							<div class="form-group" id="dos" style="display:none">
								<label for="password" class="control-label">Confirmar contraseña:</label>
								<input type="password" value="" name="password" id="password" class="form-control" data-validation="confirmation">
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
});
</script>
<?php require_once show_template('footer-design'); ?>
<script src="<?= js; ?>/selectize.min.js"></script>
<script>
   $(function () {
    // Obtiene a los estudiantes
    $('#empleado').selectize({
        persist: false,
        createOnBlur: true,
        create: false,
        onInitialize: function (){
            $('#empleado').css({
                display: 'block',
                left: '-10000px',
                opacity: '0',
                position: 'absolute',
                top: '-10000px'
            });
        },
        onChange: function () {
            $('#empleado').trigger('blur');
        },
        onBlur: function () {
            $('#empleado').trigger('blur');
        }
    });

    $('#tipo_contrasenia').on('change', function (e) {
        var valor = $('#tipo_contrasenia').val();
        if(valor == "P"){
           $('#uno').show();
           $('#dos').show();
        }else{
           $('#uno').hide();
           $('#dos').hide();
        }
    });

    $('#accion_usuario').on('change', function (e) {
        var valor = $('#accion_usuario').val();
        //Si la accion es Actualizar o Crear me muestre Tipo de contraseña
        if(valor == "A" || valor == "C"){
           $('#tipo_contrasenia_div').show();
        }else{
           $('#tipo_contrasenia_div').hide();
        }
    });
})
</script>