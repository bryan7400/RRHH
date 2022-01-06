<?php
  $csrf = set_csrf();   
?>
<form id="form_modo">
<div class="modal fade" id="modal_modo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Modo de Calificación</h5>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
			</div>
			<div class="modal-body">
				<div class="control-group">
					<label class="control-label">Modo de Calificación: </label>
					<div class="controls">
            <input type="hidden" name="<?= $csrf; ?>">
            <input id="id_modo" name="id_modo" type="hidden" class="form-control">
            <input id="descripcion_modo" name="descripcion_modo" type="text" class="form-control">						
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Fecha Inicio: </label>
					<div class="controls">
            <input id="fecha_inicio" name="fecha_inicio" type="date" class="form-control" onchange="comparafechas()">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Fecha Final: </label>
					<div class="controls">
            <input id="fecha_final" name="fecha_final" type="date" class="form-control" onchange="comparafechas()">
					</div>
				</div>
			<p class="msgFechas" style="color:red"></p>
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
//$(function () {
    $("#form_modo").validate({
      rules: {
        descripcion_modo: {required: true},
        fecha_inicio: {required: true},
        fecha_final: {required: true}
      },
      errorClass: "help-inline",
      errorElement: "span",
      highlight: highlight,
      unhighlight: unhighlight,
      messages: {
        descripcion_modo: "Debe ingresar modo de calificación",
        fecha_inicio: "Debe ingresar fecha de inicio",
        fecha_final: "Debe ingresar fecha final"
      },
      //una ves validado guardamos los datos en la DB
      submitHandler: function(form){
          var fechas=comparafechas();
          if(fechas){
             var datos = $("#form_modo").serialize();
          $.ajax({
              type: 'POST',
              url: "?/s-modo-calificacion/guardar",
              data: datos,
              success: function (resp) {
                console.log(resp);
                cont=0;
                switch(resp){
                  case '1': dataTable.ajax.reload();
                            $("#modal_modo").modal("hide");
                            $("#modal_area").modal("hide");
                            alertify.success('Se editó el área de calificación correctamente'); 
                            break;
                  case '2': dataTable.ajax.reload();
                            $("#modal_modo").modal("hide");
                            alertify.success('Se registro el área de calificación correctamente');
                            break;
                }
              }
          });
             }else{
              alertify.error('Revise las fechas');
             }
          return false;
      }
    });
    
 // });
    function comparafechas(){
        var fechaini= $("#fecha_inicio").val();
         var fechafin= $("#fecha_final").val();
          if(fechafin>fechaini){ 
              $('.msgFechas').text(''); return true;
             }else{ 
              $('.msgFechas').text('Las fecha final deve ser mayor al inicio');    return false;
             } 
    }
</script>