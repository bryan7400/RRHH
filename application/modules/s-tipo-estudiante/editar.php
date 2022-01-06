<?php
  $csrf = set_csrf();  
  
?>
<form id="form_tipo">
<div class="modal fade" id="modal_tipo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Tipo Estudiante</h5>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
			</div>
			<div class="modal-body">
				<div class="control-group">
					<label class="control-label">Nombre tipo estudiante: </label>
					<div class="controls">
                        <input type="hidden" name="<?= $csrf; ?>">
                        <input id="id_tipo" name="id_tipo" type="hidden" class="form-control">						
						<input id="nombre_tipo" name="nombre_tipo" type="text" class="form-control">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Descripción: </label>
					<div class="controls">
                       <textarea id="descripcion_tipo" name="descripcion_tipo" type="text" class="form-control"></textarea>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Descuento beca (Bs): </label>
					<div class="controls">						
						<input id="monto_beca" name="monto_beca" type="text" value="0" class="form-control">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Descuento beca (%): </label>
					<div class="controls">						
						<input id="descuento" name="descuento" type="text"  value="0" class="form-control">
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
    $("#form_tipo").validate({
      rules: {
        nombre_tipo: {required: true},
      },
      errorClass: "help-inline",
      errorElement: "span",
      highlight: highlight,
      unhighlight: unhighlight,
      messages: {
          nombre_tipo: "Debe ingresar un nombre tipo estudiante"
      },
      //una ves validado guardamos los datos en la DB
      submitHandler: function(form){
          var datos = $("#form_tipo").serialize();
          $.ajax({
              type: 'POST',
              url: "?/s-tipo-estudiante/guardar",
              data: datos,
              success: function (resp) {
                console.log(resp);
                cont=0;
                switch(resp){
                  case '1': 
                            dataTable.ajax.reload();
                            $("#modal_tipo").modal("hide");
                            alertify.success('Se registro el tipo de estudiante correctamente');
                            break;
                  case '2': dataTable.ajax.reload();
                            $("#modal_tipo").modal("hide"); 
                            alertify.success('Se editó el tipo de estudiante correctamente');                            
                            break;
                }
              }
          });
      }
    })
})
</script>