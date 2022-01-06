    <?php

    // Obtiene la cadena csrf
    $csrf = set_csrf();


    // Obtiene los permisos



    ?>
   
    <div class="modal fade" id="modal_filtro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalLabel">Filtrar Por Fecha</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    
            <input type="hidden" name="<?= $csrf; ?>">

     
            <div class="form-row form-group">
            <input id="id_contrato" name="id_contrato" type="hidden" class="form-control">



              
            </div>




    

    <div class="form-row form-group">
    <div class="form-group col-md-6">
              <label class="control-label" for="fecha_inicio_filtro">Fecha inicio: </label>
              <div class="controls">
                <input id="fecha_inicio_filtro" name="fecha_inicio_filtro" type="date" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>" required title="Seleccione una fecha inicial" >
              </div>
            </div>
    <div class="form-group col-md-6 Indef">
              <label class="control-label" for="fecha_final_filtro">Fecha final: </label>
              <div class="controls">
                <input  id="fecha_final_filtro" onchange="DateCheck();" name="fecha_final_filtro" type="date" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
              </div>
            </div>

    </div>







            
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
            <a onclick="filtrar_fecharef();" class="btn btn-primary pull-right" id="btn_nuevo">Buscar</a>
                    
                </div>
            </div>
        </div>
    </div>

    <script>

function filtrar_fecharef(){


if ( $("#fecha_inicio_filtro").val() =='') {
            var fecha_inicio_filtro="0";
            var fecha_final_filtro="0";
        }else{
            var fecha_inicio_filtro=$("#fecha_inicio_filtro").val();
            var fecha_final_filtro=$("#fecha_final_filtro").val();
        }
 $("#modal_filtro").modal("hide");
window.location.href = '?/rrhh-personal/listar/'+fecha_inicio_filtro+'/'+fecha_final_filtro;
}


        function DateCheck()
    {
      var StartDate= document.getElementById('fecha_inicio_filtro').value;
      var EndDate= document.getElementById('fecha_final_filtro').value;
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


    </script>

























