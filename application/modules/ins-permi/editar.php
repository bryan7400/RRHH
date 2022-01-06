<?php
  $csrf = set_csrf();

  $estudiantes=$db->query("SELECT *
                FROM ins_inscripcion i
                INNER JOIN ins_estudiante e ON i.estudiante_id=e.id_estudiante
                INNER JOIN ins_inscripcion r ON i.estudiante_id=r.estudiante_id
                inner join sys_persona sp on e.persona_id=sp.id_persona
                WHERE i.estado = 'A'")->fetch();


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





<div class="modal fade" id="modal_estudiante" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div style="width: 90%;
   max-width:1100px;" class="modal-dialog modal-lg" role="document">
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modal-title">Registros Medicos</h5>


        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </a>
<br>
           
                        

    </div>


    
<div class="modal-body">


<form id="form_estudiante">
<label class="text-primary">INFORMACIÓN MEDICA DEL ESTUDIANTE</label>


<input type="hidden" name="5a5fdf19883c04158d3d9efa0ae989a42eece847">

<input type="hidden" value="0" name="id_asignacionx" id="id_asignacionx">
<input type="hidden" value="0" name="id_postulante" id="id_postulante">
<input type="hidden" value="0" name="id_medico_estudiante" id="id_medico_estudiante">



<div class="row">

    <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12 mb-2">
        <label for="estudiante_id"><label style="color:red">*</label>Estudiante:</label>
    
    <select name="estudiante_id" id="estudiante_id" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " onchange="setestudiantes();" data-validation-optional="true" >
        <option value="" selected="selected">Buscar</option>
        <?php 
        

        foreach ($estudiantes as $estudiante) { ?>
            <option value="<?= escape($estudiante['estudiante_id']); ?>"><?= escape($estudiante['nombres']); ?> <?= escape($estudiante['primer_apellido']); ?> <?= escape($estudiante['segundo_apellido']); ?></option>
        <?php } ?>
    </select>
    </div>

    <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12 mb-2 ">
        <label for="categoria_medico"><label style="color:red">*</label> Categoria:</label>
        <select name="categoria_medico" id="categoria_medico" class="form-control text-uppercase" onchange="setcargo();" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true">
            <option value="" selected="selected">Seleccionar</option>
            <option value="PESO" >Peso</option>
            <option value="ESTATURA" >Estatura</option>
            <option value="ALERGIA" >Alergias</option>
            <option value="VACUNA" >Vacunas</option>
            <option value="SANGRE" >Sangre</option>
            
        </select>
    </div>

</div>




<div id="dv_estatura" class="row medico">

    <div class="col">
        <label for="estatura"><label style="color:white">*</label> Estatura:</label>
        <input type="text" class="form-control" id="estatura" name="estatura" placeholder="">
    </div>
    <div class="col">


        <label class="control-label" for="fecha_estatura">Fecha: </label>
          <div class="controls">
            <input id="fecha_estatura"  name="fecha_estatura" type="date" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
          </div>
    </div>

    

</div>



<div id="dv_peso" class="row medico">

    <div class="col">
        <label for="peso"><label style="color:white">*</label>Peso:</label>
        <input type="text" class="form-control" id="peso" name="peso" placeholder="">
    </div>
    
    <div class="col">


        <label class="control-label" for="fecha_peso">Fecha: </label>
          <div class="controls">
            <input id="fecha_peso"  name="fecha_peso" type="date" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
          </div>
    </div>
    

</div>



<div id="dv_alergia" class="row medico">

    <div class="col-md-6">
        <label for="alergia"><label style="color:white">*</label>Alergia:</label>
        <input type="text" class="form-control" id="alergia" name="alergia" placeholder="">
    </div>
    

    
</div>


<div id="dv_vacuna" class="row medico">

    <div class="col-md-6">
        <label for="vacuna"><label style="color:white">*</label>Vacuna:</label>
        <input type="text" class="form-control" id="vacuna" name="vacuna" placeholder="">
    </div>
    

</div>
<div id="dv_sangre" class="row medico">

    <div class="col-md-6">
        <label for="tipo_sangre"><label style="color:white">*</label> Tipo Sangre:</label>
        <input type="text" class="form-control" id="tipo_sangre" name="tipo_sangre" placeholder="">
    </div>
    

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
    <button type="submit" class="btn btn-primary pull-right" id="btn_persona_nuevo">Guardar</button>
</div>



<div class="card-body">

    <label class="text-primary">REGISTROS MEDICOS</label>
                <!-- ============================================================== -->
                <!-- datos --> 
                <!-- ============================================================== -->
                <div class="table-responsive">
                <?php if ($contratos) : ?>
    <table id="table1" class="table table-bordered table-condensed table-striped table-hover">
        <thead>
            <tr class="active">
                <th class="text-nowrap">#</th>
                <th class="text-nowrap">Categoria</th>
                <th class="text-nowrap">Sangre</th>
                <th class="text-nowrap">Estatura(m)</th>
                <th class="text-nowrap">Fecha Estatura</th>
                <th class="text-nowrap">Peso(Kg)</th>
                <th class="text-nowrap">Fecha Peso</th>
                <th class="text-nowrap">Alergia</th>
                <th class="text-nowrap">Vacuna</th>
                
                <?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
                <th class="text-nowrap">Opciones</th>
                <?php endif ?>
            </tr>
        </thead>
        <tfoot>
            <tr class="active">
                <th class="text-nowrap stext-middle" data-datafilter-filter="false">#</th>
                <th class="text-nowrap">Categoria</th>
                <th class="text-nowrap">Sangre</th>
                <th class="text-nowrap">Estatura(m)</th>
                <th class="text-nowrap">Fecha Estatura</th>
                <th class="text-nowrap">Peso(Kg)</th>
                <th class="text-nowrap">Fecha Peso</th>
                <th class="text-nowrap">Alergia</th>
                <th class="text-nowrap">Vacuna</th>                            
                <?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
                <th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
                <?php endif ?>
            </tr>
        </tfoot>
        <tbody>
            
        </tbody>
    </table>
                <?php else : ?>
                </div>
                <div class="alert alert-info">
                    <strong>Atención!</strong>
                    <ul>
                        <li>No existen Registros registrados en la base de datos.</li>
                        <li>Para crear nuevos Registros debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
                    </ul>
                </div>
                <?php endif ?>
                <!-- ============================================================== -->
                <!-- end datos -->
                <!-- ============================================================== -->
                </div>
</div>
</div>
</div>
</form>

<div class="modal fade" tabindex="-1" role="dialog" id="modal_eliminar">

  <div class="modal-dialo modal-dialog-centered " role="document">
    <div class="modal-content">
<div class="modal-header">
        <label class="text-primary">ELIMINACION DE REGISTROS</label>


        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </a>
<br>
           
                        

    </div>
      <div class="modal-body">
        <input type="hidden" id="area_eliminar">
        <p>¿Esta seguro de eliminar el registro <span id="texto_contrato"></span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" onclick="$('#modal_eliminar').hide();">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn_eliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>


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




function editar_doc(){



    var ci_busqueda=$("#ci_busqueda").val();
    $.ajax({
        url: '?/ins-registro-medico/procesos',
        type: 'POST',
        data:{
            'accion': 'recuperar_datos_ci',
            'ci_busqueda':ci_busqueda 
            },
        dataType: 'JSON',
        success: function(resp){  
     
            $("#modal_estudiante").modal("show");
            $("#form_estudiante")[0].reset();
            $("#btn_contrato_editar").hide();
            $("#btn_contrato_nuevo").show();

            $('#id_medico_estudiante').val(resp["id_medico_estudiante"]);
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

   
    

    

    $("#form_estudiante").validate({
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
            var datos = $("#form_estudiante").serialize();

            y=$('#sueldo_total').val();
            x=parseInt(y);


            //if(x>0){

                $.ajax({
                    type: 'POST',
                    url: "?/ins-registro-medico/guardar",
                    data: datos,
                    success: function (resp) {
         
                        //console.log(resp);
                        //alert(resp);
    $("#id_medico_estudiante").val('0');
    $("#categoria_medico").val('');
    $("#nombre").val('');
    $("#celular").val('');
    $("#descripcion").val('');

    dataTable1.ajax.reload();
   
    
                        cont = 0;
                        switch(resp){
                            case '1': 
                                    
                                    alertify.success('Se registro el interes correctamente');
                                      
                                      break;
                            case '2':
                                    
                                    alertify.success('Se edito el interes correctamente');
                                      
                                      break;
                        }



                         $("#dv_estatura input").each(function() {
      this.value = "";
  })

    $("#dv_peso input").each(function() {
      this.value = "";
  })

    $("#dv_alergia input").each(function() {
      this.value = "";
  })

    $("#dv_vacuna input").each(function() {
      this.value = "";
  })
    $("#dv_sangre input").each(function() {
      this.value = "";
  })


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



function CalcularSueldo(){
    id=$("#tipo").val();
    if(id==2){    
        hh=$("#horas_academicas").val();
        ss=$("#sueldo_por_hora").val();
        st=hh*ss;
        $("#sueldo_total").val(st);
    }
}



var $materias = $('#materias');


function setcargo(){
    id=$("#categoria_medico").val();
    
    
    
    if(id=='ESTATURA'){
        $(".medico").hide();
        $("#dv_estatura ").show();
        
    }
    if(id=='PESO'){
         $(".medico").hide();
        $("#dv_peso").show();
    }
    if(id=='ALERGIA'){
        
         $(".medico").hide();
        $("#dv_alergia ").show();
    }
    if(id=='VACUNA'){
        
         $(".medico").hide();
        $("#dv_vacuna ").show();
    }
    if(id=='SANGRE'){
        
         $(".medico").hide();
        $("#dv_sangre ").show();
    }



/*

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
        url: '?/ins-registro-medico/procesos',
        type: 'POST',
        data:{
            'accion': 'recuperar_datos_contratos',
            'cargos':cargos 
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
        
      
*/  

}

/*

function setcargo(){
    id=$("#categoria_medico").val();
    
    $(".ItIs").css({'display':'none'});

    if(id=='PASATIEMPO'){
        $(".ItIsAdmin").css({'display':'block'});
    }
    if(id=='AMIGO'){
        $(".ItIsTeacher").css({'display':'block'});
    }
    if(id=='ALERGIAS'){
        $(".ItIsAdmin").css({'display':'block'});
    }
}

*/







var dataTable1;


function setestudiantes2(){





estudiante_id = $("#estudiante_id option:selected").val();



var columns=[
    {data: 'id_medico_estudiante'},
    {data: 'categoria_medico'},
    {data: 'tipo_sangre'},
    {data: 'estatura'},
    {data: 'fecha_estatura'},
    {data: 'peso'},
    {data: 'fecha_peso'},
    {data: 'alergia'},
    {data: 'vacuna'}
];
var cont = 0;
//function listarr(){
 dataTable1 = $('#table1').DataTable({
    language: dataTableTraduccion,
    searching: true,
    paging:true,
    "lengthChange": true,
    "responsive": true,
    ajax: {
        url: '?/ins-registro-medico/busqueda',
        dataSrc: '',
        type:'POST',
        data:{
            'estudiante_id': estudiante_id,
            },
        dataType: 'json',
    },
    columns: columns,

    "columnDefs": [

{
        "render": function (data, type, row) {
            var result = "";
            var contenido = row['id_medico_estudiante'] + "*" + row['categoria_medico']+ "*" + row['tipo_sangre'] + "*" + row['estatura']+ "*" +  row['fecha_estatura']+ "*" +  row['peso']+ "*" +  row['fecha_peso']+ "*" +  row['alergia']+ "*" +  row['vacuna'];
            result+="<?php if (false) : ?><a href='#' class='btn btn-info btn-xs' onclick = 'ver("+'"'+contenido+'"'+")'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
                    
                    "<?php if ($permiso_editar) : ?><a href='#' class='btn btn-warning btn-xs' style='color:white' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note' style='color:black'></span></a><?php endif ?> &nbsp" +
                    "<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' role='button' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash' style='color:black'></span></a><?php endif ?>";
            return result;
        },
        "targets": 9
},
            
            
            
            {
                    "render": function (data, type, row) {
                        cont = cont +1;
                        return cont;
                    },
                    "targets": 9
            }


    ]
});
    







}




function setestudiantes(){
        $('#table1').show();
    var table = $('#table1').DataTable();
 

    table.destroy();

setestudiantes2();

}
</script>