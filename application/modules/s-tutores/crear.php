<?php

//obtiene el valor
$id_familiar = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

/* Nombre del dominio */
$nombre_dominio = escape($_institution['nombre_dominio']);

// Obtiene los expedidos de los documentos de identidad
$expedidos = $db->select('expedido')->from('sys_persona')->group_by('expedido')->where('expedido!=', '')->fetch();

// Obtiene los idiomas frecuentes
$idiomas = $db->select('idioma_frecuente')->from('ins_familiar')->group_by('idioma_frecuente')->where('idioma_frecuente!=', '')->fetch();

// Obtiene los grados de instruccion
$grados = $db->select('grado_instruccion')->from('ins_familiar')->group_by('grado_instruccion')->where('grado_instruccion!=', '')->fetch();

// Obtiene las ocupaciones de instruccion
$profesiones = $db->select('profesion')->from('ins_familiar')->group_by('profesion')->where('profesion!=', '')->fetch();

// Obtiene los grados de instruccion
$parentescos = $db->select('parentesco')->from('ins_familiar')->group_by('parentesco')->where('parentesco!=', '')->fetch();



?>
<?php require_once show_template('header-design'); ?>
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">

<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Crear Familiar</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Inscripciones</a></li>
						<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Tutores</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Crear Familiar</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>


 <form id="form_tutor">
<div class="row">
   
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="card">
			<div class="card-body">
				<div class="form-row">
					<div class="col col-xl-4 col-lg-4 col-md-12 col-sm-6 col-xs-12">
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="row">
								<div class="col-12">
									<div class="card">
										<div class="card-body">
											<div class="row">
												<div class="col-md-12" style="width:auto; height:300px;">
													<div class="img-container" style="width:auto; height:300px;">
														<img id="image" src="files/<?=$nombre_dominio?>/profiles/avatar.jpg" alt="Picture" style="width:auto; height:300px;">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
							     <input name="inputFileFotografia" id="inputFileFotografia" type="file">
							</div>
						</div>
					</div>
					<div class="col col-xl-4 col-lg-4 col-md-12 col-sm-6 col-xs-12">
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
							<label class="control-label">Nombres:(*) </label>
							<div class="controls control-group">
								<input type="hidden" id="id_estudiante" name="id_estudiante">
								<input id="nombres" name="nombres" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();">
							</div>
						</div>
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
							<label class="control-label">Primer Apellido: </label>
							<div class="controls control-group" >
								<input id="primer_apellido" name="primer_apellido" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();">
							</div>
						</div>
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
							<label class="control-label">Segundo Apellido: </label>
							<div class="controls control-group">
								<input id="segundo_apellido" name="segundo_apellido" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();">
							</div>
						</div>
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
							<label class="control-label">Tipo de Documento:(*) </label>
							<div class="controls control-group">
							<select id="tipo_documento" name="tipo_documento" class="form-control" required>
								<option value="">Seleccione</option>
								<option value="1">CI</option>
								<option value="2">Pasaporte</option>
								<option value="3">CI extranjero</option>
							</select>
							</div>
						</div>
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
							<label class="control-label">Número de Documento:(*) </label>
							<div class="controls control-group">
								<input id="numero_documento" name="numero_documento" type="text" class="form-control">
							</div>
						</div>
						
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
    						<label class="control-label">Expedido: (*)</label>
    						<div class="controls control-group">
    							<select name="expedido" id="expedido" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
    								<option value="" selected="selected">Seleccionar</option>
    								<option value="LP">LP</option>
    								<option value="OR">OR</option>
    								<option value="CBBA">CBBA</option>
    								<option value="SC">SC</option>
    								<option value="TJ">TJ</option>
    								<option value="CH">CH</option>
    								<option value="BE">BN</option>
    								<option value="PD">PN</option>
    								<option value="PT">PT</option>
    							</select>
    						</div>
						</div>
						
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
							<label class="control-label">Complemento: </label>
							<div class="controls control-group">
								<input id="complemento" name="complemento" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();">
							</div>
						</div>
						
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
							<label class="control-label">NIT: </label>
							<div class="controls control-group">
								<input id="nit" name="nit" type="text" class="form-control">
							</div>
						</div>
						
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
							<label class="control-label">Género:(*) </label>
							<div class="controls control-group">
									<label class="custom-control custom-radio custom-control-inline">
										<input type="radio" name="genero" value="v" checked="" class="custom-control-input"><span class="custom-control-label">Varón</span>
									</label>
									<label class="custom-control custom-radio custom-control-inline">
										<input type="radio" name="genero" value="m" class="custom-control-input"><span class="custom-control-label">Mujer</span>
									</label>
							</div>
						</div>
					
					</div>
					<div class="col col-xl-4 col-lg-4 col-md-12 col-sm-6 col-xs-12">
					    
					    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
							<label for="fecha_nacimiento" class="control-label">Fecha de Nacimiento:(*) </label>
							<div class="controls control-group">
							<input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control text-uppercase" autocomplete="off" data-validation-allowing="float">
							</div>
						</div>
						
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
    						<label class="control-label">Idioma que habla frecuentemente: (*)</label>
    						<div class="controls control-group">
    							<select name="idioma_frecuente" id="idioma_frecuente" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
    								<option value="" selected="selected">Seleccionar</option>
    								<?php foreach ($idiomas as $idioma) : ?>
    									<option value="<?= $idioma['idioma_frecuente']; ?>"><?= escape($idioma['idioma_frecuente']); ?></option>
    								<?php endforeach ?>
    							</select>
    						</div>
						</div>
						
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
							<label class="control-label">Correo Electrónico: </label>
							<div class="controls control-group">
								<input id="correo_electronico" name="correo_electronico" type="email" class="form-control">
							</div>
						</div>
						
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
							<label class="control-label">Teléfono:(*) </label>
							<div class="controls control-group">
								<input id="telefono" name="telefono" type="text" class="form-control">
							</div>
						</div>
						
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
							<label class="control-label">Referencia teléfono: </label>
							<div class="controls control-group">
								<input id="referencia_telefono" name="referencia_telefono" type="text" class="form-control">
							</div>
						</div>
						
						
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
							<label class="control-label">Ocupación:(*)</label>
							<div class="controls control-group">
								<select name="profesion" id="profesion" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
									<option value="" selected="selected">Seleccionar</option>
									<?php foreach ($profesiones as $profesion) : ?>
										<option value="<?= $profesion['profesion']; ?>"><?= escape($profesion['profesion']); ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
							<label class="control-label">Dirección oficina: </label>
							<div class="controls control-group">
								<input id="direccion" name="direccion" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();">
							</div>
						</div>
						
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
							<label class="control-label">Mayor grado de instruccion alcanzado : (*)</label>
							<div class="controls control-group">
								
								<select name="grado_instruccion" id="grado_instruccion" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
									<option value="" selected="selected">Seleccionar</option>
									<?php foreach ($grados as $grado) : ?>
										<option value="<?= $grado['grado_instruccion']; ?>"><?= escape($grado['grado_instruccion']); ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>

						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
							<label class="control-label">Cual es el parentesco con el estudiante? : (*)</label>
							<div class="controls control-group">
								<div class="controls control-group">
									<select name="parentesco" id="parentesco" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
										<option value="" selected="selected">Seleccionar</option>
										<?php foreach ($parentescos as $parentesco) : ?>
											<option value="<?= $parentesco['parentesco']; ?>"><?= escape($parentesco['parentesco']); ?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body">
              <button type="submit" class="btn btn-primary pull-left" id="btn_nuevo">Registrar</button>
		    </div>
		</div>
	</div>
	
</div>
</form>

<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/educheck.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>

<?php require_once show_template('footer-design'); ?>

<script>

    function init() {
      var inputFile = document.getElementById('inputFileFotografia');
      inputFile.addEventListener('change', mostrarImagen, false);
    }
    
    function mostrarImagen(event) {
      var file = event.target.files[0];
      var reader = new FileReader();
      reader.onload = function(event) {
        var img = document.getElementById('image');
        img.src= event.target.result;
      }
      reader.readAsDataURL(file);
    }
    
    window.addEventListener('load', init, false);
    

    var imagen = [];
 
  $("#form_tutor").validate({
    rules: {
      nombres: {
        required: true
      },
      tipo_documento: {
        required: true
      },
      numero_documento: {
        required: true
      },
      expedido: {
        required: true
      },
      fecha_nacimiento: {
        required: true
      },
      idioma_frecuente: {
        required: true  
      },
      telefono: {
        required: true
      },
      grado_instruccion: {
        required: true  
      },
      profesion: {
        required: true
      },
      parentesco: {
        required: true
      }
      
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
      nombres: "Debe ingresar su nombre.",
      tipo_documento: "Debe seleccionar el tipo de documento.",
      numero_documento: "Debe ingresar su numero de documento.",
      expedido: "Debe seleccionar el lugar expedito.",
      fecha_nacimiento: "Debe ingresar la fecha de nacimiento.",
      idioma_frecuente: "Debe ingresar un idioma frecuente.",
      telefono: "Debe ingresar un numero de teléfono.",
      grado_instruccion: "Debe ingresar su grado de instrucción alcanzado.",
      profesion: "Debe ingresar una ocupación o profesión.",
      parentesco: "Debe ingresar el parentesco que tendra."
    },
    //una ves validado guardamos los datos en la DB
    submitHandler: function(form) {
      //var datos = $("#form_tutor").serialize();
      var form_data = new FormData($("#form_tutor")[0]);
      //alert(datos);
      $.ajax({
        type: 'POST',
        url: "?/s-tutores/guardar",
        data: form_data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'text',
        success: function(resp) {
          cont = 0;
          console.log(resp);
          switch (resp) {
            case "0":
              alertify.info('No se puede registar, ya existe un familiar con el mismo numero de documento');
              break;
            case "1":
              alertify.success('Se registro el familiar correctamente');
              location.href="?/s-tutores/listar";
              break;
            case "2":
              alertify.success('Se editó el familiar correctamente');
              location.href="?/s-tutores/listar";
              break;
          }
          
        }

      });

    }
  })
  
  // Selectize
  var $expedido = $('#expedido');
	$expedido.selectize({
		persist: false,
		createOnBlur: true,
		create: true,
		onInitialize: function() {
			$expedido.css({
				display: 'block',
				left: '-10000px',
				opacity: '0',
				position: 'absolute',
				top: '-10000px'
			});
		}
	});

	var $grado_instruccion = $('#grado_instruccion');
	$grado_instruccion.selectize({
		persist: false,
		createOnBlur: true,
		create: true,
		onInitialize: function() {
			$grado_instruccion.css({
				display: 'block',
				left: '-10000px',
				opacity: '0',
				position: 'absolute',
				top: '-10000px'
			});
		}
	});

	var $idioma_frecuente = $('#idioma_frecuente');
	$idioma_frecuente.selectize({
		persist: false,
		createOnBlur: true,
		create: true,
		onInitialize: function() {
			$idioma_frecuente.css({
				display: 'block',
				left: '-10000px',
				opacity: '0',
				position: 'absolute',
				top: '-10000px'
			});
		}
	});

	var $parentesco = $('#parentesco');
	$parentesco.selectize({
		persist: false,
		createOnBlur: true,
		create: true,
		onInitialize: function() {
			$parentesco.css({
				display: 'block',
				left: '-10000px',
				opacity: '0',
				position: 'absolute',
				top: '-10000px'
			});
		}
	});

	var $profesion = $('#profesion');
	$profesion.selectize({
		persist: false,
		createOnBlur: true,
		create: true,
		onInitialize: function() {
			$profesion.css({
				display: 'block',
				left: '-10000px',
				opacity: '0',
				position: 'absolute',
				top: '-10000px'
			});
		}
	});
</script>
