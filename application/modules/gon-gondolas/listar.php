<?php

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los gondolas
$gondolas = $db->select('z.*')
                ->from('gon_gondolas z')
                ->order_by('z.id_gondola', 'asc')
                ->where('z.estado', '1')
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
                <h2 class="pageheader-title">Gondolas</h2>
                <p class="pageheader-text"></p>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gondolas</a></li>
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
                                                <a href="#" onclick="abrir_crear();" class="dropdown-item">Registrar gondola</a>
                                            <?php endif ?>
                                            <?php if ($permiso_imprimir) : ?>
                                                <div class="dropdown-divider"></div>
                                                <a href="?/gon-gondolas/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body"  style="    overflow: auto;">
                    <!-- ============================================================== -->
                    <!-- datos -->
                    <!-- ============================================================== -->
                    <?php if ($gondolas) : ?>
                        <table id="table" class="table table-bordered table-condensed table-striped table-hover">
                            <thead>
                            <tr class="active">
                                <th class="text-nowrap">#</th>
                                <th class="text-nowrap">Nombre</th>
                                <th class="text-nowrap">Descripción</th>
                                <th class="text-nowrap">Placa</th>
                                <th class="text-nowrap">Capacidad</th>
                               <!--
                                <th class="text-nowrap">Tipo gondola</th>
                                <th class="text-nowrap">Conductor</th>-->
                           <!--     <th class="text-nowrap">Estado</th>
                                <th class="text-nowrap">Usuario registro</th>
                                <th class="text-nowrap">Fecha registro</th>
                                <th class="text-nowrap">Usuario modificacion</th>
                                <th class="text-nowrap">Fecha modificacion</th>-->
                                <?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
                                    <th class="text-nowrap">Opciones</th>
                                <?php endif ?>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr class="active">
                                <th class="text-nowrap text-middle">#</th>
                                <th class="text-nowrap text-middle">Nombre</th>
                                <th class="text-nowrap text-middle">Descripción</th>
                                <th class="text-nowrap text-middle">Placa</th>
                                <th class="text-nowrap text-middle">Capacidad</th>
                                <!--
                                <th class="text-nowrap text-middle">Tipo gondola</th>
                                <th class="text-nowrap text-middle">Conductor</th>-->
                                <!--<th class="text-nowrap text-middle">Estado</th>
                                <th class="text-nowrap text-middle">Usuario registro</th>
                                <th class="text-nowrap text-middle">Fecha registro</th>
                                <th class="text-nowrap text-middle">Usuario modificacion</th>
                                <th class="text-nowrap text-middle">Fecha modificacion</th>-->
                                <?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
                                    <th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
                                <?php endif ?>
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php foreach ($gondolas as $nro => $gondola) : ?>
                                <tr>
                                    <th class="text-nowrap"><?= $nro + 1; ?></th>
                                    <td class="text-nowrap"><?= escape($gondola['nombre']); ?></td>
                                    <td class="text-nowrap"><?= escape($gondola['descripcion']); ?></td>
                                    <td class="text-nowrap"><?= escape($gondola['placa']); ?></td>
                                    <td class="text-nowrap"><?= escape($gondola['capacidad']); ?></td>
                                  <!--  
                                    <td class="text-nowrap"><?//= escape($gondola['tipo_gondola']); ?></td>
                                    <td class="text-nowrap"><?//= escape($gondola['ruta_id']); ?></td>
                                   <td class="text-nowrap"><?//= escape($gondola['estado']); ?></td>
                                    <td class="text-nowrap"><?//= escape($gondola['usuario_registro']); ?></td>
                                    <td class="text-nowrap"><?//= escape($gondola['fecha_registro']); ?></td>
                                    <td class="text-nowrap"><?//= escape($gondola['usuario_modificacion']); ?></td>
                                    <td class="text-nowrap"><?//= escape($gondola['fecha_modificacion']); ?></td>-->
                                    <?php 
                                        if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : 
                                            $contenido=$gondola['id_gondola']."*".$gondola['nombre']."*".$gondola['descripcion']."*".$gondola['capacidad']."*".$gondola['placa']."*".$gondola['tipo_gondola'];                                            
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
                                                <a onclick="eliminar('<?= $gondola['id_gondola']; ?>');" data-toggle="tooltip" data-title="Eliminar gondolas" class="btn btn-danger" style="color:#fff;"><span class='icon-trash'></span></a>
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
        /*$(window).bind('keydown', function (e) {
            if (e.altKey || e.metaKey) {
                switch (String.fromCharCode(e.which).toLowerCase()) {
                    case 'n':
                        e.preventDefault();
                        //window.location = '?/gestiones/crear';
                        $('#modal_gestion').modal('toggle');
                    break;
                }
            }
        });*/
        <?php endif ?>
        

        <?php if ($gondolas) : ?>
        /*var dataTable = $('#table').DataTable({
            language: dataTableTraduccion,
            searching: true,
            paging:true,
            "lengthChange": true,
            "responsive": true
        });*/
        <?php endif ?>
    });

<?php if ($permiso_ver) : ?>
function ver(contenido){
    var d = contenido.split("*");
    $("#gestion_ver").modal("show");
    $("#nom_gestion").text(d[1]);
    $("#ini_gestion").text(d[2]);
    $("#fi_gestion").text(d[3]);
    $("#ini_vacaciones").text(d[4]);
    $("#fi_vacaciones").text(d[5]);
}
<?php endif ?>

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

<?php if ($permiso_eliminar) : ?>
function eliminar(id){
    bootbox.confirm('¿Está seguro de eliminar la gondola?', function (result) {
        if(result){
            url="?/gon-gondolas/eliminar/"+id;
            window.location = url;
        }
    });
}
<?php endif ?>

</script>
<?php require_once show_template('footer-design'); ?>


