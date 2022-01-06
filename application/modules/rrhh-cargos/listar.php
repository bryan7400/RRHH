<?php
// Obtiene los permisos
$permiso_crear = in_array('modal', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_modificar = in_array('modal', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);

require_once show_template('header-design');  

$cargos = $db->from('per_cargos')
             ->where('estado', "A")
             ->order_by('id_cargo', 'asc')
             ->fetch();
?>
 
<!--cabecera-->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">CARGOS</h2>
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
                        <div class="btn-group">
							<div class="input-group">
								<div class="input-group-append be-addon">
									<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
									<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item">Seleccionar acción</a>
										
                                        <?php if ($permiso_crear) : ?>
                                            <div class="dropdown-divider"></div>
                                            <a href="#" onclick="abrir_modal();" class="dropdown-item" > 
                                                <span class="glyphicon glyphicon-plus"></span> Crear cargo</a>
                                        <?php endif ?> 
                                        <?php if ($permiso_imprimir) : ?>
                                            <div class="dropdown-divider"></div>
                                            <a href="?/rrhh-cargos/imprimir" class="dropdown-item" > 
                                                <span class="glyphicon glyphicon-print"></span> Imprimir cargos</a>
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

                    <div class="table-responsive">
                    <table id="table" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">                
                		<thead>
                			<tr class="active">
                				<th class="text-nowrap">#</th>
                                <th class="text-nowrap">Cargo</th>
                                <th class="text-nowrap">Obligación</th>
                                <th class="text-nowrap">Descripción</th>
                                <?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
                                <th class="text-nowrap">Opciones</th>
                                <?php endif ?>
                			</tr>
                		</thead>
                		 
                		<tbody>
                            <?php foreach ($cargos as $nro => $cargo) : ?>
                            <tr>
                                <th class="text-nowrap"><?= $nro + 1; ?></th>
                                <td class="text-nowrap"><?= escape($cargo['cargo']); ?></td>
                                <td class="width-md"><?= escape($cargo['obligacion']); ?></td>
                                <td class="width-md"><?= escape($cargo['descripcion']); ?></td>
                                <?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
                                <td class="text-nowrap text-center">
                                    <?php if ($permiso_ver) : ?>
                                        <a class="btn btn-default btn-sm" href="?/rrhh-cargos/ver/<?= $cargo['id_cargo']; ?>" data-toggle="tooltip" data-title="Ver cargo">
                                            <span class="glyphicon glyphicon-search"></span></a>
                                    <?php endif ?>
                                    <?php if ($permiso_modificar) : ?>
                                        <a href="#" class="btn btn-outline-warning btn-xs" data-toggle="tooltip" data-title="Editar" onclick="editar('<?= $cargo['id_cargo']; ?>');"><span class="fa fa-list"></span></a>                    
                                    <?php endif ?>
                                    <?php //if ($permiso_eliminar) : ?>
                                        <a href="#" data-toggle="tooltip" data-title="Eliminar" data-eliminar="true" class="btn btn-outline-danger btn-xs" onclick="abrir_eliminar('<?= $cargo['id_cargo']; ?>');"><span class="fa fa-trash-alt"></span></a>
                                    <?php //endif ?>
                                </td>
                                <?php endif ?>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                	</table>
                    </div>
                </form> 
            </div>
        </div>
    </div>

</div>

<?php
    require_once("modal.php");//modal 
?>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>

<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/js/jquery.validate.js"></script>
<script src="<?= js; ?>/jquery.maskedinput.min.js"></script>
<!--libs-->
<script src="<?= js; ?>/selectize.min.js"></script>
 
<script src="assets/themes/concept/assets/vendor/multi-select/js/jquery.multi-select.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>

<script src="assets/themes/concept/assets/vendor/datatables/js/data-table.js"></script>
<script src="assets/themes/concept/assets/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>
<script>
       
var cont = 0;
var dataTable = $('#table').DataTable({
    language: dataTableTraduccion,
    searching: true,
    paging:true,
    "lengthChange": true,
    "responsive": true
});

var rutaproceso='?/rrhh-cargos/procesos';

//modal inicial listade horarios
var datosasigancion='';
         
<?php if ($permiso_crear) : ?>
function abrir_modal(nro){
    $("#modal_contrato").modal("show");
    $("#titulo_persona").text("Crear Cargo");
    $("#form_contrato")[0].reset();
    $("#btn_contrato_editar").hide();
    $("#btn_contrato_nuevo").show();
}    
function editar(nro){

    $("#titulo_persona").text("Editar Cargo");
    $.ajax({
        url: rutaproceso,
        type: 'POST',
        data:{
            'accion': 'recuperar_datos',
            'id_componente':nro 
            },
        dataType: 'JSON',
        success: function(resp){     
            $("#modal_contrato").modal("show");
            $("#form_contrato")[0].reset();
            $("#btn_contrato_editar").hide();
            $("#btn_contrato_nuevo").show();

            $('#id_cargo').val(resp["id_cargo"]);
            $('#cargo').val(resp["cargo"]);
            $('#obligacion').val(resp["obligacion"]);            
            $('#descripcion').val(resp["descripcion"]);            
        }
    });
}
<?php endif ?>

<?php if ($permiso_crear) : ?>
function abrir_eliminar(nro){

    if(confirm("Desea eliminar el cargo?")){
    $.ajax({
        url: rutaproceso,
        type: 'POST',
        data:{
            'accion': 'eliminar_personal',
            'id_componente':nro 
            },
        dataType: 'JSON',
        success: function(resp){     
            location.reload();
        }
    });

    }
}
<?php endif ?>
    
</script> 

<style>
    .ajs-message.ajs-custom { color: #31708f;  background-color: #d9edf7;  border-color: #31708f; }
</style>