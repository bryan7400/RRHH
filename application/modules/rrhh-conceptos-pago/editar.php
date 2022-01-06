<?php
  $csrf = set_csrf();
?>
<form id="form_concepto_pago">
<div class="modal fade" id="modal_concepto_pago" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document" >
		<div class="modal-content">
			<div class="modal-header" style="background-color:#2ec551">
				<h5 class="modal-title" id="exampleModalLabel"><font color="#fff"><span id="titulo_concepto_pago"></span>Concepto de pago</font></h5>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
			</div>
			<div class="modal-body">  
				<div class="control-group">
					<label class="control-label"><font style="color:red">*</font> Concepto de Pago: </label>
					<div class="controls">
            <input id="id_concepto_pago" name="id_concepto_pago" type="hidden" class="form-control">
            <input id="tipo" name="tipo" type="hidden" value="BONO" class="form-control">
						<input id="nombre_concepto_pago" name="nombre_concepto_pago" type="text" class="form-control">
					</div>
				</div>
				<div class="control-group margen">
					<label class="control-label">Descripción: </label>
					<div class="controls">
						<textarea id="descripcion" name="descripcion" type="text" class="form-control"></textarea> 
					</div>
				</div>
        <div class="control-group margen">
          <label class="control-label">Mes: </label>
          <div class="controls">
           <!--  <input id="mes" name="mes" type="text" class="form-control"> -->
            <select id="mes" name="mes" type="text" class="form-control">
              <option value="">Seleccionar</option>
              <option value="1">ENERO</option>
              <option value="2">FEBRERO</option>
              <option value="3">MARZO</option>
              <option value="4">ABRIL</option>
              <option value="5">MAYO</option>
              <option value="6">JUNIO</option>
              <option value="7">JULIO</option>
              <option value="8">AGOSTO</option>
              <option value="9">SEPTIEMBRE</option>
              <option value="10">OCTUBRE</option>
              <option value="11">NOVIEMBRE</option>
              <option value="12">DICIEMBRE</option>
            </select>
          </div>
        </div>
        

        <div class="control-group margen">
            <label class="control-label"><font style="color:red">*</font> Descuento por: </label>
            <div class="controls">
                <!--  <input id="mes" name="mes" type="text" class="form-control"> -->
                <select id="tipo_fijo_porcentaje" name="tipo_fijo_porcentaje" type="text" class="form-control" onchange="TipoBono();">
                    <option value="fijo">Monto Fijo</option>
                    <option value="porcentaje">Porcentaje</option>
                </select>
            </div>
        </div>
        <div class="control-group margen porcentaje">
            <label class="control-label">Porcentaje: </label>
            <div class="controls">
                <input id="porcentajex" name="porcentajex" type="text" value="0" class="form-control">
            </div>
        </div>
        <div class="control-group margen monto">
            <label class="control-label">Monto: </label>
            <div class="controls">
                <input id="montox" name="montox" type="text" value="0" class="form-control">
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
            $("#modal_concepto_pago").modal("hide");
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

function TipoBono(){
    if($("#tipo_fijo_porcentaje").val()=="fijo"){
        $(".porcentaje").css({'display':'none'});
        $(".monto").css({'display':'block'});
        $("#porcentajex").val('0');        
    }else{
        $(".porcentaje").css({'display':'block'});
        $(".monto").css({'display':'none'});
        $("#montox").val('0');
    }
}
</script>