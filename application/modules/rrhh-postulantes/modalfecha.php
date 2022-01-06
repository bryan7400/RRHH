    <?php

    // Obtiene la cadena csrf
    $csrf = set_csrf();


    // Obtiene los permisos



    ?>
   
    <div class="modal fade" id="modal_filtro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalLabel">Filtrar Postulantes</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    
            <input type="hidden" name="<?= $csrf; ?>">

     
            <div class="form-row form-group">
            <input id="id_contrato" name="id_contrato" type="hidden" class="form-control">



              
            </div>




    

    <div class="form-row form-group">
    <div class="form-group col-md-6">
              <label class="control-label" for="fecha_inicio_filtro">Fecha inicio: </label>
              <div class="controls">
                <input id="fecha_inicio_filtro" name="fecha_inicio_filtro" type="date" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>" required title="Seleccione una fecha inicial" >
              </div>
            </div>
    <div class="form-group col-md-6 Indef">
              <label class="control-label" for="fecha_final_filtro">Fecha final: </label>
              <div class="controls">
                <input id="fecha_final_filtro" onchange="DateCheck();" name="fecha_final_filtro" type="date" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
              </div>
            </div>

    </div>







            
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
            <a onclick="filtrar_fecha();" class="btn btn-primary pull-right" id="btn_nuevo" style="color: white;">Filtrar</a>
                    
                </div>
            </div>
        </div>
    </div>

    <script>



function filtrar_fecha_tabla(){
var fecha_inicio_filtro=$("#fecha_inicio_filtro").val();
var fecha_final_filtro=$("#fecha_final_filtro").val();
var columns=[
    {data: 'id_postulacion'},
    {data: 'nombre'},
    {data: 'fecha_nacimiento'},
    {data: 'fecha_registro'},
    {data: 'ci'},
    {data: 'estado'},
    {data: 'cargo'},
    {data: 'celular'}
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
}

function filtrar_fecha(){

var table = $('#table').DataTable();

 table.destroy();

 filtrar_fecha_tabla();
 $("#modal_filtro").modal("hide");

 /*
console.log('hola');
var fecha_inicio_filtro=$("#fecha_inicio_filtro").val();
var fecha_final_filtro=$("#fecha_final_filtro").val();
$.ajax({
        url: '?/rrhh-postulantes/busqueda',
        type: 'POST',
        data:{
            'fecha_inicio_filtro': fecha_inicio_filtro,
            'fecha_final_filtro':fecha_final_filtro 
            },
        dataType: 'JSON',
        success: function(resp){ 

                    //dataTable.ajax.reload();

                    console.log(resp); 
                    dataTable.ajax.reload();

                    
                  }




              });
*/


}


        function DateCheck()
    {
      var StartDate= document.getElementById('fecha_inicio_filtro').value;
      var EndDate= document.getElementById('fecha_final_filtro').value;
      var eDate = new Date(EndDate);
      var sDate = new Date(StartDate);
      if(StartDate!= '' && StartDate!= '' && sDate> eDate)
        {
        alert("Por favor asegurese que la fecha final sea mayor a la inicial.");
        return false;
        }
    }
        function setfec(){
        id=$("#tipo_contrato").val();
        
        $(".Indef").css({'display':'none'});

        if(id=='Plazo Fijo'){
            $(".Indef").css({'display':'block'});
        }
        if(id=='Servicio o Producto'){
            $(".Indef").css({'display':'block'});
        }
        
    }

    function setcargo(){
        id=$("#area_contrato").val();
        
        $(".ItIs").css({'display':'none'});

        if(id=='Administrativo'){
            $(".ItIsAdmin").css({'display':'block'});
        }
        if(id=='Docente'){
            $(".ItIsTeacher").css({'display':'block'});
        }
        if(id=='Docente'){
            $(".ItIsTeacherAdmin").css({'display':'block'});
        }
    }



    $(function () {

      var $nivel_academico = $('#nivel_academico');

        $nivel_academico.selectize({
            maxOptions: 7,
            onInitialize: function () {
                $nivel_academico.show().addClass('selectize-translate');
            },
            onChange: function () {
                $nivel_academico.trigger('blur');
            },
            onBlur: function () {
                $nivel_academico.trigger('blur');
            }
        });
        
        $('form:first').on('reset', function () {
            $nivel_academico.get(0).selectize.clear();
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
                  url: "?/rrhh-contratos/guardar",
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
                                $("#modal_filtro").modal("hide");
                                alertify.success('Se registro el Contrato correctamente');
                                break;
                      case '2': dataTable.ajax.reload();
                                $("#modal_filtro").modal("hide"); 
                                alertify.success('Se editó el Contrato correctamente'); 
                                break;
                    }
                  }
              });
          }
        })
      })




    </script>

























