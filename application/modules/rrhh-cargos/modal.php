<?php
  $csrf = set_csrf();
?> 
<style>
.floatt{
    float:left;
    padding: 0px;
}
.clearr{
    clear: both;
}
</style>

<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">
<link href="<?= css; ?>/bootstrap-datetimepicker.min.css" rel="stylesheet">

<form id="form_contrato">
<div class="modal fade" id="modal_contrato" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><span id="titulo_persona"></span> </h5>
        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </a>
    </div>
    <div class="modal-body">
        <input type="hidden" name="5a5fdf19883c04158d3d9efa0ae989a42eece847">

        <input type="hidden" value="0" name="id_cargo" id="id_cargo">

        <div class="form-row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-2">
                <label class="control-label"><font style="color:red">*</font> Cargo: </label>
                <input type="text" value="" name="cargo" id="cargo" class="form-control" autofocus="autofocus" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max100">
            </div>
        </div>
        <div class="form-row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-2">
                <label class="control-label">Obligación: </label>
                <textarea name="obligacion" id="obligacion" class="form-control" data-validation="required letternumber" data-validation-allowing="-+/.,:;@#&'()_\n "></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-2">
                <label class="control-label">Descripción: </label>
                <textarea name="descripcion" id="descripcion" class="form-control" data-validation="letternumber" data-validation-allowing="-+/.,:;@#&'()_\n " data-validation-optional="true"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary pull-right" id="btn_persona_nuevo">Guardar</button>
    </div>
</div>
</div>
</div>
</form>

<style>
    .margen {
        margin-top: 15px;
    }
    #nivel_academico{
        padding: 0; !important;
        height: 0; !important;
        width: 0; !important;
    }
    #materias{
        padding: 0; !important;
        height: 0; !important;
        width: 0; !important;
    }
</style>

<script src="<?= js; ?>/jquery.maskedinput.min.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/js/jquery.validate.js"></script>
<script src="application/modules/generador-menus/generador-menu.js"></script>
<script src="assets/themes/concept/assets/vendor/multi-select/js/jquery.multi-select.js"></script>

<script src="assets/themes/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="assets/themes/concept/assets/vendor/datepicker/js/datepicker.js"></script>
<script src="assets/themes/concept/assets/vendor/datepicker/js/datepicker.es.js"></script>

<script>
var $materias = $('#materias');

$(function () {    
        
    $("#form_contrato").validate({
        rules: {
            //ruta: {required: true}
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
                url: "?/rrhh-cargos/guardar",
                data: datos,
                success: function (resp) {
     
                    cont = 0;
                    switch(resp){
                        case '2': 
                                  alertify.success('Se registro la gestión escolar correctamente');
                                  location.href = "?/rrhh-cargos/listar";  
                                  break;
                        case '1':
                                  alertify.success('Se registro la gestión escolar correctamente');
                                  location.href = "?/rrhh-cargos/listar";  
                                  break;
                    }
                }
            });
        }
    })
})
</script>