<?php

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los concepto_pago
$concepto_pago = $db->select('z.*')->from('rhh_concepto_pago z')->order_by('z.id_concepto_pago', 'asc')->fetch();
// Obtiene los permisos
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_modificar = in_array('editar', $_views);
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
            <h2 class="pageheader-title">Concepto de Pago</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Recursos Humanos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Conceptos de Pago</li>
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
										<a href="#" onclick="abrir_crear();" class="dropdown-item">Crear Concepto de Pago</a>
										<?php endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/rrhh-conceptos-pago/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Conceptos de Pagos</a>
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

				<?php if ($concepto_pago) : ?>
				<div class="table-responsive">
				<table id="table" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
					<thead>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<th class="text-nowrap">Concepto de Pago</th>
							<th class="text-nowrap">Descripción</th>
							<th class="text-nowrap">Porcentaje</th>
							<th class="text-nowrap">Monto</th>
							<th class="text-nowrap">Mes</th>
							<th class="text-nowrap">Gestión</th>
							<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
							<th class="text-nowrap">Opciones</th>
							<?php endif ?>
						</tr>
					</thead>
					<tfoot>
						<tr class="active">
							<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
							<th class="text-nowrap text-middle">Concepto de Pago</th>							
							<th class="text-nowrap text-middle">Descripción</th>
							<th class="text-nowrap text-middle">Porcentaje</th>
							<th class="text-nowrap text-middle">Monto</th>
							<th class="text-nowrap text-middle">Mes</th>
							<th class="text-nowrap text-middle">Gestión</th>
							<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
							<th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
							<?php endif ?>
						</tr>
					</tfoot>
					<tbody id="listado_concepto_pago">
					</tbody>
				</table>
				</div>
				<?php else : ?>

				<div class="alert alert-info">
					<strong>Atención!</strong>
					<ul>
						<li>No existen gestiones registrados en la base de datos.</li>
						<li>Para crear nuevos gestiones debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
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
	  	<input type="hidden" id="concepto_eliminar">
        <p>¿Esta seguro de eliminar la gestion <span id="texto_concepto"></span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn_eliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>
<!--modal para eliminar-->

<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<!--script src="<?= js; ?>/jquery.dataFilters.min.js"></script-->
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/educheck.js"></script>

<?php 
	if($permiso_modificar){
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
					window.location = '?/concepto_pago/crear';
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
		bootbox.confirm('¿Está seguro que desea eliminar el concepto pago?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
	
	<?php if ($concepto_pago) : ?>
	/*$('#table').DataFilter({
		filter: true,
		name: 'concepto_pago',
		reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
	});*/
	<?php endif ?>
});

<?php if ($permiso_modificar) : ?>
function abrir_editar(contenido){
	$("#form_concepto_pago")[0].reset();
	//validator.resetForm();
	$("#modal_concepto_pago").modal("show");
	$("#titulo_concepto_pago").text("Editar ");
	$('#table tbody').off();
	var d = contenido.split("*");
	$("#id_concepto_pago").val(d[0]);
	$("#nombre_concepto_pago").val(d[1]);
	$("#porcentaje").val(d[2]);
	$("#btn_nuevo").hide();
	$("#btn_editar").show();
}
<?php endif ?>

<?php if ($permiso_crear) : ?>
function abrir_crear(){
	$("#modal_concepto_pago").modal("show");
	$("#form_concepto_pago")[0].reset();
	//$("#form_gestion").reset();
	$("#titulo_concepto_pago").text("Crear ");
	
	$("#btn_editar").hide();
	$("#btn_nuevo").show();
}
<?php endif ?>

var columns=[
	{data: 'id_concepto_pago'},
	{data: 'nombre_concepto_pago'},
	{data: 'descripcion'},
	{data: 'porcentaje'},
	{data: 'monto'},
	{data: 'mes'},
	{data: 'gestion'}
];
var cont = 0;
var dataTable = $('#table').DataTable({
	language: dataTableTraduccion,
	searching: true,
	paging:true,
	"lengthChange": true,
	"responsive": true,
	ajax: {
		url: '?/rrhh-conceptos-pago/busqueda',
		dataSrc: '',		
		type:'POST',
		dataType: 'json'
	},
	columns: columns,
	"columnDefs": [
			{
					"render": function (data, type, row) {
						var result = "";
						var contenido = row['id_concepto_pago'] + "*" + row['nombre_concepto_pago']+ "*" + row['porcentaje'];
						result+="<?php if ($permiso_ver) : ?><a href='#' class='btn btn-info btn-xs' onclick = 'ver("+'"'+contenido+'"'+");'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
								"<?php if ($permiso_modificar) : ?><a href='#' class='btn btn-warning btn-xs' style='color:white' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note'></span></a><?php endif ?> &nbsp" +
								"<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash'></span></a><?php endif ?>";
						return result;
					},
					"targets": 7
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

<?php if ($permiso_ver) : ?>
function ver(contenido){
	var d = contenido.split("*");
	$('#table tbody').on('click', 'tr', function () {
		//var data = dataTable.row( this ).data();
		//alert( 'You clicked on '+data[0]+'\'s row' );
		$("#concepto_pago_ver").modal("show");
		$("#nombre_ver").text(d[1]);
		$("#porcentaje_ver").text(d[2]);
	});
}
<?php endif ?> 

<?php if ($permiso_eliminar) : ?>
function abrir_eliminar(contenido){
	$("#modal_eliminar").modal("show");
	var d = contenido.split("*");
	$("#concepto_eliminar").val(d[0]);
	$("#texto_concepto_pago").text(d[1]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
$("#btn_eliminar").on('click', function(){
	//alert($("#gestion_eliminar").val())
	id_concepto = $("#concepto_eliminar").val();
	$.ajax({
		url: '?/rrhh-conceptos-pago/eliminar',
		type:'POST',
		data: {'id_concepto':id_concepto},
		success: function(resp){
			//alert(resp)
			cont = 0;
			switch(resp){
				case '1': $("#modal_eliminar").modal("hide");
							dataTable.ajax.reload();
							alertify.success('Se elimino el concepto de pago correctamente');break;
				case '2': $("#modal_eliminar").modal("hide");
							alertify.error('No se pudo eliminar ');
							break;
			}
		}
	})
})
<?php endif ?>
</script>
<?php require_once show_template('footer-design'); ?>