<?php 
// var_dump("Hola Luis");
// echo ("<hr>");
// exit();
$fecha_a = "2020-03-15";

		// echo "<pre>";
		// var_dump($marcacion);
		// echo "</pre>";
	
?>
<?php require_once show_template('header-design'); ?>
<!--link rel="stylesheet" href="assets/themes/concept/assets/vendor/cropper/dist/cropper.min.css"-->
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/cropper-mazter/css/cropper.css">
<link href="assets/themes/concept/assets/vendor/datepicker/css/datepicker.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/bootstrap-fileinput-master/css/fileinput.css" rel="stylesheet">
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">
<link rel="stylesheet" href="<?= libraries; ?>/fileinput/css/fileinput.min.css">


<div id="loading-screen" style="display:none">
    <img src="<?= imgs; ?>/spinning-circles.svg">
</div>
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="page-header">			
				<h2 class="pageheader-title" data-idtutor="">Importar Tarjeta de control</h2>
				<p class="pageheader-text"></p>
				<div class="page-breadcrumb">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">RRHH</a></li>
							<li class="breadcrumb-item active" aria-current="page">Importar Tarjeta de control</li>
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
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="card">
			<div class="card-header">
				<h1 class="text-center">Importar archivo de asistencia</h1><br>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="form-group col-md-6">
						<input type="number" class="form-control" name="nro_hoja" id="nro_hoja" placeholder="Número de hoja">
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-12">
						<input type="file" name="archivo" id="archivo" multiple="true">
					</div>
				</div>
			</div>	
		</div>
	</div>
</div>	


<div class="modal fade" id="modal-confirmacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" data-hidden="true">
					&times;
				</button>
				<h4>Confirmación</h4>
			</div>
			<div class="modal-body">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="radio">
								<input type="radio" name="optionsRadios" id="optionsRadios1" value="no" checked>
								<label for="optionsRadios1">
									Eliminar solo el archivo.
								</label>
							</div>
							<div class="radio">
								<input type="radio" name="optionsRadios" id="optionsRadios2" value="si">
								<label for="optionsRadios2">
									Eliminar con sus asistencias ya registradas en el sistema.
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-boton-aceptar="true">
					Aceptar
				</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">
					cerrar
				</button>
			</div>				
		</div>
	</div>
</div>


<?php
$directory = files."/archivos/";
$images = glob($directory . "*.*");
?>

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

<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<script src="<?= js; ?>/jquery.dataFilters.min.js"></script>

<?php require_once show_template('footer-design'); ?>

<script src="<?= libraries; ?>/fileinput/js/fileinput.min.js"></script>
<script src="<?= libraries; ?>/fileinput/js/locales/es.js"></script>
<script>
	$(function(){
		 var cnt = $('#archivo').fileinput({
			language: 'es',	
			theme: 'fa',
            uploadUrl: "./?/rrhh-importar-tarjeta/importar",
            allowedFileExtensions: ['xls', 'xlsx'],
            overwriteInitial: false,
            maxFileSize:2000,
            maxFilesNum: 10,
            initialPreview: [
            	<?php foreach($images as $image){?>
				"<img src='<?= $image; ?>' height='120px' class='file-preview-image'>",
				<?php } ?>
			],
			initialPreviewConfig: [<?php foreach($images as $image){ $infoImagenes=explode("/",$image);?>
			{caption: "<?php echo $infoImagenes[4];?>",  height: "120px", url: "./?/rrhh-importar-tarjeta/borrar", key:"<?php echo $infoImagenes[4];?>"},
			<?php } ?>],
            uploadExtraData: function(){
			    return {
			        nro_hoja: $("#nro_hoja").val()
			    };
			},
            slugCallback: function (filename) {
                return filename.replace('(', '_').replace(']', '_');
            }
		}).on("filebeforedelete", function(event, data) {
			$("[data-boton-aceptar]").data('data-boton-aceptar',data);
			$('#modal-confirmacion').modal('show'); 
        	return true;
    	}).on('fileuploaded', function(event, data, id, index) {
    		//location.reload();
    	});
    	
		$("[data-boton-aceptar]").on('click',function(){
			var valor = $('input[name=optionsRadios]:checked').val();
			var archivo = $(this).data('data-boton-aceptar');
			$.ajax({
	            url: '?/rrhh-importar-tarjeta/borrar',
	            type: 'POST',
	            dataType: 'json',
	            data:{key:archivo,valor:valor}
	        })
	        .done(function(resultado) {
	        	//location.reload();
	        }).fail( function() {
			    alert( 'Error!!' );
			});
		});

		/*$("#archivo button[title='Eliminar archivo']").on('click',function(e){
			e.preventDefault();
			console.log("eliminar");
		});*/
	});	
</script>
