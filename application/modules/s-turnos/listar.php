<?php 
// Obtiene la cadena csrf
$csrf = set_csrf(); 

// Obtiene el id de la gestion actual
$id_gestion=$_gestion['id_gestion'];


// Obtener los turnos de la gestion

$sql_turnos = "SELECT * FROM ins_turno WHERE gestion_id = $id_gestion AND estado = 'A' ORDER BY orden ASC";
//var_dump($sql_turnos);exit();

$turnos = $db->query($sql_turnos)->fetch();


// Obtiene los permisos
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_editar = in_array('editar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);
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
            <h2 class="pageheader-title">Turnos</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Configuración</a></li>
						<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Turno</a></li>
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
										<a href="#" onclick="abrir_crear();" class="dropdown-item">Crear Turno</a>
										<?php endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-turnos/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Turno</a>
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

				<?php if ($turnos) : ?>
				<div class="table-responsive">
				<table id="table" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
					<thead>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<!--<th class="text-nowrap">Imagen</th>-->
							<th class="text-nowrap">Nombre turno</th>
							<th class="text-nowrap">Descripción</th>
							<th class="text-nowrap">Hora inicio</th>
							<th class="text-nowrap">Hora final</th>
							<th class="text-nowrap">Orden</th>
							<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
							<th class="text-nowrap">Opciones</th>
							<?php endif ?>
						</tr>
					</thead>
					<tfoot>
						<tr class="active">
							<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
							<!--<th class="text-nowrap">Imagen</th>-->
							<th class="text-nowrap text-middle">Nombre turno</th>
							<th class="text-nowrap text-middle">Descripción</th>
							<th class="text-nowrap text-middle">Hora inicio</th>
							<th class="text-nowrap text-middle">Hora final</th>
							<th class="text-nowrap text-middle">Orden</th>
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
						<li>No existen turnos registrados en la base de datos.</li>
						<li>Para crear nuevos turnos debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
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
	  	<input type="hidden" id="turno_eliminar">
        <p>¿Esta seguro de eliminar el turno <span id="texto_turno"></span>?</p>
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
					<p class="lead"><strong>Información del turno</strong></p><hr>
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
						<tr>
							<th>
								<div class="td" align="right">Hora Inicio:</div>
							</th>
							<td>
								<div class="td hora_inicio" id="hora_inicio_ver">00</div>
							</td>
						</tr>
						<tr>
							<th>
								<div class="td" align="right">Hora Final:</div>
							</th>
							<td>
								<div class="td hora_final" id="hora_final_ver">00</div>
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

});

<?php if ($permiso_editar) : ?>
function abrir_editar(contenido){
	//console.log(contenido);
	$("#form_materia")[0].reset();
	$("#modal_materia").modal("show");
	$("#titulo_turno").text("Editar ");
	$('#table tbody').off();
	var d = contenido.split("*");
	$("#id_turno").val(d[0]);
	$("#nombre_turno").val(d[1]);
	$("#descripcion").val(d[2]);
	$("#hora_inicio").val(d[3]);
	$("#hora_final").val(d[4]);
    $("#orden").val(d[5]);    
	$("#btn_nuevo").hide();
	$("#btn_editar").show();
}
<?php endif ?>

<?php if ($permiso_crear) : ?>
function abrir_crear(){
	$("#modal_materia").modal("show");
	$("#id_turno").val(0);
	$("#form_materia")[0].reset();
	$("#titulo_turno").text("Crear ");
	$("#btn_editar").hide();
	$("#btn_nuevo").show();
}
<?php endif ?>
    
var columns=[
	{data: 'id_turno'},
	{data: 'nombre_turno'},
	{data: 'descripcion'},
	{data: 'hora_inicio'},
	{data: 'hora_final'},
    {data: 'orden'}	
];
var cont = 0;
var dataTable = $('#table').DataTable({
	language: dataTableTraduccion,
	searching: true,
	paging:true,
	"lengthChange": true,
	"responsive": true,
	ajax: {
		url: '?/s-turnos/busqueda',
		dataSrc: '',		
		type:'POST',
		dataType: 'json'
	},
	columns: columns,
	"columnDefs": [
			{
					"render": function (data, type, row) {
						var result = "";
						//var contenido = row['id_materia'] + "*" + row['nombre_materia']+ "*" + row['descripcion']+ "*"+ row['nivel_academico_id'] +"*"+ row['imagen_materia'];
						//result+="<?php// if ($permiso_ver) : ?><a href='?/s-materia/ver/" + row['id_materia'] + "' class='btn btn-info btn-xs'><span class='icon-eye'></span></a><?php //endif ?> &nbsp"+
						var contenido = row['id_turno'] + "*" + row['nombre_turno']+ "*" + row['descripcion']+ "*" + row['hora_inicio']+ "*" + row['hora_final']+ "*" + row['orden'];
                        
						//result+="<?php if ($permiso_ver) : ?><a href='?/s-materia/ver/" + row['id_tipo_documento'] + "' class='btn btn-info btn-xs'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
						//result+="<?php if ($permiso_ver) : ?><a href='#'  onclick='ver("+'"'+contenido+'"'+")' class='btn btn-info btn-xs'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
						result+="<?php if ($permiso_editar) : ?><a href='#' class='btn btn-warning btn-xs' style='color:white' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note'></span></a><?php endif ?> &nbsp" +
								"<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash'></span></a><?php endif ?>";
						return result;
					},
					"targets": 6
			},
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
	$("#hora_inicio_ver").text(d[3]);
	$("#hora_final_ver").text(d[4]);
}
<?php endif ?> 

<?php if ($permiso_eliminar) : ?>
    
function abrir_eliminar(contenido){
	$("#modal_eliminar").modal("show");
	var d = contenido.split("*");
	$("#turno_eliminar").val(d[0]);
	$("#texto_turno").text(d[1]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
    
$("#btn_eliminar").on('click', function(){
	//alert($("#gestion_eliminar").val())
	id_turno = $("#turno_eliminar").val();
	$.ajax({
		url: '?/s-turnos/eliminar',
		type:'POST',
		data: {'id_turno':id_turno},
		success: function(resp){
			//alert(resp)
			switch(resp){
				case '1': $("#modal_eliminar").modal("hide");
							dataTable.ajax.reload();
							alertify.success('Se elimino el turno correctamente');break;
				case '2': $("#modal_eliminar").modal("hide");
							alertify.error('No se pudo eliminar ');
							break;
			}
		}
	})
})
<?php endif ?>
</script>
