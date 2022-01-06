<?php 

// Obtiene la cadena csrf 
$csrf 	= set_csrf();  
$id_rol = $_user['rol_id'];

// Obtiene los usuarios
$usuarios = $db->select('u.*, r.rol')->from('sys_users u')->join('sys_roles r', 'u.rol_id = r.id_rol', 'left')->where('u.visible', 's')->fetch();

// Obtiene los permisos
$permiso_crear 	= in_array('crear', $_views); 
$permiso_ver 	= in_array('ver', $_views);
$permiso_editar = in_array('modificar', $_views);
//$permiso_editar = in_array('editar', $_views);
$permiso_imprimir 	= in_array('imprimir', $_views);
$permiso_eliminar 	= in_array('eliminar', $_views);
$permiso_bloquear 	= in_array('bloquear', $_views);
$permiso_desbloquear = in_array('desbloquear', $_views);

$permiso_resetear = in_array('resetear', $_views);

$permiso_crear_curso = in_array('crear-curso', $_views);
$permiso_crear_masivo = in_array('crear-masivo', $_views);

?>
<?php require_once show_template('header-design'); ?>
 
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"> 
        <div class="page-header">
            <h2 class="pageheader-title">Usuarios</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Administración</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Usuarios</a></li>
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
										<a href="?/usuarios/crear" class="dropdown-item">Crear usuario</a>
										<?php endif ?>
										<?php if ($permiso_crear_masivo) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/usuarios/crear-masivo" class="dropdown-item">Crear masivo</a>
										<?php endif ?>
										<?php if ($permiso_crear_curso) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/usuarios/crear-curso" class="dropdown-item">Crear por curso</a>
										<?php endif ?>
										<?php if ($permiso_eliminar) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/usuarios/eliminar" class="dropdown-item" data-grupo-eliminar="true">Eliminar usuarios</a>
										<?php endif ?>
										<?php if ($permiso_bloquear) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/usuarios/bloquear" class="dropdown-item" data-grupo-bloquear="true">Bloquear/Desbloquear Grupal</a>
										<?php endif ?>
										<?php if ($permiso_bloquear) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/usuarios/bloquear" class="dropdown-item" data-grupo-bloquear="true">Bloquear usuarios</a>
										<?php endif ?>
										<?php if ($permiso_desbloquear) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/usuarios/desbloquear" class="dropdown-item" data-grupo-desbloquear="true">Desbloquear usuarios</a>
										<?php endif ?>
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/usuarios/imprimir" class="dropdown-item" target="_blank">Imprimir usuarios</a>
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
				<?php if ($usuarios) : ?>
				<div class="table-responsive">
				<table id="table" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
					<thead>
					<tr class="active">
						<th class="text-nowrap">#</th>
						<th class="text-nowrap">Avatar</th>
						<th class="text-nowrap">Usuario</th>
						<th class="text-nowrap">Correo</th>
						<th class="text-nowrap">Rol</th>
						<th class="text-nowrap">Activo</th>
						<th class="text-nowrap">Actividad</th>						
						<th class="text-nowrap">Persona</th>
						<?php if ($id_rol==1 || $id_rol==2 ) : ?>						
							<th class="text-nowrap">Código</th>
						<?php else: ?>
							<th class="text-nowrap">Código</th>
						<?php endif ?>
						<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar || $permiso_bloquear || $permiso_desbloquear) : ?>
						<th class="text-nowrap text-center">Opciones</th>
						<?php endif ?>
						<!-- <?php if ($permiso_eliminar || $permiso_bloquear || $permiso_desbloquear) : ?>
						<th class="text-nowrap">
							<span class="hidden">Selección</span>
							<span class="glyphicon glyphicon-check"></span>
						</th>
						<?php endif ?> -->
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
						<li>No existen usuarios registrados en la base de datos.</li>
						<li>Para crear nuevos usuarios debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
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
	  	<input type="hidden" id="usuario_eliminar">
        <p>¿Esta seguro de eliminar usuario <span id="texto_usuario"></span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn_eliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<!--modal para desbloquear-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_desbloquear">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
	  	<input type="hidden" id="usuario_desbloquear">
        <p>¿Esta seguro de desbloquear usuario <span id="texto_desbloquear"></span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn_desbloquear">Desbloquear</button>
      </div>
    </div>
  </div>
 </div>

<!--modal para bloquear-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_bloquear">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
	  	<input type="hidden" id="usuario_bloquear">
        <p>¿Esta seguro de bloquear usuario <span id="texto_bloquear"></span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn_bloquear">Bloquear</button>
      </div>
    </div>
  </div>
</div>

<!--modal para bloquear-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_resetear">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
	  	<input type="hidden" id="usuario_resetear">
        <p>¿Esta seguro de Resetear Código Sesión de Usuario <span id="texto_resetear"></span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn_resetear">Resetear</button>
      </div>
    </div>
  </div>
</div>

<?php $fecha=date('Y-m-d'); $fecha_actual= json_encode($fecha); ?>
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

<script>

$(function () {	
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/usuarios/crear';
					//$('#modal_gestion').modal('toggle');
				break;
			}
		}
	});
	<?php endif ?>
});
// var columns=[
// 	{data: 'id_gestion'},
// 	{data: 'gestion'},
// 	{data: 'inicio_gestion'},
// 	{data: 'final_gestion'},
// 	{data: 'inicio_vacaciones'},
// 	{data: 'final_vacaciones'}
// ];

var columns=[
	{data: 'id_user'},
	{data: 'avatar'},
	{data: 'username'},
	{data: 'email'},
	{data: 'rol'},
	{data: 'active'},
	{data: 'actividad'},
	{data: 'persona'},
	{data: 'codigo'}
];

var cont = 0;
var dataTable = $('#table').DataTable({
	language: dataTableTraduccion,
	searching: true,
	paging:true,
	"lengthChange": true,
	"responsive": true,
	ajax: {
		url: '?/usuarios/busqueda',
		dataSrc: '',		
		type:'POST',
		dataType: 'json'
	},
	columns: columns,

	"columnDefs": [
			{
				"render": function (data, type, row){
					var result 		= "";
					var contenido 	= row['id_user'] + "*" + row['avatar']+ "*" + row['username']+ "*" + row['email']+ "*" + row['rol']+ "*" + row['active']+ "*" + row['persona'];
					var usuario 	= row['id_user'];
					// var r   		= row['rols'].split("@");
					// var id_rol   = r[0];
					// var rol      = r[1];
					//"<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash'></span></a><?php endif ?>";
					result+="<?php if ($permiso_ver) : ?><a href='?/usuarios/ver/"+usuario+"' class='btn btn-info btn-xs' title='Ver Usuario'><span class='icon-eye'></span></a><?php endif ?> &nbsp" +
							"<?php if ($permiso_editar) : ?><a href='?/usuarios/modificar/"+usuario+"' class='btn btn-warning btn-xs' title='Modificar Usuario' style='color:white'><span class='icon-note'></span></a><?php endif ?> &nbsp" +
					"<?php if ($permiso_bloquear) : ?><a href='#' class='btn btn-danger btn-xs' title='Bloquear Usuario' onclick='abrir_bloquear("+'"'+contenido+'"'+")'><span class='fas fa-ban'></span></a><?php endif ?> &nbsp" +
					"<?php if ($permiso_desbloquear) : ?><a href='#' class='btn btn-success btn-xs' title='Desbloquear Usuario' onclick='abrir_desbloquear("+'"'+contenido+'"'+")'><span class='fas fa-check-circle'></span></a><?php endif ?> &nbsp" +
					"<?php if ($permiso_desbloquear) : ?><a href='#' class='btn btn-dark btn-xs'title='Resetear Código Usuario' onclick='abrir_resetear("+'"'+contenido+'"'+")'><span class='far fa-id-badge'></span></a><?php endif ?> &nbsp";
					
					return result;
				},
				"targets": 9
			},
			{
				"render": function (data, type, row){
					cont = cont +1;
					return cont;
				},
				"targets": 0
			},
			{
				"render": function(data, type, row) {
					var imagen = "";
					if (row['avatar'] == null  || row['avatar'] == "") {
						foto = "assets/imgs/avatar.jpg";
					} else {
						foto = "files/profiles/estudiantes/" + row['avatar'] + ".jpg";
					}
					imagen += "<img src='" + foto + "' class='img-rounded cursor-pointer' data-toggle='modal' data-target='#modal_mostrar' data-modal-size='modal-md' data-modal-title='Imagen' width='64' height='64'>";
					return imagen;
				},
				"targets": 1
			},
			{
				"render": function(data, type, row) {
					switch (row['active']) {
						case 's':
							return "<span class='badge badge-success text-center'>SI</span>";
							break;
						case 'n':
							return "<span class='badge badge-danger  text-center'>NO</span>";
							break;
					}
				},
				"targets": 5
			},
			{
				"render": function(data, type, row) {

					if (row['actividad'] == 'NO'){
		                return "<span class='badge badge-danger text-center'>Sin<br>actividad</span>";
		            }else{
		                return "<span class='badge badge-warning text-center' style='color:#fff'>"+row['actividad']+"</span>";
		            } 
				},
				"targets": 6
			},
			{
				"render": function(data, type, row) {

					return "<?php if ($rol_id==1 || $rol_id==2) : ?>"+ row['codigo']+" <?php else: ?> <?php endif ?> &nbsp";
					
				},
				"targets": 8
			},
	],
    "footerCallback": function ( row, data, start, end, display ) {
	    var api = this.api(), data;
	    // Remove the formatting to get integer data for summation
	    var intVal = function ( i ) {
	        return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
	    };
	}
});

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
<?php endif ?> 

<?php if ($permiso_eliminar) : ?>
function abrir_eliminar(contenido){
	$("#modal_eliminar").modal("show");
	var d = contenido.split("*");
	$("#usuario_eliminar").val(d[0]);
	$("#texto_usuario").text(d[1]);
}

$("#btn_eliminar").on('click', function(){
	id_usuario = $("#usuario_eliminar").val();
	$.ajax({
		url: '?/usuarios/eliminar',
		type:'POST',
		data: {'id_usuario':id_usuario},
		success: function(resp){
			switch(resp){
				case '1': $("#modal_eliminar").modal("hide");
							dataTable.ajax.reload();
							alertify.success('Se elimino el usuario correctamente');break;
				case '2': $("#modal_eliminar").modal("hide");
							alertify.error('No se pudo eliminar el usuario intente nuevamente');
							break;
			}
		}
	})
})
<?php endif ?>

<?php if ($permiso_desbloquear) : ?>
function abrir_desbloquear(contenido){
	$("#modal_desbloquear").modal("show");
	var d = contenido.split("*");
	$("#usuario_desbloquear").val(d[0]);
	$("#texto_desbloquear").text(d[2]);
}

$("#btn_desbloquear").on('click', function(){
	id_usuario = $("#usuario_desbloquear").val();
	$.ajax({
		url: '?/usuarios/desbloquear',
		type:'POST',
		data: {'id_usuario':id_usuario},
		success: function(resp){
			switch(resp){
				case '1': $("#modal_desbloquear").modal("hide");
							dataTable.ajax.reload();
							alertify.success('Se desbloqueó el usuario correctamente');break;
				case '2': $("#modal_desbloquear").modal("hide");
							alertify.error('No se pudo desbloquear el usuario intente nuevamente');
							break;
			}
		}
	})
})
<?php endif ?>

<?php if ($permiso_bloquear) : ?>
function abrir_bloquear(contenido){
	$("#modal_bloquear").modal("show");
	var d = contenido.split("*");
	$("#usuario_bloquear").val(d[0]);
	$("#texto_bloquear").text(d[2]);
}

$("#btn_bloquear").on('click', function(){
	id_usuario = $("#usuario_bloquear").val();
	$.ajax({
		url: '?/usuarios/bloquear',
		type:'POST',
		data: {'id_usuario':id_usuario},
		success: function(resp){
			switch(resp){
				case '1': $("#modal_bloquear").modal("hide");
							dataTable.ajax.reload();
							alertify.success('Se bloqueó el usuario correctamente');break;
				case '2': $("#modal_bloquear").modal("hide");
							alertify.error('No se pudo bloquear el usuario intente nuevamente');
							break;
			}
		}
	})
})
<?php endif ?>

<?php if ($permiso_resetear) : ?>
function abrir_resetear(contenido){
	$("#modal_resetear").modal("show");
	var d = contenido.split("*");
	$("#usuario_resetear").val(d[0]);
	$("#texto_resetear").text(d[2]);
}

$("#btn_resetear").on('click', function(){
	id_usuario = $("#usuario_resetear").val();
	$.ajax({
		url: '?/usuarios/resetear',
		type:'POST',
		data: {'id_usuario':id_usuario},
		success: function(resp){
			switch(resp){
				case '1': $("#modal_resetear").modal("hide");
							dataTable.ajax.reload();
							alertify.success('Se reseteó el usuario correctamente');break;
				case '2': $("#modal_resetear").modal("hide");
							alertify.error('No se pudo resetear el usuario intente nuevamente');
							break;
			}
		}
	})
})
<?php endif ?>
</script>
