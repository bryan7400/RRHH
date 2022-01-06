<?php

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los comunidados
$comunidados = $db->select('z.*')->from('ins_comunicados z')->order_by('z.id_comunicado', 'asc')->fetch();


//obtiene los roles 
$roles = $db->query("SELECT * FROM sys_roles WHERE rol != 'Superusuario'")->fetch();

// Obtiene los permisos
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);

?>

<?php require_once show_template('header-design'); ?>
<style>
  .datepicker {z-index: 1151 !important;}
</style>
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/bootstrap/css/bootstrap.min.css">
<link href="assets/themes/concept/assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
<link rel="stylesheet" href="assets/themes/concept/assets/libs/css/style.css">
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
<link href="assets/themes/concept/assets/vendor/bootstrap-colorpicker/%40claviska/jquery-minicolors/jquery.minicolors.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/datepicker/css/datepicker.css" rel="stylesheet">

<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"> 
        <div class="page-header">
            <h2 class="pageheader-title">Comunicados</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Agenda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Comunicados</li>
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
										<a href="#" onclick="abrir_crear();" class="dropdown-item">Crear Comunicados</a>
										<?php endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-comunicados/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Comunicados</a>
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
				<table id="table" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
					<thead>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<th class="text-nowrap">Fecha de Inicio</th>
							<th class="text-nowrap">Fecha a Terminar</th>
							<th class="text-nowrap">Nombre del Comunicado</th>
							<th class="text-nowrap">Descripción</th>
							<th class="text-nowrap">Usuarios</th>
							<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
							<th class="text-nowrap">Opciones</th>
							<?php endif ?>
						</tr>
					</thead>
					<tfoot>
						<tr class="active">
							<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
							<th class="text-nowrap text-middle">Fecha de Inicio</th>
							<th class="text-nowrap text-middle">Fecha a Termina</th>
							<th class="text-nowrap text-middle">Nombre del Comunicado</th>
							<th class="text-nowrap text-middle">Descripción</th>
							<th class="text-nowrap text-middle">Usuarios</th>
							<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
							<th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
							<?php endif ?>
						</tr>
					</tfoot>
					<tbody id="listado_gestion_escolar">
					</tbody>
				</table>
				</div>
				
			</div>
			<!-- ============================================================== -->
			<!-- end datos -->
			<!-- ============================================================== -->
		</div>
	</div>
</div>

<!-- librerias para full calendar -->
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/calendar.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/fullcalendar.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/es.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/jquery-ui.min.js"></script>

<!-- librerias para el color -->
<script src="assets/themes/concept/assets/vendor/bootstrap-colorpicker/%40claviska/jquery-minicolors/jquery.minicolors.min.js"></script>

<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-select/js/bootstrap-select.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-select/js/require.js"></script>

<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>

<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/educheck.js"></script>

<script src="<?= themes; ?>/concept/assets/libs/js/main-js.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.es.js"></script>
<?php require_once show_template('footer-design'); ?>

<?php 
	if($permiso_crear){
		require_once ("crear.php");
	}
?>
<script>
$(function () {
  <?php if ($permiso_eliminar) : ?>
    $('[data-eliminar]').on('click', function (e) {
      e.preventDefault();
      var href = $(this).attr('href');
      var csrf = '<?= $csrf; ?>';
      bootbox.confirm('¿Está seguro que desea eliminar el gestion?', function (result) {
        if (result) {
          $.request(href, csrf);
        }
      });
    });
  <?php endif ?>
})

$('#color_evento').minicolors({
        theme: 'bootstrap'
});

var columns=[
	{data: 'id_comunicado'},
	{data: 'fecha_inicio'},
	{data: 'fecha_final'},
	{data: 'nombre_evento'},
	{data: 'descripcion'}
];

var administrativo = "";
var profesor = "";
var tutor = "";
var estudiante = ""
var estilo = "";

var cont = 0;
var dataTable = $('#table').DataTable({
	language: dataTableTraduccion,
	searching: true,
	paging:true,
	"lengthChange": true,
	"responsive": true,
	ajax: {
		url: '?/s-comunicados/busquedas',
		dataSrc: '',		
		type:'POST',
		dataType: 'json'
	},
	columns: columns,
	"columnDefs": [
			{
					"render": function (data, type, row) {
						var result = "";
						var contenido = row['id_comunicado'] + "*" + row['codigo']+ "*" + row['fecha_inicio']+ "*" + row['fecha_final']+ "*" + row['nombre_evento']+ "*" + row['descripcion']+ "*" + row['color']+ "*" + row['usuarios']+ "*" + row['estados']+ "*" + row['ids'];
						result+="<?php if ($permiso_ver) : ?><a href='#' class='btn btn-info btn-xs' onclick = 'abrir_ver("+'"'+contenido+'"'+");'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
								"<?php if ($permiso_modificar) : ?><a href='#' class='btn btn-warning btn-xs' style='color:white' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note'></span></a><?php endif ?> &nbsp" +
								"<?php if ($permiso_eliminar) : ?><a href='?/s-comunicados/eliminar/"+ row['id_comunicado'] +"' class='btn btn-danger btn-xs' data-eliminar='true'><span class='icon-trash'></span></a><?php endif ?>";
						
						return result;
					},
					"targets": 6
			},
			{
					"render": function (data, type, row) {
						var html = '';
            var usuario = row['usuarios'].split(",");
            var estados = row['estados'].split(",");
            var tamanio = usuario.length;
            html += '<ul type="square">'; 
        
						for (let i = 0; i < tamanio; i++) {
                if(estados[i] == "SI"){
                  estilo = "#07D81E;";
                  respuesta = "SI";
                }else{
                  estilo = "#E8463B;";
                  respuesta = "NO";
                }
                html += '<li> '+ usuario[i] +': <span style= " color: '+ estilo +'">'+ respuesta +'</span></li>';
              
						}
						html += '</ul>';
						return html;
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
function abrir_ver(contenido){
    var d = contenido.split("*");
    var inicio = d[2].split(" ");
    var final = d[3].split(" ");
    var estados = d[8].split(",");
    var espacio = d[9].split(",");
    var cadena = [];
    contador = estados.length;
    for(var i = 0; i < contador; i++){
      if(estados[i] == "SI"){
        cadena.push(espacio[i]);
        //.log("asd");
      }
    }
    $("#form_agregar_evento")[0].reset();
    $("#modal_agregar_evento").modal("show");
    
    //console.log(d);
    $("#titulo_modal").text("Ver Comunicado");
    $("#id_comunicado").val(d[0]);
    $("#nombre_evento").val(d[4]);
    $("#descripcion_evento").val(d[5]);
    $("#color_evento").minicolors('value',d[6]);
    
    $("#fecha_inicio").val(inicio[0]);
    $("#hora_inicio").val(inicio[1]);
    $("#fecha_final").val(final[0]);
    $("#hora_final").val(final[1]);
    // ids = {}
    $('#select_roles').selectpicker('val', cadena);
    $("#btn_editar").hide();
    $("#btn_agregar").hide();
//$("#select_roles").
}
<?php endif ?>

<?php if ($permiso_modificar) : ?>
function abrir_editar(contenido){
    var d = contenido.split("*");
    var inicio = d[2].split(" ");
    var final = d[3].split(" ");
    var estados = d[8].split(",");
    var espacio = d[9].split(",");
    var cadena = [];
    contador = estados.length;
    for(var i = 0; i < contador; i++){
      if(estados[i] == "SI"){
        cadena.push(espacio[i]);
        //.log("asd");
      }
    }
    $("#form_agregar_evento")[0].reset();
    $("#modal_agregar_evento").modal("show");
    
    //console.log(d);
    $("#titulo_modal").text("Ver Comunicado");
    $("#id_comunicado").val(d[0]);
    $("#nombre_evento").val(d[4]);
    $("#descripcion_evento").val(d[5]);
    $("#color_evento").minicolors('value',d[6]);
    
    $("#fecha_inicio").val(inicio[0]);
    $("#hora_inicio").val(inicio[1]);
    $("#fecha_final").val(final[0]);
    $("#hora_final").val(final[1]);
    // ids = {}
    $('#select_roles').selectpicker('val', cadena);
    $("#btn_editar").show();
    $("#btn_agregar").hide();
//$("#select_roles").
}
<?php endif ?>

<?php if ($permiso_crear) : ?>
function abrir_crear(){
    $("#form_agregar_evento")[0].reset();
    $("#modal_agregar_evento").modal("show");
    $("#titulo_modal").text("Crear Comunicado");
    $("#btn_editar").hide();
    $("#btn_agregar").show();
}
<?php endif ?>
//funcion para crear eventos
/*$('#btn_agregar').on('click', function(e){
  
  });*/

</script>
