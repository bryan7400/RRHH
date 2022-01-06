<?php

// Obtiene la cadena csrf
$csrf = set_csrf();  
$gestion=$_gestion['id_gestion'];
// Obtiene los sareacalificacion
$areas = $db->select('z.*,g.gestion')->from('cal_area_calificacion z')->join('ins_gestion g','g.id_gestion=z.gestion_id')
            ->where('g.id_gestion',$gestion)->order_by('z.id_area_calificacion', 'asc')->fetch();
//var_dump($areas);exit();
// Obtiene los permisos 
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_editar = in_array('editar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views); 

?>
<?php require_once show_template('header-design'); ?>
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Clientes</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Configuración</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Clientes</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Listar</li>
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
                        <div class="text-label hidden-xs">Seleccione:</div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
                        <div class="btn-group ">
                             <div class="input-group">
                                <div class="input-group-append be-addon" >
                                    <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item">Seleccionar acción</a>
										<?php if ($permiso_crear) : ?>
										<div class="dropdown-divider"></div>
                                        <a href="#" onclick="abrir_crear();" class="dropdown-item">Crear área de calificación</a>
                                         <a href="#" onclick="abrir_ordenar_areas();" class="dropdown-item">Ordenar áreas de calificación</a>
                                        <?php endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-area-calificacion/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir área de calificación</a>
										<?php endif ?>
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
				<div class="table-responsive">
				<?php if ($areas) : ?>
				<table id="table" class="table table-bordered table-condensed table-striped table-hover">
					<thead>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<th class="text-nowrap">Descripción</th>
							<th class="text-nowrap">Obtención de Nota</th>
							<th class="text-nowrap">Ponderado</th>
							<th class="text-nowrap">Gestión</th>
							<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
							<th class="text-nowrap">Opciones</th>
							<?php endif ?>
						</tr>
					</thead>
					<tfoot>
						<tr class="active">
							<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
							<th class="text-nowrap text-middle">Descripción</th>
							<th class="text-nowrap">Obtención de Nota</th>
                            <th class="text-nowrap">Ponderado</th>
							<th class="text-nowrap text-middle">Gestión</th>
							<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
							<th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
							<?php endif ?>
						</tr>
					</tfoot>
					<tbody>
					</tbody>
				</table>
				<?php else : ?>
				</div>
				<div class="alert alert-info">
					<strong>Atención!</strong>
					<ul>
						<li>No existen área de calificación registrados en la base de datos.</li>
						<li>Para crear nuevos área de calificación debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
					</ul>
				</div>
				<?php endif ?>
                <!-- ============================================================== -->
                <!-- end datos -->
                <!-- ============================================================== -->
				</div>
			</div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- end row -->
<!-- ============================================================== --> 
<!--modal para eliminar-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_eliminar">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
	  	<input type="hidden" id="area_eliminar">
        <p>¿Esta seguro de eliminar el área de calificación <span id="texto_area"></span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn_eliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<!-- <script src="<?= js; ?>/jquery.dataFilters.min.js"></script> -->
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
	if($permiso_ver){
		require_once ("ver.php");
	}
	require_once ("modal-ordenar-areas.php");
?>
<script>
$(function () {
	
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					//window.location = '?/gestiones/crear';
					$('#modal_area').modal('toggle');
				break;
			}
		}
	});
	<?php endif ?>
	
	<?php if ($permiso_eliminar) : ?>
	$('[data-eliminar]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var csrf = '<?= $csrf; ?>';
		bootbox.confirm('Está seguro que desea eliminar área de calificación?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
	 
	<?php if ($areas) : ?>
	// $('#nivel_academico').DataFilter({
	// 	filter: true,
	// 	name: 'niveles',
	// 	reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
	// });
	<?php endif ?>
	//carga toda la lista de grupo proyecto con DataTable
});

<?php if ($permiso_editar) : ?>
function abrir_editar(contenido){
	$("#form_area")[0].reset();
	$("#modal_area").modal("show");
	var d = contenido.split("*");
	$("#id_area").val(d[0]);
	$("#descripcion_area").val(d[1]);
	$("#obtencion_nota").val(d[2]);
	$("#ponderado_area").val(d[3]);
	$("#btn_nuevo").hide(); 
	$("#btn_editar").show();
}
<?php endif ?>


function abrir_ordenar_areas(){
    $("#modal_ordenar_area").modal("show");
}

<?php if ($permiso_crear) : ?>
function abrir_crear(){
	$("#modal_area").modal("show");
	$("#form_area")[0].reset();
	$("#btn_editar").hide(); 
	$("#btn_nuevo").show();
}
<?php endif ?>

var columns=[
	{data: 'id_area_calificacion'},
	{data: 'descripcion'},
	{data: 'obtencion_nota'},
	{data: 'ponderado'},
	{data: 'gestion'}
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
		url: '?/s-area-calificacion/busqueda',
		dataSrc: '',
		type:'POST',
		dataType: 'json'
	},
	columns: columns,

	"columnDefs": [
			{
					"render": function (data, type, row) {
						var result = "";
						var contenido = row['id_area_calificacion'] + "*" + row['descripcion']+ "*" + row['obtencion_nota'] + "*" + row['ponderado']+ "*" +  row['gestion'];
						result+="<?php if (false) : ?><a href='#' class='btn btn-info btn-xs' onclick = 'ver("+'"'+contenido+'"'+")'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
								"<?php if ($permiso_editar) : ?><a href='#' class='btn btn-warning btn-xs' style='color:white' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note'></span></a><?php endif ?> &nbsp" +
								"<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash'></span></a><?php endif ?>";
						return result;
					},
					"targets": 5
			},
			{
					"render": function (data, type, row) {
						var contenido = row['obtencion_nota'];
						
						var html = "";
						
						if(contenido === "D" ){
						    html += "REVISADO SOLO POR EL DOCENTE";
						}else if(contenido === "E"){
						    html += "SERA REVISADO CUANDO EL ESTUDIANTE ENVIE UNA RESPUESTA";
						}else if(contenido === "SE"){
						    html += "EL ESTUDIANTE SE AUTOCALIFICA";
						}
						
						return html;
					},
					"targets": 2
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
//} 
<?php if ($permiso_ver) : ?>
function ver(contenido){
	var d = contenido.split("*");
	$("#area_ver").modal("show");
	$("#descripcion_ver").text(d[1]);
	$("#ponderado_ver").text(d[2]);
	$("#gestion_ver").text(d[3]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
function abrir_eliminar(contenido){
	$("#modal_eliminar").modal("show");
	var d = contenido.split("*");
	$("#area_eliminar").val(d[0]);
	$("#texto_area").text(d[1]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
$("#btn_eliminar").on('click', function(){
	id_area_calificacion = $("#area_eliminar").val();
	$.ajax({
		url: '?/s-area-calificacion/eliminar',
		type:'POST',
		data: {'id_area_calificacion':id_area_calificacion},
		success: function(resp){
			//alert(resp)
			switch(resp){
				case '1': $("#modal_eliminar").modal("hide");
							dataTable.ajax.reload();
							alertify.success('Se elimino el área de calificación correctamente');break;
				case '2': $("#modal_eliminar").modal("hide");
							alertify.error('No se pudo eliminar ');
							break;
			}
		}
	})
})
<?php endif ?>
</script>