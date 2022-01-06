<?php
  $csrf = set_csrf();
?> 

<form id="form_gestion">
<div class="modal fade" id="modal_gestion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="titulo_gestion"></span> Gestión Escolar</h5>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
			</div>
			<div class="modal-body">
				<div class="control-group">
					<label class="control-label">Nombre Gestión: </label>
					<div class="controls">
                        <input type="hidden" name="<?= $csrf; ?>">
                        <input id="id_gestion" name="id_gestion" type="hidden" class="form-control">						
						<input id="nombre_gestion" name="nombre_gestion" type="text" class="form-control">
					</div>
				</div>
				<div class="control-group margen"> 
					<label class="control-label">Inicio Gestión: </label>
					<div class="controls">
						<input id="inicio_gestion" name="inicio_gestion" type="date" class="form-control">
					</div>
				</div>
				<div class="control-group margen">
					<label class="control-label">Final Gestión: </label>
					<div class="controls">
						<input id="final_gestion" name="final_gestion" type="date" class="form-control">
					</div>
        </div>
        <div class="control-group margen">
					<label class="control-label">Inicio Vacación: </label>
					<div class="controls">
						<input id="inicio_vacaciones" name="inicio_vacaciones" type="date" class="form-control">
					</div>
        </div>
        <div class="control-group margen">
					<label class="control-label">Final Vacación: </label>
					<div class="controls">
						<input id="final_vacaciones" name="final_vacaciones" type="date" class="form-control">
					</div>
				</div>
			</div>
			<div class="modal-footer">
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
<script>
$(function () {

  $("#form_gestion").validate({
    rules: {
        final_gestion: {required: true},
        inicio_gestion: {required: true},
        nombre_gestion: {required: true}
        //id_gestion: {required: true}
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
        final_gestion: "Debe seleccionar la fecha a terminar.",
        inicio_gestion: "Debe seleccionar la fecha de inicio.",
        nombre_gestion: "Debe ingresar un nombre la gestión"
    },
    //una ves validado guardamos los datos en la DB
    submitHandler: function(form){

        //alert();
        var datos = $("#form_gestion").serialize();
        $.ajax({
            type: 'POST',
            url: "?/s-gestion-escolar/guardar",
            data: datos,
            success: function (resp) {
              console.log(resp);
              cont = 0;
              switch(resp){

                case '2': dataTable.ajax.reload();
                          $("#modal_gestion").modal("hide");
                          alertify.success('Se registro la gestión escolar correctamente');
                          break;
                case '1':
                          dataTable.ajax.reload();
                          $("#modal_gestion").modal("hide");
                          alertify.success('Se editó la gestión escolar correctamente'); break;
              }
              //pruebaa();
            }
            
        });
        
    }
  })
})
</script>