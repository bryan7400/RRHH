<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK (MARIBEL JORGE LUIS)
 */

// Define las cabeceras
header('Content-Type: application/json');

// Verifica la peticion post
if (is_post()) {
    
    // echo "<pre>";
    // var_dump($_POST);
    // echo "</pre>";
    // exit();

    //Obtiene los datos
    // obtenemos la fecha y hora actual del sistema
    $fechaHoraActual = date('Y-m-d H:i:s');
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');

    $usuario          = clear($_POST['usuario']);
    $contrasenia      = clear($_POST['contrasenia']);

    $id_asesor_curso_actividad = isset($_POST['id_asesor_curso_actividad']) ? $_POST['id_asesor_curso_actividad'] : 0;
    $id_usuario       = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : 0;

    $id_estudiante = isset($_POST['estudiantes']) ? $_POST['estudiantes'] : "";
    $id_estudiante = substr($id_estudiante, 0, -1);

    $nota = isset($_POST['notas']) ? $_POST['notas'] : 0;
    $nota = substr($nota, 0, -1);

    //Convertimos array
    $a_idEstudiante = explode("@", $id_estudiante);
    $a_nota         = explode("@", $nota);


    // Encripta la contraseña para compararla en la base de datos
    $usuario = md5($usuario);
    $contrasenia = encrypt($contrasenia);

    // Obtiene los datos del usuario
    $usuario = $db->select('persona_id')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
    $id_gestion = $_POST['id_gestion'];

    // Verifica la existencia del usuario 
    if ($usuario) {

        for ($i = 0; $i < sizeof($a_idEstudiante); $i++) {
            $i_id_estudiante = $a_idEstudiante[$i];
            $i_nota          = $a_nota[$i];

            //BUSCAR NOTA CON EST Y ACTIVIDAD
            
            $nota_actividad_estudiante = $db->query("SELECT * FROM tem_estudiante_curso_actividad where estudiante_id =  $i_id_estudiante AND asesor_curso_actividad_id = $id_asesor_curso_actividad")->fetch_first();
            if ($nota_actividad_estudiante) {
                //SI SE ENCUENTRA ACTUALIZAR
                $datos = array(
                    'nota' => $i_nota
                );
                 $var = $nota_actividad_estudiante['id_estudiante_curso_actividad'];
                $id = $db->where('id_estudiante_curso_actividad',$var)->update('tem_estudiante_curso_actividad', $datos);
                
            } else {
                //SI NO SE ENCUANTRA CREAR
                $id = $db->insert('tem_estudiante_curso_actividad', array(
                    'estudiante_id' => $i_id_estudiante,
                    'archivo' => '',
                    'asesor_curso_actividad_id' => $id_asesor_curso_actividad,
                    'fecha_registro' => $fecha,
                    'hora_registro' => $hora,
                    'nota' => $i_nota,
                    'nota_cualitativa' => '',
                    'estado_actividad' => 'NO',
                    'observacion_rehacer' => '',
                    'usuario_registro' => $id_usuario,
                    'estado_calificado' => 'SI',
                    'archivo_corregido' => '',
                    'observacion_docente' => '',
                    'observacion_corregido' => '',
                    'observacion_administador' => '',
                    'ver_nota' => 'NO',
                    'estado_presentacion' => 'CARTILLA',
                    'cartilla' => 'SI',
                    'examen_url' => 'NO'
                ));
            }
        }

        
            echo json_encode(array('estado' => 's'));
       
    } else {
        // Devuelve los resultados
        echo json_encode(array('estado' => 'n login'));
    }
} else {
    // Devuelve los resultados
    echo json_encode(array('estado' => 'n post'));
}
