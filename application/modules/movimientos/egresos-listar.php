<?php 

// Obtiene las fechas inicial y final
// $fecha_inicial = str_replace('/', '-', now($_format));
// $fecha_final = str_replace('/', '-', now($_format));

// Obtiene los formatos
$formato_textual = get_date_textual($_format);
$formato_numeral = get_date_numeral($_format); 

// Verifica si existen los parametros
// if (sizeof($_params) == 2) {
// 	// Verifica el tipo de los parametros
// 	if (!is_date($_params[0]) || !is_date($_params[1])) {
// 		// Redirecciona la pagina
// 		redirect('?/movimientos/egresos-listar/' . $fecha_inicial . '/' . $fecha_final);
// 	}
// } else {
// 	// Redirecciona la pagina
// 	redirect('?/movimientos/egresos-listar/' . $fecha_inicial . '/' . $fecha_final);
// } 
  
// Obtiene los parametros
// $fecha_inicial = date_encode($_params[0]);
// $fecha_final = date_encode($_params[1]);

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

// Obtiene los egresos
$egresos = $db->select("m.*, concat(p.nombres, ' ', p.primer_apellido, ' ', p.segundo_apellido) as empleado")
				->from('caj_movimientos m')
				->join('per_asignaciones e', 'm.asignacion_id = e.id_asignacion', 'left')
				->join('sys_persona p', 'e.persona_id = p.id_persona', 'left')
				->where('m.tipo', 'e')
				->where('p.id_persona', $_user['persona_id'])
				->between('m.fecha_movimiento', $fecha_inicial, $fecha_final)
				->order_by('m.fecha_movimiento desc, m.hora_movimiento desc')
				->fetch();

//var_dump($egresos);exit();
// Obtiene la moneda oficial
$moneda = $db->from('gen_monedas')->where('principal', 's')->fetch_first();
$moneda = ($moneda) ? '(' . $moneda['codigo'] . ')' : '';

// Almacena los permisos en variables
$permiso_crear 		= in_array('egresos-crear', $_views);
$permiso_modificar 	= in_array('egresos-modificar', $_views);
$permiso_eliminar 	= in_array('egresos-eliminar', $_views);
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
            <h2 class="pageheader-title">Egresos de efectivo</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Caja</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Egresos de efectivo</a></li>
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
                                        <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="?/movimientos/egresos-crear"><span class="glyphicon glyphicon-plus"></span> Nuevo</a>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
            <hr>
			<?php if ($message = get_notification()) : ?>
			<div class="alert alert-<?= $message['type']; ?>">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong><?= $message['title']; ?></strong>
				<p><?= $message['content']; ?></p>
			</div>
			<?php endif ?>
            <div class="card-body">
                <!-- ============================================================== -->
                <!-- datos --> 
                <!-- ============================================================== -->
				<?php if ($egresos) : ?>
				<table id="table" class="table table-bordered table-condensed table-striped table-hover">
					<thead>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<th class="text-nowrap">Comprobante</th>
							<th class="text-nowrap">Fecha</th>
							<th class="text-nowrap">Concepto</th>
							<th class="text-nowrap">Monto <?= escape($moneda); ?></th>
							<th class="text-nowrap">Observación</th>
							<th class="text-nowrap">Empleado</th>
							<?php if ($permiso_modificar || $permiso_eliminar) : ?>
							<th class="text-nowrap">Opciones</th>
							<?php endif ?>
						</tr>
					</thead>
					<tfoot>
						<tr class="active">
							<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
							<th class="text-nowrap text-middle" data-datafilter-filter="true">Comprobante</th>
							<th class="text-nowrap text-middle" data-datafilter-filter="true">Fecha</th>
							<th class="text-nowrap text-middle" data-datafilter-filter="true">Concepto</th>
							<th class="text-nowrap text-middle" data-datafilter-filter="true">Monto <?= escape($moneda); ?></th>
							<th class="text-nowrap text-middle" data-datafilter-filter="true">Observación</th>
							<th class="text-nowrap text-middle" data-datafilter-filter="true">Empleado</th>
							<?php if ($permiso_modificar || $permiso_eliminar) : ?>
							<th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
							<?php endif ?>
						</tr>
					</tfoot>
					<tbody>
						<?php foreach ($egresos as $nro => $egreso) : ?>
						<tr>
							<th class="text-nowrap"><?= $nro + 1; ?></th>
							<td class="text-nowrap"><?= escape($egreso['nro_comprobante']); ?></td>
							<td class="text-nowrap">
								<span><?= escape(date_decode($egreso['fecha_movimiento'], $_institution['formato'])); ?></span>
								<span class="text-primary"><?= escape($egreso['hora_movimiento']); ?></span>
							</td>
							<td><?= escape($egreso['concepto']); ?></td>
							<td class="text-nowrap text-right" data-monto="<?= $egreso['monto']; ?>"><?= escape($egreso['monto']); ?></td>
							<td><?= escape($egreso['observacion']); ?></td>
							<td class="text-nowrap"><?= escape($egreso['empleado']); ?></td>
							<?php if ($permiso_modificar || $permiso_eliminar) : ?>
							<td class="text-nowrap">
								<?php if ($permiso_modificar) : ?>	
								<a href="?/movimientos/egresos-modificar/<?= $egreso['id_movimiento']; ?>" data-toggle="tooltip" data-title="Modificar egreso"><i class="glyphicon glyphicon-edit"></i></a>
								<?php endif ?>
								<?php if ($permiso_eliminar) : ?>	
								<a href="?/movimientos/egresos-eliminar/<?= $egreso['id_movimiento']; ?>" data-toggle="tooltip" data-title="Eliminar egreso" data-eliminar="true"><i class="glyphicon glyphicon-trash"></i></a>
								<?php endif ?>
							</td>
							<?php endif ?>
						</tr>
						<?php endforeach ?>
					</tbody>
				</table>
				<br>
				<div class="well">
					<p class="lead margin-none">
						<b>Empleado:</b>
						<span><?= escape($_user['nombres'] . ' ' . $_user['primer_apellido'] . ' ' . $_user['segundo_apellido']); ?></span>
					</p>
					<p class="lead margin-none">
						<b>Total:</b>
						<u id="monto">0.00</u>
						<span><?= escape($moneda); ?></span>
					</p>
				</div>
				<?php else : ?>
				<div class="alert alert-info margin-none">
					<strong>Atención!</strong>
					<ul>
						<li>No existen egresos registrados.</li>
						<li>Puede buscar egresos por rango de fechas, verifique que las fechas sean válidas.</li>
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
		<form method="post" action="?/movimientos/egresos-listar" id="form_cambiar" class="modal-content loader-wrapper" autocomplete="off">
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

    // $('#table').DataFilter({
    //   filter: true,
    //   name: 'conceptos',
    // });
    $('#table').on('search.dt order.dt page.dt length.dt', function () {
    	console.log('hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh');
		var suma = 0;
		$('[data-monto]:visible').each(function (i) {
			var monto = parseFloat($(this).attr('data-monto'));
			suma = suma + monto;
		});
		$('#monto').text(suma.toFixed(2));
	}).DataFilter({
		filter: true,
		name: 'movimientos',
		reports: 'excel|word|pdf|html',
		values: {
			stateSave: true
		}
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
      var ruta_imprimir = '?/movimientos/egresos-listar' + inicial_fecha + final_fecha;
      // console.log('gggg');
      // $("#imprimir").attr('href', ruta_imprimir);
      // var g = $("#imprimir").attr('href');
      // console.log(g);
      window.location = '?/movimientos/egresos-listar' + inicial_fecha + final_fecha;
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
