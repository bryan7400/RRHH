<?php
  $csrf = set_csrf();
$contratos = $db->query("SELECT * FROM rrhh_contrato WHERE estado = 'A'")->fetch();
 $estudiantes=$db->query("SELECT *
                FROM ins_inscripcion i
    INNER JOIN ins_estudiante e ON i.estudiante_id=e.id_estudiante
    INNER JOIN ins_inscripcion r ON i.estudiante_id=r.estudiante_id
    inner join sys_persona sp on e.persona_id=sp.id_persona
    WHERE  i.estado = 'A'  
    AND r.estado = 'A'  
    ORDER BY primer_apellido")->fetch();

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
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">




<div class="modal fade" id="modal_estudiante" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modal-title">Estudiantes</h5>


        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </a>
<br>
           
                        

    </div>


    
<div class="modal-body">


<form id="form_estudiante">
<label class="text-primary">INFORMACIÓN ESTUDIANTE</label>


<input type="hidden" name="5a5fdf19883c04158d3d9efa0ae989a42eece847">

<input type="hidden" value="0" name="id_asignacionx" id="id_asignacionx">
<input type="hidden" value="0" name="id_postulante" id="id_postulante">
<input type="hidden" value="0" name="id_informacion_estudiante" id="id_informacion_estudiante">

<div class="form-row">





    <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12 mb-2">



    <label for="estudiante_id"><label style="color:red">*</label> Estudiante:</label>
    <select name="estudiante_id" id="estudiante_id" class="form-control" autofocus="autofocus" data-validation="required" data-validation-allowing="+-/.#() ">
        <option value="" selected="selected">Seleccionar</option>
        <?php foreach ($estudiantes as $estudiante) : ?>
        <option value="<?= $estudiante['estudiante_id']; ?>"><?= escape($estudiante['nombres']); ?> <?= escape($estudiante['primer_apellido']); ?> <?= escape($estudiante['segundo_apellido']); ?></option>
        <?php endforeach ?>
    </select>
    </div>

    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 mb-2 ">
        <label for="categoria_informacion"><label style="color:red">*</label> Categoria:</label>
        <select name="categoria_informacion" id="categoria_informacion" class="form-control text-uppercase" onchange="setcargo();" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true">
            <option value="" selected="selected">Seleccionar</option>
            <option value="AMIGOS" >Amigos</option>
            <option value="PASATIEMPOS" >Pasatiempos</option>
            <option value="COMIDA" >Comida</option>
            
        </select>
    </div>


    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2 ">
        <label for="nombre" class="nombre"><label style="color:white">*</label> Nombre:</label>
        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="">
    </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2 ItIs ItIsTeacher ">
        <label class="" for="celular" id="celu" ><label style="color:red">*</label> Celular:</label>
        <input type="text" class="form-control" id="celular" name="celular" placeholder="">
    </div>
    
    
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
                    <label for="descripcion" class=" descripcion control-label">Descripcion:</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" data-validation="required letternumber" data-validation-allowing="-+/.,:;@#&'()_\n "></textarea>
                </div>
</div>








<div class="modal-footer">
    <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
    <button type="submit" class="btn btn-primary pull-right" id="btn_persona_nuevo">Guardar</button>
</div>



<div class="card-body">

    <label class="text-primary">INTERESES DEL ESTUDIANTE</label>
                <!-- ============================================================== -->
                <!-- datos --> 
                <!-- ============================================================== -->
                <div class="table-responsive">
                <?php if ($contratos) : ?>
                <table id="table1" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
                    <thead>
                        <tr class="active">
                            <th class="text-nowrap">#</th>
                            <th class="text-nowrap">Categoria Informacion</th>
                            <th class="text-nowrap">Nombre</th>
                            <th class="text-nowrap">Celular</th>
                            <th class="text-nowrap">Descripcion</th>
                            

                            <?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
                            <th class="text-nowrap">Opciones</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="active">
                            <th class="text-nowrap stext-middle" data-datafilter-filter="false">#</th>
                            <th class="text-nowrap">Categoria Informacion</th>
                            <th class="text-nowrap">Nombre</th>
                            <th class="text-nowrap">Celular</th>
                            <th class="text-nowrap">descripcion</th>
                            
                            
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
                        <li>No existen Contratos registrados en la base de datos.</li>
                        <li>Para crear nuevos Contratos debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
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
        <p>¿Esta seguro de eliminar el Contrato <span id="texto_contrato"></span>?</p>
      </div>
      <div class="modal-footer">



        <button type="button" class="btn btn-light" onclick="$('#modal_eliminar').modal('hide');" aria-label="Close">Cancelar</button>

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
        url: '?/ins-informacion-estudiante/procesos',
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

            $('#id_informacion_estudiante').val(resp["id_informacion_estudiante"]);
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
                    url: "?/ins-informacion-estudiante/guardar",
                    data: datos,
                    success: function (resp) {
         
                        //console.log(resp);
                        //alert(resp);
    $("#id_informacion_estudiante").val('');
    $("#categoria_informacion").val('');
    $("#nombre").val('');
    $("#celular").val('');
    $("#descripcion").val('');
                        cont = 0;
                        switch(resp){
                            case '1': 
                                    dataTable1.ajax.reload();
                                    alertify.success('Se registro la gestión escolar correctamente');
                                      
                                      break;
                            case '2':
                                    dataTable1.ajax.reload();
                                    alertify.success('Se ED la gestión escolar correctamente');
                                      
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
    id=$("#categoria_informacion").val();
    
    if(id==''){
        $("#celular ").hide();
    $("#nombre ").hide();
    $("#descripcion ").hide();
    $(".nombre ").hide();
    $(".descripcion ").hide();
        
    }

    if(id=='PASATIEMPOS'){
        
        $("#celular ").hide();
        $("#celu").hide();
        $("#celular ").val("");
        $(".nombre ").show();
    $(".descripcion ").show();
     $("#nombre ").show();
    $("#descripcion ").show();
    }
    if(id=='AMIGOS'){
        $(".ItIsTeacher").css({'display':'block'});
        $("#celular ").show();
        $("#celu").show();
        $(".nombre ").show();
    $(".descripcion ").show();
     $("#nombre ").show();
    $("#descripcion ").show();
    }
    if(id=='COMIDA'){
        
        $("#celular ").hide();
        $("#celu").hide();
        $("#celular ").val("");
        $(".nombre ").show();
    $(".descripcion ").show();
     $("#nombre ").show();
    $("#descripcion ").show();
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
        url: '?/ins-informacion-estudiante/procesos',
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
    id=$("#categoria_informacion").val();
    
    $(".ItIs").css({'display':'none'});

    if(id=='PASATIEMPO'){
        $(".ItIsAdmin").css({'display':'block'});
    }
    if(id=='AMIGO'){
        $(".ItIsTeacher").css({'display':'block'});
    }
    if(id=='COMIDA'){
        $(".ItIsAdmin").css({'display':'block'});
    }
}

*/







var dataTable1;


function setestudiantes2(){





estudiante_id = $("#estudiante_id option:selected").val();


if (estudiante_id) {


var columns=[
    {data: 'id_informacion_estudiante'},
    {data: 'categoria_informacion'},
    {data: 'nombre'},
    {data: 'celular'},
    {data: 'descripcion'}
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
        url: '?/ins-informacion-estudiante/busqueda',
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
            var contenido = row['id_informacion_estudiante'] + "*" + row['categoria_informacion']+ "*" + row['nombre'] + "*" + row['celular']+ "*" +  row['descripcion'];
            result+="<?php if (false) : ?><a href='#' class='btn btn-info btn-xs' onclick = 'ver("+'"'+contenido+'"'+")'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
                    
                    "<?php if ($permiso_editar) : ?><a href='#' class='btn btn-warning btn-xs' style='color:white' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note' style='color:black'></span></a><?php endif ?> &nbsp" +
                    "<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' role='button' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash' style='color:black'></span></a><?php endif ?>";
            return result;
        },
        "targets": 5
},
            
            
            
            {
                    "render": function (data, type, row) {
                        cont = cont +1;
                        return cont;
                    },
                    "targets": 0
            }


    ]
});
    

}





}




function setestudiantes(){
        $('#table1').show();
    var table = $('#table1').DataTable();
 

    table.destroy();

setestudiantes2();

}



$(function () {
    // Obtiene a los estudiantes
    $('#estudiante_id').selectize({
        persist: false,
        createOnBlur: true,
        create: false,
        onInitialize: function (){
            $('#estudiante_id').css({
                display: 'block',
                left: '-10000px',
                opacity: '0',
                position: 'absolute',
                top: '-10000px'
            });
        },
        onChange: function () {
            $('#estudiante_id').trigger('blur');
        },
        onBlur: function () {
            $('#estudiante_id').trigger('blur');
        }
    });

    $("#estudiante_id").change(function(){
        

        $('#table1').show();
    var table = $('#table1').DataTable();
 

    table.destroy();

setestudiantes2();


        
    });
})


</script>