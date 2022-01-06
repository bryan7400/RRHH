<?php 
// Obtiene la cadena csrf 
$csrf = set_csrf(); 

// Obtiene los paralelo
$paralelo = $db->select('z.*')->from('ins_paralelo z')->order_by('z.id_paralelo', 'asc')->fetch();

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
            <h2 class="pageheader-title">Paralelo</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Configuraión</a></li>
						<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Paralelo</a></li>
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
										<a href="#" onclick="abrir_crear();" class="dropdown-item">Crear Paralelo</a>
										<?php endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-paralelo/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Paraelo</a>
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

				<?php if ($paralelo) : ?>
				<div class="table-responsive">
				<table id="table" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
					<thead>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<th class="text-nowrap">Nombre paralelo</th>
							<th class="text-nowrap">Asignar Color</th>
							<th class="text-nowrap">Descripción</th>
							<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
							<th class="text-nowrap">Opciones</th>
							<?php endif ?>
						</tr>
					</thead>
					<tfoot>
						<tr class="active">
							<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
							<th class="text-nowrap text-middle">Nombre paralelo</th>
							<th class="text-nowrap text-middle">Asignar Color</th>
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
						<li>No existen paralelos registrados en la base de datos.</li>
						<li>Para crear nuevos paralelos debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
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
	  	<input type="hidden" id="paralelo_eliminar">
        <p>¿Esta seguro de eliminar el paralelo <span id="texto_paralelo"></span>?</p>
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
					$('#vf bgrg  dcf').modal('toggle');
				break;
			}
		}
	});
	<?php endif ?>
	
	<?php if ($paralelo) : ?>
	// $('#table').DataFilter({
	// 	filter: true,
	// 	name: 'gestion',
	// 	reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
	// });
	<?php endif ?>
	
});

<?php if ($permiso_editar) : ?>
function abrir_editar(contenido){
	$("#form_paralelo")[0].reset();
	$("#modal_paralelo").modal("show");
	var d = contenido.split("*");
	$("#id_paralelo").val(d[0]);
	$("#nombre_paralelo").val(d[1]);
	$("#color_paralelo").val(d[2]);
	$("#descripcion").val(d[3]);
	$("#btn_nuevo").hide();
	$("#btn_editar").show();
}
<?php endif ?>

<?php if ($permiso_crear) : ?>
function abrir_crear(){
	$("#modal_paralelo").modal("show");
	$("#form_paralelo")[0].reset();
	$("#btn_editar").hide();
	$("#btn_nuevo").show();
}
<?php endif ?>
var columns=[
	{data: 'id_paralelo'},
	{data: 'nombre_paralelo'},
	{data: 'color_paralelo'},
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
		url: '?/s-paralelo/busqueda',
		dataSrc: '',		
		type:'POST',
		dataType: 'json'
	},
	columns: columns,
	"columnDefs": [
			{
					"render": function (data, type, row) {
						var result = "";
						var contenido = row['id_paralelo'] + "*"+ row['nombre_paralelo']+ "*" + row['color_paralelo'] + "*" + row['descripcion'];
						result+="<?php if ($permiso_ver) : ?><a href='#' class='btn btn-info btn-xs' onclick = 'ver("+'"'+contenido+'"'+")'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
								"<?php if ($permiso_editar) : ?><a href='#' class='btn btn-warning btn-xs' style='color:white' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note'></span></a><?php endif ?> &nbsp" +
								"<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash'></span></a><?php endif ?>";
						return result;
					},
					"targets": 4
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
	$("#paralelo_ver").modal("show");
	$("#nombre_paralelo_ver").text(d[1]);
	$("#color_paralelo_ver").text(d[2]);
	$("#descripcion_ver").text(d[3]);
}
<?php endif ?> 

<?php if ($permiso_eliminar) : ?>
function abrir_eliminar(contenido){
	$("#modal_eliminar").modal("show");
	var d = contenido.split("*");
	$("#paralelo_eliminar").val(d[0]);
	$("#texto_paralelo").text(d[1]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
$("#btn_eliminar").on('click', function(){
	//alert($("#gestion_eliminar").val())
	id_paralelo = $("#paralelo_eliminar").val();
	$.ajax({
		url: '?/s-paralelo/eliminar',
		type:'POST',
		data: {'id_paralelo':id_paralelo},
		success: function(resp){
			//alert(resp)
			switch(resp){
				case '1': $("#modal_eliminar").modal("hide");
							dataTable.ajax.reload();
							alertify.success('Se elimino el paralelo correctamente');break;
				case '2': $("#modal_eliminar").modal("hide");
							alertify.error('No se pudo eliminar ');
							break;
			}
		}
	})
})
<?php endif ?>
</script>
