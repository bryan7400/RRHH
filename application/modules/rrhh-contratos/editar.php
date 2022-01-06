<?php

// Obtiene la cadena csrf
$csrf = set_csrf();


// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);


$permiso_subir = in_array('subir', $_views);

?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>



<form id="form_contrato" enctype="multipart/form-data"> 
<div class="modal fade" id="modal_contrato" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <h5 class="modal-title" id="exampleModalLabel">Área de Contrato</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body">
                
        <input type="hidden" name="<?= $csrf; ?>">

 
        <div class="form-row form-group">
        <input id="id_contrato" name="id_contrato" type="hidden" class="form-control">

        <div class="form-group col">
        <label for="area_contrato"></label>Area de Contrato:</label>
        <select name="area_contrato" id="area_contrato" class="form-control text-uppercase" data-validation="required" data-validation-optional="true" onchange="setcargo();" name="area_contrato" id="area_contrato" class="form-control text-uppercase" required title="Seleccione una opcion" >

            <option value="" selected="selected">Seleccionar</option>
            <option value="Docente">Docente</option>
            <option value="Administrativo" >Administrativo</option>
            <option value="Estudiante" >Estudiante</option>
            
        </select>
        </div>


        <div class="form-group col">
        <label for="tipo_contrato"></label>Tipo de Contrato:</label>
        <select onchange="setfec();" name="tipo_contrato" id="tipo_contrato" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true" required title="Seleccione una opcion" >
            
            <option value="" selected="selected">Seleccionar</option>
            <option value="Indefinido" >Indefinido</option>
            <option value="Plazo Fijo" >Plazo Fijo</option>
            <option value="Consultoria" >Consultoria</option>
            <option value="Servicio o Producto" >Servicio o producto</option>
            
        </select>
        </div>
          
        </div>




<div class="form-row form-group">

      <div class="form-group col-md-6">
      <label for="modalidad_contrato"></label>Modalidad:</label>
        <select name="modalidad_contrato" id="modalidad_contrato" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true" required title="Seleccione una opcion" >

            <option value="" selected="selected">Seleccionar</option>
            <option value="Tiempo Completo" >Tiempo Completo</option>
            <option value="Medio tiempo" >Medio tiempo</option>
            <option value="Servicio o Producto" >Otro</option>
            
        </select>
    </div>
    


    <div class="form-group col-md-6">
      <label for="tipo_documento"></label> Tipo Documento:</label>
        <select name="tipo_documento" id="tipo_documento" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true" required title="Seleccione una opcion" >
            <option value="" selected="selected">Seleccionar</option>
            <option value="Contrato" >Contrato</option>
            <option value="Reglamento" >Reglamento</option>
            <option value="Poliza" >Poliza</option>
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
<div class="form-group col-md-6 Indef">
          <label class="control-label" for="fecha_final">Fecha final: </label>
          <div class="controls">
            <input id="fecha_final" onchange="DateCheck();" name="fecha_final" type="date" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
          </div>
        </div>

</div>



<div id="nivel_aca" class="form-row form-group">
    
        
<div  class="col ItIs ItIsTeacher ItIsTeacherAdmin" >
    <label for="nivel_academico">Nivel Academico:</label>
    
    <select name="nivel_academico[]" id="nivel_academico" class="" multiple="multiple" required title="Seleccione una opcion" >
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
          

          <label for="archivo_documento" class="control-label">Archivo:</label>
          <input  type="file" name="archivo_documento" id="archivo_documento" class="form-control" data-validation="required mime size dimension" data-validation-allowing="jpg, png" data-validation-max-size="4M" data-validation-dimension="max1920" required="Seleccione una imagen" accept=".pdf,.application/pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required title="Seleccione un archivo" >
          
          
        </div>      
        <div class="form-group">
             
          <input type="hidden" type="text" value="" name="archivo_documento_nombre" id="archivo_documento_nombre" class="form-control" autofocus="autofocus" data-validation="required letter length" data-validation-allowing=" " data-validation-length="max100">
           <input type="hidden" type="text" value="" name="documento" id="documento" class="form-control" autofocus="autofocus" data-validation="required letter length" data-validation-allowing=" " data-validation-length="max100">
          
          
        </div>  
        
       <div
    class="g-recaptcha"
    data-sitekey="6LcKS7wdAAAAALOIflsirUl4khgkwp9PddZlOVXk">
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

<script>

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

























