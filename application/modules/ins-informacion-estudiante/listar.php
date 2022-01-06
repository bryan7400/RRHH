<?php

// exit();

// Obtiene la cadena csrf
$csrf = set_csrf();

$nombre_dominio = escape($_institution['nombre_dominio']);

// Obtiene el id de la gestion actual
$id_gestion = $_gestion['id_gestion'];

$contratos = $db->query("SELECT * FROM rrhh_contrato WHERE estado = 'A'")->fetch();
// Obtiene los permisos 
 
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_editar = in_array('editar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views); 
$permiso_contrato  = in_array('editar', $_views);


$permiso_crear = true;//;in_array('crear', $_views);
$permiso_ver = true;//;in_array('ver', $_views);
$permiso_editar = true;//;in_array('editar', $_views);
$permiso_eliminar = true;//;in_array('eliminar', $_views);
$permiso_imprimir = true;//;in_array('imprimir', $_views); 
$permiso_contrato  = true; //in_array('editar', $_views);


//Total inscritos activos por gestion sin importar el estado
$nroInscritosHistorial = $db->query("SELECT COUNT(estado_inscripcion) as inscritos FROM ins_inscripcion WHERE estado='A' AND gestion_id = $id_gestion")->fetch();

//Nro de Inscritos
$nroInscritos = $db->query("SELECT COUNT(estado_inscripcion) as inscritos FROM ins_inscripcion WHERE estado='A' AND gestion_id = $id_gestion AND estado_inscripcion = 'INSCRITO'")->fetch();

//Nro de inscritos nuevos y antiguos
$nroNA = $db->query("SELECT IFNULL(SUM(i.estado_estudiante= 'NUEVO'),0) AS nro_nuevos, IFNULL(SUM(i.estado_estudiante= 'ANTIGUO'),0) AS nro_antiguos 
FROM ins_inscripcion AS i
WHERE i.gestion_id = $id_gestion AND i.estado = 'A'")->fetch();

// Nro de incorporados, bajas y retirados
$nroIBR = $db->query("SELECT IFNULL(SUM(i.estado_inscripcion = 'INCORPORADO'),0) AS nro_incorporados, IFNULL(SUM(i.estado_inscripcion= 'BAJA'),0) AS nro_bajas,  IFNULL(SUM(i.estado_inscripcion= 'RETIRADO'),0) AS nro_retirados 
FROM ins_inscripcion AS i
WHERE i.gestion_id = $id_gestion AND i.estado = 'A'")->fetch();

// Obtiene los estudiantes
$estudiantes = $db->select('z.*')->from('vista_estudiantes z')->order_by('z.id_estudiante', 'asc')->fetch();

//Nro de Reservas
//$nroReservas = $db->query("SELECT COUNT(estado_inscripcion) as reservas FROM ins_inscripcion WHERE estado_inscripcion='RESERVA' AND gestion_id = $id_gestion")->fetch();

//Nro de inscritos hoy
$nroInsHoy = $db->query("SELECT COUNT(fecha_inscripcion)AS inscritos_hoy FROM ins_inscripcion WHERE DATE(fecha_inscripcion)=CURDATE()")->fetch();

//Nro de Varones y Mujeres
// $nroVM = $db->query("SELECT IFNULL(SUM(p.genero= 'v'),0) AS nro_varones, IFNULL(SUM(p.genero= 'm'),0) AS nro_mujeres,  COUNT(i.id_inscripcion) AS inscritos, IFNULL(ap.capacidad,0) AS cupo_total
// FROM ins_inscripcion AS i
// INNER JOIN ins_estudiante e ON e.id_estudiante = i.estudiante_id
// INNER JOIN sys_persona p ON p.id_persona = e.persona_id
// INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo = i.aula_paralelo_id
// WHERE i.gestion_id = $id_gestion AND i.estado = 'A'")->fetch();

//var_dump($estudiantes);exit();
// Obtiene los permisos  
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_editar = in_array('crear', $_views);
$permiso_editar_curso = in_array('editar-inscripcion', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);
$permiso_inscripcion = in_array('inscripcion-estudiante-tutor', $_views);
$permiso_pago = in_array('asignar-pago', $_views);

?>
<?php require_once show_template('header-design'); ?>
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">

<style>

.ribbons {
   
    top: 0px;
    background-color: #59b3ff !important;
    padding: 31px 15px !important;
    text-align: center !important;
    left: 5px !important;
    font-family: 'Circular Std Medium'!important;
    color: #fff !important;
    transform: rotate(45deg) !important;
}

.ribbons-text {
    transform: rotate(
315deg
) !important;
    position: absolute !important;
    top: 15px !important;
    left: 5px !important;
    color: #fff !important;
}



</style>

<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Informacion del Estudiante</h2>
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



<?php if (false) : ?>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="" style="margin-top:2%">
                <label class="control-label">Buscar estudiante: </label>
                <div class="controls control-group">
                    <select name="est_antiguos" id="est_antiguos" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./&() " required>
                        <option value="" selected="selected">Seleccionar</option>
                        <?php foreach ($est_antiguos as $antiguo) : ?>
                            <option value="<?= $antiguo['id_datos_estudiante']; ?>"><?= escape($antiguo['nombre_completo']); ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    </br>
    <div id="boton_inscripcion">
        <!-- <a href='?/s-inscripciones/crear/" + id_estudiante + "' class='btn btn-xs btn-warning' style='color:white'>Editar</a> -->
    </div>
    <div id="boton_pagado">
        <!-- <a href='?/s-inscripciones/crear/" + id_estudiante + "' class='btn btn-xs btn-warning' style='color:white'>Editar</a> -->
    </div>
    </br>
<?php endif ?>


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
                    <div class="btn-group ">
                         <div class="input-group">
                            <div class="input-group-append be-addon" >
                                <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item">Seleccionar acción</a>
                                    
                                    <?php if ($permiso_crear) : ?>
                                    <div class="dropdown-divider"></div>
                                    <a href="#" onclick="abrir_crear();" class="dropdown-item">Crear Informacion</a>
                                     
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
                        <table id="table" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
                            <thead>
                                <tr class="active">
                                    <th class="text-nowrap">#</th>
                                    <th class="text-nowrap">Foto</th>
                                    <th class="text-nowrap">Código</th>
                                    <th class="text-nowrap">Apellidos y Nombres</th>
                                    <th class="text-nowrap">C.I.</th>
                                    <th class="text-nowrap">RUDE</th>
                                    <th class="text-nowrap">Curso</th>
                                    <th class="text-nowrap">Tipo Estudiante</th>
                                    <th class="text-nowrap">Género</th>
                                    <th class="text-nowrap">Tutor</th>
                                    <th class="text-nowrap">Usuario Registro</th>
                                    <!-- <th class="text-nowrap">Contacto</th> -->
                                    <?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
                                        <th class="text-nowrap">Opciones</th>
                                    <?php endif ?>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class="active">
                                    <th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
                                    <th class="text-nowrap text-middle">Foto</th>
                                    <th class="text-nowrap text-middle">Código</th>
                                    <th class="text-nowrap text-middle">Apellidos y Nombres</th>
                                    <th class="text-nowrap text-middle">C.I.</th>
                                    <th class="text-nowrap text-middle">RUDE</th>
                                    <th class="text-nowrap text-middle">Curso</th>
                                    <th class="text-nowrap text-middle">Tipo Estudiante</th>
                                    <th class="text-nowrap text-middle">Género</th>
                                    <th class="text-nowrap text-middle">Tutor</th>
                                    <th class="text-nowrap">Usuario Registro</th>
                                    <!-- <th class="text-nowrap text-middle">Contacto</th> -->
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
                <input type="hidden" id="id_estudiante">
                <p>¿Esta seguro de eliminar la inscripcion del estudiante <span id="texto_estudiante"></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_eliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>




<!--modal para confirmar pago-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_confirmar">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <input type="hidden" id="id_estudiante">
                <p>¿Esta seguro de habilitar al estudiante <span id="texto_estudiante"></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_habilitar">Habilitar</button>
            </div>
        </div>
    </div>
</div>



<!--modal para asignar area-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_asignar_area">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form_asignar_area">
                <div class="modal-body">
                    <input type="hidden" id="id_estudiante_area" name="id_estudiante_area">
                    <p>Asignar area al estudiante <span id="texto_estudiante_area"></span>?</p>
                </div>

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
                    <label class="control-label">Area: </label>
                    <div class="controls control-group">
                        <select id="area" name="area" class="form-control">
                            <option value="">Seleccione</option>
                            <option value="CONSTRUCCION">CONSTRUCCION</option>
                            <option value="INFORMATICA">INFORMATICA</option>
                            <option value="CONTABILIDAD">CONTABILIDAD</option>
                            <option value="SALUD">SALUD</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btn_registrar_area">Registrar area</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- modal para dar de baja o retirar -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_baja_retirar">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form_baja_retirar">
                <div class="modal-body">
                    <input type="hidden" id="id_estudiante_rb" name="id_estudiante_rb">
                    <p>Estudiante <span id="texto_estudiante_rb"></span>?</p>
                </div>

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="margin-bottom: 4%;">
                    <label class="control-label">Estado inscripcion: </label>
                    <div class="controls control-group">
                        <select id="estado_rb" name="estado_rb" class="form-control">
                            <option value="RETIRADO">RETIRADO</option>                          
                            <option value="BAJA">BAJA</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom:15px;">
                        <label id="etiqueta_fecha" class="control-label">Fecha :</label>
                        <div class="controls control-group">
                            <input type='date' class='form-control' id="fecha_rb" name="fecha_rb" required/>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:15px;">
                        <label for="title" class="control-label">Observacion :</label>
                        <div class="controls control-group">
                        <textarea type="text" name="descripcion_rb" class="form-control" id="descripcion_rb" rows="4" maxlength="10000"></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btn_registrar_rb">Registrar estado inscripción</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--modal para revertir el estado de la eliminacion-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_revertir_estado_inscripcion">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <input type="hidden" id="id_estudiante_revertir_estado_inscripcion">
                <p>¿Esta seguro de revertir el estado de la inscripcion del estudiante <span id="texto_estudiante_revertir_estado_inscripcion"></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_revertir_estado_inscripcion">Revertir</button>
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
<script src="<?= js; ?>/selectize.min.js"></script>
<!--Mensajes de proceso cuando se realiza peticion ajax -->
<script src="<?= js; ?>/jquery.blockUI.js"></script>
<!-- Mi funcion para la Carga -->
<script src="<?= js; ?>/funciones.js"></script>
<!--script src="<?= $ruta ?>/s-gestion-escolar.js"></script-->
<?php require_once show_template('footer-design'); ?>
<?php
if ($permiso_editar) {
    require_once("editar.php");
}
if ($permiso_ver) {
    //require_once ("ver.php");
}
?>
<script>
    $(window).on("load", estado_caja);

    var nombre_dominio = "<?= $nombre_dominio ?>";

    $(function() {

        $("#form_asignar_area").validate({
            rules: {
                area: {
                    required: true
                }
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: highlight,
            unhighlight: unhighlight,
            messages: {
                area: "Debe seleccionar un area para el estudiante.",
            },
            //una ves validado guardamos los datos en la DB
            submitHandler: function(form) {
                var datos = $("#form_asignar_area").serialize();
                datos = datos + '&boton=' + 'guardar_area';
                console.log(datos);
                mensaje = "<img src='<?= imgs; ?>/loading.gif'  class='img-responsive' style='width:140px !important; height:115px !important' alt=''/><h1>Procesando espere...</h1>";
                transicion(mensaje);
                $.ajax({
                    type: 'POST',
                    url: "?/s-inscripciones/procesos",
                    data: datos,
                    //dataType: 'json',
                    success: function(resp) {
                        console.log(resp);
                        switch (resp) {
                            case '4':
                                transicionSalir();
                                $("#modal_asignar_area").modal("hide");
                                alertify.success('Se inscribio correctamente a un area');
                                dataTable.ajax.reload();
                                //location.reload();
                                break;

                            case '0':
                                transicionSalir();
                                $("#modal_asignar_area").modal("hide");
                                alertify.error('No de pudo asignar a un area');
                                dataTable.ajax.reload();
                                //location.reload();
                                break;
                        }
                    }
                });
            }
        });

        // Cambiar el estado de la inscripcion
        $("#form_baja_retirar").validate({
            rules: {
                estado_rb: {
                    required: true
                },
                fecha_rb: {
                    required: true
                }
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: highlight,
            unhighlight: unhighlight,
            messages: {
                estado_rb: "Debe seleccionar el estado de la inscripcion.",
                fecha_rb: "Debe ingresar la fecha del estado de inscripcion.",
            },
            //una ves validado guardamos los datos en la DB
            submitHandler: function(form) {
                var datos = $("#form_baja_retirar").serialize();
                datos = datos + '&boton=' + 'registrar_retirar_baja';
                console.log(datos);
                mensaje = "<img src='<?= imgs; ?>/loading.gif'  class='img-responsive' style='width:140px !important; height:115px !important' alt=''/><h1>Procesando espere...</h1>";
                transicion(mensaje);
                $.ajax({
                    type: 'POST',
                    url: "?/s-inscripciones/procesos",
                    data: datos,
                    //dataType: 'json',
                    success: function(resp) {
                        console.log(resp);
                        switch (resp) {
                            case '1':
                                transicionSalir();
                                $("#modal_baja_retirar").modal("hide");
                                alertify.success('Se realizo el cambio del estado de la inscripción');
                                dataTable.ajax.reload();                                
                                break;

                            case '0':
                                transicionSalir();
                                $("#modal_baja_retirar").modal("hide");
                                alertify.error('No de pudo realizar el cambio del estado de la inscripción');
                                break;
                        }
                    }
                });
            }
        });

        

        $("#boton_inscripcion").hide();
        <?php if ($permiso_crear) : ?>
            $(window).bind('keydown', function(e) {
                if (e.altKey || e.metaKey) {
                    switch (String.fromCharCode(e.which).toLowerCase()) {
                        case 'n':
                            e.preventDefault();
                            window.location = '?/gestiones/crear';
                            break;
                    }
                }
            });
        <?php endif ?>

        <?php if ($permiso_eliminar) : ?>
            $('[data-eliminar]').on('click', function(e) {
                e.preventDefault();
                var href = $(this).attr('href');
                var csrf = '<?= $csrf; ?>';
                bootbox.confirm('Está seguro que desea eliminar el gestion?', function(result) {
                    if (result) {
                        $.request(href, csrf);
                    }
                });
            });
        <?php endif ?>

        <?php if ($estudiantes) : ?>
            // $('#nivel_academico').DataFilter({
            //  filter: true,
            //  name: 'niveles',
            //  reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
            // });
        <?php endif ?>
        //carga toda la lista de grupo proyecto con DataTable


        $('#est_antiguos').selectize({
            persist: true,
            createOnBlur: true,
            create: true,
            onInitialize: function() {
                $('#est_antiguos').css({
                    display: 'block',
                    left: '-10000px',
                    opacity: '0',
                    position: 'absolute',
                    top: '-10000px'
                });
            },
            onChange: function() {
                $('#est_antiguos').trigger('blur');
            },
            onBlur: function() {
                $('#est_antiguos').trigger('blur');
            }
        }).on('change', function(e) {
            var codigo = $(this).val();
            valor = 'codigo=' + codigo + '&boton=' + 'nro_cuotas';
            console.log(valor);
            $.ajax({
                type: 'POST',
                url: "?/s-inscripciones/procesos",
                data: valor,
                success: function(resp) {
                    $("#boton_inscripcion").hide();
                    if ((resp * 1) > 0) {
                        var boton = "<h5> Tiene " + resp + " Deudas pendientes, su inscripcion no se podra realizar... Lo sentimos</h5>";
                        boton += "<a href='#' class='btn btn-xs btn-primary' style='color:white' onclick='mensualidad_pagadas(" + '"' + codigo + '"' + ")'>Saldo Pagado</a>";
                        $("#boton_inscripcion").show();
                        $("#boton_inscripcion").html(boton);
                        alertify.success('Tiene deudas pendientes');
                    } else {
                        var boton = "<a href='?/s-inscripciones/crear/0/" + codigo + "' class='btn btn-xs btn-primary' style='color:white'>Inscribir</a> <a href='#' class='btn btn-danger btn-xs' onclick='abrir_eliminar_ant(" + '"' + codigo + '"' + ")'><span class='icon-trash'></span></a>";
                        $("#boton_inscripcion").show();
                        $("#boton_inscripcion").html(boton);
                    }
                }
            });
        });

    });

    <?php if ($permiso_editar) : ?>
function abrir_editar_estudiante(contenido){
    var d = contenido.split("*");
    var nro=(d[1]);

    var $select2 =   $('#estudiante_id').selectize();
            var selectize2 = $select2[0].selectize;
            selectize2.setValue(nro);
            selectize2.refreshOptions();
    $("#categoria_informacion").val('');
    setcargo();
    setestudiantes();
    $("#modal_estudiante").modal("show");
    
    
}
<?php endif ?>
    <?php if ($permiso_crear) : ?>

        function abrir_crear() {
            $("#modal_estudiante").modal("show");
            $("#form_estudiante")[0].reset();
            $("#btn_editar").hide();
            $("#btn_nuevo").show();
        }
    <?php endif ?>

    function estado_caja() {

        console.log('holaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
        var id_estudiante = $('#id_estudiante').val();
        $.ajax({

            type: 'POST',

            url: "?/s-inscripciones/procesos",

            dataType: 'json',

            data: {
                'id_estudiante': id_estudiante,
                'boton': 'estado_caja'
            },

            success: function(data) {

                console.log(data);
                console.log('holaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
                if (data.contador == 1) {
                    $("#id_" + id_estudiante).html('SI');
                } else {
                    $("#id_" + id_estudiante).html('NO');
                }
            }

        });

    }
    var columns = [{
            data: 'id_estudiante'
        },
        {
            data: 'foto'
        },
        {
            data: 'codigo_estudiante'
        },
        {
            data: 'nombre_completo'
        },
        {
            data: 'numero_documento'
        },
        {
            data: 'nro_rude'
        },
        {
            data: 'curso'
        },
        {
            data: 'nombre_tipo_estudiante'
        },
        {
            data: 'genero'
        },
        {
            data: 'nombres_familiar'
        },
        {
            data: 'username'
        },
        
        
        //{data: 'contacto'}
    ];
    var cont = 0;
    //function listarr(){
    var dataTable = $('#table').DataTable({
        language: dataTableTraduccion,
        searching: true,
        paging: true,
        "lengthChange": true,
        "responsive": true,
        ajax: {
            url: '?/s-inscripciones/busqueda',
            dataSrc: '',
            type: 'POST',
            dataType: 'json'
        },
        columns: columns,

        "columnDefs": [{
                "render": function(data, type, row) {
                    var result = "";
                    var contenido = row['estado_inscripcion'] + "*" + row['id_estudiante'] + "*" + row['foto'] + "*" + row['codigo_estudiante'] + "*" + row['nombre_completo'] + "*" + row['segundo_apellido'] + "*" + row['nombres'] + "*" + row['numero_documento'] + "*" + row['genero'] + "*" + row['fecha_nacimiento'] + "*" + row['estado_estudiante'];
                    var id_estudiante = row['id_estudiante'];

                    //console.log('------> '+row['estado_inscripcion']);
                    
                    if(row['estado_inscripcion'] != "BAJA" && row['estado_inscripcion'] != "RETIRADO"){                     
                        //var url = "?/s-inscripciones/ver/" + row['id_estudiante'];        "<?php if ($permiso_ver) : ?><a href='?/s-inscripciones/editar-inscripcion-pago/" + id_estudiante + "' class='btn btn-xs btn-info'><span class='icon-eye'></span></a><?php endif ?> &nbsp" +
                        //"<?php if ($permiso_pago) : ?><a href='?/s-inscripciones/editar-inscripcion-pago/" + id_estudiante + "' class='btn btn-xs btn-success' style='color:white'>Pago</a><?php endif ?> &nbsp" +
                        result +=
                            
                            
                            "<?php if ($permiso_editar) : ?><a href='#' class='btn btn-warning btn-xs' style='color:white' onclick='abrir_editar_estudiante("+'"'+contenido+'"'+")'><span class='icon-note' style='color:black'></span></a><?php endif ?> &nbsp" +
                            "<?php if ($permiso_editar_curso) : ?><a href='?/s-inscripciones/editar-inscripcion/" + id_estudiante + "' disabled class='btn btn-xs btn-primary' style='color:white'> Curso</a><?php endif ?> &nbsp" 
                            ;

                        if (row['corresponde_area'] == "SI") {
                            result += "<?php if (true) : ?><a href='#' class='btn btn-info btn-xs' onclick='abrir_asignar_area(" + '"' + contenido + '"' + ")' style='color:white'><span class=''></span>Asignar Area</a><?php endif ?>";
                        }   
                    }else{
                        result +=
                            "<?php if ($permiso_ver) : ?><a href='#' class='btn btn-xs btn-success' onclick='abrir_revertir_estado_inscripcion(" + '"' + contenido + '"' + ")'><span class='icon-magic-wand'></span></a><?php endif ?>";                            
    
                    }

                    return result;
                },
                "targets": 11
            },
            
            {
                "render": function(data, type, row) {
                    switch (row['genero']) {
                        case 'v':
                            return "Varón";
                            break;
                        case 'm':
                            return "Mujer";
                            break;
                    }
                },
                "targets": 8
            },
            {
                "render": function(data, type, row) {
                    var imagen = "";
                    //var foto = "imgs . '/avatar.jpg'";
                    if (row['foto'] == null) { //""
                        foto = "assets/imgs/avatar.jpg";
                    } else if ((row['foto'] == "")) {
                        foto = "assets/imgs/avatar.jpg";
                    } else {
                        foto = "files/"+nombre_dominio+"/profiles/estudiantes/" + row['foto'] + ".jpg";
                    }

                    if(row['estado_estudiante']=="nuevo"){
                        imagen += "<div class='product-img-head'>";
                        imagen += " <div class='product-img'>";
                        imagen += "<img src='" + foto + "' class='img-rounded cursor-pointer' data-toggle='modal' data-target='#modal_mostrar' data-modal-size='modal-md' data-modal-title='Imagen' width='74' height='74'>";
                        imagen += " <div class='ribbons small'></div>";
                        imagen += " <div class='ribbons-text'>Nuevo</div>";                 
                        imagen += "</div>"; 
                    }else{
                        imagen += "<div class='product-img-head'>";
                        imagen += " <div class='product-img'>";
                        imagen += "<img src='" + foto + "' class='img-rounded cursor-pointer' data-toggle='modal' data-target='#modal_mostrar' data-modal-size='modal-md' data-modal-title='Imagen' width='74' height='74'>";                                   
                        imagen += "</div>"; 
                    }   
                                    
                    return imagen;
                },
                "targets": 1
            },
            {
                "render": function(data, type, row) {

                    familiar = row['nombres_familiar'] + "<br> Contacto : " + row['contacto'];
                    return familiar;
                },
                "targets": 9
            },
            
            {
                "render": function(data, type, row) {
                    var nombre_curso = ""
                    if (row['estado_inscripcion'] == "BAJA") {
                        nombre_curso += "<span class='m-r-20 d-inline-block'>";
                        nombre_curso += "   <span>" + row['nombre_completo'] + "</span>";
                        nombre_curso += "</span>";
                        nombre_curso += "<br>";
                        nombre_curso += "<span class='m-r-20 d-inline-block'>";
                        nombre_curso += "   <span class='m-l-1 text-danger'>" + row['estado_inscripcion'] + "</span>";
                        nombre_curso += "</span>";
                    } else if (row['estado_inscripcion'] == "RETIRADO") {
                        nombre_curso += "<span class='m-r-20 d-inline-block'>";
                        nombre_curso += "   <span>" + row['nombre_completo'] + "</span>";
                        nombre_curso += "</span>";
                        nombre_curso += "<br>";
                        nombre_curso += "<span class='m-r-20 d-inline-block'>";
                        nombre_curso += "   <span class='m-l-1 text-warning'>" + row['estado_inscripcion'] + "</span>";
                        nombre_curso += "</span>";
                    }else{
                        nombre_curso += "<span class='m-r-20 d-inline-block'>";
                        nombre_curso += "   <span>" + row['nombre_completo'] + "</span>";
                        nombre_curso += "</span>";
                        nombre_curso += "<br>";
                        nombre_curso += "<span class='m-r-20 d-inline-block'>";
                        nombre_curso += "   <span class='m-l-1 text-success'>" + row['estado_inscripcion'] + "</span>";
                        nombre_curso += "</span>";
                    }

                    return nombre_curso;
                },
                "targets": 3
            },{
                "render": function(data, type, row) {
                    cont = cont + 1;
                    return cont;
                },
                "targets": 0
            }
            
        ]
    });
    //} 






    //Datos de los campos de la tabla ins_estudiante_antiguo
    var columns_ = [{
            data: 'codigo'
        },
        {
            data: 'nombre_completo'
        },
        {
            data: 'fecha_nacimiento'
        },
        {
            data: 'tutor'
        },
        {
            data: 'cuotas_pendiente'
        },
        {
            data: 'curso'
        },
        //{data: 'contacto'}
    ];
    var cont = 0;
    //function listarr(){
    var dataTable_ = $('#table_ant').DataTable({
        language: dataTableTraduccion,
        searching: true,
        paging: true,
        "lengthChange": true,
        "responsive": true,
        ajax: {
            url: '?/s-inscripciones/busqueda-antiguos',
            dataSrc: '',
            type: 'POST',
            dataType: 'json'
        },
        columns: columns_,
        "columnDefs": [{
            "render": function(data, type, row) {
                cont = cont + 1;
                return cont;
            },
            "targets": 0
        }]
    });


    <?php if ($permiso_ver) : ?>

        function ver() {
            $('#table tbody').on('click', 'tr', function() {
                var data = dataTable.row(this).data();
                //alert( 'You clicked on '+data[0]+'\'s row' );
                $("#estudiante_ver").modal("show");
                $("#nombre_estudiante").text(data['nombres']);
                $("#tipo_documento").text(data['tipo_documento']);
                $("#numero_documento").text(data['numero_documento']);
                $("#complemento").text(data['complemento']);
                $("#genero").text(data['genero']);
                $("#fecha_nacimiento").text(data['fecha_nacimiento']);
            });
        }
    <?php endif ?>



    <?php if (true) : ?>

        function abrir_eliminar(contenido) {
            $("#modal_eliminar").modal("show");
            var d = contenido.split("*");
            $("#id_estudiante").val(d[1]);
            $("#texto_estudiante").text(d[4]);
        }

    <?php endif ?>

    <?php if (true) : ?>

        function abrir_eliminar_ant(contenido) {
            $("#modal_eliminar").modal("show");
            //var d = contenido.split("*");
            $("#id_estudiante").val(contenido);
            $("#texto_estudiante").text(d[4]);
        }

    <?php endif ?>


    <?php if (true) : ?>

        function abrir_baja_retirar(contenido) {
            $("#modal_baja_retirar").modal("show");
            var d = contenido.split("*");
            $("#id_estudiante_rb").val(d[1]);
            $("#texto_estudiante_rb").text(d[4]);
        }

    <?php endif ?>

    <?php if (true) : ?>

    function abrir_revertir_estado_inscripcion(contenido) {
        $("#modal_revertir_estado_inscripcion").modal("show");
        var d = contenido.split("*");
        $("#id_estudiante_revertir_estado_inscripcion").val(d[1]);
        $("#texto_estudiante_revertir_estado_inscripcion").text(d[4]);
    }

    <?php endif ?>  
    

    <?php if (true) : ?>

        function abrir_asignar_area(contenido) {
            $("#modal_asignar_area").modal("show");
            var d = contenido.split("*");
            $("#id_estudiante_area").val(d[1]);
            $("#texto_estudiante_area").text(d[4]);
        }

    <?php endif ?>

    <?php if (true) : ?>

        function mensualidad_pagadas(contenido) {
            $("#modal_confirmar").modal("show");
            var d = contenido.split("*");
            $("#id_estudiante").val(d[1]);
            $("#texto_estudiante_").text(d[4]);
        }

    <?php endif ?>


    $("#btn_eliminar").on('click', function() {
        //alert($("#id_estudiante").val())
        id_estudiante = $("#id_estudiante").val();
        $.ajax({
            url: '?/s-inscripciones/eliminar-e',
            type: 'POST',
            data: {
                'id_estudiante': id_estudiante
            },
            success: function(resp) {
                //alert(resp)
                switch (resp) {
                    case '1':
                        $("#modal_eliminar").modal("hide");
                        dataTable.ajax.reload();
                        //refrescarPagina()
                        alertify.success('Se dio de baja el estudiante correctamente');
                        break;
                    case '2':
                        $("#modal_eliminar").modal("hide");
                        alertify.error('No se pudo eliminar ');
                        break;
                }
            }
        })
    })
    
    $("#btn_revertir_estado_inscripcion").on('click', function() {
        //alert($("#id_estudiante").val())
        id_estudiante = $("#id_estudiante_revertir_estado_inscripcion").val();
        $.ajax({
            url: '?/s-inscripciones/revertir-estado-inscripcion',
            type: 'POST',
            data: {
                'id_estudiante': id_estudiante
            },
            success: function(resp) {
                //alert(resp)
                switch (resp) {
                    case '1':
                        $("#modal_revertir_estado_inscripcion").modal("hide");
                        dataTable.ajax.reload();
                        alertify.success('Se  revertio el estado del estudiante correctamente');
                        break;
                    case '2':
                        $("#modal_revertir_estado_inscripcion").modal("hide");
                        alertify.success('No se puede revertir el estado del estudiante');
                        break;
                    
                }
            }
        })
    })

    $("#btn_habilitar").on('click', function() {
        //alert($("#id_estudiante").val())
        id_estudiante = $("#id_estudiante").val();
        $.ajax({
            url: '?/s-inscripciones/habilitar-e',
            type: 'POST',
            data: {
                'id_estudiante': id_estudiante
            },
            success: function(resp) {
                //alert(resp)       

                if (resp > 0) {
                    $("#modal_confirmar").modal("hide");
                    //dataTable.ajax.reload();
                    //refrescarPagina()
                    alertify.success('Se habilito al estudiante correctamente');
                    var boton = "<a href='?/s-inscripciones/crear/0/" + resp + "' class='btn btn-xs btn-primary' style='color:white'>Inscribir</a>";
                    $("#boton_inscripcion").show();
                    $("#boton_inscripcion").html(boton);
                } else {
                    $("#modal_confirmar").modal("hide");
                    alertify.error('No se pudo eliminar ');
                }

            }
        })
    })
    
    function reporte_estudiantes_usuarios() {       
            $(location).attr('href', '?/s-inscripciones/excel-estudiantes-usuarios');       
    }

    function refrescarPagina() {
        location.reload();
    }
</script>


<script>
$(function () {
    
    <?php if ($permiso_crear) : ?>
    $(window).bind('keydown', function (e) {
        if (e.altKey || e.metaKey) {
            switch (String.fromCharCode(e.which).toLowerCase()) {
                case 'n':
                    e.preventDefault();
                    //window.location = '?/gestiones/crear';
                    $('#modal_estudiante').modal('toggle');

                    $("#modal_estudiante").modal("show");

                    
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
        bootbox.confirm('Está seguro que desea eliminar al Contrato?', function (result) {
            if (result) {
                $.request(href, csrf);
            }
        });
    });
    <?php endif ?>
     
    <?php if ($contratos) : ?>
    // $('#nivel_academico').DataFilter({
    //  filter: true,
    //  name: 'niveles',
    //  reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
    // });
    <?php endif ?>
    //carga toda la lista de grupo proyecto con DataTable
});




<?php if ($permiso_editar) : ?>
function abrir_editar2(contenido){
    $("#form_estudiante")[0].reset();
    $("#btn_nuevo").hide();
    $("#btn_editar").show();
    $("#archivo_documento").attr('required', true);
    $('input[name="genero"]').removeAttr('checked');
    $("#modal_estudiante").modal("show");
    var d = contenido.split("*");
    $("#id_contrato").val(d[0]);
    $("#nombres").val(d[1]);
    $("#tipo_documento").val(d[2]);
    $("#numero_documento").val(d[3]);
    $("#expedido").val(d[4]);
    $("input[name=genero][value=" + d[5] + "]").attr('checked', 'checked');
    $("#fecha_nacimiento").val(d[6]);
    $("#direccion").val(d[7]);
    $("#archivo_documento").val(d[8]);
    $("#celular").val(d[9]);
    $("#email").val(d[10]);
    
     
    
}
<?php endif ?>


<?php if ($permiso_editar) : ?>
function abrir_editar(contenido){
    var d = contenido.split("*");
    var nro=(d[0]);
    $.ajax({
        url: '?/ins-informacion-estudiante/procesos',
        type: 'POST',
        data:{
            'accion': 'recuperar_datos',
            'id_informacion_estudiante':nro 
            },
        dataType: 'JSON',
        success: function(resp){    


    
    $("#btn_nuevo").hide();
    $("#btn_editar").show();
    $("#id_informacion_estudiante").val(resp["id_informacion_estudiante"]);
    $("#categoria_informacion").val(resp["categoria_informacion"]);
    $("#nombre").val(resp["nombre"]);
    $("#celular").val(resp["celular"]);
    $("#tipo_documento").val(resp["tipo_documento"]);
    $("#descripcion").val(resp["descripcion"]);
    
    setcargo();

   
        }
    });
}
    
     
    

<?php endif ?>

function abrir_ordenar_contratos(){
    $("#modal_ordenar_contrato").modal("show");
}

<?php if ($permiso_crear) : ?>
function abrir_crear(){
    $('#table1').hide();
 
    $("#form_estudiante")[0].reset();

    $("#modal_estudiante").modal("show");
    $("#btn_editar").hide();
    $("#fotedit").hide(); 

    $("#btn_nuevo").show();
}
<?php endif ?>


//} 
<?php if ($permiso_ver) : ?>
function ver(contenido){
    var d = contenido.split("*");
    $("#area_ver").modal("show");
    $("#descripcion_ver").text(d[1]);
    $("#ponderado_ver").text(d[2]);
    $("#gestion_ver").text(d[3]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
function abrir_eliminar2(contenido){
    $("#modal_eliminar").modal("show");
    var d = contenido.split("*");
    $("#id_informacion_estudiante").val(d[0]);
    $("#texto_contrato").text(d[1]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
$("#btn_eliminar").on('click', function(){

    id_informacion_estudiante = $("#id_informacion_estudiante").val();
    $.ajax({
        url: '?/ins-informacion-estudiante/eliminar',
        type:'POST',
        data: {'id_informacion_estudiante':id_informacion_estudiante},
        success: function(resp){
            //alert(resp)
            $("#id_informacion_estudiante").val('0');
    $("#categoria_informacion").val('');
    $("#nombre").val('');
    $("#celular").val('');
    $("#descripcion").val('');
            switch(resp){

    

                case '1': $("#modal_eliminar").modal("hide");
                            dataTable1.ajax.reload();
                            alertify.success('Se elimino el contrato correctamente');break;
                case '2': $("#modal_eliminar").modal("hide");
                            alertify.error('No se pudo eliminar ');
                            break;
            }
        }
    })
})
<?php endif ?>


<?php if ($permiso_eliminar) : ?>
    function abrir_eliminar(contenido){
    var d = contenido.split("*");
    $("#id_informacion_estudiante").val(d[0]);
    $("#texto_contrato").text(d[1]);
    if(confirm("Esta seguro de eliminar la información?")){

        
        id_informacion_estudiante = $("#id_informacion_estudiante").val();
    $.ajax({
        url: '?/ins-informacion-estudiante/eliminar',
        type:'POST',
        data: {'id_informacion_estudiante':id_informacion_estudiante},
        success: function(resp){
            //alert(resp)
            $("#id_informacion_estudiante").val('0');
    $("#categoria_informacion").val('');
    $("#nombre").val('');
    $("#celular").val('');
    $("#descripcion").val('');
            switch(resp){

    

                case '1': 
                            dataTable1.ajax.reload();
                            alertify.success('Se elimino el contrato correctamente');break;
                case '2': 
                            alertify.error('No se pudo eliminar ');
                            break;
            }
        }
    })




    }
    else{
        return false;
    }
}
<?php endif ?>
</script>