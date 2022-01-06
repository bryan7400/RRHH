<?php
  $csrf = set_csrf();

?>
<form id="form_concepto_pago">
<div class="modal fade" id="modal_concepto_pago" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="titulo_concepto_pago"></span>Pensión</h5>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
			</div>
			<div class="modal-body">  
				<div class="control-group">
					<label class="control-label">Concepto de Pago: </label>
					<div class="controls">
            <input id="id_concepto_pago" name="id_concepto_pago" type="hidden" class="form-control">
						<input id="nombre_concepto_pago" name="nombre_concepto_pago" type="text" class="form-control">
					</div>
				</div>
				<div class="control-group margen">
					<label class="control-label">Porcentaje: </label>
					<div class="controls">
						<input id="porcentaje" name="porcentaje" type="text" class="form-control">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary pull-right" id="btn_nuevo">Registrar</button>
				<button type="submit" class="btn btn-primary pull-right" id="btn_editar">Editar</button>
			</div>
		</div>
	</div>
</div>
</form>
<style>
.margen {
  margin-top: 15px;
}
</style>
<script>

$("#form_concepto_pago").validate({
  rules: {
      //id_pensiones: {required: true},
      nombre_concepto_pago: {required: true},
      porcentaje: {required: true}
  },
  errorClass: "help-inline",  
  errorElement: "span",
  highlight: highlight,
  unhighlight: unhighlight,
  messages: {
    nombre_concepto_pago: "Debe ingresar el concepto de pago.",
    porcentaje: "Debe ingresar el porcentaje"
  },
  //una ves validado guardamos los datos en la DB
  submitHandler: function(form){
      //alert();
      var datos = $("#form_concepto_pago").serialize();
      $.ajax({
          type: 'POST',
          url: "?/rrhh-conceptos-pago/guardar",
          data: datos,
          success: function (resp) {
            cont = 0;
            console.log(resp);
            switch(resp){
              case '2': dataTable.ajax.reload();
                        $("#modal_concepto_pago").modal("hide");
                        alertify.success('Se registro el concepto de pago correctamente');
                        break;
              case '1': dataTable.ajax.reload();
                        $("#modal_concepto_pago").modal("hide");
                        alertify.success('Se editó el concepto de pago correctamente'); break;
            }
            //pruebaa();
          }
          
      });
      
  }
})

</script>