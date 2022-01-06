<?php

//obtiene el valor L
$id_estudiante = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene nicel académico
$nivel = $db->select('z.*')->from('ins_nivel_academico z')->order_by('id_nivel_academico')->fetch();

// Obtiene nicel académico
$tipo_estudiante = $db->select('z.*')->from('ins_tipo_estudiante z')->order_by('id_tipo_estudiante')->fetch();

$pais = $db->select('z.*')->from('sys_paises z')->order_by('id_pais')->fetch();
$departamento = $db->select('z.*')->from('sys_departamentos z')->order_by('id_departamento')->fetch();
$provincia = $db->select('z.*')->from('sys_provincias z')->order_by('id_provincia')->fetch();
$localidad = $db->select('z.*')->from('sys_localidades z')->order_by('id_localidad')->fetch();

// Obtiene datos del familiar
$tutores = $db->query("select p.id_persona, p.nombres, p.primer_apellido, p.segundo_apellido, p.numero_documento, f.id_familiar FROM sys_persona p INNER JOIN ins_familiar f ON  (p.id_persona=f.persona_id) ORDER BY p.nombres")->fetch();

// Obtiene datos de los pagos
$pagos = $db->query("SELECT * FROM pen_pensiones p ORDER BY p.nombre_pension")->fetch();

// Obtiene la cadena csrf
$csrf = set_csrf(); 

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);
//$permiso_crear_familiar = in_array('crear-familiar', $_views);

?>
<!doctype html>
<html lang="en">
 
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Inscripción</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="assets/themes/concept/assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/themes/concept/assets/libs/css/style.css">
    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">

<!--link rel="stylesheet" href="assets/themes/concept/assets/vendor/cropper/dist/cropper.min.css"-->
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/cropper-mazter/css/cropper.css">
<link href="assets/themes/concept/assets/vendor/datepicker/css/datepicker.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/bootstrap-fileinput-master/css/fileinput.css" rel="stylesheet">
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">
</head>
<body>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper p-0">
        <!-- ============================================================== -->
        <!-- navbar -->
        <!-- ============================================================== -->
        <nav class="navbar navbar-expand dashboard-top-header bg-white">
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- brand logo -->
                <!-- ============================================================== -->
                <div class="dashboard-nav-brand">
                    <a class="dashboard-logo" href="?/s-inscripciones/listar">Listado</a>
                </div>
                <!-- ============================================================== -->
                <!-- end brand logo -->
                <!-- ============================================================== -->
            </div>
        </nav>
        <!-- ============================================================== -->
        <!-- end navbar -->
        <!-- ============================================================== -->

<div class="container-fluid">
            <!-- <div class="dashboard-ecommerce"> -->
                <!-- <div class="container-fluid dashboard-content "> -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="page-header">
			<h2 class="pageheader-title" data-idtutor="">Inscripción</h2>
			<p class="pageheader-text"></p>
			<div class="page-breadcrumb">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Estudiantes</a></li>
						<li class="breadcrumb-item active" aria-current="page">Crear Estudiantes</li>
					</ol>
				</nav>
			</div>
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
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="alert alert-success" role="alert">
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
										<div class="list-group" id="resultt">
											<img src="assets/imgs/avatar.jpg" id="avatar_pm" name="avatar_pm" class="" style="width:auto; height:300px;">
										</div>
										<div class="list-group">
											<label class="list-group-item text-ellipsis">
												Subir Imagen
												<input type="file" class="sr-only" id="input_pm" name="image_pm" accept="image/*">
											</label>
											<a href="#" class="list-group-item text-ellipsis" data-suprimir="true">
												<span class="glyphicon glyphicon-eye-close"></span>
												<span>Eliminar imagen</span>
											</a>
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
							<div class="">
								<label class="control-label">Nombres: </label>
								<div class="controls control-group">
									<input type="hidden" name="<?= $csrf; ?>">
									<input type="hidden" class="form-control" id="f_id_familiar" name="f_id_familiar">
									<input type="hidden" class="form-control" id="f_id_persona" name="f_id_persona">
									<input id="f_nombres" name="f_nombres" type="text" class="form-control">
								</div>
							</div>
							<div class="" style="margin-top:2%">
								<label class="control-label">Primer Apellido: </label>
								<div class="controls control-group">
									<input id="f_primer_apellido" name="f_primer_apellido" type="text" class="form-control">
								</div>
							</div>
							<div class="" style="margin-top:2%">
								<label class="control-label">Segundo Apellido: </label>
								<div class="controls control-group">
									<input id="f_segundo_apellido" name="f_segundo_apellido" type="text" class="form-control">
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
							<div class="">
								<label class="control-label">Género: </label>
								<div class="controls">
									<label class="custom-control custom-radio custom-control-inline">
										<input type="radio" value="1" id="f_genero" name="f_genero" checked="" class="custom-control-input"><span class="custom-control-label">Varón</span>
									</label>
									<label class="custom-control custom-radio custom-control-inline" id="f_genero" name="f_genero">
										<input type="radio" value="2" id="f_genero" name="f_genero" class="custom-control-input"><span class="custom-control-label">Mujer</span>
									</label>
								</div>
							</div>
							<div class="" style="margin-top:2%">
								<label class="control-label">Fecha de Nacimiento: </label>
								<div class="controls control-group">
									<!-- <input id="f_fecha_nacimiento" name="f_fecha_nacimiento" type="date" class="form-control"> -->
									<input type='text' class='datepicker-here form-control' id="f_fecha_nacimiento_tutor" name="f_fecha_nacimiento_tutor" readOnly />
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
									<input id="f_profesion" name="f_profesion" type="text" class="form-control">
								</div>
							</div>
							<div class="" style="margin-top:2%">
								<label class="control-label">Dirección de Oficina: </label>
								<div class="controls control-group">
									<input id="f_direccion_oficina" name="f_direccion_oficina" type="text" class="form-control">
								</div>
								<br>
								<div class="">
									<label class="control-label">Es el tutor responsable: </label>
									<div class="controls">
										<label class="custom-control custom-radio custom-control-inline">
											<input type="radio" value="0" id="f_tutor" name="f_tutor" checked="" class="custom-control-input"><span class="custom-control-label">No</span>
										</label>
										<label class="custom-control custom-radio custom-control-inline" id="f_tutor" name="f_tutor">
											<input type="radio" value="1" id="f_tutor" name="f_tutor" class="custom-control-input"><span class="custom-control-label">Si</span>
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<br>
					<div class="" style="margin-top:2%" id="div_parentesco">
						<label class="control-label">Cual es el parentesco con el estudiante? : </label>
						<div class="controls control-group">
							<input id="f_parentesco" name="f_parentesco" type="text" class="form-control">
						</div>
					</div>
					<br>
					<div align="right">
						<button type="button" class="btn btn-success">Limpiar</button>
						<button type="submit" class="btn btn-primary" id="btn_agregar_familiar">Guardar</button>
					</div>
				</form>
				<br>
				<div class="row">
					
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="alert alert-success" role="alert">
							<b id="lista_familia"></b>
						</div>
					</div>
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
					<a class="nav-link active" id="personales-tab" data-toggle="tab" href="#personales" role="tab" aria-controls="personales" aria-selected="true">Datos Estudiante</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="familiar-tab" data-toggle="tab" href="#familiar" role="tab" aria-controls="familiares" aria-selected="false">Actualizacion de datos</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="rude-tab" data-toggle="tab" href="#rude" role="tab" aria-controls="rude" aria-selected="false">Rude</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="vacunas-tab" data-toggle="tab" href="#vacunas" role="tab" aria-controls="vacunas" aria-selected="false">Vacunas</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="inscripcion-tab" data-toggle="tab" href="#inscripcion" role="tab" aria-controls="inscripcion" aria-selected="false">Inscripción</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="rep_documentos-tab" data-toggle="tab" href="#rep_documentos" role="tab" aria-controls="inscripcion" aria-selected="false">Recepcion de documentos</a>
				</li>

				<li class="nav-item">
					<a class="nav-link" id="pagos-tab" data-toggle="tab" href="#pagos" role="tab" aria-controls="pagos" aria-selected="false">Pagos</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="documentos-tab" data-toggle="tab" href="#documentos" role="tab" aria-controls="documentos" aria-selected="false">Documentos</a>
				</li>
			</ul>
			<div class="tab-content" id="myTabContent2">
				<div class="tab-pane fade show active" id="personales" role="tabpanel" aria-labelledby="personales-tab">

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
														<a href="#" class="list-group-item text-ellipsis" data-suprimir="true">
															<span class="glyphicon glyphicon-eye-close"></span>
															<span>Eliminar imagen</span>
														</a>
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
												<input id="nombres" name="nombres" type="text" class="form-control">
												<input type="hidden" id="nombre_imagen" name="nombre_imagen">
											</div>
											<!--input type="file" id="imagen_cortada" name="imagen_cortada" -->
										</div>
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
											<label class="control-label">Primer Apellido: </label>
											<div class="controls control-group">
												<input id="primer_apellido" name="primer_apellido" type="text" class="form-control">
											</div>
										</div>
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
											<label class="control-label">Segundo Apellido: </label>
											<div class="controls control-group">
												<input id="segundo_apellido" name="segundo_apellido" type="text" class="form-control">
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
												<input id="numero_documento" name="numero_documento" type="text" class="form-control">
											</div>
										</div>
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
											<label class="control-label">Complemento: </label>
											<div class="controls control-group">
												<input id="complemento" name="complemento" type="text" class="form-control">
											</div>
										</div>
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
									</div>

									<div class="col col-xl-4 col-lg-4 col-md-12 col-sm-6 col-xs-12">

										<h5 class="section-title">2.4. LUGAR DE NACIMIENTO</h5>
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
											<label for="title" class="control-label">Pais: </label>
											<div class="control-group">
												<select name="pais" id="pais" onchange="listar_vacantes();" class="form-control">
													<option value="" selected="selected">Seleccionar</option>
													<?php foreach ($pais as $value) : ?>
														<option value="<?= $value['id_pais']; ?>"><?= escape($value['nombre']); ?></option>
													<?php endforeach ?>
												</select>
											</div>

										</div>
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
											<label for="title" class="control-label">Departamento: </label>
											<div class="controls control-group">
												<select name="departamento" id="departamento" onchange="listar_vacantes();" class="form-control">
													<option value="" selected="selected">Seleccionar</option>
													<?php foreach ($departamento as $value) : ?>
														<option value="<?= $value['id_departamento']; ?>"><?= escape($value['nombre']); ?></option>
													<?php endforeach ?>
												</select>
											</div>
										</div>
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
											<label for="title" class="control-label">Provincia: </label>
											<div class="controls control-group">
												<select name="provincia" id="provincia" onchange="listar_vacantes();" class="form-control">
													<option value="" selected="selected">Seleccionar</option>
													<?php foreach ($provincia as $value) : ?>
														<option value="<?= $value['id_provincia']; ?>"><?= escape($value['nombre']); ?></option>
													<?php endforeach ?>
												</select>
											</div>
										</div>
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
											<label for="title" class="control-label">Localidad: </label>
											<div class="controls control-group">
												<select name="localidad" id="localidad" onchange="listar_vacantes();" class="form-control">
													<option value="" selected="selected">Seleccionar</option>
													<?php foreach ($localidad as $value) : ?>
														<option value="<?= $value['id_localidad']; ?>"><?= escape($value['nombre']); ?></option>
													<?php endforeach ?>
												</select>
											</div>
										</div>
										<h5 class="section-title">2.5. FECHA DE NACIMIENTO</h5>
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
											<label for="title" class="control-label">Fecha de Nacimiento: </label>
											<div class="controls control-group">
												<input type='text' class='datepicker-here form-control' id="fecha_nacimiento" name="fecha_nacimiento" readOnly />
											</div>
										</div>
										<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
											<!--label class="control-label">Dirección: </label-->
											<div class="controls">
												<input id="direccion" name="direccion" type="HIDDEN" class="form-control">
											</div>
										</div>
									</div>

								</div><br><br>
								<div align="right">
									<!--button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
									<button type="submit" class="btn btn-primary pull-right" id="btn_nuevo">Registrar</button>
									<button type="submit" class="btn btn-primary pull-right" id="btn_editar">Editar</button-->
									<button type="submit" class="btn btn-success"><span class="fa fa-arrow-right"> Siguiente</span></button>
								</div>
							</form>
						</div>
						<!-- fin formulario hijo -->
					</div>
					<!-- fin row -->
				</div>
				<!-- fin tab padre estudiante -->

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

				<div class="tab-pane fade" id="familiar" role="tabpanel" aria-labelledby="familiar-tab">
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
															<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																<h5 class="section-title">2.4. CODIGO RUDE</h5>
																<div class="">
																	<div class="controls control-group">
																		<input type="hidden" name="<?= $csrf; ?>">
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
																		<option value="1">Psiquica</option>
																		<option value="2">Autismo</option>
																		<option value="3">Sindrome de Down</option>
																		<option value="4">Intelectual</option>
																		<option value="5">Auditiva</option>
																		<option value="6">Fisica-Motora</option>
																		<option value="7">Sordoceguera</option>
																		<option value="8">Multiple</option>
																		<option value="9">Visual</option>
																	</select>
																</div>
															</div>

															<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																<label class="control-label">Grado de Discapacidad: </label>
																<div class="control-group">
																	<select name="grado_discapacidad" id="grado_discapacidad" onchange="listar_vacantes();" class="form-control">
																		<option value="" selected="selected">Seleccionar</option>
																		<option value="1">Leve</option>
																		<option value="2">Moderado</option>
																		<option value="3">Grave</option>
																		<option value="4">Muy Grave</option>
																		<option value="5">Ceguera total</option>
																		<option value="6">Baja visíon</option>
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
																		<select id="departamento" name="departamento" class="form-control">
																			<option value="" selected="selected">Seleccionar</option>
																			<?php foreach ($departamento as $value) : ?>
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
																		<select id="provincia" name="provincia" class="form-control">
																			<option value="" selected="selected">Seleccionar</option>
																			<?php foreach ($provincia as $value) : ?>
																				<option value="<?= $value['id_provincia']; ?>"><?= escape($value['nombre']); ?></option>
																			<?php endforeach ?>
																		</select>
																	</div>
																</div>
															</div>
															<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
																<div class="" style="margin-top:2%">
																	<label class="control-label">Seccion/Municipio: </label>
																	<div class="controls control-group">
																		<input id="seccion" name="seccion" type="text" class="form-control">
																	</div>
																</div>
															</div>
															<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
																<div class="" style="margin-top:2%">
																	<label class="control-label">Localidad/Comunidad: </label>
																	<div class="controls control-group">
																		<input id="localidad" name="localidad" type="text" class="form-control">
																	</div>
																</div>
															</div>
														</div>

														<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
															<div class="" style="margin-top:2%">
																<label class="control-label">Zona/Villa: </label>
																<div class="controls control-group">
																	<input id="zona" name="zona" type="text" class="form-control">
																</div>
															</div>
															<div class="" style="margin-top:2%">
																<label class="control-label">Avenida/Calle: </label>
																<div class="controls control-group">
																	<input id="avenida" name="avenida" type="text" class="form-control">
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
													<button type="submit" class="btn btn-primary" id="btn_agregar_familiar">Siguiente</button>
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

				<div class="tab-pane fade" id="rep_documentos" role="tabpanel" aria-labelledby="rep-documentos-tab">
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
									<div class="media influencer-profile-data d-flex align-items-center p-2">
										<input type="hidden" id="id_familiares" name="id_familiares">
										<div class="media-body">
											<div class="influencer-profile-data">

												<div class="row">
													<form id="form_recep_documentos">
														<div class="form-row">
															<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																<h5 class="section-title">RECEPCION DE DOCUMENTOS</h5>
															</div>

															<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																<br>
																<h5 class="section-title">Certificado de nacimiento</h5>
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
																<br>
																<h5 class="section-title">Fotocopia de Carnet</h5>
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
																<br>
																<h5 class="section-title">Carnet de familia</h5>
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
														</div>

														<br>
														<div align="right">
															<button type="submit" class="btn btn-primary" id="btn_agregar_familiar" onclick="atrasSubirDoc()">Subir Documentos</button>
															<button type="button" class="btn btn-success" onclick="volverInscribir()">Volver a inscribir</button>
															<button type="submit" class="btn btn-primary" id="btn_recep_documentos">Guardar</button>
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

				<div class="tab-pane fade" id="documentos" role="tabpanel" aria-labelledby="documentos-tab">
					<input type="hidden" id="id_documentos" name="id_documentos">
					<input id="input-ru" name="img_documentos[]" type="file" data-browse-on-zone-click="true" multiple>
					<!--div class="">
						<button class="btn btn-success pull-right"><span class="fa fa-arrow-left"> Atras</span></button>
						<button type="submit" class="btn btn-success pull-right"><span class="fa fa-arrow-right"> Siguiente</span></button>
					</div-->
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
															<form id="form_datos_rude">
																<div class="form-row">

																	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																		<div class="">
																			<br>
																			<label class="control-label">4.1.1. ¿Cual es el idioma con el cual aprendio a hablar en su niñez?: </label>
																			<div class="controls control-group">
																				<input type="hidden" name="<?= $csrf; ?>">
																				<input id="a" name="a" type="text" class="form-control">
																			</div>
																		</div>

																		<div class="">
																			<label class="control-label">4.1.2. ¿Que idioma(s) habla frecuentemente? (añada todas las necesarias): </label>
																			<div class="controls control-group">
																				<input id="b" name="b" type="text" class="form-control">
																			</div>
																		</div>

																		<div class="">
																			<label class="control-label">4.1.3. ¿Pertenece a una nacion, pueblo indigena originaria campesino o afroboliviano?: </label>
																			<div class="controls control-group">
																				<input type="hidden" name="<?= $csrf; ?>">
																				<input id="c" name="c" type="text" class="form-control">
																			</div>
																		</div>
																		<br>
																		<h5 class="section-title">4.2. SALUD DE LA O EL ESTUDIANTE.</h5>
																		<br>
																		<div>
																			<br>
																			<label class="section-title">4.2.1. ¿Existe algun centro de Salud/Posta/Hospital en su comunidad/barrio/zona?</label>
																			<div class="form-group row">
																				<label class="col-12 col-sm-6 col-form-label text-sm-right"></label>
																				<div class="col-12 col-sm-6 col-lg-6 pt-1">
																					<div class="switch-button">
																						<input type="checkbox" name="d" id="d"><span>
																							<label for="d"></label></span>
																					</div>
																				</div>
																			</div>
																		</div>

																		<label class="section-title">4.2.2. ¿El año pasado por problemas de salud, acudio o se atendio en...?</label>
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
																			<label class="custom-control-label" for="4226">6. La farmacia sin receta medica(automedicacion)</label>
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
																			</select>
																		</div>
																	</div>

																	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">

																	</div>

																	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																		<br>
																		<label class="section-title">4.2.4. ¿Tiene seguro de salud?</label>
																		<div>
																			<div class="form-group row">
																				<label class="col-12 col-sm-6 col-form-label text-sm-right"></label>
																				<div class="col-12 col-sm-6 col-lg-6 pt-1">
																					<div class="switch-button">
																						<input type="checkbox" name="f" id="f"><span>
																							<label for="f"></label></span>
																					</div>
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

																			<div class="form-group row">
																				<label class="col-12 col-sm-6 col-form-label text-sm-right"></label>
																				<div class="col-12 col-sm-6 col-lg-6 pt-1">
																					<div class="switch-button">
																						<input type="checkbox" name="g" id="g"><span>
																							<label for="g"></label></span>
																					</div>
																				</div>
																			</div>
																		</div>

																		<label class="section-title">4.3.2. ¿Tiene baño en su vivienda?</label>
																		<div>

																			<div class="form-group row">
																				<label class="col-12 col-sm-6 col-form-label text-sm-right"></label>
																				<div class="col-12 col-sm-6 col-lg-6 pt-1">
																					<div class="switch-button">
																						<input type="checkbox" name="h" id="h"><span>
																							<label for="h"></label></span>
																					</div>
																				</div>
																			</div>
																		</div>

																		<label class="section-title">4.3.3. ¿Tiene red de alcantarillado?</label>
																		<div>

																			<div class="form-group row">
																				<label class="col-12 col-sm-6 col-form-label text-sm-right"></label>
																				<div class="col-12 col-sm-6 col-lg-6 pt-1">
																					<div class="switch-button">
																						<input type="checkbox" name="i" id="i"><span>
																							<label for="i"></label></span>
																					</div>
																				</div>
																			</div>
																		</div>

																		<label class="section-title">4.3.4. ¿Usa energia electrica para alumbrar su vivienda?</label>
																		<div>

																			<div class="form-group row">
																				<label class="col-12 col-sm-6 col-form-label text-sm-right"></label>
																				<div class="col-12 col-sm-6 col-lg-6 pt-1">
																					<div class="switch-button">
																						<input type="checkbox" name="j" id="j"><span>
																							<label for="j"></label></span>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>

																	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																		<br>
																		<label class="section-title">4.3.5.¿Cuenta con servicio de recojo de basura?</label>
																		<div>

																			<div class="form-group row">
																				<label class="col-12 col-sm-6 col-form-label text-sm-right"></label>
																				<div class="col-12 col-sm-6 col-lg-6 pt-1">
																					<div class="switch-button">
																						<input type="checkbox" name="k" id="k"><span>
																							<label for="k"></label></span>
																					</div>
																				</div>
																			</div>
																		</div>

																		<label class="section-title">4.3.6. La vivienda que ocupa el hogar es :</label>
																		<div class="">
																			<div class="controls">
																				<label class="custom-control custom-radio custom-control-inline">
																					<input type="radio" value="1" id="l" name="l" checked="" class="custom-control-input"><span class="custom-control-label">Propia</span>
																				</label><br>
																				<label class="custom-control custom-radio custom-control-inline" id="l" name="l">
																					<input type="radio" value="2" id="l" name="l" class="custom-control-input"><span class="custom-control-label">Alquilada</span>
																				</label><br>
																				<label class="custom-control custom-radio custom-control-inline">
																					<input type="radio" value="3" id="l" name="l" checked="" class="custom-control-input"><span class="custom-control-label">Anticretico</span>
																				</label><br>
																				<label class="custom-control custom-radio custom-control-inline" id="l" name="l">
																					<input type="radio" value="4" id="l" name="l" class="custom-control-input"><span class="custom-control-label">Cedida por servicios</span>
																				</label><br>
																				<label class="custom-control custom-radio custom-control-inline">
																					<input type="radio" value="5" id="l" name="l" checked="" class="custom-control-input"><span class="custom-control-label">Prestada por parientes o amigos</span>
																				</label><br>
																				<label class="custom-control custom-radio custom-control-inline" id="l" name="l">
																					<input type="radio" value="6" id="l" name="l" class="custom-control-input"><span class="custom-control-label">Contrato Mixto (Alquiler y anticretico)</span>
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
																		<label class="section-title">4.4.2. ¿Con que frecuencia usa el internet?</label>
																		<div class="">
																			<div class="controls">
																				<label class="custom-control custom-radio custom-control-inline">
																					<input type="radio" value="1" id="m" name="m" checked="" class="custom-control-input"><span class="custom-control-label">Diariamente</span>
																				</label><br>
																				<label class="custom-control custom-radio custom-control-inline" id="m" name="m">
																					<input type="radio" value="2" id="m" name="m" class="custom-control-input"><span class="custom-control-label">Una sola vez</span>
																				</label><br>
																				<label class="custom-control custom-radio custom-control-inline">
																					<input type="radio" value="1" id="m" name="m" checked="" class="custom-control-input"><span class="custom-control-label">Mas de una vez a la semana</span>
																				</label><br>
																				<label class="custom-control custom-radio custom-control-inline" id="m" name="m">
																					<input type="radio" value="2" id="m" name="m" class="custom-control-input"><span class="custom-control-label">Una vez al mes</span>
																				</label><br>
																			</div>
																		</div>
																	</div>

																	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
																		<br>
																		<h5 class="section-title">4.5. ACTIVIDAD LABORAL DE LA O EL ESTUDIANTE.</h5>
																	</div>

																	<!-- columnas -->
																	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12" style="background-color:#f3f3f3;">
																		<br>
																		<label class="section-title">4.5.1. En la pasada gestion ¿El estudiante trabajó?</label>

																		<div class="">
																			<div class="controls">
																				<label class="custom-control custom-radio custom-control-inline">
																					<input type="radio" value="1" id="n" name="n" checked="" class="custom-control-input"><span class="custom-control-label">Si</span>
																				</label><br>
																				<label class="custom-control custom-radio custom-control-inline" id="n" name="n">
																					<input type="radio" value="2" id="n" name="n" class="custom-control-input"><span class="custom-control-label">No</span>
																				</label><br>
																				<label class="custom-control custom-radio custom-control-inline">
																					<input type="radio" value="1" id="n" name="n" checked="" class="custom-control-input"><span class="custom-control-label">Ns/Nr</span>
																				</label><br>
																			</div>
																		</div>
																	</div>

																	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12" style="background-color:#f3f3f3;">
																		<br><br>
																		<label class="section-title">Maque los meses que trabajo</label>

																		<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="4511" name="mes[0]" value="4511">
																				<label class="custom-control-label" for="4511">Ene</label>
																			</div>
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="4512" name="mes[1]" value="4511">
																				<label class="custom-control-label" for="4512">Feb</label>
																			</div>
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="4513" name="mes[2]" value="4511">
																				<label class="custom-control-label" for="4513">Mar</label>
																			</div>
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="4514" name="mes[3]" value="4511">
																				<label class="custom-control-label" for="4514">Abr</label>
																			</div>
																		</div>
																		<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="4515" name="mes[4]" value="4511">
																				<label class="custom-control-label" for="4515">May</label>
																			</div>
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="4516" name="mes[5]" value="4511">
																				<label class="custom-control-label" for="4516">Jun</label>
																			</div>
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="4517" name="mes[6]" value="4511">
																				<label class="custom-control-label" for="4517">Jul</label>
																			</div>
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="4518" name="mes[7]" value="4511">
																				<label class="custom-control-label" for="4518">Ago</label>
																			</div>
																		</div>

																		<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="4519" name="mes[8]" value="4511">
																				<label class="custom-control-label" for="4519">Sep</label>
																			</div>
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="45110" name="mes[9]" value="4511">
																				<label class="custom-control-label" for="45110">Oct</label>
																			</div>
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="45111" name="mes[10]" value="4511">
																				<label class="custom-control-label" for="45111">Nov</label>
																			</div>
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="45112" name="mes[11]" value="4511">
																				<label class="custom-control-label" for="45112">Dic</label>
																			</div>
																		</div>
																	</div>

																	<!-- columnas -->
																	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																		<br>
																		<label class="section-title">4.5.2. En la pasada gestion ¿En que actividad trabajo el estudiante?</label>

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
																		<br><br><br>
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
																				<input id="o" name="o" type="text" class="form-control">
																			</div>
																		</div>
																	</div>


																	<!-- columnas -->
																	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																		<br>
																		<label class="section-title">4.5.3. En que turno trabajo el estudiante?</label>

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
																					<input type="radio" value="1" id="q" name="q" checked="" class="custom-control-input"><span class="custom-control-label">Si</span>
																				</label><br>
																				<label class="custom-control custom-radio custom-control-inline" id="q" name="q">
																					<input type="radio" value="2" id="q" name="q" class="custom-control-input"><span class="custom-control-label">No</span>
																				</label><br>
																				<label class="custom-control custom-radio custom-control-inline">
																					<input type="radio" value="1" id="q" name="q" checked="" class="custom-control-input"><span class="custom-control-label">Ns/Nr</span>
																				</label><br>
																			</div>
																		</div>
																	</div>

																	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																		<br><br>
																		<label class="section-title"></label>
																		<div class="custom-control custom-checkbox">
																			<input type="checkbox" class="custom-control-input" id="4551" name="pago[0]" value="4551">
																			<label class="custom-control-label" for="4551">En especie</label>
																		</div>
																		<div class="custom-control custom-checkbox">
																			<input type="checkbox" class="custom-control-input" id="4552" name="pago[0]" value="4551">
																			<label class="custom-control-label" for="4552">Dinero</label>
																		</div>
																	</div>

																	<!-- columnas -->
																	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
																		<br>
																		<label class="section-title">5.1. LA O EL ESTUDIANTE VIVE HABITUALMENTE CON :</label>
																		<div class="">
																			<div class="controls">
																				<label class="custom-control custom-radio custom-control-inline">
																					<input type="radio" value="1" id="r" name="r" checked="" class="custom-control-input"><span class="custom-control-label">Padre y Madre</span>
																				</label><br>
																				<label class="custom-control custom-radio custom-control-inline" id="r" name="r">
																					<input type="radio" value="2" id="r" name="r" class="custom-control-input"><span class="custom-control-label">Solo Padre</span>
																				</label><br>
																				<label class="custom-control custom-radio custom-control-inline">
																					<input type="radio" value="3" id="r" name="r" checked="" class="custom-control-input"><span class="custom-control-label">Solo Madre</span>
																				</label><br>
																				<label class="custom-control custom-radio custom-control-inline">
																					<input type="radio" value="4" id="r" name="r" checked="" class="custom-control-input"><span class="custom-control-label">Tutor(a)</span>
																				</label><br>
																				<label class="custom-control custom-radio custom-control-inline">
																					<input type="radio" value="5" id="r" name="r" checked="" class="custom-control-input"><span class="custom-control-label">Solo(a)</span>
																				</label><br>
																			</div>
																		</div>
																	</div>
																</div>
																<br>
																<div align="right">
																	<button type="button" class="btn btn-success" onclick="atrasActualizacion()">Atras</button>
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

				<div class="tab-pane fade" id="inscripcion" role="tabpanel" aria-labelledby="inscripcion-tab">
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
										<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
											<div class="card  alert-primary-">
												<div class="card-body">
													<h2 class="mb-1">Tipo</h2>
													<div class="control-group">
														<select name="tipo_estudiante" id="tipo_estudiante" class="form-control">
															<option value="" selected="selected">Seleccionar</option>
															<?php foreach ($tipo_estudiante as $value) : ?>
																<option value="<?= $value['id_tipo_estudiante']; ?>"><?= escape($value['nombre_tipo_estudiante']); ?></option>
															<?php endforeach ?>
														</select>
													</div>
												</div>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
											<div class="card  alert-primary-">
												<div class="card-body">
													<h2 class="mb-1">Nivel</h2>
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
										<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
											<div class="card  alert-primary-">
												<div class="card-body">
													<h2 class="mb-1">Curso</h2>
													<div class="control-group">
														<select name="select_curso" id="select_curso" onchange="listar_paralelos();" class="form-control">
															<option value="" selected="selected">Seleccionar</option>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
											<div class="card  alert-primary-">
												<div class="card-body">
													<h2 class="mb-1">Paralelo</h2>
													<div class="control-group">
														<select name="select_paralelo" id="select_paralelo" onchange="listar_vacantes();" class="form-control">
															<option value="" selected="selected">Seleccionar</option>
														</select>
													</div>
												</div>
											</div>
										</div>
										<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
											<div class="card  alert-primary-">
												<div class="card-body">
													<h2 class="mb-1">Vacantes</h2>
													<div class="control-group">
														<input type="text" class="form-control" name="vacantes" id="vacantes">
														<!--select name="select_paralelo" id="vacantes" class="form-control">
													</select-->
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<div class="alert alert-primary" role="alert">
									<b>Para terminar la inscripción presione en Finalizar</b>
								</div>
							</div>
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-right">
								<button type="submit" class="btn btn-primary pull-right" onclick="atrasVacunas()">Atras</button>
								<button type="submit" class="btn btn-primary pull-right" id="btn_inscripcion">Finalizar Inscripcion</button>
							</div>
							<input type="hidden" id="correo" onclick="correo_prueba();" class="btn btn-secondary" value="correo">
						</div>
					</form>
				</div>

				<div class="tab-pane fade" id="pagos" role="tabpanel" aria-labelledby="pagos-tab">
					<form method="post" action="#" id="form_pago" autocomplete="off">
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
											<tbody  id="contenedor_familiar">
												<?php $contador=0;?>
												<?php foreach ($pagos as $p): ?>
													<?php $contador=$contador+1; ?>
													<tr>
														<td class="text-nowrap text-center"><input type="checkbox" value="<?= escape($p['id_pensiones']); ?>" name="id_pensiones[]" id="id_pensiones<?= $contador; ?>"></td>
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
								<button type="submit" class="btn btn-primary pull-right"  id="btn_pago">Registrar Pagos de Estudiante</button>
							</div>
						</div>
					</form>
				</div>

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
				<button type="button" class="btn btn-primary" id="crop_mp">Recortar</button>
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
									<input type="hidden" name="<?= $csrf; ?>">
									<input type="hidden" class="form-control" id="f_id_familiar" name="f_id_familiar">
									<input type="hidden" class="form-control" id="f_id_persona" name="f_id_persona">
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
</div>
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
    <script src="<?= themes; ?>/concept/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="<?= themes; ?>/concept/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="<?= themes; ?>/concept/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <script src="<?= themes; ?>/concept/assets/libs/js/main-js.js"></script>

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

<!-- </div> -->
<!-- </div> -->
</div>
</body>
 
</html>
<?php
$directory = ""
?>
<script>
	//Variable de id_estudiante que se captura para registrar el rude
	let id_familiar_tutor = 0;
	var a_id_familiar = [];
	let id_estudiante = 0;
	let id_inscripcion_rude = 0;
	let lista_familiar = "";

	$(function() {
		cargar_tipo_documento();
		var id_estudiante = <?= $id_estudiante; ?>;
		/*var isChecked = document.getElementById('f_tutor').checked;
		if(isChecked){
			check = 1;
		}else{
			check = 0;
		}*/


		if (id_estudiante) {
			datos_estudiante(id_estudiante);
		} else {
			$("#form_datos_personales")[0].reset();
			$("#form_vacunas")[0].reset();
		}

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

	var disabledDays = [0, 6];
	//asignamos propiedades y capturamos la fecha del calendario del padre, madre y tutor
	$('#fecha_nacimiento').datepicker({
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


	var disabledDays = [0, 6];
	//asignamos propiedades y capturamos la fecha del calendario del padre, madre y tutor
	$('#f_fecha_nacimiento_tutor').datepicker({
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


	function datos_estudiante(id_estudiante) {
		$.ajax({
			url: '?/s-estudiantes/procesos',
			type: 'POST',
			data: {
				'id_estudiante': id_estudiante,
				'boton': 'datos_estudiante'
			},
			dataType: 'JSON',
			success: function(resp) {
				$("#id_estudiante").val(resp['datos_personales']['id_estudiante']);
				$("#nombres").val(resp['datos_personales']['nombres']);
				$("#primer_apellido").val(resp['datos_personales']['primer_apellido']);
				$("#segundo_apellido").val(resp['datos_personales']['segundo_apellido']);
				$("#tipo_documento").val(resp['datos_personales']['tipo_documento']);
				$("#numero_documento").val(resp['datos_personales']['numero_documento']);
				$("#complemento").val(resp['datos_personales']['complemento']);
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
		var avatar_pm = document.getElementById('avatar_pm'); //elemento para la imagen recortada
		var image_pm = document.getElementById('image_pm'); //elemento para hacer el recorte
		var input_pm = document.getElementById('input_pm'); //elemento para cargar la imagden

		var nombre_imagen;
		var $progress = $('.progress');
		var $progressBar = $('.progress-bar');
		var $alert = $('.alert');
		var $modal = $('#modal_subir_tutor');
		//var cropper;
		var Cropper = window.Cropper;

		$('[data-toggle="tooltip"]').tooltip();

		input_pm.addEventListener('change', function(e) {
			var files = e.target.files;
			var done = function(url) {
				nombre_imagen = input_pm.value;
				input_pm.value = '';
				image_pm.src = url;
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
			cropper = new Cropper(image_pm, {
				aspectRatio: 1, //controla el cuadro transparente para hacer el recorte
				viewMode: 2, // controla el modo de ver el la imagen subida
			});
		}).on('hidden.bs.modal', function() {
			cropper.destroy();
			cropper = null;
		});

		document.getElementById('crop_pm').addEventListener('click', function() {
			var initialAvatarURL;
			var canvas;

			$modal.modal('hide');

			if (cropper) {
				//define el tamaño que se va a guardar la imagen recortada
				canvas = cropper.getCroppedCanvas({
					width: 350,
					height: 350,
				});
				initialAvatarURL = avatar_pm.src;
				avatar_pm.src = canvas.toDataURL();
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
							avatar_pm.src = "files/profiles/temporal/fotos/" + respuesta; //carga la imagen recortada al elemento avatar
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
	//Fin del metodo para subir imagen del padre, madre y tutor

	//Luis esto es importante
	/*$("#form_datos_personales").on('submit', function(e) {
		e.preventDefault();
		console.log($('[data-idtutor]').attr('data-idtutor'));
	});*/

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
							//$("#ids_familar").val("hola Luchito");
							/*$("#id_estudiante").val(resp['id_estudiante']);
							$("#id_vacunas").val(resp['id_estudiante']);
							$("#id_familiares").val(resp['id_estudiante']);
							$("#id_documentos").val(resp['id_estudiante']);
							$("#id_inscripciones").val(resp['id_estudiante']);
							id_estudiante = resp['id_estudiante'];
							listar_familiares(id_estudiante);*/
							alertify.success('Se registro las vacunas del estudiante correctamente');
							break;
						case 2: //dataTable.ajax.reload();
							$('#inscripcion-tab').tab('show');
							/*$("#id_estudiante").val(resp['id_estudiante']);
							$("#id_vacunas").val(resp['id_estudiante']);
							$("#id_familiares").val(resp['id_estudiante']);
							$("#id_documentos").val(resp['id_estudiante']);
							$("#id_inscripciones").val(resp['id_estudiante']);
							id_estudiante = resp['id_estudiante'];
							listar_familiares(id_estudiante);*/
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
			genero: {
				required: true
			},
			pais: {
				required: true
			},
			departamento: {
				required: true
			},
			provincia: {
				required: true
			},
			localidad: {
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
			genero: "Debe seleccionar el género.",
			pais: "Debe seleccionar un pais.",
			departamento: "Debe seleccionar un departamento.",
			provincia: "Debe seleccionar una provincia.",
			localidad: "Debe seleccionar una localidad.",
			fecha_nacimiento: "Debe seleccionar la fecha de nacimiento",
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
								//saltamos al siguiente tab actualizacion de datos
								$('#familiar-tab').tab('show');
								//Resivimos el id_estudiante que creamos
								id_estudiante = resp['id_estudiante'];
								/*$("#id_estudiante").val(resp['id_estudiante']);
								$("#id_familiares").val(resp['id_estudiante']);
								$("#id_vacunas").val(resp['id_estudiante']);
								$("#id_documentos").val(resp['id_estudiante']);
								$("#id_inscripciones").val(resp['id_estudiante']);
								id = resp['id_estudiante'];
								//listar_familiares(id);*/
								alertify.success('Se registro datos personales del estudiante correctamente');
								break;
							case 2: //dataTable.ajax.reload();
								$('#vacunas-tab').tab('show');
								$("#id_estudiante").val(resp['id_estudiante']);
								alertify.success('Se modifico los datos del estudiante correctamente');
								break;
							case 3: //dataTable.ajax.reload();
								alertify.error('El estudiante ya esta registrado en la gestion actual');
								break;
						}
					}
				});
			} else {
				alertify.error('Debe registrar los Datos de Padre y Madre o Tutor');
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
				required: true
			},
			libro: {
				required: true
			},
			partida: {
				required: true
			},
			folio: {
				required: true
			},
			departamento: {
				required: true
			},
			provincia: {
				required: true
			},
			seccion: {
				required: true
			},
			localidad: {
				required: true
			},
			zona: {
				required: true
			},
			telefono: {
				required: true
			},
			celular: {
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
			datos = datos + ('&id_estudiante=' + id_estudiante);
			datos = datos + ('&id_inscripcion_rude=' + id_inscripcion_rude);
			datos = datos + '&boton=guardar_certificado';
			//console.log(datos);	
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
				required: true
			},
			b: {
				required: true
			},
			c: {
				required: true
			},
			d: {
				required: true
			},
			salud: {
				required: true
			},
			e: {
				required: true
			},
			f: {
				required: true
			},
			g: {
				required: true
			},
			h: {
				required: true
			},
			i: {
				required: true
			},
			j: {
				required: true
			},
			k: {
				required: true
			},
			l: {
				required: true
			},
			internet: {
				required: true
			},
			m: {
				required: true
			},
			n: {
				required: true
			},
			trabajo: {
				required: true
			},
			trabajo: {
				required: true
			},
			turno: {
				required: true
			},
			p1: {
				required: true
			},
			q: {
				required: true
			},
			pago: {
				required: true
			},
			r: {
				required: true
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
							$('#vacunas-tab').tab('show');
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
							alertify.success('Se registro correctamente el RUDE');
							break;
						case 2: //dataTable.ajax.reload();
							$('#familiar-tab').tab('show');
							//$("#id_estudiante").val(resp['id_estudiante']);
							alertify.success('Se modifico los datos del estudiante correctamente');
							break;
						case 3: //dataTable.ajax.reload();
							alertify.error('El estudiante ya esta registrado en la gestion actual');
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
			f_fecha_nacimiento: {
				required: true
			},
			f_telefono: {
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
			primer_apellido: "Debe ingresar primer apellido",
			f_tipo_documento: "Debe ingresar tipo de documento",
			f_numero_documento: "Debe ingresar número de documento",
			f_fecha_nacimiento: "Debe ingresar fecha de nacimiento",
			f_telefono: "Debe ingresar teléfono",
			f_parentesco: "Debe ingresar un tipo de parentesco"
		},
		//una ves validado guardamos los datos en la DB
		submitHandler: function(form) {
			var datos = $("#form_familiar").serialize();
			//var id_familiares = $("#id_familiares").val();
			//datos = datos + '&tutorcheck=' + check;
			datos = datos + '&boton=' + 'agregar_familiar';
			$.ajax({
				type: 'POST',
				url: "?/s-inscripciones/procesos",
				data: datos,
				dataType: 'json',
				success: function(resp) {
					console.log(resp['estado']);
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
							//Vamos mostrando como se arma el array
							console.log(a_id_familiar);
							//lista_familiar = lista_familiar +resp['familiar'];
							//$("#lista_familia").html(lista_familiar);
							

							$('[data-idtutor]').attr('data-idtutor', resp);
							document.getElementById("form_familiar").reset();
							break;
						case 2: //dataTable.ajax.reload();
							//$("#modal_familiar").modal("hide");
							alertify.success('Se editó el familiar correctamente');
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
			//var id_estudiante = $("#id_documentos").val();
			//datos = datos + '&id_familiares='+ id_familiares;
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
							alertify.success('Se registro el familiar correctamente');
							break;
						case '2': //dataTable.ajax.reload();
							//$("#modal_familiar").modal("hide");
							alertify.success('Se editó el familiar correctamente');
							break;
					}
				}
			});
		}
	});

	/************************************************************/
	/*   t1 formulario de registro de inscripcion    */
	/************************************************************/
	$("#form_inscripcion").validate({
		rules: {
			tipo_estudiante: {
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
			},
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight: highlight,
		unhighlight: unhighlight,
		messages: {
			tipo_estudiante: "Debe seleccionar el tipo de estudiante.",
			nivel_academico: "Debe seleccionar el nivel academico.",
			select_curso: "Debe seleccionar el curso.",
			select_paralelo: "Debe seleccionar el paralelo."
		},
		//una ves validado guardamos los datos en la DB
		submitHandler: function(form) {
			var datos = $("#form_inscripcion").serialize();
			//var id_estudiante = $("#id_documentos").val();
			datos = datos + '&id_estudiante=' + id_estudiante;
			datos = datos + '&id_familiar_tutor=' + id_familiar_tutor;
			//datos = datos + '&id_familiar=' + a_id_familiar;
			//datos = datos + '&id_familiares='+ id_familiares;
			datos = datos + '&boton=' + 'guardar_inscripcion';
			console.log(datos);
			$.ajax({
				type: 'POST',
				url: "?/s-inscripciones/procesos",
				data: datos,
				dataType: 'json',
				success: function(resp) {
					console.log(resp['estado']);
					switch (resp['estado']) {
						case 1:

							$('#rep_documentos-tab').tab('show');

							break;
						case 2: //dataTable.ajax.reload();
							$("#modal_familiar").modal("hide");
							alertify.success('Se editó el familiar correctamente');
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

	function listar_familiares(id_estudiante) {
		$.ajax({
			type: 'POST',
			url: "?/s-estudiantes/procesos",
			dataType: 'json',
			data: {
				'id_estudiante': id_estudiante,
				'boton': 'listar_familiares'
			},
			success: function(data) {
				html = "";
				imagen = "";
				for (var i = 0; i < data.length; i++) {
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
					html += '<tr><td>' + (i + 1) + '</td><td>' + imagen + '</td><td>' + data[i]['primer_apellido'] + '</td><td>' + data[i]['segundo_apellido'] + '</td><td>' + data[i]['nombres'] + '</td><td>' + data[i]['numero_documento'] + '</td><td>' + data[i]['telefono_oficina'] + '</td><td>' + check + '</td><td><button class="btn btn-success btn-xs" onclick="abrir_modificar(' + "'" + contenido + "'" + ');"><i class="fa fa-edit"></i></button>  <button class="btn btn-danger btn-xs" onclick="eliminar_familiar(' + "'" + contenido + "'" + ');"><i class="fa fa-trash"></i></button> </td></tr>'
				}
				$("#contenedor_familiar").html(html);
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
		$("#modal_familiar").modal("show");
		$("#titulo_modal_familiar").text("Modificar datos del Familiar");
		$("#form_familiar")[0].reset();
		$("#btn_editar").show();
		$("#btn_nuevo").hide();
		id_familiar = d[1];
		$.ajax({
			url: '?/s-estudiantes/procesos',
			type: 'POST',
			data: {
				'id_familiar': id_familiar,
				'boton': 'buscar_datos_personales'
			},
			dataType: 'JSON',
			success: function(resp) {
				//console.log(resp);
				$("#id_familiar").val(resp['id_familiar']);
				$("#id_persona").val(resp['id_persona']);
				$("#nombres").val(resp['nombres']);
				$("#primer_apellido").val(resp['primer_apellido']);
				$("#segundo_apellido").val(resp['segundo_apellido']);
				$("#tipo_documento").val(resp['tipo_documento']);
				$("#numero_documento").val(resp['numero_documento']);
				$("#complemento").val(resp['complemento']);
				$("#genero").val(resp['genero']);
				$("#fecha_nacimiento").val(moment(resp['fecha_nacimiento']).format('YYYY-MM-DD'));
				$("#telefono").val(resp['telefono']);
				$("#profesion").val(resp['profesion']);
				$("#direccion_oficina").val(resp['direccion_oficina']);
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

	function listar_cursos() {
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

	function listar_paralelos() {
		id_curso = $("#select_curso option:selected").val();
		//alert(nivel);
		$.ajax({
			url: '?/s-inscripciones/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_paralelos',
				'id_curso': id_curso
			},
			dataType: 'JSON',
			success: function(resp) {
				//console.log(resp);
				//alert(resp[0]['id_catalogo_detalle']);
				$("#select_paralelo").html("");
				$("#select_paralelo").append('<option value="' + 0 + '">Seleccionar</option>');
				for (var i = 0; i < resp.length; i++) {
					$("#select_paralelo").append('<option value="' + resp[i]["id_aula_paralelo"] + '">' + resp[i]["nombre_paralelo"] + '</option>');
				}
				//console.log(resp[0]);
			}
		});
	}

	function listar_vacantes() {
		id_aula_paralelo = $("#select_paralelo option:selected").val()
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
					//$("#vacantes").text(resp);
					$("#vacantes").val(resp);
					//$("#vacantes").html('<option value="'+resp+'">'+ resp+'</option>');
					$("#btn_inscripcion").show();
				} else {
					$("#btn_inscripcion").hide();
					alertify.error('No hay vacantes en este curso y paralelo');
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
		
		//Sereamos todos los formularios
		/*document.getElementById("nombres").value("");
		document.getElementById("tipo_documento").value("");
		document.getElementById("numero_documento").value("");
		document.getElementById("complemento").value("");
		document.getElementById("fecha_nacimiento").value("");
		
		document.getElementById("form_datos_certificado").reset();
		document.getElementById("form_datos_rude").reset();
		document.getElementById("form_vacunas").reset();
		document.getElementById("form_inscripcion").reset();
		document.getElementById("form_documento").reset();
		document.getElementById("form_recep_documentos").reset();*/
		$('#personales-tab').tab('show');	
	}

	function atrasInicio() {
		$('#personales-tab').tab('show');
	}

	function atrasActualizacion() {
		$('#familiar-tab').tab('show');
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

$("#form_pago").validate({
	rules: {
		id_pensiones: {required: true},
	},
	errorClass: "help-inline",
	errorElement: "span",
	highlight: highlight,
	unhighlight: unhighlight,
	messages: {
		id_pensiones: "Debe seleccionar el tipo de estudiante.",
	},
	//una ves validado guardamos los datos en la DB
	submitHandler: function(form){
		var datos = $("#form_pago").serialize();
		//var id_estudiante = $("#id_documentos").val();
		//datos = datos + '&id_familiares='+ id_familiares;
		//datos = datos + '&boton='+ 'guardar_concepto_pago';
		console.log(datos);
		$.ajax({
			type: 'POST',
			url: "?/s-inscripciones/procesos",
			//data: datos,
			data: {'id_estudiante': id_estudiante,'boton': 'guardar_concepto_pago'},
			success: function (resp) {
				console.log(resp);
				switch(resp){
					case '1': //dataTable.ajax.reload();
							document.location.href="?/s-inscripciones/imprimir-pago"
							break;
					case '2': //dataTable.ajax.reload();
							alertify.success('Error, verifique la información'); 
							break;
				}
			}
		});
	}
})
	//window.onload = cargar_tipo_documento();
</script>