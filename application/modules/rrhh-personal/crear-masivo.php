<?php  

// Obtiene la cadena csrf
$csrf = set_csrf();


// Obtiene los cargos
$cargos = $db->from('per_cargos')->order_by('cargo', 'asc')->where('estado', 'A')->fetch();
$gestions = $db->from('ins_gestion')->order_by('gestion', 'asc')->where('estado', 'A')->fetch();

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
            <h2 class="pageheader-title">Crear/Actualizar Personal (Masivo)</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Administración</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Personal</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Crear/Actualizar persona (Masivo)</li>
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
                                        <a href="?/personas/listar" class="dropdown-item">Listar personas</a>
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
						<form method="post" action="?/rrhh-personal/guardar-masivo" id="formulario" autocomplete="off">
							

							
                            <div class="form-group">
                                <label for="cargo_id" class="control-label">Cargo:</label>
                                <select name="cargo_id" id="cargo_id" class="form-control" data-validation="required number">
                                    <option value="" selected="selected">Seleccionar</option>
                                    <?php foreach ($cargos as $cargo) : ?>
                                    <option value="<?= $cargo['id_cargo']; ?>"><?= escape($cargo['cargo']); ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="accion_persona" class="control-label">Acción:</label>
                                <select name="accion_persona" id="accion_persona" class="form-control" data-validation="required">
                                    <option value="" selected="selected">Seleccionar...</option>
                                    <option value="R">Recontratar Personal</option>
                                    <!-- <option value="LCS">Limpiar Código Sesión</option> -->
                                    <!-- <option value="E">Eliminar Personal</option> -->
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="gestion_n" class="control-label">Gestion:</label>
                                <select name="gestion_n" id="gestion_n" class="form-control" data-validation="required number">
                                    <option value="" selected="selected">Seleccionar</option>
                                    <?php foreach ($gestions as $gestion) : ?>
                                    <option value="<?= $gestion['id_gestion']; ?>"><?= escape($gestion['gestion']); ?></option>
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

    $('#accion_persona').on('change', function (e) {
        var valor = $('#accion_persona').val();
        //Si la accion es Actualizar o Crear me muestre Tipo de contraseña
        if(valor == "A" || valor == "C"){
           $('#tipo_contrasenia_div').show();
        }else{
           $('#tipo_contrasenia_div').hide();
        }
    });
})



</script>