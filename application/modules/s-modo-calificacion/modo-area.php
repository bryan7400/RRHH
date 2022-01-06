 
<?php
$csrf = set_csrf();
?>
<form id="form_area">
  <div class="modal fade" id="modo_modo_area" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><span id="titulo_gestion"></span>Areas de calificación</h5>
          <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </a>
        </div>
        <div class="modal-body p-3">
          <div class="control-group">
            <div class="controls">
              <input type="hidden" name="<?= $csrf; ?>">
              <input name="id_modo_calificacion" id="id_modo_calificacion"  type="hidden" placeholder="id_modo_calificacion" >
              
               <br>
            </div>
          </div>
         <div class="control-group">
            <table class="table table-bordered">
                <thead class="active">
                    <tr>
                        <td>Nro</td>
                        <td>Descripción</td>
                        <td>Ponderado</td>
                        <td>Gestión</td>
                        <td>Seleccione</td>
                    </tr>
                </thead>
                <tbody id="contenedor_area"> 
                </tbody>
            </table>
  				</div>
          <!--<p>* obs <a href="?/s-profesor-materia/listar">ir</a></p>-->
           </div>
           
        <hr>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary pull-right"  >Registrar</button>
          </div>
        </div>
      </div>
    </div>
</form>
<script>
    
    $("form#form_area").validate({
    rules: {
 
      //id_gestion: {required: true}
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
    // id_docente: "Debe seleccionar una asignacion."
    },
    //una ves validado guardamos los datos en la DB
    submitHandler: function(form) {
        //e.preventDefault();
        var datos = $("#form_area").serialize();
        
         //alert(datos);
          $.ajax({
              type: 'POST',
              url: "?/s-modo-calificacion/guardar-modo-area",
              data: datos,
              success: function (resp) {
                 console.log('respacc '+resp);
                switch(resp){
                  case '1':  dataTable.ajax.reload();
                            //$("#modal_modo").modal("hide");
                            $("#modo_modo_area").modal("hide");
                            alertify.success('Se REGISTRO el área de calificación correctamente');
                            break;
                  case '2':  dataTable.ajax.reload(); 
                            $("#modo_modo_area").modal("hide");
                            alertify.success('Se EDITO el área de calificación correctamente'); 
                            break;
                        
                  default:
                        alertify.error('Operacion DESCONOCIDA');
                            break;
                        
                }
              }
          }).fail(function () {
              alert('modo area') ;
                return false;
           
            });
        return false;
      }
    })
</script>