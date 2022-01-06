<?php

// Obtiene la cadena csrf
$csrf = set_csrf();


// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);


$permiso_subir = in_array('subir', $_views);

?>

<form id="form_contrato" enctype="multipart/form-data"> 
<div class="modal fade"  id="modal_estudiante" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 90%;max-width:1300px;" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <h5 class="modal-title" id="exampleModalLabel">informacion Estudiante </h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body">
      











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
                                        <a href="?/ins-informacion-estudiante/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Contrato</a>
                                        <?php endif ?> 
                                        <?php if ($permiso_imprimir) : ?>
                                        <div class="dropdown-divider"></div>
                                        <a href="?/ins-informacion-estudiante/excel" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Excel de Contrato</a>
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
                <table id="table1" class="table table-bordered table-condensed table-striped table-hover">
                    <thead>
                        <tr class="active">
                            <th class="text-nowrap">#</th>
                            <th class="text-nowrap">codigo_estudiante</th>
                            
                            <th class="text-nowrap">Paterno</th>
                            <th class="text-nowrap">Materno</th>
                            <th class="text-nowrap">Nombres</th>
                            <th class="text-nowrap"># de documento</th>
                            <th class="text-nowrap">Foto</th>
                            <th class="text-nowrap">Fecha_nacimiento</th>
                            <th class="text-nowrap">Genero</th>
                            <th class="text-nowrap">Direccion</th>

                            <?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
                            <th class="text-nowrap">Opciones</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                       <tr class="active">
                            <th class="text-nowrap stext-middle" data-datafilter-filter="false">#</th>
                            <th class="text-nowrap">codigo_estudiante</th>
                            
                            <th class="text-nowrap">Paterno</th>
                            <th class="text-nowrap">Materno</th>
                            <th class="text-nowrap">Nombres</th>
                            <th class="text-nowrap"># de documento</th>
                            <th class="text-nowrap">Foto</th>
                            <th class="text-nowrap">Fecha_nacimiento</th>
                            <th class="text-nowrap">Genero</th>
                            <th class="text-nowrap">Direccion</th>
                            
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








       
        
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary pull-right" id="btn_nuevo">Registrar</button>
                <button type="submit" class="btn btn-primary pull-right" id="btn_editar">Editar</button>
            </div>

            </div>
        </div>
    </div>
</div>
</form>

<script>




$(function () {

  
    
    $('form:first').on('reset', function () {
        
    });


    $("#form_contrato").validate({
      rules: {
        nombres: {required: true},
        genero: {required: true}
        
      },
      errorClass: "help-inline",
      errorElement: "span",
      highlight: highlight,
      unhighlight: unhighlight,
      messages: {
        genero: "Debe ingresar un nombre ",
        genero: "elija su genero"
      },
      //una ves validado guardamos los datos en la DB
      submitHandler: function(form){

        var formData = new FormData($("#form_contrato")[0]);

        //var formData = new FormData($("#form_contrato"));
        //var files = $('#foto')[0].files[0];
        //formData.append('file',files);

         //var frmData = new FormData;
        //frmData.append("imagen",$("input[name=imagen]")[0].files[0]);
          //var datos = $("#form_contrato").serialize();
          $.ajax({
              type: 'POST',
              url: "?/ins-informacion-estudiante/guardar",
              data: formData,
              cache: false,
              contentType: false,
              processData: false,  
            
              success: function (resp) {
                console.log(resp); 
                cont=0;
                switch(resp){

                  case '1':
                            dataTable.ajax.reload();
                            $("#modal_estudiante").modal("hide");
                            alertify.success('Se registro el Contrato correctamente');
                            break;
                  case '2': dataTable.ajax.reload();
                            $("#modal_estudiante").modal("hide"); 
                            alertify.success('Se editó el Contrato correctamente'); 
                            break;
                }
              }
          });
      }
    })
  })






var columns=[
    {data: 'id_estudiante'},
    {data: 'codigo_estudiante'},
    //{data: 'rude'},
    {data: 'primer_apellido'},
    {data: 'segundo_apellido'},
    {data: 'nombres'},
    {data: 'numero_documento'},
    {data: 'foto'},
    {data: 'fecha_nacimiento'},
    {data: 'genero'},
    {data: 'direccion'}
];
var cont = 0;
//function listarr(){
var dataTable = $('#table1').DataTable({
    language: dataTableTraduccion,
    searching: true,
    paging:true,
    "lengthChange": true,
    "responsive": true,
    ajax: {
        url: '?/ins-informacion-estudiante/busqueda',
        dataSrc: '',
        type:'POST',
        dataType: 'json'
    },
    columns: columns,

    "columnDefs": [

{
        "render": function (data, type, row) {
            var result = "";
            var contenido = row['id_estudiante'] + "*" + row['codigo_estudiante']  + "*" + row['primer_apellido']+ "*" +  row['segundo_apellido']+ "*" +  row['nombres']+ "*" +  row['numero_documento']+ "*" +  row['foto']+ "*" +  row['fecha_nacimiento']+ "*" +  row['genero']+ "*" +  row['direccion'];
            result+="<?php if (false) : ?><a href='#' class='btn btn-info btn-xs' onclick = 'ver("+'"'+contenido+'"'+")'><span class='icon-eye'></span></a><?php endif ?>"+
                    "<?php if ($permiso_editar) : ?><a href='?/ins-informacion-estudiante/listar-documento/"+''+row['id_estudiante']+''+"'' class='btn btn-info btn-xs' style='color:black' onclick='abrir_documento()'><span style='color:black' class='fa fa-file'></span></a><?php endif ?>" +
                    "<?php if ($permiso_editar) : ?><a href='?/ins-informacion-estudiante/imprimir/"+''+row['id_estudiante']+''+"'' class='btn btn-success btn-xs' style='color:black' onclick='abrir_documento()'><span style='color:black' class='fa fa-print'></span></a><?php endif ?>" +
                    "<?php if ($permiso_editar) : ?><a href='#' class='btn btn-warning btn-xs' style='color:white' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note' style='color:black'></span></a><?php endif ?>" +
                    "<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' role='button' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash' style='color:black'></span></a><?php endif ?>";
            return result;
        },
        "targets": 10
},
            {
                "render": function(data, type, row) {
                    var imagen = "";
                    if (row['foto'] == null  || row['foto'] == "") {
                        archivo_documento = "files/logos/xpicture.png";
                    } else {
                        archivo_documento = "files/demoeducheck/rrhh/" + row['foto'];
                    }
                    //imagen += "<a img='" + archivo_documento + "' class='btn btn-dark btn-xs'  role='button' download><i class='fa fa-download'></i></a>";

                    imagen += "<img src='files/logos/xpicture.png' width='50' height='50' class='btn btn-dark btn-xs'  role='button'>";
                    return imagen;
                },
                "targets": 6

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
</script>

























