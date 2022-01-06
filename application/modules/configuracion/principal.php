<?php
//$busquedas=$_SESSION['busquedas'];
// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los temas instalados
$temas = get_directories(themes);

// Obtiene los permisos
$permiso_subir = in_array('subir', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_informacion = in_array('informacion', $_views);
$permiso_presentacion = in_array('presentacion', $_views); 

?>
<?php require_once show_template('header-design'); ?>
<style>
	@media (min-width: 768px) {

		.table-display>.tbody>.tr>.td,
		.table-display>.tbody>.tr>.th,
		.table-display>.tfoot>.tr>.td,
		.table-display>.tfoot>.tr>.th,
		.table-display>.thead>.tr>.td,
		.table-display>.thead>.tr>.th {
			padding-bottom: 15px;
			vertical-align: top;
		}

		.table-display>.tbody>.tr>.td:first-child,
		.table-display>.tbody>.tr>.th:first-child,
		.table-display>.tfoot>.tr>.td:first-child,
		.table-display>.tfoot>.tr>.th:first-child,
		.table-display>.thead>.tr>.td:first-child,
		.table-display>.thead>.tr>.th:first-child {
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
			<h2 class="pageheader-title">Configuración General </h2>
			<p class="pageheader-text"></p>
			<div class="page-breadcrumb">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Administración</a></li>
						<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Configuración General</a></li>
						<!-- <li class="breadcrumb-item active" aria-current="page">Listar</li> -->
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- end pageheader -->
<!-- ============================================================== -->
<div class="row">
	<!-- ============================================================== -->
	<!-- row -->
	<!-- ============================================================== -->
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="card">
			<!-- <h5 class="card-header">Generador de menús</h5> -->
			<div class="card-header">
				<div class="row">
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
						<div class="text-label hidden-xs">Seleccionar acción:</div>
					</div>
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
						<div class="btn-group">
							<div class="input-group">
								<div class="input-group-append be-addon">
									<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
									<div class="dropdown-menu">
										<a class="dropdown-item">Seleccionar acción</a>
										<div class="dropdown-divider"></div>
										<a href="?/principal/escritorio" class="dropdown-item">Página Principal</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="card-body">
				<!-- ============================================================== -->
				<!-- datos -->
				<!-- ============================================================== -->


				<div class="row">
					<div class="col-sm-4 col-md-4">
						<div class="card card-figure has-hoverable">
							<!-- .card-figure -->
							<figure class="figure">
								<img src="<?= ($_institution['logotipo'] == '') ? imgs . '/16by9.jpg' : files . '/logos/' . $_institution['logotipo']; ?>" class="img-responsive thumbnail cursor-pointer" data-toggle="modal" data-target="#modal_mostrar" data-modal-size="modal-lg" data-modal-title="Logotipo">
								<!-- <img class="img-fluid" src="../assets/images/card-img.jpg" alt="Card image cap"> -->
							</figure>
							<!-- /.card-figure -->
						</div>
						<?php if ($permiso_subir || $permiso_eliminar || $permiso_informacion || $permiso_presentacion) : ?>
							<div class="list-group">
								<?php if ($permiso_subir) : ?>
									<a href="#" class="list-group-item text-ellipsis" onclick="subir()" data-toggle="modal" data-target="#modal_subir" data-backdrop="static" data-keyboard="false">
										<span class="glyphicon glyphicon-fire"></span>
										<span>Subir logotipo</span>
									</a>
								<?php endif ?>
								<?php if ($permiso_eliminar) : ?>
									<a href="?/configuracion/eliminar" class="list-group-item text-ellipsis">
										<span class="glyphicon glyphicon-eye-close"></span>
										<span>Eliminar logotipo</span>
									</a>
								<?php endif ?>
								<?php if ($permiso_informacion) : ?>
									<a href="#" class="list-group-item text-ellipsis" onclick="modificar()" data-toggle="modal" data-target="#modal_informacion">
										<span class="glyphicon glyphicon-home"></span>
										<span>Modificar información</span>
									</a>
								<?php endif ?>
								<?php if ($permiso_presentacion) : ?>
									<a href="#" class="list-group-item text-ellipsis" onclick="presentacion()" data-toggle="modal" data-target="#modal_presentacion">
										<span class="glyphicon glyphicon-facetime-video"></span>
										<span>Modificar presentación</span>
									</a>
								<?php endif ?>
							</div>
						<?php endif ?>
					</div>

					<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12 mb-8">
						<div class="tab-regular">
							<?php if ($permiso_informacion || $permiso_presentacion) : ?>
								<ul class="nav nav-tabs nav-fill" id="myTab7" role="tablist">
									<?php if ($permiso_informacion) : ?>
										<li class="nav-item">
											<a class="nav-link active" id="home-tab-justify" data-toggle="tab" href="#home-justify" role="tab" aria-controls="home" aria-selected="true">
												<font style="vertical-align: inherit;">
													<font style="vertical-align: inherit;">Información</font>
												</font>
											</a>
										</li>
									<?php endif ?>
									<?php if ($permiso_presentacion) : ?>
										<li class="nav-item">
											<a class="nav-link" id="profile-tab-justify" data-toggle="tab" href="#profile-justify" role="tab" aria-controls="profile" aria-selected="false">
												<font style="vertical-align: inherit;">
													<font style="vertical-align: inherit;">Presentación</font>
												</font>
											</a>
										</li>
									<?php endif ?>
								</ul>
							<?php endif ?>
							<div class="tab-content" id="myTabContent7">
								<?php if ($permiso_informacion) : ?>
									<div class="tab-pane fade show active" id="home-justify" role="tabpanel" aria-labelledby="home-tab-justify">
										<p class="lead"><strong>Información de la institución</strong></p>
										<hr>
										<div class="table-display">

											<table class="table table-striped">
												<tr>
													<th>
														<div class="td" align="right">Nombre de la institución:</div>
													</th>
													<td>
														<div class="td"><?= escape($_institution['nombre']); ?></div>
													</td>
												</tr>
												<tr>
													<th>
														<div class="td" align="right">Sigla de la institución:</div>
													</th>
													<td>
														<div class="td"><?= escape($_institution['sigla']); ?></div>
													</td>
												</tr>
												<tr>
													<th>
														<div class="td" align="right">Lema de la institución:</div>
													</th>
													<td>
														<div class="td"><?= escape($_institution['lema']); ?></div>
													</td>
												</tr>
												<tr>
													<th>
														<div class="td" align="right">Codigo SIE:</div>
													</th>
													<td>
														<div class="td"><?= escape($_institution['codigo_sie']); ?></div>
													</td>
												</tr>
												<tr>
													<th>
														<div class="td" align="right">Razón social:</div>
													</th>
													<td>
														<div class="td"><?= escape($_institution['razon_social']); ?></div>
													</td>
												</tr>
												<tr>
													<th>
														<div class="td" align="right">NIT:</div>
													</th>
													<td>
														<div class="td"><?= ($_institution['nit'] != '') ? escape($_institution['nit']) : 'No asignado'; ?></div>
													</td>
												</tr>
												<tr>
													<th>
														<div class="td" align="right">Propietario:</div>
													</th>
													<td>
														<div class="td"><?= escape($_institution['propietario']); ?></div>
													</td>
												</tr>
												<tr>
													<th>
														<div class="td" align="right">Dirección:</div>
													</th>
													<td>
														<div class="td"><?= escape($_institution['direccion']); ?></div>
													</td>
												</tr>
												<tr>
													<th>
														<div class="td" align="right">Teléfono:</div>
													</th>
													<td>
														<div class="td"><?= escape($_institution['telefono']); ?></div>
													</td>
												</tr>
												<tr>
													<th>
														<div class="td" align="right">Correo electrónico:</div>
													</th>
													<td>
														<div class="td"><?= escape($_institution['correo']); ?></div>
													</td>
												</tr>
											</table>
										</div>

									</div>
								<?php endif ?>
								<?php if ($permiso_presentacion) : ?>
									<div class="tab-pane fade" id="profile-justify" role="tabpanel" aria-labelledby="profile-tab-justify">
										<p class="lead"><strong>Presentación del sistema</strong></p>
										<hr>
										<div class="table-display">
											<div class="tbody">
												<div class="tr">
													<div class="td">Pie de página mostrada en los reportes:</div>
													<div class="td"><?= escape($_institution['informacion']); ?></div>
												</div>
												<div class="tr">
													<div class="th">Formato de visualización para las fechas:</div>
													<div class="td"><?= escape(get_date_textual($_format)); ?></div>
												</div>
												<div class="tr">
													<div class="th">Mostrar fecha y hora:</div>
													<div class="td"><?= ($_institution['reloj'] == 's') ? 'Si' : 'No'; ?></div>
												</div>
												<div class="tr">
													<div class="th">Ícono del sistema:</div>
													<div class="td"><?= ($_institution['icono'] != '') ? escape($_institution['icono']) : 'No asignado'; ?></div>
												</div>
												<div class="tr">
													<div class="th">Tema del sistema:</div>
													<div class="td"><?= ($_institution['tema'] != '') ? escape(capitalize($_institution['tema'])) : 'No asignado'; ?></div>
												</div>
											</div>
										</div>
										<p>
											<!--img src="<?= themes . '/' . $_institution['tema'] . '/preview.jpg' ?>" class="img-responsive thumbnail cursor-pointer" data-toggle="modal" data-target="#modal_mostrar" data-modal-size="modal-lg" data-modal-title="Tema"-->
										</p>
										<dl>
									</div>
								<?php endif ?>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!-- ============================================================== -->
		<!-- end row -->
		<!-- ============================================================== -->


		<!-- Modal subir inicio -->
		<?php if ($permiso_subir) : ?>
			<div id="modal_subir" class="modal fade" tabindex="-1">
				<div class="modal-dialog">
					<form method="post" action="?/configuracion/subir" enctype="multipart/form-data" id="form_subir" class="modal-content loader-wrapper" autocomplete="off">
						<input type="hidden" name="<?= $csrf; ?>">
						<div class="modal-header">
							<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
							<h4 class="modal-title">Subir logotipo</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label for="logotipo_subir" class="control-label">Logotipo:</label>
								<input type="file" name="logotipo" id="logotipo_subir" class="form-control" data-validation="required mime size dimension" data-validation-allowing="jpg, png" data-validation-max-size="4M" data-validation-dimension="max1920">
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary">
								<span class="glyphicon glyphicon-floppy-disk"></span>
								<span>Guardar</span>
							</button>
							<button type="reset" class="btn btn-default">
								<span class="glyphicon glyphicon-refresh"></span>
								<span>Restablecer</span>
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

		<!-- Modal informacion inicio -->

		<div id="modal_informacion" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<form method="post" action="?/configuracion/informacion" id="form_informacion" class="modal-content loader-wrapper" autocomplete="off">
					<input type="hidden" name="<?= $csrf; ?>">
					<div class="modal-header">
						<h4 class="modal-title">Modificar información</h4>
					</div>
					<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
					<div class="modal-body">
						<div class="form-group">
							<label for="nombre" class="control-label">Nombre de la institución:</label>
							<input type="text" value="<?= $_institution['nombre']; ?>" name="nombre" id="nombre" class="form-control" data-validation="required letternumber length" data-validation-allowing="-. " data-validation-length="max100">
						</div>
						<div class="form-group">
							<label for="sigla" class="control-label">Sigla de la institución:</label>
							<input type="text" value="<?= $_institution['sigla']; ?>" name="sigla" id="sigla" class="form-control" data-validation="required alphanumeric length" data-validation-allowing="-." data-validation-length="max10">
						</div>
						<div class="form-group">
							<label for="lema" class="control-label">Lema de la institución:</label>
							<input type="text" value="<?= $_institution['lema']; ?>" name="lema" id="lema" class="form-control" data-validation="required letternumber length" data-validation-allowing="-.,:; " data-validation-length="max200">
						</div>
						<div class="form-group">
							<label for="lema" class="control-label">Codigo SIE:</label>
							<input type="text" value="<?= $_institution['codigo_sie']; ?>" name="codigo_sie" id="codigo_sie" class="form-control" data-validation="required letternumber length" data-validation-allowing="-.,:; " data-validation-length="max200">
						</div>
						<div class="form-group">
							<label for="razon_social" class="control-label">Razón social:</label>
							<textarea name="razon_social" id="razon_social" class="form-control" data-validation="required letternumber" data-validation-allowing="-.,:;\n "><?= $_institution['razon_social']; ?></textarea>
						</div>
						<div class="form-group">
							<label for="nit" class="control-label">NIT:</label>
							<input type="text" value="<?= $_institution['nit']; ?>" name="nit" id="nit" class="form-control" data-validation="number length" data-validation-length="max20" data-validation-optional="true">
						</div>
						<div class="form-group">
							<label for="propietario" class="control-label">Propietario:</label>
							<input type="text" value="<?= $_institution['propietario']; ?>" name="propietario" id="propietario" class="form-control" data-validation="required letter length" data-validation-allowing=" " data-validation-length="max200">
						</div>
						<div class="form-group">
							<label for="direccion" class="control-label">Dirección:</label>
							<input type="text" value="<?= $_institution['direccion']; ?>" name="direccion" id="direccion" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.,#º() " data-validation-length="max300">
						</div>
						<div class="form-group">
							<label for="telefono" class="control-label">Teléfono:</label>
							<input type="text" value="<?= $_institution['telefono']; ?>" name="telefono" id="telefono" class="form-control" data-selectize="<?= $_institution['telefono']; ?>" data-validation="alphanumeric length" data-validation-length="max100" data-validation-allowing="+-,() " data-validation-optional="true">
						</div>
						<div class="form-group">
							<label for="correo" class="control-label">Correo electrónico:</label>
							<input type="text" value="<?= $_institution['correo']; ?>" name="correo" id="correo" class="form-control" data-validation="required email length" data-validation-length="max100">
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">
							<span class="glyphicon glyphicon-floppy-disk"></span>
							<span>Guardar</span>
						</button>
						<button type="reset" class="btn btn-default">
							<span class="glyphicon glyphicon-refresh"></span>
							<span>Restablecer</span>
						</button>
					</div>
					<!-- <div id="loader_informacion" class="loader-wrapper-backdrop hidden">
				<span class="loader"></span>
			</div> -->
				</form>
			</div>
		</div>

		<!-- Modal informacion fin -->

		<!-- Modal presentacion inicio -->
		<?php if ($permiso_presentacion) : ?>
			<div id="modal_presentacion" class="modal fade" tabindex="-1">
				<div class="modal-dialog">
					<form method="post" action="?/configuracion/presentacion" id="form_presentacion" class="modal-content loader-wrapper" autocomplete="off">
						<input type="hidden" name="<?= $csrf; ?>">
						<div class="modal-header">
							<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
							<h4 class="modal-title">Modificar presentación</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label for="informacion" class="control-label">Pie de página mostrada en los reportes:</label>
								<textarea name="informacion" id="informacion" class="form-control" data-validation="required letternumber length" data-validation-allowing="+-/.,:;@#&() " data-validation-length="10-200"><?= $_institution['informacion']; ?></textarea>
							</div>
							<div class="form-group">
								<label for="formato" class="control-label">Formato de visualización para las fechas:</label>
								<select name="formato" id="formato" class="form-control" data-validation="required">
									<option value="">Seleccionar</option>
									<option value="Y-m-d" <?= ($_format == 'Y-m-d') ? ' selected="selected"' : ''; ?>>yyyy-mm-dd</option>
									<option value="Y/m/d" <?= ($_format == 'Y/m/d') ? ' selected="selected"' : ''; ?>>yyyy/mm/dd</option>
									<option value="d-m-Y" <?= ($_format == 'd-m-Y') ? ' selected="selected"' : ''; ?>>dd-mm-yyyy</option>
									<option value="d/m/Y" <?= ($_format == 'd/m/Y') ? ' selected="selected"' : ''; ?>>dd/mm/yyyy</option>
								</select>
							</div>
							<div class="form-group">
								<label for="reloj" class="control-label">Mostrar fecha y hora:</label>
								<div class="radio">
									<label>
										<input type="radio" value="s" name="reloj" id="reloj" <?= ($_institution['reloj'] == 's') ? ' checked="checked"' : ''; ?>>
										<span>Si</span>
									</label>
								</div>
								<div class="radio">
									<label>
										<input type="radio" value="n" name="reloj" <?= ($_institution['reloj'] == 'n') ? ' checked="checked"' : ''; ?>>
										<span>No</span>
									</label>
								</div>
							</div>
							<div class="form-group">
								<label for="icono" class="control-label">Ícono del sistema:</label>
								<select name="icono" id="icono" class="form-control">
									<option value="">Seleccionar</option>
									<option value="glyphicon glyphicon-fire" <?= ($_institution['icono'] == 'glyphicon glyphicon-fire') ? ' selected="selected"' : ''; ?>>Fire</option>
									<option value="glyphicon glyphicon-leaf" <?= ($_institution['icono'] == 'glyphicon glyphicon-leaf') ? ' selected="selected"' : ''; ?>>Leaf</option>
									<option value="glyphicon glyphicon-apple" <?= ($_institution['icono'] == 'glyphicon glyphicon-apple') ? ' selected="selected"' : ''; ?>>Apple</option>
									<option value="glyphicon glyphicon-bell" <?= ($_institution['icono'] == 'glyphicon glyphicon-bell') ? ' selected="selected"' : ''; ?>>Bell</option>
									<option value="glyphicon glyphicon-flash" <?= ($_institution['icono'] == 'glyphicon glyphicon-flash') ? ' selected="selected"' : ''; ?>>Flash</option>
									<option value="glyphicon glyphicon-tree-conifer" <?= ($_institution['icono'] == 'glyphicon glyphicon-tree-conifer') ? ' selected="selected"' : ''; ?>>Tree conifer</option>
									<option value="glyphicon glyphicon-tree-deciduous" <?= ($_institution['icono'] == 'glyphicon glyphicon-tree-deciduous') ? ' selected="selected"' : ''; ?>>Tree deciduous</option>
									<option value="glyphicon glyphicon-baby-formula" <?= ($_institution['icono'] == 'glyphicon glyphicon-baby-formula') ? ' selected="selected"' : ''; ?>>Baby formula</option>
								</select>
							</div>
							<div class="form-group">
								<label for="tema" class="control-label">Tema del sistema:</label>
								<?php foreach ($temas as $nro => $tema) : ?>
									<div class="radio">
										<label>
											<input type="radio" value="<?= $tema ?>" name="tema" <?= ($nro == 0) ? ' id="tema"' : ''; ?><?= ($_institution['tema'] == $tema) ? ' checked="checked"' : ''; ?>>
											<span class="text-capitalize"><?= $tema; ?></span>
										</label>
									</div>
								<?php endforeach ?>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary">
								<span class="glyphicon glyphicon-floppy-disk"></span>
								<span>Guardar</span>
							</button>
							<button type="reset" class="btn btn-default">
								<span class="glyphicon glyphicon-refresh"></span>
								<span>Restablecer</span>
							</button>
						</div>
						<div id="loader_presentacion" class="loader-wrapper-backdrop hidden">
							<span class="loader"></span>
						</div>
					</form>
				</div>
			</div>
		<?php endif ?>
		<!-- Modal presentacion fin -->

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
		<script src="<?= js; ?>/selectize.min.js"></script>

		<script src="<?= js; ?>/bootbox.min.js"></script>
		<script>
			function subir() {
				$("#modal_subir").modal("show");
			}

			function modificar() {
				$("#modal_informacion").modal("show");
			}

			function presentacion() {
				$("#modal_presentacion").modal("show");
			}
			// $(function () {	
			// 	<?php if ($permiso_subir) : ?>
			// 	var $modal_subir = $('#modal_subir'), $form_subir = $('#form_subir'), $loader_subir = $('#loader_subir');

			// 	$.validate({
			// 		form: '#form_subir',
			// 		modules: 'file',
			// 		onSuccess: function () {
			// 			$loader_subir.removeClass('hidden');
			// 		}
			// 	});

			// 	$modal_subir.on('hidden.bs.modal', function () {
			// 		$form_subir.trigger('reset');
			// 	}).on('show.bs.modal', function (e) {
			// 		if ($('.modal:visible').size != 0) { e.preventDefault(); }
			// 	});
			// 	<?php endif ?>

			// 	<?php if ($permiso_informacion) : ?>	
			// 	var $modal_informacion = $('#modal_informacion'), $form_informacion = $('#form_informacion'), $loader_informacion = $('#loader_informacion'), $telefono = $('#telefono');

			// 	$.validate({
			// 		form: '#form_informacion',
			// 		modules: 'basic',
			// 		onSuccess: function () {
			// 			$loader_informacion.removeClass('hidden');
			// 		}
			// 	});

			// 	$telefono.selectize({
			// 		create: true,
			// 		createOnBlur: true,
			// 		maxOptions: 6,
			// 		persist: false,
			// 		onInitialize: function () {
			// 			$telefono.show().addClass('selectize-translate');
			// 		},
			// 		onChange: function () {
			// 			$telefono.trigger('blur');
			// 		},
			// 		onBlur: function () {
			// 			$telefono.trigger('blur');
			// 		}
			// 	});

			// 	$modal_informacion.find('form').on('reset', function () {
			// 		$telefono.get(0).selectize.setValue($telefono.attr('data-selectize').split(','));
			// 	});

			// 	$modal_informacion.on('hidden.bs.modal', function () {
			// 		$form_informacion.trigger('reset');
			// 	}).on('show.bs.modal', function (e) {
			// 		if ($('.modal:visible').size != 0) { e.preventDefault(); }
			// 	}).on('shown.bs.modal', function () {
			// 		$form_informacion.find('.form-control:nth(0)').focus();
			// 	});
			// 	<?php endif ?>

			// 	<?php if ($permiso_presentacion) : ?>
			// 	var $modal_presentacion = $('#modal_presentacion'), $form_presentacion = $('#form_presentacion'), $loader_presentacion = $('#loader_presentacion');

			// 	$.validate({
			// 		form: '#form_presentacion',
			// 		modules: 'basic',
			// 		onSuccess: function () {
			// 			$loader_presentacion.removeClass('hidden');
			// 		}
			// 	});

			// 	$modal_presentacion.on('hidden.bs.modal', function () {
			// 		$form_presentacion.trigger('reset');
			// 	}).on('show.bs.modal', function (e) {
			// 		if ($('.modal:visible').size != 0) { e.preventDefault(); }
			// 	}).on('shown.bs.modal', function () {
			// 		$form_presentacion.find('.form-control:nth(0)').focus();
			// 	});
			// 	<?php endif ?>

			// 	<?php if ($permiso_eliminar) : ?>
			// 	$('[data-eliminar]').on('click', function (e) {
			// 		e.preventDefault();
			// 		var href = $(this).attr('href');
			// 		var csrf = '<?= $csrf; ?>';
			// 		bootbox.confirm('¿Está seguro que desea eliminar el logotipo de la institución?', function (result) {
			// 			if (result) {
			// 				$.request(href, csrf);
			// 			}
			// 		});
			// 	});
			// 	<?php endif ?>

			/*/var $modal_mostrar = $('#modal_mostrar');
			var $loader_mostrar = $('#loader_mostrar');
			var size, title, image;

			$modal_mostrar.on('hidden.bs.modal', function() {
			$loader_mostrar.show();
			$modal_mostrar.find('.modal-dialog').attr('class', 'modal-dialog');
			$modal_mostrar.find('.modal-title').text('');
			}).on('show.bs.modal', function(e) {
			if ($('.modal:visible').size != 0) {
				e.preventDefault();
			}
			size = $(e.relatedTarget).attr('data-modal-size');
			title = $(e.relatedTarget).attr('data-modal-title');
			image = $(e.relatedTarget).attr('src');
			size = (size) ? 'modal-dialog ' + size : 'modal-dialog';
			title = (title) ? title : 'Imagen';
			$modal_mostrar.find('.modal-dialog').attr('class', size);
			$modal_mostrar.find('.modal-title').text(title);
			$modal_mostrar.find('[data-modal-image]').attr('src', image);
			}).on('shown.bs.modal', function() {
			$loader_mostrar.hide();
			});
			});*/
		</script>
		<?php require_once show_template('footer-design'); ?>