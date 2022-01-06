<?php
  $csrf = set_csrf();
?> 

<form id="form_gestion">
<div class="modal fade" id="modal_gestion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span id="titulo_gestion"></span> Gestión Escolar</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            

            <div class="modal-body">            
                <input type="hidden" name="<?= $csrf; ?>">
                <input type="hidden" value="0" name="id_gondola" id="id_gondola">
                <div class="form-group">
                    <label for="ruta" class="control-label">Nombre:</label>
                    <input type="text" value="" name="ruta" id="ruta" class="form-control" autofocus="autofocus" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max50">
                </div>
                <div class="form-group">
                    <label for="descripcion" class="control-label">descripcion:</label>
                    <textarea name="descripcion" id="descripcion" cols="30" rows="2" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="capacidad" class="control-label">Capacidad:</label>
                    <input type="text" value="" name="capacidad" id="capacidad" class="form-control" data-validation="required number">
                </div>
                <div class="form-group">
                    <label for="placa" class="control-label">Placa:</label>
                    <input type="text" value="" name="placa" id="placa" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max50">
                </div>
                <div class="form-group">
                    <label for="tipo_gondola" class="control-label">Tipo gondola:</label>
                    <select name="tipo_gondola" id="tipo_gondola" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./& " data-validation-optional="true">
                        <option value="" selected="selected">Buscar</option>
                        <?php foreach ($gondolas as $gondola) { ?>
                            <option value="<?= escape($gondola['tipo_gondola']); ?>"><?= escape($gondola['tipo_gondola']); ?></option>
                        <?php } ?>
                    </select>
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

    $("#form_gestion").validate({
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
            var datos = $("#form_gestion").serialize();
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