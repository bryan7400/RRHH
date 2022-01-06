<?php

//obtiene el valor
$id_general = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene todos los pagos realizados
$general = $db->query("SELECT pg.*, sp.id_persona, CONCAT(sp.nombres, sp.primer_apellido, sp.segundo_apellido) nombre_empleado
FROM pen_pensiones_estudiante_general pg
INNER JOIN sys_users su ON pg.usuario_registro = su.id_user
INNER JOIN sys_persona sp ON su.persona_id = sp.id_persona
WHERE pg.id_general = '$id_general'
ORDER BY pg.fecha_general ASC")->fetch_first();

// Obtiene los detalles
$detalles = $db->query("SELECT*
FROM  pen_pensiones_estudiante_detalle d
inner JOIN pen_pensiones_estudiante pe ON d.pensiones_estudiante_id = pe.id_pensiones_estudiante
inner JOIN pen_pensiones_detalle pd ON pe.detalle_pension_id = pd.id_pensiones_detalle
inner JOIN pen_pensiones p ON pd.pensiones_id = p.id_pensiones
inner JOIN ins_inscripcion i ON pe.inscripcion_id = i.id_inscripcion
inner JOIN ins_aula_paralelo ap ON i.aula_paralelo_id = ap.id_aula_paralelo
inner JOIN ins_aula a ON ap.aula_id = a.id_aula
inner JOIN ins_paralelo ip ON ap.paralelo_id = ip.id_paralelo
inner JOIN ins_nivel_academico na ON i.nivel_academico_id = na.id_nivel_academico
inner JOIN ins_estudiante e ON i.estudiante_id = e.id_estudiante
inner JOIN sys_persona per ON e.persona_id = per.id_persona
WHERE d.general_id = '$id_general'
ORDER BY d.id_pensiones_estudiante_detalle ASC")->fetch();

// Obtiene los permisos
$permiso_imprimir = in_array('imprimir', $_views);  

?>
<?php require_once show_template('header-design'); ?>
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"> 
        <div class="page-header">
            <h2 class="pageheader-title">Mis Notas de Remisión</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Pagos y Cobranzas</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Notas de Remisión</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ver</li>
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
        <!-- ============================================================== -->
        <!-- datos --> 
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                <!-- ============================================================== -->
                <!-- sales traffice source  -->
                <!-- ============================================================== -->
                <div class="card">
                    <h3 class="card-header"> Información del Cobro </h3>
                    <div class="card-body p-1">
                        <ul class="traffic-sales list-group list-group-flush">
                            <li class="list-group-item"><span>Fecha y Hora</span><span class="traffic-sales-amount"><?= escape($general['fecha_general']); ?> <?= escape($general['hora_general']); ?></span></li>
                            <li class="list-group-item"><span>Cliente</span><span class="traffic-sales-amount"><?= escape($general['nombre_cliente']); ?></span></li>
                            <li class="list-group-item"><span>NIT/CI</span><span class="traffic-sales-amount"><?= escape($general['nit_ci']); ?></span></li>
                            <li class="list-group-item"><span>Tipo Documento</span><span class="traffic-sales-amount"><?= escape($general['documento_pago']); ?></span></li>
                            <li class="list-group-item"><span>Nro. Factura</span><span class="traffic-sales-amount"><?= escape($general['nro_factura']); ?></span></li>
                            <li class="list-group-item"><span>Descripción</span><span class="traffic-sales-amount"><?= escape($general['observacion']); ?></span></li>
                            <li class="list-group-item"><span>Monto Total</span><span class="traffic-sales-amount"><?= escape(number_format($general['monto_total'],2));?></span></li>
                            <li class="list-group-item"><span>Código Control</span><span class="traffic-sales-amount"><?= escape($general['codigo_control']); ?></span></li>
                            <li class="list-group-item"><span>Nro. Registro</span><span class="traffic-sales-amount"><?= escape($general['nro_registros']); ?></span></li>
                            <li class="list-group-item"><span>Empleado</span><span class="traffic-sales-amount"><?= escape($general['nombre_empleado']); ?></span></li>
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="#" class="btn-primary-link"></a>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12">
                <div class="card">
                    <h3 class="card-header">Detalle del Cobro</h3>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr class="border-0">
                                        <th class="border-0 text-center">Nº</th>
                                        <th class="border-0">Nombre Estudiante</th>
                                        <th class="border-0 text-center">Detalle</th>
                                        <th class="border-0 text-center">Importe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $total = 0;?>
                                    <?php foreach ($detalles as $nro => $detalle) : ?>
                                        <?php
                                                $total = $total + $detalle['monto'];
                                                $curso = $detalle['nombre_aula'].' '.$detalle['nombre_paralelo'].' '.$detalle['nombre_nivel']; 
                                        ?>
                                        <tr>
                                            <th class="text-nowrap text-middle text-center"><?= $nro + 1; ?></th>
                                            <td class="text-nowrap text-middle text-justify"><?= escape($detalle['nombres'].' '.$detalle['primer_apellido'].' '.$detalle['segundo_apellido']); ?>,<br> <i><?= $curso; ?> | <?= $detalle['nombre_turno']; ?></i></td>
                                            <td class="text-nowrap text-middle text-center"><?= escape($detalle['nombre_pension']); ?> <i>(cuota <?= escape($detalle['nro']); ?>)</i></td>
                                            <td class="text-nowrap text-middle text-center"><?= escape($detalle['monto']); ?></td>
                                        </tr>
                                    <?php endforeach ?>
                                    <tr>
                                    <td colspan="2"></td>
                                    <?php if($general['documento_pago'] == 'NOTA'): ?>
                                        <td colspan="1"><a href="?/s-pago-nota/imprimir-recibo/<?= $general['id_general']; ?>" class="btn btn-info float-right">Reimprimir</a></td>
                                    <?php endif ?>
                                    <td colspan="1"><a href="?/s-pago-nota/listar" class="btn btn-primary float-right">Mis Notas</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <!-- ============================================================== -->
            <!-- end datos -->
            <!-- ============================================================== -->
    </div>
</div>
<!-- ============================================================== -->
<!-- row -->
<!-- ============================================================== -->

<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/educheck.js"></script>
<?php require_once show_template('footer-design'); ?>

<script>
$(function () {
    var dataTable = $('#table').DataTable({
    language: dataTableTraduccion,
    searching: true,
    paging:true,
    "lengthChange": true, 
    "responsive": true
    });
});
</script>
