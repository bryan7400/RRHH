
<?php
// Obtiene los horarios
//$horarios = $db->from('per_horarios')->order_by('id_horario', 'asc')->fetch();
//SELECT asi.`sueldo_total`,ca.`cargo`,e.*  FROM `per_asignaciones` asi LEFT JOIN sys_persona e  ON asi.`persona_id` = e.`id_persona` LEFT JOIN `per_cargos` ca  ON asi.`cargo_id` = ca.`id_cargo`


// Obtiene los permisos
$permiso_crear     = in_array('modalcrear', $_views);
$permiso_contrato  = in_array('modalcontrato', $_views);
$permiso_ver       = in_array('ver', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_eliminar  = in_array('eliminar', $_views);
$permiso_imprimir  = in_array('imprimir', $_views);
$nombre_dominio = escape($_institution['nombre_dominio']);

$gestiones = $db->query("SELECT * FROM ins_gestion kp WHERE  kp.estado='A' ")->fetch();


?>
<?php  require_once show_template('header-design');  ?>



<style>
  .datepicker {z-index: 1151 !important;}
</style> 
<link href="assets/themes/concept/assets/vendor/datepicker/css/datepicker.css" rel="stylesheet">


<!--cabecera-->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Evaluacion por colega</h2>
            <p></p>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">RRHH</a></li>
                        <!--<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Registros iniciales</a></li>-->
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
            <div class="card-header" >
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-right" id="personal_listar_button">
                        <div class="text-label hidden-xs">Seleccionar acción:</div>
						<div class="btn-group">
							<div class="input-group">
								<div class="input-group-append be-addon">
									<button  type="button" onclick="window.location.reload();" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Regresar</button>
									
								</div>
							</div>
						</div> 
					</div>    
                </div>
            </div>

            <div class="card-body">
               <div class="listpersonal">
                    
                    <?php //if ($horarios) : ?>
                	<table id="table" class="table table-bordered table-condensed table-striped table-hover">
                		<thead>
                			<tr class="active">
                				<th class="text-nowrap">#</th>
                				<th class="text-nowrap">foto</th>
                				<th class="text-nowrap">Nombres</th> <!--
                				<th class="text-nowrap">Apellido paterno</th>
                				<th class="text-nowrap">Apellido materno</th>-->
                				<th class="text-nowrap">Datos</th>
                				<th class="text-nowrap">Felicitaciones</th>
                				<th class="text-nowrap">faltas</th>
                				<!--<th class="text-nowrap">Cargo</th>
                				<th class="text-nowrap">Salario</th>-->
                				<!--<th class="text-nowrap">Horarios</th>-->
                				 
                				<th class="text-nowrap">Opciones</th>				
                			</tr>
                		</thead>
                		 
                		<tbody>
                		 
                		</tbody>
                	</table> 
               </div>
               <div class="listKardex" style="display:none">
                 <div class="row">
                        <!-- ============================================================== -->
                        <!-- profile -->
                        <!-- ============================================================== -->
                        <div class="col-xl-3 col-lg-3 col-md-5 col-sm-12 col-12">
                            <!-- ============================================================== -->
                            <!-- card profile -->
                            <!-- ============================================================== -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="user-avatar text-center d-block">
                                        <img src="../educhecka/assets/imgs/avatar.jpg" alt="User Avatar" class="rounded-circle user-avatar-xxl view-image">
                                    </div>
                                    <div class="text-center">
                                        <h2 class="font-24 mb-0 view-name">Michael J. Christy</h2>
                                        <p class="view-cargo">Project Manager  </p>
                                    </div>
                                </div>
                                <div class="card-body border-top">
                                    <h3 class="font-16">Contact Information</h3>
                                    <div class="">
                                        <ul class="list-unstyled mb-0">
                                        <li class="mb-2 "><i class="fas fa-fw fa-envelope mr-2"></i><span class="view-email">michaelchristy@gmail.com</span></li>
                                        <li class="mb-0"><i class="fas fa-fw fa-phone mr-2"></i><span  class=" view-telefono"></span> </li>
                                    </ul>
                                    </div>
                                </div>
                                <div class="card-body border-top">
                                    <h3 class="font-16">Cumpleaños</h3>
                                    <h1 class="mb-0 view-cumple"> </h1>
                                    <div class="rating-star">
                                        <i class="fa fa-birthday-cake "></i>
                                        <i class="fa fa-birthday-cake "></i>
                                        <i class="fa fa-birthday-cake "></i>
                                        <i class="fa fa-birthday-cake "></i>
                                        
                                        <p class="d-inline-block text-dark">14 Reviews </p>
                                    </div>
                                </div>
                               <!-- <div class="card-body border-top">
                                    <h3 class="font-16">Social Channels</h3>
                                    <div class="">
                                        <ul class="mb-0 list-unstyled">
                                        <li class="mb-1"><a href="#"><i class="fab fa-fw fa-facebook-square mr-1 facebook-color"></i>fb.me/michaelchristy</a></li>
                                        <li class="mb-1"><a href="#"><i class="fab fa-fw fa-twitter-square mr-1 twitter-color"></i>twitter.com/michaelchristy</a></li>
                                        <li class="mb-1"><a href="#"><i class="fab fa-fw fa-instagram mr-1 instagram-color"></i>instagram.com/michaelchristy</a></li>
                                        <li class="mb-1"><a href="#"><i class="fas fa-fw fa-rss-square mr-1 rss-color"></i>michaelchristy.com/blog</a></li>
                                        <li class="mb-1"><a href="#"><i class="fab fa-fw fa-pinterest-square mr-1 pinterest-color"></i>pinterest.com/michaelchristy</a></li>
                                        <li class="mb-1"><a href="#"><i class="fab fa-fw fa-youtube mr-1 youtube-color"></i>youtube/michaelchristy</a></li>
                                    </ul>
                                    </div>
                                </div>
                                <div class="card-body border-top">
                                    <h3 class="font-16">Category</h3>
                                    <div>
                                        <a href="#" class="badge badge-light mr-1">Fitness</a><a href="#" class="badge badge-light mr-1">Life Style</a><a href="#" class="badge badge-light mr-1">Gym</a>
                                    </div>
                                </div>-->
                            </div>
                            <!-- ============================================================== -->
                            <!-- end card profile -->
                            <!-- ============================================================== -->
                        </div>
                        <!-- ============================================================== -->
                        <!-- end profile -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- campaign data -->
                        <!-- ============================================================== -->
                        <div class="col-xl-9 col-lg-9 col-md-7 col-sm-12 col-12">
                            <h4>Felicitaciones</h4> 
                           <table id="table2" width="100%" class="table table-bordered table-condensed table-striped table-hover">
                                <thead>
                                    <tr class="active">
                                        <th class="text-nowrap">#</th>
                                        <th class="text-nowrap">fecha</th>
                                        <th class="text-nowrap">Concepto</th>  
                                        <th class="text-nowrap">Observacion</th>
                                        <th class="text-nowrap">tipo </th>
                                        <th class="text-nowrap">adjunto</th>

                                        <th class="text-nowrap">Opciones</th>				
                                    </tr>
                                </thead>

                                <tbody>

                                </tbody>
                            </table>
                             <h4>Sanciones</h4> 
                   <table id="table3" width="100%" class="table table-bordered table-condensed table-striped table-hover">
                		<thead>
                			<tr class="active">
                				<th class="text-nowrap">#</th> 
                				<th class="text-nowrap">fecha</th>
                				<th class="text-nowrap">Concepto</th>  
                				<th class="text-nowrap">Observacion</th>
                				<th class="text-nowrap">tipo </th>
                				<th class="text-nowrap">adjunto</th>
                			  
                				<th class="text-nowrap">Opciones</th> 				
                			</tr>
                		</thead>
                		 
                		<tbody>
                		 
                		</tbody>
                	</table>
                        </div>
                        <!-- ============================================================== -->
                        <!-- end campaign data -->
                        <!-- ============================================================== -->
                    </div>
                 
               </div>
                
            </div>
        </div>
    </div>

</div>
 
 

 
<!--datapicker-->
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.es.js"></script>




<?php
//if ($permiso_editar) {
	//require_once("modalcrear.php");//modal
   // require_once("modalcontrato.php");//modal 
    require_once ("modal-kardex.php");//modal 
//} 
?>
<script>





var dataTable = $('#table').DataTable({
  language: dataTableTraduccion,
  searching: true,
  paging:true,
  stateSave:true,
  "lengthChange": true,
  "responsive": true
}); 
var dataTableFelici = $('#table2').DataTable({
  language: dataTableTraduccion,
  searching: true,
  paging:true,
  stateSave:true,
  "lengthChange": true,
  "responsive": true
});var dataTableSan= $('#table3').DataTable({
  language: dataTableTraduccion,
  searching: true,
  paging:true,
  stateSave:true,
  "lengthChange": true,
  "responsive": true
});
//FUNCION DE RECARGA DE DATATABLE 
//ruta de proceso
var rutaproceso='?/rrhh-kardex/procesos';

listarpersonal();
function listarpersonal(){

      $('#personal_listar_button').hide();

    $.ajax({
        url: rutaproceso,
        type: 'POST',
        data:{
			'accion': 'listar_tabla' 
			},
        dataType: 'JSON',
        success: function(resp){
            
            //limpiamos la tabla
            dataTable.clear().draw(); 
            //recorremos los datos retornados y lo añadimos a la tabla
            var counter=1;//numero de datos
            for (var i = 0; i < resp.length; i++) {
              var foto='files/<?php echo $nombre_dominio; ?>/profiles/avatar.jpg';
                if(resp[i]["foto"]!='' && resp[i]["foto"]!=null){
                  foto='files/<?php echo $nombre_dominio; ?>/profiles/personal/'+resp[i]["foto"]+ '.jpg';
                  //  alert(resp[i]["foto"]);
                }

                var reWhiteSpace = new RegExp("\\s+");
                console.log("llega");
        // Check for white space
        if (reWhiteSpace.test(resp[i]['cargo'])) {
            //alert("Please Check Your Fields For Spaces");
            
            var cargo= resp[i]['cargo'].trim();
        }else{
             var cargo= resp[i]['cargo'];
        }
       
        


                var datos=resp[i]['id_persona']+'*'+foto+'*'+resp[i]['nombres']+ '*'+resp[i]['primer_apellido']+'*'+resp[i]['segundo_apellido']+'*'+resp[i]['genero']+'*'+resp[i]['fecha_nacimiento']+'*'+resp[i]['celular']+'*'+ cargo+'*'+resp[i]['sueldo_total']+'*'+resp[i]['email']+'*'+resp[i]['numero_documento'];
                   let result = datos.replace("**", "* *"); 
                   let results = result.replace("**", "* *"); 
                var botones='<a href="#" data-toggle="tooltip" data-title="Ver horario" class="btn btn-outline-info btn-xs" onclick="abrir_ver('+"'"+results+"'"+');"><span class="fa fa-eye" ></span></a><a href="#" data-toggle="tooltip" data-title="Ver horario" class="btn btn-outline-success btn-xs" onclick="felicitacion('+resp[i]['id_persona']+')"  title="Nueva Felicitaciones"><span class="fa fa-plus" ></span></a><a href="#" data-toggle="tooltip" data-title="Ver horario" class="btn btn-outline-danger btn-xs"  onclick="sancion('+resp[i]['id_persona']+')" title="Nueva Sancion"><span class="fa fa-plus" ></span></a>  <div class="btn-group" role="group"><button id="btnGroupDrop1" type="button" class="btn btn-outline-black dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-print" ></span> </button>    <div class="dropdown-menu " aria-labelledby="btnGroupDrop1">  <?php foreach ($gestiones as $gestion): ?>    <a class="dropdown-item " target="_blank" href="?/rrhh-kardex/imprimir/'+resp[i]['id_persona']+'/<?php echo $gestion["id_gestion"] ?>"><?php echo $gestion["gestion"] ?></a>   <?php endforeach; ?>    </div>  </div> ';
                    
                 dataTable.row.add( [
                            counter,
                            '<img src="'+foto+'" class="img-rounded cursor-pointer" data-toggle="modal" data-target="#modal_mostrar" data-modal-title="Avatar" width="64" height="64">',  
                            resp[i]["cargo"]+':'+resp[i]["nombres"]+' '+resp[i]["primer_apellido"]+' '+resp[i]["segundo_apellido"],  
                            'Tel:'+resp[i]["telefono"]+"  Cel:"+resp[i]["celular"]+'<br>Fecha Nac:'+resp[i]["fecha_nacimiento"],  
                            '<div class="btn btn-light" style="width: 100%;"> <span class=" fas fa-trophy"></span><span class="badge badge-success">'+resp[i]["cantFeli"]+'</span><span class="lineaest bac"></span><div class="progress" style="height: .5em;">   <div class="progress-bar bg-success" style="width:'+resp[i]["cantFeli"]+'0%" role="progressbar"> </div> </div></div>',  
                     
                            '<div class="btn btn-light" style="width: 100%;"><span class="fas fa-gavel"></span><span class="badge badge-danger">'+resp[i]["cantSanc"]+'</span><span class="lineaest bac"></span><div class="progress" style="height: .5em;">   <div class="progress-bar bg-danger" style="width:'+resp[i]["cantSanc"]+'0%" role="progressbar"> </div> </div></div>',//' ',
                            //' ',  
                            botones  
                        ] ).draw( false ); 
               counter++;
            }
	    }
	});
}
    
function felicitacion(id){
		$("#modal_felicitacion").modal("show");
		$("#form_felicitacion")[0].reset();
		$("#form_felicitacion").find('.modal-header').css('background','#46ff74');
     $('#tipoFelSanc').val('felicitacion');
     $('#id_persona').val(id);
		var d = contenido;//.split("*");
		//$("#id_estudiante").val(d);//[0]);
		//$("#id_profesor_materia").val($('#id_materia').val());
		//$("#modo_calificacion_id").val($('#bimestre').val());
        //$("#id_profesor_materia").val(d[4]);
 
	}
function sancion(id){
		$("#modal_felicitacion").modal("show");
		$("#form_felicitacion")[0].reset();
		$("#form_felicitacion").find('.modal-header').css('background','#ffbac1');
        $('#tipoFelSanc').val('sancion');
        $('#id_persona').val(id); 
		var d = contenido;//.split("*");
		//$("#id_estudiante_s").val(d);//[0]);
		//$("#id_profesor_materia_s").val($('#id_materia').val());//val(d[4]);
        //$("#modo_calificacion_id_s").val($('#bimestre').val());
		//console.log(contenido);
	 
	}
function abrir_ver(datos){
    var d=datos.split('*');
      $('.listpersonal').hide(); 
      $('.listKardex').show();
      $('#personal_listar_button').show();

       $('.view-image').attr('src',d[1]);
      $('.view-cargo').text(d[2]+' '+d[3]+' '+d[3]);
      $('.view-name').html(d[8]);
      $('.view-email').html(d[10]);
      $('.view-telefono').html(d[7]);
      $('.view-cumple').html(d[6]);
    id_persona=d[0];
   listarkardex(id_persona);
   // 0['id_persona']+'*'+1['foto']+'*'+2['nombres']+ '*'+3['primer_apellido']+'*'+3['segundo_apellido']+'*'+5['genero']+'*'+6['fecha_nacimiento']+'*'+7['celular']+'*'+8['cargo']+'*'+9['sueldo_total']+'*'+10['email']+'*'+11['numero_documento'];
    
}
var id_persona='';
function listarkardex(id_pers){
      $.ajax({
        url: rutaproceso,
        type: 'POST',
        data:{
			'accion': 'listar_reg_kardex',
            'idpersona':id_pers
			},
        dataType: 'JSON',
        success: function(resp){
             var counter=1;
            //limpiamos la tabla
            dataTableFelici.clear().draw();
            dataTableSan.clear().draw();

           


             for (var i = 0; i < resp.length; i++) {
                 var datos=resp[i]['id_kardex']+'*'+resp[i]['fecha_kardex']+'*'+resp[i]['concepto_kardex']+ '*'+resp[i]['observacion_kardex']+'*'+resp[i]['tipo_kardex']+'*'+resp[i]['tipo_ev_kardex']+'*'+resp[i]['adjunto_kardex']+'*'+resp[i]['persona_id']+'*'+resp[i]['estado'];
                    

                 var imagen = "";
                    if (resp[i]['adjunto_kardex']!=0) {
                        adjunto_kardex = "files/<?php echo $nombre_dominio; ?>/rrhh/kardex/evaluaciones_personal/" + resp[i]['adjunto_kardex'];
                        imagen += "<a href='" + adjunto_kardex + "' class='btn btn-dark btn-xs'  role='button' download><i class='fa fa-download'></i></a>";
                    } else {

                        
                    }
                    

                    
                    
                var botones='<a href="#" data-toggle="tooltip" data-title="Ver horario" class="btn btn-info btn-xs" onclick="editar('+"'"+datos+"'"+')"  title="Editar "><span class="fa fa-edit" ></span></a><a href="#" data-toggle="tooltip" data-title="Ver horario" class="btn btn-danger btn-xs"  onclick="eliminar('+resp[i]['id_kardex']+')" title="Eliminar"><span class="fa fa-trash " ></span></a>';
                 var tipo='sin tipo';
                 if(resp[i]["tipo_ev_kardex"]==1){
                     tipo='<p>Evaluacion</p>';
                 }else{
                     tipo='<p>Memorandum</p>';
                     
                 }
                 if(resp[i]["tipo_kardex"]=='felicitacion'){
                  var tipokard='<span class="badge badge-success">felicitacion</span>';
                 dataTableFelici.row.add( [
                            counter,
                            resp[i]["fecha_kardex"],  
                            resp[i]["concepto_kardex"],  
                            resp[i]["observacion_kardex"],  
                            tipokard+tipo,  
                            imagen,  
                            botones  
                        ] ).draw( false ); 
               counter++; 
                 }else if(resp[i]["tipo_kardex"]=='sancion'){
                   //  alert('sancion');
                  var tipokard='<span class="badge badge-danger">Sancion</span>';
                 dataTableSan.row.add( [
                            counter,
                            resp[i]["fecha_kardex"],  
                            resp[i]["concepto_kardex"],  
                            resp[i]["observacion_kardex"],  
                            tipokard+tipo,  
                            imagen,  
                            botones  
                        ] ).draw( false ); 
               counter++; 
                 }
             } 
        }
     });
}
    
function editar(datos){
    //alert('edit');


    
    $("#modal_felicitacion").modal("show");
    $("#form_felicitacion")[0].reset();
    $("#form_felicitacion").find('.modal-header').css('background','#7abfff');
	 var d = datos.split("*");

     
     var datee = d[1].split(" ");
     console.log(datee);
    $('[name=id_kardex]').val(d[0]);
    console.log(d[1]);
    $('[name=fecha_felicitacion]').val(datee[0]);
    $('[name=concepto]').val(d[2]);
    $('[name=tipo_f]').val(d[5]);
    $('[name=descripcion]').val(d[3]);
    $('[name=archivo_documento_nombre]').val(d[6]);
    
    //0['id_kardex']+'*'+1['fecha_kardex']+'*'+2['concepto_kardex']+ '*'+3['observacion_kardex']+'*'+4['tipo_kardex']+'*'+5['tipo_ev_kardex']+'*'+6['adjunto_kardex']+'*'+7['persona_id']+'*'+8['estado'];
 
}
 
    
function eliminar(idcom){
        //alert('hola'+idcom);
      alertify.confirm('<span style="color:red">ELIMINAR REGISTRO</span>', 'Esta accion eliminara este registro, y sus archivos. ¿Desea eliminar?', function(){ //casi de si
            $.ajax({
                url: rutaproceso,
                type:'POST',
                data: {'accion':'eliminar_kardex',
                   'id_componente':idcom},
                success: function(resp){
                    // alert(resp);
                    switch(resp){
                        case '1': //$("#modal_todos").modal("hide");
                        alertify.success('Se elimino el registro correctamente');
                        listarkardex(id_persona);//id=varable de pagina
                         break;
                        case '2': //$("#modal_todos").modal("hide");
                         alertify.error('No se pudo eliminar '+resp); 
                        break;
                    }
                }
            });
             //alertify.success('Eliminado')  
         }, function(){ 
              alertify.notify('No eliminado', 'custom');
              //alertify.notify('custom message.', 'custom', 20);
             //alertify.error('Cancel');
         
         })
      //ajax
      
      
      
    }
 
</script> 
<style>
    .ajs-message.ajs-custom { color: #31708f;  background-color: #d9edf7;  border-color: #31708f; }
</style>