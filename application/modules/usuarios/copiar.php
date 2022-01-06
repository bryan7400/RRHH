<?php
  
  $csrf = set_csrf(); 

  // Obtiene los parametros
  $id_gestion = (isset($_params[0])) ? $_params[0] : 0; 

  // Obtiene los parametros
  // $consulta_pensiones = $fila = $db->query("SELECT * FROM vista_inscripciones WHERE gestion_id = $id_gestion AND estudiante_id = $id_estudiante")->fetch_first();

?> 
<!-- <form id="form_gestion"> -->
<div class="modal fade" id="modal_copiar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="titulo_gestion"></span>Copiar Registros Iniciales</h5>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
			</div>

			<div class="modal-body">

        <input type="hidden" name="<?= $csrf; ?>">
        <input type="text" name="id_gestion" id="copiar_id_gestion">

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th class="text-center">Detalle</th>
                  <th class="text-center">Acción</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Modos de Calificación</td>
                  <td class="text-center">
                    <a class="btn btn-primary btn-md" style="color:#fff" id="btn_copiar_modo_calificacion">Copiar</a>
                  </td>
                </tr>
                <tr>
                  <td>Áreas de Evaluación</td>
                  <td class="text-center"><a class="btn btn-primary btn-md" style="color:#fff" id="btn_copiar_area_evaluacion">Copiar</a></td>
                </tr>
                <tr>
                  <td>Tipos de Estudiante</td>
                  <td class="text-center"><a class="btn btn-primary btn-md" style="color:#fff" id="btn_copiar_tipo_estudiante">Copiar</a></td>
                </tr>
                <tr>
                  <td>Nivel Académico</td>
                  <td class="text-center"><a class="btn btn-primary btn-md" style="color:#fff" id="btn_copiar_nivel_academico">Copiar</a></td>
                </tr>
                <tr>
                  <td>Turno</td>
                  <td class="text-center"><a class="btn btn-primary btn-md" style="color:#fff" id="btn_copiar_turno">Copiar</a></td>
                </tr>
              </tbody>
          </table>
        </div>
        <hr>
        <div class="modal-footer">
          <!--<button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary pull-right" id="btn_nuevo">Copiar Todo</button> -->
  			</div>
		</div>
	</div>
</div>
<!-- </form> -->

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

$(window).on("load",cargar_gestion);

function cargar_gestion(){
   var id =  $("#copiar_id_gestion").val();
   console.log(id+'id');
}

$(function () {
  $("#btn_copiar_modo_calificacion").on('click', function() {
    var id_gestion =  $("#copiar_id_gestion").val();
    $.ajax({
      url: '?/s-gestion-escolar/procesos',
      type: 'POST',
      data: {
        'boton': 'btn_copiar_modo_calificacion',
        'id_gestion': id_gestion,
      },
      success: function(resp) {
        switch (resp) {
          case '1':
            $("#modal_copiar").modal("show");
            alertify.success('Se dio de baja el estudiante correctamente');
            $("#btn_copiar_modo_calificacion").hide();
            break;
          case '2':
            $("#modal_copiar").modal("show");
            alertify.error('No se pudo eliminar ');
            break;
        }
      }
    })
  })

  $("#btn_copiar_area_evaluacion").on('click', function() {
    var id_gestion =  $("#copiar_id_gestion").val();
    $.ajax({
      url: '?/s-gestion-escolar/procesos',
      type: 'POST',
      data: {
        'boton': 'btn_copiar_area_evaluacion',
        'id_gestion': id_gestion,
      },
      success: function(resp) {
        switch (resp) {
          case '1':
            $("#modal_copiar").modal("show");
            alertify.success('Se dio de baja el estudiante correctamente');
            break;
          case '2':
            $("#modal_copiar").modal("show");
            alertify.error('No se pudo eliminar ');
            break;
        }
      }
    })
  })

  $("#btn_copiar_tipo_estudiante").on('click', function() {
    var id_gestion =  $("#copiar_id_gestion").val();
    $.ajax({
      url: '?/s-gestion-escolar/procesos',
      type: 'POST',
      data: {
        'boton': 'btn_copiar_tipo_estudiante',
        'id_gestion': id_gestion,
      },
      success: function(resp) {
        switch (resp) {
          case '1':
            $("#modal_copiar").modal("show");
            alertify.success('Se dio de baja el estudiante correctamente');
            break;
          case '2':
            $("#modal_copiar").modal("show");
            alertify.error('No se pudo eliminar ');
            break;
        }
      }
    })
  })

  $("#btn_copiar_nivel_academico").on('click', function() {
    var id_gestion =  $("#copiar_id_gestion").val();
    $.ajax({
      url: '?/s-gestion-escolar/procesos',
      type: 'POST',
      data: {
        'boton': 'btn_copiar_nivel_academico',
        'id_gestion': id_gestion,
      },
      success: function(resp) {
        switch (resp) {
          case '1':
            $("#modal_copiar").modal("show");
            alertify.success('Se dio de baja el estudiante correctamente');
            break;
          case '2':
            $("#modal_copiar").modal("show");
            alertify.error('No se pudo eliminar ');
            break;
        }
      }
    })
  })

  $("#btn_copiar_turno").on('click', function() {
    var id_gestion =  $("#copiar_id_gestion").val();
    $.ajax({
      url: '?/s-gestion-escolar/procesos',
      type: 'POST',
      data: {
        'boton': 'btn_copiar_turno',
        'id_gestion': id_gestion,
      },
      success: function(resp) {
        switch (resp) {
          case '1':
            $("#modal_copiar").modal("show");
            alertify.success('Se dio de baja el estudiante correctamente');
            break;
          case '2':
            $("#modal_copiar").modal("show");
            alertify.error('No se pudo eliminar ');
            break;
        }
      }
    })
  })

})
</script>