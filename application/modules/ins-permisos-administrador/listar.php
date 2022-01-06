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
            <h2 class="pageheader-title">Permisos</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Configuración</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Permiso</a></li>
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
                                        <a href="#" onclick="abrir_crear();" class="dropdown-item">Crear Permiso</a>
                                         
                                        <?php endif ?>  
                                        
                                        
                                        <?php if ($permiso_imprimir) : ?>
                                        <div class="dropdown-divider"></div>
                                        <a href="?/ins-permisos-administrador/imprimir" target="_blank" class="dropdown-item">Imprimir</a>
                                         
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
                            <th class="text-nowrap">estudiante</th>
                            <th class="text-nowrap">familiar</th>
                            <th class="text-nowrap">Materias</th>
                            <th class="text-nowrap">Horarios</th>
                            <th class="text-nowrap">Motivo</th>
                            <th class="text-nowrap">Comprobante</th>
                            <th class="text-nowrap">Estado</th>
                            <th class="text-nowrap">Inicia</th>
                            <th class="text-nowrap">Finaliza</th>
                            <th class="text-nowrap">Tipo</th>
                            <?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
                            <th class="text-nowrap">Opciones</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="active">
                            <th class="text-nowrap stext-middle" data-datafilter-filter="false">#</th>
                            <th class="text-nowrap">estudiante</th>
                            <th class="text-nowrap">familiar</th>
                            <th class="text-nowrap">Materias</th>
                            <th class="text-nowrap">Horarios</th>
                            <th class="text-nowrap">Motivo</th>
                            <th class="text-nowrap">Comprobante</th>
                            <th class="text-nowrap">Estado</th>
                            <th class="text-nowrap">Inicia</th>
                            <th class="text-nowrap">Finaliza</th>
                            <th class="text-nowrap">Tipo</th>
                            
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
                        <li>No existen Permisos registrados en la base de datos.</li>
                        <li>Para crear nuevos Permisos debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
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
        <p>¿Esta seguro de eliminar el Permiso?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn_eliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal_aprobar">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <input type="hidden" id="area_aprobar">
        <p>¿Esta seguro de Aprobar el Permiso </span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" id="btn_aprobar">Aprobar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal_rechazar">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <input type="hidden" id="area_rechazar">
        <p>¿Esta seguro de rechazar el Permiso </span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger " id="btn_rechazar">Rechazar</button>
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
        bootbox.confirm('Está seguro que desea eliminar al Permiso?', function (result) {
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
    $("#id_permiso").val(d[0]);
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

    estudiante_id = (d[1]);
   



   $.ajax({
            url: '?/ins-permisos-administrador/procesos',
            type: 'POST',
            data: {
                'estudiante_id': estudiante_id,
                'accion': 'listar_familiares',
            },
            dataType: 'JSON',
            success: function(resp){
               // alert('ejemplo');
                //alert(resp[0]['id_catalogo_detalle']); 
                //console.log(resp);
                
                $("#familiar_id").html("");
                $("#familiar_id").append('<option value="' + 0 + '">Seleccionar familiar</option>');


                $.each(resp, function (index, value) {
                    // APPEND OR INSERT DATA TO SELECT ELEMENT.
                    $('#familiar_id').append('<option value="' + value.id_persona + '">' + value.primer_apellido + ' ' + value.segundo_apellido + ' ' + value.nombres + '</option>');
                });
                     
                
            }
        });




    $.ajax({
            url: '?/ins-permisos-administrador/procesos',
            type: 'POST',
            data: {
                'estudiante_id': estudiante_id,
                'accion': 'listar_materias',
            },
            dataType: 'JSON',
            success: function(item){
               // alert('ejemplo');
                //alert(resp[0]['id_catalogo_detalle']); 
                //console.log(r

                
            $("#categoria").val("");
                     
    $('#materia_id').selectize()[0].selectize.destroy();
    $('#materia_id').selectize({
        maxItems: 100,
        valueField: 'id_materia',
        labelField: 'nombre_materia',
        searchField: 'nombre_materia',

        options: item,
        create: false,
        render: {
        option: function(item, escape) {
            
       return '<option value="' + (item.id_materia ? item.id_materia : item.id_curso) + '">'  + ' ' + (item.nombre_materia ? item.nombre_materia : item.nombre_curso) + ' '  + (item.hora_ini ? item.hora_ini : item.horario_dia) + ' ' + (item.hora_ini ? item.hora_fin : item.horario_dia) + '</option>';


        }



    }
    });




            }
        });



$.ajax({
            url: '?/ins-permisos-administrador/procesos',
            type: 'POST',
            data: {
                'estudiante_id': estudiante_id,
                'accion': 'listar_horarios',
            },
            dataType: 'JSON',
            success: function(item){
               // alert('ejemplo');
                //alert(resp[0]['id_catalogo_detalle']); 
                //console.log(r

            



    $('#horario_id').selectize()[0].selectize.destroy();
    $('#horario_id').selectize({
        maxItems: null,
        valueField: 'id_horario_dia',
        labelField: 'hora_ini',
        searchField: 'hora_ini',

        options: item,
        create: false,
        render: {
        option: function(item, escape) {
            
       

        return '<option value="' + (item.id_horario_dia) + '">' + (item.hora_ini) + ' ' + (item.hora_fin) + ' </option>';
        




        }


    }
    });


            }
        });

    $.ajax({
        url: '?/ins-permisos-administrador/procesos',
        type: 'POST',
        data:{
            'accion': 'recuperar_datos',
            'id_permiso':nro 
            },
        dataType: 'JSON',
        success: function(resp){    

    $("#dvmateria").hide();
    $("#dvhorario").hide();

    $("#form_contrato")[0].reset();
    $("#btn_nuevo").hide();
    $("#btn_editar").show();
    $("#fotedit").show();
    $("#archivo_documento").attr('required', false);
    $("#modal_contrato").modal("show");
    $("#id_permiso").val(resp["id_permiso"]);
    $("#estudiante_id").val(resp["estudiante_id"]);
    $("#familiar_id").val(resp["familiar_id"]);
    $("#categoria").val(resp["categoria"]);
    


    $("#tipo_permiso").val(resp["tipo_permiso"]);
    $("#grupo_permiso").val(resp["grupo_permiso"]);
    $("#contrato_id").val(resp["contrato_id"]);
    $("#motivo").val(resp["motivo"]);
    $("#seguimiento_permiso").val(resp["seguimiento_permiso"]);
    $("#fecha_inicio").val(resp["fecha_inicio"]);
    $("#fecha_final").val(resp["fecha_final"]);
    $("#archivo_documento_nombre").val(resp["archivo_documento"]);
    $('#archivo_documento').attr('src', "files/demoeducheck/rrhh/" + resp["archivo_documento"]);
    

    var str_array_skills2 = resp["materia_id"].split(',');
            var $select2 =   $('#materia_id').selectize();
            var selectize2 = $select2[0].selectize;
            selectize2.setValue(str_array_skills2);
            selectize2.refreshOptions();

    var str_array_skills3 = resp["horarios_id"].split(',');
            var $select2 =   $('#horario_id').selectize();
            var selectize2 = $select2[0].selectize;
            selectize2.setValue(str_array_skills3);
            selectize2.refreshOptions();


        }
    });

    settodo();
}
    
     
    

<?php endif ?>

function abrir_ordenar_contratos(){
     var d = contenido.split("*");
    var nro=(d[0]);


    estudiante_id = (d[1]);
   
    $.ajax({
            url: '?/ins-permisos-administrador/procesos',
            type: 'POST',
            data: {
                'estudiante_id': estudiante_id,
                'accion': 'listar_materias',
            },
            dataType: 'JSON',
            success: function(item){
               // alert('ejemplo');
                //alert(resp[0]['id_catalogo_detalle']); 
                //console.log(r

                
            $("#categoria").val("");
                     
            



    $('#materia_id').selectize()[0].selectize.destroy();
    $('#materia_id').selectize({
        maxItems: 100,
        valueField: 'id_materia',
        labelField: 'nombre_materia',
        searchField: 'nombre_materia',

        options: item,
        create: false,
        render: {
        option: function(item, escape) {
            
       return '<option value="' + (item.id_materia ? item.id_materia : item.id_curso) + '">'  + ' ' + (item.nombre_materia ? item.nombre_materia : item.nombre_curso) + ' '  + (item.hora_ini ? item.hora_ini : item.horario_dia) + ' ' + (item.hora_ini ? item.hora_fin : item.horario_dia) + '</option>';
        




        }




    }
    });




            }
        });
}

<?php if ($permiso_crear) : ?>
function abrir_crear(){
    $("#modal_contrato").modal("show");
    $("#form_contrato")[0].reset();
    $("#btn_editar").hide();
    $("#fotedit").hide(); 
        $("#dvhorario").hide();
    $("#dvmateria").hide();
    $("#btn_nuevo").show();
    setfec();
    setcargo();
}
<?php endif ?>


var columns=[
    {data: 'id_permiso'},
    {data: 'estudiante_id'},
    {data: 'familiar_id'},
    {data: 'materia_id'},
    {data: 'horarios_materias'},
    {data: 'motivo'},
    {data: 'archivo_documento'},
    {data: 'seguimiento_permiso'},
    {data: 'fecha_inicio'},
    {data: 'fecha_final'},
    {data: 'tipo_permiso'}
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
        url: '?/ins-permisos-administrador/busqueda',
        dataSrc: '',
        type:'POST',
        dataType: 'json'
    },
    columns: columns,

    "columnDefs": [

{
        "render": function (data, type, row) {
            var result = "";
            var results= row['seguimiento_permiso'];
<?php $results = "<script>document.write(results)</script>"?> 
            var contenido = row['id_permiso'] + "*" + row['estudiante_id']+ "*" + row['familiar_id'] + "*" + row['materia_id']+ "*" +  row['horarios_id']+ "*" +  row['motivo']+ "*" +  row['seguimiento_permiso']+ "*" +  row['archivo_documento']+ "*" +  row['fecha_inicio']+ "*" +  row['fecha_final']+ "*" +  row['tipo_permiso']+ "*" +  row['grupo_permiso'];

                if (row['seguimiento_permiso'] == 'SOLICITUD' ) {


                result+="<?php if (false) : ?><a href='#' class='btn btn-info btn-xs' onclick = 'ver("+'"'+contenido+'"'+")'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
                    
                    "<?php if ($permiso_editar ) : ?><a href='#' id='bt_aprobar"+row['id_permiso']+"' row['estudiante_id']' class='btn btn-success btn-xs' style='color:black' onclick='aprobar("+'"'+contenido+'"'+")'>Aprobar <span class='fas fa-check-circle' style='color:black'></span></a><?php endif ?>" 
                    
                        +
                    
                    "<?php if ($permiso_editar) : ?><a href='#' id='bt_rechazar' class='btn btn-danger    btn-xs' style='color:BLACK' onclick='rechazar("+'"'+contenido+'"'+")'>Rechazar <span class='fas fa-times-circle' ></span></a><?php endif ?> </BR></BR>" +
                    
                    "<?php if ($permiso_editar) : ?><a href='#' class='btn btn-warning btn-xs' style='color:white' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note' style='color:black'></span></a><?php endif ?> &nbsp" +
                    "<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' role='button' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash' style='color:black'></span></a><?php endif ?>";

                    } else {    
            
                    if (row['seguimiento_permiso'] == 'RECHAZADO' ) {


            result+="<?php if (false) : ?><a href='#' class='btn btn-info btn-xs' onclick = 'ver("+'"'+contenido+'"'+")'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
                    
                    "<?php if ($permiso_editar ) : ?><a href='#' id='bt_aprobar"+row['id_permiso']+"' row['estudiante_id']' class='btn btn-success btn-xs' style='color:black' onclick='aprobar("+'"'+contenido+'"'+")'>Aprobar <span class='fas fa-check-circle' style='color:black'></span></a><?php endif ?>" 
                    
                        +
                    
                    
                    "<?php if ($permiso_editar) : ?><a href='#' class='btn btn-warning btn-xs' style='color:white' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note' style='color:black'></span></a><?php endif ?> &nbsp" +
                    "<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' role='button' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash' style='color:black'></span></a><?php endif ?>";

                    } else {
                


                result+="<?php if (false) : ?><a href='#' class='btn btn-info btn-xs' onclick = 'ver("+'"'+contenido+'"'+")'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
                    
                    
                    "<?php if ($permiso_editar) : ?><a href='#' id='bt_rechazar' class='btn btn-danger    btn-xs' style='color:BLACK' onclick='rechazar("+'"'+contenido+'"'+")'>Rechazar <span class='fas fa-times-circle' ></span></a><?php endif ?> </BR></BR>" +
                    
                    "<?php if ($permiso_editar) : ?><a href='#' class='btn btn-warning btn-xs' style='color:white' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note' style='color:black'></span></a><?php endif ?> &nbsp" +
                    "<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' role='button' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash' style='color:black'></span></a><?php endif ?>";





                    }

                  }  
            return result;
        },
        "targets": 11
},
       

            {
                "render": function(data, type, row) {
                    var imagen = "";
                    if (row['archivo_documento'] == null  || row['archivo_documento'] == "") {
                        archivo_documento = "files/logos/xpicture.png";
                    } else {
                        archivo_documento = "files/demoeducheck/permisos/" + row['archivo_documento'];
                    }
                    imagen += "<a href='" + archivo_documento + "' class='btn btn-dark btn-xs'  role='button' download><i class='fa fa-download'></i></a>";

                    
                    return imagen;
                },
                "targets": 6

            },
            {
                "render": function(data, type, row) {
                    
                    var imagen = "";
                    if (row['seguimiento_permiso']=="APROBADO") {


                    imagen +=  '<td><span style="color:#009975">'+ row['seguimiento_permiso'] +'</span></td>';
                     ;

                    }else{


                    imagen +=  '<td><span style="color:red">'+ row['seguimiento_permiso'] +'</span></td>';
                     ;
                     
                    }
                    

                    
                    return imagen;
                },
                "targets": 7

            },
            

            {
                    "render": function (data, type, row) {
                        var imagen = "";
                        imagen += row['username'].replace(".", " ");  

                    
                    return imagen;
                    },
                    "targets": 1
            },

            
            

            {
                    "render": function (data, type, row) {
                        var imagen = "";
                        if (row['nombres']) {
                            imagen +=  row['nombres']+ " " + row['primer_apellido'];
                        }
                        

                    
                    return imagen;
                    },
                    "targets": 2
            },
            {
                    "render": function (data, type, row) {
                        cont = cont +1;
                        return cont;
                    },
                    "targets": 0
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
    id_permiso = $("#area_eliminar").val();
    $.ajax({
        url: '?/ins-permisos-administrador/eliminar',
        type:'POST',
        data: {'id_permiso':id_permiso},
        success: function(resp){
            //alert(resp)


            switch(resp){
                case '1': $("#modal_eliminar").modal("hide");
                            dataTable.ajax.reload();
                            alertify.success('Se elimino el contrato correctamente');break;
                case '2': $("#modal_eliminar").modal("hide");
                            alertify.error('No se pudo eliminar ');
                            break;
            }
        }
    })
})
<?php endif ?>


<?php if ($permiso_eliminar) : ?>
function aprobar(contenido){
    $("#modal_aprobar").modal("show");
    var d = contenido.split("*");
    $("#area_aprobar").val(d[0]);
    $("#texto_permiso").text(d[1]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
$("#btn_aprobar").on('click', function(){
    id_permiso = $("#area_aprobar").val();
    $.ajax({
        url: '?/ins-permisos-administrador/procesos',
        type:'POST',
         data: {
                'id_permiso': id_permiso,
                'accion': 'aprobar_permiso',
            },
        success: function(resp){
            //alert(resp)

             
            switch(resp){
                case '1': $("#modal_aprobar").modal("hide");
                            dataTable.ajax.reload();
                            alertify.success('Se Aprobo el Permiso correctamente');


                
                            break;
                case '2': $("#modal_aprobar").modal("hide");
                            alertify.error('No se pudo Aprobar el Permiso ');
                            break;
            }


        }
    })
})
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
function rechazar(contenido){
    $("#modal_rechazar").modal("show");
    var d = contenido.split("*");
    $("#area_rechazar").val(d[0]);
    $("#texto_permiso").text(d[1]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
$("#btn_rechazar").on('click', function(){
    id_permiso = $("#area_rechazar").val();
    $.ajax({
        url: '?/ins-permisos-administrador/procesos',
        type:'POST',
         data: {
                'id_permiso': id_permiso,
                'accion': 'rechazar_permiso',
            },
        success: function(resp){
            //alert(resp)

            $("#bt_aprobar").show();
             $("#bt_rechazar").hide();
            switch(resp){
                case '1': $("#modal_rechazar").modal("hide");
                            dataTable.ajax.reload();
                            alertify.success('Se Rechazo el Permiso correctamente');break;
                case '2': $("#modal_rechazar").modal("hide");
                            alertify.error('No se pudo Rechazo el Permiso ');
                            break;
            }
        }
    })
})
<?php endif ?>
</script>