<?php
// Obtiene los permisos
$permiso_crear     = in_array('modalcrear', $_views);
$permiso_contrato  = in_array('modalcontrato', $_views);
$permiso_ver       = in_array('ver', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_eliminar  = in_array('eliminar', $_views);
$permiso_imprimir  = in_array('imprimir', $_views);

?>
<?php  
require_once show_template('header-design');  

$empleados = $db->query("SELECT asi.*, ca.cargo, e.*, p.*
                        FROM sys_persona e 
                        INNER JOIN per_asignaciones asi ON asi.persona_id = e.id_persona                                 
                        INNER JOIN per_postulacion p ON p.id_postulacion = e.postulante_id                                 
                        LEFT JOIN per_cargos ca ON asi.cargo_id = ca.id_cargo

                        LEFT JOIN ins_gestion g ON g.id_gestion='".$_gestion['id_gestion']."' 
                        
                        WHERE g.gestion >= YEAR(asi.fecha_inicio)
                                AND g.gestion <= YEAR(asi.fecha_final)
                                AND asi.estado='A'                        
                        GROUP BY persona_id
                        ")->fetch();
?>
 
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

                    <div class="table-responsive">
                    <table id="table" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">                
                		<thead>
                			<tr class="active">
                				<th class="text-nowrap">#</th>
                				<th class="text-nowrap">Foto</th>
                				<th class="text-nowrap">Nombres</th>
                				<th class="text-nowrap">Número Documento<br>Complemento/Exp.</th>
                				<th class="text-nowrap">Género</th>
                				<th class="text-nowrap">Fecha Nacimiento</th>
                				<th class="text-nowrap">Teléfono</th>
                                <th class="text-nowrap">Correo Electrónico</th>
                				<th class="text-nowrap">Estado</th>
                				<th class="text-nowrap">Cargo</th>
                				<th class="text-nowrap">Salario</th>
                				<th class="text-nowrap">Fecha de<br>Inicio</th>
                                <th class="text-nowrap">Fecha de<br>Finalización</th>
                                <th class="text-nowrap">Opciones</th>				
                			</tr>
                		</thead>
                		 
                		<tbody>
                            <?php 
                                foreach ($empleados as $nro => $empleado){ 
                                    
                                    $id_persona=$empleado['id_persona'];
                                    $id_asignacion=$empleado['id_asignacion'];
                                    $cargo=$empleado['cargo'];
                                    $sueldo_total=$empleado['sueldo_total'];
                                    $horario_id=$empleado['horario_id'];
                                    
                                    $datos=$id_asignacion.'*'.$empleado['foto'].'*'.$empleado['nombres'].'*'.$empleado['primer_apellido'].'*'.$empleado['segundo_apellido'].'*'.$empleado['genero'].'*'.$empleado['fecha_nacimiento'].'*'.$empleado['fecha_nacimiento'].'*'.$cargo.'*'.$sueldo_total.'*'.$horario_id;                
                                ?>
                                <tr>
                                <td><?= $nro+1; ?></td>
                                <td></td>
                                <td><?= $empleado["nombres"].' '.$empleado["primer_apellido"].' '.$empleado["segundo_apellido"]; ?></td>
                                
                                <td><?= $empleado["numero_documento"]; ?> <?= $empleado["complemento"]; ?> <?= $empleado["expedido"]; ?></td>
                                <td><?php if($empleado["genero"]=='m'){
                                        echo 'FEMENINO';
                                     }else{
                                       echo 'MASCULINO';
                                } ?></td>
                                <td><?= date_decode($empleado["fecha_nacimiento"], $_format); ?></td>
                                <td><?= $empleado["celular"]; ?></td>
                                <td><?= $empleado["email"]; ?></td>
                                
                                <td class="text-success">CONTRATADO</td>
                                <td><?= $cargo; ?></td>
                                <td><?= $sueldo_total; ?></td>
                                
                                <td><?= $empleado["fecha_inicio"]; ?></td>
                                <td><?= $empleado["fecha_final"]; ?></td>
                                
                                <td>
                                    <!--a href="?/rrhh-personal/ver/<?= $id_asignacion; ?>" data-toggle="tooltip" data-title="Ver horario" class="btn btn-outline-info btn-xs"><span class="fa fa-eye" ></span></a-->
                    
                                    <a href="#" class="btn btn-outline-warning btn-xs" data-toggle="tooltip" data-title="Editar" onclick="editar_contrato('<?= $id_asignacion; ?>');"><span class="fa fa-list"></span></a>
                    
                                    <a href="#" class="btn btn-outline-warning btn-xs" onclick="abrir_horario('<?php echo $datos ?>');"><span class="fa fa-clock"></span></a>
                    
                                    <a href="?/rrhh-personal/listar-contrato/<?= $id_asignacion; ?>" class="btn btn-outline-warning btn-xs"><span class="fa fa-file"></span></a>
                    
                                    <a href="#" data-toggle="tooltip" data-title="Eliminar horario" data-eliminar="true" class="btn btn-outline-danger btn-xs" onclick="abrir_eliminar('<?= $id_asignacion; ?>');"><span class="fa fa-trash-alt"></span></a>
                                </td>
                                </tr>
                            <?php } ?>
            
                        </tbody>
                		
                	</table>
                    </div>
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

var rutaproceso='?/rrhh-personal/procesos';

//modal inicial listade horarios
var datosasigancion='';
    
function abrir_horario(contenido){

    //alert(contenido);

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
function crear_contrato(nro){
    $("#modal_contrato").modal("show");
    $("#form_contrato")[0].reset();
    $("#btn_contrato_editar").hide();
    $("#btn_contrato_nuevo").show();

    var str_array_skills = "";
    var $select =   $('#materias').selectize();
    var selectize = $select[0].selectize;
    selectize.setValue(str_array_skills);
    selectize.refreshOptions();
    

    var str_array_skills2 = "";
    var $select2 =   $('#nivel_academico').selectize();
    var selectize2 = $select2[0].selectize;
    selectize2.setValue(str_array_skills2);
    selectize2.refreshOptions();
    
    setcargo();
}    
function editar_contrato(nro){
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

            $('#id_persona').val(resp["id_persona"]);
            $('#id_postulante').val(resp["id_postulacion"]);
            $('#id_asignacionx').val(resp["id_asignacion"]);
            
            $('#nombres').val(resp["nombres"]);
            $('#primer_apellido').val(resp["primer_apellido"]);
            $('#segundo_apellido').val(resp["segundo_apellido"]);

            $('#numero_documento').val(resp["numero_documento"]);
            $('#complemento').val(resp["complemento"]);
            $('#expedido').val(resp["expedido"]);

            $('#fecha_nacimiento').val(resp["fecha_nacimiento"]);
            $('#genero').val(resp["genero"]);

            $('#contacto').val(resp["celular"]);
            $('#email').val(resp["email"]);
            $('#direccion').val(resp["direccion"]);

            $('#cargo').val(resp["cargo_id"]);
            $('#fecha_inicio').val(resp["fecha_inicio"]);
            $('#fecha_final').val(resp["fecha_final"]);

            $('#tipo_contrato').val(resp["tipo_contrato"]);
            $('#horas_academicas').val(resp["horas_academicas"]);
            $('#sueldo_por_hora').val(resp["sueldo_por_hora"]);
            $('#sueldo_total').val(resp["sueldo_total"]);


            var str_array_skills = resp["materia_id"].split(',');
            var $select =   $('#materias').selectize();
            var selectize = $select[0].selectize;
            selectize.setValue(str_array_skills);
            selectize.refreshOptions();
            

            var str_array_skills2 = resp["nivel_academico_id"].split(',');
            var $select2 =   $('#nivel_academico').selectize();
            var selectize2 = $select2[0].selectize;
            selectize2.setValue(str_array_skills2);
            selectize2.refreshOptions();
            

            $('#observacion').val(resp["observacion"]);

            if(resp["cargo_id"]==1){
                $("#tipo").val("2");
            }
            else{
                if(resp["materia_id"]==""){
                    $("#tipo").val("1");
                }else{
                    $("#tipo").val("3");
                }
            }
            setcargo();
        }
    });
}
<?php endif ?>

<?php if ($permiso_crear) : ?>
function abrir_eliminar(nro){
    $.ajax({
        url: rutaproceso,
        type: 'POST',
        data:{
            'accion': 'eliminar_personal',
            'id_componente':nro 
            },
        dataType: 'JSON',
        success: function(resp){     
            //alert(resp);
        }
    });
}
<?php endif ?>
    
</script> 
<style>
    .ajs-message.ajs-custom { color: #31708f;  background-color: #d9edf7;  border-color: #31708f; }
</style>