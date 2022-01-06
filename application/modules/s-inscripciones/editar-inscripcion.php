<?php
$id_estudiante_editar = (isset($_params[0])) ? $_params[0] : 0;

$nombre_dominio = escape($_institution['nombre_dominio']);

//var_dump($id_estudiante);exit();
$gestion = $_gestion['id_gestion'];
$estudiante = $db->query("SELECT e.*, a.*
							  FROM vista_estudiantes e
							  LEFT JOIN ins_inscripcion i ON i.estudiante_id = e.id_estudiante
							  LEFT JOIN vista_aula_paralelo a ON a.id_aula_paralelo = i.aula_paralelo_id
                              WHERE e.id_estudiante = $id_estudiante_editar AND i.gestion_id = $gestion")->fetch_first();

// Obtiene nicel académico
//$nivel = $db->select('z.*')->from('ins_nivel_academico z')->order_by('id_nivel_academico')->fetch();
$nivel = $db->query("SELECT * FROM ins_nivel_academico na WHERE na.gestion_id = $gestion AND na.estado = 'A' ORDER BY na.id_nivel_academico ")->fetch();
// Obtiene nicel académico
$tipo_estudiante = $db->query("SELECT * FROM ins_tipo_estudiante te WHERE te.gestion_id = $gestion AND te.estado = 'A' ORDER BY te.id_tipo_estudiante ")->fetch();


?>

<?php require_once show_template('header-design'); ?>

<!-- ============================================================== -->
<!-- pageheader  -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Editar Paralelo</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Estudiantes inscritos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar paralelo</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- pageheader  -->
<!-- ============================================================== -->

<div class="" style="margin-left: -2%">
    <div class="dashboard-influence">
        <div class="container-fluid dashboard-content">
            <div class="card influencer-profile-data">
                <div class="card-body">
                    <div class="form-row">
                        <div class="col col-xl-3 col-lg-4 col-md-6 col-sm-12 text-center">
                            
                            <?php 
                            	if ($estudiante['foto'] == null) { 
            						$foto = "assets/imgs/avatar.jpg";
            					} else if ($estudiante['foto'] == "") {
            						$foto = "assets/imgs/avatar.jpg";
            					} else {
            						$foto = "files/".$nombre_dominio."/profiles/estudiantes/" . $estudiante['foto']. ".jpg";
            					}
                            
                            ?>
                            
                            <img src="<?= $foto ?>" alt="User Avatar" class="rounded-circle user-avatar-xxl"> 
                        </div>
                        <div class="col col-xl-5 col-lg-4 col-md-6 col-sm-12">
                            <div class="row">
                                <div class="user-avatar-name">
                                    <h2 class="mb-1"><?= $estudiante['primer_apellido'] . " " . $estudiante['segundo_apellido'] . " " .  $estudiante['nombres']; ?></h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="user-avatar-address">
                                    <div class="row" style="margin-bottom: 1%;">
                                        <div class="col col-md-6 col-sm-6">
                                            <span> <b>Rude: </b> <?= ($estudiante['rude'] == "") ? "Sin Rude" : $estudiante['rude']; ?></span>
                                        </div>
                                        <div class="col col-md-6 col-sm-6">
                                            <span> <b> Código Estudiante: </b> <?= ($estudiante['codigo_estudiante'] == "") ? "Sin Codigo de Estudiate" : $estudiante['codigo_estudiante']; ?> </span>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 1%;">
                                        <div class="col col-md-6 col-sm-6">
                                            <span><b> Tipo de Documento: <?= ($estudiante['numero_documento'] == "") ? "Sin CI." : $estudiante['numero_documento']; ?></b></span>
                                        </div>
                                        <div class="col col-md-4 col-sm-6">
                                            <span><b>Género</b> <?= ($estudiante['genero'] == "v") ? "Varón" : "Mujer"; ?> </span>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 1%;">
                                        <div class="col col-md-6 col-sm-6">
                                            <b>Fecha de Nacimiento</b> <?= ($estudiante['fecha_nacimiento'] == "") ? "Sin Fecha de Nacimiento" : $estudiante['fecha_nacimiento']; ?> </span>
                                        </div>
                                        <div class="col col-md-6 col-sm-6">
                                            <span class=""><b>Dirección: </b><?= ($estudiante['direccion'] == "") ? "Sin Dirección" : $estudiante['direccion']; ?></span>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 1%;">
                                        <div class="col col-md-6 col-sm-6">
                                            <span><b> Curso:</b> <?= ($estudiante['nombre_aula'] == "") ? "Sin Curso" : $estudiante['nombre_aula']; ?> <?= ($estudiante['nombre_paralelo'] == "") ? "Sin Paralelo" : $estudiante['nombre_paralelo']; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <!--div class="thumbnail hidden" data-print-code="true">
								<img class="barcode img-responsive" jsbarcode-format="code128" jsbarcode-value="<?= substr($estudiante['numero_documento'], 2); ?>" jsbarcode-displayValue="true" jsbarcode-width="2" jsbarcode-height="64" jsbarcode-margin="0" jsbarcode-textMargin="-3" jsbarcode-fontSize="20" jsbarcode-lineColor="#333">
							</div-->
                            <div class="" align="center">
                                <div class="" id="qr">
                                </div>
                                <div><?= escape($estudiante['codigo_estudiante']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-influence">
        <div class="container-fluid dashboard-content">
            <div class="card influencer-profile-data">
                <div class="card-body">
                    <form id="form_inscripcion_editar">
                        <div class="influence-profile-content pills-regular">
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-campaign" role="tabpanel" aria-labelledby="pills-campaign-tab">

                                    <input type="hidden" id="id_inscripciones" name="id_inscripciones">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="section-block">
                                            <input type="hidden" name="ids_familar" id="ids_familar" value="">
                                            <h3 class="section-title">Modificar Paralelo</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                            <div class="card  alert-primary-">
                                                <div class="card-body">
                                                    <h4 class="mb-1">Tipo de estudiante</h4>
                                                    <div class="control-group">
                                                        <select name="tipo_estudiante" id="tipo_estudiante" class="form-control">
                                                            <option value="" selected="selected">Seleccionar</option>
                                                            <?php foreach ($tipo_estudiante as $value) : ?>
                                                                <option value="<?= $value['id_tipo_estudiante']; ?>"><?= escape($value['nombre_tipo_estudiante']); ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                            <div class="card  alert-primary-">
                                                <div class="card-body">

                                                    <h4 class="mb-1">Turno </h4>
                                                    <div class="control-group">
                                                        <select name="turno" id="turno" class="form-control">
                                                            <option value="" selected="selected">Seleccionar</option>
                                                            <?php foreach ($turnos as $value) : ?>
                                                                <option value="<?= $value['id_turno']; ?>"><?= escape($value['nombre_turno']); ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                    <br>
                                                    <h4 class="mb-1">Nivel</h4>
                                                    <div class="control-group">
                                                        <select name="nivel_academico" id="nivel_academico" class="form-control" onchange="listar_curso_nivel();">
                                                            <option value="" selected="selected">Seleccionar</option>
                                                            <?php foreach ($nivel as $value) : ?>
                                                                <option value="<?= $value['id_nivel_academico']; ?>"><?= escape($value['nombre_nivel']); ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                            <div class="card  alert-primary-">
                                                <div class="card-body">
                                                    <h4 class="mb-1">Curso</h4>
                                                    <div class="control-group">
                                                        <select name="select_curso" id="select_curso" onchange="listar_vacantes();" class="form-control">
                                                            <option value="" selected="selected">Seleccionar</option>
                                                        </select>
                                                    </div>
                                                </div>                                              
                                            </div>
                                        </div>                                       

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-right">
                                <!-- <button type="submit" class="btn btn-danger pull-right" onclick="atrasVacunas()">Atras</button> -->
                                <button type="submit" class="btn btn-primary pull-right" id="btn_inscripcion">Modificar</button>
                            </div>
                            <!--input type="hidden" id="correo" onclick="correo_prueba();" class="btn btn-secondary" value="correo"-->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-influence" style="margin-top: -7%">
        <div class="container-fluid dashboard-content">
            <div class="row">
                <!-- ============================================================== -->
                <!-- campaign activities   -->
                <!-- ============================================================== -->
                <div class="col-lg-12">
                    <div class="section-block">
                        <h3 class="section-title">Familiares</h3>
                    </div>
                    <div class="card">
                        <div class="campaign-table table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="border-0">
                                        <th class="border-0">Nro.</th>
                                        <th class="border-0">Primer Apellido</th>
                                        <th class="border-0">Segundo Apellido</th>
                                        <th class="border-0">Nombres</th>
                                        <th class="border-0">Ocupación</th>
                                        <th class="border-0">Dirección Oficina</th>
                                        <th class="border-0">Telefóno Oficina</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $familiar = $db->select('e.*')
                                        ->from('vista_estudiante_familiar e')
                                        ->where('e.id_estudiante', $estudiante['id_estudiante'])->fetch();
                                    foreach ($familiar as $key => $familia) {

                                        ?>
                                        <tr>
                                            <td><?= escape($key + 1); ?></td>
                                            <td><?= escape($familia['primer_apellido']); ?></td>
                                            <td><?= escape($familia['segundo_apellido']); ?></td>
                                            <td><?= escape($familia['nombres']); ?></td>
                                            <td><?= escape($familia['profesion']); ?></td>
                                            <td><?= escape($familia['direccion_oficina']); ?></td>
                                            <td><?= escape($familia['telefono_oficina']); ?></td>

                                        </tr>

                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end campaign activities   -->
                <!-- ============================================================== -->
            </div>
        </div>
    </div>
</div>

</div>
<?php require_once show_template('footer-design'); ?>
<script src="<?= themes; ?>/concept/assets/vendor/cropper-mazter/js/cropper.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/cropper-mazter/js/imagenes.js"></script>
<!--script src="<?= themes; ?>/concept/assets/vendor/cropper-mazter/js/main.js"></script-->
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.es.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-fileinput-master/js/fileinput.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-fileinput-master/js/es.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/educheck.js"></script>

<script src="<?= js; ?>/JsBarcode.all.min.js"></script>
<script src="<?= js; ?>/qrcode.min.js"></script>

<script>
    var id_estudiante = 0;
    //Variables para el cambio de paralelo
    var id_tipo_estudiante = 0;
    var id_turno = 0;
    var id_nivel_academico = 0;
    var id_curso = 0;

    var id_estudiante_editar = <?= $id_estudiante_editar; ?>;

    $(function() {

        JsBarcode('.barcode').init();
        //cargar_tipo_documento();
        //listar_documentos(arrayDocumentos);
        datos_estudiante(id_estudiante_editar);
        //ocultamos la reserva de la inscripcion de estudiante
        //$("#btn_reservar_guardar").hide();

        /************************************************************/
        //Desabilitamos los valores tipo de estudiante turno y nivel
        /************************************************************/
        $('#tipo_estudiante').prop('disabled', 'disabled');
		$('#turno').prop('disabled', 'disabled');
		$('#nivel_academico').prop('disabled', 'disabled');
		//$('#select_curso').prop('disabled', 'disabled');


        /************************************************************/
        /*   t1 formulario de registro de inscripcion    */
        /************************************************************/
        $("#form_inscripcion_editar").validate({
            rules: {
                tipo_estudiante: {
                    required: true
                },
                turno: {
                    required: true
                },
                nivel_academico: {
                    required: true
                },
                select_curso: {
                    required: true
                }
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: highlight,
            unhighlight: unhighlight,
            messages: {
                tipo_estudiante: "Debe seleccionar el tipo de estudiante.",
                turno: "Debe seleccionar un turno para la inscripcion.",
                nivel_academico: "Debe seleccionar el nivel academico.",
                select_curso: "Debe seleccionar el curso."
            },
            //una ves validado guardamos los datos en la DB
            submitHandler: function(form) {
                //console.log(datos);
                if (id_tipo_estudiante == $("#tipo_estudiante option:selected").val() && id_turno == $("#turno option:selected").val() && id_nivel_academico == $("#nivel_academico option:selected").val() && id_curso == $("#select_curso option:selected").val()) {
                    alertify.warning('No se realizo ningun cambio');
                } else {
                    //alertify.error('Se edito correctamente al nuevo curso');
                    var datos = $("#form_inscripcion_editar").serialize();
                    datos = datos + '&a_id_tipo_estudiante='+id_tipo_estudiante;
                    datos = datos + '&a_id_turno='+id_turno;
                    datos = datos + '&a_id_nivel_academico='+id_nivel_academico;
                    datos = datos + '&a_id_curso='+id_curso;
                    datos = datos + '&estudiante_id='+id_estudiante_editar;
                    datos = datos + '&boton=' + 'guardar_inscripcion_editar';
                    $.ajax({
                        type: 'POST',
                        url: "?/s-inscripciones/procesos",
                        data: datos,
                        dataType: 'json',
                        success: function(resp) {
                            console.log(resp);
                            switch (resp['estado']) {
                                case 1:
                                    alertify.success('Se edito correctamente inscripcion');
                                    location.reload();
                                    break;
                                case 2:
                                    
                                    alertify.error('No se pudo editar la inscripcion');
                                    
                                    break;
                            }
                        }
                    });
                }


            }
        })
    });


    //Funciones de los metodos

    function datos_estudiante(id_estudiante_editar) {
        //console.log("Hola Luis");	
        $.ajax({
            url: '?/s-inscripciones/procesos',
            type: 'POST',
            data: {
                'id_estudiante': id_estudiante_editar,
                'boton': 'datos_estudiante'
            },
            dataType: 'JSON',
            success: function(resp) {
                console.log(resp);
                id_estudiante = resp['datos_personales']['id_estudiante'];
                id_inscripcion_rude = resp['datos_personales']['id_ins_inscripcion_rude'];

                //Preguntamos los familiares
                a_id_familiar = resp['familiares'];
                //form Inscripcion Tab Inscripcion

                $("#tipo_estudiante").val(resp['datos_personales']['tipo_estudiante_id']);
                $("#turno").val(resp['datos_personales']['turno_id']);
                $("#nivel_academico").val(resp['datos_personales']['nivel_academico_id']);

                id_tipo_estudiante = resp['datos_personales']['tipo_estudiante_id'];
                id_turno = resp['datos_personales']['turno_id'];
                id_nivel_academico = resp['datos_personales']['nivel_academico_id'];
                id_curso = resp['datos_personales']['aula_paralelo_id'];

                //Cargamos el select de turnos
                cargar_select_turno(resp['datos_personales']['turno_id']);
                //Cargamos el select del Curso
                cargar_select_curso(resp['datos_personales']['aula_paralelo_id'], resp['datos_personales']['nivel_academico_id'], resp['datos_personales']['turno_id']);



                var imagen = $('#avatar');
                var url;
                if (resp['datos_personales']['foto']) {
                    url = 'files/profiles/estudiantes/' + resp['datos_personales']['foto'] + '.jpg';
                } else {
                    url = 'assets/imgs/avatar.jpg';
                }
                //imagen.src = url;
                $("#avatar").attr("src", url);
            }
        })
    }

    function cargar_select_curso(aula_paralelo_id, nivel_academico_id, turno_id) {
        nivel = nivel_academico_id;
        turno = turno_id;
        //alert(nivel);
        $.ajax({
            url: '?/s-inscripciones/procesos',
            type: 'POST',
            data: {
                'boton': 'listar_cursos_editar',
                'nivel': nivel,
                'turno': turno,
                'aula_paralelo_id': aula_paralelo_id                
            },
            dataType: 'JSON',
            success: function(resp) {
                //alert(resp[0]['id_catalogo_detalle']);
                //console.log(resp);
                $("#select_curso").html("");
                $("#select_curso").append('<option value="' + 0 + '">Seleccionar</option>');
                for (var i = 0; i < resp.length; i++) {
                    if (resp[i]["id_aula_paralelo"] == aula_paralelo_id) {
                        $("#select_curso").append('<option value="' + resp[i]["id_aula_paralelo"] + '" selected="selected">' + resp[i]["nombre_aula"] + ' ' + resp[i]["nombre_paralelo"] + '</option>');
                    } else {
                        $("#select_curso").append('<option value="' + resp[i]["id_aula_paralelo"] + '">' + resp[i]["nombre_aula"] + ' ' + resp[i]["nombre_paralelo"] + '</option>');
                    }
                }
                //console.log(resp[0]);
            }
        });
    }

    function cargar_select_turno(turno_id) {
        //alert(turno_id);
        $.ajax({
            url: '?/s-inscripciones/procesos',
            type: 'POST',
            data: {
                'boton': 'listar_turnos'
            },
            dataType: 'JSON',
            success: function(resp) {
                $("#turno").html("");
                $("#turno").append('<option value="' + 0 + '">Seleccionar</option>');
                for (var i = 0; i < resp.length; i++) {
                    if (resp[i]["id_turno"] == turno_id) {
                        $("#turno").append('<option value="' + resp[i]["id_turno"] + '" selected="selected">' + resp[i]["nombre_turno"] + '</option>');
                    } else {
                        $("#turno").append('<option value="' + resp[i]["id_turno"] + '">' + resp[i]["nombre_turno"] + '</option>');
                    }
                }
                //console.log(resp[0]);
            }
        });
    }

    function listar_niveles() {
        id_turno = $("#turno option:selected").val()
        //alert(nivel);
        $.ajax({
            url: '?/s-inscripciones/procesos',
            type: 'POST',
            data: {
                'boton': 'listar_niveles',
                'id_turno': id_turno
            },
            dataType: 'JSON',
            success: function(resp) {
                if (resp > 0) {
                    $("#vacantes").val(resp);
                    $("#btn_inscripcion").show();
                    nro_varones_mujeres(id_aula_paralelo);
                } else {
                    $("#btn_inscripcion").hide();
                    alertify.error('No hay vacantes en este curso y paralelo');
                }
            }
        });
    }

    function listar_vacantes() {
        id_aula_paralelo = $("#select_curso option:selected").val()
        //alert(nivel);
        $.ajax({
            url: '?/s-inscripciones/procesos',
            type: 'POST',
            data: {
                'boton': 'listar_vacantes',
                'id_aula_paralelo': id_aula_paralelo
            },
            dataType: 'JSON',
            success: function(resp) {
                if (resp > 0) {
                    $("#vacantes").val(resp);
                    $("#btn_inscripcion").show();
                    nro_varones_mujeres(id_aula_paralelo);
                } else {
                    $("#btn_inscripcion").hide();
                    alertify.error('Sin vacantes el paralelo');
                }
            }
        });
    }

    function listar_curso_nivel() {
        nivel = $("#nivel_academico option:selected").val();
        turno = $("#turno option:selected").val();
        //alert(nivel);
        $.ajax({
            url: '?/s-inscripciones/procesos',
            type: 'POST',
            data: {
                'boton': 'listar_cursos',
                'nivel': nivel,
                'turno': turno
            },
            dataType: 'JSON',
            success: function(resp) {
                //alert(resp[0]['id_catalogo_detalle']);
                //console.log(resp);
                $("#select_curso").html("");
                $("#select_curso").append('<option value="' + 0 + '">Seleccionar</option>');
                for (var i = 0; i < resp.length; i++) {
                    $("#select_curso").append('<option value="' + resp[i]["id_aula_paralelo"] + '">' + resp[i]["nombre_aula"] + ' ' + resp[i]["nombre_paralelo"] + '</option>');
                }
                //console.log(resp[0]);
            }
        });
    }

    function nro_varones_mujeres(id_aula_paralelo) {
        $.ajax({
            url: '?/s-inscripciones/procesos',
            type: 'POST',
            data: {
                'boton': 'nro_varones_mujeres',
                'id_aula_paralelo': id_aula_paralelo
            },
            dataType: 'JSON',
            success: function(resp) {
                console.log(resp);
                $("#nro_ninos").val("#Varones : " + resp[0]['nro_varones']);
                $("#nro_ninas").val("#Mujeres : " + resp[0]['nro_mujeres']);
                $("#inscritos").val("#Inscritos : " + resp[0]['inscritos']);
                $("#cupo_total").val("#cupo_total : " + resp[0]['cupo_total']);
                //alertify.error('No hay vacantes en este curso y paralelo');

            }
        });
    }

    var qrcode = new QRCode('qr', {
        text: "<?= $estudiante['codigo_estudiante']; ?>",
        //imagePath: "assets/imgs/avatar.jpg",
        width: 150,
        height: 150,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    })
</script>