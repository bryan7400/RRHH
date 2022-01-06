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





<div class="modal fade" id="modal_contrato" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modal-title">Crear Personal/Contrato</h5>


        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </a>
<br>
           
                        

    </div>


    
<div class="modal-body">

<div class="form-group col-sm-6">
    <font style="color:red">*</font>Buscar por C.I.
    <input class="form-control form-control-lg" name="ci_busqueda" id="ci_busqueda" type="text" placeholder="" onkeyup="editar_doc();" autocomplete="off" autofocus="autofocus" data-validation="required">
</div>
<form id="form_contrato">
<label class="text-primary">INFORMACIÓN BÁSICA</label>


<input type="hidden" name="5a5fdf19883c04158d3d9efa0ae989a42eece847">

<input type="hidden" value="0" name="id_asignacionx" id="id_asignacionx">
<input type="hidden" value="0" name="id_postulante" id="id_postulante">
<input type="hidden" value="0" name="id_persona" id="id_persona">

<div class="form-row">
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
        <label for="nombres"><label style="color:red">*</label> Nombres:</label>
        <input type="text" class="form-control" id="nombres" name="nombres" placeholder="">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
        <label for="primer_apellido"><label style="color:white">*</label> Primer Apellido:</label>
        <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" placeholder="">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
        <label for="segundo_apellido"><label style="color:red">*</label> Segundo Apellido:</label>
        <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido" placeholder="">
    </div>
</div>
<div class="form-row">
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
        <label for="numero_documento"><label style="color:red">*</label> Número Documento:</label>
        <input type="text" class="form-control" id="numero_documento" name="numero_documento" placeholder="">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
        <label for="complemento"><label style="color:white">*</label> Complemento:</label>
        <input type="text" class="form-control" id="complemento" name="complemento" placeholder="">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
        <label for="expedido"><label style="color:red">*</label> Expedido:</label>
        <select name="expedido" id="expedido" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true">
            <option value="" selected="selected">Buscar</option>
            <option value="LP" selected="selected">LP</option>
            <option value="PT" selected="selected">PT</option>
            <option value="OR" selected="selected">OR</option>
            <option value="CB" selected="selected">CB</option>
            <option value="CH" selected="selected">CH</option>
            <option value="TJ" selected="selected">TJ</option>
            <option value="BN" selected="selected">BN</option>
            <option value="PA" selected="selected">PA</option>
            <option value="SC" selected="selected">SC</option>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
        <label for="fecha_nacimiento"><label style="color:red">*</label> Fecha Nacimiento:</label>
        <input type="text" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
        <label for="genero"><label style="color:red">*</label> Género:</label>
        <select name="genero" id="genero" class="form-control" autofocus="autofocus">
            <option value="" selected="selected">Seleccionar</option>
            <option value="v">VARON</option>
            <option value="m">MUJER</option>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
        <label for="contacto"><label style="color:red">*</label> Celular:</label>
        <input type="text" class="form-control" id="contacto" name="contacto" placeholder="">
    </div>
    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 mb-2">
        <label for="email"><label style="color:red">*</label> Correo:</label>
        <input type="text" class="form-control" id="email" name="email" placeholder="">
    </div>
</div>
<div class="form-row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-2">
        <label for="direccion"><label style="color:red">*</label> Dirección Domicilio:</label>
        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="">
    </div>
</div>
<hr>
<label class="text-primary">INFORMACIÓN DE CONTRATO</label>
<hr>
<div class="form-row">
<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
    <label for="cargo_id"><label style="color:red">*</label> Cargo:</label>
    
    <select name="tipo" id="tipo" class="form-control text-uppercase" data-validation="required" data-validation-optional="true" onchange="setcargo();">
        <option value="" selected="selected">Buscar</option>
        <option value="1">Administrativo</option>
        <option value="2">Docente</option>
        <option value="">Docente-Administrativo</option>
    </select>
</div>

<div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 mb-2">
    <label for="cargo_id"><label style="color:red">*</label> Contratos:</label>
    
    <select name="contrato" id="contrato" class="form-control text-uppercase" data-validation="required" data-validation-optional="true" onchange="setcontrato();">
        <option value="0" >Seleccionar</option>
    </select>
</div>
</div>
<div class="form-row">





<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2 ItIs ItIsAdmin ItIsTeacherAdmin">
    <label for="tipo_gondola"><label style="color:red">*</label>Tipo:</label>
    
    <select name="cargo" id="cargo" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true" >
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




<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2 ItIs ItIsTeacher ItIsTeacherAdmin">
    <label for="tipo_gondola"><label style="color:red">*</label>Nivel Academico:</label>
    
    <select name="nivel_academico[]" id="nivel_academico" class="form-control text-uppercase" multiple="multiple" data-validation="required"
    >
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
</div>

<div class="form-row">
<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
    <label for="fecha_inicio"><label style="color:red">*</label> Fecha Inicio:</label>
    <input type="text" class="form-control" id="fecha_inicio" name="fecha_inicio" placeholder="">
</div>
<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
    <label for="fecha_final"><label style="color:red">*</label> Fecha Fin:</label>
    <input type="text" class="form-control" id="fecha_final" name="fecha_final" placeholder="">

</div>
<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
        <label for="tipo_contrato"><label style="color:red">*</label> Tipo Contrato:</label>
        <select name="tipo_contrato" id="tipo_contrato" class="form-control" autofocus="autofocus">
            <option value="" selected="selected">Seleccionar</option>
            <option value="TC">TIEMPO COMPLETO</option>
            <option value="MT">MEDIO TIEMPO</option>
        </select>
    </div>
</div>
<div class="form-row">
    

    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2 ItIs ItIsTeacher ItIsTeacherAdmin">
    <label for="materias"><label style="color:red">*</label>Materia:</label>
    
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
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2 ItIs ItIsTeacher ItIsTeacherAdmin">
        <label for="horas_academicas"><label style="color:red">*</label> Horas Trabajo:</label>
        <input type="text" class="form-control" id="horas_academicas" name="horas_academicas" placeholder="" onchange="CalcularSueldo();">
    </div>
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2 ItIs ItIsTeacher">
        <label for="sueldo_por_hora"><label style="color:red">*</label> Sueldo/Hora:</label>
        <input type="text" class="form-control" id="sueldo_por_hora" name="sueldo_por_hora" placeholder="" style="text-align: right;" onchange="CalcularSueldo();">
    </div>

    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
        <label for="tipo_gondola"><label style="color:red">*</label>Sueldo:</label>
        <input type="text" value="" name="sueldo_total" id="sueldo_total" class="form-control" autofocus="autofocus" data-validation="required number length" data-validation-allowing="-/.#() " data-validation-length="max50" style="text-align: right;">
    </div>

</div>
<div class="form-row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-2">
        <label for="observacion"><label style="color:white">*</label> Observación:</label>
        <input type="text" class="form-control" id="observacion" name="observacion" placeholder="">
    </div>
</div>
<div class="form-row">
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
        <label for="observacion"><label style="color:white">*</label> Quien firma el contrato:</label>
    </div>
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
        <?php        
        $feriados = $db->query("SELECT * 
                                FROM rrhh_firma_contrato
                                ORDER BY id_firma desc
                                ")->fetch_first();
        echo "<b>Nombre: </b>".$feriados['nombre']."<br>"; 
        echo "<b>CI: </b>".$feriados['ci']."<br>"; 
        echo "<b>Cargo: </b>".$feriados['cargo']; 
        ?>
        <input type="hidden" value="<?= $feriados['id_firma'] ?>" name="quien_firma" id="quien_firma" class="form-control">         
    </div>
</div>

<hr>

<label class="text-danger">INFORMACIÓN DE USUARIO</label>
<hr>
<div class="form-row">
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
        <label for="generar_usuario"><label style="color:red">*</label> Generar Usuario:</label>
        <select name="generar_usuario" id="generar_usuario" class="form-control" autofocus="autofocus">
            <option value="" selected="selected">Seleccionar</option>
            <option value="SI">SI</option>
            <option value="NO">NO</option>
        </select>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
        <label for="id_rol"><label style="color:white">*</label> Rol:</label>
        <select name="rol_user" id="rol_user" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true">
            <option value="" selected="selected">Buscar</option>
            <?php 
            $gondolas = $db->select('z.*')
                            ->from('sys_roles z')
                            ->order_by('z.rol', 'asc')
                            ->fetch();

            foreach ($gondolas as $gondola) {  ?>
                <option value="<?php echo escape($gondola['id_rol']); ?>"><?php echo escape($gondola['rol']); ?></option>
            <?php } ?>
        </select>
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



<?php 
//    $hoy = getdate();
//    echo $hoy['wday'];
?>


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

function editar_doc(){



    var ci_busqueda=$("#ci_busqueda").val();
    $.ajax({
        url: '?/rrhh-personal/procesos',
        type: 'POST',
        data:{
            'accion': 'recuperar_datos_ci',
            'ci_busqueda':ci_busqueda 
            },
        dataType: 'JSON',
        success: function(resp){  
     
            $("#modal_contrato").modal("show");
            $("#form_contrato")[0].reset();
            $("#btn_contrato_editar").hide();
            $("#btn_contrato_nuevo").show();

            $('#id_persona').val(resp["id_persona"]);
            $('#id_postulante').val(resp["id_postulacion"]);
            $('#id_asignacionx').val(resp["id_asignacion"]);
            
            $('#nombres').val(resp["nombre"]);
            $('#primer_apellido').val(resp["paterno"]);
            $('#segundo_apellido').val(resp["materno"]);

            $('#numero_documento').val(resp["ci"]);
            $('#complemento').val(resp["complemento"]);
            $('#expedido').val(resp["expirado"]);

            $('#fecha_nacimiento').val(resp["fecha_nacimiento"]);
            $('#genero').val(resp["genero"]);

            $('#contacto').val(resp["celular"]);
            $('#email').val(resp["email"]);
            $('#direccion').val(resp["direccion"]);

            $('#cargo').val(resp["cargo_id"]);
            $('#fecha_inicio').val(resp["fecha_inicio"]);
            $('#fecha_final').val(resp["fecha_final"]);

            $('#tipo_contrato').val(resp["tipo_contrato"]);
            $('#horas_academicas').val(resp["horas_academicas"]);
            $('#sueldo_por_hora').val(resp["sueldo_por_hora"]);
            $('#sueldo_total').val(resp["sueldo_total"]);


            var str_array_skills = resp["materia_id"].split(',');
            var $select =   $('#materias').selectize();
            var selectize = $select[0].selectize;
            selectize.setValue(str_array_skills);
            selectize.refreshOptions();
            

            var str_array_skills2 = resp["nivel_academico_id"].split(',');
            var $select2 =   $('#nivel_academico').selectize();
            var selectize2 = $select2[0].selectize;
            selectize2.setValue(str_array_skills2);
            selectize2.refreshOptions();
            

            $('#observacion').val(resp["observacion"]);

            if(resp["cargo_id"]==1){
                $("#tipo").val("2");
            }
            else{
                if(resp["materia_id"]==""){
                    $("#tipo").val("1");
                }else{
                    $("#tipo").val("3");
                }
            }
            setcargo();
        }
    });
}


$(function () {    
    
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


            //if(x>0){

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

            /*}
            else{
                alert("El monto sueldo no puede estar Vacio");
            }*/

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


    cargos = $("#tipo option:selected").val();

if (cargos == 3) {
    
        cargos= "";
    
}else{
        if (cargos == 2) {
        cargos= "Docente";
        
    }else
    {
        cargos= "Administrativo";
    }
}




    $.ajax({
        url: '?/rrhh-personal/procesos',
        type: 'POST',
        data:{
            'accion': 'recuperar_datos_contratos',
            'cargo':cargos 
            },
        dataType: 'JSON',
        



success: function(resp){  
            
            $("#contrato").html("");
                $("#contrato").append('<option value="' + 0 + '">Seleccionar contrato</option>');
                for (var i = 0; i < resp.length; i++) {
                   // if(contN<1){
//$("#contrato").append('<option selected value="' + resp[i]["id_contrato_academico"] + '">' + resp[i]["nombre_contrato"]+'</option>');
// }else{
    if (resp[i]["fecha_final"]== "0000-00-00") {
        resp[i]["fecha_final"]= "contrato Indefinido";
        }
    $("#contrato").append('<option  value="' + resp[i]["id_contrato"] + '">' + resp[i]["tipo_documento"]+' - ' + resp[i]["area_contrato"]+' - '  + resp[i]["fecha_inicio"]+' // ' + resp[i]["fecha_final"]+' - ' + resp[i]["modalidad_contrato"]+' - ' + resp[i]["gestion_id"]+' - ' + resp[i]["area_contrato"]+'</option>');


}                   
                
            }
        });
        
}

function setcontrato(){


    id_contrato = $("#contrato option:selected").val();





    $.ajax({
        url: '?/rrhh-personal/procesos',
        type: 'POST',
        data:{
            'accion': 'recuperar_contrato',
            'id_contrato':id_contrato 
            },
        dataType: 'JSON',
        success: function(resp){  
            if (resp["modalidad_contrato"]== "Tiempo Completo") {
        resp["tipo_contrato"]= "TC";
        }else {
        resp["tipo_contrato"]= "MT";
        }

            $('#fecha_inicio').val(resp["fecha_inicio"]);
            $('#fecha_final').val(resp["fecha_final"]);

            $('#tipo_contrato').val(resp["tipo_contrato"]);


            
            

            var str_array_skills2 = resp["nivel_academico"].split(',');
            var $select2 =   $('#nivel_academico').selectize();
            var selectize2 = $select2[0].selectize;
            selectize2.setValue(str_array_skills2);
            selectize2.refreshOptions();
                     
            if(resp["cargo_id"]==1){
                $("#tipo").val("2");
            }
            else{
                if(resp["materia_id"]==""){
                    $("#tipo").val("1");
                }else{
                    $("#tipo").val("3");
                }
            }

            }
        });
        
}



function CalcularSueldo(){
    id=$("#tipo").val();
    if(id==2){    
        hh=$("#horas_academicas").val();
        ss=$("#sueldo_por_hora").val();
        st=hh*ss;
        $("#sueldo_total").val(st);
    }
}
</script>