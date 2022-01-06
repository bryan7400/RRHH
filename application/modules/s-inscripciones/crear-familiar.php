<?php
  $csrf = set_csrf(); 
  
?>
<form id="form_familiar"> 
<!-- Modal para agregar_familiares -->
<div  class="modal fade" id="modal_familiar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><span id="titulo_modal_familiar"></span></h5>
        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </a>
      </div>
      <div class="modal-body">
           <div class="row">
               <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="control-group">
                        <label class="control-label">Nombres: </label>
                        <div class="controls">
                            <input type="hidden" name="<?= $csrf; ?>">
                            <input type="hidden" class="form-control" id="id_familiar" name="id_familiar">
                            <input type="hidden" class="form-control" id="id_persona" name="id_persona">						
                            <input id="nombres" name="nombres" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="control-group margen">
                        <label class="control-label">Primer Apellido: </label>
                        <div class="controls">
                            <input id="primer_apellido" name="primer_apellido" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="control-group margen">
                        <label class="control-label">Segundo Apellido: </label>
                        <div class="controls">
                            <input id="segundo_apellido" name="segundo_apellido" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="control-group margen">
                        <label class="control-label">Tipo de Documento: </label> 
                        <div class="controls">
                            <!-- <input id="tipo_documento" name="tipo_documento" type="text" class="form-control"> -->
                            <select id="tipo_documento" name="tipo_documento" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="1">CI</option>
                                <option value="2">Pasaporte</option>
                                <option value="3">CI extranjero</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group margen">
                        <label class="control-label">Número Documento: </label>
                        <div class="controls">
                            <input id="numero_documento" name="numero_documento" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="control-group margen">
                        <label class="control-label">Complemento: </label>
                        <div class="controls">
                            <input id="complemento" name="complemento" type="text" class="form-control">
                        </div>
                    </div>
                </div>
                <!-- </div>       
                <div class="row"> -->
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="control-group">
                        <label class="control-label">Género: </label>
                        <div class="controls">
                            <!-- <input id="genero" name="genero" type="text" class="form-control"> -->            
                            <select name="genero" id="genero" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="v">Varon</option>
                                <option value="m">Mujer</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group margen">
                        <label class="control-label">Fecha de Nacimiento: </label>
                        <div class="controls">						
                            <input id="fecha_nacimiento" name="fecha_nacimiento" type="date" class="form-control">
                        </div>
                    </div>
                    <div class="control-group margen">
                        <label class="control-label">Correo Electrónico: </label>
                        <div class="controls">
                            <input id="correo_electronico" name="correo_electronico" type="email" class="form-control">
                        </div>
                    </div>
                    <div class="control-group margen">
                        <label class="control-label">Teléfono: </label>
                        <div class="controls">
                            <input id="telefono" name="telefono" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="control-group margen">
                        <label class="control-label">Ocupación: </label> 
                        <div class="controls">
                            <input id="profesion" name="profesion" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="control-group margen">
                        <label class="control-label">Dirección de Oficina: </label>
                        <div class="controls">
                            <input id="direccion_oficina" name="direccion_oficina" type="text" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary pull-right" id="btn_agregar_familiar" onclick="agregar_familiar();">Guardar</button>
            
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
<script>
function agregar_familiar(){
    var parametros = {
        'nombres': $("#nombres").val(),
        'primer_apellido': $("#primer_apellido").val(),
        'segundo_apellido': $("#segundo_apellido").val(),
        'tipo_documento': $("#tipo_documento option:selected").val(),
        'numero_documento': $("#numero_documento").val(),
        'complemento': $("#complemento").val(),
        'genero': $("#genero option:selected").val(),
        'fecha_nacimiento': $("#fecha_nacimiento").val(),
        'telefono': $("#telefono").val(),
        'profesion': $("#profesion").val(),
        'direccion_oficina': $("#direccion_oficina").val(),
        'id_estudiante': '<?= $id_estudiante?>',
        'boton': 'agregar_familiar'
    }
    $.ajax({
        url: '?/s-inscripciones/procesos',
        type: 'POST',
        data: parametros,
        success: function(resp){
            //alert(resp);
            console.log(resp);
            switch(resp){
                case '1': alertify.success('Se registro el familiar correctamente');
                            $('.modal_familiar').modal('hide');
                            listar_familiares(<?= $id_estudiante?>); break; 
                case '2': alertify.error('No se pudo registrar al familiar del estudiante'); break;
                case '3': alertify.error('No se pudo registrar al familiar'); break; 
                case '4': alertify.error('No se pudo registrar los datos personales del familiar'); break;
            }
        }
    });
}
// $(function () {
//     $("#form_familiar").validate({
//       rules: {
//         nombres: {required: true},
//         //primer_apellido: {required: true},
//         segundo_apellido: {required: true},
//         tipo_documento: {required: true},
//         numero_documento: {required: true},
//         complemento: {required: true},
//         genero: {required: true},
//         fecha_nacimiento: {required: true},
//         correo_electronico: {required: true},
//         telefono: {required: true},
//         profesion: {required: true},
//         direccion_oficina: {required: true}
//       },
//       errorClass: "help-inline",
//       errorElement: "span",
//       highlight: highlight,
//       unhighlight: unhighlight,
//       messages: {
//         nombres: "Debe ingresar nombre(s) de familiar",
//         //primer_apellido: "Debe ingresar primer apellido",
//         segundo_apellido: "Debe ingresar segundo apellido",
//         tipo_documento: "Debe ingresar tipo de documento",
//         numero_documento: "Debe ingresar número de documento",
//         complemento: "Debe ingresar complemento",
//         genero: "Debe ingresar género",
//         fecha_nacimiento: "Debe ingresar fecha de nacimeinto",
//         correo_electronico: "Debe ingresar correo electrónico",
//         telefono: "Debe ingresar teléfono",
//         profesion: "Debe ingresar profesión",
//         direccion_oficina: "Debe ingresar dirección oficina"
//       },
//       //una ves validado guardamos los datos en la DB
//       submitHandler: function(form){
//           var datos = $("#form_familiar").serialize();
//           $.ajax({
//               type: 'POST',
//               url: "?/s-inscripciones/procesos",
//               data: datos,
//               success: function (resp) {
//                 console.log(resp); 
//                 switch(resp){
//                   case '1': dataTable.ajax.reload();
//                             $("#modal_inscripcion").modal("hide");
//                             alertify.success('Se registro el familiar correctamente');
//                             break;
//                   case '2': dataTable.ajax.reload();
//                             $("#modal_inscripcion").modal("hide"); 
//                             alertify.success('Se editó el familiar correctamente'); 
//                             break;
//                 }
//               }
//           });
//       }
//     })
//   })
</script>