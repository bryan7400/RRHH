
<form id="form_felicitacion" enctype="multipart/form-data">
<div class="modal fade" id="modal_felicitacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Registrar Felicitacion</h5>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
				<input id="id_kardex" type="hidden" class="form-control" name="id_kardex" >
			 
			</div>
			<div class="modal-body">

				 <input type="text" class="form-control" id="tipoFelSanc" name="tipoFelSanc" placeholder="tipoFelSanc" readonly>
				 <input type="hidden" class="form-control" id="id_persona" name="id_persona" placeholder="id_persona">
				<!--<div class="control-group" style="margin-button:15px">
					<label class="control-label">fecha: </label>
					<div class="controls">
						<input type="date" class="form-control" id="fecha_f" name="fecha_f"> 
					</div>
				</div>-->
				 
				 <div class="control-group" style="margin-button:15px">
          <label class="control-label" for="fecha_felicitacion">Fecha : </label>
          <div class="controls">
            <input id="fecha_felicitacion"  name="fecha_felicitacion" type="date" class="form-control" data-validation="required date" >
          </div>
        </div>
				<!--<div class="control-group" style="margin-button:15px">
					<label class="control-label">hora: </label>
					<div class="controls">
						<input type="time" class="form-control" id="hora_f" name="motivo"> 
					</div>
				</div>-->
				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Concepto: </label>
					<div class="controls">
						<input type="text" class="form-control" id="concepto_f" name="concepto"> 
					</div>
				</div>
				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Tipo: </label>
					<div class="controls">
					<select class="form-control" id="tipo_f" name="tipo_f">
					    <option value="1">tipo evaluacion</option>
					    <option value="2">tipo memorandum</option>
					</select> 
					</div>
				</div>

				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Descripcion: </label>
					<div class="controls">
						<textarea class="form-control" id="descripcion_f" rows="3" name="descripcion"></textarea>
					</div>
				</div>		
                
             			

				<!--<div class="control-group" style="margin-button:15px"> 
					<div class="controls">
					<input type="hidden" class="form-control" class="form-control" id="fecha_felicitacion" name="fecha_felicitacion">
					</div>
				</div>-->
								

         <div class="form-row form-group">
          

          <label for="archivo_documento" class="control-label">Archivo:</label>
          <input  type="file" name="archivo_documento" id="archivo_documento" class="form-control" data-validation="required mime size dimension" data-validation-allowing="jpg, png" data-validation-max-size="4M" data-validation-dimension="max1920"  accept=".pdf,.application/pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" >
          
          
        </div>      
        <div class="form-group">
             
          <input type="hidden" type="text" value="" name="archivo_documento_nombre" id="archivo_documento_nombre" class="form-control" autofocus="autofocus" data-validation="required letter length" data-validation-allowing=" " data-validation-length="max100">
           <input type="hidden" type="text" value="" name="documento" id="documento" class="form-control" autofocus="autofocus" data-validation="required letter length" data-validation-allowing=" " data-validation-length="max100">
          
          
        </div>  

                
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-light pull-left" data-dismiss="modal">Cancelar</button>
				<button type="submit" class="btn btn-primary pull-left">Registrar</button>
			</div>
		</div>
	</div>
</div>
</form>

<script>
$("#form_felicitacion").validate({
    rules : { 
		motivo:{required:true},
		descripcion:{required:true},
        fecha_felicitacion:{required:true}
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
        motivo: "Debe ingresar un motivo.",
		descripcion: "Debe ingresar una felicitacion.",
		fecha_felicitacion: "Debe ingresar la fecha."        
    },
    //una ves validado guardamos los datos en la DB
  	submitHandler: function(form){
      //alert();
      var datos = $("#form_felicitacion").serialize();     
      datos=datos+'&accion=guardar_kardex';
      var formData = new FormData($("#form_felicitacion")[0]);
      formData.append("accion", "guardar_kardex");
      //console.log(datos);  

      $.ajax({
          type: 'POST',
          url: "?/rrhh-kardex/procesos",
          //url: "?/d-curso-asignados/procesos",
          data: formData,
              cache: false,
              contentType: false,
              processData: false, 
          success: function (resp) {
            console.log(resp);
            switch(resp){
              case '1': //dataTable.ajax.reload();
                        listarkardex(id_persona);//id=varable de pagina
                        listarpersonal();
                        $("#modal_felicitacion").modal("hide");
                        alertify.success('Se registro correctamente');
                        break;
              case '2': //dataTable.ajax.reload();
                       listarkardex(id_persona);//id=varable de pagina
                       listarpersonal();
                        $("#modal_felicitacion").modal("hide");
                        alertify.success('Edicion correcta'); 
                        break;
            
              case '3': //dataTable.ajax.reload()
             			listarpersonal();
                        $("#modal_felicitacion").modal("hide");
                        alertify.error('Operacion fallida');
                        break;
            }
            
          }          
      });    
  }

});
   
    
</script> 
<!--Formulario para registro_____________________________________________________________________-->
  
 <form id="form_sancion" enctype="multipart/form-data">
<div class="modal fade" id="modal_sancion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Registrar Sancion</h5>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
                </a>
                <input id="id_sancion" type="hidden" class="form-control" name="id_sancion" >
                <input id="id_estudiante_s" type="hidden" class="form-control" name="id_estudiante" >
                <input id="id_profesor_materia_s" type="hidden" class="form-control" name="id_profesor_materia">
                <input id="modo_calificacion_id_s" type="hidden" class="form-control" name="modo_calificacion_id">
			</div>
			<div class="modal-body">

            
				

                <div class="control-group" style="margin-button:15px">
          <label class="control-label" for="fecha_asistir">Fecha a Asistir: </label>
          <div class="controls">
            <input id="fecha_asistir"  name="fecha_asistir" type="date" class="form-control" data-validation="required date" >
          </div>
        </div>


               <!-- <div class="control-group" style="margin-button:15px">
					<label class="control-label">fecha: </label>
					<div class="controls">
						<input type="date" class="form-control" id="fecha_s" name="motivo"> 
					</div>
				</div>
				<div class="control-group" style="margin-button:15px">
					<label class="control-label">hora: </label>
					<div class="controls">
						<input type="time" class="form-control" id="hora_s" name="motivo"> 
					</div>
				</div>-->
				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Concepto: </label>
					<div class="controls">
						<input type="text" class="form-control" id="motivo_s" name="motivo"> 
					</div>
				</div>
				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Tipo: </label>
					<div class="controls">
					<select name="" class="form-control" id="tipo_s" name="motivo">
					    <option value="">tipo 1</option>
					    <option value="">tipo 2</option>
					</select> 
					</div>
				</div>

				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Descripcion: </label>
					<div class="controls">
						<textarea class="form-control" id="descripcion_s" rows="3" name="descripcion"></textarea>
					</div>
				</div>		<div class="control-group" style="margin-button:15px">
					<label class="control-label">File: </label>
					<div class="controls">
						 <input type="file" class="form-control" id="file_s" name="file_s">
					</div>
				</div>				

             
                				
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-default btn-light pull-left" data-dismiss="modal">Cancelar</button>
				<button type="submit" class="btn btn-primary pull-left">Registrar</button>
			</div>
		</div>
	</div>
</div>
</form>

<script>

$("#form_sancion").validate({
    rules : { 
        motivo:{required:true},
        dias:{required:true}
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
        motivo: "Debe ingresar un motivo.",
        dias: "Debe ingresar dias de suspension."        
    },
    //una ves validado guardamos los datos en la DB
  submitHandler: function(form){
      //alert();
      var datos = $("#form_sancion").serialize();     
      datos=datos+'&accion=guardar_sancion';

      console.log(datos);  

      var formData = new FormData($("#form_sancion")[0]);
      formData.append("accion", "guardar_sancion");
      console.log(datos);  

      $.ajax({
          type: 'POST',
          url: "?/rrhh-kardex/procesos",
          
          data: formData,
              cache: false,
              contentType: false,
              processData: false, 
          success: function (resp) {
            console.log(resp);
            switch(resp){
              case '1': 
                    //dataTable.ajax.reload();
                     listarEstudiantesKardex();
                        $("#modal_sancion").modal("hide");
                        alertify.success('Se registro la sancion correctamente');
                        break;
              case '2': //dataTable.ajax.reload();
                        $("#modal_sancion").modal("hide");
                        alertify.warning('No se registro la sancion'); 
                        break;
              case '3': //dataTable.ajax.reload();
                        $("#modal_sancion").modal("hide");
                        alertify.success('Edicion correcta');
                        verEstCardex(idest);//traido de crear historial verEstCardex(docente.php)
                        break;
            }
            //pruebaa();
          }          
      });      
  }

});
$('#fecha_asistir').datepicker({
    timepicker: true,
    language: 'es',
    position:'bottom left',
    dateFormat: 'yyyy-mm-dd', 
    //startDate: start,
    minHours: 8,
    maxHours: 18,
    timeFormat: "hh:ii:00",//hh:horas ii:minutos mm:seg
    //timeFormat: "hh:ii",
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
})    
 
</script>    



<!--
<form id="form_citacion">
<div class="modal fade" id="modal_citacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Registrar Citacion</h5>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
				<input id="id_citacion" type="hidden" class="form-control" name="id_citacion" >
				<input id="id_estudiante_c" type="hidden" class="form-control" name="id_estudiante" >
                <input id="id_profesor_materia_c" type="hidden" class="form-control" name="id_profesor_materia" placeholder="id_profesor_materia"> 
                <input id="modo_calificacion_id_c" type="hidden" class="form-control" name="modo_calificacion_id">
			</div>

			<div class="modal-body">

				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Motivo de la Citacion: </label>
					<div class="controls">
						<input id="motivo_ci" name="motivo_ci" type="text" class="form-control">
					</div>
				</div>

				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Dia de la Citacion: </label>
					<div class="controls">
						<input id="fecha_ci" name="fecha_ci" type="text" class="form-control">
					</div>
				</div>				
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-light pull-left" data-dismiss="modal">Cancelar</button>
				<button type="submit" class="btn btn-primary pull-left">Registrar</button>
			</div>
		</div>
	</div>
</div>
</form>
 
<script>
$("#form_citacion").validate({
    rules : { 
        motivo_ci:{required:true},
        fecha_ci:{required:true}
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
        motivo_ci: "Debe ingresar un motivo.",
        fecha_ci: "Debe ingresar la fecha."        
    },
    //una ves validado guardamos los datos en la DB
  	submitHandler: function(form){
      //alert();
      var datos = $("#form_citacion").serialize();     
      datos=datos+'&accion=btn_citacion';

      console.log("Enviando: "+datos);  

      $.ajax({
          type: 'POST',
          url: "?/principal/procesos",
          //url: "?/d-curso-asignados/procesos",
          data: datos,
          success: function (resp) {
            console.log("Resp: "+resp);
            switch(resp){
              case '1': //dataTable.ajax.reload();
                        listarEstudiantesKardex();
                        $("#modal_citacion").modal("hide");
                        alertify.success('Se registro la citacion correctamente');
                        break;
              case '2': //dataTable.ajax.reload();
                        $("#modal_citacion").modal("hide");
                        alertify.warning('No se registro la citacion'); 
                        break;
                case '3': //dataTable.ajax.reload();
                        $("#modal_citacion").modal("hide");
                        alertify.success('Edicion correcta');
                        verEstCardex(idest);
                        break;
            }
            
          }          
      });      
  }

});
$('#fecha_ci').datepicker({
    timepicker: true,
    language: 'es',
    position:'top left',
    dateFormat: 'yyyy-mm-dd', 
    //startDate: start,
    minHours: 8,
    maxHours: 18,
    timeFormat: "hh:ii:00",//hh:horas ii:minutos mm:seg
  
    onSelect: function(fd, d, picker){
        var fecha_marcada = moment(d).format('YYYY-MM-DD');
        var hoy = moment(new Date()).format('YYYY-MM-DD');

        if(fecha_marcada >= hoy){

        }else{
            alertify.error('No puede asignar tareas en una fecha pasada a la actual');
            $("#fecha_inicio").val("");
        }
        
    }
})    
 
</script>  -->  
 <!--_____________________________________________________________________-->