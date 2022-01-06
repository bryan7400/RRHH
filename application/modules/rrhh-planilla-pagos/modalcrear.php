<?php
  $csrf = set_csrf();
?> 

<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">

<form id="form_contrato" action="?/rrhh-planilla-pagos/guardar" method="post">
<div class="modal fade" id="modal_contrato" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span id="titulo_contrato"></span>Contrato</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            
            <div class="modal-body">            
                <div class="form-group">
                    <label class="control-label">Año:</label>
                    <select name="anio" id="anio" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true">
                        <option value="" selected="selected">Buscar</option>
                        <?php 
                        $gondolas = $db->query('SELECT z.*
                                                FROM ins_gestion z
                                                ORDER BY gestion ASC')
                                        ->fetch();

                        foreach ($gondolas as $gondola) { ?>
                            <option value="<?= escape($gondola['gestion']); ?>"><?php echo $gondola['gestion']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">Mes:</label>
                    <select name="mes" id="mes" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true">
                        <option value="" selected="selected">Buscar</option>                        
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary pull-right" id="btn_contrato_nuevo">Guardar</button>
                <!--button type="submit" class="btn btn-primary pull-right" id="btn_contrato_editar">Editar</button-->
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
<script>

/*


$(function () {    
    var $materias = $('#materias');

    $materias.selectize({
        maxOptions: 7,
        onInitialize: function () {
            $materias.show().addClass('selectize-translate');
        },
        onChange: function () {
            $materias.trigger('blur');
        },
        onBlur: function () {
            $materias.trigger('blur');
        }
    });
    
    $('form:first').on('reset', function () {
        $materias.get(0).selectize.clear();
    });

    
    var $nivel_academico = $('#nivel_academico');

    $nivel_academico.selectize({
        maxOptions: 7,
        onInitialize: function () {
            $nivel_academico.show().addClass('selectize-translate');
        },
        onChange: function () {
            $nivel_academico.trigger('blur');
        },
        onBlur: function () {
            $nivel_academico.trigger('blur');
        }
    });
    
    $('form:first').on('reset', function () {
        $nivel_academico.get(0).selectize.clear();
    });

    

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
                url: "?/rrhh-personal/guardar-contrato",
                data: datos,
                success: function (resp) {
                    //console.log("123456");
                    //console.log(resp);
                    
                    cont = 0;
                    switch(resp){
                        case '2': 
                                  alertify.success('Se registro la gestión escolar correctamente');
                                  location.href = "?/rrhh-personal/listar";  
                                  break;
                        case '1':
                                  alertify.success('Se registro la gestión escolar correctamente');
                                  location.href = "?/rrhh-personal/listar";  
                                  break;
                    }
                }
            });
        }
    })
})
*/
</script>