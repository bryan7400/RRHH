<?php
    $csrf = set_csrf();

    // Obtiene los formatos
    $formato_textual = get_date_textual($_format);
    $formato_numeral = get_date_numeral($_format);
?> 

<form id="form_gestion">
<div class="modal fade" id="modal_gestion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span id="titulo_gestion"></span>Gestión Escolar</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            
            <div class="modal-body">            
                <input id="id_persona2" name="id_persona2" type="hidden" class="form-control">                        
                <!--<div class="control-group">
                    <label class="control-label">Apellido paterno: </label>
                    <div class="controls">
                        <input type="hidden" name="">
                        <input id="id_gestion2" name="id_gestion2" type="hidden" class="form-control">                        
                        <input id="paterno2" name="paterno2" type="text" class="form-control">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Apellido materno: </label>
                    <div class="controls">
                        <input id="materno2" name="materno2" type="text" class="form-control">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Nombres: </label>
                    <div class="controls">
                        <input id="nombres2" name="nombres2" type="text" class="form-control">
                    </div>
                </div>-->
                <div class="control-group">
                    <label class="control-label">Personal: </label>
                    <div class="controls">
                        <select name="sel_personal_crear" id="sel_personal_crear" class="form-control" required> </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Categoria: </label>
                    <div class="controls">
                        <input id="categoria2" name="categoria2" type="text" class="form-control">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Lentes: </label>
                    <div class="controls">
                        <select name="lentes2" id="lentes2" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./& " data-validation-optional="true">
                            <option value="No">No</option>
                            <option value="Si">Si</option>                            
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Audifonos: </label>
                    <div class="controls">
                        <select name="audifonos2" id="audifonos2" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./& " data-validation-optional="true">
                            <option value="No">No</option>
                            <option value="Si">Si</option>                            
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Grupo Sanguineo: </label>
                    <div class="controls">
                        <input id="grupo_sanguineo2" name="grupo_sanguineo2" type="text" class="form-control">
                    </div>
                </div>
                <div class="control-group margen"> 
                    <label class="control-label">Fecha Emision: </label>
                    <div class="controls">
                        <input type="date" name="f_emision2" id="f_emision2" class="form-control">
                    </div>                    
                </div>
                <div class="control-group margen">
                    <label class="control-label">Fecha Vencimiento: </label>
                    <div class="controls">
                        <input type="date" name="f_vencimiento2" id="f_vencimiento2" class="form-control" >
                    </div>
                </div>
            </div>

            <div class="modal-footer">
               <input type="hidden"  name="id_conductor" value=""> 
                <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary pull-right" id="btn_nuevo">Guardar</button>
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
</style>
<link href="assets/themes/concept/assets/vendor/datepicker/css/datepicker.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/bootstrap-fileinput-master/css/fileinput.css" rel="stylesheet">

<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.es.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-fileinput-master/js/fileinput.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-fileinput-master/js/es.js"></script>

<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/bootstrap-datetimepicker.min.js"></script>

<script>

/*function fecha(){
    console.log('kjdsfhg');
      var disabledDays = [0, 6];
    $('#f_emision2').datepicker({
        language: 'es',
        position:'bottom left',
        onSelect: function(fd, d, picker){
            var fecha_marcada = moment(d).format('YYYY-MM-DD');
            var hoy = moment(new Date()).format('YYYY-MM-DD');

            if(fecha_marcada < hoy){
              //$("#inicio_gestion").val(moment(fecha_marcada).format('DD-MM-YYYY HH:mm'));
            
            }else{
              alertify.error('No puede una fecha mayor a la actual');
              $("#f_emision2").val("");
            }
            //console.log("asd");
        }
    })
}*/

$(function () {
    $("#form_gestion").validate({
    rules: {
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
    },
    //una ves validado guardamos los datos en la DB
    submitHandler: function(form){

        //alert();
        var datos = $("#form_gestion").serialize();
        
        $.ajax({
            type: 'POST',
            url: "?/gon-conductor/guardar",
            data: datos,
            success: function (resp) {
              console.log(resp);
              cont = 0;
              switch(resp){

                case '2': //dataTable.ajax.reload();
                          $("#modal_gestion").modal("hide");
                          location.href = "?/gon-conductor/listar";  
                          break;
                case '1':
                          //dataTable.ajax.reload();
                          $("#modal_gestion").modal("hide");
                          location.href = "?/gon-conductor/listar";  
                          //alertify.success('Se editó la gestión escolar correctamente'); 
                          break;
              }
              //pruebaa();
            }          
        });        
    }
  })



  /*  $.validate({
        modules: 'basic'
    });

    $('#f_emision2').datetimepicker({
        format: '< ? = strtoupper($formato_textual); ?>'
    });

    $('#f_vencimiento2').datetimepicker({
        format: '< ? = strtoupper($formato_textual); ?>'
    });
    $("#form_gestion").validate({
        rules: {
            ruta: {required: true}
            //id_gestion: {required: true}
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: highlight,
        unhighlight: unhighlight,
        messages: {
            ruta: "Debe ingresar un nombre la gestión"
        },
        //una ves validado guardamos los datos en la DB
        submitHandler: function(form){
            var datos = $("#form_gestion").serialize();
            $.ajax({
                type: 'POST',
                url: "?/gon-conductor/guardar",
                data: datos,
                success: function (resp) {
                    console.log(resp);
                    cont = 0;
                    switch(resp){
                        case '2': alertify.success('Se registro la gestión escolar correctamente');
                                  location.href = "?/gon-conductor/listar";  
                                  break;
                        case '1':
                                  location.href = "?/gon-conductor/listar";  
                                  break;
                    }
                }
            });
        }
    })*/
})
</script>