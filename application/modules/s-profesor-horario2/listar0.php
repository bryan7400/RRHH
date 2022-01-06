<?php
 
// Obtiene la cadena csrf 
$csrf = set_csrf();

 
 
?>
<?php require_once show_template('header-design'); ?>

<link rel="stylesheet" href="assets/themes/concept/assets/vendor/multi-select/css/multi-select.css">
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Asignaciones curso/horario </h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Asignaciones</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Curso/Horario</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Vista Asignación</li>
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
    <!-- validation form -->
    <!-- ============================================================== -->
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            
          <!--  <div class="card-header">
               
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                        <div class="text-label hidden-xs">Seleccionar acción:</div> 
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
						<div class="btn-group">
								<div class="input-group">
								<div class="input-group-append be-addon">
									<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
									<div class="dropdown-menu">
										<a class="dropdown-item">Seleccionar acción</a>
										<?php //if ($permiso_crear) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-curso-paralelo-profesor-materia/crear" class="dropdown-item">Crear Asignación</a>
										<?php// endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-curso-paralelo/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Tutor</a>
										<?php endif ?>
									</div>
								</div>
							</div>
						</div> 
					</div>
           
                </div>
            </div>-->

            <div class="card-body">
              
               <!--<h3>froma 1</h3>
                <form class="" id="form-menu" method="post" action="?/s-curso-paralelo/guardar" autocomplete="off">
                    <input type="hidden" name="<?= $csrf; ?>">
 
                                            <?php foreach ($paralelo as $elemento) : ?>
                                        <option value="<?= $elemento['id_paralelo']; ?>"><?= escape($elemento['nombre_paralelo']); ?></option>
                                        <?php endforeach ?>
              
              <!--  <h3>Asignacion aula-paralelo:</h3>-->
      
               <!-- <form class="" id="form-menu" method="post" action="?/s-curso-paralelo/guardar" autocomplete="off">-->
                    <input type="hidden" name="<?= $csrf; ?>">
                    <form action="" class="modal_nuevo_paralelo">
                    <div class="row">
                        
                           <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 ">
                         <div class="form-group">
                            <label for="aula_id" class="control-label">Seleccione Dia:</label>
                            <select required name="dia" id="dia" class="form-control" onchange="listar_paralelos_tabla();" >
                                <option value="">(todos) Semana</option>
                                <option value="1">Lunes</option>
                                <option value="2">Martes</option>
                                <option value="3">Miercoles</option>
                                <option value="4">Jueves</option>
                                <option value="5">Viernes</option>
                                <option value="6">Sabado</option>
                            </select>
                           
                            </div>
                          
                             
                        </div>  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 ">
                         <div class="form-group">
                                <label for="aula_id" class="control-label">Seleccione turno:</label>
                               <select required name="turno" id="turno" onchange="listar_paralelos_tabla();" class="form-control">
									 <option value="" selected="selected">Seleccionar t</option>
										 
								</select>
                           
                            </div>
                          
                             
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 ">
                            <div class="form-group">
                                <label for="aula_id" class="control-label">Seleccione nivel:</label>
                               <select required name="nivel" id="nivel" onchange="listar_aulas();" class="form-control">
									 <option value="0" selected="selected">Seleccionar nivel</option>
										 
								</select>
            
                            </div>
                             
                        </div>
                           <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 ">
                            <div class="form-group">
                                <label for="aula_id" class="control-label">Seleccione aula:</label>
                               <select required name="aula" id="aula" onchange="listar_paralelos_tabla();" class="form-control">
									 <option value="" selected="selected">Seleccionar aula</option>
										 
								</select>
                            
                            </div>
                             
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 ">
                            <div class="form-group">
                                <label for="paralelo" class="control-label">Seleccione paralelo:</label>
                               <select required name="paralelo_listar" id="paralelo_listar" onchange="listar_paralelos_tabla();" class="form-control">
									 <option value="" selected="selected">Seleccionar un aula con paralelos</option>
										 
								</select>
                            
                            </div>
                             
                        </div>
                           
                           
                           
                           <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 ">
                            <div class="form-group">
                                <label for="aula_id" class="control-label">Seleccione Accion:</label>
                             <div class="btn-group">
								<div class="input-group">
								<div class="input-group-append be-addon">
									<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
									<div class="dropdown-menu">
										<a class="dropdown-item">Seleccionar acción</a>
										<?php //if ($permiso_crear) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-profesor-horario/crear" class="dropdown-item">Crear Asignación</a>
										<?php// endif ?>  
										<?php //if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-profesor-horario/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Exportar pdf</a>
										<a href="?/s-profesor-horario/imprimirexcel" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Exportar Excel</a>
										<?php //endif ?>
									</div>
								</div>
							</div>
						</div> 
                            
                            </div>
                             
                        </div>
                         
                       
                       <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                           
                               <div class="form-group">
                   
                            </div>
                        </div> 
                        
                    </div>
                    </form>
                  
      <!-- data-toggle="modal" data-target="#myModal" style="display:block"-->
                   
                   <form class="" id="form-menu" method="post" action="?/s-curso-paralelo/guardar" autocomplete="off">
                    <input type="hidden" name="<?= $csrf; ?>">
                    
                   <table class="table" id="Tabla_paralelos">
                       <thead>
                          <tr> <th>n</th>
                           <th>Hora Inicio</th>
                           <th>Hora Fin</th>
                           <th>Turno</th>
                           <th>Curso</th> 
                           <th>Nivel</th>
                           <th>Docente Nombre</th><th>Materia</th><th>Asignar</th></tr>
                       </thead><tbody>
                        <!--   <tr><td>1 </td>
                           <td>1 </td>
                           <td>A</td>
                           <td><input type="text" class="form-control"></td></tr>  -->
                       </tbody>
                   </table>
                    <!-- <div class="row">
                    	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                        	<div class="form-group text-center">
								<button type="submit" class="btn btn-primary">
									<i class="icon-check"></i>
									<span>Registrar</span>
								</button>
								<button type="reset" class="btn btn-light">
									<i class="icon-arrow-left-circle"></i>
									<span>Restablecer</span>
								</button>
							</div>
						</div>
                    </div>-->
                </form> 
             
                
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end validation form -->
    <!-- ============================================================== -->
</div>
 
       <!-- Button to Open the Modal -->
<!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
  Open modal
</button>-->
<!-- The Modal -->
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Asigne un paralelo</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
    <form action="" class="agregar_paralelo">
      <!-- Modal body -->
      <div class="modal-body">
            <label for="aula_id" class="control-label">Paralelo: </label>
            <select required name="paralelo" id="paralelo"   class="form-control">
            <option value="" selected="selected">Seleccionar paralelo</option>
            </select>
            <label for="Iparacidad" class="control-label">Capacidad de aula: </label>
            <input  required type="number" class="form-control" value="" id="Iparacidad" placeholder="Ingrese capacidad de aula"/>
                        
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success" id="btnNuevo" >
                         <i class="icon-plus"></i>
                         <span>Agrerar</span>
        </button><!--onclick="agregar_paralelo()"-->

    </div>
    </form>
  </div>
</div>
    
</div>
 
<!--modal para eliminar-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_eliminar">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
	  	<input type="hidden" id="curso_eliminar">
        <p>¿Esta seguro de eliminar el curso <span id="texto_curso"></span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn_eliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<form id="form_curso">
<div class="modal fade" id="modal_curso" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="titulo_curso"></span> Curso </h5>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
			</div>
			<div class="modal-body">
				<div class="">
					<label class="control-label">Capacidad del aula: </label>
					<div class="control-group">
            <input type="hidden" name="<?= $csrf; ?>">
            <input id="id_aula" name="id_asignacion" type="hidden" class="form-control">						
						<input id="capacidad_aula" name="capacidad_aula" type="number" class="form-control" placeholder="Ej: 35">
					</div>
				</div>
				 
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
        <!--<button type="submit" class="btn btn-primary pull-right" id="btn_nuevo">Guardar</button>-->
				<button type="submit" class="btn btn-primary pull-right" id="btn_editar">Editar</button>
			</div>
		</div>
	</div>
</div>
</form>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/js/jquery.validate.js"></script>
<script src="application/modules/generador-menus/generador-menu.js"></script>  
<script src="assets/themes/concept/assets/vendor/multi-select/js/jquery.multi-select.js"></script>
<style>
.submenu  a.nav-link {
    /*background: #d4d5e0;
    background: linear-gradient(to right, #24274a, #283d86,#24274a);*/
    background: linear-gradient(to right, #0e0c28, #283d86,#0e0c28);
}
</style>


<?php 
//	if($permiso_modificar){
		require_once ("editar.php");
//	}
	/*if($permiso_ver){
		require_once ("ver.php");
	}*/
?>
<script>
 var dataTable = $('#Tabla_paralelos').DataTable({
  language: dataTableTraduccion,
  searching: true,
  paging:true,
  stateSave:true,
  "lengthChange": true,
  "responsive": true
  });


    listar_turno();
    var contT=0;   
	function listar_turno() {
	 nivel = 0;//$("#nivel_academico option:selected").val()
		$.ajax({
			url: '?/s-curso-paralelo-profesor-materia/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_turno',
				'nivel': nivel
			},
			dataType: 'JSON',
			success: function(resp){
		    console.log('Listar aula'+ resp); 
               // alert('ejemplo');
				//alert(resp[0]['id_catalogo_detalle']); 
				//console.log(resp);
                var cont=0;
				$("#turno").html("");
				$("#turno").append('<option value="">(Todos)Seleccionar</option>');
				for (var i = 0; i < resp.length; i++) {
                   // if(cont<1){
					//$("#turno").append('<option selected value="' + resp[i]["id_turno"] + '">' + resp[i]["nombre_turno"]+'</option>');
                    //}else{
                        $("#turno").append('<option  value="' + resp[i]["id_turno"] + '">' + resp[i]["nombre_turno"]+'</option>');
                   //}
                   // cont++;
                    listar_nivel();
                    listar_aulas();
				}
				//console.log(resp[0]);
                   

			}
		});
        
	} 
   //listar_nivel();
    var contN=0;   
	function listar_nivel() {
	 //turno = $("#turno option:selected").val()
     //alert(turno);
		$.ajax({
			url: '?/s-curso-paralelo/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_nivel'//,
				//'turno': turno
			},
			dataType: 'JSON',
			success: function(resp){
		    console.log('Listar aula'+ resp); 
               // alert('ejemplo');
				//alert(resp[0]['id_catalogo_detalle']); 
				//console.log(resp);
                
				$("#nivel").html("");
				$("#nivel").append('<option value="' + 0 + '">Seleccionar nivel</option>');
				for (var i = 0; i < resp.length; i++) {
                   // if(contN<1){
					//$("#nivel").append('<option selected value="' + resp[i]["id_nivel_academico"] + '">' + resp[i]["nombre_nivel"]+'</option>');
                   // }else{
                        $("#nivel").append('<option  value="' + resp[i]["id_nivel_academico"] + '">' + resp[i]["nombre_nivel"]+'</option>');
                   // }contT++;
				}
				//console.log(resp[0]);
                   

			}
		});
        
	}  

//LLENADOP DE SELECTS CON AULA Y SIS ID
	listar_aulas();
	function listar_aulas() {
	// nivel = 0;//$("#nivel_academico option:selected").val()
	//var turno = $("#turno option:selected").val();//mañána tarde noche
	var nivel = $("#nivel option:selected").val();//primaria  sec
       // alert('ejemplo'+nivel);
        
		$.ajax({
			url: '?/s-curso-paralelo/procesos',
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
                   

			}
		});
        
	}
   listar_paralelos();
	function listar_paralelos() {
	// nivel = 0;//$("#nivel_academico option:selected").val()
	//var turno = $("#turno option:selected").val();//mañána tarde noche
	var aula = $("#aula option:selected").val();//primaria  sec
      // alert('para-'+aula+'-');
        
		$.ajax({
			url: '?/s-horario-semana/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_paralelos',
				'aula': aula
			},
			dataType: 'JSON',
			success: function(resp){
		    //console.log('Listar aula'+ resp); 
              //alert('rest aulaas');
				 
				$("#paralelo_listar").html("");
				$("#paralelo_listar").append('<option value="0">(Todos)Seleccionar</option>');////' + 0 +
				for (var i = 0; i < resp.length; i++) {
					$("#paralelo_listar").append('<option value="' + resp[i]["id_paralelo"] + '">' + resp[i]["nombre_paralelo"] +'</option>');
				}
				 
                   

			}
		});
        
       //listar_paralelos_tabla();
        
	}
   listar_paralelos_tabla();
	function listar_paralelos_tabla() {
    //listar_paralelos();
	var aula = $("#aula option:selected").val();//this
	var turno = $("#turno option:selected").val();//this
	var nivel = $("#nivel option:selected").val();//this
    var paralelo = $("#paralelo_listar  option:selected").val();
    var dia = $("#dia  option:selected").val();
   // alert('funcion lisrtar tabla dia:'+dia);
    var boton='';
 
		$.ajax({
			url: '?/s-profesor-horario/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_paralelos_asignados',
                
				'aula': aula,
                'turno':turno,
                'nivel':nivel,
                'paralelo':paralelo,
                'dia':dia
			},
			dataType: 'JSON',
			success: function(resp){
		    console.log('Listar paralelos '+ resp);
  
        var counter=1;
        dataTable.clear().draw();//limpia y actualisa la tabla
for (var i = 0; i < resp.length; i++) {
    var contenido = resp[i]['id_aula_paralelo'] + "*" + resp[i]['capacidad'];//;+ "*" + row['descripcion']+ "*" + row['nombre_nivel'];
                    
/*nombre_aula
nombre_paralelo
descripcion
nombres
primer_apellido
nombre_materia*/
    var nombreD='';
if(resp[i]["codigo_profesor"]=='P-0'){
   nombreD='<span style="color:red">'+resp[i]["nombres"]+' '+resp[i]["primer_apellido"]+'</span>';
   }else{
   nombreD=resp[i]["nombres"]+' '+resp[i]["primer_apellido"];
       
   }
        dataTable.row.add( [
            counter,
            resp[i]["hora_ini"]+' - '+resp[i]["hora_fin"],
            resp[i]["nombre_dia"],
            resp[i]["nombre_turno"],
            resp[i]["nombre_aula"]+' '+
            resp[i]["nombre_paralelo"],
            resp[i]["nombre_nivel"],
            nombreD,
            resp[i]["nombre_materia"],
            "<a class='btn btn-success btn-xs' ONCLICK='abrir_editar("+'"'+resp[i]["id_horario_profesor_materia"]+'*'+resp[i]["profesor_materia_id"]+'*'+resp[i]["curso_paralelo_id"]+'*'+resp[i]["horario_dia_id"]+'"'+")'><span class='icon-pencil'></span>Editar</a><a class='btn btn-danger btn-xs' onclick='abrir_eliminar("+resp[i]["id_horario_profesor_materia"] +")'><span class='icon-trash'></span>Editar</a>"
        ] ).draw( false );
 //para actualisar id_horario_profesor_materia id_aula_paralelo id_docente_materia horarios
         counter++;
  
			//		$("#Tabla_paralelos").find('tbody').append('<tr><td>1</td>            <td>' + resp[i]["turno"] + '</td> <td>' + resp[i]["nombre_aula"] +' '+resp[i]["nombre_nivel"]+'</td>                                <td>' + resp[i]["descripcion"] + '</td> <td><input disabled type="text" class="form-control" value="' + resp[i]["capacidad"] + '"/></td><td> <a href="#" class="btn btn-warning btn-xs" style="color:white" onclick="abrir_editar('+"'"+contenido+"'"+')"><span class="icon-note"></span></a><a href='+"'#' class='btn btn-danger btn-xs' onclick='abrir_eliminar("+resp[i]["id_aula_paralelo"] +")'><span class='icon-trash'></span></a>"+'</td></tr>');//
				}
                
                
                if(aula>0){
                   
                  //  $('#btnNuevo').css('display','block'); nombre_nivel

                }else{
                  //  $('#btnNuevo').css('display','none');
                    
                }
				 
			}
		});
	} 
    
 
$("form.modal_nuevo_paralelo").submit(function(e){
     e.preventDefault();
    $("#myModal").modal("show");
    
});

  $("form.agregar_paralelo").submit(function(e){

    //function agregar_paralelo(){
        e.preventDefault();
        paralelo = $("#paralelo option:selected").val();
        turno = $("#turno option:selected").val();
        aula = $("#aula option:selected").val();
        Iparacidad = $("#Iparacidad").val();
        
        //alert(Iparacidad);
        $.ajax({
			url: '?/s-curso-paralelo/procesos',
			type: 'POST',
			data: {
				'boton': 'crear_curso_paralelo',
				'aula': aula,
                'paralelo':paralelo,
                'turno':turno,
                'Iparacidad':Iparacidad
			},
			 
			success: function(resp){
                if(resp){
                    listar_paralelos_tabla();
                    $("#myModal").modal("hide");
                   }else{
                    alert('RESP:'+ resp);
                   
                   }
		    console.log('RESP:'+ resp);
				//$("#Tabla_paralelos").html("");
				
			}
		});
        
        
        //guardar a la BD
        
    //}
    });
        
    
//modal ASIGNACION
<?php //if ($permiso_crear) : ?>
function abrir_editar(cont){
	$("#modal_gestion").modal("show");
	$("#form_gestion")[0].reset();
	//$("#form_gestion").reset();
	$("#titulo_gestion").text("Editar ");
    
	var d = cont.split("*");
    //$("#titulo_gestion").text("Asignar ");
	id_aula_paralelo=d[0];
    $("#tipoAc").val('edit');
    $("#aula_par_prof_mat_id").val(d[0]);
    $("#id_docente").val(d[1]);
    $("#aula_paralelo_id").val(d[2]);
    $("#horario").val(d[3]);
    //$("#hora_fin").val(d[4]);
	$("#btn_nuevo").hide();
	$("#btn_modificar").show();
}
<?php //endif ?>
 
   function abrir_eliminar(contenido){
	$("#modal_eliminar").modal("show");
	//var d = contenido.split("*");
	//$("#curso_eliminar").val(d[0]);
	$("#curso_eliminar").val(contenido);
	//$("#texto_curso").text(d[1]);
      
}
    
 
    
    $("#btn_eliminar").on('click', function(){
	//alert($("#gestion_eliminar").val())
     //   alert('ejemplo');
	 var id_aula_paralelo = $("#curso_eliminar").val();
  // alert(id_aula_paralelo);
	$.ajax({
		url: '?/s-profesor-horario/eliminar',
		type:'POST',
		data: {'id_aula_paralelo':id_aula_paralelo},
		success: function(resp){
			//alert(resp)
			switch(resp){
				case '1': $("#modal_eliminar").modal("hide");
							//dataTable.ajax.reload();
							alertify.success('Se elimino el curso correctamente');
                            listar_paralelos_tabla(); 
                    break;
				case '2': $("#modal_eliminar").modal("hide");
							alertify.error('No se pudo eliminar '+resp);
							break;
			}
		}
	})
})
    
$("#form_curso").validate({
  rules: {
      nombre_aula: {required: true},
      nombre_nivel: {required: true}
      //id_gestion: {required: true}
  },
  errorClass: "help-inline",
  errorElement: "span",
  highlight: highlight,
  unhighlight: unhighlight,
  messages: {
      nombre_aula: "Debe ingresar nombre de curso.",
      nombre_nivel: "Debe seleccionar un nivel académico"
  },
  //una ves validado guardamos los datos en la DB
  submitHandler: function(form){
      //alert();
      var datos = $("#form_curso").serialize();
        //  alert('envio'+datos);
      $.ajax({
          type: 'POST',
          url: "?/s-curso-paralelo/guardar",
          data: datos,
          success: function (resp) {
             // alert(resp);
            cont = 0;
            switch(resp){
              case '1': //dataTable.ajax.reload();
                        $("#modal_curso").modal("hide");
                        alertify.success('Se registro el curso correctamente');
                        listar_paralelos_tabla();
                        break;
              case '2': //dataTable.ajax.reload();
                        $("#modal_curso").modal("hide");
                        alertify.success('Se editó el curso correctamente');
                        listar_paralelos_tabla();
                        break;
            }
            //pruebaa();
          }
          
      });
      
  }
})
        </script>

