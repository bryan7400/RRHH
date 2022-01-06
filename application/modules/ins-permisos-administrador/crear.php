<?php

// Obtiene la cadena csrf
$csrf = set_csrf();


// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);


$permiso_subir = in_array('subir', $_views);

?>
<form id="form_cliente" enctype="multipart/form-data"> 
<div class="modal fade" id="modal_cliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Área de Calificación</h5>
        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </a>
      </div>
      <div class="modal-body">
        
        <input type="hidden" name="<?= $csrf; ?>">
        <div class="form-group">
             <input id="id_cliente" name="id_cliente" type="hidden" class="form-control">
          <label for="nombres" class="control-label">Nombres:</label>
          <input type="text" value="" name="nombres" id="nombres" class="form-control" autofocus="autofocus" data-validation="required letter length" data-validation-allowing=" " data-validation-length="max100">
        </div>
        <div class="form-row form-group">
    <div class="col">
        
          <label for="primer_apellido" class="control-label">Apellido paterno:</label>
          <input type="text" value="" name="primer_apellido" id="primer_apellido" class="form-control" data-validation="letter length" data-validation-allowing=" " data-validation-length="max100" data-validation-optional="true">
        </div>
        <div class="col">
        
          <label for="segundo_apellido" class="control-label">Apellido materno:</label>
          <input type="text" value="" name="segundo_apellido" id="segundo_apellido" class="form-control" data-validation="required letter length" data-validation-allowing=" " data-validation-length="max100">
        </div>
        </div>
        

<div class="form-row form-group">

      <div class="form-group col-md-4">
      <label for="tipo_documento"></label> tipo de Documento:</label>
        <select name="tipo_documento" id="tipo_documento" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true">
            
            <option value="CI" selected="selected">CI</option>
            <option value="Pasaporte" >Pasaporte</option>
            <option value="Libreta Militar" >Libreta Militar</option>
            
        </select>
    </div>
    <div class="form-group col-md-6 ">
          <label for="numero_documento" class="control-label"></label>N# documento:</label>
          <input type="text" value="" name="numero_documento" id="numero_documento" class="form-control" data-validation="required alphanumeric length" data-validation-allowing="-+,() " data-validation-length="max100">
        </div>


    <div class="form-group col-md-2">
      <label for="expedido"></label> Exp:</label>
        <select name="expedido" id="expedido" class="form-control text-uppercase" data-validation="letternumber required" data-validation-allowing="-+./& " data-validation-optional="true">
            
            <option value="LP" selected="selected">LP</option>
            <option value="PT" >PT</option>
            <option value="OR" >OR</option>
            <option value="CB" >CB</option>
            <option value="CH" >CH</option>
            <option value="TJ" >TJ</option>
            <option value="BN" >BN</option>
            <option value="PA" >PA</option>
            <option value="SC" >SC</option>
        </select>
    </div>

  </div>
  <div class="form-row form-group">

      
    <div class="form-group col-md-6 ">
          <label for="celular" class="control-label"></label>N# Celular:</label>
          <input type="number" value="" name="celular" id="celular" class="form-control" data-validation="required alphanumeric length" data-validation-allowing="-+,() " data-validation-length="max100">
        </div>

        <div class="form-group col-md-6 ">
          <label for="email" class="control-label"></label>Email:</label>
          <input type="email" value="" name="email" id="email" class="form-control" >
        </div>


    

  </div>
        
    <div class="form-group" >
        <label for="genero" class="col-form-label">Genero: </label><br>
                 
        <label class="custom-control custom-radio custom-control-inline">
        <input type="radio" name="genero" class="custom-control-input" id="contutor" value="v" ><span class="custom-control-label" >Masculino</span>
        </label>
        
        <label class="custom-control custom-radio custom-control-inline">
        <input type="radio" name="genero"  class="custom-control-input" id="sintutor" value="m"><span class="custom-control-label" >Femenino</span>
        </label>
    </div>
        

<div class="control-group margen">
          <label class="control-label">Fecha Nacimiento: </label>
          <div class="controls">
            <input id="fecha_nacimiento" name="fecha_nacimiento" type="date" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
          </div>
        </div>

        
        
        <div class="form-group">
          <label for="direccion" class="control-label">Dirección:</label>
          <textarea name="direccion" id="direccion" class="form-control" data-validation="required letternumber" data-validation-allowing="-+/.,:;@#&'()_\n "></textarea>
        </div>
        <div class="form-group">
          <label for="foto" class="control-label">Foto:</label>
          <input  id="foto" type="file" name="foto" id="foto" class="form-control" data-validation="required mime size dimension" data-validation-allowing="jpg, png" data-validation-max-size="4M" data-validation-dimension="max1920">
         
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
$(function () {
    $("#form_cliente").validate({
      rules: {
        nombres: {required: true}
      },
      errorClass: "help-inline",
      errorElement: "span",
      highlight: highlight,
      unhighlight: unhighlight,
      messages: {
        nombres: "Debe ingresar un nombre "
      },
      //una ves validado guardamos los datos en la DB
      submitHandler: function(form){

        var formData = new FormData($("#form_cliente")[0]);

        //var formData = new FormData($("#form_cliente"));
        //var files = $('#foto')[0].files[0];
        //formData.append('file',files);

         //var frmData = new FormData;
        //frmData.append("imagen",$("input[name=imagen]")[0].files[0]);
          //var datos = $("#form_cliente").serialize();
          $.ajax({
              type: 'POST',
              url: "?/s-area-prueba/guardar",
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
                            $("#modal_cliente").modal("hide");
                            alertify.success('Se registro el Cliente correctamente');
                            break;
                  case '2': dataTable.ajax.reload();
                            $("#modal_cliente").modal("hide"); 
                            alertify.success('Se editó el Cliente correctamente'); 
                            break;
                }
              }
          });
      }
    })
  })
</script>