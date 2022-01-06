<?php   
 
// Obtiene los parametros  cliente  tutor_id
$id_estudiante = (isset($_params[0])) ? $_params[0] : 0; 

// Obtiene los parametros 
$gestion=$_gestion['id_gestion'];           
  
// Obtiene la cadena csrf 
$csrf = set_csrf();     

// Obtiene los estudiantes
$estudiante = $db->select('z.*')->from('vista_estudiantes z')->where('id_estudiante',$id_estudiante)->fetch_first();

// Obtiene nivel académico
$nivel = $db->select('z.*')->from('ins_nivel_academico z')->order_by('id_nivel_academico')->fetch();

// Obtiene roles
$roles = $db->select('z.*')->from('sys_roles z')->where('z.rol','Tutor')->or_where('z.rol','Estudiante')->order_by('z.rol')->fetch();

// Obtiene personas
//$personas = $db->select('z.*, e.*')->from('sys_persona z')->join('ins_estudiante e','z.id_persona=e.persona_id','inner')->order_by('z.nombres')->fetch();
$personas = $db->query("SELECT*,CONCAT(ins.nombre_aula, ' ', ins.nombre_paralelo) as nombre_aula_paralelo
        FROM sys_persona p
        INNER JOIN ins_familiar f ON p.id_persona=f.persona_id
        INNER JOIN ins_estudiante_familiar ef ON f.id_familiar=ef.familiar_id
        INNER JOIN ins_inscripcion i ON ef.estudiante_id=i.estudiante_id
        INNER JOIN ins_estudiante e ON i.estudiante_id=e.id_estudiante
        INNER JOIN vista_inscripciones ins ON e.id_estudiante = ins.estudiante_id 
        INNER JOIN sys_persona sp ON e.persona_id=sp.id_persona
        INNER JOIN gon_puntos gp ON gp.id_punto = i.punto_id
        INNER JOIN gon_rutas gr ON gr.id_ruta = gp.ruta_id")->fetch();

// Obtiene tutores
$tutores = $db->select('z.*,sp.*')->from('ins_familiar z')->join('sys_persona sp','z.persona_id=sp.id_persona','inner')->order_by('sp.nombres')->fetch();

// Obtiene estudiantes
$estudiantes = $db->select('z.*,sp.*')->from('ins_estudiante z')->join('sys_persona sp','z.persona_id=sp.id_persona','inner')->order_by('sp.nombres')->fetch();

// Obtiene los tipos de descuento
$descuentos = $db->select('z.*')->from('pen_tipo_descuento z')->where('gestion_id',$gestion)->fetch();

$almacen = $db->from('inv_almacenes')->where('principal', 'S')->fetch_first(); 
$id_almacen = ($almacen) ? $almacen['id_almacen'] : 0; 

// Obtiene la moneda oficial 
$moneda = $db->from('inv_monedas')->where('oficial', 'S')->fetch_first();
$moneda = ($moneda) ? '(' . $moneda['sigla'] . ')' : ''; 

// Define la fecha de hoy  
$hoy = date('Y-m-d'); 

// Obtiene la dosificacion del periodo actual
$dosificacion = $db->from('inv_dosificaciones')->where('fecha_registro <=', $hoy)->where('fecha_limite >=', $hoy)->where('activo', 'S')->fetch_first();

// Obtiene los clientes 
$clientes = $db->query("SELECT c.* 
FROM ((SELECT nombre_cliente, nit_ci FROM inv_egresos) 
UNION (SELECT nombre_cliente, nit_ci FROM inv_proformas) 
UNION (
       SELECT CONCAT (p.nombres,' ',p.primer_apellido,' ',p.segundo_apellido) AS nombre_cliente, p.nit AS nit_ci
       FROM sys_persona p
       ) 
) c 
GROUP BY c.nombre_cliente, c.nit_ci
ORDER BY c.nombre_cliente ASC, c.nit_ci ASC")->fetch();

// Define el limite de filas
$limite_longitud = 200;

// Define el limite monetario
$limite_monetario = 10000000;
$limite_monetario = number_format($limite_monetario, 2, '.', '');  

// Obtiene los permisos  
$permiso_listar = in_array('listar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);
$permiso_crear_familiar = in_array('crear-familiar', $_views);
?>

<?php require_once show_template('header-design'); ?>
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">
<link href="assets/themes/concept/assets/vendor/datepicker/css/datepicker.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/bootstrap-fileinput-master/css/fileinput.css" rel="stylesheet">
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== --> 
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Cobros Góndolas: Notas de Remisión</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gódolas</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Notas de Remisión</a></li>
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
<form id="formulario" class="form-horizontal">

<div class="row">
    <!-- ============================================================== -->
    <!-- profile -->
    <!-- ==============================================================  historial_pagos();-->
    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
            <div class="row"> 
            <div class="card col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"  style="background:#fff">
                <div class="row" style="background:#fff;">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card-body">
                            <select name="id_estudiante" id="id_estudiante" class="perfil form-control" onchange="cargar_pagos_recibo();cargar_pagos_factura();historial_pagos();">
                                <option value="" selected="selected">Buscar por estudiante o código ...</option>
                                <?php foreach ($personas as $value) : ?>
                                    <option value="<?= $value['id_estudiante']; ?>"><?= escape($value['nombres']); ?> <?= escape($value['primer_apellido']); ?> <?= escape($value['segundo_apellido']); ?>  |  <?= escape($value['codigo_estudiante']); ?> </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12"></div>
                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12">
                        <div class="card-body">
                            <a href="#" class="btn btn-light btn-md" onclick="imprimir_historial();" >Historial de Pagos</a>
                        </div></div>
                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12">
                        <div class="card-body">
                            <a href="?/s-pago-nota/listar" class="btn btn-primary btn-md" >Mis Notas</a>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="card-body">                        
                        <div class="row">
                            <div class="table-responsive">
                                <table id="table" class="table table-bordered table-condensed table-striped table-hover" style="background:#fff">
                                    <thead>
                                        <tr class="active">
                                            <th class="text-nowrap text-center">Nº</th>
                                            <th class="text-nowrap text-center">Concepto</th>
                                            <th class="text-nowrap text-center">Compromiso</th>
                                            <th class="text-nowrap text-center">Fecha límite</th>                                            
                                            <th class="text-nowrap text-center">Monto</th>
                                            <th class="text-nowrap text-center">Días atraso</th>
                                            <th class="text-nowrap text-center">Mora día</th>                                                                                        
                                            <th class="text-nowrap text-center">Monto total</th>
                                            <th class="text-nowrap text-center">Adelanto</th>
                                            <th class="text-nowrap text-center">Saldo actual</th>
                                            <th class="text-nowrap text-center">Saldo</th>
                                            <th class="text-nowrap text-center">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contenedor_deudas_recibo"></tbody>
                                </table>
                                <div class="section-block text-center" id="contenedor_deudas_factura"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"  style="background:#fff">
                <div class="section-block">
                    <h3 class="section-title" id="titulo-tabla">Historial de pagos</h3>
                </div>
                <div class="">
                    <div class="card-body">                        
                        <div class="row">
                            <div class="table-responsive">
                                <table id="table" class="table table-bordered table-condensed table-hover">
                                    <thead>
                                        <tr class="active">
                                            <!-- <th class="text-nowrap text-center">#</th> -->
                                            <th class="text-nowrap text-center">Factura</th>
                                            <th class="text-nowrap text-center">Documento</th>
                                            <th class="text-nowrap text-center">Concepto</th>
                                            <th class="text-nowrap text-center">Fecha límite</th>
                                            <th class="text-nowrap text-center">Fecha cobro</th>
                                            <th class="text-nowrap text-center">Monto</th>
                                            <th class="text-nowrap text-center">Mora día</th>
                                            <th class="text-nowrap text-center">Días atraso</th>
                                            <th class="text-nowrap text-center">Monto total</th>
                                            <th class="text-nowrap text-center">Cancelado</th>
                                            <th class="text-nowrap text-center">Saldo</th>
                                            <th class="text-nowrap text-center">Usuario</th>
                                            <th class="text-nowrap text-center">Compromiso</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contenedor_historial_pagos"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- -->
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
        <!-- ============================================================== -->
        <!-- card influencer one -->
        <!-- ============================================================== -->
        <!-- <form id="formulario" class="form-horizontal"> -->
            <div class="card">
                <div class="card-header p-4">
                    <a class="pt-2 d-inline-block" href="#"><label for="factura_recibo" class="col-12 col-lg-12 col-form-label text-right">Documento de pago:</label></a>                   
                    <div class="float-right">  
                        <h3 class="mb-0">
                            <select name="factura_recibo" id="factura_recibo" class="form-control">
                                <!-- <option value="">Seleccionar</option> -->
                                <!-- <option value="FACTURA">FACTURA</option>
                                <option value="RECIBO">RECIBO</option> -->
                            </select> </h3>
                            <br>
                        <label>Fecha:</label> <?= date('d/m/Y');?>
                    </div> 
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label for="cliente" class="col-5 col-md-5 col-lg-4 col-form-label text-right">Buscar:</label>
                        <div class="col-7 col-md-7 col-lg-8">
                            <select name="cliente" id="cliente" class="form-control">
                                <option value="">Buscar</option>
                                <?php foreach ($clientes as $cliente) { ?>
                                <option value="<?= escape($cliente['nit_ci']) . '|' . escape($cliente['nombre_cliente']); ?>"><?= escape($cliente['nit_ci']) . ' &mdash; ' . escape($cliente['nombre_cliente']); ?></option>
                                <?php } ?>
                            </select>  
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nit_ci" class="col-5 col-md-5 col-lg-4 col-form-label text-right"><label style="color:red">*</label> NIT / CI:</label>
                        <div class="col-7 col-md-7 col-lg-8">
                            <input id="nit_ci" name="nit_ci" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nombre_cliente" class="col-5 col-md-5 col-lg-4 col-form-label text-right"><label style="color:red">*</label> Señor(es):</label>
                        <div class="col-7 col-md-7 col-lg-8">
                            <input id="nombre_cliente" name="nombre_cliente" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fecha_emision" class="col-5 col-md-5 col-lg-4 col-form-label text-right"><label style="color:red">*</label> Fecha de emisión: </label>
                        <div class="col-7 col-md-7 col-lg-8">
                            <input type="text" value="<?= date('d/m/Y'); ?>" name="fecha_emision" id="fecha_emision" class="datepicker-here form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="telefono" class="col-5 col-md-5 col-lg-4 col-form-label text-right">Teléfono:</label>
                        <div class="col-7 col-md-7 col-lg-8">
                            <input type="text" value="" name="telefono" id="telefono" class="form-control text-uppercase" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="direccion" class="col-5 col-md-5 col-lg-4 col-form-label text-right">Dirección:</label>
                        <div class="col-7 col-md-7 col-lg-8">
                            <input type="text" value="" name="direccion" id="direccion" class="form-control text-uppercase" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="observacion" class="col-5 col-md-5 col-lg-4 col-form-label text-right">Observación:</label>
                        <div class="col-7 col-md-7 col-lg-8">
                            <textarea name="observacion" id="observacion" class="form-control text-uppercase" rows="2" autocomplete="off"></textarea>
                        </div>
                    </div>

                    <div class="table-responsive-sm">
                        <table id="ventas" class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="center" style="display: none">#</th>
                                    <th class="center">Estudiante</th>
                                    <th class="center">Detalle</th>
                                    <th class="center">Importe</th>
                                    <th class="center"></th> 
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class="active">
                                    <th class="text-nowrap text-right" colspan="2">Importe total <?= escape($moneda); ?></th>
                                    <th class="text-nowrap text-right" data-subtotal="" id="subtotal">0.00</th>
                                    <th class="text-nowrap text-center"></th>  
                                </tr>
                            </tfoot>
                            <tbody id="pensiones"></tbody>
                        </table>
                    </div>

                    <div class="form-group" style="display: none">
                        <div class="col-xs-12">
                            <input type="text" name="almacen_id" value="<?= $almacen['id_almacen']; ?>" class="translate" tabindex="-1" data-validation="required number" data-validation-error-msg="El almacén no esta definido">
                            <input type="text" name="nro_registros" value="0" class="translate" tabindex="-1" data-ventas="" data-validation="required number" data-validation-allowing="range[1;<?= $limite_longitud; ?>]" data-validation-error-msg="El número de productos a vender debe ser mayor a cero y menor a <?= $limite_longitud; ?>">
                            <input type="text" name="monto_total"   value="0" class="translate" tabindex="-1" data-total=""  data-validation="required number" data-validation-allowing="range[0.01;<?= $limite_monetario; ?>],float" data-validation-error-msg="El monto total de la venta debe ser mayor a cero y menor a <?= $limite_monetario; ?>">
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="col-xs-12 text-right">
                        <button type="submit" class="btn btn-primary btn-xs">Guardar</button>
                        <button type="reset" class="btn btn-default btn-xs">Restablecer</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- ============================================================== -->
        <!-- end card influencer one -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- end profile --> 
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- end row -->
<!-- ============================================================== --> 
<table class="hidden" style="display: none">
    <tbody id="fila_filtrar" data-negativo="<?= imgs; ?>/" data-positivo="<?= files; ?>/b-productos/">
        <tr>
            <td class="text-nowrap text-middle text-center width-collapse">
                <img src="" class="img-rounded cursor-pointer" data-toggle="modal" data-target="#modal_mostrar" data-modal-size="modal-md" data-modal-title="Imagen" width="75" height="75">
            </td>
            <td class="text-nowrap text-middle" data-codigo=""></td>
            <td class="text-middle">
                <em></em>
                <span class="hidden" data-nombre=""></span>
            </td>
            <td class="text-nowrap text-middle"></td>
            <td class="text-nowrap text-middle text-right lead" data-stock=""></td>
            <td class="text-nowrap text-middle text-right lead" data-valor=""></td>
            <td class="text-nowrap text-middle text-center width-collapse">
                <button type="button" class="btn btn-primary btn-xs" data-vender="" onclick="vender(this);calcular_saldo()"><i class="icon-basket"></i></button>
                <button type="button" class="btn btn-default btn-xs" data-actualizar="" onclick="actualizar(this);calcular_saldo()"><i class="icon-refresh"></i></button>
            </td>
        </tr>

    </tbody>
</table>

<?php $fecha=date('Y-m-d'); $fecha_actual= json_encode($fecha); ?>
<!--     <script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script> -->
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/educheck.js"></script>
<script src="<?= js; ?>/jquery-ui-1.10.4.min.js"></script>
<script src="<?= js; ?>/bootstrap-notify.min.js"></script>
<!-- <script src="<?= js; ?>/buzz.min.js"></script> -->
<script src="<?= js; ?>/bootbox.min.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.es.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-fileinput-master/js/fileinput.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-fileinput-master/js/es.js"></script>
<script>
    var global_id_rol ="";
    var global_rol ="";
    var i=1;
    $(function () {
        var almacen=<?= $id_almacen;?>;

       $('#fecha_emision').datepicker({
            language: 'es',
            position:'bottom left',
            onSelect: function(fd, d, picker){
                var fecha_marcada = moment(d).format('YYYY-MM-DD');
                var hoy = moment(new Date()).format('YYYY-MM-DD');
                $('#fecha_emision').va(hoy);
            }
        })

        $('#id_estudiante').selectize({
            persist: false,
            createOnBlur: true,
            create: false,
            onInitialize: function (){
                $('#id_estudiante').css({
                    display: 'block',
                    left: '-10000px',
                    opacity: '0',
                    position: 'absolute',
                    top: '-10000px'
                });
            },
            onChange: function () {
                $('#id_estudiante').trigger('blur');
            },
            onBlur: function () {
                $('#id_estudiante').trigger('blur');
            }
        }); 

        $('#cliente').selectize({
            persist: true,
            createOnBlur: true,
            create: true,
            onInitialize: function () {
                $('#cliente').css({
                    display: 'block',
                    left: '-10000px',
                    opacity: '0',
                    position: 'absolute',
                    top: '-10000px'
                });
            },
            onChange: function () {
                $('#cliente').trigger('blur');
            },
            onBlur: function () {
                $('#cliente').trigger('blur');
            }
        }).on('change', function (e) {
            var valor = $(this).val();
            console.log(valor);
            valor = valor.split('|');
            $(this)[0].selectize.clear();
            if (valor.length != 1) {
                $('#nit_ci').prop('readonly', true);
                $('#nombre_cliente').prop('readonly', true);
                $('#nit_ci').val(valor[0]);
                $('#nombre_cliente').val(valor[1]);
            } else {
                $('#nit_ci').prop('readonly', false);
                $('#nombre_cliente').prop('readonly', false);
                if (es_nit(valor[0])) {
                    $('#nit_ci').val(valor[0]);
                    $('#nombre_cliente').val('').focus();
                } else {
                    $('#nombre_cliente').val(valor[0]);
                    $('#nit_ci').val('').focus();
                }
            }
        });
    });

    function es_nit(texto) {
        var numeros = '0123456789';
        for(i = 0; i < texto.length; i++){
            if (numeros.indexOf(texto.charAt(i), 0) != -1){ return true;}
        }
        return false;
    }

    $(".tipo_pago").click(function(){
          var tipo= $(this).attr('data-tipo-pago');
          console.log(tipo);
          if(tipo=='P'){
             listar_estudiantes();
          }else if(tipo=='C'){
             listar_personas();
          }
    })
    
    //Obtiene la lista de pensiones pediantes de hijos de familiar
    function cargar_pagos_factura(){
            id_estudiante = $("#id_estudiante").val();
            //alert(id_estudiante);
            $.ajax({
                url: '?/s-pago-nota/procesos',
                type: 'POST',
                data: {'boton': 'cargar_pagos_factura', 'id_estudiante': id_estudiante},
                dataType: 'JSON',
                success: function(data){
                    console.log(data);
                    console.log('hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhjjjjjjjjjjjjjjjjjjjj');
                     html='<a href="?/s-pago-nota/crear" class="">Existen pagos pendientes, ver detalle ...</a>'+
                    '<div class="progress progress-sm">'+
                       '<div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 35%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>'+
                    '</div>';
                    if(data.contador > 0){
                        $("#contenedor_deudas_factura").html(html);

                    }else{
                        $("#contenedor_deudas_factura").html("");
                    }
                }
            });
        }
    //fin  
    
    // function bloquear_pago(id) {
    //     console.log(id+'  holaaaaaaa');
    //     i=id+1;
    //     console.log(i+' iiiiiii');
    //     $( "#fila_pensiones_"+i ).addClass( "disabled" );
    // }

    function activar_pago(id) {
        $( "#fila_pensiones_"+id ).removeClass( "disabled" );
    }

    // function activar_pago(id) {
        
    //     console.log(id+'  holaaaaaaa');
    //     i=id+1;
    //     console.log(i+' iiiiiii');
    //     $( "#fila_pensiones_"+i ).removeClass( "disabled" );
    // }

    //Obtiene la lista de pensiones pediantes de hijos de familiar
    function cargar_pagos_recibo(){

        // Limpia el detalle de la factura
        $('#pensiones').html('');

        var id_estudiante = $("#id_estudiante").val();
        var id_usuario = '<?= $_user['id_user'];?>';
        var estado = '';

        $.ajax({
            url: '?/s-pago-nota/procesos',
            type: 'POST',
            data: {'boton': 'cargar_pagos_recibo', 'id_estudiante': id_estudiante},
            dataType: 'JSON',
            success: function(data){
                console.log(data);
                html="";
                $("#contenedor_deudas_recibo").html("");
                for(var i=0; i < data.length;i++){
                    contenido = data[i]['id_pensiones_estudiante'] +"*"+  data[i]['nombre_pension']+"*"+  data[i]['monto_cancelado'] +"*"+  data[i]['fecha_inicio'] +"*"+ data[i]['fecha_final']+"*"+ data[i]['estudiante_id'] +"*"+ data[i]['id_pensiones_estudiante']+"*"+ data[i]['cancelado'] +"*"+ data[i]['fecha_cancelado'];

                    /*var dias = dias_transcurridos(data[i]['fecha_final']);
                    if(dias >= 0){
                        mora_dia = dias * data[i]['mora_dia'];
                    }else{
                        mora_dia = 0;
                    }*/

                    if(data[i]['monto_cancelado']==null){
                       data[i]['monto_cancelado']=0;
                    }

                    if(data[i]['monto'] > data[i]['monto_cancelado']){
                        var saldo =0;
                        console.log(contenido);
                        if(data[i]['cancelado'] == 'SI'){
                            total = parseInt(data[i]['monto']) + mora_dia;
                            saldo = data[i]['monto'] - data[i]['monto_cancelado']; 
                            cancelado = "SI";
                        }else{
                            var dias = dias_transcurridos(data[i]['fecha_final'], '');
                            //console.log(dias+'DIAS');
                            if(dias >= 0){
                                mora_dia = dias * data[i]['mora_dia'];
                            }else{
                                mora_dia = 0;
                            }
                            if(mora_dia >= 0){
                                total = parseInt(data[i]['monto']) + mora_dia;
                                saldo = total - data[i]['monto_cancelado'];
                                cancelado = saldo;
                            }else{
                                total = parseInt(data[i]['monto']);
                                saldo = total - data[i]['monto_cancelado'];
                                cancelado = saldo;
                            }
                            //cancelado = "NO"; 
                        }

                        if(dias < 0 || dias == 0){
                            dias_mora=0;
                            saldo_actual=cancelado-data[i]['monto_adelanto'];
                            html += '<tr style="background:#fff"><td>'+(i+1)+'</td>\n\
                                  <td class="text-justify" style="color:green"><font size=2>'+data[i]['nombre_pension']+' (cuota '+data[i]['nro']+')</font></td>\n\
                                  <td class="text-center">'+data[i]['compromiso']+'</td>\n\
                                  <td class="text-center" style="color:green">'+data[i]['fecha_final']+'</td>\n\
                                  <td class="text-right">'+ data[i]['monto'] +'</td>\n\
                                  <td class="text-center">'+ dias_mora +'</td>\n\
                                  <td class="text-right">'+ data[i]['mora_dia'] +'</td>\n\
                                  <td class="text-right">'+ saldo.toFixed(2) +'</td>\n\
                                  <td class="text-right">'+ data[i]['monto_adelanto'] +'</td>\n\
                                  <td class="text-right">'+ saldo_actual.toFixed(2) +'</td>\n\
                                  <td class="text-right">'+ cancelado.toFixed(2) +'</td>';
                                  if(data[i]['usuario_id'] == id_usuario){ 
                                      html += '<td><a id="fila_recibo_'+(i+1)+'" class="btn btn-light active btn-xs" disabled style="color:green" data-pagar'+data[i]['id_pensiones_estudiante']+'="22" onclick="pagar('+data[i]['id_pensiones_estudiante']+','+id_estudiante+','+data[i]['id_inscripcion']+','+cancelado+');bloquear_recibo('+(i)+');"><i class="icon-action-redo"></i> Pagar</a></td>';
                                  }else{
                                      html += '<td></td>';                                    
                                  }
                                  html += '</tr>';
                        }else{
                            dias_mora=dias;
                            saldo_actual=cancelado-data[i]['monto_adelanto']; 
                            html += '<tr style="background:#fff"><td>'+(i+1)+'</td>\n\
                                  <td class="text-justify" style="color:red"><font size=2>'+data[i]['nombre_pension']+' (cuota '+data[i]['nro']+')</font></td>\n\
                                  <td class="text-center">'+data[i]['compromiso']+'</td>\n\
                                  <td class="text-center" style="color:red">'+data[i]['fecha_final']+'</td>\n\
                                  <td class="text-right">'+ data[i]['monto'] +'</td>\n\
                                  <td class="text-center">'+ dias_mora +'</td>\n\
                                  <td class="text-right">'+ data[i]['mora_dia'] +'</td>\n\
                                  <td class="text-right">'+ saldo.toFixed(2) +'</td>\n\
                                  <td class="text-right">'+ data[i]['monto_adelanto'] +'</td>\n\
                                  <td class="text-right">'+ saldo_actual.toFixed(2) +'</td>\n\
                                  <td class="text-right">'+ cancelado.toFixed(2) +'</td>';                                 
                                  if(data[i]['usuario_id'] == id_usuario){
                                      html += '<td><a id="fila_recibo_'+(i+1)+'" class="btn btn-light active btn-xs" disabled style="color:red" data-pagar'+data[i]['id_pensiones_estudiante']+'="22" onclick="pagar('+data[i]['id_pensiones_estudiante']+','+id_estudiante+','+data[i]['id_inscripcion']+','+cancelado+');bloquear_recibo('+(i)+');"><i class="icon-action-redo"></i> Pagar</a></td>';
                                  }else{ 
                                       html += '<td></td>';                                      
                                  }
                                  html += '</tr>';
                        }

                        // if(data[i]['usuario_id'] == id_usuario){ 
                        //     //estado = '';
                        //     //console.log(estado+'vacio');
                        // }else{
                        //     //estado = 'disabled';
                        //     //console.log(estado+'dddddddddddddddd');
                        //     $( "#fila_recibo_"+(i+1)).prop( "disabled", true );
                        // } 

                    }else{ }
                }
                $("#contenedor_deudas_recibo").html(html);
            }
        });
    }
    //fin  

    function bloquear_recibo(id) {
        console.log(id+'  holaaaaaaa');
        i=id+1;
        console.log(i+' iiiiiii');
        $( "#fila_recibo_"+i ).addClass( "disabled" );
    }

    function activar_recibo(id) {
        $( "#fila_recibo_"+id ).removeClass( "disabled" );
    }

    function dias_transcurridos(fecha_final, fecha_pago){       
        if(typeof fecha_pago == "undefined"){
            console.log('no definido');
            fe = new Date();
            fecha_actual = fe.getFullYear() + "-" + (fe.getMonth()+1) + "-" + fe.getDate();
        }else{
            fecha_actual = <?=$fecha_actual;?>;
            console.log(fecha_actual+' fecha_actual');
        }
        var aFecha1 = fecha_final.split('-');
        var aFecha2 = fecha_actual.split('-');
        var fFecha1 = Date.UTC(aFecha1[0],aFecha1[1]-1,aFecha1[2]);
        var fFecha2 = Date.UTC(aFecha2[0],aFecha2[1]-1,aFecha2[2]);
        var dif = fFecha2 - fFecha1;
        var dias = Math.floor(dif / (1000 * 60 * 60 * 24));
        return dias;
    }

    //Obtiene los pagos 
    function pagar(elemento,id_estudiante,id_inscripcion,cancelar) {
        console.log(elemento+'   '+id_estudiante+'   '+id_inscripcion+ '   HHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH');
        adicionar_producto(elemento,id_estudiante,id_inscripcion,cancelar);
    }
    
    function adicionar_producto(id_producto,id_estudiante,id_inscripcion,cancelar) {

            var $ventas = $('#pensiones');
            $.ajax({
                url: '?/s-pago-nota/procesos',
                type: 'POST',
                data: {'boton': 'listar_datos_factura', 'id_producto': id_producto, 'id_estudiante': id_estudiante, 'id_inscripcion': id_inscripcion},
                dataType: 'JSON',
                success: function(data){
                    console.log(data);
                    $('#nombre_cliente').val(data.nombre_cliente);
                    $('#nit_ci').val(data.nit_ci);
                    //$('#factura_recibo').val(data.tipo_documento);
                    $("#factura_recibo").prepend("<option value='"+data.tipo_documento+"' selected='selected'>"+data.tipo_documento+"</option>");

                    var $producto = $ventas.find('[data-producto=' + id_producto + ']');
                    var $cantidad = $producto.find('[data-cantidad]');
                    var numero = $ventas.find('[data-producto]').length + 1;
                    var codigo = $.trim($('[data-codigo=' + id_producto + ']').text());
                    var nombre = $.trim($('[data-nombre=' + id_producto + ']').text()); 
                    var stock = $.trim($('[data-stock=' + id_producto + ']').text());
                    var valor = $.trim($('[data-valor=' + id_producto + ']').text());
                    var plantilla = '';
                    var cantidad;
                    plantilla = '<tr data-producto="'+i+'">' +
                                    '<td style="display: none" class="text-nowrap text-middle"><input type="hidden" value="'+data.id_inscripcion+'" name="inscripciones[]" tabindex="-1"><b>' + numero + '</b></td>' +
                                    '<td class="text-middle"><input type="hidden" value="'+data.estudiante_id+'" name="estudiantes[]" tabindex="-1"><font size=2>'+data.nombres+' '+data.primer_apellido+' ' + data.segundo_apellido + ',<br><i>' + data.nombre_aula+' '+ data.nombre_paralelo+' '+ data.nombre_nivel+' | '+ data.nombre_turno+'</i></font></td>' +
                                    '<td class="text-middle"><input type="hidden" value="' + data.id_pensiones_estudiante +'" name="pensiones[]" tabindex="-1"><font size=2>'+data.nombre_pension+' (cuota '+data.nro+')</font></td>' +
                                    '<td class="text-nowrap text-middle text-right" data-importe=""><input type="hidden" value="' + cancelar + '" name="montos[]" class="form-control text-right width-input" maxlength="10" autocomplete="off">' + cancelar + '</td>' +
                                    '<td class="text-nowrap text-middle text-center">' +
                                        '<a href="#"tabindex="-1" onclick="eliminar_producto(' + i + ');activar_pago(' + i + ');activar_recibo(' + i + ');"><span class="text-danger icon-trash"></span></a>' +
                                    '</td>' +
                                '</tr>';
                    $ventas.append(plantilla);
                    $ventas.find('[title]').tooltip({
                        container: 'body',
                        trigger: 'hover'
                    });
                    i++;
                    calcular_total();
                }
            });
    }
    function eliminar_producto(id_producto) { 
        bootbox.confirm('¿Está seguro que desea eliminar el producto?', function (result) {
            if(result){ 
                $('[data-producto=' + id_producto + ']').remove();
                renumerar_productos(); 
                calcular_total(); 
                //calcular_descuento(); 
            }
        });
    }

    function renumerar_productos() {
        var $ventas = $('#ventas tbody');
        var $productos = $ventas.find('[data-producto]');
        $productos.each(function (i) {
            $(this).find('td:first').text(i + 1);
        });
    }

    function descontar_precio(id_producto) {
        var $producto = $('[data-producto=' + id_producto + ']');
        var $precio = $producto.find('[data-precio]');
        var $descuento = $producto.find('[data-descuento]');
        var precio, descuento;

        precio = $.trim($precio.attr('data-precio'));
        precio = ($.isNumeric(precio)) ? parseFloat(precio) : 0;
        descuento = $.trim($descuento.val());
        descuento = ($.isNumeric(descuento)) ? parseFloat(descuento) : 0;
        precio = precio - (precio * descuento / 100);
        $precio.val(precio.toFixed(2));
        calcular_importe(id_producto);
    }

    function calcular_importe(id_producto) {
        var $producto = $('[data-producto=' + id_producto + ']');
        var $cantidad = $producto.find('[data-cantidad]');
        var $precio = $producto.find('[data-precio]');
        var $descuento = $producto.find('[data-descuento]');
        var $importe = $producto.find('[data-importe]');
        var cantidad, precio, importe, fijo;

        fijo = $descuento.attr('data-descuento');
        fijo = ($.isNumeric(fijo)) ? parseFloat(fijo) : 0;
        cantidad = $.trim($cantidad.val());
        cantidad = ($.isNumeric(cantidad)) ? parseInt(cantidad) : 0;
        precio = $.trim($precio.val());
        precio = ($.isNumeric(precio)) ? parseFloat(precio) : 0.00;
        descuento = $.trim($descuento.val());
        descuento = ($.isNumeric(descuento)) ? parseFloat(descuento) : 0;
        importe = cantidad * precio;
        importe = importe.toFixed(2);
        $importe.text(importe);

        calcular_total();
    }

    function calcular_total() {
        var $ventas = $('#ventas tbody');
        var $total = $('[data-subtotal]:first');
        var $importes = $ventas.find('[data-importe]');
        var importe, total = 0;

        $importes.each(function (i) { 
            importe = $.trim($(this).text());
            importe = parseFloat(importe);
            total = total + importe;
        });

        $total.text(total.toFixed(2));
        $('[data-ventas]:first').val($importes.length).trigger('blur'); 
        $('[data-total]:first').val(total.toFixed(2)).trigger('blur');
    }

    function tipo_descuento() {
        var descuento = $('#tipo').val();
        console.log(descuento);
        if(descuento==0){
            console.log(0);
           $('#div-descuento').hide();
          // $("input").prop('disabled', true);
        }else if(descuento==1){
            console.log(1);
           $('#div-descuento').show();
          // $("input").prop('disabled', true);
        }
        //calcular_descuento();
    }

    function calcular_saldo() {
        var $ventas = $('#ventas tbody');
        var $total = $('[data-subtotal]:first');
        var $importes = $ventas.find('[data-importe]');
        var acuenta = $('#acuenta_total').val();
        var importe, total = 0;

        $importes.each(function (i) {
            importe = $.trim($(this).text());
            importe = parseFloat(importe);
            total = total + importe;
        });
        $total.text(total.toFixed(2));
        var importe_total= total.toFixed(2);
        console.log(importe_total);
        console.log(acuenta);

        var total_descuento=0, formula=0, total_importe_descuento=0;

        if(acuenta==""){  
            saldo=0;
            $('#saldo').html(saldo.toFixed(2));
            $('#saldo_total').val(saldo.toFixed(2));

        }else if (acuenta!=""){
            saldo=parseFloat(importe_total)-parseFloat(acuenta);

            $('#saldo').html(saldo.toFixed(2));
            $('#saldo_total').val(saldo.toFixed(2));
        }
    }
    //fin 

    function calcular_descuento() {
        var descuento = $('#tipo_descuento').val();
        var monto = $('#monto').val();
        console.log('des:'+descuento+'  monto:'+monto);
        var total_descuento=0, formula=0, monto_total_descuento=0;

        if(descuento==0){  
            monto_total_descuento=parseFloat(monto)-parseFloat(descuento);
            $('#monto_total').val(monto_total_descuento.toFixed(2));
        }else if (descuento!=""){
            formula=(descuento/100)*monto;
            monto_total_descuento=parseFloat(monto)-parseFloat(formula);            
            $('#monto_total').val(monto_total_descuento.toFixed(2));
        }
    }

    $("#formulario").validate({
        rules: {
            nit_ci: {required: true},
            nombre_cliente: {required: true},
            factura_recibo: {required: true},
            fecha_emision: {required: true}
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: highlight,
        unhighlight: unhighlight,
        messages: {
        nit_ci: "Debe ingresar NIT/CI.",
        nombre_cliente: "Debe ingresar nombre del cliente.",
        factura_recibo: "Debe seleccionar el documento comprobante de pago.",
        fecha_emision: "Debe ingresar la fecha de pago."
        },
        //una ves validado guardamos los datos en la DB
        submitHandler: function(form){
        bootbox.confirm('¿Está seguro de realizar el pago?', function (respuesta) {
        if (respuesta) {
              var factura_recibo = $('#factura_recibo').val();
              var datos = $("#formulario").serialize();
              $.ajax({
                  type: 'POST',
                  url: "?/s-pago-nota/guardar",
                  data: datos,
                  success: function (pago) {
                    console.log(pago);
                    if (pago) {       
                        //imprimir_factura(venta);
                        // bootbox.confirm('¿Está seguro de realizar el pago?', function (respuesta) {
                        //     if (respuesta) {
                                console.log(respuesta+'id factura');
                                //guardar_factura();
                                var pag=$.trim(pago);
                                console.log(pag+'id factura');
    
                                if(factura_recibo=='FACTURA'){
                                   imprimir_factura(pag);
                                }else if(factura_recibo=='RECIBO'){
                                   imprimir_recibo(pag);
                                }
                                $("#formulario")[0].reset();
                                cargar_pagos_factura();
                                cargar_pagos_recibo();
                                historial_pagos();
                                $('#pensiones').html('');
                                $('#subtotal').html('0.00');
                                
                                alertify.success('El pago fue realizado satisfactoriamente.'); 
                        //     }
                        // });
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

    function imprimir_factura(resp) {
        window.open('?/s-pago-nota/imprimir-factura/'+resp, true);
        //window.open('?/s-pago-nota/pagar', true);
    }

    function imprimir_recibo(resp) {
        window.open('?/s-pago-nota/imprimir-recibo/'+resp, true);
    } 

    function historial_pagos(){
        //console.log('gffffffffffffffffffffffffffffffffffff');
            id_estudiante = $("#id_estudiante").val();
            $.ajax({
                url: '?/s-pago-nota/procesos',
                type: 'POST',
                data: {'boton': 'listar_historial_pensiones', 'id_estudiante': id_estudiante},
                dataType: 'JSON',
                success: function(data){
                    console.log('skjdhgsf');
                    console.log(data);
                    console.log('ddddd');
                    html="";
                    $("#contenedor_historial_pagos").html("");
                    for(var i=0; i < data.length;i++){
                        //contenido = data[i]['id_pensiones_estudiante'] +"*"+  data[i]['nombre_pension']+"*"+  data[i]['monto_cancelado'] +"*"+  data[i]['fecha_inicio'] +"*"+ data[i]['fecha_final']+"*"+ data[i]['estudiante_id'] +"*"+ data[i]['id_pensiones']+"*"+ data[i]['cancelado'] +"*"+ data[i]['fecha_cancelado'];
                        /*var dias = dias_transcurridos(data[i]['fecha_final']);
                        if(dias >= 0){
                            mora_dia = dias * data[i]['mora_dia'];
                        }else{
                            mora_dia = 0;
                        }*/
                        //if(data[i]['monto'] > data[i]['monto_cancelado']){
                            var saldo =0;
                            //console.log(contenido);
                            // if(data[i]['cancelado'] == 'SI'){
                            //     total = parseInt(data[i]['monto']) + mora_dia;
                            //     saldo = data[i]['monto'] - data[i]['monto_cancelado']; 
                            //     cancelado = "SI";
                            // }else{
                                var dias = dias_transcurridos(data[i]['fecha_final'], '');
                                //console.log(dias+'DIAS');
                                if(dias >= 0){
                                    mora_dia = dias * data[i]['mora_dia'];
                                }else{
                                    mora_dia = 0;
                                }
                                if(mora_dia >= 0){
                                    total = parseInt(data[i]['monto']) + mora_dia;
                                    saldo = total - data[i]['monto_cancelado'];
                                    cancelado = saldo;
                                }else{

                                    total = parseInt(data[i]['monto']);
                                    saldo = total - data[i]['monto_cancelado'];
                                    cancelado = saldo;
                                }
                                //cancelado = "NO";<td>'+(i+1)+'</td>\n\
                           // }

                            if(dias<0){
                               dias=0;
                            }

                            var monto_total=parseFloat(data[i]['monto'])+parseFloat(data[i]['mora_dia']*dias);
                            //var monto_total=0;

                            html += '<tr>\n\
                                  <td class="text-center"><font size=2>'+ data[i]['nro_factura'] +'</font></td>\n\
                                  <td class="text-center"><font size=1.5>'+ data[i]['documento_pago'] +'</font></td>\n\
                                  <td class="text-center"><font size=1.5>'+ data[i]['nombre_pension']+'<br><i>(cuota '+ data[i]['nro']+')</i></font></td>\n\
                                  <td class="text-center"><font size=2>'+ data[i]['fecha_final']+'</font></td>\n\
                                  <td class="text-center"><font size=2>'+ data[i]['fecha_general']+' '+data[i]['hora_general']+'</font></td>\n\
                                  <td class="text-right"><font size=2>'+ data[i]['monto'] +'</font></td>\n\
                                  <td class="text-right"><font size=2>'+ data[i]['mora_dia'] +'</font></td>\n\
                                  <td class="text-center"><font size=2>'+ dias +'</font></td>\n\
                                  <td class="text-center"><font size=2>'+ monto_total.toFixed(2) +'</font></td>\n\
                                  <td class="text-right"><font size=2>'+ data[i]['monto_cancelado'] +'</font></td>\n\
                                  <td class="text-right"><font size=2>'+ saldo.toFixed(2) +'</font></td>\n\
                                  <td class="text-center"><font size=1.5>'+ data[i]['nombres'] +' '+ data[i]['primer_apellido'] +' '+ data[i]['segundo_apellido'] +'<br><i>('+ data[i]['username'] +')</i></font></td>\n\
                                  <td class="text-center"><font size=1.5>'+ data[i]['compromiso'] +'</font></td>';
                        //}
                    }
                    $("#contenedor_historial_pagos").html(html);
                }
            });
    }
    //fin

    function imprimir_historial() {
        var id_estudiante = $('#id_estudiante').val();
        window.open('?/s-pago-nota/imprimir/'+id_estudiante, true);
    }  
</script>