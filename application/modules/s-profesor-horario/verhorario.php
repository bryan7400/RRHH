<?php
 
// Obtiene la cadena csrf 
$csrf = set_csrf();

//$turno=isset($_params[0])?$_params[0]:0;
//$nivel=isset($_params[1])?$_params[1]:0;
$aula=isset($_params[0])?$_params[0]:0; 
$turno=isset($_params[1])?$_params[1]:0; 
//$paralelo=isset($_params[0])?$_params[0]:0;

// Obtiene datos del paralelo
$datosparalelo = $db->query("SELECT ins_turno.nombre_turno,b.id_aula_paralelo,c.nombre_aula,e.nombre_paralelo,d.* FROM ins_aula_paralelo b ,ins_aula c,ins_nivel_academico d,ins_paralelo e,ins_turno
    WHERE 
    ins_turno.id_turno=b.turno_id AND
    b.aula_id=c.id_aula AND
    c.nivel_academico_id= d.id_nivel_academico AND
    b.estado='A' and
    b.paralelo_id= e.id_paralelo AND
    b.id_aula_paralelo=".$aula)->fetch(); 
?>
<input type="hidden" value="<?=$aula?>" id="id_aula_rec">
<input type="hidden" value="<?=$turno?>" id="turno_id">
<?php require_once show_template('header-design'); ?>

<link rel="stylesheet" href="assets/themes/concept/assets/vendor/multi-select/css/multi-select.css">

<style>
    td{
position: relative;
        
    }
   #Tabla_paralelos button.btn.btn-info.btn-xs{
        position: absolute;
    top: 0;
    right: 0; 
    }
 #Tabla_paralelos button.btn.btn-danger.btn-xs{
        position: absolute;
    top: 2em;
    right: 0; 
    }


</style>


<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title"> <?php foreach ($datosparalelo as $elemento):
      echo $elemento['nombre_aula'].' '.escape($elemento['nombre_paralelo']); 
      ?></h2>
        <p><?=escape($elemento['nombre_turno']).'-'.escape($elemento['nombre_nivel'])?></p>
        <input type="hidden" value="<?=$elemento['id_nivel_academico']?>" id="nivel_id">
        <?php endforeach ?>
            <h2 class="pageheader-title"> HORARIO POR CURSOS</h2>
         <p>Visualiza horario de un curso</p>
      
            <p class="pageheader-text">hola</p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Inscripcion</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Estudiantes Inscritos</a></li>
                        <!--<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Curso/Materia</a></li>-->
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
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 ">
                            <div class="form-group">
                            <label for="aula_id" class="control-label">Buscar por:</label>
                            <div class="row">
                            <a class="col-6 form-control inline btn btn-def" href="?/s-profesor-horario/listar" class="dropdown-item">Listado por cursos</a> 
                             <a class="col-6 form-control btn btn-def" href="?/s-profesor-horario/verhorariodocente">Por Docentes</a>
                            <a class="col-6 form-control inline btn btn-success" href="#" class="dropdown-item">Por cursos</a>
                             </div>
                            </div>
                             
                        </div>
                         
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 form-group row pl-4">
                             <div class="p-3 form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="checkboxcolor" onclick="ver_tabla_horario();">
                            <label class="form-check-label" for="checkboxcolor">Color</label>
                          </div>
                           <!--<div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">Comprimido</label>
                          </div>--><div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="check_cod_materia" onclick="ver_tabla_horario();">
                            <label class="form-check-label" for="check_cod_materia">Solo codigos materia</label>
                          </div> 
                             
                        </div>
                       
                        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="form-group">
                                <label for="aula_id" class="control-label">Seleccione Accion:</label>
                             <div class="btn-group">
								<div class="input-group">
								<div class="input-group-append be-addon">
									<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
									<div class="dropdown-menu">
										<!--<a class="dropdown-item">Seleccionar acción</a>-->
										<?php //if ($permiso_crear) : ?>
							 <div class=" col-sm-12 col-12 ">
                           
                               <div class="form-group">
                                
                                <button type="button" class="btn btn-success" id="btnNuevo" onclick="abrir_crear(<?=$aula?>)" title="Nuevo docente materia y horarios"><span>Agregar nuevo</span> </button>

                                    </div>
                                </div> 
										<div class="dropdown-divider"></div>
										<a href="?/s-profesor-horario/listar" class="dropdown-item">Ver por lista de cursos</a><a href="?/s-profesor-horario/verhorariodocente" class="dropdown-item">Ver horario por docente</a>
										<?php// endif ?>  
										<?php //if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<!--<a href="?/s-curso-paralelo-profesor-materia/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Asignaciones</a>-->
										<a href="#" class="dropdown-item"  onclick="impresionpdf()"><span class="glyphicon glyphicon-print"></span>Exportar pdf</a>
										<a href="#" class="dropdown-item"  onclick="impresionpdfmini()"><span class="glyphicon glyphicon-print"></span>Exportar mini pdf </a>
										<a href="#" class="dropdown-item"  onclick="impresionInteligente2()"><span class="glyphicon glyphicon-print"></span> Exportar Excel</a>
										<?php //endif ?>
									</div>
								</div>
							</div>
						</div> 
                       <!-- <label for="">Color</label>
                        <input type="checkbox" ><label for="">Comprimido</label>
                        <input type="checkbox" ><label for="">Solo codigos</label>
                        <input type="checkbox" >-->
                       
                      
                        </div>
                             
                        </div>
                      
                    </div>
                    </form>
                    <!--FILTROS DE TURNO AULA -->
                    <div class="row">
                            
                           <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 ">
                         <div class="form-group">
                                <label for="aula_id" class="control-label">Seleccione turno:</label>
                               <select required name="turno" id="turno" onchange="listar_nivel();" class="form-control">
									 <option value="" selected="selected">Seleccionar t</option>
										 
								</select>
                           
                            </div>
                          
                             
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 ">
                            <div class="form-group">
                                <label for="aula_id" class="control-label">Seleccione nivel:</label>
                               <select required name="nivel" id="nivel" onchange="listar_aulas();" class="form-control">
									 <option value="0" selected="selected">Seleccionar nivel</option>
										 
								</select>
                   
                            </div>
                             
                        </div>
                           <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 ">
                            <div class="form-group">
                                <label for="aula_id" class="control-label">Seleccione aula:</label>
                               <select required name="aula" id="aula" onchange="listar_paralelos();"  class="form-control">
									 <option value="" selected="selected">Seleccionar aula</option>
										 
								</select>
                            
                            </div>
                             
                        </div>
                          <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 ">
                           <div class="form-group">
                                <label for="paralelo" class="control-label">Seleccione paralelo:<span id="paralelo_img">ok</span></label>
                               <select required name="paralelo_listar" id="paralelo_listar" onchange="funcionverhorario();" class="form-control">
									 <option value="" selected="selected">Esperando...</option>
										 
								</select>
                            
                            </div>
                             
                        </div>
                         
                       
                  
                        
                    </div>
                    
                   <table class="table table-bordered" id="Tabla_paralelos">
                     <!--  <thead>
                          <tr> <th>n</th>
                           
                           <th>Nombre Completo</th>
                           <th>Documento</th>
                           <th>Turno</th>
                           <th>Nivel</th>
                           <th>Curso</th>
                           <th>Paralelo</th>
                           <th>Tipo Estudiante</th>
                           <th>Tutor</th>
                           <th>Opciones</th>
                           </tr>
                       </thead><tbody>
             
                       </tbody>-->
                        
                      <thead>
                          <tr> 
                            <th>n</th>
                            <th>Hrs</th>
                           <th style="display:none" >id_horario_dia</th>
                           <th >Lunes</th>
                           <th>Martes</th>
                           <th>Miercoles</th>
                           <th>Jueves</th>
                           <th>Viernes</th>
                           <th>Sabado</th>
                         
                              
                           <!--<th>Asignar</th>-->
                           </tr>
                       </thead>
                        <tbody>
                          <!-- <tr>
                           <td>-</td>
                           <td>-</td>
                           <td class="td_id_horario_dia">-</td>
                           <td>-</td>
                           <td>-</td>
                           <td>-</td>
                           <td>-</td>
                           <td>-</td>
                            
                           </tr>-->
                
                             
                       </tbody>
                   </table>
 
               <!-- </form> -->
             
                
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
 
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/js/jquery.validate.js"></script>
<script src="application/modules/generador-menus/generador-menu.js"></script>    
<script src="assets/themes/concept/assets/vendor/multi-select/js/jquery.multi-select.js"></script>
<?php 
//	if($permiso_modificar){
		require_once ("editar.php");
//	}
	/*if($permiso_ver){
		require_once ("ver.php");
	}*/
?>
<style>
    
    .miniletra{
        font-size: 0.5em;;
    } 
    .medletra{
        font-size: 0.7em;;
    } 
.submenu  a.nav-link {
    /*background: #d4d5e0;
    background: linear-gradient(to right, #24274a, #283d86,#24274a);*/
    background: linear-gradient(to right, #0e0c28, #283d86,#0e0c28);
}
</style>
<script>
    'use strict';
   // $("#turno").selectize();
/*function abrir_crear(x){
alertify.success('holaa'+x);
    
}*/
/* var dataTable = $('#Tabla_paralelos').DataTable({
  language: dataTableTraduccion,
  searching: true,
  paging:true,
  stateSave:true,
  "lengthChange": true,
  "responsive": true
  });*/
    $('#turno').focus();

 
ver_tabla_horario();
    
function ver_tabla_horario() {
    //var aula = $("#aula option:selected").val();//this
    var aula = $("#id_aula_rec").val();//this
	var turno =$("#turno_id").val();//$("#turno option:selected").val();//this
	var nivel = 0;//$("#nivel option:selected").val();//this
	var paralelo = 0;//$("#paralelo_listar option:selected").val();//this
    //varaiables de uso 
/*    var respuesta=''; 
    var horas =  new Array(); 
    var horas_fin =  new Array();
     horas.push(response[j]["hora_ini"]);
            horas_fin.push(response[j]["hora_fin"]);
    var j=0;
     var cc=0; */
    //enviar la busqueda solo de horarios q existan
    $('#Tabla_paralelos').find('tbody').html('');
    console.log('1-inicio de funcion-');
    $.ajax({
			url: '?/s-profesor-horario/procesos',
			type: 'POST',
			data: {
                'boton': 'listar_horarios_new',
                'aula': aula,
                'turno':turno,
                'nivel':nivel,
                'paralelo':paralelo 
			},
			dataType: 'JSON',
    })
    .done(function(response) {
        //alert(response);
        //respuesta = response;
        //console.log('2-done1-');$('#Tabla_paralelos').find('tbody').html(' ');
        var j;
        for (j = 0; j < response.length; j++) {
       // console.log('responseeeeeeeeeeeeeeeeeee');
    //llenar la tabla vacia
    //$('#Tabla_paralelos').find('tbody').append('<tr><td></td><td></td><td class="td_id_horario_dia"></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>');
    $('#Tabla_paralelos').find('tbody').append('<tr><td></td><td></td><td class="td_id_horario_dia"  style="display:none" ></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>');
   //llenamos las horas en la tabla
    $('#Tabla_paralelos').find('tbody').find("tr:eq("+j+")").find("td:eq(1)").text(response[j]["hora_ini"]+' - '+response[j]["hora_fin"]);
            
      //llenar id horario dia - columna oculta
    $('#Tabla_paralelos').find('tbody').find("tr:eq("+j+")").find("td:eq(2)").text(response[j]["id_horario_dia"]);
            
   if(response[j]["complemento"]=='descanso'){
           $('#Tabla_paralelos').find('tbody').find("tr:eq("+j+")").find("td:eq(3)").text('--DESCANSO--');
           //poner colores y unir tabla
           $('#Tabla_paralelos').find('tbody').find("tr:eq("+j+")").css('background','#00d0ffa1').find("td:eq(3)").attr('colspan','6').nextAll().remove();
                       
    }
    }
        //llenar materias
          $.ajax({
    url: '?/s-profesor-horario/procesos',
    type: 'POST',
    data: {
        'boton': 'listar_cursos_horario_new',
        'aula': aula,
        'turno':turno,
        'nivel':nivel,
        'paralelo':paralelo

    },
    dataType: 'JSON',
    beforeSend: function() {


    }, 
    success: function(data){
        
      //var llenardatos='';
        //console.log('2-Succes inicio for-'); 
         var lunes=''; var martes=''; var miercoles=''; var jueves='';var viernes='';var sabado='';var horaini='';//var horaini='<td>'+respuesta[j]["hora_ini"]+' - '+respuesta[j]["hora_fin"]+'</td>';
        //var horario_id=0;
        //var horario_id2=0;
       // $('#Tabla_paralelos').find('tbody').html('');
       // var strhora='';
        
       //recorrer el data con valores de un horario
        for (var i = 0; i < data.length; i++) {
           // console.log(':::::::::::::::::::-'+data[i]["cod_materia"]+'-::::::::::::::::::::::::::::::::::::');
            //console.log(data);
            //horario_id=data[i]["id_horario_dia"];
 
            // var doc_curso=''; 
            //var btnEdit=''; 
            //var btnDelete='';
              var btnEdit="<button class='btn btn-info btn-xs' ONCLICK='abrir_editar("+'"'+data[i]["id_horario_profesor_materia"]+'*'+data[i]["aula_paralelo_id"]+'*'+data[i]["asignacion_id"]+'*'+data[i]["materia_id"]+'*'+data[i]["horario_dia_id"]+'*'+data[i]["dia_semana_id"]+'*'+data[i]["turno_id"]+'*'+data[i]["id_nivel_academico"]+'*'+data[i]["id_aula_paralelo_asignacion_materia"]+'"'+")'><span class='icon-pencil'></span></button>";
            
              var btnDelete="<button class='btn btn-danger btn-xs' ONCLICK='abrir_eliminar("+'"'+data[i]["id_horario_profesor_materia"]+'"'+")'><span class='icon-trash'></span></button>";
            
            
           var  datmateria='';
           var  datdocente='';
            //materiacod='transparent';
                if( $('#check_cod_materia').prop('checked') ) {
                    datmateria=data[i]["cod_materia"]; 
                    datdocente='<b class="miniletra">'+data[i]["iniciales"]+'</b>'; 
                 }else{
                   datmateria=data[i]["nombre_materia"];
                    datdocente='<b class="medletra">'+data[i]["nombres_doc"]+'</b>';
                   //datdocente='<b class="medletra">'+'SIN ASIGNACION DE DOCENTE'+'</b>';
                     
                 }
            
           if(data[i]["nombres_doc"]==null){
               var datostd='<div style="color:red;">'+datmateria+'<br>VACIO</div>';
           }else{
                var datostd=datmateria+'<br>'+datdocente; 
           }
            
            
            console.log(i+'-data-');            //recorrer en for las hrs de la tabla
            $("#Tabla_paralelos tbody tr").each(function (index) {
               // console.log('-tr-'+$(this).find('.td_id_horario_dia').text());     //recorrer en for las hrs de la tabla
                
                //alertify.success('Se elimino la materia correctamente');
               if($(this).find('.td_id_horario_dia').text()==data[i]["id_horario_dia"]){
                
                   //en caso de ser descanso
                  /* if(data[i]["complemento"]=='descanso'){
                       $('#Tabla_paralelos').find('tbody').find(this).find("td:eq(3)").text('--DESCANSO--');
                       //poner colores y unir tabla
                       $('#Tabla_paralelos').find('tbody').find(this).css('background','#00d0ffa1').find("td:eq(3)").attr('colspan','6').nextAll().remove();
                       
                   }else{*/
                   //sino color
               var colortd='transparent';
                   if( $('#checkboxcolor').prop('checked') ) {
                      colortd=data[i]["color_materia"];
                    }
                   
                   
                   //vemos a que dia corresponde
                       
                        if(data[i]["dia_semana_id"]==1){
                        $('#Tabla_paralelos').find('tbody').find(this).find("td:eq(3)").html(datostd+btnEdit+btnDelete).css('background',colortd);
                           // console.log('................'+data[i]["color_materia"]);
                            
                        }else if(data[i]["dia_semana_id"]==2){
                        $('#Tabla_paralelos').find('tbody').find(this).find("td:eq(4)").html(datostd+btnEdit+btnDelete).css('background',colortd);
                        }else if(data[i]["dia_semana_id"]==3){
                        $('#Tabla_paralelos').find('tbody').find(this).find("td:eq(5)").html(datostd+btnEdit+btnDelete).css('background',colortd);
                        }else if(data[i]["dia_semana_id"]==4){
                        $('#Tabla_paralelos').find('tbody').find(this).find("td:eq(6)").html(datostd+btnEdit+btnDelete).css('background',colortd);
                        }else if(data[i]["dia_semana_id"]==5){
                        $('#Tabla_paralelos').find('tbody').find(this).find("td:eq(7)").html(datostd+btnEdit+btnDelete).css('background',colortd);
                        }else if(data[i]["dia_semana_id"]==6){
                        $('#Tabla_paralelos').find('tbody').find(this).find("td:eq(8)").html(datostd+btnEdit+btnDelete).css('background',colortd);
                        }
                   //}
                   //alertify.success('hola'+$(this).find('.td_id_horario_dia').text());
                    //buscar el dia y llenarlos
                    
                 }
            })
          /*  for(var k;k<6;k++){
               var hs1=$('#Tabla_paralelos').find('tbody').find("tr:eq("+k+")").find("td:eq(1)").text(data[i]["hora_ini"]);
                if(){
                    
                }
            }*/
            
           /* if(data[i]["id_horario_dia"]==1){
                
                lunes=lunes+' - '+data[i]["nombre_materia"]+ doc_curso+btnEdit+btnDelete;
            
            } else if(data[i]["id_horario_dia"]==2){
               martes=martes+' - '+ data[i]["nombre_materia"]+doc_curso+btnEdit+btnDelete; 
            } else   if(data[i]["id_horario_dia"]==3){
              miercoles=miercoles+' - '+ data[i]["nombre_materia"]+doc_curso+btnEdit+btnDelete;
            }
            
            if(horario_id!=horario_id2){
                //+1 avanzar uan fila mas 
             horario_id0=data[i]["id_horario_dia"];//iguala
              //strhora+= '</tr>';
             }
            horario_id=data[i]["id_horario_dia"];*/
            //strhora
 

        }
        
       // $('#Tabla_paralelos').find('tbody').append('<td>'+1+'</td>'+'<td>'+horaini+'</td>'+'<td>'+lunes+'</td><td>'+martes+'</td><td>'+miercoles+'</td></tr>');
        //color de cuadro
        
        
    }//fin success 2 ajaxs
    });
        
    }); //fin 1er ajax
 
   // $('#Tabla_paralelos').find('tbody').find("td:eq(3)").text('pos 0-3  holasss'); 
   // $('#Tabla_paralelos').find('tbody').find("tr:eq(1)").find("td:eq(2)").text('pos 1-2 este es dato jquery'); 
  
    
   /* 
    
  $.ajax({
    url: '?/s-profesor-horario/procesos',
    type: 'POST',
    data: {
        'boton': 'listar_cursos_horario_new', 
        'aula': aula,
        'turno':turno,
        'nivel':nivel,
        'paralelo':paralelo

    },
    dataType: 'JSON',
    beforeSend: function() {


    }, 
    success: function(data){
        console.log(data);  
      var llenardatos='';
        //console.log('2-Succes inicio for-'); 
        var lunes=''; var martes=''; var miercoles=''; var jueves='';var viernes='';var sabado='';var horaini='';//var horaini='<td>'+respuesta[j]["hora_ini"]+' - '+respuesta[j]["hora_fin"]+'</td>';
        var horario_id=0;
        var horario_id2=0;
        $('#Tabla_paralelos').find('tbody').html('');
        var strhora='';
       //recorrer el data con valores de un horario
        for (var i = 0; i < data.length; i++) {
            //horario_id=data[i]["id_horario_dia"];
 
            var doc_curso=''; 
            var btnEdit=''; 
            var btnDelete=''; 
            
            if(data[i]["id_horario_dia"]==1){
                lunes=lunes+' - '+data[i]["nombre_materia"]+ doc_curso+btnEdit+btnDelete;
            
            } else if(data[i]["id_horario_dia"]==2){
               martes=martes+' - '+ data[i]["nombre_materia"]+doc_curso+btnEdit+btnDelete; 
            } else   if(data[i]["id_horario_dia"]==3){
              miercoles=miercoles+' - '+ data[i]["nombre_materia"]+doc_curso+btnEdit+btnDelete;
            }
            
            if(horario_id!=horario_id2){
                //+1 avanzar uan fila mas 
             horario_id0=data[i]["id_horario_dia"];//iguala
              //strhora+= '</tr>';
             }
            horario_id=data[i]["id_horario_dia"];
            //strhora
 

        }
        
        $('#Tabla_paralelos').find('tbody').append('<td>'+1+'</td>'+'<td>'+horaini+'</td>'+'<td>'+lunes+'</td><td>'+martes+'</td><td>'+miercoles+'</td></tr>');
        //color de cuadro
        
        
    }//fin success 2 ajaxs
})
   .done(function(data) {
 
  });*/
 }

 
function abrir_crear(x){
    //alert('aquicrear');
    //turno1
	$("#modal_gestion").modal("show");
	$("#form_gestion")[0].reset();
	$("#titulo_gestion").text("Asignar ");
	id_aula_paralelo=x;
     $("#tipoAc").val('new');
    $("#aula_paralelo_id").val(id_aula_paralelo);
    //$("#btn_eliminarde").hide();
    //editar oculata materia, a mostrar ahora
    $('#id_materia').show();
    $('#id_materia').siblings().show();//(d[3]);//label
    $('#id_materia').removeAttr('disabled');
    
	$("#btn_modificar").hide();
	$("#btn_nuevo").show();
    
    var turno_id=$("#turno_id").val();
    $("#horario").find('option').hide();
	$("#horario").find('.turno'+turno_id).show(); 
    
    
   // $(location).attr('href','?/s-profesor-horario/verhorario/'+x);//+'/'+1+'/'+1+'/'+1);
    
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
                    ver_tabla_horario();
                    $("#myModal").modal("hide");
                   }else{
                   // alert('RESP:'+ resp);
                   
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
    
    var id_aula_paralelo=0;
    var horario=0;//id recojido de 
    var dia=0;//id dia,envidoa edicion.php
function abrir_editar(cont){
    
    //alert('recoje datos de edit y nada mas.... gusrdar');
	$("#modal_gestion").modal("show");
	$("#form_gestion")[0].reset();
	//$("#form_gestion").reset();
	$("#titulo_gestion").text("Editar ");
     
    var  nivel_id=$("#nivel_id").val();
	var d = cont.split("*");
    
    /*0 id_horario_profesor_materia
    1 aula_paralelo_id
    2 profesor_id
    3 materia_id
    4 horario_dia_id
    5 dia_semana_id
    6 turno_id*/
   
	horario=d[4];
	dia=d[5];
    $("#tipoAc").val('edit');
    $("#aula_par_prof_mat_id").val(d[0]);
    $("#aula_paralelo_id").val(d[1]); 
    
    
    $('#id_docente').data('selectize').setValue(d[2]); 
    
    //ocultar edicion materia
    //$('#id_materia').siblings().hide();//(d[3]);//label
    //$('#id_materia').attr('disabled','true'); 
    //$('#id_materia').hide();//(d[3]);//label
    //---------------------------
    $('#id_materia').val(d[3]);//select materia nueva
    
    
    //alert('aula_paralelo_id'+d[3]);
	//$("#id_docente").val(d[1]);//id de actual
	//$("#id_materia").val(d[2]);//id de actual
	$("#horario").val(horario);//id de actual
	$("#dia").val(dia);//id de actual
	$("#id_aula_asig_mat").val(d[8]);//id de actual
    
    $("#btn_nuevo").hide();
	$("#btn_modificar").show();
    $("#horario").find('option').hide();
	$("#horario").find('.turno'+d[6]).show(); 
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
 
	 var id_horario_mat = $("#curso_eliminar").val();
 
	$.ajax({
		url: '?/s-profesor-horario/eliminar',
		type:'POST',
		data: {'id_a_eliminar':id_horario_mat},
		success: function(resp){
			// alert(resp)
			switch(resp){
				case '1': $("#modal_eliminar").modal("hide"); 
							alertify.success('Se elimino la materia correctamente');
                            ver_tabla_horario();
                    
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
                        ver_tabla_horario();
                        break;
              case '2': //dataTable.ajax.reload();
                        $("#modal_curso").modal("hide");
                        alertify.success('Se editó el curso correctamente');
                        ver_tabla_horario();
                        break;
            }
            //pruebaa();
          }
          
      });
      
  }
})
    
    
    
    
function impresionpdfmini(){//pdf mini horario comprimido
 
    var id_aula_p = $("#id_aula_rec").val();//this//
    var id_turno = $("#turno_id").val();//this//
    $(location).attr('href','?/s-profesor-horario/imprimirtodo/'+id_aula_p+'/'+id_turno);//+'/'+aula+'/'+paralelo);
   // window.location.href = "?/s-curso-paralelo-profesor-materia/imprimir";   
}    
function impresionpdf(){//pdf
	//var turno = $("#turno option:selected").val();//this
	//var nivel = $("#nivel option:selected").val();//this
    //var aula = $("#aula option:selected").val();//this
	//var paralelo = $("#paralelo_listar option:selected").val();//this
    var id_aula_p = $("#id_aula_rec").val();//this//
    var id_turno = $("#turno_id").val();//this//
    $(location).attr('href','?/s-profesor-horario/imprimir/'+id_aula_p+'/'+id_turno);//+'/'+aula+'/'+paralelo);
   // window.location.href = "?/s-curso-paralelo-profesor-materia/imprimir";   
}
function impresionInteligente2(){//excel
	//var turno = $("#turno option:selected").val();//this
	//var nivel = $("#nivel option:selected").val();//this
    //var aula = $("#aula option:selected").val();//this
	//var paralelo = $("#paralelo_listar option:selected").val();//this
   var id_aula_p = $("#id_aula_rec").val();//this//
   var id_turno = $("#turno_id").val();//this//
     
    //$(location).attr('href','?/s-profesor-horario/horario-docente-excel.php/'+turno+'/'+nivel+'/'+aula+'/'+paralelo);
     
    $(location).attr('href','?/s-profesor-horario/horario-curso-excel/'+id_aula_p+'/'+id_turno);//  
}
//FILTRO DE TURNO PARALELO
   listar_turno();
    var contT=0;   
	function listar_turno() {
	 nivel = 0;//$("#nivel_academico option:selected").val()
		$.ajax({
			url: '?/s-curso-paralelo/procesos',
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
                    //listar_aulas();
				}
				//console.log(resp[0]);
                   

			}
		});
        
	}   
    
    function listar_nivel() {
	 //turno = $("#turno option:selected").val()
     // alert('list nivel');
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
				listar_aulas();
                   

			}
		});
        
	}  
    listar_aulas();
	function listar_aulas() {
	// nivel = 0;//$("#nivel_academico option:selected").val()
	//var turno = $("#turno option:selected").val();//mañána tarde noche
        
	var nivel = $("#nivel option:selected").val();//primaria  sec
      
        
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
                 //funcionverhorario();   

			}
		});
        
	}
    function listar_paralelos() {
	// nivel = 0;//$("#nivel_academico option:selected").val()
	//var turno = $("#turno option:selected").val();//mañána tarde noche
	var aula = $("#aula option:selected").val();//primaria  sec
       //alert('para-'+aula+'-');
        
		$.ajax({
			url: '?/s-profesor-horario/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_paralelos_val_ap',
				'aula': aula
			},
			dataType: 'JSON',
			success: function(resp){
		    //console.log('Listar aula'+ resp); 
              //alert('rest aulaas');
				 
				$("#paralelo_listar").html("");
				$("#paralelo_listar").append('<option value="0">(Todos)Seleccionar</option>');////' + 0 +
				for (var i = 0; i < resp.length; i++) {
					$("#paralelo_listar").append('<option value="' + resp[i]["id_aula_paralelo"] + '">' + resp[i]["nombre_paralelo"] +'</option>');
				}
				  $('#paralelo_img').html('');
                   

			}
		}); 
        
         //funcionverhorario();
        
	}
    function funcionverhorario(){
        var turno = $("#turno option:selected").val();//this
        if(!turno)
            turno=0;
	   //var nivel = $("#nivel option:selected").val();//this
        //var aula = $("#aula option:selected").val();//this
	    var paralelo_ap = $("#paralelo_listar option:selected").val();//con val aulaparalelo
        if(!paralelo_ap)
            paralelo_ap=0;
       // alert('filtrar el horario ap:'+paralelo_ap);
        //var id_docente = $("#id_docente2 option:selected").val();//this//
     
    $(location).attr('href','?/s-profesor-horario/verhorario/'+paralelo_ap+'/'+turno);
    }
        </script>

