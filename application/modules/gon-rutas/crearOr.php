<?php
    $csrf = set_csrf();
?> 

<form id="form_gestion">
<div class="modal fade" id="modal_gestion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span id="titulo_gestion"></span>Rutas </h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            
            <div class="modal-body">            
                <div class="control-group">
                    <label class="control-label">Nombre de Ruta: </label>
                    <div class="controls">
                        <input type="hidden" name="<?= $csrf; ?>">
                        <input id="id_gestion2" name="id_gestion2" type="hidden" class="form-control">                        
                        <input id="nombre2" name="nombre2" type="text" class="form-control">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Descripción: </label>
                    <div class="controls">
                        <input id="descripcion2" name="descripcion2" type="text" class="form-control">
                    </div>
                </div>
                <div id="map" style="min-height: 5em;background: rgb(240 255 255);"></div>
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

<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/bootstrap-datetimepicker.min.js"></script>

<script>

$(function(){
    $("#form_gestion").validate({
        rules: {
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: highlight,
        unhighlight: unhighlight,
        messages: {
        },
        //una ves validado guardamos los datos en la DB
        submitHandler: function(form){
            //alert();
            var datos = $("#form_gestion").serialize();
            $.ajax({
                type: 'POST',
                url: "?/gon-rutas/guardar",
                data: datos,
                success: function (resp) {
                    console.log(resp);
                    cont = 0;
                    switch(resp){
                        case '2': //dataTable.ajax.reload();
                                  $("#modal_gestion").modal("hide");
                                  location.href = "?/gon-rutas/listar";  
                                  break;
                        case '1':
                                  //dataTable.ajax.reload();
                                  $("#modal_gestion").modal("hide");
                                  location.href = "?/gon-rutas/listar";  
                                  break;
                    } 
                }          
            });        
        }
    })
})
</script>