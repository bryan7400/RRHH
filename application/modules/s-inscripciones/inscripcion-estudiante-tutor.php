<?php

// Obtiene los parametros 
$id_estudiante = (isset($_params[0])) ? $_params[0] : 0;

$id_est=json_encode($id_estudiante);
// var_dump($id_est);exit();
// Obtiene la cadena csrf 
$csrf = set_csrf();

// Obtiene los estudiantes
$estudiante = $db->select('z.*')->from('vista_estudiantes z')->where('id_estudiante',$id_estudiante)->fetch_first();
// Obtiene nicel académico
$nivel = $db->select('z.*')->from('ins_nivel_academico z')->order_by('id_nivel_academico')->fetch();

// Obtiene los estudiantes
$estud = $db->select('z.*,s.*')->from('ins_estudiante z')->join('sys_persona s','z.persona_id=s.id_persona')->order_by('z.id_estudiante', 'asc')->fetch_first();
//var_dump($estudiante);exit();

// Obtiene los permisos  
$permiso_listar = in_array('listar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);
$permiso_crear_familiar = in_array('crear-familiar', $_views);

?>
<?php require_once show_template('header-design'); ?>
<!--link rel="stylesheet" href="assets/themes/concept/assets/vendor/jquery/jquery-ui.css"-->

<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Inscripción</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Secretaria</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Inscripción</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Inscribir Estudiante</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- end pageheader -->
<!-- ============================================================== -->
<div class="row ">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
        <div class="alert alert-primary" role="alert">
            <b>PASO 2.</b> SELECCIONAR EL CURSO Y PARALELO
        </div>
    </div>
</div>

<div class="row">
<!-- ============================================================== -->
<!-- profile -->
<!-- ============================================================== -->
<div class="col-xl-3 col-lg-3 col-md-5 col-sm-12 col-12">
    <!-- ============================================================== -->
    <!-- card profile -->
    <!-- ============================================================== -->
    <div class="card">
        <div class="card-body">
            <div class="user-avatar text-center d-block">
                <?php if($estudiante['foto']==''): ?>
                <img src="assets/imgs/avatar.jpg" alt="User Avatar" class="rounded-circle user-avatar-xxl">
                <?php else : ?>
                <img src="assets/imgs/fotos/estudiantes/<?= $estudiante['foto'] ?>" alt="User Avatar" class="rounded-circle user-avatar-xxl">
                <?php endif ?>
            </div>
            <div class="text-center">
                <h2 class="font-24 mb-0"><?= escape($estudiante['primer_apellido'] . " " .$estudiante['segundo_apellido'] . " " .$estudiante['nombres']); ?></h2>
                <p>Estudiante <input type ="hidden" id="id_estu" value="<?=$id_estudiante;?>"></p>
            </div>
        </div>
        <div class="card-body border-top" style="background-color: #b39ddb; color:#fff">
            <h3 class="font-16">Información</h3>
            <div class="">
                <ul class="list-unstyled mb-0">
                <li class="mb-2"><b>Tipo de Estudiante: </b><select style="display:none" class="form-control" name="tipo_estudiante" id="tipo_estudiante"></select><span id="span_tipo_estudiante"></span> </li>
                <li class="mb-2"><b>Código: </b><?= escape($estudiante['codigo_estudiante']); ?></li>
                <li class="mb-0"><b>Documento: </b><?= escape($estud['numero_documento']); ?></li>
                <li class="mb-0"><b>Fecha de Nacimiento: </b><?= escape($estud['fecha_nacimiento']); ?></li>
            </ul>
            </div>
        </div>
        <div class="card-body border-top" style="background-color: #e6ceff; color:#fff">
            <h3 class="font-16">Historial</h3>
            <h3 class="mb-0">80</h3><p class="d-inline-block text-dark">Nota de aprobación </p>
            <div class="">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><b>Nivel Anterior: </b> Secundaria</li>
                    <li class="mb-2"><b>Curso Anterior: </b> 4to</li>
                    <li class="mb-2"><b>Paralelo Anterior: </b> B</li>
                </ul>
            </div>
        </div>
        <!-- <div class="card-body border-top">
            <h3 class="font-16">Social Channels</h3>
            <div class="">
                <ul class="mb-0 list-unstyled">
                <li class="mb-1"><a href="#"><i class="fab fa-fw fa-facebook-square mr-1 facebook-color"></i>fb.me/michaelchristy</a></li>
                <li class="mb-1"><a href="#"><i class="fab fa-fw fa-twitter-square mr-1 twitter-color"></i>twitter.com/michaelchristy</a></li>
                <li class="mb-1"><a href="#"><i class="fab fa-fw fa-instagram mr-1 instagram-color"></i>instagram.com/michaelchristy</a></li>
                <li class="mb-1"><a href="#"><i class="fas fa-fw fa-rss-square mr-1 rss-color"></i>michaelchristy.com/blog</a></li>
                <li class="mb-1"><a href="#"><i class="fab fa-fw fa-pinterest-square mr-1 pinterest-color"></i>pinterest.com/michaelchristy</a></li>
                <li class="mb-1"><a href="#"><i class="fab fa-fw fa-youtube mr-1 youtube-color"></i>youtube/michaelchristy</a></li>
            </ul>
            </div>
        </div> -->
<!--         <div class="card-body border-top">
            <h3 class="font-16">Category</h3>
            <div>
                <a href="#" class="badge badge-light mr-1">Fitness</a><a href="#" class="badge badge-light mr-1">Life Style</a><a href="#" class="badge badge-light mr-1">Gym</a>
            </div>
        </div> -->
    </div>
    <!-- ============================================================== -->
    <!-- end card profile -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- end profile -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- campaign data -->
<!-- ============================================================== -->
<div class="col-xl-9 col-lg-9 col-md-7 col-sm-12 col-12">
    <!-- ============================================================== -->
    <!-- campaign tab one -->
    <!-- ============================================================== -->
    <div class="influence-profile-content pills-regular">
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-campaign" role="tabpanel" aria-labelledby="pills-campaign-tab">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="section-block">
                            <h3 class="section-title">Curso a Inscribir</h3>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                        <div class="card  alert-primary-">
                            <div class="card-body">
                                <h2 class="mb-1">Nivel</h2>
								<select name="nivel_academico" id="nivel_academico" class="form-control" onchange="listar_cursos();">
									<option value="" selected="selected">Seleccionar</option>
                                    <?php foreach ($nivel as $value) : ?>
                                        <option value="<?= $value['id_nivel_academico']; ?>"><?= escape($value['nombre_nivel']); ?></option>
                                    <?php endforeach ?>
								</select>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                        <div class="card  alert-primary-">
                            <div class="card-body">
                                <h2 class="mb-1">Curso</h2>
								<select name="select_curso" id="select_curso" onchange="listar_paralelos();" class="form-control">
									<option value="" selected="selected">Seleccionar</option>
								</select>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                        <div class="card  alert-primary-">
                            <div class="card-body">
                                <h2 class="mb-1">Paralelo</h2>
								<select name="select_paralelo" id="select_paralelo" onchange="listar_vacantes();" class="form-control">
									<option value="" selected="selected">Seleccionar</option>
								</select>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                        <div class="card  alert-primary-">
                            <div class="card-body">
                                <h2 class="mb-1">Vacantes</h2>
								<!-- <span class="text-center" id="vacantes"></span> -->
                                <select name="select_paralelo" id="vacantes" class="form-control">
								</select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section-block">
                    <h3 class="section-title">Lista de Familiares</h3>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6 pull-left">
                                <input type="text" class="form-control" placeholder="Buscar por Nombre o Apellidos" id="bus_familiar">
                            </div>
                            
                            <div class=" col-md-6 col-sm-6 col-xs-6" align="right">
                                <button type="button" class="btn btn-sm btn-primary" onclick="abrir_form_familiar();">Agregar Familiar</button>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="table-responsive">
                                <table id="table" class="table table-bordered table-condensed table-striped table-hover">
                                    <thead>
                                        <tr class="active">
                                            <th class="text-nowrap text-center">#</th>
                                            <th class="text-nowrap text-center">Primer Apellido</th>
                                            <th class="text-nowrap text-center">Segundo Apellido</th>
                                            <th class="text-nowrap text-center">Nombres</th>
                                            <th class="text-nowrap text-center">C.I.</th>
                                            <th class="text-nowrap text-center">Profesión</th>
                                            <th class="text-nowrap text-center">Dirección</th>
                                            <th class="text-nowrap text-center">Teléfono</th>
                                            <th class="text-nowrap text-center">Tutor</th>
                                            <th class="text-nowrap text-center">Opciones</th>
                                        </tr>
                                    </thead>
                                    <!-- <tfoot>
                                        <tr class="active">
                                            <th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
                                            <th class="text-nowrap text-middle">Nombre completo</th>
                                            <th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
                                        </tr>
                                    </tfoot> -->
                                    <tbody  id="contenedor_familiar"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="border-top card-footer p-0"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end campaign tab one -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- end campaign data -->
<!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- end row -->
<!-- ============================================================== --> 
<div class="row">
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
        <div class="alert alert-primary" role="alert">
            <b>PASO 3.</b> PARA FINALIZAR CLICK EN ...
        </div>
    </div>
    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-12 text-right">
          <button class="btn btn-primary pull-right"  id="btn_inscripcion" onclick="inscribir()">Terminar Inscripción</button>
    </div>
</div>
<!--modal para eliminar-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_eliminar_familiar">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
	  	<input type="hidden" id="familiar_eliminar">
        <p>¿Esta seguro de eliminar familiar <span id="texto_familiar"></span>?</p>
        <input type="hidden" class="form-control" id="id_est_fam">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn_eliminar_familiar">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<!--fin modal para eliminar-->
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<!-- <script src="<?= js; ?>/jquery.dataFilters.min.js"></script> -->
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/educheck.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/jquery/jquery-ui-1.10.4.min.js"></script>


<!--script src="<?= $ruta?>/s-gestion-escolar.js"></script-->
<?php require_once show_template('footer-design'); ?>
<?php 
	if($permiso_crear_familiar){
		require_once ("crear-familiar.php");
	}	
?>
<script>
let id_estudiante=<?= $id_est;?>;
function listar_tipo_estudiante(){
    id = $("#id_estu").val();
    //id = id_estudiante
    $.ajax({
        url: '?/s-inscripciones/procesos',
        type: 'POST',
        data: {'boton':'listar_tipo_estudiante', 'id': id},
        dataType: 'JSON',
        success: function(resp){
            //alert(resp[0]['id_catalogo_detalle']);
            //console.log(resp);
            $("#tipo_estudiante").html("");
            // $("#tipo_estudiante").append('<option value="'+ 0 +'">Seleccione</option>');
            for(var i=0;i<resp.length;i++){
                $("#tipo_estudiante").append('<option value="'+resp[i]["id_tipo_estudiante"]+'">'+ resp[i]["nombre_tipo_estudiante"]+'</option>');
                $("#span_tipo_estudiante").text(resp[i]["nombre_tipo_estudiante"]);
            }
            //console.log(resp[0]);
        }
    });
}

function listar_cursos(){
    nivel = $("#nivel_academico option:selected").val()
    //alert(nivel);
    $.ajax({
        url: '?/s-inscripciones/procesos',
        type: 'POST',
        data: {'boton':'listar_cursos', 'nivel': nivel},
        dataType: 'JSON',
        success: function(resp){
            //alert(resp[0]['id_catalogo_detalle']);
            //console.log(resp);
            $("#select_curso").html("");
            $("#select_curso").append('<option value="'+ 0 +'">Seleccione</option>');
            for(var i=0;i<resp.length;i++){
                $("#select_curso").append('<option value="'+resp[i]["id_aula"]+'">'+ resp[i]["nombre_aula"]+'</option>');
            }
            //console.log(resp[0]);
        }
    });
}

function listar_paralelos(){
    id_curso = $("#select_curso option:selected").val();
    //alert(nivel);
    $.ajax({
        url: '?/s-inscripciones/procesos',
        type: 'POST',
        data: {'boton':'listar_paralelos', 'id_curso': id_curso},
        dataType: 'JSON',
        success: function(resp){
            //alert(resp[0]['id_catalogo_detalle']);
            console.log(resp);
            $("#select_paralelo").html("");
            $("#select_paralelo").append('<option value="'+ 0 +'">Seleccione</option>');
            for(var i=0;i<resp.length;i++){
                $("#select_paralelo").append('<option value="'+resp[i]["id_aula_paralelo"]+'">'+ resp[i]["nombre_paralelo"]+'</option>');
            }
            //console.log(resp[0]);
        }
    });
}

function listar_vacantes(){
    id_aula_paralelo = $("#select_paralelo option:selected").val()
    //alert(nivel);
    $.ajax({
        url: '?/s-inscripciones/procesos',
        type: 'POST',
        data: {'boton':'listar_vacantes', 'id_aula_paralelo': id_aula_paralelo},
        dataType: 'JSON',
        success: function(resp){
            if(resp > 0){
                //$("#vacantes").text(resp);
                $("#vacantes").html('<option value="'+resp+'">'+ resp+'</option>');
                $("#btn_inscripcion").show();
            }else{
                $("#btn_inscripcion").hide();
                alertify.error('No hay vacantes en este curso y paralelo');
            }
        }
    });
}

listar_familiares('<?= $id_estudiante?>');
function listar_familiares(id_estudiante){
    id = id_estudiante
    $.ajax({
        url: '?/s-inscripciones/procesos',
        type: 'POST',
        data: {'boton':'listar_familiares', 'id_estudiante': id_estudiante},
        dataType: 'JSON',
        success: function(resp){
            html ="";
            // for(var i = 0; i < resp.length; i++){
            //     contenido = resp[i]['id_estudiante_familiar'] +"*"+ resp[i]['id_familiar'] +"*"+  resp[i]['nombre_familiar']+"*"+  resp[i]['numero_documento']+"*"+  resp[i]['profesion']+"*"+  resp[i]['direccion_oficina']+"*"+  resp[i]['telefono_oficina'] +"*"+  resp[i]['id_estudiante'];
            //     html += '<tr><td class="text-center">'+ (i+1) +'</td><td class="text-center">'+ resp[i]['nombre_familiar'] +'</td><td class="text-center">'+ resp[i]['numero_documento'] +'</td><td class="text-center">'+ resp[i]['profesion'] +'</td><td class="text-center">'+ resp[i]['direccion_oficina'] +'</td><td class="text-center">'+ resp[i]['telefono_oficina'] +'</td><td class="text-center">'+ check +'</td><td class="text-center"><button class="btn btn-xs btn-warning" style="color:white" onclick="abrir_modificar_familiar('+"'"+contenido+"'"+');"><span class="icon-note"></span></button> &nbsp <button class="btn btn-danger btn-xs" onclick="abrir_eliminar('+"'"+contenido+"'"+')"><span class="icon-trash"></span></button></td></tr>';

            //     if(resp[i]['tutor'] == '1'){
            //         check = '<input type="checkbox" checked data-toggle="toggle" id="tutor'+ i +'" name="exp[]" value="'+ contenido +'" onclick="seleccionar_tutor('+ i +','+id_estudiante+');">';
            //     }else{
            //         check = '<input type="checkbox" data-toggle="toggle" id="tutor'+ i +'" name="exp[]" value="'+ contenido +'" onclick="seleccionar_tutor('+ i +','+id_estudiante+');">';
            //     } 
            // }
            for(var i = 0; i < resp.length; i++){
                            contenido = resp[i]['id_estudiante_familiar'] +"*"+ resp[i]['id_familiar'] +"*"+  resp[i]['primer_apellido']+"*"+  resp[i]['segundo_apellido']+"*"+  resp[i]['nombres']+"*"+  resp[i]['numero_documento']+"*"+  resp[i]['profesion']+"*"+  resp[i]['direccion_oficina']+"*"+  resp[i]['telefono_oficina'] +"*"+  resp[i]['id_estudiante'];
                            if(resp[i]['tutor'] == '1'){
                                check = '<input type="checkbox" checked data-toggle="toggle" id="tutor'+ i +'" name="exp[]" value="'+ contenido +'" onclick="seleccionar_tutor('+ i +','+id_estudiante+');">';
                            }else{
                                check = '<input type="checkbox" data-toggle="toggle" id="tutor'+ i +'" name="exp[]" value="'+ contenido +'" onclick="seleccionar_tutor('+ i +','+id_estudiante+');">';
                            } 
                            html += '<tr><td>'+ (i+1) +'</td><td>'+ resp[i]['primer_apellido'] +'</td><td>'+ resp[i]['segundo_apellido'] +'</td><td>'+ resp[i]['nombres'] +'</td><td>'+ resp[i]['numero_documento'] +'</td><td>'+ resp[i]['profesion'] +'</td><td>'+ resp[i]['direccion_oficina'] +'</td><td>'+ resp[i]['telefono_oficina'] +'</td><td>'+ check +'</td><td><button class="btn btn-success btn-xs" onclick="abrir_modificar_familiar('+"'"+contenido+"'"+');"><i class="fa fa-edit"></i></button> &nbsp <button class="btn btn-danger btn-xs" onclick="abrir_eliminar_familiar('+"'"+contenido+"'"+')"><i class="fa fa-trash"></i></button></tr>';
            }
            $("#contenedor_familiar").html(html);
        }
    });
}

$("#bus_familiar").autocomplete({
    //source: "?/s-inscripciones/procesos",
    source: function( request , response ){
 
        var url = "?/s-inscripciones/procesos";   //url donde buscará los estados
        $.ajax({
            url: url,
            type: 'POST',
            data: {'busqueda' : request.term,
                    'boton': 'buscar_familiar'},
            dataType: 'JSON',
            success: function(data){
                response(data);
            }
        })

        //busqueda es la varible que mandaremos por post con el contenido del input
    },
    minLength: 2,
    /*select: function(event, ui) {
        //event.preventDefault();
        id_estudiante = '<?= $id_estudiante?>';
        id_familiar = ui.item.id_familiar;
        console.log(id_familiar);
        $('#bus_familiar').val("");
        //prueba(id_estudiante, id_familiar);
        $.ajax({
            url: '?/s-inscripciones/procesos',
            type: 'POST',
            data: {'id_estudiante': id_estudiante, 'id_familiar': id_familiar, 'boton':'agregar_familiar2'},
            success: function(resp){
                //console.log(resp);
                if(resp == 1){
                    listar_familiares(id_estudiante);
                    alertify.success('Se agrego al familiar correctamente');
                }else{
                    alertify.error('No se agregar al familiar');
                }
            }
        })
    }*/
})


//$(function () {
    // $("#bus_familiar").autocomplete({
    //     source: "s-inscripciones/procesos.php?boton=buscar_familiar",
    //     minLength: 2,
    //     select: function(event, ui) {
    //         //event.preventDefault();
    //         id_estudiante = '<?= $id_estudiante?>';
    //         id_familiar = ui.item.id_familiar;
    //         $('#bus_familiar').val("");
    //         //prueba(id_estudiante, id_familiar);
    //         $.ajax({
    //             url: '?/s-inscripciones/procesos',
    //             type: 'POST',
    //             data: {'id_estudiante': id_estudiante, 'id_familiar': id_familiar, 'boton':'agregar_familiar2'},
    //             success: function(resp){
    //                 //console.log(resp);
    //                 if(resp == 1){
    //                     listar_familiares(id_estudiante);
    //                     alertify.success('Se agrego al familiar correctamente');
    //                 }else{
    //                     alertify.error('No se agregar al familiar');
    //                 }
    //             }
    //         })
    //     }
    // })
//});
function abrir_form_familiar(){
    /*$("#modal_familiar").modal("show");
    $("#titulo_modal_familiar").text("Registrar Familiar");
    $("#form_familiar")[0].reset();
    $("#btn_editar").hide();
    $("#btn_nuevo").show();*/
    document.location.href="?/tutores/crear";
}
function abrir_modificar_familiar(contenido){
    /*var d = contenido.split("*");
    $("#modal_familiar").modal("show");
    $("#titulo_modal_familiar").text("Modificar datos del Familiar");
    $("#form_familiar")[0].reset();
    $("#btn_editar").show();
    $("#btn_nuevo").hide();
    id_familiar = d[1];
    $.ajax({
        url: '?/s-inscripciones/procesos',
        type: 'POST',
        data: {'id_familiar': id_familiar, 'boton':'buscar_datos_personales'},
        dataType: 'JSON',
        success: function(resp){
            //console.log(resp);
            $("#id_familiar").val(resp['id_familiar']);
            $("#id_persona").val(resp['id_persona']);
            $("#nombres").val(resp['nombres']);
            $("#primer_apellido").val(resp['primer_apellido']);
            $("#segundo_apellido").val(resp['segundo_apellido']);
            $("#tipo_documento").val(resp['tipo_documento']);
            $("#numero_documento").val(resp['numero_documento']);
            $("#complemento").val(resp['complemento']);
            $("#genero").val(resp['genero']);
            $("#fecha_nacimiento").val(moment(resp['fecha_nacimiento']).format('YYYY-MM-DD'));
            $("#telefono").val(resp['telefono']);
            $("#profesion").val(resp['profesion']);
            $("#direccion_oficina").val(resp['direccion_oficina']);
        }
    });*/
    document.location.href="?/s-tutores/crear";
}
<?php if ($permiso_eliminar) : ?>
function abrir_eliminar(contenido){
	$("#modal_eliminar").modal("show");
	var d = contenido.split("*");
	$("#familiar_eliminar").val(d[0]);
	$("#texto_familiar").text(d[2]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
// $("#btn_eliminar").on('click', function(){
// 	//alert($("#gestion_eliminar").val())
// 	id_gestion = $("#gestion_eliminar").val();
// 	$.ajax({
// 		url: '?/s-gestion-escolar/eliminar',
// 		type:'POST',
// 		data: {'id_gestion':id_gestion},
// 		success: function(resp){
// 			//alert(resp)
// 			switch(resp){
// 				case '1': $("#modal_eliminar").modal("hide");
// 							dataTable.ajax.reload();
// 							alertify.success('Se elimino la gestión academica correctamente');break;
// 				case '2': $("#modal_eliminar").modal("hide");
// 							alertify.error('No se pudo eliminar ');
// 							break;
// 			}
// 		}
// 	})
// })
<?php endif ?>

            
function seleccionar_tutor(i, id_estudiante){
    nombre = "#tutor"+i;
    var contenido = $(nombre).val();

    //console.log(contenido);
    var d = contenido.split("*");
    if( $(nombre).prop('checked') ) {
        //alert('Seleccionado');
        $.ajax({
            url: '?/s-inscripciones/procesos',
            type: 'POST',
            data: {'id_estudiante_familiar':d[0],'id_tutor':d[1], 'id_estudiante':d[7], 'boton':'seleccionar_tutor'},
            success: function(resp){
                //alert(resp);
                console.log(resp);
                if(resp== 1){
                    //$("#contenedor_familiar").html("");
                    listar_familiares(id_estudiante);
                    $("#id_estudiante_familiar").val(d[0]);
                    /*$("#nombre_tut").text(d[2]);
                    $("#numero_tut").text(d[3]);
                    $("#profesion_tut").text(d[4]);
                    $("#direccion_tut").text(d[5]);
                    $("#telefono_tut").text(d[6]);*/
                }
            }
        });
    }else{
        $.ajax({
            url: '?/s-inscripciones/procesos',
            type: 'POST',
            data: {'id_estudiante_familiar':d[0], 'boton':'borrar_tutor'},
            success: function(resp){
                //alert(resp);
                //console.log(resp);
                if(resp== 1){
                    //$("#contenedor_familiar").html("");
                    listar_familiares(id_estudiante);
                    $("#id_estudiante_familiar").val("");
                    /*$("#nombre_tut").text("");
                    $("#numero_tut").text("");
                    $("#profesion_tut").text("");
                    $("#direccion_tut").text("");
                    $("#telefono_tut").text("");*/
                }
            }
        });
    }
}

function inscribir(){
    var id_estudiante_familiar = $("#id_estudiante_familiar").val();
    var id_aula_paralelo = $("#select_paralelo option:selected").val();
    var id_nivel_academico = $("#nivel_academico option:selected").val();
    var id_tipo_estudiante = $("#tipo_estudiante option:selected").val();
    var id_estudiante = "<?= $id_estudiante?>";
    if(id_estudiante_familiar =="" || id_aula_paralelo == 0){
        //alert("Debe seleccionar un familiar y seleccionar un paralelo");
        alertify.error('Debe seleccionar un familiar y seleccionar un paralelo');
    }else{
        $.ajax({
            url: '?/s-inscripciones/procesos',
            type: 'POST',
            data: {'boton':'inscribir',
                    'id_aula_paralelo':id_aula_paralelo,
                    'id_estudiante': id_estudiante,
                    'id_estudiante_familiar': id_estudiante_familiar,
                    'id_tipo_estudiante': id_tipo_estudiante,
                    'id_nivel_academico': id_nivel_academico},
            success: function(resp){
                //console.log(resp);
                switch (resp){
                    case '1': alertify.success('Se inscribio al estudiante correctamente');
                                $("#btn_inscripcion").hide(); break;
                    case '2': alertify.error('No se pudo registrar sus datos al estudiante'); break;
                    case '3': alertify.error('No se pudo inscribir al estudiante'); break;
                }
            }
        });
    }
}

function agregar_familiar(){
    var parametros = {
        'nombres': $("#nombres").val(),
        'primer_apellido': $("#primer_apellido").val(),
        'segundo_apellido': $("#segundo_apellido").val(),
        'tipo_documento': $("#tipo_documento option:selected").val(),
        'numero_documento': $("#numero_documento").val(),
        'complemento': $("#complemento").val(),
        'genero': $("#genero option:selected").val(),
        'fecha_nacimiento': $("#fecha_nacimiento").val(),
        'telefono': $("#telefono").val(),
        'profesion': $("#profesion").val(),
        'direccion_oficina': $("#direccion_oficina").val(),
        'id_estudiante': '<?= $id_estudiante?>',
        'boton': 'agregar_familiar'
    }
    $.ajax({
        url: '?/s-inscripciones/procesos',
        type: 'POST',
        data: parametros,
        success: function(resp){
            //alert(resp);
            console.log(resp);
            switch(resp){
                case '1': alertify.success('Se registro el familiar correctamente');
                            $('.modal_familiar').modal('hide');
                            listar_familiares(<?= $id_estudiante?>); break; 
                case '2': alertify.error('No se pudo registrar al familiar del estudiante'); break;
                case '3': alertify.error('No se pudo registrar al familiar'); break; 
                case '4': alertify.error('No se pudo registrar los datos personales del familiar'); break;
            }
        }
    });
}

function abrir_eliminar_familiar(conenido){
    console.log(contenido);
    var d = contenido.split("*");
    $("#modal_eliminar_familiar").modal("show");
    $("#id_est_fam").val(d[0]);
}

$("#btn_eliminar_familiar").on('click', function(){
    var id_estudiante = '<?= $id_estudiante?>';
    var id_estudiante_familiar = $("#id_est_fam").val();
    $.ajax({
        url: '?/s-inscripciones/procesos',
        type: 'POST',
        data: {'id_estudiante_familiar': id_estudiante_familiar, 'boton':'eliminar_familiar'},
        success: function(resp){
            //console.log(resp);
            if(resp == 1){
                listar_familiares(id_estudiante);
                $("#modal_eliminar_familiar").modal("hide");
                alertify.success('Se elimino el familiar correctamente');
            }else{
                alertify.error('No se pudo eliminar al familiar');
            }
        }
    })
});
window.onload = listar_tipo_estudiante();
</script>