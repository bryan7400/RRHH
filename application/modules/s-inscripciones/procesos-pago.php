<?php
 /*
 * FunctionPHP - Framework Functional PHP
 * @package  FunctionPHP
 * @proyect  Educheck 
 * @version  v2
 * @author   Maribel Callisaya
 */ 
 
$boton = $_POST['boton'];

// Obtiene el id de la gestion actual
$id_gestion   = $_gestion['id_gestion'];
$fecha_actual = Date('Y-m-d H:i:s');

//obtiene el listado de cursos
if ($boton == "listar_cursos") {
    //var_dump($_POST);exit();
    //obtiene el nivel
    $nivel = $_POST['nivel'];
    $turno = $_POST['turno'];

    //obtiene los cursos segun el nivel
    $cursos = $db->query("SELECT ap.id_aula_paralelo, ap.capacidad, a.nombre_aula , p.nombre_paralelo , p.descripcion , na.nombre_nivel, t.nombre_turno
                            FROM ins_aula_paralelo AS ap
                            INNER JOIN ins_aula AS a ON a.id_aula = ap.aula_id
                            INNER JOIN ins_nivel_academico AS na ON na.id_nivel_academico = a.nivel_academico_id
                            INNER JOIN ins_paralelo AS p ON p.id_paralelo = ap.paralelo_id
                            INNER JOIN ins_turno t ON ap.turno_id = t.id_turno
                            WHERE a.nivel_academico_id = $nivel AND ap.estado = 'A' AND a.estado = 'A' AND ap.turno_id = $turno
                            ORDER BY a.id_aula, p.id_paralelo")->fetch();
    echo json_encode($cursos);
}

//obtiene el nro de varones y mujeres inscritos en un curso y gestion especifico
if ($boton == "listar_vacantes") {
    //obtiene el nivel
    $id_aula_paralelo = $_POST['id_aula_paralelo'];
    //obtiene los cursos segun el nivel
    $nroVM = $db->query("SELECT IFNULL(SUM(p.genero= 'v'),0) AS nro_varones, IFNULL(SUM(p.genero= 'm'),0) AS nro_mujeres,  COUNT(i.id_inscripcion) AS inscritos, IFNULL(ap.capacidad,0) AS cupo_total
                            FROM ins_inscripcion AS i
                            INNER JOIN ins_estudiante e ON e.id_estudiante = i.estudiante_id
                            INNER JOIN sys_persona p ON p.id_persona = e.persona_id
                            INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo = i.aula_paralelo_id
                            WHERE i.aula_paralelo_id = $id_aula_paralelo AND i.gestion_id = $id_gestion AND i.estado = 'A'")->fetch_first();

    echo json_encode($nroVM);
}

//obtiene el listado de cursos
// if ($boton == "listar_niveles") {
//     $niveles = $db->query("SELECT * FROM ins_nivel_academico WHERE estado = 'A' AND gestion_id = $id_gestion")->fetch();
//     echo json_encode($niveles);
// }
//obtiene el listado de cursos
if ($boton == "listar_niveles") {

    $turno = $_POST['id_turno'];
    //$niveles = $db->query("SELECT * FROM ins_nivel_academico WHERE estado = 'A' AND gestion_id = $id_gestion")->fetch();
    //obtiene los cursos segun el nivel
    $niveles = $db->query("SELECT  na.nombre_nivel, na.id_nivel_academico
                            FROM ins_aula_paralelo AS ap
                            INNER JOIN ins_aula AS a ON a.id_aula = ap.aula_id
                            INNER JOIN ins_nivel_academico AS na ON na.id_nivel_academico = a.nivel_academico_id
                            WHERE ap.estado = 'A' AND a.estado = 'A' AND ap.turno_id = $turno
                            and a.gestion_id = $id_gestion 
                            group by na.id_nivel_academico
                            ORDER BY na.nombre_nivel ")->fetch();
    echo json_encode($niveles);
}

if ($boton == "guardar_inscripcion_editar") {
    
    // /*Capturamos el curso elegido*/
    // echo "<pre>"; 
    // var_dump($_POST);
    // echo "</pre>";
    // exit();

    $id_inscripcion      = (isset($_POST['inscripcion_id'])) ? $_POST['inscripcion_id'] : 0;
    $fecha_inicio_cobro  = (isset($_POST['fecha_inicio'])) ? $_POST['fecha_inicio'] : '0000-00-00';
    $id_tipo_estudiante  = (isset($_POST['tipo_estudiante'])) ? $_POST['tipo_estudiante'] : $_POST['a_id_tipo_estudiante'];
    $id_aula_paralelo    = (isset($_POST['select_curso'])) ? $_POST['select_curso'] : $_POST['a_id_curso'];   
    $id_turno            = (isset($_POST['turno'])) ? $_POST['turno'] : $_POST['a_id_turno'];
    $id_nivel_academico  = (isset($_POST['nivel_academico'])) ? $_POST['nivel_academico'] : $_POST['a_id_nivel_academico'];

    $a_id_tipo_estudiante  = (isset($_POST['a_id_tipo_estudiante'])) ? $_POST['a_id_tipo_estudiante'] : 0;
    $a_id_aula_paralelo    = (isset($_POST['a_id_curso'])) ? $_POST['a_id_curso'] : 0;   
    $a_id_turno            = (isset($_POST['a_id_turno'])) ? $_POST['a_id_turno'] : 0;
    $a_id_nivel_academico  = (isset($_POST['a_id_nivel_academico'])) ? $_POST['a_id_nivel_academico'] : 0;
 
    if($fecha_inicio_cobro != '0000-00-00'){

        $pagos_sql = " SELECT *  
        FROM pen_pensiones p 
        INNER JOIN pen_concepto c ON p.concepto_id = c.id_concepto
        INNER JOIN pen_pensiones_detalle pd ON p.id_pensiones = pd.pensiones_id
        WHERE p.estado ='A' AND p.gestion_id = $id_gestion AND p.nombre_pension != 'RESERVA' "; 
        $pagos = $db->query($pagos_sql)->fetch();

        $pagos_asignar = $db->query("SELECT *
        FROM pen_pensiones p 
        INNER JOIN pen_concepto c ON p.concepto_id = c.id_concepto
        /*INNER JOIN pen_pensiones_detalle ppd ON p.id_pensiones = ppd.pensiones_id*/
        WHERE p.estado ='A' AND p.gestion_id = $id_gestion 
        group by p.id_pensiones
        ORDER BY p.nombre_pension")->fetch();

        //var_dump($pagos);exit();

        // foreach ($pagos as $key => $value) {
        //     $pag_tipo_estudiante_id = $value['tipo_estudiante_id'];
        //     $pag_nivel_academico_id = $value['nivel_academico_id'];
        //     $pag_turno_id           = $value['turno_id'];
        //     $pag_aula_paralelo_id   = $value['aula_paralelo_id'];

        //var_dump($pagos);exit();
        $auxiliar_p = array();
        $auxiliar_pp = array();
        $array0 = '';
        $array1 = '';
        $array2 = '';
        $array3 = '';
        $a = 0;
        foreach($pagos as $val){

            if($val['tipo_concepto']=='GRUPAL'){
                //var_dump('GRUPAL');
                $turno = explode(",", $val['turno_id']);
                //echo($val['turno_id'].'turno');
                $contador   = count($turno);
                $array0 = '';
                for($i=0;$i<$contador;$i++){
                        if($turno[$i]==$id_turno){
                            $array0 = $val['id_pensiones'];
                        }
                }

                $nivel      = explode(",", $val['nivel_academico_id']);
                //echo($val['nivel_academico_id'].'nivel');
                $contador1  = count($nivel);
                $array1 = '';

                for($j=0;$j<$contador1;$j++){
                        if($nivel[$j]==$id_nivel_academico){
                            $a = $id_nivel_academico;
                            $array1 = $val['id_pensiones'];
                        }
                }
            
                //$tipo_estudiante      = explode(",", $val['tipo_estudiante_id']);
                //echo($val['tipo_estudiante_id'].'tipo');
                //$contador2  = count($tipo_estudiante);
                $array2 = '';
                // for($k=0;$k<$contador2;$k++){
                //         //if($tipo_estudiante[$k]==$id_tipo_estudiante){
                //         if($tipo_estudiante[$k]==$id_tipo_estudiante){
                //             $array2 = $val['id_pensiones'];
                //         }
                // }

                if($array0>0 && $array0==$array1){
                    ///var_dump('expression hhhhhhhhhhhhhhhhhhhhhhhhhhhhhh');
                    array_push($auxiliar_p, $array1);
                    //var_dump('si'); var_dump($array1);
                }else{
                    //var_dump($a);
                    // var_dump('no');
                }
                //echo '<br>';
                
            }else if($val['tipo_concepto']=='GRUPAL2'){
                //var_dump('GRUPAL2');
                $curso = explode(",", $val['aula_paralelo_id']);
                $contador   = count($curso);
                $array3 = '';
                for($i=0;$i<$contador;$i++){
                        if($curso[$i]==$id_aula_paralelo ){
                            $array3= $val['id_pensiones'];
                        }
                }
                array_push($auxiliar_p, $array3);
                //var_dump($array3);
            }else if($val['tipo_concepto']=='INDIVIDUAL'){
                //var_dump('INDIVIDUAL');
                //No aplica ya que requiere primero la inscripcion 
            }else if($val['tipo_concepto']=='GENERAL'){
                //var_dump('GENERAL');
                $id_pension = $val['id_pensiones'];
                array_push($auxiliar_p, $id_pension);
            }


            //var_dump($array);
            $arraynew = array(
                        'id_pensioness'     => $auxiliar_p,
                        'tipo_concepto'     => $val['tipo_concepto'],
            );
            array_push($auxiliar_pp, $arraynew);
        }
        $nue = array_filter($auxiliar_p);
        $nuevo_array = array_values($nue);
        
        $contador = count($nuevo_array);
       //var_dump($contador);
       // exit();
        $auxiliar_por_asignar = array();
        //var_dump($pagos);
        for ($a=0; $a<$contador; $a++) {
            //var_dump($nue[$i]);exit();
            foreach ($pagos as $value) {
            //var_dump($value);
                if($nuevo_array[$a] == $value['id_pensiones']){
                    $arraypa = (array) [
                        'id_pensiones'   => $value['id_pensiones'],
                        'nombre_pension' => $value['nombre_pension'],
                        'nombre_concepto' => $value['nombre_concepto'],
                        'descripcion'    => $value['descripcion'],
                        'tipo_concepto'  => $value['tipo_concepto'],
                        'fecha_final'    => $value['fecha_final'],
                        'cuota'          => $value['cuota'],
                        'nro'    => $value['nro'],
                        'acuenta'  => 0,
                        'saldo'  => $value['cuota'],
                        'id_pensiones_detalle' => $value['id_pensiones_detalle'],
                        'monto'  => $value['monto'],
                        'descuento_bs' => $value['descuento_bs'],
                        'mora_dia'  => $value['mora_dia'],
                        'descuento_porcentaje' => $value['descuento_porcentaje'],
                        'fecha_inicio' => $value['fecha_inicio'],
                        'tipo_documento'  => $value['tipo_documento'],
                    ];
                    array_push($auxiliar_por_asignar, $arraypa);
                }else{
                    //$array = (array) [];
                    //$auxiliar_asignar='';
                    //var_dump($nue[$i].$value['id_pensiones']);
                }
            }
        }

        //var_dump($auxiliar_por_asignar);exit();

        // Obtenemos los pagos asignados al estudiante mediante su inscripcion de gestion actual
        $mensualidad = $db->query("SELECT pe.id_pensiones_estudiante, pe.inscripcion_id, pe.historial_id, p.id_pensiones, p.nombre_pension, pd.nro, pd.id_pensiones_detalle
        FROM pen_pensiones p 
        INNER JOIN pen_pensiones_detalle pd ON p.id_pensiones = pd.pensiones_id
        INNER JOIN pen_pensiones_estudiante pe ON pd.id_pensiones_detalle=pe.detalle_pension_id
        WHERE pe.inscripcion_id = $id_inscripcion
        AND pd.fecha_final >= '$fecha_inicio_cobro'")->fetch();
        //var_dump($mensualidad);
        //exit();
        //if($mensualidad){  
        
                // var_dump($id_pensiones_estudiante);
                // exit();
                $inscripcion = array(
                    'aula_paralelo_id'   => $id_aula_paralelo,
                    'tipo_estudiante_id' => $id_tipo_estudiante,
                    'nivel_academico_id' => $id_nivel_academico,
                    'turno_id'           => $id_turno,  
                );
                $id_inscripcion_update = $db->where('id_inscripcion', $id_inscripcion)->update('ins_inscripcion', $inscripcion); 

                if($id_inscripcion_update){

                    $sql_ins = $db->query("SELECT * FROM ins_inscripcion i  WHERE i.id_inscripcion = $id_inscripcion ")->fetch_first();
                    //Introducimos los datos anteriores al Historial      
                    $historial = array(        
                        'inscripcion_id'        => $sql_ins['id_inscripcion'],      
                        'estudiante_id'         => $sql_ins['estudiante_id'],
                        'tipo_estudiante_id'    => $sql_ins['tipo_estudiante_id'],
                        'aula_paralelo_id'      => $sql_ins['aula_paralelo_id'],
                        'turno_id'              => $sql_ins['turno_id'],
                        'nivel_academico_id'    => $sql_ins['nivel_academico_id'],
                        'gestion_id'            => $id_gestion,
                        'estado'                => 'A',
                        'usuario_registro'      => $_user['id_user'],
                        'fecha_registro'        => Date('Y-m-d H:i:s'),
                        'usuario_modificacion'  => 0,
                        'fecha_modificacion'    => '0000-00-00',      
                    );
                    $id_inscripcion_historico = $db->insert('ins_inscripcion_historial', $historial);

                    //var_dump($id_inscripcion_historico);

                    if($id_inscripcion_historico){    

                        foreach($mensualidad as $value){
                            $id_pensiones_estudiante = $value['id_pensiones_estudiante'];

                            // $id_aula_paralelo,
                            // $id_tipo_estudiante,
                            // $id_nivel_academico,
                            // $id_turno,

                            // Obtiene datos de los pagos
                            // $pagos = $db->query("SELECT *  
                            // FROM pen_pensiones p 
                            // INNER JOIN pen_pensiones_detalle pd ON p.id_pensiones = pd.pensiones_id
                            // WHERE p.estado ='A' AND p.gestion_id = $id_gestion AND p.nombre_pension != 'RESERVA' AND p.aula_paralelo_id = $id_aula_paralelo
                            // OR  p.estado ='A' AND p.gestion_id = $id_gestion AND p.nombre_pension != 'RESERVA' AND  p.nivel_academico_id = $id_nivel_academico AND p.tipo_estudiante_id = $id_tipo_estudiante AND p.turno_id = $id_turno
                            // OR  p.estado ='A' AND p.nombre_pension != 'RESERVA' AND p.tipo_concepto LIKE 'GENERAL' ")->fetch();


                            $cobro = $db->query("SELECT IFNULL(count(p.pensiones_estudiante_id),0) contador FROM pen_pensiones_estudiante_detalle p  WHERE p.pensiones_estudiante_id = $id_pensiones_estudiante ")->fetch_first();
                            //var_dump( $pagos);
                            foreach($auxiliar_por_asignar as $val){ 
                                
                                if($value['nro']==$val['nro'] && $cobro['contador']==0){

                                    $pension_detalle = array(
                                        'inscripcion_id'        => $id_inscripcion,      
                                        'historial_id'          => $id_inscripcion_historico,
                                        'detalle_pension_id'    => $val['id_pensiones_detalle'],
                                        'monto'                 => $val['monto'],
                                        'descuento_bs'          => $val['descuento_bs'],
                                        'mora_dia'              => $val['mora_dia'],                                  
                                        'descuento_porcentaje'  => $val['descuento_porcentaje'],      
                                        'tipo_concepto'         => $val['tipo_concepto'],
                                        'cuota'                 => $val['cuota'],                     
                                        'fecha_inicio'          => $val['fecha_inicio'],      
                                        'fecha_final'           => $val['fecha_final'],
                                        'nombre_cliente'        => '',
                                        'nit_ci'                => 0,                     
                                        'tipo_documento'        => $val['tipo_documento'],      
                                        'compromiso'            => 'NO',
                                        'observacion_pension_estudiante' => 'Concepto de pago de Estudiante ha sido modificado.',
                                    );

                                    
                                    $id_cobro = $db->where('id_pensiones_estudiante', $id_pensiones_estudiante)->update('pen_pensiones_estudiante', $pension_detalle); 
                                }
                            }
                        
                        }

                    }
               // }
           
            // var_dump($pension_detalle);
            // exit();
        }else{
            // $inscripcion = array(
            //     'aula_paralelo_id'   => $id_aula_paralelo,
            //     'tipo_estudiante_id' => $id_tipo_estudiante,
            //     'nivel_academico_id' => $id_nivel_academico,
            //     'turno_id'           => $id_turno,  
            // );
            // $id_inscripcion_update = $db->where('id_inscripcion', $id_inscripcion)->update('ins_inscripcion', $inscripcion); 

            // if($id_inscripcion_update){

            //     $sql_ins = $db->query("SELECT * FROM ins_inscripcion i  WHERE i.id_inscripcion = $id_inscripcion ")->fetch_first();
            //     //Introducimos los datos anteriores al Historial      
            //     $historial = array(        
            //         'inscripcion_id'        => $sql_ins['id_inscripcion'],      
            //         'estudiante_id'         => $sql_ins['estudiante_id'],
            //         'tipo_estudiante_id'    => $sql_ins['tipo_estudiante_id'],
            //         'aula_paralelo_id'      => $sql_ins['aula_paralelo_id'],
            //         'turno_id'              => $sql_ins['turno_id'],
            //         'nivel_academico_id'    => $sql_ins['nivel_academico_id'],
            //         'gestion_id'            => $id_gestion,
            //         'estado'                => 'A',
            //         'usuario_registro'      => $_user['id_user'],
            //         'fecha_registro'        => Date('Y-m-d H:i:s'),
            //         'usuario_modificacion'  => 0,
            //         'fecha_modificacion'    => '0000-00-00',      
            //     );
            //     $id_inscripcion_historico = $db->insert('ins_inscripcion_historial', $historial);  
            // }else{
            //     $respuesta = array(
            //         //'id_historico' => $id_inscripcion_historico,
            //         'estado' => 0
            //     );
            // }   
        }
        //exit();
        $respuesta = array(
            //'id_historico' => $id_inscripcion_historico,
            'estado' => 1
        );    
    }else{
        $respuesta = array(
            //'id_historico' => $id_inscripcion_historico,
            'estado' => 0
        );
    }
    echo json_encode($respuesta);
}

/****************************************************/
// el metodo guardar antiguo una inscripcion
/****************************************************/

if ($boton == "guardar_concepto_pago") {
    //var_dump($_POST);die;
    $id_estudiante  = $_POST['id_estudiante'];
    //$id_inscripcion  = $_POST['id_inscripcion']; 
    $id_pensiones  = (isset($_POST['id_pensiones'])) ? $_POST['id_pensiones'] : array();
    $tipo_concepto = (isset($_POST['tipo_concepto'])) ? $_POST['tipo_concepto'] : array();

    $busqueda = $db->query("SELECT IFNULL(COUNT(i.codigo_inscripcion),0) AS codigo_inscripcion, i.id_inscripcion, ih.id_historial
    FROM ins_inscripcion i INNER JOIN ins_inscripcion_historial ih ON i.id_inscripcion = ih.inscripcion_id WHERE i.estudiante_id = $id_estudiante AND i.gestion_id = $id_gestion AND i.estado='A' ")->fetch_first();

    // Obtiene datos de los pagos
    foreach ($id_pensiones as $nro => $elemento) {
        $pagos = $db->query("SELECT * FROM pen_pensiones p inner join pen_pensiones_detalle pd on p.id_pensiones=pd.pensiones_id where p.id_pensiones='$id_pensiones[$nro]' ORDER BY p.nombre_pension")->fetch();
        //var_dump($pagos);exit();
        $contador = $busqueda['codigo_inscripcion'];

        foreach ($pagos as $value) {
            $detalle_estudiante = array(
                'detalle_pension_id'    => $value['id_pensiones_detalle'],
                'inscripcion_id'        => $busqueda['id_inscripcion'],
                'historial_id'          => $busqueda['id_historial'],
                'tipo_concepto'         => $value['tipo_concepto'],
                'fecha_registro'        => date('Y-m-d H:i:s'),
                'fecha_modificacion'    => '0000-00-00 00:00:00',
                'usuario_registro'      => $_user['id_user'],
                'usuario_modificacion'  => 0,
                'cuota'                 => $value['cuota'],
                'descuento_porcentaje'  => $value['descuento_porcentaje'],
                'descuento_bs' => $value['descuento_bs'],
                'monto'        => $value['monto'],
                'mora_dia'     => $value['mora_dia'],
                'fecha_inicio' => $value['fecha_inicio'],
                'fecha_final'  => $value['fecha_final'],
            );
            //var_dump($detalle_estudiante);
            $id_pensiones_estudiante = $db->insert('pen_pensiones_estudiante', $detalle_estudiante);
        }
    }
    if ($id_pensiones_estudiante) {
        echo 1;
    } else {
        echo 2;
    }
}


// Fin de reserva
