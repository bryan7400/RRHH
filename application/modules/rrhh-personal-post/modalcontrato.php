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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span id="titulo_contrato"></span>Contrato</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            
            <div class="modal-body">            
                <input type="hidden" name="<?= $csrf; ?>">
                <input type="hidden" value="0" name="id_contrato" id="id_contrato">
                
                <div class="form-group">
                    <div class="col-sm-4 floatt">
                    <label for="tipo_gondola" class="control-label">Nombre y Apellido:</label>
                    </div>
                    <div class="col-sm-8 floatt">
                    <select name="id_postulante" id="id_postulante" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true">
                        <option value="" selected="selected">Buscar</option>
                        <?php 
                        $gondolas = $db->query('SELECT z.*
                                                FROM sys_persona z
                                                WHERE postulante_id>0 AND id_persona NOT IN(SELECT persona_id FROM per_asignaciones)
                                                ORDER BY primer_apellido, segundo_apellido, nombres ASC')
                                        ->fetch();

                        foreach ($gondolas as $gondola) { ?>
                            <option value="<?= escape($gondola['id_persona']); ?>"><?php echo $gondola['primer_apellido']." ".$gondola['segundo_apellido']." ".$gondola['nombres']; ?></option>
                        <?php } ?>
                    </select>
                    </div>
                    <div class="clearr"></div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 floatt">
                    <label for="tipo_gondola" class="control-label">Tipo:</label>
                    </div>
                    <div class="col-sm-8 floatt">
                        <select name="tipo" id="tipo" class="form-control text-uppercase" data-validation="required" data-validation-optional="true" onchange="setcargo();">
                            <option value="" selected="selected">Buscar</option>
                            <option value="1">Administrativo</option>
                            <option value="2">Docente</option>
                            <option value="3">Docente-Administrativo</option>
                        </select>
                    </div>
                    <div class="clearr"></div>
                </div>

                <div class="form-group ItIs ItIsAdmin ItIsTeacherAdmin">
                    <div class="col-sm-4 floatt">
                    <label for="tipo_gondola" class="control-label">Cargo:</label>
                    </div>
                    <div class="col-sm-8 floatt">
                        <select name="cargo" id="cargo" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true" onchange="setcargo();">
                        <option value="" selected="selected">Buscar</option>
                        <?php 
                        $gondolas = $db->select('z.*')
                                        ->from('per_cargos z')
                                        ->where('z.id_cargo!=', '1') //no se muestra docente!!!
                                        ->order_by('z.cargo', 'asc')
                                        ->fetch();

                        foreach ($gondolas as $gondola) { ?>
                            <option value="<?= escape($gondola['id_cargo']); ?>"><?= escape($gondola['cargo']); ?></option>
                        <?php } ?>
                        </select>
                    </div>
                    <div class="clearr"></div>
                </div>

                <div class="form-group ItIs ItIsTeacher ItIsTeacherAdmin">
                    <div class="col-sm-4 floatt">
                    <label for="tipo_gondola" class="control-label">Nivel Academico:</label>
                    </div>
                    <div class="col-sm-8 floatt">
                    <select name="nivel_academico[]" id="nivel_academico" class="form-control" autofocus="autofocus" multiple="multiple" data-validation="required length" data-validation-allowing="," data-validation-length="max100">
                        <option value="" selected="selected">Buscar</option>
                        <?php 
                        $gondolas = $db->select('z.*')
                                        ->from('ins_nivel_academico z')
                                        ->order_by('z.nombre_nivel', 'asc')
                                        ->fetch();

                        foreach ($gondolas as $gondola) { ?>
                            <option value="<?= escape($gondola['id_nivel_academico']); ?>"><?= escape($gondola['nombre_nivel']); ?></option>
                        <?php } ?>
                    </select>
                    </div>
                    <div class="clearr"></div>
                </div>

                <div class="form-group ItIs ItIsTeacher ItIsTeacherAdmin">
                    <div class="col-sm-4 floatt">
                    <label for="materias" class="control-label">Materia:</label>
                    </div>
                    <div class="col-sm-8 floatt">                    
                    <select name="materias[]" id="materias" class="form-control" autofocus="autofocus" multiple="multiple" data-validation="required length" data-validation-allowing="," data-validation-length="max100">
                        <option value="" selected="selected">Buscar</option>
                        <?php 
                        $gondolas = $db->select('z.*')
                                        ->from('pro_materia z')
                                        ->order_by('z.nombre_materia', 'asc')
                                        ->fetch();

                        foreach ($gondolas as $gondola) { ?>
                            <option value="<?= escape($gondola['id_materia']); ?>"><?= escape($gondola['nombre_materia']); ?></option>
                        <?php } ?>
                    </select>
                    </div>
                    <div class="clearr"></div>
                </div>

                <div class="form-group ItIs ItIsTeacher ItIsTeacherAdmin">
                    <div class="col-sm-4 floatt">
                    <label for="tipo_gondola" class="control-label">Horas Academicas:</label>
                    </div>
                    <div class="col-sm-8 floatt">
                    <input type="text" value="" name="horas" id="horas" class="form-control" autofocus="autofocus" data-validation="required number length" data-validation-allowing="-/.#() " data-validation-length="max50" onchange="CalcularSueldo();" style="text-align: right;">
                    </div>
                    <div class="clearr"></div>
                </div>
                
                <div class="form-group ItIs ItIsTeacher">
                    <div class="col-sm-4 floatt">
                    <label for="tipo_gondola" class="control-label">Sueldo por Hora:</label>
                    </div>
                    <div class="col-sm-8 floatt">                    
                    <input type="text" value="" name="sueldoxhora" id="sueldoxhora" class="form-control" autofocus="autofocus" data-validation="required number length" data-validation-allowing="-/.#() " data-validation-length="max50" onchange="CalcularSueldo();" style="text-align: right;">
                    </div>
                    <div class="clearr"></div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 floatt">
                    <label for="tipo_gondola" class="control-label">Sueldo:</label>
                    </div>
                    <div class="col-sm-8 floatt">
                    <input type="text" value="" name="sueldo_total" id="sueldo_total" class="form-control" autofocus="autofocus" data-validation="required number length" data-validation-allowing="-/.#() " data-validation-length="max50" style="text-align: right;">
                    </div>
                    <div class="clearr"></div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 floatt">
                    <label for="tipo_gondola" class="control-label">Fecha Inicio:</label>
                    </div>
                    <div class="col-sm-8 floatt">
                    <input type="date" value="" name="fecha_inicio" id="fecha_inicio" class="form-control" data-validation="required"> 
                    </div>
                    <div class="clearr"></div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 floatt">
                    <label for="tipo_gondola" class="control-label">Fecha Final:</label>
                    </div>
                    <div class="col-sm-8 floatt">
                    <input type="date" value="" name="fecha_final" id="fecha_final" class="form-control"> 
                    </div>
                    <div class="clearr"></div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 floatt">
                    <label for="tipo_gondola" class="control-label">Quien firma el contrato:</label>
                    </div>
                    <div class="col-sm-8 floatt">
                    <?php        
                    $feriados = $db->query("SELECT * 
                                            FROM rrhh_firma_contrato
                                            ORDER BY id_firma desc
                                            ")->fetch_first();
                    echo $feriados['nombre']."<br>"; 
                    echo $feriados['ci']."<br>"; 
                    echo $feriados['cargo']; 
                    ?>

                    <input type="hidden" value="<?= $feriados['id_firma'] ?>" name="quien_firma" id="quien_firma" class="form-control"> 
                    </div>
                    <div class="clearr"></div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary pull-right" id="btn_contrato_nuevo">Guardar</button>
                <button type="submit" class="btn btn-primary pull-right" id="btn_contrato_editar">Editar</button>
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
    var $materias = $('#materias');

    $(".ItIs").css({'display':'none'});

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

            y=$('#sueldo_total').val();
            x=parseInt(y);


            if(x>0){

                $.ajax({
                    type: 'POST',
                    url: "?/rrhh-personal/guardar-contrato",
                    data: datos,
                    success: function (resp) {
         
                        //console.log(resp);
                        //alert(resp);

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
            else{
                alert("El monto sueldo no puede estar Vacio");
            }

        }
    })
})

    /*$('#fecha1').datepicker({
        language: 'es',
        position:'bottom left',
        onSelect: function(fd, d, picker){
            var fecha_marcada = moment(d).format('YYYY-MM-DD');
            var hoy = moment(new Date()).format('YYYY-MM-DD');

            if(fecha_marcada < hoy){
              //$("#fecha_inicio").val(moment(fecha_marcada).format('DD-MM-YYYY HH:mm'));
            
            }else{
              alertify.error('No puede una fecha mayor a la actual');
              $("#fecha1").val("");
            }
        }
    })

    /*$('#fecha2').datepicker({
        language: 'es',
        position:'bottom left',
        onSelect: function(fd, d, picker){
            var fecha_marcada = moment(d).format('YYYY-MM-DD');
            var hoy = moment(new Date()).format('YYYY-MM-DD');

            if(fecha_marcada < hoy){
              //$("#fecha_inicio").val(moment(fecha_marcada).format('DD-MM-YYYY HH:mm'));
            
            }else{
              alertify.error('No puede una fecha mayor a la actual');
              $("#fecha2").val("");
            }
        }
    })*/

function setcargo(){
    id=$("#tipo").val();
    
    $(".ItIs").css({'display':'none'});
    $("#sueldo_total").removeAttr('readonly');


    if(id==1){
        $(".ItIsAdmin").css({'display':'block'});
    }
    if(id==2){
        $(".ItIsTeacher").css({'display':'block'});
        $("#sueldo_total").attr('readonly','readonly');
    }
    if(id==3){
        $(".ItIsTeacherAdmin").css({'display':'block'});
    }
}
function CalcularSueldo(){
    id=$("#tipo").val();
    if(id==2){    
        hh=$("#horas").val();
        ss=$("#sueldoxhora").val();
        st=hh*ss;
        $("#sueldo_total").val(st);
    }
}
</script>