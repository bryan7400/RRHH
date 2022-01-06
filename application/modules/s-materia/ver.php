<?php

$nombre_dominio = escape($_institution['nombre_dominio']); 
// Obtiene el id_producto
$id_materia = (isset($_params[0])) ? $_params[0] : 0;

//$busquedas=$_SESSION['busquedas'];
// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene el id de la gestion actual
$id_gestion = $_gestion['id_gestion'];
// Obtiene los temas instalados
$temas = get_directories(themes);

// Obtiene los permisos
$permiso_subir = in_array('subir', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_informacion = in_array('informacion', $_views);
$permiso_presentacion = in_array('presentacion', $_views);

//obtiene las asignaciones del producto
$materia = $db->select('a.*')->from('pro_materia a')->where('id_materia', $id_materia)->fetch_first();
//var_dump($materia);exit();

// Obtener los niveles academicos por gestion
$niveles_academicos = $db->select('na.*')->from('ins_nivel_academico na')->where('na.gestion_id', $id_gestion)->order_by('na.id_nivel_academico', 'asc')->fetch();
//var_dump($niveles_academicos);exit();


//Obtenemos los niveles seleccionados por materia
$aNivel = explode(",",$materia['nivel_academico_id']);
$cadNiveles = "";
foreach ($aNivel as $k) {
	//var_dump($k);
	foreach ($niveles_academicos as $key => $value) {
		//var_dump($value['id_nivel_academico']);
		if($k == $value['id_nivel_academico']){
			$cadNiveles = $cadNiveles ." ". $value['descripcion'];
		}
	}
}
//var_dump($cadNiveles);

?>
<?php require_once show_template('header-design'); ?>
<!--link rel="stylesheet" href="assets/themes/concept/assets/vendor/cropper/dist/cropper.min.css"-->
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/cropper-mazter/css/cropper.css">
<link href="assets/themes/concept/assets/vendor/datepicker/css/datepicker.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/bootstrap-fileinput-master/css/fileinput.css" rel="stylesheet">
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">
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
										<a href="?/s-materia/listar" class="dropdown-item">Materia</a>
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
					<div class="col col-xl-4 col-lg-4 col-md-12 col-sm-6 col-xs-12">
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="row">
								<div class="col-12">
									<input type="hidden" name="id_materia" id="id_materia" value="<?= $id_materia ?>">
									<div class="list-group" id="result">
										<img src="<?= ($materia['imagen_materia'] == '') ? 'files/logos/logo-defecto-institucion.png' : 'files/'.$nombre_dominio.'/profiles/materias/' . $materia['imagen_materia'] . '.jpg'; ?>" id="avatar" name="avatar" class="" style="width:auto; height:300px;">
									</div>
									<br>
									<div class="list-group">
										<label class="list-group-item text-ellipsis">
											Subir Imagen
											<input type="file" class="sr-only" id="input" name="image" accept="image/*">
										</label>
										<!--<a href="#" class="list-group-item text-ellipsis" data-suprimir="true">-->
										<!--	<span class="glyphicon glyphicon-eye-close"></span>-->
										<!--	<span>Eliminar imagen</span>-->
										<!--</a>-->
									</div>
								</div>
							</div>
							<div class="progress">
								<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
							</div>
						</div>
					</div>

					<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12 mb-8">
						<div class="tab-regular">
							<?php if ($permiso_informacion || $permiso_presentacion) : ?>
								<ul class="nav nav-tabs nav-fill" id="myTab7" role="tablist">
									<?php if ($permiso_informacion) : ?>
										<li class="nav-item">
											<a class="nav-link active" id="home-tab-justify" data-toggle="tab" href="#home-justify" role="tab" aria-controls="home" aria-selected="true">
												<!--font style="vertical-align: inherit;">
													<font style="vertical-align: inherit;">Información</font>
												</font-->
											</a>
										</li>
									<?php endif ?>
									<?php if ($permiso_presentacion) : ?>
										<!--li class="nav-item">
											<a class="nav-link" id="profile-tab-justify" data-toggle="tab" href="#profile-justify" role="tab" aria-controls="profile" aria-selected="false">
												<font style="vertical-align: inherit;">
													<font style="vertical-align: inherit;">Presentación</font>
												</font>
											</a>
										</li-->
									<?php endif ?>
								</ul>
							<?php endif ?>
							<div class="tab-content" id="myTabContent7">
								<?php //if ($permiso_informacion) : 
								?>
								<div class="tab-pane fade show active" id="home-justify" role="tabpanel" aria-labelledby="home-tab-justify">
									<p class="lead"><strong>Información de la Materia</strong></p>
									<hr>
									<div class="table-display">

										<table class="table table-striped">
											<tr>
												<th>
													<div class="td" align="right">Nombre de la Materia:</div>
												</th>
												<td>
													<div class="td"><?= escape($materia['nombre_materia']); ?></div>
												</td>
											</tr>
											<tr>
												<th>
													<div class="td" align="right">Descripción:</div>
												</th>
												<td>
													<div class="td"><?= escape($materia['descripcion']); ?></div>
												</td>
											</tr>
											<tr>
												<th>
													<div class="td" align="right">Orden:</div>
												</th>
												<td>
													<div class="td"><?= escape($materia['orden']); ?></div>
												</td>
											</tr>
											<tr>
												<th>
													<div class="td" align="right">Niveles Academicos:</div>
												</th>
												<td>
												
													<div class="td"><?= escape($cadNiveles); ?></div>
												</td>
											</tr>
										</table>
									</div>
								</div>
								<?php //endif 
								?>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!-- ============================================================== -->
		<!-- end row -->
		<!-- ============================================================== -->


		<!-- ============================================================== -->
		<!-- modal imagen subir -->
		<!-- ============================================================== -->
		<div class="modal fade" id="modal_subir" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="modalLabel">Crop the image</h5>
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
		<!-- modal imagen subir -->
		<!-- ============================================================== -->




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

		<script src="<?= themes; ?>/concept/assets/vendor/cropper-mazter/js/cropper.js"></script>
		<script src="<?= themes; ?>/concept/assets/vendor/cropper-mazter/js/imagenes.js"></script>
		<!--script src="<?= themes; ?>/concept/assets/vendor/cropper-mazter/js/main.js"></script-->
		<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
		<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.js"></script>
		<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.es.js"></script>
		<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-fileinput-master/js/fileinput.js"></script>
		<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-fileinput-master/js/es.js"></script>
		<script src="<?= js; ?>/jquery.validate.js"></script>
		<script src="<?= js; ?>/educheck.js"></script>


		<?php require_once show_template('footer-design'); ?>

		<?php
		$directory = ""
		?>
		
		
		
		<script>
		
		    var nombre_dominio = "<?=$nombre_dominio?>"; 
		    
			$("#input-ru").fileinput({
				language: "es",
				uploadAsync: false,
				uploadUrl: "?/s-profesor/documentos",
				allowedFileExtensions: ["jpg", "png", "jpeg", "pdf", "docx", "txt"],
				minFileCount: 1,
				maxFileCount: 10,
				showUpload: true,
				showRemove: false,
				uploadExtraData: {
					'id_estudiante': $("#id_documentos").val()
				},
			});

			/*function datos_estudiante(id_estudiante){
				$.ajax({
					url: '?/s-profesor/procesos',
					type: 'POST',
					data: {'id_estudiante': id_estudiante, 'boton':'datos_estudiante'},
					dataType: 'JSON',
					success: function (resp){
						$("#id_estudiante").val(resp['datos_personales']['id_estudiante']);
						$("#nombres").val(resp['datos_personales']['nombres']);
						$("#primer_apellido").val(resp['datos_personales']['primer_apellido']);
						$("#segundo_apellido").val(resp['datos_personales']['segundo_apellido']);
						$("#tipo_documento").val(resp['datos_personales']['tipo_documento']);
						$("#numero_documento").val(resp['datos_personales']['numero_documento']);
						$("#complemento").val(resp['datos_personales']['complemento']);
						if(resp['datos_personales']['genero'] == 'v'){
							$("#genero_v").attr('checked', 'checked');
						}else{
							$("#genero_m").attr('checked', 'checked');
						}
						var imagen = $('#avatar');
						var url;
						if(resp['datos_personales']['foto']){
							url = 'files/profiles/profesores/' + resp['datos_personales']['foto'] + '.jpg';
						}else{
							url = 'assets/imgs/avatar.jpg';
						}
						
						//imagen.src = url;
						$("#avatar").attr("src",url);
						//$("#fecha_nacimiento").val(resp['datos_personales']['fecha_nacimiento']);
						$("#fecha_nacimiento").data('datepicker').selectDate(new Date(resp['datos_personales']['fecha_nacimiento']));
						$("#direccion").val(resp['datos_personales']['direccion']);
						//console.log(imagen);
					} 
				})
			}*/

			window.addEventListener('DOMContentLoaded', function() {
				var avatar = document.getElementById('avatar'); //elemento para la imagen recortada
				var image = document.getElementById('image'); //elemento para hacer el recorte
				var input = document.getElementById('input'); //elemento para cargar la imagden


				var nombre_imagen;
				var materia_id;
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

							$.ajax('?/s-materia/subir', {
								method: 'POST',
								data: formData,
								processData: false, //es importante que este el false
								contentType: false, //es importante que este el false
								success: function(respuesta) {
									avatar.src = "files/"+nombre_dominio+"/profiles/temporal/fotos/" + respuesta; //carga la imagen recortada al elemento avatar
									$("#avatar").val(respuesta);
									actualizarImagenMateria(respuesta);

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

			function actualizarImagenMateria(imagen_materia) {
				var id_materia = document.getElementById('id_materia'); //id_materia
				//console.log("Hola Maretial"+id_materia.value);	
				$.post({
					url: '?/s-materia/guardar',
					type: 'POST',
					data: {
						'accion': 'editarImagen',
						'id_materia': id_materia.value,
						'imagen_materia': imagen_materia
					},
					dataType: 'JSON',
					success: function(resp) {
						cont = 0;
						switch (resp) {
							case 1:
								alertify.success('Se modifico la imagen de la materia');
								break;
							case 2:
								alertify.error('Error al cargar la imagen de la materia');
								break;
						}

					}
				});
			}

			//window.onload = cargar_tipo_documento();
		</script>