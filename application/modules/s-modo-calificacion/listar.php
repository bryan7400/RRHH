<?php
// Obtiene la cadena csrf   
$csrf = set_csrf();
// Obtiene el id de la gestion actual 
$id_gestion=$_gestion['id_gestion']; 

// Obtiene los modo calificacion
$modos = $db->select('z.*,g.gestion')->from('cal_modo_calificacion z')
->join('ins_gestion g','g.id_gestion=z.gestion_id')->where('g.id_gestion', $id_gestion)->where('z.estado', 'A')->order_by('z.id_modo_calificacion', 'asc')->fetch();

$consulta_modo_area = $db->query("SELECT * FROM cal_area_calificacion ac INNER JOIN ins_gestion g ON ac.gestion_id = g.id_gestion WHERE ac.estado = 'A' AND ac.gestion_id =$id_gestion")->fetch();

$modo_area=json_encode($consulta_modo_area);

// Obtiene los permisos
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_editar = in_array('editar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views); 
$permiso_area_modo = in_array('crear', $_views);
$permiso_modo_area = in_array('modo-area', $_views);
 
?>
<?php require_once show_template('header-design'); ?>
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Modo de Calificación</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Configuración</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Modo de Calificación</a></li>
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
                                        <a href="#" onclick="abrir_crear();" class="dropdown-item">Crear Modo de Calificación</a>
                                        <?php endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-modo-calificacion/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Modo de Calificación</a>
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
				<?php if ($modos) : ?>
				<div class="table-responsive">
				<table id="table" class="table table-bordered table-condensed table-striped table-hover">
					<thead>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<th class="text-nowrap">Fecha inicio</th>
							<th class="text-nowrap">Fecha final</th>
							<th class="text-nowrap">Descripción</th>
							<th class="text-nowrap">Gestión</th>
							<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
							<th class="text-nowrap">Opciones</th>
							<?php endif ?>
						</tr>
					</thead>
					<tfoot>
						<tr class="active">
							<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
							<th class="text-nowrap text-middle">Fecha inicio</th>
							<th class="text-nowrap text-middle">Fecha final</th>
							<th class="text-nowrap text-middle">Descripción</th>
							<th class="text-nowrap text-middle">Gestión</th>
							<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
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
						<li>No existen modo calificación registrados en la base de datos.</li>
						<li>Para crear nuevos modo calificación debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
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
	  	<input type="hidden" id="modo_eliminar">
        <p>¿Esta seguro de eliminar el modo de calificación <span id="texto_modo"></span>?</p>
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
	//if($permiso_modo_area){
		require_once ("modo-area.php");
	//}
?>
<script>
$(function () {
	
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					$('#modal_modo').modal('toggle');
				break;
			}
		}
	});
	<?php endif ?>
	
	<?php if ($modos) : ?>
	// $('#nivel_academico').DataFilter({
	// 	filter: true,
	// 	name: 'niveles',
	// 	reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
	// });
	<?php endif ?>
});

<?php if ($permiso_editar) : ?>
function abrir_editar(contenido){
	$("#form_modo")[0].reset();
	$("#modal_modo").modal("show");
	var d = contenido.split("*");
	$("#id_modo").val(d[0]);
	$("#fecha_inicio").val(d[1]);
	$("#fecha_final").val(d[2]);
	$("#descripcion_modo").val(d[3]);
	$("#btn_nuevo").hide(); 
	$("#btn_editar").show();
}
<?php endif ?>

<?php if ($permiso_crear) : ?>
function abrir_crear(){
	$("#modal_modo").modal("show");
	$("#form_modo")[0].reset();
	$("#btn_editar").hide(); 
	$("#btn_nuevo").show();
}
<?php endif ?>

var columns=[
	{data: 'id_modo_calificacion'},
	{data: 'fecha_inicio'},
	{data: 'fecha_final'},
	{data: 'descripcion'},
	{data: 'gestion'}
];
var cont = 0; 
//function listar
var dataTable = $('#table').DataTable({
	language: dataTableTraduccion,
	searching: true,
	paging:true,
	"lengthChange": true,
	"responsive": true,
	ajax: {
		url: '?/s-modo-calificacion/busqueda', 
		dataSrc: '',
		type:'POST',
		dataType: 'json'
	},
	columns: columns,

	"columnDefs": [
			{
					"render": function (data, type, row) {
						var result = "";
						var contenido = row['id_modo_calificacion'] + "*" + row['fecha_inicio']+ "*" +  row['fecha_final']+ "*" +row['descripcion']+ "*" + row['gestion'];
                        var contenido_area=row['id_modo_calificacion'] + "*" +row['modos_calificacion'];//'1||2';
                        
                        
						result+="<?php if (false) : ?><a href='#' class='btn btn-info btn-xs' onclick = 'ver("+'"'+contenido+'"'+")'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
										"<?php if ($permiso_editar) : ?><a href='#' class='btn btn-xs btn-warning' style='color:white' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note'></span></a><?php endif ?> &nbsp"+
								"<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash'></span></a><?php endif ?> &nbsp"+
										"<?php if ($permiso_modo_area) : ?><a href='#' class='btn btn-xs btn-primary' onclick='modo_area("+'"'+contenido_area+'"'+")'><span class='icon-menu'></span></a><?php endif ?>";
						return result;
					},
					"targets": 5
			},
			{
					"render": function (data, type, row) {
					    var contenido = row['fecha_final'];
					    var aFecha = contenido.split("-");
					    return aFecha[2]+"/"+aFecha[1]+"/"+aFecha[0];
					},
					"targets": 2
			},
			{
					"render": function (data, type, row) {
						var contenido = row['fecha_inicio'];
					    var aFecha = contenido.split("-");
					    return aFecha[2]+"/"+aFecha[1]+"/"+aFecha[0];
					},
					"targets": 1
			},
			{
					"render": function (data, type, row) {
						cont = cont + 1;
						return cont;
					},
					"targets": 0
			}
	]
});

<?php if ($permiso_modo_area) : ?>
function modo_area(contenido){
	//console.log('holaaaa');
	$("#btn_editar").hide(); 
	$("#btn_nuevo").show();
	var data = <?= $modo_area;?>;
	var d = contenido.split("*");
	$("#modo_modo_area").modal("show");
	$("#id_modo_calificacion").val(d[0]);
	var modos_dato=d[1];//modos 1,3
    var modos_calificacion = modos_dato.split("|");
    
    html = "";
    for(var i=0; i < data.length;i++){
        contenido = data[i]['id_area_calificacion'] +"*"+  data[i]['descripcion'] +"*"+  data[i]['ponderado'] +"*"+ data[i]['id_gestion']+"*"+  data[i]['gestion'];
        
        html+='<tr><td>'+(i+1)+'</td><td>'+data[i]['descripcion']+'</td><td>'+data[i]['ponderado']+'</td><td>'+data[i]['gestion']+'</td><td><input type="checkbox" data-toggle="toggle" value="'+data[i]['id_area_calificacion'] +'" name="vector[]"';
         
        for(var j=0; j < modos_calificacion.length;j++){
            if(modos_calificacion[j]==data[i]['id_area_calificacion']){
                console.log('llenado checkbox:'+modos_calificacion[j]);    
               html+=' checked ';
            }
        }
        
        html += '></td></tr>';
    }
    $("#contenedor_area").html(html);
}
<?php endif ?>

<?php if ($permiso_ver) : ?>
function ver(contenido){
	var d = contenido.split("*");
	$("#modo_ver").modal("show");
	$("#gestion_").text(d[1]);
	$("#descripcion_modo_").text(d[2]);
	$("#fecha_inicio_").text(d[3]);
	$("#fecha_final_").text(d[4]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
function abrir_eliminar(contenido){
	$("#modal_eliminar").modal("show");
	var d = contenido.split("*");
	$("#modo_eliminar").val(d[0]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
$("#btn_eliminar").on('click', function(){
	//alert($("#gestion_eliminar").val())
	id_modo = $("#modo_eliminar").val();
	$.ajax({
		url: '?/s-modo-calificacion/eliminar',
		type:'POST',
		data: {'id_modo':id_modo},
		success: function(resp){
			switch(resp){
				case '1': $("#modal_eliminar").modal("hide");
							dataTable.ajax.reload();
							alertify.success('Se elimino el modo de calificación correctamente');break;
				case '2': $("#modal_eliminar").modal("hide");
							alertify.error('No se pudo eliminar ');
							break;
			}
		}
	})
})
<?php endif ?>
</script>