<?php     
   
// Obtiene los parametros  cliente  tutor_id
$id_estudiante = (isset($_params[0])) ? $_params[0] : 0;  

// Obtiene los parametros de gestion 
$gestion = $_gestion['id_gestion'];  

// Obtiene los parametros de impresion
$impresion = $_institution['impresion'];  

// Obtiene la cadena csrf 
$csrf = set_csrf();     

// Obtiene personas
$personas = $db->select('*')->from('sys_persona p')->join('per_asignaciones a','p.id_persona = a.persona_id','inner')->order_by('p.nombres')->fetch();

// Obtiene tutores
$conceptos = $db->select('*')->from('rhh_concepto_pago r')->order_by('r.nombre_concepto_pago')->fetch();

$almacen = $db->from('inv_almacenes')->where('principal', 'S')->fetch_first(); 
$id_almacen = ($almacen) ? $almacen['id_almacen'] : 0; 

// Obtiene la moneda oficial
$moneda = $db->from('inv_monedas')->where('oficial', 'S')->fetch_first();
$moneda = ($moneda) ? '(' . $moneda['sigla'] . ')' : ''; 

// Define la fecha de hoy  
$hoy = date('Y-m-d'); 

// Obtiene la dosificacion del periodo actual
$dosificacion = $db->from('inv_dosificaciones')->where('fecha_registro <=', $hoy)->where('fecha_limite >=', $hoy)->where('activo', 'S')->fetch_first();

// Define el limite de filas
$limite_longitud = $_institution['longitud'];

// Define el limite monetario
$limite_monetario = 10000000;
$limite_monetario = number_format($limite_monetario, 2, '.', '');  

// Obtiene los permisos  
$permiso_listar     = in_array('listar', $_views);
$permiso_imprimir   = in_array('imprimir', $_views);
$permiso_historial  = true;

if ($impresion == 'pdf'){
    $condicion = $dosificacion && $almacen;
}else if ($impresion == 'termica'){
    $condicion = $_terminal && $dosificacion && $almacen;
}

?>

<?php require_once show_template('header-design'); ?>
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== --> 
<?php if ($impresion == 'pdf') : ?>
<div class="row">
<?php elseif ($impresion == 'termica'):  ?>
<div class="row" data-servidor="<?= ip_local . 'sistema/factura.php'; ?>"  data-servidor-r="<?= ip_local . 'sistema/nota.php'; ?>">
<?php endif ?>
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Movimientos de Pagos</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">RRHH</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Movimientos de Pagos</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Crear</a></li>
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
    <!-- ============================================================== -->
    <!-- Sección 1 -->
    <!-- ============================================================== -->
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
        <!-- ============================================================== -->
        <!-- Boleta de pago -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 list-group">
                <form id="formulario" class="form-horizontal">
                    <input type="hidden" name="id_movimiento" id="id_movimiento" value="0">
                    <div class="card">
                        <div class="card-header"> 
                            <div class="text-center">
                                 <label><font size="5px">BOLETA DE PAGO</font></label>
                            </div> 
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="id_asignacion" class="col-4 col-lg-3 col-form-label text-right"><label style="color:red">*</label> Personal:</label>
                                <div class="col-8 col-lg-9">
                                    <select name="id_asignacion" id="id_asignacion" class="perfil form-control" onchange="informacion();historial();">
                                        <option value="" selected="selected">Buscar ...</option>
                                        <?php foreach ($personas as $value) : ?>
                                            <option value="<?= $value['id_asignacion']; ?>"><?= escape($value['primer_apellido']); ?> <?= escape($value['segundo_apellido']); ?>  <?= escape($value['nombres']); ?> | CI: <?= escape($value['codigo']); ?>  | COD: <?= escape($value['codigo']); ?> </option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="id_concepto" class="col-4 col-lg-3 col-form-label text-right"><label style="color:red">*</label> Concepto:</label>
                                <div class="col-8 col-lg-9">
                                    <select name="id_concepto" id="id_concepto" class="form-control">
                                        <option value="">Buscar ...</option>
                                        <?php foreach ($conceptos as $concepto): ?>
                                        <option value="<?= escape($concepto['id_concepto_pago']); ?>"><?= escape($concepto['nombre_concepto_pago']); ?></option>
                                        <?php endforeach ?>
                                    </select> 
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="mes" class="col-4 col-lg-3 col-form-label text-right"><label style="color:red">*</label>Mes:</label>
                                <div class="col-8 col-lg-9">
                                    <input id="mes" name="mes" type="text" value="<?= date('m'); ?>" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="gestion" class="col-4 col-lg-3 col-form-label text-right"><label style="color:red">*</label>Gestión:</label>
                                <div class="col-8 col-lg-9">
                                    <input id="gestion" name="gestion" type="text" value="<?= date('Y'); ?>" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="monto" class="col-4 col-lg-3 col-form-label text-right"><label style="color:red">*</label>Monto.<?= $moneda; ?>:</label>
                                <div class="col-8 col-lg-9">
                                    <input id="monto" name="monto" type="text" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="fecha_pago" class="col-4 col-lg-3 col-form-label text-right"><label style="color:red">*</label>Fecha Pago:</label>
                                <div class="col-8 col-lg-9">
                                    <input type="text" value="<?= date('d/m/Y'); ?>" name="fecha_pago" id="fecha_pago" class="form-control text-uppercase" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="observacion" class="col-4 col-lg-3 col-form-label text-right">Observación:</label>
                                <div class="col-8 col-lg-9">
                                    <textarea name="observacion" id="observacion" class="form-control text-uppercase" rows="2" autocomplete="off"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="col-xs-12 text-right">
                                <div class="form-group row">
                                    <label for="telefono" class="col-2 col-lg-2 col-form-label text-right">Imprimir?</label>
                                    <div class="col-2 col-lg-2">
                                        <select class="form-control" id="imprimir" name="imprimir"> 
                                            <option value="1">SI</option>
                                            <option value="0">NO</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary active btn-md"  id="cobrar_pago" style="display:true">Pagar</button>
                                <button type="reset" class="btn btn-light active btn-md">Restablecer</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Fin boleta de pago -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Comprobacion de la terminal -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 list-group">
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">Información sobre la transacción</h5>
                        <small class="text-muted"><?= date('d/m/Y H:i:s'); ?></small>
                    </div>
                    <div class="table-display">
                        <div class="tbody">
                            <div class="tr">
                                <div class="th">
                                    <span class="glyphicon glyphicon-home"></span>
                                    <span>Casa matriz: <?= escape($_institution['nombre']); ?></span>
                                </div>
                            </div>
                            <div class="tr">
                                <div class="th">
                                    <span class="glyphicon glyphicon-qrcode"></span>
                                    <span>NIT: <?= escape($_institution['nit']); ?></span>
                                </div>
                            </div>
                            <?php if ($_terminal) : ?>
                            <div class="tr">
                                <div class="th">
                                    <span class="glyphicon glyphicon-phone"></span>
                                    <span>Terminal:</span>
                                </div>
                                <div class="td"><?= escape($_terminal['terminal']); ?></div>
                            </div>
                            <div class="tr">
                                <div class="th">
                                    <span class="glyphicon glyphicon-print"></span>
                                    <span>Impresora:</span>
                                </div>
                                <div class="td"><?= escape($_terminal['impresora']); ?></div>
                            </div>
                            <?php endif ?>
                            <div class="tr">
                                <div class="th">
                                    <span class="glyphicon glyphicon-user"></span>
                                    <span>Empleado: <?= ($_user['persona_id'] == 0) ? 'No asignado' : escape($_user['nombres'] . ' ' . $_user['primer_apellido'] . ' ' . $_user['segundo_apellido']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Fin comprobacion de la terminal -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- Fin seccion 1 --> 
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Sección 2 -->
    <!-- ============================================================== -->
    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
        <!-- ============================================================== -->
        <!-- Historial de pagos -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="card col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"  style="background:#fff">
                <!-- <div class="section-block">
                    <h3 class="section-title" id="">Historial de Movimientos de Pagos</h3>
                </div> -->
                <div class="card-header"> 
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                            <div class="text-label hidden-xs"><font size="5px">Historial de Movimientos de Pagos </font></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
                            <div class="btn-group">
                                <div class="input-group">
                                    <div class="input-group-append be-addon">
                                        <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item">Seleccionar acción</a>
                                            <?php if ($permiso_listar) : ?>
                                            <div class="dropdown-divider"></div>
                                            <a href="?/rrhh-movimiento-pago/listar" class="dropdown-item">Lista General</a>
                                            <?php endif ?>
                                            <?php if ($permiso_historial) : ?>
                                            <a href="#" class="dropdown-item" onclick="imprimir_historial();">Imprimir Historial</a>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="card-body">
                        <table id="informacion" class="table table-bordered table-condensed table-hover">
                            <thead>
                                <tr class="">
                                    <th class="text-nowrap text-center" rowspan="3" id="imagen"></th>
                                    <th class="text-nowrap text-center">Nombres</th>
                                    <td class="text-nowrap text-center" id="nombres"></td>
                                    <th class="text-nowrap text-center">Apellidos</th>
                                    <td class="text-nowrap text-center" id="apellidos"></td>
                                    <th class="text-nowrap text-center">CI</th>
                                    <td class="text-nowrap text-center" id="ci"></td>
                                </tr>
                                <tr class="">
                                    <th class="text-nowrap text-center">Celular</th>
                                    <td class="text-nowrap text-center" id="celular"></td>
                                    <th class="text-nowrap text-center">Correo</th>
                                    <td class="text-nowrap text-center" id="correo"></td>
                                    <th class="text-nowrap text-center">Dirección</th>
                                    <td class="text-nowrap text-center" id="direccion"></td>
                                </tr>
                                <tr class="">
                                    <th class="text-nowrap text-center">Cargo</th>
                                    <td class="text-nowrap text-center" id="cargo"></td>
                                    <th class="text-nowrap text-center">Tiempo</th>
                                    <td class="text-nowrap text-center" id="tiempo"></td>
                                    <th class="text-nowrap text-center">Salario</th>
                                    <td class="text-nowrap text-center" id="salario"></td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="card-body">                        
                        <div class="row">
                            <div class="table-responsive">
                                <table id="table" class="table table-bordered table-condensed table-hover">
                                    <thead>
                                        <tr class="active">
                                            <th class="text-nowrap text-center">#</th>
                                            <th class="text-nowrap text-center">Nº Nota</th>
                                            <th class="text-nowrap text-center">Concepto</th>
                                            <th class="text-nowrap text-center">Gestión</th>
                                            <th class="text-nowrap text-center">Mes</th>
                                            <th class="text-nowrap text-center">Monto</th>
                                            <th class="text-nowrap text-center">Fecha Pago</th>
                                            <th class="text-nowrap text-center">Observación</th>
                                            <th class="text-nowrap text-center">Usuario</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contenedor_historial"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Fin historial de pagos -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- Fin seccion 2 --> 
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- Fin row -->
<!-- ============================================================== --> 

<?php $fecha=date('Y-m-d'); $fecha_actual= json_encode($fecha); ?>

<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/educheck.js"></script>
<script src="<?= js; ?>/bootbox.min.js"></script>
<script>

    let i               = 1;
    let logitud         = <?= $limite_longitud;?>;

    $(function () {

        var almacen=<?= $id_almacen;?>;

        // Obtiene al personal
        $('#id_asignacion').selectize({
            persist: false,
            createOnBlur: true,
            create: false,
            onInitialize: function (){
                $('#id_asignacion').css({
                    display: 'block',
                    left: '-10000px',
                    opacity: '0',
                    position: 'absolute',
                    top: '-10000px'
                });
            },
            onChange: function () {
                $('#id_asignacion').trigger('blur');
            },
            onBlur: function () {
                $('#id_asignacion').trigger('blur');
            }
        }); 
        
        // Obtiene los conceptos de pagos
        $('#id_concepto').selectize({
            persist: false,
            createOnBlur: true,
            create: false,
            onInitialize: function (){
                $('#id_concepto').css({
                    display: 'block',
                    left: '-10000px',
                    opacity: '0',
                    position: 'absolute',
                    top: '-10000px'
                });
            },
            onChange: function () {
                $('#id_concepto').trigger('blur');
            },
            onBlur: function () {
                $('#id_concepto').trigger('blur');
            }
        }); 

        var dataTable = $('#table').DataTable({
            language: dataTableTraduccion,
            searching: true,
            paging:true,
            "lengthChange": true, 
            "responsive": true
        }); 

    });
    
    function eliminar_producto(i) { 
        bootbox.confirm('¿Está seguro que desea eliminar el cuota?', function (result) {
            if(result){ 
                $('[data-producto=' + i + ']').remove();
                //renumerar_productos(); 
                calcular_total(); 
                //calcular_descuento(); 
            }
        });
    }

    <?php if ($impresion == 'pdf') : ?>
        $("#formulario").validate({ 
            rules: {
                id_persona:         {required: true},
                id_concepto_pago:   {required: true},
                mes:                {required: true},
                gestion:            {required: true},
                monto:              {required: true},
                fecha_pago:         {required: true}
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: highlight,
            unhighlight: unhighlight,
            messages: {
                        id_persona: "Debe seleccionar un personal para realizar pago.",
                        id_concepto_pago: "Debe seleccionar un cocnepto de pago.",
                        mes: "Debe seleccionar el mes correspondiente.",
                        gestion: "Debe ingresar la gestión.",
                        monto: "Debe ingresar el monto del pago.",
                        fecha_pago: "Debe ingresar fecha de pago."
            },
            //una ves validado guardamos los datos en la DB
            submitHandler: function(form){
                bootbox.confirm('¿Está seguro de realizar el pago?', function (respuesta) {
                    if (respuesta) {
                        var datos = $("#formulario").serialize();
                        $.ajax({
                                type: 'POST',
                                url: "?/rrhh-movimiento-pago/guardar",
                                data: datos,
                                success: function (pago) {
                                    if (pago) {

                                        console.log(pago);

                                        // Liampiar e impresion
                                        var pag=$.trim(pago); 
                                        var imprimir = $('#imprimir').val();

                                        if(imprimir==1){

                                            $("#formulario")[0].reset();
                                            historial();
                                            imprimir_pago(pag);

                                        }else if(imprimir==0){

                                            $("#formulario")[0].reset();
                                            historial();
                                            alertify.success('El pago fue realizado satisfactoriamente, sin impresión.'); 
                                        }

                                    } else {
                                        $('#loader').fadeOut(100);
                                        alertify.danger('Ocurrió un problema en el proceso, no se puedo obtener la dosificación ni tampoco guardar los datos del pago, verifique si la se guardó parcialmente.');
                                    }
                                }
                        });
                    }
                });
            }
        })

        function imprimir_pago(id) {
            window.open('?/rrhh-movimiento-pago/imprimir/'+id, true);
        } 
    
    <?php elseif ($impresion == 'termica'):  ?>
        $("#formulario").validate({
            rules: {
                nit_ci: {required: true},
                nombre_cliente: {required: true}, 
                factura_recibo: {required: true}
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: highlight,
            unhighlight: unhighlight,
            messages: {
            nit_ci: "Debe ingresar NIT/CI.",
            nombre_cliente: "Debe ingresar nombre del cliente.",
            factura_recibo: "Debe seleccionar el documento comprobante de pago."
            },
            //una ves validado guardamos los datos en la DB
            submitHandler: function(form){
                bootbox.confirm('¿Está seguro de realizar el pago?', function (respuesta) {
                    if (respuesta) { 
                        var factura_recibo = $('#factura_recibo').val();
                        var datos = $("#formulario").serialize();
                        $.ajax({
                            type: 'POST',
                            url: "?/s-pago-computarizado/guardar",
                            dataType: 'json',
                            data: datos,
                            success: function (pago) {                   
                                if (pago) {       
                                    var impticket = $('#imprimir').val();
                                    if(impticket==1){
                                        if(factura_recibo=='FACTURA'){
                                            $('#pensiones').html('');
                                            $('#subtotal').html('0.00');
                                            $("#formulario")[0].reset();
                                            cargar_pagos_factura();
                                            //cargar_pagos_recibo();
                                            historial_pagos();
                                            imprimir_factura(pago);
                                        }else if(factura_recibo=='NOTA'){
                                            $('#pensiones').html('');
                                            $('#subtotal').html('0.00');
                                            $("#formulario")[0].reset();
                                            cargar_pagos_factura();
                                            //cargar_pagos_recibo();
                                            historial_pagos();
                                            imprimir_recibo(pago);
                                        }
                                        //alertify.success('El pago fue realizado satisfactoriamente.'); 
                                    }else if(impticket==0){
                                        $('#pensiones').html('');
                                        $('#subtotal').html('0.00');
                                        $("#formulario")[0].reset();
                                        cargar_pagos_factura();
                                        //cargar_pagos_recibo();
                                        historial_pagos();
                                        alertify.success('El pago fue realizado satisfactoriamente, sin impresión.'); 
                                    }
                                    
                                } else {
                                    //$('#loader').fadeOut(1000);
                                    alertify.danger('Ocurrió un problema en el proceso, no se puedo obtener la dosificación ni tampoco guardar los datos del pago, verifique si la se guardó parcialmente.');
                                }
                            }
                        });
                    }
                });
            }
        })

        function imprimir_factura(venta) {
            var servidor = $.trim($('[data-servidor]').attr('data-servidor'));
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: servidor,
                data: venta
            }).done(function (respuesta) {
                // $('#loader').fadeOut(100);
                switch (respuesta.estado) {
                    case 's':
                        //window.location.reload();
                        alertify.success('la impresión se realizo exitosamente.'); 
                        break;
                    case 'p':
                        alertify.warning('La impresora no responde, asegurese de que este conectada y registrada en el sistema, una vez solucionado el problema vuelva a intentarlo nuevamente.');
                        break;
                    default:
                        alertify.danger('Error DEFAULT: Ocurrió un problema durante el proceso, no se envió los datos para la impresión de la factura.');
                        break;
                }
            }).fail(function (e) {
                // console.log(e);
                // $('#loader').fadeOut(1000);
                alertify.danger('Error FAIL: Ocurrió un problema durante el proceso, reinicie la terminal para dar solución al problema y si el problema persiste contactese con el con los desarrolladores.');
            }).always(function () {
                $('#formulario').trigger('reset');
                $('#form_buscar_0').trigger('submit');
            });
        }

        function imprimir_recibo(venta) {
            //window.open('?/s-pago-computarizado/imprimir-recibo/'+resp, true);
            var servidor = $.trim($('[data-servidor-r]').attr('data-servidor-r'));
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: servidor,
                data: venta
            }).done(function (respuesta) {
                // $('#loader').fadeOut(1000);
                // console.log(respuesta.estado);
                switch (respuesta.estado) {
                    case 's':
                        //window.location.reload();
                        alertify.success('la impresión se realizo exitosamente.'); 
                        break;
                    case 'p':
                        alertify.warning('La impresora no responde, asegurese de que este conectada y registrada en el sistema, una vez solucionado el problema vuelva a intentarlo nuevamente.');
                        break;
                    default:
                        alertify.danger('Error DEFAULT: Ocurrió un problema durante el proceso, no se envió los datos para la impresión de la factura.');
                        break;
                }
            }).fail(function (e) {
                // console.log(e);
                // $('#loader').fadeOut(1000);
                alertify.danger('Error FAIL: Ocurrió un problema durante el proceso, reinicie la terminal para dar solución al problema y si el problema persiste contactese con el con los desarrolladores.');
            }).always(function () {
                $('#formulario').trigger('reset');
                $('#form_buscar_0').trigger('submit');
            });
        } 

    <?php endif ?>

    function informacion(){
        id_asignacion = $("#id_asignacion").val();
        $.ajax({
            url: '?/rrhh-movimiento-pago/procesos',
            type: 'POST',
            data: {'boton': 'listar_informacion', 'id_asignacion': id_asignacion},
            dataType: 'JSON',
            success: function(data){
                var imagen = "";
                //var foto = "imgs . '/avatar.jpg'";
                if (data.foto == "") {
                    foto = "files/profiles/personal/avatar.jpg";
                } else {
                    foto = "files/profiles/personal/" + data.foto + ".jpg";
                }

                imagen = "<img src='" + foto + "' class='img-rounded cursor-pointer' data-toggle='modal' data-target='#modal_mostrar' data-modal-size='modal-md' data-modal-title='Imagen' width='70' height='90'>";
                $("#nombres").html(data.nombres);
                $("#apellidos").html(data.primer_apellido);
                $("#celular").html(data.segundo_apellido);
                $("#correo").html(data.email);
                $("#direccion").html(data.direccion);
                $("#cargo").html(data.cargo);
                $("#ci").html(data.numero_documento);
                $("#salario").html(data.sueldo);
                $("#imagen").html(imagen);
            }
        });
    }

    function historial(){
        id_asignacion = $("#id_asignacion").val();

        $.ajax({
            url: '?/rrhh-movimiento-pago/procesos',
            type: 'POST',
            data: {'boton': 'listar_historial', 'id_asignacion': id_asignacion},
            dataType: 'JSON',
            success: function(data){
                console.log(data);
                console.log('++++++++++++++++++++++++++++');
                html="";
                $("#contenedor_historial").html(""); 

                for(var i=0; i < data.length;i++) {

                        html += '<tr>\n\
                              <td class="text-center"><font size=2>'+ (i+1) +'</font></td>\n\
                              <td class="text-center"><font size=2>'+ data[i]['nro'] +'</font></td>\n\
                              <td class="text-center"><font size=2>'+ data[i]['nombre_concepto_pago'] +'</font></td>\n\
                              <td class="text-center"><font size=2>'+ data[i]['gestion_id']+'</font></td>\n\
                              <td class="text-center"><font size=2>'+ data[i]['mes_cancelado']+'</font></td>\n\
                              <td class="text-right"><font size=2>'+ data[i]['monto_cancelado']+'</font></td>\n\
                              <td class="text-center"><font size=2>'+ data[i]['fecha_pago'] +'</font></td>\n\
                              <td class="text-justify"><font size=2>'+ data[i]['observacion'] +'</font></td>\n\
                              <td class="text-center"><font size=2>'+ data[i]['usuario_registro'] +'</font></td>';
                }

                $("#contenedor_historial").html(html);
            }
        });
    }

    function imprimir_historial() {
        var id_asignacion = $('#id_asignacion').val();
        window.open('?/rrhh-movimiento-pago/imprimir-historial/'+id_asignacion, true);
    }  

</script>