<?php

//obtiene el valor id_estudiante del GET
$id_estudiante_editar = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene el id de la gestion actual
$id_gestion = $_gestion['id_gestion'];

// Obtiene nicel académico
$nivel = $db->select('z.*')->from('ins_nivel_academico z')->order_by('id_nivel_academico')->fetch();

// Obtiene nicel académico
$tipo_estudiante = $db->select('z.*')->from('ins_tipo_estudiante z')->order_by('id_tipo_estudiante')->fetch();

$paisA = $db->select('z.*')->from('sys_paises z')->order_by('id_pais')->fetch();
$departamentoA = $db->select('z.*')->from('sys_departamentos z')->order_by('id_departamento')->fetch();
$provinciaA = $db->select('z.*')->from('sys_provincias z')->order_by('id_provincia')->fetch();
$localidadA = $db->select('z.*')->from('sys_localidades z')->order_by('id_localidad')->fetch();

// Obtiene datos del familiar
$tutores = $db->query("select p.id_persona, p.nombres, p.primer_apellido, p.segundo_apellido, p.numero_documento, f.id_familiar FROM sys_persona p INNER JOIN ins_familiar f ON  (p.id_persona=f.persona_id) ORDER BY p.nombres")->fetch();

// Obtiene datos de los pagos
$pagos = $db->query("SELECT * FROM pen_pensiones p ORDER BY p.nombre_pension")->fetch();

// Obtiene datos de los tipos  de documentos
$documentos = $db->query("SELECT * FROM ins_tipo_documentos p ORDER BY p.nombre")->fetch();

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

// Obtiene los Turnos
$turnos = $db->select('*')->from('ins_turno')->where(array('estado' => 'A', 'gestion_id' => $id_gestion))->fetch();

// Obtiene los paises rude
$paises = $db->select('*')->from('ins_inscripcion_rude')->group_by('nac_pais')->where('nac_pais!=', '')->fetch();

// Obtiene los dep rude
$departamentos = $db->select('*')->from('ins_inscripcion_rude')->group_by('nac_departamento')->where('nac_departamento!=', '')->fetch();

// Obtiene los provicin rude
$provincias = $db->select('*')->from('ins_inscripcion_rude')->group_by('nac_provincia')->where('nac_provincia!=', '')->fetch();

// Obtiene los localidad rude
$localidades = $db->select('*')->from('ins_inscripcion_rude')->group_by('nac_localidad')->where('nac_localidad!=', '')->fetch();

// Obtiene los idioma materno rude
$cuatro_us = $db->query("SELECT s.411 AS cuatro
							FROM ins_inscripcion_rude AS s
							WHERE s.411 != ''
							GROUP BY  s.411")->fetch();

// Obtiene los idioma materno rude
$trabajos = $db->query("SELECT s.4521 AS trabajo
							FROM ins_inscripcion_rude AS s
							WHERE s.4521 != ''
							GROUP BY  s.4521")->fetch();

// Obtiene los idioma materno rude
$transportes = $db->query("SELECT s.461a AS transporte
							FROM ins_inscripcion_rude AS s
							WHERE s.461a != ''
							GROUP BY  s.461a")->fetch();

// Obtiene datos de la inscripcion para validar con los conceptos de pago
// $inscripcion = $db->query("SELECT * FROM ins_inscripcion i WHERE i.estudiante_id = $id_estudiante_editar AND i.gestion_id = $id_gestion")->fetch_first();
// $id_aula_paralelo = $inscripcion['aula_paralelo_id'];
// $id_nivel_academico = $inscripcion['nivel_academico_id'];
// $id_tipo_estudiante = $inscripcion['tipo_estudiante_id'];
// // Obtiene datos de los pagos
// $pagos = $db->query("SELECT * 
// FROM pen_pensiones p 
// WHERE p.gestion_id = $id_gestion AND p.nombre_pension != 'RESERVA' AND p.aula_paralelo_id = $id_aula_paralelo
// OR p.gestion_id = $id_gestion AND p.nombre_pension != 'RESERVA' AND  p.nivel_academico_id = $id_nivel_academico AND p.tipo_estudiante_id = $id_tipo_estudiante
// OR p.nombre_pension != 'RESERVA' AND p.tipo_concepto LIKE 'GENERAL'
// group by p.id_pensiones
// ORDER BY p.nombre_pension")->fetch();

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);
//$permiso_crear_familiar = in_array('crear-familiar', $_views);
?>

<?php require_once show_template('header-design'); ?>
<!--link rel="stylesheet" href="assets/themes/concept/assets/vendor/cropper/dist/cropper.min.css"-->
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/cropper-mazter/css/cropper.css">
<link href="assets/themes/concept/assets/vendor/datepicker/css/datepicker.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/bootstrap-fileinput-master/css/fileinput.css" rel="stylesheet">
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="page-header">
			<?php if ($id_estudiante_editar) : ?>
				<h2 class="pageheader-title" data-idtutor="">Editar Estudiante</h2>
				<p class="pageheader-text"></p>
				<div class="page-breadcrumb">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Estudiantes</a></li>
							<li class="breadcrumb-item active" aria-current="page">Editar Estudiantes</li>
						</ol>
					</nav>
				</div>
			<?php else : ?>
				<h2 class="pageheader-title" data-idtutor="">Registro Estudiante</h2>
				<p class="pageheader-text"></p>
				<div class="page-breadcrumb">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Estudiantes</a></li>
							<li class="breadcrumb-item active" aria-current="page">Registro Estudiantes</li>
						</ol>
					</nav>
				</div>
			<?php endif ?>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- end pageheader -->
<!-- ============================================================== -->
<!--div>
	<h5>Nivel Académico</h5>
	<label class="custom-control custom-radio custom-control-inline">
		<input type="radio" name="nivel" data-nivel="I" onclick="seleccionar_nivel_academico(this);" value="I" checked="" class="custom-control-input nivel"><span class="custom-control-label">Inicial</span>
	</label>
	<label class="custom-control custom-radio custom-control-inline">
		<input type="radio" name="nivel" data-nivel="P" onclick="seleccionar_nivel_academico(this);" value="P" class="custom-control-input nivel"><span class="custom-control-label">Primaria</span>
	</label>
	<label class="custom-control custom-radio custom-control-inline">
		<input type="radio" name="nivel" data-nivel="S" onclick="seleccionar_nivel_academico(this);" value="S" class="custom-control-input nivel"><span class="custom-control-label">Secundaria</span>
	</label>
</div-->

<div class="row">
	<!-- ============================================================== -->
	<!-- basic tabs  -->
	<!-- ============================================================== -->
	<div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
		<?php if ($id_estudiante_editar) : ?>
			<div class="card" style="background-color:#DFF2FF;">
			<?php else : ?>
				<div class="card">
				<?php endif ?>
				<div class="card-header">
					<div class="row">
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="" role="alert">
								<b>Registro de Padre o Tutor</b>
							</div>
						</div>
					</div>
				</div>
				<!-- ============================================================== -->
				<!-- datos -->
				<!-- ============================================================== -->
				<div class="card-body">
					<form id="form_familiar">
						<div class="form-row">
							<div class="col col-xl-12 col-lg-12 col-md-12 col-sm-16 col-xs-12">
								<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
								</div>
								<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12">
									<div class="row">
										<div class="col-12">
											<div class="list-group" id="result">
												<img src="assets/imgs/avatar.jpg" id="f_avatar" name="f_avatar" class="" style="width:auto; height:300px;">
											</div>
											<div class="list-group">
												<label class="list-group-item text-ellipsis">
													Subir Imagen
													<input type="file" class="sr-only" id="f_input" name="f_image" accept="image/*">
												</label>
												<!-- <a href="#" class="list-group-item text-ellipsis" data-suprimir="true">
												<span class="glyphicon glyphicon-eye-close"></span>
												<span>Eliminar imagen</span>
											</a> -->
											</div>
										</div>
									</div>
									<!--div class="progress">
												<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
											</div-->
								</div>
								<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
								</div>
							</div>

							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
								<br>
								<div class="">
									<label class="control-label">Nombres: </label>
									<div class="controls control-group">
										<input type="hidden" name="<?= $csrf; ?>">
										<input type="hidden" class="form-control" id="f_id_familiar" name="f_id_familiar">
										<input type="hidden" class="form-control" id="f_id_persona" name="f_id_persona">
										<input type="hidden" class="form-control" id="id_estudiante_editar" name="id_estudiante_editar">
										<input id="f_nombres" name="f_nombres" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();">
										<input type="hidden" id="f_nombre_imagen" name="f_nombre_imagen">
									</div>
								</div>
								<div class="" style="margin-top:2%">
									<label class="control-label">Primer Apellido: </label>
									<div class="controls control-group">
										<input id="f_primer_apellido" name="f_primer_apellido" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();">
									</div>
								</div>
								<div class="" style="margin-top:2%">
									<label class="control-label">Segundo Apellido: </label>
									<div class="controls control-group">
										<input id="f_segundo_apellido" name="f_segundo_apellido" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();">
									</div>
								</div>
								<div class="" style="margin-top:2%">
									<label class="control-label">Tipo de Documento: </label>
									<div class="controls control-group">
										<!-- <input id="tipo_documento" name="tipo_documento" type="text" class="form-control"> -->
										<select id="f_tipo_documento" name="f_tipo_documento" class="form-control">
											<option value="">Seleccione</option>
											<option value="1">CI</option>
											<option value="2">Pasaporte</option>
											<option value="3">CI extranjero</option>
										</select>
									</div>
								</div>

								<div class="" style="margin-top:2%">
									<label class="control-label">Número Documento: </label>
									<div class="controls control-group">
										<input id="f_numero_documento" name="f_numero_documento" type="text" class="form-control">
									</div>
								</div>
								<div class="" style="margin-top:2%">
									<label class="control-label">Expedido: </label>
									<div class="controls control-group">
										<!--input id="f_expedido" name="f_expedido" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();"-->
										<select name="f_expedido" id="f_expedido" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
											<option value="" selected="selected">Seleccionar</option>
											<?php foreach ($expedidos as $expedido) : ?>
												<option value="<?= $expedido['expedido']; ?>"><?= escape($expedido['expedido']); ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
								<div class="" style="margin-top:2%">
									<label class="control-label">Complemento: </label>
									<div class="controls control-group">
										<input id="f_complemento" name="f_complemento" type="text" class="form-control">
									</div>
								</div>
								<div class="" style="margin-top:2%">
									<label class="control-label">Nit: </label>
									<div class="controls control-group">
										<input id="f_nit" name="f_nit" type="text" class="form-control">
									</div>
								</div>
							</div>

							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
								<br>
								<div class="">
									<label class="control-label">Género: </label>
									<div class="controls">
										<label class="custom-control custom-radio custom-control-inline">
											<input type="radio" value="v" id="f_genero_v" name="f_genero" checked="" class="custom-control-input"><span class="custom-control-label">Varón</span>
										</label>
										<label class="custom-control custom-radio custom-control-inline" id="f_genero" name="f_genero">
											<input type="radio" value="m" id="f_genero_m" name="f_genero" class="custom-control-input"><span class="custom-control-label">Mujer</span>
										</label>
									</div>
								</div>
								<div class="" style="margin-top:2%">
									<label class="control-label">Fecha de Nacimiento: </label>
									<div class="controls control-group">
										<input id="f_fecha_nacimiento_tutor" name="f_fecha_nacimiento_tutor" type="date" class="form-control">
										<!-- <input type='text' class='datepicker-here form-control' id="f_fecha_nacimiento_tutor" name="f_fecha_nacimiento_tutor" readOnly /> -->
									</div>
								</div>
								<div class="" style="margin-top:2%">
									<label class="control-label">Idioma que habla frecuentemente: </label>
									<div class="controls control-group">
										<!-- <input id="f_idioma_frecuente" name="f_idioma_frecuente" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();"> -->

										<select name="f_idioma_frecuente" id="f_idioma_frecuente" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
											<option value="" selected="selected">Seleccionar</option>
											<?php foreach ($idiomas as $idioma) : ?>
												<option value="<?= $idioma['idioma_frecuente']; ?>"><?= escape($idioma['idioma_frecuente']); ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
								<div class="" style="margin-top:2%">
									<label class="control-label">Correo Electrónico: </label>
									<div class="controls control-group">
										<input id="f_correo_electronico" name="f_correo_electronico" type="email" class="form-control">
									</div>
								</div>
								<div class="" style="margin-top:2%">
									<label class="control-label">Teléfono: </label>
									<div class="controls control-group">
										<input id="f_telefono" name="f_telefono" type="text" class="form-control">
									</div>
								</div>
								<div class="" style="margin-top:2%">
									<label class="control-label">Ocupación: </label>
									<div class="controls control-group">
										<!--input id="f_profesion" name="f_profesion" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();"-->
										<select name="f_profesion" id="f_profesion" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
											<option value="" selected="selected">Seleccionar</option>
											<?php foreach ($profesiones as $profesion) : ?>
												<option value="<?= $profesion['profesion']; ?>"><?= escape($profesion['profesion']); ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>

								<div class="" style="margin-top:2%">
									<label class="control-label">Dirección de Oficina: </label>
									<div class="controls control-group">
										<input id="f_direccion_oficina" name="f_direccion_oficina" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();">
									</div>
									<br>
									<div class="">
										<label class="control-label">Es el tutor responsable: </label>
										<div class="controls">
											<label class="custom-control custom-radio custom-control-inline">
												<input type="radio" value="0" id="f_tutor_no" name="f_tutor" checked="" class="custom-control-input"><span class="custom-control-label">No</span>
											</label>
											<label class="custom-control custom-radio custom-control-inline" id="f_tutor" name="f_tutor">
												<input type="radio" value="1" id="f_tutor_si" name="f_tutor" class="custom-control-input"><span class="custom-control-label">Si</span>
											</label>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="" style="margin-top:2%" id="div_parentesco">
							<label class="control-label">Mayor grado de instruccion alcanzado : </label>
							<div class="controls control-group">
								<!--input id="f_grado_instruccion" name="f_grado_instruccion" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();"-->
								<select name="f_grado_instruccion" id="f_grado_instruccion" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
									<option value="" selected="selected">Seleccionar</option>
									<?php foreach ($grados as $grado) : ?>
										<option value="<?= $grado['grado_instruccion']; ?>"><?= escape($grado['grado_instruccion']); ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>

						<div class="" style="margin-top:2%" id="div_parentesco">
							<label class="control-label">Cual es el parentesco con el estudiante? : </label>
							<div class="controls control-group">
								<!-- <input id="f_parentesco" name="f_parentesco" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();"> -->
								<div class="controls control-group">
									<!-- <input id="tipo_documento" name="tipo_documento" type="text" class="form-control"> -->
									<select name="f_parentesco" id="f_parentesco" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
										<option value="" selected="selected">Seleccionar</option>
										<?php foreach ($parentescos as $parentesco) : ?>
											<option value="<?= $parentesco['parentesco']; ?>"><?= escape($parentesco['parentesco']); ?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
						</div>
						<br>
						<div align="right">
							<button type="submit" class="btn btn-info" onclick="refrescarPagina()" id="btn_agregar_nuevo_familiar">Crear Nuevo Tutor</button>
							<button type="submit" class="btn btn-primary" id="btn_agregar_familiar">Guardar</button>
							<button type="button" class="btn btn-success" onclick="limpiarFormulario()">Limpiar</button>

						</div>
					</form>
					<br>
					<div id="tabla_tutores_temporal">
						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<div class="alert alert-success" role="alert">
									<div class="table-responsive">
										<table id="table" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
											<thead>
												<tr class="active">
													<th class="text-nowrap text-center">NOMBRE COMPLETO</th>
													<th class="text-nowrap text-center">TUTOR</th>
												</tr>
											</thead>
											<tbody id="lista_familia_temporal">

											</tbody>
										</table>
									</div>
									<!-- <b id="lista_familia_temporal"></b> -->
								</div>
							</div>
						</div>
					</div>

					<div id="tabla_tutores">
						<div class="card-body">
							<div class="row">
								<div class="table-responsive">
									<table id="table" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
										<thead>
											<tr class="active">
												<th class="text-nowrap text-center">#</th>
												<th class="text-nowrap text-center">Foto</th>
												<th class="text-nowrap text-center">Primer Apellido</th>
												<th class="text-nowrap text-center">Segundo Apellido</th>
												<th class="text-nowrap text-center">Nombre Completo</th>
												<th class="text-nowrap text-center">Tutor</th>
												<th class="text-nowrap text-center">Opciones</th>
											</tr>
										</thead>
										<tbody id="contenedor_familiares">

										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="border-top card-footer p-0"></div>
					</div>

				</div>
				<!-- ============================================================== -->
				<!-- end datos -->
				<!-- ============================================================== -->
				</div>
			</div>

			<!--div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-5"-->
			<div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
				<!-- menu del tab -->
				<div class="tab-regular">
					<ul class="nav nav-tabs nav-fill" id="myTab2" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="inscripcion-tab" data-toggle="tab" href="#inscripcion" role="tab" aria-controls="inscripcion" aria-selected="true">Inscripción</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="personales-tab" data-toggle="tab" href="#personales" role="tab" aria-controls="personales" aria-selected="false">Datos Estudiante</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="complemento_estudiante-tab" data-toggle="tab" href="#complemento_estudiante" role="tab" aria-controls="complemento_estudiante" aria-selected="false">RUDE Parte I</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="rude-tab" data-toggle="tab" href="#rude" role="tab" aria-controls="rude" aria-selected="false">RUDE Parte II</a>
						</li>
						<!-- <li class="nav-item">
							<a class="nav-link" id="pagos-tab" data-toggle="tab" href="#pagos" role="tab" aria-controls="pagos" aria-selected="false">Pagos</a>
						</li> -->
						<li class="nav-item">
							<a class="nav-link" id="rep_documentos-tab" data-toggle="tab" href="#rep_documentos" role="tab" aria-controls="inscripcion" aria-selected="false">Recepcion de documentos</a>
						</li>
						<!-- <li class="nav-item">
					<a class="nav-link" id="documentos-tab" data-toggle="tab" href="#documentos" role="tab" aria-controls="documentos" aria-selected="false">Documentos</a>
				</li> -->
						<li class="nav-item">
							<a class="nav-link" id="vacunas-tab" data-toggle="tab" href="#vacunas" role="tab" aria-controls="vacunas" aria-selected="false">Vacunas</a>
						</li>
					</ul>

					<?php if ($id_estudiante_editar) : ?>
						<div class="tab-content" id="myTabContent2" style="background-color:#DFF2FF;">
						<?php else : ?>
							<div class="tab-content" id="myTabContent2">
							<?php endif ?>

							<div class="tab-pane fade  show active" id="inscripcion" role="tabpanel" aria-labelledby="inscripcion-tab">

								<form id="form_inscripcion">
									<div class="influence-profile-content pills-regular">
										<div class="tab-content" id="pills-tabContent">
											<div class="tab-pane fade show active" id="pills-campaign" role="tabpanel" aria-labelledby="pills-campaign-tab">

												<input type="hidden" id="id_inscripciones" name="id_inscripciones">
												
												<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
													<div class="section-block">
														<input type="hidden" name="ids_familar" id="ids_familar" value="">
														<h3 class="section-title">Curso a Inscribir</h3>
													</div>
												</div>
												<div class="row">
													<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
														<div class="card  alert-primary-">
															<div class="card-body">
																<h4 class="mb-1">Tipo de estudiante</h4>
																<div class="control-group">
																	<select name="tipo_estudiante" id="tipo_estudiante" class="form-control">
																		<option value="" selected="selected" disabled>Seleccionar</option>
																		<?php foreach ($tipo_estudiante as $value) : ?>
																			<option value="<?= $value['id_tipo_estudiante']; ?>"><?= escape($value['nombre_tipo_estudiante']); ?></option>
																		<?php endforeach ?>
																	</select>
																</div>
															</div>
														</div>
													</div>

													<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
														<div class="card  alert-primary-">
															<div class="card-body">

																<h4 class="mb-1">Turno </h4>
																<div class="control-group">
																	<select name="turno" id="turno" class="form-control">
																		<option value="" selected="selected">Seleccionar</option>
																		<?php foreach ($turnos as $value) : ?>
																			<option value="<?= $value['id_turno']; ?>"><?= escape($value['nombre_turno']); ?></option>
																		<?php endforeach ?>
																	</select>
																</div>
																<br>
															<h4 class="mb-1">Nivel</h4>
																<div class="control-group">
																	<select name="nivel_academico" id="nivel_academico" class="form-control" onchange="listar_cursos();">
																		<option value="" selected="selected">Seleccionar</option>
																		<?php foreach ($nivel as $value) : ?>
																			<option value="<?= $value['id_nivel_academico']; ?>"><?= escape($value['nombre_nivel']); ?></option>
																		<?php endforeach ?>
																	</select>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
														<div class="card  alert-primary-">
															<div class="card-body">
																<h4 class="mb-1">Curso</h4>
																<div class="control-group">
																	<select name="select_curso" id="select_curso" onchange="listar_vacantes();" class="form-control">
																		<option value="" selected="selected">Seleccionar</option>
																	</select>
																</div>
															</div>

															<div class="card-body">
																<h4 class="mb-1">Vacantes</h4>
																<div class="control-group">
																	<input type="text" class="form-control-plaintext" name="vacantes" id="vacantes">
																	<!--select name="select_paralelo" id="vacantes" class="form-control">
													</select-->
																</div>
															</div>
														</div>
													</div>

													<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
														<div class="card  alert-primary-">
															<div class="card-body">
																<h4 class="mb-1">Informacion de Curso</h4>
																<div class="control-group">
																	<input type="text" class="form-control-plaintext" name="nro_ninos" id="nro_ninos" value="#niños">
																	<input type="text" class="form-control-plaintext" name="nro_ninas" id="nro_ninas" value="#niñas">
																	<hr>
																	<input type="text" class="form-control-plaintext" name="inscritos" id="inscritos" value="#inscritos">
																	<hr>
																	<input type="text" class="form-control-plaintext" name="cupo_total" id="cupo_total" value="#cupos"> </div>
															</div>
														</div>
													</div>

												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

										</div>
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-right">
											<!-- <button type="submit" class="btn btn-primary pull-right" onclick="atrasVacunas()">Atras</button> -->
											<button type="button" id="nuevo_familiar_editar" class="btn btn-success" onclick="volverInscribir()">Registrar Nuevo Familiar</button>
											<button type="submit" class="btn btn-primary pull-right" id="btn_inscripcion">Guardar</button>
										</div>
										<!--input type="hidden" id="correo" onclick="correo_prueba();" class="btn btn-secondary" value="correo"-->
									</div>
								</form>
							</div>


							<?php if ($id_estudiante_editar) : ?>
								<div class="tab-pane fade" id="personales" role="tabpanel" aria-labelledby="personales-tab" style="background-color:#DFF2FF;">
								<?php else : ?>
									<div class="tab-pane fade" id="personales" role="tabpanel" aria-labelledby="personales-tab">
									<?php endif ?>

									<div class="row">
										<!-- Formulario de registro de los hijos -->
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
											<h5 class="section-title">2. DATOS DE LA O EL ESTUDIANTE</h5>
											<form id="form_datos_personales">
												<div class="form-row">
													<div class="col col-xl-4 col-lg-4 col-md-12 col-sm-6 col-xs-12">
														<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
															<div class="row">
																<div class="col-12">
																	<div class="list-group" id="result">
																		<img src="assets/imgs/avatar.jpg" id="avatar" name="avatar" class="" style="width:auto; height:300px;">
																	</div>
																	<div class="list-group">
																		<label class="list-group-item text-ellipsis">
																			Subir Imagen
																			<input type="file" class="sr-only" id="input" name="image" accept="image/*">
																		</label>
																		<!-- <a href="#" class="list-group-item text-ellipsis" data-suprimir="true">
															<span class="glyphicon glyphicon-eye-close"></span>
															<span>Eliminar imagen</span>
														</a> -->
																	</div>
																</div>
															</div>
															<!--div class="progress">
												<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
											</div-->
														</div>
													</div>
													<div class="col col-xl-4 col-lg-4 col-md-12 col-sm-6 col-xs-12">
														<h5 class="section-title">2.1. NOMBRES Y APELLIDOS</h5>
														<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
															<label class="control-label">Nombres: </label>
															<div class="controls control-group">
																<input type="hidden" id="id_estudiante" name="id_estudiante">
																<input type="hidden" id="id_persona" name="id_persona">
																<input type="hidden" id="id_turno" name="id_turno">
																<input id="nombres" name="nombres" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();">
																<input type="hidden" id="nombre_imagen" name="nombre_imagen">
															</div>
															<!--input type="file" id="imagen_cortada" name="imagen_cortada" -->
														</div>
														<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
															<label class="control-label">Primer Apellido: </label>
															<div class="controls control-group">
																<input id="primer_apellido" name="primer_apellido" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();">
															</div>
														</div>
														<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
															<label class="control-label">Segundo Apellido: </label>
															<div class="controls control-group">
																<input id="segundo_apellido" name="segundo_apellido" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();">
															</div>
														</div>
														<h5 class="section-title">2.2. DOCUMENTOS DE IDENTIFICACION</h5>
														<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
															<label class="control-label">Tipo de Documento: </label>
															<div class="controls control-group">
																<select id="tipo_documento" name="tipo_documento" class="form-control">
																	<option value="">Seleccione</option>
																	<option value="1">CI</option>
																	<option value="2">Pasaporte</option>
																	<option value="3">CI extranjero</option>
																</select>
															</div>
														</div>
														<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
															<label class="control-label">Número de Documento: </label>
															<div class="controls control-group">
																<input id="numero_documento" name="numero_documento" type="text" class="form-control" data-validation-url="?/s-inscripciones/validar_documento">
															</div>
														</div>
														<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
															<label class="control-label">Expedido: </label>
															<div class="controls control-group">
																<select name="expedido" id="expedido" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
																	<option value="" selected="selected">Seleccionar</option>
																	<?php foreach ($expedidos as $expedido) : ?>
																		<option value="<?= $expedido['expedido']; ?>"><?= escape($expedido['expedido']); ?></option>
																	<?php endforeach ?>
																</select>
															</div>
														</div>
														<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
															<label class="control-label">Complemento: </label>
															<div class="controls control-group">
																<input id="complemento" name="complemento" type="text" class="form-control">
															</div>
														</div>
													</div>

													<div class="col col-xl-4 col-lg-4 col-md-12 col-sm-6 col-xs-12">
														<h5 class="section-title">2.3. SEXO</h5>
														<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
															<label class="control-label">Género: </label>
															<div class="controls control-group">
																<label class="custom-control custom-radio custom-control-inline">
																	<input type="radio" id="genero_v" name="genero" value="v" checked="" class="custom-control-input"><span class="custom-control-label">Varón</span>
																</label>
																<label class="custom-control custom-radio custom-control-inline">
																	<input type="radio" id="genero_m" name="genero" value="m" class="custom-control-input"><span class="custom-control-label">Mujer</span>
																</label>
															</div>
														</div>
														<h5 class="section-title">2.5. FECHA DE NACIMIENTO</h5>
														<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
															<label for="title" class="control-label">Fecha de Nacimiento: </label>
															<div class="controls control-group">
															<input id="fecha_nacimiento" name="fecha_nacimiento" type="date" class="form-control">
																<!-- <input type='text' class='datepicker-here form-control' id="fecha_nacimiento" name="fecha_nacimiento" readOnly /> -->
															</div>
														</div>
														<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
															<!--label class="control-label">Dirección: </label-->
															<div class="controls">
																<input id="direccion" name="direccion" type="HIDDEN" class="form-control">
															</div>
														</div>
														<div>
															<h5 class="section-title">El estudiante es Incorporado ?</h5>
															<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
																<div class="controls control-group">
																	<label class="custom-control custom-radio custom-control-inline">
																		<input type="radio" id="no_incorporado" name="incorporado" value="0" checked="" class="custom-control-input"><span class="custom-control-label">No</span>
																	</label>
																	<label class="custom-control custom-radio custom-control-inline">
																		<input type="radio" id="si_incorporado" name="incorporado" value="1" class="custom-control-input"><span class="custom-control-label">Si</span>
																	</label>
																</div>
															</div>
														</div>
														<br>
														<div>
															<h5 class="section-title">Desea hacer la reserva del Curso ?</h5>
															<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
																<div class="controls control-group">
																	<label class="custom-control custom-radio custom-control-inline">
																		<input type="radio" id="no_reserva" name="estado_reserva" value="0" checked="" class="custom-control-input"><span class="custom-control-label">No</span>
																	</label>
																	<label class="custom-control custom-radio custom-control-inline">
																		<input type="radio" id="si_reserva" name="estado_reserva" value="1" class="custom-control-input"><span class="custom-control-label">Si</span>
																	</label>
																</div>
															</div>
														</div>
														<div id="panel_reserva">
															<div class="" style="margin-top:2%">
																<label class="control-label">Monto para reservar: </label>
																<div class="controls control-group">
																	<input id="monto_reserva" name="monto_reserva" type="text" class="form-control">
																</div>
															</div>
															<br>
															<div class="" style="margin-top:2%">
																<label for="title" class="control-label">Fecha limite de la reserva: </label>
																<div class="controls control-group">
																	<input type='text' class='datepicker-here form-control' id="fecha_reserva" name="fecha_reserva" readOnly />
																</div>
															</div>
														</div>
													</div>

												</div><br><br>
												<div align="right">
													<button type="button" class="btn btn-success pull-left"><span class="fa fa-arrow-left" onclick="atrasInscripcion()"> Atras</span></button>
													<button type="submit" class="btn btn-primary" id="btn_guardar_estudiante">Guardar</button>
													<button type="submit" class="btn btn-danger" id="btn_reservar_guardar">Guardar y Reservar</button>
													<button type="submit" class="btn btn-success"><span class="fa fa-arrow-right"> Siguiente</span></button>
												</div>
											</form>
										</div>
										<!-- fin formulario hijo -->
									</div>
									<!-- fin row -->
									</div>

									<!-- fin tab padre estudiante -->
									<div class="tab-pane fade" id="complemento_estudiante" role="tabpanel" aria-labelledby="complemento_estudiante-tab">
										<div class="card">
											<div class="card-body">
												<div class="row">
													<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
														<div class="media influencer-profile-data d-flex align-items-center p-2">
															<input type="hidden" id="id_familiares" name="id_familiares">
															<div class="media-body">
																<div class="influencer-profile-data">
																	<div class="row">
																		<form id="form_datos_certificado">
																			<div class="form-row">
																				<h5 class="section-title">2.4. LUGAR DE NACIMIENTO</h5>
																				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
																					<label for="title" class="control-label">Pais: </label>
																					<div class="controls control-group">
																						<select name="pais" id="pais" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
																							<option value="" selected="selected">Seleccionar</option>
																							<?php foreach ($paises as $pais) : ?>
																								<option value="<?= $pais['nac_pais']; ?>"><?= escape($pais['nac_pais']); ?></option>
																							<?php endforeach ?>
																						</select>
																					</div>

																				</div>
																				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
																					<label for="title" class="control-label">Departamento: </label>
																					<div class="controls control-group">
																						<select name="departamento" id="departamento" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
																							<option value="" selected="selected">Seleccionar</option>
																							<?php foreach ($departamentos as $departamento) : ?>
																								<option value="<?= $departamento['nac_departamento']; ?>"><?= escape($departamento['nac_departamento']); ?></option>
																							<?php endforeach ?>
																						</select>
																					</div>
																				</div>
																				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
																					<label for="title" class="control-label">Provincia: </label>
																					<div class="controls control-group">
																						<select name="provincia" id="provincia" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
																							<option value="" selected="selected">Seleccionar</option>
																							<?php foreach ($provincias as $provincia) : ?>
																								<option value="<?= $provincia['nac_provincia']; ?>"><?= escape($provincia['nac_provincia']); ?></option>
																							<?php endforeach ?>
																						</select>
																					</div>
																				</div>
																				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
																					<div class="controls control-group">
																						<select name="localidad" id="localidad" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
																							<option value="" selected="selected">Seleccionar</option>
																							<?php foreach ($localidades as $localidad) : ?>
																								<option value="<?= $localidad['nac_localidad']; ?>"><?= escape($localidad['nac_localidad']); ?></option>
																							<?php endforeach ?>
																						</select>
																					</div>
																				</div>

																				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																					<h5 class="section-title">2.4. CODIGO RUDE</h5>
																					<div class="">
																						<div class="controls control-group">
																							<input type="hidden" name="<?= $csrf; ?>">
																							<!-- <input type="text" id="id_inscripcion_rude" name="id_inscripcion_rude" value=""> -->
																							<input type="hidden" id="id_inscripcion_rude" name="id_inscripcion_rude" value="">
																							<input type="hidden" id="id_inscripcion" name="id_inscripcion" value="">
																							<input id="nro_rude" name="nro_rude" type="text" class="form-control">
																						</div>
																					</div>
																				</div>

																				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																					<br>
																					<h5 class="section-title">2.5. ¿El/La estudiante presenta alguna discapacidad (No pase a 2.6)?</h5>
																					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
																						<div class="controls control-group">
																							<label class="custom-control custom-radio custom-control-inline">
																								<input type="radio" id="si_disc" name="discapacidad" value="0" checked="" class="custom-control-input"><span class="custom-control-label">No</span>
																							</label>
																							<label class="custom-control custom-radio custom-control-inline">
																								<input type="radio" id="no_disc" name="discapacidad" value="1" class="custom-control-input"><span class="custom-control-label">Si</span>
																							</label>
																						</div>
																					</div>
																				</div>


																				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																					<div class="">
																						<label class="control-label">Nro de registro de discapacidad o IBC: </label>
																						<div class="controls control-group">
																							<input type="hidden" name="<?= $csrf; ?>">
																							<input id="nro_ibc" name="nro_ibc" type="text" class="form-control">
																						</div>
																					</div>
																					<br>
																					<label class="control-label">Tipo de Discapacidad: </label>
																					<div class="control-group">
																						<select name="tipo_discapacidad" id="tipo_discapacidad" onchange="listar_vacantes();" class="form-control">
																							<option value="" selected="selected">Seleccionar</option>
																							<option value="1">PSIQUICA</option>
																							<option value="2">AUTISMO</option>
																							<option value="3">SINDROME DE DOWN</option>
																							<option value="4">INTELACTUAL</option>
																							<option value="5">AUDITIVA</option>
																							<option value="6">FISICA-MOTORA</option>
																							<option value="7">SORDOSEGUERA</option>
																							<option value="8">MULTIPLE</option>
																							<option value="9">VISUAL</option>
																						</select>
																					</div>
																				</div>

																				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																					<label class="control-label">Grado de Discapacidad: </label>
																					<div class="control-group">
																						<select name="grado_discapacidad" id="grado_discapacidad" onchange="listar_vacantes();" class="form-control">
																							<option value="" selected="selected">Seleccionar</option>
																							<option value="1">LEVE</option>
																							<option value="2">MODERADO</option>
																							<option value="3">GRAVE</option>
																							<option value="4">MUY GRAVE</option>
																							<option value="5">CEGUERA TOTAL</option>
																							<option value="6">BAJA VISION</option>
																						</select>
																					</div>
																				</div>


																				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																					<br>
																					<h5 class="section-title">2.6. CERTIFICADO DE NACIMIENTO</h5>
																				</div>
																				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																					<div class="">
																						<label class="control-label">Oficialia N°: </label>
																						<div class="controls control-group">
																							<input type="hidden" name="<?= $csrf; ?>">
																							<input id="oficialia" name="oficialia" type="text" class="form-control">
																						</div>
																					</div>
																					<div class="" style="margin-top:2%">
																						<label class="control-label">Libro N°: </label>
																						<div class="controls control-group">
																							<input id="libro" name="libro" type="text" class="form-control">
																						</div>
																					</div>
																				</div>

																				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																					<div class="" style="margin-top:2%">
																						<label class="control-label">Partida N°: </label>
																						<div class="controls control-group">
																							<input id="partida" name="partida" type="text" class="form-control">
																						</div>
																					</div>
																					<div class="" style="margin-top:2%">
																						<label class="control-label">Folio N°: </label>
																						<div class="controls control-group">
																							<input id="folio" name="folio" type="text" class="form-control">
																						</div>
																					</div>
																				</div>

																				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																					<h5 class="section-title">3. DIRECCION ACTUAL DE LA O EL ESTUDIANTE </h5>
																				</div>

																				<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
																					<div class="" style="margin-top:2%">
																						<label class="control-label">Departamento: </label>
																						<div class="controls control-group">
																							<select id="departamento_rude" name="departamento" class="form-control" onchange="listar_provincias_rude();">
																								<option value="" selected="selected">Seleccionar</option>
																								<?php foreach ($departamentoA as $value) : ?>
																									<option value="<?= $value['id_departamento']; ?>"><?= escape($value['nombre']); ?></option>
																								<?php endforeach ?>
																							</select>
																						</div>
																					</div>
																				</div>
																				<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
																					<div class="" style="margin-top:2%">
																						<label class="control-label">Provincia: </label>
																						<div class="controls control-group">
																							<select id="provinciar_rude" name="provincia" class="form-control">
																								<option value="" selected="selected">Seleccionar</option>
																								<?php // foreach ($provincia as $value) : 
																								?>
																								<!--	<option value="<? //= $value['id_provincia']; 
																														?>"><? //= escape($value['nombre']); 
																										?></option>-->
																								<?php  //endforeach 
																								?>
																							</select>
																						</div>
																					</div>
																				</div>
																				<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
																					<div class="" style="margin-top:2%">
																						<label class="control-label">Seccion/Municipio: </label>
																						<div class="controls control-group">
																							<input id="seccion" name="seccion" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();">
																						</div>
																					</div>
																				</div>
																				<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
																					<div class="" style="margin-top:2%">
																						<label class="control-label">Localidad/Comunidad: </label>
																						<div class="controls control-group">
																							<input id="localidad" name="localidad" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();">
																						</div>
																					</div>
																				</div>
																			</div>

																			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																				<div class="" style="margin-top:2%">
																					<label class="control-label">Zona/Villa: </label>
																					<div class="controls control-group">
																						<input id="zona" name="zona" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();">
																					</div>
																				</div>
																				<div class="" style="margin-top:2%">
																					<label class="control-label">Avenida/Calle: </label>
																					<div class="controls control-group">
																						<input id="avenida" name="avenida" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();">
																					</div>
																				</div>
																			</div>
																			<div class="row">
																				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																						<div class="" style="margin-top:2%">
																							<label class="control-label">Nro Vivienda: </label>
																							<div class="controls control-group">
																								<input id="nrovivienda" name="nrovivienda" type="text" class="form-control">
																							</div>
																						</div>
																						<div class="" style="margin-top:2%">
																							<label class="control-label">Telefono fijo: </label>
																							<div class="controls control-group">
																								<input id="telefono" name="telefono" type="text" class="form-control">
																							</div>
																						</div>
																					</div>

																					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																						<div class="" style="margin-top:2%">
																							<label class="control-label">Celular de contacto: </label>
																							<div class="controls control-group">
																								<input id="celular" name="celular" type="text" class="form-control">
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																	</div>
																	<br>
																	<div align="right">
																		<button type="button" class="btn btn-success" onclick="atrasInicio()">Atras</button>
																		<button type="submit" class="btn btn-primary" id="btn_agregar_familiar">Guardar</button>
																		<button type="submit" class="btn btn-success" id="btn_agregar_familiar">Siguiente</button>
																	</div>
																	</form>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>


									<div class="tab-pane fade" id="rude" role="tabpanel" aria-labelledby="rude-tab">
										<div class="card">
											<div class="card-body">
												<div class="row">
													<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
														<div class="media influencer-profile-data d-flex align-items-center p-2">
															<div class="media-body">
																<div class="influencer-profile-data">
																	<div class="row">
																		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																			<h5 class="section-title">4. ASPECTOS SOCIOECONOMICOS DE LA O EL ESTUDIANTE</h5>
																			<h5 class="section-title">4.1. IDIOMA Y PERTENECIA CULTURAL.</h5>
																			<div>
																				<form id="form_datos_rude" name="form_datos_rude">
																					<div class="form-row">

																						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																							<div class="">
																								<br>
																								<label class="control-label">4.1.1. ¿Cual es el idioma con el cual aprendio a hablar en su niñez?: </label>
																								<div class="controls control-group">
																									<select name="a" id="a" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
																										<option value="" selected="selected">Seleccionar</option>
																										<?php foreach ($cuatro_us as $cuatro_u) : ?>
																											<option value="<?= $cuatro_u['cuatro']; ?>"><?= escape($cuatro_u['cuatro']); ?></option>
																										<?php endforeach ?>
																									</select>
																								</div>
																							</div>

																							<div class="">
																								<label class="control-label">4.1.2. ¿Que idioma(s) habla frecuentemente? (añada todas las necesarias): </label>
																								<div class="controls control-group">
																									<select name="b" id="b" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
																										<option value="" selected="selected">Seleccionar</option>
																										<?php foreach ($cuatro_us as $cuatro_u) : ?>
																											<option value="<?= $cuatro_u['cuatro']; ?>"><?= escape($cuatro_u['cuatro']); ?></option>
																										<?php endforeach ?>
																									</select>
																								</div>
																							</div>

																							<div class="">
																								<label class="control-label">4.1.3. ¿Pertenece a una nacion, pueblo indigena originaria campesino o afroboliviano?: </label>
																								<div class="controls control-group">
																									<select id="c" name="c" class="form-control">
																										<option value="">Seleccione</option>
																										<option value="NINGUNO">NINGUNO</option>
																										<option value="AFROBOLIVIANO">AFROBOLIVIANO</option>
																										<option value="ESSE EJA">ESSE EJA</option>
																										<option value="AYMARA">AYMARA</option>
																										<option value="AYOROA">AYOROA</option>
																										<option value="BAURES">BAURES</option>
																										<option value="CANICHANA">CANICHANA</option>
																										<option value="CABINEÑO">CABINEÑO</option>
																										<option value="CAYUBABA">CAYUBABA</option>
																										<option value="CHACOBO">CHACOBO</option>
																										<option value="CHIMAN">CHIMAN</option>
																										<option value="CHIQUITANO(MONKOX)">CHIQUITANO(MONKOX)</option>
																										<option value="ESE EJJA">ESE EJJA</option>
																										<option value="GUARANI">GUARANI</option>
																										<option value="GUARASUG WE">GUARASUGWE</option>
																										<option value="ITOMANO">ITOMANO</option>
																										<option value="LECO">LECO</option>
																										<option value="KALLAWAYA">KALLAWAYA</option>
																										<option value="MACHINERI">MACHINERI</option>
																										<option value="MAROPA">MAROPA</option>
																										<option value="MOJOS-IGNACIANO">MOJOS-IGNACIANO</option>
																										<option value="MOJOS-TRINITARIO">MOJOS-TRINITARIO</option>
																										<option value="MORE">MORE</option>
																										<option value="MOSETEN">MOSETEN</option>
																										<option value="MOVIMA">MOVIMA</option>
																										<option value="PACAWARA">PACAWARA</option>
																										<option value="PUKINA">PUKINA</option>
																										<option value="QUECHUA">QUECHUA</option>
																										<option value="SIRIONO">SIRIONO</option>
																										<option value="TACANA">TACANA</option>
																										<option value="TAPIETE">TAPIETE</option>
																										<option value="TOROMONA">TOROMONA</option>
																										<option value="URU CHIPAYA">URU CHIPAYA</option>
																										<option value="WEENHAYEK">WEENHAYEK</option>
																										<option value="YAMANAHUA">YAMANAHUA</option>
																										<option value="YUKI">YUKI</option>
																										<option value="YUCARARE">YUCARARE</option>
																									</select>
																								</div>
																							</div>
																							<br>
																							<h5 class="section-title">4.2. SALUD DE LA O EL ESTUDIANTE.</h5>
																							<br>
																							<div>
																								<label class="section-title">4.2.1. ¿Existe algun centro de Salud/Posta/Hospital en su comunidad/barrio/zona?</label>
																								<div class="form-group row">
																									<label class="col-12 col-sm-6 col-form-label text-sm-right"></label>
																									<div class="">
																										<div class="controls">
																											<label class="custom-control custom-radio custom-control-inline">
																												<input type="radio" value="0" id="no_d" name="d" checked="" class="custom-control-input"><span class="custom-control-label">No</span>
																											</label>
																											<label class="custom-control custom-radio custom-control-inline" id="d" name="d">
																												<input type="radio" value="1" id="si_d" name="d" class="custom-control-input"><span class="custom-control-label">Si</span>
																											</label>
																										</div>
																									</div>
																								</div>
																							</div>

																							<label class="section-title">4.2.2. ¿El año pasado por problemas de salud, acudio o se atendio en...? (Puede marcar mas de una opcion)</label>
																						</div>

																						<!-- columnas -->

																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4221" name="salud[0]" value="4221">
																								<label class="custom-control-label" for="4221">1. Caja o seguro de salud</label>
																							</div>

																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4222" name="salud[1]" value="4222">
																								<label class="custom-control-label" for="4222">2. Estableciminetos de salud publica</label>
																							</div>

																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4223" name="salud[2]" value="4223">
																								<label class="custom-control-label" for="4223">3. Estableciminetos de salud privada</label>
																							</div>
																						</div>

																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4224" name="salud[3]" value="4224">
																								<label class="custom-control-label" for="4224">4. En su vivienda</label>
																							</div>

																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4225" name="salud[4]" value="4225">
																								<label class="custom-control-label" for="4225">5. Medicina Tradicional</label>
																							</div>

																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4226" name="salud[5]" value="4226">
																								<label class="custom-control-label" for="4226">6. La farmacia sin receta medica(automedicación)</label>
																							</div>
																						</div>

																						<!-- columnas -->

																						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																							<br>
																							<label class="section-title">4.2.3. El año pasado ¿Cuantas veces fue al Centro de salud?</label>
																						</div>

																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																							<div class="controls control-group">
																								<select id="e" name="e" class="form-control">
																									<option value="">Seleccione</option>
																									<option value="1 a 2 veces">1 a 2 veces</option>
																									<option value="3 a 5 veces">3 a 5 veces</option>
																									<option value="6 o mas">6 o mas</option>
																									<option value="Ninguna">Ninguna</option>
																								</select>
																							</div>
																						</div>

																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">

																						</div>

																						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																							<br>
																							<label class="section-title">4.2.4. ¿Tiene seguro de salud?</label>
																							<div>
																								<div class="">
																									<div class="controls">
																										<label class="custom-control custom-radio custom-control-inline">
																											<input type="radio" value="0" id="no_f" name="f" checked="" class="custom-control-input"><span class="custom-control-label">No</span>
																										</label>
																										<label class="custom-control custom-radio custom-control-inline" id="f" name="f">
																											<input type="radio" value="1" id="si_f" name="f" class="custom-control-input"><span class="custom-control-label">Si</span>
																										</label>
																									</div>
																								</div>
																							</div>
																						</div>
																						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																							<br>
																							<h5 class="section-title">4.3. ACCESO DE LA O EL ESTUDIANTE A LOS SERVICIOS BASICOS.</h5>
																							<br>
																						</div>
																						<!-- columnas -->
																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																							<br>
																							<label class="section-title">4.3.1. ¿Tiene acceso a agua por cañeria de red?</label>
																							<div>
																								<div class="">
																									<div class="controls">
																										<label class="custom-control custom-radio custom-control-inline">
																											<input type="radio" value="0" id="no_g" name="g" checked="" class="custom-control-input"><span class="custom-control-label">No</span>
																										</label>
																										<label class="custom-control custom-radio custom-control-inline" id="g" name="g">
																											<input type="radio" value="1" id="si_g" name="g" class="custom-control-input"><span class="custom-control-label">Si</span>
																										</label>
																									</div>
																								</div>
																							</div>

																							<label class="section-title">4.3.2. ¿Tiene baño en su vivienda?</label>
																							<div>
																								<div class="">
																									<div class="controls">
																										<label class="custom-control custom-radio custom-control-inline">
																											<input type="radio" value="0" id="no_h" name="h" checked="" class="custom-control-input"><span class="custom-control-label">No</span>
																										</label>
																										<label class="custom-control custom-radio custom-control-inline" id="h" name="h">
																											<input type="radio" value="1" id="si_h" name="h" class="custom-control-input"><span class="custom-control-label">Si</span>
																										</label>
																									</div>
																								</div>
																							</div>

																							<label class="section-title">4.3.3. ¿Tiene red de alcantarillado?</label>
																							<div>

																								<div class="">
																									<div class="controls">
																										<label class="custom-control custom-radio custom-control-inline">
																											<input type="radio" value="0" id="no_i" name="i" checked="" class="custom-control-input"><span class="custom-control-label">No</span>
																										</label>
																										<label class="custom-control custom-radio custom-control-inline" id="i" name="i">
																											<input type="radio" value="1" id="si_i" name="i" class="custom-control-input"><span class="custom-control-label">Si</span>
																										</label>
																									</div>
																								</div>
																							</div>

																							<label class="section-title">4.3.4. ¿Usa energia electrica para alumbrar su vivienda?</label>
																							<div>
																								<div class="">
																									<div class="controls">
																										<label class="custom-control custom-radio custom-control-inline">
																											<input type="radio" value="0" id="no_j" name="j" checked="" class="custom-control-input"><span class="custom-control-label">No</span>
																										</label>
																										<label class="custom-control custom-radio custom-control-inline" id="j" name="j">
																											<input type="radio" value="1" id="si_j" name="j" class="custom-control-input"><span class="custom-control-label">Si</span>
																										</label>
																									</div>
																								</div>
																							</div>
																						</div>

																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																							<br>
																							<label class="section-title">4.3.5.¿Cuenta con servicio de recojo de basura?</label>
																							<div>

																								<div class="">
																									<div class="controls">
																										<label class="custom-control custom-radio custom-control-inline">
																											<input type="radio" value="0" id="no_k" name="k" checked="" class="custom-control-input"><span class="custom-control-label">No</span>
																										</label>
																										<label class="custom-control custom-radio custom-control-inline" id="k" name="k">
																											<input type="radio" value="1" id="si_k" name="k" class="custom-control-input"><span class="custom-control-label">Si</span>
																										</label>
																									</div>
																								</div>
																							</div>

																							<label class="section-title">4.3.6. La vivienda que ocupa el hogar es : (Marque solo una opcion)</label>
																							<div class="">
																								<div class="controls">
																									<label class="custom-control custom-radio custom-control-inline">
																										<input type="radio" value="1" id="l_1" name="l" checked="" class="custom-control-input"><span class="custom-control-label">Propia</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline" id="l" name="l">
																										<input type="radio" value="2" id="l_2" name="l" class="custom-control-input"><span class="custom-control-label">Alquilada</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline">
																										<input type="radio" value="3" id="l_3" name="l" class="custom-control-input"><span class="custom-control-label">Anticretico</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline" id="l" name="l">
																										<input type="radio" value="4" id="l_4" name="l" class="custom-control-input"><span class="custom-control-label">Cedida por servicios</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline">
																										<input type="radio" value="5" id="l_5" name="l" class="custom-control-input"><span class="custom-control-label">Prestada por parientes o amigos</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline" id="l" name="l">
																										<input type="radio" value="6" id="l_6" name="l" class="custom-control-input"><span class="custom-control-label">Contrato Mixto (Alquiler y anticretico)</span>
																									</label>
																								</div>
																							</div>
																						</div>

																						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																							<br>
																							<h5 class="section-title">4.4. ACCESO A INTERNET DE LA O EL ESTUDIANTE.</h5>
																						</div>

																						<!-- columnas -->
																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																							<br>
																							<label class="section-title">4.4.1. El Estudiante accede a internet en: (Puede marcar mas de una opcion)</label>

																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4411" name="internet[0]" value="4411">
																								<label class="custom-control-label" for="4411">Su vivienda</label>
																							</div>
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4412" name="internet[1]" value="4412">
																								<label class="custom-control-label" for="4412">La Unidad Educativa</label>
																							</div>
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4413" name="internet[2]" value="4413">
																								<label class="custom-control-label" for="4413">Lugares Publicos</label>
																							</div>
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4414" name="internet[3]" value="4414">
																								<label class="custom-control-label" for="4414">Telefono Celular</label>
																							</div>
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4415" name="internet[4]" value="4415">
																								<label class="custom-control-label" for="4415">No accede a internet (pase a 4.5)</label>
																							</div>
																						</div>

																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																							<br>
																							<label class="section-title">4.4.2. ¿Con que frecuencia usa internet? (Marque solo una opcion)</label>
																							<div class="">
																								<div class="controls">
																									<label class="custom-control custom-radio custom-control-inline">
																										<input type="radio" value="1" id="m_1" name="m" checked="" class="custom-control-input"><span class="custom-control-label">Diariamente</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline" id="m" name="m">
																										<input type="radio" value="2" id="m_2" name="m" class="custom-control-input"><span class="custom-control-label">Una sola vez</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline">
																										<input type="radio" value="3" id="m_3" name="m" class="custom-control-input"><span class="custom-control-label">Mas de una vez a la semana</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline" id="m" name="m">
																										<input type="radio" value="4" id="m_4" name="m" class="custom-control-input"><span class="custom-control-label">Una vez al mes</span>
																									</label><br>
																								</div>
																							</div>
																						</div>

																						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																							<br>
																							<h5 class="section-title">4.5. ACTIVIDAD LABORAL DE LA O EL ESTUDIANTE.</h5>
																						</div>

																						<!-- columnas style="background-color:#f3f3f3;" -->
																						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																							<label class="section-title">4.5.1. En la pasada gestion ¿El estudiante trabajó?</label>
																						</div>

																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																							<br>
																							<div class="">
																								<div class="controls">
																									<label class="custom-control custom-radio custom-control-inline">
																										<input type="radio" value="1" id="n_1" name="n" class="custom-control-input"><span class="custom-control-label">Si</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline" id="n" name="n">
																										<input type="radio" value="2" id="n_2" name="n" checked="" class="custom-control-input"><span class="custom-control-label">No (Pase a 4.6)</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline">
																										<input type="radio" value="3" id="n_3" name="n" class="custom-control-input"><span class="custom-control-label">Ns/Nr No (Pase a 4.6)</span>
																									</label><br>
																								</div>
																							</div>
																						</div>



																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">

																							<div class="clearfix"></div>
																							<div class="form-row" style="width: 100%;">
																								<div class="col-sm-4">
																									<div class="custom-control custom-checkbox">
																										<input type="checkbox" class="custom-control-input" id="4511" name="mes[0]" value="4511">
																										<label class="custom-control-label" for="4511">Ene</label>
																									</div>
																									<div class="custom-control custom-checkbox">
																										<input type="checkbox" class="custom-control-input" id="4512" name="mes[1]" value="4512">
																										<label class="custom-control-label" for="4512">Feb</label>
																									</div>
																									<div class="custom-control custom-checkbox">
																										<input type="checkbox" class="custom-control-input" id="4513" name="mes[2]" value="4513">
																										<label class="custom-control-label" for="4513">Mar</label>
																									</div>
																									<div class="custom-control custom-checkbox">
																										<input type="checkbox" class="custom-control-input" id="4514" name="mes[3]" value="4514">
																										<label class="custom-control-label" for="4514">Abr</label>
																									</div>
																								</div>
																								<div class="col-sm-4">
																									<div class="custom-control custom-checkbox">
																										<input type="checkbox" class="custom-control-input" id="4515" name="mes[4]" value="4515">
																										<label class="custom-control-label" for="4515">May</label>
																									</div>
																									<div class="custom-control custom-checkbox">
																										<input type="checkbox" class="custom-control-input" id="4516" name="mes[5]" value="4516">
																										<label class="custom-control-label" for="4516">Jun</label>
																									</div>
																									<div class="custom-control custom-checkbox">
																										<input type="checkbox" class="custom-control-input" id="4517" name="mes[6]" value="4517">
																										<label class="custom-control-label" for="4517">Jul</label>
																									</div>
																									<div class="custom-control custom-checkbox">
																										<input type="checkbox" class="custom-control-input" id="4518" name="mes[7]" value="4518">
																										<label class="custom-control-label" for="4518">Ago</label>
																									</div>
																								</div>
																								<div class="col-sm-4">
																									<div class="custom-control custom-checkbox">
																										<input type="checkbox" class="custom-control-input" id="4519" name="mes[8]" value="4519">
																										<label class="custom-control-label" for="4519">Sep</label>
																									</div>
																									<div class="custom-control custom-checkbox">
																										<input type="checkbox" class="custom-control-input" id="45110" name="mes[9]" value="45110">
																										<label class="custom-control-label" for="45110">Oct</label>
																									</div>
																									<div class="custom-control custom-checkbox">
																										<input type="checkbox" class="custom-control-input" id="45111" name="mes[10]" value="45111">
																										<label class="custom-control-label" for="45111">Nov</label>
																									</div>
																									<div class="custom-control custom-checkbox">
																										<input type="checkbox" class="custom-control-input" id="45112" name="mes[11]" value="45112">
																										<label class="custom-control-label" for="45112">Dic</label>
																									</div>
																								</div>
																							</div>
																						</div>

																						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																							<br>
																							<label class="section-title">4.5.2. En la pasada gestion ¿En que actividad trabajo el estudiante?</label>
																						</div>
																						<!-- columnas -->
																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																							<br>
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4521" value="4521" name="trabajo[0]">
																								<label class="custom-control-label" for="4521">Agricultura</label>
																							</div>
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4522" value="4522" name="trabajo[1]">
																								<label class="custom-control-label" for="4522">Ganaderia o pesca</label>
																							</div>
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4523" value="4523" name="trabajo[2]">
																								<label class="custom-control-label" for="4523">Mineria</label>
																							</div>
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4524" value="4524" name="trabajo[3]">
																								<label class="custom-control-label" for="4524">Construccion</label>
																							</div>
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4525" value="4525" name="trabajo[4]">
																								<label class="custom-control-label" for="4525">Zafra</label>
																							</div>
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4526" value="4526" name="trabajo[5]">
																								<label class="custom-control-label" for="4526">Vendedor Dependiente</label>
																							</div>
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4527" value="4527" name="trabajo[6]">
																								<label class="custom-control-label" for="4527">Vendedor por cuenta propia</label>
																							</div>
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4528" value="4528" name="trabajo[7]">
																								<label class="custom-control-label" for="4528">Transporte o mecanica</label>
																							</div>

																						</div>

																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																							<br>
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4529" value="4529" name="trabajo[8]">
																								<label class="custom-control-label" for="4529">Lustrabotas</label>
																							</div>
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="45210" value="45210" name="trabajo[9]">
																								<label class="custom-control-label" for="45210">Trabajador(a) del hogar o niñero(a)</label>
																							</div>
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="45211" value="45211" name="trabajo[10]">
																								<label class="custom-control-label" for="45211">Ayudante familiar o comunitario en agricultura o ganaderia o pesca</label>
																							</div>
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="45212" value="45212" name="trabajo[11]">
																								<label class="custom-control-label" for="45212">Ayudante en el hogar en comercio o ventas</label>
																							</div>
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="45213" value="45213" name="trabajo[12]">
																								<label class="custom-control-label" for="45213">Otro trabajo</label>
																							</div>
																							<div class="" style="margin-top:2%">
																								<label class="control-label">Especifique: </label>
																								<div class="controls control-group">
																									<select name="o" id="o" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
																										<option value="" selected="selected">Seleccionar</option>
																										<?php foreach ($trabajos as $trabajo) : ?>
																											<option value="<?= $trabajo['trabajo']; ?>"><?= escape($trabajo['trabajo']); ?></option>
																										<?php endforeach ?>
																									</select>
																								</div>
																							</div>
																						</div>


																						<!-- columnas -->
																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																							<br>
																							<label class="section-title">4.5.3. En que turno trabajo el estudiante? (Puede marcar mas de una opcion)</label>

																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4531" name="turno[0]" value="4531">
																								<label class="custom-control-label" for="4531">Mañana</label>
																							</div>
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4532" name="turno[1]" value="4532">
																								<label class="custom-control-label" for="4532">Tarde</label>
																							</div>
																							<div class="custom-control custom-checkbox">
																								<input type="checkbox" class="custom-control-input" id="4533" name="turno[2]" value="4533">
																								<label class="custom-control-label" for="4533">Noche</label>
																							</div>
																						</div>

																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																						</div>

																						<!-- columnas -->
																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																							<br>
																							<label class="section-title">4.5.4. Con que frecuencia trabajo?</label>

																							<label class="control-label">Departamento: </label>
																							<div class="controls control-group">
																								<select id="p" name="p" class="form-control">
																									<option value="">Seleccione</option>
																									<option value="Todos los dias">Todos los dias</option>
																									<option value="Fines de semana">Fines de semana</option>
																									<option value="Dias festivos">Dias festivos</option>
																									<option value="Dias hábiles">Dias hábiles</option>
																									<option value="Eventual/esporadico">Eventual/esporadico</option>
																									<option value="En vacaciones">En vacaciones</option>
																								</select>
																							</div>
																						</div>

																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																						</div>

																						<!-- columnas -->
																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																							<br>
																							<label class="section-title">4.5.5. ¿Recibio algun pago?</label>
																							<div class="">
																								<div class="controls">
																									<label class="custom-control custom-radio custom-control-inline">
																										<input type="radio" value="1" id="q_1" name="q" class="custom-control-input"><span class="custom-control-label">Si</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline" id="q" name="q">
																										<input type="radio" value="2" id="q_2" name="q" checked="" class="custom-control-input"><span class="custom-control-label">No</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline">
																										<input type="radio" value="3" id="q_3" name="q" class="custom-control-input"><span class="custom-control-label">Ns/Nr</span>
																									</label><br>
																								</div>
																							</div>
																						</div>

																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																							<div id="especies">
																								<br><br>
																								<label class="section-title"></label>
																								<div class="custom-control custom-checkbox">
																									<input type="checkbox" class="custom-control-input" id="4551" name="pago[0]" value="4551">
																									<label class="custom-control-label" for="4551">En especie</label>
																								</div>
																								<div class="custom-control custom-checkbox">
																									<input type="checkbox" class="custom-control-input" id="4552" name="pago[1]" value="4551">
																									<label class="custom-control-label" for="4552">Dinero</label>
																								</div>
																							</div>
																						</div>

																						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																							<br>
																							<h5 class="section-title">4.6. MEDIO DE TRANSPORTE PARA LLEGAR A LA UNIDAD EDUCATIVA</h5>
																						</div>

																						<!-- columnas -->
																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">

																							<br>
																							<label class="section-title">4.6.1. Generalmente, ¿Como llega el estudiante a la unidad educativa? (Coloque una opción)</label>
																							<br>

																							<div class="">
																								<div class="controls">
																									<label class="custom-control custom-radio custom-control-inline">
																										<input type="radio" value="1" id="s_1" name="s" checked="" class="custom-control-input"><span class="custom-control-label">A pie</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline" id="s" name="s">
																										<input type="radio" value="2" id="s_2" name="s" class="custom-control-input"><span class="custom-control-label">En vehiculo de tramsporte terrestre</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline">
																										<input type="radio" value="3" id="s_3" name="s" class="custom-control-input"><span class="custom-control-label">Fluvial</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline">
																										<input type="radio" value="4" id="s_4" name="s" class="custom-control-input"><span class="custom-control-label">Otro(Especifique)</span>
																									</label><br>
																								</div>
																								<div class="" style="margin-top:2%">
																									<div class="" style="margin-top:2%">
																										<label class="control-label">Especifique: </label>
																										<div class="controls control-group">
																											<select name="461" id="461" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
																												<option value="" selected="selected">Seleccionar</option>
																												<?php foreach ($transportes as $transporte) : ?>
																													<option value="<?= $transporte['transporte']; ?>"><?= escape($transporte['transporte']); ?></option>
																												<?php endforeach ?>
																											</select>
																										</div>
																									</div>
																								</div>
																							</div>
																						</div>

																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">

																							<br>
																							<label class="section-title">4.6.2. Según el medio de transporte señalado ¿Cual es el tiempo maximo que demora el estudiante desde su vivienda hasta la Unidad Educativa? (Coloque una opción)</label>
																							<br>
																							<div class="">
																								<div class="controls">
																									<label class="custom-control custom-radio custom-control-inline">
																										<input type="radio" value="1" id="t_1" name="t" checked="" class="custom-control-input"><span class="custom-control-label">Menos de media hora</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline" id="t" name="t">
																										<input type="radio" value="2" id="t_2" name="t" checked="" class="custom-control-input"><span class="custom-control-label">Entre media hora y una hora</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline">
																										<input type="radio" value="3" id="t_3" name="t" checked="" class="custom-control-input"><span class="custom-control-label">Entre una a dos horas</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline">
																										<input type="radio" value="4" id="t_4" name="t" checked="" class="custom-control-input"><span class="custom-control-label">Mas de 2 horas</span>
																									</label><br>
																								</div>
																							</div>
																						</div>


																						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																							<br>
																							<h5 class="section-title">4.7. ABANDONO ESCOLAR CORRESPONDIENTE A LA GESTION ANTERIOR</h5>
																						</div>

																						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																							<br>
																							<label class="section-title">4.7.1. ¿El estudiante abandono la unidad educativa el año pasado?</label>
																							<br>

																							<div class="">
																								<div class="controls">
																									<label class="custom-control custom-radio custom-control-inline">
																										<input type="radio" value="1" id="u_1" name="u" class="custom-control-input"><span class="custom-control-label">Si</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline" id="u" name="u">
																										<input type="radio" value="2" id="u_2" name="u" checked="" class="custom-control-input"><span class="custom-control-label">No (Pase a la pregunta 5.1)</span>
																									</label><br>
																								</div>
																							</div>
																						</div>

																						<!-- columnas -->
																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																							<div id="abandono_uno">
																								<br>
																								<label class="section-title">4.7.2. ¿Cuál o cuáles fueron las razones de abandono escolar?</label>
																								<br>
																								<div class="custom-control custom-checkbox">
																									<input type="checkbox" class="custom-control-input" id="4721" name="abandono[0]" value="4721">
																									<label class="custom-control-label" for="4721">Tuvo que ayudar a sus padres en su trabajo</label>
																								</div>
																								<div class="custom-control custom-checkbox">
																									<input type="checkbox" class="custom-control-input" id="4722" name="abandono[1]" value="4722">
																									<label class="custom-control-label" for="4722">Tuvo trabajo remunerado</label>
																								</div>
																								<div class="custom-control custom-checkbox">
																									<input type="checkbox" class="custom-control-input" id="4723" name="abandono[2]" value="4723">
																									<label class="custom-control-label" for="4723">Falta de Dinero</label>
																								</div>
																								<div class="custom-control custom-checkbox">
																									<input type="checkbox" class="custom-control-input" id="4724" name="abandono[3]" value="4724">
																									<label class="custom-control-label" for="4724">Edad temprana(precocidad)/edad tardia(resago)</label>
																								</div>
																								<div class="custom-control custom-checkbox">
																									<input type="checkbox" class="custom-control-input" id="4725" name="abandono[4]" value="4725">
																									<label class="custom-control-label" for="4725">La unidad educativa era distante</label>
																								</div>
																								<div class="custom-control custom-checkbox">
																									<input type="checkbox" class="custom-control-input" id="4726" name="abandono[5]" value="4726">
																									<label class="custom-control-label" for="4726">Labores de casa/Cuidado de niños(as)</label>
																								</div>
																							</div>
																						</div>

																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																							<div id="abandono_dos">
																								<br>
																								<div class="custom-control custom-checkbox">
																									<input type="checkbox" class="custom-control-input" id="4727" name="abandono[6]" value="4727">
																									<label class="custom-control-label" for="4727">Embarazo o paternidad</label>
																								</div>
																								<div class="custom-control custom-checkbox">
																									<input type="checkbox" class="custom-control-input" id="4728" name="abandono[7]" value="4728">
																									<label class="custom-control-label" for="4728">Por Enfermedad/accidente/discapacidad</label>
																								</div>
																								<div class="custom-control custom-checkbox">
																									<input type="checkbox" class="custom-control-input" id="4729" name="abandono[8]" value="4729">
																									<label class="custom-control-label" for="4729">Viaje o traslado</label>
																								</div>
																								<div class="custom-control custom-checkbox">
																									<input type="checkbox" class="custom-control-input" id="47210" name="abandono[9]" value="47210">
																									<label class="custom-control-label" for="47210">Falta de intires</label>
																								</div>
																								<div class="custom-control custom-checkbox">
																									<input type="checkbox" class="custom-control-input" id="47211" name="abandono[10]" value="47211">
																									<label class="custom-control-label" for="47211">Bullying o discriminacion en la unidad educativa</label>
																								</div>
																								<div class="" style="margin-top:2%">
																									<label class="control-label">Otro(especifique) </label>
																									<div class="controls control-group">
																										<input id="472a" name="472a" type="text" class="form-control" onKeyUp="this.value=this.value.toUpperCase();">
																									</div>
																								</div>
																							</div>
																						</div>


																						<!-- columnas -->
																						<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																							<br>
																							<label class="section-title">5.1. LA O EL ESTUDIANTE VIVE HABITUALMENTE CON :</label>
																							<div class="">
																								<div class="controls">
																									<label class="custom-control custom-radio custom-control-inline">
																										<input type="radio" value="1" id="r_1" name="r" checked="" class="custom-control-input"><span class="custom-control-label">Padre y Madre</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline" id="r" name="r">
																										<input type="radio" value="2" id="r_2" name="r" class="custom-control-input"><span class="custom-control-label">Solo Padre</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline">
																										<input type="radio" value="3" id="r_3" name="r" class="custom-control-input"><span class="custom-control-label">Solo Madre</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline">
																										<input type="radio" value="4" id="r_4" name="r" class="custom-control-input"><span class="custom-control-label">Tutor(a)</span>
																									</label><br>
																									<label class="custom-control custom-radio custom-control-inline">
																										<input type="radio" value="5" id="r_5" name="r" class="custom-control-input"><span class="custom-control-label">Solo(a)</span>
																									</label><br>
																								</div>
																							</div>
																						</div>
																					</div>
																					<br>

																					<div align="right">

																						<button type="button" class="btn btn-primary" onclick="atrasActualizacion()">Atras</button>
																						<button type="submit" class="btn btn-success" id="btn_agregar_rude">Guardar</button>
																						<button type="button" class="btn btn-success" onclick="reporteRude()">Generar RUDE</button>
																						<button type="submit" class="btn btn-primary" id="btn_agregar_rude">Siguiente</button>
																					</div>
																				</form>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>


									<div class="tab-pane fade" id="pagos" role="tabpanel" aria-labelledby="pagos-tab">
										<form id="form_pago" autocomplete="off">
											<!-- 						<div class="row">
							<div class="col col-lg-6 text-left">
							</div>
							
							<div class="col col-lg-6 text-right">
								<button type="button" class="btn btn-sm btn-success" onclick="abrir_form_familiar();">Agregar Pago</button>
							</div>
						</div> -->
											<div class="alert alert-info" role="alert">
												<b>Debe marcar los conceptos de pagos correspondiente al estudiante.</b>
											</div>
											<div class="">
												<div class="card-body">
													<div class="row">
														<div class="table-responsive">
															<table id="table" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
																<thead>
																	<tr class="active">
																		<th class="text-nowrap text-center">Marcar</th>
																		<th class="text-nowrap text-center">Concepto pago</th>
																	</tr>
																</thead>
																<tbody id="contenedor_familiar">
																	<?php $contador = 0; ?>
																	<?php foreach ($pagos as $p) : ?>
																		<?php $contador = $contador + 1; ?>
																		<tr>
																			<td class="text-nowrap text-center"><input type="checkbox" value="<?= escape($p['id_pensiones']); ?>" name="id_pensiones[]" id="id_pensiones<?= $contador; ?>">
																				<input type="hidden" value="<?= escape($p['tipo_concepto']); ?>" name="tipo_concepto[]"></td>
																			<td class="text-nowrap text-center"><?= escape($p['nombre_pension']); ?></td>
																		</tr>
																	<?php endforeach ?>
																</tbody>
															</table>
														</div>
													</div>
												</div>
												<div class="border-top card-footer p-0"></div>
											</div>
											<br>
											<div class="row">
												<!-- <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<div class="alert alert-warning" role="alert">
									<b>Debe marcar los conceptos de pagos correspondiente al estudiante.</b>
								</div>
							</div> -->
												<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-right">
													<button type="submit" class="btn btn-primary pull-right" id="btn_pago">Registrar Pagos de Estudiante</button>
												</div>
											</div>
										</form>
									</div>

									<!-- RECEPCION DE DOCUMENOS -->
									<div class="tab-pane fade" id="rep_documentos" role="tabpanel" aria-labelledby="rep-documentos-tab">
										<div class="card">
											<div class="card-body">
												<div class="row">
													<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
														<div class="media influencer-profile-data d-flex align-items-center p-2">

															<div class="media-body">
																<div class="influencer-profile-data">
																	<div class="row">
																		<form id="form_recep_documentos">
																			<div class="form-row">

																				<div class="">
																					<div class="card-body">
																						<div class="row">
																							<div class="table-responsive">
																								<table id="table" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
																									<thead>
																										<tr class="active">
																											<th class="text-nowrap text-center">#</th>
																											<th class="text-nowrap text-center">Documento</th>
																											<th class="text-nowrap text-center">Copia</th>
																											<th class="text-nowrap text-center">Original</th>
																											<th class="text-nowrap text-center">Observacion</th>
																										</tr>
																									</thead>
																									<tbody id="contenedor_documentos">

																									</tbody>
																								</table>
																							</div>
																						</div>
																					</div>
																					<div class="border-top card-footer p-0"></div>
																				</div>
																				<br>
																				<!-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																	<h5 class="section-title">Documentos Digitales</h5>
																</div>
																<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
																	<input type="text" id="id_documentos" name="id_documentos">
																	<input id="input-ru" name="img_documentos[]" type="file" data-browse-on-zone-click="true" multiple>
																</div>
															</div> -->

																				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																				</div>

																			</div>
																			<br>
																			<div align="right">
																				<button type="button" class="btn btn-info" onclick="imprimir_reglamento(<?= $id_estudiante_editar ?>)">Generar Reglamento</button>
																				<button type="submit" class="btn btn-primary" id="btn_agregar_documentos" onclick="atrasSubirDoc()">Guardar Documentos</button>
																				<button type="button" class="btn btn-success" onclick="volverInscribir()">Registrar Familiar</button>
																				<button type="button" class="btn btn-primary" id="btn_recep_documentos" onclick="refrescarPagina()">Finalizar Inscripcion</button>
																			</div>
																		</form>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>


									<!-- Tab de vacunas  -->
									<div class="tab-pane fade" id="vacunas" role="tabpanel" aria-labelledby="vacunas-tab">
										<!-- vacunas  -->
										<form id="form_vacunas">
											<div class="form-row">
												<div class="col col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-12">
													<div>
														<input type="hidden" id="id_vacunas" name="id_vacunas">
														<h5 class="section-title">BCG(Tuberculosis): única Recién Nacido</h5>
														<div class="form-group row">
															<label class="col-12 col-sm-6 col-form-label text-sm-right">Vacuna: </label>
															<div class="col-12 col-sm-6 col-lg-6 pt-1">
																<div class="switch-button">
																	<input type="checkbox" name="bcg" id="bcg"><span>
																		<label for="bcg"></label></span>
																</div>
															</div>
														</div>
													</div>

													<div>
														<h5 class="section-title">Antipolio</h5>
														<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
															<div class="form-group row">
																<label class="col-12 col-sm-6 col-form-label text-sm-right">1º (2 meses):</label>
																<div class="col-12 col-sm-6 col-lg-6 pt-1">
																	<div class="switch-button">
																		<input type="checkbox" name="a1" id="a1"><span>
																			<label for="a1"></label></span>
																	</div>
																</div>
															</div>
															<div class="form-group row">
																<label class="col-12 col-sm-6 col-form-label text-sm-right">2º (4 meses):</label>
																<div class="col-12 col-sm-6 col-lg-6 pt-1">
																	<div class="switch-button">
																		<input type="checkbox" name="a2" id="a2"><span>
																			<label for="a2"></label></span>
																	</div>
																</div>
															</div>
															<div class="form-group row">
																<label class="col-12 col-sm-6 col-form-label text-sm-right">3º (6 meses):</label>
																<div class="col-12 col-sm-6 col-lg-6 pt-1">
																	<div class="switch-button">
																		<input type="checkbox" name="a3" id="a3"><span>
																			<label for="a3"></label></span>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-12">
													<div>
														<h5 class="section-title">Pentavalente</h5>
														<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
															<div class="form-group row">
																<label class="col-12 col-sm-6 col-form-label text-sm-right">1º (2 meses):</label>
																<div class="col-12 col-sm-6 col-lg-6 pt-1">
																	<div class="switch-button">
																		<input type="checkbox" name="p1" id="p1"><span>
																			<label for="p1"></label></span>
																	</div>
																</div>
															</div>
															<div class="form-group row">
																<label class="col-12 col-sm-6 col-form-label text-sm-right">2º (4 meses):</label>
																<div class="col-12 col-sm-6 col-lg-6 pt-1">
																	<div class="switch-button">
																		<input type="checkbox" name="p2" id="p2"><span>
																			<label for="p2"></label></span>
																	</div>
																</div>
															</div>
															<div class="form-group row">
																<label class="col-12 col-sm-6 col-form-label text-sm-right">3º (6 meses):</label>
																<div class="col-12 col-sm-6 col-lg-6 pt-1">
																	<div class="switch-button">
																		<input type="checkbox" name="p3" id="p3"><span>
																			<label for="p3"></label></span>
																	</div>
																</div>
															</div>
														</div>
													</div>

													<div>
														<h5 class="section-title">Antiamarilla única (12 meses)</h5>
														<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
															<div class="form-group row">
																<label class="col-12 col-sm-6 col-form-label text-sm-right">1º (2 meses):</label>
																<div class="col-12 col-sm-6 col-lg-6 pt-1">
																	<div class="switch-button">
																		<input type="checkbox" name="am1" id="am1"><span>
																			<label for="am1"></label></span>
																	</div>
																</div>
															</div>
														</div>
													</div>

												</div>
												<div class="col col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-12">
													<div>
														<h5 class="section-title">SRP (MMR): única (12 a 23 meses)</h5>
														<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
															<div class="form-group row">
																<label class="col-12 col-sm-6 col-form-label text-sm-right">Vacuna:</label>
																<div class="col-12 col-sm-6 col-lg-6 pt-1">
																	<div class="switch-button">
																		<input type="checkbox" name="srp1" id="srp1"><span>
																			<label for="srp1"></label></span>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div>
														<h5 class="section-title">Otra</h5>
														<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
															<div class="form-group row">
																<label class="col-12 col-sm-6 col-form-label text-sm-right">Vacuna:</label>
																<div class="col-12 col-sm-6 col-lg-6 pt-1">
																	<div class="switch-button">
																		<input type="checkbox" name="o1" id="o1"><span>
																			<label for="o1"></label></span>
																	</div>
																</div>
															</div>
														</div>
													</div>

													<div>
														<h5 class="section-title">Observaciones</h5>
														<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
															<div class="control-group row">
																<div class="col-12 col-sm-12 col-lg-6 pt-1">
																	<textarea name="observaciones_vacunas" id="observaciones_vacunas" cols="30" rows="4"></textarea>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="">
												<button type="submit" class="btn btn-success pull-left"><span class="fa fa-arrow-left" onclick="atrasRude()"> Atras</span></button>
												<button type="submit" class="btn btn-success pull-right"><span class="fa fa-arrow-right"> Siguiente</span></button>
											</div>
										</form>
										<!-- end vacunas  -->
									</div>

									<!-- <div class="tab-pane fade" id="documentos" role="tabpanel" aria-labelledby="documentos-tab">
					<input type="hidden" id="id_documentos" name="id_documentos">
					<input id="input-ru" name="img_documentos[]" type="file" data-browse-on-zone-click="true" multiple>
					<div class="">
						<button class="btn btn-success pull-right"><span class="fa fa-arrow-left"> Atras</span></button>
						<button type="submit" class="btn btn-success pull-right"><span class="fa fa-arrow-right"> Siguiente</span></button>
					</div>
				</div> -->

								</div>
							</div>
						</div>
						<!-- ============================================================== -->
						<!-- end basic tabs  -->
						<!-- ============================================================== -->
				</div>

				<!-- ============================================================== -->
				<!-- modal  -->
				<!-- ============================================================== -->
				<!-- Modal -->
				<div class="modal fade" id="modal_subir" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="modalLabel">Editar Imagen</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col col-sm-9">
										<div class="img-container">
											<img id="image" src="" width="400px" height="600px">
										</div>
									</div>
									<div class="col col-sm-3">
										<div class="row" id="actions">
											<div class="docs-buttons">
												<div class="btn-group">
													<button type="button" class="btn btn-primary btn-block" data-method="setDragMode" data-option="move" title="Mover">
														<span class="docs-tooltip" data-toggle="tooltip" title="Mover">
															<span class="fa fa-arrows-alt"> Mover</span>
														</span>
													</button>
													<!--button type="button" class="btn btn-primary" data-method="setDragMode" data-option="crop" title="Crop">
									<span class="docs-tooltip" data-toggle="tooltip" title="cropper.setDragMode(&quot;crop&quot;)">
									<span class="fa fa-crop-alt"></span>
									</span>
								</button-->
												</div><br>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.zoom(0.1)">
															<span class="fa fa-search-plus"> Aumentar</span>
														</span>
													</button>
												</div><br>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.zoom(-0.1)">
															<span class="fa fa-search-minus"> Reducir</span>
														</span>
													</button>
												</div><br>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Move Left">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.move(-10, 0)">
															<span class="fa fa-arrow-left"> Mover Izquierda</span>
														</span>
													</button>
												</div><br>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Move Right">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.move(10, 0)">
															<span class="fa fa-arrow-right"> Mover Derecha</span>
														</span>
													</button>
												</div>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Move Up">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.move(0, -10)">
															<span class="fa fa-arrow-up"> Mover Arriba</span>
														</span>
													</button>
												</div><br>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Move Down">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.move(0, 10)">
															<span class="fa fa-arrow-down"> Mover Abajo</span>
														</span>
													</button>
												</div><br>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Rotate Left">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.rotate(-45)">
															<span class="fa fa-undo-alt"> Rotar -45°</span>
														</span>
													</button>
												</div><br>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Rotate Right">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.rotate(45)">
															<span class="fa fa-redo-alt"> Rotar +45°</span>
														</span>
													</button>
												</div><br>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1" title="Flip Horizontal">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.scaleX(-1)">
															<span class="fa fa-arrows-alt-h"></span>
														</span>
													</button>
												</div><br>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" title="Flip Vertical">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.scaleY(-1)">
															<span class="fa fa-arrows-alt-v"></span>
														</span>
													</button>
												</div><br>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="crop" title="Crop">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.crop()">
															<span class="fa fa-check"></span>
														</span>
													</button>
												</div>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="clear" title="Clear">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.clear()">
															<span class="fa fa-times"></span>
														</span>
													</button>
												</div>
											</div>
											<div class="col-md-3 docs-toggles">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								<button type="button" class="btn btn-primary" id="crop">Recortar</button>
							</div>
						</div>
					</div>
				</div>
				<!-- ============================================================== -->
				<!-- modal  -->
				<!-- ============================================================== -->


				<!-- ============================================================== -->
				<!-- modal subir imagen tutor  -->
				<!-- ============================================================== -->
				<!-- Modal -->
				<div class="modal fade" id="modal_subir_tutor" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="modalLabel">Editar Imagen tutor</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col col-sm-9">
										<div class="img-container">
											<img id="f_image" src="" width="400px" height="600px">
										</div>
									</div>
									<div class="col col-sm-3">
										<div class="row" id="actions">
											<div class="docs-buttons">
												<div class="btn-group">
													<button type="button" class="btn btn-primary btn-block" data-method="setDragMode" data-option="move" title="Mover">
														<span class="docs-tooltip" data-toggle="tooltip" title="Mover">
															<span class="fa fa-arrows-alt"> Mover</span>
														</span>
													</button>
													<!--button type="button" class="btn btn-primary" data-method="setDragMode" data-option="crop" title="Crop">
									<span class="docs-tooltip" data-toggle="tooltip" title="cropper.setDragMode(&quot;crop&quot;)">
									<span class="fa fa-crop-alt"></span>
									</span>
								</button-->
												</div><br>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.zoom(0.1)">
															<span class="fa fa-search-plus"> Aumentar</span>
														</span>
													</button>
												</div><br>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.zoom(-0.1)">
															<span class="fa fa-search-minus"> Reducir</span>
														</span>
													</button>
												</div><br>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Move Left">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.move(-10, 0)">
															<span class="fa fa-arrow-left"> Mover Izquierda</span>
														</span>
													</button>
												</div><br>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Move Right">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.move(10, 0)">
															<span class="fa fa-arrow-right"> Mover Derecha</span>
														</span>
													</button>
												</div>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Move Up">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.move(0, -10)">
															<span class="fa fa-arrow-up"> Mover Arriba</span>
														</span>
													</button>
												</div><br>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Move Down">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.move(0, 10)">
															<span class="fa fa-arrow-down"> Mover Abajo</span>
														</span>
													</button>
												</div><br>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Rotate Left">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.rotate(-45)">
															<span class="fa fa-undo-alt"> Rotar -45°</span>
														</span>
													</button>
												</div><br>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Rotate Right">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.rotate(45)">
															<span class="fa fa-redo-alt"> Rotar +45°</span>
														</span>
													</button>
												</div><br>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1" title="Flip Horizontal">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.scaleX(-1)">
															<span class="fa fa-arrows-alt-h"></span>
														</span>
													</button>
												</div><br>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" title="Flip Vertical">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.scaleY(-1)">
															<span class="fa fa-arrows-alt-v"></span>
														</span>
													</button>
												</div><br>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="crop" title="Crop">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.crop()">
															<span class="fa fa-check"></span>
														</span>
													</button>
												</div>
												<div class="btn-group">
													<button type="button" class="btn btn-primary" data-method="clear" title="Clear">
														<span class="docs-tooltip" data-toggle="tooltip" title="cropper.clear()">
															<span class="fa fa-times"></span>
														</span>
													</button>
												</div>
											</div>
											<div class="col-md-3 docs-toggles">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								<button type="button" class="btn btn-primary" id="f_crop">Recortar</button>
							</div>
						</div>
					</div>
				</div>
				<!-- ============================================================== -->
				<!-- fin modal subir imagen tutor -->
				<!-- ============================================================== -->

				<!-- ============================================================== -->
				<!-- modal  -->
				<!-- ============================================================== -->

				<form id="form_familiar">
					<!-- Modal formulario discapacidad -->
					<div class="modal fade" id="modal_familiar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">

							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel"><span id="titulo_modal_familiar"></span></h5>
									<a href="#" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</a>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
											<div class="">
												<label class="control-label">Nro de registro de discapacidad o IBC: </label>
												<div class="controls control-group">
													<input type="text" name="<?= $csrf; ?>">
													<input type="text" class="form-control" id="f_id_familiar" name="f_id_familiar">
													<input type="text" class="form-control" id="f_id_persona" name="f_id_persona">
													<input id="f_nombres" name="f_nombres" type="text" class="form-control">
												</div>
											</div>
											<br>
											<label class="control-label">Tipo de Discapacidad: </label>
											<div class="control-group">
												<select name="select_paralelo" id="select_paralelo" onchange="listar_vacantes();" class="form-control">
													<option value="" selected="selected">Seleccionar</option>
												</select>
											</div>
										</div>

										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
											<label class="control-label">Grado de Discapacidad: </label>
											<div class="control-group">
												<select name="select_paralelo" id="select_paralelo" onchange="listar_vacantes();" class="form-control">
													<option value="" selected="selected">Seleccionar</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
									<button type="submit" class="btn btn-primary pull-right" id="btn_agregar_familiar">Guardar</button>
								</div>
							</div>
						</div>
					</div>
			</div>
			</form>

			<!--modal para eliminar-->
			<div class="modal fade" tabindex="-1" role="dialog" id="modal_eliminar">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-body">
							<input type="hidden" id="familiar_eliminar">
							<p>¿Esta seguro de retirar al familiar <span id="texto_familiar"></span>?</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
							<button type="button" class="btn btn-primary" id="btn_eliminar">Eliminar</button>
						</div>
					</div>
				</div>
			</div>

			<script src="<?= themes; ?>/concept/assets/vendor/cropper-mazter/js/cropper.js"></script>
			<script src="<?= themes; ?>/concept/assets/vendor/cropper-mazter/js/imagenes.js"></script>
			<!--script src="<?= themes; ?>/concept/assets/vendor/cropper-mazter/js/main.js"></script-->
			<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
			<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.js"></script>
			<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.es.js"></script>
			<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-fileinput-master/js/fileinput.js"></script>
			<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-fileinput-master/js/es.js"></script>
			<script src="<?= js; ?>/selectize.min.js"></script>
			<script src="<?= js; ?>/jquery.validate.js"></script>
			<script src="<?= js; ?>/educheck.js"></script>

			<?php require_once show_template('footer-design'); ?>
			<?php
			$directory = ""
			?>
			<script>
				//Variables de id_estudiante que se captura para registrar el rude
				var id_familiar_tutor = 0;
				var a_id_familiar = [];
				var arrayDocumentos = [];
				var id_estudiante = 0;
				var id_inscripcion_rude = 0;
				var lista_familiar = "";
				var id_tipo_estudiante = 0;
				var id_nivel_academico = 0;
				var id_turno = 0;
				//Variables para la parte de inscripcion
				var id_aula_paralelo_A = 0;
				var estado_reserva = 0;
				var fecha_limite_reserva = "";
				var monto_reserva = 0;

				var id_estudiante_editar = <?= $id_estudiante_editar; ?>;

				function imprimir_pago(id) {
					//$.open('?/b-electronicas/imprimir/' + venta, true); 
					//window.location.reload();
					window.open('?/s-inscripciones/imprimir-pago/' + id, true);
				}

				$(function() {

					cargar_tipo_documento();
					listar_documentos(arrayDocumentos);

					//ocultamos la reserva de la inscripcion de estudiante
					$("#panel_reserva").hide();
					$("#btn_reservar_guardar").hide();
					$("#vacunas-tab").prop('disabled', true);
					$("#nuevo_familiar_editar").hide();

					//Ocultamos algunas variables del Tab Rude II
					$("#especies").hide();
					$("#abandono_uno").hide();
					$("#abandono_dos").hide();

					//Botones en el crear
					$("#btn_agregar_nuevo_familiar").hide();

					if (id_estudiante_editar) {
						datos_estudiante(id_estudiante_editar);
						$("#tabla_tutores").show();
						$("#tabla_tutores_temporal").hide();
						$("#nuevo_familiar_editar").show();
						$("#id_estudiante_editar").val(id_estudiante_editar);
						$("#btn_agregar_nuevo_familiar").show();
						$("#btn_agregar_familiar").show();

					} else {
						$("#btn_limpiar_nuevo_familiar").hide();
						//Cereamos los datos de todos los formularios
						$("#form_datos_personales")[0].reset();
						$("#form_familiar")[0].reset();
						$("#form_vacunas")[0].reset();
						$("#form_datos_certificado")[0].reset();
						$("#form_recep_documentos")[0].reset();
						$("#form_datos_rude")[0].reset();
						$("#form_inscripcion")[0].reset();
						$("#form_pago")[0].reset();
						$("#tabla_tutores").hide();
						$("#tabla_tutores_temporal").show();

					}

					$("#form_pago").validate({
						rules: {
							id_pensiones: {
								required: true
							},
						},
						errorClass: "help-inline",
						errorElement: "span",
						highlight: highlight,
						unhighlight: unhighlight,
						messages: {
							id_pensiones: "Debe seleccionar el tipo de estudiante.",
						},
						//una ves validado guardamos los datos en la DB
						submitHandler: function(form) {
							//console.log('ggggggggggggggggggggggggggggggggggg');
							var datos = $("#form_pago").serialize();
							//var id_estudiante = $("#id_documentos").val();
							//datos = datos + '&id_familiares='+ id_familiares;
							datos = datos + '&boton=' + 'guardar_concepto_pago' + '&id_estudiante=' + id_estudiante;
							console.log(datos);
							$.ajax({
								type: 'POST',
								url: "?/s-inscripciones/procesos",
								data: datos,
								//data: {'id_estudiante': id_estudiante,'boton': 'guardar_concepto_pago'},
								success: function(resp) {
									console.log(resp);
									switch (resp) {
										case '1': //dataTable.ajax.reload();
											//document.location.href="?/s-inscripciones/imprimir-pago";
											imprimir_pago(id_estudiante);
											alertify.success('Registro exitoso.');
											break;
										case '2': //dataTable.ajax.reload();
											alertify.success('Error, verifique la información');
											break;
									}
								}
							});
						}
					})

					$("#form_recep_documentos").validate({
						rules: {
							id_pensiones: {
								required: true
							},
						},
						errorClass: "help-inline",
						errorElement: "span",
						highlight: highlight,
						unhighlight: unhighlight,
						messages: {
							id_pensiones: "Debe seleccionar el tipo de estudiante.",
						},
						//una ves validado guardamos los datos en la DB
						submitHandler: function(form) {

							var datos = $("#form_recep_documentos").serialize();
							datos = datos + '&boton=' + 'guardar_documentos' + '&id_estudiante=' + id_estudiante;
							console.log(datos);
							$.ajax({
								type: 'POST',
								url: "?/s-inscripciones/procesos",
								data: datos,
								//data: {'id_estudiante': id_estudiante,'boton': 'guardar_concepto_pago'},
								success: function(resp) {
									console.log(resp);
									switch (resp) {
										case '1': //dataTable.ajax.reload();
											//document.location.href="?/s-inscripciones/imprimir-pago";
											imprimir_pago(id_estudiante);
											alertify.success('Registro exitoso.');
											break;
										case '2': //dataTable.ajax.reload();
											alertify.success('Error, verifique la información');
											break;
									}
								}
							});
						}
					})

					$("input[name=opc_registro]").click(function() {
						var valor = $(this).val();
						$.ajax({
							url: '?/s-estudiantes/procesos',
							type: 'post',
							data: {
								boton: valor
							},
							success: function(response) {
								console.log(response);
								$('#resultado_estudiante').html(response);
							}
						});
					});

					$("input[name=estado_reserva]").click(function() {
						var valor = $(this).val();
						console.log("-> " + valor);
						if (valor == 1) {
							$.ajax({
								url: '?/s-inscripciones/procesos',
								type: 'post',
								data: {
									boton: 'reserva'
								},
								success: function(response) {
									console.log(response);
									//$('#resultado_estudiante').html(response);
									if (response == 1) {
										$("#panel_reserva").show();
										$("#btn_reservar_guardar").show();
										$("#btn_guardar_estudiante").hide();
									} else {
										$("#panel_reserva").hide();
										alertify.error('Se debe Registrar primero un concepto de pago Reserva');
									}
								}
							});
						} else if (valor == 0) {
							if (id_estudiante_editar) {
								$("#panel_reserva").hide();
								$("#btn_reservar_guardar").hide();
								$("#btn_guardar_estudiante").show();
								$.ajax({
									url: '?/s-inscripciones/procesos',
									type: 'post',
									data: {
										boton: 'inscribir_reserva',
										id_estudiante: id_estudiante_editar
									},
									success: function(response) {
										console.log(response);
										//$('#resultado_estudiante').html(response);
										if (response == 1) {
											$("#panel_reserva").hide();
											// $("#btn_reservar_guardar").show();
											// $("#btn_guardar_estudiante").hide();
											$("#no_reserva").prop("disabled", true);
											$("#si_reserva").prop("disabled", true);
											alertify.success('Se cambio de Reserva a Inscripcion correctamente');

										} else {
											//$("#panel_reserva").hide();
											alertify.error('Ocurrio un error... no se pudo cambiar el estado de reserva a inscripcion');
										}
									}
								});
							} else {
								$("#panel_reserva").hide();
								$("#btn_reservar_guardar").hide();
								$("#btn_guardar_estudiante").show();
							}

						}

					});

					$("input[name=discapacidad]").click(function() {
						var valor = $(this).val();
						console.log(valor);
						if (valor == 1) {
							$("#nro_ibc").show();
							$("#tipo_discapacidad").show();
							$("#grado_discapacidad").show();

						} else if (valor == 0) {
							$("#nro_ibc").hide();
							$("#tipo_discapacidad").hide();
							$("#grado_discapacidad").hide();
						}
					});

					$("input[name=q]").click(function() {
						var valor = $(this).val();
						console.log(valor);
						if (valor == 1) {
							$("#especies").show();

						} else {
							$("#especies").hide();
						}

					});

					$("input[name=u]").click(function() {
						var valor = $(this).val();
						console.log(valor);
						if (valor == 1) {
							$("#abandono_uno").show();
							$("#abandono_dos").show();


						} else {
							$("#abandono_uno").hide()
							$("#abandono_dos").hide();

						}


					});

					$('#lista_familiar').selectize({
						persist: false,
						createOnBlur: true,
						create: false,
						onInitialize: function() {
							$('#lista_familiar').css({
								display: 'block',
								left: '-10000px',
								opacity: '0',
								position: 'absolute',
								top: '-10000px'
							});
						},
						onChange: function() {
							$('#lista_familiar').trigger('blur');
							//alert($("#lista_familiar").val());
						},
						onBlur: function() {
							$('#lista_familiar').trigger('blur');
						}
					}).on('change', function(e) {
						var id_familiar = $(this).val();
						var id_estudiante = $("#id_familiares").val();
						if (id_estudiante) {
							$.ajax({
								url: '?/s-estudiantes/procesos',
								type: 'POST',
								data: {
									'boton': 'identificar_familiar',
									'id_familiar': id_familiar,
									'id_estudiante': id_estudiante
								},
								dataType: 'JSON',
								success: function(resp) {
									console.log(resp)
									switch (resp) {
										case 1:
											listar_familiares(id_estudiante);
											alertify.success('Se asigno al familiar correctamente');
											break;
										case 2:
											alertify.error('No se pudo asignar');
											break;
										case 3:
											alertify.error('No hay estudiante registrado o familiar asignado');
											break;
									}
								}
							});
						} else {
							alertify.error('No hay un estudiante registrado');
						}

					});
				});


				//Entrada de los tipos de documentos
				$("#input-ru").fileinput({
					language: "es",
					uploadAsync: false,
					uploadUrl: "?/s-estudiantes/documentos",
					allowedFileExtensions: ["jpg", "png", "jpeg", "pdf", "docx", "txt"],
					minFileCount: 1,
					maxFileCount: 10,
					showUpload: true,
					showRemove: false,
					uploadExtraData: {
						'id_estudiante': $("#id_documentos").val()
					},
				});

				//Calendario para el estudiante
				var disabledDays = [0, 6];
				//asignamos propiedades y capturamos la fecha del calendario del padre, madre y tutor
				$('#fecha_nacimientoo').datepicker({
					language: 'es',
					position: 'bottom left',
					onSelect: function(fd, d, picker) {
						var fecha_marcada = moment(d).format('YYYY-MM-DD');
						var hoy = moment(new Date()).format('YYYY-MM-DD');

						if (fecha_marcada < hoy) {
							//$("#fecha_inicio").val(moment(fecha_marcada).format('DD-MM-YYYY HH:mm'));

						} else {
							alertify.error('No puede una fecha mayor a la actual');
							$("#fecha_inicio").val("");
						}
						//console.log("asd");
					}
				})

				//Calendario para el tutor
				var disabledDays = [0, 6];
				//asignamos propiedades y capturamos la fecha del calendario del padre, madre y tutor
				$('#f_fecha_nacimiento_tutorr').datepicker({
					language: 'es',
					position: 'bottom left',
					onSelect: function(fd, d, picker) {
						var fecha_marcada = moment(d).format('YYYY-MM-DD');
						var hoy = moment(new Date()).format('YYYY-MM-DD');

						if (fecha_marcada < hoy) {
							//$("#fecha_inicio").val(moment(fecha_marcada).format('DD-MM-YYYY HH:mm'));

						} else {
							alertify.error('No puede una fecha mayor a la actual');
							$("#fecha_inicio").val("");
						}
						//console.log("asd");
					}
				})

				//Calendario para la reserva
				var disabledDays = [0, 6];
				//asignamos propiedades y capturamos la fecha del calendario del padre, madre y tutor
				$('#fecha_reserva').datepicker({
					language: 'es',
					position: 'bottom left',
					onSelect: function(fd, d, picker) {
						var fecha_marcada = moment(d).format('YYYY-MM-DD');
						var hoy = moment(new Date()).format('YYYY-MM-DD');

						if (fecha_marcada > hoy) {
							//$("#fecha_inicio").val(moment(fecha_marcada).format('DD-MM-YYYY HH:mm'));

						} else {
							alertify.error('No puede poner una fecha pasada para el limite');
							$("#fecha_inicio").val("");
						}
						//console.log("asd");
					}
				})


				function datos_estudiante(id_estudiante_editar) {
					listar_familiares(id_estudiante_editar);
					console.log("estoy en datos estudiante -> " + id_estudiante_editar);

					$("#panel_reserva_opciones").hide();
					$.ajax({
						url: '?/s-inscripciones/procesos',
						type: 'POST',
						data: {
							'id_estudiante': id_estudiante_editar,
							'boton': 'datos_estudiante'
						},
						dataType: 'JSON',
						success: function(resp) {
							console.log(resp);
							console.log("json tutores");
							//Inicilizamos las variables
							id_estudiante = resp['datos_personales']['id_estudiante'];
							id_inscripcion_rude = resp['datos_personales']['id_ins_inscripcion_rude'];
							arrayDocumentos = resp['documentos'];
							console.log(arrayDocumentos[0]);
							listar_documentos(arrayDocumentos);
							//Preguntamos los familiares
							res_a_id_familiar = resp['familiares'];
							for (var i = 0; i < res_a_id_familiar.length; i++) {
								console.log(res_a_id_familiar[i]['familiar_id']);
								a_id_familiar.push(res_a_id_familiar[i]['familiar_id'])
							}

							//form Inscripcion Tab Inscripcion
							$("#tipo_estudiante").val(resp['datos_personales']['tipo_estudiante_id']);
							$("#turno").val(resp['datos_personales']['turno_id']);
							$("#nivel_academico").val(resp['datos_personales']['nivel_academico_id']);
							//Cargamos el select del Curso
							cargar_select_curso(resp['datos_personales']['aula_paralelo_id'], resp['datos_personales']['nivel_academico_id']);
							//$("#select_curso").val(resp['datos_personales']['aula_paralelo_id']);
							$('#tipo_estudiante').prop('disabled', 'disabled');
							$('#turno').prop('disabled', 'disabled');
							$('#nivel_academico').prop('disabled', 'disabled');
							$('#select_curso').prop('disabled', 'disabled');

							$("#si_reserva").attr('checked', 'checked');
							//Reserva
							if (resp['datos_personales']['estado_reserva'] == '0') {
								$("#no_reserva").attr('checked', 'checked');
							} else {
								$("#si_reserva").attr('checked', 'checked');
							}
							$("#fecha_reserva").data('datepicker').selectDate(new Date(resp['datos_personales']['fecha_limite_reserva']));
							$("#monto_reserva").val(resp['datos_personales']['monto_reserva']);

							//form Estudiante Tab Datos Estudiante
							$("#id_estudiante").val(resp['datos_personales']['id_estudiante']);
							$("#id_persona").val(resp['datos_personales']['id_persona']);
							$("#nombres").val(resp['datos_personales']['nombres']);
							$("#primer_apellido").val(resp['datos_personales']['primer_apellido']);
							$("#segundo_apellido").val(resp['datos_personales']['segundo_apellido']);
							$("#tipo_documento").val(resp['datos_personales']['tipo_documento']);
							$("#numero_documento").val(resp['datos_personales']['numero_documento']);
							$("#complemento").val(resp['datos_personales']['complemento']);
							//$("#expedido").val(resp['datos_personales']['expedido']);
							$('#expedido').data('selectize').setValue(resp['datos_personales']['expedido']);
							if (resp['datos_personales']['genero'] == 'v') {
								$("#genero_v").attr('checked', 'checked');
							} else {
								$("#genero_m").attr('checked', 'checked');
							}
							var imagen = $('#avatar');
							var url;
							if (resp['datos_personales']['foto']) {
								url = 'files/profiles/estudiantes/' + resp['datos_personales']['foto'] + '.jpg';
							} else {
								url = 'assets/imgs/avatar.jpg';
							}

							//imagen.src = url;
							$("#avatar").attr("src", url);
							//$("#fecha_nacimiento").val(resp['datos_personales']['fecha_nacimiento']);
							$("#fecha_nacimiento").data('datepicker').selectDate(new Date(resp['datos_personales']['fecha_nacimiento']));
							$("#direccion").val(resp['datos_personales']['direccion']);
							//console.log(imagen);

							//form rude parte I tab rude parte I
							$("#id_inscripcion_rude").val(resp['datos_personales']['id_ins_inscripcion_rude']);
							$("#id_inscripcion").val(resp['datos_personales']['id_inscripcion']);

							$("#nro_rude").val(resp['datos_personales']['nro_rude']);
							if (resp['datos_personales']['discapacidad'] == '1') {
								$("#si_disc").attr('checked', 'checked');
							} else {
								$("#no_disc").attr('checked', 'checked');
							}

							$('#pais').data('selectize').setValue(resp['datos_personales']['nac_pais']);
							$('#departamento').data('selectize').setValue(resp['datos_personales']['nac_departamento']);
							$('#provincia').data('selectize').setValue(resp['datos_personales']['nac_provincia']);
							$('#localidad').data('selectize').setValue(resp['datos_personales']['nac_localidad']);

							$("#nro_ibc").val(resp['datos_personales']['nro_ibc']);
							$("#tipo_discapacidad").val(resp['datos_personales']['tipo_discapacidad']);
							$("#grado_discapacidad").val(resp['datos_personales']['grado_discapacidad']);
							$("#oficialia").val(resp['datos_personales']['oficialia']);
							$("#partida").val(resp['datos_personales']['partida']);
							$("#libro").val(resp['datos_personales']['libro']);
							$("#folio").val(resp['datos_personales']['folio']);
							$("#departamento").val(resp['datos_personales']['departamento']);
							$("#departamento").val(resp['datos_personales']['departamento']);
							$("#provincia").val(resp['datos_personales']['provincia']);
							$("#seccion").val(resp['datos_personales']['seccion']);

							$("#localidad").val(resp['datos_personales']['localidad']);
							$("#zona").val(resp['datos_personales']['zona']);
							$("#avenida").val(resp['datos_personales']['avenida']);
							$("#nrovivienda").val(resp['datos_personales']['nrovivienda']);
							$("#telefono").val(resp['datos_personales']['telefono']);
							$("#celular").val(resp['datos_personales']['celular']);

							//form rude parte II tab rude parte II

							$('#a').data('selectize').setValue(resp['datos_personales']['411']);
							$('#b').data('selectize').setValue(resp['datos_personales']['412']);
							$("#c").val(resp['datos_personales']['413']);
							$("#nro_rude").val(resp['datos_personales']['nro_rude']);
							if (resp['datos_personales']['421'] == '1') {
								$("#si_d").attr('checked', 'checked');
							} else {
								$("#no_d").attr('checked', 'checked');
							}

							var check = resp['datos_personales']['422'];
							if (check != "") {
								var aCheck = check.split(",");
								for (var i = 0; i < aCheck.length; i++) {
									//console.log(aCheck[i]);
									$('#' + aCheck[i])[0].checked = true;
								}
							}

							$("#e").val(resp['datos_personales']['423']);

							if (resp['datos_personales']['424'] == '1') {
								$("#si_f").attr('checked', 'checked');
							} else {
								$("#no_f").attr('checked', 'checked');
							}

							if (resp['datos_personales']['431'] == '1') {
								$("#si_g").attr('checked', 'checked');
							} else {
								$("#no_g").attr('checked', 'checked');
							}

							if (resp['datos_personales']['432'] == '1') {
								$("#si_h").attr('checked', 'checked');
							} else {
								$("#no_h").attr('checked', 'checked');
							}

							if (resp['datos_personales']['433'] == '1') {
								$("#si_i").attr('checked', 'checked');
							} else {
								$("#no_i").attr('checked', 'checked');
							}

							if (resp['datos_personales']['434'] == '1') {
								$("#si_j").attr('checked', 'checked');
							} else {
								$("#no_j").attr('checked', 'checked');
							}

							if (resp['datos_personales']['435'] == '1') {
								$("#si_k").attr('checked', 'checked');
							} else {
								$("#no_k").attr('checked', 'checked');
							}
							/******** */
							if (resp['datos_personales']['436'] == '1') {
								$("#l_1").attr('checked', 'checked');
							}
							if (resp['datos_personales']['436'] == '2') {
								$("#l_2").attr('checked', 'checked');
							}
							if (resp['datos_personales']['436'] == '3') {
								$("#l_3").attr('checked', 'checked');
							}
							if (resp['datos_personales']['436'] == '4') {
								$("#l_4").attr('checked', 'checked');
							}
							if (resp['datos_personales']['436'] == '5') {
								$("#l_5").attr('checked', 'checked');
							}
							if (resp['datos_personales']['436'] == '6') {
								$("#l_6").attr('checked', 'checked');
							}
							/******** */

							var check = resp['datos_personales']['441'];
							if (check != "") {
								var aCheck = check.split(",");
								for (var i = 0; i < aCheck.length; i++) {
									console.log(aCheck[i]);
									$('#' + aCheck[i])[0].checked = true;
								}
							}

							/************/
							if (resp['datos_personales']['442'] == '1') {
								$("#m_1").attr('checked', 'checked');
							}
							if (resp['datos_personales']['442'] == '2') {
								$("#m_2").attr('checked', 'checked');
							}
							if (resp['datos_personales']['442'] == '3') {
								$("#m_3").attr('checked', 'checked');
							}
							if (resp['datos_personales']['442'] == '4') {
								$("#m_4").attr('checked', 'checked');
							}
							/************/

							/************/
							if (resp['datos_personales']['451'] == '1') {
								$("#n_1").attr('checked', 'checked');
							}
							if (resp['datos_personales']['451'] == '2') {
								$("#n_2").attr('checked', 'checked');
							}
							if (resp['datos_personales']['451'] == '3') {
								$("#n_3").attr('checked', 'checked');
							}

							/************/



							if (resp['datos_personales']['421'] == '1') {
								$("#si_d").attr('checked', 'checked');
							} else {
								$("#no_d").attr('checked', 'checked');
							}

							var check = resp['datos_personales']['4511'];
							if (check != "") {
								var aCheck = check.split(",");
								for (var i = 0; i < aCheck.length; i++) {
									console.log(aCheck[i]);
									$('#' + aCheck[i])[0].checked = true;
								}
							}

							var check = resp['datos_personales']['452'];
							if (check != "") {
								var aCheck = check.split(",");
								for (var i = 0; i < aCheck.length; i++) {
									console.log(aCheck[i]);
									$('#' + aCheck[i])[0].checked = true;
								}
							}

							$('#o').data('selectize').setValue(resp['datos_personales']['4521']);

							var check = resp['datos_personales']['453'];
							if (check != "") {
								var aCheck = check.split(",");
								for (var i = 0; i < aCheck.length; i++) {
									console.log(aCheck[i]);
									$('#' + aCheck[i])[0].checked = true;
								}
							}

							$("#p").val(resp['datos_personales']['454']);


							/************/
							if (resp['datos_personales']['455'] == '1') {
								$("#q_1").attr('checked', 'checked');
							}
							if (resp['datos_personales']['455'] == '2') {
								$("#q_2").attr('checked', 'checked');
							}
							if (resp['datos_personales']['455'] == '3') {
								$("#q_3").attr('checked', 'checked');
							}

							/************/

							var check = resp['datos_personales']['4551a'];
							if (check != "") {
								var aCheck = check.split(",");
								for (var i = 0; i < aCheck.length; i++) {
									console.log(aCheck[i]);
									$('#' + aCheck[i])[0].checked = true;
								}
							}

							/************/
							if (resp['datos_personales']['461'] == '1') {
								$("#s_1").attr('checked', 'checked');
							}
							if (resp['datos_personales']['461'] == '2') {
								$("#s_2").attr('checked', 'checked');
							}
							if (resp['datos_personales']['461'] == '3') {
								$("#s_3").attr('checked', 'checked');
							}
							if (resp['datos_personales']['461'] == '4') {
								$("#s_4").attr('checked', 'checked');
							}

							$('#461').data('selectize').setValue(resp['datos_personales']['461a']);

							/************/

							/************/
							if (resp['datos_personales']['462'] == '1') {
								$("#t_1").attr('checked', 'checked');
							}
							if (resp['datos_personales']['462'] == '2') {
								$("#t_2").attr('checked', 'checked');
							}
							if (resp['datos_personales']['462'] == '3') {
								$("#t_3").attr('checked', 'checked');
							}
							if (resp['datos_personales']['462'] == '4') {
								$("#t_4").attr('checked', 'checked');
							}
							/************/

							/************/
							if (resp['datos_personales']['471'] == '1') {
								$("#u_1").attr('checked', 'checked');
							}
							if (resp['datos_personales']['471'] == '2') {
								$("#u_2").attr('checked', 'checked');
							}
							/************/

							/************/
							var check = resp['datos_personales']['472'];
							if (check != "") {
								var aCheck = check.split(",");
								for (var i = 0; i < aCheck.length; i++) {
									console.log(aCheck[i]);
									$('#' + aCheck[i])[0].checked = true;
								}
							}

							/************/
							$("#472a").val(resp['datos_personales']['472a']);
							/************/

							if (resp['datos_personales']['51'] == '1') {
								$("#r_1").attr('checked', 'checked');
							}
							if (resp['datos_personales']['51'] == '2') {
								$("#r_2").attr('checked', 'checked');
							}
							if (resp['datos_personales']['51'] == '3') {
								$("#r_3").attr('checked', 'checked');
							}
							if (resp['datos_personales']['51'] == '4') {
								$("#r_4").attr('checked', 'checked');
							}
							if (resp['datos_personales']['51'] == '5') {
								$("#r_5").attr('checked', 'checked');
							}
							/************/
						}
					})
				}

				//Metodo para poder subir la imagen del estudiante
				window.addEventListener('DOMContentLoaded', function() {
					var avatar = document.getElementById('avatar'); //elemento para la imagen recortada
					var image = document.getElementById('image'); //elemento para hacer el recorte
					var input = document.getElementById('input'); //elemento para cargar la imagden

					var nombre_imagen;
					var $progress = $('.progress');
					var $progressBar = $('.progress-bar');
					var $alert = $('.alert');
					var $modal = $('#modal_subir');
					//var cropper;
					var Cropper = window.Cropper;

					$('[data-toggle="tooltip"]').tooltip();

					input.addEventListener('change', function(e) {
						var files = e.target.files;
						var done = function(url) {
							nombre_imagen = input.value;
							input.value = '';
							image.src = url;
							$alert.hide();
							$modal.modal('show');
						};
						var reader;
						var file;
						var url;

						if (files && files.length > 0) {
							file = files[0];

							if (URL) {
								done(URL.createObjectURL(file));
							} else if (FileReader) {
								reader = new FileReader();
								reader.onload = function(e) {
									done(reader.result);
								};
								reader.readAsDataURL(file);
							}
						}
					});

					$modal.on('shown.bs.modal', function() {
						cropper = new Cropper(image, {
							aspectRatio: 1, //controla el cuadro transparente para hacer el recorte
							viewMode: 2, // controla el modo de ver el la imagen subida
						});
					}).on('hidden.bs.modal', function() {
						cropper.destroy();
						cropper = null;
					});

					document.getElementById('crop').addEventListener('click', function() {
						var initialAvatarURL;
						var canvas;

						$modal.modal('hide');

						if (cropper) {
							//define el tamaño que se va a guardar la imagen recortada
							canvas = cropper.getCroppedCanvas({
								width: 350,
								height: 350,
							});
							initialAvatarURL = avatar.src;
							avatar.src = canvas.toDataURL();
							$progress.show();
							$alert.removeClass('alert-success alert-warning');
							canvas.toBlob(function(blob) {
								var formData = new FormData();
								formData.append('avatar', blob, nombre_imagen);
								$.ajax('?/s-inscripciones/imagen', {
									method: 'POST',
									data: formData,
									processData: false, //es importante que este el false
									contentType: false, //es importante que este el false
									success: function(respuesta) {
										avatar.src = "files/profiles/temporal/fotos/" + respuesta; //carga la imagen recortada al elemento avatar
										$("#nombre_imagen").val(respuesta);
									}
									/*xhr: function () {
						var xhr = new XMLHttpRequest();

						xhr.upload.onprogress = function (e) {
							var percent = '0';
							var percentage = '0%';
							if (e.lengthComputable) {
							percent = Math.round((e.loaded / e.total) * 100);
							percentage = percent + '%';
							$progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
							}
						};
                    return xhr;
                    },
                    success: function () {
                    $alert.show().addClass('alert-success').text('Upload success');
                    },

                    error: function () {
                    avatar.src = initialAvatarURL;
                    $alert.show().addClass('alert-warning').text('Upload error');
                    },

                    complete: function () {
                    $progress.hide();
                    },*/
								});
							});
						}
					});
				});

				//Metodo para poder subir la imagen del padre, madre y tutor
				window.addEventListener('DOMContentLoaded', function() {
					var f_avatar = document.getElementById('f_avatar'); //elemento para la imagen recortada
					var f_image = document.getElementById('f_image'); //elemento para hacer el recorte
					var f_input = document.getElementById('f_input'); //elemento para cargar la imagden

					var nombre_imagen;
					var $progress = $('.progress');
					var $progressBar = $('.progress-bar');
					var $alert = $('.alert');
					var $modal = $('#modal_subir_tutor');
					//var cropper;
					var Cropper = window.Cropper;

					$('[data-toggle="tooltip"]').tooltip();

					f_input.addEventListener('change', function(e) {
						var files = e.target.files;
						var done = function(url) {
							nombre_imagen = f_input.value;
							f_input.value = '';
							f_image.src = url;
							$alert.hide();
							$modal.modal('show');
						};
						var reader;
						var file;
						var url;

						if (files && files.length > 0) {
							file = files[0];

							if (URL) {
								done(URL.createObjectURL(file));
							} else if (FileReader) {
								reader = new FileReader();
								reader.onload = function(e) {
									done(reader.result);
								};
								reader.readAsDataURL(file);
							}
						}
					});

					$modal.on('shown.bs.modal', function() {
						cropper = new Cropper(f_image, {
							aspectRatio: 1, //controla el cuadro transparente para hacer el recorte
							viewMode: 2, // controla el modo de ver el la imagen subida
						});
					}).on('hidden.bs.modal', function() {
						cropper.destroy();
						cropper = null;
					});

					document.getElementById('f_crop').addEventListener('click', function() {
						var initialAvatarURL;
						var canvas;

						$modal.modal('hide');

						if (cropper) {
							//define el tamaño que se va a guardar la imagen recortada
							canvas = cropper.getCroppedCanvas({
								width: 350,
								height: 350,
							});
							initialAvatarURL = f_avatar.src;
							f_avatar.src = canvas.toDataURL();
							$progress.show();
							$alert.removeClass('alert-success alert-warning');
							canvas.toBlob(function(blob) {
								var formData = new FormData();
								formData.append('avatar', blob, nombre_imagen);
								$.ajax('?/s-inscripciones/imagen', {
									method: 'POST',
									data: formData,
									processData: false, //es importante que este el false
									contentType: false, //es importante que este el false
									success: function(respuesta) {
										f_avatar.src = "files/profiles/temporal/fotos/" + respuesta; //carga la imagen recortada al elemento avatar
										$("#f_nombre_imagen").val(respuesta);
									}
									/*xhr: function () {
						var xhr = new XMLHttpRequest();

						xhr.upload.onprogress = function (e) {
							var percent = '0';
							var percentage = '0%';
							if (e.lengthComputable) {
							percent = Math.round((e.loaded / e.total) * 100);
							percentage = percent + '%';
							$progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
							}
						};
                    return xhr;
                    },
                    success: function () {
                    $alert.show().addClass('alert-success').text('Upload success');
                    },

                    error: function () {
                    avatar.src = initialAvatarURL;
                    $alert.show().addClass('alert-warning').text('Upload error');
                    },

                    complete: function () {
                    $progress.hide();
                    },*/
								});
							});
						}
					});
				});
				//Fin del metodo para subir imagen del padre, madre y tutor

				//Luis esto es importante
				/*$("#form_datos_personales").on('submit', function(e) {
					e.preventDefault();
					console.log($('[data-idtutor]').attr('data-idtutor'));
				});*/

				/************************************************************/
				/*   t1 formulario de registro de inscripcion    */
				/************************************************************/
				$("#form_inscripcion").validate({
					rules: {
						tipo_estudiante: {
							required: true
						},
						turno: {
							required: true
						},
						nivel_academico: {
							required: true
						},
						select_curso: {
							required: true
						},
						select_paralelo: {
							required: true
						}
					},
					errorClass: "help-inline",
					errorElement: "span",
					highlight: highlight,
					unhighlight: unhighlight,
					messages: {
						tipo_estudiante: "Debe seleccionar el tipo de estudiante.",
						turno: "Debe seleccionar un turno para la inscripcion.",
						nivel_academico: "Debe seleccionar el nivel academico.",
						select_curso: "Debe seleccionar el curso.",
						select_paralelo: "Debe seleccionar el paralelo."
					},
					//una ves validado guardamos los datos en la DB
					submitHandler: function(form) {
						var datos = $("#form_inscripcion").serialize();
						datos = datos + '&id_aula_paralelo_A=' + id_aula_paralelo_A;
						datos = datos + '&boton=' + 'guardar_inscripcion';
						//console.log(datos);
						$.ajax({
							type: 'POST',
							url: "?/s-inscripciones/procesos",
							data: datos,
							dataType: 'json',
							success: function(resp) {
								console.log(resp);
								switch (resp['estado']) {
									case 1:
										id_aula_paralelo_A = resp['id_aula_paralelo'];
										id_tipo_estudiante = resp['tipo_estudiante'];
										id_turno = resp['id_turno'];
										id_nivel_academico = resp['nivel_academico'];
										estado_reserva = resp['estado_reserva'];
										fecha_limite_reserva = resp['fecha_limite_reserva'];
										monto_reserva = resp['monto_reserva'];
										//console.log(id_aula_paralelo_A);
										$("#id_turno").val(id_turno);
										alertify.success('Se inscribio correctamente al curso');
										$('#personales-tab').tab('show');
										//imprimir_documentos(id_estudiante);
										break;

									case 2: //dataTable.ajax.reload();
										//$("#modal_familiar").modal("hide");
										//alertify.success('Se editó el familiar correctamente');
										alertify.error('Se edito correctamente al nuevo curso');
										$('#personales-tab').tab('show');
										break;
								}
							}
						});
					}
				})


				/************************************************************/
				/**t1 formulario de registro de estudiantes datos personales*/
				/************************************************************/

				$("#form_datos_personales").validate({
					rules: {
						//id_pensiones: {required: true},
						nombres: {
							required: true
						},
						primer_apellido: {
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
						genero: {
							required: true
						},
						fecha_nacimiento: {
							required: true
						},
						dirección: {
							required: true
						},
						imagen_cortada: {
							required: true
						}
						//id_pension: {required: true}
					},
					errorClass: "help-inline",
					errorElement: "span",
					highlight: highlight,
					unhighlight: unhighlight,
					messages: {
						//id_pensiones: "Debe seleccionar un nivel académico.",
						nombres: "Debe ingresar el nombre.",
						primer_apellido: "Debe ingresar el primer apellido",
						tipo_documento: "Debe seleccionar el tipo de documento.",
						numero_documento: "Debe ingresar el número de documento",
						expedido: "Debe poner el lugar expedito del documento de identidad.",
						genero: "Debe seleccionar el género.",
						direccion: "Debe ingresar la dirección.",
						imagen_cortada: "Debe ingresar imagen cortada."
					},
					//una ves validado guardamos los datos en la DB
					submitHandler: function(form) {
						if (a_id_familiar.length > 0) {
							var datos = new FormData($("#form_datos_personales")[0]);
							$.ajax({
								type: 'POST',
								url: "?/s-inscripciones/guardar-estudiante",
								data: datos,
								dataType: 'json',
								contentType: false,
								processData: false,
								success: function(resp) {
									cont = 0;
									switch (resp['estado']) {
										case 1: //dataTable.ajax.reload();								
											//Resivimos el id_estudiante que creamos
											id_estudiante = resp['id_estudiante'];
											$("#id_estudiante").val(resp['id_estudiante']);
											$("#id_persona").val(resp['id_persona']);
											$("#id_turno").val(resp['id_turno']);
											//Datos de la reserva para ser enviados																														
											registrarInscripcion(id_estudiante, resp['estado_reserva'], resp['monto_reserva'], resp['fecha_reserva'], resp['id_turno']);
											//alertify.success('Se registro datos personales del estudiante correctamente');
											break;
										case 2: //dataTable.ajax.reload();
											//$('#complemento_estudiante-tab').tab('show');
											$("#id_estudiante").val(resp['id_estudiante']);
											alertify.success('Se modifico los datos del estudiante correctamente');
											break;
										case 3: //dataTable.ajax.reload();
											alertify.success('El estudiante ya esta registrado en la gestion actual');
											break;
										case 4: //dataTable.ajax.reload();
											alertify.success('El estudiante fue modificado con exito');
											$('#complemento_estudiante-tab').tab('show');
											break;
									}
								}
							});
						} else {
							alertify.error('Debe registrar los Datos de Padre y Madre o Tutor antes poder inscribirse...');
						}

					}
				})

				/************************************************************/
				/*   t1 formulario de registro certificado de nacimiento    */
				/************************************************************/

				//Accion de guardar el Tab Certificado de nacimiento
				$("#form_datos_certificado").validate({
					rules: {
						//id_pensiones: {required: true},
						oficialia: {
							required: false
						},
						libro: {
							required: false
						},
						partida: {
							required: false
						},
						folio: {
							required: false
						},
						departamento: {
							required: false
						},
						provincia: {
							required: false
						},
						seccion: {
							required: false
						},
						localidad: {
							required: false
						},
						zona: {
							required: false
						},
						telefono: {
							required: false
						},
						celular: {
							required: false
						}
						//id_pension: {required: true}
					},
					errorClass: "help-inline",
					errorElement: "span",
					highlight: highlight,
					unhighlight: unhighlight,
					messages: {
						//id_pensiones: "Debe seleccionar un nivel académico.",
						oficialia: "Debe ingresar oficialia N°.",
						libro: "Debe ingresar Libro N°",
						partida: "Debe ingresar Partida N°.",
						folio: "Debe ingresar Folio N°",
						departamento: "Debe seleccionar un departamento.",
						provincia: "Debe seleccionar una provincia.",
						seccion: "Debe seleccionar una seccion.",
						localidad: "Debe seleccionar una localidad.",
						zona: "Debe introduccir una zona.",
						telefono: "Debe introducir un telefono de contacto.",
						celular: "Debe ingresar un celular de contacto."
					},
					//una ves validado guardamos los datos en la DB
					submitHandler: function(form) {
						//var datos = new FormData($("#form_datos_certificado")[0]);
						var datos = $("#form_datos_certificado").serialize();
						datos = datos + ('&id_estudiante=' + (id_estudiante * 1));
						//datos = datos + ('&id_inscripcion_rude=' + (id_inscripcion_rude*1));
						datos = datos + '&boton=guardar_certificado';
						console.log("Certificado");
						console.log(datos);
						$.ajax({
							type: 'POST',
							url: "?/s-inscripciones/procesos",
							data: datos,
							dataType: 'json',
							success: function(resp) {
								cont = 0;
								//console.log(resp['id_inscripcion_rude']);
								switch (resp['estado']) {
									//switch (resp) {	
									case 1: //dataTable.ajax.reload();
										//saltamos al siguiente tab actualizacion de datos
										$('#rude-tab').tab('show');
										id_inscripcion_rude = resp['id_inscripcion_rude'];
										//Resivimos el id_estudiante que creamos
										/*id_estudiante = resp['id_estudiante'];
										$("#id_estudiante").val(resp['id_estudiante']);
										$("#id_familiares").val(resp['id_estudiante']);
										$("#id_vacunas").val(resp['id_estudiante']);
										$("#id_documentos").val(resp['id_estudiante']);
										$("#id_inscripciones").val(resp['id_estudiante']);
										id = resp['id_estudiante'];*/
										//listar_familiares(id);
										alertify.success('Se Inicio el registro del RUDE Correctamente');
										break;
									case 2: //dataTable.ajax.reload();
										$('#rude-tab').tab('show');
										id_inscripcion_rude = resp['id_inscripcion_rude'];
										//$("#id_estudiante").val(resp['id_estudiante']);
										alertify.success('Se modifico los datos de Inicio de rude');
										break;
									case 3: //dataTable.ajax.reload();
										$('#familiar-tab').tab('show');
										alertify.error('El estudiante ya esta registrado en la gestion actual');
										break;
								}
							}
						});
					}
				})

				/************************************************************/
				/*   t1 formulario de registro del RUDE                     */
				/************************************************************/
				$("#form_datos_rude").validate({
					rules: {
						a: {
							required: false
						},
						b: {
							required: false
						},
						c: {
							required: false
						},
						d: {
							required: false
						},
						salud: {
							required: false
						},
						e: {
							required: false
						},
						f: {
							required: false
						},
						g: {
							required: false
						},
						h: {
							required: false
						},
						i: {
							required: false
						},
						j: {
							required: false
						},
						k: {
							required: false
						},
						l: {
							required: false
						},
						internet: {
							required: false
						},
						m: {
							required: false
						},
						n: {
							required: false
						},
						trabajo: {
							required: false
						},
						trabajo: {
							required: false
						},
						turno: {
							required: false
						},
						p1: {
							required: false
						},
						q: {
							required: false
						},
						pago: {
							required: false
						},
						r: {
							required: false
						}
					},
					errorClass: "help-inline",
					errorElement: "span",
					highlight: highlight,
					unhighlight: unhighlight,
					messages: {
						a: "Debe ingresar un idioma.",
						b: "Debe ingresa los idiomas que habla.",
						c: "Debe elegir una opcion.",
						d: "Debe responder la pregunta.",
						salud: "Debe responder la pregunta.",
						e: "Debe responder cuantas veces asistio al centro de salud",
						f: "Debe responder si cuenta con seguro de salud.",
						g: "Debe responder la pregunta.",
						h: "Debe responder la pregunta.",
						i: "Debe responder la pregunta.",
						j: "Debe responder la pregunta.",
						k: "Debe responder la pregunta.",
						l: "Debe responder la pregunta.",
						internet: "Debe seleccionar una opcion.",
						m: "Debe responder la pregunta.",
						n: "Debe responder la pregunta.",
						trabajo: "Debe elegir al menos una opcion.",
						turno: "Debe responder la pregunta.",
						p: "Debe responder la pregunta.",
						q: "Debe responder la pregunta.",
						pago: "Debe responder la pregunta.",
						r: "Debe responder la pregunta."
					},
					//una ves validado guardamos los datos en la DB
					submitHandler: function(form) {
						//var datos = new FormData($("#form_datos_certificado")[0]);
						var datos = $("#form_datos_rude").serialize();
						datos = datos + ('&id_inscripcion_rude=' + id_inscripcion_rude);
						datos = datos + '&boton=guardar_rude';
						console.log(datos);
						$.ajax({
							type: 'POST',
							url: "?/s-inscripciones/procesos",
							data: datos,
							dataType: 'json',
							success: function(resp) {
								cont = 0;
								//console.log(resp['id_inscripcion_rude']);
								switch (resp['estado']) {
									//switch (resp) {	
									case 1: //dataTable.ajax.reload();
										//saltamos al siguiente tab actualizacion de datos
										$('#rep_documentos-tab').tab('show');
										id_inscripcion_rude = resp['id_inscripcion_rude'];
										listar_documentos();
										//Resivimos el id_estudiante que creamos
										/*id_estudiante = resp['id_estudiante'];
										$("#id_estudiante").val(resp['id_estudiante']);
										$("#id_familiares").val(resp['id_estudiante']);
										$("#id_vacunas").val(resp['id_estudiante']);
										$("#id_documentos").val(resp['id_estudiante']);
										$("#id_inscripciones").val(resp['id_estudiante']);
										id = resp['id_estudiante'];*/
										//listar_familiares(id);
										alertify.success('Se registro correctamente el RUDE parte II');
										break;
									case 2: //dataTable.ajax.reload();
										$('#rep_documentos-tab').tab('show');
										//$("#id_estudiante").val(resp['id_estudiante']);
										alertify.success('Se modifico los datos de RUDE parte II');
										break;
									case 3: //dataTable.ajax.reload();
										alertify.error('Error al modfiicar el RUDE');
										break;
								}
							}
						});
					}
				})

				/************************************************************/
				/*   t1 formulario de registro del familiar                 */
				/************************************************************/
				$("#form_familiar").validate({
					rules: {
						f_nombres: {
							required: true
						},
						f_primer_apellido: {
							required: true
						},
						f_tipo_documento: {
							required: true
						},
						f_numero_documento: {
							required: true
						},
						f_expedido: {
							required: true
						},
						f_fecha_nacimiento: {
							required: true
						},
						f_idioma_frecuente: {
							required: true
						},
						f_telefono: {
							required: true
						},
						f_grado_instruccion: {
							required: true
						},
						f_parentesco: {
							required: true
						}
					},
					errorClass: "help-inline",
					errorElement: "span",
					highlight: highlight,
					unhighlight: unhighlight,
					messages: {
						f_nombres: "Debe ingresar nombre(s) de familiar",
						f_primer_apellido: "Debe ingresar primer apellido",
						f_tipo_documento: "Debe ingresar tipo de documento",
						f_expedido: "Debe poner el lugar de expedito del Documento de identidad",
						f_numero_documento: "Debe ingresar número de documento",
						f_fecha_nacimiento: "Debe ingresar fecha de nacimiento",
						f_idioma_frecuente: "Debe ingresar el idioma que habla mas",
						f_telefono: "Debe ingresar teléfono",
						f_grado_instruccion: "Debe ingresar el mayor grado de instrucción",
						f_parentesco: "Debe ingresar un tipo de parentesco"
					},
					//una ves validado guardamos los datos en la DB
					submitHandler: function(form) {
						var datos = $("#form_familiar").serialize();
						//var id_familiares = $("#id_familiares").val();
						//datos = datos + '&tutorcheck=' + check;
						datos = datos + '&boton=' + 'agregar_familiar';
						console.log(datos);

						$.ajax({
							type: 'POST',
							url: "?/s-inscripciones/procesos",
							data: datos,
							dataType: 'json',
							success: function(resp) {
								console.log(resp);
								switch (resp['estado']) {
									case 1: //dataTable.ajax.reload();
										//$("#modal_familiar").modal("hide");
										//listar_familiares(id_familiares);
										alertify.success('Se registro el familiar correctamente');
										//Preguntamos si e familiar es el tutor responsable ante la unidad educativa	
										if (resp['valor_tutor'] != 0) {
											id_familiar_tutor = resp['id_familiar'];
											//id_familiar       = id_familiar + (resp['id_familiar']+",");										
											a_id_familiar.push(resp['id_familiar']);
										} else {
											//id_familiar       = id_familiar + (resp['id_familiar']+",");	
											a_id_familiar.push(resp['id_familiar']);
										}
										//Vamos mostrando como se arma el array defamiliares
										console.log(a_id_familiar);
										lista_familiar = lista_familiar + resp['familiar'];
										$("#lista_familia_temporal").html(lista_familiar);
										//$('[data-idtutor]').attr('data-idtutor', resp);
										document.getElementById("form_familiar").reset();
										break;

									case 2: //dataTable.ajax.reload();
										//$("#modal_familiar").modal("hide");
										alertify.success('Se editó el familiar correctamente');
										listar_familiares(id_estudiante_editar);
										break;

									case 3: //dataTable.ajax.reload();
										//$("#modal_familiar").modal("hide");
										alertify.success('Se editó el familiar correctamente');
										break;
									case 10: //dataTable.ajax.reload();
										//$("#modal_familiar").modal("hide");
										alertify.info('Se añadio un nuevo familiar correctamente');
										break;
								}
							}
						});
					}
				})

				/************************************************************/
				/*   t1 formulario de registro de todos los documentos      */
				/************************************************************/
				$("#form_documento").validate({
					rules: {},
					errorClass: "help-inline",
					errorElement: "span",
					highlight: highlight,
					unhighlight: unhighlight,
					messages: {},
					//una ves validado guardamos los datos en la DB
					submitHandler: function(form) {

						var datos = $("#form_documento").serialize();
						datos = datos + ('&id_estudiante=' + (id_estudiante * 1));
						//var id_estudiante = $("#id_estudiante").val();
						//datos = datos + '&id_estudiante='+ id_estudiante;
						datos = datos + '&boton=' + 'guardar_documentos';
						$.ajax({
							type: 'POST',
							url: "?/s-estudiantes/procesos",
							data: datos,
							success: function(resp) {
								console.log(resp);
								switch (resp) {
									case '1': //dataTable.ajax.reload();							
										/*$("#modal_familiar").modal("hide");
										listar_familiares(id_familiares);*/
										alertify.success('Se registro los documentos correctamente');
										break;
									case '2': //dataTable.ajax.reload();
										//$("#modal_familiar").modal("hide");
										alertify.success('Se editó los documentos');
										break;
								}
							}
						});
					}
				});

				/************************************************************/
				/**t1 formulario de registro de las vacunas                 */
				/************************************************************/
				$("#form_vacunas").validate({
					rules: {
						//id_pensiones: {required: true},
						observaciones_vacunas: {
							required: true
						}
					},
					errorClass: "help-inline",
					errorElement: "span",
					highlight: highlight,
					unhighlight: unhighlight,
					messages: {
						//id_pensiones: "Debe seleccionar un nivel académico.",
						observaciones_vacunas: "Debe ingresar observaciones."
					},
					//una ves validado guardamos los datos en la DB
					submitHandler: function(form) {
						var datos = $("#form_vacunas").serialize();
						datos = datos + '&id_estudiante=' + id_estudiante;
						datos = datos + '&boton=vacunas';
						$.ajax({
							type: 'POST',
							url: "?/s-inscripciones/procesos",
							data: datos,
							dataType: 'json',
							success: function(resp) {
								cont = 0;
								switch (resp['estado']) {
									case 1: //dataTable.ajax.reload();
										$('#inscripcion-tab').tab('show');
										$("#ids_familar").val(a_id_familiar.join('/'));
										/*listar_familiares(id_estudiante);*/
										alertify.success('Se registro las vacunas del estudiante correctamente');
										break;
									case 2: //dataTable.ajax.reload();
										$('#inscripcion-tab').tab('show');
										/*listar_familiares(id_estudiante);*/
										alertify.success('Se modifico los datos del estudiante correctamente');
										break;
									case 3: //dataTable.ajax.reload();
										alertify.error('No se pudo registrar al estudiante');
										break;
								}
							}
						});
					}
				})

				/*let id_estudiante = $("#id_familiares").val();

				var columns=[
					{data: 'id_estudiante_familiar'},
					{data: 'foto'},
					{data: 'primer_apellido'},
					{data: 'segundo_apellido'},
					{data: 'nombres'},
					{data: 'numero_documento'},
					{data: 'telefono_oficina'},
					{data: 'tutor'}
				];
				var cont = 0;
				//function listarr(){
				var dataTable = $('#table').DataTable({
					language: dataTableTraduccion,
					searching: true,
					paging:true,
					"lengthChange": true,
					"responsive": true,
					ajax: {
						url: '?/s-estudiantes/procesos',
						dataSrc: '',
						type:'POST',
						data: {'id_estudiante': id_estudiante, 'boton': 'listar_familiares'},
						dataType: 'json'
					},
					columns: columns,
					"columnDefs": [
							{
									"render": function (data, type, row) {
										contenido = row['id_estudiante_familiar'] +"*"+ row['id_familiar'] +"*"+ row['nombre_familiar']+"*"+ row['numero_documento']+"*"+ row['profesion']+"*"+ row['direccion_oficina']+"*"+ row['telefono_oficina'] +"*"+ row['id_estudiante'];
				                       	html = '<div class=""><button class="btn btn-success btn-xs" onclick="abrir_modificar_familiar('+"'"+contenido+"'"+');"><i class="fa fa-edit"></i></button> &nbsp <button class="btn btn-danger btn-xs" onclick="abrir_eliminar_familiar('+"'"+contenido+"'"+')"><i class="fa fa-trash"></i></button></div>'
										//html = ' <a href="#" class="btn btn-primary btn-xs">Primary</a> <a href="#" class="btn btn-brand btn-xs">Brand</a>'
										return html;
									},
									"targets": 8
							},
							{
									"render": function (data, type, row) {
										if(row['tutor'] == 1){
											check = '<input type="checkbox" class="form-control" checked>';
										}else{
											check = '<input type="checkbox" class="form-control">';
										}
										return check;
									},
									"targets": 7
							},
							{
									"render": function (data, type, row) {
										var imagen = "";
										//var foto = "imgs . '/avatar.jpg'";
										//var foto = "assets/imgs/avatar.jpg";
										//console.log(foto);
										if(row['foto'] == ""){
											foto = "assets/imgs/avatar.jpg";
										}else{
											foto = "assets/imgs/" + row['foto'];
										}
										imagen += "<img src='"+ foto +"' class='img-rounded cursor-pointer' data-toggle='modal' data-target='#modal_mostrar' data-modal-size='modal-md' data-modal-title='Imagen' width='64' height='64'>";
										return imagen;
									},
									"targets": 1
							},
							{
									"render": function (data, type, row) {
										cont = cont +1;
										return cont;
									},
									"targets": 0
							}
					]
				});*/

				function imprimir_documentos(id) {
					//$.open('?/b-electronicas/imprimir/' + venta, true); 
					//window.location.reload();
					window.open('?/s-inscripciones/imprimir-poliza/' + id, true);
					window.open('?/s-inscripciones/imprimir-contracto-servicio/' + id, true);
				}


				function listar_familiares(id_estudiante) {
					console.log("Familiares");
					console.log(id_estudiante);
					console.log("Fin familiares");
					$.ajax({
						type: 'POST',
						url: "?/s-inscripciones/procesos",
						dataType: 'JSON',
						data: {
							'id_estudiante': id_estudiante,
							'boton': 'listar_familiares'
						},
						success: function(data) {
							html = "";
							imagen = "";
							for (var i = 0; i < data.length; i++) {
								console.log(data[i]['id_estudiante_familiar']);
								console.log("Fin familiares");
								contenido = data[i]['id_estudiante_familiar'] + "*" + data[i]['id_familiar'] + "*" + data[i]['id_estudiante'] + "*" + data[i]['nombres'] + "*" + data[i]['primer_apellido'] + "*" + data[i]['segundo_apellido'] + "*" + data[i]['numero_documento'] + "*" + data[i]['profesion'] + "*" + data[i]['direccion_oficina'] + "*" + data[i]['telefono_oficina'] + "*" + data[i]['foto'] + "*" + data[i]['tutor'];
								if (data[i]['tutor'] == '1') {
									check = '<input type="checkbox" checked data-toggle="toggle" id="tutor' + i + '" name="exp[]" value="' + contenido + '" onclick="seleccionar_tutor(' + i + ',' + id_estudiante + ');">';
								} else {
									check = '<input type="checkbox" data-toggle="toggle" id="tutor' + i + '" name="exp[]" value="' + contenido + '" onclick="seleccionar_tutor(' + i + ',' + id_estudiante + ');">';
								}

								if (data[i]['foto'] == "") {
									foto = "assets/imgs/avatar.jpg";
								} else {
									foto = "files/profiles/familiares/" + data[i]['foto'] + ".jpg";
								}
								imagen = "<img src='" + foto + "' class='img-rounded cursor-pointer' data-toggle='modal' data-target='#modal_mostrar' data-modal-size='modal-md' data-modal-title='Imagen' width='64' height='64'>";
								//html += '<tr><td>' + (i + 1) + '</td><td>' + imagen + '</td><td>' + data[i]['primer_apellido'] + '</td><td>' + data[i]['segundo_apellido'] + '</td><td>' + data[i]['nombres'] + '</td><td>' + data[i]['numero_documento'] + '</td><td>' + data[i]['telefono_oficina'] + '</td><td>' + check + '</td><td><button class="btn btn-success btn-xs" onclick="abrir_modificar(' + "'" + contenido + "'" + ');"><i class="fa fa-edit"></i></button>  <button class="btn btn-danger btn-xs" onclick="eliminar_familiar(' + "'" + contenido + "'" + ');"><i class="fa fa-trash"></i></button> </td></tr>'
								html += '<tr><td>' + (i + 1) + '</td><td>' + imagen + '</td><td>' + data[i]['primer_apellido'] + '</td><td>' + data[i]['segundo_apellido'] + '</td><td>' + data[i]['nombres'] + '</td><td>' + check + '</td><td><button class="btn btn-success btn-xs" onclick="abrir_modificar_familiar(' + "'" + contenido + "'" + ');"><i class="fa fa-edit"></i></button>  <button class="btn btn-danger btn-xs" onclick="eliminar_familiar(' + "'" + contenido + "'" + ');"><i class="fa fa-trash"></i></button> </td></tr>'
							}
							$("#contenedor_familiares").html(html);
						}
					});
				}

				//Listado de documentos recepcionados
				function listar_documentos(arrayDocumentos) {
					$.ajax({
						type: 'POST',
						url: "?/s-inscripciones/procesos",
						dataType: 'JSON',
						data: {
							'boton': 'listar_documentos'
						},
						success: function(data) {
							console.log(data);
							html = "";
							imagen = "";
							for (var i = 0; i < data.length; i++) {
								contenido = data[i]['id_tipo_documento'] + "*" + data[i]['nombre'] + "*" + data[i]['descripcion'] + "*" + data[i]['estado'];
								console.log("Recorremos array de documentos");
								console.log(arrayDocumentos);
								if (arrayDocumentos[i] == null) {
									checkCopia = '<input type="checkbox" name="copia[' + data[i]['id_tipo_documento'] + ']" value="' + data[i]['id_tipo_documento'] + '">';
								} else {
									if (arrayDocumentos[i]['copia'] == "X") {
										checkCopia = '<input type="checkbox" checked name="copia[' + data[i]['id_tipo_documento'] + ']" value="' + data[i]['id_tipo_documento'] + '">';
									} else {
										checkCopia = '<input type="checkbox" name="copia[' + data[i]['id_tipo_documento'] + ']" value="' + data[i]['id_tipo_documento'] + '">';
									}
								}

								if (arrayDocumentos[i] == null) {
									checkOriginal = '<input type="checkbox" name="original[' + data[i]['id_tipo_documento'] + ']" value="' + data[i]['id_tipo_documento'] + '">';
								} else {
									if (arrayDocumentos[i]['original'] == "X") {
										checkOriginal = '<input type="checkbox" checked name="original[' + data[i]['id_tipo_documento'] + ']" value="' + data[i]['id_tipo_documento'] + '">';
									} else {
										checkOriginal = '<input type="checkbox" name="original[' + data[i]['id_tipo_documento'] + ']" value="' + data[i]['id_tipo_documento'] + '">';
									}
								}


								if (arrayDocumentos[i] == null) {
									textObservacion = '<input type="text" value="" name="observacion[' + data[i]['id_tipo_documento'] + ']">';
								} else {
									if (arrayDocumentos[i]['nombre_documento'] != "") {
										textObservacion = '<input type="text" value="' + arrayDocumentos[i]['nombre_documento'] + '" name="observacion[' + data[i]['id_tipo_documento'] + ']">';
									} else {
										textObservacion = '<input type="text" value="" name="observacion[' + data[i]['id_tipo_documento'] + ']">';
									}
								}

								/*if (data[i]['tutor'] == '1') {
									check = '<input type="checkbox" checked data-toggle="toggle" id="tutor' + i + '" name="exp[]" value="' + contenido + '" onclick="seleccionar_tutor(' + i + ',' + id_estudiante + ');">';
								} else {
									check = '<input type="checkbox" data-toggle="toggle" id="tutor' + i + '" name="exp[]" value="' + contenido + '" onclick="seleccionar_tutor(' + i + ',' + id_estudiante + ');">';
								}

								if (data[i]['foto'] == "") {
									foto = "assets/imgs/avatar.jpg";
								} else {
									foto = "files/profiles/familiares/" + data[i]['foto'] + ".jpg";
								}
								imagen = "<img src='" + foto + "' class='img-rounded cursor-pointer' data-toggle='modal' data-target='#modal_mostrar' data-modal-size='modal-md' data-modal-title='Imagen' width='64' height='64'>";
								*/
								//html += '<tr><td>' + (i + 1) + '</td><td>' + imagen + '</td><td>' + data[i]['primer_apellido'] + '</td><td>' + data[i]['segundo_apellido'] + '</td><td>' + data[i]['nombres'] + '</td><td>' + data[i]['numero_documento'] + '</td><td>' + data[i]['telefono_oficina'] + '</td><td>' + check + '</td><td><button class="btn btn-success btn-xs" onclick="abrir_modificar(' + "'" + contenido + "'" + ');"><i class="fa fa-edit"></i></button>  <button class="btn btn-danger btn-xs" onclick="eliminar_familiar(' + "'" + contenido + "'" + ');"><i class="fa fa-trash"></i></button> </td></tr>'
								html += '<tr><td class="text-nowrap text-center">' + (i + 1) + '</td><td class="text-nowrap text-center">' + data[i]['nombre'] + '</td><td class="text-nowrap text-center">' + checkCopia + '</td><td class="text-nowrap text-center">' + checkOriginal + '</td><td class="text-nowrap text-center">' + textObservacion + '</td></tr>'
							}
							$("#contenedor_documentos").html(html);
						}
					});
				}

				function seleccionar_tutor(i, id_estudiante) {
					nombre = "#tutor" + i;
					var contenido = $(nombre).val();
					var d = contenido.split("*");
					if ($(nombre).prop('checked')) {
						//alert('Seleccionado');
						$.ajax({
							url: '?/s-estudiantes/procesos',
							type: 'POST',
							data: {
								'id_estudiante_familiar': d[0],
								'id_tutor': d[1],
								'id_estudiante': d[2],
								'boton': 'seleccionar_tutor'
							},
							success: function(resp) {
								if (resp == 1) {
									listar_familiares(id_estudiante);
								}
							}
						});
					} else {
						$.ajax({
							url: '?/s-estudiantes/procesos',
							type: 'POST',
							data: {
								'id_estudiante_familiar': d[0],
								'boton': 'borrar_tutor'
							},
							success: function(resp) {
								if (resp == 1) {
									listar_familiares(id_estudiante);
									$("#id_estudiante_familiar").val("");
								}
							}
						});
					}
				}

				function abrir_form_familiar() {
					$("#modal_familiar").modal("show");
					$("#titulo_modal_familiar").text("Registrar Familiar");
					$("#form_familiar")[0].reset();
					$("#btn_editar").hide();
					$("#btn_nuevo").show();
				}

				function abrir_modificar_familiar(contenido) {
					var d = contenido.split("*");
					//$("#modal_familiar").modal("show");
					//$("#titulo_modal_familiar").text("Modificar datos del Familiar");
					$("#form_familiar")[0].reset();
					//$("#btn_editar").show();
					//$("#btn_nuevo").hide();
					id_familiar = d[1];
					$.ajax({
						url: '?/s-inscripciones/procesos',
						type: 'POST',
						data: {
							'id_familiar': id_familiar,
							'boton': 'buscar_datos_personales'
						},
						dataType: 'JSON',
						success: function(resp) {
							//console.log(resp);
							$("#id_estudiante_editar").val("");
							$("#f_id_familiar").val(resp['id_familiar']);
							$("#f_id_persona").val(resp['id_persona']);
							$("#f_nombres").val(resp['nombres']);
							$("#f_primer_apellido").val(resp['primer_apellido']);
							$("#f_segundo_apellido").val(resp['segundo_apellido']);
							$("#f_tipo_documento").val(resp['tipo_documento']);
							$("#f_numero_documento").val(resp['numero_documento']);
							$('#f_expedido').data('selectize').setValue(resp['expedido']);
							$("#f_complemento").val(resp['complemento']);
							//$("#f_genero").val(resp['genero']);
							if (resp['genero'] == 'm') {
								$("#f_genero_m").attr('checked', 'checked');
							} else {
								$("#f_genero_v").attr('checked', 'checked');
							}

							$("#f_fecha_nacimiento_tutor").val(moment(resp['fecha_nacimiento']).format('DD/MM/YYYY'));
							$('#f_idioma_frecuente').data('selectize').setValue(resp['idioma_frecuente']);
							$("#f_correo_electronico").val(resp['correo_electronico']);
							$("#f_telefono").val(resp['telefono_oficina']);
							$('#f_profesion').data('selectize').setValue(resp['profesion']);

							//$("#f_tutor").val(resp['tutor']);
							if (resp['tutor'] == '1') {
								$("#f_tutor_si").attr('checked', 'checked');
							} else {
								$("#f_tutor_no").attr('checked', 'checked');
							}
							$("#f_direccion_oficina").val(resp['direccion_oficina']);
							$('#f_grado_instruccion').data('selectize').setValue(resp['grado_instruccion']);
							$('#f_parentesco').data('selectize').setValue(resp['parentesco']);

							var imagen = $('#f_avatar');
							var url;
							if (resp['foto']) {
								url = 'files/profiles/familiares/' + resp['foto'] + '.jpg';
							} else {
								url = 'assets/imgs/avatar.jpg';
							}
							//imagen.src = url;
							$("#f_avatar").attr("src", url);
						}
					});
				}

				function cargar_idioma_frecuente(id_familiar, idioma) {
					$.post({
						url: '?/s-inscripciones/procesos',
						type: 'POST',
						data: {
							'boton': 'cargar_idioma'
						},
						dataType: 'JSON',
						success: function(resp) {
							//console.log("CargarIdiomas -> "+idioma);
							//console.log(resp);
							$("#f_idioma_frecuente").html("");
							//$("#f_idioma_frecuente").append('<option value="' + 0 + '">Seleccione</option>');
							for (var i = 0; i < resp.length; i++) {
								if (resp[i]["idioma_frecuente"] == idioma) {
									console.log("CargarIdiomasFor -> " + resp[i]["idioma_frecuente"]);
									$("#f_idioma_frecuente").append('<option value="' + resp[i]["idioma_frecuente"] + '" selected="selected">' + resp[i]["idioma_frecuente"] + '</option>');
								} else {
									$("#f_idioma_frecuente").append('<option value="' + resp[i]["idioma_frecuente"] + '">' + resp[i]["idioma_frecuente"] + '</option>');
								}

							}
						}
					});
				}


				function cargar_tipo_documento() {
					$.post({
						url: '?/s-inscripciones/procesos',
						type: 'POST',
						data: {
							'boton': 'listar_tipo_documento'
						},
						dataType: 'JSON',
						success: function(resp) {
							$("#tipo_documento").html("");
							$("#tipo_documento").append('<option value="' + 0 + '">Seleccione</option>');
							for (var i = 0; i < resp.length; i++) {
								$("#tipo_documento").append('<option value="' + resp[i]["id_catalogo_detalle"] + '">' + resp[i]["nombre_catalogo_detalle"] + '</option>');
							}
						}
					});
				}


				function cargar_select_curso(aula_paralelo_id, nivel_academico_id) {
					nivel = nivel_academico_id;
					turno = $("#turno option:selected").val()

					//alert(nivel);
					$.ajax({
						url: '?/s-inscripciones/procesos',
						type: 'POST',
						data: {
							'boton': 'listar_cursos',
							'nivel': nivel,
							'turno': turno
						},
						dataType: 'JSON',
						success: function(resp) {
							//alert(resp[0]['id_catalogo_detalle']);
							//console.log(resp);
							$("#select_curso").html("");
							$("#select_curso").append('<option value="' + 0 + '">Seleccionar</option>');
							for (var i = 0; i < resp.length; i++) {
								if (resp[i]["id_aula_paralelo"] == aula_paralelo_id) {
									$("#select_curso").append('<option value="' + resp[i]["id_aula_paralelo"] + '" selected="selected">' + resp[i]["nombre_aula"] + ' ' + resp[i]["nombre_paralelo"] + '</option>');
								} else {
									$("#select_curso").append('<option value="' + resp[i]["id_aula_paralelo"] + '">' + resp[i]["nombre_aula"] + ' ' + resp[i]["nombre_paralelo"] + '</option>');
								}
							}
							//console.log(resp[0]);
						}
					});
				}

				function listar_cursos() {
					nivel = $("#nivel_academico option:selected").val();
					turno = $("#turno option:selected").val()

					//alert(nivel);
					$.ajax({
						url: '?/s-inscripciones/procesos',
						type: 'POST',
						data: {
							'boton': 'listar_cursos',
							'nivel': nivel,
							'turno': turno
						},
						dataType: 'JSON',
						success: function(resp) {
							//console.log(resp);
							//alert(resp[0]['id_catalogo_detalle']);
							$("#select_curso").html("");
							$("#select_curso").append('<option value="' + 0 + '">Seleccionar</option>');
							for (var i = 0; i < resp.length; i++) {
								$("#select_curso").append('<option value="' + resp[i]["id_aula_paralelo"] + '">' + resp[i]["nombre_aula"] + ' ' + resp[i]["nombre_paralelo"] + '</option>');
							}
							//console.log(resp[0]);
						}
					});
				}

				function listar_vacantes() {
					id_aula_paralelo = $("#select_curso option:selected").val()
					//alert(nivel);
					$.ajax({
						url: '?/s-inscripciones/procesos',
						type: 'POST',
						data: {
							'boton': 'listar_vacantes',
							'id_aula_paralelo': id_aula_paralelo
						},
						dataType: 'JSON',
						success: function(resp) {
							if (resp > 0) {
								$("#vacantes").val(resp);
								$("#btn_inscripcion").show();
								nro_varones_mujeres(id_aula_paralelo);
							} else {
								$("#btn_inscripcion").hide();
								alertify.error('No hay vacantes en este curso y paralelo');
							}
						}
					});
				}

				function nro_varones_mujeres(id_aula_paralelo) {
					$.ajax({
						url: '?/s-inscripciones/procesos',
						type: 'POST',
						data: {
							'boton': 'nro_varones_mujeres',
							'id_aula_paralelo': id_aula_paralelo
						},
						dataType: 'JSON',
						success: function(resp) {
							console.log(resp);
							$("#nro_ninos").val("#Varones : " + resp[0]['nro_varones']);
							$("#nro_ninas").val("#Mujeres : " + resp[0]['nro_mujeres']);
							$("#inscritos").val("#Inscritos : " + resp[0]['inscritos']);
							$("#cupo_total").val("#cupo_total : " + resp[0]['cupo_total']);
							//alertify.error('No hay vacantes en este curso y paralelo');

						}
					});
				}

				function registrarInscripcion(id_estudiante, estado_reserva, monto_reserva, fecha_reserva, id_turno) {
					$.ajax({
						url: '?/s-inscripciones/procesos',
						type: 'POST',
						data: {
							'boton': 'registrar_inscripcion_estudiante',
							'id_estudiante': id_estudiante,
							'id_turno': id_turno,
							'id_aula_paralelo': id_aula_paralelo_A,
							'id_tipo_estudiante': id_tipo_estudiante,
							'id_nivel_academico': id_nivel_academico,
							'ids_familiar': a_id_familiar.join('/'),
							'id_familiar_tutor': id_familiar_tutor,
							'estado_reserva': estado_reserva,
							'fecha_limite_reserva': fecha_limite_reserva,
							'monto_reserva': monto_reserva,
						},
						dataType: 'JSON',
						success: function(resp) {
							console.log("RegistarInscripcion");
							console.log(resp);
							switch (resp['estado']) {
								case 1:
									ins_id_estudiante = resp['id_inscripcion'];
									id_inscripcion = resp['id_inscripcion'];
									$("#id_inscripcion").val(resp['id_inscripcion']);
									registrarInscripcionRudeVacio(ins_id_estudiante);
									console.log(estado_reserva + '    ññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññññ');
									if (estado_reserva == 1) {
										console.log(estado_reserva + '    gggggggggggggggggggggggggggggggggggggggggggggggggg');
										reservar_cupo_imprimir_comprobante(id_estudiante, id_inscripcion, monto_reserva);
										//Aqui mandar las variables para la reserva
										/*id_familiar_tutor
										id_estudiante
										fecha_reserva
										monto_reserva*/
									}
									break;
								case 2:
									$("#id_estudiante").val(resp['id_estudiante']);
									$("#id_inscripcion").val(resp['id_inscripcion']);
									alertify.success('Se edito correctamente la edicion de la inscripcion...');
									//$('#complemento_estudiante-tab').tab('show');
									break;
								case 0: //dataTable.ajax.reload();
									alertify.error('No se pudo registrar al estudiante...');
									break;
							}
						}
					});
				}

				function registrarInscripcionRudeVacio(id_inscripcion_rude) {
					//document.form_datos_rude.submit()
					$.ajax({
						url: '?/s-inscripciones/procesos',
						type: 'POST',
						data: {
							'boton': 'registrar_inscripcion_estudiante_rude',
							'id_estudiante': id_estudiante,
							'id_inscripcion_rude': id_inscripcion_rude
						},
						dataType: 'JSON',
						success: function(resp) {
							console.log("registro rude");
							console.log(resp);
							switch (resp['estado']) {
								case 1:
									id_inscripcion_rude = resp['id_inscripcion_rude'];
									alertify.success('Se realizo correctamente la inscripcion...');
									console.log("id_inscripcion_rude");
									console.log(id_inscripcion_rude);
									//ponemos una etiqueta de id_inscripcion rude
									$("#id_inscripcion_rude").val(id_inscripcion_rude);
									$('#complemento_estudiante-tab').tab('show');
									break;
								case 2:
									$("#id_estudiante").val(resp['id_estudiante']);
									alertify.success('Se edito correctamente rude...');
									$('#complemento_estudiante-tab').tab('show');
									break;
								case 3: //dataTable.ajax.reload();
									alertify.error('No se pudo registrar al estudiante ...');
									break;
							}
						}
					});
				}

				function eliminar_familiar(contenido) {
					$("#modal_eliminar").modal("show");
					var d = contenido.split("*");
					//console.log(d);
					$("#familiar_eliminar").val(d[0]);
					var nombre = d[3] + " " + d[4] + " " + d[5];
					$("#texto_familiar").text(nombre);
				}

				function correo_prueba() {
					$.ajax({
						url: '?/s-estudiantes/procesos',
						type: 'POST',
						data: {
							'boton': 'correo_prueba'
						},
						dataType: 'JSON',
						success: function(resp) {
							console.log(resp);
							/*if(resp > 0){
                //$("#vacantes").text(resp);
				$("#vacantes").val(resp);
                //$("#vacantes").html('<option value="'+resp+'">'+ resp+'</option>');
                $("#btn_inscripcion").show();
            }else{
                $("#btn_inscripcion").hide();
                alertify.error('No hay vacantes en este curso y paralelo');
            }*/
						}
					});
				}

				$("#btn_eliminar").on('click', function() {
					//alert($("#gestion_eliminar").val())
					var id_estudiante = $("#id_familiares").val();
					var id_estudiante_familiar = $("#familiar_eliminar").val();
					$.ajax({
						url: '?/s-estudiantes/procesos',
						type: 'POST',
						data: {
							'id_estudiante_familiar': id_estudiante_familiar,
							'boton': 'eliminar_familiar'
						},
						success: function(resp) {
							console.log(resp);
							switch (resp) {
								case '1':
									$("#modal_eliminar").modal("hide");
									listar_familiares(id_estudiante);
									alertify.success('Se retiro al familiar correctamente');
									break;
								case '2':
									$("#modal_eliminar").modal("hide");
									alertify.error('No se pudo eliminar');
									break;
							}
						}
					})
				})

				function atrasSubirDoc() {
					$('#documentos-tab').tab('show');
				}

				function volverInscribir() {
					//Cereamos todos los formularios
					console.log("volver a inscribir familiar");

					$("#id_estudiante").val("");
					$("#id_persona").val("");
					$("#nombres").val("");
					$("#tipo_documento").val("");
					$("#numero_documento").val("");
					$("#expedido").val("");
					$("#complemento").val("");
					$("#fecha_nacimiento").val("");
					$("#no_reserva").attr('checked', 'checked');
					$("#monto_reserva").val("");
					$("#fecha_reserva").val("");
					$("#btn_guardar_estudiante").show();
					$("#btn_reservar_guardar").hide();
					$("#panel_reserva").hide();

					//Inscripcion
					$('#tipo_estudiante').prop('disabled', false);
							$('#turno').prop('disabled', false);
							$('#nivel_academico').prop('disabled', false);
							$('#select_curso').prop('disabled', false);

					$("#form_datos_certificado")[0].reset();
					$("#form_datos_rude")[0].reset();
					$("#form_vacunas")[0].reset();
					$("#form_inscripcion")[0].reset();
					$("#form_recep_documentos")[0].reset();
					id_estudiante = 0;
					id_inscripcion_rude = 0;
					id_tipo_estudiante = 0;
					id_nivel_academico = 0;
					//Variables para la parte de inscripcion
					id_aula_paralelo_A = 0;
					estado_reserva = 0;
					fecha_limite_reserva = "";
					monto_reserva = 0;
					$('#inscripcion-tab').tab('show');

				}

				function refrescarPagina() {
					location.reload();
				}

				function atrasActualizacion() {
					$('#complemento_estudiante-tab').tab('show');
				}

				function atrasRude() {
					$('#rude-tab').tab('show');
				}

				function atrasVacunas() {
					$('#vacunas-tab').tab('show');
				}

				function atrasInscripcion() {
					$('#inscripcion-tab').tab('show');
				}

				function limpiarFormulario() {
					document.getElementById("form_familiar").reset();
				}

				//llenamos los select con datos de la base de datos
				function listar_paises() {
					nivel = $("#nivel_academico option:selected").val()
					//alert(nivel);
					$.ajax({
						url: '?/s-inscripciones/procesos',
						type: 'POST',
						data: {
							'boton': 'listar_cursos',
							'nivel': nivel
						},
						dataType: 'JSON',
						success: function(resp) {
							//alert(resp[0]['id_catalogo_detalle']);
							//console.log(resp);
							$("#select_curso").html("");
							$("#select_curso").append('<option value="' + 0 + '">Seleccionar</option>');
							for (var i = 0; i < resp.length; i++) {
								$("#select_curso").append('<option value="' + resp[i]["id_aula"] + '">' + resp[i]["nombre_aula"] + '</option>');
							}
							//console.log(resp[0]);
						}
					});
				}

				function listar_departamentos() {
					nivel = $("#nivel_academico option:selected").val()
					//alert(nivel);
					$.ajax({
						url: '?/s-inscripciones/procesos',
						type: 'POST',
						data: {
							'boton': 'listar_cursos',
							'nivel': nivel
						},
						dataType: 'JSON',
						success: function(resp) {
							//alert(resp[0]['id_catalogo_detalle']);
							//console.log(resp);
							$("#select_curso").html("");
							$("#select_curso").append('<option value="' + 0 + '">Seleccionar</option>');
							for (var i = 0; i < resp.length; i++) {
								$("#select_curso").append('<option value="' + resp[i]["id_aula"] + '">' + resp[i]["nombre_aula"] + '</option>');
							}
							//console.log(resp[0]);
						}
					});
				}

				function reporteRude() {
					var id_estudiante = $('#id_estudiante').val();
					var id_inscripcion_rude = $('#id_inscripcion_rude').val();
					var id_inscripcion = $('#id_inscripcion').val();
					//var id_inscripcion = $('#id_inscripcion').val();
					/*var id_estudiante = 454;
					var id_inscripcion_rude = 4;
					var id_inscripcion = 454;*/
					enviarRudePost('?/s-inscripciones/procesos', cadena_rude(id_estudiante, id_inscripcion_rude, id_inscripcion));
				}

				//arma un array con todos los paraletros que se van a enviar al reporte bimestral
				function cadena_rude(id_estudiante, id_inscripcion_rude, id_inscripcion) {
					var parametros = {
						'id_estudiante': id_estudiante,
						'id_inscripcion_rude': id_inscripcion_rude,
						'id_inscripcion': id_inscripcion,
						'boton': 'reporte_rude'
					}
					return parametros;
				}

				//envia los datos como si fuera un formulario con el metodo POST
				function enviarRudePost(url, datos) {
					var form = '<form action="' + url + '" method="POST" target="_blank">';
					$.each(datos, function(key, value) {
						form += '<input type="hidden" name="' + key + '" value="' + value + '">';
					});
					form += '</form>';
					var formElment = $(form);
					$(document.body).append(formElment);
					formElment.submit();
				}

				listar_paises();

				function listar_paises() {
					nivel = 0; //$("#nivel_academico option:selected").val()
					$.ajax({
						url: '?/s-inscripciones/procesos',
						type: 'POST',
						data: {
							'boton': 'listar_paices',
							'nivel': nivel
						},
						dataType: 'JSON',
						success: function(resp) {
							console.log('Listar paices' + resp);
							//alert(resp[0]['id_catalogo_detalle']);
							//console.log(resp);
							$("#pais").html("");
							$("#pais").append('<option value="' + 0 + '">Seleccionar</option>');
							for (var i = 0; i < resp.length; i++) {
								$("#pais").append('<option value="' + resp[i]["id_pais"] + '">' + resp[i]["nombre"] + '</option>');
							}
							//console.log(resp[0]);
						}
					});
				}

				function listar_departamentos() {
					nivel = 0; //$("#nivel_academico option:selected").val();
					var idpais = $("#pais option:selected").val();
					// alert('departmento, pais:'+idpais);
					$.ajax({
						url: '?/s-inscripciones/procesos',
						type: 'POST',
						data: {
							'boton': 'listar_departamentos',
							'idpais': idpais,
							'nivel': nivel
						},
						dataType: 'JSON',
						success: function(resp) {
							//alert(resp);
							//alert(resp[0]['id_catalogo_detalle']);
							//console.log(resp);
							$("#departamento").html("");
							$("#departamento").append('<option value="' + 0 + '">Seleccionar</option>');
							for (var i = 0; i < resp.length; i++) {
								$("#departamento").append('<option value="' + resp[i]["id_departamento"] + '">' + resp[i]["nombre"] + '</option>');
							}
							//console.log(resp[0]);
						}
					});
				}

				function listar_provincias() {
					//alert('ejemplo');
					nivel = 0; //$("#nivel_academico option:selected").val();
					var iddepartamento = $("#departamento option:selected").val();
					// alert('departmento, pais:'+idpais);
					$.ajax({
						url: '?/s-inscripciones/procesos',
						type: 'POST',
						data: {
							'boton': 'listar_provincias',
							'idpais': iddepartamento,
							'nivel': nivel
						},
						dataType: 'JSON',
						success: function(resp) {
							//alert(resp);
							//alert(resp[0]['id_catalogo_detalle']);
							//console.log(resp);
							$("#provincia").html("");
							$("#provincia").append('<option value="' + 0 + '">Seleccionar</option>');
							for (var i = 0; i < resp.length; i++) {
								$("#provincia").append('<option value="' + resp[i]["id_provincia"] + '">' + resp[i]["nombre"] + '</option>');
							}
							//console.log(resp[0]);
						}
					});
				}

				function listar_provincias_rude() {
					//alert('ejemplo');
					nivel = 0; //$("#nivel_academico option:selected").val();
					var iddepartamento = $("#departamento_rude option:selected").val();
					// alert('departmento, pais:'+idpais);
					$.ajax({
						url: '?/s-inscripciones/procesos',
						type: 'POST',
						data: {
							'boton': 'listar_provincias',
							'idpais': iddepartamento,
							'nivel': nivel
						},
						dataType: 'JSON',
						success: function(resp) {
							//alert(resp);
							//alert(resp[0]['id_catalogo_detalle']);
							//console.log(resp);
							$("#provinciar_rude").html("");
							$("#provinciar_rude").append('<option value="' + 0 + '">Seleccionar</option>');
							for (var i = 0; i < resp.length; i++) {
								$("#provinciar_rude").append('<option value="' + resp[i]["id_provincia"] + '">' + resp[i]["nombre"] + '</option>');
							}
							//console.log(resp[0]);
						}
					});
				}

				function listar_localidades() {
					//alert('ejemplo');
					nivel = 0; //$("#nivel_academico option:selected").val();
					var idprovincia = $("#provincia option:selected").val();
					// alert('departmento, pais:'+idpais);
					$.ajax({
						url: '?/s-inscripciones/procesos',
						type: 'POST',
						data: {
							'boton': 'listar_localidades',
							'idpais': idprovincia,
							'nivel': nivel
						},
						dataType: 'JSON',
						success: function(resp) {
							//alert(resp);
							//alert(resp[0]['id_catalogo_detalle']);
							//console.log(resp);
							$("#localidad").html("");
							$("#localidad").append('<option value="' + 0 + '">Seleccionar</option>');
							for (var i = 0; i < resp.length; i++) {
								$("#localidad").append('<option value="' + resp[i]["id_localidad"] + '">' + resp[i]["nombre"] + '</option>');
							}
							//console.log(resp[0]);
						}
					});
				}

				// Funcion que ejecuta la reseva de un cupo
				function reservar_cupo_imprimir_comprobante(id_estudiante, id_inscripcion, monto_reserva) {
					console.log(id_estudiante + '***' + id_inscripcion + '***' + monto_reserva);
					// var datos = datos + '&boton=' + 'reservar_cupo_imprimir_comprobante';
					// datos = datos + '&id_estudiante=' + 'id_estudiante';
					// datos = datos + '&id_inscripcion=' + 'id_inscripcion';
					// datos = datos + '&monto_reserva=' + 'monto_reserva';
					$.ajax({
						type: 'POST',
						url: "?/s-inscripciones/procesos",
						data: {
							'boton': 'reservar_cupo_imprimir_comprobante',
							'id_estudiante': id_estudiante,
							'id_inscripcion': id_inscripcion,
							'monto_reserva': monto_reserva
						},
						dataType: 'JSON',
						success: function(resp) {
							console.log(resp);
							imprimir_recibo(resp);
							// var pag=$.trim(resp);
							// switch (resp) {
							// 	case 1:
							// 	    imprimir_recibo(pag);
							// 		alertify.success('Se edito correctamente rude...');
							// 		break;
							// 	case 2: //dataTable.ajax.reload();
							// 		alertify.error('No se pudo registrar al estudiante ...');
							// 		break;
							// }
						}
					});
				}

				function imprimir_recibo(resp) {
					console.log('holofffffffffffffffg');
					window.open('?/s-pagos-adelantos/imprimir-recibo/' + resp, true);
				}

				function imprimir_reglamento(resp) {
					console.log('holofffffffffffffffg');
					window.open('?/s-inscripciones/imprimir-reglamento/' + resp, true);
				}
				// Fin de Funcion que ejecuta la reseva de un cupo
				//window.onload = cargar_tipo_documento();
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

				var $f_expedido = $('#f_expedido');
				$f_expedido.selectize({
					persist: false,
					createOnBlur: true,
					create: true,
					onInitialize: function() {
						$f_expedido.css({
							display: 'block',
							left: '-10000px',
							opacity: '0',
							position: 'absolute',
							top: '-10000px'
						});
					}
				});

				var $f_grado_instruccion = $('#f_grado_instruccion');
				$f_grado_instruccion.selectize({
					persist: false,
					createOnBlur: true,
					create: true,
					onInitialize: function() {
						$f_grado_instruccion.css({
							display: 'block',
							left: '-10000px',
							opacity: '0',
							position: 'absolute',
							top: '-10000px'
						});
					}
				});

				var $f_idioma_frecuente = $('#f_idioma_frecuente');
				$f_idioma_frecuente.selectize({
					persist: false,
					createOnBlur: true,
					create: true,
					onInitialize: function() {
						$f_idioma_frecuente.css({
							display: 'block',
							left: '-10000px',
							opacity: '0',
							position: 'absolute',
							top: '-10000px'
						});
					}
				});

				var $f_parentesco = $('#f_parentesco');
				$f_parentesco.selectize({
					persist: false,
					createOnBlur: true,
					create: true,
					onInitialize: function() {
						$f_parentesco.css({
							display: 'block',
							left: '-10000px',
							opacity: '0',
							position: 'absolute',
							top: '-10000px'
						});
					}
				});

				var $f_profesion = $('#f_profesion');
				$f_profesion.selectize({
					persist: false,
					createOnBlur: true,
					create: true,
					onInitialize: function() {
						$f_profesion.css({
							display: 'block',
							left: '-10000px',
							opacity: '0',
							position: 'absolute',
							top: '-10000px'
						});
					}
				});

				//Rude Parte I , II
				var $pais = $('#pais');
				$pais.selectize({
					persist: false,
					createOnBlur: true,
					create: true,
					onInitialize: function() {
						$f_profesion.css({
							display: 'block',
							left: '-10000px',
							opacity: '0',
							position: 'absolute',
							top: '-10000px'
						});
					}
				});

				var $departamento = $('#departamento');
				$departamento.selectize({
					persist: false,
					createOnBlur: true,
					create: true,
					onInitialize: function() {
						$f_profesion.css({
							display: 'block',
							left: '-10000px',
							opacity: '0',
							position: 'absolute',
							top: '-10000px'
						});
					}
				});

				var $provincia = $('#provincia');
				$provincia.selectize({
					persist: false,
					createOnBlur: true,
					create: true,
					onInitialize: function() {
						$f_profesion.css({
							display: 'block',
							left: '-10000px',
							opacity: '0',
							position: 'absolute',
							top: '-10000px'
						});
					}
				});

				var $localidad = $('#localidad');
				$localidad.selectize({
					persist: false,
					createOnBlur: true,
					create: true,
					onInitialize: function() {
						$f_profesion.css({
							display: 'block',
							left: '-10000px',
							opacity: '0',
							position: 'absolute',
							top: '-10000px'
						});
					}
				});


				var $411 = $('#a');
				$411.selectize({
					persist: false,
					createOnBlur: true,
					create: true,
					onInitialize: function() {
						$f_profesion.css({
							display: 'block',
							left: '-10000px',
							opacity: '0',
							position: 'absolute',
							top: '-10000px'
						});
					}
				});

				var $412 = $('#b');
				$412.selectize({
					persist: false,
					createOnBlur: true,
					create: true,
					onInitialize: function() {
						$f_profesion.css({
							display: 'block',
							left: '-10000px',
							opacity: '0',
							position: 'absolute',
							top: '-10000px'
						});
					}
				});

				var $o = $('#o');
				$o.selectize({
					persist: false,
					createOnBlur: true,
					create: true,
					onInitialize: function() {
						$f_profesion.css({
							display: 'block',
							left: '-10000px',
							opacity: '0',
							position: 'absolute',
							top: '-10000px'
						});
					}
				});

				var $461 = $('#461');
				$461.selectize({
					persist: false,
					createOnBlur: true,
					create: true,
					onInitialize: function() {
						$f_profesion.css({
							display: 'block',
							left: '-10000px',
							opacity: '0',
							position: 'absolute',
							top: '-10000px'
						});
					}
				});
			</script>