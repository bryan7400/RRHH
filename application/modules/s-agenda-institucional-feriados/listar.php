<?php

// Obtiene la cadena csrf
$csrf = set_csrf();  
$gestion=$_gestion['id_gestion'];
// Obtiene los agenda
$agenda = $db->query("SELECT * FROM rrhh_contrato WHERE estado = 'A'")->fetch();


$feriados = $db->query("SELECT * FROM ins_agenda_institucional WHERE estado = 'A'")->fetch();


// Obtiene los permisos 
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_editar = in_array('editar', $_views);
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
            <h2 class="pageheader-title">Agenda Institucional</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Configuración</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Agenda</a></li>
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
                                        <a href="#" onclick="abrir_crear();" class="dropdown-item">Crear Agenda</a>
                                         
                                        <?php endif ?> 

                                        <!-- 
                                        <?php if ($permiso_imprimir) : ?>
                                        <div class="dropdown-divider"></div>
                                        <a href="?/s-agenda-institucional-feriados/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Agenda</a>
                                        <?php endif ?> 
                                        <?php if ($permiso_imprimir) : ?>
                                        <div class="dropdown-divider"></div>
                                        <a href="?/s-agenda-institucional-feriados/excel" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Excel de Agenda</a>
                                        <?php endif ?>

                                        -->

                                        
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


<div class="row">
<div class="row col-2">
    </div>
<div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
    
    <div class="accrodion-regular">
        <div id="accordion4">
            <div style="background-color: #d4edda;" class="card ">
                <div class="card-header text-center"  id="headingEleven">
                    <h5 class="mb-0">
                       <button class="btn btn-link collapsed " data-toggle="collapse" data-target="#collapseEnero" aria-expanded="false" aria-controls="collapseEnero">
                         <span class="fas fa-angle-down mr-3"></span>ENERO
                     </button>       </h5>
                </div>
                <div style="background-color: #e2e3e5;" id="collapseEnero" class="collapse" aria-labelledby="headingEleven" data-parent="#accordion4">
                    <div class="card-body bg-white ">
                <!-- ============================================================== -->
                <!-- datos --> 
                <!-- ============================================================== -->
                
 <div class="row">

                        <?php foreach ($feriados as $nro => $feriado) : ?>


<?php $month_ini = date("m",strtotime($feriado['fecha_inicio'])); ?>
<?php $month_fin = date("m",strtotime($feriado['fecha_final'])); ?>

   

   <?php if ($month_ini== 1 || $month_fin== 1) : ?>


<div class="col-md-3">
        <div class="card-body <?= escape($feriado['color']); ?>" >

            <h5 class="card-title"><?= escape($feriado['titulo']); ?> (<?= escape($feriado['grupo']); ?>)</h5>
            <h6 class="card-subtitle text-muted">Asueto: <?= escape($feriado['tipo_agenda']); ?>                </h6>
            <img style="width:auto;height: 180px;" class="img-fluid mb-4" src="files/demoeducheck/agenda/<?= escape($feriado['imagen']); ?>"  alt="Card image cap">

            <p class="card-text" style="color: black;"><?= escape($feriado['descripcion']); ?></p>

            <figcaption class="figure-caption">
                                            <ul class="list-inline d-flex text-muted mb-0">
                                                <li class="list-inline-item text-truncate mr-auto"><?= escape($feriado['fecha_inicio']); ?>    al <?= escape($feriado['fecha_inicio']); ?> </li>
                                                

                                                    <li class="list-inline-item" >
                                                        <a  href='#' onclick='abrir_editar(<?= escape($feriado['id_agenda']); ?>)' style='color:white' >  <span><i class='icon-note'  style="background-color: orange;color:black;"></i></span></a>


                                                        <a href='#'   role='button' onclick='abrir_eliminar(<?= escape($feriado['id_agenda']); ?>)'><span class='icon-trash' style="background-color: red;color:black;" ></span></a>
                                                    </li>
                                            </ul>
                                        </figcaption>
            <p class="text-muted"></p>
            
        </div>
</div>








<?php else : ?>




<?php endif ?>

<?php endforeach ?>








                            </div>               
                <!-- ============================================================== -->
                <!-- end datos -->
                <!-- ============================================================== -->
                </div>
                </div>
            </div>
            

            <div style="background-color: #d4edda;" class="card ">
                <div class="card-header text-center" id="headingEleven">
                    <h5 class="mb-0">
                       <button class="btn btn-link collapsed " data-toggle="collapse" data-target="#collapseFebrero" aria-expanded="false" aria-controls="collapseFebrero">
                         <span class="fas fa-angle-down mr-3"></span>FEBRERO 
                     </button>       </h5>
                </div>
                <div style="background-color: #e2e3e5;"  id="collapseFebrero" class="collapse" aria-labelledby="headingEleven" data-parent="#accordion4">
                    <div class="card-body bg-white">
<div class="row">

                        <?php foreach ($feriados as $nro => $feriado) : ?>


<?php $month_ini = date("m",strtotime($feriado['fecha_inicio'])); ?>
<?php $month_fin = date("m",strtotime($feriado['fecha_final'])); ?>

   

   <?php if ($month_ini== 2 || $month_fin== 2) : ?>


<div class="col-md-3">
        <div class="card-body <?= escape($feriado['color']); ?>" >

            <h5 class="card-title"><?= escape($feriado['titulo']); ?> (<?= escape($feriado['grupo']); ?>)</h5>
            <h6 class="card-subtitle text-muted">Asueto: <?= escape($feriado['tipo_agenda']); ?>                </h6>
            <img style="width:auto;height: 180px;" class="img-fluid mb-4" src="files/demoeducheck/agenda/<?= escape($feriado['imagen']); ?>"  alt="Card image cap">

            <p class="card-text" style="color: black;"><?= escape($feriado['descripcion']); ?></p>

            <figcaption class="figure-caption">
                                            <ul class="list-inline d-flex text-muted mb-0">
                                                <li class="list-inline-item text-truncate mr-auto"><?= escape($feriado['fecha_inicio']); ?>    al <?= escape($feriado['fecha_inicio']); ?> </li>
                                                

                                                    <li class="list-inline-item" >
                                                        <a  href='#' onclick='abrir_editar(<?= escape($feriado['id_agenda']); ?>)' style='color:white' >  <span><i class='icon-note'  style="background-color: orange;color:black;"></i></span></a>


                                                        <a href='#'   role='button' onclick='abrir_eliminar(<?= escape($feriado['id_agenda']); ?>)'><span class='icon-trash' style="background-color: red;color:black;" ></span></a>
                                                    </li>
                                            </ul>
                                        </figcaption>
            <p class="text-muted"></p>
            
        </div>
</div>








<?php else : ?>




<?php endif ?>

<?php endforeach ?>








                            </div>
                    </div>
                </div>
            </div>
            <div style="background-color: #d4edda;" class="card">
                <div class="card-header text-center" id="headingTwelve">
                    <h5 class="mb-0">
    <button class="btn btn-link collapsed " data-toggle="collapse" data-target="#collapseMarzo" aria-expanded="false" aria-controls="collapseMarzo">
          <span class="fas fa-angle-down mr-3"></span>MARZO 
                     </button>
                               </h5>
                </div>
                <div style="background-color: #e2e3e5;"  id="collapseMarzo" class="collapse" aria-labelledby="headingTwelve" data-parent="#accordion4">
                    <div class="card-body">

                        <div class="row">

                        <?php foreach ($feriados as $nro => $feriado) : ?>


<?php $month_ini = date("m",strtotime($feriado['fecha_inicio'])); ?>
<?php $month_fin = date("m",strtotime($feriado['fecha_final'])); ?>

   

   <?php if ($month_ini== 3 || $month_fin== 3) : ?>


<div class="col-md-3">
        <div class="card-body <?= escape($feriado['color']); ?>" >

            <h5 class="card-title"><?= escape($feriado['titulo']); ?> (<?= escape($feriado['grupo']); ?>)</h5>
            <h6 class="card-subtitle text-muted">Asueto: <?= escape($feriado['tipo_agenda']); ?>                </h6>
            <img style="width:auto;height: 180px;" class="img-fluid mb-4" src="files/demoeducheck/agenda/<?= escape($feriado['imagen']); ?>"  alt="Card image cap">

            <p class="card-text" style="color: black;"><?= escape($feriado['descripcion']); ?></p>

            <figcaption class="figure-caption">
                                            <ul class="list-inline d-flex text-muted mb-0">
                                                <li class="list-inline-item text-truncate mr-auto"><?= escape($feriado['fecha_inicio']); ?>    al <?= escape($feriado['fecha_inicio']); ?> </li>
                                                

                                                    <li class="list-inline-item" >
                                                        <a  href='#' onclick='abrir_editar(<?= escape($feriado['id_agenda']); ?>)' style='color:white' >  <span><i class='icon-note'  style="background-color: orange;color:black;"></i></span></a>


                                                        <a href='#'   role='button' onclick='abrir_eliminar(<?= escape($feriado['id_agenda']); ?>)'><span class='icon-trash' style="background-color: red;color:black;" ></span></a>
                                                    </li>
                                            </ul>
                                        </figcaption>
            <p class="text-muted"></p>
            
        </div>
</div>








<?php else : ?>




<?php endif ?>

<?php endforeach ?>








                            </div>
                    </div>
                </div>
            </div>
            <div style="background-color: #d4edda;" class="card ">
                <div class="card-header text-center" id="headingEleven">
                    <h5 class="mb-0">
                       <button class="btn btn-link collapsed " data-toggle="collapse" data-target="#collapseAbril" aria-expanded="false" aria-controls="collapseAbril">
                         <span class="fas fa-angle-down mr-3"></span>ABRIL 
                     </button>       </h5>
                </div>
                <div style="background-color: #e2e3e5;"  id="collapseAbril" class="collapse" aria-labelledby="headingEleven" data-parent="#accordion4">
                    <div class="card-body bg-white">
<div class="row">

                        <?php foreach ($feriados as $nro => $feriado) : ?>


<?php $month_ini = date("m",strtotime($feriado['fecha_inicio'])); ?>
<?php $month_fin = date("m",strtotime($feriado['fecha_final'])); ?>

   

   <?php if ($month_ini== 4 || $month_fin== 4) : ?>


<div class="col-md-3">
        <div class="card-body <?= escape($feriado['color']); ?>" >

            <h5 class="card-title"><?= escape($feriado['titulo']); ?> (<?= escape($feriado['grupo']); ?>)</h5>
            <h6 class="card-subtitle text-muted">Asueto: <?= escape($feriado['tipo_agenda']); ?>                </h6>
            <img style="width:auto;height: 180px;" class="img-fluid mb-4" src="files/demoeducheck/agenda/<?= escape($feriado['imagen']); ?>"  alt="Card image cap">

            <p class="card-text" style="color: black;"><?= escape($feriado['descripcion']); ?></p>

            <figcaption class="figure-caption">
                                            <ul class="list-inline d-flex text-muted mb-0">
                                                <li class="list-inline-item text-truncate mr-auto"><?= escape($feriado['fecha_inicio']); ?>    al <?= escape($feriado['fecha_inicio']); ?> </li>
                                                

                                                    <li class="list-inline-item" >
                                                        <a  href='#' onclick='abrir_editar(<?= escape($feriado['id_agenda']); ?>)' style='color:white' >  <span><i class='icon-note'  style="background-color: orange;color:black;"></i></span></a>


                                                        <a href='#'   role='button' onclick='abrir_eliminar(<?= escape($feriado['id_agenda']); ?>)'><span class='icon-trash' style="background-color: red;color:black;" ></span></a>
                                                    </li>
                                            </ul>
                                        </figcaption>
            <p class="text-muted"></p>
            
        </div>
</div>








<?php else : ?>




<?php endif ?>

<?php endforeach ?>








                            </div>
                    </div>
                </div>
            </div>
            <div style="background-color: #d4edda;" class="card">
                <div class="card-header text-center" id="headingTwelve">
                    <h5 class="mb-0">
    <button class="btn btn-link collapsed " data-toggle="collapse" data-target="#collapseMayo" aria-expanded="false" aria-controls="collapseMayo">
          <span class="fas fa-angle-down mr-3"></span>MAYO 
                     </button>
                               </h5>
                </div>
                <div style="background-color: #e2e3e5;"  id="collapseMayo" class="collapse" aria-labelledby="headingTwelve" data-parent="#accordion4">
                    <div class="card-body">
<div class="row">

                        <?php foreach ($feriados as $nro => $feriado) : ?>


<?php $month_ini = date("m",strtotime($feriado['fecha_inicio'])); ?>
<?php $month_fin = date("m",strtotime($feriado['fecha_final'])); ?>

   

   <?php if ($month_ini== 5 || $month_fin== 5) : ?>


<div class="col-md-3">
        <div class="card-body <?= escape($feriado['color']); ?>" >

            <h5 class="card-title"><?= escape($feriado['titulo']); ?> (<?= escape($feriado['grupo']); ?>)</h5>
            <h6 class="card-subtitle text-muted">Asueto: <?= escape($feriado['tipo_agenda']); ?>                </h6>
            <img style="width:auto;height: 180px;" class="img-fluid mb-4" src="files/demoeducheck/agenda/<?= escape($feriado['imagen']); ?>"  alt="Card image cap">

            <p class="card-text" style="color: black;"><?= escape($feriado['descripcion']); ?></p>

            <figcaption class="figure-caption">
                                            <ul class="list-inline d-flex text-muted mb-0">
                                                <li class="list-inline-item text-truncate mr-auto"><?= escape($feriado['fecha_inicio']); ?>    al <?= escape($feriado['fecha_inicio']); ?> </li>
                                                

                                                    <li class="list-inline-item" >
                                                        <a  href='#' onclick='abrir_editar(<?= escape($feriado['id_agenda']); ?>)' style='color:white' >  <span><i class='icon-note'  style="background-color: orange;color:black;"></i></span></a>


                                                        <a href='#'   role='button' onclick='abrir_eliminar(<?= escape($feriado['id_agenda']); ?>)'><span class='icon-trash' style="background-color: red;color:black;" ></span></a>
                                                    </li>
                                            </ul>
                                        </figcaption>
            <p class="text-muted"></p>
            
        </div>
</div>








<?php else : ?>




<?php endif ?>

<?php endforeach ?>








                            </div>
                    </div>
                </div>
            </div>


            
            <div style="background-color: #d4edda;" class="card ">
                <div class="card-header text-center" id="headingEleven">
                    <h5 class="mb-0">
                       <button class="btn btn-link collapsed " data-toggle="collapse" data-target="#collapseJunio" aria-expanded="false" aria-controls="collapseJunio">
                         <span class="fas fa-angle-down mr-3"></span>JUNIO 
                     </button>       </h5>
                </div>
                <div style="background-color: #e2e3e5;"  id="collapseJunio" class="collapse" aria-labelledby="headingEleven" data-parent="#accordion4">
                    <div class="card-body bg-white">
<div class="row">

                        <?php foreach ($feriados as $nro => $feriado) : ?>


<?php $month_ini = date("m",strtotime($feriado['fecha_inicio'])); ?>
<?php $month_fin = date("m",strtotime($feriado['fecha_final'])); ?>

   

   <?php if ($month_ini== 6 || $month_fin== 6) : ?>


<div class="col-md-3">
        <div class="card-body <?= escape($feriado['color']); ?>" >

            <h5 class="card-title"><?= escape($feriado['titulo']); ?> (<?= escape($feriado['grupo']); ?>)</h5>
            <h6 class="card-subtitle text-muted">Asueto: <?= escape($feriado['tipo_agenda']); ?>                </h6>
            <img style="width:auto;height: 180px;" class="img-fluid mb-4" src="files/demoeducheck/agenda/<?= escape($feriado['imagen']); ?>"  alt="Card image cap">

            <p class="card-text" style="color: black;"><?= escape($feriado['descripcion']); ?></p>

            <figcaption class="figure-caption">
                                            <ul class="list-inline d-flex text-muted mb-0">
                                                <li class="list-inline-item text-truncate mr-auto"><?= escape($feriado['fecha_inicio']); ?>    al <?= escape($feriado['fecha_inicio']); ?> </li>
                                                

                                                    <li class="list-inline-item" >
                                                        <a  href='#' onclick='abrir_editar(<?= escape($feriado['id_agenda']); ?>)' style='color:white' >  <span><i class='icon-note'  style="background-color: orange;color:black;"></i></span></a>


                                                        <a href='#'   role='button' onclick='abrir_eliminar(<?= escape($feriado['id_agenda']); ?>)'><span class='icon-trash' style="background-color: red;color:black;" ></span></a>
                                                    </li>
                                            </ul>
                                        </figcaption>
            <p class="text-muted"></p>
            
        </div>
</div>








<?php else : ?>




<?php endif ?>

<?php endforeach ?>








                            </div>
                    </div>
                </div>
            </div>
            <div style="background-color: #d4edda;" class="card">
                <div class="card-header text-center" id="headingTwelve">
                    <h5 class="mb-0">
    <button class="btn btn-link collapsed " data-toggle="collapse" data-target="#collapseJulio" aria-expanded="false" aria-controls="collapseJulio">
          <span class="fas fa-angle-down mr-3"></span>JULIO 
                     </button>
                               </h5>
                </div>
                <div style="background-color: #e2e3e5;"  id="collapseJulio" class="collapse" aria-labelledby="headingTwelve" data-parent="#accordion4">
                    <div class="card-body">
<div class="row">

                        <?php foreach ($feriados as $nro => $feriado) : ?>


<?php $month_ini = date("m",strtotime($feriado['fecha_inicio'])); ?>
<?php $month_fin = date("m",strtotime($feriado['fecha_final'])); ?>

   

   <?php if ($month_ini== 7 || $month_fin== 7) : ?>


<div class="col-md-3">
        <div class="card-body <?= escape($feriado['color']); ?>" >

            <h5 class="card-title"><?= escape($feriado['titulo']); ?> (<?= escape($feriado['grupo']); ?>)</h5>
            <h6 class="card-subtitle text-muted">Asueto: <?= escape($feriado['tipo_agenda']); ?>                </h6>
            <img style="width:auto;height: 180px;" class="img-fluid mb-4" src="files/demoeducheck/agenda/<?= escape($feriado['imagen']); ?>"  alt="Card image cap">

            <p class="card-text" style="color: black;"><?= escape($feriado['descripcion']); ?></p>

            <figcaption class="figure-caption">
                                            <ul class="list-inline d-flex text-muted mb-0">
                                                <li class="list-inline-item text-truncate mr-auto"><?= escape($feriado['fecha_inicio']); ?>    al <?= escape($feriado['fecha_inicio']); ?> </li>
                                                

                                                    <li class="list-inline-item" >
                                                        <a  href='#' onclick='abrir_editar(<?= escape($feriado['id_agenda']); ?>)' style='color:white' >  <span><i class='icon-note'  style="background-color: orange;color:black;"></i></span></a>


                                                        <a href='#'   role='button' onclick='abrir_eliminar(<?= escape($feriado['id_agenda']); ?>)'><span class='icon-trash' style="background-color: red;color:black;" ></span></a>
                                                    </li>
                                            </ul>
                                        </figcaption>
            <p class="text-muted"></p>
            
        </div>
</div>








<?php else : ?>




<?php endif ?>

<?php endforeach ?>








                            </div>
                    </div>
                </div>
            </div>



            
            <div style="background-color: #d4edda;" class="card ">
                <div class="card-header text-center" id="headingEleven">
                    <h5 class="mb-0">
                       <button class="btn btn-link collapsed " data-toggle="collapse" data-target="#collapseAgosto" aria-expanded="false" aria-controls="collapseAgosto">
                         <span class="fas fa-angle-down mr-3"></span>AGOSTO 
                     </button>       </h5>
                </div>
                <div style="background-color: #e2e3e5;"  id="collapseAgosto" class="collapse" aria-labelledby="headingEleven" data-parent="#accordion4">
                    <div class="card-body bg-white">
<div class="row">

                        <?php foreach ($feriados as $nro => $feriado) : ?>


<?php $month_ini = date("m",strtotime($feriado['fecha_inicio'])); ?>
<?php $month_fin = date("m",strtotime($feriado['fecha_final'])); ?>

   

   <?php if ($month_ini== 8 || $month_fin== 8) : ?>


<div class="col-md-3">
        <div class="card-body <?= escape($feriado['color']); ?>" >

            <h5 class="card-title"><?= escape($feriado['titulo']); ?> (<?= escape($feriado['grupo']); ?>)</h5>
            <h6 class="card-subtitle text-muted">Asueto: <?= escape($feriado['tipo_agenda']); ?>                </h6>
            <img style="width:auto;height: 180px;" class="img-fluid mb-4" src="files/demoeducheck/agenda/<?= escape($feriado['imagen']); ?>"  alt="Card image cap">

            <p class="card-text" style="color: black;"><?= escape($feriado['descripcion']); ?></p>

            <figcaption class="figure-caption">
                                            <ul class="list-inline d-flex text-muted mb-0">
                                                <li class="list-inline-item text-truncate mr-auto"><?= escape($feriado['fecha_inicio']); ?>    al <?= escape($feriado['fecha_inicio']); ?> </li>
                                                

                                                    <li class="list-inline-item" >
                                                        <a  href='#' onclick='abrir_editar(<?= escape($feriado['id_agenda']); ?>)' style='color:white' >  <span><i class='icon-note'  style="background-color: orange;color:black;"></i></span></a>


                                                        <a href='#'   role='button' onclick='abrir_eliminar(<?= escape($feriado['id_agenda']); ?>)'><span class='icon-trash' style="background-color: red;color:black;" ></span></a>
                                                    </li>
                                            </ul>
                                        </figcaption>
            <p class="text-muted"></p>
            
        </div>
</div>








<?php else : ?>




<?php endif ?>

<?php endforeach ?>








                            </div>
                    </div>
                </div>
            </div>
            <div style="background-color: #d4edda;" class="card">
                <div class="card-header text-center" id="headingTwelve">
                    <h5 class="mb-0">
    <button class="btn btn-link collapsed " data-toggle="collapse" data-target="#collapseSeptiempre" aria-expanded="false" aria-controls="collapseSeptiempre">
          <span class="fas fa-angle-down mr-3"></span>SEPTIEMBRE 
                     </button>
                               </h5>
                </div>
                <div style="background-color: #e2e3e5;"  id="collapseSeptiempre" class="collapse" aria-labelledby="headingTwelve" data-parent="#accordion4">
                    <div class="card-body">
<div class="row">

                        <?php foreach ($feriados as $nro => $feriado) : ?>


<?php $month_ini = date("m",strtotime($feriado['fecha_inicio'])); ?>
<?php $month_fin = date("m",strtotime($feriado['fecha_final'])); ?>

   

   <?php if ($month_ini== 8 || $month_fin== 8) : ?>


<div class="col-md-3">
        <div class="card-body <?= escape($feriado['color']); ?>" >

            <h5 class="card-title"><?= escape($feriado['titulo']); ?> (<?= escape($feriado['grupo']); ?>)</h5>
            <h6 class="card-subtitle text-muted">Asueto: <?= escape($feriado['tipo_agenda']); ?>                </h6>
            <img style="width:auto;height: 180px;" class="img-fluid mb-4" src="files/demoeducheck/agenda/<?= escape($feriado['imagen']); ?>"  alt="Card image cap">

            <p class="card-text" style="color: black;"><?= escape($feriado['descripcion']); ?></p>

            <figcaption class="figure-caption">
                                            <ul class="list-inline d-flex text-muted mb-0">
                                                <li class="list-inline-item text-truncate mr-auto"><?= escape($feriado['fecha_inicio']); ?>    al <?= escape($feriado['fecha_inicio']); ?> </li>
                                                

                                                    <li class="list-inline-item" >
                                                        <a  href='#' onclick='abrir_editar(<?= escape($feriado['id_agenda']); ?>)' style='color:white' >  <span><i class='icon-note'  style="background-color: orange;color:black;"></i></span></a>


                                                        <a href='#'   role='button' onclick='abrir_eliminar(<?= escape($feriado['id_agenda']); ?>)'><span class='icon-trash' style="background-color: red;color:black;" ></span></a>
                                                    </li>
                                            </ul>
                                        </figcaption>
            <p class="text-muted"></p>
            
        </div>
</div>








<?php else : ?>




<?php endif ?>

<?php endforeach ?>








                            </div>
                    </div>
                </div>
            </div>


            
            <div style="background-color: #d4edda;" class="card ">
                <div class="card-header text-center" id="headingEleven">
                    <h5 class="mb-0">
                       <button class="btn btn-link collapsed " data-toggle="collapse" data-target="#collapseOctubre" aria-expanded="false" aria-controls="collapseOctubre">
                         <span class="fas fa-angle-down mr-3"></span>OCTUBRE 
                     </button>       </h5>
                </div>
                <div style="background-color: #e2e3e5;"  id="collapseOctubre" class="collapse" aria-labelledby="headingEleven" data-parent="#accordion4">
                    <div class="card-body bg-white">
<div class="row">

                        <?php foreach ($feriados as $nro => $feriado) : ?>


<?php $month_ini = date("m",strtotime($feriado['fecha_inicio'])); ?>
<?php $month_fin = date("m",strtotime($feriado['fecha_final'])); ?>

   

   <?php if ($month_ini== 10 || $month_fin== 10) : ?>


<div class="col-md-3">
        <div class="card-body <?= escape($feriado['color']); ?>" >

            <h5 class="card-title"><?= escape($feriado['titulo']); ?> (<?= escape($feriado['grupo']); ?>)</h5>
            <h6 class="card-subtitle text-muted">Asueto: <?= escape($feriado['tipo_agenda']); ?>                </h6>
            <img style="width:auto;height: 180px;" class="img-fluid mb-4" src="files/demoeducheck/agenda/<?= escape($feriado['imagen']); ?>"  alt="Card image cap">

            <p class="card-text" style="color: black;"><?= escape($feriado['descripcion']); ?></p>

            <figcaption class="figure-caption">
                                            <ul class="list-inline d-flex text-muted mb-0">
                                                <li class="list-inline-item text-truncate mr-auto"><?= escape($feriado['fecha_inicio']); ?>    al <?= escape($feriado['fecha_inicio']); ?> </li>
                                                

                                                    <li class="list-inline-item" >
                                                        <a  href='#' onclick='abrir_editar(<?= escape($feriado['id_agenda']); ?>)' style='color:white' >  <span><i class='icon-note'  style="background-color: orange;color:black;"></i></span></a>


                                                        <a href='#'   role='button' onclick='abrir_eliminar(<?= escape($feriado['id_agenda']); ?>)'><span class='icon-trash' style="background-color: red;color:black;" ></span></a>
                                                    </li>
                                            </ul>
                                        </figcaption>
            <p class="text-muted"></p>
            
        </div>
</div>








<?php else : ?>




<?php endif ?>

<?php endforeach ?>








                            </div>
                    </div>
                </div>
            </div>
            <div style="background-color: #d4edda;" class="card">
                <div class="card-header text-center" id="headingTwelve">
                    <h5 class="mb-0">
    <button class="btn btn-link collapsed " data-toggle="collapse" data-target="#collapseNoviembre" aria-expanded="false" aria-controls="collapseNoviembre">
          <span class="fas fa-angle-down mr-3"></span>NOVIEMBRE 
                     </button>
                               </h5>
                </div>
                <div style="background-color: #e2e3e5;"  id="collapseNoviembre" class="collapse" aria-labelledby="headingTwelve" data-parent="#accordion4">
                    <div class="card-body">
<div class="row">

                        <?php foreach ($feriados as $nro => $feriado) : ?>


<?php $month_ini = date("m",strtotime($feriado['fecha_inicio'])); ?>
<?php $month_fin = date("m",strtotime($feriado['fecha_final'])); ?>

   

   <?php if ($month_ini== 11 || $month_fin== 11) : ?>


<div class="col-md-3">
        <div class="card-body <?= escape($feriado['color']); ?>" >

            <h5 class="card-title"><?= escape($feriado['titulo']); ?> (<?= escape($feriado['grupo']); ?>)</h5>
            <h6 class="card-subtitle text-muted">Asueto: <?= escape($feriado['tipo_agenda']); ?>                </h6>
            <img style="width:auto;height: 180px;" class="img-fluid mb-4" src="files/demoeducheck/agenda/<?= escape($feriado['imagen']); ?>"  alt="Card image cap">

            <p class="card-text" style="color: black;"><?= escape($feriado['descripcion']); ?></p>

            <figcaption class="figure-caption">
                                            <ul class="list-inline d-flex text-muted mb-0">
                                                <li class="list-inline-item text-truncate mr-auto"><?= escape($feriado['fecha_inicio']); ?>    al <?= escape($feriado['fecha_inicio']); ?> </li>
                                                

                                                    <li class="list-inline-item" >
                                                        <a  href='#' onclick='abrir_editar(<?= escape($feriado['id_agenda']); ?>)' style='color:white' >  <span><i class='icon-note'  style="background-color: orange;color:black;"></i></span></a>


                                                        <a href='#'   role='button' onclick='abrir_eliminar(<?= escape($feriado['id_agenda']); ?>)'><span class='icon-trash' style="background-color: red;color:black;" ></span></a>
                                                    </li>
                                            </ul>
                                        </figcaption>
            <p class="text-muted"></p>
            
        </div>
</div>








<?php else : ?>




<?php endif ?>

<?php endforeach ?>








                            </div>
                    </div>
                </div>
            </div>

            <div style="background-color: #d4edda;" class="card">
                <div class="card-header text-center" id="headingTwelve">
                    <h5 class="mb-0">
    <button class="btn btn-link collapsed " data-toggle="collapse" data-target="#collapseDiciembre" aria-expanded="false" aria-controls="collapseDiciembre">
          <span class="fas fa-angle-down mr-3"></span>DICIEMBRE 
                     </button>
                               </h5>
                </div>
                <div style="background-color: #e2e3e5;"  id="collapseDiciembre" class="collapse" aria-labelledby="headingTwelve" data-parent="#accordion4">
                    <div class="card-body">
<div class="row">

                        <?php foreach ($feriados as $nro => $feriado) : ?>


<?php $month_ini = date("m",strtotime($feriado['fecha_inicio'])); ?>
<?php $month_fin = date("m",strtotime($feriado['fecha_final'])); ?>

   

   <?php if ($month_ini== 12 || $month_fin== 12) : ?>


<div class="col-md-3">
        <div class="card-body <?= escape($feriado['color']); ?>" >

            <h5 class="card-title"><?= escape($feriado['titulo']); ?> (<?= escape($feriado['grupo']); ?>)</h5>
            <h6 class="card-subtitle text-muted">Asueto: <?= escape($feriado['tipo_agenda']); ?>                </h6>
            <img style="width:auto;height: 180px;" class="img-fluid mb-4" src="files/demoeducheck/agenda/<?= escape($feriado['imagen']); ?>"  alt="Card image cap">

            <p class="card-text" style="color: black;"><?= escape($feriado['descripcion']); ?></p>

            <figcaption class="figure-caption">
                                            <ul class="list-inline d-flex text-muted mb-0">
                                                <li class="list-inline-item text-truncate mr-auto"><?= escape($feriado['fecha_inicio']); ?>    al <?= escape($feriado['fecha_inicio']); ?> </li>
                                                

                                                    <li class="list-inline-item" >
                                                        <a  href='#' onclick='abrir_editar(<?= escape($feriado['id_agenda']); ?>)' style='color:white' >  <span><i class='icon-note'  style="background-color: orange;color:black;"></i></span></a>


                                                        <a href='#'   role='button' onclick='abrir_eliminar(<?= escape($feriado['id_agenda']); ?>)'><span class='icon-trash' style="background-color: red;color:black;" ></span></a>
                                                    </li>
                                            </ul>
                                        </figcaption>
            <p class="text-muted"></p>
            
        </div>
</div>








<?php else : ?>




<?php endif ?>

<?php endforeach ?>








                            </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



</div>                
                
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
        <p>¿Esta seguro de eliminar el evento <span id="texto_contrato"></span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn_eliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>


<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<!-- <script src="<?= js; ?>/jquery.dataFilters.min.js"></script> -->
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/educheck.js"></script>

<?php require_once show_template('footer-design'); ?>
<?php 

    if($permiso_editar){
        require_once ("editar.php");
    }   
    if($permiso_ver){
        require_once ("ver.php");
    }
    require_once ("modal-ordenar-areas.php");
?>
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
        bootbox.confirm('¿Está seguro que desea eliminar al Agenda?', function (result) {
            if (result) {
                $.request(href, csrf);
            }
        });
    });
    <?php endif ?>
     
    <?php if ($agenda) : ?>
    // $('#nivel_academico').DataFilter({
    //  filter: true,
    //  name: 'niveles',
    //  reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
    // });
    <?php endif ?>
    //carga toda la lista de grupo proyecto con DataTable
});




<?php if ($permiso_editar) : ?>
function abrir_editar2(contenido){
    $("#form_contrato")[0].reset();
    $("#btn_nuevo").hide();
    $("#btn_editar").show();
    $("#archivo_documento").attr('required', true);
    $('input[name="genero"]').removeAttr('checked');
    $("#modal_contrato").modal("show");
    var d = contenido.split("*");
    $("#id_agenda").val(d[0]);
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
    
    var nro=contenido;
    $.ajax({
        url: '?/s-agenda-institucional-feriados/procesos',
        type: 'POST',
        data:{
            'accion': 'recuperar_datos',
            'id_agenda':nro 
            },
        dataType: 'JSON',
        success: function(resp){    


    $("#form_contrato")[0].reset();
    $("#btn_nuevo").hide();
    $("#btn_editar").show();
    $("#archivo_documento").attr('required', false);
    $("#modal_contrato").modal("show");
    $("#id_agenda").val(resp["id_agenda"]);
    $("#titulo").val(resp["titulo"]);
    $("#tipo_agenda").val(resp["tipo_agenda"]);
    $("#descripcion").val(resp["descripcion"]);
    $("#grupo").val(resp["grupo"]);
    $("#prioridad").val(resp["color"]);
    $("#fecha_inicio").val(resp["fecha_inicio"]);
    $("#fecha_final").val(resp["fecha_final"]);
    $("#archivo_documento_nombre").val(resp["imagen"]);
    $('#archivo_documento').attr('src', "files/demoeducheck/agenda/" + resp["archivo_documento"]);
    

  

            
    

        }
    });
}
    
     
    

<?php endif ?>

function abrir_ordenar_agenda(){
    $("#modal_ordenar_contrato").modal("show");
}

<?php if ($permiso_crear) : ?>
function abrir_crear(){
    $("#modal_contrato").modal("show");
    $("#form_contrato")[0].reset();
    $("#btn_editar").hide();
    $("#fotedit").hide(); 
    $("#archivo_documento").attr('required', true);
    $("#btn_nuevo").show();
    
    
}
<?php endif ?>



//} 
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
   var nro=contenido;
    $("#area_eliminar").val();
    $("#id_agenda").val();

$("#btn_eliminar").on('click', function(){
    var nro=contenido;
    $.ajax({
        url: '?/s-agenda-institucional-feriados/eliminar',
        type:'POST',
        data: {'id_agenda':nro},
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

}
<?php endif ?>
</script>