<?php
$rol_id = $_user['rol_id'];
$id_persona = $_user['persona_id'];
$id_gestion = $_gestion['id_gestion'];

$fecha_actual = date("Y-m-d");
$fecha_menos_5_dias = date("Y-m-d", strtotime($fecha_actual . "- 5 days"));

$fecha_maniana = date("Y-m-d", strtotime($fecha_actual . "+ 1 days"));

// echo ($fecha_menos_5_dias." = ".$fecha_actual);
// exit();


//asistencia de estudiantes
/********************************************************************** */
$sql_asistencia = "SELECT *
                    FROM ins_asistencia_estudiante_general AS iaeg
                    WHERE iaeg.gestion_id = $id_gestion";
// echo $sql_asistencia;

$res_asistencia = $db->query($sql_asistencia)->fetch();

// $contamos cuantos asistieron
$contador_asitencia = 0;
$contador_faltas = 0;

$sql_estudiantes_actuales = "SELECT ie.id_estudiante, ia.nombre_aula, ip.nombre_paralelo, ina.nombre_nivel, ina.descripcion,p.genero, ii.aula_paralelo_id
                                FROM ins_inscripcion AS ii
                                INNER JOIN ins_aula_paralelo AS iap ON iap.id_aula_paralelo = ii.aula_paralelo_id
                                INNER JOIN ins_aula AS ia ON ia.id_aula = iap.aula_id
                                INNER JOIN ins_nivel_academico AS ina ON ina.id_nivel_academico = ia.nivel_academico_id
                                INNER JOIN ins_paralelo AS ip ON ip.id_paralelo = iap.paralelo_id
                                INNER JOIN ins_estudiante AS ie ON ie.id_estudiante = ii.estudiante_id
                                INNER JOIN sys_persona AS p ON p.id_persona = ie.persona_id
                                WHERE ii.estado = 'A' AND ii.estado_inscripcion = 'INSCRITO' AND ii.gestion_id = $id_gestion AND iap.estado = 'A'
                             ";
$sql_estudiantes_actuales .= " ORDER BY p.primer_apellido ASC, p.segundo_apellido ASC, p.nombres ASC ";

$res_estudiantes_actuales = $db->query($sql_estudiantes_actuales)->fetch();
$efectivo_estudiantes = count($res_estudiantes_actuales);

foreach ($res_asistencia as  $value) {
    $jsAsistencia = (array) json_decode($value['json_asistencia']);

    if (isset($jsAsistencia[$fecha_actual])) {
        $contador_asitencia++;
    }
    // echo "<pre>";
    // var_dump($jsAsistencia['2021-04-21']);
    // echo "</pre>";
}

$contador_faltas = $efectivo_estudiantes - $contador_asitencia;





//visitas de roles los ultimos 5 dias

/********************************************************************** */
$sql_visita_sp = "SELECT sr.rol, COUNT(su.rol_id)AS total
                        FROM sys_procesos AS sp
                        INNER JOIN sys_users AS su ON su.id_user = sp.usuario_id
                        INNER JOIN sys_roles AS sr ON sr.id_rol = su.rol_id 
                        WHERE sp.direccion LIKE '%autenticar%' AND sp.fecha_proceso >= '$fecha_menos_5_dias ' AND sp.fecha_proceso <= '$fecha_actual'
                        GROUP BY sr.id_rol";
$res_visita_sp = $db->query($sql_visita_sp)->fetch();


/********************************************************************** */
$sql_visita_docente = "SELECT sr.rol, CONCAT(sper.primer_apellido,' ', sper.segundo_apellido,' ', sper.nombres)AS docente,COUNT(sper.id_persona)AS total
                            FROM sys_procesos AS sp
                            INNER JOIN sys_users AS su ON su.id_user = sp.usuario_id
                            INNER JOIN sys_persona AS sper ON sper.id_persona = su.persona_id
                            INNER JOIN sys_roles AS sr ON sr.id_rol = su.rol_id 
                            WHERE sp.direccion LIKE '%autenticar%' AND sp.fecha_proceso >= '$fecha_menos_5_dias ' AND sp.fecha_proceso <= '$fecha_actual' AND sr.id_rol = 3
                            GROUP BY sper.id_persona
                            ORDER BY total DESC
                            LIMIT 5";

$res_visita_docente = $db->query($sql_visita_docente)->fetch();

/********************************************************************** */
$sql_visita_estudiante = "SELECT sr.rol, CONCAT(sper.primer_apellido,' ', sper.segundo_apellido,' ', sper.nombres)AS estudiante,COUNT(sper.id_persona)AS total
                            FROM sys_procesos AS sp
                            INNER JOIN sys_users AS su ON su.id_user = sp.usuario_id
                            INNER JOIN sys_persona AS sper ON sper.id_persona = su.persona_id
                            INNER JOIN sys_roles AS sr ON sr.id_rol = su.rol_id 
                            WHERE sp.direccion LIKE '%autenticar%' AND sp.fecha_proceso >= '$fecha_menos_5_dias ' AND sp.fecha_proceso <= '$fecha_actual' AND sr.id_rol = 5
                            GROUP BY sper.id_persona
                            ORDER BY total DESC
                            LIMIT 5";

$res_visita_estudiante = $db->query($sql_visita_estudiante)->fetch();

/********************************************************************** */
/*                          ACTIVIDADES                                 */
/********************************************************************** */
// Fecha actual

//Numero actividades
// $sql_actividades = "SELECT COUNT( taca.id_asesor_curso_actividad) AS total 
//                         FROM tem_asesor_curso_actividad AS taca
//                         WHERE taca.estado_actividad = 'A' AND DATE(taca.fecha_registro) = '$fecha_actual'";
$res_actividades = '';

//Numero actividades presentables
// $sql_actividades_presentable = "SELECT COUNT( taca.id_asesor_curso_actividad) AS total 
//                         FROM tem_asesor_curso_actividad AS taca
//                         WHERE taca.estado_actividad = 'A' AND DATE(taca.fecha_registro) = '$fecha_actual' AND taca.presentar_actividad = 'SI'";
$res_actividades_presentable = '';

//Conteo de actividades creadas por tipo
// $sql_actividades_tipo = "SELECT taca.tipo_actividad, COUNT(taca.tipo_actividad) AS total 
//                                     FROM tem_asesor_curso_actividad AS taca
//                                     WHERE taca.estado_actividad = 'A' AND DATE(taca.fecha_registro) = '$fecha_actual'
//                                     GROUP BY taca.tipo_actividad ";
$res_actividades_tipo = '';

$nroReunion = 0;
$nroExamen  = 0;

foreach ($res_actividades_tipo as $value) {
    if ($value['tipo_actividad'] == "REUNION") {
        $nroReunion = (isset($value['total'])) ? $value['total'] : 0;
    }
    if ($value['tipo_actividad'] == "EXAMEN") {
        $nroExamen = (isset($value['total'])) ? $value['total'] : 0;
    }
}




?>
<?php require_once show_template('header-design'); ?>
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/bootstrap/css/bootstrap.min.css">
<link href="assets/themes/concept/assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
<link rel="stylesheet" href="assets/themes/concept/assets/libs/css/style.css">
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
<link href="assets/themes/concept/assets/vendor/bootstrap-colorpicker/%40claviska/jquery-minicolors/jquery.minicolors.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/datepicker/css/datepicker.css" rel="stylesheet">

<link rel="stylesheet" href="assets/themes/concept/assets/assets/vendor/charts/morris-bundle/morris.css">
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/charts/chartist-bundle/chartist.css">
<?php if ($rol_id == 10 || $rol_id == 2 || $rol_id == 7) : ?>
    <!-- ============================================================== -->
    <!-- Perfil Director / Administrativo -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12" background-color='red'>

            <!-- Actividades creadas y por tipo -->
            <div class="row">

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block">
                                <h5 class="text-muted">Presentes</h5>
                                <h2 class="mb-0"> <?= $contador_asitencia ?></h2>
                            </div>
                            <div class="float-right icon-circle-medium  icon-box-lg  bg-info-light mt-1">
                                <i class="fa fa-check fa-fw fa-sm text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block">
                                <h5 class="text-muted">Ausentes</h5>
                                <h2 class="mb-0"> <?= $contador_faltas ?></h2>
                            </div>
                            <div class="float-right icon-circle-medium  icon-box-lg  bg-secondary-light mt-1">
                                <i class=" fas fa-thumbs-down fa-fw fa-sm text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block">
                                <h5 class="text-muted">Permisos</h5>
                                <h2 class="mb-0"> 0</h2>
                            </div>
                            <div class="float-right icon-circle-medium  icon-box-lg  bg-primary-light mt-1">
                                <i class="fas fa-clipboard-check fa-fw fa-sm text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block">
                                <h5 class="text-muted">Atrasos</h5>
                                <h2 class="mb-0"> 0</h2>
                            </div>
                            <div class="float-right icon-circle-medium  icon-box-lg  bg-brand-light mt-1">
                                <i class="fas fa-clock fa-fw fa-sm text-brand"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actividades creadas y por tipo -->
            <div class="row">

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block">
                                <h5 class="text-muted">Actividades</h5>
                                <h2 class="mb-0"><?= $res_actividades['total'] ?></h2>
                            </div>
                            <div class="float-right icon-circle-medium  icon-box-lg  bg-brand-light mt-1">
                                <i class="fas fa-align-left fa-fw fa-sm text-brand"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block">
                                <h5 class="text-muted">Presentables</h5>
                                <h2 class="mb-0"> <?= $res_actividades_presentable['total'] ?></h2>
                            </div>
                            <div class="float-right icon-circle-medium  icon-box-lg  bg-primary-light mt-1">
                                <i class="fas fa-clipboard-check fa-fw fa-sm text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block">
                                <h5 class="text-muted">Reuniones</h5>
                                <h2 class="mb-0"> <?= $nroReunion ?></h2>
                            </div>
                            <div class="float-right icon-circle-medium  icon-box-lg  bg-secondary-light mt-1">
                                <i class="fas fa-users fa-fw fa-sm text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block">
                                <h5 class="text-muted">Examenes</h5>
                                <h2 class="mb-0"> <?= $nroExamen ?></h2>
                            </div>
                            <div class="float-right icon-circle-medium  icon-box-lg  bg-info-light mt-1">
                                <i class="fas fa-pencil-alt fa-fw fa-sm text-light"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .list-group-item {
                    color: white;
                }
            </style>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-muted">Ranking visitas por rol (5 ultimos dias)</h4>
                            <ul class="list-group">
                                <?php
                                $sw = 0;
                                foreach ($res_visita_sp as $value) : ?>
                                    <?php
                                    if ($sw == 0) {
                                        $rango = $value['total'];
                                        $sw = 1;
                                    }
                                    $porcentaje = round((($value['total'] * 100) / $rango), 0);
                                    ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #0061c5 <?= $porcentaje ?>%, #2f2f2f  <?= $porcentaje ?>%)!important;"> <?= $value['rol'] ?> <span class="badge badge-primary badge-pill"><?= $value['total'] ?></span> </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="text-muted">Docentes que mas visitan (5 ultimos dias)</h5>
                            <ul class="list-group">
                                <?php
                                $sw = 0;
                                foreach ($res_visita_docente as $value) : ?>
                                    <?php
                                    if ($sw == 0) {
                                        $rango = $value['total'];
                                        $sw = 1;
                                    }
                                    $porcentaje = round((($value['total'] * 100) / $rango), 0);
                                    ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center" style="background: linear-gradient(45deg, #f31818  <?= $porcentaje ?>%, #6b004f <?= $porcentaje ?>%)!important;"><?= $value['docente'] ?> <span class="badge badge-primary badge-pill"><?= $value['total'] ?></span> </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="text-muted">Estudiantes que mas visitan (5 ultimos dias)</h5>

                            <ul class="list-group">
                                <?php
                                $sw = 0;
                                foreach ($res_visita_estudiante as $value) : ?>
                                    <?php
                                    if ($sw == 0) {
                                        $rango = $value['total'];
                                        $sw = 1;
                                    }
                                    $porcentaje = round((($value['total'] * 100) / $rango), 0);
                                    ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center" style="background: linear-gradient(90deg, #38b129 <?= $porcentaje ?>%, #135a84 <?= $porcentaje ?>%);"> <?= $value['estudiante'] ?> <span class="badge badge-primary badge-pill"><?= $value['total'] ?></span> </li>
                                <?php endforeach; ?>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <h5 class="card-header">Estado financiero </h5>
                        <div class="card-body">
                            <div id="morris_donut" style="height: 15em;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                    <div class="card alert-success">
                        <div class="card-body">
                            <h3 class="mb-1 text-center" style="font-size: 25px;color: green;">0</h3>
                            <p class="text-center">Ingresos</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                    <div class="card alert-warning">
                        <div class="card-body">
                            <h1 class="mb-2 text-center" style="font-size:25px; color: orange;">0</h1>
                            <p class="text-center">Egresos</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                    <div class="card alert-danger">
                        <div class="card-body">
                            <h1 class="mb-2 text-center" style="font-size: 25px; color: red;">0</h1>
                            <p class="text-center">Deudas</p>
                        </div>
                    </div>
                </div>
            </div>
            <!--la agenda-->
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="contariner">
                        <div class="card">
                            <div class="card-body" align="center">
                                <div id='d' style="width:100%; height:100%">
                                    <div class="card">
                                        <div class="alert-primary card-header  bg-light text-left p-3 ">
                                            <h4 class="mb-0 text-black"> Descargar Aplicaciones</h4>
                                        </div>
                                        <div class="card-body border-top">
                                            <ul class="list-unstyled bullet-icon-book-open text-left font-14">
                                                <li><i class="icon-book-open"> </i><a href="files/aplicaciones/app-docente-educheck.apk" style="color:blue" target="_blank">Aplicación para Docentes de Educheck</a></li>
                                                <li><i class="icon-book-open"> </i><a href="files/aplicaciones/app-tutor-papa-docente-educheck.apk" style="color:blue" target="_blank">Aplicación para Papá/Tutor/Familiar de Educheck</a></li>
                                                <li><i class="icon-book-open"> </i><a href="#" style="color:blue">Aplicación para el control de Gondolas de Educheck(Proximamente)</a></li>
                                            </ul>
                                        </div>

                                        <div class="card-body border-top">
                                            <ul class="list-unstyled bullet-icon-book-open text-left font-14">
                                                <li><i class="icon-book-open"> </i> Docentes, Reunión general de docentes.</li>
                                            </ul>
                                        </div>

                                        <div class="card-header alert-primary bg-light text-left p-3 ">
                                            <h4 class="mb-0 text-black"> Mañana <?= date("d/m/Y", strtotime($fecha_maniana)) ?> </h4>
                                        </div>

                                        <div class="card-body border-top">
                                            <ul class="list-unstyled bullet-icon-book-open text-left font-14">

                                            </ul>
                                        </div>

                                        <div class="card-header alert-primary bg-light text-left p-3 ">
                                            <h4 class="mb-0 text-black">Ultimos comunicados publicados </h4>
                                        </div>

                                        <div class="card-body border-top">
                                            <ul class="list-unstyled bullet-icon-book-open text-left font-14">

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
    </div>
    <!-- ============================================================== -->
    <!-- Fin Perfil Director / Administrativo -->
    <!-- ============================================================== -->
<?php elseif ($rol_id == 6) : ?>
    <!-- ============================================================== -->
    <!-- Perfil Secretaria -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12" background-color='red'>
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="font-16 text-center">Estudiantes</h3>
                            <div class="">
                                <span class="badge badge-dark badge-pill">14</span>
                                <label class="form-control-label">Atrasos</label>
                            </div>
                            <div class="">
                                <span class="badge badge-light badge-pill">8</span>
                                <label class="form-control-label">Ausentes</label>
                            </div>
                            <div class="">
                                <span class="badge badge-dark badge-pill">3</span>
                                <label class="form-control-label">Permisos</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body border-top">
                            <h3 class="font-16 text-center">Docentes</h3>
                            <div class="">
                                <span class="badge badge-dark badge-pill">4</span>
                                <label class="form-control-label">Atrasos</label>
                            </div>
                            <div class="">
                                <span class="badge badge-light badge-pill">1</span>
                                <label class="form-control-label">Ausentes</label>
                            </div>
                            <div class="">
                                <span class="badge badge-dark badge-pill">0</span>
                                <label class="form-control-label">Permisos</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body border-top">
                            <h3 class="font-16 text-center">Administrativos</h3>
                            <div class="">
                                <span class="badge badge-dark badge-pill">4</span>
                                <label class="form-control-label">Atrasos</label>
                            </div>
                            <div class="">
                                <span class="badge badge-light badge-pill">2</span>
                                <label class="form-control-label">Ausentes</label>
                            </div>
                            <div class="">
                                <span class="badge badge-dark badge-pill">1</span>
                                <label class="form-control-label">Permisos</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <h5 class="card-header"> </h5>
                        <div class="card-body">
                            <div class="ct-chart-bipolar ct-golden-section"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12">

            <div class="row">

                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                    <div class="card alert-success">
                        <div class="card-body">
                            <h1 class="mb-5 text-center" style="font-size: 38px;color: green;">10500</h1>
                            <p>Ingresos</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                    <div class="card alert-warning">
                        <div class="card-body">
                            <h1 class="mb-5 text-center" style="font-size: 38px; color: orange;">650</h1>
                            <p>Egresos</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                    <div class="card alert-danger">
                        <div class="card-body">
                            <h1 class="mb-5 text-center" style="font-size: 38px; color: red;">1890</h1>
                            <p>Deudas</p>
                        </div>
                    </div>
                </div>
            </div>
            <!--la agenda-->
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="contariner">
                        <div class="card">
                            <div class="card-body" align="center">
                                <div id='calendario' style="width:100%; height:100%"></div>
                                <div id='' style="width:100%; height:100%"></div>
                                <div id='d' style="width:100%; height:100%">
                                    <div class="card">
                                        <div class="alert-primary card-header  bg-light text-left p-3 ">
                                            <h4 class="mb-0 text-black"> Hoy <?= date('d/m/Y') ?></h4>
                                        </div>
                                        <div class="card-body border-top">
                                            <ul class="list-unstyled bullet-icon-book-open text-left font-14">
                                                <li><i class="icon-book-open"> </i> </li>
                                                <li><i class="icon-book-open"> </i> </li>
                                                <li><i class="icon-book-open"> </i> </li>
                                            </ul>
                                        </div>
                                        <div class="card-header alert-primary bg-light text-left p-3 ">
                                            <h4 class="mb-0 text-black"> Mañana </h4>
                                        </div>
                                        <div class="card-body border-top">
                                            <ul class="list-unstyled bullet-icon-book-open text-left font-14">
                                                <li><i class="icon-book-open"> </i> </li>
                                                <li><i class="icon-book-open"> </i> </li>
                                                <li><i class="icon-book-open"> </i> </li>
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
        <!-- ============================================================== -->
        <!-- Fin Perfil Secretaria -->
        <!-- ============================================================== -->
    <?php elseif ($rol_id == 33) : ?>
        <!-- ============================================================== -->
        <!-- Perfil Docente -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-12">
                <div class="row">
                    <div class="col-xl-10 col-lg-10 col-md-10 col-sm-12 col-12">
                        <div class="card  alert-primary-">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12">
                                        <select name="id_materia" id="id_materia" class="form-control" onchange="listar_aula_paralelo();">
                                            <?php foreach ($materias as $val) : ?>
                                                <option value="<?= $val['id_materia'] ?>" selected><?= $val['nombre_materia'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12" id="aula_paralelo">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-header d-flex">
                                <h3 class="card-header-title">Bimestre</h3>
                                <select class="custom-select ml-auto w-auto" id="bimestre">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-12">
                        <div class="tab-regular">
                            <ul class="nav nav-tabs nav-fill" id="myTab7" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pizarra-tab" onclick="cargar_pizarra_materia(4)" data-toggle="tab" href="#contnedor-pizarra" role="tab" aria-controls="home" aria-selected="true"><i class="icon-note"></i> Mi Pizarra</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="asistencia-tab" onclick="cargar_registrar_asistencia();" data-toggle="tab" href="#contnedor-asistencia" role="tab" aria-controls="profile" aria-selected="false"><i class="icon-volume-2"></i> Mis Asistencias</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="nota-tab" data-toggle="tab" href="#contnedor-nota" role="tab" aria-controls="contact" aria-selected="false"><i class="icon-note"></i> Mis Notas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="kardex-tab" onclick="cargar_kardex();" data-toggle="tab" href="#contnedor-kardex" role="tab" aria-controls="contact" aria-selected="false"><b><i class="icon-graduation font-16"></i></b> Kardex</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="reporte-tab" onclick="cargar_reporte_nota();" data-toggle="tab" href="#contnedor-reporte" role="tab" aria-controls="contact" aria-selected="false"><b><i class="icon-graduation font-16"></i></b> Mis Reportes</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent7">
                                <div class="tab-pane fade show active" id="contnedor-pizarra" role="tabpanel" aria-labelledby="pizarra-tab">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                            <!-- ============================================================== -->
                                            <!-- gauge four  -->
                                            <!-- ============================================================== -->
                                            <div class="">
                                                <h5 class="">Promedio del Curso por Bimestre</h5>
                                                <div class="card-body">
                                                    <div id="grafico-lineal"></div>
                                                </div>
                                            </div>
                                            <!-- ============================================================== -->
                                            <!-- end gauge four  -->
                                            <!-- ============================================================== -->
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                            <div class="">

                                                <div class="card-body">
                                                    <h5 class="">Altos Promedios de la Materia</h5>
                                                    <ul class="list-group">
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            estudiante 1
                                                            <span class="badge badge-primary badge-pill">100</span>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            estudiante 1
                                                            <span class="badge badge-primary badge-pill">98</span>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            estudiante 1
                                                            <span class="badge badge-primary badge-pill">90</span>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="card-body">
                                                    <h5 class="">Bajos Promedios de la Materia</h5>
                                                    <ul class="list-group">
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            estudiante 1
                                                            <span class="badge badge-danger badge-pill">51</span>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            estudiante 1
                                                            <span class="badge badge-danger badge-pill">50</span>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            estudiante 1
                                                            <span class="badge badge-danger badge-pill">49</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="contnedor-asistencia" role="tabpanel" aria-labelledby="asistencia-tab">
                                </div>
                                <div class="tab-pane fade" id="contnedor-nota" role="tabpanel" aria-labelledby="nota-tab">
                                    <div class="row">
                                        <?php $contador = 0; ?>
                                        <?php foreach ($area_calificacion as $k => $val) : ?>
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12" style="cursor:pointer;" id="boton" data-area="<?= $val['id_area_calificacion']; ?>" onclick="cargar_registrar_nota(<?= $val['id_area_calificacion']; ?>)">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="metric-value d-inline-block">
                                                            <h2 class="mb-1"><?= $val['descripcion']; ?></h2>
                                                            <input type="hidden" id="id_area_<?= $val['id_area_calificacion']; ?>" value="<?= $val['id_area_calificacion']; ?>">
                                                        </div>
                                                        <div class="metric-label d-inline-block float-right text-success font-weight-bold">
                                                            <span class="icon-circle-small icon-puzzle-xs text-dark bg-light font-18"><i class="icon-puzzle"></i></span><span class="ml-1 text-dark font-20"><?= $val['ponderado']; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="card-body bg-light p-t-40 p-b-40">
                                                        <div id="sparkline-revenue">
                                                            <center><img width="150" height="150" src="<?= imgs . '/ic_area_calificacion.png' ?>"></center>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $contador++; ?>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="contnedor-kardex" role="tabpanel" aria-labelledby="kardex-tab">
                                </div>
                                <div class="tab-pane fade" id="contnedor-reporte" role="tabpanel" aria-labelledby="reporte-tab">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
                <div class="row">
                </div>
                <!--la agenda-->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="contariner">
                            <div class="card">
                                <div class="card-body" align="center">
                                    <div id='calendario' style="width:100%; height:100%"></div>
                                    <div id='' style="width:100%; height:100%"></div>
                                    <div id='d' style="width:100%; height:100%">
                                        <div class="card">
                                            <div class="alert-primary card-header  bg-light text-left p-3 ">
                                                <h4 class="mb-0 text-black"> Hoy <?= date('d/m/Y') ?></h4>
                                            </div>
                                            <div class="card-body border-top">
                                                <ul class="list-unstyled bullet-icon-book-open text-left font-14">
                                                    <li><i class="icon-book-open"> </i> </li>
                                                    <li><i class="icon-book-open"> </i> </li>
                                                    <li><i class="icon-book-open"> </i> </li>
                                                </ul>
                                            </div>
                                            <div class="card-header alert-primary bg-light text-left p-3 ">
                                                <h4 class="mb-0 text-black"> Mañana 09/10/2019 </h4>
                                            </div>
                                            <div class="card-body border-top">
                                                <ul class="list-unstyled bullet-icon-book-open text-left font-14">
                                                    <li><i class="icon-book-open"> </i> </li>
                                                    <li><i class="icon-book-open"> </i> </li>
                                                    <li><i class="icon-book-open"> </i> </li>
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
            <!-- ============================================================== -->
            <!-- Fin Perfil Docente -->
            <!-- ============================================================== -->
        <?php elseif ($rol_id == 4 || $rol_id == 5) : ?>
        <?php endif ?>

        <!-- Gráficos -->
        <script src="<?= themes; ?>/concept/assets/vendor/charts/morris-bundle/raphael.min.js"></script>
        <script src="<?= themes; ?>/concept/assets/vendor/charts/morris-bundle/morris.js"></script>
        <script src="<?= themes; ?>/concept/assets/vendor/charts/chartist-bundle/chartist.min.js"></script>

        <script>
            $(document).ready(function() {

                //mandar un ajax de pedir datos
                if ($('#morris_donut').length) {
                    Morris.Donut({
                        element: 'morris_donut',
                        data: [{
                                value: 70,
                                label: 'ingresos'
                            },
                            {
                                value: 15,
                                label: 'Gastos'
                            },
                            //{ value: 10, label: 'baz' },
                            // { value: 5, label: 'A really really long label' }
                        ],

                        labelColor: '#2e2f39',
                        gridTextSize: '14px',
                        colors: [
                            "#5969ff",
                            "#ff407b",
                            //    "#25d5f2",
                            //    "#ffc750"

                        ],

                        formatter: function(x) {
                            return x + "%"
                        },
                        resize: true
                    });
                }
            });
        </script>