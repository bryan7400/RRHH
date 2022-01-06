<?php
// Obtiene los permisos
$permiso_crear     = in_array('modalcrear', $_views);
/*
$permiso_contrato  = in_array('modalcontrato', $_views);
$permiso_ver       = in_array('ver', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_eliminar  = in_array('eliminar', $_views);
$permiso_imprimir  = in_array('imprimir', $_views);
*/
?>
<?php  require_once show_template('header-design');  ?>
<!--<link rel="stylesheet" href="assets/themes/concept/assets/vendor/multi-select/css/multi-select.css">-->
 
<!--cabecera-->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">PLANILLA DE SUELDOS</h2>
            <p></p>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">RRHH</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Lista de Planilla de Sueldos </a></li>                        
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
                                            <a href="#" onclick="crear_contrato();"class="dropdown-item" > <span class="fa fa-plus"> </span> Crear Planilla Mensual</a>
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
 
                <form class="" id="form-menu" autocomplete="off">
                    <!--input type="hidden" name="<?php // $csrf; ?>"-->
                    
                    <?php //if ($horarios) : ?>
                	<table id="table" class="table table-bordered table-condensed table-striped table-hover">
                		<thead>
                			<tr class="active">
                				<th class="text-nowrap">#</th>
                				<th class="text-nowrap">Año</th>
                				<th class="text-nowrap">Mes</th>
                				<th class="text-nowrap">Nro. Personas</th>
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
if ($permiso_crear) {
	require_once("modalcrear.php");
} 
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
var dataTable = $('#table').DataTable({
    language: dataTableTraduccion,
    searching: true,
    paging:true,
    stateSave:true,
    "lengthChange": true,
    "responsive": true
});

//var rutaproceso='?/rrhh-personal/procesos';

listarplanillas();

function listarplanillas(){
    $.ajax({
        url: '?/rrhh-planilla-pagos/listar-datos',
        type: 'POST',
        data:{
			
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
                var datos=resp[i]['anio']+'*'+resp[i]['mes']+'*'+resp[i]['nro'];
                    
                var botones='';
                
                    
                var botones='<a target="_blank" href="?/rrhh-planilla-pagos/planilla-mensual/'+resp[i]['anio']+'/'+resp[i]['mes']+'"  class="btn btn-danger btn-xs"><span class="fa fa-file"></span> Ver Planilla</a>&nbsp;';
                
                botones+='<a href="?/rrhh-planilla-pagos/imprimir-planilla/'+resp[i]['anio']+'/'+resp[i]['mes']+'" target="_blank" class="btn btn-success btn-xs"><span class="fa fa-file"></span> Imprimir</a>';
 
                

                switch(resp[i]["mes"]){
                    case "1":   resp[i]["mes"]="Enero";     break;
                    case "2":   resp[i]["mes"]="Febrero";   break;
                    case "3":   resp[i]["mes"]="Marzo";     break;
                    case "4":   resp[i]["mes"]="Abril";     break;
                    case "5":   resp[i]["mes"]="Mayo";      break;
                    case "6":   resp[i]["mes"]="Junio";     break;
                    case "7":   resp[i]["mes"]="Julio";     break;
                    case "8":   resp[i]["mes"]="Agosto";         break;
                    case "9":   resp[i]["mes"]="Septiembre";     break;
                    case "10":   resp[i]["mes"]="Octubre";       break;
                    case "11":   resp[i]["mes"]="Noviembre";     break;
                    case "12":   resp[i]["mes"]="Diciembre";     break;
                }

                dataTable.row.add( [
                                    counter,
                                    resp[i]["anio"],
                                    resp[i]["mes"],  
                                    resp[i]["nro"],  
                                    botones 
                                ] ).draw(false ); 
                counter++;
            }
    	}
    });
}
//modal inicial listade horarios
var datosasigancion='';
        
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