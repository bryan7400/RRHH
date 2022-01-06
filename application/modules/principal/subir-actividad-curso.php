<?php
// Obtiene la cadena csrf
$csrf = set_csrf();
$id_gestion = $_gestion['id_gestion'];
$modos = $db->query("SELECT * FROM cal_modo_calificacion WHERE gestion_id = $id_gestion AND estado = 'A'")->fetch();
$areas = $db->query("SELECT * FROM cal_area_calificacion WHERE gestion_id = $id_gestion AND estado = 'A'")->fetch();
$fecha = date('Y-m-d');


?>

<!-- Modal -->
<div class="modal fade" id="modal_agregar_evento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <form class="form-horizontal" id="form_agregar_evento" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="titulo_modal">Actividad</h5>
          <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </a>
        </div>
        <div class="modal-body">

         <!-- <div id="panel_modo">
            <div class="form-group" style="margin-bottom:15px;">
              <label for="title" class="control-label">Modo de calificación:</label>
              <div class="controls control-group">
                <select class="form-control" id="id_modo_calificacion_e" name="id_modo_calificacion_e">
                  <?php// foreach ($modos as $modo) { ?>
                    <option value="<?//= $modo['id_modo_calificacion'] ?>"><?//= $modo['descripcion'] ?></option>
                  <?php// } ?>
                </select>
              </div>
            </div>
          </div>-->
          <input type="hidden"  id="id_modo_calificacion_e" name="id_modo_calificacion_e" placeholder="id_modo_calificacion_e">
          <input type="hidden"  id="tipo_extra_m" name="tipo_extra_m" placeholder="tipo_extra_m">
          <input type="hidden" id="id_area_calificacion" name="id_area_calificacion" placeholder="id_area_calificacion">
          <input type="hidden" name="id_asesor_curso_actividad_a" class="form-control" id="id_asesor_curso_actividad_a">
          <input type="hidden" name="asignacion_docente_id" class="form-control" id="asignacion_docente_id">
          <input type="hidden" name="accion"   value="guardar_tarea_completa"> 
          
         <!-- <input type="hidden" name="id_modo_calificacion" class="form-control" id="id_modo_calificacion">-->

        <!--  <div class="form-group" style="margin-bottom:15px;">
            <label for="title" class="control-label">Area de calificación:</label>
            <div class="controls control-group">
              <select class="form-control" id="id_area_calificacion" name="id_area_calificacion">
                <?php// foreach ($areas as $area) { ?>
                  <option value="<?//= $area['id_area_calificacion'] ?>"><?//= $area['descripcion'] ?></option>
                <?php// } ?>
              </select>
            </div>
          </div>-->

          <div class="form-group" style="margin-bottom:15px;">
            <label for="title" class="control-label">Tipo actividad:</label>
            <div class="controls control-group">
              <!-- <input type="text" name="tipo_actividad" class="form-control" id="tipo_actividad"> -->
              <select class="form-control " id="tipo_actividad" name="tipo_actividad" onchange="tipoactividad();">
                <option value="ARCHIVO">ARCHIVO</option>
                <option value="VIDEO">VIDEO</option>
                <option value="AUDIO">AUDIO</option>
                <option value="EXAMEN">EXAMEN</option>
                <option value="REUNION">REUNION</option>
                <option value="IMAGEN">IMAGEN</option>
              </select>
            </div>
          </div>


          <div class="form-group" style="margin-bottom:15px;">
            <label class="control-label">Nombre de la actividad:</label>
            <div class="controls control-group">
              <input type="text" name="nombre_actividad" class="form-control" id="nombre_actividad" onKeyUp="this.value=this.value.toUpperCase();">
            </div>
          </div>

          <div class="form-group" style="margin-bottom:15px;">
            <label for="title" class="control-label">Descripción actividad:</label>
            <div class="controls control-group">
              <textarea type="text" name="descripcion_actividad" class="form-control" id="descripcion_actividad" rows="4" maxlength="10000"></textarea>
            </div>
          </div>

          <div class="form-group" style="margin-bottom:15px;">
            <label for="title" class="control-label">Documentos:</label>
            <!-- <input type="file" name="file_evento_p" class="form-control" id="file_evento_p"/>       -->
            <input type="file" name="file_evento_p[]" multiple="multiple" class="form-control" id="file_evento_p[]" />
          </div>


          <div class="alert alert-primary" role="alert">
            Formatos Aceptables 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'epub', 'txt', 'avi', 'mpeg', 'mp4', 'm4a', '3gp', 'flv', 'ogg', 'mp3', 'wav', 'wma', 'acc', 'bmp', 'gif', 'jpg', 'jpeg', 'png', 'psd', 'ai', 'svg', 'rar', 'zip', '7z', 'gz', 'tar', 'exe'
          </div>



          <div class="form-group  d-none" id="docs" >
            <input type="text" id="file_evento_i" name="file_evento_i" class="form-control" data-role="tagsinput" />
          </div>


          <!-- mostramos datos que seran utilizados a la hora de crear estudiantes -->
          
        <!--    <label for="title" class="control-label">Utilizar nuestro generador:</label>
            <select class="form-control " id="utilizar_generador" name="utilizar_generador" onchange="utilizargenerador();">
                  <option value="NO">NO</option>
                  <option value="SI">SI</option>
            </select>-->
<div class="container-fluid">
    <div class="row">
          <div id="panel_examen_generador" style="display: none;" class="col-4">
            <div class="form-group" style="margin-bottom:15px;">
              <label for="title" class="control-label">Utilizar nuestro generador:</label>
              <div class="controls control-group">
                
                <select class="form-control " id="utilizar_generador" name="utilizar_generador" onchange="utilizargenerador();">
                  <option value="NO">NO</option>
                  <option value="SI">SI</option>
                </select>
              </div>
            </div>
          </div>

          <div id="panel_urls" style="display: none;" class="col-12">
            <div class="form-group" style="margin-bottom:15px;">
              <?php for ($i = 1; $i <= 10; $i++) { ?>
                <div class="row">
                  <div class="col-sm-4 floatt2">
                    <input class="form-control form-control-lg dependiente_div<?php echo $i; ?>" name="username<?php echo $i; ?>" id="username<?php echo $i; ?>" type="text" placeholder="" autocomplete="off" autofocus="autofocus" value="URL <?php echo $i; ?>:" readonly <?php if ($i != 1) { ?> style="display:none;" <?php } ?> onchange="Habilitar(<?php echo $i + 1; ?>);">
                  </div>
                  <div class="col-sm-8 floatt2">
                    <input class="form-control form-control-lg dependiente_div<?php echo $i; ?>" name="url_doc<?php echo $i; ?>" id="url_doc<?php echo $i; ?>" type="text" placeholder="" autocomplete="off" autofocus="autofocus" <?php if ($i != 1) { ?> style="display:none;" <?php } ?> onchange="Habilitar(<?php echo $i + 1; ?>);">
                  </div>
                  <div style="clear: both;"></div>
                </div>
              <?php } ?>
            </div>
          </div>

          <div id="panel_url_reunion"  style="display: none;" class="col-8">
            <div class="form-group" style="margin-bottom:15px;">
              <label id="etiqueta_reunion_examen" for="title" class="control-label">URL de la reunion:</label>
              <div class="controls control-group">
                <input type="text" name="url_reunion" class="form-control" id="url_reunion">
              </div>
            </div>
          </div>
       <!-- <div class="col-12">-->
          <div id="panel_presentable" class="col-6" >
            <div class="form-group" style="margin-bottom:15px;">
              <label for="title" class="control-label">Presentar actividad:</label>
              <div class="controls control-group">
                <!-- <input type="text" name="tipo_actividad" class="form-control" id="tipo_actividad"> -->
                <select class="form-control " id="presentar_actividad" name="presentar_actividad" onchange="presentaractividad();">
                  <option value="NO">NO</option>
                  <option value="SI">SI</option>
                </select>
              </div>
            </div>
          </div> 
       <!-- </div>-->
       <!-- <div class="col-12">-->
          <!-- panel que solo se muestra cuando se habilite la opcion de presentar actividad -->
          <div id="panel_fecha_hora_presentable" class="col-12 alert alert-primary" role="alert"  style="display: none;">
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="form-group" style="margin-bottom:15px;">
                  <label id="etiqueta_fecha" class="control-label">Fecha de Presentación:</label>
                  <div class="controls control-group">
                    <input type='date' class='form-control' id="fecha_presentacion" name="fecha_presentacion" />
                  </div>
                </div>
              </div>

              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="form-group" style="margin-bottom:15px;">
                  <label id="etiqueta_hora" class="control-label">Hora de presentacion:</label>
                  <div class="controls control-group">
                    <input type="time" value="23:59" name="hora_final" id="hora_final" class="form-control">
                  </div>
                </div>
              </div>
            </div>
          </div>
            
      <!--  </div>
        <div class="col-12">-->

          <div id="panel_programable" class="col-6" >
            <div class="form-group" style="margin-bottom:15px;">
              <label for="title" class="control-label">Actividad programable:</label>
              <div class="controls control-group">

                <select class="form-control " id="actividad_programable" name="actividad_programable" onchange="programaractividad();">
                  <option value="NO">NO</option>
                  <option value="SI">SI</option>
                </select>
              </div>
            </div>
          </div>
            
        <!--</div>-->
      <!--  <div class="col-12">-->
          <!-- panel que solo se muestra cuando se habilite la opcion de actividad programable -->
          <div id="panel_fecha_hora_programable" class="col-12 alert alert-success" role="alert"  style="display: none;">
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="form-group" style="margin-bottom:15px;">
                  <label for="title" class="control-label">Fecha a mostrar la actividad:</label>
                  <div class="controls control-group">
                    <input type='date' class='form-control' id="fecha_programable" name="fecha_programable" />
                  </div>
                </div>
              </div>

              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="form-group" style="margin-bottom:15px;">
                  <label id="etiqueta_hora_examen" class="control-label">Hora a mostrar la actividad:</label>
                  <div class="controls control-group">
                    <input type="time" value="00:01" name="hora_programable" id="hora_programable" class="form-control">
                  </div>
                </div>
              </div>
            </div>
          </div>
            
       <!-- </div>-->
       <!-- <div class="col-6">
            
        </div>
        <div class="col-6">
            
        </div>-->
        
    </div>
</div>



        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="btn_agregar" onclick="registrar();">Registrar</button>
         <!-- <button type="button" class="btn btn-primary" id="btn_editar" onclick="editar();">Editar</button>-->
        </div>
      </form>
    </div>
  </div>
</div>

<!--lib reloj-->
<!--<script src="assets/vendor/jquery/jquery-3.3.1.min.js"></script>-->
<link rel="stylesheet" href="application\modules\s-horarios-new/lib/gijgo.css">
<script src="application\modules\s-horarios-new/lib/gijgo.min.js"></script>


<link href="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>


<script>
  $('#hora_inici').timepicker();
  $('#hora_final').timepicker();

  $("#form_agregar_evento").validate({
    rules: {
      nombre_actividad: {
        required: true
      },
      fecha_presentacion: {
        required: true
      },
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
      nombre_actividad: "Debe ingresar el nombre la actividad.",
      fecha_presentacion: "Debe seleccionar la fecha.",
    },
    //una ves validado guardamos los datos en la DB
    submitHandler: function(form) {
      var form_data = new FormData($("#form_agregar_evento")[0]);

      if ($("#tipo_actividad").val() == "REUNION" || $("#tipo_actividad").val() == "EXAMEN") {
        
        var fecha_pre = $("#fecha_presentacion").val();
        var fecha_hoy = '<?= $fecha ?>';
        //console.log(new Date(fecha_hoy)+" : "+new Date(fecha_pre));
        if (new Date(fecha_hoy).getTime() <= new Date(fecha_pre).getTime()) {
          mensaje ="<img src='<?= imgs; ?>/loading.gif'  class='img-responsive' style='width:140px !important; height:115px !important' alt=''/><h1>Procesando espere...</h1>";
          //transicion(mensaje);
           // debugger;
          $.ajax({
            type: 'POST',
            url: "?/principal/procesos",//guardar",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'JSON',
            //datatype: 'text',
            success: function(resp) {

              if (Object.keys(resp).length > 0) {

                switch (resp['resp']) {
                  case '1': //dataTable.ajax.reload();
                    //listar_paralelos_tabla(); 
                    //dataTable.destroy();
                    $("#modal_agregar_evento").modal("hide");
                    //dataTable.ajax.reload();
                    //transicionSalir();
                    //mover_archivos(resp['archivos']);                
                    alertify.success('Se registro la actividad correctamente');
                    $("#id_asesor_curso_actividad_a").val(0);
                        //actualisar la pagina::::::::::::::::::::::::::::::::



//.::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

                    break;
                  case '2':
                    $("#modal_agregar_evento").modal("hide");
                    //dataTable.ajax.reload();
                    //transicionSalir();
                    //mover_archivos(resp['archivos']);               
                    $("#id_asesor_curso_actividad").val(0);
                    alertify.success('Se editó la actividad correctamente');
                    $("#id_asesor_curso_actividad_a").val(0);
                    break;

                  case '3':
                    //dataTable.ajax.reload();
                    //transicionSalir();
                    alertify.alert('Archivos Incompatibles...');

                    break;
                }

              } else {
                alertify.alert('No se encuentra respuesta exitosa');
              }
            }
          });
            
        } else {
          alertify.alert('La fecha de presentacion no debe ser menor a la fecha actual');
        }
      } else {
        //debugger;
       //alert('aqui');
          
        var presentar_estado = $("#presentar_actividad").val();
        var programable_estado = $("#actividad_programable").val();
        console.log(presentar_estado + " : " + programable_estado);

        var fecha_pre = $("#fecha_presentacion").val();
        var fecha_pro = $("#fecha_programable").val();
        var fecha_hoy = '<?= $fecha ?>';
        
        console.log(new Date(fecha_pro)+" < "+new Date(fecha_pre));
        //if (new Date(fecha_hoy).getTime() <= new Date(fecha_pre).getTime()) {

          if (presentar_estado === "SI" && programable_estado === "SI"){
            if (new Date(fecha_pro).getTime() <= new Date(fecha_pre).getTime()) {
              if (new Date(fecha_hoy).getTime() <= new Date(fecha_pro).getTime()) {
                mensaje = "<img src='<?= imgs; ?>/loading.gif'  class='img-responsive' style='width:140px !important; height:115px !important' alt=''/><h1>Procesando espere...</h1>";
                //debugger;
                //transicion(mensaje);
                $.ajax({
                  type: 'POST',
                  url:"?/principal/procesos",
                  data: form_data,
                  cache: false,
                  contentType: false,
                  processData: false,
                  dataType: 'JSON',
                  //datatype: 'text',
                  success: function(resp) {

                    if (Object.keys(resp).length > 0) {

                      switch (resp['resp']) {
                        case '1': 
                          //dataTable.destroy();
                          $("#modal_agregar_evento").modal("hide");
                          //dataTable.ajax.reload();
                          //transicionSalir();               
                          alertify.success('Se registro la actividad correctamente');
                          $("#id_asesor_curso_actividad_a").val(0);
                          break;
                        case '2':
                          $("#modal_agregar_evento").modal("hide");
                          //dataTable.ajax.reload();
                          //transicionSalir();               
                          $("#id_asesor_curso_actividad").val(0);
                          alertify.success('Se editó la actividad correctamente');
                          $("#id_asesor_curso_actividad_a").val(0);
                          break;

                        case '3':
                          //dataTable.ajax.reload();
                          //transicionSalir();
                          alertify.alert('Archivos Incompatibles...');

                          break;
                      }

                    } else {
                      alertify.alert('No se encuentra respuesta exitosa');
                    }
                  }
                });
              } else {
                alertify.alert('La fecha de programable es menor que la fecha actual');
              }
            } else {
              alertify.alert('La fecha de presentacion es menor que la fecha programable');
            }

          }
          if (presentar_estado === "SI" && programable_estado === "NO") {
            if (new Date(fecha_hoy).getTime() <= new Date(fecha_pre).getTime()) {
              mensaje = "<img src='<?= imgs; ?>/loading.gif'  class='img-responsive' style='width:140px !important; height:115px !important' alt=''/><h1>Procesando espere...</h1>";
              //transicion(mensaje);
              $.ajax({
                type: 'POST',
                url: "?/principal/procesos",
                data: form_data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'JSON',
                //datatype: 'text',
                success: function(resp) {

                  if (Object.keys(resp).length > 0) {

                  //debugger;
                    switch (resp['resp']) {
                      case '1': //dataTable.ajax.reload();
                        //listar_paralelos_tabla(); 
                        //dataTable.destroy();
                        $("#modal_agregar_evento").modal("hide");
                        //dataTable.ajax.reload();
                        //transicionSalir();
                        //mover_archivos(resp['archivos']);                
                        alertify.success('Se registro la actividad correctamente');
                        $("#id_asesor_curso_actividad_a").val(0);
                         listar_estudiantes();   
                        break;
                      case '2':
                        $("#modal_agregar_evento").modal("hide");
                        //dataTable.ajax.reload();
                        //transicionSalir();
                        //mover_archivos(resp['archivos']);               
                        $("#id_asesor_curso_actividad").val(0);
                        alertify.success('Se editó la actividad correctamente');
                        $("#id_asesor_curso_actividad_a").val(0);
                        break;

                      case '3':
                        //dataTable.ajax.reload();
                        //transicionSalir();
                        alertify.alert('Archivos Incompatibles...');

                        break;
                    }

                  } else {
                    alertify.alert('No se encuentra respuesta exitosa');
                  }
                }
              });
            } else {
              alertify.alert('La fecha de presentacion no debe ser menor a la fecha actual');
            }

          }
          if (presentar_estado === "NO" && programable_estado === "SI") {
            if (new Date(fecha_hoy).getTime() <= new Date(fecha_pro).getTime()) {
              mensaje = "<img src='<?= imgs; ?>/loading.gif'  class='img-responsive' style='width:140px !important; height:115px !important' alt=''/><h1>Procesando espere...</h1>";
              //transicion(mensaje);
              $.ajax({
                type: 'POST',
                url: "?/principal/procesos",//"?/d-actividad-curso/guardar",
                data: form_data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'JSON',
                //datatype: 'text',
                success: function(resp) {

                  if (Object.keys(resp).length > 0) {
              debugger;

                    switch (resp['resp']) {
                      case '1': //dataTable.ajax.reload();
                        //listar_paralelos_tabla(); 
                        //dataTable.destroy();
                        $("#modal_agregar_evento").modal("hide");
                        //dataTable.ajax.reload();
                        //transicionSalir();
                        //mover_archivos(resp['archivos']);                
                        alertify.success('Se registro la actividad correctamente');
                        $("#id_asesor_curso_actividad_a").val(0);
                             listar_estudiantes();  
                        break;
                      case '2':
                        $("#modal_agregar_evento").modal("hide");
                        //dataTable.ajax.reload();
                        //transicionSalir();
                        //mover_archivos(resp['archivos']);               
                        $("#id_asesor_curso_actividad").val(0);
                        alertify.success('Se editó la actividad correctamente');
                        $("#id_asesor_curso_actividad_a").val(0);
                        break;

                      case '3':
                        //dataTable.ajax.reload();
                        //transicionSalir();
                        alertify.alert('Archivos Incompatibles...');

                        break;
                    }

                  } else {
                    alertify.alert('No se encuentra respuesta exitosa');
                  }
                }
              });
            } else {
              alertify.alert('La fecha de programable no debe ser menor a la fecha actual');
            }
          }

          if (presentar_estado === "NO" && programable_estado === "NO") {
            //if (new Date(fecha_hoy).getTime() <= new Date(fecha_pro).getTime()) {
              if (true) {
              mensaje = "<img src='<?= imgs; ?>/loading.gif'  class='img-responsive' style='width:140px !important; height:115px !important' alt=''/><h1>Procesando espere...</h1>";
              //transicion(mensaje);
              $.ajax({
                type: 'POST',
                url: "?/principal/procesos",//"?/d-actividad-curso/guardar",
                data: form_data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'JSON',
                //datatype: 'text',
                success: function(resp) {

                  if (Object.keys(resp).length > 0) {

             // debugger;
                    switch (resp['resp']) {
                      case '1': //dataTable.ajax.reload();
                        //listar_paralelos_tabla(); 
                        //dataTable.destroy();
                        $("#modal_agregar_evento").modal("hide");
                        //dataTable.ajax.reload();
                        //transicionSalir();
                        //mover_archivos(resp['archivos']);                
                        alertify.success('Se registro la actividad correctamente');
                        $("#id_asesor_curso_actividad_a").val(0);
                             listar_estudiantes();  
                        break;
                      case '2':
                        $("#modal_agregar_evento").modal("hide");
                        //dataTable.ajax.reload();
                        //transicionSalir();
                        //mover_archivos(resp['archivos']);               
                        $("#id_asesor_curso_actividad").val(0);
                        alertify.success('Se editó la actividad correctamente');
                        $("#id_asesor_curso_actividad_a").val(0);
                        break;

                      case '3':
                        //dataTable.ajax.reload();
                        //transicionSalir();
                        alertify.success('Archivos Incompatibles...');

                        break;
                    }

                  } else {
                    alertify.alert('No se encuentra respuesta exitosa');
                  }
                }
              });
            } else {
              alertify.alert('algo ocurrio cominicate con el administrador');
            }
          }
        //}
      }
      return false;
    }
  });


  //Web services para consultar la movida
  function mover_archivos(documentos) {

    url_webServices = url + "documentos_docentes/copear_archivos.php";
    //url_documentos = "https://sanluisdegonzaga.com/sistema/files/documentos/";
    url_documentos = "https://sanluisdegonzaga.com/sistemaactual/files/documentos/";
    $.ajax({
      url: url_webServices,
      type: 'POST',
      data: {
        'documentos': documentos,
        'url': url_documentos
      },
      dataType: 'JSON',
      success: function(resp) {
        if (Object.keys(resp).length > 0) {
          switch (resp['exitoso']) {
            case '0':
              alertify.warning('Ocurrio algunos errores en el copeado');
              break;
            case '1':
              eliminar_archivos(resp['archivos']);
              //llamamos la funcion para poder borrar los documentos del servidor donde esta el trabajo
              //alertify.success('Se creo la actividad correctamente');
              break;
          }
        } else {
          alertify.warning('No se encuentra respuesta exitosa al mover archivos');
        }
      }
    });
  }

  //Web services para eliminar los documentos
  function eliminar_archivos(documentos) {
    $.ajax({
      type: 'POST',
      url: "?/d-actividad-curso/borrar-documento",
      data: {
        'documentos': documentos
      },
      dataType: 'JSON',
      success: function(resp) {
        if (Object.keys(resp).length > 0) {
          switch (resp['exitoso']) {
            case '0':
              alertify.warning('Ocurrio algunos errores en el eliminado');
              break;
            case '1':
              //llamamos la funcion para poder borrar los documentos del servidor donde esta el trabajo
              //alertify.success('Se Borro exitosamente');
              alertify.success('Se registro el comunicado correctamente');
              break;
          }
        } else {
          alertify.warning('No se encuentra respuesta exitosa al mover archivos');
        }
      },
      // código a ejecutar sin importar si la petición falló o no
      complete: function(xhr, status) {
        setTimeout("location.reload()", 1000);
      }
    });
  }

  function registrar() {
    $('#btn_agregar').attr("type", "submit");
    $('#btn_agregar').disabled = true;
  }

  function editar() {
    $('#btn_editar').attr("type", "submit");
    $('#btn_editar').disabled = true;
  }

  function presentaractividad() {
    presentar_actividad = $('#presentar_actividad').val();
    if (presentar_actividad == "SI") {
      $("#panel_fecha_hora_presentable").show();
    } else {
      $("#panel_fecha_hora_presentable").hide();
    }
  }

  function programaractividad() {
    presentar_actividad = $('#actividad_programable').val();
    if (presentar_actividad == "SI") {
      $("#panel_fecha_hora_programable").show();
    } else {
      $("#panel_fecha_hora_programable").hide();
    }
  }

  function utilizargenerador() {
    utilizar_generador = $('#utilizar_generador').val();
    if (utilizar_generador == "SI") {
      $("#panel_urls").hide();
      $("#panel_url_reunion").hide();
      $("#panel_fecha_hora_presentable").show();
    } else {
      $("#panel_urls").hide();
      $("#panel_url_reunion").show();
      $("#panel_fecha_hora_presentable").show();
      $("#etiqueta_reunion_examen").text("URL de la examen");
      $("#etiqueta_fecha").text("Fecha del examen :");
      $("#etiqueta_hora").text("Hora limite del examen :");
    }
    $("#panel_programable").hide();
    $("#panel_fecha_hora_programable").hide();
  }
    //alert('asd');
  function tipoactividad() {
    tipo_actividad = $('#tipo_actividad').val();

    $("#etiqueta_hora_examen").text("Hora de presentacion:");


    switch (tipo_actividad) {

      case "REUNION":
        $("#panel_urls").hide();
        $("#panel_url_reunion").show();
        $("#panel_presentable").hide();
        $("#panel_programable").hide();
        $("#panel_examen_generador").hide();
        $("#panel_fecha_hora_presentable").show();
        $("#panel_fecha_hora_programable").hide();
        $("#etiqueta_fecha").text("Fecha de la reunion :");
        $("#etiqueta_hora").text("Hora de la reunion :");
        $("#etiqueta_reunion_examen").text("URL de la reunion");
        break;

      case "EXAMEN":
        $("#panel_urls").hide();
        $("#panel_url_reunion").hide();
        $("#panel_presentable").hide();
        $("#panel_programable").hide();
        $("#panel_examen_generador").show();
        $("#panel_fecha_hora_presentable").show();
        $("#panel_fecha_hora_programable").hide();
        $("#panel_url_reunion").show();
        $("#etiqueta_reunion_examen").text("URL del examen");
        $("#etiqueta_fecha").text("Fecha del examen :");
        $("#etiqueta_hora").text("Hora limite del examen :");
        $("#utilizar_generador").val("NO");
        break;

      default:
        $("#panel_urls").show();
        $("#panel_presentable").show();
        $("#panel_programable").show();
        $("#etiqueta_fecha").text("Fecha de presentacion :");
        $("#etiqueta_hora").text("Hora de presentacion :");
        $("#panel_url_reunion").hide();
        $("#panel_examen_generador").hide();
        $("#panel_fecha_hora_presentable").hide();
        $("#panel_fecha_hora_programable").hide();
        $("#actividad_programable").val("NO");
        $("#presentar_actividad").val("NO");

        break;
    }
  }


  function Habilitar(nro) {
    $('.dependiente_div' + nro).css({
      'display': 'block'
    });
  }
</script>