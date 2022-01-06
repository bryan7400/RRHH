<?php
  //$csrf = set_csrf();
?>
 
<form id="form_actividad">
<div class="modal fade" id="modal_actividad" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="titulo_actividad"></span></h5>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
			</div>
			<div class="modal-body">
               
                <input id="id_actividad_materia_modo_area" name="id_actividad_materia_modo_area" type="hidden" class="form-label">						
				<div class="control-group" style="margin-bottom:15px;">
					<label class="control-label">Nombre Actividad: </label>
					<div class="controls">
                        <!--input type="hidden" name="<?= $csrf; ?>"-->
						<input id="nombre_actividad" name="nombre_actividad" type="text" class="form-control impcomp">
						<b class=" pcomp nombre_actividad">nombre</b>
						
					</div>
				</div>
				<div class="control-group" style="margin-bottom:15px;">
					<label class="control-label">Descripción Actividad: </label>
					<div class="controls">
						<input id="descripcion_actividad" name="descripcion_actividad" type="text" class="form-control impcomp">
						<b class=" pcomp descripcion_actividad"></b>
					</div>
				</div>
				<div class="control-group" style="margin-bottom:15px;">
					<label class="control-label">Fecha de Presentacion: </label>
					<div class="controls">
						<input id="fecha_presentacion" name="fecha_presentacion" type="datetime" class="form-control impcomp" value="<?php echo date('Y-m-d H:i:s');?>" readOnly>
						<b class="  pcomp fecha_presentacion"></b>
					</div>
                </div>
            </div>
			<div class="modal-footer">
                <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary pull-right" id="btn_nuevo">Registrar</button>
                <button type="submit" class="btn btn-primary pull-right" id="btn_modificar">Guardar edicion</button>
                <!--nuvas acciones-->
                <button type="button" class="btn btn-primary pull-right" id="btn_vista_aEdit" onclick="vista_aEdit()">Editar</button>
                <button type="button" class="btn btn-danger pull-right" id="btn_eliminar" onclick="abrir_eliminar()">Eliminar</button>
			</div>
		</div>
	</div>
</div>
</form>
<!--modal agregar comentario para cada estudainte -->
 
<form id="form_comentario">
<div class="modal fade" id="modal_comentario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				 
				<h5 class="modal-title" id="exampleModalLabel"><span id="titulo_actividad">VALORACION CUALITATIVA: </span></h5>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
			</div>
			<div class="modal-body">
               
               <!-- <input id="id_actividad_materia_modo_areaC" name="id_actividad_materia_modo_area" type="text" class="form-label">	-->					
				<div class="control-group" style="margin-bottom:15px;">
					<div class="controls">
                        <input type="hidden" name="id_estudiante_modo_observacion" id="id_estudiante_modo_observacion" >
                        <input type="hidden" name="id_estudianteC" id="id_estudianteC" >
                        <!--<input type="text" name="id_aula_asignacionC" id="id_aula_asignacionC" >-->
                        <textarea id="valoracionCualitativa" name="valoracionCualitativa"  class="form-control impcomp" rows="8" onkeyup="contarcaracteres(this)">
                        </textarea>
					 
						<b class=" pcomp nombre_actividad">nombre</b>
						<p>Caracteres: <span class="Ncaracteres">00</span></p>
					</div>
				</div>
				 
            </div>
			<div class="modal-footer">
                <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary pull-right" id="btn_nuevoComentario">Registrar</button>
                <button type="submit" class="btn btn-primary pull-right" id="btn_modificarComentario">Guardar edicion</button>
               
			</div>
		</div>
	</div>
</div>
</form>



<script>

function contarcaracteres(obj){
     var nn=$('#valoracionCualitativa').val().length;
    $('.Ncaracteres').text(nn);
}    
     
    
    
$('#fecha_presentacion').datepicker({
    timepicker: true,
    language: 'es',
    position:'top left',
    dateFormat: 'yyyy-mm-dd', 
    //startDate: start,
    minHours: 8,
    maxHours: 18,
    timeFormat: "hh:ii:00",//"hh:ii",
    /*onRenderCell: function (date, cellType) {
    if (cellType == 'day') {
            var day = date.getDay(),
                isDisabled = disabledDays.indexOf(day) != -1;

            return {
                disabled: isDisabled
            }
        }
    },*/
    onSelect: function(fd, d, picker){
        var fecha_marcada = moment(d).format('YYYY-MM-DD');
        var hoy = moment(new Date()).format('YYYY-MM-DD');

        if(fecha_marcada >= hoy){

        }else{
            alertify.error('No puede asignar tareas en una fecha pasada a la actual');
            $("#fecha_inicio").val("");
        }
        
    }
});
    
$("#form_actividad").validate({
  rules: {
      //validacion de los campos
      nombre_actividad: {required: true},
      fecha_presentacion: {required: true}
  },
  errorClass: "help-inline",
  errorElement: "span",
  highlight: highlight, //da estilo cuando esta validado
  unhighlight: unhighlight, //da estilo cuando no esta validado
  messages: {
      //mensajes personalizado por cada campo
      nombre_actividad: "Debe ingresar el nombre de la actividad.",
      fecha_presentacion: "Debe seleccionar la fecha de presentación."
  },
  //una ves validado guardamos los datos en la DB
  submitHandler: function(form){
      
     var id_aula_asig_materia=$('#id_materia').val();
      //alert('id_materia'+id_materia);
      var id_modo_area=$(objV).attr('modo_area_id');
     // alert('id_modo_area'+id_modo_area); 
      var id_bimestre=$('#bimestre').val();
      //alert('bimestre'+id_bimestre);
      //var id_area=$(objV).attr('area_head_id');
      //alert('area'+id_area);
      
      var datos = $("#form_actividad").serialize();
      datos=datos+'&id_aula_asig_materia='+id_aula_asig_materia;      
      datos=datos+'&id_bimestre='+id_bimestre;      
      datos=datos+'&id_modo_area='+id_modo_area;      
       datos=datos+'&accion=guardar_tarea';      
      // alert('aquidatos'+datos);
 

       
      $.ajax({
          type: 'POST',
          url: "?/principal/procesos",
          data: datos,
          success: function (resp) {
       
              switch(resp){
                case '1':  
                listar_estudiantes();
                alertify.success('Se editó la actividad correctamente'); break;
                $("#modal_actividad").modal("hide");
                case '2':  
                listar_estudiantes();
                alertify.success('Se registro la actividad correctamente'); break;
                $("#modal_actividad").modal("hide");
                case '5':
                      alertify.error('No se pudo crear la actividad correctamente'); 
                break; 
                case '6':
                      alertify.error('No se editó la actividad correctamente'); 
                break; 
                case '10':
                      alertify.error('Revise las fechas correspondientes a su bimestre'); 
                break;
                case '11':
                      alertify.error('El modo de calificacion no existe, deve otogar permisos en gestion modos... '); 
                break;
                      
                default:
                    alertify.error('No se pudo registrar la actividad');
      
                }
           
          }
      });
      
      return false;
  }
})
      
$("#form_comentario").validate({
  rules: { 
      valoracionCualitativa: {required: true} 
  },
  errorClass: "help-inline",
  errorElement: "span",
  highlight: highlight, //da estilo cuando esta validado
  unhighlight: unhighlight, //da estilo cuando no esta validado
  messages: { 
      valoracionCualitativa: "Debe ingresar el nombre de la actividad." 
  },
  //una ves validado guardamos los datos en la DB
  submitHandler: function(form){
      
     var id_estudiante_modo_observacion=$('#id_estudiante_modo_observacion').val();
     var valoracionCualitativa=$('#valoracionCualitativa').val();
     var id_estudianteC=$('#id_estudianteC').val();
    // var id_aula_asignacionC=$('#id_aula_asignacionC').val();
     var id_aula_asignacion=$('#id_materia').val();
     var id_bimestre=$('#bimestre').val();
      //alert(valoracionCualitativa.length);
      
      if(valoracionCualitativa.length<=400){
         
         // alertify.error('ok'); 
            $.ajax({
                  type: 'POST',
                  url: "?/principal/procesos",
                  data: {
                      accion:'valoracionCualitativaModo',
                      id_estudiante_modo_observacion:id_estudiante_modo_observacion,
                      valoracionCualitativa:valoracionCualitativa,
                      id_estudianteC:id_estudianteC,
                      //id_aula_asignacionC:id_aula_asignacionC,
                      id_aula_asignacion:id_aula_asignacion,
                      id_bimestre:id_bimestre
                  },
                  success: function (resp) {
 
                      //$("#modal_comentario").modal("hide"); 
                      switch(resp){
                        case '1':  
                        //listar_estudiantes();
                        $("#modal_comentario").modal("hide");
                        alertify.success('Se creo la actividad correctamente'); 
                        listar_estudiantes();
                        break;
                        case '2':
                            $("#modal_comentario").modal("hide");
                            alertify.success('Se edito la actividad correctamente'); 
                             // listar_estudiantes();
                        break; 
                        case '5':
                              alertify.error('No se puedo crear'); 
                        break;  
                        default:
                            alertify.success('Ocurrio un error inesperado');
                              //$("#modal_actividad").modal("hide");
                            //recibir el id
                           // listar_estudiantes(); 
                        }
                  }
            }); 
         }else{
          alertify.error('El tamaño deve ser menor a 400 caracteres'); 
         }
      
/*     var id_aula_asig_materia=$('#id_materia').val();
      //alert('id_materia'+id_materia);
      var id_modo_area=$(objV).attr('modo_area_id');
     // alert('id_modo_area'+id_modo_area); 
      var id_bimestre=$('#bimestre').val();
 
      var datos = $("#form_actividad").serialize();
      datos=datos+'&id_aula_asig_materia='+id_aula_asig_materia;      
      datos=datos+'&id_bimestre='+id_bimestre;      
      datos=datos+'&id_modo_area='+id_modo_area;      
       datos=datos+'&accion=guardar_tarea';      
 
      $.ajax({
          type: 'POST',
          url: "?/principal/procesos",
          data: datos,
          success: function (resp) {
 
       
              switch(resp){
                case '1':  
                listar_estudiantes();
                alertify.success('Se editó la actividad correctamente'); break;
                $("#modal_actividad").modal("hide");
                case '5':
                      alertify.error('No se pudo crear la actividad correctamente'); 
                break; 
                case '6':
                      alertify.error('No se editó la actividad correctamente'); 
                break; 
                case '10':
                      alertify.error('Revise las fechas correspondientes a su bimestre'); 
                break;
                      
                default:
                    alertify.success('Se registro la actividad correctamente');
                $("#modal_actividad").modal("hide");
                    //recibir el id
                    listar_estudiantes();
                    //newtarea(objV,tipoV,resp);
                }
            //pruebaa();
          }
      });*/
      
      return false;
  }
})
    
function newtarea(obj,tipo,id_act_rec){ 
        var area_id=$(obj).attr('area_head_id');
        //var id_actividad=parseInt($(obj).parent().attr('colspan'));//3
        var colspan_ant=parseInt($(obj).parent().attr('colspan'));//3
       // alert(colspan_ant);
        var colspan_new=colspan_ant+1;//4 
        $('thead').find("."+tipo+'prom').before('<th  class="vertical  '+tipo+colspan_ant+'" style="background:#7cedff"><div class="vertical">NEW tarea '+colspan_ant+'</div></th>');
         
        $(".tbody tr").each(function(){
             
             var estudiante=$(this).attr('estudiante_id');
            var clasprom=tipo+'prom';
            //alert('aqui tipo:-'+clasprom+'-');
            $(this).find("."+clasprom).before('<td  class="'+tipo+colspan_ant+'" > <input type="text" class="inpmov" onkeyup="keyupimp(this)"  area_estudiante="'+area_id+'-'+estudiante+'" area_id="'+area_id+'" estudiante_id="'+estudiante+'" actividad_id="'+id_act_rec+'" estudiante_tarea_id="'+estudiante+'-'+id_act_rec+'" > </td>');
      
        });
        
        
        $(obj).parent().attr('colspan',colspan_new); 
        
 
    }

</script>