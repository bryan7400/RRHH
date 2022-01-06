<?php
$csrf = set_csrf();
//var_dump($niveles_academicos);
?>
<form id="form_materia">
  <div class="modal fade" id="modal_materia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><span id="titulo_gestion"></span> Documento</h5>
          <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </a>
        </div>
        <div class="modal-body">
          <div class="control-group">
            <label class="control-label">Nombre Documento: </label>
            <div class="controls">
              <input type="hidden" name="<?= $csrf; ?>">
              <input id="id_materia" name="id_materia" type="hidden" class="form-control">
              <input id="nombre_materia" name="nombre_materia" type="text" class="form-control">
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Descripción: </label>
            <div class="controls">
              <input id="descripcion" name="descripcion" type="text" class="form-control">
            </div>
          </div>
         <!-- <label class="control-label">Niveles Academicos: </label>
          <div class="custom-control custom-checkbox">
            <?php //foreach ($niveles_academicos as $value) : ?>
              <div class="col-12 col-sm-6 col-lg-6 pt-1">
                <input type="checkbox" class="custom-control-input" id="<?//= $value['id_nivel_academico']; ?>" name="nivel_academico[<?//= $value['id_nivel_academico']; ?>]" value="<?//= $value['id_nivel_academico']; ?>">
                <label class="custom-control-label" for="<?//= $value['id_nivel_academico']; ?>"> <?//= $value['nombre_nivel']; ?></label>
              </div>
            <?php // endforeach ?>
          </div>-->

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
        required: false
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
        url: "?/s-documentos/guardar",
        data: datos,
        success: function(resp) {
          cont = 0;
          console.log(resp);
          switch (resp) {
            case '2':
              dataTable.ajax.reload();
              $("#modal_materia").modal("hide");
              alertify.success('Se registro el materia correctamente');
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