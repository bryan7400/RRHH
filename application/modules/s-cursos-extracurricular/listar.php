
<?php 
// Obtiene los horarios
//$horarios = $db->from('per_horarios')->order_by('id_horario', 'asc')->fetch();
//SELECT asi.`sueldo_total`,ca.`cargo`,e.*  FROM `per_asignaciones` asi LEFT JOIN sys_persona e  ON asi.`persona_id` = e.`id_persona` LEFT JOIN `per_cargos` ca  ON asi.`cargo_id` = ca.`id_cargo`


// Obtiene los permisos
$permiso_crear     = in_array('modalcrear', $_views);
$permiso_ver       = in_array('listar', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_eliminar  = in_array('eliminar', $_views);
$permiso_imprimir  = in_array('imprimir', $_views);
$nombre_dominio = escape($_institution['nombre_dominio']);
?>
<?php  require_once show_template('header-design');  ?>



<style>
  .datepicker {z-index: 1151 !important;}
</style> 
<link href="assets/themes/concept/assets/vendor/datepicker/css/datepicker.css" rel="stylesheet">

<!--cuerpo card table--> 
<div class="row listadocard"> 
   <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
             <h2 class="pageheader-title"> CURSO EXTRACURRICULAR</h2>
            <p></p>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Cursos</a></li>
                        <!--<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Registros iniciales</a></li>-->
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Extracurricular </a></li>
                        
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="col-xl-10 col-lg-9 col-md-8 col-sm-8 col-12">
        <div class="card"> 

            <div class="card-header">
                <div class="row">                   
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-left"><button class="btn btn-info btnvolver" onclick=" volver()" style="display:none"><span class="fa fa-angle-left "></span> Volver</button></div> 
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
                        <div class="text-label hidden-xs">Seleccionar acción:</div>
						<div class="btn-group">
							<div class="input-group">
								<div class="input-group-append be-addon">
									<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
									<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item">Seleccionar acción</a>
										 <div class="dropdown-divider"></div>
                                            <a href="#" onclick="crear_curso()"class="dropdown-item" > <span class="fa fa-plus"> </span> Crear curso</a>                                         
									</div>
								</div>
							</div>
						</div> 
					</div>  
                </div>
            </div>
 
            <div class="card-body">
                <div class="row contcards"></div>
			</div>
           
            <div class="card-body">
               
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
                                        <img src="./files/profiles/avatar.jpg" alt="User Avatar" class="rounded-circle user-avatar-xxl view-image">
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
                           <table id="table2" class="table table-bordered table-condensed table-striped table-hover">
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
                   <table id="table3" class="table table-bordered table-condensed table-striped table-hover">
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
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-4 col-12">
      
     <div class="card">
        <h5 class="card-header">Opciones de busqueda:
		   <div class="input-group mb-2 mr-sm-2">
			<div class="input-group-prepend">
			  <div class="input-group-text"><span class="fa fa-search"></span></div>
			</div>
			<input type="text" class="form-control" id="buscarCue" placeholder="Buscar" onkeyup="filtrarkey(this)" style="    text-transform:uppercase;">
		  </div>
        
        </h5>
			<div class="card-body">
				<ul class="list-group list-categorias">
					<li class="list-group-item d-flex justify-content-between align-items-center">
						<img src="../files/categoriaCurso/I-28052020162257.jpg" alt="user" class=" rounded-circle user-avatar-md "> Cras justo odio
						<span class="badge badge-primary badge-pill">14</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center" ondblclick="edit_categoria()">
						Dapibus ac facilisis in
						<span class="badge badge-primary badge-pill">2</span>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						Morbi leo risus
						<span class="badge badge-primary badge-pill">1</span>
					</li>
				</ul>
				<p style=" font-size: xx-small; ">* Doble click en cada categoria para editarlo</p>
			</div>
        </div>
 </div>
</div>
<div class="row vistacard" style="display:none">
    <button class="btn btn-info btnvolver" onclick=" volver()" style=""><span class="fa fa-angle-left "></span> Volver</button> 

    <!-- curso datos  --> 
     <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card influencer-profile-data">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-3 col-lg-5 col-md-5 col-sm-4 col-12">
                                <div class="text-center">
                                   <img src="./files/categoriaCurso/image.jpg" alt="imagen_curso" class="rounded-circle user-avatar-xxl imagen_curso"><br><br>
                                   <!--<button class="fa fa-cogs fa-lg  btn btn-secundary" title="Administrar curso" onclick="administrarCurso()"></button>-->
                                   <p><button class="btn btn-outline-warning" onclick="edit_curso();" title="Editar curso"><span class="fa fa-edit"
                                   ></span>  </button><button class="btn btn-outline-danger  btn-elim_curso" onclick="eliminar_curso();" title="Eliminar curso"><span class="fa fa-trash"></span></button><button class="btn btn-outline-info d-none" onclick="imprimir_curso();" title="Generar reporte"><span class="fa fa-print"></span></button></p> 
                                    
                                    </div>
                                </div>
                            <div class="col-xl-9 col-lg-7 col-md-7 col-sm-8 col-12">
                                    <div class="user-avatar-info">
                                        <div class="m-b-20 row">
                                            <div class="user-avatar-name col-12">
                                                <h2 class="mb-1 "><span class="nombre_curso">nombre_curso</span> <span class="badge badge-info categoria">categoria</span></h2>
                                            </div>
                                                
                                        <div class="user-avatar-address  col-12">
                                             <p class="mb-2" ><span class="fa fa-bullseye "></span> Objetivo del curso:<span class="text-dark font-medium ml-2 objetivo_curso">objetivo_curso</span><span></span></p>
                                            <!--<p><span class="fa fa-bullseye "></span> <span  class="objetivo_curso">objetivo_curso</span> </p>-->
                                            <p class="mb-2 border-bottom" ><span class="fa fa-quote-left "></span> Descripcion del curso:<span class="text-dark font-medium ml-2 descripcion_curso">descripcion_curso</span><span></span></p> 
                                           <!-- <p class="mb-2 border-bottom" ><span class="fa fa-quote-left "></span> categoria <span  class="categoria"> categoria </span>:<span class="text-dark font-medium ml-2 descripcion_cat">descripcion_cat</span><span></span></p>-->
                                               
                                            <!--<p class=" border-bottom"><span class="fa fa-star "></span> <b><span  class="categoria"> categoria </span></b> <span  class="descripcion_cat">descripcion_cat</span> </p>-->
                                            
                                            <!--<p class=" "><b><span class="fa fa-quote-left "> Requisito</span></b>  <span  class="nombre_pre">nombre_pre</span>  </p>-->
                                            <div class="contrequisitos border-bottom">
                                            	
                                            <p class="mb-2" ><span class="fas fa-thumbtack" style="color:red"></span> Requisito:<span class="text-dark font-medium ml-2 nombre_pre">nombre_pre</span> - <span class="desc_pre mr-2"></span>  Tipo:<span class="text-dark font-medium  tipo_pre">tipo_pre</span> <span class=" fa fa-edit   " style="color:blue;cursor:pointer"></span> <span class="fa fa-times  " style="color:red;cursor:pointer"></span></p>
                                            <p class="mb-2" ><span class="fas fa-thumbtack" style="color:red"></span> Requisito:<span class="text-dark font-medium ml-2 nombre_pre">nombre_pre</span> - <span class="desc_pre mr-2"></span>  Tipo:<span class="text-dark font-medium  tipo_pre">tipo_pre</span> <span class=" fa fa-edit   " style="color:blue;cursor:pointer"></span> <span class="fa fa-times  " style="color:red;cursor:pointer"></span></p>
                                            
                                            </div>
                                            
                                            
                                            <!-- <span class="btn mr-2 btn-sm " style="color:green" onclick="nueva_asignacion()"> <i class="fa fa-plus"></i> Nueva Asignacion</span> -->
                                             <span class="btn mr-2 btn-sm  " style="color:orange" onclick="crear_requisito()"><i class="fa fa-plus"></i>Agregar Requisito</span><!--btn btn-info -->
                                        </div>
                                                     
                                            
                                        </div>
                                       
                                    </div>
                                    
                                
                                </div>
                            </div>
                        </div>
                         
                    </div>
                </div>
                
                
      <!--asignacion-->
      <div class="container-fluid">
          <div class="row">
                 <!--  list eventos-->
    <div class="col-xl-3 col-lg-5 col-md-5 col-sm-4 col-12 container-fluid  mb-4" >
         <div class="card" style="    height: 100%;">
         <div class="card-body">
                        <div class="text-center">
                           <h3>Activaciones</h3>
                                    <ul class="list-group mb-3 listactivaciones">
                                       sin asignaciones
                                    </ul>
                        <button type="submit" class="btn btn-secondary" title="Mostrar toda la lista de cursos" onclick="nueva_asignacion()">Nueva asignacion</button>
                </div>
            </div>
        </div>  
      </div>
     <!--estadisticas o resumen datos-->
     
    <div class="col-xl-9 col-lg-7 col-md-7 col-sm-6 col-12 container-fluid "><!--estadisticasRow-->
<div class="row">
        <div class="col-12">
            
         <div class="card">
              <div class="card-body">
                    <h3>Expositor</h3>
                   <div class=" d-flex  align-items-center" style=" font-size: .8em;">  <img src="./files/logos/avatar.jpg" alt="user" class=" rounded-circle user-avatar-md mr-2  foto"><p class="mb-0  "><span class="nombres">EFRAIN</span> <span class="primer_apellido">QUISPE</span> <span class="segundo_apellido">CORDERO</span></p> </div>
                    
              </div>           
         </div>
        </div>
        <div class="col-12">
            
         <div class="card">
              <div class="card-body">
                   
                    <h3>Inscritos</h3>
                        <div class="progress mt-3  ">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 100%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                        </div> 

                            <p class="d-inline-block text-dark">Inscritos <span class="inscritos">inscritos</span> de <span class="cupo">cupo</span> (minimo:<span class="cupo_minimo_curso">min</span>)</p>
              </div>           
         </div>
        </div>
 
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-inline-block">
                        <h5 class="text-muted">Certificado</h5>
                        <h2 class="mb-0 certificado"></h2>
                    </div>
                    <div class="float-right icon-circle-medium  icon-box-lg  bg-info-light mt-1">
                        <i class="fa fa-eye fa-fw fa-sm text-info"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-inline-block">
                        <h5 class="text-muted">Carga horaria</h5>
                        <h2 class="mb-0 carga_horaria"> </h2>
                    </div>
                    <div class="float-right icon-circle-medium  icon-box-lg  bg-primary-light mt-1">
                        <i class="fa fa-history  fa-fw fa-sm text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-inline-block">
                        <h5 class="text-muted">Inscritos</h5>
                        <h2 class="mb-0 inscritos">0</h2>
                    </div>
                    <div class="float-right icon-circle-medium  icon-box-lg  bg-secondary-light mt-1">
                        <i class="fa fa-handshake fa-fw fa-sm text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-inline-block">
                        <h5 class="text-muted">Modulo</h5>
                        <h2 class="mb-0 modulo">0</h2>
                    </div>

                    <div class="float-right icon-circle-medium  icon-box-lg  bg-brand-light mt-1">
                        <i class="fa fa-cubes fa-fw fa-sm text-secondary"></i>
                    </div>
                         <!--fa fa-modx fa fa-fort-awesome  -->
                </div>
            </div>
        </div>
        <!--datos de asiganacion-->
          <div class="col-12">
        <div class="card influencer-profile-data">
            <div class="card-body">
             <div class=" col-12">
                <div class="user-avatar-info">
                   
                    <!--  <div class="float-right"><a href="#" class="user-avatar-email text-secondary">www.henrybarbara.com</a></div> d-xl-inline-block  d-block-->
                    <div class="user-avatar-address">
                        <p class="border-bottom pb-3">
                            <span class=" mb-2"><i class="fa fa-map-marker-alt ml-2 text-primary "></i> <span class="ambiente"> ambiente</span></span> 


                            <span class=" mb-2"><i class="fa fa-clock ml-2 text-primary "></i> <span class="horario_dia"> horario_dia</span></span>
                            <span class=" mb-2"><i class="fa fa-calendar  ml-2 text-primary "></i> <span class="fecha_inicio"> fecha_inicio</span> al <span class="fecha_fin"> fecha_fin</span></span> 

                            <span class=" mb-2"><i class="fa fa-expand   ml-2 text-primary "></i> Duracion:<span class="duracion"> duracion</span>H <span class="periodo"></span></span>
                           
 
                        </p>
                        <!--<div class="user-social-media d-xlX-inline-block btn editAsignacion" onclick="editar_asignacion()"><span class="mr-2 twitter-color"> <i class="fa fa-edit"></i></span><span>Editar Asignacion</span></div>
                    <div class="user-social-media d-xlX-inline-block btn editAsignacion"  onclick="eliminar_asignacion()"><span class="mr-2  pinterest-color"> <i class="fa fa-trash "></i></span><span>Eliminar asignacion</span></div>
                    <div class="user-social-media d-xlX-inline-block btn" onclick="fin_asignacion()"><span class="mr-2  " style="color:red"> <i class="fa fa-power-off"></i></span><span>Culminar Curso</span></div>-->
                        <p><span class="fa fa-bullseye "></span> <span  class="objetivo_curso">objetivo_curso</span> </p>
                        <p class=" border-bottom"><span class="fa fa-quote-left "></span> <span  class="descripcion_curso">descripcion_curso</span> </p>
                        <button class="btn btn-secondary d-none" onclick="fin_asignacion()"><span title=" Culminar Asignacion actual"><i class="fa fa-power-off"></i> Culminar esta actividad</span></button>
                         <span class="btn btn-info btn-sm" onclick="editar_asignacion()" title=" Editar Asigancion"><i class="fa fa-edit"></i> Editar asignacion</span>
                            <span class="btn btn-danger  btn-sm btn-elim_asig" onclick="eliminar_asignacion()" title=" Eliminar asignacion"><i class="fa fa-trash "></i> Eliminar asignacion</span>
                            
                    </div>
                </div>


                        </div>
            
                        </div>
                        </div>
                    </div>
                </div>
            </div>
     
          </div>
      </div> 
    

        <!--inscritos-->
        <div class=" col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-inline-block">
                       <h3>INSCRITOS   </h3><h5><span class="fecha_inscripcion_inicio"></span> al <span class="fecha_inscripcion_fin"></span></h5>
                    </div> 
                    <h2>Disponibles <span  class="disponibles"></span>   
                     <button class="btn btn-info btninscribir" onclick="crear_Inscribir()">Inscribir</button>
                     <button class="btn btn-warning btnllenoins d-none" >Inscripción llena</button>
                     <button class="btn btn-info" onclick="btnimprimir()">Imprimir</button>
                     </h2>
                           <p class="col-md-6  col-sm-12 col-12 observaciones"></p>
                           <div class="campaign-table table-responsive">
                        <table class="table" id="tableInscritos">
                            <thead>
                                <tr class="border-0">
                                    <th class="border-0">#</th>
                                    <!--<th class="border-0">Foto</th>-->
                                    <th class="border-0">Nombres</th> 
                                    <th class="border-0">Curso</th>
                                    <th class="border-0">Fecha/Hora inscripcion</th>
                                    <th class="border-0">Tipo/observación</th>
                                  <!--  <th class="border-0">Acción</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="m-r-10"><img src="./files/logos/avatar.jpg" alt="user" width="35"></div>
                                    </td>
                                    <td>Marco Quino</td>
                                    <td>3° A sec</td>
                                    <td>12:00 7 Aug,2018</td>
                                    <td>Tipo / obs</td>
                                    <td>
                                        <div class="dropdown float-right">
                                            <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="true">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                         </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item" onclick="editar_Inscripcion()">Editar Inscripcion</a> 
                                                <a href="javascript:void(0);" class="dropdown-item">Reporte de inscripcion</a>
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Eliminar Inscripcion</a> 
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="m-r-10"><img src="./files/logos/avatar.jpg" alt="user" width="35"></div>
                                    </td>
                                    <td>Marco Quino</td>
                                    
                                    <td>3° A sec</td>
                                    <td>12:00 7 Aug,2018</td>
                                    <td>Tipo / obs</td>
                                    <td>
                                        <div class="dropdown float-right">
                                            <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="true">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                         </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr> 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
       <!-- <div class="row">
 
            <div class="col-lg-12">
                <div class="section-block">
                   <div class="mt-3 pb-3">
                           <h3>Incripciones</h3>
                           <div class="row">

                           <p class="col-md-6  col-sm-12 col-12"><span class="fecha_inscripcion_inicio"></span> al <span class="fecha_inscripcion_fin"></span><br> <button class="btn btn-info" onclick="crear_Inscribir()">Inscribir</button> </p>
                           <p class="col-md-6  col-sm-12 col-12 observaciones"></p>

                           </div>
  
                        </div>
                    <h3 class="section-title">INSCRITOS</h3>
                </div>
                <div class="card">
                    <div class="campaign-table table-responsive">
                        <table class="table" id="tableInscritos">
                            <thead>
                                <tr class="border-0">
                                    <th class="border-0">foto</th>
                                    <th class="border-0">Nombres</th> 
                                    <th class="border-0">Curso</th>
                                    <th class="border-0">Hora y fecha inscripcion</th>
                                    <th class="border-0">tipo/observacion</th>
                                    <th class="border-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="m-r-10"><img src="./files/profiles/chica.png" alt="user" width="35"></div>
                                    </td>
                                    <td>Marco Quino</td>
                                    
                                    <td>3° A sec</td>
                                    <td>12:00 7 Aug,2018</td>
                                    <td>Tipo / obs</td>
                                    <td>
                                        <div class="dropdown float-right">
                                            <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="true">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                         </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                               
                                                <a href="javascript:void(0);" class="dropdown-item" onclick="editar_Inscripcion()">Editar Inscripcion</a> 
                                                <a href="javascript:void(0);" class="dropdown-item">Reporte de inscripcion</a>
                                                
                                                <a href="javascript:void(0);" class="dropdown-item">Eliminar Inscripcion</a> 
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="m-r-10"><img src="./files/profiles/chico.png" alt="user" width="35"></div>
                                    </td>
                                    <td>Marco Quino</td>
                                    
                                    <td>3° A sec</td>
                                    <td>12:00 7 Aug,2018</td>
                                    <td>Tipo / obs</td>
                                    <td>
                                        <div class="dropdown float-right">
                                            <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="true">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                         </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                               
                                                <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                                              
                                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                              
                                                <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                                                 
                                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr> 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
        </div>-->
         
</div>
<!--<div class="row adminCurso" style="display:none">
    <div class=" col-12 d-flex justify-content-between">
       <div> <h2><button class="btn btn-info btnvolver" onclick=" volver2()" style=""><span class="fa fa-angle-left "></span> Volver</button> Administrar de cursos</h2> </div>
       <div><p><button class="btn btn-warning" onclick="edit_curso();">Editar Curso</button><button class="btn btn-danger" onclick="eliminar_curso();">Eliminar curso Actual</button></p></div> 
   
    
    </div>
    <div class="card col-12">
                    <div class="campaign-table table-responsive">
                        <table class="table" id="tableInscritos">
                            <thead>
                                <tr class="border-0">
                                  
                                    <th class="border-0">foto</th>
                                    <th class="border-0">Curso</th> 
                                    <th class="border-0">Modulo</th>
                                    <th class="border-0">Docenete</th>
                                    <th class="border-0">obs</th>
                                    <th class="border-0">Action</th> 
                                </tr>
                            </thead>
                            <tbody> <tr>   <td>   <div class="m-r-10"><img src="./files/profiles/chico.png" alt="user" width="35"></div>   </td>   <td>LIZBETH ANALY GARCIA APAZA</td>  <td>3° A sec</td>  <td>00:00:00  0000-00-00</td>  <td>/null </td> <td>  <div class="dropdown float-right">  <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="true">   <i class="mdi mdi-dots-vertical"></i>   </a>     <div class="dropdown-menu dropdown-menu-right">  <a href="javascript:void(0);" class="dropdown-item">Editar Inscripcion</a>                                                 <a href="javascript:void(0);" class="dropdown-item">Reporte de inscripcion</a>  <a href="javascript:void(0);" class="dropdown-item">Eliminar Inscripcion</a>    </div>  </div>  </td>    </tr> <tr>   <td>   <div class="m-r-10"><img src="./files/profiles/chica.png" alt="user" width="35"></div>   </td>   <td>JULIA TITIRICO ARUQUIPA</td>  <td>3° A sec</td>  <td>00:00:00  0000-00-00</td>  <td>/null </td> <td>  <div class="dropdown float-right">  <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="true">   <i class="mdi mdi-dots-vertical"></i>   </a>     <div class="dropdown-menu dropdown-menu-right">  <a href="javascript:void(0);" class="dropdown-item">Editar Inscripcion</a>                                                 <a href="javascript:void(0);" class="dropdown-item">Reporte de inscripcion</a>  <a href="javascript:void(0);" class="dropdown-item">Eliminar Inscripcion</a>    </div>  </div>  </td>    </tr></tbody>
                        </table>
                    </div>
                </div>
</div>-->
<style>
    .imgCard{
    /*overflow: hidden;
    position: relative;*/
    height: 14em;
    object-fit: cover;
    }
    .infor{
    padding: 1em;   }

    .imgDocente{
    width: 3em;
    height: 3em;
    border-radius: 50%;
    border: 2px #000 solid;
    }

    .cardMy{
    box-shadow: 2px 2px 10px rgba(0,0,0,0.2);
    transition: all ease .5s;


    } .cardMy:hover{
    /*transform: scale(1.03);*/
    box-shadow: 0px 31px 20px rgba(0,0,0,0.5);
    transform: rotateX(10deg) translateZ(40px);
    } 
    /*numero de datos*/
    .back_lila {
    background: linear-gradient(to bottom, rgba(251, 253, 255, 0) 0%,rgb(223, 21, 138) 40%,rgba(40, 224, 62, 0.7) 68%,rgb(5, 99, 25) 100%);
    }
    .float_isqab {
    color: white;
    position: absolute;
    top: 1px;
    right: 8px;
    z-index: 101;
    text-align: center;
    padding: 6px;
    border-radius: 0px 0px 10px 10px;
    }
    .btnvolver{
        position: fixed;
    z-index: 100;
    box-shadow: 10px 10px 10px #857c7c;
    border-radius: 20px;
    }
    .listactivaciones li:hover{
    background: #c4ff40e6;
        transform: scale(1.04);
    cursor: pointer;
        
    }
    .listactivaciones  .active{
    background: #c4ff40e6
    }
    .list-categorias li:hover{
    background: #bffece;
        transform: scale(1.04);
    cursor: pointer;
        
    }
    .ist-categorias  .active{
    background: #c4ff40e6
    }
</style> 
<!--datapicker-->
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.es.js"></script>

<script src="<?= js; ?>/selectize.min.js"></script>



<?php
//if ($permiso_editar) {  
    require_once ("modal-cursos.php");//modal 
//} 
?>
<script>
 /*
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
});
var dataTableSan= $('#table3').DataTable({
  language: dataTableTraduccion,
  searching: true,
  paging:true,
  stateSave:true,
  "lengthChange": true,
  "responsive": true
});*/
//FUNCION DE RECARGA DE DATATABLE 
//ruta de proceso
var rutaproceso='?/s-cursos-extracurricular/procesos';
var nombre_dominio='<?=$nombre_dominio;?>';
    
//funciones de inicio
listarcursos();listarCategoria();listar_concepto_pago();

function listarcursos(){
    $.ajax({
        url: rutaproceso,
        type: 'POST',
        data:{
			'accion': 'listar_cursos' 
			},
        dataType: 'JSON',
        success: function(resp){
         
            //limpiamos la tabla .contcards
            $('.contcards').html('');//dataTable.clear().draw(); 
            //recorremos los datos retornados y lo añadimos a la tabla
            var counter=1;//numero de datos
            for (var i = 0; i < resp.length; i++) {
              //var foto='../files/profiles/anuncios/cursodefault2.jpg';
                var imgCurso='';
                if(resp[i]["imagen_curso"]!='' && resp[i]["imagen_curso"]!=null){
                  imgCurso='./files/'+nombre_dominio+'/cursoextracurricular/'+resp[i]["imagen_curso"]; }else{ 
                    if(resp[i]['id_curso_asignacion']==null){
                        imgCurso='./files/'+nombre_dominio+'/cursoextracurricular/cursodefault1.jpg'; }else{
                        imgCurso='./files/'+nombre_dominio+'/cursoextracurricular/cursodefault2.jpg';  }    
                }
                var fotodocente='';
                if(resp[i]["foto"]!='' && resp[i]["foto"]!=null){
                  //fotodocente='./files/profiles/personal/'+resp[i]['foto']; 
                    fotodocente='./files/logos/avatar.jpg';    
                }else{  
                  fotodocente='./files/logos/avatar.jpg';      
                }
                var imgCategoria='';
                if(resp[i]["imagen"]=='' || resp[i]["imagen"]==null ){
                    //certificado='<span class="btn btn-warning fa fa-certificate " title="El curso cuenta con certificado">'+resp[i]['carga_horaria']+'h</span> ';
                    imgCategoria='../files/categoriaCurso/categoriaCurso.jpg'; 
                   }else{
                    imgCategoria='../files/categoriaCurso/'+resp[i]["imagen"]; 
                       
                   }
                var certificado='';
                if(resp[i]["certificado"]=='SI'){
                    //certificado='<span class="btn btn-warning fa fa-certificate " title="El curso cuenta con certificado">'+resp[i]['carga_horaria']+'h</span> ';
                    certificado='<span class="fa fa fa-certificate fa-lg" style="color: #ff6f00;" title="El curso cuenta con certificado"></span>'+resp[i]['carga_horaria']+'h';
                   }
                //console.log(resp[i]['pension_id']);
                var datos= resp[i]['inscritos']+'*'+resp[i]['nombres']+'*'+resp[i]['primer_apellido']+'*'+resp[i]['segundo_apellido']+'*'+fotodocente+'*'+resp[i]['id_categoria']+'*'+resp[i]['categoria']+'*'+imgCategoria+'*'+resp[i]['id_curso']+'*'+resp[i]['categoria_id']+'*'+resp[i]['nombre_curso']+'*'+imgCurso+'*'+resp[i]['cupo_minimo_curso']+'*'+resp[i]['objetivo_curso']+'*'+resp[i]['descripcion_curso']+'*'+resp[i]['estado_curso']+'*'+resp[i]['usuario_registro']+'*'+resp[i]['fecha_registro']+'*'+resp[i]['usuario_modificacion']+'*'+resp[i]['fecha_modificacion']+'*'+resp[i]['id_curso_asignacion']+'*'+resp[i]['curso_id']+'*'+resp[i]['asignacion_id']+'*'+resp[i]['horario_dia']+'*'+resp[i]['cupo']+'*'+resp[i]['fecha_inicio']+'*'+resp[i]['fecha_fin']+'*'+resp[i]['modulo']+'*'+resp[i]['duracion']+'*'+resp[i]['certificado']+'*'+resp[i]['carga_horaria']+'*'+resp[i]['periodo']+'*'+resp[i]['ambiente']+'*'+resp[i]['fecha_inscripcion_inicio']+'*'+resp[i]['fecha_inscripcion_fin']+'*'+resp[i]['observaciones']+'*'+resp[i]['descripcion_cat']+'*'+resp[i]['pension_id'];//+'*'+resp[i]['nombre_pre']+'*'+resp[i]['desc_pre']+'*'+resp[i]['tipo_pre'];//foto=resp[i]['imagen_curso'] , pre.nombre AS nombre_pre,pre.descripcion AS desc_pre,pre.tipo AS tipo_pre 
                    
                var botones='';
                if(resp[i]['id_curso_asignacion']==null){ 
                 $('.contcards').append('<div categoria="'+resp[i]['categoria']+'" class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12" style="  -webkit-perspective: 800px; perspective: 800px; ">  <div class="card cardMy"  onclick="ver_curso('+"'"+datos+"'"+')">       <img src="'+imgCurso+'" alt="Card image" class="imgCard" > <div class="card-body">   <h5><b>'+resp[i]['nombre_curso'].toUpperCase()+'</b></h5> <p class="card-text">'+resp[i]['objetivo_curso'].toUpperCase()+'</p><p class="card-text">  '+resp[i]['descripcion_curso'].toUpperCase()+'</p>    <div class=" badge badge-info">  <strong><span class="fa fa-thumbs-up fa-lg"></span>  '+resp[i]['categoria']+' </strong></div>  <div class="alert alert-secondary  d-flex justify-content-between align-items-center">   SIN ASIGNAR </div>  </div> </div>  </div>');    // infor
                   
                   }else{
                   $('.contcards').append('<div categoria="'+resp[i]['categoria']+'" class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12"  style="  -webkit-perspective: 800px; perspective: 800px; "> <div class="card cardMy" onclick="ver_curso('+"'"+datos+"'"+')">  <div class="ribbons bg-warning"></div>  <div class="ribbons-text">Nuevo</div>  <span class="float_isqab back_lila">   <p class="fa fa-street-view  fa-2x"></p><p>'+resp[i]['inscritos']+'/'+resp[i]['cupo']+'</p><p>min '+resp[i]['cupo_minimo_curso']+'</p>  </span> <img src="'+imgCurso+'" alt="Card image" class="imgCard" >  <div class="card-body">  <h5><b>'+resp[i]['nombre_curso'].toUpperCase()+'</b></h5>  <p class="card-text"><span class="fa fa-clock fa-lg"></span>'+resp[i]['horario_dia']+'</p><p class="card-text">'+resp[i]['objetivo_curso'].toUpperCase()+'</p>     <div class=" badge badge-info ">  <strong><span class="fa fa-thumbs-up fa-lg"></span>   '+resp[i]['categoria']+'</strong></div>  <div class="alert alert-warning  d-flex justify-content-between align-items-center" style=" font-size: .8em;" >   <img src="./files/logos/avatar.jpg" alt="" class="imgDocente"> '+resp[i]['nombres']+' '+resp[i]['primer_apellido']+'<span >'+certificado+'</span>  </div>  </div>  </div>  </div>');
                   }//<img src="./files/'+resp[i]['foto']+'" alt="" class="imgDocente">
             
            }
            $('.contcards');//.toLowerCase();//toLowerCase//toUpperCase
	    }
	});
}

function listarCategoria(){
     $.ajax({
        url: rutaproceso,
        type: 'POST',
        data:{
			'accion': 'listar_categorias' 
			},
        dataType: 'JSON',
        success: function(resp){
         
            //limpiamos la tabla .contcards
             $('#selCategoriaCurso').html('<option value="">Seleccione...</option>');//dataTable.clear().draw(); 
            //recorremos los datos retornados y lo añadimos a la tabla
			 $('.list-categorias').html('<span class="btn btn-success fa fa-plus btn-block btnnewCategoria" title="Nueva categoria" onclick="crear_categoria()"> Nueva categoria</span>');
            var counter=1;//numero de datos
            for (var i = 0; i < resp.length; i++) {
              //var foto='../files/profiles/anuncios/cursodefault2.jpg';
                var imgCat='';
                if(resp[i]["imagen"]!='' && resp[i]["imagen"]!=null){
                  imgCat='';//'./files/categoriaCurso/'+resp[i]["imagen"];
                  }else{ 
                    imgCat='';//'./files/categoriaCurso/image.jpg';     
                }   
                var datos= resp[i]['categoria']+'*'+resp[i]['descripcion_cat']+'*'+resp[i]['id_categoria']+'*'+resp[i]['imagen'];//categoria: "Musical" descripcion_cat: "Enseñando instrumentos de cuerda" id_categoria: "1" imagen: 
                
                $('#selCategoriaCurso').append('<option value="'+resp[i]['id_categoria']+'">'+resp[i]['categoria']+'</option>');
				//categoria lista principal
				 $('.list-categorias').prepend('<li class="list-group-item d-flex justify-content-between align-items-center" title="'+resp[i]['descripcion_cat']+'" onclick="filt_por_cat('+"'"+resp[i]['categoria']+"'"+')" ondblclick="edit_categoria('+"'"+datos+"'"+')">  '+resp[i]['categoria']+'<span class="badge badge-primary badge-pill">'+resp[i]['ncategorias']+'</span> </li>');
                 //  <img src="'+imgCat+'" alt="user" class=" rounded-circle user-avatar-md mr-2 ">
            }$('.list-categorias').prepend('<li class="list-group-item d-flex justify-content-between align-items-center" title="Mostrar todos"  onclick="filt_por_cat('+"'todos'"+')"> Ver todas categorias</li>');
	    }
	});
}
function crear_categoria(){
	$('#modal_categoria').modal('show');
	$("#form_categoria")[0].reset();
	$('.btn-eliminar').hide();
	$('.btn-registrar').text('Guardar nuevo');
	
}
function edit_categoria(datos){
	var d=datos.split('*');
	// resp[i]['categoria']+'*'+resp[i]['descripcion_cat']+'*'+resp[i]['id_categoria']+'*'+resp[i]['imagen'];
	$('#form_categoria [name=nombre]').val(d[0]);
	$('#form_categoria [name=descripcion]').val(d[1]);
	$('#form_categoria [name=id_cat]').val(d[2]);
	$('#modal_categoria').modal('show');
	$('.btn-eliminar').show();
	$('.btn-registrar').text('Editar');
	
}
function elim_categoria(){
	var id=$('#id_cat').val();
    console.log('Eliminando categoria con id:'+id);
	 alertify.confirm('<span  >ELIMINAR CATEGORIA</span>', 'Esta accion eliminara esta categoria, esta accion podria afectar a todos los cursos creados con esta categaria. ¿Desea eliminar?', function(){ //casi de si
            $.ajax({ url: rutaproceso, type:'POST', data: {'accion':'eliminar_categoria', 'id_componente':id}, success: function(resp){ 
                    switch(resp){
                        case '1': //$("#modal_todos").modal("hide");
                        alertify.success('Se elimino el registro correctamente');
                       listarCategoria();$('#modal_categoria').modal('show');
                         break;
                        case '2': //$("#modal_todos").modal("hide");
                         alertify.error('No se pudo eliminar '); 
                        break;
                        case '3': //$("#modal_todos").modal("hide");
                         alertify.error('Primero deve eliminar todos los cursos creados en esta categoria'); 
                        break;
                    }
              }});
    },function(){ 
              alertify.notify('No eliminado', 'custom'); 
         
    });
}
function filt_por_cat(cat){
    //alert(cat);
    if(cat=='todos'){
        $('.contcards').find('div').slideDown(); 
    }else{    
        $('.contcards').find('[categoria='+cat+']').slideDown();
        $('.contcards').find('[categoria='+cat+']').siblings().slideUp();
    }
    
}
function filtrarkey(obj){
    var dato=$(obj).val().toUpperCase();
    console.log(dato);
    //$('.contcards').find('div')
    if($.trim(dato)!=""){
   $(".contcards div:not(:contains('"+dato+"'))").hide();//.addClass();
       }
    else{
        $(".contcards div").show();//.removeClass("miClase");
    }
    
}
var id_curso=0;//id variable de curso asignado
var id_curso_asignacion=0;//id variable de curso  var id_curso_asignacion=
var id_pension=0;//id variable de curso  var id_curso_asignacion=
var array_d_curso;
function  ver_curso(datos){
    var d=datos.split('*');
        array_d_curso=d;
    $(".listadocard").slideUp();
    $(".vistacard").slideDown();
    //datos generales
    
    //datos de curso

    $(".objetivo_curso").html(d[13]);
    $(".descripcion_curso").html(d[14]);
    $(".cupo_minimo_curso").html(d[12]);
    $(".imagen_curso").attr('src',d[11]);
    $(".nombre_curso").html(d[10]);
        //categoria
        $(".categoria").html(d[6]);
        $(".imagen").attr('src',d[7]);
        $(".descripcion_cat").html(d[36]);
    
    //datos de asignacion
    if(d[20]=='' || d[20]==null || d[20]=='null'){
       //ocultar los objetos actuales
        $(".horario_dia").parent().parent().hide();
        $(".cupo").html(0);
        $(".fecha_inscripcion_inicio").parent().parent().parent().hide();
        $(".editAsignacion").hide();//
        $(".estadisticasRow").hide();//certificacion/cargaHorario/inscritos/Modulo
        $('#tableInscritos').parent().parent().parent().parent().hide();//tabla inscritos
        $('.masdatos').hide();//tabla inscritos
        //alert('ES NULLL');
       }else{
        $(".horario_dia").parent().parent().show();
        //$(".cupo").html(0);
        $(".fecha_inscripcion_inicio").parent().parent().parent().show();
        $(".editAsignacion").show();//
        $(".estadisticasRow").show();//certificacion/cargaHorario/inscritos/Modulo
        $('#tableInscritos').parent().parent().parent().parent().show();//tabla inscritos
        $('.masdatos').show();//tabla inscritos
       //requisito
            //$(".nombre_pre").html(d[37]); 
            //$(".desc_pre").html(d[38]); 
            //$(".tipo_pre").html(d[39]); 
 
       /* 
    $(".inscritos").html(d[0]);
        $(".horario_dia").html(d[23]);
        $(".cupo").html(d[24]);
        $(".fecha_inicio").html(d[25]);
        $(".fecha_fin").html(d[26]);

        $(".modulo").html(d[27]);
        $(".duracion").html(d[28]);
        $(".certificado").html(d[29]);
        $(".carga_horaria").html(d[30]+'H');
        $(".periodo").html(d[31]);
        $(".ambiente").html(d[32]);
        $(".fecha_inscripcion_inicio").html(d[33]);
        $(".fecha_inscripcion_fin").html(d[34]);
        $(".observaciones").html(d[35]);
            //requisitos

            //docente asignacion
            $(".foto").attr('src',d[4]);
            $(".nombres").html(d[1]);
            $(".primer_apellido").html(d[2]);
            $(".segundo_apellido").html(d[3]); */
            
           
       }
    id_curso=d[8]; //varables glovales
   id_curso_asignacion=d[20];//varables glovales   
   id_pension=d[37];//varables glovales

   console.log(id_curso_asignacion+'id_curso_asignacion id_curso_asignacion id_curso_asignacion');
   console.log(id_pension+'id_pension id_pension id_pension');
   
    listar_asignaciones_simples();//list asignaciones
	listar_requisitos();
    /*
    0-> inscritos: "2"
    1-> nombres: "CONSTANCIO"
    2-> primer_apellido: "VILLAZANTE"
    3-> segundo_apellido: "HUASCO"
    4-> foto: "25bd80db63ebe84354198b24a0cc9376.jpg"
    5-> id_categoria: "1"
    6-> categoria: "Musical"
    7-> imagen: "simg"
    8-> id_curso: "1"
    9-> categoria_id: "1"
    10-> nombre_curso: "democracia"
    11-> imagen_curso: ""
    -> cupo_minimo_curso: "12"
    13-> objetivo_curso: "ggg"
    -> descripcion_curso: "fsdfsd"
    15-> estado_curso: "A"
    -> usuario_registro: "0"
    -> fecha_registro: "0000-00-00 00:00:00"
    18-> usuario_modificacion: "0"
    -> fecha_modificacion: "0000-00-00 00:00:00"
    
    20-> id_curso_asignacion: "3"
    -> curso_id: "1"
    -> asignacion_id: "1"
    23-> horario_dia: "14:00"
    24-> cupo: "12"
    25-> fecha_inicio: "0000-00-00"
    -> fecha_fin: "0000-00-00"
    -> modulo: "0"
    -> duracion: "0"
    -> certificado: "SI"
    30-> carga_horaria: "0"
    31-> periodo: ""
    32-> ambiente: ""
    -> fecha_inscripcion_inicio: "0000-00-00"
    -> fecha_inscripcion_fin: "0000-00-00"
    35-> observaciones: null 
    36-> descripcion_cat
    37->nombre_pre
38->desc_pre
39->tipo_pre*/
 
}
function listar_concepto_pago() {
    
   nivel = 0;//$("#nivel_academico option:selected").val()
    $.ajax({
      url: '?/s-cursos-extracurricular/procesos',
      type: 'POST',
      data: {
        'accion': 'listar_concepto_pago',//listar_docente',
        'nivel': nivel
      },
      dataType: 'JSON',
      success: function(resp){
        console.log('Listar doc'+ resp); 
                 
        //alert(resp[0]['id_catalogo_detalle']); 
        //console.log(resp);
        var cont=0;
        $("#id_pension").html("");
        $("#id_pension").append('<option value="">Seleccionar Concepto Pago</option>');//<option value=0>Sin asignar</option>
        for (var i = 0; i < resp.length; i++) {
 
                        $("#id_pension").append('<option  value="' + resp[i]["id_pensiones"] + '">'  + resp[i]["nombre_pension"]+' ' + resp[i]["codigo_concepto"]+ ' - Nro. Cuota ' +resp[i]["nro_cuota"]+ ' - Monto Cuota Bs. ' +resp[i]["monto"]+'</option>');
        }
        //console.log(resp[0]);
                   

      }
    }).done(function (data) {
           //alert('done');
            $('#id_pension').selectize();
        });
        
  }
function listar_requisitos(){
	   $.ajax({ url: rutaproceso, type: 'POST', data:{
			'accion': 'listar_requisitos',
            'id_curso':id_curso//asignado en vercurso
	   }, dataType: 'JSON', success: function(resp){
          
            $('.contrequisitos').html(''); 
             /*id_contenido_prerequisito: "1"
            curso_id: "modulo1,modulo2"
            descripcion: "1"
            nombre: "Modulos anteriores"
            tipo: "PREREQUISITO"*/
            for (var i = 0; i < resp.length; i++) {//style="color:red"
		   		var datos=resp[i]['id_contenido_prerequisito']+'*'+resp[i]['curso_id']+'*'+resp[i]['descripcion']+'*'+resp[i]['nombre']+'*'+resp[i]['tipo'];
				
				$('.contrequisitos').append('<p class="mb-2"><span class="fas fa-thumbtack" ></span> Requisito:<span class="text-dark font-medium ml-2 nombre_pre">'+resp[i]['nombre']+'</span> - <span class="desc_pre mr-2">'+resp[i]['descripcion']+'</span>  Tipo:<span class="text-dark font-medium  tipo_pre">'+resp[i]['tipo']+'</span> <span class=" fa fa-edit   " style="color:blue;cursor:pointer" onclick="edit_requisito('+"'"+datos+"'"+')"></span> <span class="fa fa-times  " style="color:red;cursor:pointer" onclick="eliminar_requisito('+resp[i]['id_contenido_prerequisito']+')"></span></p>');
				
			}
	   }
	});
}
	
function edit_requisito(datos){
	var d=datos.split('*');
	/*id_contenido_prerequisito 
    curso_id 
    descripcion 
    nombre 
    tipo: "PREREQUISITO"*/
	$("#modal_requisito").modal("show"); 
	$('#form_requisito [name=id_requisito]').val(d[0]);
	$('#form_requisito [name=id_curso]').val(d[1]);
	$('#form_requisito [name=nombre]').val(d[3]);
	$('#form_requisito [name=tipo]').val(d[4]);
	$('#form_requisito [name=descripcion]').val(d[2]);
	//alert('hola edit');
}	
function eliminar_requisito(id){
    alertify.confirm('<span  >ELIMINAR REQUISITO</span>', 'Esta accion eliminara este prerequisito, no afectara al cuso general. ¿Desea eliminar?', function(){ //casi de si
    $.ajax({ url: rutaproceso, type:'POST', data: {'accion':'eliminar_requisito', 'id_componente':id}, success: function(resp){ 
            switch(resp){
                case '1': //$("#modal_todos").modal("hide");
                alertify.success('Se elimino el registro correctamente');
                listar_requisitos();
                 break;
                case '2': //$("#modal_todos").modal("hide");
                 alertify.error('No se pudo eliminar '); 
                break;
            }
      }});
    },function(){ 
              alertify.notify('No eliminado', 'custom'); 
         
    });
    
	//alert('hola edit');
}
	
var array_d_asignacion;

function listar_asignaciones_simples(){//listar asignaciones 
    //fechaini - hora ini - docente
      $.ajax({
        url: rutaproceso,
        type: 'POST',
        data:{
			'accion': 'listar_cursos_asignaciones',
            'id_curso':id_curso//asignado en vercurso
			},
        dataType: 'JSON',
        success: function(resp){
           //ocultar el boton eliminar
           if(resp.length>0){
                $('.btn-elim_curso').hide();
               
            }else{
                 $('.btn-elim_curso').show();
            }
            //limpiamos la tabla .contcards
            $('.listactivaciones').html('');//dataTable.clear().draw(); 
            //recorremos los datos retornados y lo añadimos a la tabla
            var counter=1;//numero de datos
            var badge='';
            for (var i = 0; i < resp.length; i++) {
                //MOSTRAR DATOS DE ASIGNACION
                var active='';
                var datos=resp[i]['ambiente']+'*'+resp[i]['asignacion_id']+'*'+resp[i]['carga_horaria']+'*'+resp[i]['certificado']+'*'+resp[i]['cupo']+'*'+resp[i]['curso_id']+'*'+resp[i]['duracion']+'*'+resp[i]['estado']+'*'+resp[i]['estadoasig']+'*'+resp[i]['fecha_fin']+'*'+resp[i]['fecha_inicio']+'*'+resp[i]['fecha_inscripcion_fin']+'*'+resp[i]['fecha_inscripcion_inicio']+'*'+resp[i]['fecha_modificacion']+'*'+resp[i]['fecha_registro']+'*'+resp[i]['foto']+'*'+resp[i]['gestion']+'*'+resp[i]['habilitado']+'*'+resp[i]['horario_dia']+'*'+resp[i]['id_curso_asignacion']+'*'+resp[i]['inscritos']+'*'+resp[i]['modulo']+'*'+resp[i]['nombres']+'*'+resp[i]['observaciones']+'*'+resp[i]['periodo']+'*'+resp[i]['primer_apellido']+'*'+resp[i]['segundo_apellido']+'*'+resp[i]['usuario_modificacion']+'*'+resp[i]['usuario_registro']+'*'+resp[i]['pension_id'];
                if(resp[i]['id_curso_asignacion']==id_curso_asignacion){//id_curso_asignacion asig en crear =0?
                  // console.log('id encontrado OKOK');
                   ver_asignacion(datos);
                   active='active';
                 }
                  // console.log('listado normal');
                if(resp[i]['habilitado']=='SI'){
                   badge='<span class="badge badge-success">HABILITADO</span> ';
                   }else if(resp[i]['habilitado']=='ESPERA'){
                   badge='<span class="badge badge-warning">EN ESPERA</span> ';   
                   }else{
                   badge='<span class="badge badge-light">CONCLUIDO</span> ';   
                   }
                $('.listactivaciones').append('<li class="list-group-item d-flex justify-content-between '+active+'" onclick="ver_asignacion('+"'"+datos+"'"+',this)">  <div class="text">  <h6 class="my-0">'+badge+' '+resp[i]['fecha_inicio']+' </h6> <small class="text-justify text-muted">DOCENTE: '+resp[i]['nombres']+' '+resp[i]['primer_apellido']+'<br>'+'CONCEPTO PAGO: '+resp[i]['nombre_pension']+'</small>  </div>  <span class="text-muted">'+resp[i]['horario_dia']+'</span>  </li>');
                
            }
        }
      });
}

function ver_asignacion(datos,obj){
     $(obj).addClass('active').siblings().removeClass('active');
    /* ALFABETICAMENTE
    0=>ambiente                   1=>asignacion_id                2=>carga_horaria            3=>certificado: "NO"    4=>cupo: "12"                   5=>curso_id: "1"                6=>duracion: "5"            7=>estado: "A"       8=>estadoasig: "A"              9=>fecha_fin: "0000-00-00"      10=> fecha_inicio: "0000-00-00"   
    11=> fecha_inscripcion_fin: "0000-00-00"                        12=> fecha_inscripcion_inicio: "0000-00-00"     13=> fecha_modificacion: "0000-00-00 00:00:00"                  14=> fecha_registro: "0000-00-00 00:00:00"        15=> foto: "25bd80db63ebe84354198b24a0cc9376.jpg"                16=> gestion: "1"
    17=> habilitado: "SI"    18=> horario_dia: "14:00"     19=> id_curso_asignacion: "3"  20=> inscritos: "2"     
    21=> modulo: "3"         22=> nombres: "CONSTANCIO"    23=> observaciones: "observaciones a los que lleguen tarde"
    24=> periodo: ""         25=> primer_apellido: "VILLAZANTE"     26=> segundo_apellido: "HUASCO"
    27=> usuario_modificacion: "0"    28=> usuario_registro: "0"*/
      var d=datos.split('*');
     id_curso_asignacion=d[19];//varables glovales
     array_d_asignacion=d;//gloval para edicion y eliminacion
     var fotodocente='';
    if(d[15]!='' && d[15]!='null'){
      //fotodocente='./files/profiles/personal/'+d[15]; 
    }else{  
      fotodocente='./files/logos/avatar.jpg';      
      }
     console.log('corr ver_asignacion()'+fotodocente);
     var inscritos=d[20];
     var cupo=d[4]; 
     var porc=(inscritos*100/cupo)+'%';
     $(".progress-bar").attr('style','width: '+porc);
     
     $(".inscritos").html(d[20]);
        $(".horario_dia").html(d[18]);
        $(".cupo").html(d[4]);
        $(".fecha_inicio").html(d[10]);
        $(".fecha_fin").html(d[9]);

        $(".modulo").html(d[21]);
        $(".duracion").html(d[6]);
        $(".certificado").html(d[3]);
        $(".carga_horaria").html(d[2]+'H');
        $(".periodo").html(d[24]);
        $(".ambiente").html(d[0]);
        $(".fecha_inscripcion_inicio").html(d[12]);
        $(".fecha_inscripcion_fin").html(d[11]);
        $(".observaciones").html(d[23]);
        //requisitos

        //docente asignacion
        $(".foto").attr('src',fotodocente);
        $(".nombres").html(d[22]);
        $(".primer_apellido").html(d[25]);
        $(".segundo_apellido").html(d[26]); 
      listarIncritos(d[19]);
     //ocultar insripcion
     //if(inscritos<cupo){
         //inscribir
        //$('.btninscribir').show();
        //$('.btnllenoins').hide();
         
        //}else{
        // $('.btninscribir').hide();
        // $('.btnllenoins').show();
        //}
        $('.disponibles').html(cupo-inscritos);
    // console.log(cupo+'-dasdasd-'+inscritos);
            
 }

function listarIncritos(asignacion_id){
   // alert('list eins');
   $('#tableInscritos').find('tbody').html('');//dataTable.clear().draw(); 
    $.ajax({
        url: rutaproceso,
        type: 'POST',
        data:{
			'accion': 'listar_inscritos',
            'id':asignacion_id
			},
        dataType: 'JSON',
        success: function(resp){
            if(resp.length>0){
                $('.btn-elim_asig').hide();
               
            }else{
                 $('.btn-elim_asig').show();
            }
           //  debugger;
            var counter=1;//numero de datos
            for (var i = 0; i < resp.length; i++) {
                var datos=resp[i]['id_curso_inscripcion']+'*'+resp[i]['estudiante_id']+'*'+resp[i]['curso_asignacion_id']+'*'+resp[i] ['tipo_inscripcion']+'*'+resp[i]['observacion'];
				
              /*nombres: "LIZBETH ANALY"
            primer_apellido: "GARCIA"
            segundo_apellido: "APAZA"
            id_curso_inscripcion: "1"
            gestion_id: "1"
            estudiante_id: "123"
            curso_asignacion_id: "3"
            fecha_inscripcion: "0000-00-00"
            hora_inscripcion: "00:00:00"
            tipo_inscripcion: ""
            usuario_registro: "0"
            fecha_registro: "0000-00-00 00:00:00"
            observacion: null
            estado_curso_inscripcion*/
                if(resp[i]['genero']=='v'){
                   src='./files/profiles/avatar_v.jpg';
                   
                   }else{
                   src='./files/profiles/avatar_m.jpg';
                   }
                $('#tableInscritos').find('tbody').append(' <tr>   <td>'+(i+1)+'</td>   <td>'+resp[i]['nombres']+' '+resp[i]['primer_apellido']+' '+resp[i]['segundo_apellido']+'</td>  <td>'+resp[i]['nombre_aula']+' '+resp[i]['nombre_paralelo']+' '+resp[i]['nombre_nivel']+'</td>  <td>'+resp[i]['fecha_registro']+'</td>  <td>'+resp[i]['observacion']+' </td> <td>     <a href="javascript:void(0);" class="btn btn-danger" onclick="eliminar_inscripcion('+resp[i]['id_curso_inscripcion']+','+resp[i]['curso_asignacion_id']+')">Eliminar </a>   </td></tr>'); //+resp[i]['tipo_inscripcion']+'/'
                //<td>  <div class="dropdown float-right">  <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="true">   <i class="mdi mdi-dots-vertical"></i>   </a>     <div class="dropdown-menu dropdown-menu-right">  <a href="javascript:void(0);" class="dropdown-item" onclick="editar_Inscripcion('+"'"+datos+"'"+')">Editar Inscripcion</a>     <a href="javascript:void(0);" class="dropdown-item">Reporte de inscripcion</a>  <a href="javascript:void(0);" class="dropdown-item" onclick="eliminar_inscripcion('+resp[i]['id_curso_inscripcion']+','+resp[i]['curso_asignacion_id']+')">Eliminar Inscripcion</a>    </div>  </div>  </td>  
                //<a href="javascript:void(0);" class="dropdown-item" onclick="editar_Inscripcion('+"'"+datos+"'"+')">Editar Inscripcion</a>     <a href="javascript:void(0);" class="dropdown-item">Reporte de inscripcion</a>
                //foto avatar  <div class="m-r-10"><img src="'+src+'" alt="user" width="35"></div>
            }
        }});
    }
function volver(){
    $(".listadocard").slideDown();
    $(".vistacard").slideUp();
}
    
function administrarCurso(){
    
    $(".vistacard").slideUp();
    $(".adminCurso").slideDown();
}
function volver2(){
    $(".vistacard").slideDown();//mostrar
    $(".adminCurso").slideUp();//ocultar
}
function  crear_curso(){
    resetForm();
    //$("#form_curso")[0].reset();
    //$("#form_curso").trigger("reset");
    $("#modal_curso").modal("show");
    //$("#form_curso")[0].reset();
}

function resetForm() {
   //console.log('gggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg');
    document.getElementById("form_curso").reset();
} 

function  edit_curso(){
    resetForm();
    var d=array_d_curso;//array creado en ver_curso()
    $("#modal_curso").modal("show");
    $("[name=id_curso]").val(d[8]); 
    $("[name=nombre]").val(d[10]);
    $("[name=cupo]").val(d[12]);
    $("[name=objetivo]").val(d[13]);
    $("[name=descripcion]").val(d[14]);
    $("[name=categoria]").val(d[9]);
     
    
    /*
    0-> inscritos: "2"
    1-> nombres: "CONSTANCIO"
    2-> primer_apellido: "VILLAZANTE"
    3-> segundo_apellido: "HUASCO"
    4-> foto: "25bd80db63ebe84354198b24a0cc9376.jpg"
    
    5-> id_categoria: "1"
    6-> categoria: "Musical"
    7-> imagen: "simg"
    
    8-> id_curso: "1"
    9-> categoria_id: "1"
    10-> nombre_curso: "democracia"
    11-> imagen_curso: ""
    -> cupo_minimo_curso: "12"
    13-> objetivo_curso: "ggg"
    -> descripcion_curso: "fsdfsd"
    15-> estado_curso: "A"
    -> usuario_registro: "0"
    -> fecha_registro: "0000-00-00 00:00:00"
    18-> usuario_modificacion: "0"
    -> fecha_modificacion: "0000-00-00 00:00:00"
    
    20-> id_curso_asignacion: "3"
    -> curso_id: "1"
    -> asignacion_id: "1"
    23-> horario_dia: "14:00"
    24-> cupo: "12"
    25-> fecha_inicio: "0000-00-00"
    -> fecha_fin: "0000-00-00"
    -> modulo: "0"
    -> duracion: "0"
    -> certificado: "SI"
    30-> carga_horaria: "0"
    31-> periodo: ""
    32-> ambiente: ""
    -> fecha_inscripcion_inicio: "0000-00-00"
    -> fecha_inscripcion_fin: "0000-00-00"
    35-> observaciones: null 
    36-> descripcion_cat
    37->nombre_pre
38->desc_pre
39->tipo_pre*/
    
    
}

function eliminar_curso(){
    
    alertify.confirm('<span style="color:red">ELIMINAR CURSO</span>', 'Esta accion eliminara este registro, sus inscritos y sus archivos. ¿Desea eliminar?', function(){ //casi de si
        $.ajax({ url: rutaproceso, type:'POST', data: {'accion':'eliminar_curso', 'id_componente':id_curso}, success: function(resp){ 
                switch(resp){
                    case '1': //$("#modal_todos").modal("hide");
                    alertify.success('Se elimino el registro correctamente');
                    listarcursos();volver();
                     break;
                    case '2': //$("#modal_todos").modal("hide");
                     alertify.error('No se pudo eliminar '+resp); 
                    break;
                }
          }});
    },function(){ 
              alertify.notify('No eliminado', 'custom');
              
    });
    
}

function imprimir_curso(){
   //alert('hola quino');
    $(location).attr('href','?/s-cursos-extracurricular/expexcel/'+id_curso_asignacion+'/'+id_curso);///'+turno+'/'+nivel+'/'+aula+'/'+paralelo);
}

function crear_Inscribir(){
    //alert(id_pension);
    $("#form_inscribir")[0].reset();
    $(".id_asignacion").val(id_curso_asignacion);     
    $(".id_pensiones").val(id_pension);     
    $("#modal_inscribir").modal("show");
	$('#form_inscribir [name=id_estudiante]').parent().show();
}

function editar_Inscripcion(datos){
	var d=datos.split('*');
	$('#form_inscribir #id_inscribir').val(d[0]);
	$('#form_inscribir [name=id_asignacion]').val(d[2]);
	$('#form_inscribir [name=id_estudiante]').parent().hide();
	$('#form_inscribir [name=tipo]').val(d[3]);
	$('#form_inscribir [name=obs]').val(d[4]);
    $("#modal_inscribir").modal("show");
	/*resp[i]['id_curso_inscripcion']+'*'+resp[i]['estudiante_id']+'*'+resp[i]['curso_asignacion_id']+'*'+resp[i] ['tipo_inscripcion']+'*'+resp[i]['observacion'];*/
}

function eliminar_inscripcion(id_inscripcion,asignacion_id){
    alertify.confirm('<span style="color:red">ELIMINAR INSCRITO</span>', 'Esta accion eliminara este registro. ¿Desea eliminar?', function(){ //casi de si
            $.ajax({ url: rutaproceso, type:'POST', data: {'accion':'eliminar_inscripcion', 'id_componente':id_inscripcion}, success: function(resp){ 
                    switch(resp){
                        case '1': 
						$("#modal_inscribir").modal("hide");
                        alertify.success('Se elimino el registro correctamente');
                       	//listarIncritos(asignacion_id);
                        listar_asignaciones_simples();//cantidades de inscripcion
                         break;
                        case '2': 
						$("#modal_inscribir").modal("hide");
                       	
                         alertify.error('No se pudo eliminar '+resp); 
                        break;
                    }
              }});
    },function(){ 
              alertify.notify('No eliminado', 'custom');
              
    });
    
}

function btnimprimir(){
    //var turno = $("#turno option:selected").val();//this
	//var nivel = $("#nivel option:selected").val();//this
    //var aula = $("#aula option:selected").val();//this
	//var paralelo = $("#paralelo_listar option:selected").val();//this
     
    $(location).attr('href','?/s-cursos-extracurricular/expexcel/'+id_curso_asignacion);///'+turno+'/'+nivel+'/'+aula+'/'+paralelo);
}
function nueva_asignacion(){
    $("#form_asignacion")[0].reset();
    $(".id_curso").val(id_curso);//varable gloval listar
    $("#modal_asignacion").modal("show");

}
function editar_asignacion(){
    
    $("#modal_asignacion").modal("show");
     
     var d=array_d_asignacion;//array creado en ver_asignacion() 
    $("[name=id_asigcurso]").val(d[19]); 
    $("[name=fechaini]").val(d[10]); 
    $("[name=fechafin]").val(d[9]); 
    $("[name=horaini]").val(d[18]); 
    $("[name=duracion]").val(d[6]); 
    $("[name=ambiente]").val(d[0]); 
    $("[name=periodo]").val(d[24]); 
    $("[name=cupo]").val(d[4]); 
    $("[name=modulo]").val(d[21]); 
    $('#id_docente').data('selectize').setValue(d[1]);
    
    //$("#radio"+d[29]).attr('checked',true); 
    $("#radio"+d[3]).prop('checked',true); 
    $("[name=fechainscripini]").val(d[12]); 
    $("[name=fechainscripfin]").val(d[11]); 
    $("[name=cargaHoraria]").val(d[2]); 
     $("[name=observaciones]").val(d[23]); 
     /* ALFABETICAMENTE
0=>ambiente                   1=>asignacion_id                2=>carga_horaria            3=>certificado: "NO"    4=>cupo: "12"                   5=>curso_id: "1"                6=>duracion: "5"            7=>estado: "A"       8=>estadoasig: "A"              9=>fecha_fin: "0000-00-00"      10=> fecha_inicio: "0000-00-00"   
11=> fecha_inscripcion_fin: "0000-00-00"                        12=> fecha_inscripcion_inicio: "0000-00-00"     13=> fecha_modificacion: "0000-00-00 00:00:00"                  14=> fecha_registro: "0000-00-00 00:00:00"        15=> foto: "25bd80db63ebe84354198b24a0cc9376.jpg"                16=> gestion: "1"
17=> habilitado: "SI"    18=> horario_dia: "14:00"     19=> id_curso_asignacion: "3"  20=> inscritos: "2"     
21=> modulo: "3"         22=> nombres: "CONSTANCIO"    23=> observaciones: "observaciones a los que lleguen tarde"
24=> periodo: ""         25=> primer_apellido: "VILLAZANTE"     26=> segundo_apellido: "HUASCO"
27=> usuario_modificacion: "0"    28=> usuario_registro: "0"*/
    
    
}
function eliminar_asignacion(){
     alertify.confirm('<span style="color:red">ELIMINAR REGISTRO</span>', 'Esta accion eliminara este registro, sus inscritos y sus archivos. ¿Desea eliminar?', function(){ //casi de si
            $.ajax({ url: rutaproceso, type:'POST', data: {'accion':'eliminar_asignacion', 'id_componente':id_curso_asignacion}, success: function(resp){ 
                    switch(resp){
                        case '1': //$("#modal_todos").modal("hide");
                        alertify.success('Se elimino el registro correctamente');
                        listarcursos();volver();
                         break;
                        case '2': //$("#modal_todos").modal("hide");
                         alertify.error('No se pudo eliminar '+resp); 
                        break;
                    }
              }});
    },function(){ 
              alertify.notify('No eliminado', 'custom'); 
         
    });
    
}
function fin_asignacion(){
	 alertify.confirm('<span style="color:red">CULMINAR ACTIVIDAD</span>', 'Esta accion marcara esta asignacion como culminada. ¿Desea culminar con esta actividad?', function(){ //casi de si
            $.ajax({ url: rutaproceso, type:'POST', data: {'accion':'culminar_asignacion', 'id_componente':id_curso_asignacion}, success: function(resp){ 
                    switch(resp){
                        case '1': //$("#modal_todos").modal("hide");
                        alertify.success('Se culmina la actividad correctamente');
                        //listarcursos();
						volver();
                        break;
							
                        case '2': //$("#modal_todos").modal("hide");
                         alertify.error('No se pudo eliminar '+resp); 
                        break;
                    }
              }});
    },function(){ 
              alertify.notify('No eliminado', 'custom'); 
         
    });
}
	 
function crear_requisito(){
    $("#form_requisito")[0].reset();
     $(".id_curso").val(id_curso);//varable gloval listar
      $('#modal_requisito').modal('show');
}
 

//anteror 
/*function felicitacion(id){
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
    $('.btnvolver').show();
    var d=datos.split('*');
      $('.listpersonal').hide(); 
      $('.listKardex').show();
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
                    
                var botones='<a href="#" data-toggle="tooltip" data-title="Ver horario" class="btn btn-info btn-xs" onclick="editar('+"'"+datos+"'"+')"  title="Editar "><span class="fa fa-edit" ></span></a><a href="#" data-toggle="tooltip" data-title="Ver horario" class="btn btn-danger btn-xs"  onclick="eliminar('+resp[i]['id_kardex']+')" title="Eliminar"><span class="fa fa-trash " ></span></a>';
                 //tipo:::::::::
                 var tipo='sin tipo';
                 if(resp[i]["tipo_ev_kardex"]==1){
                     tipo='<p>Evaluacion</p>';
                 }else{
                     tipo='<p>Memorandum</p>';
                     
                 }
                 //file::::::::::
                 var file='';
            if(resp[i]["adjunto_kardex"]!='' && resp[i]["adjunto_kardex"]!=0){
               var nombreDescarga=resp[i]["adjunto_kardex"].split('-');
               file+='<a class="fa fa-download btn btn-default " onclick="descarga"  href="files/cardexPersonal/'+resp[i]["adjunto_kardex"]+'" dowload="'+resp[i]["adjunto_kardex"]+'"> '+nombreDescarga[1]+'</a>'; 
                 }else{
                file+='<a class="fa fa-file btn btn-default " onclick="descarga"> Sin archiv</a>'; 
               }
                 
                 if(resp[i]["tipo_kardex"]=='felicitacion'){
                  var tipokard='<span class="badge badge-success">felicitacion</span>';
                 dataTableFelici.row.add( [
                            counter,
                            resp[i]["fecha_kardex"],  
                            resp[i]["concepto_kardex"],  
                            resp[i]["observacion_kardex"],  
                            tipokard+tipo,  
                            file,//resp[i]["adjunto_kardex"],  
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
                             file,//resp[i]["adjunto_kardex"],  
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
	 var d = datos.split("*");;
    $('[name=id_kardex]').val(d[0]);
    $('[name=fecha_felicitacion]').val(d[1]);
    $('[name=concepto]').val(d[2]);
    $('[name=tipo_f]').val(d[5]);
    $('[name=descripcion]').val(d[3]);
    
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
                        listarcursos();
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
 */
</script> 
<style>
    .ajs-message.ajs-custom { color: #31708f;  background-color: #d9edf7;  border-color: #31708f; }
</style>