<?php
  $csrf = set_csrf(); 
?>
<form id="form_curso">
<div class="modal fade" id="modal_curso" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="titulo_curso"></span> Grado </h5>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
			</div>
			<div class="modal-body">
				
				<div class="">
					<label class="control-label">Nombre: </label>
					<div class="control-group">
                        <input type="hidden" name="<?= $csrf; ?>">
                        <input id="id_aula" name="id_aula" type="hidden" class="form-control">						
						<input id="nombre_aula" name="nombre_aula" type="text" class="form-control">
					</div>
				</div>
				
				<div class="" style="margin-top:3%">
					<label class="control-label">Descripción: </label>
					<div class="control-group">
						<input id="descripcion" name="descripcion" type="text" class="form-control">
					</div>
				</div>
				
				<div class="" style="margin-top:3%">
					<label class="control-label">Nivel Académico: </label>
					<div class="">
                        <select name="nivel_academico" id="nivel_academico" class="form-control">
                          <option value="" selected="selected">Seleccionar</option>
                          <?php foreach ($nivel_academico as $value) : ?>
                            <option value="<?= $value['id_nivel_academico']; ?>"><?= escape($value['nombre_nivel']); ?></option>
                          <?php endforeach ?>
                        </select>
					</div>
				</div>
        
				<div class="" style="margin-top:3%">
					<label class="control-label">Orden: </label>
					<div class="control-group">
						<input id="orden" name="orden" type="text" class="form-control" value="0">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary pull-right" id="btn_nuevo">Guardar</button>
				<button type="submit" class="btn btn-primary pull-right" id="btn_editar">Editar</button>
			</div>
		</div>
	</div>
</div>
</form>

<script>

$("#form_curso").validate({
  rules: {
      nombre_aula: {required: true},
      nivel_academico: {required: true}
  },
  errorClass: "help-inline",
  errorElement: "span",
  highlight: highlight,
  unhighlight: unhighlight,
  messages: {
      nombre_aula: "Debe ingresar nombre de curso.",
      nivel_academico: "Debe seleccionar un nivel académico"
  },
  //una ves validado guardamos los datos en la DB
  submitHandler: function(form){
      //alert();
      var datos = $("#form_curso").serialize();
      $.ajax({
          type: 'POST',
          url: "?/s-curso/guardar",
          data: datos,
          success: function (resp) {
            cont = 0;
            switch(resp){
              case '1': dataTable.ajax.reload();
                        $("#modal_curso").modal("hide");
                        alertify.success('Se registro el curso correctamente');
                        break;
              case '2': dataTable.ajax.reload();
                        $("#modal_curso").modal("hide");
                        alertify.success('Se editó el curso correctamente'); 
                        break;
            }
            //pruebaa();
          }
          
      });
      
  }
})

</script>