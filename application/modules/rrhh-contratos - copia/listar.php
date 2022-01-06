<?php

// Obtiene la cadena csrf
$csrf = set_csrf();  
$gestion=$_gestion['id_gestion'];
// Obtiene los contratos
$contratos = $db->query("SELECT * FROM rrhh_contrato WHERE estado = 'A'")->fetch();
// Obtiene los permisos 
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_editar = in_array('editar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views); 
$permiso_contrato  = in_array('editar', $_views);


?>

<?php require_once show_template('header-design'); ?>

<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Contratos</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Configuración</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Contrato</a></li>
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
                        <div class="btn-group ">
                             <div class="input-group">
                                <div class="input-group-append be-addon" >
                                    <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item">Seleccionar acción</a>
                                        <?php if ($permiso_crear) : ?>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" onclick="abrir_crear();" class="dropdown-item">Crear Contrato</a>
                                         
                                        <?php endif ?>  
                                        <?php if ($permiso_imprimir) : ?>
                                        <div class="dropdown-divider"></div>
                                        <a href="?/rrhh-contratos/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Contrato</a>
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
                <div class="table-responsive">
                <?php if ($contratos) : ?>
                <table id="table" class="table table-bordered table-condensed table-striped table-hover">
                    <thead>
                        <tr class="active">
                            <th class="text-nowrap">#</th>
                            <th class="text-nowrap">Area</th>
                            <th class="text-nowrap">Tipo Contrato</th>
                            <th class="text-nowrap">Modalidad</th>
                            <th class="text-nowrap">Tipo Documento</th>
                            <th class="text-nowrap">Gestion</th>
                            <th class="text-nowrap">Nivel Academico</th>
                            <th class="text-nowrap">Archivo</th>
                            <th class="text-nowrap">Inicio</th>
                            <th class="text-nowrap">Final</th>

                            <?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
                            <th class="text-nowrap">Opciones</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="active">
                            <th class="text-nowrap stext-middle" data-datafilter-filter="false">#</th>
                            <th class="text-nowrap">Area</th>
                            <th class="text-nowrap">Tipo Contrato</th>
                            <th class="text-nowrap">Modalidad</th>
                            <th class="text-nowrap">Tipo Documento</th>
                            <th class="text-nowrap">Gestion</th>
                            <th class="text-nowrap">Nivel Academico</th>
                            <th class="text-nowrap">Archivo</th>
                            <th class="text-nowrap">Inicio</th>
                            <th class="text-nowrap">Final</th>
                            
                            <?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
                            <th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
                            <?php endif ?>
                        </tr>
                    </tfoot>
                    <tbody>
                        
                    </tbody>
                </table>
                <?php else : ?>
                </div>
                <div class="alert alert-info">
                    <strong>Atención!</strong>
                    <ul>
                        <li>No existen Contratos registrados en la base de datos.</li>
                        <li>Para crear nuevos Contratos debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
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
</div>
<!-- ============================================================== -->
<!-- end row -->


<!-- ============================================================== --> 
<!--modal para eliminar-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_eliminar">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <input type="hidden" id="area_eliminar">
        <p>¿Esta seguro de eliminar el Contrato <span id="texto_contrato"></span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn_eliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<!-- <script src="<?= js; ?>/jquery.dataFilters.min.js"></script> -->
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/educheck.js"></script>

<?php require_once show_template('footer-design'); ?>
<?php 

    if($permiso_editar){
        require_once ("editar.php");
    }   
    if($permiso_ver){
        require_once ("ver.php");
    }
    require_once ("modal-ordenar-areas.php");
?>
<script>
$(function () {
    
    <?php if ($permiso_crear) : ?>
    $(window).bind('keydown', function (e) {
        if (e.altKey || e.metaKey) {
            switch (String.fromCharCode(e.which).toLowerCase()) {
                case 'n':
                    e.preventDefault();
                    //window.location = '?/gestiones/crear';
                    $('#modal_contrato').modal('toggle');

                    $("#modal_contrato").modal("show");

                    
                break;
            }
        }
    });
    <?php endif ?>
    
    <?php if ($permiso_eliminar) : ?>
    $('[data-eliminar]').on('click', function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var csrf = '<?= $csrf; ?>';
        bootbox.confirm('¿Está seguro que desea eliminar al Contrato?', function (result) {
            if (result) {
                $.request(href, csrf);
            }
        });
    });
    <?php endif ?>
     
    <?php if ($contratos) : ?>
    // $('#nivel_academico').DataFilter({
    //  filter: true,
    //  name: 'niveles',
    //  reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
    // });
    <?php endif ?>
    //carga toda la lista de grupo proyecto con DataTable
});




<?php if ($permiso_editar) : ?>
function abrir_editar2(contenido){
    $("#form_contrato")[0].reset();
    $("#btn_nuevo").hide();
    $("#btn_editar").show();
    $("#archivo_documento").attr('required', true);
    $('input[name="genero"]').removeAttr('checked');
    $("#modal_contrato").modal("show");
    var d = contenido.split("*");
    $("#id_contrato").val(d[0]);
    $("#nombres").val(d[1]);
    $("#tipo_documento").val(d[2]);
    $("#numero_documento").val(d[3]);
    $("#expedido").val(d[4]);
    $("input[name=genero][value=" + d[5] + "]").attr('checked', 'checked');
    $("#fecha_nacimiento").val(d[6]);
    $("#direccion").val(d[7]);
    $("#archivo_documento").val(d[8]);
    $("#celular").val(d[9]);
    $("#email").val(d[10]);
    
     
    
}
<?php endif ?>


<?php if ($permiso_editar) : ?>
function abrir_editar(contenido){
    var d = contenido.split("*");
    var nro=(d[0]);
    $.ajax({
        url: '?/rrhh-contratos/procesos',
        type: 'POST',
        data:{
            'accion': 'recuperar_datos',
            'id_contrato':nro 
            },
        dataType: 'JSON',
        success: function(resp){    


    $("#form_contrato")[0].reset();
    $("#btn_nuevo").hide();
    $("#btn_editar").show();
    $("#fotedit").show();
    $("#archivo_documento").attr('required', false);
    $("#modal_contrato").modal("show");
    $("#id_contrato").val(resp["id_contrato"]);
    $("#area_contrato").val(resp["area_contrato"]);
    $("#tipo_contrato").val(resp["tipo_contrato"]);
    $("#modalidad_contrato").val(resp["modalidad_contrato"]);
    $("#tipo_documento").val(resp["tipo_documento"]);
    $("#fecha_inicio").val(resp["fecha_inicio"]);
    $("#fecha_final").val(resp["fecha_final"]);
    $("#archivo_documento_nombre").val(resp["archivo_documento"]);
    $('#archivo_documento').attr('src', "files/demoeducheck/rrhh/" + resp["archivo_documento"]);
    

    var str_array_skills2 = resp["nivel_academico"].split(',');
            var $select2 =   $('#nivel_academico').selectize();
            var selectize2 = $select2[0].selectize;
            selectize2.setValue(str_array_skills2);
            selectize2.refreshOptions();

    setfec();        
    setcargo();

        }
    });
}
    
     
    

<?php endif ?>

function abrir_ordenar_contratos(){
    $("#modal_ordenar_contrato").modal("show");
}

<?php if ($permiso_crear) : ?>
function abrir_crear(){
    $("#modal_contrato").modal("show");
    $("#form_contrato")[0].reset();
    $("#btn_editar").hide();
    $("#fotedit").hide(); 

    $("#btn_nuevo").show();
    setfec();
    setcargo();
}
<?php endif ?>


var columns=[
    {data: 'id_contrato'},
    {data: 'area_contrato'},
    {data: 'tipo_contrato'},
    {data: 'modalidad_contrato'},
    {data: 'tipo_documento'},
    {data: 'gestion_id'},
    {data: 'nivel_academico'},
    {data: 'archivo_documento'},
    {data: 'fecha_inicio'},
    {data: 'fecha_final'}
];
var cont = 0;
//function listarr(){
var dataTable = $('#table').DataTable({
    language: dataTableTraduccion,
    searching: true,
    paging:true,
    "lengthChange": true,
    "responsive": true,
    ajax: {
        url: '?/rrhh-contratos/busqueda',
        dataSrc: '',
        type:'POST',
        dataType: 'json'
    },
    columns: columns,

    "columnDefs": [

{
        "render": function (data, type, row) {
            var result = "";
            var contenido = row['id_contrato'] + "*" + row['area_contrato']+ "*" + row['tipo_contrato'] + "*" + row['modalidad_contrato']+ "*" +  row['tipo_documento']+ "*" +  row['gestion_id']+ "*" +  row['nivel_academico']+ "*" +  row['archivo_documento']+ "*" +  row['fecha_inicio']+ "*" +  row['fecha_final'];
            result+="<?php if (false) : ?><a href='#' class='btn btn-info btn-xs' onclick = 'ver("+'"'+contenido+'"'+")'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
                    "<?php if ($permiso_editar) : ?><a  href='?/rrhh-contratos/listar-documento/"+''+row['id_contrato']+''+"'' class='btn btn-info btn-xs' style='color:black' target='_blank'><span style='color:black' class='fa fa-file'></span></a><?php endif ?> &nbsp" +
                    "<?php if ($permiso_editar) : ?><a href='?/rrhh-contratos/imprimir/"+''+row['id_contrato']+''+"'' class='btn btn-success btn-xs' style='color:black' onclick='abrir_documento()'  target='_blank'><span style='color:black' class='fa fa-print'></span></a><?php endif ?> &nbsp" +
                    "<?php if ($permiso_editar) : ?><a href='#' class='btn btn-warning btn-xs' style='color:white' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note' style='color:black'></span></a><?php endif ?> &nbsp" +
                    "<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' role='button' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash' style='color:black'></span></a><?php endif ?>";
            return result;
        },
        "targets": 10
},
            {
                "render": function(data, type, row) {
                    var imagen = "";
                    if (row['archivo_documento'] == null  || row['archivo_documento'] == "") {
                        archivo_documento = "files/logos/xpicture.png";
                    } else {
                        archivo_documento = "files/demoeducheck/rrhh/" + row['archivo_documento'];
                    }
                    imagen += "<a href='" + archivo_documento + "' class='btn btn-dark btn-xs'  role='button' download><i class='fa fa-download'></i></a>";

                    
                    return imagen;
                },
                "targets": 7

            },
            
            
            {
                    "render": function (data, type, row) {
                        cont = cont +1;
                        return cont;
                    },
                    "targets": 10
            }


    ]
});
//} 
<?php if ($permiso_ver) : ?>
function ver(contenido){
    var d = contenido.split("*");
    $("#area_ver").modal("show");
    $("#descripcion_ver").text(d[1]);
    $("#ponderado_ver").text(d[2]);
    $("#gestion_ver").text(d[3]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
function abrir_eliminar(contenido){
    $("#modal_eliminar").modal("show");
    var d = contenido.split("*");
    $("#area_eliminar").val(d[0]);
    $("#texto_contrato").text(d[1]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
$("#btn_eliminar").on('click', function(){
    id_contrato = $("#area_eliminar").val();
    $.ajax({
        url: '?/rrhh-contratos/eliminar',
        type:'POST',
        data: {'id_contrato':id_contrato},
        success: function(resp){
            //alert(resp)
            switch(resp){
                case '1': $("#modal_eliminar").modal("hide");
                            dataTable.ajax.reload();
                            alertify.success('Se elimino el contrato correctamente');break;
                case '2': $("#modal_eliminar").modal("hide");
                            alertify.error('No se pudo eliminar, esta siendo utilizado por otro registro ');
                            break;
            }
        }
    })
})
<?php endif ?>
</script>