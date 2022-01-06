<?php
// Obtiene los horarios
//$horarios = $db->from('per_horarios')->order_by('id_horario', 'asc')->fetch();

// Obtiene los permisos
$permiso_crear     = in_array('crear', $_views);
$permiso_ver       = in_array('ver', $_views);
$permiso_modificar = in_array('x-modificar', $_views);
$permiso_eliminar  = in_array('x-eliminar', $_views);
$permiso_imprimir  = in_array('imprimir', $_views);

?>
<?php $nivel_docente = $db->query("SELECT * 
                                  FROM ins_nivel_academico 
                                  WHERE estado = 'A' AND gestion_id=1 
                                  ORDER BY id_nivel_academico ASC
                                  ")->fetch();?>
<?php //var_dump($nivel_docente);exit();// $contador=0;?>
<?php

require_once show_template('header-design');  ?>
<!-- <link rel="stylesheet" href="assets/themes/concept/assets/vendor/multi-select/css/multi-select.css"> -->
 
<!--cabecera-->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">HORARIOS  </h2>
            <p>Se designa horarios los cuales seran controlados para pago de salarios</p>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">RRHH</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Registros iniciales</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">horarios</a></li>
                        
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!--cuerpo card table--> 
<div class="row"> 
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            
            <div class="card-header">
 
                <div class="row">
          
 
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-right">
                        <div class="text-label hidden-xs">Seleccionar acción:</div>
						<div class="btn-group">
								<div class="input-group">
								<div class="input-group-append be-addon">
									<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
									<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item">Seleccionar acción</a>
										<?php if ($permiso_crear) : ?>
										<div class="dropdown-divider"></div>
										<a href="#"  onclick="abrir_crear()"class="dropdown-item" > <span class="fa fa-plus"> </span>  Crear horario</a> 
										<?php endif ?>  
										
										<?php  if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/rh-horarios/imprimir" class="dropdown-item" target="_blank"><span class="fa fa-print"> </span> Imprimir Asignacion</a>
										<?php  endif ?>
									</div>
								</div>
							</div>
						</div> 
					</div>
    
                </div>
            </div>

            <div class="card-body">
 
                    <input type="hidden" name="<?= $csrf; ?>">
 
                  
                   
                   <form class="" id="form-menu" method="post" action="?/s-curso-paralelo/guardar" autocomplete="off">
                    <input type="hidden" name="<?= $csrf; ?>">
                    
                  <?php //if ($horarios) : ?>
	<table id="table" class="table table-bordered table-condensed table-striped table-hover">
		<thead>
			<tr class="active">
				<th class="text-nowrap">#</th>
				<th class="text-nowrap">Días</th>
				<th class="text-nowrap">Entrada</th>
				<th class="text-nowrap">Salida</th>
				<th class="text-nowrap">Descripción</th>
				 
				<th class="text-nowrap">Opciones</th>
				 
			</tr>
		</thead>
		 
		<tbody>
		 
		</tbody>
	</table>
  </form> 
        </div>
        </div>
    </div>
 
</div>

<?php
if ($permiso_modificar || $permiso_crear) {
	require_once("crear.php");//modal
}
if ($permiso_ver) {
	require_once("ver.php");//modal 
}
?>


 <script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/js/jquery.validate.js"></script>
<script src="<?= js; ?>/jquery.maskedinput.min.js"></script>
<!--libs-->
<script src="<?= js; ?>/selectize.min.js"></script>
 
<script src="assets/themes/concept/assets/vendor/multi-select/js/jquery.multi-select.js"></script>

<!--<script src="application/modules/generador-menus/generador-menu.js"></script>    -->
<script>
   // $("#modal_horario").modal("show");
$(function () {
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/horarios/crear';
				break;
			}
		}
	});
	<?php endif ?>
 
     
});
 var dataTable = $('#table').DataTable({
  language: dataTableTraduccion,
  searching: true,
  paging:true,
  stateSave:true,
  "lengthChange": true,
  "responsive": true
  });
//FUNCION DE RECARGA DE DATATABLE 
    listartabla();
    function listartabla(){
        $.ajax({
        url: '?/rrhh-horarios/procesos',
        type: 'POST',
        data: {
				'accion': 'listar_horarios' 
			},
        dataType: 'JSON',
        success: function(resp){
        console.log('Listar personas '+ resp);

        var counter=1;
        //limpiamos la tabla
        dataTable.clear().draw(); 
        //recorremos los datos retornados y lo añadimos a la tabla
        var counter=1;//numero de datos
        for (var i = 0; i < resp.length; i++) {
        var datos=resp[i]['id_horario']+'*'+resp[i]['dias']+ '*'+resp[i]['entrada']+ '*'+resp[i]['salida']+ '*'+resp[i]['descripcion']+ '*'+resp[i]['fecha_inicio']+ '*'+resp[i]['fecha_fin']+ '*'+resp[i]['aplicadoa']+ '*'+resp[i]['concepto_pago_id'];
           
//$permiso_crear 
var botones='';
        <?php  if ($permiso_ver) : ?> 
        botones+='<a href="#" data-toggle="tooltip" data-title="Ver horario" class="btn btn-outline-info btn-xs" onclick="abrir_ver('+"'"+datos+"'"+');"><span class="fa fa-eye" ></span></a>';
          <?php  endif ?>   
        <?php  if ($permiso_modificar) : ?> 
        botones+='<a href="#"   class="btn btn-outline-warning btn-xs" onclick="abrir_editar('+"'"+datos+"'"+');"><span class="fa fa-edit"></span></a>';
        <?php  endif ?> 
        <?php  if ($permiso_eliminar) : ?> 
        botones+='<a href="#" data-toggle="tooltip" data-title="Eliminar horario" data-eliminar="true" class="btn btn-outline-danger btn-xs" onclick="abrir_eliminar('+resp[i]['id_horario']+');"><span class="fa fa-trash-alt"></span></a>';//acciones de la ultima columna
        <?php  endif ?> 
            
         dataTable.row.add( [
                    counter,
                    resp[i]["dias"],
                    resp[i]["entrada"],
                    resp[i]["salida"],
                    resp[i]["descripcion"], 
                    botones 
                ] ).draw( false ); 
           counter++;
        }

	 }
	 });
	}
    
    function abrir_crear(e){
         //e.preventDefault();
          $("#id_componente").val(''); 
          $("#formCrear")[0].reset(); 
          $("#modal_horario").modal("show");
         //limpiamos valores de dias selectize
         $("#dias").data('selectize').setValue('');
            $("#btn_editar").hide();
        $("#btn_guardar").show();
        $("#btn_limpìar").show();
         return false;
     }
    
   function abrir_ver(contenido){ 
      
    var d = contenido.split("*");
    var id_horario = d[0];// id_horario
    var dias = d[1];// dias 
    var inicio = d[2];// inicio 
    var final = d[3];// final 
    var comentario = d[4];// comentario 
var diasArray = d[1].split(',');
 
    $("#id_componente").val(d[0]); 
       //caso lelect
     $("#v_dias").parent().html('<b>DIAS:</b> '+dias);    
    $("#v_entrada").parent().html('<b>ENTRADA:</b> '+inicio);  
    $("#v_salida").parent().html('<b>SALIDA:</b> '+final);  
    $("#v_descripcion").parent().html('<b>DESCRIPCION:</b> '+comentario);  
    $("#modalver").find('input').attr('disbled','true');
    $("#modalver").modal("show");
  
 
}
 
    
   function abrir_editar(contenido){
    
    var d = contenido.split("*");
    var id_horario = d[0];// id_horario
    var dias = d[1];// dias 
    var inicio = d[2];// inicio 
    var final = d[3];// final 
    var comentario = d[4];// comentario 
    var fecha_inicio = d[5];//  
    var fecha_final = d[6];//  
    var concepto_pago = d[8];// concepto pago 
var diasArray = d[1].split(',');
var aplicadoaArray = d[7].split(',');
   
  console.log(diasArray);
 
    $("#id_componente").val(d[0]); 
    $("#dias").data('selectize').setValue(diasArray);    
    $("#entrada").val(d[2]);
    $("#salida").val(d[3]);
    $("#descripcion").val(d[4]);
    $("#fecha_inicio").val(d[5]);
    $("#fecha_fin").val(d[6]);
    $("#concepto_pago").val(d[8]);
    $("#aplicadoa").data('selectize').setValue(aplicadoaArray);
            
    $("#modal_horario").modal("show");
            
         
//PRUEBAS DE SELECTIZE
//$('#select_roles').selectpicker('val', cadena);//siii multiselect
//$("#dias").data('selectize').setValue('mie');//a una lista select
//$("#dias").data('selectize').setValue( ['mar','lun']);//siuiii lista de datos cargado s avista con select
     
    //$('#dias').selectpicker('val', cadena);
   //$("#dias").data('selectize').setValue(cadena); 
    //$("#dias").getOption(1); 
    //$("#dias").addItem(1, silent); 
 //$('#dias').selectpicker('val', cadena);
 //$('#dias').data('selectize').setValue(1);
 //$("#dias").html('<option selected>sdf</option>'); 



    $("#btn_editar").show();
    $("#btn_guardar").hide();
    $("#btn_limpìar").hide();
//$("#select_roles").
}

/*alertify.defaults.transition = "slide";
alertify.defaults.theme.ok = "btn btn-primary";
alertify.defaults.theme.cancel = "btn btn-danger";
alertify.defaults.theme.input = "form-control";*/
    function abrir_eliminar(id_horario){
    //preguntar si se eliminara?
         alertify.confirm('<span style="color:red">ELIMINAR HORARIO</span>', 'Deve estar con autorizacion de para esta accion, si lo tiene ¿desea eliminar?', function(){ //casi de si
            $.ajax({
                url: '?/rrhh-horarios/procesos',
                type:'POST',
                data: {accion:'eliminar_horarios',
                    'id_componente':id_horario},
                success: function(resp){
                     
                    switch(resp){
                        case '1': $("#modal_eliminar").modal("hide");
                        alertify.success('Se elimino el horario correctamente');
                        listartabla(); break;
                        case '2': $("#modal_eliminar").modal("hide");
                         alertify.error('No se pudo eliminar '+resp); 
                        break;
                    }
                }
            }) ;
             //alertify.success('Eliminado')  
         }, function(){ 
              alertify.notify('No eliminado', 'custom');
              //alertify.notify('custom message.', 'custom', 20);
             //alertify.error('Cancel');
         
         })
    }
</script> 
<style>
.ajs-message.ajs-custom { color: #31708f;  background-color: #d9edf7;  border-color: #31708f; }
</style>