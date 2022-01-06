<?php

// Obtiene la cadena csrf
$csrf = set_csrf();
$id_gestion = $_gestion['id_gestion'];
$id_usuario = $_user['id_user'];

// Obtiene la moneda oficial
$moneda = $db->from('inv_monedas')->where('oficial', 'S')->fetch_first();
$moneda = ($moneda) ? '(' . $moneda['sigla'] . ')' : '';

// Obtiene los formatos para la fecha
$formato_textual = get_date_textual($_institution['formato']);
$formato_numeral = get_date_numeral($_institution['formato']);

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

if($_user['id_rol'] == 1 || $_user['id_rol'] == 2){
// Obtiene todos los pagos realizados
$cobros = $db->query("SELECT pg.*, sp.id_persona, CONCAT(sp.nombres,' ',sp.primer_apellido,' ' ,sp.segundo_apellido) nombre_empleado
FROM pen_pensiones_estudiante_general pg
INNER JOIN sys_users su ON pg.usuario_registro = su.id_user
INNER JOIN sys_persona sp ON su.persona_id = sp.id_persona
WHERE pg.documento_pago = 'NOTA'
AND pg.gestion_id = $id_gestion
AND pg.fecha_general >= '$fecha_inicial'
AND pg.fecha_general <= '$fecha_final'
ORDER BY pg.fecha_general ASC")->fetch();
}else{
// Obtiene todos los pagos realizados
$cobros = $db->query("SELECT pg.*, sp.id_persona, CONCAT(sp.nombres,' ', sp.primer_apellido,' ', sp.segundo_apellido) nombre_empleado
FROM pen_pensiones_estudiante_general pg
INNER JOIN sys_users su ON pg.usuario_registro = su.id_user
INNER JOIN sys_persona sp ON su.persona_id = sp.id_persona
WHERE pg.documento_pago = 'NOTA'
AND pg.usuario_registro = $id_usuario
AND pg.gestion_id = $id_gestion
AND pg.fecha_general >= '$fecha_inicial'
AND pg.fecha_general <= '$fecha_final'
ORDER BY pg.fecha_general ASC")->fetch();  
}
// Obtiene los permisos
$permiso_ver = in_array('ver', $_views);
$permiso_imprimir = in_array('imprimir', $_views);  
$permiso_cambiar = true;
?>

<?php require_once show_template('header-design'); ?>
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"> 
        <div class="page-header">
            <h2 class="pageheader-title">Mis Notas </h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Pagos y Cobranzas</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Notas de Remisión</a></li>
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
                                        <?php if ($permiso_imprimir) : ?>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" data-cambiar="true"><span class="glyphicon glyphicon-print"></span> Cambiar Fecha</a>
                                        <?php endif ?> 
                                        <?php if ($permiso_imprimir) : ?>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="?/s-pago-computarizado/crear"><span class="glyphicon glyphicon-print"></span> Notas de Remisión</a>
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
                <?php if ($cobros) : ?>
                <div class="table-responsive">
                <table id="table" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
                    <thead>
                        <tr class="active">
                            <th class="text-nowrap">Nº</th>
                            <th class="text-nowrap">Fecha</th>
                            <th class="text-nowrap">Tipo</th>
                            <th class="text-nowrap">Cliente</th>
                            <th class="text-nowrap">NIT/CI</th>
                            <th class="text-nowrap">Nro. Factura</th>
                            <th class="text-nowrap">Monto Total</th>
                            <th class="text-nowrap">Registros</th>
                            <th class="text-nowrap">Empleado</th>
                            <?php if ($permiso_ver) : ?>
                            <th class="text-nowrap">Opciones</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="active">
                            <th class="text-nowrap text-middle" data-datafilter-filter="false"></th>
                            <th class="text-nowrap text-middle">Fecha</th>
                            <th class="text-nowrap text-middle">Tipo</th>
                            <th class="text-nowrap text-middle">Cliente</th>
                            <th class="text-nowrap text-middle">NIT/CI</th>
                            <th class="text-nowrap text-middle">Nro. Factura</th>
                            <th class="text-nowrap text-middle">Monto Total</th>
                            <th class="text-nowrap text-middle">Registros</th>
                            <th class="text-nowrap text-middle">Empleado</th>
                            <?php if ($permiso_ver) : ?>
                            <th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
                            <?php endif ?>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($cobros as $nro => $cobro) : ?>
                            <tr>
                                <th class="text-nowrap text-middle text-center"><?= $nro + 1; ?></th>
                                <td class="text-nowrap text-middle text-center"><?= escape($cobro['fecha_general']); ?></td>
                                <td class="text-nowrap text-middle text-center"><?= escape($cobro['documento_pago']); ?></td>
                                <td class="text-nowrap text-middle text-justify"><?= escape($cobro['nombre_cliente']); ?></td>
                                <td class="text-nowrap text-middle text-center"><?= escape($cobro['nit_ci']); ?></td>
                                <td class="text-nowrap text-middle text-center"><?= escape($cobro['nro_factura']); ?></td>
                                <td class="text-nowrap text-middle text-right"><?= escape(number_format($cobro['monto_total'],2)); ?></td>
                                <td class="text-nowrap text-middle text-center"><?= escape($cobro['nro_registros']); ?></td>
                                <td class="text-nowrap text-middle text-justify"><?= escape($cobro['nombre_empleado']); ?></td>

                                <?php if ($permiso_ver) : ?>
                                <td class="text-nowrap text-middle text-center">
                                    <?php if ($permiso_ver) : ?>
                                    <a href="?/s-pago-nota/ver/<?= $cobro['id_general']; ?>" data-toggle="tooltip" data-title="Ver cobro" class="btn btn-info btn-xs"><span class="icon-magnifier"></span></a>
                                    <?php endif ?>
                                </td>
                                <?php endif ?>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                </div>
                <?php else : ?>

                <div class="alert alert-info">
                    <strong>Atención!</strong>
                    <ul>
                        <li>No existen notas de remisión registrados en la base de datos.</li>
                        <li>Para crear nuevos notas de remisión debe hacer clic en el botón de acciones y seleccionar la opción Notas de Remisión.</li>
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
<?php if ($permiso_cambiar) : ?>

    <div id="modal_fecha" class="modal fade">
      <div class="modal-dialog">
        <form id="form_fecha" class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Cambiar fecha</h4>
          </div>
    
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="inicial_fecha">Fecha inicial:</label>
                  <input type="date" name="inicial" value="" id="inicial_fecha" class="form-control">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="final_fecha">Fecha final:</label>
                  <input type="date" name="final" value="" id="final_fecha" class="form-control">
                </div>
              </div>
            </div>
          </div>
    
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-aceptar="true">
              <span class="glyphicon glyphicon-ok"></span>
              <span>Aceptar</span>
            </button>
            <button type="button" class="btn btn-default" data-cancelar="true">
              <span class="glyphicon glyphicon-remove"></span>
              <span>Cancelar</span>
            </button>
          </div>
        </form>
      </div>
    </div>

<?php endif ?>
<!-- ============================================================== -->
<!-- row -->
<!-- ============================================================== -->
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

  $.validate({
    form: '#form_fecha',
    modules: 'date',
    onSuccess: function () {
      var inicial_fecha = $.trim($('#inicial_fecha').val());
      var final_fecha = $.trim($('#final_fecha').val());
      final_fecha = (final_fecha != '') ? ('/' + final_fecha ) : '';
      inicial_fecha = (inicial_fecha != '') ? ('/' + inicial_fecha) :''; 
      window.location = '?/s-pago-nota/listar' + inicial_fecha + final_fecha;
    }

  });

  var $form_fecha = $('#form_fecha');
  var $modal_fecha = $('#modal_fecha');
  
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
    $('#modal_fecha').modal({
      backdrop: 'static'
    });

  });

<?php } ?>

<?php if ($permiso_anular) { ?>

  var $form_anular = $('#form_anular');
  var $modal_anular = $('#modal_anular');
  
  $modal_anular.on('shown.bs.modal', function () {
    $modal_anular.find('[data-aceptar]').focus();
  });
  
  $modal_anular.find('[data-cancelar]').on('click', function () {
    $modal_anular.modal('hide');
  });
  
  $('[data-anular]').on('click', function () {
    $('#modal_anular').modal({
       backdrop: 'static'
    });
    var id_factura = $(this).attr('data-factura');
    $('#id_general').val(id_factura);
    var fecha_ini = '<?= $fecha_inicial;?>';
    var fecha_fin = '<?= $fecha_final;?>';
    $('#fecha0').val(fecha_ini);
    $('#fecha1').val(fecha_fin);
  });

<?php } ?>
</script>
