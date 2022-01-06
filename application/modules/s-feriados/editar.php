<?php
  $csrf = set_csrf();
?>
<form id="form_feriado">
<div class="modal fade" id="modal_feriado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="titulo_feriado"></span> Días Feriados</h5>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
			</div>
			<div class="modal-body">
				<div class="control-group">
					<label class="control-label">Fecha de Inicio: </label>
					<div class="controls">
            <input type="hidden" name="<?= $csrf; ?>">
            
            <input id="id_feriado" name="id_feriado" type="hidden" class="form-control">						
						
            <input id="fecha_inicio" name="fecha_inicio" type="date" class="form-control">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Fecha a Terminar: </label>
					<div class="controls">
						<input id="fecha_final" name="fecha_final" type="date" class="form-control">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Descripción: </label>
					<div class="controls">
						<input id="descripcion_feriado" name="descripcion_feriado" type="text" class="form-control">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-rounded btn-light pull-left" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-rounded btn-primary pull-right" id="btn_nuevo">Guardar</button>
				<button type="submit" class="btn btn-rounded btn-primary pull-right" id="btn_editar">Editar</button>
			</div>
		</div>
	</div>
</div>
</form>

<script>

$("#form_feriado").validate({
  rules: {
      fecha_inicio: {required: true},
      fecha_final: {required: true},
      descripcion_feriado: {required: true}
      //id_feriado: {required: true}
  },
  errorClass: "help-inline", 
  errorElement: "span",
  highlight: highlight,
  unhighlight: unhighlight,
  messages: {
    fecha_inicio: "Debe seleccionar la fecha de inicio.",
    fecha_final: "Debe seleccionar la fecha a terminar.",
    descripcion_feriado: "Debe ingresar una descripción"
  },
  //una ves validado guardamos los datos en la DB
  submitHandler: function(form){
      //alert();
      var datos = $("#form_feriado").serialize();
      $.ajax({
          type: 'POST',
          url: "?/s-feriados/guardar",
          data: datos,
          success: function (resp) {
            console.log(resp);
            switch(resp){
              case '2': dataTable.ajax.reload();
                        $("#modal_feriado").modal("hide");
                        alertify.success('Se registro días feriados correctamente');
                        break;
              case '1': dataTable.ajax.reload();
                        $("#modal_feriado").modal("hide");
                        alertify.success('Se editó días feriados correctamente'); break;
            }
            //pruebaa();
          }
          
      });
      
  }
})

</script>