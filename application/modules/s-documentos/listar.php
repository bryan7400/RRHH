<?php 
// Obtiene la cadena csrf
$csrf = set_csrf(); 

// Obtiene el id de la gestion actual
$id_gestion=$_gestion['id_gestion'];

// Obtiene los paralelo
//$materia = $db->select('z.*')->from('pro_materia z')->order_by('z.id_materia', 'asc')->fetch();
$documentos = $db->select('z.*')->from('ins_tipo_documentos z')->order_by('z.nombre', 'asc')->fetch();

// Obtener los niveles academicos por gestion
$niveles_academicos = $db->select('na.*')->from('ins_nivel_academico na')->where('na.gestion_id',$id_gestion)->order_by('na.id_nivel_academico', 'asc')->fetch();
//var_dump($niveles_academicos);

// Obtiene los permisos
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_editar = in_array('editar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('', $_views);
//mios
$permiso_informacion = in_array('informacion', $_views);
$permiso_presentacion = in_array('presentacion', $_views);
?>
<?php require_once show_template('header-design'); ?>
 
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"> 
        <div class="page-header">
            <h2 class="pageheader-title">Tipo de documento</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Registros Iniciales</a></li>
						<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Documentos</a></li>
                        <!--li class="breadcrumb-item active" aria-current="page">Listar</li-->
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
										<?php if ($permiso_crear) : ?>
										<div class="dropdown-divider"></div>
										<a href="#" onclick="abrir_crear();" class="dropdown-item">Crear Documento</a>
										<?php endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-materia/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Materia</a>
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
		
				<?php if ($message = get_notification()) : ?>
				<div class="alert alert-<?= $message['type']; ?>">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong><?= $message['title']; ?></strong>
					<p><?= $message['content']; ?></p>
				</div>
				<?php endif ?>

				<?php if ($documentos) : ?>
				<div class="table-responsive">
				<table id="table" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
					<thead>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<!--<th class="text-nowrap">Imagen</th>-->
							<th class="text-nowrap">Nombre documento</th>
							<th class="text-nowrap">Descripción</th>
							<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
							<th class="text-nowrap">Opciones</th>
							<?php endif ?>
						</tr>
					</thead>
					<tfoot>
						<tr class="active">
							<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
							<!--<th class="text-nowrap">Imagen</th>-->
							<th class="text-nowrap text-middle">Nombre documento</th>
							<th class="text-nowrap text-middle">Descripción</th>
							<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
							<th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
							<?php endif ?>
						</tr>
					</tfoot>
					<tbody>
					</tbody>
				</table>
				</div>
				<?php else : ?>

				<div class="alert alert-info">
					<strong>Atención!</strong>
					<ul>
						<li>No existen materias registrados en la base de datos.</li>
						<li>Para crear nuevos materias debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
					</ul>
				</div>
				<?php endif ?>
			</div>
			<!-- ============================================================== -->
			<!-- end datos -->
			<!-- ============================================================== -->
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- row -->
<!-- ============================================================== -->

<!--modal para eliminar-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_eliminar">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
	  	<input type="hidden" id="materia_eliminar">
        <p>¿Esta seguro de eliminar el documento <span id="texto_materia"></span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn_eliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div><!--modal para ver-->

 <div class="modal fade" tabindex="-1" role="dialog" id="modal_ver2">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
      <div class="tab-regular">
				<?php if ($permiso_informacion || $permiso_presentacion) : ?>
				<ul class="nav nav-tabs nav-fill" id="myTab7" role="tablist">
				<?php if ($permiso_informacion) : ?>
					<li class="nav-item">
						<a class="nav-link active" id="home-tab-justify" data-toggle="tab" href="#home-justify" role="tab" aria-controls="home" aria-selected="true">
						</a>
					</li>
						<?php endif ?>
						<?php if ($permiso_presentacion) : ?>
							 
						<?php endif ?>
				</ul>
					<?php endif ?>
					<div class="tab-content" id="myTabContent7">
					<?php //if ($permiso_informacion) : ?>
					<div class="tab-pane fade show active" id="home-justify" role="tabpanel" aria-labelledby="home-tab-justify">
					<p class="lead"><strong>Información del documento</strong></p><hr>
					<div class="table-display">

					<table class="table table-striped">
					<tr>
					<th>
					<div class="td" align="right">Nombre de la Documento:</div>
					</th>
						<td>
							<div class="td nombre_Mat" id="nombre_doc_ver">Nombre1</div>
						</td>
						</tr>
						<tr>
							<th>
								<div class="td" align="right">Descripción:</div>
							</th>
							<td>
								<div class="td nombre_Desc" id="descripcion_ver">desc</div>
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
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button>
        <!--<button type="button" class="btn btn-primary" id="btn_eliminar">Eliminar</button>-->
      </div>
    </div>
  </div>
</div>

<!--script src="<?= js; ?>/jquery.dataTables.min.js"></script>
<script src="<?= js; ?>/dataTables.bootstrap.min.js"></script-->
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<!--script src="<?= js; ?>/jquery.dataFilters.min.js"></script-->
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/educheck.js"></script>

<?php require_once show_template('footer-design'); ?>
<?php 
	if($permiso_editar){
		require_once ("editar.php");
	}
?>
<script>
$(function () {
	
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					$('#modal_materia').modal('toggle');
				break;
			}
		}
	});
	<?php endif ?>
	
	/*<?php if ($materia) : ?>
	$('#table').DataFilter({
		filter: true,
		name: 'gestion',
		reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
	});
	<?php endif ?>*/
	
});

<?php if ($permiso_editar) : ?>
function abrir_editar(contenido){
	//console.log(contenido);
	$("#form_materia")[0].reset();
	$("#modal_materia").modal("show");
	//$("#titulo_materia").text("Editar ");
	$('#table tbody').off();
	var d = contenido.split("*");
	//cargamos con los check seleccionados
	/*if(d[3] != ""){
		var aCheck = d[3].split(",");
		for(var i = 0 ; i < aCheck.length ; i++){
			console.log(aCheck[i]);
			$('#'+aCheck[i])[0].checked = true;
		}
	}*/	
	$("#id_materia").val(d[0]);
	$("#nombre_materia").val(d[1]);
	$("#descripcion").val(d[2]);
	$("#btn_nuevo").hide();
	$("#btn_editar").show();
}
<?php endif ?>

<?php if ($permiso_crear) : ?>
function abrir_crear(){
	$("#modal_materia").modal("show");
	$("#form_materia")[0].reset();
	$("#titulo_materia").text("Crear ");
	$("#btn_editar").hide();
	$("#btn_nuevo").show();
}
<?php endif ?>
    
var columns=[
	{data: 'id_tipo_documento'},
/*	{data: 'imagen_materia'},*/
	{data: 'nombre'},
	{data: 'descripcion'}
	
];
var cont = 0;
var dataTable = $('#table').DataTable({
	language: dataTableTraduccion,
	searching: true,
	paging:true,
	"lengthChange": true,
	"responsive": true,
	ajax: {
		url: '?/s-documentos/busqueda',
		dataSrc: '',		
		type:'POST',
		dataType: 'json'
	},
	columns: columns,
	"columnDefs": [
			{
					"render": function (data, type, row) {
						var result = "";
					
						var contenido = row['id_tipo_documento'] + "*" + row['nombre']+ "*" + row['descripcion'];
                        
						//result+="<?php if ($permiso_ver) : ?><a href='?/s-materia/ver/" + row['id_tipo_documento'] + "' class='btn btn-info btn-xs'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
						//result+="<?php if ($permiso_ver) : ?><a href='#'  onclick='ver("+'"'+contenido+'"'+")' class='btn btn-info btn-xs'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
						result+="<?php if ($permiso_editar) : ?><a href='#' class='btn btn-warning btn-xs' style='color:white' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note'></span></a><?php endif ?> &nbsp" +
								"<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash'></span></a><?php endif ?>";
						return result;
					},
					"targets": 3
			},
			/*{
					"render": function (data, type, row) {
						var imagen = "";
						//var foto = "imgs . '/avatar.jpg'";
						if(row['imagen_materia'] == ""){
							foto = "assets/imgs/avatar.jpg";
						}else{
							foto = "files/profiles/materias/" + row['imagen_materia'] + ".jpg";
							//foto = "files/profiles/materias/" + row['imagen_materia'];
						}
						imagen += "<img src='"+ foto +"' class='img-rounded cursor-pointer' data-toggle='modal' data-target='#modal_mostrar' data-modal-size='modal-md' data-modal-title='Imagen' width='64' height='64'>";
						return imagen;
					},
					"targets": 1
			},*/
			{
					"render": function (data, type, row) {
						cont = cont +1;
						return cont;
					},
					"targets": 0
			}
	]
});

<?php if ($permiso_ver) : ?>
function ver(contenido){
	var d = contenido.split("*");
	$("#modal_ver2").modal("show");
	$("#nombre_doc_ver").text(d[1]);
	$("#descripcion_ver").text(d[2]);
}
<?php endif ?> 

<?php if ($permiso_eliminar) : ?>
function abrir_eliminar(contenido){
	$("#modal_eliminar").modal("show");
	var d = contenido.split("*");
	$("#materia_eliminar").val(d[0]);
	$("#texto_materia").text(d[1]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
$("#btn_eliminar").on('click', function(){
	//alert($("#gestion_eliminar").val())
	id_materia = $("#materia_eliminar").val();
	$.ajax({
		url: '?/s-documentos/eliminar',
		type:'POST',
		data: {'id_materia':id_materia},
		success: function(resp){
			//alert(resp)
			switch(resp){
				case '1': $("#modal_eliminar").modal("hide");
							dataTable.ajax.reload();
							alertify.success('Se elimino la documento correctamente');break;
				case '2': $("#modal_eliminar").modal("hide");
							alertify.error('No se pudo eliminar ');
							break;
			}
		}
	})
})
<?php endif ?>
</script>
