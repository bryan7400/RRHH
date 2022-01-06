<?php
$csrf = set_csrf();
// Obtiene los permisos
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);

//obtiene los roles 
$roles = $db->query("SELECT * FROM sys_roles WHERE rol != 'Superusuario'")->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <?php require_once show_template('header-design'); ?>
<!--<link rel="stylesheet" href="assets/themes/concept/assets/vendor/bootstrap/css/bootstrap.min.css">
<link href="assets/themes/concept/assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
<link rel="stylesheet" href="assets/themes/concept/assets/libs/css/style.css">-->
<!--<link rel="stylesheet" href="assets/themes/concept/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">-->
<link href="assets/themes/concept/assets/vendor/bootstrap-colorpicker/%40claviska/jquery-minicolors/jquery.minicolors.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/datepicker/css/datepicker.css" rel="stylesheet">
</head>
<body>
   <!--	.col-xs-	.col-sm-	.col-md-	.col-lg--->
    <div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
        <div class="page-header">
            <h2 class="pageheader-title">Comunicados</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Agenda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Comunicados</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<form class="form_comunicado" id="form_agregar_comunicado"> 
 <div class="row">
 
 
	<div class="col-xl-4 col-lg-5 col-md-12   col-sm-12 col-xs-12">
	<div class="card" style="    min-height: 45em;">
 
   <div class="card-body">
       <!-- Modal -->
 
        <div class="modal-content">
          <div class="card-head">
            <h3 class="p-4">Comunicado</h3>
          </div>
            <div class="modal-body"> <!--comieza el body de la modal-->
             <div class="form-group" style="margin-bottom:15px;">
                  <label for="title" class="control-label">Roles:</label>
                <div class="controls control-group">
                  <select class="selectpicker form-control" id="select_roles" name="select_roles" multiple title="Seleccione" onchange="listar(this);">
                    <?php
                      foreach ($roles as $key => $rol) {
                    ?>
                      <option value="<?= $rol['id_rol'];?>"><?= $rol['rol']?></option>
                    <?php
                      }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group" style="margin-bottom:15px;">
                <label class="control-label">Titulo:</label>
                <div class="controls control-group">
                  <input type="hidden" name="id_comunicado" class="form-control" id="id_comunicado">
                  <input type="text" name="nombre_evento" class="form-control" id="nombre_evento">
                </div>
              </div>
              <div class="form-group" style="margin-bottom:15px;">
                <label for="title" class="control-label">Descripción:</label>
                <div class="controls control-group">
                  <input type="text" name="descripcion_evento" class="form-control" id="descripcion_evento">
                </div>
              </div>			  
              <div class="form-group">
                <label for="hiddeninput">Color:</label>
                <br>
                <input type="hidden" id="color_evento" name="color_evento" class="form-control" value="#3462c0">
              </div>
 
              <div class="form-group" style="margin-bottom:15px;">
                <label for="title" class="control-label">Fecha de Inicio:</label>
                <div class="controls control-group">
                    <input type='text' class='datepicker-here form-control' id="fecha_inicio" name="fecha_inicio" readOnly/>
                </div>
              </div>
              <div class="form-group" style="margin-bottom:15px;">
                <label for="title" class="control-label">Fecha a Terminar:</label>
                <div class="controls control-group">
                    <input type='text' class='datepicker-here form-control' id="fecha_final" name="fecha_final" readOnly/>
                </div>
              </div>
          
              <div class="form-group" id="div_eliminar" style="display:none">
           
                <label class="custom-control custom-checkbox" style="color:red">
                  <input type="checkbox" class="custom-control-input" name="eliminar" id="eliminar"><span class="custom-control-label">Eliminar evento</span>
                </label>
              </div>

            </div><!--termina el body de la modal-->
            
          
        </div>
 
    </div><!--fin card body-->
    </div>
         
    </div>
    <div class="col-xl-8 col-lg-7 col-md-12 col-sm-12 col-12">
	<div class="card" style="    min-height: 45em;">
    <div class="card-head">
        <h3 class="p-4">Lista de envio</h3>
    </div>
       <div class="card-body">
        <div class="table-responsive">
				<table id="Tabla_personas" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
					<thead>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<th class="text-nowrap">Eviar a:</th>
							<th class="text-nowrap">Nombre</th>
							<th class="text-nowrap">Apellido paterno</th>
							<th class="text-nowrap">Apellido materno</th>
							<th class="text-nowrap">Documento</th>
							<?php// if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
							<th class="text-nowrap">Genero</th>
							<?php// endif ?>
						</tr>
					</thead>
				<!--	<tfoot>
						<tr class="active">
							<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
							<th class="text-nowrap text-middle">Fecha de Inicio</th>
							<th class="text-nowrap text-middle">Fecha a Termina</th>
							<th class="text-nowrap text-middle">Nombre del Comunicado</th>
							<th class="text-nowrap text-middle">Descripción</th>
							<th class="text-nowrap text-middle">Usuarios</th>
							<?php// if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
							<th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
							<?php// endif ?>
						</tr>
					</tfoot>-->
					<tbody id="listado_gestion_escolar">
					</tbody>
				</table>
				</div>
    </div>
        <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal" >Limpiar</button>
              <button type="submit" class="btn btn-primary" id="btn_agregar" >Guardar todo</button>
              <!--<button type="submit" class="btn btn-primary" id="btn_editar">Editar</button>-->
        </div>
        </div>
    </div>
    
    
</div>
</form> 
  
<!-- librerias para full calendar -->
<!--<script src="<?//= themes; ?>/concept/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
<script src="<?//= themes; ?>/concept/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
<script src="<?//= themes; ?>/concept/assets/vendor/full-calendar/js/calendar.js"></script>-->
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script><!--
<script src="<?//= themes; ?>/concept/assets/vendor/full-calendar/js/fullcalendar.js"></script>
<script src="<?//= themes; ?>/concept/assets/vendor/full-calendar/js/es.js"></script>
<script src="<?//= themes; ?>/concept/assets/vendor/full-calendar/js/jquery-ui.min.js"></script>-->

<!-- librerias para el color -->
<script src="assets/themes/concept/assets/vendor/bootstrap-colorpicker/%40claviska/jquery-minicolors/jquery.minicolors.min.js"></script>

<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-select/js/bootstrap-select.js"></script>
<!--<script src="<?//= themes; ?>/concept/assets/vendor/bootstrap-select/js/require.js"></script>-->

<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>

<!--<script src="<?= js; ?>/selectize.min.js"></script>-->
<!--<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>-->
<!--<script src="<?= js; ?>/educheck.js"></script>-->

<!--<script src="<?= themes; ?>/concept/assets/libs/js/main-js.js"></script>-->
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.es.js"></script>



<?php //require_once show_template('footer-design'); ?>

<?php 
/*	if($permiso_crear){
		require_once ("crear.php");
	}*/
?>

<script>
    $('#color_evento').minicolors({
        theme: 'bootstrap'
});
    //formato de fchas
    
var disabledDays = [0, 6];
$('#fecha_inicio').datepicker({
    timepicker: true,
    language: 'es',
    position:'top left',
    //startDate: start,
    minHours: 8,
    maxHours: 18,
    timeFormat: "hh:ii",
    /*onRenderCell: function (date, cellType) {
    if (cellType == 'day') {
            var day = date.getDay(),
                isDisabled = disabledDays.indexOf(day) != -1;

            return {
                disabled: isDisabled
            }
        }
    },*/
    onSelect: function(fd, d, picker){
        var fecha_marcada = moment(d).format('YYYY-MM-DD');
        var hoy = moment(new Date()).format('YYYY-MM-DD');

        if(fecha_marcada >= hoy){
          //$("#fecha_inicio").val(moment(fecha_marcada).format('DD-MM-YYYY HH:mm'));
        
        }else{
          alertify.error('No puede asignar tareas en una fecha pasada a la actual');
          $("#fecha_inicio").val("");
        }
        //console.log("asd");
    }
})

$('#fecha_final').datepicker({
    timepicker: true,
    language: 'es',
    position:'top left',
    //startDate: start,
    minHours: 8,
    maxHours: 18,
    timeFormat: "hh:ii",
    /*onRenderCell: function (date, cellType) {
    if (cellType == 'day') {
            var day = date.getDay(),
                isDisabled = disabledDays.indexOf(day) != -1;

            return {
                disabled: isDisabled
            }
        }
    },*/
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
//datatables y llenado
var dataTable = $('#Tabla_personas').DataTable({
  language: dataTableTraduccion,
  searching: true,
  paging:true,
  stateSave:true,
  "lengthChange": true,
  "responsive": true
  });
function listar(thiss){
    //alert(thiss.getValue());
    //var valores=$("#select_roles").val();
    var valores=$(thiss).val();
   // alert(valores);
   listar_personas(valores);//ajax
  
}
     
function listar_personas(valores) {
 
	//var aula = $("#aula option:selected").val();//this
	//var turno = $("#turno option:selected").val();//this
	//var nivel = $("#nivel option:selected").val();//this
    var boton='';
      
		$.ajax({
			url: '?/s-comunicados/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_persona',
				'valores': valores 
			},
			dataType: 'JSON',
			success: function(resp){
		    console.log(resp);
                
      
        var counter=1;
        dataTable.clear().draw();//limpia y actualisa la tabla
for (var i = 0; i <resp.length; i++) {
   console.log(resp[i]["nombres"]);
 
    var gen='';
    if(resp[i]["genero"]=='v'){
        gen=' Varon <span class="icon-user"></span>';
        
    }  else if(resp[i]["genero"]=='m'){
        gen='<span class="icon-user-female"></span> Mujer';
        
    }
   //lista aula paralelo controls control-group
        dataTable.row.add( [
            counter,
            '<div class="controls control-group"><input class="checkenvio form-control" type="checkbox" name="est[]" value="'+resp[i]["id_persona"]+'" /></div>',
            resp[i]["nombres"],
            resp[i]["primer_apellido"],
            resp[i]["segundo_apellido"],
            'Doc: '+resp[i]["numero_documento"],
            gen//,
            //resp[i]["fecha_nacimiento"]//,
            /*star,star,
            "<a class='btn btn-info btn-xs' ONCLICK='abrir_ver("+'"'+resp[i]["id_aula_paralelo"]+'*'+resp[i]["id_turno"]+'"'+")'><span class='icon-eye'></span></a><a class='btn btn-success btn-xs' ONCLICK='abrir_crear("+'"'+resp[i]["id_aula_paralelo"]+'*'+resp[i]["id_turno"]+'"'+")'><span class='icon-plus'></span></a>"*/
        ] ).draw( false ); 
           counter++;
    }//fin for
     console.log('fin for');
				 
		}
		});
	}
    
//guardar comunicado
        //Guardar
  //$("form#form_comunicado").validate({
   
$("#form_agregar_comunicado").validate({
  rules: {
      nombre_evento: {required: true},
      fecha_inicio: {required: true},
	    fecha_final: {required: true},
	    select_roles: {required: true},
	    est: {required: true}
      
      //id_gestion: {required: true}
  },
  errorClass: "help-inline",
  errorElement: "span",
  highlight: highlight,
  unhighlight: unhighlight,
  messages: {
      nombre_evento: "Debe ingresar el nombre del evento.",
      fecha_inicio: "Debe seleccionar la fecha de inicio.",
	    fecha_final: "Debe seleccionar la fecha a terminar.",
      select_roles: "Debe seleccionar al menos un rol.",
      est: "Debe seleccionar al menos un rol."
  },
  //una ves validado guardamos los datos en la DB
  submitHandler: function(form){
	//e.preventDefault();
    var parametros = {
	    'id_comunicado': $("#id_comunicado").val(),
      'nombre_evento': $("#nombre_evento").val(),
      'descripcion': $("#descripcion_evento").val(),
      'color': $("#color_evento").val(),
      'fecha_inicio': $("#fecha_inicio").val(),
      'fecha_final': $("#fecha_final").val(),
	    'roles': $("#select_roles").val(),
	    'est': $(".checkenvio").val()
    }
  
	 var parametros2 = $("#form_agregar_comunicado").serialize();

    $.ajax({
          type: 'POST',
          url: "?/s-comunicados/guardar-personal",
          data: parametros2,
          success: function (resp) {
            console.log(resp);
            cont = 0;
            switch(resp){
              case '1': dataTable.ajax.reload();
                        $("#modal_agregar_evento").modal("hide");
                        alertify.success('Se registro el comunicado correctamente');
                        break;
              case '2':
                        dataTable.ajax.reload();
                        $("#modal_agregar_evento").modal("hide");
                        alertify.success('Se editó el comunicado correctamente'); break;
                case '3':
                     
                        alertify.success('Deve seleccionar un nombre de la lista'); break;
            }
            //pruebaa();
          }
          
    });



    /*$.ajax({
        url: '?/s-comunicados/procesos',
        type: 'POST',
        data: parametros,
        success: function (data){
          //console.log(data);
          if(data == 1){
            $('#modal_agregar_evento').modal('hide');
            $("#form_agregar_evento")[0].reset();
            $("#calendario").fullCalendar("refetchEvents");
            alertify.success('Se agrego el evento correctamente');
          }else{
            alertify.error('No se pudo agregar el evento');
          }
        }
    });


      //alert();*/
      
      
  }
})
    
</script>

</body>
</html>