<?php
  $csrf = set_csrf(); 
?>
<form id="form_area"> 
<div class="modal fade" id="modal_area" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Área de Calificación</h5>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
			</div>
			<div class="modal-body">
				<div class="control-group">
					<label class="control-label">Nombre Área: </label>
					<div class="controls">
                        <input type="hidden" name="<?= $csrf; ?>">
                        <input id="id_area" name="id_area" type="hidden" class="form-control">
                        <input id="descripcion_area" name="descripcion_area" type="text" class="form-control">						
					</div>
				</div>
				
				<div class="form-group">
                    <label for="obtencion_nota" class="control-label">Seleccione turno:<span id="turno_img">ok</span></label>
                    <select required name="obtencion_nota" id="obtencion_nota" class="form-control">
                        <option value="E" selected="selected">SERA REVISADO CUANDO EL ESTUDIANTE ENVIE UNA RESPUESTA</option>
                        <option value="D" >REVISADO POR SOLO DOCENTE</option>
                        <option value="SE" >EL ESTUDIANTE SE AUTOCALIFICA </option>
                    </select>
                </div>
				
				<div class="control-group">
					<label class="control-label">Ponderado: </label>
					<div class="controls">
              <input id="ponderado_area" name="ponderado_area" type="text" class="form-control">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary pull-right" id="btn_nuevo">Registrar</button>
				<button type="submit" class="btn btn-primary pull-right" id="btn_editar">Editar</button>
			</div>
		</div>
	</div>
</div>
</form>

<script>
$(function () {
    $("#form_area").validate({
      rules: {
        descripcion_area: {required: true},
        obtencion_nota: {required: true},
        ponderado_area: {required: true}
      },
      errorClass: "help-inline",
      errorElement: "span",
      highlight: highlight,
      unhighlight: unhighlight,
      messages: {
        descripcion_area: "Debe ingresar un nombre área",
        ponderado_area: "Debe ingresar un ponderado"
      },
      //una ves validado guardamos los datos en la DB
      submitHandler: function(form){
          var datos = $("#form_area").serialize();
          $.ajax({
              type: 'POST',
              url: "?/s-area-calificacion/guardar",
              data: datos,
              success: function (resp) {
                console.log(resp); 
                cont=0;
                switch(resp){
                  case '1':
                            dataTable.ajax.reload();
                            $("#modal_area").modal("hide");
                            alertify.success('Se registro el área de calificación correctamente');
                            break;
                  case '2': dataTable.ajax.reload();
                            $("#modal_area").modal("hide"); 
                            alertify.success('Se editó el área de calificación correctamente'); 
                            break;
                }
              }
          });
      }
    })
  })
</script>