<?php
 
// Obtiene la cadena csrf 
$csrf = set_csrf();

// Obtiene el modelo paralelo
/*$paralelo = $db->query('SELECT c.nombre_aula,e.`nombre_paralelo`,d.`descripcion`,h.`nombres`,h.`primer_apellido`,i.`nombre_materia` FROM int_aula_paralelo_profesor_materia a,ins_aula_paralelo b ,ins_aula c,ins_nivel_academico d,`ins_paralelo` e,
pro_profesor_materia f,pro_profesor g,sys_persona h,pro_materia i 
WHERE
a.`aula_paralelo_id`=b.`id_aula_paralelo` AND
b.`aula_id`=c.`id_aula` AND
c.`nivel_academico_id`= d.`id_nivel_academico` AND
b.`paralelo_id`= e.`id_paralelo` AND
a.`profesor_materia_id`=f.`id_profesor_materia` AND
f.`profesor_id`= g.`id_profesor`AND
g.`persona_id`=h.`id_persona` AND
f.`materia_id`=i.`id_materia`')->fetch();*/
//$paralelo = $db->from('ins_paralelo')->order_by('nombre_paralelo', 'asc')->fetch();
 
?>
<?php require_once show_template('header-design'); ?>

<link rel="stylesheet" href="assets/themes/concept/assets/vendor/multi-select/css/multi-select.css">
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Horarios </h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Configuración</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Horarios</a></li>
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
                        
                        <div  hidden class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 ">
                         <div class="form-group">
                                <label for="aula_id" class="control-label">Seleccione turno:</label>
                               <select required name="turno" id="turno" onchange="listar_paralelos_tabla();" class="form-control">
									 <option value="" selected="selected">Seleccionar t</option>
										 
								</select> 
                            </div> 
                         </div>
                        <div hidden class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 ">
                            <div class="form-group">
                                <label for="aula_id" class="control-label">Seleccione nivel:</label>
                               <select required name="nivel" id="nivel" onchange="listar_aulas();" class="form-control">
									 <option value="0" selected="selected">Seleccionar nivel</option>
										 
								</select>
            
                            </div>
                             
                        </div>
                        <div  hidden class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 ">
                            <div class="form-group">
                                <label for="aula_id" class="control-label">Seleccione aula:</label>
                               <select required name="aula" id="aula" onchange="listar_paralelos_tabla();" class="form-control">
									 <option value="" selected="selected">Seleccionar aula</option>
										 
								</select>
                            
                            </div>
                             
                        </div>
                        
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 ">
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
										<!--<a href="?/s-profesor-horario/crear" class="dropdown-item">Crear Asignación</a>-->
										<a href="#" onclick="abrir_crear();" class="dropdown-item">Nuevo horario</a>
										
										<?php// endif ?>  
										<?php //if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-horarios-new/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Exportar pdf</a>
										<!--<a href="?/s-horarios-new/imprimirexcel" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Exportar Excel</a>-->
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
      
                   
                   <form class="" id="form-menu" method="post" action="?/s-curso-paralelo/guardar" autocomplete="off">
                    <input type="hidden" name="<?= $csrf; ?>">
                    
                   <table class="table" id="Tabla_paralelos">
                       <thead>
                          <tr> <th>n</th>
                           <th>Hora Inicio</th>
                           <th>Hora Fin</th>
                           <th>Turno</th>
                           <th>Materias</th>
                          <th>Asignar</th></tr>
                       </thead><tbody>
             
                       </tbody>
                   </table>
                
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
     <div class="modal-header">
	  	<input type="hidden" id="curso_eliminar">
        <p>¿Esta seguro de eliminar el horario<span id="texto_curso"></span>?</p>
        <p></p>
      </div>
      <div class="modal-body">
	  	 
        <p>Esta accion ocultara las materias que se encuentre segistrados en el horario, puede perder informacion</p>
        <p></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="btn_eliminar">Eliminar</button>
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
			url: '?/s-horarios-new/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_turno',
				'nivel': nivel
			},
			dataType: 'JSON',
			success: function(resp){
		   // console.log('Listar horassss'+ resp); 
               // alert('ejemplo');
				//alert(resp[0]['id_catalogo_detalle']); 
				//console.log(resp);
                var cont=0;
				//$("#turno").html("");
				$("#turno_sel").html("");
				//$("#turno").append('<option value="">(Todos)Seleccionar</option>');
				$("#turno_sel").append('<option value="">(Todos)Seleccionar</option>');
				for (var i = 0; i < resp.length; i++) {
                   
                        //$("#turno").append('<option  value="' + resp[i]["id_turno"] + '">' + resp[i]["nombre_turno"]+'</option>');
                        $("#turno_sel").append('<option  value="' + resp[i]["id_turno"] + '">' + resp[i]["nombre_turno"]+'</option>');
                    
                   // listar_nivel();
                   // listar_aulas();
				}
				//console.log(resp[0]);
                   

			}
		});
        
	} 
   //listar_nivel();
   /* var contN=0;   
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
		    //console.log('Listar aula'+ resp); 
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
        
	}  */

//LLENADOP DE SELECTS CON AULA Y SIS ID
	//listar_aulas();
	/*function listar_aulas() {
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
		    //console.log('Listar aula'+ resp); 
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
    //listar_paralelos();
	function listar_paralelos() {
	// nivel = 0;//$("#nivel_academico option:selected").val()
	//var turno = $("#turno option:selected").val();//mañána tarde noche
	var nivel = $("#nivel option:selected").val();//primaria  sec
       // alert('ejemplo'+nivel);
        
		$.ajax({
			url: '?/s-curso-paralelo/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_paralelos',
				'nivel': nivel, 
			},
			dataType: 'JSON',
			success: function(resp){
		   // console.log('Listar aula'+ resp); 
               //alert('rest aulaas');
				//alert(resp[0]['id_catalogo_detalle']); 
				//console.log(resp);
				$("#paralelo").html("");
				$("#paralelo").append('<option value="">(Todos)Seleccionar</option>');////' + 0 +
				for (var i = 0; i < resp.length; i++) {
					$("#paralelo").append('<option value="' + resp[i]["id_paralelo"] + '">' + resp[i]["nombre_paralelo"]+'</option>');
				}
				//console.log(resp[0]);
                   

			}
		});
        
	}*/
    
   listar_paralelos_tabla();
function listar_paralelos_tabla() {
    //listar_paralelos();
  //alert('paralelos');
//	var aula = $("#aula option:selected").val();//this
//	var turno = $("#turno option:selected").val();//this
//	var nivel = $("#nivel option:selected").val();//this
    var boton='';
 
		$.ajax({
			url: '?/s-horarios-new/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_horarios',
                
//				'aula': aula,
//                'turno':turno,
//                'nivel':nivel
			},
			dataType: 'JSON',
			success: function(resp){
		    console.log('Listar horarios '+ resp);
  
        var counter=1;
        dataTable.clear().draw();//limpia y actualisa la tabla
for (var i = 0; i < resp.length; i++) {
    console.log('Listar horarios '+ resp[i]["hora_ini"]+ resp[i]["nombre_turno"]);
    
    //100%=20
    //?%=5 cant
    //100*5/20=25 
    var cant =resp[i]["materias_cant"]
    var valorgraf=100*cant/10;
//background: linear-gradient(to right, rgb(255, 255, 255) 10%, rgb(162, 171, 255) 50%, rgba(255, 0, 0, 0) 50%);
    var grafic=resp[i]["materias_cant"]+'<i class="icon-graduation"></i>'+'<div style="    background: linear-gradient(to right,  rgb(9, 218, 45) '+valorgraf+'%, rgba(255, 0, 0, 0) '+valorgraf+'%);   width: 100%;    height: 10px;"></div>'
    
        dataTable.row.add( [
            counter,
            resp[i]["hora_ini"],
            resp[i]["hora_fin"],
            resp[i]["nombre_turno"]+' '+resp[i]["complemento"],
            grafic,
 
            "<a class='btn btn-outline-primary btn-xs' ONCLICK='abrir_editar("+'"'+resp[i]["id_horario_dia"]+'*'+resp[i]["hora_ini"]+'*'+resp[i]["hora_fin"]+'*'+resp[i]["turno_id"]+'*'+resp[i]["complemento"]+'"'+")' title='Editar'> <i class='icon-pencil'></i></a><a class='btn btn-outline-danger btn-xs' onclick='abrir_eliminar("+resp[i]["id_horario_dia"] +")' title='Eliminar'><i class='icon-trash'></i></a>"
        ] ).draw( false );
 //para actualisar id_horario_profesor_materia id_aula_paralelo id_docente_materia horarios
         counter++;
  
 
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
		 
				
			}
		});
      
    });
        
    
//modal ASIGNACION
<?php //if ($permiso_crear) : ?>
function abrir_editar(cont){
    
    //alert('aqui');
	$("#modal_gestion").modal("show");
	$("#form_gestion")[0].reset();
	//$("#form_gestion").reset();
	$("#titulo_gestion").text("Editar ");
    
	var d = cont.split("*");
    //0 id horario dia
    //1 hora ini
    //2 hora fin
	id_aula_paralelo=d[0];
    $("#tipoAc").val('edit');
    $("#horario_id").val(d[0]);
    //$("#id_docente").val(d[1]);
    //$("#aula_paralelo_id").val(d[2]);
    $("#hora_inicio").val(d[1]);
    $("#hora_fin").val(d[2]);
    //alert(d[2]+'-'+d[2]);
    $("#turno_sel").val(d[3]);//turno
    //descanso 'complemento'=>$descanso
      // $("#descanso").prop(':checked', true);
 
    
    if(d[4]=='descanso'){
       //$("#descanso").attr('checked',true);
       $("#descanso").prop('checked', true);
         //alert('aqui true');
       }else{
         //alert('aqui false');
           $("#descanso").attr('checked',false);  
       }
    
	$("#btn_nuevo").hide();
	$("#btn_modificar").show();
}
    
    function abrir_crear(){
	$("#modal_gestion").modal("show");
	$("#form_gestion")[0].reset(); 
	$("#titulo_gestion").text("Nuevo ");
     
    $("#tipoAc").val('new'); 
	$("#btn_nuevo").show();
	$("#btn_modificar").hide();
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
	 var id_horario = $("#curso_eliminar").val();
    //alert(id_horario);
	$.ajax({
		url: '?/s-horarios-new/eliminar',
		type:'POST',
		data: {'id_horario':id_horario},
		success: function(resp){
			//alert(resp)
			switch(resp){
				case '1': $("#modal_eliminar").modal("hide");
							//dataTable.ajax.reload();
							alertify.success('Se elimino el horario');
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
          url: "?/s-horario-new/guardar",
          data: datos,
          success: function (resp) {
             // alert(resp);
            cont = 0;
            switch(resp){
              case '1': //dataTable.ajax.reload();
                        $("#modal_curso").modal("hide");
                        alertify.success('Se registro el horario correctamente');
                        listar_paralelos_tabla();
                        break;
              case '2': //dataTable.ajax.reload();
                        $("#modal_curso").modal("hide");
                        alertify.success('Se editó el horario correctamente');
                        listar_paralelos_tabla();
                        break;
            }
            //pruebaa();
          }
          
      });
      
  }
})
        </script>

