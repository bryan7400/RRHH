<form id="form_felicitacion">
<div class="modal fade" id="modal_felicitacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Registrar Felicitacion</h5>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
				<input id="id_felicitacion" type="hidden" class="form-control" name="id_felicitacion" >
				<input id="id_estudiante" type="hidden" class="form-control" name="id_estudiante" >
                <input id="id_profesor_materia" type="hidden" class="form-control" name="id_profesor_materia">
                <input id="modo_calificacion_id" type="hidden" class="form-control" name="modo_calificacion_id">
			</div>
			<div class="modal-body">

				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Motivo de la Felicitacion: </label>
					<div class="controls">
						<textarea class="form-control" id="motivo_feli" rows="3" name="motivo"></textarea>
					</div>
				</div>

				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Descripcion: </label>
					<div class="controls">
						<textarea class="form-control" id="descripcion" rows="3" name="descripcion"></textarea>
					</div>
				</div>				

				<div class="control-group" style="margin-button:15px">
					<!--<label class="control-label">Fecha de la Felicitacion: </label>-->
					<div class="controls">
					<input type="hidden" class="form-control" class="form-control" id="fecha_felicitacion" name="fecha_felicitacion">
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
      datos=datos+'&accion=btn_felicitacion';

      //console.log(datos);  

      $.ajax({
          type: 'POST',
          url: "?/principal/procesos",
          //url: "?/d-curso-asignados/procesos",
          data: datos,
          success: function (resp) {
            console.log(resp);
            switch(resp){
              case '1': //dataTable.ajax.reload();
                        listarEstudiantesKardex();
                        $("#modal_felicitacion").modal("hide");
                        alertify.success('Se registro la felicitacion correctamente');
                        break;
              case '2': //dataTable.ajax.reload();
                        $("#modal_felicitacion").modal("hide");
                        alertify.warning('No se registro la felicitacion'); 
                        break;
             case '3': //dataTable.ajax.reload();
                        $("#modal_felicitacion").modal("hide");
                        alertify.success('Edicion correcta');
                        verEstCardex(idest);
                        break;
            }
            
          }          
      });    
  }

});
$('#fecha_felicitacion').datepicker({
    timepicker: true,
    language: 'es',
    position:'top left',
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
<!--_____________________________________________________________________-->
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
 <!--_____________________________________________________________________-->
 <form id="form_sancion">
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
					<label class="control-label">Motivo de la Suspencion: </label>
					<div class="controls">
						<input id="motivo" name="motivo" type="text" class="form-control">
					</div>
                </div>
                
                <div class="control-group" style="margin-button:15px">
					<label class="control-label">Dias de Suspencion: </label>
					<div class="controls">
						<input id="dias" name="dias" type="number" class="form-control">
					</div>
                </div>
                
                <div class="form-group" style="margin-button:15px">
                    <label for="inputText4" class="col-form-label">Con presencia del tutor: </label><br>
                             
                    <label class="custom-control custom-radio custom-control-inline">
                    <input type="radio" name="traertutor" class="custom-control-input" id="contutor" value="1" ><span class="custom-control-label" >Con presencia del Tutor</span>
                    </label>
                    
                    <label class="custom-control custom-radio custom-control-inline">
                    <input type="radio" name="traertutor" checked="" class="custom-control-input" id="sintutor" value="0"><span class="custom-control-label" value="0">Sin Tutor</span>
                    </label>
                </div>
                <div class="control-group" style="margin-button:15px">
					<label class="control-label">Fecha a Asistir:</label>
					<div class="controls">
						<input id="fecha_asistir" name="fecha_asistir" type="text" class="form-control">
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
      datos=datos+'&accion=btn_sancion';

      console.log(datos);  

      $.ajax({
          type: 'POST',
          url: "?/principal/procesos",
          data: datos,
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
    position:'top left',
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