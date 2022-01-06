<?php
$csrf = set_csrf();
?>
<form id="form_gestion">
  <div class="modal fade" id="modal_gestion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><span id="titulo_gestion"></span>  Horarios</h5>
          <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </a>
        </div>
        <div class="modal-body">
          <div class="control-group">
            <div class="controls">
              <input type="hidden" name="<?= $csrf; ?>">
              <input name="horario_id" id="horario_id"  type="hidden" placeholder="aula_paralelo_id" >
              <input name="tipoAc" id="tipoAc"  type="hidden"  placeholder="tipoAc">
              <input name="aula_par_prof_mat_id" id="aula_par_prof_mat_id"  type="hidden"  placeholder="id_horario_profesor_materia"> 
             <!-- <select name="id_docente" id="id_docente" class="form-control">
               
              </select>--> <br>
            </div>
          </div>
           <div class="control-group">
            <label class="control-label">Hora Inicial: </label>
            <input required type="time" placeholder="" name="hora_inicio" id="hora_inicio" class="form-control" onchange="comparar_horas()" >
        <!-- <input id="timepicker" width="276" />-->
            </div>
            <div class="control-group">
          
            <label class="control-label">Hora Final: <span class="msgErrorHora" style="color:red;display:none">La hora final deve ser manor a la hora inicial</span> </label>
            <input required type="time" placeholder="" name="hora_fin" id="hora_fin" class="" onchange="comparar_horas()" >
            </div>
            <div class="control-group">
          
            <label class="control-label">Turno  </label>
            
            <select required name="turno_sel" id="turno_sel" class="form-control">
                <!--<option value="1">Lunes</option>
                <option value="2">Martes</option>
                <option value="3">Miercoles</option>
                <option value="4">Jueves</option>
                <option value="5">Viernes</option> -->
            </select>
            </div> <div class="control-group">
          <div class="custom-control custom-checkbox">
                <input type="checkbox" name="descanso" class="custom-control-input" id="descanso" value="1">
                <label class="custom-control-label" for="descanso">Registro de descanso Pedagogico</label>
            </div>
            </div> 
         
          <!--<p>* obs <a href="?/s-profesor-materia/listar">ir</a></p>-->
           </div>
        <hr>
          
          
          <div class="modal-footer">
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

<link rel="stylesheet" href="application\modules\s-horarios-new/lib/gijgo.css"> 
<script src="assets/vendor/jquery/jquery-3.3.1.min.js"></script><script src="application\modules\s-horarios-new/lib/gijgo.min.js"></script>
 

<script>
    $('#hora_inicio').timepicker();
    $('#hora_fin').timepicker();
</script>
<script>
//CARGAR SIGANCIONES MATERIA DOCENTE 
 
    
    //Guardar
  $("#form_gestion").validate({
    rules: {
      id_docente: {
        required: true
      }/*,
      id_curso_paralelo: {
        required: true
      },
      id_asignatura: {
        required: true
      }*/
      //id_gestion: {required: true}
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
      $.ajax({
        type: 'POST',
        url: "?/s-horarios-new/guardar",
        data: datos,
        success: function(resp) {
         //alert(resp);
          cont = 0;
          switch (resp) {
                  //retorna 1 si se creop el horario corectamtne
            case '1':
              //dataTable.ajax.reload();
              $("#modal_gestion").modal("hide");
              alertify.success('Se creo correctamente  el horario'); 
            listar_paralelos_tabla();
              break;
                  //retorna 2 si se edito correctamtene
            case '2': 
              $("#modal_gestion").modal("hide");
              alertify.success('Se edito correctamente  el horario');
            listar_paralelos_tabla();
              break;
                  //en caso de dato repetido
            case '3':
              
              alertify.warning('Ya se asigno anteriormente a este horario');
              break;
              case '4':
              
              alertify.warning('No se puede editar descanso a un horario con materias');
              break;
            default : alertify.error('No se pudo guardar'+resp);
          }
          //pruebaa();
        }

      });

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
    $('#btn_modificar').attr('disabled',true);
        
    }else{
    $('.msgErrorHora').hide();
    $('#btn_nuevo').attr('disabled',false);
    $('#btn_modificar').attr('disabled',false);
        
    }
   
//});
}
    

    
    
    
</script>