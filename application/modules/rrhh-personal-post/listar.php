
<?php
// Obtiene los permisos
$permiso_crear     = in_array('modalcrear', $_views);
$permiso_contrato  = in_array('modalcontrato', $_views);
$permiso_ver       = in_array('ver', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_eliminar  = in_array('eliminar', $_views);
$permiso_imprimir  = in_array('imprimir', $_views);

?>
<?php  require_once show_template('header-design');  ?>
 
<!--cabecera-->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">PERSONAL</h2>
            <p></p>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">RRHH</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Lista de personal </a></li>
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
										
										<?php if ($permiso_contrato) : ?>
                                            <div class="dropdown-divider"></div>
                                            <a href="#" onclick="crear_contrato()"class="dropdown-item" > <span class="fa fa-plus"> </span> Crear contrato</a>
                                        <?php endif ?>                                        
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
                				<th class="text-nowrap">Foto</th>
                				<th class="text-nowrap">Nombres</th>
                				<th class="text-nowrap">Genero</th>
                				<th class="text-nowrap">Fecha de<br>nacimiento</th>
                				<th class="text-nowrap">Telefono</th>
                				<th class="text-nowrap">Cargo</th>
                				<th class="text-nowrap">Salario</th>
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
//if ($permiso_editar) {
	require_once("modalcrear.php");//modal
    require_once("modalcontrato.php");//modal 
    //require_once("modalvercontrato.php");//modal 
//} 
?>


<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/js/jquery.validate.js"></script>
<script src="<?= js; ?>/jquery.maskedinput.min.js"></script>
<!--libs-->
<script src="<?= js; ?>/selectize.min.js"></script>
 
<script src="assets/themes/concept/assets/vendor/multi-select/js/jquery.multi-select.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
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
//ruta de proceso
var rutaproceso='?/rrhh-personal/procesos';

listarpersonal();

function listarpersonal(){
    $.ajax({
        url: rutaproceso,
        type: 'POST',
        data:{
			'accion': 'listar_tabla' 
			},
        dataType: 'JSON',
        success: function(resp){
        
            //console.log('Listar personas '+ resp);

            var counter=1;
            //limpiamos la tabla
            dataTable.clear().draw(); 
            //recorremos los datos retornados y lo añadimos a la tabla
            var counter=1;//numero de datos
            for (var i = 0; i < resp.length; i++) {
                var datos=resp[i]['id_asignacion']+'*'+resp[i]['foto']+'*'+resp[i]['nombres']+ '*'+resp[i]['primer_apellido']+'*'+resp[i]['segundo_apellido']+'*'+resp[i]['genero']+'*'+resp[i]['fecha_nacimiento']+'*'+resp[i]['fecha_nacimiento']+'*'+resp[i]['cargo']+'*'+resp[i]['sueldo_total']+'*'+resp[i]['horario_id'];
                    
                var botones='<a href="?/rrhh-personal/ver/'+resp[i]['id_asignacion']+'" data-toggle="tooltip" data-title="Ver horario" class="btn btn-outline-info btn-xs"><span class="fa fa-eye" ></span></a>';
                    
                botones+='<a href="#"   class="btn btn-outline-warning btn-xs" onclick="abrir_horario('+"'"+datos+"'"+');"><span class="fa fa-clock"></span></a>';
                    
                botones+='<a href="?/rrhh-personal/listar-contrato/'+resp[i]['id_asignacion']+'" class="btn btn-outline-warning btn-xs"><span class="fa fa-file"></span></a>';
                    
                //botones+='<a href="#" data-toggle="tooltip" data-title="Eliminar horario" data-eliminar="true" class="btn btn-outline-danger btn-xs" onclick="abrir_eliminar('+resp[i]['id_asignacion']+');"><span class="fa fa-trash-alt"></span></a>'; 
                    
                 dataTable.row.add( [
                            counter,
                            '',
                            resp[i]["nombres"]+' '+resp[i]["primer_apellido"]+' '+resp[i]["segundo_apellido"],  
                            resp[i]["genero"],  
                            resp[i]["fecha_nacimiento"],  
                            resp[i]["telefono"]+" "+resp[i]["celular"],  
                            resp[i]["cargo"],
                            resp[i]["sueldo_total"],
                            //resp[i]["nombres"],  
                            botones 
                        ] ).draw( false ); 
               counter++;
            }
	    }
	});
}

//resp[i]["foto"]

//modal inicial listade horarios
var datosasigancion='';
    
function abrir_horario(contenido){
     $("#id_componente1").val('0');//id de persona 

    var d = contenido.split("*"); 
    $("#id_componente1").val(d[0]);//id de persona 

    $("#modal_horario").modal("show");    
    $("#btn_editar").show();
    $("#btn_guardar").hide();
    $("#btn_limpìar").hide();
    listarHorario();        
}
        
<?php if ($permiso_crear) : ?>
function crear_contrato(){
    $("#modal_contrato").modal("show");
    $("#form_contrato")[0].reset();
    $("#btn_contrato_editar").hide();
    $("#btn_contrato_nuevo").show();
}    
<?php endif ?>
    
</script> 
<style>
    .ajs-message.ajs-custom { color: #31708f;  background-color: #d9edf7;  border-color: #31708f; }
</style>