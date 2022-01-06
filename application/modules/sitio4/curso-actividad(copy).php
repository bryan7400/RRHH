<?php

 // Obtiene los parametros
$id_aula_paralelo = (isset($_params[0])) ? $_params[0] : 0;

$actividades = $db->query("SELECT *
FROM tem_asesor_curso_actividad acd
INNER JOIN pro_asignacion_asesor aa ON acd.asignacion_asesor_id = aa.id_asignacion_asesor
WHERE aa.aula_paralelo_id=21
ORDER BY acd.fecha_presentacion_actividad ASC")->fetch();
//var_dump($estudiantes);exit();

$asesor = $db->query("SELECT *
FROM pro_asignacion_asesor aa
INNER JOIN per_asignaciones pa ON aa.asignacion_id = pa.id_asignacion
INNER JOIN sys_persona sp ON pa.persona_id = sp.id_persona
INNER JOIN ins_aula_paralelo ap ON aa.aula_paralelo_id = ap.id_aula_paralelo
INNER JOIN ins_paralelo p ON p.id_paralelo=ap.paralelo_id
INNER JOIN ins_aula a ON a.id_aula=ap.aula_id
INNER JOIN ins_turno t ON ap.turno_id = t.id_turno
INNER JOIN ins_nivel_academico na ON a.nivel_academico_id=na.id_nivel_academico
WHERE aa.tipo_asignacion = 'TITULAR'
AND aa.aula_paralelo_id = 21")->fetch_first();

?>
<!doctype html>
<html lang="en"> 
 
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/png" href="<?= project; ?>/logo.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="assets/themes/concept/assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/themes/concept/assets/libs/css/style.css">
    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <!--datatables-->
    <link rel="stylesheet" href="<?= css; ?>/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/datatables/css/dataTables.bootstrap4.css"> 
    <link rel="stylesheet" href="<?= css; ?>/educheck.css"> 

    <title>Educheck</title>
</head> 

<body>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper">
        <!-- ============================================================== -->
        <!-- navbar -->
        <!-- ============================================================== -->
        <div class="dashboard-header">
            <nav class="navbar navbar-expand-lg bg-white fixed-top">
                <a class="navbar-brand" href="index.html">educheck</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse " id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-right-top">
                        <li class="nav-item">
                        </li>
                        <li class="nav-item dropdown notification">
                        </li>
                        <li class="nav-item dropdown connection">
                            
                        </li>
                        <li class="nav-item dropdown nav-user">
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- ============================================================== -->
        <!-- end navbar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="container-fluid dashboard-content">
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content">

                    
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
                        <div class="card">
                                <div class="card-body">
                                    <div class="user-avatar text-center d-block">
                                        <img src="assets/images/avatar-1.jpg" alt="User Avatar" class="rounded-circle user-avatar-xxl">
                                    </div>
                                    <div class="text-center">
                                        <h2 class="font-22 mb-0"><?= $asesor['nombres']?> <?= $asesor['primer_apellido']?> <?= $asesor['segundo_apellido']?></h2>
                                        <p>Asesor Titular de Curso</p>
                                    </div>
                                </div>
                                <div class="card-body border-top">
                                    <h3 class="font-16"><?= $asesor['nombre_nivel']?></h3>
                                    <h1 class="mb-0"><?= $asesor['nombre_aula']?> <?= $asesor['nombre_paralelo']?></h1>
                                    <div class="rating-star">
                                        <i class="fa fa-fw fa-star"></i>
                                        <i class="fa fa-fw fa-star"></i>
                                        <i class="fa fa-fw fa-star"></i>
                                        <i class="fa fa-fw fa-star"></i>
                                        <i class="fa fa-fw fa-star"></i>
                                        <p class="d-inline-block text-dark">Turno: <?= $asesor['nombre_turno']?> </p>
                                    </div>
                                </div>
                                <div class="card-body border-top">
                                    <h3 class="font-16">Information de Acional</h3>
                                    <div class="">
                                        <ul class="list-unstyled mb-0">
                                        <li class="mb-2"><i class="fas fa-fw fa-envelope mr-2"></i>michaelchristy@gmail.com</li>
                                        <li class="mb-0"><i class="fas fa-fw fa-phone mr-2"></i>(900) 123 4567</li>
                                    </ul>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-12">
                        <div class="card">
            
            <div class="card-body">
                            <div class="row">
                            <div class="table-responsive">
                                <?php if ($actividades) : ?>
                                <table id="table" class="table table-bordered table-condensed table-striped table-hover">
                                    <thead>
                                        <tr class="active">
                                            <th class="text-nowrap">#</th>
                                            <th class="text-nowrap">Fecha de presentación</th>
                                            <th class="text-nowrap">Nombre actividad</th>
                                            <th class="text-nowrap">Archivo</th>
                                            <th class="text-nowrap">URL</th>
                                            <th class="text-nowrap">Descripción actividad</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr class="active">
                                            <th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
                                            <th class="text-nowrap text-middle" data-datafilter-filter="false">Fecha de presentación</th>
                                            <th class="text-nowrap text-middle">Nombre actividad</th>
                                            <th class="text-nowrap text-middle">Archivo</th>
                                            <th class="text-nowrap text-middle">URL</th>
                                            <th class="text-nowrap text-middle">Descripción actividad</th>
                                        </tr>
                                    </tfoot> 
                                    <tbody>
                                        <?php foreach ($actividades as $nro => $value) : ?>
                                        <tr>
                                            <th class="text-nowrap text-middle"><?= $nro + 1; ?></th>
                                            <td class="text-nowrap text-middle"><?= $value['fecha_presentacion_actividad']; ?></td>
                                            <td class="text-nowrap text-middle"><?= $value['nombre_actividad']; ?></td>
                                            <td class="text-nowrap text-middle"><a href="files/comunicados/<?= $value['archivo']; ?>"><span>Descargar</span></a></td>
                                            <td class="text-nowrap text-middle"><a href="<?= $value['url_actividad']; ?>" target="_blank"><?= $value['url_actividad']; ?></a></td>
                                            <td class="text-nowrap text-middle"><?= $value['descripcion_actividad']; ?></td>
                                        </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                                </div>
                                <?php else : ?>
                                <div class="alert alert-info">
                                    <strong>Atención!</strong>
                                    <ul>
                                        <li>No existen actividades registrados en la base de datos.</li>
                                        <li>Debe consultar con su Asesor de  <kbd>curso</kbd>.</li>
                                    </ul>
                                </div>
                                <?php endif ?>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <!-- ============================================================== -->
                <!-- footer -->
                <!-- ============================================================== -->
                <!-- <div class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                Copyright © 2018 Concept. All rights reserved. Dashboard by <a href="https://colorlib.com/wp/">Colorlib</a>.
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="text-md-right footer-links d-none d-sm-block">
                                    <a href="javascript: void(0);">About</a>
                                    <a href="javascript: void(0);">Support</a>
                                    <a href="javascript: void(0);">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                <!-- ============================================================== -->
                <!-- end footer -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- end wrapper  -->
            <!-- ============================================================== -->
        </div>
    </div>
        <!-- ============================================================== -->
        <!-- end main wrapper  -->
        <!-- ============================================================== -->
        <!-- Optional JavaScript -->
        <!-- jquery 3.3.1 -->
        <script src="assets/themes/concept/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
        <!-- bootstap bundle js -->
        <script src="assets/themes/concept/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
        <!-- slimscroll js -->
        <script src="assets/themes/concept/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
        <!-- main js -->
        <script src="assets/themes/concept/assets/libs/js/main-js.js"></script>

        <script src="assets/themes/concept/assets/vendor/jquery/jquery.dataTables.min.js"></script>
        <script src="assets/themes/concept/assets/vendor/educheck/js/educheck.js"></script>
        <script src="assets/themes/concept/assets/vendor/datatables/js/data-table.js"></script>
        <script src="assets/themes/concept/assets/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>

        <script>
        $(function () {
            <?php if ($actividades) : ?>
            var dataTable = $('#table').DataTable({
            language: dataTableTraduccion,
            searching: true,
            paging:true,
            "lengthChange": true, 
            "responsive": true
            });
            <?php endif ?>
        });
        </script>

</body>
 
</html>