<?php

// Obtiene la cadena csrf
$csrf = set_csrf();


// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);


$permiso_subir = in_array('subir', $_views);

?>
<form id="form_contrato" enctype="multipart/form-data"> 
<div class="modal fade" id="modal_contrato" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <h5 class="modal-title" id="exampleModalLabel">Agenda Institucional</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body">
                
        <input type="hidden" name="<?= $csrf; ?>">

 
        <div class="form-row form-group">
        <input id="id_agenda" name="id_agenda" type="hidden" class="form-control">

        

        <div class="control-group col-6 col-sm-6 col-lg-6">
              <label class="control-label">Titulo: </label>
              <div class="controls">
               
                
                <input id="titulo" name="titulo" type="text" class="form-control" placeholder="">
              </div>
            </div>
        <div class="form-group col-md-3">
      <label for="tipo_agenda"></label>Asueto:</label>
        <select name="tipo_agenda" id="tipo_agenda" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true" required title="Seleccione una opcion" >

            <option value="" selected="selected">Seleccionar</option>
            <option value="SI" >Si</option>
            <option value="NO" >No</option>
            
        </select>



    </div>

    <div class="form-group col-md-3">
      <label for="grupo"></label>Grupo:</label>
        <select name="grupo" id="grupo" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true" required title="Seleccione una opcion" >

            <option value="" selected="selected">Seleccionar</option>
            <option value="NACIONAL" >Nacional</option>
            <option value="INTERNACIONAL" >Internacional</option> 
            <option value="INSTITUCIONAL" >Institucional</option>           
        </select>
    </div>


        
          
        </div>




<div class="form-row form-group">
    <div class="form-group col-md-6">
                    <label for="descripcion" class="control-label">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" data-validation="required letternumber" data-validation-allowing="-+/.,:;@#&'()_\n "></textarea>

</div>
                    <div class="form-group col-md-3">
      <label for="prioridad"></label>Prioridad:</label>
        <select name="prioridad" id="prioridad" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true" required title="Seleccione una opcion" >

            <option value="" selected="selected">Seleccionar</option>
            <option value="alert alert-danger" class="alert alert-danger"  >Alta</option>
            <option value="alert alert-warning" class="alert alert-warning" >Media</option>
            <option value="alert alert-primary" class="alert alert-primary"  >Baja</option>
            
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







       
        

        <div class="form-row form-group">
          

          <label for="archivo_documento" class="control-label">Imagen
          :</label>
          <input  type="file" name="archivo_documento" id="archivo_documento" class="form-control" data-validation="required mime size dimension" data-validation-allowing="jpg, png" data-validation-max-size="4M" data-validation-dimension="max1920" required="Seleccione una imagen"  required title="Seleccione un archivo" >
          
          
        </div>      
        <div class="form-group">
             
          <input type="hidden" type="text" value="" name="archivo_documento_nombre" id="archivo_documento_nombre" class="form-control" autofocus="autofocus" data-validation="required letter length" data-validation-allowing=" " data-validation-length="max100">
           <input type="hidden" type="text" value="" name="documento" id="documento" class="form-control" autofocus="autofocus" data-validation="required letter length" data-validation-allowing=" " data-validation-length="max100">
          
          
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
    





$(function () {



$("#form_contrato").validate({
        rules: {
            titulo: {required: true}
            //id_gestion: {required: true}
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: highlight,
        unhighlight: unhighlight,
        messages: {
            titulo: "Debe ingresar un nombre la gestión"
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
              url: "?/s-agenda-institucional-feriados/guardar",
              data: formData,
              cache: false,
              contentType: false,
              processData: false,  
            
              success: function (resp) {
                console.log(resp); 
                cont=0;

                console.log("paso");
                switch(resp){
                    
                  case '1':

                            location.reload();
                            $("#modal_contrato").modal("hide");
                            alertify.success('Se Agendo correctamente');
                            break;
                  case '2': 
                            location.reload();
                            $("#modal_contrato").modal("hide"); 
                            alertify.success('Se editó la Agenda correctamente'); 
                            break;
                }
              }
          });
      }

      })
  })

</script>

























