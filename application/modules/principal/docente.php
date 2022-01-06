<?php 
	$rol_id = $_user['rol_id'];  
	$id_persona = $_user['persona_id']; 
 

	$id_gestion = $_gestion['id_gestion'];
//Tipo de Calificacion
// ponderado_calificacion = A , Con los ponderados asignados (suma)
// ponderado_calificacion = P , Con los ponderados trabajados al procentaje de 100
    $ponderado_calificacion = escape($_institution['ponderado_calificacion']);
    $nombre_dominio = escape($_institution['nombre_dominio']);

    $fecha = date('Y-m-d');
    $sql_modo_calificacion   = "SELECT * FROM cal_modo_calificacion WHERE gestion_id ='$id_gestion' and fecha_inicio <= '$fecha' and fecha_final >= '$fecha' AND estado = 'A'";
    $modo_calificacion_actual    = $db->query($sql_modo_calificacion)->fetch_first();
	$id_modo_calificacion_actual = $modo_calificacion_actual['id_modo_calificacion'];
	$habilitado                  = $modo_calificacion_actual['habilitado'];

//BUSQUEDA 1 ELABORADO MARCO quino
$materias = $db->query("SELECT	'NORMAL' as tipo,ina.tipo_calificacion AS tipo_calificacion,pad.id_asignacion_docente, CONCAT(pm.nombre_materia ,' - ', ia.nombre_aula,' ', ip.nombre_paralelo,' ', ina.nombre_nivel) as curso
		FROM  per_asignaciones AS pa 
                        
        INNER JOIN	pro_asignacion_docente AS pad ON pad.asignacion_id = pa.id_asignacion
        INNER JOIN	pro_materia AS pm ON pm.id_materia = pad.materia_id
        INNER JOIN	ins_aula_paralelo AS iap ON iap.id_aula_paralelo = pad.aula_paralelo_id
        INNER JOIN  ins_aula AS ia ON ia.id_aula = iap.aula_id
        INNER JOIN  ins_nivel_academico AS	ina ON ina.id_nivel_academico = ia.nivel_academico_id 
        INNER JOIN  ins_paralelo AS ip ON ip.id_paralelo = iap.paralelo_id
        WHERE pa.persona_id = $id_persona  AND pa.estado = 'A' AND pad.estado_docente = 'A' AND pad.gestion_id = $id_gestion")->fetch();
 
    
    $escuelas_diciplinas = $db->query("SELECT 'EXTRA' as tipo,'CUANTITATIVO' AS tipo_calificacion,ca.id_curso_asignacion as id_asignacion_docente, cur.nombre_curso, ca.observaciones
    FROM ext_curso_asignacion as ca
    INNER JOIN  ext_curso as cur ON ca.curso_id  = cur.id_curso
    INNER JOIN per_asignaciones AS a ON a.id_asignacion = ca.asignacion_id
    INNER JOIN sys_persona AS p ON p.id_persona = a.persona_id
    WHERE p.id_persona = $id_persona AND cur.estado = 'A' AND ca.gestion_id = $id_gestion")->fetch();
    
    
 $modo_calificacion = $db->query("SELECT * FROM cal_modo_calificacion
 WHERE gestion_id=$id_gestion AND estado='A'")->fetch(); 

 

$consulta_profesor_materia="SELECT ap.id_aula_paralelo ,apam.aula_paralelo_id ,
au.`nombre_aula`,pa.`nombre_paralelo`,ni.`nombre_nivel` 
FROM pro_materia mat 

INNER JOIN int_aula_paralelo_asignacion_materia apam ON apam.materia_id=mat.id_materia
INNER JOIN ins_horario_profesor_materia hpm ON hpm.aula_paralelo_asignacion_materia_id=apam.`id_aula_paralelo_asignacion_materia`

INNER JOIN `ins_aula_paralelo` ap ON ap.`id_aula_paralelo`=apam.`aula_paralelo_id` 
INNER JOIN `ins_aula` au ON au.`id_aula`=ap.`aula_id` 
INNER JOIN ins_paralelo pa ON pa.`id_paralelo`=ap.`paralelo_id` 
INNER JOIN `ins_nivel_academico` ni ON ni.`id_nivel_academico`=au.`nivel_academico_id`
INNER JOIN per_asignaciones asi ON asi.id_asignacion=apam.asignacion_id

WHERE asi.persona_id=$id_persona 
GROUP BY apam.aula_paralelo_id ";   

	$sql_profesor_materia=$db->query($consulta_profesor_materia)->fetch();
	$sql_pm='';$sql_pm=json_encode($sql_profesor_materia);
	//var_dump($sql_pm);exit();
    
    // Obtiene los dregistrarnota
    $area_calificacion = $db->query("SELECT * FROM cal_area_calificacion WHERE estado = 'A' ORDER BY id_area_calificacion ASC")->fetch();    
	//Fin Docente principal
    $clasificacion = $db->query("SELECT * FROM cal_clasificacion_cualitativo   ORDER BY id_clasificacion_cualitativo ASC")->fetch();
	$json_clasificacion=json_encode($clasificacion);

	//calificar area
	// Obtiene la cadena csrf  
	$csrf = set_csrf();

	//verifica que los datos esten enviados por el metodo Get
	if(isset($_params[0]) && isset($_params[1]) && isset($_params[2])){
		$id_aula_paralelo = $_params[0];
		$id_profesor_materia = $_params[1];
		$id_modo_calificacion = $_params[2];
	}else{
		$id_aula_paralelo = 0;
		$id_profesor_materia = 0;
		$id_modo_calificacion = 0;
	}

	//busca los datos de la vista profesor materia
	//$s = $db->select('z.*')->from('vista_profesor_materia z')->where('z.id_profesor_materia', $id_profesor_materia)->fetch_first();
    

    //$s = $db->select('z.*')->from('pro_profesor z')->where('z.persona_id', $id_persona)->fetch_first();
    //$id_profesor = $s['id_profesor'];

    $s = $db->select('z.*')->from('per_asignaciones z')->where('z.persona_id', $id_persona)->fetch_first();

    $id_profesor = $s['id_asignacion'];
    //var_dump($id_profesor);exit();
	//busca los datos de la vista modo calificacion area calificacion
	//$where_modo_area = array('y.id_modo_calificacion'=> $id_modo_calificacion, 'y.id_area_calificacion'=> $id_area_calificacion);
	$consulta_modo_area = $db->select('y.*')->from('vista_modo_calificacion_area_calificacion y')->fetch_first();
	//var_dump($consulta_modo_area);die;

	// Obtiene los dregistrarnota
	//$area_calificacion = $db->query("SELECT * FROM cal_area_calificacion ORDER BY id_area_calificacion ASC")->fetch();
	//var_dump($cursos_asignados);die;

	// Obtiene los permisos
	$permiso_crear_actividad = in_array('modal-crear', $_views);

//ver::
	$permiso_crear = in_array('crear', $_views);
	$permiso_editar_actividad = in_array('editar-actividad', $_views);
	$permiso_ver = in_array('ver', $_views);
	$permiso_modificar = in_array('modificar', $_views);
	$permiso_eliminar_actividad = in_array('eliminar-actividad', $_views);
	$permiso_imprimir = in_array('imprimir', $_views);
	//fin calificar area
?>
<?php require_once show_template('header-design'); ?>
<style>
  .datepicker {z-index: 1151 !important;}
</style>
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/bootstrap/css/bootstrap.min.css">
<link href="assets/themes/concept/assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
<link rel="stylesheet" href="assets/themes/concept/assets/libs/css/style.css">
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
<link href="assets/themes/concept/assets/vendor/bootstrap-colorpicker/%40claviska/jquery-minicolors/jquery.minicolors.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/datepicker/css/datepicker.css" rel="stylesheet">

<!--<link rel="stylesheet" href="assets/themes/concept/assets/assets/vendor/charts/morris-bundle/morris.css">-->
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/charts/chartist-bundle/chartist.css">
<link href="assets/themes/concept/assets/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/datepicker/css/datepicker.css" rel="stylesheet">

<?php if($rol_id == 3 || $rol_id == 4):?>
<!-- ============================================================== -->
<!-- Perfil Docente -->
<!-- ============================================================== -->
<div class="row">
	<div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-12 tabs">
		<div class="row">
            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12">
                <div class="card  alert-primary-"> 
                    <div class="card-body">
                    	<div class="row">
	                    	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<select name="id_materia" id="id_materia" class="form-control" onchange="listar_estudiantes()">
								<!--listar_aula_paralelo();-->
								<option value="0">SELECCIONE MATERIA-CURSO</option>
								 
											<?php foreach ($materias as $val) : ?>
												<option value="<?= $val['id_asignacion_docente'] ?>" tipo_calificacion="<?=$val['tipo_calificacion']?>" tipo_extra="<?=$val['tipo']?>"> <?= $val['curso'] ?></option>
											<?php endforeach ?>	
											
											<?php foreach ($escuelas_diciplinas as $val) : ?>
												<option value="<?= $val['id_asignacion_docente'] ?>" tipo_calificacion="<?=$val['tipo_calificacion']?>" tipo_extra="<?=$val['tipo']?>"> <?= $val['nombre_curso'].' - EXTRACURRICULAR' ?></option>
											<?php endforeach ?>
											
									<!--<?php // foreach ($materias as $val):?>
                                       
		                                   <option value="<?//=$val['id_aula_paralelo_asignacion_materia']?>"  tipo_calificacion="<?//=$val['tipo_calificacion']?>"> <?//= $val['nombre_materia'].' - '.$val['nombre_aula'].' '.$val['nombre_paralelo'].' - '.$val['nombre_nivel'] ?></option>
		                                    
		                            <?php // endforeach ?>-->
		                        </select>
	                        </div>
	                       
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
               <div class="card  alert-primary-"> 
                    <div class="card-body">
                    	<div class="row">
                        <h3 class="card-header-title pl-2 pr-2">Periodos</h3>
                        <select class="form-control w-auto " id="bimestre" onchange="listar_estudiantes();"> 
                            <?php foreach ($modo_calificacion as $val) : ?>
								    <option value="<?= $val['id_modo_calificacion'] ?>" title="de<?= $val['fecha_inicio'] ?> a <?= $val['fecha_final'] ?>"  habilitado="<?=$val['habilitado']?>"> <?= $val['descripcion'] ?></option>
							 <?php endforeach ?>
                           <!-- <option value="1">1</option> -->
                        </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12">
            <span onclick="oculta_calendar()" class="btnhidecalendar"><i class="btn fa fa-calendar text-white" style="font-size:22px;text-color:white"></i> </span>
                
            </div>
        </div>
        
        
		<div class="row" id="contenedor_pizarra">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-12">
               
               
               
               
<!--::::::::::::::::::::::::tab2:::::::::::::::::::::::::::-->

<div class=" tab-regular">
 
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#Asistencia">Asistencia</a>
    </li>
    <li class="nav-item">
      <a class="nav-link " data-toggle="tab" href="#cardex">Kardex</a>
    </li>
    <li class="nav-item">
      <a class="nav-link " data-toggle="tab" href="#notas">Registro notas</a>
    </li><li class="nav-item">
      <a class="nav-link " data-toggle="tab" href="#comunicados">Comunicados</a>
    </li>
  </ul>
 
  <div class="tab-content">
    <div id="Asistencia" class="  tab-pane active">
    
      <p>Agregue fechas y marque solo a los atrasados e inasistentes</p>
         <button class="btn btn-info" onclick="agregarfecha(this)">Llamar asistencia</button> 
         <button class="btn btn-success" onclick="reporte_asistencia_materia();">Reporte de asistencias</button><br><br>
        <table class="table table-bordered tableasistencia">
            <thead>
                <tr style="height: 8em;">
                    <th >#</th>
                    <th >Apellidos y Nombres</th>
                   
                    <th class="eventos">Eventos</th>

                </tr>

            </thead>
            <tbody class="tbody">
               <tr><td colspan="4">SELECCIONE OPCIÓN ARRIBA DE MATERIA-CURSO</td>
                </tr>


            </tbody>
        </table>
    </div>
    <style>
        .js-cd-img{
            display: flex;
   justify-content: center;
   align-items: center;
        } 
    .tipofelicitacion{
       background: orange!important;
        }
    .tipoCitacion{
       background: #495fff!important;
        color: white;
        } 
    .tipSancion{
       background: red!important;
        color: white;
        }
       /* .oculto{
            animation-duration: 3s;
              animation-name: slidein;
            display: none;
        }*/
        /*.visible{
            display: block;
        }*/
      </style>
      <script>
          var est=true;
      function vermas(obj){
          //if(est){ 
          $(obj).parent().siblings('p').slideToggle();//removeClass('oculto').addClass('visible');//show();
          //est=false;
          //}else{
          //$(obj).parent().siblings('p').removeClass('visible').addClass('oculto');//hide();
          //est=true; 
         //}
      }
      </script> 
    <div id="cardex" class="  tab-pane fade">
    <!--contenido cardex-->
     <h3 class="titlecardex">Kardex por estudiante <button class="btn btn-info" onclick="impKardexExcel()">Imprimir</button></h3>
    
    <section class="cd-timeline js-cd-timeline tablekardexEst" style="display:none">
                       <button class="btnAtrasCardex" onclick="btnAtrasCardex(9)">atras</button>
                        <div class="cd-timeline__container">
                           <!---------------------------->
                            <div class="cd-timeline__block js-cd-block">
                                <div class="cd-timeline__img cd-timeline__img--picture js-cd-img tipofelicitacion">
                                  <span class="fas fa-trophy"></span>
                                   
                                </div>
                                <!-- cd-timeline__img -->
                                <div class="cd-timeline__content js-cd-content">
                                    <h3>FELICIDADES FELICIDADES FELICIDADES FELICIDADES FELICIDADES  <a href="#0" class="btn btn-link btn-xs " onclick="vermas(this)">ver mas</a> </h3>
                                  <!--  <a href="#0" class="btn btn-primary btn-lg fa fa-star"> </a><a href="#0" class="btn btn-primary btn-lg fa fa-star"> </a><a href="#0" class="btn btn-primary btn-lg fa fa-star"></a>--> <!--style="display:none"-->
                                    <p style="display:none">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut.</p>
                                    <span class="cd-timeline__date">12 July, 2016 
                                    <!--<a href="#0" class="btn btn-primary btn-lg fa fa-star"> </a>
                                    <a href="#0" class="btn btn-primary  fa fa-star"> </a>-->
                                     </span> <span class="float-right"> <a href="#" class="btn btn-primary btn-sm fa fa-edit"> </a><a href="#" class="btn btn-primary btn-sm fa fa-trash"> </a></span>

                                </div>
                                <!-- cd-timeline__content -->
                            </div>
                            <!-- cd-timeline__block -->
                            <div class="cd-timeline__block js-cd-block">
                                <div class="cd-timeline__img cd-timeline__img--movie js-cd-img tipoCitacion">
                                   <span class="fas fa-trophy "></span> <!--<img src="../assets/vendor/timeline/img/cd-icon-movie.svg" alt="Movie">-->
                                </div>
                                <!-- cd-timeline__img -->
                                <div class="cd-timeline__content js-cd-content">
                                    <h3>Title of section 2</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde?</p>
                                 <a href="#0" class="btn btn-primary btn-lg">Read More</a>
                                    <span class="cd-timeline__date">20 July, 2017</span>
                                </div>
                                <!-- cd-timeline__content -->
                            </div>
                            <!-- cd-timeline__block -->
                            <div class="cd-timeline__block js-cd-block">
                                <div class="cd-timeline__img cd-timeline__img--picture js-cd-img cd-timeline__img--bounce-in tipSancion">
                                   <span class="fas fa-trophy "></span>
                                    <!--<img src="../assets/vendor/timeline/img/cd-icon-picture.svg" alt="Picture">-->
                                </div>
                                <!-- cd-timeline__img -->
                                <div class="cd-timeline__content js-cd-content cd-timeline__content--bounce-in">
                                    <h3>Title of section 3</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi, obcaecati, quisquam id molestias eaque asperiores voluptatibus cupiditate error assumenda delectus odit similique earum voluptatem doloremque dolorem ipsam quae rerum quis. Odit, itaque, deserunt corporis vero ipsum nisi eius odio natus ullam provident pariatur temporibus quia eos repellat consequuntur perferendis enim amet quae quasi repudiandae sed quod veniam dolore possimus rem voluptatum eveniet eligendi quis fugiat aliquam sunt similique aut adipisci.</p>
                                  <a href="#0" class="btn btn-primary btn-lg">Read More</a>
                                    <span class="cd-timeline__date">24 July, 2018</span>
                                </div>
                                <!-- cd-timeline__content -->
                            </div>
                            <!-- cd-timeline__block -->
                            <div class="cd-timeline__block js-cd-block">
                                <div class="cd-timeline__img cd-timeline__img--location js-cd-img cd-timeline__img--bounce-in tipofelicitacion">
                                 <span class="fas fa-trophy "></span>
                                  <!--  <img src="../assets/vendor/timeline/img/cd-icon-location.svg" alt="Location">-->
                                </div>
                                <!-- cd-timeline__img -->
                                <div class="cd-timeline__content js-cd-content cd-timeline__content--bounce-in">
                                    <h3>Title of section 4</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut.</p>
                                 <a href="#0" class="btn btn-primary btn-lg">Read More</a>
                                    <span class="cd-timeline__date">12 September, 2018</span>
                                </div>
                                <!-- cd-timeline__content -->
                            </div>
                            <!-- cd-timeline__block -->
                            <div class="cd-timeline__block js-cd-block">
                                <div class="cd-timeline__img cd-timeline__img--location js-cd-img cd-timeline__img--bounce-in">
                                    <img src="../assets/vendor/timeline/img/cd-icon-location.svg" alt="Location">
                                </div>
                                <!-- cd-timeline__img -->
                                <div class="cd-timeline__content js-cd-content cd-timeline__content--bounce-in">
                                    <h3>Title of section 5</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum.</p>
                                 <a href="#0" class="btn btn-primary btn-lg">Read More</a>
                                   
                                    <span class="cd-timeline__date">20 September, 2018</span>
                                </div>
                                <!-- cd-timeline__content -->
                            </div>
                            <!-- cd-timeline__block -->
                            <div class="cd-timeline__block js-cd-block">
                                <div class="cd-timeline__img cd-timeline__img--movie js-cd-img cd-timeline__img--bounce-in">
                                    <img src="../assets/vendor/timeline/img/cd-icon-movie.svg" alt="Movie">
                                </div>
                                <!-- cd-timeline__img -->
                                <div class="cd-timeline__content js-cd-content cd-timeline__content--bounce-in">
                                    <h3>Final Section</h3>
                                    <p>This is the content of the last section</p>
                                    <span class="cd-timeline__date">1 October, 2018</span>
                                </div>
                                <!-- cd-timeline__content -->
                            </div>
                            <!-- cd-timeline__block -->
                        </div>
                    </section>
    <!-- 
     <div>
    <div class="tiposancion"><p>ACCIONES</p></div>
    <div  class="tipofelicitacion"><p>SANCION</p></div>     
     </div>-->
      
      <!-- <table class="table " id="tablekardexEst">
            <thead>
                <tr style="height: 8em;">
                    <th >#</th>
                    <th >FECHA</th>
                    <th >TIPO</th>
                    <th >DESCRIPCION</th>
                    <th >SANCIONES</th>
                    <th class="eventos">ACCIONES</th>

                </tr>
                <tr>
                    <td>1</td>
                    <td>13/02/2020</td>
                    <td class="tiposancion">SANCION</td>
                
                </tr>
                <tr>
                    <td>1</td>
                    <td>13/02/2020</td>
                    <td  class="tipofelicitacion">FELICITACIONES</td>
                  
                </tr>

            </thead>
            <tbody class="tbody">
               <tr><td colspan="4">El estudiante </td>
                </tr> 
            </tbody>
        </table>
         --> 
           <hr>
        <table class="table table-bordered tablekardex" id="tablekardex">
            <thead>
                <tr style="height: 8em;">
                    <th >#</th>
                    <th >APELLIDOS Y NOMBRES</th>
                    <th >FELICITACIONES</th>
                    <th >CITACIONES</th>
                    <th >SANCIONES</th>
                    <th class="eventos">VISTA E IMPRESIÓN</th>

                </tr>

            </thead>
            <tbody class="tbody">
               <tr><td colspan="4">SELECCIONE OPCIÓN ARRIBA DE MATERIA-CURSO</td>
                </tr> 
            </tbody>
        </table>
    </div>
    
    <div id="notas" class=" tab-pane fade">
    <h4 class="tipo_calificacion">  </h4>
    <p>Registra notas facilmente <span class="badge badge-info">
    <?php
    //var_dump($ponderado_calificacion);exit();
    if($ponderado_calificacion=='A'){
        echo 'ponderado máximo pts ';
    }else{
        echo 'ponderado a 100% ';
        
    }
                                                        
    ?>
     </span> en la vista general <button class="btn btn-info" onclick="impNotasExcel()"> Imprimir</button><!--<button class="btn btn-info">Imprimir todo</button>--></p>
    <table class="table table-bordered tablegeneral">
        <thead>
            <tr class="trbtns">
                <th rowspan="2">#</th>
                <th rowspan="2">PATERNO</th>
                <th rowspan="2">MATERNO</th>
                <th rowspan="2">NOMBRES</th>
            </tr>
            <tr class="trvertical" ></tr> 
        </thead>
        <tbody class="tbody">
           <tr>
               <td colspan="4">SELECCIONE OPCIÓN ARRIBA DE MATERIA-CURSO</td>
            </tr>
        </tbody>
    </table> 
</div>
     <div id="comunicados" class="  tab-pane fade">
         <div class="row">
        <span class="btn btn-light  fa fa-sat" onclick="btncomunic('t')">Todos</span>
        <span class="btn btn-primary fa fa-sat" onclick="btncomunic('v')">Niños</span>
        <span class="btn fa fa-sat" style="background:#ff5fef;color:#ffffff" onclick="btncomunic('m')">Niñas</span>
        <span class="btn btn-warning fa fa-sat" onclick="btncomunic('selec')">Seleccionar</span> 
        </div>
        <br>
        <table class="table table-bordered" id="tablecomunic" style="width:100%">
           <thead><th>#</th>
           <th>Fecha Inicio</th>
           <th>Fecha Fin</th>
           <th>Título </th>
           <th>Descripción</th>
           <th>Archivo Adjunto</th>
           <th>Tipo/Usuario</th>
           <th>Opciones</th>
           
           </thead>
            <tbody>
            <!--<tr>    
                
               <td colspan="7"><span class="badge badge-pill" style="background:#f0f0f8;"><span style="color: #808080;" ></span> Selecciones una materia - curso</span></td> 
               
            </tr>-->
             
           </tbody>
        </table>
        
    </div>
  </div>
</div>
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->       
               
           <!--tabs ocultos anteriror codigo-->    
         <!--      
           <div class="tab-regular">
               <ul class="nav nav-tabs nav-fill" id="myTab7" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active"  id="nota2-tab"   data-toggle="tab" href="#contnedor-nota2" role="tab" aria-controls="home" aria-selected="true"><i class="icon-note"></i>marco</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active"  id="pizarra-tab" onclick="cargar_pizarra_materia(4)" data-toggle="tab" href="#contnedor-pizarra" role="tab" aria-controls="home" aria-selected="true"><i class="icon-note"></i>  Mi Pizarra</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="asistencia-tab"  data-toggle="tab" href="#contnedor-asistencia" role="tab" aria-controls="profile" aria-selected="false"><i class="icon-volume-2"></i> Mis Asistencias</a>
                        </li>onclick="cargar_registrar_asistencia();"
                        <li class="nav-item">
                            <a class="nav-link" id="nota-tab" onclick="cargar_area_calificacion();" data-toggle="tab" href="#contnedor-nota" role="tab" aria-controls="contact" aria-selected="false"><i class="icon-note"></i>  Mis Notas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="kardex-tab" onclick="cargar_kardex();" data-toggle="tab" href="#contnedor-kardex" role="tab" aria-controls="contact" aria-selected="false"><b><i class="icon-graduation font-16"></i></b> Kardex</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="reporte-tab" onclick="cargar_reporte_nota();" data-toggle="tab" href="#contnedor-reporte" role="tab" aria-controls="contact" aria-selected="false"><b><i class="icon-graduation font-16"></i></b> Mis Reportes</a>
                        </li>
                    </ul>
               <div class="tab-content" id="myTabContent7">
                        
                         <div class="tab-pane fade active show" id="contnedor-nota2" role="tabpanel" aria-labelledby="nota2-tab">
 <h4 class="tipo_calificacion">  </h4>

<table class="table table-bordered tablegeneral">
    <thead>
        <tr class="trbtns">
            <th  rowspan="2">#</th>
            <th rowspan="2">MATERNO</th>
            <th  rowspan="2">PATERNO</th>
            <th  rowspan="2">NOMBRES</th>
 
            

        </tr>
        <tr class="trvertical" >
 
        </tr> 
    </thead>
    <tbody class="tbody">
       <tr><td colspan="4">SELECCIONE OPCION ARRIBA DE CURSO</td>
        </tr>
 
  
    </tbody>
</table> 
                        </div>
                        
                        <div class="tab-pane fade" id="contnedor-pizarra" role="tabpanel" aria-labelledby="pizarra-tab">
                        	<div class="row">
							    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
						 
							            <div class="">
							                <h5 class="">Promedio del Curso por Bimestre</h5>
							                <div class="card-body">
							                    <div id="grafico-lineal"></div>
							                </div>
							            </div>
					 
							    </div>

							    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
		                            <div class="">
		                                
		                                <div class="card-body">
		                                	<h5 class="">Altos Promedios de la Materia</h5>
		                                    <ul class="list-group">
		                                        <li class="list-group-item d-flex justify-content-between align-items-center">
		                                            Cras justo odio
		                                            <span class="badge badge-primary badge-pill">100</span>
		                                        </li>
		                                        <li class="list-group-item d-flex justify-content-between align-items-center">
		                                            Dapibus ac facilisis in
		                                            <span class="badge badge-primary badge-pill">98</span>
		                                        </li>
		                                        <li class="list-group-item d-flex justify-content-between align-items-center">
		                                            Morbi leo risus
		                                            <span class="badge badge-primary badge-pill">90</span>
		                                        </li>
		                                    </ul>
		                                </div>

		                                <div class="card-body">
		                                	<h5 class="">Bajos Promedios de la Materia</h5>
		                                    <ul class="list-group">
		                                        <li class="list-group-item d-flex justify-content-between align-items-center">
		                                            Cras justo odio
		                                            <span class="badge badge-danger badge-pill">51</span>
		                                        </li>
		                                        <li class="list-group-item d-flex justify-content-between align-items-center">
		                                            Dapibus ac facilisis in
		                                            <span class="badge badge-danger badge-pill">50</span>
		                                        </li>
		                                        <li class="list-group-item d-flex justify-content-between align-items-center">
		                                            Morbi leo risus
		                                            <span class="badge badge-danger badge-pill">49</span>
		                                        </li>
		                                    </ul>
		                                </div>
		                            </div>
							    </div>
						    </div>
                        </div>
                        
                        <div class="tab-pane fade" id="contnedor-asistencia" role="tabpanel" aria-labelledby="asistencia-tab">
                              <button class="btn btn-info" onclick="agregarfecha()">Llamar asistencia</button><br>                
                            <table class="table table-bordered tableasistencia">
                                <thead>
                                    <tr style="height: 8em;">
                                        <th >#</th>
                                        <th >MATERNO</th>
                                        <th >PATERNO</th>
                                        <th >NOMBRES</th>

                                    </tr>

                                </thead>
                                <tbody class="tbody">
                                   <tr><td colspan="4">SELECCIONE OPCION ARRIBA DE CURSO</td>
                                    </tr>


                                </tbody>
                            </table>
                        </div>
                       
                        <div class="tab-pane fade" id="contnedor-nota" role="tabpanel" aria-labelledby="nota-tab">
 
                        </div>
 
                        <div class="tab-pane fade" id="contnedor-kardex" role="tabpanel" aria-labelledby="kardex-tab">
                        </div>
                        <div class="tab-pane fade" id="contnedor-reporte" role="tabpanel" aria-labelledby="reporte-tab">
                        </div>
                    </div>
                </div>
                
               --> 
            </div>
		</div>
	</div>
	<div class="hidee col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 calendar ">
		<div class="row">
        </div>
 
		<div class="row">
		    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		        <div class="contariner" >
		            <div class="card">
		                <div class="card-body" align="center">
                        <div class="row">
		                                <ul class="font-14">
		                                   <li><a href="files/aplicaciones/app-docente-educheck.apk" class="nav-link text-left"><i class="fas fa-download"></i> Descargar Aplicación Docente</a></li>		                                    
		                                </ul>
		                            </div>
		                    <div id='calendario'style="width:100%; height:100%" ></div>
			                <div id=''style="width:100%; height:100%" ></div>
				            <div id='d'style="width:100%; height:100%" >
				          	    <div class="card">
		                            <div class="alert-primary card-header  bg-light text-left p-3 ">
		                                <h4 class="mb-0 text-black"> Hoy <?= date('d/m/Y')?></h4>
		                            </div>
		                            <div class="card-body border-top">
		                                <ul class="list-unstyled bullet-icon-book-open text-left font-14">
		                                    <li><i class="icon-book-open"> </i>  Facebook, Instagram, Pinterest,Snapchat.</li>
		                                    <li><i class="icon-book-open"> </i>  Guaranteed follower growth for increas brand awareness.</li>
		                                    <li><i class="icon-book-open"> </i>  Daily updates on choose platforms</li>
		                                </ul>
		                            </div>
		                            <div class="card-header alert-primary bg-light text-left p-3 ">
		                                <h4 class="mb-0 text-black"> Mañana 09/10/2019 </h4>
		                            </div>
		                            <div class="card-body border-top">
		                                <ul class="list-unstyled bullet-icon-book-open text-left font-14">
		                                    <li><i class="icon-book-open"> </i>  Facebook, Instagram, Pinterest,Snapchat.</li>
		                                    <li><i class="icon-book-open"> </i>  Guaranteed follower growth for increas brand awareness.</li>
		                                    <li><i class="icon-book-open"> </i>  Daily updates on choose platforms</li>
		                                </ul>
		                            </div>
		                        </div>
				            </div>
			            </div>
		            </div>
		        </div>
		    </div>
	    </div>
    </div>
 
<?php endif ?>  
<div><img src="" alt="">
<!-- Gráficos -->
<script src="<?= themes; ?>/concept/assets/vendor/charts/morris-bundle/raphael.min.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/charts/morris-bundle/morris.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/charts/morris-bundle/Morrisjs.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/charts/chartist-bundle/chartist.min.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/gauge/gauge.min.js"></script>
<!-- Calendeario -->
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/calendar.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/fullcalendar.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/es.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/jquery-ui.min.js"></script>

<!-- librerias para el color -->
<script src="assets/themes/concept/assets/vendor/bootstrap-colorpicker/%40claviska/jquery-minicolors/jquery.minicolors.min.js"></script>

<!--calendario-->
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.es.js"></script>

<!-- librerias para el multi selector -->
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-select/js/bootstrap-select.js"></script>
<!--<script src="<?//= themes; ?>/concept/assets/vendor/bootstrap-select/js/require.js"></script>-->

<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>

<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/educheck.js"></script>
<script src="<?= js; ?>/bootbox.min.js"></script>
<script src="<?= themes; ?>/concept/assets/libs/js/main-js.js"></script>

<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.es.js"></script>
<script src="assets/themes/concept/assets/vendor/datatables/js/data-table.js"></script>
<script src="assets/themes/concept/assets/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>


</div>

<?php //require_once show_template('footer-design'); 
 
    require_once ("modal_crear.php");//modal comentario
	    require_once ("modal_kardex.php");
	    require_once ("modal_comunicados.php");
    require_once("subir-actividad-curso.php"); //MODAL LUIS
//}

?>
<!--<h5>holaa arreglado</h5>-->
<script >
    // alert('holda');</script>
<style>
    
    .btncaja{ 
        height: 100%;
        width: 100%;
        padding: 0.3em;
    }
    
    .btncaja:hover{ 
      background: #bfff35;
        filter: drop-shadow(2px 4px 6px black);
        
    }
    
    
    .btncaja:hover  .cajaicons{ 
        display: flex;
        /*transition: transform 0.4s;*/
        
    }
    /*.cajaicons:hover{
        display: flex;
    }*/
    .cajaicons{
        display: none;
        position: absolute;
        background: #c2ffc1a3;
        top: -2em;border-radius: 5px;
   
        
    }
    .cajaicons img{
        height: 2.5em;
        width: 2.5em; 
    }
    .cajaicons img:hover{
        transform: scale(1.3);
        filter: hue-rotate(20deg);
}

    }
    .cajaicons img:nth-child(0){
        background: #00aaff;
    }  
    .cajaicons img:nth-child(1){
        background: #007bff; 
    }
    .cajaicons img:nth-child(2){
        background: red; 
    }
    
    
    
    
    </style>
    
<script>
    

    var dataTable = $('#tablecomunic').DataTable({
      language: dataTableTraduccion,  
      stateSave:true,
      "lengthChange": true,
      "responsive": true
    });
     
    //formato de fecha
    
    var  arrayClasif   = (<?=$json_clasificacion?>);
    var  ponderado_ins = '<?=$ponderado_calificacion?>';
    var  id_modo_calificacion_actual ='<?=$id_modo_calificacion_actual?>';
	var  habilitado    ='<?=$habilitado?>';
    var htmlClasificacion = '';
    localStorage();
    generarselectinicial();
    //geenra componentes html del modal seleccion de estados calificacion inicial
    
    function generarselectinicial(){
         for (var i = 0; i < arrayClasif.length; i++) {
             htmlClasificacion+=('<img src="assets/imgs/imginic'+arrayClasif[i]['id_clasificacion_cualitativo']+'.jpg" alt="" num_calificacion="'+arrayClasif[i]['id_clasificacion_cualitativo']+'" onclick="calificarInicial(this)" title="'+arrayClasif[i]['nombre_clasificacion']+'">');
            }  
    }
    
    var tipo_calificacion='';
    var tipo_extra='';
    var nivel_academico_id='';
	var habilitado = '';
    
  // CODIGO  QUINO_:::::::::::::::::::::::::::::::::::::::::::::::::
    var id_aula_asignacion=0;
    var id_bimestre=0;
    
    function listar_estudiantes(){
        id_aula_asignacion = $('#id_materia').val();//id_asignacion_docente         
        id_bimestre = $('#bimestre').val();
    	habilitado  = $('#bimestre').find('option:selected').attr('habilitado');
        
        //if(id_bimestre < id_modo_calificacion_actual){
        if(habilitado == "NO"){
            $('#contenedor_pizarra').fadeTo('slow',.6);
            $('#contenedor_pizarra').append('<div id="bloqueo" style="position: absolute;top:50;left:0;width: 100%;height:100%;z-index:2;opacity:0.4;filter: alpha(opacity = 50)"></div>');
            alertify.error('Modo de calificacion cerrado, comuniquese con administracion de la institucion educativa');
        }else{
             $('#contenedor_pizarra').fadeTo('slow',1);
             $("#bloqueo").attr("style","");
        }
        
        //PARA EL CASO E INICIAL SU ID DEVERA SER 1 OBLIGATORIAMENTE
        //tipo_calificacion=$('option:selected, #id_materia').attr('tipo_calificacion');
       tipo_calificacion=$('#id_materia').find('option:selected').attr('tipo_calificacion');
       tipo_extra=$('#id_materia').find('option:selected').attr('tipo_extra');
       
        //descripcion de tabla
        //$('.tipo_calificacion').text(tipo_calificacion);
        var icontipo_evaluacion='';
        if(tipo_calificacion=='CUANTITATIVO'){
           icontipo_evaluacion='Registo de notas '+tipo_calificacion+' <span class="fa fa-calculator"></span>';
           }else{
           icontipo_evaluacion='Registo de notas '+tipo_calificacion+' <span class="fa fa-comments"></span>';
           }
        $('.tipo_calificacion').html(icontipo_evaluacion);//<span class="fa fa-comment-dots"></span>
           
        if(id_aula_asignacion==0 || id_aula_asignacion==''){
            $('.tablegeneral').find('tbody').html('<tr><td colspan="4">SELECCIONE OPCIóN ARRIBA DE CURSO</td> </tr>');
            //$('.tablegeneral').find('tbody').html('<tr><td colspan="4">SELECCIONE OPCION ARRIBA DE CURSO</td> </tr>');
        }else{
            $('.tablegeneral').find('tbody').html('');
            $('.tablegeneral').find('.trvertical').html('');
            
            $('.tablegeneral').find('.trbtns').html('   <th rowspan="2">#</th>  <th rowspan="2">PATERNO</th>  <th rowspan="2">MATERNO</th>   <th rowspan="2">NOMBRES</th>');
           
            
            $('.tableasistencia').find('tbody').html('');
            $('.tablekardex').find('tbody').html('');
             
        }
        //debugger
        var accion='';
        if(tipo_extra=='EXTRA'){
                accion='listar_estud_extra';
                tipo_extra = 'E'; 
           }else if(tipo_extra=='NORMAL'){
                accion='listar_estud';
               tipo_extra = 'N'; 
           } 
       // alert(tipo_extra);
        $.ajax({
        url: '?/principal/procesos',
        type: 'POST',
        data: {
			'accion': accion,//'listar_estud',
            'id_aula_asignacion':id_aula_asignacion,//id_asignacion_docente
            'id_bimestre':id_bimestre,
             'tipo_extra':tipo_extra
			},
        dataType: 'JSON',
        success: function(resp){
           //alert('res'+resp);
            //var cc=1;
            for (var i = 0; i < resp.length; i++) { 
                $('.tablegeneral').find('tbody').append('<tr estudiante_id="'+resp[i]['estudiante_id']+'" id_estudiante_modo_observacion="'+resp[i]['id_estudiante_modo_observacion']+'" valoracion_cualitativa="'+resp[i]['valoracion_cualitativa']+'"><td>'+(i+1)+'</td>   <td>'+resp[i]['primer_apellido']+'</td>      <td>'+resp[i]['segundo_apellido']+'</td>   <td>'+resp[i]['nombres']+'</td>   </tr>'); 
                //atabla asistencia
                $('.tableasistencia').find('tbody').append('<tr estudiante_id="'+resp[i]['estudiante_id']+'" class="tr_estudiante"><td>'+(i+1)+'</td>   <td>'+resp[i]['primer_apellido']+' '+resp[i]['segundo_apellido']+' '+resp[i]['nombres']+'</td> <td class="datos">acciones o est</td>   </tr>'); 
                //tabla de kardex ya se lista abajo en listarEstudiantesKardex()
                 //$('.tablekardex').find('tbody').append('<tr estudiante_id="'+resp[i]['estudiante_id']+'" class="tr_estudiante"><td>'+(i+1)+'</td><td>'+resp[i]['primer_apellido']+'</td><td>'+resp[i]['segundo_apellido']+'</td><td>'+resp[i]['nombres']+'</td> <td class="eventos"><button class="fas fa-trophy" onclick="kardex1(this)" title="Felicitaciones"></button><button class="fas fa-envelope" onclick="kardex2(this)" title="Citaciones"></button><button class="fas fa-gavel" onclick="kardex3(this)" title="sanciones"></button><button class="fas fa-eye" onclick="kardex3(this)" title="sanciones"></button><button class="fas fa-print" onclick="kardex3(this)" title="sanciones"></button></td>   </tr>'); // fas fa-gavel fas fa-frown
                
            }
            //debugger;
            //alert('sucees');
        }
	 }).done(function(){
            //alert('dionee');
           listar_areas();
           listarasistencia();
           listarEstudiantesKardex();//
           listarComunicados()
           
        });
    }
    
    function listarEstudiantesKardex(){
      
        asignacion_docente_id=$('#id_materia').val();
        id_bimestre=$('#bimestre').val();
        $.ajax({
        url: '?/principal/procesos',
        type: 'POST',
        data: {
				'accion': 'listar_estud_kardex',//',
                'asignacion_docente_id':asignacion_docente_id,//
                'id_bimestre':id_bimestre,
                'tipo_extra':tipo_extra
			},
        dataType: 'JSON',
        success: function(resp){
			 console.log('listar estudaites KARDEX resp');
              $('.tablekardex').find('tbody').html('');
           // debugger;
              for (var i = 0; i < resp.length; i++) {
                //console.log(resp[i]['citaciones']);

                var datos=resp[i]['estudiante_id']+'*';
                var estFelicitaciones='<div class="btn btn-light" style="width: 100%;" onclick="felicitacion('+"'"+datos+"'"+')" title="Nueva Felicitaciones"> <span class=" fas fa-trophy"  ></span><span class="badge badge-success">'+resp[i]['felicitaciones']+'</span><span class="lineaest bac"></span><div class="progress" style="height: .5em;">   <div class="progress-bar bg-success" style="width:'+resp[i]['felicitaciones']+'0%" role="progressbar"> </div> </div></div>';

                var estCitaciones='<div class="btn btn-light" style="width: 100%;" onclick="citacion('+"'"+datos+"'"+')"  title="Nueva Citacion"><span class="fas fa-frown"  ></span><span class="badge badge-warning">'+resp[i]['citaciones']+'</span><span class="lineaest bac"></span><div class="progress" style="height: .5em;">   <div class="progress-bar bg-warning" style="width:'+resp[i]['citaciones']+'0%" role="progressbar"> </div> </div></div>';
                var estSanciones='<div class="btn btn-light" style="width: 100%;" onclick="sancion('+"'"+datos+"'"+')" title="Nueva Sancion"><span class="fas fa-gavel"  ></span><span class="badge badge-danger">'+resp[i]['sanciones']+'</span><span class="lineaest bac"></span><div class="progress" style="height: .5em;">   <div class="progress-bar bg-danger" style="width:'+resp[i]['sanciones']+'0%" role="progressbar"> </div> </div></div>';

                var accions ='  <button class="btn fas fa-eye" onclick="verEstCardex('+resp[i]['id_inscripcion']+')" title="Ver historial"></button><button class="btn fas fa-print" onclick="printCardex('+resp[i]['id_inscripcion']+')" title="sanciones"></button>';

                $('.tablekardex').find('tbody').append('<tr estudiante_id="'+resp[i]['estudiante_id']+'" class="tr_estudiante"><td>'+(i+1)+'</td><td>'+resp[i]['primer_apellido']+' '+resp[i]['segundo_apellido']+' '+resp[i]['nombres']+'</td><td>'+estFelicitaciones+'</td><td>'+estCitaciones+'</td><td>'+estSanciones+'</td> <td class="eventos">'+accions+'</td></tr>'); // fas fa-gavel fas fa-frown
            }//

        }
         }).done(function(){
            //alert('estudaites list');
           //listar_areas();
           //listarasistencia();
        });



    }

    
//::::::::::KARDEX::::::::::::..
    
    var idest=0;//usado en actaulizar datos de cardex
    function verEstCardex(id){
      //  alert('est 3');
         idest=id;//usado en actaulizar datos de cardex(modal_cardex)

      $('.tablekardex').hide();
      $('.tablekardexEst').show();
        id_bimestre=$('#bimestre').val(); 
         
        //LLENAR
        $.ajax({
        url: '?/principal/procesos',
        type: 'POST',
        data: {
				'accion': 'historialcardex', 
                'id_inscripcion':id,
                 'id_bimestre':id_bimestre
			},
        dataType: 'JSON',
        success: function(resp){
        $('.cd-timeline__container').html(' ');
        for (var i = 0; i < resp.length; i++) {
               //console.log(resp[i]['fecha']);
               var id_evento=resp[i]['id_evento'];
               var fecha=resp[i]['fecha'];
               var motivo=resp[i]['motivo'];
               var descripcion=resp[i]['descripcion'];
               var tipo=resp[i]['tipo'];
               var classtipo='';
               var classicon='';
            if(tipo=='ci')//SA FE
            {
                classtipo='tipoCitacion';
                classicon='fas fa-frown';
            }else if(tipo=='sa'){
                classtipo='tipSancion';
                classicon='fas fa-gavel';
            }else if(tipo=='fe'){
                classtipo='tipofelicitacion';
                classicon='fas fa-trophy';
            }
            var datos=fecha+'*'+motivo+'*'+descripcion+'*'+tipo+'*'+id_evento;

                var html='<div class="cd-timeline__block js-cd-block"><div class="cd-timeline__img cd-timeline__img--picture js-cd-img '+classtipo+'">   <span class="'+classicon+'"></span>    </div>    <div class="cd-timeline__content js-cd-content"> <h3>'+motivo+' <a href="#0" class="btn btn-link btn-xs " onclick="vermas(this)">ver mas</a> </h3><p style="display:none"> '+descripcion+' </p>  <span class="cd-timeline__date">'+fecha+' </span> <span class="float-right"> <a href="#" onclick="editA('+"'"+datos+"'"+')" class="btn btn-primary btn-sm fa fa-edit"> </a></span>   </div>   </div>';
                $('.cd-timeline__container').append(html);
            //<a href="#" class="btn btn-primary btn-sm fa fa-trash"> </a>

            }//

        }
         });

    }

function editA(datos){

    var arrayd=datos.split('*');
var tipo=arrayd[3];
var id_evento=arrayd[4];
//alert(id_evento);
 var tipoaccion='';
//alert('datos :  '+arrayd[3]);

if(tipo=='sa'){
	$("#modal_sancion").modal("show");
	tipoaccion='datosmodalSancion';
}else if(tipo=='ci'){
	$("#modal_citacion").modal("show");
	tipoaccion='datosmodalCitacion';
}else if(tipo=='fe'){
	$("#modal_felicitacion").modal("show");
	tipoaccion='datosmodalFelicitacion';
}
$.ajax({
url: '?/principal/procesos',
type: 'POST',
data: {
		'accion': tipoaccion,
		'id_comunicado':id_evento
},
dataType: 'JSON',
success: function(resp){

	for (var i = 0; i < resp.length; i++) {
	//cargar a modalver
        if(tipo=='sa'){
            $('#id_sancion').val(resp[i]['id_sancion']);
            $('#motivo').val(resp[i]['motivo']);
            $('#dias').val(resp[i]['dias_suspencion']);
            var traer=resp[i]['traer_tutor'];
            if(traer=="1"){
               // $('#sintutor').attr("selected");
                  $('#contutor').prop("checked",true);
             //$('#contutor').removeAttr("selected");
               
             }else if(traer=="0"){
              $('#sintutor').prop("checked",true);
                 //$('#contutor').atrr("checked","checked");
             }
            
            $('#fecha_asistir').val(resp[i]['fecha_traer_tutor']);
             
            
        }else if(tipo=='ci'){
            $('#id_citacion').val(resp[i]['id_citacion']);
            $('#motivo_ci').val(resp[i]['motivo']);
            $('#fecha_ci').val(resp[i]['fecha_asistencia']);

        }else if(tipo=='fe'){
            $('#id_felicitacion').val(resp[i]['id_felicitaciones']);
            $('#motivo_feli').val(resp[i]['motivo']);
            $('#descripcion').val(resp[i]['descripcion']);
            $('#fecha_felicitacion').val(resp[i]['fecha_felicitacion']);

        }


	 }//
	}
 });

}
    //Registro de Felicitacion a determinado estudiante
/*<?php// if ($permiso_felicitacion) : ?>*/
	function felicitacion(contenido){
		$("#modal_felicitacion").modal("show");
		$("#form_felicitacion")[0].reset();
		var d = contenido.split("*");
		$("#id_estudiante").val(d[0]);
		$("#id_profesor_materia").val($('#id_materia').val());
		$("#modo_calificacion_id").val($('#bimestre').val());
        //$("#id_profesor_materia").val(d[4]);
 
	}
/*<?php// endif ?>*/

//Registro de Citacion a determinado estudiante
/*<?php// if ($permiso_citacion) : ?>*/
	function citacion(contenido){
		$("#modal_citacion").modal("show");
		$("#form_citacion")[0].reset();
		var d = contenido.split("*");
		$("#id_estudiante_c").val(d[0]);
		$("#id_profesor_materia_c").val($('#id_materia').val());
        $("#modo_calificacion_id_c").val($('#bimestre').val());
		
	 
	}
/*<?php// endif ?>*/

//Registro de Sancion a determinado estudiante
/*<?php// if ($permiso_sancion) : ?>*/
	function sancion(contenido){
		//console.log( id_estu+" / "+ id_profesor_mate);
		$("#modal_sancion").modal("show");
		$("#form_sancion")[0].reset();
		var d = contenido.split("*");
		$("#id_estudiante_s").val(d[0]);
		$("#id_profesor_materia_s").val($('#id_materia').val());//val(d[4]);
        $("#modo_calificacion_id_s").val($('#bimestre').val());
		//console.log(contenido);
	 
	}
/*<?php// endif ?>*/
  
   function btnAtrasCardex(obj){
      //  alert('est 3');
      $('.tablekardex').show();
      $('.tablekardexEst').hide();
     
    } 
    
   function printCardex(id_insc){
        
       // alert('PRINT 3');
        //reporte de cardex
         var id_aula_asignacion=$('#id_materia').val();
        var id_modo=$('#bimestre').val();
        //enviara a excel
        //$(obj).find('span').hide();//html('EN PROCESO...');
        //$(obj).find('p').show(); 
        $(location).attr('href','?/principal/excel-cardex-est/'+id_aula_asignacion+'/'+id_insc+'/'+id_modo);
        
        //excel_cardex_est.php
        
    }  
    
   function impKardexExcel(){
        
         var id_aula_asignacion=$('#id_materia').val();
        var id_modo=$('#bimestre').val();
        //enviara a excel
        //$(obj).find('span').hide();//html('EN PROCESO...');
        //$(obj).find('p').show(); 
        $(location).attr('href','?/principal/excel-cardex-todos/'+id_aula_asignacion+'/'+id_modo);
         
    }   
   
    //:::::: NOTAS ::::::::::::::::::
    
    //boton de agregar nuevatarea y el promedio general y comentario cualitativo
    function listar_areas(){
         var id_modo_calificacion=$('#bimestre').val(); 
		 //var id_profesor_materia=$('#id_profesor_materia').val();
		//var id_profesor=<?= $id_profesor;?>;
         
        //listar_areas
         $.ajax({
        url: '?/principal/procesos',
        type: 'POST',
        data: {
				'accion': 'listar_areas', 
				//'nivel_academico_id': nivel_academico_id//,
				'modo': id_modo_calificacion 
			},
        dataType: 'JSON',
        success: function(resp){
        
        for (var i = 0; i < resp.length; i++) {
            
            //console.log(resp[i]['descripcion']+' '+resp[i]['id_area_calificacion']);
            //CAEBCERAS AREAS '+resp[i]['obtencion_nota']+'
            $('.trbtns').append( '<th colspan="1"> '+resp[i]['descripcion']+' '+resp[i]['ponderado']+'<br><span class="fa fa-plus btn bg-secondary" title="Nueva Tarea" onclick="modalnuevo(this,'+"'"+resp[i]['id_area_calificacion']+"'"+')" area_head_id="'+resp[i]['id_area_calificacion']+'" modo_area_id="'+resp[i]['id_modo_calificacion_area_calificacion']+'" area_obtencion_nota="'+resp[i]['obtencion_nota']+'"></span></th>');
            
            $('.trvertical').append( '<th class="vertical '+resp[i]['id_area_calificacion']+'prom"><div class="vertical">Promedio</div></th>');
            
            //RECORR CUERPO, COL DE  PROMEDIOS
             $('.tablegeneral').find(".tbody tr").each(function(){
             var estudiante=$(this).attr('estudiante_id'); 
            $(this).append('<td class="'+resp[i]['id_area_calificacion']+'prom prom"><p area_estudiante_prom="'+resp[i]['id_area_calificacion']+'-'+estudiante+'"  area_ponderacion="'+resp[i]['ponderado']+'">-</p></td>');  
            });
            
        }
            //CABECERAS DE OTROS Y PORMEDIO
            $('.trbtns').append( '<th>COMENTARIO</th> <th>PROMEDIO</th>');  
            $('.trvertical').append( '   <th></th> <th></th> '); 
            
            //agregar COMENTATRIO y PROMEDIO GENERAL
             $('.tablegeneral').find(".tbody tr").each(function(){
              var estudiante=$(this).attr('estudiante_id'); 
              var valoracion_cualitativa=$(this).attr('valoracion_cualitativa'); //si tiene o no
              var id_estudiante_modo_observacion=parseInt($(this).attr('id_estudiante_modo_observacion')); //si tiene o no
            var tipobtn='';
             if(isNaN(id_estudiante_modo_observacion)){
                tipobtn='btn-success fa fa-plus';
                }else{
                tipobtn='btn-warning fa fa-edit';
                
                }
            $(this).append( '<td><span class="btn '+tipobtn+'" onclick="comentarMateriaEst('+estudiante+')"></span></td><td class="promGeneral">00</td>');
            });
             
	 }
	 }).done(function(){
             listar_tareas();
             
             $('#bimestre').focus();
         });
    }
    
    //listar_tareas();
    function listar_tareas(){
        //$('.prom').siblings().remove();
        
        
        
        var asignacion_docente_id=$('#id_materia').val();//ASIGNA_docente_id
        //var id_area_calificacion=id;
		var id_modo_calificacion=$('#bimestre').val();
         //alertify.warning('tareas id_aula_asignacion'+id_aula_asignacion);
		// var id_aula_paralelo=$('#id_aula_paralelo').val();
		//var id_profesor_materia=$('#id_profesor_materia').val();
         $.ajax({
        url: '?/principal/procesos',
        type: 'POST',
        data: {
			accion: 'listar_actividades',  
            id_modo_calificacion:id_modo_calificacion, 
            asignacion_docente_id:asignacion_docente_id,
            tipo_extra:tipo_extra
			},
        dataType: 'JSON',
        success: function(resp){
            console.log(resp);
            for (var i = 0; i < resp.length; i++) {
            
               //alertify.success(resp[i]['descripcion']);
                var id_area_cal=resp[i]['area_calificacion_id']; //resp[i]['id_area_calificacion'];
                var descripcion_area=resp[i]['area_calificacion_id'];//resp[i]['descripcion'];
                var id_tareaT=resp[i]['id_asesor_curso_actividad'];//resp[i]['id_actividad_materia_modo_area'];
                var nombre_actividad=resp[i]['nombre_actividad'];
                var desc_act=resp[i]['descripcion_actividad'];
                var fecha_act=resp[i]['fecha_presentacion_actividad'];//resp[i]['fecha_presentacion'];
                var bloqueado='N';//resp[i]['bloqueado'];
               // alert(bloqueado);
                //debugger;
                
                newtareaRec(id_area_cal,descripcion_area,id_tareaT,nombre_actividad,desc_act,fecha_act,bloqueado);
            
                //console.log(resp[i]['id_actividad_materia_modo_area']);
            }
	   }
	 }).done(function(){
        //alertify.warning('done tareas');
        
           cargar_notas();
         });
    }
 
    //agrega automaticamente al cargar
    //AGREGAR IMPUTS CON DATOS Y ATRR PROPIOS
    function newtareaRec(id_area,tipo,id_tareaT,nombre,desc,fecha,bloqueado){
       // alert($(x).parent().attr('colspan'));
        //var tipo=$(obj).parent().text();
        $obj=$('[area_head_id='+id_area+']');
        //var area_id=$($obj).attr('modo_area_id');//('area_id');//de bd si
        colspan_ant=parseInt($obj.parent().attr('colspan'));//3
        var colspan_new=colspan_ant+1;//4 
        
        var datos=id_tareaT+'*'+nombre+'*'+desc+'*'+fecha;
        //creamos cabeceras de cada tarea antes de cada promedio
        $('thead').find("."+tipo+'prom').before('<th  class="vertical  '+tipo+colspan_ant+'" title="'+nombre+'" onclick="modalver('+"'"+datos +"'"+')"><div class="vertical" >'+nombre+'</div></th>');
        
        
        $('[area_head_id='+id_area+']').parent().attr('colspan',colspan_new); 
        
        //debugger;
        //$('tbody').find("."+tipo+'prom').before('<td  class="'+tipo+colspan_ant+'"> <input type="text" class="inpmov"> </td>'); 
        
        //ARRAY LOS ESTUDIANTES Y CARGARLOS CON NOTAS
        
        $(".tbody tr").each(function(){
             
            var estudiante=$(this).attr('estudiante_id');
            var clasprom=tipo+'prom';
            
            //alert('aqui tipo:-'+clasprom+'-');
            if(tipo_calificacion=='CUANTITATIVO'){
                
                if(bloqueado=='S'){
                   
                $(this).find("."+clasprom).before('<td  class="'+tipo+colspan_ant+'"> <input disabled type="text" class="inpmov"   area_estudiante="'+id_area+'-'+estudiante+'" area_id="'+id_area+'" estudiante_id="'+estudiante+'" actividad_id="'+id_tareaT+'"  estudiante_tarea="'+estudiante+'-'+id_tareaT+'" value="0"> </td>');
                
                   }else{
                $(this).find("."+clasprom).before('<td  class="'+tipo+colspan_ant+'"> <input type="text" class="inpmov" onFocusout="keyupimp(this)"  area_estudiante="'+id_area+'-'+estudiante+'" area_id="'+id_area+'" estudiante_id="'+estudiante+'" actividad_id="'+id_tareaT+'"  estudiante_tarea="'+estudiante+'-'+id_tareaT+'" value="0"> </td>');
                       
                   }
                
            }else{
                if(bloqueado=='S'){
                   //SI BLOQUEADO
                    $(this).find("."+clasprom).before('<td  class="'+tipo+colspan_ant+'" style="filter: grayscale(1);"><div  class="btncaja"><div class="reaccion" reaccionbd="0" area_estudiante="'+id_area+'-'+estudiante+'" area_id="'+id_area+'" estudiante_id="'+estudiante+'" actividad_id="'+id_tareaT+'"  estudiante_tarea="'+estudiante+'-'+id_tareaT+'">-</div>  </div></td>');//btncaj.btn
                }else{
                     //NIO  BLOQUEADO
                       $(this).find("."+clasprom).before('<td  class="'+tipo+colspan_ant+'" style="position:relative"><div  class="btncaja "><div class="reaccion" reaccionbd="0" area_estudiante="'+id_area+'-'+estudiante+'" area_id="'+id_area+'" estudiante_id="'+estudiante+'" actividad_id="'+id_tareaT+'"  estudiante_tarea="'+estudiante+'-'+id_tareaT+'">-</div> <div class="cajaicons" >'+htmlClasificacion+'<input type="text" placeholder="escribe algo" num_calificacion="0" onFocusout="calificarInicial(this)" > </div></div></td>');//btncaj.btn       
                }
               
                
                //<input type="button" class="inpmov btn" onkeyup="keyupimp(this)"  area_estudiante="'+id_area+'-'+estudiante+'" area_id="'+id_area+'" estudiante_id="'+estudiante+'" actividad_id="'+id_tareaT+'"  estudiante_tarea="'+estudiante+'-'+id_tareaT+'" value="0">
            }
            //alertify.success('aqui CARGAR NOTAS CADA ESTUDAINTE Y TAREA');
            //$("[estudiante_id="+ estudiante +"]").find("."+ tipo +'prom').before('<td  class="'+tipo+colspan_ant+'"> <input type="text" class="inpmov"> </td>');
        });
        
           // alert('<td  class="'+tipo+colspan_ant+'"> <input type="text" class="inpmov" onkeyup="keyupimp(this)"  area_estudiante="'+area_id+'-'+estudiante+'" area_id="'+area_id+'" tarea_id="'+colspan_ant+'" > </td>');
        
        
       // $obj.parent().attr('colspan',colspan_new); 
        
 
    }
    
    function cargar_notas(){
        var asignacion_docente_id=$('#id_materia').val(); //asignacion_docente_id
		var id_modo_calificacion=$('#bimestre').val();
        $.ajax({
            url: '?/principal/procesos',
            type: 'POST',
            data:{
                accion: 'listar_notas_est',
                id_modo_calificacion:id_modo_calificacion, 
                asignacion_docente_id:asignacion_docente_id
                },
            dataType: 'JSON',
            success: function(resp){
           // alertify.warning('hola'+resp);
                console.log(resp);
                //debugger;
                for (var i = 0; i < resp.length; i++) {
                    //alert(resp[i]['nota_cualitativa']);
                    var nota_cualitativa=resp[i]['nota_cualitativa'];
                    var nota_cuantitativa=resp[i]['nota'];
                    if(nota_cuantitativa=='0' && nota_cualitativa!='' ){
                       //nota_cualitativa
                       var est_tarea=resp[i]['estudiante_id']+'-'+resp[i]['id_asesor_curso_actividad'];
                        var htmlresult='';
                        //if(resp[i]['nota_cualitativa']==0){
                        //    htmlresult=resp[i]['nota_cualitativa'];
                        //}else 
                        if(nota_cualitativa==1 || nota_cualitativa==2 || nota_cualitativa==3 ||nota_cualitativa==4){
                            htmlresult='<img src="assets/imgs/imginic'+nota_cualitativa+'.jpg" alt="" num_calificacion="'+nota_cualitativa+'">';
                            
                        }else{
                            htmlresult=nota_cualitativa;
                            //alert('vargando notas'+htmlresult);
                        }
                        $('.tablegeneral').find("[estudiante_tarea="+est_tarea+"]").html(htmlresult);
                        
                        
                        
                       }else{
                        var est_tarea=resp[i]['estudiante_id']+'-'+resp[i]['id_asesor_curso_actividad'];
                        //alertify.success(est_tarea);
                        //   debugger;
                        $('.tablegeneral').find("[estudiante_tarea="+est_tarea+"]").val(nota_cuantitativa);
                        //nota cuantitativas
                        $obj=$('.tablegeneral').find("[estudiante_tarea="+est_tarea+"]");
                        promediosimp($obj);
                           
                       }                    
                    
                    //console.log(resp[i]['id_actividad_materia_modo_area']);
                }
            }
        }); 
    }
    //agregar nuva campo de notas por el boton de cada area
    var objV;
    var id_area_calificacionV=0;
    function modalnuevo(obj,id_area_calificacion){
        //tipo_calificacion
        //tipo_extra
        objV=obj;
        id_area_calificacionV=id_area_calificacion;
        var area=$(obj).attr('area_obtencion_nota'); //limpia todo el formulario
        if(area!='D'){
            
            var modo=$("#bimestre").val(); 
            var asignacion_docente_id=$("#id_materia").val(); 
            //var tipo_curso=$("#tipo_curso").val(); 
            //alert(tipo_curso);
            $("#tipo_extra_m").val(tipo_extra);
            $("#asignacion_docente_id").val(asignacion_docente_id);
            $("#id_area_calificacion").val(id_area_calificacion);
            $("#id_modo_calificacion_e").val(modo);
            
            $("#modal_agregar_evento").modal("show");//completo
           
        }else{
           //ABRIR CREAR NORMAL SIMPLE
            $("#form_actividad")[0].reset(); //limpia todo el formulario
           //prepara la modal para crear la actividad 
       
            $("#tipo_extra_simple").val(tipo_extra);//SIMPLE
            
            $("#modal_actividad").modal("show"); 
            
            $("#titulo_actividad").text("Crear Actividad"); //pone el titulo
            $("#btn_nuevo").show(); //muestra el boton registrar
            $("#btn_modificar").hide();	//oculta el boton editar
            $("#btn_eliminar").hide();
            $("#btn_vista_aEdit").hide();
            $(".impcomp").show();//mostrar los imputs
            $(".pcomp").hide();//ocultar texto vista
            //debugger;
           //newtarea(objV,tipoV);
        }
    }
    
    function modalver(datos){
        //id_area_calificacion
        //descripcion
        //id_actividad_materia_modo_area
        //nombre_actividad
        //descripcion_actividad
        //fecha_presentacion
        var d=datos.split('*');
        //llamado boton creado en listar tareas
        //carga valores a los imputs
        $('#id_actividad_materia_modo_area').val(d[0]);
        $('#nombre_actividad').val(d[1]);
        $('#descripcion_actividad').val(d[2]);
        $('#fecha_presentacion').val(d[3]);  
        
       //carga valores a vista
        $('.nombre_actividad').text(d[1]);
        $('.descripcion_actividad').text(d[2]);
        $('.fecha_presentacion').text(d[3]);
        
        
        $("#modal_actividad").modal("show"); 
		$("#titulo_actividad").text("Vista Actividad"); //pone el titulo
		//$("#btn_nuevo").show(); //muestra el boton registrar
		$("#btn_eliminar").show();	//oculta el boton elim,inar
		$("#btn_vista_aEdit").show();	//oculta el boton elim,inar
		$("#btn_nuevo").hide();	//oculta el boton editar
		$("#btn_modificar").hide();	//oculta el boton editar
        //ocultar imputs
        
		$(".impcomp").hide();	//oculta el boton editar
		$(".pcomp").show();	//oculta el boton editar
    }
    
    function vista_aEdit(){
        //e.preventDefault();
        $("#btn_eliminar").show();	//oculta el boton elim,inar
		$("#btn_vista_aEdit").hide();	//oculta el boton elim,inar
		$("#btn_nuevo").hide();	//oculta el boton editar
		$("#btn_modificar").show();	//oculta el boton editar
        //ocultar imputs 
		//$(".impcomp").show();	//oculta el boton editar
		//$(".pcomp").hide();
        ///$(".pcomp").fadeOut("slow"); 
        ///$(".impcomp").fadeIn("slow"); 
        $(".pcomp").slideUp("slow"); 
        $(".impcomp").slideDown("slow");
        return false;
    }
    
    function abrir_eliminar(){
        var idactiv=$('#id_actividad_materia_modo_area').val();
        //preguntar si se eliminara?
         alertify.confirm('<span style="color:red">ELIMINAR ACTIVIDAD</span>', 'Esta accion eliminara todas las notas de esta actividad ¿Desea eliminar?', function(){ //casi de si
           //  debugger;
            $.ajax({
                url: '?/principal/procesos',
                type:'POST',
                data: {accion:'eliminar_dato',
                   'id_componente':idactiv},
                success: function(resp){
                    // alert(resp);
                    switch(resp){
                        case '1': $("#modal_actividad").modal("hide");
                        alertify.success('Se elimino el horario correctamente');
                        listar_estudiantes();
                         break;
                        case '2': $("#modal_actividad").modal("hide");
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
    }
    
    //$('.input-number').on('input', function () { 
    //this.value = this.value.replace(/[^0-9]/g,'');
    //});
    function keyupimp(obj){ 
        $(obj).val($(obj).val().replace(/[^0-9]/g,''));
        var nota=$(obj).val(); 
        var area_estudiante=$(obj).attr("area_estudiante");
        //alert(ponderado_ins);
        //verificar que tiop de notas es = ponderado
        if(!isNaN(nota)){
            if(ponderado_ins=='A'){
                var notamax=parseInt($("[area_estudiante_prom="+area_estudiante+"]").attr('area_ponderacion')); 
                //alertify.warning('A:'+notamax);  
                if(nota<0 || nota>notamax ){
                    //error
                    alertify.warning('Deve ser mayor a 0 y menor a '+notamax);
                    $(obj).css('background','#ff407b');
                     return false; 
                }else{
                    //verdad guarda
                    $(obj).css('background','#f8ff87');
                    promediosimp(obj);
                    //debugger;
                    idsdecelda(obj);
                }
           
           }else{
               alertify.warning('P');   
                //alertify.success('ok');
                if(nota<0 || nota>100 ){
                    alertify.warning('Deve ser mayor a 0 y menor a 100');
                    $(obj).css('background','#ff407b');
                     return false; 
                }else{ 
                promediosimp(obj);
                idsdecelda(obj);

                 }


           }
       }else{
         alertify.warning('Introduce un numero');   
            return false;  
       }
        /*var area_estudiante=$(obj).attr('area_estudiante'); 
        var promedio=0;
        var sumanotas=0;
        var cantidadDatos=0; 
        $("[area_estudiante="+area_estudiante+"]").each(function(){
            //alert($(this).val());
            if($(this).val()!='' && $(this).val()!=0){
                cantidadDatos++; 
                sumanotas= sumanotas + parseInt($(this).val()); 
               } 
         });
        
         promedio=Math.round(sumanotas/cantidadDatos);
        //x = Math.round(20.49);
        $("[area_estudiante_prom="+area_estudiante+"]").text(promedio) 
        //alert('aqui key'+valor);
        //promedio de promedios prom
        var sumaprom=0;
        var cantidadProm=0;
        
        $(obj).parent().parent().find('.prom').each(function(){
            var valor=$(this).text();
            if(valor!='' && valor!=0 && !isNaN(valor) && $.isNumeric(valor)){
           //fd  true       true            true            false 
                cantidadProm++; 
                sumaprom= sumaprom + parseInt($(this).text());
               
               } 
         });
        
        promedioProm=Math.round(sumaprom/cantidadProm);
        //x = Math.round(20.49);
        $(obj).parent().parent().find('.promGeneral').text(promedioProm);*/  
    }
    
    function promediosimp(obj){
        
        var area_estudiante=$(obj).attr('area_estudiante');
        //$(obj)//area-id estudiante-id
        var promedio=0;
        var sumanotas=0;
        var cantidadDatos=0;
        //promedios de area
        var notamax=$("[area_estudiante_prom="+area_estudiante+"]").attr('area_ponderacion'); 
        //alert(notamax);
        $("[area_estudiante="+area_estudiante+"]").each(function(){
            //if($(this).val()!='' && $(this).val()!=0){
        
            var nota=0;
            if($(this).val()!=''){
                nota=parseInt($(this).val());
            }else{
                nota=0;
            }
            
                cantidadDatos++; 
                sumanotas= sumanotas + nota; 
            //   } 
         }); 
        
                
        if(ponderado_ins=='A'){
           
              promedio=Math.round(sumanotas/cantidadDatos); 
           }else{
              promedio=Math.round((sumanotas/cantidadDatos)*notamax/100); 
               
           }
         //segun promedio ponderado en pts a cada trabajao
        
        //segun promedio ponderado 100%
        $("[area_estudiante_prom="+area_estudiante+"]").text(promedio);  
        
        //promedio de promedios prom
        var sumaprom=0;
        var cantidadProm=0; 
        $(obj).parent().parent().find('.prom').each(function(){
            var valor=$(this).text();
            if(valor!='' && valor!=0 && !isNaN(valor) && $.isNumeric(valor)){
           //fd  true       true            true            false 
                cantidadProm++; 
                sumaprom= sumaprom + parseInt($(this).text()); 
               } 
         }); 
        
        promedioProm=sumaprom;//Math.round(sumaprom/cantidadProm); 
        $(obj).parent().parent().find('.promGeneral').text(promedioProm); 
        //idsdecelda(obj);
    }
    //guarda en cada click las notas
    function idsdecelda(obj){
        //var persona=$(obj).parent().parent().attr('estudiante_id');  
        //var tarea=$(obj).attr('estudiante_tarea'); 
        $(obj).css('background','#fff1ab'); 
        var estudiante_id=$(obj).attr('estudiante_id'); 
        var area=$(obj).attr('area_id'); 
        var actividad_mat_id=$(obj).attr('actividad_id'); 
        var nota=$(obj).val(); 
  
        //console.log(nota);
       /* $('.txt_persona_id').text(estudiante_id);
        $('.txt_area_id').text(area);
        $('.txt_tarea_id').text(actividad_mat_id);*/
        // debugger;
        $.ajax({
        url: '?/principal/procesos',
        type: 'POST',
        data: {
			'accion': 'registrar_nota',  
            estudiante_id:estudiante_id, 
            actividad_mat_id:actividad_mat_id,
            nota:nota 
            },
        dataType: 'text',
        success: function(resp){
            if(resp=='bloqueado'){
                alertify.error('Tarea blokeada para cambios'); 
            }else
            if(resp=='update' || resp=='crear'){
                alertify.success('Nota actualizada'); 
            }else{
                alertify.warning('Revise que su nota se haya guardado');
                 $(obj).css('background','#ffadf9'); 
            }
            //for (var i = 0; i < resp.length; i++) {
            
                //newtareaRec(resp[i]['id_area_calificacion'],resp[i]['descripcion'],resp[i]['nombre_actividad'],resp[i]['id_actividad_materia_modo_area']);
            
                //console.log(resp[i]['id_actividad_materia_modo_area']);
            //}
	       }
	   });
    }
    
    function abrir_crear(){
		
		//prepara la modal para crear la actividad
		//$("#form_actividad")[0].reset(); //limpia todo el formulario
		$("#modal_actividad").modal("show"); //abre el modal
		$("#titulo_actividad").text("Crear Actividad"); //pone el titulo
		$("#btn_nuevo").show(); //muestra el boton registrar
		$("#btn_modificar").hide();	//oculta el boton editar
		
	}
    function  impNotasExcel(){//(obj,id_aula_paralelo)
        //recojer datos
        var id_asignacion_docente=$('#id_materia').val();
        var id_modo=$('#bimestre').val();
        //enviara a excel
        //$(obj).find('span').hide();//html('EN PROCESO...');
        //$(obj).find('p').show();
        //var tipo_cal=0;
        //if(tipo_calificacion=='CUALITATIVO'){
        //   tipo_cal=11;
        //   }else{
        //   tipo_cal=12;
        //   }
        
        $(location).attr('href','?/principal/excelnotas/'+id_asignacion_docente+'/'+id_modo);//+'/'+tipo_cal);
    
    }
    
//:::::general
    function oculta_calendar(){
       //alert('aqui');
       
       if($('.calendar').hasClass('hidee')){
           //caelndario visible
           $('.tabs').removeClass('complet');//complet
           $('.calendar').removeClass('hidee');//show
            sessionStorage.setItem('calendarVisible','true'); //
           
       }else{ 
           //caelndario oculto
           $('.tabs').addClass('complet');
           $('.calendar').addClass('hidee');
            sessionStorage.setItem('calendarVisible','false'); 
       }
       //localStorage.visitas = 1;
        //localStorage["color_fondo"] = "yellow";
        //localStorage.setItem("color_texto", "green");
        
        
       //$('.calendar').<span onclick="btnbars()" class="btnhidemenu"><i class="btn fa fa-bars" style="font-size:22px"></i> </span>
   }
    function localStorage(){
        
        var calendarVisible = sessionStorage.getItem("calendarVisible");
        //alert(calendarVisible);
        if(calendarVisible=='true' || calendarVisible==null){
               $('.tabs').removeClass('complet');//complet
               $('.calendar').removeClass('hidee');//show
                sessionStorage.setItem('calendarVisible','true'); //
           }else{
              //caelndario oculto
               $('.tabs').addClass('complet');
               $('.calendar').addClass('hidee');
                sessionStorage.setItem('calendarVisible','false');   
           }
    }
    
    function calificarInicial(obj){
       // alert('hola');
       var valor_calificacion= parseInt($(obj).attr('num_calificacion'));
        if(valor_calificacion>=1 && valor_calificacion<=4){
            $(obj).parent().siblings('.reaccion').html('<img src="assets/imgs/imginic'+valor_calificacion+'.jpg" alt="" num_calificacion="'+valor_calificacion+'">');
 
           }else if(valor_calificacion==0){// 
               var valor_calificacion=$(obj).val();   
               if(valor_calificacion==''|| valor_calificacion==0){
                   //console.log('-'+valor_calificacion+'-');
                    return false;
                  }
            $(obj).parent().siblings('.reaccion').html(valor_calificacion);
              
           }
            $(obj).parent().siblings('.reaccion').attr('reaccionbd',valor_calificacion); 
        //alert($(x).parent().attr('estudiante_id')) ;
         var estudiante_id=$(obj).parent().siblings('.reaccion').attr('estudiante_id'); 
        var area=$(obj).parent().siblings('.reaccion').attr('area_id'); 
        var actividad_mat_id=$(obj).parent().siblings('.reaccion').attr('actividad_id'); 
        var nota=valor_calificacion;//$(obj).val(); 
        //    debugger;
        $.ajax({
        url: '?/principal/procesos',
        type: 'POST',
        data: {
			'accion': 'registrar_nota_cualitativa',  
            estudiante_id:estudiante_id, 
            actividad_mat_id:actividad_mat_id,
            nota:nota 
            },
        dataType: 'JSON',
        success: function(resp){
      
	       }
	   });
        
    }
    
    function comentarMateriaEst(idest){
        //alert('est:'+idest+' id_aula_asignacion:'+id_aula_asignacion);
        //id_aula_asignacion
        $("#form_comentario")[0].reset(); //limpia todo el formulario
         $("#form_comentario #id_estudiante_modo_observacion").val(''); 
		$("#modal_comentario").modal("show"); 
        
		$("#id_estudianteC").val(idest); 
		//$("#id_aula_asignacionC").val(id_aula_asignacion); 
		$("#valoracionCualitativa").val(''); 
        
        
        
        $("#btn_nuevoComentario").show();	//oculta el boton elim,inar
		$("#btn_modificarComentario").hide();	//oculta el boton elim,inar
		$(".pcomp").hide();	//oculta el boton elim,inar
		$(".impcomp").show();	//oculta el boton elim,inar
        
        //recuperar datos de
         
         var id_aula_asignacion=$('#id_materia').val();
         var id_bimestre=$('#bimestre').val();
        //cargamos el id est y su valoracion 
        $.ajax({
                  type: 'POST',
                  url: "?/principal/procesos",
                  data: {
                      accion:'ver_valoracion_cualitativa',  
                      id_estudianteC:idest, 
                      id_aula_asignacion:id_aula_asignacion,
                      id_bimestre:id_bimestre
                  },
                dataType: 'JSON',
                  success: function (resp) {
                    //alert(resp.length);
                    //alert(resp['id_estudiante_modo_observacion']);
                    for (var i = 0; i < resp.length; i++) {
                         
                    // console.log(resp[i]['id_estudiante_modo_observacion']);
                     $('#id_estudiante_modo_observacion').val(resp[i]['id_estudiante_modo_observacion']);
                     $('#valoracionCualitativa').val(resp[i]['valoracion_cualitativa']);
                         
                    }
                       if(!resp){
                          alertify.error('No se pudo cargar datos de edicion');
                          }
                  }
            
            });
 
     
    }
    
//::::::: asistencia
    function listarasistencia(){
        contadordeagrgadoFecha=0;//rest para agregar nuaa asistencia
        id_aula_asignacion=$('#id_materia').val();
        id_bimestre=$('#bimestre').val();
        $.ajax({
                  type: 'POST',
                  url: "?/principal/procesos",
                  data: {
                     accion:'asistencia_estudiantes',  
                      //id_estudianteC:idest, 
                     aula_paralelo_asignacion_materia_id:id_aula_asignacion,
                      modo_calificacion_id:id_bimestre,
                      tipo_extra:tipo_extra
                  },
                dataType: 'JSON',
                  success: function (resp) {
                       var cc=true;
                   // RECORRE LAS ESTUDAINTES 
                    $('.tableasistencia').find("thead tr").html( ' <th>#</th>   <th>Apellidos y Nombres</th>  <th class="datos">Estadisticas</th> ');//limpiar tabla
                      for (var j = 0; j < resp.length; j++) {
                        $('.tableasistencia').find('[estudiante_id='+resp[j]['estudiante_id']+']').css('background','#dbfffd');
                       // var json_asistecia=resp[j]['json_asistencia'];
                        var json_asistencia=JSON.parse(resp[j]['json_asistencia']); 
                          //quitamos la ultima coma
                        // var strLength = json_asistecia.length; 
                        // var json_asistecia_limp = json_asistecia.substr(0, strLength - 1);
                        //convertimos en array las fechas
                        //var arrayaist=json_asistecia_limp.split(',');
                          
                        var estudiante_id=resp[j]['estudiante_id'];
                          
                        //console.log(arrayaist);
                         //::::: ::::::: ::::::: cargar fechas::solo 1 vez ::::: :::::::: ::::::::: ::::::: :::::::
                      if(cc){
                            ////
                              //$('.tableasistencia').find("thead tr").html( ' ');
                              //$('.tableasistencia').find("thead tr").html( ' <th>#</th>   <th>Apellidos y Nombres</th>  <th class="datos">Estadistica</th> ');
                             //for (var i = 0; i < arrayaist.length; i++) {
							for (var fechai in json_asistencia) {
                                 //console.log(arrayaist[i].split('@'));
                                 //var fechaassi=arrayaist[i].split('@');
                                 //var fecha=fechaassi[0].split(' ');
								//var ast=json_asistencia[fecha];
                                // debugger;
                                 //agregar titulos solo 1 vez
                                 
                                $('.tableasistencia').find("thead tr").append('<th class="verticalx" title="'+fechai+'"><div class="verticalx text-center"><span class="btn fa fa-edit" onclick="editasist('+"'"+fechai+"'"+')"></span><p>'+fechai+'</p></div></th>');
                                    
                            //console.log(fechaassi[0]);
                                // alert(idest);
                                    $('.tableasistencia').find("tbody tr").each(function(){
                                        //var idest=$(this).attr('estudiante_id');
                                        var estudiante=$(this).attr('estudiante_id'); 
                                        $(this).append('<td class="" fecha_asist="'+fechai+'"  est_asist="'+estudiante+'" fecha-est="'+fechai+'-'+estudiante+'"> <select class="form-control" style="padding: 0;" disabled onchange="actualizar_Asit(this)"><option  value="p" >PRESENTE</option><option value="f" >FALTA</option><option  value="a" >ATRASO</option><option value="l" >LICENCIA</option></select></td>');  //arrayaist[i].split('@')
                                       
                                    });
                                                                  
                                }
                             cc=false;
                        }
                          
                     //::::::::::::::::::::::::::AGREGAR AISTENCIAS  a cada dia :::::::::::::::::::::::::::::::
						  //ARREGLAR
					  for (var fechai in json_asistencia) {
                      //for (var i = 0; i < arrayaist.length; i++) {
                          //var fechaassi=arrayaist[i].split('@');
                          //var fecha=fechaassi[0].split(' ');
                           //console.log(fecha[0]+' usuario='+estudiante_id);
                          
                         $('.tableasistencia').find('tbody').find('[fecha-est='+fechai+'-'+estudiante_id+']').find('select').val(json_asistencia[fechai]); 
                      }
                                                
                    }
                    //console.log(fechaassi[1]);
                     //Estadisticasd de asistencias luego de cargar 
             		actualisar_estadisticas();
                                                                             
                                            
        
                  }//success
        });
        
    }
        
	function actualisar_estadisticas(){
		     $('.tableasistencia').find("tbody tr").each(function(){
                      //contar Faltas=1
                      var sumafaltas=0;
                      var sumaAtraso=0;
                      var sumaPresente=0;
                      var sumaLicencia=0;var cc=1;
                      $(this).find('td').find('select').each(function(){
                         if($(this).val()=='p'){
                              sumaPresente++;
                          }else if($(this).val()=='a'){
                              sumaAtraso++;
                          }else if($(this).val()=='f'){
                              sumafaltas++;
                          }else if($(this).val()=='l'){
                              sumaLicencia++;
                          }  
                          cc++;
                      });
                      var html ='';
                      if(sumaPresente)
                          html+='<span class="fa fa-check badge badge-success" title="Presentes"> '+sumaPresente+'</span>';
                         if(sumaAtraso)
                          html+='<span class="fa fa-clock badge badge-warning" title="Atrasos"> '+sumaAtraso+'</span>';
                       if(sumafaltas)
                            html+='<span class=" fas fa-times badge badge-danger" title="Faltas"> '+sumafaltas+'</span>';
                        if(sumaLicencia)
                           html+=' <span class="fa fa-file badge badge-info" title="Licencias"> '+sumaLicencia+'</span>'
                       $(this).find('.datos').html(html);
                       $(this).find('.datos').css('background','#f1f1f1');
                  });
	}
	function editasist(fecha){
		 $('.tableasistencia').find('[fecha_asist='+fecha+']').find('select').prop('disabled',false);
	}
	function actualizar_Asit(obj){
		var estado=$(obj).val();
		var estudiante=$(obj).parent().attr('est_asist');
		var fecha=$(obj).parent().attr('fecha_asist'); 
		
		//fecha_registro=$('#fecha_registro').val();
        id_aula_asignacion=$('#id_materia').val();
        id_bimestre=$('#bimestre').val();
		 $.ajax({
                url: '?/principal/procesos',
                type: 'POST',
                data: {
                    //accion: 'guardar_asistencia',
					accion: 'actualizar_asistencia',  
                    estado:estado,
                    estudiante_id:estudiante,
					fecha:fecha,
					
                    asignacion_mat_id:id_aula_asignacion,
                    modo_calificacion_id:id_bimestre,
					//estdatos:estdatos, 
                    tipo_extra:tipo_extra

                    },
                //dataType: 'JSON',
                success: function(resp){
					if(resp=='s'){
						alertify.success('Actualizando');
						actualisar_estadisticas();
					}else{
						alertify.error('No se pudo actualizar');
					}
				}
		 });
		
 		
					
		
	}
	
    var contadordeagrgadoFecha=0;
    function agregarfecha(obj){
        if(contadordeagrgadoFecha==0){
            contadordeagrgadoFecha=1;
           
         id_aula_asignacion=$('#id_materia').val();
        if(id_aula_asignacion!=0){
            //$('.tableasistencia').find("thead tr").append( '<th class="vertical "><div class="vertical"><p>31/03/12</p></div></th>');
            var fechahoy=hoyFecha();//<?//=//date('Y-m-d');?>+'';
            
            $('.tableasistencia').find("thead tr").find(".datos").after( '<th class="vertical " title="'+fechahoy+'"><div class="vertical"><p>'+fechahoy+'</p></div></th>');
            $('.tableasistencia').find("tbody tr").each(function(){
                 var estudiante=$(this).attr('estudiante_id'); 
                $(this).find(".datos").after('<td class="" fecha_asist="'+fechahoy+'" est_asist="'+estudiante+'" fecha-est="'+fechahoy+'-'+estudiante+'"> <select  class="form-control estadohoy" style="padding: 0;"><option value="p">PRESENTE</option><option value="f">FALTA</option><option value="a">ATRASO</option><option value="l">LICENCIA</option></select></td>');  
            });
            $('.tableasistencia').find('tbody').append('<tr><td colspan="3"  ></td><td class=""><button class="btn btn-success" onclick="guardarhoy()"><i class="fa fa-save"></i> Guardar</button></td></tr>'); 
            
           }else{
            alertify.warning('Seleccione un curso ARRIBA');
            $('#id_materia').focus();
           }
           }else{
            alertify.warning('Solo puede agregar una fecha a la vez');
               
           }
        
        
        /*$('.trvertical').append( '<th class="vertical '+resp[i]['descripcion']+'prom"><div class="vertical">Promedio</div></th>');
        
        
        '<th class="vertical  SER1" title="PLATILLINA" onclick="modalver('4*PLATILLINA*armar a papas*2020-02-28 12:23:00')"><div class="vertical">PLATILLINA</div></th>';
        */
    }
 
    function hoyFecha(){
        var hoy = new Date();
            var dd = hoy.getDate();
            var mm = hoy.getMonth()+1;
            var yyyy = hoy.getFullYear();

            dd = addZero(dd);
            mm = addZero(mm);

            return yyyy+'-'+mm+'-'+dd;
    } 

    function addZero(i) {
        if (i < 10) {
            i = '0' + i;
        }
        return i;
    }
    
    function guardarhoy(){
       
        fecha_registro=$('#fecha_registro').val();
        id_aula_asignacion=$('#id_materia').val();
        id_bimestre=$('#bimestre').val();
    //estadohoy array de hoy estados a l f
		var estdatos=new Array();
        var estados='';var cc1=0;
        $('.tableasistencia').find('.estadohoy').each(function(){
			var estados =$(this).val();
			var fecha_asist =$(this).parent().attr('fecha_asist');
			var est_asist =$(this).parent().attr('est_asist');
 			estdatos.push({estados:estados,fecha_asist:fecha_asist,estudiante_id:est_asist});
            //estados+=$(this).val()+'@';
            //cc1++;
        });
        //var estudaintes='';var cc2=0;
        //$('.tableasistencia').find('.tr_estudiante').each(function(){
       // $('.tableasistencia').find('.estadohoy').forEach(function (elem) {
        //    estudaintes+=$(this).attr('estudiante_id')+'@';
        //    cc2++;
        //});
		
		//debugger;
        //console.log(cc1);
        //console.log(cc2);
        //if(cc1==cc2){
            //alert('est'+estados+' /n est:'+estudaintes+' aulmateria'+id_aula_asignacion+'- bimest'+id_bimestre);
            
        //console.log('enviandoajax');
            $.ajax({
                url: '?/principal/procesos',
                type: 'POST',
                data: {
                    accion: 'guardar_asistencia', 
                    //fecha_registro:fecha_registro,
                    asignacion_mat_id:id_aula_asignacion,
                    modo_calificacion_id:id_bimestre,
					estdatos:estdatos,
                    //estudiante_id:estudaintes,
                    //asistencia:estados,
                    tipo_extra:tipo_extra

                    },
                //dataType: 'JSON',
                success: function(resp){
                    //console.log(resp);
                     switch(resp){
                        case 'a':// $("#modal_actividad").modal("hide");
                        alertify.success('Se registro la asistencia correctamente');
                     listar_estudiantes();
                        //listar_estudiantes();
                         break;
                        case 'b':// $("#modal_actividad").modal("hide");
                         alertify.error('Error al registrar asistencia'); 
                        break;
                        case 'c': //$("#modal_actividad").modal("hide");
                         alertify.warning('Fecha ya registrada anteriormente '); 
                        break;
                        case 's': alertify.success('Se registro la primera asistencia'); 
                     listar_estudiantes();
                        break;
                        case 'x': //$("#modal_actividad").modal("hide");
                         alertify.error('Error al registrar asistencia'); 
                        break;
                    }
                    
              /*      for (var i = 0; i < resp.length; i++) {

                       //alertify.success(resp[i]['descripcion']);
                        var id_area_cal=resp[i]['id_area_calificacion'];
                        var descripcion_area=resp[i]['descripcion'];
                        var id_tareaT=resp[i]['id_actividad_materia_modo_area'];
                        var nombre_actividad=resp[i]['nombre_actividad'];
                        var desc_act=resp[i]['descripcion_actividad'];
                        var fecha_act=resp[i]['fecha_presentacion'];

                        newtareaRec(id_area_cal,descripcion_area,id_tareaT,nombre_actividad,desc_act,fecha_act);

                        //console.log(resp[i]['id_actividad_materia_modo_area']);
                    }*/
               }
             }).done(function(){

               //cargar_notas();
             });

            
        //}
    }
        
    function agregarasistenciaest(arrayaist){
    
           //::::: ::::::: :::::::cargar horarios::::::: :::::::: ::::::::: ::::::: :::::::
         for (var i = 0; i < arrayaist.length; i++) {
             //if si existe una clasefecha si nada no agrega esta fecha    
            //var i=0;
            if(id_aula_asignacion!=0){
                $('.tableasistencia').find("thead tr").append( '<th class="vertical " title="'+arrayaist[i]+'"><div class="vertical"><p>'+arrayaist[i]+'</p></div></th>');

                $('.tableasistencia').find("tbody tr").each(function(){
                    var estudiante=$(this).attr('estudiante_id'); 
                    $(this).append('<td class=""><select class="form-control" style="padding: 0;" ><option  value="p" selected>PRESENTE</option><option value="f" >FALTA</option><option  value="a" >ATRASO</option><option value="l" >LICENCIA</option></select></td>');  
                });

               }else{
                alertify.warning('Seleccione un curso ARRIBA');
                $('#id_materia').focus();
               }
         }//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    
    
          /*for (var j = 0; j < arrayaist.length; j++) {
            var fechas=arrayaist[j].split('@');
            }*/
}
    
 //accioens de comuncado tabs
    function btncomunic(tipo){
       var modo_id=$('#bimestre').val();
         var aula_asig_mat_id=$('#id_materia').val();
        //liimpiar
        $('#id_comunicado').val('');
        $("#form_todos")[0].reset(); 
        
        $('#aula_asig_mat_id').val(aula_asig_mat_id);
        $('#modo_id').val(modo_id);
        if(aula_asig_mat_id==0){
            alertify.warning('Selecciona materia curso');
            $('#id_materia').focus();
        }else{ 
        $("#modal_todos").modal("show"); 
        $("#accion").val("newComunicado"); 
       
        $('#tipo').val(tipo);
        
        if(tipo_extra=='NORMAL'){
            tipo_extra='N';
        }if(tipo_extra=='EXTRA'){
            tipo_extra='E';
        }
        
        $('#tipo_extra').val(tipo_extra);
        $('#Tabla_personas').find('tbody').html('');
            if(tipo=='t'){
                 $('.spantipo').html('<span class="badge badge-pill" style="background:#f0f0f8;"><span style="color: #808080;" class="icon-people"></span> Todos</span>');
                 $('.lista_grupo').hide();
            }else if(tipo=='v'){
                 $('.spantipo').html('<span class="badge badge-pill" style="background:#5969ff;color:#ffffff"><span style="color: ffffff;" class="icon-user"></span> Niños</span>');
                 $('.lista_grupo').hide();
            }else if(tipo=='m'){
                 $('.spantipo').html('<span class="badge badge-pill " style="background:#ff5fef;color:#ffffff"><span style="color: #ffffff;" class="icon-user-female"></span> Niñas</span>');
                 $('.lista_grupo').hide();
            }else if(tipo=='selec'){
                 $('.spantipo').html('<span class="badge badge-pill " style="background:#ffc108"><span style="color: #000008;" class="icon-pin"></span> Seleccionados</span>');
                $('.lista_grupo').show();
                listar_est_curso();//en modal_comunicados
                //listar_estudiantes();
                numEst=0;//numeracion de lista en 0
            }
        }
    } 
    
    function listarComunicados(){
        //alert('listar comunica '+tipo_extra);
        id_aula_asignacion=$('#id_materia').val();
        id_bimestre=$('#bimestre').val();
        
        $.ajax({
        url: '?/principal/procesos',
        type: 'POST',
        data:{
			'accion': 'listarComunicados',
            id_aula_asig_materia:id_aula_asignacion,
            id_modo:id_bimestre,
            estado_curso:tipo_extra 
			},
        dataType: 'JSON',
        success: function(resp){
        //console.log('Listar comunicados '+ resp);

        //limpiamos la tabla
         dataTable.clear().draw(); 
        //recorremos los datos retornados y lo añadimos a la tabla
        var counter=1;//numero de datos
        var tipo='';
        for (var i = 0; i < resp.length; i++) {
             var datos=resp[i]['id_comunicado']+'*'+resp[i]['fecha_inicio']+'*'+resp[i]['fecha_final']+ '*'+resp[i]['nombre_evento']+'*'+resp[i]['descripcion']+'*'+resp[i]['color']+'*'+resp[i]['persona_id']+'*'+resp[i]['file']+'*'+resp[i]['prioridad']+'*'+resp[i]['aula_paralelo_asignacion_materia_id']+'*'+resp[i]['modo_calificacion_id']+'*'+resp[i]['grupo'];
            // counter++;
            if( resp[i]["grupo"]=='m'){
               tipo='<span class="badge badge-pill " style="background:#ff5fef;color:#ffffff"><span style="color: #ffffff;" class="icon-user-female"></span> Niñas</span>';
               } else if( resp[i]["grupo"]=='v'){
               tipo='<span class="badge badge-pill" style="background:#5969ff;color:#ffffff"><span style="color: ffffff;" class="icon-user"></span> Niños</span>';
               }  else if( resp[i]["grupo"]=='t'){
               tipo='<span class="badge badge-pill" style="background:#f0f0f8;"><span style="color: #808080;" class="icon-people"></span> Todos</span>';
               } else if( resp[i]["grupo"]=='selec'){
               tipo='<span class="badge badge-pill " style="background:#ffc108"><span style="color: #000008;" class="icon-pin"></span> Seleccionados</span>';
               }
            var botones='<span onclick="editcomunicado('+"'"+datos+"'"+')" class="btn btn-xs btn-info  fa fa-edit"></span>';
            botones+=' <span class="fa fa-trash btn-xs btn btn-danger " onclick="elimComunicado('+ resp[i]['id_comunicado'] +')"></span>';
            var file='';
            if(resp[i]["file"]!=''){
               file+='<a class="fa fa-download btn btn-default " onclick="descarga"  href="files/<?=$nombre_dominio;?>/comunicados/'+resp[i]["file"]+'" dowload="'+resp[i]["file"]+'"> Descargar</a>'; 
                /*<a class="btn btn-" href="files/comunicados/file.txt" dowload="ADJ.txt"> Descargar<i class="icon-arrow-down-circle"></i></a>*/
               }else{
                file+='<a class="fa fa-file btn btn-default " onclick="descarga"> Sin archiv</a>'; 
               }
            var prioridad='';
               if(resp[i]["prioridad"]=="1"){
                prioridad=' title="Normal"';
               }else if(resp[i]["prioridad"]=="2"){
                prioridad='style="color: orange;" title="Importante"';
               }else if(resp[i]["prioridad"]=="3"){
                prioridad='style="color: red;" title="Urgente"';
               } 
            dataTable.row.add( [
                    counter,
                    resp[i]["fecha_inicio"],
                    resp[i]["fecha_final"],
                   ' <span '+prioridad+'>'+resp[i]["nombre_evento"]+'</span>',
                    resp[i]["descripcion"], 
                    file, 
                    tipo, 
                    botones 
                ] ).draw( false ); 
            counter++;
            
        }

	 }
	 });
        
        
         
    }
    
    function editcomunicado(str){
       $("#form_todos")[0].reset(); 
        $("#modal_todos").modal("show"); 
        $("#accion").val("newComunicado"); 
        var arr=str.split('*');
       var id_comunicado=arr[0];
       var fecha_inicio=arr[1];
       var fecha_final=arr[2];
       var nombre_evento=arr[3];
       var descripcion=arr[4];
       var color=arr[5];
       var persona_id=arr[6];
       var file=arr[7];
       var prioridad=arr[8];
       var aula_paralelo_asignacion_materia_id=arr[9];
       var modo_calificacion_id=arr[10];
       var tipo=arr[11];//grupo
       //var tipo_extra=tipo_extra;//genrral
    //::::::::::::CARGAR PERSONAS:::::::::::::::::
    var arraypersonas=persona_id.split(",");//10 strinf de personas
    console.log('------------------------------'+arraypersonas);
    var nlistaest=0; 
    for (var i=0;i<arraypersonas.length;i++) {
       var id_personas=arraypersonas[i];
       if(id_personas!=''){
         nlistaest++; 
        listar_a_tabla(nlistaest,id_personas);
       }
       
    }
    //:::::::::::::::::::::::::::::::::::::
        
        
        $('#Tabla_personas').find('tbody').html('');
        if(tipo=='t'){
                 $('.spantipo').html('<span class="badge badge-pill" style="background:#f0f0f8;"><span style="color: #808080;" class="icon-people"></span> Todos</span>');
                 $('.lista_grupo').hide();
            }else if(tipo=='v'){
                 $('.spantipo').html('<span class="badge badge-pill" style="background:#5969ff;color:#ffffff"><span style="color: ffffff;" class="icon-user"></span> Niños</span>');
                 $('.lista_grupo').hide();
            }else if(tipo=='m'){
                 $('.spantipo').html('<span class="badge badge-pill " style="background:#ff5fef;color:#ffffff"><span style="color: #ffffff;" class="icon-user-female"></span> Niñas</span>');
                 $('.lista_grupo').hide();
            }else if(tipo=='selec'){
                 $('.spantipo').html('<span class="badge badge-pill " style="background:#ffc108"><span style="color: #000008;" class="icon-pin"></span> Seleccionados</span>');
                $('.lista_grupo').show();
                listar_est_curso();//en modal_comunicados
            }
        $('#id_comunicado').val(id_comunicado);
        $('#tipo').val(tipo);
        $('#titulo').val(nombre_evento);
        $('#descripcionE').val(descripcion);
        $('#fecha_ini').val(fecha_inicio);
        $('#fecha_fin').val(fecha_final);
        $('#prioridad').val(prioridad);
        $('#tipo_extra').val(tipo_extra);
         
        //ajax lista
        
    }
     
  function elimComunicado(idcom){
        
      alertify.confirm('<span style="color:red">ELIMINAR ACTIVIDAD</span>', 'Esta accion eliminara todas las notas de esta actividad ¿Desea eliminar?', function(){ //casi de si
            $.ajax({
                url: '?/principal/procesos',
                type:'POST',
                data: {accion:'eliminar_comunic',
                   'id_componente':idcom},
                success: function(resp){
                    // alert(resp);
                    switch(resp){
                        case '1': $("#modal_todos").modal("hide");
                        alertify.success('Se elimino el horario correctamente');
                        listar_estudiantes();
                         break;
                        case '2': $("#modal_todos").modal("hide");
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
 
  
//::::::::::::::::::::::::::::::::::::::FIN QUINO :::::::::::::::::::::::::::::::::::::::::::::::::::
    
</script>
<!--ALERTIFY COLORS-->
<style>
    .ajs-error{
        color: beige;
    } 
    .ajs-success{
        color: beige;
    }
 
</style>
<!--ASISTENCIAS-->
<style>
 select option:hover{
background : RED ;
}
 
    .tab-content{
        overflow: auto;
    }
    .complet{

        -ms-flex: 0 0 100%!important;
         flex: 0 0 100%!important; 
         max-width: 100%!important; 
    }
    
    .hidee{
        display: none;
    }
    .btnhidecalendar{
         background: #58c6fb;
        border-radius: 40%;
        position: fixed;
        bottom: 5em;
        right: 4em;
        z-index: 100;
    }
 
div.vertical
{
width: 5em;
height: 1.5em;
overflow: hidden;
margin-left: -35px;
margin-top: -48px;
 position: absolute;
 /*width: 85px;*/
 transform: rotate(-90deg);
 -webkit-transform: rotate(-90deg); /* Safari/Chrome */
 -moz-transform: rotate(-90deg); /* Firefox */
 -o-transform: rotate(-90deg); /* Opera */
 -ms-transform: rotate(-90deg); /* IE 9 */
}

th.vertical
{
 width: 260px;
 height: 100px;
/* line-height: -100px;*/
 /*padding-bottom: 20px;*/
 text-align: left;
cursor: pointer;
 position: relative;
}
    th.vertical:hover
{
 background: #84ff9e;
} 
/*::::::::::.::HORARIOS ::::::::::::::::::::::::*/
.tableasistencia div.vertical
{
width: 6em;
}
/*.tableasistencia th.vertical
{
    width: 7em;
    height: 1em;
    overflow: hidden;
    margin-left: -45px;
}*/
    
    
    
    
    .inpmov{
        width: 100%;
        height: 100%;
    }
    td{
    padding: 0!important;
    margin: 0;
    height: 0; 
        text-align: center;
    }
    .prom{
        background: #e6e6f2;
    }
    .trbtns{
            font-size: 0.7em;
    }
    
    .group-cardex-btn .active{
        transform:scale(1.20);
 
    }
 
  
    </style>
<script>
   
    
$(function () {
        $(window).on("load", listar_aula_paralelo());

    if ($('#grafico-lineal').length) {
            // Use Morris.Area instead of Morris.Line
            Morris.Line({
                element: 'grafico-lineal',
                behaveLikeLine: false,
                data: [
                    { y: '1', a: 70},
                    { y: '2', a: 55},
                    { y: '3', a: 80},
                    { y: '4', a: 90},
                    { y: '5', a: 100}
                ],

                xkey: 'y',
                ykeys: ['a'],
                labels: ['Bimestre'],
                lineColors: ['#eac702'],
                resize: false,
                gridTextSize: '14px'
            });
        }

	//Grafico
    if ($('.ct-chart-bipolar').length) {
        var data = {
            labels: ['sdsd', 'W2', 'W3', 'W4', 'W5', 'W6', 'W7', 'W8', 'W9', 'W10'],
            series: [
                [1, 2, 4, 8, 6, -2, -1, -4, -6, -2]
            ]
        };
        var options = {
            high: 10,
            low: -10,
            axisX: {
                labelInterpolationFnc: function(value, index) {
                    return index % 2 === 0 ? value : null;
                }
            },
            axisY: {
                
            }
        };

        new Chartist.Bar('.ct-chart-bipolar', data, options);
    }
    //Grafico
	// $('[data-restablecer]').on('click', function (e) {
	// 	e.preventDefault();
	// 	bootbox.confirm('¿Está seguro que desea restablecer todos los filtros que configuró?', function (result) {
	// 		if (result) {
	// 			for (var storage in localStorage) {
	// 				if (storage.match(/DataTables/) || storage.match(/DataFilters/)) {
	// 					localStorage.removeItem(storage)
	// 				}
	// 			}
	// 		}
	// 	});
	// });

	var $modal_mostrar = $('#modal_mostrar'), $loader_mostrar = $('#loader_mostrar'), size, title, image;

	$modal_mostrar.on('hidden.bs.modal', function () {
		$loader_mostrar.show();
		$modal_mostrar.find('.modal-dialog').attr('class', 'modal-dialog');
		$modal_mostrar.find('.modal-title').text('');
	}).on('show.bs.modal', function (e) {
		if ($('.modal:visible').size() != 0) { e.preventDefault(); }
		size = $(e.relatedTarget).attr('data-modal-size');
		title = $(e.relatedTarget).attr('data-modal-title');
		image = $(e.relatedTarget).attr('src');
		size = (size) ? 'modal-dialog ' + size : 'modal-dialog';
		title = (title) ? title : 'Imagen';
		$modal_mostrar.find('.modal-dialog').attr('class', size);
		$modal_mostrar.find('.modal-title').text(title);
		$modal_mostrar.find('[data-modal-image]').attr('src', image);
	}).on('shown.bs.modal', function () {
		$loader_mostrar.hide();
	});

	//function abrir_calendario(){
  var listar_eventos = "listareventos";
  //var eventos = [{"id":"1","title":"reunion de profesores","description":"","start":"2019-06-04 09:00:00","end":"2019-06-04 10:30:00","color":"#008000"},{"id":"2","title":"reunion de padres de familia","description":"","start":"2019-06-14 08:30:00","end":"2019-06-14 10:30:00","color":"#40E0D0"}]
  $('#calendario').fullCalendar({
	    height: 500,
	    defaultView: 'month',
	    lang: 'es',
	    columnFormat: 'dddd',
	    dayNames: ['Do','Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
	    header: {
	            left: 'prev,next today',
	            center: 'title',
	            right: 'month,agendaWeek,agendaDay'
	        },
	    defaultDate: new Date(),
	    hiddenDays: [ 6, 0 ], // hide Tuesdays and Thursdays
	    editable: true,
	    eventLimit: true,
	    weekend: false,
	    selectable: true,
	    minTime: '08:00:00',
	    maxTime: '20:00:00',
	    slotDuration: '00:30:00',
	    slotLabelInterval : '00:30:00',
	    eventSources:[
	        {
	            url: '?/d-agenda/procesos', // use the `url` property
	            method: 'POST',
	            data: {'boton': 'listar_eventos'}
	        },
	        {
	            url: '?/d-agenda/procesos', // use the `url` property
	            method: 'POST',
	            data: {'boton': 'listar_comunicados'}
	        }
	     
	    ],

    select: function(start, end) {
      // leemos las fechas de inicio de evento y hoy
      var fecha_marcada = moment(start).format('YYYY/MM/DD');
      var hoy = moment(new Date()).format('YYYY/MM/DD');
      if (fecha_marcada >= hoy) {
        $('#modal_evento').modal('show');
        $("#form_evento")[0].reset();
        $("#titulo_modal").text("Agregar Tarea");
        $("#div_eliminar").hide();
        $("#btn_agregar").show();
        $("#btn_editar").hide();
        //$("#fecha_inicio").datepicker("option", "defaultDate", new Date(2019,8,30))
        $("#fecha_inicio").data('datepicker').selectDate(new Date(fecha_marcada))
        $("#fecha_terminar").data('datepicker').selectDate(new Date(fecha_marcada))
      }else{
        alertify.error('No puede asignar eventos en una fecha pasada a la actual'); 
      }
    },
    eventRender: function(event, element) {
        element.attr('href', 'javascript:void(0);');
        element.bind('dblclick', function() {
        var fecha_marcada = moment(event.start['_i']).format('YYYY-MM-DD');
        var hoy = moment(new Date()).format('YYYY-MM-DD');
        if (fecha_marcada >= hoy) {
          $("#form_evento")[0].reset();
          $("#modal_evento").modal("show");
          $("#titulo_modal").text("Editar Evento");
          $("#id_evento").val(event.id_evento);
          $("#nombre_evento").val(event.title);
          $("#descripcion_evento").val(event.description);
          $("#color_evento").minicolors('value',event.color);
          /*$("#fecha_inicio").val(moment(event.start['_i']).format('DD/MM/YYYY HH:mm'));
          $("#fecha_terminar").val(moment(event.end['_i']).format('DD/MM/YYYY HH:mm'));*/
          $("#fecha_inicio").data('datepicker').selectDate(new Date(event.start['_i']));
          $("#fecha_terminar").data('datepicker').selectDate(new Date(event.end['_i']));
          $("#btn_agregar").hide();
          $("#btn_editar").show();
          //console.log(event);
        }else{
          $("#form_ver")[0].reset();
          $("#modal_ver").modal("show");
          $("#titulo_modal_ver").text("Ver Evento");
          $("#id_tarea_ver").val(event.id_actividad_materia_modo_area);
          $("#nombre_evento_ver").text(event.title);
          $("#descripcion_evento_ver").text(event.descripcion);
          $("#fecha_inicio_ver").text(event.start['_i']);
          $("#fecha_final_ver").text(event.end['_i']);
        }
      });
    },
    eventDrop: function(event, delta, revertFunc) { // si changement de position
      edit(event);
    },
    eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur
      edit(event);
    }
  });

}); 
</script>
<!-- onclick="location.href='?/d-registrar-nota/calificar-actividad/<?//= $id_aula_paralelo?>/<?//= $id_profesor_materia?>/<?//= $id_modo_calificacion?>/<?//= $val['id_area_calificacion'];?>'"-->
<script>
 
    
	function listar_aula_paralelo() {
        
		var id_materia=$('#id_materia').val();
		var sql_pm=<?= ($sql_pm);?>;
		var id_persona=<?= ($id_persona);?>;
		var paralelo='';var valu='';var resulta = "";
		 resulta+='<option value="">Seleccionar</option>';
	    for (var i = 0; i < sql_pm.length; i++){
 
	    		resulta+="<option value='"+sql_pm[i].id_aula_paralelo+"'>"+sql_pm[i].nombre_aula+" "+sql_pm[i].nombre_paralelo+" "+sql_pm[i].nombre_nivel+"</option>"; 
				$('#id_profesor_materia').val(sql_pm[i].id_profesor_materia); 
	    }
        $('#id_aula_paralelo').html(resulta);

        //cargar_area_calificacion();
        $.ajax({
        url: '?/principal/procesos',
        type: 'POST',
        data:{
			'accion': 'ret_pararelo',
            id_materia:id_materia,
            //id_aula_paralelo:'',
            persona_id:id_persona
            
			},
        dataType: 'JSON',
        success: function(resp){
        //console.log('Listar personas '+ resp);

        var counter=1;
        //limpiamos la tabla
        dataTable.clear().draw(); 
        //recorremos los datos retornados y lo añadimos a la tabla
        var counter=1;//numero de datos
        for (var i = 0; i < resp.length; i++) {
            // var datos=resp[i]['id_asignacion']+'*'+resp[i]['foto']+'*'+resp[i]['nombres']+ '*'+resp[i]['primer_apellido']+'*'+resp[i]['segundo_apellido']+'*'+resp[i]['genero']+'*'+resp[i]['fecha_nacimiento']+'*'+resp[i]['fecha_nacimiento']+'*'+resp[i]['cargo']+'*'+resp[i]['sueldo']+'*'+resp[i]['horario_id'];
            // counter++;
            
        }

	 }
	 });
	}

	function cargar_registrar_nota(id) {
        console.log('lllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllll');
		console.log(id);
		//var id=$(this).attr('data-area');
		var id_area_calificacion=id;
		var id_modo_calificacion=$('#bimestre').val();
		var id_aula_paralelo=$('#id_aula_paralelo').val();
		var id_profesor_materia=$('#id_profesor_materia').val();
		console.log(id_modo_calificacion);

		$("#contnedor-nota").load("?/d-registrar-nota/calificar-actividad",{id_aula_paralelo:id_aula_paralelo, id_profesor_materia:id_profesor_materia, id_area_calificacion:id_area_calificacion, id_modo_calificacion:id_modo_calificacion},
		function(response, status, xhr) {
			    if (status == "error") {
				    var msg = "Error!, algo ha sucedido: ";
				    $("#contnedor-nota").html(msg + xhr.status + " " + xhr.statusText);
			    }
		});
	}	

	function cargar_area_calificacion() {
		var id_modo_calificacion=$('#bimestre').val();
		var id_aula_paralelo=$('#id_aula_paralelo').val();
		var id_profesor_materia=$('#id_profesor_materia').val();

		$("#contnedor-nota").load("?/d-registrar-nota/listar-areas-calificacion",{id_aula_paralelo:id_aula_paralelo, id_profesor_materia:id_profesor_materia,id_modo_calificacion:id_modo_calificacion},
		function(response, status, xhr) {
			    if (status == "error") {
				    var msg = "Error!, algo ha sucedido: ";
				    $("#contnedor-nota").html(msg + xhr.status + " " + xhr.statusText);
			    }
		});
	}

	function cargar_pizarra_materia() {
		//console.log('hola');
		//alert('fgjhhghgg');
		var id_modo_calificacion=$('#bimestre').val();
		var id_aula_paralelo=$('#id_aula_paralelo').val();
		var id_profesor_materia=$('#id_profesor_materia').val();

		$("#contnedor-pizarra").load("?/d-reportes/pizarra",{id_aula_paralelo:id_aula_paralelo, id_profesor_materia:id_profesor_materia, id_modo_calificacion:id_modo_calificacion},
		function(response, status, xhr) {
			    if (status == "error") {
				    var msg = "Error!, algo ha sucedido: ";
				    $("#contnedor-pizarra").html(msg + xhr.status + " " + xhr.statusText);
			    }
		});
	}

	function cargar_registrar_asistencia() {
		//var id=$(this).attr('data-area');
		var id_modo_calificacion=$('#bimestre').val();
		var id_aula_paralelo=$('#id_aula_paralelo').val();
		var id_profesor_materia=$('#id_profesor_materia').val();
        var id_profesor=<?= $id_profesor;?>;
		console.log(' modo '+id_modo_calificacion+' aula_p '+id_aula_paralelo+' prof_mat  '+id_profesor_materia+' prof  '+id_profesor);
		

		/*$("#contnedor-asistencia").load("?/d-curso-asignados/listar-estudiantes-curso",{id_aula_paralelo:id_aula_paralelo, id_profesor_materia:id_profesor_materia, id_profesor:id_profesor, id_modo_calificacion:id_modo_calificacion},
		function(response, status, xhr) {
			    if (status == "error") {
				    var msg = "Error!, algo ha sucedido: ";
				    $("#contnedor-asistencia").html(msg + xhr.status + " " + xhr.statusText);
			    }
		});*/
	}
	
	function cargar_reporte_nota() {
		var id_modo_calificacion=$('#bimestre').val();
		var id_aula_paralelo=$('#id_aula_paralelo').val();
		var id_profesor_materia=$('#id_profesor_materia').val();
		var id_profesor=<?= $id_profesor;?>;

		$("#contnedor-reporte").load("?/d-registrar-nota/historial-notas",{id_aula_paralelo:id_aula_paralelo, id_profesor_materia:id_profesor_materia, id_profesor:id_profesor, id_modo_calificacion:id_modo_calificacion},
		function(response, status, xhr) {
			    if (status == "error") {
				    var msg = "Error!, algo ha sucedido: ";
				    $("#contnedor-reporte").html(msg + xhr.status + " " + xhr.statusText);
			    }
		});
	}

	function cargar_kardex() {
		var id_modo_calificacion=$('#bimestre').val(); 
		var id_aula_paralelo=$('#id_aula_paralelo').val();
		var id_profesor_materia=$('#id_profesor_materia').val();
		var id_profesor=<?= $id_profesor;?>;

		$("#contnedor-kardex").load("?/d-reportes/listar",{id_aula_paralelo:id_aula_paralelo, id_profesor_materia:id_profesor_materia, id_profesor:id_profesor, id_modo_calificacion:id_modo_calificacion},
		function(response, status, xhr) {
			    if (status == "error") {
				    var msg = "Error!, algo ha sucedido: ";
				    $("#contnedor-kardex").html(msg + xhr.status + " " + xhr.statusText);
			    }
		});
	}
		//reporte bimestral
function reporte_bimestral(){
    var id_area_calificacion=1;
	var id_modo_calificacion=$('#bimestre').val();
	var id_aula_paralelo=$('#id_aula_paralelo').val();
	var id_profesor_materia=$('#id_profesor_materia').val();
	enviarPost('?/d-registrar-nota/procesos', cadena_bimestral(id_modo_calificacion, id_area_calificacion, id_profesor_materia, id_aula_paralelo));
}

//reporte anual
function reporte_anual(){
	var id_aula_paralelo=$('#id_aula_paralelo').val();
	var id_profesor_materia=$('#id_profesor_materia').val();
	enviarPost('?/d-registrar-nota/procesos', cadena_anual(id_profesor_materia, id_aula_paralelo));
}

//arma un array con todos los paraletros que se van a enviar al reporte bimestral
function cadena_bimestral(modo_calificacion,area_calificacion, profesor_materia, aula_paralelo){
	var parametros = {
				'id_modo_calificacion':modo_calificacion,
				'id_area_calificacion':area_calificacion,
				'id_profesor_materia':profesor_materia,
				'id_aula_paralelo':aula_paralelo,
				'boton':'reporte_bimestral'
	}
	return parametros;
}

//arma un array con todos los paraletros que se van a enviar al reporte anual
function cadena_anual(profesor_materia, aula_paralelo){
	var parametros = {
				'id_profesor_materia':profesor_materia,
				'id_aula_paralelo':aula_paralelo,
				'boton':'reporte_anual'
	}
	return parametros;
}

//envia los datos como si fuera un formulario con el metodo POST
function  enviarPost(url, datos) {
	var form = '<form action="'+url+'" method="POST" target="_blank">' ;
	$.each(datos, function (key,value) {
		form += '<input type="hidden" name="'+key+'" value="' + value + '">';
	});
	form += '</form>';
	var formElment = $(form);
	$(document.body).append(formElment);
	formElment.submit();
}
    
    //.-------------------------
    // Reporte de excel para la asistencia
    function reporte_asistencia_materia() {
		
	    key_tipo               = 0;
	    tipo_	               = $('#id_materia').find('option:selected').attr('tipo_extra');
		id_asignacion_docente_ = $('#id_materia').val();
		id_bimestre_           = $('#bimestre').val();
		
		if(tipo_ == "EXTRA"){
		    key_tipo = 1; // 1 indica que es de tipo extracurriculas
		}
		
		if (id_asignacion_docente_ > 0 && id_bimestre_ > 0) {
			$(location).attr('href', '?/d-actividad-curso/asistencia-materia-excel/' + id_asignacion_docente_ + '/' + id_bimestre_ + '/' + key_tipo);
		} else {
			alertify.warning("Elija un curso");
		}
	}
    
</script>
