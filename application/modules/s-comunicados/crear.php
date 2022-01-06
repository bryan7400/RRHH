<?php

// Obtiene la cadena csrf
$csrf = set_csrf();
// Obtiene los permisos

$roles = $db->query("SELECT * FROM sys_roles WHERE rol != 'Superusuario'")->fetch();?>

<!-- Modal -->
<div class="modal  fade" id="modal_agregar_evento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form class="form-horizontal" id="form_agregar_evento"> 
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_modal"></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body"> <!--comieza el body de la modal-->
              
              <div class="form-group" style="margin-bottom:15px;">
                <label class="control-label">Titulo:</label>
                <div class="controls control-group">
                  <input type="hidden" name="id_comunicado" class="form-control" id="id_comunicado">
                  <input type="text" name="nombre_evento" class="form-control" id="nombre_evento">
                </div>
              </div>
              <div class="form-group" style="margin-bottom:15px;">
                <label for="title" class="control-label">Descripción:</label>
                <div class="controls control-group">
                  <input type="text" name="descripcion_evento" class="form-control" id="descripcion_evento">
                </div>
              </div>			  
              <div class="form-group">
                <label for="hiddeninput">Color:</label>
                <br>
                <input type="hidden" id="color_evento" name="color_evento" class="form-control" value="#3462c0">
              </div>
              <div class="form-group" style="margin-bottom:15px;">
                <label for="title" class="control-label">Fecha de Inicio:</label>
                <div class="controls control-group">
                    <input type='text' class='datepicker-here form-control' id="fecha_inicio" name="fecha_inicio" readOnly/>
                </div>
              </div>
              <div class="form-group" style="margin-bottom:15px;">
                <label for="title" class="control-label">Fecha a Terminar:</label>
                <div class="controls control-group">
                    <input type='text' class='datepicker-here form-control' id="fecha_final" name="fecha_final" readOnly/>
                </div>
              </div>
              <div class="form-group" style="margin-bottom:15px;">
                  <label for="title" class="control-label">Roles:</label>
                <div class="controls control-group">
                  <select class="selectpicker form-control" id="select_roles" name="select_roles" multiple title="Seleccione">
                    <?php if ($roles): ?>                      
                      <?php foreach ($roles as $key => $valor): ?>
                        <option value="<?= $valor['id_rol'];?>"><?= $valor['rol'];?></option>                      
                      <?php endforeach ?>
                    <?php endif ?>                    
                  </select>
                </div>
              </div>
                <div class="form-group" style="margin-bottom:15px;">
                <label for="title" class="control-label">Archivo:</label>
                <div class="controls control-group">
                  <input type="file" name="file_evento_p" class="form-control" id="file_evento_p">
                </div>
              </div>	
                <div class=" form-group" >
                <label for="title" class="control-label">Prioridad</label>
                <div class="controls control-group">
                    <select name="prioridad" id="prioridad" class='form-control'>
                        <option value="1"  >Bajo (Normal)</option>
                        <option value="2" style="color:orange">Medio</option>
                        <option value="3" style="color:red">Alto</option>
                       <!-- <option value="2" style="color:blue">Importante</option>-->
                    </select>
                </div>
                  <input type="hidden" name="rolescomp" class="form-control" id="rolescomp">
              </div>	
              
              <div class="form-group" id="div_eliminar" style="display:none">
                <!--label for="end" class="col-sm-4 control-label" style="color:red">Eliminar evento: </label>
                <div class="col-sm-1">
                  <input type="checkbox" name="eliminar" class="form-control" id="eliminar">
                </div-->
                <label class="custom-control custom-checkbox" style="color:red">
                  <input type="checkbox" class="custom-control-input" name="eliminar" id="eliminar"><span class="custom-control-label">Eliminar evento</span>
                </label>
              </div>

            </div><!--termina el body de la modal-->
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal" >Cancelar</button>
              <button type="submit" class="btn btn-primary" id="btn_agregar" >Registrar</button>
              <button type="submit" class="btn btn-primary" id="btn_editar">Editar</button>
            </div>
          </form>
        </div>
    </div>
</div>

<script>

$("#form_agregar_evento").validate({
  rules: {
      nombre_evento: {required: true},
      fecha_inicio: {required: true},
	    fecha_final: {required: true},
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
      //select_roles: "Debe seleccionar al menos un rol."
  },
  //una ves validado guardamos los datos en la DB
  submitHandler: function(form){
	//e.preventDefault();
  var id_comunicado=$("#id_comunicado").val();
  var nombre_evento=$("#nombre_evento").val();
  var descripcion_evento=$("#descripcion_evento").val();
  var color_evento=$("#color_evento").val();
  var fecha_inicio=$("#fecha_inicio").val();
  var fecha_final=$("#fecha_final").val();
  var select_roles=$("#select_roles").val()

  $("#rolescomp").val($("#select_roles").val());
  var select_roles=$("#rolescomp").val();
  var form_data = new FormData($("#form_agregar_evento")[0]); 
     
    $.ajax({
          type: 'POST',
          url: "?/s-comunicados/guardar",
          data: form_data,//+'&roles='+'1,2,3,4,6',//parametros,
        cache: false,
        contentType: false,
        processData: false, 
        datatype: 'text',
          success: function (resp) {
            console.log(resp);
            var resp=JSON.parse(resp,true);
            var estresp=resp['estresp'];
              
            //console.log("esta es la respuesta:"+resp);;
            //cont = 0;
            switch(estresp){
              case '1': //dataTable.ajax.reload();
                    listar_comunicados_tabla(); 
                    $("#modal_agregar_evento").modal("hide");
                    alertify.success('Se registro el comunicado correctamente.');
                    notificarfire(resp.datos.nombre_evento,resp.datos.descripcion,resp.datos.fecha_inicio,resp.datos.usuarios,resp.datos.id_comunicado,resp.datos.persona_id,resp.datos.grupo,0); 
                    //notificarfire(nombre_evento,descripcion_evento,fecha_inicio,select_roles,id_comunicado,',',0); //0=grupo administrativo, t todos est, m=mujeres, v= varones
                    //notificarfire(titulo,descripcion,fecha,rol_id,id_comunicado,personas_id,grupo);
                    break;
              case '2':
                    listar_comunicados_tabla(); 
                    $("#modal_agregar_evento").modal("hide");
                    alertify.success('Se editó el comunicado correctamente.');
                     notificarfire(resp.datos.nombre_evento,resp.datos.descripcion,resp.datos.fecha_inicio,resp.datos.usuarios,resp.datos.id_comunicado,resp.datos.persona_id,resp.datos.grupo,0); 
                    //notificarfire(nombre_evento,descripcion_evento,fecha_inicio,select_roles,id_comunicado,',',0); 
                    break;
            } 
          },
          error: function(e){
            console.log("e");
          }
    });
return false;  
  }
})

var disabledDays = [0, 6];
$('#fecha_inicio').datepicker({
    timepicker: true,
    language: 'es',
    position:'top left',
    dateFormat: 'yyyy-mm-dd', 
    minHours: 8,
    maxHours: 18,
    timeFormat: "hh:ii:00",
    onSelect: function(fd, d, picker){
        var fecha_marcada = moment(d).format('YYYY-MM-DD');
        var hoy = moment(new Date()).format('YYYY-MM-DD');
        if(fecha_marcada >= hoy){        
        }else{
          alertify.error('No puede asignar tareas en una fecha pasada a la actual.');
          $("#fecha_inicio").val("");
        }
    }
})

$('#fecha_final').datepicker({
    timepicker: true,
    language: 'es',
    position:'top left',
    dateFormat: 'yyyy-mm-dd', 
    minHours: 8,
    maxHours: 18,
    timeFormat: "hh:ii:00",//"hh:ii",
    onSelect: function(fd, d, picker){
        var fecha_marcada = moment(d).format('YYYY-MM-DD');
        var hoy = moment(new Date()).format('YYYY-MM-DD');
        if(fecha_marcada >= hoy){
        }else{
            alertify.error('No puede asignar tareas en una fecha pasada a la actual.');
            $("#fecha_inicio").val("");
        }        
    }
});
   
</script>
