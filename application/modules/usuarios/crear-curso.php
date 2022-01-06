<?php  

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los roles
$roles = $db->from('sys_roles')->order_by('rol', 'asc')->fetch();

// Obtiene los roles
$roles = $db->from('sys_roles')->order_by('rol', 'asc')->fetch();

$cursos = $db->query('SELECT ap.id_aula_paralelo,
p.nombre_paralelo, a.nombre_aula, na.nombre_nivel, t.nombre_turno, na.acronimo_nivel
FROM ins_aula_paralelo ap 
INNER JOIN ins_paralelo p ON p.id_paralelo=ap.paralelo_id
INNER JOIN ins_aula a ON a.id_aula=ap.aula_id
INNER JOIN ins_nivel_academico na ON a.nivel_academico_id=na.id_nivel_academico
INNER JOIN ins_turno t ON ap.turno_id=t.id_turno
GROUP BY ap.id_aula_paralelo
ORDER BY na.id_nivel_academico, a.id_aula, p.nombre_paralelo ASC')->fetch();
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
						<form method="post" action="?/usuarios/guardar-curso" id="formulario" autocomplete="off">
							<input type="hidden" name="<?= $csrf; ?>">
                            <input type="hidden" name="id_user" value="0">

                            <div class="form-group">
                                <label for="curso" class="control-label">Cursos:</label>
                                <select name="curso" id="curso" class="form-control" autofocus="autofocus" data-validation="" data-validation-allowing="+-/.#() ">
                                    <option value="" selected="selected">Seleccionar</option>
                                    <?php foreach ($cursos as $value) : ?>
                                    <option value="<?= $value['id_aula_paralelo']; ?>"><?= escape($value['nombre_aula']); ?> <?= escape($value['nombre_paralelo']); ?> <?= escape($value['nombre_nivel']); ?> <?= escape($value['nombre_turno']); ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
							
							<!-- <div class="form-group">
								<label for="password_confirmation" class="control-label">Contraseña:</label>
								<input type="password" value="" name="password_confirmation" id="password_confirmation" class="form-control" data-validation="required length strength" data-validation-length="5-50" data-validation-strength="2">
							</div>
							<div class="form-group">
								<label for="password" class="control-label">Confirmar contraseña:</label>
								<input type="password" value="" name="password" id="password" class="form-control" data-validation="required confirmation">
							</div> -->
							<div class="form-group">
								<label for="email" class="control-label">Correo:</label>
								<input type="text" value="" name="email" id="email" class="form-control" data-validation="email" data-validation-optional="true">
							</div>
							<div class="form-group">
								<label for="rol_id" class="control-label">Rol</label>
								<select name="rol_id" id="rol_id" class="form-control" data-validation="required number">
									<option value="5" selected="selected">Estudiante</option>
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
    $('#curso').selectize({
        persist: false,
        createOnBlur: true,
        create: false,
        onInitialize: function (){
            $('#curso').css({
                display: 'block',
                left: '-10000px',
                opacity: '0',
                position: 'absolute',
                top: '-10000px'
            });
        },
        onChange: function () {
            $('#curso').trigger('blur');
        },
        onBlur: function () {
            $('#curso').trigger('blur');
        }
    });
})
</script>