<?php

// Obtiene la cadena csrf
$csrf = set_csrf();


// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);


$permiso_subir = in_array('subir', $_views);


$materias = $db->select('z.*')
                        ->from('pro_materia z')
                        ->order_by('z.nombre_materia', 'asc')
                        ->fetch();


$contratos = $db->select('z.*')
                        ->from('rrhh_contrato z')
                        ->order_by('z.tipo_documento', 'asc')
                        ->fetch();


$materia = $db->select('z.*')->from('pro_materia z')->order_by('z.id_materia', 'asc')->fetch();

$informacion_estudiante = $db->query("SELECT *,CONCAT(id_curso,'e') as id_materia, nombre_curso AS nombre_materia FROM ext_curso_inscripcion eci
INNER JOIN ext_curso_asignacion eca ON eca.id_curso_asignacion = eci.curso_asignacion_id
INNER JOIN ext_curso ec ON ec.id_curso = eca.curso_id
WHERE eca.estado = 'A' 
AND estudiante_id = '663' ")->fetch();


$materias = array_merge($materia, $informacion_estudiante);



$familiares = $db->query("SELECT * FROM  ins_estudiante_familiar ief
INNER JOIN ins_familiar ie ON ie.id_familiar=ief.familiar_id
INNER JOIN sys_persona  per ON per.id_persona=ie.persona_id
INNER JOIN sys_users su ON su.persona_id = per.id_persona
  ")->fetch(); 

$estudiantes=$db->query("SELECT *
                FROM ins_inscripcion i
    INNER JOIN ins_estudiante e ON i.estudiante_id=e.id_estudiante
    INNER JOIN ins_inscripcion r ON i.estudiante_id=r.estudiante_id
    inner join sys_persona sp on e.persona_id=sp.id_persona
    WHERE  i.estado = 'A'  
    AND r.estado = 'A'  
    ORDER BY primer_apellido")->fetch();
/*
 $estudiantes=$db->query("SELECT *
                FROM ins_inscripcion i
                INNER JOIN ins_estudiante e ON i.estudiante_id=e.id_estudiante
                INNER JOIN ins_inscripcion r ON i.estudiante_id=r.estudiante_id
                inner join sys_persona sp on e.persona_id=sp.id_persona
                WHERE i.estado = 'A' ORDER BY primer_apellido")->fetch();
*/
?>


<style>
.floatt{
    float:left;
    padding: 0px;
}
.clearr{
    clear: both;
}
.selectize-dropdown-content {
    max-height: 500px;
 }
</style>

<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">

 
 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">  


    <script src="https://cdn.bootcss.com/select2/3.4.5/select2.min.js"></script>
    <link href="https://cdn.bootcss.com/select2/3.4.5/select2.min.css" rel="stylesheet"/>

<form id="form_contrato" enctype="multipart/form-data"> 
<div class="modal fade" id="modal_contrato" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <h5 class="modal-title" id="exampleModalLabel">Administracion de Permisos</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body">
      <input id="id_permiso" name="id_permiso" type="hidden" class="form-control">           
        <input type="hidden" name="<?= $csrf; ?>">


<div class="form-row">

    <div class="col">
        <label for="estudiante_id"><label style="color:red">*</label>Estudiante:</label>
    
    <select name="estudiante_id" id="estudiante_id" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " onchange="listar_familiares();" data-validation-optional="true" >
        <option value="" selected="selected">Buscar</option>
        <?php 
        

        foreach ($estudiantes as $estudiante) { ?>
            <option value="<?= escape($estudiante['id_estudiante']); ?>"> <?= escape($estudiante['primer_apellido']); ?>  <?= escape($estudiante['segundo_apellido']); ?> <?= escape($estudiante['nombres']); ?></option>
        <?php } ?>
    </select>
    </div>

    <div class="col">
        <label for="familiar_id"><label style="color:red">*</label>Familiar:</label>
    
    <select name="familiar_id" id="familiar_id" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& "  data-validation-optional="true" >
        <option value="" selected="selected">Buscar</option>
        <?php 
        

        foreach ($familiares as $familiare) { ?>
            <option value="<?= escape($familiare['id_familiar']); ?>"> <?= escape($familiare['primer_apellido']); ?>  <?= escape($familiare['segundo_apellido']); ?> <?= escape($familiare['nombres']); ?></option>
        <?php } ?>
    </select>

    
    </div>


    <div class="col-md-2">
      <label for="categoria"><label style="color:red">*</label></label>Categoria:</label>
        <select name="categoria" id="categoria" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true" onchange="settodo();" required title="Seleccione una opcion" >
            <option value="" selected="selected">Seleccionar</option>
            <option value="TODO" >TODO</option>
            <option value="HORARIO" >HORARIO</option>
            <option value="MATERIA" >MATERIA</option>
        </select>
    </div>


</div>


<div class="form-row">
    

    


<div class="col-md-8"  id="dvmateria" >
    <label for="materia_id">Materia</label>
    
    <select  name="materia_id[]" id="materia_id" class="" multiple="multiple" required title="Seleccione una opcion" >
        <option value="" selected="selected">Seleccionar</option>
        
    </select>  
    </div> 

     


<div class="col-md-4" id="dvhorario" >
    <label for="horario_id">horario</label>
    
    <select name="horario_id[]" id="horario_id" class="" multiple="multiple" required title="Seleccione una opcion" >
        <option value="" selected="selected">Seleccionar</option>
        
    </select>  
    </div> 

     
</div>


<div class="form-row"   >
    
    <div class="col-md-6">
        <label for="tipo_permiso"><label style="color:red">*</label></label>Tipo de Permiso:</label>
        <select onchange="setfec();" name="tipo_permiso" id="tipo_permiso" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true" required title="Seleccione una opcion" >
            
            <option value="" selected="selected">Seleccionar</option>
            <option value="SALUD" >Salud</option>
            <option value="INSTITUCIONAL" >institucional</option>
            <option value="PERSONAL" >Personal</option>
            
        </select>
        </div>

        

</div>


 



        <div class="form-row form-group">
        

        



    <div class="col">
        <label for="contrato_id"><label style="color:red">*</label>Contratos:</label>
    
    <select name="contrato_id" id="contrato_id" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& "  data-validation-optional="true" >
        <option value="" selected="selected">Buscar</option>
        <?php 
        

        foreach ($contratos as $contrato) { ?>
            <option value="<?= escape($contrato['id_contrato']); ?>"><?= escape($contrato['tipo_contrato']); ?> <?= escape($contrato['fecha_inicio']); ?> Al <?= escape($contrato['fecha_final']); ?></option>
        <?php } ?>
    </select>
    </div>    



        

        <div class="form-group col">
        <label for="seguimiento_permiso"><label style="color:red">*</label></label>Seguimiento:</label>
        <select onchange="setfec();" name="seguimiento_permiso" id="seguimiento_permiso" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true" required title="Seleccione una opcion" >
            
            <option value="" selected="selected">Seleccionar</option>
            <option value="SOLICITUD" >Seguimiento</option>
            <option value="APROBADO" >Aprobado</option>
            <option value="RECHAZADO" >Rechazado</option>
            
        </select>
        </div>
          
        </div>






<div class="form-row form-group">
<div class="form-group col-md-6">
          <label class="control-label" for="fecha_inicio">Fecha inicio: </label>
          <div class="controls">
            <input id="fecha_inicio" name="fecha_inicio" type="date" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>" required title="Seleccione una fecha inicial" >
          </div>
        </div>
<div class="form-group col-md-6">
          <label class="control-label" for="fecha_final">Fecha final: </label>
          <div class="controls">
            <input id="fecha_final" onchange="DateCheck();" name="fecha_final" type="date" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
          </div>
        </div>

</div>


<div class="form-row form-group">

      
    

<div class="form-group col">
          <label for="motivo" class="control-label">Motivo:</label>
          <textarea name="motivo" id="motivo" class="form-control" data-validation="required letternumber" data-validation-allowing="-+/.,:;@#&'()_\n "></textarea>
        </div>


    

  </div>



       
        

        <div class="form-row form-group">
          

          <label for="archivo_documento" class="control-label">Archivo:</label>
          <input  type="file" name="archivo_documento" id="archivo_documento" class="form-control" data-validation="required mime size dimension" data-validation-allowing="jpg, png" data-validation-max-size="4M" data-validation-dimension="max1920" required="Seleccione una imagen" accept=".pdf,.application/pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required title="Seleccione un archivo" >
          
          
        </div>      
        <div class="form-group">
             
          <input type="hidden" type="text" value="" name="archivo_documento_nombre" id="archivo_documento_nombre" class="form-control" autofocus="autofocus" data-validation="required letter length" data-validation-allowing=" " data-validation-length="max100">
           <input type="hidden" type="text" value="" name="documento" id="documento" class="form-control" autofocus="autofocus" data-validation="required letter length" data-validation-allowing=" " data-validation-length="max100">
          
          
        </div>  
        <p>
      

        
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary pull-right" id="btn_nuevo">Registrar</button>
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
#horario_id{
    padding: 0; !important;
    height: 0; !important;
    width: 0; !important;
}
#materia_id{
    padding: 0; !important;
    height: 0; !important;
    width: 0; !important;
}
</style>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="assets/themes/concept/assets/vendor/multi-select/js/jquery.multi-select.js"></script>


<script>


$('#estudiante_id').on('click', function () {
    estudiante_id = $("#estudiante_id option:selected").val();
    $("#dvhorario").hide();
    $("#dvmateria").hide();
   
    $.ajax({
            url: '?/ins-permisos-administrador/procesos',
            type: 'POST',
            data: {
                'estudiante_id': estudiante_id,
                'accion': 'listar_materias',
            },
            dataType: 'JSON',
            success: function(item){
               // alert('ejemplo');
                //alert(resp[0]['id_catalogo_detalle']); 
                //console.log(r

                
            $("#categoria").val("");
                     
            



    $('#materia_id').selectize()[0].selectize.destroy();
    $('#materia_id').selectize({
        maxItems: 100,
        valueField: 'id_materia',
        labelField: 'nombre_materia',
        searchField: 'nombre_materia',

        options: item,
        create: false,
        render: {
        option: function(item, escape) {
            
       return '<option value="' + (item.id_materia ? item.id_materia : item.id_curso) + '">'  + ' ' + (item.nombre_materia ? item.nombre_materia : item.nombre_curso) + ' '  + (item.hora_ini ? item.hora_ini : item.horario_dia) + ' ' + (item.hora_ini ? item.hora_fin : item.horario_dia) + '</option>';
        




        }




    }
    });

    listar_horario();


            }
        });
});




</script>
<script>

 function listar_familiares() {
     
     estudiante_id = $("#estudiante_id option:selected").val()
     // alert('list nivel');
        $.ajax({
            url: '?/ins-permisos-administrador/procesos',
            type: 'POST',
            data: {
                'estudiante_id': estudiante_id,
                'accion': 'listar_familiares',
            },
            dataType: 'JSON',
            success: function(resp){
               // alert('ejemplo');
                //alert(resp[0]['id_catalogo_detalle']); 
                //console.log(resp);
                
                $("#familiar_id").html("");
                $("#familiar_id").append('<option value="' + 0 + '">Seleccionar familiar</option>');


                $.each(resp, function (index, value) {
                    // APPEND OR INSERT DATA TO SELECT ELEMENT.
                    $('#familiar_id').append('<option value="' + value.id_persona + '">' + value.primer_apellido + ' ' + value.segundo_apellido + ' ' + value.nombres + '</option>');
                });
                     
                
            }
        });
        
    }  


function listar_horario() {
     
     estudiante_id = $("#estudiante_id option:selected").val()
     // alert('list nivel');
$.ajax({
            url: '?/ins-permisos-administrador/procesos',
            type: 'POST',
            data: {
                'estudiante_id': estudiante_id,
                'accion': 'listar_horarios',
            },
            dataType: 'JSON',
            success: function(item){
               // alert('ejemplo');
                //alert(resp[0]['id_catalogo_detalle']); 
                //console.log(r

            



    $('#horario_id').selectize()[0].selectize.destroy();
    $('#horario_id').selectize({
        maxItems: null,
        valueField: 'id_horario_dia',
        labelField: 'hora_ini',
        searchField: 'hora_ini',

        options: item,
        create: false,
        render: {
        option: function(item, escape) {
            
       

        return '<option value="' + (item.id_horario_dia) + '">' + (item.hora_ini) + ' ' + (item.hora_fin) + ' </option>';
        




        }


    }
    });


            }
        });

     
    }  




function listar_materias() {
     
      estudiante_id = $("#estudiante_id option:selected").val();
    $("#dvhorario").hide();
    $("#dvmateria").hide();
   
    $.ajax({
            url: '?/ins-permisos-administrador/procesos',
            type: 'POST',
            data: {
                'estudiante_id': estudiante_id,
                'accion': 'listar_materias',
            },
            dataType: 'JSON',
            success: function(item){
               // alert('ejemplo');
                //alert(resp[0]['id_catalogo_detalle']); 
                //console.log(r

                
            $("#categoria").val("");
                     
            



    $('#materia_id').selectize()[0].selectize.destroy();
    $('#materia_id').selectize({
        maxItems: 100,
        valueField: 'id_materia',
        labelField: 'nombre_materia',
        searchField: 'nombre_materia',

        options: item,
        create: false,
        render: {
        option: function(item, escape) {
            
       return '<option value="' + (item.id_materia ? item.id_materia : item.id_curso) + '">'  + ' ' + (item.nombre_materia ? item.nombre_materia : item.nombre_curso) + ' '  + (item.hora_ini ? item.hora_ini : item.horario_dia) + ' ' + (item.hora_ini ? item.hora_fin : item.horario_dia) + '</option>';
        




        }




    }
    });




            }
        });
     
    }  


function settodo(){
    id=$("#categoria").val();
    
   
    if(id=='TODO'){

    $("#dvmateria").hide();
    $("#dvhorario").hide();   


/*
 estudiante_id = $("#estudiante_id option:selected").val()
     // alert('list nivel');
$.ajax({
            url: '?/ins-permisos-administrador/procesos',
            type: 'POST',
            data: {
                'estudiante_id': estudiante_id,
                'accion': 'listar_todas_materias',
            },
            dataType: 'JSON',
            success: function(item){   


    
    
                console.log("settodomaterias");
    var str_array_skills2 = item["materias"].split(',');
            var $select2 =   $('#materia_id').selectize();
            var selectize2 = $select2[0].selectize;
            selectize2.setValue(str_array_skills2);
            selectize2.refreshOptions();

  

        }
    });


$.ajax({
            url: '?/ins-permisos-administrador/procesos',
            type: 'POST',
            data: {
                'estudiante_id': estudiante_id,
                'accion': 'listar_todos_horarios',
            },
            dataType: 'JSON',
            success: function(item){   


    
    
                console.log("setstodo");
    var str_array_skills2 = item["horarios"].split(',');
            var $select2 =   $('#horario_id').selectize();
            var selectize2 = $select2[0].selectize;
            selectize2.setValue(str_array_skills2);
            selectize2.refreshOptions();

  

        }
    });

*/



    }
    if(id=='HORARIO'){
        $("#dvmateria").hide();
        $("#dvhorario").show();
    }
    if(id=='MATERIA'){
        $("#dvhorario").hide();
        $("#dvmateria").show();
    }
}








    function DateCheck()
{
  var StartDate= document.getElementById('fecha_inicio').value;
  var EndDate= document.getElementById('fecha_final').value;
  var eDate = new Date(EndDate);
  var sDate = new Date(StartDate);
  if(StartDate!= '' && StartDate!= '' && sDate> eDate)
    {
    alert("Por favor asegurese que la fecha final sea mayor a la inicial.");
    return false;
    }
}
    function setfec(){
    id=$("#tipo_contrato").val();
    
    $(".Indef").css({'display':'none'});

    if(id=='Plazo Fijo'){
        $(".Indef").css({'display':'block'});
    }
    if(id=='Servicio o Producto'){
        $(".Indef").css({'display':'block'});
    }
    
}

var $materia_id = $('#materia_id');





$(function () {

  var $horario_id = $('#horario_id');

$materia_id.selectize({
        maxOptions: 7,
        onInitialize: function () {
            $materia_id.show().addClass('materia_id');
        },
        onChange: function () {
            $materia_id.trigger('blur');
        },
        onBlur: function () {
            $materia_id.trigger('blur');
        }
    });
        
        $('form:first').on('reset', function () {
        $materia_id.get(0).selectize.clear();
    });

    $horario_id.selectize({
        maxOptions: 7,
        onInitialize: function () {
            $horario_id.show().addClass('horario_id');
        },
        onChange: function () {
            $horario_id.trigger('blur');
        },
        onBlur: function () {
            $horario_id.trigger('blur');
        }
    });
    
    $('form:first').on('reset', function () {
        $horario_id.get(0).selectize.clear();
    });


    $("#form_contrato").validate({
      rules: {
        nombres: {required: true},
        genero: {required: true}
        
      },
      errorClass: "help-inline",
      errorElement: "span",
      highlight: highlight,
      unhighlight: unhighlight,
      messages: {
        genero: "Debe ingresar un nombre ",
        genero: "elija su genero"
      },
      //una ves validado guardamos los datos en la DB
      submitHandler: function(form){

        var formData = new FormData($("#form_contrato")[0]);

        //var formData = new FormData($("#form_contrato"));
        //var files = $('#foto')[0].files[0];
        //formData.append('file',files);

         //var frmData = new FormData;
        //frmData.append("imagen",$("input[name=imagen]")[0].files[0]);
          //var datos = $("#form_contrato").serialize();
          $.ajax({
              type: 'POST',
              url: "?/ins-permisos-administrador/guardar",
              data: formData,
              cache: false,
              contentType: false,
              processData: false,  
            
              success: function (resp) {
                console.log(resp); 
                cont=0;
                switch(resp){

                  case '1':
                            dataTable.ajax.reload();
                            $("#modal_contrato").modal("hide");
                            alertify.success('Se registro el Permiso correctamente');
                            break;
                  case '2': dataTable.ajax.reload();
                            $("#modal_contrato").modal("hide"); 
                            alertify.success('Se editó el Permiso correctamente'); 
                            break;
                  case '3': dataTable.ajax.reload();
                            $("#modal_contrato").modal("hide"); 
                            alertify.warning('Ya existe un permiso en esas fechas'); 
                            break;
                }
              }
          });
      }
    })
  })


</script>













