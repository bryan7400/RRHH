<?php
  $csrf = set_csrf();

  $gondolas = $db->select('*')->from('per_cargos z')->order_by('z.cargo', 'asc')->fetch();
?> 

<form id="form_contrato">
<div class="modal fade" id="modal_contrato" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span id="titulo_gestion"></span>Contrato</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            

            <div class="modal-body">            
                <input type="hidden" name="<?= $csrf; ?>">
                <input type="hidden" value="0" name="id_gondola" id="id_gondola">
                <div class="form-group">
                    <label for="ruta" class="control-label">Nombre:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <label for="ruta" class="control-label" id="nombre_postulante"></label>                    
                </div>
                
                <div class="form-group administrativo">
                    <label for="tipo_gondola" class="control-label">Cargo:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <label for="tipo_gondola" class="control-label" id="cargo_postulante"></label>                    
                </div>

                <div class="form-group docente">
                    <label for="descripcion" class="control-label">Horas Academicas:</label>
                    <textarea name="descripcion" id="descripcion" cols="30" rows="2" class="form-control"></textarea>
                </div>
                <div class="form-group docente">
                    <label for="placa" class="control-label">Materias:</label>
                    <input type="text" value="" name="placa" id="placa" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max50">
                </div>
                
                <div class="form-group">
                    <label for="placa" class="control-label">Sueldo:</label>
                    <input type="text" value="" name="placa" id="placa" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max50">
                </div>
                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary pull-right" id="btn_nuevo">Guardar</button>
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
<link href="assets/themes/concept/assets/vendor/datepicker/css/datepicker.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/bootstrap-fileinput-master/css/fileinput.css" rel="stylesheet">

<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.es.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-fileinput-master/js/fileinput.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-fileinput-master/js/es.js"></script>
<script>
  
$(function () {

    $("#form_contrato").validate({
        rules: {
            ruta: {required: true}
            //id_gestion: {required: true}
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: highlight,
        unhighlight: unhighlight,
        messages: {
            ruta: "Debe ingresar un nombre la gestión"
        },
        //una ves validado guardamos los datos en la DB
        submitHandler: function(form){
            var datos = $("#form_contrato").serialize();
            $.ajax({
                type: 'POST',
                url: "?/gon-gondolas/guardar",
                data: datos,
                success: function (resp) {
                    console.log(resp);
                    cont = 0;
                    switch(resp){
                        case '2': alertify.success('Se registro la gestión escolar correctamente');
                                  location.href = "?/gon-gondolas/listar";  
                                  break;
                        case '1':
                                  location.href = "?/gon-gondolas/listar";  
                                  break;
                    }
                }
            });
        }
    })
})
</script>