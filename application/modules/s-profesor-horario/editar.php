<?php
$csrf = set_csrf();
?>
<link rel="stylesheet" href="assets/css/selectize.bootstrap4.css" />

<form id="form_gestion">
  <div class="modal fade" id="modal_gestion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
        
          <h5 class="modal-title" id="exampleModalLabel">  <span id="titulo_gestion"></span>  Docente-Materia</h5>
          <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </a>
        </div>
        <div class="modal-body">
          <div class="control-group">
            <div class="controls">
              <input type="hidden" name="<?= $csrf; ?>">
              <input name="aula_paralelo_id" id="aula_paralelo_id"  type="hidden" placeholder="aula_paralelo_id" >
              <input name="tipoAc" id="tipoAc"  type="hidden"  placeholder="tipoAc">
              <input name="aula_par_prof_mat_id" id="aula_par_prof_mat_id"  type="hidden"  placeholder="id_horario_profesor_materia">
              <input id="id_aula_asig_mat" name="id_aula_asig_mat" type="hidden" class="form-control" placeholder="id_aula_asig_mat">
              <!--input id="id_docente" name="id_docente" type="text" class="form-control"-->
              <label class="">Docente: </label>
              <select required name="id_docente" id="id_docente" class="form-control">
               
              </select>  
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Materia: (Obligatorio) </label>
            <select  required name="id_materia" id="id_materia" class="form-control">
               
            </select>  
      
            </div>
          <!--  <div class="control-group">
          
            <label class="control-label">Hora Final: <span class="msgErrorHora" style="color:red;display:none">La hora final deve ser manor a la hora inicial</span> </label>
            <input required type="time" placeholder="" name="hora_fin" id="hora_fin" class="form-control" onchange="comparar_horas()" >
            </div>-->
            
            <div class="control-group">
          
            <label class="control-label">Horario  <a href="?/s-horarios-new/listar"> (Obligatorio)</a></label>
            
            <select required name="horario" id="horario" class="form-control">
                <option value="1"> </option> 
            </select>
            </div> 
            
            <div class="control-group">
          
            <label class="control-label">Dia  (Obligatorio)</label>
            
            <select required name="dia" id="dia" class="form-control">
                <option value="1">Lunes</option>
                <option value="2">Martes</option>
                <option value="3">Miercoles</option>
                <option value="4">Jueves</option>
                <option value="5">Viernes</option>
                <option value="6">Sabado</option>
            </select>
            </div> 
         <!--  <div class="control-group">
            <label class="control-label">Seleccione curso paralelo: </label>
            <div class="controls">
              <input type="hidden" name="<?//= $csrf; ?>">
              <input id="id_gestion" name="id_gestion" type="hidden" class="form-control">
             
              <select name="id_docente" id="id_docente" class="form-control">
               
              </select>
            </div>
          </div>-->
          <p>* Para asignar un Docente materia deve accedere al menu Gestion>Asignaciones y seleccionar <a href="?/s-profesor-materia/listar">Profesor/Materia</a></p>
           </div>
        <hr>
          
          
          <div class="modal-footer"> 
             <!--<button type="button" class="btn btn-danger pull-right" id="btn_eliminarde" onclick="abrir_eliminar(1)" >Eliminar</button>-->
            <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary pull-right" id="btn_nuevo" >Guardar</button>
            <button type="submit" class="btn btn-primary pull-right" id="btn_modificar" >Editar</button>
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
<link rel="stylesheet" href="assets/css/gijgo.css"> 
<script src="assets/gijgo.min.js"></script>
 

<script>
    $('#hora_inicio').timepicker();
    $('#hora_fin').timepicker();
</script>
<script>
//CARGAR SIGANCIONES MATERIA DOCENTE 
// listar_d
//listar_asignacion_docente_materia();//sin USO YA
function listar_asignacion_docente_materia() {
	 nivel = 0;//$("#nivel_academico option:selected").val()
		$.ajax({
			url: '?/s-profesor-horario/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_asignacion_docente_materia',
				'nivel': nivel
			},
			dataType: 'JSON',
			success: function(resp){
		    console.log('Listar aula'+ resp); 
               // alert('ejemplo');
				//alert(resp[0]['id_catalogo_detalle']); 
				//console.log(resp);
                var cont=0;
				$("#id_docente").html("");
				$("#id_docente").append('<option value="">Seleccionar</option>');
				for (var i = 0; i < resp.length; i++) {
/*id_profesor_materia
nombres
primer_apellido
nombre_materia*/
                        $("#id_docente").append('<option  value="' + resp[i]["id_profesor_materia"] + '">' + resp[i]["primer_apellido"]+' ' + resp[i]["nombres"]+' - ' + resp[i]["nombre_materia"]+'</option>');
                    
                   // listar_nivel();
                   // listar_aulas();
				}
				//console.log(resp[0]);
                   

			}
		});
        
	}   // listar_asignacion_docente_materia de php 
listar_docente();
function listar_docente() {
    
	 nivel = 0;//$("#nivel_academico option:selected").val()
		$.ajax({
			url: '?/s-profesor-horario/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_docente',//listar_docente',
				'nivel': nivel
			},
			dataType: 'JSON',
			success: function(resp){
		    console.log('Listar doc'+ resp); 
                 
				//alert(resp[0]['id_catalogo_detalle']); 
				//console.log(resp);
                var cont=0;
				$("#id_docente").html("");
				$("#id_docente").append('<option value="">Seleccionar</option><option value=0>Sin asignar</option>');
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
    
listar_materias(0);
function listar_materias(nivel_id) {
    
    if(nivel_id==0){
       nivel_id=$("#nivel_id").val();
       }
    //var tipo_evaluacion=$("#nivel_academico option:selected").val()
		$.ajax({
			url: '?/s-profesor-horario/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_materias',//listar_docente',
				'nivel': nivel_id 
			},
			dataType: 'JSON',
			success: function(resp){
                //destruir selectyixze
		    console.log('Listar doc'+ resp); 
                // alert('ejemplo');
				//alert(resp[0]['id_catalogo_detalle']); 
				//console.log(resp);
                var cont=0;
				$("#id_materia").html("").append('<option value="">Seleccionar</option>');
                var cc=0;
				for (var i = 0; i < resp.length; i++) {
 
                        $("#id_materia").append('<option  value="' + resp[i]["id_materia"] + '">' + resp[i]["nombre_materia"]+' - '+ resp[i]["nombre_nivel"]+'</option>');cc++;
				}
                if(cc==0){
                   $("#id_materia").html("").append('<option value="">Sin materias en este nivle</option>');
                    alertify.warning('El nivel no tien materias, agregar en edicion de materias');
                   }
				//console.log(resp[0]);
                   

			}
		}).done(function (data) {
           //alert('done');
           // $('#id_materia').selectize();
        });
        
	} 
    
    //Guardar
  $("#form_gestion").validate({
    rules: {
      id_docente: {
        required: true
      },/*,
      id_curso_paralelo: {
        required: true
      },
      id_asignatura: {
        required: true
      }*/
      horario: {required: true}
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
      id_docente: "Debe seleccionar una asignacion."/*,
      id_curso_paralelo: "Debe seleccionar un curso.",
      id_asignatura: "Debe seleccionar la asignatura."*/
    },
    //una ves validado guardamos los datos en la DB
    submitHandler: function(form) {

       
      var datos = $("#form_gestion").serialize();
      
          if($('#horario').val()!='' && $('#horario').val()!=0)
          {// && $('#id_materia').val()!=0  && $('#id_materia').val()!=''
          console.log('s-profesor-horario>editar>form_gestion: crear nuevo-');

          //alert('dia'+dia);
              var misma_materia=0;
              if(horario==$('#horario').val() && dia==$('#dia').val() ){
                 misma_materia=1; 
                }else{
                 misma_materia=0;
                }
            //alert(misma_materia);
          $.ajax({
            type: 'POST',
            url: "?/s-profesor-horario/guardar",
            data: datos+'&misma_materia='+misma_materia,
            success: function(resp) {
             //alert(resp);
              cont = 0;
              switch (resp) {
                case '1':
                  //dataTable.ajax.reload();
                  $("#modal_gestion").modal("hide");
                    try{
                    listar_paralelos_tabla();//en caso de listar

                    }catch{
                     ver_tabla_horario();//en caso de agrear de verhorario
                    }
                  alertify.success('Se CREO correctamente  el Docente horarios y materia');
                   // ver_tabla_horario();
                  break;
                case '2':
                  //dataTable.ajax.reload();
                  $("#modal_gestion").modal("hide");
                  alertify.success('Se EDITO correctamente  el Docente horarios y materia');
                //listar_paralelos_tabla();//tabla de vista listar
                   ver_tabla_horario();// de vista verhorario
                  break;
                case '3':
                  //dataTable.ajax.reload();
                  //$("#modal_gestion").modal("hide");
                  alertify.warning('Ya se asigno otro anteriormente en este HORARIO y dia');
                  break;
                case '4': 
                  alertify.warning('No se puede ingresar materia a un espacio asiganado a DESCANSO');
                  break;
                default : alertify.error('No se pudo guardar'+resp);
              }
              //pruebaa();
            }

          });
        }else{
            //alert('no tiene horario'+$('#horario').val());
       
            alertify.error('Complete los campos');
        }
        
    }
      
  })
function comparar_horas(){ 
//$('#hora_fin').change(function(){
    
     // alert('vlivkk');
   var hora_inicio=$('#hora_inicio').val();
   var hora_fin=$('#hora_fin').val();
    console.log('a hora es '+hora_inicio+' - '+hora_fin);
    
    if(hora_inicio>=hora_fin && hora_fin!=''){
    $('.msgErrorHora').show();
    $('#btn_nuevo').attr('disabled',true);
        
    }else{
    $('.msgErrorHora').hide();
    $('#btn_nuevo').attr('disabled',false);
        
    }
   
//});
}
   
listar_horario();
function listar_horario() {
 //alert('horario');
		$.ajax({
			url: '?/s-profesor-horario/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_horarios'//,
				//'turno': turno
			},
			dataType: 'JSON',
			success: function(resp){
		    console.log('Listar aula'+ resp); 
        
                
				$("#horario").html("");
				$("#horario").append('<option value="' + 0 + '">Seleccionar horario</option>');
				for (var i = 0; i < resp.length; i++) {
                   // if(contN<1){
					//$("#nivel").append('<option selected value="' + resp[i]["id_nivel_academico"] + '">' + resp[i]["nombre_nivel"]+'</option>');
                   // }else{
                        $("#horario").append('<option class="turno'+resp[i]["turno_id"]+'" value="' + resp[i]["id_horario_dia"] + '">' + resp[i]["hora_ini"]+ ' - '+ resp[i]["hora_fin"]+ '  '+ resp[i]["nombre_turno"]+ '  '+ resp[i]["complemento"]+'</option>');
                   // }contT++;
				}
				//console.log(resp[0]);
                   

			}
		});
        
	} 
    
    
    
</script>