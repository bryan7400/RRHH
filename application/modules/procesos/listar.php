<?php 

// Obtiene las fechas inicial y final
// $fecha_inicial = str_replace('/', '-', now($_format));
// $fecha_final = str_replace('/', '-', now($_format));

// Obtiene los formatos
$formato_textual = get_date_textual($_format);
$formato_numeral = get_date_numeral($_format); 

// Obtiene el rango de fechas
$gestion = date('Y');
$gestion_base = date('Y-m-d');
//$gestion_base = ($gestion - 16) . date('-m-d');
$gestion_limite = ($gestion + 16) . date('-m-d');

// Obtiene fecha inicial
$fecha_inicial = (isset($_params[0])) ? $_params[0] : $gestion_base;
$fecha_inicial = (is_date($fecha_inicial)) ? $fecha_inicial : $gestion_base;
$fecha_inicial = date_encode($fecha_inicial);

// Obtiene fecha final
$fecha_final = (isset($_params[1])) ? $_params[1] : $gestion_limite;
$fecha_final = (is_date($fecha_final)) ? $fecha_final : $gestion_limite;
$fecha_final = date_encode($fecha_final); 
//var_dump($fecha_inicial, $fecha_final);exit();

// Obtiene los procesos
$proceso_consulta = "SELECT* FROM sys_procesos p
inner join sys_users u ON p.usuario_id = u.id_user
inner join sys_roles r ON u.rol_id = r.id_rol
where p.fecha_proceso >= '$fecha_inicial'
and p.fecha_proceso <= '$fecha_final'
ORDER BY p.fecha_proceso, p.hora_proceso desc";
$procesos = $db->query($proceso_consulta)->fetch();
// Arreglo de procesos
$arreglo_procesos = array('c' => 'Creación', 'r' => 'Visualización', 'u' => 'Modificación', 'd' => 'Eliminación');

// Arreglo de niveles
$arreglo_niveles = array('l' => 'Bajo', 'm' => 'Medio', 'h' => 'Alto');
$permiso_cambiar = true;
$permiso_imprimir = true;
?>
<?php require_once show_template('header-design'); ?>
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Administración de Procesos</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Administración</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Administración de Procesos</a></li>
                        <!-- <li class="breadcrumb-item active" aria-current="page">Listar</li> -->
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
                                        <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" data-cambiar="true"><span class="glyphicon glyphicon-print"></span> Cambiar Fecha</a>
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
            

	<?php if ($procesos) : ?>
	<table id="table" class="table table-bordered table-condensed table-striped table-hover">
		<thead>
			<tr class="active">
				<th class="text-nowrap">#</th>
				<th class="text-nowrap">Fecha</th>
				<th class="text-nowrap">Hora</th>
				<th class="text-nowrap">Proceso</th>
				<th class="text-nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nivel&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
				<th class="text-nowrap">Detalle</th>
				<th class="text-nowrap">Ruta</th>
				<th class="text-nowrap">Usuario</th>
				<th class="text-nowrap">Rol</th>
			</tr>
		</thead>
		<tfoot>
			<tr class="active">
				<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
				<th class="text-nowrap text-middle">&nbsp;&nbsp;&nbsp;Fecha&nbsp;&nbsp;</th>
				<th class="text-nowrap text-middle">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hora&nbsp;&nbsp;&nbsp;</th>
				<th class="text-nowrap text-middle">Proceso</th>
				<th class="text-nowrap text-middle">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nivel&nbsp;&nbsp;&nbsp;</th>
				<th class="text-nowrap text-middle">Detalle</th>
				<th class="text-nowrap text-middle">Ruta</th>
				<th class="text-nowrap text-middle">Usuario</th>
				<th class="text-nowrap text-middle">Rol</th>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($procesos as $nro => $proceso) : ?>
			<tr>
				<th class="text-nowrap"><?= $nro + 1; ?></th>
				<td class="text-nowrap"><?= date_decode($proceso['fecha_proceso'], $_format); ?></td>
				<td class="text-nowrap">&nbsp;&nbsp;&nbsp;<?= escape($proceso['hora_proceso']); ?>&nbsp;&nbsp;&nbsp;</td>
				<td class="text-nowrap"><?= escape($arreglo_procesos[$proceso['proceso']]); ?></td>
				<td class="text-nowrap">&nbsp;&nbsp;&nbsp;<?= escape($arreglo_niveles[$proceso['nivel']]); ?>&nbsp;&nbsp;&nbsp;</td>
				<td><?= escape($proceso['detalle']); ?></td>
				<td class="text-nowrap text-danger"><?= escape($proceso['direccion']); ?></td>
				<td class="text-nowrap"><?= escape($proceso['username']); ?></td>
				<td class="text-nowrap"><?= escape($proceso['rol']); ?></td>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	<?php else : ?>
	<div class="alert alert-info margin-none">
		<strong>Atención!</strong>
		<ul>
			<li>No existen procesos registrados.</li>
			<li>Puede buscar procesos por rango de fechas, verifique que las fechas sean válidas.</li>
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


<!-- Modal cambiar inicio -->
<div id="modal_cambiar" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/procesos-virtual/listar" id="form_cambiar" class="modal-content loader-wrapper" autocomplete="off">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h4 class="modal-title">Cambiar fecha</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="inicial_fecha" class="control-label">Fecha inicial:</label>
					<input type="date" value="" name="inicial" id="inicial_fecha" class="form-control" data-validation="required date">
				</div>
				<div class="form-group">
					<label for="final_fecha" class="control-label">Fecha final:</label>
					<input type="date" value="" name="final" id="final_fecha" class="form-control" data-validation="required date">
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">
					<span class="glyphicon glyphicon-share-alt"></span>
					<span>Cambiar</span>
				</button>
				<button type="reset" class="btn btn-default">
					<span class="glyphicon glyphicon-refresh"></span>
					<span>Restablecer</span>
				</button>
			</div>
			<div id="loader_cambiar" class="loader-wrapper-backdrop hidden">
				<span class="loader"></span>
			</div>
		</form>
	</div>
</div>
<!-- Modal cambiar fin -->

<script src="<?= js; ?>/modernizr.min.js"></script>
<script src="<?= js; ?>/jquery.dataTables.min.js"></script>
<script src="<?= js; ?>/dataTables.bootstrap.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/jquery.maskedinput.min.js"></script>
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<script src="<?= js; ?>/jquery.data-filters.min.js"></script>
<script src="<?= js; ?>/moment.min.js"></script>
<script src="<?= js; ?>/moment.es.js"></script>
<script src="<?= js; ?>/bootstrap-datetimepicker.min.js"></script>

<?php require_once show_template('footer-design'); ?>
<script>
$(function () {
    $('#table').DataFilter({
      filter: true,
      name: 'conceptos',
      reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
    });
});

<?php if ($permiso_cambiar) { ?>
  var formato = $('[data-formato]').attr('data-formato');
  var mascara = $('[data-mascara]').attr('data-mascara');
  var gestion = $('[data-gestion]').attr('data-gestion');
  var $inicial_fecha = $('#inicial_fecha');
  var $final_fecha = $('#final_fecha');
  //var $usuario = $('#curso');

  $.validate({
    form: '#form_cambiar',
    modules: 'date',
    onSuccess: function () {
      var inicial_fecha = $.trim($('#inicial_fecha').val());
      var final_fecha = $.trim($('#final_fecha').val());
      //var usuario = $.trim($('#usuario').val());
      // var vacio = gestion.replace(new RegExp('9', 'g'), '0');
      // inicial_fecha = inicial_fecha.replace(new RegExp('\\.', 'g'), '-');
      // inicial_fecha = inicial_fecha.replace(new RegExp('/', 'g'), '-');
      // final_fecha = final_fecha.replace(new RegExp('\\.', 'g'), '-');
      // final_fecha = final_fecha.replace(new RegExp('/', 'g'), '-');
      // vacio = vacio.replace(new RegExp('\\.', 'g'), '-');
      // vacio = vacio.replace(new RegExp('/', 'g'), '-');
      final_fecha = (final_fecha != '') ? ('/' + final_fecha ) : '';
      inicial_fecha = (inicial_fecha != '') ? ('/' + inicial_fecha) :''; 
      //usuario = (usuario != '') ? ('/' + usuario) :'';
      var ruta_imprimir = '?/procesos/listar' + inicial_fecha + final_fecha;
      // console.log('gggg');
      // $("#imprimir").attr('href', ruta_imprimir);
      // var g = $("#imprimir").attr('href');
      // console.log(g);
      window.location = '?/procesos/listar' + inicial_fecha + final_fecha;
    }
  });

  var $form_fecha = $('#form_cambiar');
  var $modal_fecha = $('#modal_cambiar');

  $form_fecha.on('submit', function (e) {
    e.preventDefault();
  });

  $modal_fecha.on('show.bs.modal', function () {
    $form_fecha.trigger('reset');
  });

  $modal_fecha.on('shown.bs.modal', function () {
    $modal_fecha.find('[data-aceptar]').focus();
  });

  $modal_fecha.find('[data-cancelar]').on('click', function () {
    $modal_fecha.modal('hide');
  });

  $modal_fecha.find('[data-aceptar]').on('click', function () {
    $form_fecha.submit();
  });

  $('[data-cambiar]').on('click', function () {
    $('#modal_cambiar').modal({
      backdrop: 'static'
    });
  });

<?php } ?>
</script>