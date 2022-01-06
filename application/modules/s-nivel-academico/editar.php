<?php
  $csrf = set_csrf(); 
  
?>
<form id="form_nivel">
<div class="modal fade" id="modal_nivel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Nivel Académico</h5>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
			</div>
			<div class="modal-body">
				<div class="control-group">
					<label class="control-label">Nombre Nivel: </label>
					<div class="controls">
                        <input type="hidden" name="<?= $csrf; ?>">
                        <input id="id_nivel" name="id_nivel" type="hidden" class="form-control">						
						<input id="nombre_nivel" name="nombre_nivel" type="text" class="form-control">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Acrñonimo: </label>
					<input type="text" id="acronimo_nivel" name="acronimo_nivel" class="form-control">
				</div>
				<div class="control-group">
					<label class="control-label">Descripción: </label>
					<div class="controls">
                       <textarea id="descripcion_nivel" name="descripcion_nivel" type="text" class="form-control"></textarea>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Tipo calificación: </label>
					<select id="tipo_calificacion" name="tipo_calificacion" class="form-control">
					    <option value="CUALITATIVO">CUALITATIVO</option>
					    <option value="CUANTITATIVO">CUANTITATIVO</option>
					</select>
				</div>
				<div class="control-group">
					<label class="control-label">Color: </label>
					<input type="color" id="color" name="color" class="form-control">
				</div>
				<div class="control-group">
					<label class="control-label">Orden: </label>
					<input type="number" id="orden_nivel" name="orden_nivel" class="form-control">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary pull-right" id="btn_nuevo">Registrar</button>
				<button type="submit" class="btn btn-primary pull-right" id="btn_modificar">Editar</button>
			</div>
		</div>
	</div>
</div>
</form>

<script>
$(function () {
    $("#form_nivel").validate({
      rules: {
        nombre_nivel: {required: true},
      },
      errorClass: "help-inline",
      errorElement: "span",
      highlight: highlight,
      unhighlight: unhighlight,
      messages: {
          nombre_nivel: "Debe ingresar un nombre nivel"
      },
      //una ves validado guardamos los datos en la DB
      submitHandler: function(form){
          var datos = $("#form_nivel").serialize();
          //alert(datos);
          $.ajax({
              type: 'POST',
              url: "?/s-nivel-academico/guardar",
              data: datos,
              success: function (resp) {
                cont=0;
                switch(resp){
                  case '1': dataTable.ajax.reload();
                            $("#modal_nivel").modal("hide"); 
                            alertify.success('Se registro el nivel académico correctamente');
                            break;
                  case '2': dataTable.ajax.reload();
                            $("#modal_nivel").modal("hide"); 
                            alertify.success('Se editó el nivel académico correctamente');
                            break;
                }
                //pruebaa();
              }
             
          });
         
      }
    })
  })

    // function pruebaa(){
    //   //console.log($("#table_filter >label >input").val());
    //   alert("hola");
    // }
  //window.onload = prueba();
</script>