<?php

// Obtiene la cadena csrf 
$csrf = set_csrf();
// Obtiene el id de la gestion actual
$id_gestion=$_gestion['id_gestion'];
// Obtiene los stipoestudiante
$tipos = $db->select('z.*,g.gestion')->from('ins_tipo_estudiante z')
            ->join('ins_gestion g','g.id_gestion=z.gestion_id')
            ->where('g.id_gestion', $id_gestion)->where('z.estado', 'A')
            ->order_by('z.id_tipo_estudiante', 'asc')->fetch();

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
            <h2 class="pageheader-title">Tipo Estudiante </h2> 
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Configuración</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Tipo Estudiante</a></li>
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
                        <div class="btn-group">
                             <div class="input-group">
                                <div class="input-group-append be-addon">
                                    <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item">Seleccionar acción</a>
										<?php if ($permiso_crear) : ?>
										<div class="dropdown-divider"></div>
                                        <a href="#" onclick="abrir_crear();" class="dropdown-item">Crear Tipo</a>
                                        <?php endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-tipo-estudiante/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Tipo</a>
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
				<?php if ($tipos) : ?>
				<table id="table" class="table table-bordered table-condensed table-striped table-hover">
					<thead>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<th class="text-nowrap">Nombre tipo estudiante</th>
							<th class="text-nowrap">Descripción</th>
							<th class="text-nowrap">Monto Descuento</th>
							<th class="text-nowrap">% Descuento</th>
							<th class="text-nowrap">Fecha registro</th>
							<th class="text-nowrap">Gestión</th>
							<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
							<th class="text-nowrap">Opciones</th>
							<?php endif ?>
						</tr>
					</thead>
					<tfoot>
						<tr class="active">
							<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
							<th class="text-nowrap text-middle">Nombre tipo estudiante</th>
							<th class="text-nowrap text-middle">Descripción</th>
							<th class="text-nowrap text-middle">Monto Descuento</th>
							<th class="text-nowrap text-middle">% Descuento</th>
							<th class="text-nowrap text-middle">Fecha registro</th>
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
				<div class="alert alert-info">
					<strong>Atención!</strong>
					<ul>
						<li>No existen tipo estudiante registrados en la base de datos.</li>
						<li>Para crear nuevos tipo estudiante debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
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
<!-- ============================================================== -->
<!-- end row -->
<!-- ============================================================== --> 
<!--modal para eliminar-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_eliminar">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
	  	<input type="hidden" id="tipo_eliminar">
        <p>¿Esta seguro de eliminar el tipo de estudiante <span id="texto_tipo"></span>?</p>
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
<!--script src="<?= $ruta?>/s-gestion-escolar.js"></script-->
<?php require_once show_template('footer-design'); ?>
<?php 
	if($permiso_editar){
		require_once ("editar.php");
	}
	if($permiso_ver){
		require_once ("ver.php");
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
					$('#modal_tipo').modal('toggle');
				break;
			}
		}
	});
	<?php endif ?>
	
	<?php if ($permiso_eliminar) : ?>
	// $('[data-eliminar]').on('click', function (e) {
	// 	e.preventDefault();
	// 	var href = $(this).attr('href');
	// 	var csrf = '<?= $csrf; ?>';
	// 	bootbox.confirm('¿Está seguro que desea eliminar el gestion?', function (result) {
	// 		if (result) {
	// 			$.request(href, csrf);
	// 		}
	// 	});
	// });
	<?php endif ?>
	
	<?php if ($tipos) : ?>
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
	$("#form_tipo")[0].reset();
	$("#modal_tipo").modal("show");
	var d = contenido.split("*");
	$("#id_tipo").val(d[0]);
	$("#nombre_tipo").val(d[1]);
	$("#descripcion_tipo").val(d[2]);
	$("#monto_beca").val(d[3]);
	$("#descuento").val(d[4]);
	$("#btn_nuevo").hide(); 
	$("#btn_editar").show(); 
}
<?php endif ?>

<?php if ($permiso_crear) : ?>
function abrir_crear(){
	$("#modal_tipo").modal("show");
	$("#form_tipo")[0].reset();
	$("#btn_editar").hide(); 
	$("#btn_nuevo").show();
}
<?php endif ?>

var columns=[
	{data: 'id_tipo_estudiante'},
	{data: 'nombre_tipo_estudiante'},
	{data: 'descripcion'},
	{data: 'monto_beca'},
	{data: 'descuento_beca'},
	{data: 'fecha_registro'},
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
		url: '?/s-tipo-estudiante/busqueda',
		dataSrc: '',
		type:'POST',
		dataType: 'json'
	},
	columns: columns,

	"columnDefs": [
			{
					"render": function (data, type, row) {
						var result = "";
						var contenido = row['id_tipo_estudiante'] + "*" + row['nombre_tipo_estudiante']+ "*" + row['descripcion']+ "*" + row['monto_beca']+ "*" + row['descuento_beca']+ "*" + row['fecha_registro']+ "*" + row['gestion'];
						result+="<?php if ($permiso_ver) : ?><a href='#' class='btn btn-info btn-xs' onclick = 'ver("+'"'+contenido+'"'+")'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
								"<?php if ($permiso_editar) : ?><a href='#' class='btn btn-warning btn-xs' style='color:white' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note'></span></a><?php endif ?> &nbsp" +
								"<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash'></span></a><?php endif ?>";
						return result;    
					},
					"targets": 7
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
	$("#tipo_ver").modal("show");
	$("#nombre_tipo_ver").text(d[1]);
	$("#descripcion_tipo_ver").text(d[2]);
	$("#monto_beca_ver").text(d[3]);
	$("#descuento").text(d[4]);
	$("#gestion_tipo_ver").text(d[5]);
}

<?php endif ?>

<?php if ($permiso_eliminar) : ?>
function abrir_eliminar(contenido){
	$("#modal_eliminar").modal("show");
	var d = contenido.split("*");
	$("#tipo_eliminar").val(d[0]);
	$("#texto_tipo").text(d[1]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
$("#btn_eliminar").on('click', function(){
	//alert($("#gestion_eliminar").val())
	id_tipo_estudiante = $("#tipo_eliminar").val();
	//console.log(id_tipo_estudiante+'ggggggggggggggg');
	$.ajax({
		url: '?/s-tipo-estudiante/eliminar',
		type:'POST',
		data: {'id_tipo_estudiante':id_tipo_estudiante},
		success: function(resp){
			//alert(resp)
			switch(resp){
				case '1': $("#modal_eliminar").modal("hide");
							dataTable.ajax.reload();
							alertify.success('Se elimino tipo de estudiante correctamente');break;
				case '2': $("#modal_eliminar").modal("hide");
							alertify.error('No se pudo eliminar ');
							break;
			}
		}
	})
})
<?php endif ?>
</script>