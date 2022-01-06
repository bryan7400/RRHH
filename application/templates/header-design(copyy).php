<?php



// Obtiene el menu de herramientas

$_herramientas = json_decode(@file_get_contents(storages . '/herramientas.json'), true);



// Obtiene los menus

$_menus = $db->select('m.*, p.archivos')->from('sys_permisos p')->join('sys_menus m', 'p.menu_id = m.id_menu')->where('p.rol_id', $_SESSION[user]['rol_id'])->where('m.id_menu != ', 0)->order_by('m.orden', 'asc')->fetch();



// Construye la barra de menus

$_menus = construir_menu_horizontal($_menus); 



?>

<!DOCTYPE html> 

<html lang="es">  

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	

	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

	<meta name="mobile-web-app-capable" content="yes"> 

	<title>Maranata</title> 
    
    <link rel="icon" type="image/png" href="<?= project; ?>/logotipo.png">
    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/bootstrap-select/css/bootstrap-select.css">

    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">

    <!--link rel="stylesheet" href="assets/themes/concept/assets/vendor/bootstrap/css/bootstrap.min.css.old"-->

    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/bootstrap/css/educheck.css">

    <link rel="stylesheet" href="assets/css/educheck.css">



    <!-- dataTable CSS -->

    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/datatables/css/buttons.bootstrap4.css">

    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/datatables/css/dataTables.bootstrap4.css">

    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/datatables/css/fixedHeader.bootstrap4.css">

    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/datatables/css/select.bootstrap4.css">

    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/jquery-gritter/css/jquery.gritter.css">

    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/alertify/css/alertify.min.css">



    <!--graficos estadisticos-->

    <link href="assets/themes/concept/assets/vendor/fonts/circular-std/style.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/themes/concept/assets/libs/css/style.css">

    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">

    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/charts/chartist-bundle/chartist.css">

    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/charts/morris-bundle/morris.css">

    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css">

    <!-- <link rel="stylesheet" href="assets/themes/concept/assets/vendor/charts/c3charts/c3.css"> -->

    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/fonts/flag-icon-css/flag-icon.min.css">

    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/fonts/simple-line-icons/css/simple-line-icons.css">

    <link rel="stylesheet" href='assets/themes/concept/assets/vendor/full-calendar/css/fullcalendar.css' rel='stylesheet' />

    <link rel="stylesheet" href='assets/themes/concept/assets/vendor/full-calendar/css/fullcalendar.print.css' rel='stylesheet' media='print' />





    <title>Checkcode</title>

    <!-- jquery 3.3.1 -->

    <script src="<?= js; ?>/bootbox.min.js"></script>

    <script src="assets/themes/concept/assets/vendor/jquery/jquery-3.3.1.min.js"></script>

    <script src="assets/themes/concept/assets/vendor/jquery/jquery.dataTables.min.js"></script>

    <!--<script src="assets/themes/concept/assets/vendor/jquery/jquery.dataFilters.min.js"></script>-->

    <script src="assets/themes/concept/assets/vendor/jquery/jquery.validate.js"></script>

    <script src="assets/themes/concept/assets/vendor/jquery-gritter/js/jquery.gritter.min.js"></script>

    <script src="assets/themes/concept/assets/vendor/alertify/js/alertify.min.js"></script>

    <!--script src="assets/themes/concept/assets/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

    <script src="assets/themes/concept/assets/vendor/bootstrap-datetimepicker/js/moment.min.js"></script>

    <script src="assets/themes/concept/assets/vendor/bootstrap-datetimepicker/js/moment.es.js"></script-->

    <!--js principal de educheck-->

    <script src="assets/themes/concept/assets/vendor/educheck/js/educheck.js"></script>

    <!-- dataTable js -->

    <!--script src="assets/themes/concept/assets/vendor/datatables/js/buttons.bootstrap4.min.js"></script-->

<!--     <script src="assets/themes/concept/assets/vendor/datatables/js/data-table.js"></script>

    <script src="assets/themes/concept/assets/vendor/datatables/js/dataTables.bootstrap4.min.js"></script> -->

    <!-- bootstap bundle js -->

    <script src="assets/themes/concept/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>

    <!-- slimscroll js -->

    <script src="assets/themes/concept/assets/vendor/slimscroll/jquery.slimscroll.js"></script>

    <!-- main js -->

    <script src="assets/themes/concept/assets/libs/js/main-js.js"></script>

    <!-- dataTable js -->

    <!-- <script src="assets/themes/concept/assets/vendor/dataTables/js/data-table.js"></script> -->

    <!--script src="assets/themes/concept/assets/vendor/dataTables/js/buttons.bootstrap4.min.js"></script-->

    <!-- <script src="assets/themes/concept/assets/vendor/dataTables/js/dataTables.bootstrap4.min.js"></script> -->



    <script src="assets/themes/concept/assets/vendor/popover/matrix.popover.js"></script>

    <!-- chart chartist js -->

    <!-- <script src="assets/themes/concept/assets/vendor/charts/chartist-bundle/chartist.min.js"></script> -->

    <!-- sparkline js -->

    <!-- <script src="assets/themes/concept/assets/vendor/charts/sparkline/jquery.sparkline.js"></script> -->

    <!-- morris js -->

<!--     <script src="assets/themes/concept/assets/vendor/charts/morris-bundle/raphael.min.js"></script>

    <script src="assets/themes/concept/assets/vendor/charts/morris-bundle/morris.js"></script> -->

    <!-- chart c3 js -->

<!--     <script src="assets/themes/concept/assets/vendor/charts/c3charts/c3.min.js"></script>

    <script src="assets/themes/concept/assets/vendor/charts/c3charts/d3-5.4.0.min.js"></script>

    <script src="assets/themes/concept/assets/vendor/charts/c3charts/C3chartjs.js"></script>

    <script src="assets/themes/concept/assets/libs/js/dashboard-ecommerce.js"></script> -->



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

                <!-- <a class="navbar-brand" href="index.html">EDU CHECK</a> -->
                <a href="<?= index_private; ?>" class="navbar-brand">
                        <img src="<?= imgs . '/logo-texto.png'; ?>" height="30" style="margin-top: -5px;">
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

                    <span class="navbar-toggler-icon"></span>

                </button>

                <div class="collapse navbar-collapse " id="navbarSupportedContent">

                    <ul class="navbar-nav ml-auto navbar-right-top">

                        <li class="nav-item">

                            <div id="custom-search" class="top-search-bar">

                                <input class="form-control" type="text" placeholder="Buscar...">

                            </div>

                        </li>

                        <li class="nav-item dropdown notification">

                            <a class="nav-link nav-icons" href="#" id="navbarDropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-fw fa-envelope"></i> <span class="aca ponemos la clase indicator"></span></a>

                            <ul class="dropdown-menu dropdown-menu-right notification-dropdown">

                                <li>

                                    <div class="notification-title"> Mensajes</div>

                                    <div class="notification-list">

                                        <div class="list-group">

                                            <a href="#" class="list-group-item list-group-item-action active">

                                                <div class="notification-info">

                                                    <div class="notification-list-user-img"><img src="assets/imgs/avatar-2.jpg" alt="" class="user-avatar-md rounded-circle"></div>

                                                    <div class="notification-list-user-block"><span class="notification-list-user-name">Jeremy Rakestraw</span>accepted your invitation to join the team.

                                                        <div class="notification-date">2 min ago</div>

                                                    </div>

                                                </div>

                                            </a>

                                            <a href="#" class="list-group-item list-group-item-action">

                                                <div class="notification-info">

                                                    <div class="notification-list-user-img"><img src="assets/imgs/avatar-3.jpg" alt="" class="user-avatar-md rounded-circle"></div>

                                                    <div class="notification-list-user-block"><span class="notification-list-user-name">John Abraham </span>is now following you

                                                        <div class="notification-date">2 days ago</div>

                                                    </div>

                                                </div>

                                            </a>

                                            <a href="#" class="list-group-item list-group-item-action">

                                                <div class="notification-info">

                                                    <div class="notification-list-user-img"><img src="assets/imgs/avatar-4.jpg" alt="" class="user-avatar-md rounded-circle"></div>

                                                    <div class="notification-list-user-block"><span class="notification-list-user-name">Monaan Pechi</span> is watching your main repository

                                                        <div class="notification-date">2 min ago</div>

                                                    </div>

                                                </div>

                                            </a>

                                            <a href="#" class="list-group-item list-group-item-action">

                                                <div class="notification-info">

                                                    <div class="notification-list-user-img"><img src="assets/imgs/avatar-5.jpg" alt="" class="user-avatar-md rounded-circle"></div>

                                                    <div class="notification-list-user-block"><span class="notification-list-user-name">Jessica Caruso</span>accepted your invitation to join the team.

                                                        <div class="notification-date">2 min ago</div>

                                                    </div>

                                                </div>

                                            </a>

                                        </div>

                                    </div>

                                </li>

                                <li>

                                    <div class="list-footer"> <a href="#">Ver Todos los mensajes</a></div>

                                </li>

                            </ul>

                        </li>



                        <!-- -->

                        <li class="nav-item dropdown notification">

                          <a class="nav-link nav-icons" href="#" id="navbarDropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-fw fa-bell"></i> <span class="indicator"></span></a>

                          <ul class="dropdown-menu dropdown-menu-right notification-dropdown">

                              <li>

                                  <div class="notification-title">Notificaciones</div>

                                  <div class="notification-list">

                                      <div class="list-group">

                                          <a href="#" class="list-group-item list-group-item-action active">

                                              <div class="notification-info">

                                                  <div class="notification-list-user-img"><img src="assets/imgs/avatar-2.jpg" alt="" class="user-avatar-md rounded-circle"></div>

                                                  <div class="notification-list-user-block"><span class="notification-list-user-name">Jeremy Rakestraw</span>accepted your invitation to join the team.

                                                      <div class="notification-date">2 min ago</div>

                                                  </div>

                                              </div>

                                          </a>

                                         

                                      </div>

                                  </div>

                              </li>

                              <li>

                                <div class="list-footer"> <a href="#">Ver todas las Notificaciones</a></div>

                              </li>

                          </ul>

                      </li>

                        <!--- Usuario logueado -->

                        <li class="nav-item dropdown connection">

                            <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?= ($_user['avatar'] == '') ? imgs . '/avatar.jpg' : files . '/profiles/' . $_user['avatar']; ?>" alt="" class="user-avatar-md rounded-circle"><i> <?= escape($_user['username']); ?></i></a>

                            <ul class="dropdown-menu dropdown-menu-right connection-dropdown">



                                <li class="connection-list">

                                    <div class="row">

                                        <div class="nav-user-info col-sm-12 col-12">

                                            <div align="center">

                                                <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?= ($_user['avatar'] == '') ? imgs . '/avatar.jpg' : files . '/profiles/' . $_user['avatar']; ?>" width="100" height="100" alt="foto" style="width:100px;moz-border-radius:30%;khtml-border-radius:30%;o-border-radius:30%;webkit-border-radius:30%;ms-border-radius:50%;border-radius:50%;"> </a>

                                                <h5 class="mb-0 text-white nav-user-name"><?= escape($_user['username']); ?></h5>

                                                <span class="status"></span><span class="">En linea</span>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <a class="dropdown-item" href="#"></a>

                                        <a class="dropdown-item" href="?/perfil/mostrar"><i class="fas fa-user mr-2"></i>Mi Perfil</a>

                                        <a class="dropdown-item" href="#"><i class="fas fa-cog mr-2"></i>Configuraciones</a>

                                        <a class="dropdown-item" href="?/<?= site; ?>/salir"><i class="fas fa-power-off mr-2"></i>Cerrar sesion</a>

                                    </div>

                                </li>                                     

                            </ul>

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

        <div class="nav-left-sidebar sidebar-dark">

            <div class="menu-list">

                <nav class="navbar navbar-expand-lg navbar-light">

                    <a class="d-xl-none d-lg-none" href="#">Menú</a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">

                        <span class="navbar-toggler-icon"></span>

                    </button>

                    <div class="collapse navbar-collapse" id="navbarNav">

                        <ul class="navbar-nav flex-column">

                            <li class="nav-divider">

                                Menú
                                <span class="badge badge-primary"> GESTIÓN <?= $_gestion['gestion']; ?> </span>

                            </li>

                            <li class="nav-item">

                            	<?php if (environment == 'development' && $_herramientas && $_user['rol_id'] == 1 && $_user['visible'] == 'n') : ?>

                                <a class="nav-link active" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-0" aria-controls="submenu-1">

                                <i class="fa fa-fw fa-user-circle"></i>Desarrollo 

                                <span class="badge badge-success">6</span>

                                </a>

                                <div id="submenu-0" class="collapse submenu" style="">

                                    <ul class="nav flex-column">

                                    	<?php foreach ($_herramientas as $_herramienta) : ?>

                                        <li class="nav-item">

                                            <a class="nav-link" href="<?= $_herramienta['ruta']; ?>"><?= $_herramienta['menu']; ?></a>

                                        </li>

                                        <?php endforeach ?> 

                                    </ul>

                                </div>

                            </li>

                            <?php endif ?> 

							<?= $_menus; ?> 

                        </ul>

                    </div>

                </nav>

            </div>

        </div>







        <!-- ============================================================== -->

        <!-- end left sidebar -->

        <!-- ============================================================== -->

        <!-- ============================================================== -->

        <!-- wrapper  -->

        <!-- ============================================================== -->

        <div class="dashboard-wrapper">

            <div class="dashboard-ecommerce">

                <div class="container-fluid dashboard-content ">