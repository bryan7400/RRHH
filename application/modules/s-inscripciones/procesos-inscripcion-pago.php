<?php

$boton = $_POST['boton'];

// Obtiene el id de la gestion actual
$id_gestion   = $_gestion['id_gestion'];
$fecha_actual = Date('Y-m-d H:i:s');

/****************************************************/
//      Metodo guardar una nueva inscripcion        //
/****************************************************/
if ($boton == "guardar_inscripcion_editar") {
    
    /*Capturamos el curso elegido*/
    // echo "<pre>";
    // var_dump($_POST);
    // echo "</pre>";
    // exit();

    $id_inscripcion = $_POST['inscripcion_id'];
    //Capturamos las variables que llegaran tanto de formulario con modificaciones como los datos antiguos de la inscripcion.

    $id_tipo_estudiante  = (isset($_POST['tipo_estudiante'])) ? $_POST['tipo_estudiante'] : $_POST['a_id_tipo_estudiante'];
    $id_aula_paralelo    = (isset($_POST['select_curso'])) ? $_POST['select_curso'] : $_POST['a_id_curso'];   
    $id_turno            = (isset($_POST['turno'])) ? $_POST['turno'] : $_POST['a_id_turno'];
    $id_nivel_academico  = (isset($_POST['nivel_academico'])) ? $_POST['nivel_academico'] : $_POST['a_id_nivel_academico'];

    $inscripcion = array(
        'aula_paralelo_id' => $id_aula_paralelo,
        'tipo_estudiante_id' => $id_tipo_estudiante,
        'nivel_academico_id' => $id_nivel_academico,
        'turno_id' => $id_turno,  
    );

    $db->where('id_inscripcion', $_POST['inscripcion_id'])->update('ins_inscripcion', $inscripcion);

    //Introducimos los datos anteriores al Historial      
    $a_id_tipo_estudiante = $_POST['a_id_tipo_estudiante'];
    $a_id_aula_paralelo   = $_POST['a_id_curso'];
    $a_id_turno           = $_POST['a_id_turno'];   
    $a_id_nivel_academico = $_POST['a_id_nivel_academico'];
    
    $inscripcion_Historico = array(        
        'inscripcion_id' => $id_inscripcion,
        'tipo_estudiante_id' => $a_id_tipo_estudiante,
        'aula_paralelo_id' => $a_id_aula_paralelo,
        'turno_id' => $a_id_turno,
        'nivel_academico_id' => $a_id_nivel_academico,
        'gestion_id' => $id_gestion,
        'fecha_limite' => Date('Y-m-d H:i:s'),
        'estado' => 'A',
        'usuario_registro' => $_user['id_user'],
        'fecha_registro' => Date('Y-m-d H:i:s'),
        'usuario_modificacion' => 0,
        'fecha_modificacion' => '0000-00-00',      
    );

    $id_inscripcion_historico = $db->insert('ins_inscripcion_historico', $inscripcion_Historico);
      
    $respuesta = array(
        'id_historico' => $id_inscripcion_historico,
        'estado' => 1
    );    

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

    $busqueda = $db->query("SELECT COUNT(codigo_inscripcion) as codigo_inscripcion, id_inscripcion FROM ins_inscripcion WHERE estudiante_id = $id_estudiante AND gestion_id = $id_gestion")->fetch_first();

    // Obtiene datos de los pagos
    foreach ($id_pensiones as $nro => $elemento) {
        $pagos = $db->query("SELECT * FROM pen_pensiones p inner join pen_pensiones_detalle pd on p.id_pensiones=pd.pensiones_id where p.id_pensiones='$id_pensiones[$nro]' ORDER BY p.nombre_pension")->fetch();
        //var_dump($pagos);exit();
        $contador = $busqueda['codigo_inscripcion'];

        foreach ($pagos as $value) {
            $detalle_estudiante = array(
                'detalle_pension_id'    => $value['id_pensiones_detalle'],
                'inscripcion_id'        => $busqueda['id_inscripcion'],
                'tipo_concepto'         => $value['tipo_concepto'],
                'tipo_documento'        => $value['tipo_documento'],
                'fecha_registro'        => date('Y-m-d H:i:s'),
                'fecha_modificacion'    => '0000-00-00 00:00:00',
                'usuario_registro'      => $_user['id_user'], 
                'usuario_modificacion'  => 0,
                'cuota'                 => $value['cuota'],
                'descuento_porcentaje'  => $value['descuento_porcentaje'],
                'descuento_bs'          => $value['descuento_bs'],
                'monto'                 => $value['monto'],
                'mora_dia'              => $value['mora_dia'],
                'fecha_inicio'          => $value['fecha_inicio'],
                'fecha_final'           => $value['fecha_final'],
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
