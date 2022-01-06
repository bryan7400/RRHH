<?php

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los gondolas
$gondolas = $db->select('z.*, c.*')
                ->from('per_postulacion z')
                ->join('per_cargos c', 'c.id_cargo=z.cargo_id', 'left')
                ->where('z.estado', 'A')
                ->order_by('z.id_postulacion', 'asc')
                ->fetch();
                
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
                <h2 class="pageheader-title">Postulantes</h2>
                <p class="pageheader-text"></p>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Postulantes</a></li>
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
                                                <!--div class="dropdown-divider"></div>
                                                <a href="#" onclick="abrir_crear();" class="dropdown-item">Registrar gondola</a-->
                                            <?php endif ?>
                                            <?php if ($permiso_imprimir) : ?>
                                                <div class="dropdown-divider"></div>
                                                <a href="?/rrhh-postulantes/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
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
                    <?php if ($gondolas) : ?>
                        <table id="table" class="table table-bordered table-condensed table-striped table-hover">
                            <thead>
                            <tr class="active">
                                <th class="text-nowrap">#</th>
                                <th class="text-nowrap">Nombre</th>
                                <th class="text-nowrap">Fecha de nacimiento</th>
                                <th class="text-nowrap">Fecha de postulacion</th>
                                <th class="text-nowrap">Cargo</th>
                                <th class="text-nowrap">Estado</th>
                                <?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
                                    <th class="text-nowrap">Opciones</th>
                                <?php endif ?>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr class="active">
                                <th class="text-nowrap text-middle">#</th>
                                <th class="text-nowrap text-middle">Nombre</th>
                                <th class="text-nowrap text-middle">Fecha de nacimiento</th>
                                <th class="text-nowrap text-middle">Fecha de postulacion</th>
                                <th class="text-nowrap text-middle">Cargo</th>
                                <th class="text-nowrap text-middle">Estado</th>
                                <?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
                                    <th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
                                <?php endif ?>
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php foreach ($gondolas as $nro => $gondola) : ?>
                                <tr>
                                    <th class="text-nowrap"><?= $nro + 1; ?></th>
                                    <td class="text-nowrap"><?= escape($gondola['paterno'])." ".escape($gondola['materno'])." ".escape($gondola['nombre']); ?></td>
                                    <td class="text-nowrap"><?= escape($gondola['fecha_nacimiento']); ?></td>
                                    <td class="text-nowrap"><?= escape($gondola['fecha_registro']); ?></td>
                                    <td class="text-nowrap"><?= escape($gondola['cargo']); ?></td>
                                    <td class="text-nowrap"></td>
                                    <?php 
                                        if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : 
                                            $contenido=$gondola['id_postulacion']."*".$gondola['nombre']."*".$gondola['paterno']."*".$gondola['materno']."*".$gondola['localidad']."*".$gondola['provincia']."*".$gondola['departamento']."*".$gondola['fecha_nacimiento']."*".$gondola['estado_civil']."*".$gondola['ci']."*".$gondola['expirado']."*".$gondola['direccion']."*".$gondola['nro_direccion']."*".$gondola['zona']."*".$gondola['ciudad']."*".$gondola['telefono']."*".$gondola['celular']."*".$gondola['email']."*".$gondola['afp']."*".$gondola['nua']."*".$gondola['conyuge']."*".$gondola['fecha_nacimiento_c']."*".$gondola['fecha_bautismo']."*".$gondola['pastor']."*".$gondola['iglesia']."*".$gondola['distrito']."*".$gondola['escalafon']."*".$gondola['fecha_escalafon']."*".$gondola['unidad']."*".$gondola['turno']."*".$gondola['asignatura']."*".$gondola['periodos']."*".$gondola['fecha_registro']."*";                                            
                                    ?>
                                        <td class="text-nowrap">
                                            <?php if ($permiso_ver) : ?>
                                                <a href="?/rrhh-postulantes/ver/<?= $gondola['id_postulacion']; ?>" data-toggle="tooltip" data-title="Ver" class="btn btn-info"><span class='icon-eye'></span></a>
                                            <?php endif ?>
                                            
                                            <?php if ($permiso_modificar) : 
                                                ?>
                                                <!--a href="#" onclick="abrir_editar('<?php // $contenido; ?>');" data-toggle="tooltip" data-title="Modificar" class="btn btn-warning"><span class='icon-note'></span></a-->
                                            <?php endif ?>
                                            
                                            <?php if ($permiso_modificar) : 
                                                ?>
                                                <a href="#" onclick="abrir_contrato('<?= $gondola['id_postulacion']; ?>','<?= $gondola['id_cargo']; ?>','<?= $gondola['paterno']." ".$gondola['materno']." ".$gondola['nombre']; ?>','<?= $gondola['cargo']; ?>');" data-toggle="tooltip" data-title="Contratar" class="btn btn-success"><span class='icon-plus'></span></a>
                                            <?php endif ?>
                                            
                                            <?php if ($permiso_eliminar) : ?>
                                                <a onclick="eliminar('<?= $gondola['id_postulacion']; ?>');" data-toggle="tooltip" data-title="Eliminar" class="btn btn-danger" style="color:#fff;"><span class='icon-trash'></span></a>
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
                                <li>No existen postulaciones registradas en la base de datos.</li>
                                <li>Para crear nuevas postulaciones debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
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
    require_once ("contrato.php");
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

<?php if ($permiso_crear) : ?>
    function abrir_crear(){
        $("#modal_gestion").modal("show");
        $("#form_gestion")[0].reset();
        //$("#form_gestion").reset();
        //$("#titulo_gestion").text("Crear ");
        $("#btn_editar").hide();
        $("#btn_nuevo").show();
    }
<?php endif ?>

<?php if ($permiso_modificar){ ?>
function abrir_editar(contenido){
    $("#form_gestion")[0].reset();
    //validator.resetForm();
    $("#modal_gestion").modal("show");
    $("#titulo_gestion").text("Editar ");
    //$('#table tbody').off();
    var d = contenido.split("*");
    $("#id_gondola").val(d[0]);
    $("#ruta").val(d[1]);
    $("#descripcion").val(d[2]);
    $("#capacidad").val(d[3]);
    $("#placa").val(d[4]);
    $("#tipo_gondola").val(d[5]);
    $("#btn_nuevo").hide();
    $("#btn_editar").show();
}
<?php } ?>

<?php if ($permiso_modificar){ ?>
function abrir_contrato(id, id_cargo, nombre, cargo){
    $("#modal_contrato").modal("show");
    $("#form_contrato")[0].reset();
    $("#id_postulante").val(id);
    $("#nombre_postulante").text(nombre);
    $("#cargo_postulante").text(cargo);
    $("#btn_nuevo").show();
}
<?php } ?>

<?php if ($permiso_eliminar) : ?>
function eliminar(id){
    bootbox.confirm('¿Está seguro de eliminar la postulacion?', function (result) {
        if(result){
            url="?/rrhh-postulantes/eliminar/"+id;
            window.location = url;
        }
    });
}
<?php endif ?>

</script>
<?php require_once show_template('footer-design'); ?>


