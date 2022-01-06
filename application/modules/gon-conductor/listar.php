<?php

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los gondolas
$conductors = $db->query("SELECT z.*, a.nombres, a.primer_apellido, a.segundo_apellido  
FROM gon_conductor z 
INNER JOIN per_asignaciones asi on asi.id_asignacion = z.asignacion_id
INNER JOIN sys_persona a on asi.persona_id = a.id_persona WHERE z.estado='A'")->fetch();

//var_dump($gondolas);
// Obtiene los permisos
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);

?>
<?php require_once show_template('header-design'); ?>
    <!-- ============================================================== -->
    <!-- pageheader -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Conductores</h2>
                <p class="pageheader-text"></p>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Conductores</a></li>
                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Creación</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Listar</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end pageheader -->
    <!-- ============================================================== -->
    <div class="row">
        <!-- ============================================================== -->
        <!-- row -->
        <!-- ============================================================== -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <!-- <h5 class="card-header">Generador de menús</h5> -->
                <div class="card-header">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                            <div class="text-label hidden-xs">Seleccione:</div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
                            <div class="btn-group">
                                <div class="input-group">
                                    <div class="input-group-append be-addon">
                                        <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item">Seleccionar acción</a>
                                            <?php if ($permiso_crear) : ?>
                                                <div class="dropdown-divider"></div>
                                                <a href="#" onclick="abrir_crear();" class="dropdown-item">Registrar conductor</a>
                                            <?php endif ?>
                                            <?php if ($permiso_imprimir) : ?>
                                                <div class="dropdown-divider"></div>
                                                <a href="?/gon-conductor/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body" style="    overflow: auto;">
                    <!-- ============================================================== -->
                    <!-- datos -->
                    <!-- ============================================================== -->
                    <?php if ($conductors) : ?>
                        <table id="table" class="table table-bordered table-condensed table-striped table-hover">
                            <thead>
                            <tr class="active">
                                <th class="text-nowrap">#</th>
                                <th class="text-nowrap">Nombre</th>
                                <th class="text-nowrap">Categoria</th>
                                <th class="text-nowrap">Lentes</th>
                                <th class="text-nowrap">Audifonos</th>
                                <th class="text-nowrap">Grupo sanguineo</th>
                                <th class="text-nowrap">Fecha emisión</th>
                                <th class="text-nowrap">Fecha vencimiento</th>
                                <?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
                                    <th class="text-nowrap">Opciones</th>
                                <?php endif ?>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr class="active">
                                <th class="text-nowrap text-middle">#</th>
                                <th class="text-nowrap text-middle">Nombre</th>
                                <th class="text-nowrap text-middle">Categoria</th>
                                <th class="text-nowrap text-middle">Lentes</th>
                                <th class="text-nowrap text-middle">Audifonos</th>
                                <th class="text-nowrap text-middle">Grupo sanguineo</th>
                                <th class="text-nowrap text-middle">Fecha emisión</th>
                                <th class="text-nowrap text-middle">Fecha vencimiento</th>
                                <?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
                                    <th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
                                <?php endif ?>
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php foreach ($conductors as $nro => $conductor) :                                 
                                $contenido=escape($conductor['id_conductor'])."*"; 
                                $contenido.=escape($conductor['nombres'].'*'.$conductor['primer_apellido'].'*'.$conductor['segundo_apellido'])."*"; 
                                $contenido.=escape($conductor['categoria'])."*"; 
                                $contenido.=escape($conductor['lentes'])."*"; 
                                $contenido.=escape($conductor['audifonos'])."*"; 
                                $contenido.=escape($conductor['grupo_sanguineo'])."*"; 
                                $contenido.=escape($conductor['fecha_emision'])."*"; 
                                $contenido.=escape($conductor['fecha_vencimiento'])."*"; 
                                $contenido.=escape($conductor['asignacion_id'])."*"; 
                                ?>
                                <tr>
                                    <th class="text-nowrap"><?= $nro + 1; ?></th>
                                    <td class="text-nowrap"><?= escape($conductor['nombres'].' '.$conductor['primer_apellido'].' '.$conductor['segundo_apellido']); ?></td>
                                    <td class="text-nowrap"><?= escape($conductor['categoria']); ?></td>
                                    <td class="text-nowrap"><?= escape($conductor['lentes']); ?></td>
                                    <td class="text-nowrap"><?= escape($conductor['audifonos']); ?></td>
                                    <td class="text-nowrap"><?= escape($conductor['grupo_sanguineo']); ?></td>
                                    <td class="text-nowrap"><?= escape($conductor['fecha_emision']); ?></td>
                                    <td class="text-nowrap"><?= escape($conductor['fecha_vencimiento']); ?></td>
                                    <?php 
                                        if ($permiso_ver || $permiso_modificar || $permiso_eliminar) :                                             
                                    ?>
                                        <td class="text-nowrap">
                                            <?php if ($permiso_ver) : ?>
                                                <a href="#" onclick="ver('<?= $contenido; ?>')" data-toggle="tooltip" data-title="Ver gondolas" class="btn btn-info"><span class='icon-eye'></span></a>
                                            <?php endif ?>
                                            <?php if ($permiso_modificar) : 
                                                ?>
                                                <a href="#" onclick="abrir_editar('<?= $contenido; ?>');" data-toggle="tooltip" data-title="Modificar gondolas" class="btn btn-warning"><span class='icon-note'></span></a>
                                            <?php endif ?>
                                            <?php if ($permiso_eliminar) : ?>
                                                <a onclick="eliminar('<?= $conductor['id_conductor']; ?>');" data-toggle="tooltip" data-title="Eliminar gondolas" class="btn btn-danger" style="color:#fff;"><span class='icon-trash'></span></a>
                                            <?php endif ?>
                                        </td>
                                    <?php endif ?>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <div class="alert alert-info">
                            <strong>Atención!</strong>
                            <ul>
                                <li>No existen gondolas registrados en la base de datos.</li>
                                <li>Para crear nuevos gondolas debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
                            </ul>
                        </div>
                    <?php endif ?>
                    <!-- ============================================================== -->
                    <!-- end datos -->
                    <!-- ============================================================== -->
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end row -->
    <!-- ============================================================== -->
    <!--modal para eliminar-->
    <div class="modal fade" tabindex="-1" role="dialog" id="modal_eliminar">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <input type="hidden" id="id_estudiante">
                    <p>¿Esta seguro de eliminar estudiante <span id="texto_estudiante"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btn_eliminar">Eliminar</button>
                </div>
            </div>
        </div>
    </div>


<?PHP
        require_once ("crear.php");

    //if($permiso_ver){
        require_once ("ver.php");
    //} 
?>


<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>

<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/bootbox.min.js"></script>

<script src="<?= js; ?>/educheck.js"></script>
    <!--script src="<?= $ruta?>/s-gestion-escolar.js"></script-->
<script>
	cargarPersonal();
	function cargarPersonal(){
		//var datos = $("#form_gestion").serialize();
		   $.ajax({
            type: 'POST',
            url: "?/gon-conductor/procesos",
            data: {boton:'listar_personal'},
			dataType: 'JSON',   
            success: function (resp) {
            $('#sel_personal_crear').html('<option value="">Seleccione...</option>');
			for(var i=0;i<resp.length;i++){
             $('#sel_personal_crear').append('<option value="'+resp[i]['id_asignacion']+'">'+resp[i]['nombres']+' '+resp[i]['primer_apellido']+' '+resp[i]['segundo_apellido']+'</option>');
			}
		 
              /*switch(resp){

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
              }*/
       
            }          
        });
	}
var columns=[
    
    {data: 'f_emision2'},
    {data: 'f_vencimiento2'}
    
];

<?php if ($permiso_ver){ ?>
function ver(contenido){
    var d = contenido.split("*");
    $("#gestion_ver").modal("show");
    $("#nombre").text(d[1]+" "+d[2]+" "+d[3]);
    $("#categoria").text(d[4]);
    $("#lentes").text(d[5]);
    $("#audifonos").text(d[6]);
    $("#grupo_sanguineo").text(d[7]);
    $("#f_emision").text(d[8]);
    $("#f_vencimiento").text(d[9]);
}
<?php } ?>

<?php if ($permiso_crear){ ?>
    function abrir_crear(){
		$('#sel_personal_crear').removeAttr('disabled');
        $("#modal_gestion").modal("show");
        $("#form_gestion")[0].reset();
        //$("#form_gestion").reset();
        //$("#titulo_gestion").text("Crear ");
        $("#btn_editar").hide();
        $("#btn_nuevo").show();
    }
<?php } ?>

<?php if ($permiso_modificar){ ?>
function abrir_editar(contenido){
	$('#sel_personal_crear').attr('disabled','true');
    $("#form_gestion")[0].reset();
    //validator.resetForm();
    $("#modal_gestion").modal("show");
    $("#titulo_gestion").text("Editar ");
    
    //$('#table tbody').off();
    var d = contenido.split("*");
    $("[name=id_conductor]").val(d[0]);//9 asignacion
    $("#paterno2").val(d[2]);
    $("#materno2").val(d[3]);
    $("#nombres2").val(d[1]);
    $("#categoria2").val(d[4]);
    
    $("#lentes2 option[value='"+d[5]+"']").attr("selected",true);      
    $("#audifonos2 option[value='"+d[6]+"']").attr("selected",true);      
    
    $("#grupo_sanguineo2").val(d[7]);    
    $("#f_emision2").val(moment(d[8]).format('YYYY-MM-DD'));
    $("#f_vencimiento2").val(moment(d[9]).format('YYYY-MM-DD'));
    $("#sel_personal_crear").val(d[10]);

    $("#id_persona2").val(d[10]);
    
    $("#btn_nuevo").hide();
    $("#btn_editar").show();
	
}
<?php } ?>

<?php if ($permiso_eliminar){ ?>
function eliminar(id){
    bootbox.confirm('¿Está seguro de eliminar al conductor?', function (result) {
        if(result){
            url="?/gon-conductor/eliminar/"+id;
            window.location = url;
        }
    });
}
<?php } ?>

</script>
<?php require_once show_template('footer-design'); ?>


