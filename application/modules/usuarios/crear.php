<?php  

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los roles
$roles = $db->from('sys_roles')->order_by('rol', 'asc')->fetch();

// Obtiene de personas
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
            <h2 class="pageheader-title">Crear usuario</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Administración</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Usuario</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Crear usuario</li>
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
						<form method="post" action="?/usuarios/guardar" id="formulario" autocomplete="off">
							<input type="hidden" name="<?= $csrf; ?>">

							<div class="form-group">
								<label for="empleado" class="control-label">Persona:</label>
								<select name="empleado" id="empleado" class="form-control" autofocus="autofocus" data-validation="required" data-validation-allowing="+-/.#() ">
									<option value="" selected="selected">Seleccionar</option>
									<?php foreach ($empleados as $value) : ?>
									<option value="<?= $value['id_persona']; ?>"><?= escape($value['nombres']); ?> <?= escape($value['primer_apellido']); ?> <?= escape($value['segundo_apellido']); ?></option>
									<?php endforeach ?>
								</select>
							</div>
							
							<div class="form-group">
								<label for="username" class="control-label">Usuario:</label>
								<input type="text" value="" name="username" id="username" class="form-control" autofocus="autofocus" data-validation="required alphanumeric length server" data-validation-allowing="_." data-validation-length="5-100" data-validation-url="?/usuarios/validar-crear">
							</div>
							<div class="form-group">
								<label for="password_confirmation" class="control-label">Contraseña:</label>
								<input type="password" value="" name="password_confirmation" id="password_confirmation" class="form-control" data-validation="required length strength" data-validation-length="5-50" data-validation-strength="2">
							</div>
							<div class="form-group">
								<label for="password" class="control-label">Confirmar contraseña:</label>
								<input type="password" value="" name="password" id="password" class="form-control" data-validation="required confirmation">
							</div>
							<div class="form-group">
								<label for="email" class="control-label">Correo:</label>
								<input type="text" value="" name="email" id="email" class="form-control" data-validation="email" data-validation-optional="true">
							</div>
							<div class="form-group">
								<label for="rol_id" class="control-label">Rol:</label>
								<select name="rol_id" id="rol_id" class="form-control" data-validation="required number">
									<option value="" selected="selected">Seleccionar</option>
									<?php foreach ($roles as $rol) : ?>
									<option value="<?= $rol['id_rol']; ?>"><?= escape($rol['rol']); ?></option>
									<?php endforeach ?>
								</select>
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

    $("#empleado").change(function(){
        
        var id = $(this).val();

        $.ajax({
          url:"?/usuarios/buscar", 
          method:"POST",  
          data:{id:id},  
          dataType:"json",
          timeout: 20000,
          }).done(function(resultado){ 
            console.log(resultado);
            if(resultado == 1){
                console.log('resultado');
                $('#empleado').val(null).trigger('change');
                //$("#empleado").val('');
                //$('select option').remove();
            }else{
                 console.log('no');
            }

        }).fail(function(jqXHR, textStatus, errorThrown){
                if(textStatus === 'timeout'){
                    console.log("error : " + xhr.responseText);      
                   alert('Error de tiempo de espera'); 
                }else if (jqXHR.status == 404){
                    console.log("error : " + xhr.responseText);      
                    //alert('Requested page not found [404]');
                }else if (jqXHR.status == 500){
                     console.log("error : " + xhr.responseText);      
                }
        })
    });
})

</script>