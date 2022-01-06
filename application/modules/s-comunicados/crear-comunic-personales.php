<?php
$csrf = set_csrf();
// Obtiene los permisos
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);
//obtiene los roles
$roles = $db->query("SELECT * FROM sys_roles WHERE rol != 'Superusuario'")->fetch();
?>
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/bootstrap-select/css/bootstrap-select.css">
<!--<link href="assets/themes/concept/assets/vendor/bootstrap-fileinput-master/css/fileinput.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/bootstrap-colorpicker/%40claviska/jquery-minicolors/jquery.minicolors.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/datepicker/css/datepicker.css" rel="stylesheet">-->
<!--modal grande styles-->
<style>
@media (min-width: 992px){
.modal-grande{
width: 90% !important;
}
}
@media (min-width: 768px){
/*.modal-dialog*/
.modal-grande{
width: 90% !important;
margin: 30px auto;
}
}
@media (min-width: 576px){
.modal-grande {
max-width: 90% !important;
margin: 1.75rem auto;
} }
</style>
<!-- Modal -->
<div class="modal  fade" id="modal_agregar_personas_ev" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-grande" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="titulo_modal_p"></h5>
        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </a>
      </div>
      <div class="modal-body">
            <form class="form_comunicado" id="form_agregar_comunicado">
              <div class="row">
                <div class="col-xl-4 col-lg-5 col-md-12   col-sm-12 col-xs-12">
                  <div class="card" style="    min-height: 45em;">
                    <div class="card-body">
                      <!-- Modal -->
                      <div class="modal-content">
                        <div class="card-head">
                        </div>
                        <div class="modal-body"> <!--comieza el body de la modal-->
                        <h3 class="p-3">Comunicado</h3>
                        <div class="form-group" style="margin-bottom:15px;">
                          <label class="control-label">Titulo:</label>
                          <div class="controls control-group">
                            <input type="hidden" name="id_comunicado" class="form-control" id="id_comunicado_p">
                            <input type="text" name="nombre_evento" class="form-control" id="nombre_evento_p">
                          </div>
                        </div>
                        <div class="form-group" style="margin-bottom:15px;">
                          <label for="title" class="control-label">Descripción:</label>
                          <div class="controls control-group">
                            <input type="text" name="descripcion_evento" class="form-control" id="descripcion_evento_p">
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-3 form-group">
                            <label for="hiddeninput">Color:</label>
                            <br>
                            <input type="hidden" id="color_evento_p" name="color_evento" class="form-control" value="#3462c0">
                          </div>
                          <div class="col-5  form-group">
                            <label for="hiddeninput">Archivo(opcional):</label>
                            <br>
                            <div class="custom-file mb-3">
                              <input type="file" class="custom-file-input" id="customFile" name="file_evento_p">
                              <label class="custom-file-label" for="customFile"><i class="icon-doc"></i> Adjuntar archivo</label>
                            </div>
                          </div>
                          <div class="col-4  form-group descargarfile">
                            <a class="btn btn-" href="" dowload=""> Descargar<i class="icon-arrow-down-circle"></i></a>
                          </div>
                          <div class=" col-4 form-group" style="margin-bottom:15px;">
                            <label for="title" class="control-label">Fecha Inicio:</label>
                            <div class="controls control-group">
                              <input type='text' class='datepicker-here form-control' id="fecha_inicio_p" name="fecha_inicio" readOnly/>
                            </div>
                          </div>
                          <div class=" col-4 form-group" style="margin-bottom:15px;">
                            <label for="title" class="control-label">Fecha Fin:</label>
                            <div class="controls control-group">
                              <input type='text' class='datepicker-here form-control' id="fecha_final_p" name="fecha_final" readOnly/>
                            </div>
                          </div>
                          <div class=" col-4 form-group" >
                            <label for="title" class="control-label">Prioridad</label>
                            <div class="controls control-group">
                              <select name="prioridad" id="prioridad_p" class='form-control'>
                                <option value="1"  >Bajo (Normal)</option>
                                <option value="2" style="color:orange">Medio</option>
                                <option value="3" style="color:red">Alto</option>
                              </select>
                            </div>
                          </div>
                          <div class="form-group" id="div_eliminar" style="display:none">
                            <label class="custom-control custom-checkbox" style="color:red">
                              <input type="checkbox" class="custom-control-input" name="eliminar" id="eliminar_p"><span class="custom-control-label">Eliminar evento</span>
                            </label>
                          </div>
                          </div><!--termina el body de la modal-->
                        </div>
                      </div>
                      </div><!--fin card body-->
                    </div>
                  </div>
                  <div class="col-xl-8 col-lg-7 col-md-12 col-sm-12 col-12">
                    <div class="card " style="    min-height: 45em;">
                      <div class="card-head">
                        <div class="form-group pt-4 pr-4 pl-4"  >
                          <h3 class="">Lista de envio</h3>
                          <label for="title" class="control-label">Filtrar por roles:</label>
                          <div class="controls control-group">
                            <select class="selectpicker form-control" id="select_roles_p" name="select_roles" multiple title="Seleccione" onchange="listar(this);">
                              <?php
                              foreach ($roles as $key => $rol) {
                              ?>
                              <option value="<?= $rol['id_rol'];?>"><?= $rol['rol']?></option>
                              <?php
                              }
                              ?>
                            </select>
                          </div>
                          <div class="controls control-group">
                            <label for="id_user" class="control-label">Seleccione personas:</label>
                            <select id="id_user_p" name="id_usuario" class="form-control" onchange="listar_a_tabla();">
                              <option value="">Buscar...</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="card-body">
                        <!--<input type="checkbox" data-toggle="toggle" value="1" name="vector[]" checked >
                        <input type="checkbox" class="id_personas" name="id_personas_array[]" value="1" checked>-->
                        <div class="table-responsive">
                          <table id="Tabla_personas" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
                            <thead>
                              <tr class="active">
                                <th class="text-nowrap" style="display:none"></th>
                                <th class="text-nowrap">#</th>
                                <th class="text-nowrap">Nombres</th>
                                <th class="text-nowrap">Documento</th>
                                
                                <th class="text-nowrap">Genero</th>
                                <th class="text-nowrap">Quitar</th>
                                
                              </tr>
                            </thead>          <tbody id="listado_gestion_escolar">
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal" >Cerrar</button>
                      <button type="reset" class="btn btn-default" data- onclick="limpiar()" id="btn_limpìar_p" >Limpiar</button>
                      <button type="submit" class="btn btn-primary" id="btn_agregar_p" >Guardar todo</button>
                      <button type="submit" class="btn btn-primary" id="btn_editar_p" >Editar</button>
                      <!--<button type="submit" class="btn btn-primary" id="btn_editar">Editar</button>-->
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script>
    $('#color_evento_p').minicolors({
    theme: 'bootstrap'
    });
    //formato de fchas
    var disabledDays = [0, 6];

    $('#fecha_inicio_p').datepicker({
      timepicker: true,
      language: 'es',
      position:'top left',
      dateFormat: 'dd-mm-yyyy',
      //startDate: start,
      minHours: 8,
      maxHours: 19,
      timeFormat: "hh:ii:00",//hh:horas ii:minutos mm:seg
      onSelect: function(fd, d, picker){
        var fecha_marcada = moment(d).format('DD-MM-YYYY');
        var hoy = moment(new Date()).format('DD-MM-YYYY');
        if(fecha_marcada >= hoy){
        }else{
        alertify.error('No puede asignar tareas en una fecha pasada a la actual.');
        $("#fecha_inicio").val("");
      }
      }
    })
    $('#fecha_final_p').datepicker({
      timepicker: true,
      language: 'es',
      position:'top left',
      dateFormat: 'dd-mm-yyyy',
      minHours: 8,
      maxHours: 18,
      timeFormat: "hh:ii:00",//hh:horas ii:minutos mm:seg
      onSelect: function(fd, d, picker){
        var fecha_marcada = moment(d).format('DD-MM-YYYY');
        var hoy = moment(new Date()).format('DD-MM-YYYY');
        if(fecha_marcada >= hoy){
        }else{
        alertify.error('No puede asignar tareas en una fecha pasada a la actual.');
        $("#fecha_inicio").val("");
        }
      }
    })


    //array de datos personas
    function listar(thiss){
      var valores1=$(thiss).val();
      listar_usuarios(valores1);//ajax
    }

    listar_usuarios();

    function listar_usuarios(valores) {
        // console.log(valores);
        var boton='';
        $.ajax({
        url: '?/s-comunicados/procesos',
        type: 'POST',
        data: {
        'boton': 'listar_usuarios',
        'valores': valores
        },
        dataType: 'JSON',
        success: function(resp){
        // console.log(resp);//debugger;
        var counter=1;
        try{
        $('#id_user_p').selectize()[0].selectize.destroy();
        }catch{
        }
        $('#id_user_p').html('');
        $('#id_user_p').append('<option value="">Buscar...</option>');
        for (var i = 0; i <resp.length; i++) {
        //console.log(resp[i]["nombres"]);
        $('#id_user_p').append('<option value="'+resp[i]["id_user"]+'">'+resp[i]["nombres"]+' '+resp[i]["primer_apellido"]+' '+resp[i]["segundo_apellido"]+' - '+resp[i]["numero_documento"]+'</option>');
        counter++;
        }//fin for
        //console.log('fin for');
        },error: function(e){
          //console.log(e);
        }
        }).done(function(){
        try{
        $('#id_user_p').selectize();
        }catch{
        }
        //$('#id_user_p').selectize();
        //$('#id_user_p').selectize()[0].selectize.clear();
        });
    }
    
    var n=0;
    function listar_a_tabla(x){      
    var id_user=0;
    if(x>0){
    id_user=x;
    }else{
    id_user=$('#id_user_p').val();
    }
    try{
    $('#id_user_p').data('selectize').setValue('');
    }catch{}
    $.ajax({
    type: 'POST',
    url: "?/s-comunicados/procesos",
    dataType: 'json',
    data: {'id_user': id_user, 'boton': 'agregar_usuario'},
    success: function (data) {
    html = "";
    for(var i=0; i < data.length;i++){
    var contenido = '';//data[i]['id_inscripcion'] + "*" + data[i]['codigo_estudiante'] + "*" +data[i]['primer_apellido'] + "*" +data[i]['segundo_apellido'] + "*" +data[i]['nombres'] + "*" +data[i]['numero_documento'];
    n++;
    var gen='';
    if(data[i]["genero"]=='v'){
    gen=' Varon <span style="color: blue;" class="icon-user"></span>';
    }  else if(data[i]["genero"]=='m'){
    gen='<span style="color: #ff0bec;" class="icon-user-female"></span> Mujer';
    }
    html += '<tr><td style="display:none"><input type="checkbox" class="id_user" name="id_user_array[]" value="'+data[i]["id_user"]+'" checked></td><td class="text-center">'+ n+'</td>'+
    '<td class="text-justify">'+ data[i]['primer_apellido'] +' '+ data[i]['segundo_apellido'] +' '+ data[i]['nombres'] +'</td>'+
    '<td class="text-center">'+ data[i]['numero_documento'] +'</td>'+
    '<td>'+gen+'</td>'+
    '<td class="text-center"><a class="btneliminarestudiante"><i class="fa fa-trash" style="color:#b61600"></i></a></td></tr>';
    }
    $("#Tabla_personas").append(html);
    $('.table-responsive').css('border','0px solid transparent');
    $('.msgtabla').remove();
    }
    });
    }
    $(document).on('click','.btneliminarestudiante', function(){
    //console.log('eliminar');
    $(this).parent().parent().remove();
    // $(this).parent().parent().remove();
    });
    
    function limpiar(){
    $("#Tabla_personas").find('tbody').html('');
    $('#form_agregar_comunicado').trigger("reset");
    /*$("#form_agregar_comunicado")[0].reset();*/
    }
    
    $("#form_agregar_comunicado").validate({
    rules: {
    nombre_evento: {required: true},
    fecha_inicio: {required: true},
    fecha_final: {required: true}//,
    //select_roles: {required: true}
    //id_gestion: {required: true}
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
    nombre_evento: "Debe ingresar el nombre del evento.",
    fecha_inicio: "Debe seleccionar la fecha de inicio.",
    fecha_final: "Debe seleccionar la fecha a terminar.",
    // select_roles: "Debe seleccionar al menos un rol."
    },
    //una ves validado guardamos los datos en la DB
    submitHandler: function(form){
    //e.preventDefault();
    /* var parametros = {
    'id_comunicado': $("#id_comunicado_p").val(),
    'nombre_evento': $("#nombre_evento_p").val(),
    'descripcion': $("#descripcion_evento_p").val(),
    'color': $("#color_evento_p").val(),
    'fecha_inicio': $("#fecha_inicio_p").val(),
    'fecha_final': $("#fecha_final_p").val(),
    // 'roles': $("#select_roles").val()
    }*/
    if($(".id_user").val()){
    //alert('enviar');
    $('.table-responsive').css('border','0px solid transparent');
    $('.msgtabla').remove();
    }else{
    //alert('aqui nnn');
    $('.table-responsive').css('border','1px solid red');        $('.table-responsive').append('<span style="color:red" class="msgtabla">Busca nombres y agregalos al menos un destinatario</span>');
    return false;
    }
    var id_comunicado= $("#id_comunicado_p").val();
    var nombre_evento=$("#nombre_evento_p").val();
    var descripcion= $("#descripcion_evento_p").val();
    var color= $("#color_evento_p").val();
    var fecha_inicio= $("#fecha_inicio_p").val();
    var fecha_final= $("#fecha_final_p").val();
    var select_roles=$("#select_roles").val();
    //alert(nombre_evento);
    var form_data = new FormData($("#form_agregar_comunicado")[0]);
    $.ajax({
    type: 'POST',
    url: "?/s-comunicados/guardar-personal",
    data: form_data,
    cache: false,
    contentType: false,
    processData: false,
    datatype: 'text',
    success: function (resp) {
    //console.log(resp);
    //var d=resp.split("*");
    //resp=d[0];
    //var personas_id=d[1];
    //cont = 0;
      var resp=JSON.parse(resp,true);
      var estresp=resp['estresp'];
      console.log(resp);
    switch(estresp){
    case '1': //dataTable.ajax.reload();
        alertify.success('Se registro el comunicado personal correctamente.');
        listar_comunicados_tabla(); $("#modal_agregar_personas_ev").modal("hide");
         notificarfire(resp.datos.nombre_evento,resp.datos.descripcion,resp.datos.fecha_inicio,resp.datos.usuarios,resp.datos.id_comunicado,resp.datos.persona_id,resp.datos.grupo,0); 
         // function notificarfire(titulo,descripcion,fecha,rol_id,id_comunicado,personas_id,grupo,asignacion_docente_id){
           //notificarfire(nombre_evento,descripcion,fecha_inicio,'select_roles',id_comunicado,personas_id,0);
        limpiar();
        break;
    case '2':
        listar_comunicados_tabla();
        //dataTable.ajax.reload();
        $("#modal_agregar_personas_ev").modal("hide");
        alertify.success('Se editó el comunicado correctamente.'+resp.datos.grupo);
        notificarfire(resp.datos.nombre_evento,resp.datos.descripcion,resp.datos.fecha_inicio,resp.datos.usuarios,resp.datos.id_comunicado,resp.datos.persona_id,resp.datos.grupo,0); 
        //notificarfire(nombre_evento,descripcion,fecha_inicio,'select_roles',id_comunicado,personas_id,0);
        limpiar();
        break;
    case '3':
    alertify.success('Deve seleccionar un nombre de la lista.'); break;
    default:
    alertify.error('Ocurrio un error en el proceso. Contactese con soporte tecnico.');
    break;
    }
    //pruebaa();
    }
    });
    return false;
    }
    })
    </script>
  <!--</body>
</html>-->