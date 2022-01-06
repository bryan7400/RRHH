<?php
$csrf = set_csrf();
//var_dump($niveles_academicos);
?>
<form id="form_materia">
  <div class="modal fade" id="modal_materia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><span id="titulo_gestion"></span> Campo</h5>
          <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </a>
        </div>
        <div class="modal-body">
         <div class="row">
          <div class="control-group col-12 col-sm-12 col-lg-12">
            <label class="control-label">Campo: </label>
            <div class="controls">
              <input type="hidden" name="<?= $csrf; ?>">
              <input id="id_campo" name="id_campo" type="hidden" class="form-control">
              <input id="nombre_campo" name="nombre_campo" type="text" class="form-control" placeholder="">
            </div>
          </div>
         </div>
         
         <div class="row">
          <div class="control-group col-12 col-sm-12 col-lg-12">
            <label class="control-label">Descripción: </label>
            <div class="controls">
              <input id="descripcion_campo" name="descripcion_campo" type="text" class="form-control">
            </div>
          </div>   
         </div>
         
         <div class="row">
            <div class="control-group col-12 col-sm-12 col-lg-12">
                <label class="control-label">Orden: </label>
                <div class="controls">
                  <!--<input id="color" name="color" type="text" class="form-control">-->
                  <input type="text" id="orden_campo" name="orden_campo" class="form-control" value="">
                </div>
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
 $('#color').minicolors({
        theme: 'bootstrap'
});
    
  $("#form_materia").validate({
    rules: {
      nombre_campo: {
        required: true
      },
      orden_campo: {
        required: true
      }
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
      nombre_campo: "Debe ingresar el nombre de campo.",
      orden_campo: "Debe ingresar un orden de campo."
    },
    //una ves validado guardamos los datos en la DB
    submitHandler: function(form) {
      var datos = $("#form_materia").serialize();
      //alert(datos);
      $.ajax({
        type: 'POST',
        url: "?/s-campo/guardar",
        data: datos,
        success: function(resp) {
          cont = 0;
          console.log(resp);
          switch (resp) {
            case '2':
              dataTable.ajax.reload();
              //listar_materias(); 
              $("#modal_materia").modal("hide");
              alertify.success('Se registro el campo correctamente');
              break;
            case '1':
              dataTable.ajax.reload();
              //listar_materias();   
              $("#modal_materia").modal("hide");
              alertify.success('Se editó el campo correctamente');
              break;
          }
          //pruebaa();
        }

      });

    }
  })
</script>