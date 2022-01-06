<?php
$csrf = set_csrf();
//var_dump($niveles_academicos);
?>
<form id="form_materia">
  <div class="modal fade" id="modal_materia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><span id="titulo_turno"></span> Turno</h5>
          <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </a>
        </div>
        <div class="modal-body">
          <div class="control-group">
            <label class="control-label">Nombre turno: </label>
            <div class="controls">
              <input type="hidden" name="<?= $csrf; ?>">
              <input id="id_turno" name="id_turno" type="hidden" class="form-control">
              <input id="nombre_turno" name="nombre_turno" type="text" class="form-control" required>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Descripción: </label>
            <div class="controls">
              <input id="descripcion" name="descripcion" type="text" class="form-control">
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Hora Inicio: </label>
            <div class="controls">
              <input id="hora_inicio" name="hora_inicio" type="time" class="form-control" required>
            </div>
          </div><div class="control-group">
            <label class="control-label">Hora Final:: </label>
            <div class="controls">
              <input id="hora_final" name="hora_final" type="time" class="form-control" required>
            </div>
          </div>
            
          <div class="control-group">
            <label class="control-label">Orden: </label>
            <div class="controls">
              <input id="orden" name="orden" type="number" class="form-control" required>
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

<script>
  $("#form_materia").validate({
    rules: {
      nombre_materia: {
        required: true
      },
      nivel_academico: {
        required: true
      }
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
      nombre_materia: "Debe ingresar el nombre de documento.",
      descripcion: "Debe ingresar una descripción."
    },
    //una ves validado guardamos los datos en la DB
    submitHandler: function(form) {
      //alert();
      var datos = $("#form_materia").serialize();
      $.ajax({
        type: 'POST',
        url: "?/s-turnos/guardar",
        data: datos,
        success: function(resp) {
          cont = 0;
          console.log(resp);
          switch (resp) {
            case '2':
              dataTable.ajax.reload();
              $("#modal_materia").modal("hide");
              alertify.success('Se registro el turno correctamente');
              break;
            case '1':
              dataTable.ajax.reload();
              $("#modal_materia").modal("hide");
              alertify.success('Se editó el materia correctamente');
              break;
          }
          //pruebaa();
        }

      });

    }
  })
</script>