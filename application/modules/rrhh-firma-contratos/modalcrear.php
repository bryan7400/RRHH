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

<form id="form_crear">
<div class="modal fade" id="modal_crear" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span id="titulo_contrato"></span>Firma de Contrato</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            
            <div class="modal-body">            
                <input type="hidden" name="<?= $csrf; ?>">
                <input type="hidden" value="0" name="id_contrato" id="id_contrato">
                
                <div class="form-group">
                    <div class="col-sm-4 floatt">
                    <label for="tipo_gondola" class="control-label">Personal:</label>
                    </div>
                    <div class="col-sm-8 floatt">
                    <select name="id_postulante" id="id_postulante" class="form-control text-uppercase" data-validation="letternumber required" onchange="actualizar();">
                        <option value="" selected="selected">Buscar</option>
                        <?php 
                        $gondolas = $db->query('SELECT z.*, p.*, c.*
                                                FROM per_asignaciones z
                                                JOIN sys_persona p ON id_persona=persona_id
                                                JOIN per_cargos c ON id_cargo=cargo_id
                                                ORDER BY primer_apellido, segundo_apellido, nombres ASC')
                                        ->fetch();

                        foreach ($gondolas as $gondola) { ?>
                            <option value="<?= escape($gondola['id_persona'])."|".$gondola['primer_apellido']." ".$gondola['segundo_apellido']." ".$gondola['nombres']."|".$gondola['numero_documento']."|".$gondola['cargo']; ?>"><?php echo $gondola['primer_apellido']." ".$gondola['segundo_apellido']." ".$gondola['nombres']; ?></option>
                        <?php } ?>
                    </select>
                    </div>
                    <div class="clearr"></div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 floatt">
                        <label for="tipo_gondola" class="control-label">Nombre y Apellido:</label>
                    </div>
                    <div class="col-sm-8 floatt">
                        <input type="hidden" value="" name="id_asignacion" id="id_asignacion" class="form-control" autofocus="autofocus" data-validation="required" data-validation-length="max50">
                        <input type="text" value="" name="nombre" id="nombre" class="form-control" autofocus="autofocus" data-validation="required" data-validation-length="max50" onchange="Limpiar();">
                    </div>
                    <div class="clearr"></div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 floatt">
                        <label for="tipo_gondola" class="control-label">CI:</label>
                    </div>
                    <div class="col-sm-8 floatt">
                        <input type="text" value="" name="ci" id="ci" class="form-control" autofocus="autofocus" data-validation="required" data-validation-length="max50">
                    </div>
                    <div class="clearr"></div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 floatt">
                        <label for="tipo_gondola" class="control-label">Cargo:</label>
                    </div>
                    <div class="col-sm-8 floatt">
                        <input type="text" value="" name="cargo" id="cargo" class="form-control" autofocus="autofocus" data-validation="required" data-validation-length="max50">
                    </div>
                    <div class="clearr"></div>
                </div>
                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary pull-right" id="btn_contrato_nuevo">Guardar</button>
            </div>

        </div>
    </div>
</div>
</form>



<?php 
//    $hoy = getdate();
//    echo $hoy['wday'];
?>


<style>
.margen {
    margin-top: 15px;
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

$(function () {    
    $("#form_crear").validate({
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
            var datos = $("#form_crear").serialize();
            $.ajax({
                type: 'POST',
                url: "?/rrhh-firma-contratos/guardar",
                data: datos,
                success: function (resp) {
                    switch(resp){
                        case '1':
                                  alertify.success('Se registro a la persona que firmara los contratos');
                                  location.href = "?/rrhh-firma-contratos/listar";  
                                  break;
                    }
                }
            });
        }
    })
})

function actualizar(){
    x=$("#id_postulante").val();
    x=x.split("|");
    $("#id_asignacion").val(x[0]);
    $("#nombre").val(x[1]);
    $("#ci").val(x[2]);
    $("#cargo").val(x[3]);
}
function Limpiar(){
    $("#id_asignacion").val('0');
}
</script>