<?php

// Obtiene la cadena csrf
$csrf = set_csrf();

/* Nombre del dominio */
$nombre_dominio = escape($_institution['nombre_dominio']);

// Obtiene los permisos
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_editar = in_array('modificar', $_views);
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
            <h2 class="pageheader-title">Familiares</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Inscripción</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Familiares</li>
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
										<a href="?/s-tutores/crear" class="dropdown-item">Registrar Familiar</a>
										<?php endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-tutores/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Familiares</a>
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
				<?php if (true) : ?>
				<div class="table-responsive">
				<table id="table" class="table table-bordered table-condensed table-striped table-hover" width="100%">
					<thead>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<th class="text-nowrap">Foto</th>
							<th class="text-nowrap">Primer Apellido</th>
							<th class="text-nowrap">Segundo Apellido</th>
							<th class="text-nowrap">Nombres</th>
							<th class="text-nowrap">Número de Documento</th>
							<th class="text-nowrap">Profesión</th>
							<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
							<th class="text-nowrap">Opciones</th>
							<?php endif ?>
						</tr>
					</thead>
					<tfoot>
						<tr class="active">
							<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
							<th class="text-nowrap text-middle">Foto</th>
							<th class="text-nowrap text-middle">Primer Apellido</th>
							<th class="text-nowrap text-middle">Segundo Apellido</th>
							<th class="text-nowrap text-middle">Nombres</th>
							<th class="text-nowrap text-middle">Número de Documento</th>
							<th class="text-nowrap text-middle">Profesión</th>
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
				<!--div class="alert alert-info">
					<strong>Atención!</strong>
					<ul>
						<li>No existen familiar registrados en la base de datos.</li>
						<li>Para crear nuevos familiar debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
					</ul>
				</div-->
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
<!-- <script src="<?= js; ?>/jquery.dataTables.min.js"></script>
<script src="<?= js; ?>/dataTables.bootstrap.min.js"></script> -->
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<script src="<?= js; ?>/jquery.dataFilters.min.js"></script>

<?php require_once show_template('footer-design'); ?>
<script>

var nombre_dominio = "<?=$nombre_dominio?>";

$(function () {
    var columns=[
	{data: 'id_familiar'},
	{data: 'foto'},
	{data: 'primer_apellido'},
	{data: 'segundo_apellido'},
	{data: 'nombres'},
	{data: 'numero_documento'},
	{data: 'profesion'}
];

//data = data + '&boton='+ 'listar_familiares';
var cont = 0;
//function listarr(){
var dataTable = $('#table').DataTable({
	language: dataTableTraduccion,
	searching: true,
	paging:true,
	"lengthChange": true,
	"responsive": true,
	ajax: {
		url: '?/s-tutores/procesos',
		//async: false,
		dataSrc: '',
		type:'POST',
		data: {'boton': 'listar_familiares'},
		dataType: 'json'
	},
	columns: columns,

	"columnDefs": [
			{
					"render": function (data, type, row) {
						var result = "";
						result+="<?php if ($permiso_ver) : ?><a href='?/s-tutores/ver/"+ row['id_familiar'] +"' class='btn btn-xs btn-info'><span class='fas fa-users'></span></a><?php endif ?> &nbsp"+
								"<?php if ($permiso_editar) : ?><a href='?/s-tutores/crear/"+ row['id_familiar'] +"' class='btn btn-xs btn-warning' style='color:white'><span class='icon-note'></span></a><?php endif ?> &nbsp";
						return result;
					},
					"targets": 7
			},
			{
					"render": function (data, type, row) {
						var imagen = "";
						if(row['foto'] == "" || row['foto'] == null){
							foto = "files/"+nombre_dominio+"/profiles/avatar.jpg";
						}else{
							foto = "files/"+nombre_dominio+"/profiles/familiares/" + row['foto'];
						}
						imagen += "<img src='"+ foto +"' class='img-rounded cursor-pointer' data-toggle='modal' data-target='#modal_mostrar' data-modal-size='modal-md' data-modal-title='Imagen' width='64' height='64'>";
						return imagen;
					},
					"targets": 1
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
	
});


</script>