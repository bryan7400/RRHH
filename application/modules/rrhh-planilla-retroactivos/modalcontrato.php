<?php
  $csrf = set_csrf();
?> 

<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">

<form id="form_contrato" action="?/rrhh-planilla-retroactivos/guardar-contrato" method="post">
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
                    <label class="control-label">Incremento al Basico %:</label>
                    
                    <input type="text" value="" name="incremento1" id="incremento1" class="form-control" autofocus="autofocus" data-validation="required number length" data-validation-allowing="-/.#() " data-validation-length="max50" onchange="verificar1();" style="text-align: right;">                
                </div>

                <div class="form-group">
                    <label class="control-label">Incremento a los que reciben mas del Basico %:</label>
                    
                    <input type="text" value="" name="incremento2" id="incremento2" class="form-control" autofocus="autofocus" data-validation="required number length" data-validation-allowing="-/.#() " data-validation-length="max50" onchange="verificar1();" style="text-align: right;">                
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
function verificar1(){
    $('#incremento1').
}
</script>