<?php

$cursos = $db->query('SELECT ap.id_aula_paralelo, IFNULL(COUNT(i.aula_paralelo_id),0) AS contador , ap.capacidad, IFNULL(ap.capacidad-COUNT(i.aula_paralelo_id),ap.capacidad) vacantes,
p.nombre_paralelo, a.nombre_aula, na.nombre_nivel
FROM ins_aula_paralelo ap 
INNER JOIN  ins_paralelo p ON p.id_paralelo=ap.paralelo_id
INNER JOIN  ins_aula a ON a.id_aula=ap.aula_id
INNER JOIN  ins_nivel_academico na ON a.nivel_academico_id=na.id_nivel_academico
INNER JOIN  ins_inscripcion i ON ap.id_aula_paralelo=i.aula_paralelo_id
GROUP BY i.aula_paralelo_id
ORDER BY na.id_nivel_academico, a.id_aula, p.nombre_paralelo ASC')->fetch();
//var_dump($estudiantes);exit();
?>
<!doctype html>
<html lang="en"> 
 
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="assets/themes/concept/assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/themes/concept/assets/libs/css/style.css">
    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
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
                <img src="assets/imgs/logo-color.png" alt="Image" class="img-fluid" width="30px"> <font color="#2e8441">PRIVADA DEL SUR</font>
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
        <!-- left sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- end left sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="container-fluid dashboard-content">
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content">
                    <!-- ============================================================== -->
                    <!-- pageheader  -->
                    <!-- ============================================================== -->
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header">
                                <h3 class="pageheader-title"><a>Bienvenido a nuestro sistema...!!! Seleccione su curso para accesder a sus actividades.</a> </h3>
                                <!-- <div class="page-breadcrumb">
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end pageheader  -->
                    <!-- ============================================================== -->
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="row">
                            <?php foreach ($cursos as $key => $value):?>
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
                                    <div class="product-thumbnail">
                                        <div class="product-img-head">
                                            <div class="product-img">
                                                <img src="assets/images/eco-product-img-1.png" alt="" class="img-fluid"></div>
                                            <div class="ribbons"></div>
                                            <div class="ribbons bg-success"></div>
                                            <div class="ribbons-text">Nuevo</div>
                                            <div class=""><a href="#" class="product-wishlist-btn"><i class="fas fa-users"></i></a></div>
                                        </div>
                                        <div class="product-content">
                                            <div class="product-content-head">
                                                <h3 class="product-title"><font size="1.5px"><?= $value['nombre_nivel']; ?></font></h3>
                                                <div class="product-rating d-inline-block">
                                                    <i class="fa fa-fw fa-star"></i>
                                                    <i class="fa fa-fw fa-star"></i>
                                                    <i class="fa fa-fw fa-star"></i>
                                                    <i class="fa fa-fw fa-star"></i>
                                                    <i class="fa fa-fw fa-star"></i>
                                                </div>
                                                <div class="product-price"><?= $value['nombre_aula']; ?> <?= $value['nombre_paralelo']; ?></div>
                                            </div>
                                            <div class="product-btn">
                                                <a href="?/sitio/curso-actividad/<?= $value['id_aula_paralelo']; ?>" class="btn btn-danger">Ver listado de actividades</a>
                                                <!-- <a href="#" class="btn btn-outline-light">Details</a> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                                <!-- <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination">
                                            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item active"><a class="page-link " href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                        </ul>
                                    </nav>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- footer -->
                <!-- ============================================================== -->
<!--                 <div class="footer">
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
</body>
 
</html>