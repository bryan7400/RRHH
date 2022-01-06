<?php
// Obtiene la cadena csrf
$csrf = set_csrf(); 
$ruta ="application/modules/s-feriados";
// Obtiene los sferiados
$feriados =  $db->select('z.*, g.gestion')
				->from('asi_dias_feriados z')
				->join('ins_gestion g', 'z.gestion_id=g.id_gestion')
				->where('z.estado','A')
				->order_by('z.id_dias_feriados', 'asc')
				->fetch();

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
            <h2 class="pageheader-title">Días Feriados</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Secretaria</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Días Feriados</a></li>
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
                                        <a href="#" onclick="abrir_crear();" class="dropdown-item">Crear días feriados</a>
                                        <?php endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-feriados/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir días feriados</a>
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
				<?php if ($feriados) : ?>
				<table id="table" class="table table-bordered table-condensed table-striped table-hover"  style="width:100%">
					<thead>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<th class="text-nowrap">Fecha de Inicio</th>
							<th class="text-nowrap">Fecha a Terminar</th>
							<th class="text-nowrap">Descripción</th>
							<th class="text-nowrap">Gestión</th>
							<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
							<th class="text-nowrap">Opciones</th>
							<?php endif ?>
						</tr>
					</thead>
					<tfoot>
						<tr class="active">
							<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
							<th class="text-nowrap text-middle">Fecha de Inicio</th>
							<th class="text-nowrap text-middle">Fecha a Terminar</th>
							<th class="text-nowrap text-middle">Descripción</th>
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
						<li>No existen feriados registrados en la base de datos.</li>
						<li>Para crear nuevos feriados debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
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
</div>
<!-- ============================================================== -->
<!-- end row -->
<!-- ============================================================== --> 
<!--modal para eliminar-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_eliminar">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
	  	<input type="hidden" id="gestion_eliminar">
        <p>¿Esta seguro de eliminar día feriado <span id="texto_feriado"></span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-rounded btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-rounded btn-primary" id="btn_eliminar">Eliminar</button>
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
	$("#btn_prueba").on('click', function(){
		pruebaa();
	});
	
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
	
	<?php if ($permiso_eliminar) : ?>
	$('[data-eliminar]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var csrf = '<?= $csrf; ?>';
		bootbox.confirm('¿Está seguro que desea eliminar día feriado?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
	
	/*<?php //if ($feriados) : ?>
	$('#table').DataFilter({
		filter: true,
		name: 'gestion',
		reports: '<?php // ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
	});
	<?php //endif ?>*/
	
});

<?php if ($permiso_editar) : ?>
function abrir_editar(contenido){
	$("#form_feriado")[0].reset();
	//validator.resetForm();
	$("#modal_feriado").modal("show");
	$("#titulo_feriado").text("Editar ");
	$('#table tbody').off();
	var d = contenido.split("*");
	$("#id_feriado").val(d[0]);
	$("#fecha_inicio").val(moment(d[1]).format('YYYY-MM-DD'));
	$("#fecha_final").val(moment(d[2]).format('YYYY-MM-DD'));
	$("#descripcion_feriado").val(d[3]);
	$("#btn_nuevo").hide();
	$("#btn_editar").show();
}
<?php endif ?>

<?php if ($permiso_crear) : ?>
function abrir_crear(){
	$("#modal_feriado").modal("show");
	$("#form_feriado")[0].reset();
	//$("#form_feriado").reset();
	$("#titulo_feriado").text("Crear ");
	
	$("#btn_editar").hide();
	$("#btn_nuevo").show();
	$("#id_feriado").val(0);	
}
<?php endif ?>
var columns=[
	{data: 'id_dias_feriados'},
	{data: 'fecha_inicio'},
	{data: 'fecha_final'},
	{data: 'descripcion'},
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
		url: '?/s-feriados/busqueda',
		dataSrc: '',
		type:'POST',
		dataType: 'json'
	},
	columns: columns,

	"columnDefs": [
			{
					"render": function (data, type, row) {
						var result = "";
						var contenido = row['id_dias_feriados'] + "*" + row['fecha_inicio']+ "*" + row['fecha_final']+ "*" + row['descripcion']+ "*" + row['gestion'];
						result+="<?php if ($permiso_ver) : ?><a href='#' onclick = 'ver();'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
								"<?php if ($permiso_editar) : ?><a href='#' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note'></span></a><?php endif ?> &nbsp" +
								"<?php if ($permiso_eliminar) : ?><a href='#' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash'></span></a><?php endif ?>";
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
function ver(){
	$('#table tbody').on('click', 'tr', function () {
		var data = dataTable.row( this ).data();
		//alert( 'You clicked on '+data[0]+'\'s row' );
		$("#feriado_ver").modal("show");
		$("#fecha_inicio").text(data['fecha_inicio']);
		$("#fecha_final").text(data['fecha_final']);
		$("#descripcion_feriado").text(data['descripcion']);
		$("#gestion_feriado").text(data['gestion']);
	});
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
function abrir_eliminar1(contenido){
	$("#modal_eliminar").modal("show");
	var d = contenido.split("*");
	$("#feriado_eliminar").val(d[0]);
	$("#texto_feriado").text(d[1]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
$("#btn_eliminar").on('click', function(){
	//alert($("#gestion_eliminar").val())
	id_dias_feriados = $("#gestion_eliminar").val();
	$.ajax({
		url: '?/s-feriados/eliminar',
		type:'POST',
		data: {'id_dias_feriados':id_dias_feriados},
		success: function(resp){
			//alert(resp)
			switch(resp){
				case '1': $("#modal_eliminar").modal("hide");
							dataTable.ajax.reload();
							alertify.success('Se elimino día feriado correctamente');break;
				case '2': $("#modal_eliminar").modal("hide");
							alertify.error('No se pudo eliminar ');
							break;
			}
		}
	})
})
<?php endif ?>




<?php if ($permiso_eliminar) : ?>
function abrir_eliminar(contenido){
	var d = contenido.split("*");
	$("#feriado_eliminar").val(d[0]);
	$("#texto_feriado").text(d[1]);
id_dias_feriados = d[0];

if(confirm("desea eliminar el feriado? ")){
    
    $.ajax({
        url: '?/s-feriados/eliminar',
        type:'POST',
        data: {'id_dias_feriados':id_dias_feriados},
        success: function(resp){
            //alert(resp)
            $("#id_medico_estudiante").val('0');
            switch(resp){


                case '1': 
                            dataTable.ajax.reload();
                            alertify.success('Se elimino el contrato correctamente');break;
                case '2': 
                			dataTable.ajax.reload();
                            alertify.error('No se pudo eliminar ');
                            break;
            }


          

        }
    })

}else{
        return false;
    }
}
<?php endif ?>



</script>
