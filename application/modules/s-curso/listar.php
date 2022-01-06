<?php  

// Obtiene la cadena csrf
$csrf = set_csrf();  
$id_gestion = $_gestion['id_gestion'];


$nivel_academico = $db->query("SELECT * FROM ins_nivel_academico ni WHERE ni.estado='A' and ni.gestion_id = $id_gestion")->fetch();


$cursos = $db->select('z.*, n.nombre_nivel')->from('ins_aula z')->join('ins_nivel_academico n','z.nivel_academico_id=n.id_nivel_academico')->where('z.gestion_id',$id_gestion)->where('z.estado','A')->order_by('n.id_nivel_academico', 'asc')->fetch();
//var_dump($nivel_academico);exit();
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
            <h2 class="pageheader-title">Grado </h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Configuración</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Grado</a></li>
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
										<a href="#" onclick="abrir_crear();" class="dropdown-item">Crear Grado</a>
										<?php endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-curso/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Curso</a>
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
				
				<div class="table-responsive">
				<?php if ($nivel_academico) : ?>
				<table id="table" class="table table-bordered table-condensed table-striped table-hover">
					<thead>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<th class="text-nowrap">Nombre grado</th>
							<th class="text-nowrap">Descripción</th>
							<th class="text-nowrap">Nivel académico</th>
							<th class="text-nowrap">Orden</th>
							<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
							<th class="text-nowrap">Opciones</th>
							<?php endif ?>
						</tr>
					</thead>
					<tfoot>
						<tr class="active">
							<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
							<th class="text-nowrap text-middle">Nombre grado</th>
							<th class="text-nowrap text-middle">descripción</th>
							<th class="text-nowrap text-middle">Nivel académico</th>
							<th class="text-nowrap text-middle">Orden</th>
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
						<li>No existen grados registrados en la base de datos.</li>
						<li>Para crear nuevos grados debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
					</ul>
				</div>
				<?php endif ?>
				</div>
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
	  	<input type="hidden" id="curso_eliminar">
        <p>¿Esta seguro de eliminar el grado <span id="texto_curso"></span>?</p>
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
					window.location = '?/gestiones/crear';
				break;
			}
		}
	});
	<?php endif ?>
	
	<?php if ($cursos) : ?>
	// $('#table').DataFilter({
	// 	filter: true,
	// 	name: 'gestion',
	// 	reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
	// });
	<?php endif ?>
	
});

<?php if ($permiso_editar) : ?>
function abrir_editar(contenido){
	$("#form_curso")[0].reset();
	$("#titulo_curso").text("Editar ");
	$("#modal_curso").modal("show");
	var d = contenido.split("*");
	$("#id_aula").val(d[0]);
	$("#nombre_aula").val(d[1]);
	$("#descripcion").val(d[2]);
	$("#nivel_academico").val(d[3]);
	$("#orden").val(d[5]);
	$("#btn_nuevo").hide();
	$("#btn_editar").show();
}
<?php endif ?>

<?php if ($permiso_crear) : ?>
function abrir_crear(){
	$("#modal_curso").modal("show");
	$("#form_curso")[0].reset();
	$("#titulo_curso").text("Crear ");
	$("#id_aula").val(0);
	$("#btn_editar").hide();
	$("#btn_nuevo").show();
}
<?php endif ?>
var columns=[
	{data: 'id_aula'},
	{data: 'nombre_aula'},
	{data: 'descripcion'},
	{data: 'nombre_nivel'},
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
		url: '?/s-curso/busqueda',
		dataSrc: '',		
		type:'POST',
		dataType: 'json'
	},
	columns: columns,
	"columnDefs": [
			{
					"render": function (data, type, row) {
						var result = "";
						var contenido = row['id_aula'] + "*" + row['nombre_aula']+ "*" + row['descripcion']+ "*" + row['id_nivel_academico']+ "*" + row['nombre_nivel']+ "*"+ row['orden'];
						result+="<?php if ($permiso_editar) : ?><a href='#' class='btn btn-warning btn-xs' style='color:white' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note'></span></a><?php endif ?> &nbsp" +
								"<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash'></span></a><?php endif ?>";
						return result;
					},
					"targets": 5
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
	$("#curso_ver").modal("show");
	$("#nombre_aula_ver").text(d[1]);
	$("#descripcion_ver").text(d[2]);
	$("#nombre_nivel_ver").text(d[3]);
	$("#orden_ver").text(d[5]);
}
<?php endif ?> 

<?php if ($permiso_eliminar) : ?>
function abrir_eliminar(contenido){
	$("#modal_eliminar").modal("show");
	var d = contenido.split("*");
	$("#curso_eliminar").val(d[0]);
	$("#texto_curso").text(d[1]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
$("#btn_eliminar").on('click', function(){
	//alert($("#gestion_eliminar").val())
	id_aula = $("#curso_eliminar").val();
	$.ajax({
		url: '?/s-curso/eliminar',
		type:'POST',
		data: {'id_aula':id_aula},
		success: function(resp){
			//alert(resp)
			switch(resp){
				case '1': $("#modal_eliminar").modal("hide");
							dataTable.ajax.reload();
							alertify.success('Se elimino el curso correctamente');break;
				case '2': $("#modal_eliminar").modal("hide");
							alertify.error('No se pudo eliminar ');
							break;
			}
		}
	})
})
<?php endif ?>
</script>