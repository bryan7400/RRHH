<?php

// Obtiene la cadena csrf
$csrf = set_csrf();  
$gestion=$_gestion['id_gestion'];
// Obtiene los contratos

$postulacion = $db->query("SELECT * FROM per_postulacion z 
        LEFT JOIN per_cargos c ON c.id_cargo=z.cargo_id
        WHERE  z.estado = 'A' 
        ORDER BY z.id_postulacion ASC")->fetch();



// Obtiene los permisos 
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_editar =  in_array('ingresar-personal', $_views);
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
            <h2 class="pageheader-title">Postulantes</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Configuración</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Postulante</a></li>
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
            <
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
                                        <?php if ($permiso_editar) : ?>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" onclick="abrir_editar2();" class="dropdown-item">Filtrar por Fecha</a>
                                         
                                        <?php endif ?>  
                                        <?php if ($permiso_imprimir) : ?>
                                        <div class="dropdown-divider"></div>
                                        
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
    <?php if ($postulacion) : ?>
    <table id="table" class="table table-bordered table-condensed table-striped table-hover "width="100%">
        <thead>
            <tr class="active">
                <th class="text-nowrap">#</th>
                <th class="text-nowrap">Nombre</th>
                <th class="text-nowrap">Fecha de Nacimiento</th>
                <th class="text-nowrap">Fecha de Postulacion</th>
                <th class="text-nowrap">ci</th>
                <th class="text-nowrap">Estado</th>
                <th class="text-nowrap">celular</th>
                <th class="text-nowrap">Cargo</th>


                <?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
                <th class="text-nowrap">Opciones</th>
                <?php endif ?>
            </tr>
        </thead>
        <tfoot>
            <tr class="active">
                <th class="text-nowrap stext-middle" data-datafilter-filter="false">#</th>
                <th class="text-nowrap">Nombre</th>
                <th class="text-nowrap">Fecha de Nacimiento</th>
                <th class="text-nowrap">Fecha de Postulacion</th>
                <th class="text-nowrap">ci</th>
                <th class="text-nowrap">Estado</th>
                <th class="text-nowrap">celular</th>
                <th class="text-nowrap">Cargo</th>
                
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
            <li>No existen Postulantes registrados en la base de datos.</li>
            <li>Para crear nuevos Postulantes debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
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
        <p>¿Esta seguro de eliminar el Postulante <span id="texto_contrato"></span>?</p>
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
    require_once ("modalfecha.php");
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
    

<script src="<?= js; ?>/educheck.js"></script>

<?php require_once show_template('footer-design'); ?>

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
        bootbox.confirm('¿Está seguro que desea eliminar al Postulante?', function (result) {
            if (result) {
                $.request(href, csrf);
            }
        });
    });
    <?php endif ?>
     
    <?php if ($postulacion) : ?>
    // $('#nivel_academico').DataFilter({
    //  filter: true,
    //  name: 'niveles',
    //  reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
    // });
    <?php endif ?>
    //carga toda la lista de grupo proyecto con DataTable
});



<?php if ($permiso_editar) : ?>
function abrir_editar(contenido){
    var d = contenido.split("*");
    var nro=(d[0]);
    $.ajax({
        url: '?/rrhh-postulantes/procesos',
        type: 'POST',
        data:{
            'accion': 'recuperar_datos',
            'id_postulacion':nro 
            },
        dataType: 'JSON',
        success: function(resp){    


    $("#form_contrato")[0].reset();
    $("#btn_nuevo").hide();
    $("#btn_editar").show();
    $("#fotedit").show();
    $("#archivo_documento").attr('required', false);
    $("#modal_contrato").modal("show");
    $("#id_postulacion").val(resp["id_postulacion"]);
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


<?php if ($permiso_editar) : ?>
function abrir_filtrar(contenido){

    $("#modal_filtrar").modal("show");


    
}
    
<?php endif ?>


<?php if ($permiso_editar) : ?>
function abrir_editar(contenido){
    var d = contenido.split("*");
    var nro=(d[0]);
    $.ajax({
        url: '?/rrhh-postulantes/procesos',
        type: 'POST',
        data:{
            'accion': 'recuperar_datos',
            'id_postulacion':nro 
            },
        dataType: 'JSON',
        success: function(resp){    


    $("#form_contrato")[0].reset();
    $("#btn_nuevo").hide();
    $("#btn_editar").show();
    $("#fotedit").show();
    $("#archivo_documento").attr('required', false);
    $("#modal_contrato").modal("show");
    $("#id_postulacion").val(resp["id_postulacion"]);
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


function listar_aulas() {
    // nivel = 0;//$("#nivel_academico option:selected").val()
    //var turno = $("#turno option:selected").val();//mañána tarde noche
        
    var nivel = $("#nivel option:selected").val();//primaria  sec
      
        
        $.ajax({
            url: '?/rrhh-postulantes/procesos',
            type: 'POST',
            data: {
                'boton': 'listar_aulas',
                'nivel': nivel, 
            },
            dataType: 'JSON',
            success: function(resp){
            console.log('Listar aula'+ resp); 
               //alert('rest aulaas');
                //alert(resp[0]['id_catalogo_detalle']); 
                //console.log(resp);
                $("#aula").html("");
                $("#aula").append('<option value="">(Todos)Seleccionar</option>');////' + 0 +
                for (var i = 0; i < resp.length; i++) {
                    $("#aula").append('<option value="' + resp[i]["id_aula"] + '">' + resp[i]["nombre_aula"] +' '+ resp[i]["nombre_nivel"]+'</option>');
                }
                //console.log(resp[0]);
                listar_paralelos_tabla();   

            }
        });
        
    }


<?php if ($permiso_editar) : ?>
function abrir_editar2(contenido){
    $("#fecha_inicio_filtro").val("");
    $("#fecha_final_filtro").val("");
    $("#modal_filtro").modal("show");
   // $("#fecha_inicio").val(resp["fecha_inicio"]);
    //$("#fecha_final").val(resp["fecha_final"]);

    
     
    
}
<?php endif ?>

<?php if ($permiso_editar) : ?>
function abrir_editar3(contenido){
    $("#form_contrato")[0].reset();
    $("#btn_nuevo").hide();
    $("#btn_editar").show();
    $("#archivo_documento").attr('required', true);
    $('input[name="genero"]').removeAttr('checked');
    $("#modal_contrato").modal("show");
    var d = contenido.split("*");
    $("#id_postulacion").val(d[0]);
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
        url: '?/rrhh-postulantes/procesos',
        type: 'POST',
        data:{
            'accion': 'recuperar_datos',
            'id_postulacion':nro 
            },
        dataType: 'JSON',
        success: function(resp){    


    $("#form_contrato")[0].reset();
    $("#btn_nuevo").hide();
    $("#btn_editar").show();
    $("#fotedit").show();
    $("#archivo_documento").attr('required', false);
    $("#modal_contrato").modal("show");
    $("#id_postulacion").val(resp["id_postulacion"]);
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

<?php if ($permiso_eliminar) : ?>
function adicional_personal(id){
    bootbox.confirm('¿Está seguro de ingresarlo al personal del colegio?', function (result) {
        if(result){
            
                
            url="?/rrhh-postulantes/guardar-personal/"+id;
                window.location = url;
        }
    });
}
<?php endif ?>

var fecha_inicio_filtro=$("#fecha_inicio_filtro").val();
var fecha_final_filtro=$("#fecha_final_filtro").val();
var columns=[
    {data: 'id_postulacion'},
    {data: 'nombre'},
    {data: 'fecha_nacimiento'},
    {data: 'fecha_registro'},
    {data: 'ci'},
    {data: 'estado'},
    {data: 'celular'},
    {data: 'cargo'}
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
        url: '?/rrhh-postulantes/busqueda',
        dataSrc: '',
        type:'POST',
        data:{
            'fecha_inicio_filtro': fecha_inicio_filtro,
            'fecha_final_filtro':fecha_final_filtro, 
            },
        dataType: 'json',
    },
    columns: columns,

    "columnDefs": [

{
        "render": function (data, type, row) {
            var result = "";
            var contenido = row['id_postulacion'] + "*" + row['nombre']+ "*" + row['fecha_nacimiento'] + "*" + row['estado'] + "*" + row['ci'] + "*" + row['celular'] + "*" + row['fecha_registro'] + "*" + row['cargo'];
            result+="<?php if (false) : ?><a href='#' class='btn btn-info btn-xs' onclick = 'ver("+'"'+contenido+'"'+")'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+
                    "<?php if ($permiso_ver) : ?><a href='?/rrhh-postulantes/ver/"+''+row['id_postulacion']+''+"'' class='btn btn-info btn-xs' style='color:black' ><span style='color:black' class='fa fa-eye'></span></a><?php endif ?> &nbsp" +
                    "<?php if ($permiso_editar) : ?><a  class='btn btn-success btn-xs' style='color:black' onclick='adicional_personal("+'"'+row['id_postulacion']+'"'+")'><span style='color:black' class='fa fa-plus'></span></a><?php endif ?> &nbsp" +
                    
                    "<?php if ($permiso_eliminar) : ?><a onclick='eliminar("+'"'+row['id_postulacion']+'*'+row['personal']+'"'+")' href='#' class='btn btn-danger btn-xs' ><span class='fa fa-trash' style='color:black'></span></a><?php endif ?>";
            return result;
        },
        "targets": 8
},

            {
                "render": function(data, type, row) {
                    var foto = "";
                    
                    if (row['personal'] =='I') {
                        estado ="<span style='color:#C00'>  Postulacion </span>" ;
                    } else {
                        

                        estado ="<span style='color:#009975'> En Personal </span>";
                    }
                    foto += estado ;
                    return foto;
                },
                "targets": 5

            },


               {
                "render": function(data, type, row) {
                    var nombre_completo = "";
                    
                    nombre = row['nombre']  +" " + row['paterno'] + " " + row['materno'] ;
                     nombre_completo += nombre ;
                    return nombre_completo;
                },
                "targets": 1

            },
            
            
            {
                    "render": function (data, type, row) {
                        cont = cont +1;
                        return cont;
                    },
                    "targets": 8
            }


    ]
});
//} 


function imprimir_filtrado() {
        //alert("imprimiendo... ?/sitio/imprimir/");
   
        
        if ( $("#fecha_inicio_filtro").val() =='') {
            var fecha_inicio_filtro="0";
            var fecha_final_filtro="0";
        }else{
            var fecha_inicio_filtro=$("#fecha_inicio_filtro").val();
            var fecha_final_filtro=$("#fecha_final_filtro").val();
        }
       

        window.open('?/rrhh-postulantes/imprimir/'+fecha_inicio_filtro+'/'+fecha_final_filtro, "_blank");
    }

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
function eliminar(id){
    var d = id.split("*");

    if ( d[1] =='A') {
        alertify.error('No se puede eliminar al personal');
                            
    }else{
      bootbox.confirm('¿Está seguro de eliminar la postulacion?', function (result) {
        if(result){
            url="?/rrhh-postulantes/eliminar/"+d[0];
            window.location = url;
        }
    });  
    }
    
}
<?php endif ?>


<?php if ($permiso_eliminar) : ?>
$("#btn_eliminar").on('click', function(){
    id_postulacion = $("#area_eliminar").val();
    $.ajax({
        url: '?/rrhh-postulantes/eliminar',
        type:'POST',
        data: {'id_postulacion':id_postulacion},
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





</script>