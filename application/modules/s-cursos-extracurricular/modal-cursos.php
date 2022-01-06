
<form id="form_curso">
<div class="modal fade" id="modal_curso" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="exampleModalLabel">Registrar curso</h3>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
				<!--VARAIBLES DE FORMULARIO-->
				<input id="id_curso" type="hidden"  name="id_curso" >
				<input  type="hidden"  name="accion"  value="guardar_curso"> 
			</div>
			<div class="modal-body">
  
				 <div class="control-group" style="margin-button:15px">
					<label class="control-label">Nombre curso: </label>
					<div class="controls">
						<input type="text" class="form-control"  name="nombre" id="nombre"> 
					</div>
				</div>
				 
				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Cupo minimo: </label>
					<div class="controls">
						<input type="number" class="form-control"   name="cupo"> 
					</div>
				</div>
				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Objetivo curso: </label>
					<div class="controls">
						<input type="text" class="form-control"   name="objetivo"> 
					</div>
				</div>
				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Descripcion: </label>
					<div class="controls">
						<textarea class="form-control" rows="3" name="descripcion"></textarea>
					</div>
				</div>
				 
				<div class="control-group container areaCategoria" style="margin-button:15px">
					<label class="control-label">categoria: </label>
					<div class="controls row selCategoria">
						<select name="categoria" id="selCategoriaCurso"  class="form-control col-10 selCategoria" >
						    <option value="">Seleccione categoria</option>
						    <option value="">Categoria 1</option>
						    <option value="">Categoria 2</option>
						</select>
					 <span class="btn btn-success col-2 fa fa-plus btnnewCategoria" title="Nueva categoria" onclick="$('#modal_categoria').modal('show');"> </span>
					</div> 
				</div>

				
				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Imagen de curso: </label>
					<div class="controls">
						 <input type="file" class="form-control"   name="file" > 
					</div>
				</div>  
								
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-light pull-left" data-dismiss="modal">Cancelar</button>
				<button type="submit" class="btn btn-primary pull-left">Registrar</button>
			</div>
		</div>
	</div>
</div>
</form>
<script>
$("#form_curso").validate({
    rules : { 
		nombre:{required:true},
		objetivo:{required:true},
        categoria:{required:true}
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
        nombre: "Debe ingresar un nombre.",
		objetivo: "Debe ingresar un objetivo.",
		categoria: "Debe seleccionar categoria."        
    },
    //una ves validado guardamos los datos en la DB
  	submitHandler: function(form){
       //var datos = $("#form_felicitacion").serialize();     
      //datos=datos+'&accion=guardar_kardex';
 
      var form_data = new FormData($("#form_curso")[0]); 
      $.ajax({
          type: 'POST',  url: "?/s-cursos-extracurricular/procesos",  data: form_data,
          cache: false, contentType: false, processData: false,  dataType: 'JSON', 
          
          success: function (resp) {
           var estado=resp['estado']+'';
           var id_curso=resp['id_curso'];
             //alert(estado+' id-curs'+id_curso);
            switch(estado){
              case '1': //listarcursos();// Creado
                    $('[name=id_curso]').val(id_curso);
                        $("#modal_curso").modal("hide");
                        alertify.success('Se registro correctamente');
                    //mensaje de hablitar evento
                    //listarCategoria();
                    alertify.confirm('<span style="color:blue">Desea activar el evento?</span>', 'Esta accion habilitara las inscripciones, asignara un ubicacion, un expositor y hora del curso. ¿Desea Activarlo?', function(){  
                         $("#modal_asignacion").modal("show");   
                     }, function(){ 
                          alertify.notify('No activado', 'custom');
                          //alertify.notify('custom message.', 'custom', 20);
                         //alertify.error('Cancel');

                     });
                        listarcursos();volver();$("#form_curso")[0].reset();
                        break;
              case '2': //actualisado
                        $("#modal_curso").modal("hide");
                        alertify.success('Edicion correcta'); 
                        //actualisar cards y ver
                        listarcursos();volver();$("#form_curso")[0].reset();
                        break;
              case '10': //error otro
                        //$("#modal_categoria").modal("hide");
                        alertify.warning('Ingrese datos validos');
                        break;
              default: //dataTable.ajax.reload();
                        //$("#modal_categoria").modal("hide");
                        alertify.error('Operacion fallida');
                        break;
            }
            
          }          
      });    
  }

});//ok guarda  X editar
</script>


<form id="form_categoria">
    <div class="modal fade" id="modal_categoria" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content sobremodal">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Registrar categoria</h4>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
				<!--variables de formulario-->
				<input id="id_cat" type="hidden"  name="id_cat" >
				<input  type="hidden"  name="accion"  value="guardar_categoria"> 
			</div>
			<div class="modal-body">

				 <!--<input type="hidden" class="form-control" id="tipoFelSanc" name="tipoFelSanc" placeholder="tipoFelSanc">
				 <input type="hidden" class="form-control" id="id_persona" name="id_persona" placeholder="id_persona">-->
				  
				 <div class="control-group" style="margin-button:15px">
					<label class="control-label">Nombre: </label>
					<div class="controls">
						<input type="text" class="form-control" id="nombre" name="nombre" placeholder="Escribe nombre" required> 
					</div>
				</div>
				  
				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Descripcion: </label>
					<div class="controls">
						<textarea class="form-control"  rows="3" name="descripcion"></textarea>
					</div>
				</div>   
				<div class="control-group d-none" style="margin-button:15px">
					<label class="control-label">Imagen de categoria: </label>
					<div class="controls">
						<input type="file"   class="form-control  " name="file" >
					</div>
				</div> 			
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-light pull-left" data-dismiss="modal">Cancelar</button>
				<button type="submit" class="btn btn-warning pull-left btn-registrar">Registrar</button><span  class="btn btn-secondary pull-left btn-eliminar" onclick="elim_categoria()">Eliminar</span>
			</div>
		</div>
	</div>
</div>
</form>
 <style>
    .sobremodal{
        background: crimson; 
        box-shadow: -5px 32px 40px rgba(0, 0, 0, 0.73);
    }
    .sobremodal label,.sobremodal h1,.sobremodal h2,.sobremodal h4,.sobremodal h3,.sobremodal h5,.sobremodal h6,.sobremodal p,.sobremodal span{
        color: white; 
        
    } 
</style> 
<script>
$("#form_categoria").validate({
    rules : { 
		id_docente:{required:true},
		descripcion:{required:true},
        fecha_felicitacion:{required:true}
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
        id_docente: "Debe seleccionar un docente.",
		descripcion: "Debe ingresar una felicitacion.",
		fecha_felicitacion: "Debe ingresar la fecha."        
    },
    //una ves validado guardamos los datos en la DB
  	submitHandler: function(form){
      //alert();
      //var datos = $("#form_felicitacion").serialize();     
      //datos=datos+'&accion=guardar_kardex';
 
      var form_data = new FormData($("#form_categoria")[0]); 
      $.ajax({
          type: 'POST',  url: "?/s-cursos-extracurricular/procesos",  data: form_data,
          cache: false, contentType: false, processData: false,   datatype: 'text',
          
          success: function (resp) {
            console.log(resp);
            switch(resp){
              case '1': //listarcursos();// Creado
                        $("#modal_categoria").modal("hide");
                        alertify.success('Se registro correctamente');
                        listarCategoria();$("#form_categoria")[0].reset();
                        break;
              case '2': //actualisado
                        $("#modal_categoria").modal("hide");
                        alertify.success('Edicion correcta'); 
						listarCategoria();$("#form_categoria")[0].reset();
                        break;
              case '10': //error otro
                        //$("#modal_categoria").modal("hide");
                        alertify.warning('Ingrese una fecha valida');
                        break;
              default: //dataTable.ajax.reload();
                        //$("#modal_categoria").modal("hide");
                        alertify.error('Operacion fallida');
                        break;
            }
            
          }          
      });    
  }

});//ok guarda  X editar
</script>
 

<form id="form_asignacion">
<div class="modal fade" id="modal_asignacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Asignacion</h4>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
				<input type="hidden" id="id_asigcurso"  name="id_asigcurso" placeholder="id_asignacion">
				<input type="hidden"  name="accion"  value="guardar_asignacion"> 
			</div>
			<div class="modal-body">

				 <input type="hidden" class="form-control id_curso" name="id_curso" placeholder="id_curso">
				 <!--<input type="hidden" class="form-control" id="id_persona" name="id_persona" placeholder="id_persona">-->
				 
			<center><h4>EJECUCION DEL CURSO</h4></center>
				 <div class="row">
				     
				<div class="control-group  col-6" style="margin-button:15px">
                 <label class="control-label">  Fecha inicio: </label>
                 <div class="input-group">
                  <div class="input-group-prepend">
                    <button class="btn btn-success" type="button"><span class="fa fa-calendar "></span></button>
                  </div>
                  <input type="date" class="form-control" placeholder="Fecha inicio" aria-label="" aria-describedby="basic-addon1"  name="fechaini" required>
                 </div>
                </div>
				<!--<div class="input-group">
					<label class="control-label"><span class="fa fa-calendar "></span> Fecha inicio: </label>
					<div class="controls">
						<input type="date" class="form-control"   name="fechaini"> 
					</div>
				</div>-->
				<div class="control-group  col-6" style="margin-button:15px">
                 <label class="control-label">  Fecha fin: </label>
                 <div class="input-group">
                  <div class="input-group-prepend">
                    <button class="btn btn-success" type="button"><span class="fa fa-calendar "></span></button>
                  </div>
                  <input type="date" class="form-control" placeholder="Fecha inicio" aria-label="" aria-describedby="basic-addon1"  name="fechafin" >
                 </div>
                </div>
                <div class="control-group  col-6" style="margin-button:15px">
                     <label class="control-label"> Hora inicio: </label>
                     <div class="input-group">
                      <div class="input-group-prepend">
                        <button class="btn btn-success" type="button"><span class="fa fa-history "></span></button>
                      </div>
                      <input type="time" class="form-control" placeholder="Fecha inicio"   name="horaini"  required>
                     </div>
                 </div>  
                 <div class="control-group  col-6" style="margin-button:15px">
                     <label class="control-label"><span class="fa fa-expand "></span> Duracion (en horas): </label>
                     <div class="input-group">
                      <div class="input-group-prepend">
                          <span class="fa fa-history btn btn-success"></span> 
                      </div>
                      <input type="number" class="form-control" placeholder="horas"   name="duracion">
                     </div>
                 </div>  
                 <!--<div class="control-group  col-4" style="margin-button:15px">
					<label class="control-label"><span class="fa fa-expand "></span> Duracion (en horas): </label>
					<div class="controls"> 
						<input type="text" class="form-control"  name="duracion"> 
					</div>
				</div>-->
				
				<div class="control-group col-4" style="margin-button:15px">
					<!--<label class="control-label"><span class="fa fa-map-marker "></span> Ambiente: </label>
					<div class="controls">
						<input type="text" class="form-control"  name="ambiente"> 
					</div>-->
					<div class="input-group  pt-2">
                      <div class="input-group-prepend">
                         <span class="fa fa-map-marker btn-info btn"></span> 
                      </div>
                      <input type="text" class="form-control" placeholder="Ambiente"    name="ambiente">
                     </div>
				</div>
				 
               <div class="control-group col-4" style="margin-button:15px">
					 <div class="input-group  pt-2">
                      <div class="input-group-prepend"> <span class="fa fa-tasks btn-info btn"></span>   </div>
                      
                      <input type="number" class="form-control" placeholder="Periodo"    name="periodo">
                     </div>
				</div>
               <div class="control-group col-4" style="margin-button:15px">
					 <div class="input-group  pt-2">
                      <div class="input-group-prepend"> <span class="fa fa-users  btn-info btn"></span>   </div>
                      
                      <input type="number" class="form-control" placeholder="cupo"    name="cupo">
                     </div>
				</div>
                 <div class="control-group col-6" style="margin-button:15px">
					 <div class="input-group  pt-2">
                      <div class="input-group-prepend"> <span class="fa fa-cubes btn-info btn" title="Nombre o numero del modulo actual"></span>   </div>
                      
                      <input type="number" class="form-control" placeholder="Modulo"    name="modulo">
                     </div>
				</div>
                 <div class="control-group col-6" style="margin-button:15px">
					 <div class="input-group  pt-2">
                      <div class="input-group-prepend"> <span class="fa fa-graduation-cap  btn-info btn  docente-selectice-icon" title="Expositor o encargado de presentar el curso" ></span>   </div>
                     <!-- <select name="docente" id=""  placeholder="Docente" class="form-control">
                          <option value="1">Carlos</option>
                      </select>-->
                      
                       <select required name="id_docente" id="id_docente" class="form-control">
               
                      </select> 
                     </div>
				</div>
             
           
            </div>
            <hr>
			<center><h4>CERTIFICADO E INSCRIPCION</h4></center>
			<div class="row">
			 
				
				<div class="control-group col-6" style="margin-button:15px">
					<label class="control-label">Cartificado: </label>.
					<div class="row">
					    <div class="col-6"><div class="custom-control custom-radio ">
                          <input type="radio" id="radioSI" name="certificado" class="custom-control-input" value="SI">
                          <label class="custom-control-label" for="radioSI">SI</label>
                        </div></div>
					    <div class="col-6"><div class="custom-control custom-radio ">
                          <input type="radio" id="radioNO" name="certificado" class="custom-control-input" value="NO" checked>
                          <label class="custom-control-label" for="radioNO">NO</label>
                        </div></div>
					</div>
					 
				</div>
 	
				<div class="control-group col-6" style="margin-button:15px">
					 <div class="input-group  pt-2">
                      <div class="input-group-prepend"> <span class="fa fa-history btn-warning btn" title="Carga horaria que se escribe en el certificado"></span>   </div>
                      
                      <input type="number" class="form-control" placeholder="Carga Horaria"    name="cargaHoraria">
                     </div>
				</div> 
			 
			    <div class="col-6">
			      <div class="control-group " style="margin-button:15px">
                 <label class="control-label">  Fecha inscripcion inicio: </label>
                 <div class="input-group">
                  <div class="input-group-prepend">
                    <button class="btn btn-warning" type="button"><span class="fa fa-calendar "></span></button>
                  </div>
                  <input type="date" class="form-control" placeholder="Fecha inicio"   name="fechainscripini">
                 </div>
                </div>
				<div class="control-group  " style="margin-button:15px">
                 <label class="control-label">  Fecha inscripcion fin: </label>
                 <div class="input-group">
                  <div class="input-group-prepend">
                    <button class="btn btn-warning" type="button"><span class="fa fa-calendar "></span></button>
                  </div>
                  <input type="date" class="form-control" placeholder="Fecha inicio"   name="fechainscripfin">
                 </div>
                </div>
				
			        
			    </div>
			    <div class="col-6">
			       <div class="control-group" style="margin-button:15px">
					<label class="control-label"><span class="fa fa-eye "></span> Observaciones: </label>
					<div class="controls">
						<textarea class="form-control"  rows="3" name="observaciones"></textarea>
					</div>
				</div>
			       <!-- <span class="btn btn-info btn-sm btn-block" onclick="crear_requisito()">Agregar Restricciones</span>-->
			    </div>
			</div> 
			
      <br>
				      <center><h4>ASIGNACIÓN DE CONCEPTO DE PAGO</h4></center>
      <div class="row">
       
        <div class="control-group col-12" style="margin-button:15px">
            <div class="input-group  pt-2">
              <div class="input-group-prepend"> 
                <span class="fas fa-money-bill-alt  btn-danger btn  pension-selectice-icon" title="Concepto de pago" ></span>
              </div>
                      
              <select required name="id_pension" id="id_pension" class="form-control">
              </select> 

            </div>
        </div>
        
      </div> 
				 
				
			<!--	<div class="control-group" style="margin-button:15px">
					<label class="control-label">Imagen de curso: </label>
					<div class="controls">
						 <input type="file" class="form-control" id="file_f"  name="file" > 
					</div>
				</div>		-->	 

						
				<!--<div class="control-group" style="margin-button:15px"> 
					<div class="controls">
					<input type="hidden" class="form-control" class="form-control" id="fecha_felicitacion" name="fecha_felicitacion">
					</div>
				</div>-->
								
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-light pull-left" data-dismiss="modal">Cancelar</button>
				<button type="submit" class="btn btn-primary pull-left">Registrar</button>
			</div>
		</div>
	</div>
</div>
</form>
<script>
$("#form_asignacion").validate({
    rules : { 
		id_docente:{required:true},
		cupo:{required:true},
        ambiente:{required:true}
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
        id_docente: "Debe seleccionar un docente.",
		cupo: "Debe ingresar un cupo.",
		ambiente: "Debe ingresar un lugar."        
    },
    //una ves validado guardamos los datos en la DB
  	submitHandler: function(form){
       //var datos = $("#form_felicitacion").serialize();     
      //datos=datos+'&accion=guardar_kardex';
		var id_docente=$('#id_docente').val();
 if(id_docente=='' ||id_docente==0 || id_docente==null ){
	 $('.docente-selectice-icon').css('background','red');
	 alertify.error('Seleccione docente');
	return false;
	}else{
      var form_data = new FormData($("#form_asignacion")[0]); 
      $.ajax({
          type: 'POST',  url: "?/s-cursos-extracurricular/procesos",  data: form_data,
          cache: false, contentType: false, processData: false,   datatype: 'text',
          
          success: function (resp) {
           //console.log(resp);
            switch(resp){
              case '1': //listarcursos();// Creado
                        $("#modal_asignacion").modal("hide");
                        alertify.success('Se registro correctamente la asignacion');
                        listarcursos(); volver();$("#form_asignacion")[0].reset();
                        break;
              case '2': //actualisado
                        $("#modal_asignacion").modal("hide");
                        alertify.success('Edicion correcta'); 
                        listarcursos();volver();$("#form_asignacion")[0].reset();
                        break;
              case '5': alertify.error('El tamaño del archivo es muy grande');
                        break;
				case '10': //error otro
                        //$("#modal_categoria").modal("hide");
                        alertify.warning('Ingrese una fecha valida');
                        break;
              default: //dataTable.ajax.reload();
                        //$("#modal_categoria").modal("hide");
                        alertify.error('Operacion fallida');
                        break;
            }
            
          }          
      }); 
	}
    return false;
  }

});//ok guarda  X editar
 
listar_docente();
function listar_docente() {
    
	 nivel = 0;//$("#nivel_academico option:selected").val()
		$.ajax({
			url: '?/s-cursos-extracurricular/procesos',
			type: 'POST',
			data: {
				'accion': 'listar_docente',//listar_docente',
				'nivel': nivel
			},
			dataType: 'JSON',
			success: function(resp){
		    //console.log('Listar doc'+ resp); 
                 
				//alert(resp[0]['id_catalogo_detalle']); 
				//console.log(resp);
                var cont=0;
				$("#id_docente").html("");
				$("#id_docente").append('<option value="">Seleccionar docente</option>');//<option value=0>Sin asignar</option>
				for (var i = 0; i < resp.length; i++) {
 
                        $("#id_docente").append('<option  value="' + resp[i]["id_asignacion"] + '">'  + resp[i]["primer_apellido"]+' ' + resp[i]["nombres"]+ ' - ' +resp[i]["cargo"]+'</option>');
				}
				//console.log(resp[0]);
                   

			}
		}).done(function (data) {
           //alert('done');
            $('#id_docente').selectize();
        });
        
	}
//listar_concepto_pago();
// function listar_concepto_pago() {
    
//    nivel = 0;//$("#nivel_academico option:selected").val()
//     $.ajax({
//       url: '?/s-cursos-extracurricular/procesos',
//       type: 'POST',
//       data: {
//         'accion': 'listar_concepto_pago',//listar_docente',
//         'nivel': nivel
//       },
//       dataType: 'JSON',
//       success: function(resp){
//         console.log('Listar doc'+ resp); 
                 
//         //alert(resp[0]['id_catalogo_detalle']); 
//         //console.log(resp);
//         var cont=0;
//         $("#id_pension").html("");
//         $("#id_pension").append('<option value="">Seleccionar Concepto Pago</option>');//<option value=0>Sin asignar</option>
//         for (var i = 0; i < resp.length; i++) {
 
//                         $("#id_pension").append('<option  value="' + resp[i]["id_pensiones"] + '">'  + resp[i]["nombre_pension"]+' ' + resp[i]["codigo_concepto"]+ ' - Nro. Cuota' +resp[i]["nro_cuota"]+ ' - Monto Total' +resp[i]["nro_cuota"]+'</option>');
//         }
//         //console.log(resp[0]);
                   

//       }
//     }).done(function (data) {
//            //alert('done');
//             $('#id_pension').selectize();
//         });
        
//   }
</script>


<form id="form_requisito">
<div class="modal fade" id="modal_requisito" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog " role="document">
		<div class="modal-content sobremodal">
			<div class="modal-header">
				<h3 class="modal-title" id="exampleModalLabel">Prerequisito</h3>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
				<input id="id_requisito" type="hidden"  name="id_requisito" >
				<input  type="hidden"  name="accion"  value="guardar_requisito"> 
			</div>
			<div class="modal-body">

				 <input type="hidden" class="form-control id_curso"  name="id_curso" placeholder="id_curso">
				 <!--<input type="hidden" class="form-control" id="id_persona" name="id_persona" placeholder="id_persona">-->
				  
				 <div class="control-group" style="margin-button:15px">
					<label class="control-label">Nombre: </label>
					<div class="controls">
						<input type="text" class="form-control"  name="nombre"> 
					</div>
				</div>
				 
				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Tipo: </label>
					<div class="controls">
						<input type="number" class="form-control"  name="tipo"> 
					</div>
				</div> 
				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Descripcion: </label>
					<div class="controls">
						<textarea class="form-control" rows="3" name="descripcion"></textarea>
					</div>
				</div>
				 
								
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-light pull-left" data-dismiss="modal">Cancelar</button>
				<button type="submit" class="btn btn-primary pull-left">Registrar</button>
			</div>
		</div>
	</div>
</div>
</form>
<script>
$("#form_requisito").validate({
    rules : { 
		motivo:{required:true},
		descripcion:{required:true},
        fecha_felicitacion:{required:true}
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
        motivo: "Debe ingresar un motivo.",
		descripcion: "Debe ingresar una felicitacion.",
		fecha_felicitacion: "Debe ingresar la fecha."        
    },
    //una ves validado guardamos los datos en la DB
  	submitHandler: function(form){
      //alert();
      //var datos = $("#form_felicitacion").serialize();     
      //datos=datos+'&accion=guardar_kardex';
 
      var form_data = new FormData($("#form_requisito")[0]); 
      $.ajax({
          type: 'POST',  url: "?/s-cursos-extracurricular/procesos",  data: form_data,
          cache: false, contentType: false, processData: false,   datatype: 'text',
          
          success: function (resp) {
            console.log(resp);
            switch(resp){
              case '1': //listarcursos();// Creado
                        $("#modal_requisito").modal("hide");
                        alertify.success('Se registro correctamente la requisito');
                        listar_requisitos();
						$("#form_requisito")[0].reset();
                        break;
              case '2': //actualisado
                        $("#modal_requisito").modal("hide");
                        alertify.success('Edicion correcta');
						listar_requisitos();
						$("#form_requisito")[0].reset();
                        break;
              case '10': //error otro
                        //$("#modal_categoria").modal("hide");
                        alertify.warning('Ingrese una fecha valida');
                        break;
              default: //dataTable.ajax.reload();
                        //$("#modal_categoria").modal("hide");
                        alertify.error('Operacion fallida');
                        break;
            }
            
          }          
      });    
    return false;
  }

});//ok guarda  X editar
  
</script>
 
<form id="form_inscribir">
      <div class="modal fade" id="modal_inscribir" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      	<div class="modal-dialog" role="document">
      		<div class="modal-content">
      			<div class="modal-header">
      				<h5 class="modal-title" id="exampleModalLabel">Formulario de Inscripción a Curso Extracurricular</h5>
      				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
      					<span aria-hidden="true">&times;</span>
      				</a>
      				<input id="id_inscribir" type="hidden"  name="id_inscribir" >
      				<input  type="hidden"  name="accion"  value="guardar_inscripcion"> 
      				<input type="hidden" class="form-control id_asignacion" id="id_asignacion" name="id_asignacion" placeholder="id_asignacion">
              <input type="hidden" class="form-control id_pensiones" id="id_pensiones" name="id_pensiones" placeholder="id_pensiones">
      			</div>
      			<div class="modal-body">
                <!-- <h3>Registrar Inscripción de Estudiante a Curso Extracurricular</h3> -->
                <div class="alert alert-primary" role="alert" style="font-size:11px">
                    <i>Información para el Usuario </i><label style="color:red">*</label>.<br>
                    <i>Solo se mostrarán en el listado estudiantes con inscripcion en la gestión actual.</i><br>
                    <i>Las cuotas a pagar del curso se le asignan de manera automatica al terminar la inscripción.</i>
                </div>
      				 <div class="control-group" style="margin-button:15px">
      					<label class="control-label">Estudiante: </label>
      					<div class="controls">
      					 
      						<select name="id_estudiante" id="id_estudiante" class="form-control"></select>
      					</div>
      				</div>
      				 
      				<div class="control-group d-none" style="margin-button:15px">
      					<label class="control-label">Tipo: </label>
      					<div class="controls">
      						<input type="number" class="form-control"   name="tipo" value="1"> 
      					</div>
      				</div>
      				 
      				 
      				<div class="control-group" style="margin-button:15px">
      					<label class="control-label">Observación: </label>
      					<div class="controls">
      						<textarea class="form-control" id="descripcion" rows="3" name="obs"></textarea>
      					</div>
      				</div> 
      								
      			</div>
      			<div class="modal-footer">
      				<button type="button" class="btn btn-default btn-light pull-left" data-dismiss="modal">Cancelar</button>
      				<button type="submit" class="btn btn-primary pull-left">Guardar</button>
      			</div>
      		</div>
      	</div>
      </div>
</form>
<script>
$("#form_inscribir").validate({
    rules : { 
		motivo:{required:true},
		descripcion:{required:true},
        fecha_felicitacion:{required:true}
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
        motivo: "Debe ingresar un motivo.",
		descripcion: "Debe ingresar una felicitacion.",
		fecha_felicitacion: "Debe ingresar la fecha."        
    },
    //una ves validado guardamos los datos en la DB
  	submitHandler: function(form){
      //alert();
      //var datos = $("#form_felicitacion").serialize();     
      //datos=datos+'&accion=guardar_kardex';
 
      var form_data = new FormData($("#form_inscribir")[0]); 
      $.ajax({
          type: 'POST',  url: "?/s-cursos-extracurricular/procesos",  data: form_data,
          cache: false, contentType: false, processData: false,   datatype: 'text',
          
          success: function (resp) {
            console.log(resp);
            switch(resp){
              case '1': //listarcursos();// Creado
                        $("#modal_inscribir").modal("hide");
                        alertify.success('Se inscribio correctamenteb');
                        //listarIncritos(id_curso_asignacion);//idasig viene se crea glovla al ver_curso()
						$("#form_inscribir")[0].reset();
                        listar_asignaciones_simples();//cantidades de inscripcion
                        break;
              case '2': //actualisado
                        $("#modal_inscribir").modal("hide");
                        alertify.success('Edicion correcta'); 
						listarIncritos(id_curso_asignacion);
						$("#form_inscribir")[0].reset();
                        break;
              case '10': //error otro
                        //$("#modal_categoria").modal("hide");
                        alertify.warning('Ingrese una fecha valida');
                        break;
              case '11':  
                        alertify.warning('El cupo esta lleno');
                        break;
              case '12':  
                        alertify.warning('El estudiante ya esta inscrito');
                        break;
              default: //dataTable.ajax.reload();
                        //$("#modal_categoria").modal("hide");
                        alertify.error('Operacion fallida');
                        break;
            }
            
          }          
      });    
    return false;
  }

});//ok guarda  X editar
 
listar_estudiante();
function listar_estudiante() {
    
	 //nivel = 0;//$("#nivel_academico option:selected").val()
		$.ajax({
			url: '?/s-cursos-extracurricular/procesos', type: 'POST', data: { 'accion': 'listar_estudiantes' },
			dataType: 'JSON',
			success: function(resp){
		     
                var cont=0;
				$("#id_estudiante").html("");
				$("#id_estudiante").append('<option value="">Seleccionar Estudiante..</option>');//<option value=0>Sin asignar</option>
				for (var i = 0; i < resp.length; i++) {
 
                        $("#id_estudiante").append('<option  value="' + resp[i]["id_estudiante"] + '">'  + resp[i]["primer_apellido"]+' ' + resp[i]["segundo_apellido"]+' ' + resp[i]["nombres"]+ ' - ' +resp[i]["numero_documento"]+'</option>');
				}
				//console.log(resp[0]);
                   

			}
		}).done(function (data) {
           //alert('done');
            $('#id_estudiante').selectize();
        });
        
	}
</script>
 



<!--mustras_____________________________________--> 
<!--
<form id="form_felicitacion">
<div class="modal fade" id="modal_felicitacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Registrar Felicitacion</h5>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
				<input id="id_kardex" type="hidden"  name="id_kardex" >
				<input  type="hidden"  name="accion"  value="guardar_kardex"> 
			</div>
			<div class="modal-body">

				 <input type="hidden" class="form-control" id="tipoFelSanc" name="tipoFelSanc" placeholder="tipoFelSanc">
				 <input type="hidden" class="form-control" id="id_persona" name="id_persona" placeholder="id_persona">
			 
				 <div class="control-group" style="margin-button:15px">
					<label class="control-label">Fecha: </label>
					<div class="controls">
						 <input type="text" class="form-control" id="fecha_felicitacion" name="fecha_felicitacion" >
					</div>
				</div>
				 
				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Concepto: </label>
					<div class="controls">
						<input type="text" class="form-control" id="concepto_f" name="concepto"> 
					</div>
				</div>
				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Tipo: </label>
					<div class="controls">
					<select class="form-control" id="tipo_f" name="tipo_f">
					    <option value="1">tipo evaluacion</option>
					    <option value="2">tipo memorandum</option>
					</select> 
					</div>
				</div>

				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Descripcion: </label>
					<div class="controls">
						<textarea class="form-control" id="descripcion_f" rows="3" name="descripcion"></textarea>
					</div>
				</div>		<div class="control-group" style="margin-button:15px">
					<label class="control-label">File: </label>
					<div class="controls">
						 <input type="file" class="form-control"    name="file" >
						 <p>Los nombre de los archivos no deven contener la letra "ñ"</p>
					</div>
				</div>				
 			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-light pull-left" data-dismiss="modal">Cancelar</button>
				<button type="submit" class="btn btn-primary pull-left">Registrar</button>
			</div>
		</div>
	</div>
</div>
</form>
<script>
 
$("#form_felicitacion").validate({
    rules : { 
		motivo:{required:true},
		descripcion:{required:true},
        fecha_felicitacion:{required:true}
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
        motivo: "Debe ingresar un motivo.",
		descripcion: "Debe ingresar una felicitacion.",
		fecha_felicitacion: "Debe ingresar la fecha."        
    },
    //una ves validado guardamos los datos en la DB
  	submitHandler: function(form){
    
        var form_data = new FormData($("#form_felicitacion")[0]); 
      $.ajax({
          type: 'POST',
          url: "?/rrhh-kardex/procesos", 
          data: form_data,
          cache: false,
            contentType: false,
            processData: false,
            datatype: 'text',
          
          success: function (resp) {
            console.log(resp);
            switch(resp){
              case '1': listarcursos();//nuevos?
                        //listarkardex(id_persona);//(edicion?) id=varable de pagina
                        $("#modal_felicitacion").modal("hide");
                        alertify.success('Se registro correctamente');
                        break;
              case '2': //dataTable.ajax.reload();
                       listarkardex(id_persona);//id=varable de pagina
                        $("#modal_felicitacion").modal("hide");
                        alertify.success('Edicion correcta'); 
                        break;
            case '10': //dataTable.ajax.reload();
                        $("#modal_felicitacion").modal("hide");
                        alertify.warning('Ingrese una fecha valida');
                        break;
             default: //dataTable.ajax.reload();
                        $("#modal_felicitacion").modal("hide");
                        alertify.error('Operacion fallida');
                        break;
            }
            
          }          
      });    
  }

});
$('#fecha_felicitacion').datepicker({
    timepicker: true,
    language: 'es',
    position:'bottom left',
    dateFormat: 'yyyy-mm-dd', 
    //startDate: start,
    minHours: 8,
    maxHours: 18,
    timeFormat: "hh:ii:00",//hh:horas ii:minutos mm:seg
  
    onSelect: function(fd, d, picker){
        var fecha_marcada = moment(d).format('YYYY-MM-DD');
        var hoy = moment(new Date()).format('YYYY-MM-DD');

        if(fecha_marcada >= hoy){

        }else{
            alertify.error('No puede asignar tareas en una fecha pasada a la actual');
            $("#fecha_inicio").val("");
        }
        
    }
})    
    
</script> 
 
 <form id="form_sancion">
<div class="modal fade" id="modal_sancion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Registrar Sancion</h5>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
                </a>
                <input id="id_sancion" type="hidden" class="form-control" name="id_sancion" >
                <input id="id_estudiante_s" type="hidden" class="form-control" name="id_estudiante" >
                <input id="id_profesor_materia_s" type="hidden" class="form-control" name="id_profesor_materia">
                <input id="modo_calificacion_id_s" type="hidden" class="form-control" name="modo_calificacion_id">
			</div>
			<div class="modal-body">
				   <div class="control-group" style="margin-button:15px">
					<label class="control-label">Fecha a Asistir:</label>
					<div class="controls">
						<input id="fecha_asistir" name="fecha_asistir" type="text" class="form-control">
					</div>
                </div>
                
				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Concepto: </label>
					<div class="controls">
						<input type="text" class="form-control" id="motivo_s" name="motivo"> 
					</div>
				</div>
				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Tipo: </label>
					<div class="controls">
					<select name="" class="form-control" id="tipo_s" name="motivo">
					    <option value="">tipo 1</option>
					    <option value="">tipo 2</option>
					</select> 
					</div>
				</div>

				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Descripcion: </label>
					<div class="controls">
						<textarea class="form-control" id="descripcion_s" rows="3" name="descripcion"></textarea>
					</div>
				</div>		<div class="control-group" style="margin-button:15px">
					<label class="control-label">File: </label>
					<div class="controls">
						 <input type="file" class="form-control" id="file_s" name="file_s">
					</div>
				</div>				

             
                				
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-default btn-light pull-left" data-dismiss="modal">Cancelar</button>
				<button type="submit" class="btn btn-primary pull-left">Registrar</button>
			</div>
		</div>
	</div>
</div>
</form>
<script>

$("#form_sancion").validate({
    rules : { 
        motivo:{required:true},
        dias:{required:true}
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
        motivo: "Debe ingresar un motivo.",
        dias: "Debe ingresar dias de suspension."        
    },
    //una ves validado guardamos los datos en la DB
  submitHandler: function(form){
      //alert();
      var datos = $("#form_sancion").serialize();     
      datos=datos+'&accion=guardar_sancion';

      console.log(datos);  

      $.ajax({
          type: 'POST',
          url: "?/rrhh-kardex/procesos",
          data: datos,
          success: function (resp) {
            console.log(resp);
            switch(resp){
              case '1': 
                    //dataTable.ajax.reload();
                     listarEstudiantesKardex();
                        $("#modal_sancion").modal("hide");
                        alertify.success('Se registro la sancion correctamente');
                        break;
              case '2': //dataTable.ajax.reload();
                        $("#modal_sancion").modal("hide");
                        alertify.warning('No se registro la sancion'); 
                        break;
              case '3': //dataTable.ajax.reload();
                        $("#modal_sancion").modal("hide");
                        alertify.success('Edicion correcta');
                        verEstCardex(idest);//traido de crear historial verEstCardex(docente.php)
                        break;
            }
            //pruebaa();
          }          
      });      
  }

});
$('#fecha_asistir').datepicker({
    timepicker: true,
    language: 'es',
    position:'bottom left',
    dateFormat: 'yyyy-mm-dd', 
    //startDate: start,
    minHours: 8,
    maxHours: 18,
    timeFormat: "hh:ii:00",//hh:horas ii:minutos mm:seg
     
    onSelect: function(fd, d, picker){
        var fecha_marcada = moment(d).format('YYYY-MM-DD');
        var hoy = moment(new Date()).format('YYYY-MM-DD');

        if(fecha_marcada >= hoy){

        }else{
            alertify.error('No puede asignar tareas en una fecha pasada a la actual');
            $("#fecha_inicio").val("");
        }
        
    }
})    
 
</script>    
 -->