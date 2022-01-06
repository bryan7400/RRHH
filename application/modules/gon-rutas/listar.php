<?php

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los rutas
/*$rutas = $db->select('z.*, pe.nombres, pe.primer_apellido, pe.segundo_apellido, g.nombre as nombre_gondola, g.placa, id_gondola, id_conductor, capacidad')
            ->from('gon_rutas z')
            ->join('gon_conductor_gondola cg','z.conductor_gondola_id = cg.id_conductor_gondola')
            
            ->join('gon_gondolas g','g.id_gondola = cg.gondola_id', 'LEFT')
            
            ->join('gon_conductor ch','ch.id_conductor = cg.conductor_id', 'LEFT')
            ->join('sys_persona pe','ch.persona_id = pe.id_persona','left')                 
            ->order_by('z.id_ruta', 'asc')->where('z.estado','1')->fetch();*/
            
            
   $rutas = $db->query("SELECT   pe.nombres, pe.primer_apellido, pe.segundo_apellido, g.nombre as nombre_gondola, g.placa, id_gondola, id_conductor, capacidad, z.*

    FROM gon_rutas z left JOIN gon_conductor_gondola cg on z.conductor_gondola_id = cg.id_conductor_gondola
    left JOIN  gon_gondolas g on g.id_gondola = cg.gondola_id
    left JOIN gon_conductor ch on ch.id_conductor = cg.conductor_id
    left JOIN per_asignaciones asi on asi.id_asignacion = ch.asignacion_id
    left JOIN sys_persona pe on asi.persona_id = pe.id_persona 
    where z.estado='1'        
    order by z.id_ruta asc")->fetch();     
            
// var_dump($rutas);
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
            <h2 class="pageheader-title">rutas</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">rutas</a></li>
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
                                            <!--<a href="#" onclick="abrir_crear();" class="dropdown-item">Registrar ruta</a>-->
										<a href="?/gon-rutas/crear" class="dropdown-item">Registrar ruta</a>
                                        <?php endif ?>
                                        <?php if ($permiso_imprimir) : ?>
                                            <div class="dropdown-divider"></div>
                                            <a href="?/s-nivel-academico/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- ============================================================== -->
                <!-- datos -->
                <!-- ============================================================== -->
                <?php if ($rutas) : ?>
                    <table id="table" class="table table-bordered table-condensed table-striped table-hover">
                        <thead>
                        <tr class="active">
                            <th class="text-nowrap">#</th>
                            <th class="text-nowrap">Ruta</th>
                            <th class="text-nowrap">Descripción</th>
                            <th class="text-nowrap">Conductor</th>
                            <th class="text-nowrap">Gondola</th>
                            <th class="text-nowrap">Capacidad del movilidad</th>
                            <th class="text-nowrap"> Estudiantes asignados</th>
                            <th class="text-nowrap">Detalle</th>
                            <?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
                                <th class="text-nowrap">Opciones</th>
                            <?php endif ?>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr class="active">
                            <th class="text-nowrap text-middle">#</th>
                            <th class="text-nowrap text-middle">ruta</th>
                            <th class="text-nowrap text-middle">Descripción</th>
                            <th class="text-nowrap text-middle">Conductor</th>
                            <th class="text-nowrap text-middle">Gondola</th>
                            <th class="text-nowrap text-middle">Capacidad</th>
                            <th class="text-nowrap text-middle">Asignados</th>
                            <th class="text-nowrap text-middle">Detalle</th>
                            <?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
                                <th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
                            <?php endif ?>
                        </tr>
                        </tfoot>
                        <tbody>
                        <?php foreach ($rutas as $nro => $ruta) : 
                                $contenido=escape($ruta['id_ruta'])."*"; 
                                $contenido.=escape($ruta['nombre'])."*"; 
                                $contenido.=escape($ruta['descripcion'])."*"; 
                                
                                $contenido2=escape($ruta['id_ruta'])."*"; 
                                $contenido2.=escape($ruta['id_conductor'])."*"; 
                                $contenido2.=escape($ruta['id_gondola'])."*"; 
                                ?>
                            <tr>
                                <th class="text-nowrap"><?= $nro + 1; ?></th>
                                <td class="text-nowrap"><?= escape($ruta['nombre']); ?></td>
                                <td class="text-nowrap"><?= escape($ruta['descripcion']); ?></td>
                                
                                <td class="text-nowrap"><?php 
									if($ruta['nombres']!=''){
									echo $ruta['nombres']." ".$ruta['primer_apellido']." ".$ruta['segundo_apellido']; 
										
									}else{
									echo '<span style="color:red">Sin asignacion de conductor</span>'; 
									}
										
									?>
                               </td>
                                <td class="text-nowrap"><?php
									echo $ruta['nombre_gondola']." - ".$ruta['placa']; 
									?></td>
                                
                                <td class="text-nowrap">
                                    <?php
                                        echo $ruta['capacidad'];
                                    ?>
                                </td>
                                <td class="text-nowrap">
                                    <?php
                                        $estudiantes = $db->query('SELECT COUNT(id_estudiante) as nro
                                            FROM ins_inscripcion a
                                            LEFT JOIN ins_estudiante b ON a.estudiante_id = b.id_estudiante
                                            LEFT JOIN sys_persona c ON b.persona_id = c.id_persona
                                            LEFT JOIN ins_aula_paralelo d ON a.aula_paralelo_id = d.id_aula_paralelo
                                            LEFT JOIN ins_paralelo e ON d.paralelo_id = e.id_paralelo
                                            LEFT JOIN ins_aula f ON d.aula_id = f.id_aula
                                            LEFT JOIN ins_turno g ON d.turno_id = g.id_turno

                                            LEFT JOIN gon_puntos GP ON GP.id_punto = a.punto_id
                                            LEFT JOIN gon_rutas r ON r.id_ruta = GP.ruta_id

                                            WHERE r.id_ruta = "'.$ruta['id_ruta'].'"
                                        ')->fetch_first();

                                        echo '<span class="badge badge-info">'.$estudiantes['nro'].'</span>';
                                    ?>
                                </td>

                                <td class="text-nowrap">
                                    <?php
                                        if($estudiantes['nro']>$ruta['capacidad']){
                                            echo '<span style="color:#f00;">'.($estudiantes['nro']-$ruta['capacidad']).' excedidos</span>';
                                        }else{
                                            echo '<span style="color:#2ec551;">'.($ruta['capacidad']-$estudiantes['nro']).' cupos</span>';
                                        }                                        
                                    ?>
                                </td>

                                <?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
                                    <td class="text-nowrap">
                                        <?php if ($permiso_eliminar) : ?>
                                            <a href="#" onclick="abrir_asignacion('<?= $contenido2; ?>');" data-toggle="tooltip" data-title="Asignar conductor" class="btn btn-info"><span class='fas fa-bus'></span></a>
                                        <?php endif ?>
                                        <?php if ($permiso_modificar) : ?>
                                            <a href="?/gon-rutas/crear-punto/<?= $ruta['id_ruta']; ?>" data-toggle="tooltip" data-title="Añadir paradas" class="btn btn-success"><span class='fas fa-map-marker-alt'></span></a>
                                        <?php endif ?>
                                        <?php if ($permiso_ver) : ?>
                                            <a href="?/gon-rutas/ver/<?= $ruta['id_ruta']; ?>" data-toggle="tooltip" data-title="Añadir estudiantes" class="btn btn-primary"><span class='fas fa-users'></span></a>
                                        <?php endif ?>
                                        <?php if ($permiso_modificar) : ?>
                                            <a href="?/gon-rutas/editar-ruta/<?= $ruta['id_ruta']; ?>" data-toggle="tooltip" data-title="Modificar rutas" class="btn btn-warning"><span class='icon-note'></span></a>  <!-- onclick="abrir_editar('<?//= $contenido; ?>');"-->
                                        <?php endif ?>
                                        <?php if ($permiso_eliminar) : ?>
                                            <a href="?/gon-rutas/eliminar/<?= $ruta['id_ruta']; ?>" data-toggle="tooltip" data-title="Eliminar rutas" data-eliminar="true" class="btn btn-danger"><span class='icon-trash'></span></a>
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
                            <li>No existen rutas registrados en la base de datos.</li>
                            <li>Para crear nuevos rutas debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
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




<?php
//require_once ("crear.php");
require_once ("asignacion.php");
?>




<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<!-- <script src="<?= js; ?>/jquery.dataFilters.min.js"></script> -->
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/bootbox.min.js"></script>

<script src="<?= js; ?>/educheck.js"></script>
<!--script src="<?= $ruta?>/s-gestion-escolar.js"></script-->
<script>
$(function () {
    <?php if ($permiso_crear) : ?>
    /* $(window).bind('keydown', function (e) {
        if (e.altKey || e.metaKey) {
            switch (String.fromCharCode(e.which).toLowerCase()) {
                case 'n':
                    e.preventDefault();
                    window.location = '?/rutas/crear';
                    break;
            }
        }
    });
    */
    <?php endif ?>


    
    <?php if ($permiso_eliminar) : ?>
    $('[data-eliminar]').on('click', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
		debugger;
        bootbox.confirm('¿Está seguro de eliminar la ruta?', function (result) {
            if(result){
                window.location = url;
            }
        });
    });
    <?php endif ?>

    <?php if ($rutas) : ?>
    var dataTable = $('#table').DataTable({
        language: dataTableTraduccion,
        searching: true,
        paging:true,
        "lengthChange": true,
        "responsive": true
    });
    <?php endif ?>
});


<?php if ($permiso_crear){ ?>
    function abrir_crear(){
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
    $("#form_gestion")[0].reset();
    //validator.resetForm();
    $("#modal_gestion").modal("show");
    $("#titulo_gestion").text("Editar ");
        
    var d = contenido.split("*");
    $("#id_gestion2").val(d[0]);
    $("#nombre2").val(d[1]);
    $("#descripcion2").val(d[2]);
    
    $("#btn_nuevo").hide();
    $("#btn_editar").show();
}
<?php } ?>

<?php if ($permiso_modificar){ ?>
function abrir_asignacion(contenido){
    
    $("#form_asignacion")[0].reset();
    //validator.resetForm();
    $("#modal_asignacion").modal("show");
    //$("#titulo_gestion").text("Editar ");
        
    //alert(contenido);

    var d = contenido.split("*");
        
    $("#id_asignacion2").val(d[0]);    
    $("#id_conductor2 option[value='"+d[1]+"']").attr("selected",true);      
    $("#id_gondola2 option[value='"+d[2]+"']").attr("selected",true);      

    $("#btn_nuevo").hide();
    $("#btn_editar").show();
}
<?php } ?>

</script>
<?php require_once show_template('footer-design'); ?>


