<?php
    $csrf = set_csrf();
?> 

<form id="form_asignacion">
<div class="modal fade" id="modal_asignacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span id="titulo_gestion"></span> Asignacion de Conductor y Gondola</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            
            <div class="modal-body">            
                <div class="control-group">
                    <label class="control-label">Gondola: </label>
                    <div class="controls">
                        
                        <input type="hidden" name="<?= $csrf; ?>">
                        <input type="hidden" name="id_asignacion2" id="id_asignacion2">

                        <select name="id_gondola2" id="id_gondola2" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./& " data-validation-optional="true">
                            <option value="">Buscar</option>
                            <?php 
                            $gondolas =  $db->query("SELECT z.*
                                                    FROM gon_gondolas z    
                                                    ORDER BY z.id_gondola ASC")
                                            ->fetch();




                        
                            /*if($query){
                                /*$db->insert('sys_procesos', array(
                                    'fecha_proceso' => date('Y-m-d'),
                                    'hora_proceso' => date('H:i:s'),
                                    'proceso' => 'u',
                                    'nivel' => 'l',
                                    'detalle' => 'Se modificó el conductor o gondola de la ruta con identificador número ' . $id_ruta . '.',
                                    'direccion' => $_location,
                                    'usuario_id' => $_user['id_user']
                                ));*/

                               /* $gondolas = array(
                                    'conductor_gondola_id' => $query['id_conductor_gondola']
                                );
                                $db->where('id_ruta', $id_ruta)->update('gon_rutas', $gondolas);
                                echo "1";            
                            }*/
 
                            foreach ($gondolas as $nro => $gondola){
                            ?>                                    
                                <option value="<?php echo $gondola['id_gondola']; ?>"><?= escape($gondola['nombre']." - ".$gondola['placa']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Conductor: </label>
                    <div class="controls">
                        
                        <select name="id_conductor2" id="id_conductor2" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./& " data-validation-optional="true">
                            <option value="">Ninguno</option>
                            <?php
                            /*$conductors = $db->select('z.*, a.nombres, a.primer_apellido, a.segundo_apellido')
                                             ->from('gon_conductor z')
                                             ->join('sys_persona a','z.persona_id = a.id_persona')
                                             ->fetch();*/
                         $conductors = $db->query("SELECT z.*, a.nombres, a.primer_apellido, a.segundo_apellido  
                            FROM gon_conductor z 
                            INNER JOIN per_asignaciones asi on asi.id_asignacion = z.asignacion_id
                            INNER JOIN sys_persona a on asi.persona_id = a.id_persona WHERE z.estado='A'")->fetch();
                            
                            foreach ($conductors as $nro => $conductor){                                
                            ?>                
                                <option value="<?php echo escape($conductor['id_conductor']); ?>"><?php echo escape($conductor['nombres'].' '.$conductor['primer_apellido'].' '.$conductor['segundo_apellido']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary pull-right" id="btn_nuevox">Guardar</button>
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

$(function () {
    $("#form_asignacion").validate({
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
            //alert("falla antes");
            var datos = $("#form_asignacion").serialize();
            $.ajax({
                type: 'POST',
                url: "?/gon-rutas/guardar-asignacion",
                data: datos,
                success: function (resp) {
					
                    switch(resp){
                        case '1':
                          //dataTable.ajax.reload();
						  $("#modal_asignacion").modal("hide");
						  location.href = "?/gon-rutas/listar"; 
						  break;
						case '5':
                          //dataTable.ajax.reload();
						  //$("#modal_asignacion").modal("hide");
						  alertify.warning('El conductor ya esta asignado en otra ruta');
						  break;
                    } 
                }
            });
        }
    })
})
</script>