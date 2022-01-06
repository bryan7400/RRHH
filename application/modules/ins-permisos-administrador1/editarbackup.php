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

$estudiantes = $db->query("SELECT e.id_estudiante,ins.id_inscripcion,ins.aula_paralelo_id,au.nombre_aula,pa.nombre_paralelo,ni.nombre_nivel,tu.nombre_turno, per.id_persona, per.nombres, per.primer_apellido, per.segundo_apellido, per.tipo_documento, per.numero_documento, per.complemento, per.expedido, per.genero, per.fecha_nacimiento, per.direccion, IF(per.foto != 'NULL', IF(per.foto !='',per.foto,''),'')AS foto,f.id_familiar, su.id_user, su.rol_id
            from ins_estudiante_familiar ef INNER JOIN ins_familiar  f ON ef.familiar_id=f.id_familiar
            INNER JOIN ins_estudiante  e ON e.id_estudiante=ef.estudiante_id
            INNER JOIN sys_persona  per ON per.id_persona=e.persona_id
            INNER JOIN sys_users su ON su.persona_id = per.id_persona
            LEFT  JOIN ins_inscripcion ins ON ins.estudiante_id  = e.id_estudiante
            LEFT  JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo  = ins.aula_paralelo_id
            INNER JOIN ins_aula au ON au.id_aula=ap.aula_id
            INNER JOIN ins_paralelo pa ON pa.id_paralelo=ap.paralelo_id
            INNER JOIN ins_nivel_academico ni ON ni.id_nivel_academico=au.nivel_academico_id
            INNER JOIN ins_turno tu ON tu.id_turno=ap.turno_id
            WHERE  ins.estado = 'A'  AND su.estado = 'A' AND su.visible = 's' ORDER BY primer_apellido")->fetch();          
            

            
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
</style>

<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">

 
 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">  


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
                
        <input type="hidden" name="<?= $csrf; ?>">


<div class="form-row">

    <div class="col">
        <label for="estudiante_id"><label style="color:red">*</label>Estudiante:</label>
    
    <select name="estudiante_id" id="estudiante_id" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " onchange="listar_materias();listar_familiares();" data-validation-optional="true" >
        <option value="" selected="selected">Buscar</option>
        <?php 
        

        foreach ($estudiantes as $estudiante) { ?>
            <option value="<?= escape($estudiante['id_estudiante']); ?>"> <?= escape($estudiante['primer_apellido']); ?>  <?= escape($estudiante['segundo_apellido']); ?> <?= escape($estudiante['nombres']); ?></option>
        <?php } ?>
    </select>
    </div>

    <div class="col">
        <label for="familiar_id"><label style="color:red">*</label>Familiar:</label>
    
    <select name="familiar_id" id="familiar_id" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " onchange="setestudiantes();" data-validation-optional="true" >
        <option value="" selected="selected">Buscar</option>
        
    </select>

    <select name="materias2" id="materias2" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " onchange="setestudiantes();" data-validation-optional="true" >
        <option value="" selected="selected">Buscar</option>
        
    </select>
    </div>


</div>


<div class="form-row">
    <div class="col">
      <label for="tipo_documento"><label style="color:red">*</label></label>Categoria:</label>
        <select name="tipo_documento" id="tipo_documento" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true" required title="Seleccione una opcion" >
            <option value="" selected="selected">Seleccionar</option>
            <option value="Contrato" >Todo</option>
            <option value="Reglamento" >Horario</option>
            <option value="Poliza" >Materia</option>
        </select>
    </div>
    <div class="col">
    <label for="materias"><label style="color:red">*</label>Materia:</label>
    
    <select name="materias" id="materias" class="form-control" autofocus="autofocus" multiple="multiple" data-validation="required length" data-validation-allowing="," data-validation-length="max100">
        <option value="" selected="selected">Buscar</option>
         <?php    
            foreach ($materias as $materia) { ?>
            <option value="<?= escape($materia['id_materia']); ?>"><?= escape($materia['nombre_materia']); ?></option>
        <?php } ?>

    </select>    
</div>

    <div class="col ">
        <label for="categoria_medico"><label style="color:red">*</label>Horarios:</label>
        <select name="categoria_medico" id="categoria_medico" class="form-control text-uppercase" onchange="setcargo();" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true">
            <option value="" selected="selected">Seleccionar</option>
        <?php    
            foreach ($materias as $materia) { ?>
            <option value="<?= escape($materia['id_materia']); ?>"><?= escape($materia['nombre_materia']); ?></option>
        <?php } ?>
            
        </select>
    </div>
</div>
<div class="form-row">
    



    
    <div class="col">
        <label for="tipo_contrato"><label style="color:red">*</label></label>Tipo de Permiso:</label>
        <select onchange="setfec();" name="tipo_contrato" id="tipo_contrato" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true" required title="Seleccione una opcion" >
            
            <option value="" selected="selected">Seleccionar</option>
            <option value="Indefinido" >Salud</option>
            <option value="Plazo Fijo" >institucional</option>
            <option value="Consultoria" >Personal</option>
            <option value="Servicio o Producto" >Otro</option>
            
        </select>
        </div>

        <div class="col">
        <label for="tipo_contrato"><label style="color:red">*</label></label>Grupo:</label>
        <select onchange="setfec();" name="tipo_contrato" id="tipo_contrato" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true" required title="Seleccione una opcion" >
            
            <option value="" selected="selected">Seleccionar</option>
            <option value="PERSONAL" >Personal</option>
            <option value="GRUPAL" >grupal</option>
            <option value="CURSO" >Curso</option>
            
        </select>
        </div>

</div>


 <div id="nivel_aca" class="form-row form-group">
    
        

        
        <div  class="col ItIs ItIsTeacher ItIsTeacherAdmin" >
    <label for="nivel_academico">Nivel Academico:</label>
    
    <select name="nivel_academico" id="nivel_academico" class=""  required title="Seleccione una opcion" >
        <option value="" selected="selected">Seleccionar</option>
        <?php 
        $nivels = $db->select('z.*')
                        ->from('ins_nivel_academico z')
                        ->order_by('z.nombre_nivel', 'asc')
                        ->fetch();

        foreach ($nivels as $nivel) { ?>
            <option value="<?= escape($nivel['id_nivel_academico']); ?>"><?= escape($nivel['nombre_nivel']); ?></option>
        <?php } ?>
    </select>       
</div>




        </div>




        <div class="form-row form-group">
        

        



    <div class="col">
        <label for="estudiante_id"><label style="color:red">*</label>Contratos:</label>
    
    <select name="estudiante_id" id="estudiante_id" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " onchange="setestudiantes();" data-validation-optional="true" >
        <option value="" selected="selected">Buscar</option>
        <?php 
        

        foreach ($contratos as $contrato) { ?>
            <option value="<?= escape($contrato['contrato_id']); ?>"><?= escape($contrato['tipo_contrato']); ?> <?= escape($contrato['fecha_inicio']); ?> Al <?= escape($contrato['fecha_final']); ?></option>
        <?php } ?>
    </select>
    </div>    



        

        <div class="form-group col">
        <label for="tipo_contrato"><label style="color:red">*</label></label>Seguimiento:</label>
        <select onchange="setfec();" name="tipo_contrato" id="tipo_contrato" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true" required title="Seleccione una opcion" >
            
            <option value="" selected="selected">Seleccionar</option>
            <option value="SEGUIMIENTO" >Seguimiento</option>
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
      <select class="selectpicker">
  <option>Mustard</option>
  <option>Ketchup</option>
  <option>Barbecue</option>
</select>  

        <input class="authorsearch" id="Authors" name="Authors" type="text" value="" />
<select class="selectpicker" multiple data-live-search="true">
  <option>Mustard</option>
  <option>Ketchup</option>
  <option>Relish</option>
</select>


<div class="container">  
    <strong>Select Language:</strong>  
    <select id="multiple-checkboxes" multiple="multiple">  
        
    </select>  
</div>  
  

        
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
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="assets/themes/concept/assets/vendor/multi-select/js/jquery.multi-select.js"></script>


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


    function listar_2materias() {
     
     estudiante_id = $("#estudiante_id option:selected").val()
     // alert('list nivel');

     $('#authorsearch').selectize({
    valueField: 'ID',
    labelField: 'PO',
    searchField: 'PO',
    create: false,
    options: [],
    render: {
        option: function (item, escape) {
            return '<div>' + item.PO + ' ' + escape(item.PO) '</div>';
        }
    },
    load: function (query, callback) {

        if (!query.length) return callback();

        var dataString = JSON.stringify({
        prefixText: query
        });

        $.ajax({
        type: "POST",
        url: "Default.aspx/GetUsers",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        async: false,
        data: dataString,
        error: function () {
            callback();
        },
        success: function (msg) {
            alert(msg.d);
            callback(msg.d);


        }
        });
    }
    });
      
        
    }  



 function listar_materias() {
     
    estudiante_id = $("#estudiante_id option:selected").val();
   $(".authorsearch").text(estudiante_id);
     // alert('list nivel');
       var $select = $('.authorsearch').selectize({
    valueField: 'id_materia',
    labelField: 'nombre_materia',
    searchField: ['id_estudiante'],
    maxOptions: 10,
    create: function (input, callback) {
        $.ajax({

            url: '?/ins-permisos-administrador/procesos',
            type: 'POST',
            data: {
                'estudiante_id': estudiante_id,
                'accion': 'listar_familiares',
            },
            dataType: 'JSON',
            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    },
    render: {
        option: function (item, escape) {
            return '<div>' + escape(item.nombre_materia) + ' ' + escape(item.hora_ini) + ' ' + escape(item.hora_fin) + '</div>';
        }
    },
    load: function (query, callback) {
        if (!query.length) return callback();
        $.ajax({

            url: '?/ins-permisos-administrador/procesos',
            type: 'POST',
            dataType: 'json',
            data: {
                'estudiante_id': estudiante_id,
                'accion': 'listar_materias',
            },
            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    }
});
        
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

var $materias = $('#materias');

function setcargo(){
    id=$("#area_contrato").val();
    
    $(".ItIs").css({'display':'none'});

    if(id=='Administrativo'){
        $(".ItIsAdmin").css({'display':'block'});
    }
    if(id=='Docente'){
        $(".ItIsTeacher").css({'display':'block'});
    }
    if(id=='Docente'){
        $(".ItIsTeacherAdmin").css({'display':'block'});
    }
}



$(function () {

  var $nivel_academico = $('#nivel_academico');

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
              url: "?/rrhh-contratos/guardar",
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
                            alertify.success('Se registro el Contrato correctamente');
                            break;
                  case '2': dataTable.ajax.reload();
                            $("#modal_contrato").modal("hide"); 
                            alertify.success('Se editó el Contrato correctamente'); 
                            break;
                }
              }
          });
      }
    })
  })


</script>













