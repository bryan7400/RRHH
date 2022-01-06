<?php
// Obtiene los permisos

$fecha_inicio_filtro = (isset($_params[0])) ? $_params[0] : 0;

$fecha_final_filtro = (isset($_params[1])) ? $_params[1] : 0;

//var_dump($fecha_final_filtro);die;
$permiso_crear     = in_array('modalcrear', $_views);
$permiso_contrato  = in_array('modalcontrato', $_views);
$permiso_ver       = in_array('ver', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_editar = in_array('editar', $_views);
$permiso_eliminar  = in_array('eliminar', $_views);
$permiso_imprimir  = in_array('imprimir', $_views);
$nombre_dominio = escape($_institution['nombre_dominio']); 
?>
<?php  
require_once show_template('header-design');  




if ($fecha_inicio_filtro=="0") {
    

    //var_dump($contratos);die;
    

    


$empleados = $db->query("SELECT asi.*, ca.cargo, e.*, p.*
                        FROM sys_persona e 
                        INNER JOIN per_asignaciones asi ON asi.persona_id = e.id_persona                                 
                        INNER JOIN per_postulacion p ON p.id_postulacion = e.postulante_id                                 
                        LEFT JOIN per_cargos ca ON asi.cargo_id = ca.id_cargo

                        LEFT JOIN ins_gestion g ON g.id_gestion='".$_gestion['id_gestion']."' 
                        
                        WHERE g.gestion >= YEAR(asi.fecha_inicio)
                                AND (g.gestion <= YEAR(asi.fecha_final)
                                OR asi.fecha_final = '0000-00-00')
                                AND asi.estado='A'                        
                        GROUP BY persona_id
                        ")->fetch();



}else{


   $fecha_inicio  = $fecha_inicio_filtro;
    $fecha_final  = $fecha_final_filtro;
//var_dump($_POST);die;
$empleados = $db->query("SELECT asi.*, ca.cargo, e.*, p.*
    FROM sys_persona e 
    INNER JOIN per_asignaciones asi ON asi.persona_id = e.id_persona                                 
    INNER JOIN per_postulacion p ON p.id_postulacion = e.postulante_id                                 
    LEFT JOIN per_cargos ca ON asi.cargo_id = ca.id_cargo

    LEFT JOIN ins_gestion g ON g.id_gestion='".$_gestion['id_gestion']."' 
    
    WHERE g.gestion >= YEAR(asi.fecha_inicio)
            AND (g.gestion <= YEAR(asi.fecha_final)
            OR asi.fecha_final = '0000-00-00')
            AND asi.estado='A'
            AND  date(asi.fecha_inicio) BETWEEN '$fecha_inicio'AND '$fecha_final'                        
    GROUP BY persona_id
    ")->fetch();




        



        
}   




?>
 <link rel="stylesheet" href="assets/themes/concept/assets/vendor/cropper-mazter/css/cropper.css">
<link href="assets/themes/concept/assets/vendor/datepicker/css/datepicker.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/bootstrap-fileinput-master/css/fileinput.css" rel="stylesheet">
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">
<style>
    @media (min-width: 768px) {

        .table-display>.tbody>.tr>.td,
        .table-display>.tbody>.tr>.th,
        .table-display>.tfoot>.tr>.td,
        .table-display>.tfoot>.tr>.th,
        .table-display>.thead>.tr>.td,
        .table-display>.thead>.tr>.th {
            padding-bottom: 15px;
            vertical-align: top;
        }

        .table-display>.tbody>.tr>.td:first-child,
        .table-display>.tbody>.tr>.th:first-child,
        .table-display>.tfoot>.tr>.td:first-child,
        .table-display>.tfoot>.tr>.th:first-child,
        .table-display>.thead>.tr>.td:first-child,
        .table-display>.thead>.tr>.th:first-child {
            padding-right: 15px;
        }
    }
</style>
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
                                            <a href="#" onclick="abrir_filtrar();" class="dropdown-item">Filtrar por Fecha</a>
                                         
                                        <?php endif ?>  
                                        <?php if ($permiso_imprimir) : ?>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" onclick="imprimir_filtrado();" class="dropdown-item" ><span class="glyphicon glyphicon-print"></span> Imprimir Filtrado</a>
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
            <td>

                                         
            <input type="hidden" name="id_persona" id="id_persona" value="<?= $id_persona ?>">
<div class="list-group" id="result">
    <img src="<?= ($empleado['foto'] == '') ? 'files/'.$nombre_dominio.'/profiles/personal/avatar.jpg' : 'files/'.$nombre_dominio.'/profiles/personal/' . $empleado['foto'] . '.jpg'; ?>" id="avatar" name="avatar" class="" style="width:80px; height:80px;">
</div>
     

                                    </td>
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
                                
                                <td class="text-nowrap">


<?php 
$currentDate = date('Y-m-d');
$currentDate = date('Y-m-d', strtotime($currentDate));
   
$startDate = date('Y-m-d', strtotime($empleado["fecha_inicio"]));
$endDate = date('Y-m-d', strtotime($empleado["fecha_final"]));
$year = date('Y', strtotime($empleado["fecha_inicio"]));

if( $year > $_gestion['gestion']){ ?>               

<span style="color:skyblue;">
                EN ESPERA
            </span>                             
        
    <?php }else{ ?>                                            
        <?php 

   
if (($currentDate >= $startDate) && ($currentDate <= $endDate)){ ?>                                            
            <span style="color:#009975">
                VIGENTE
            </span>
        <?php }else{ $empleado["fecha_inicio"]?>                                            
            <span style="color: #C00;">
                FINALIZADO
            </span>
        <?php } ?>
    <?php } ?>



    </td>

                                <td><?= $cargo; ?></td>
                                <td><?= $sueldo_total; ?></td>
                                
                                <td><?= $empleado["fecha_inicio"]; ?></td>
                                <td><?= $empleado["fecha_final"]; ?></td>
                                
                                <td>
                                    <!--a href="?/rrhh-personal/ver/<?= $id_asignacion; ?>" data-toggle="tooltip" data-title="Ver horario" class="btn btn-info btn-xs"><span class="fa fa-eye" ></span></a-->
                    
                                    <a href="#" class="btn btn-warning btn-xs" data-toggle="tooltip" data-title="Editar" onclick="editar_contrato('<?= $id_asignacion; ?>');"><span class="fa fa-list"></span></a>
                    
                                    <a href="#" class="btn btn-success btn-xs" data-toggle="tooltip" data-title="Horario" onclick="abrir_horario('<?php echo $datos ?>');"><span class="fa fa-clock" ></span></a>
                    
                                    <a href="?/rrhh-personal/listar-contrato/<?= $id_asignacion; ?>" data-toggle="tooltip" data-title="Editar Contrato" class="btn btn-info btn-xs"><span class="fa fa-file"></span></a>
                    
                                    <a href="#" data-toggle="tooltip" data-title="Eliminar horario" data-eliminar="true" role="button" class="btn btn-danger btn-xs" onclick="abrir_eliminar('<?= $id_asignacion; ?>');"><span class="fa fa-trash-alt"></span></a>

                                    <label  class=""> <a href="?/rrhh-personal/modalimagen/<?= $empleado['id_persona']; ?>"   class="btn btn-dark btn-xs" data-toggle="tooltip" data-title="Subir Foto" ><span style="color: white;" class="fa fa-camera">
                                            
                                            <input type="file" class="sr-only" id="input" name="image" accept="image/*">
                                            </span></a>
                                        </label>




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



        <!-- ============================================================== -->
<?php
//if ($permiso_editar) {
	require_once("modalcrear.php");//modal
    require_once("modalcontrato.php");//modal 
    //require_once("modalvercontrato.php");//modal 

    require_once ("modal_fecha.php");
//} 
?>

<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
        <script src="<?= js; ?>/jquery.form-validator.es.js"></script>
        <script src="<?= js; ?>/selectize.min.js"></script>

        <script src="<?= themes; ?>/concept/assets/vendor/cropper-mazter/js/cropper.js"></script>
        <script src="<?= themes; ?>/concept/assets/vendor/cropper-mazter/js/imagenes.js"></script>
        <!--script src="<?= themes; ?>/concept/assets/vendor/cropper-mazter/js/main.js"></script-->
        <script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
        <script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.js"></script>
        <script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.es.js"></script>
        <script src="<?= themes; ?>/concept/assets/vendor/bootstrap-fileinput-master/js/fileinput.js"></script>
        <script src="<?= themes; ?>/concept/assets/vendor/bootstrap-fileinput-master/js/es.js"></script>
        <script src="<?= js; ?>/jquery.validate.js"></script>
        <script src="<?= js; ?>/educheck.js"></script>
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
       

function imprimir_filtrado() {

    
        //alert("imprimiendo... ?/sitio/imprimir/");
        var fecha_inicio_filtro = "<?php echo $fecha_inicio_filtro; ?>";
        var fecha_final_filtro = "<?php echo $fecha_final_filtro; ?>";
        console.log(fecha_inicio_filtro);
        if ( fecha_inicio_filtro =='') {
         var fecha_inicio_filtro="0";
         var fecha_final_filtro="0";
        }else
        {
        }

        console.log(fecha_final_filtro);
        //var_dump($contratos);die;
        window.open('?/rrhh-personal/imprimir/'+fecha_inicio_filtro+'/'+ fecha_final_filtro, "_blank");
    }

    
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

function abrir_filtrar(contenido){
    $("#fecha_inicio_filtro").val("");
    $("#fecha_final_filtro").val("");
    $("#modal_filtro").modal("show");     
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

            window.location = '?/rrhh-personal/listar';
        }
    });
}
<?php endif ?>
    





</script> 
<style>
    .ajs-message.ajs-custom { color: #31708f;  background-color: #d9edf7;  border-color: #31708f; }
</style>