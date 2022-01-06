<?php 
// Obtiene la cadena csrf
$csrf = set_csrf(); 

//ruta estatica del js
$ruta ="application/modules/s-gestion-escolar"; 

$gestiones = $db->select('z.*')->from('ins_gestion z')->where('estado', 'A')->order_by('z.id_gestion', 'asc')->fetch();
// Obtiene los permisos
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_editar = in_array('editar', $_views);
$permiso_copiar = in_array('copiar', $_views);
$permiso_eliminar = in_array('eliminar', $_views); 
$permiso_imprimir = in_array('imprimir', $_views);  
?>
<?php require_once show_template('header-design'); ?>
 
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"> 
        <div class="page-header">
            <h2 class="pageheader-title">Gestión Escolar</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Inicio de Gestión</a></li>
						<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión Escolar</a></li>
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
										<a href="#" onclick="abrir_crear();" class="dropdown-item">Crear Gestión Escolar</a>
										<?php endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-gestion-escolar/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Gestión Escolar</a>
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
				<?php if ($gestiones) : ?>
				<div class="table-responsive">
				<table id="table" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
					<thead>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<th class="text-nowrap">Gestión</th>
							<th class="text-nowrap">Inicio gestión</th>
							<th class="text-nowrap">Final gestión</th>
							<th class="text-nowrap">Inicio vacaciones</th>
							<th class="text-nowrap">Final vacaciones</th>
							<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
								<th class="text-nowrap">Opciones</th>
							<?php endif ?>
						</tr>
					</thead>

					<tbody id="listado_gestion_escolar">
					</tbody>

					<!--tfoot>
			            <tr>
			                <th colspan="1" style="text-align:right">Total:</th>
			                <th colspan="6"></th>
			            </tr>
			        </tfoot-->
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
	  	<input type="hidden" id="gestion_eliminar">
        <p>¿Esta seguro de eliminar la gestión <span id="texto_gestion"></span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn_eliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>


<script src="<?= js; ?>/selectize.min.js"></script>
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
	if($permiso_copiar){
		require_once ("copiar.php");
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
					//window.location = '?/gestiones/crear';
					$('#modal_gestion').modal('toggle');
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
		bootbox.confirm('¿Está seguro que desea eliminar el gestión?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
	
	<?php if ($gestiones) : ?>
	// $('#table').DataFilter({
	// 	filter: true,
	// 	name: 'gestion',
	// 	reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
	// });
	<?php endif ?>

});

<?php if ($permiso_editar) : ?>
function abrir_editar(contenido){
	$("#form_gestion")[0].reset();
	//validator.resetForm();
	$("#modal_gestion").modal("show");
	$("#titulo_gestion").text("Editar ");
	//$('#table tbody').off();
	var d = contenido.split("*");
	$("#id_gestion").val(d[0]);
	$("#nombre_gestion").val(d[1]);
	$("#inicio_gestion").val(moment(d[2]).format('YYYY-MM-DD'));
	$("#final_gestion").val(moment(d[3]).format('YYYY-MM-DD'));
	$("#inicio_vacaciones").val(moment(d[4]).format('YYYY-MM-DD'));
	$("#final_vacaciones").val(moment(d[5]).format('YYYY-MM-DD'));
	$("#btn_nuevo").hide();
	$("#btn_editar").show();
}
<?php endif ?>

<?php if ($permiso_crear) : ?>
function abrir_crear(){
	$("#modal_gestion").modal("show");
	$("#form_gestion")[0].reset();
	//$("#form_gestion").reset();
	//$("#titulo_gestion").text("Crear ");
	$("#btn_editar").hide();
	$("#btn_nuevo").show();
}
<?php endif ?>

<?php if ($permiso_copiar) : ?>
function abrir_copiar(gestion){
	$("#modal_copiar").modal("show");
	//$("#form_gestion")[0].reset();
	//$("#form_gestion").reset();
	$("#copiar_id_gestion").val(gestion);
	//$("#btn_editar").hide();
	//$("#btn_nuevo").show();
}
<?php endif ?>

var columns=[
	{data: 'id_gestion'},
	{data: 'gestion'},
	{data: 'inicio_gestion'},
	{data: 'final_gestion'},
	{data: 'inicio_vacaciones'},
	{data: 'final_vacaciones'}
];
var rows=[
	{data: 'id_gestion'},
	{data: 'gestion'},
	{data: 'inicio_gestion'},
	{data: 'final_gestion'},
	{data: 'inicio_vacaciones'},
	{data: 'final_vacaciones'}
];
var cont = 0;
var dataTable = $('#table').DataTable({
	language: dataTableTraduccion,
	searching: true,
	paging:true,
	"lengthChange": true,
	"responsive": true,
	ajax: {
		url: '?/s-gestion-escolar/busqueda',
		dataSrc: '',		
		type:'POST',
		dataType: 'json'
	},
	columns: columns,

	"columnDefs": [
			{
				"render": function (data, type, row){
					var result = "";
					var contenido = row['id_gestion'] + "*" + row['gestion']+ "*" + row['inicio_gestion']+ "*" + row['final_gestion']+ "*" + row['inicio_vacaciones']+ "*" + row['final_vacaciones'];
					var gestion = row['id_gestion'];
					result+="<?php if ($permiso_ver) : ?><a href='#' class='btn btn-info btn-xs' onclick = 'ver("+'"'+contenido+'"'+")'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
							"<?php if ($permiso_editar) : ?><a href='#' class='btn btn-warning btn-xs' style='color:white' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note'></span></a><?php endif ?> &nbsp" +
							"<?php if ($permiso_copiar) : ?><a href='#' class='btn btn-info btn-xs' style='color:white' onclick='abrir_copiar("+gestion+")'><span class='icon-plus'></span></a><?php endif ?> &nbsp" +
							"<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash'></span></a><?php endif ?>";
					return result;
				},
				"targets": 6
			},
			{
				"render": function (data, type, row){
					cont = cont +1;
					return cont;
				},
				"targets": 0
			}
	],
    "footerCallback": function ( row, data, start, end, display ) {
	    var api = this.api(), data;
	    //console.log(api+'hgjfghkjfghkfdjghkfdjh');

	    // Remove the formatting to get integer data for summation
	    var intVal = function ( i ) {
	        return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
	    };

	    // Total over all pages
	    /*
	    total = api
	        .column( 1 )
	        .data()
	        .reduce( function (a, b) {
	            return intVal(a) + intVal(b);
	        }, 0 );

	    // Total over this page
	    pageTotal = api
	        .column( 1, { page: 'current'} )
	        .data()
	        .reduce( function (a, b) {
	            return intVal(a) + intVal(b);
	        }, 0 );

	    // Update footer
	    $( api.column( 1 ).footer() ).html(
	        'Bs. '+pageTotal +' ( Bs. '+ total +' Total)'
	    );
	    */
	}
});

//$(document).ready(function() {
    // $('#table').DataTable( {
    //     "footerCallback": function ( row, data, start, end, display ) {
    //         var api = this.api(), data;
    //         console.log(api+'hgjfghkjfghkfdjghkfdjh');
 
    //         // Remove the formatting to get integer data for summation
    //         var intVal = function ( i ) {
    //             return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
    //         };
 
    //         // Total over all pages
    //         total = api
    //             .column( 1 )
    //             .data()
    //             .reduce( function (a, b) {
    //                 return intVal(a) + intVal(b);
    //             }, 0 );
 
    //         // Total over this page
    //         pageTotal = api
    //             .column( 1, { page: 'current'} )
    //             .data()
    //             .reduce( function (a, b) {
    //                 return intVal(a) + intVal(b);
    //             }, 0 );
 
    //         // Update footer
    //         $( api.column( 1 ).footer() ).html(
    //             '$'+pageTotal +' ( $'+ total +' total)'
    //         );
    //     }
    // } );
///} );
<?php if ($permiso_ver) : ?>
function ver(contenido){
	var d = contenido.split("*");
	$("#gestion_ver").modal("show");
	$("#nom_gestion").text(d[1]);
	$("#ini_gestion").text(d[2]);
	$("#fi_gestion").text(d[3]);
	$("#ini_vacaciones").text(d[4]);
	$("#fi_vacaciones").text(d[5]);
}
// function ver(){
// 	$('#table tbody').on('click', 'tr', function () {
// 		var data = dataTable.row( this ).data();
// 		//alert( 'You clicked on '+data[0]+'\'s row' );
// 		$("#gestion_ver").modal("show");
// 		$("#nom_gestion").text(data['gestion']);
// 		$("#ini_gestion").text(data['inicio_gestion']);
// 		$("#fi_gestion").text(data['final_gestion']);
// 		$("#ini_vacaciones").text(data['inicio_vacaciones']);
// 		$("#fi_vacaciones").text(data['final_vacaciones']);
// 	});
// }
<?php endif ?> 

<?php if ($permiso_eliminar) : ?>
function abrir_eliminar(contenido){
	$("#modal_eliminar").modal("show");
	var d = contenido.split("*");
	$("#gestion_eliminar").val(d[0]);
	$("#texto_gestion").text(d[1]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
$("#btn_eliminar").on('click', function(){
	//alert($("#gestion_eliminar").val())
	id_gestion = $("#gestion_eliminar").val();
	$.ajax({
		url: '?/s-gestion-escolar/eliminar',
		type:'POST',
		data: {'id_gestion':id_gestion},
		success: function(resp){
			//alert(resp)
			switch(resp){
				case '1': $("#modal_eliminar").modal("hide");
							dataTable.ajax.reload();
							alertify.success('Se elimino la gestión académica correctamente');break;
				case '2': $("#modal_eliminar").modal("hide");
							alertify.error('No se pudo eliminar ');
							break;
			}
		}
	})
})
<?php endif ?>
</script>
