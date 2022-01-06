<?php
 
// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los estudiantes
$estudiantes = $db->select('z.*')->from('vista_estudiantes z')->order_by('z.id_estudiante', 'asc')->fetch();

// Obtiene los permisos  
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_editar = in_array('editar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);
$permiso_inscripcion = in_array('inscripcion-estudiante-tutor', $_views); 

?>
<?php require_once show_template('header-design'); ?>
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Cupos Disponibles</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Secretaria</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Inscripción</a></li>
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
										<a href="?/s-inscripciones/crear" class="dropdown-item">Registrar Estudiante</a>
                                        <?php endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-nivel-academico/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
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
				<?php if ($estudiantes) : ?>
				<div class="table-responsive">
				<table id="table" class="table table-bordered table-condensed table-striped table-hover">
					<thead>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<th class="text-nowrap">Nivel Académico</th>
							<th class="text-nowrap">Aula</th>
							<th class="text-nowrap">Paralelo</th>
							<th class="text-nowrap">Capacidad</th>
							<th class="text-nowrap">Inscritos</th>
							<th class="text-nowrap">Vacantes</th>
						</tr>
					</thead>
					<tfoot>
						<tr class="active">
							<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
							<th class="text-nowrap text-middle">Nivel Académico</th>
							<th class="text-nowrap text-middle">Aula</th>
							<th class="text-nowrap text-middle">Paralelo</th>
							<th class="text-nowrap text-middle">Capacidad</th>
							<th class="text-nowrap text-middle">Inscritos</th>
							<th class="text-nowrap text-middle">Vacantes</th>
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
						<li>No existen inscripción registrados en la base de datos.</li>
						<li>Para crear nuevos inscripción debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
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
	  	<input type="hidden" id="estudiante_eliminar">
        <p>¿Esta seguro de eliminar estudiante <span id="texto_estudiante"></span>?</p>
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
<!-- <script src="<?= js; ?>/jquery.dataFilters.min.js"></script> -->
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/educheck.js"></script>
<!--script src="<?= $ruta?>/s-gestion-escolar.js"></script-->
<?php require_once show_template('footer-design'); ?>
<script>
$(function () {
	var columns=[
		{data: 'nombre_nivel'},
		{data: 'nombre_nivel'},
		{data: 'nombre_aula'},
		{data: 'nombre_paralelo'},
		{data: 'capacidad'},
		{data: 'contador'},
		{data: 'vacantes'}
	];
	var cont = 0;
	//function listarr(){
	var dataTable = $('#table').DataTable({
		language: dataTableTraduccion,
		searching: true,
		paging:true,
		"lengthChange": true,
		"responsive": true,
		ajax: {
			url: '?/s-inscripciones/cupo-busqueda',
			dataSrc: '',
			type:'POST',
			dataType: 'json'
		},
		columns: columns,

		"columnDefs": [
				{
						"render": function (data, type, row) {
							cont = cont +1;
							return cont;
						},
						"targets": 0
				}
		]
	})
});
</script>