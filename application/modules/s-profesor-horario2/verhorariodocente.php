<?php
 
// Obtiene la cadena csrf 
$csrf = set_csrf();

//$turno=isset($_params[0])?$_params[0]:0;
//$nivel=isset($_params[1])?$_params[1]:0;
//$aula=isset($_params[0])?$_params[0]:0; 
//$paralelo=isset($_params[0])?$_params[0]:0;

// Obtiene datos del paralelo
/*$datosparalelo = $db->query("SELECT ins_turno.nombre_turno,b.id_aula_paralelo,c.nombre_aula,e.nombre_paralelo,d.nombre_nivel FROM ins_aula_paralelo b ,ins_aula c,ins_nivel_academico d,ins_paralelo e,ins_turno
    WHERE
    
    ins_turno.id_turno=b.turno_id AND
    b.aula_id=c.id_aula AND
    c.nivel_academico_id= d.id_nivel_academico AND
    b.estado='A' and
    b.paralelo_id= e.id_paralelo AND
    b.id_aula_paralelo=".$aula)->fetch();
 */
?>
<input type="hidden" value="<?=$aula?>" id="id_aula_rec">
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
            <h2 class="pageheader-title">HORARIO POR DOCENTES</h2>
         <p>Se designa su materia y un horario</p>
      
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Reportes Generales</a></li>
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
             
                    <div class="row">
                          
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 ">
                            <div class="form-group">
                            <label for="aula_id" class="control-label">Buscar por:</label>
                            <div class="row">
                            <a class="col-6 form-control inline btn btn-def" href="?/s-profesor-horario/listar" class="dropdown-item">Listado por cursos</a> 
                             <a class="col-6 form-control btn btn-success" href="3">Por Docentes</a>
                            <a class="col-6 form-control inline btn btn-default" href="?/s-profesor-horario/verhorario/0/0" class="dropdown-item">Por Cursos</a>
                             </div>
                            </div>
                             
                        </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 ">
                            <div class="form-group">
                                <label for="aula_id" class="control-label">Seleccione Docente</label>
                               <select required name="id_docente" id="id_docente2" onchange="ver_tabla_horario();" class="form-control">
								 
										 
								</select>
                           <!-- <button type="reset" class="btn btn-success" id="btnNuevo" data-toggle="modal" data-target="#myModal" style="display:none">
									<i class="icon-star"></i>
									<span>Nuevo paralelo</span>
					        </button>-->
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
										<a href="?/s-profesor-horario/listar" class="dropdown-item">Ver por lista de cursos</a>
							 <!--<div class=" col-sm-12 col-12 ">
                           
                               <div class="form-group">
                                
                                <button type="button" class="btn btn-success" id="btnNuevo" onclick="abrir_crear(<?=$aula?>)" title="Nuevo docente materia y horarios">
								 
									<span>Agregar nuevo</span>
                                    </button>
                                    </div>
                                </div> -->
										<?php// endif ?>  
										<?php //if ($permiso_imprimir) : ?>
								<div class="dropdown-divider"></div>
										<!--<a href="?/s-curso-paralelo-profesor-materia/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Asignaciones</a>-->
										<a href="#" class="dropdown-item"  onclick="impresion_pdf_mini()"><span class="icon-print"></span>Exportar pdf mini</a>
										
										<a href="#" class="dropdown-item"  onclick="impresion_pdf()"><span class="glyphicon glyphicon-print"></span>Exportar pdf</a>
										
										<a href="#" class="dropdown-item"  onclick="impresionInteligente2()"><span class="glyphicon glyphicon-print"></span> Exportar Excel</a>
										<?php //endif ?>
									</div>
								</div>
							</div>
						</div> 
                               
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
                           <th  >Lunes</th>
                           <th>Martes</th>
                           <th>Miercoles</th>
                           <th>Jueves</th>
                           <th>Viernes</th>
                           <th>Sabado</th>
                         
                              
                           <th>Asignar</th>
                           </tr>
                       </thead>
                        <tbody>
                           <tr>
                           <td>horas</td>
                           <td rowspan="6" colspan="8" ><b>Seleccione un turno nivel y aula para visualizar un horario</b> </td> 
                           </tr><!-- <tr>
                           <td>horas</td>
                           <td  ><b>Lenguaje</b> <p>Luis Micuel</p></td>  
                           <td  ><b>Lenguaje</b> <p>Luis Micuel</p></td> 
                           <td  ><b>Lenguaje</b> <p>Luis Micuel</p></td>
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
//alertify.success('');
    
/* var dataTable = $('#Tabla_paralelos').DataTable({
  language: dataTableTraduccion,
  searching: true,
  paging:true,
  stateSave:true,
  "lengthChange": true,
  "responsive": true
  });*/
    $('#turno').focus();
listar_docente();
function listar_docente() {
    
	 nivel = 0;//$("#nivel_academico option:selected").val()
		$.ajax({
			url: '?/s-profesor-horario/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_docente',//listar_docente',
				'nivel': nivel
			},
			dataType: 'JSON',
			success: function(resp){
		    //console.log('Listar doc2'+ resp); 
                 
				//alert(resp[0]['id_catalogo_detalle']); 
				//console.log(resp);
                var cont=0;
				$("#id_docente2").html("");
				$("#id_docente2").append('<option value="">Seleccionar</option><option value=0>TODOS</option>');
				for (var i = 0; i < resp.length; i++) {
 
                        $("#id_docente2").append('<option  value="' + resp[i]["id_asignacion"] + '">'  + resp[i]["primer_apellido"]+' ' + resp[i]["nombres"]+ ' - ' +resp[i]["numero_documento"]+'</option>');
				}
				//console.log(resp[0]);
                   

			}
		}).done(function (data) {
           //alert('done');
            $('#id_docente2').selectize();
        });
        
	}
 
//ver_tabla_horario();
function ver_tabla_horario() {
    
    //var aula = $("#aula option:selected").val();//this
    var aula = 0;//$("#id_aula_rec").val();//this
	var id_docente = $("#id_docente2 option:selected").val();//this
    //alert('tabla '+id_docente);
	var turno = 0;//$("#turno option:selected").val();//this
	var nivel = 0;//$("#nivel option:selected").val();//this
	var paralelo = 0;//$("#paralelo_listar option:selected").val();//this
    //varaiables de uso 
    var respuesta=''; 
    var horas =  new Array(); 
    var horas_fin =  new Array(); 
    var j=0;
     var cc=0;
  var respuesta;
 console.log('1-inicio de funcion-');
    //consulta horarios
    $.ajax({
			url: '?/s-profesor-horario/procesos',
			type: 'POST',
			data: {
                'boton': 'listar_horarios',
                'aula': aula,
                'turno':turno,
                'nivel':nivel,
                'paralelo':paralelo,
                'id_docente':id_docente
			},
			dataType: 'JSON',
    })
    .done(function(response) {
        respuesta = response;
        console.log('2-done1-');$('#Tabla_paralelos').find('tbody').html(' ');
        for (j = 0; j < response.length; j++) {
            //guardamos las vriables en un array
            horas.push(response[j]["hora_ini"]);
            horas_fin.push(response[j]["hora_fin"]);
            //console.log('microo------ A:::::::-'+response[j]["hora_ini"]);
            // $('#Tabla_paralelos').find('tbody').append('<tr><td>'+cc+'</td><td>'+response[j]["hora_ini"]+'</td><td></td>'+'<td></td>'+'<td></td>'+'<td></td>'+'<td></td>'+'<td></td>'+'</tr>'); 
            }
    })
    .fail(function( jqXHR, textStatus, errorThrown ) {
         if ( console && console.log ) {
             console.log( "La solicitud a fallado: " +  textStatus);
         }
    })
    .then(function(){
        //con datos obtenidos del primer ajax realizamos una nueva busqueda en los horarios correspondintes
        console.log('3-then-');
        if (respuesta){
               for (j = 0; j < respuesta.length; j++) {
                            //horaini='<td>'+respuesta[j]["hora_ini"]+'</td>';
                           
                            //console.log('5-for '+respuesta[j]["hora_ini"]+'-');
                            console.log('4-FOR A:::::::-'+j+" --"+respuesta[j]["id_horario_dia"]);  
                          $.ajax({
                            url: '?/s-profesor-horario/procesos',
                            type: 'POST',
                            data: {
                                'boton': 'listar_docente_horario', 
                                'aula': aula,
                                'turno':turno,
                                'nivel':nivel,
                                'paralelo':paralelo ,
                                'hora_inicio':respuesta[j]["id_horario_dia"],//hora_ini,
                                'id_docente':id_docente

                            },
                            dataType: 'JSON',
                            beforeSend: function() {
                                
                       
                            }, 
                            success: function(data){
                     
                          
                            }//fin success 2 ajaxs
                        }).done(function(data) {
                              var llenardatos='';
                                console.log('5-Succes inicio for-'); 
                                var lunes=''; var martes=''; var miercoles=''; var jueves='';var viernes='';var sabado='';var horaini='';//var horaini='<td>'+respuesta[j]["hora_ini"]+' - '+respuesta[j]["hora_fin"]+'</td>';
                             
                               //recorrer el data con valores de un horario
                                for (var i = 0; i < data.length; i++) {
                                      console.log('6-Succes inicio  for-:::::::::color cante:::::::::::::');
                                    var doc_curso='';
                                    if(data[i]["nombres"]==null){
                                         doc_curso= '<br><b class="miniletra" style="color:red">('+'SIN DOCENTE ASIGNADO'+' -'+data[i]["nombre_aula"]+' '+data[i]["nombre_paralelo"]+')</b>';
                                        }else{
                                        doc_curso= '<br>(<b class="miniletra">'+data[i]["nombres"]+' -'+data[i]["nombre_aula"]+' '+data[i]["nombre_paralelo"]+'</b>)';
                                                }
                                    //:::::::::::::::::::::
                                    var btnEdit="<button class='btn btn-info btn-xs' ONCLICK='abrir_editar("+'"'+data[i]["id_horario_profesor_materia"]+'*'+data[i]["aula_paralelo_id"]+'*'+data[i]["asignacion_id"]+'*'+data[i]["materia_id"]+'*'+data[i]["horario_dia_id"]+'*'+data[i]["dia_semana_id"]+'*'+data[i]["id_turno"]+'"'+")'><span class='icon-pencil'></span></button>"; 
                                    var btnDelete="<button class='btn btn-danger btn-xs' ONCLICK='abrir_eliminar("+'"'+data[i]["id_horario_profesor_materia"]+'"'+")'><span class='icon-trash'></span></button>";
                              
                                    
                                   console.log('        6-FOR-'+i+'-'+data[i]["hora_ini"]+data[i]["nombre_materia"]+data[i]["dia_semana_id"]);
                                    
                                    horaini= data[i]["hora_ini"]+' - '+data[i]["hora_fin"] ;
                                 //signar materia(s)   
                                    if(data[i]["dia_semana_id"]==1){
                                   lunes=lunes+' - '+data[i]["nombre_materia"]+ doc_curso+btnEdit+btnDelete;  
                                    } else if(data[i]["dia_semana_id"]==2){
                                       martes=martes+' - '+ data[i]["nombre_materia"]+doc_curso+btnEdit+btnDelete; 
                                    } else   if(data[i]["dia_semana_id"]==3){
                                      miercoles=miercoles+' - '+ data[i]["nombre_materia"]+doc_curso+btnEdit+btnDelete;
                                    }else  
                                    if(data[i]["dia_semana_id"]==4){
                                       jueves=jueves+' - '+ data[i]["nombre_materia"]+doc_curso+btnEdit+btnDelete; 
                                    }else   if(data[i]["dia_semana_id"]==5){
                                      viernes=viernes+' - '+ data[i]["nombre_materia"]+doc_curso+btnEdit+btnDelete;  
                                    }else if(data[i]["dia_semana_id"]==6){
                                       sabado=sabado+' - '+ data[i]["nombre_materia"]+doc_curso+btnEdit+btnDelete;  
                                    }else {
                                       llenardatos=llenardatos+'<td></td>';     
                                    }
             
                                }
                                //color de cuadro
                               if(lunes=='')
                                   lunes='<td></td>';
                                else
                                   lunes='<td style="background: #a3ffcc;">'+lunes+'</td>';   
                                if(martes=='')
                                   martes='<td></td>';
                                else
                                   martes='<td style="background: #a3ffcc;">'+martes+'</td>';
                                if(miercoles=='')
                                   miercoles='<td></td>';
                                else
                                   miercoles='<td style="background: #a3ffcc;">'+miercoles+'</td>';
                                    if(jueves=='')
                                   jueves='<td></td>';
                                else
                                   jueves='<td style="background: #a3ffcc;">'+jueves+'</td>';
                                    if(viernes=='')
                                   viernes='<td></td>';
                                else
                                   viernes='<td style="background: #a3ffcc;">'+viernes+'</td>';
                                if(sabado=='')
                                   sabado='<td></td>';
                                else
                                   sabado='<td style="background: #a3ffcc;">'+sabado+'</td>';
                                    
                            
                                console.log('7-FOR-'+horaini+lunes+martes+miercoles+jueves+viernes+sabado);
                                 
                              if(horaini!=''){
                                cc++;
                                $('#Tabla_paralelos').find('tbody').append('<tr><td>'+cc+'</td>'+'<td>'+horaini+'</td>'+lunes+martes+miercoles+jueves+viernes+sabado+'</tr>');
                                 
                                 }
                                //$('#Tabla_paralelos').find('tbody').append('<tr><td>'+cc+'</td>'+'<td>'+horas[cc-1]+' - '+horas_fin[cc-1]+'</td>'+lunes+martes+miercoles+jueves+viernes+sabado+'</tr>');
                              
                              
                          });
                         }
                   // }
               // });//each
            //});//1 er each
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
                    ver_tabla_horario();
                    $("#myModal").modal("hide");
                   }else{
                    //alert('RESP:'+ resp);
                   
                   }
		    console.log('RESP:'+ resp);
				//$("#Tabla_paralelos").html("");
				
			}
		});
        
        
        //guardar a la BD
        
    //}
    });
        
    
    
function abrir_crear(x){
    //alert('aquicrear');
	$("#modal_gestion").modal("show");
	$("#form_gestion")[0].reset();
	$("#titulo_gestion").text("Asignar ");
	id_aula_paralelo=x;
     $("#tipoAc").val('new');
    $("#aula_paralelo_id").val(id_aula_paralelo);
    //$("#btn_eliminarde").hide();
	$("#btn_modificar").hide();
	$("#btn_nuevo").show();
   // $(location).attr('href','?/s-profesor-horario/verhorario/'+x);//+'/'+1+'/'+1+'/'+1);
    
}
//modal ASIGNACION
<?php //if ($permiso_crear) : ?>
    
    var id_aula_paralelo=0;
function abrir_editar(cont){
    //alert('recoje datos de edit y nada mas.... gusrdar');
	$("#modal_gestion").modal("show");
	$("#form_gestion")[0].reset();
	//$("#form_gestion").reset();
	$("#titulo_gestion").text("Editar ");
    
	var d = cont.split("*");
/*1 id_horario_profesor_materia
2 aula_paralelo_id
3 profesor_id
4 materia_id
5 horario_dia_id
6 dia_semana_id*/
   
	id_aula_paralelo=d[0];
    $("#tipoAc").val('edit');
    $("#aula_par_prof_mat_id").val(d[0]);
    
    $("#aula_paralelo_id").val(d[1]); 
    
    $('#id_docente').data('selectize').setValue(d[2]);
    $('#id_materia').val(d[3]);
    //$('#id_docente').data('selectize').setValue(d[2]);
    //$('#id_materia').data('selectize').setValue(d[3]);
	//$("#id_docente").val(d[1]);//id de actual
	//$("#id_materia").val(d[2]);//id de actual
	$("#horario").val(d[4]);//id de actual
	$("#dia").val(d[5]);//id de actual
    
    $("#btn_nuevo").hide();
	$("#btn_modificar").show();
    $("#horario").find('option').hide();
	$("#horario").find('.turno'+d[6]).show();
	//$("#btn_eliminar").show();
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
    
    
    
    
function impresion_pdf_mini(){
	var turno = $("#turno option:selected").val();//this
	var nivel = $("#nivel option:selected").val();//this
    var aula = $("#aula option:selected").val();//this
	var paralelo = $("#paralelo_listar option:selected").val();//this 
     
    $(location).attr('href','?/s-profesor-horario/horario-docente-pdf-mini/'+turno+'/'+nivel+'/'+aula+'/'+paralelo);
   // window.location.href = "?/s-curso-paralelo-profesor-materia/imprimir";   
}function impresion_pdf(){
	var id_docente = $("#id_docente2 option:selected").val();//this//
     if(!id_docente)
         id_docente=0;
    $(location).attr('href','?/s-profesor-horario/horario-docente-pdf/'+id_docente);
   // window.location.href = "?/s-curso-paralelo-profesor-materia/imprimir";   
}function impresionInteligente2(){//excel
	//var turno = $("#turno option:selected").val();//this
	//var nivel = $("#nivel option:selected").val();//this
    //var aula = $("#aula option:selected").val();//this
	//var paralelo = $("#paralelo_listar option:selected").val();//this
    var id_docente = $("#id_docente2 option:selected").val();//this//
     if(!id_docente)
         id_docente=0; 
    $(location).attr('href','?/s-profesor-horario/horario-docente-excel/'+id_docente);//+'/'+nivel+'/'+aula+'/'+paralelo);
   // window.location.href = "?/s-curso-paralelo-profesor-materia/imprimir";   
}
    
/*function eliminar(){
     
}
$("button").on('click',eliminar);*/
        </script>