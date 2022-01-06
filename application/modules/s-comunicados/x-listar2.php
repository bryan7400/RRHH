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
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/bootstrap-colorpicker/bootstrap-colorpicker.css">
<!--link rel="stylesheet" href="assets/themes/concept/assets/vendor/fonts/circular-std/style.css">
<link rel="stylesheet" href="assets/themes/concept/assets/libs/css/style.css"-->


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
										<a href="#" onclick="abrir_crear();" class="dropdown-item">Crear Gestión Escolar</a>
										<?php endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-comunicados/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Gestión Escolar</a>
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
							<th class="text-nowrap">Nombre del Evento</th>
							<th class="text-nowrap">Descripción</th>
							<th class="text-nowrap">Color</th>
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
							<th class="text-nowrap text-middle">Nombre del Evento</th>
							<th class="text-nowrap text-middle">Descripción</th>
							<th class="text-nowrap text-middle">Color</th>
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
<!-- ============================================================== -->
<!-- row -->
<!-- ============================================================== -->



<!--div class="panel-body">
	<?php if ($permiso_crear || $permiso_imprimir) : ?>
	<div class="row">
		<div class="col-xs-6">
			<div class="text-label hidden-xs">Seleccionar acción:</div>
			<div class="text-label visible-xs-block">Acciones:</div>
		</div>
		<div class="col-xs-6 text-right">
			<div class="btn-group">
				<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
					<span class="glyphicon glyphicon-menu-hamburger"></span>
					<span class="hidden-xs">Acciones</span>
				</button>
				<ul class="dropdown-menu dropdown-menu-right">
					<li class="dropdown-header visible-xs-block">Seleccionar acción</li>
					<?php if ($permiso_crear) : ?>
					<li><a href="#" onclick="abrir_crear();"><span class="glyphicon glyphicon-plus"></span> Crear comunidados</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/s-comunicados/imprimir" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir comunidados</a></li>
					<?php endif ?>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	<?php endif ?>
	<?php if ($message = get_notification()) : ?>
	<div class="alert alert-<?= $message['type']; ?>">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong><?= $message['title']; ?></strong>
		<p><?= $message['content']; ?></p>
	</div>
	<?php endif ?>
	<?php if ($comunidados) : ?>
	<table id="table" class="table table-bordered table-condensed table-striped table-hover">
		<thead>
			<tr class="active">
				<th class="text-nowrap">Id comunidado</th>
				<th class="text-nowrap">Fecha inicio</th>
				<th class="text-nowrap">Fecha final</th>
				<th class="text-nowrap">Nombre evento</th>
				<th class="text-nowrap">Descripcion</th>
				<th class="text-nowrap">Color</th>
				<th class="text-nowrap">Usuarios</th>
				<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
				<th class="text-nowrap">Opciones</th>
				<?php endif ?>
			</tr>
		</thead>
		<tfoot>
			<tr class="active">
				<th class="text-nowrap text-middle">Id comunidado</th>
				<th class="text-nowrap text-middle">Fecha inicio</th>
				<th class="text-nowrap text-middle">Fecha final</th>
				<th class="text-nowrap text-middle">Nombre evento</th>
				<th class="text-nowrap text-middle">Descripcion</th>
				<th class="text-nowrap text-middle">Color</th>
				<th class="text-nowrap text-middle">Usuarios</th>
				<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
				<th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
				<?php endif ?>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($comunidados as $nro => $comunidados) : ?>
			<tr>
				<th class="text-nowrap"><?= $nro + 1; ?></th>
				<td class="text-nowrap"><?= escape($comunidados['fecha_inicio']); ?></td>
				<td class="text-nowrap"><?= escape($comunidados['fecha_final']); ?></td>
				<td class="text-nowrap"><?= escape($comunidados['nombre_evento']); ?></td>
				<td class="text-nowrap"><?= escape($comunidados['descripcion']); ?></td>
				<td class="text-nowrap"><?= escape($comunidados['color']); ?></td>
				<td class="text-nowrap"><?= escape($comunidados['usuarios']); ?></td>
				<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
				<td class="text-nowrap">
					<?php if ($permiso_ver) : ?>
					<a href="?/s-comunicados/ver/<?= $comunidados['id_comunicado']; ?>" data-toggle="tooltip" data-title="Ver comunidados"><span class="glyphicon glyphicon-search"></span></a>
					<?php endif ?>
					<?php if ($permiso_modificar) : ?>
					<a href="?/s-comunicados/modificar/<?= $comunidados['id_comunicado']; ?>" data-toggle="tooltip" data-title="Modificar comunidados"><span class="glyphicon glyphicon-edit"></span></a>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<a href="?/s-comunicados/eliminar/<?= $comunidados['id_comunicado']; ?>" data-toggle="tooltip" data-title="Eliminar comunidados" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span></a>
					<?php endif ?>
				</td>
				<?php endif ?>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	<?php else : ?>
	<div class="alert alert-info">
		<strong>Atención!</strong>
		<ul>
			<li>No existen comunidados registrados en la base de datos.</li>
			<li>Para crear nuevos comunidados debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
		</ul>
	</div>
	<?php endif ?>
</div-->


<!-- Modal -->
<div class="modal fade" id="modal_agregar_evento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form class="form-horizontal" id="form_agregar_evento"> 
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_modal"></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body"> <!--comieza el body de la modal-->
              
              <div class="control-group" style="margin-bottom:15px;">
                <label class="control-label">Titulo:</label>
                <div class="controls">
                  <input type="hidden" name="id_evento" class="form-control" id="id_evento">
                  <input type="text" name="nombre_evento" class="form-control" id="nombre_evento">
                </div>
              </div>
              <div class="control-group" style="margin-bottom:15px;">
                <label for="title" class="control-label">Descripción:</label>
                <div class="controls">
                  <input type="text" name="descripcion_evento" class="form-control" id="descripcion_evento">
                </div>
              </div>
              <div class="control-group" style="margin-bottom:15px;">
                <label for="color" class="control-label">Color:</label>
                <div class="controls">
                  <select name="color_evento" class="form-control" id="color_evento">
                    <option style="color:#0071c5;" value="#0071c5">&#9724; Azul oscuro</option>
                    <option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquesa</option>
                    <option style="color:#008000;" value="#008000">&#9724; Verde</option>						  
                    <option style="color:#FFD700;" value="#FFD700">&#9724; Amarillo</option>
                    <option style="color:#FF8C00;" value="#FF8C00">&#9724; Naranja</option>
                    <option style="color:#FF0000;" value="#FF0000">&#9724; Rojo</option>
                    <option style="color:#000;" value="#000">&#9724; Negro</option>
                  </select>
                </div>
              </div>
			  <div id="cp2" class="input-group colorpicker-component">
					<input type="text" value="#00AABB" class="form-control" />
					<span class="input-group-addon"><i></i></span>
				</div>
              <div class="control-group" style="margin-bottom:15px;">
                <div class="row">
                  <div class="col-sm-6">Fecha Inicial:</div>
                  <div class="col-sm-6">Hora:</div>
                </div>
                <div class="row">
                  <div class="col-sm-6"><input type="date" name="fecha_inicio" class="form-control" id="fecha_inicio"></div>
                  <div class="col-sm-6"><input type="time" name="hora_inicio" class="form-control" id="hora_inicio"></div>
                </div>
              </div>
              <div class="control-group" style="margin-bottom:15px;">
                <div class="row">
                  <div class="col-sm-6">Fecha A Terminar:</div>
                  <div class="col-sm-6">Hora:</div>
                </div>
                <div class="row">
                  <div class="col-sm-6"><input type="date" name="fecha_final" class="form-control" id="fecha_final"></div>
                  <div class="col-sm-6"><input type="time" name="hora_final" class="form-control" id="hora_final"></div>
                </div>
              </div>
			  <div class="control-group" style="margin-bottom:15px;">
                <label for="title" class="control-label">Roles:</label>
                <div class="controls">
				<select class="selectpicker form-control" id="select_roles" name="select_roles" multiple title="Seleccione">
					<?php
						foreach ($roles as $key => $rol) {
					?>
						<option value="<?= $rol['id_rol'];?>"><?= $rol['rol']?></option>
					<?php
						}
					?>
				</select>
                </div>
              </div>
              <div class="form-group" id="div_eliminar" style="display:none">
                <!--label for="end" class="col-sm-4 control-label" style="color:red">Eliminar evento: </label>
                <div class="col-sm-1">
                  <input type="checkbox" name="eliminar" class="form-control" id="eliminar">
                </div-->
                <label class="custom-control custom-checkbox" style="color:red">
                  <input type="checkbox" class="custom-control-input" name="eliminar" id="eliminar"><span class="custom-control-label">Eliminar evento</span>
                </label>
              </div>

              
            </div><!--termina el body de la modal-->
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal" >Cancelar</button>
              <button class="btn btn-primary" id="btn_agregar" >Registrar</button>
              <button class="btn btn-primary" id="btn_editar">Editar</button>
            </div>
          </form>
        </div>
    </div>
</div>


           

<!--script src="<?= themes; ?>/concept/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
<script src="<?= themes; ?>/concept/assets/libs/js/main-js.js"></script-->

<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-colorpicker/bootstrap-colorpicker.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-select/js/bootstrap-select.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-select/js/require.js"></script>
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<!--script src="<?= js; ?>/jquery.dataFilters.min.js"></script-->
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<!--script src="<?= themes; ?>/concept/assets/vendor/bootstrap-colorpicker/jquery-asColor/dist/jquery-asColor.min.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-colorpicker/jquery-asGradient/dist/jquery-asGradient.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-colorpicker/jquery-asColorPicker/dist/jquery-asColorPicker.min.js"></script-->

<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/educheck.js"></script>

<?php require_once show_template('footer-design'); ?>
<script>
$(function () {
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/s-comunicados/crear';
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
		bootbox.confirm('¿Está seguro que desea eliminar el comunidados?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
	
	<?php if ($comunidados) : ?>
	/*$('#table').DataFilter({
		filter: true,
		name: 'comunicados',
		reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
	});*/
	<?php endif ?>

	
});

$('#cp2').colorpicker();

var columns=[
	{data: 'id_comunicado'},
	{data: 'fecha_inicio'},
	{data: 'fecha_final'},
	{data: 'nombre_evento'},
	{data: 'descripcion'},
	{data: 'color'}
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
						var contenido = row['id_comunicado'] + "*" + row['codigo']+ "*" + row['fecha_inicio']+ "*" + row['fecha_final']+ "*" + row['nombre_evento']+ "*" + row['descripcion']+ "*" + row['color']+ "*" + row['usuarios'];
						result+="<?php if ($permiso_ver) : ?><a href='#' class='btn btn-info btn-xs' onclick = 'abrir_ver("+'"'+contenido+'"'+");'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
								"<?php if ($permiso_modificar) : ?><a href='#' class='btn btn-warning btn-xs' style='color:white' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note'></span></a><?php endif ?> &nbsp" +
								"<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash'></span></a><?php endif ?>";
						
						return result;
					},
					"targets": 7
			},
			{
					"render": function (data, type, row) {
						var html = '';
						var usu = row['usuarios'].split(",");
						html += '<ul type="square">';  
						for (let i = 0; i <= 3; i++) {
							var usuario = usu[i].split(":");
							var estado = usuario[1];
							switch (usuario[0]) {
								case '2': 	if(usuario[1] == 2){administrativo = "SI"; estilo = "#07D81E;"}else{administrativo = "NO"; estilo = "#E8463B"} html += '<li> Administrativos: <span style= " color: '+ estilo +'">'+ administrativo +'</span></li>'; break;
								case '3': 	if(usuario[1] == 3){profesor = "SI"; estilo = "#07D81E" }else{profesor = "NO"; estilo = "#E8463B" } html += '<li> Profesores: <span style= " color: '+ estilo +'">'+ profesor +'</span></li>'; break;
								case '4': 	if(usuario[1] == 4){tutor = "SI"; estilo = "#07D81E" }else{tutor = "NO"; estilo = "#E8463B" } html += '<li> Tutores: <span style= " color: '+ estilo +'">'+ tutor +'</span></li> '; break;
								case '5': 	if(usuario[1] == 5){estudiante = "SI"; estilo = "#07D81E" }else{estudiante = "NO"; estilo = "#E8463B" } html += '<li> Estudiantes <span style= " color: '+ estilo +'">'+ estudiante +'</span></li>'; break;
							}
						}
						html += '</ul>';
						return html;
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




/*function listar_roles(){
	$.ajax({
        url: '?/s-comunicados/procesos',
        type: 'POST',
        data: {'boton':'listar_roles'},
        dataType: 'JSON',
        success: function(resp){
            //alert(resp[0]['id_catalogo_detalle']);
            console.log(resp);
            $("#select_roles").html("");
            $("#select_roles").append('<option value="'+ 0 +'">Seleccione</option>');
            for(var i=0;i<resp.length;i++){
                $("#select_roles").append('<option value="'+resp[i]["id_rol"]+'">'+ resp[i]["rol"]+'</option>');
            }
            //console.log(resp[0]);
        }
    });
}*/
function abrir_ver(contenido){
	$("#form_agregar_evento")[0].reset();
	$("#modal_agregar_evento").modal("show");
	var d = contenido.split("*");
	console.log(d);
	$("#id_evento").val(d[0]);
	$("#nombre_evento").val(d[4]);
	$("#descripcion_evento").val(d[5]);
	$("#color_evento").val(d[6]);
}

function abrir_crear(){
	$("#modal_agregar_evento").modal("show");
	$("#titulo_modal").text("Crear Comunicado");
	$("#btn_editar").hide();
}

//funcion para crear eventos
$('#btn_agregar').on('click', function(e){
  e.preventDefault();
    var parametros = {
      'nombre_evento': $("#nombre_evento").val(),
      'descripcion': $("#descripcion_evento").val(),
      'color': $("#color_evento").val(),
      'fecha_inicio': $("#fecha_inicio").val(),
      'hora_inicio': $("#hora_inicio").val(),
      'fecha_final': $("#fecha_final").val(),
      'hora_final': $("#hora_final").val(),
	  'roles': $("#select_roles").val(),
      'boton': 'agregar_evento'
    }
    $.ajax({
        url: '?/s-comunicados/procesos',
        type: 'POST',
        data: parametros,
        success: function (data){
          //console.log(data);
          if(data == 1){
            $('#modal_agregar_evento').modal('hide');
            $("#form_agregar_evento")[0].reset();
            $("#calendario").fullCalendar("refetchEvents");
            alertify.success('Se agrego el evento correctamente');
          }else{
            alertify.error('No se pudo agregar el evento');
          }
        }
    });
  });

//window.onload = listar_roles();
</script>
