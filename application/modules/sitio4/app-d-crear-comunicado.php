<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK (MARIBEL MARCO LUIS)
 */

// Define las cabeceras
header('Content-Type: application/json');

// Verifica la peticion post
if (is_post()) {

    //Obtiene los datos
    $usuario              = clear($_POST['usuario']);
    $contrasenia          = clear($_POST['contrasenia']);


    $usuario = md5($usuario);
    $contrasenia = encrypt($contrasenia);

    // Obtiene los datos del usuario
    $usuario = $db->select('persona_id, id_user')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();

    $id_gestion = (isset($_POST['id_gestion'])) ? clear($_POST['id_gestion']) : 0;
    $id_persona = $usuario['persona_id'];
    $id_user    = $usuario['id_user'];   // Verifica la existencia del usuario 

    //obtenemos la fecha de hoy
    $hoy = date('Y-m-d');

    // Obtenemos el modo calificacion
    $sql_modo_calificacion = "SELECT * FROM cal_modo_calificacion WHERE gestion_id ='$id_gestion' and fecha_inicio <= '$hoy' and fecha_final >= '$hoy' AND estado = 'A'";
    $modos_calificacion = $db->query($sql_modo_calificacion)->fetch_first();
    $id_modo_calificacion = ((isset($modos_calificacion['id_modo_calificacion']) ? $modos_calificacion['id_modo_calificacion'] : "0"));
    
    

    if ($usuario) {

        // Verifica la existencia de datos
        if (isset($_POST['titulo']) && isset($_POST['fecha_ini'])) {

            // Obtiene los datos
            $id_comunicado      = (isset($_POST['id_comunicado'])) ? clear($_POST['id_comunicado']) : 0;
            $modo_id            = $id_modo_calificacion;
            $asignacion_docente_id   = (isset($_POST['asignacion_docente_id'])) ? clear($_POST['asignacion_docente_id']) : 0;
            $grupo              = (isset($_POST['tipo'])) ? clear($_POST['tipo']) : 0;
            $nombre_evento      = isset($_POST['titulo']) ? $_POST['titulo'] : '';
            $descripcion_evento = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
            $fecha_inicio       = isset($_POST['fecha_ini']) ?   ($_POST['fecha_ini']) : '0000-00-00 00:00:00';
            $fecha_final        = isset($_POST['fecha_fin']) ?   ($_POST['fecha_fin']) : '0000-00-00 00:00:00';
            $prioridad          = isset($_POST['prioridad']) ?   ($_POST['prioridad']) : '1';
            $color              = isset($_POST['color']) ? $_POST['color'] : '#3374FF';
            $estado_curso       = isset($_POST['tipo_extra']) ? $_POST['tipo_extra'] : 'N';
            $especifico         = (isset($_POST['especifico'])) ? $_POST['especifico'] : array();
            $id_personastr      = isset($_POST['id_personas_array']) ? $_POST['id_personas_array'] : ",,";

           
            $nombre_archivo = isset($_FILES['file']['name']) ? ($_FILES['file']['name']) : false;
            if ($nombre_archivo && $nombre_archivo != '') {
                $tipo_archivo = $_FILES['file']['type'];
                $tamano_archivo = $_FILES['file']['size'];
                //ya 
                if ($tamano_archivo > 10000000) {
                    echo 5; //el tipo de archivo no es permitido intente con un word o pdf                    
                    exit();
                } else {
                    if ($nombre_archivo != '') {
                        //se borra el archivo del servidor para poner el nuevo
                        if ($id_comunicado > 0) {
                            $bucarfile = $db->from('ins_comunicados')->where('id_comunicado', $id_comunicado)->fetch_first();
                            $file = $bucarfile["file"];
                            $delete_dir = "files/" . $nombre_dominio . "/comunicados/" . $file;
                            //var_dump($delete_dir);exit();
                            try {
                                if (is_file($delete_dir)) {
                                    unlink($delete_dir);
                                }
                            } catch (Exception $e) {
                                echo 'el archivo cambio de ubicacion.';
                            }
                        }

                        $output_dir = "files/" . $nombre_dominio . "/comunicados/";
                        $imagen = $grupo . "_" . date('dmY_His') . '.' . pathinfo($nombre_archivo, PATHINFO_EXTENSION);
                        if (!move_uploaded_file($_FILES['file']["tmp_name"], $output_dir . $imagen)) {
                            $msg = 'No pudo subir el archivo';
                        }
                    }
                }
            } else {
                $imagen = NULL;
            }

            //:::::::::::::::::::::::::::::::: INICIO DE INSERSION O UPDATE  :::::::::::::::::::::::::::::
            $estado = false;
            $estresp = 0;
            // Verifica si es creacion o modificacion
            if ($id_comunicado > 0) {
                //:::::::::: UPDATE ::::::::::::::
                if (!$nombre_archivo || $nombre_archivo == '') {
                    //array sin imagen
                    $regImag = array();
                } else {
                    //array con imagen
                    $regImag = array(
                        'file' => $imagen
                    );
                }
                $comunicados = array(
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_final' => $fecha_final,
                    'nombre_evento' => $nombre_evento,
                    'descripcion' => $descripcion_evento,
                    'color' => $color,
                    'prioridad' => $prioridad,
                    'persona_id' =>  $id_personastr ,
                    'vista_personas_id' => ',',
                    //'aula_paralelo_asignacion_materia_id' => $aula_asig_mat_id,
                    //'modo_calificacion_id' => $modo_id,
                    //'grupo' => $grupo 
                    'usuario_modificacion' => $_user['id_user'],
                    'fecha_modificacion' => Date('Y-m-d H:i:s')
                );
                $instacia_union = array_merge_recursive($comunicados, $regImag); //une las dos instancias


                // Modifica el comunidados
                $db->where('id_comunicado', $id_comunicado)->update('ins_comunicados', $instacia_union);
                // Guarda el proceso
                $db->insert('sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso' => date('H:i:s'),
                    'proceso' => 'u',
                    'nivel' => 'l',
                    'detalle' => 'Se modificó el comunidados con identificador número ' . $id_comunicado . '.',
                    'direccion' => $_location,
                    'usuario_id' => $_user['id_user']
                ));
               
                $estado = true;
                $estresp = 1;
            } else {

                //:::::::::::CREAR::::::::::::::
                //if($nombre_archivo){
                $comunicados = array(
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_final' => $fecha_final,
                    'nombre_evento' => $nombre_evento,
                    'descripcion' => $descripcion_evento,
                    'color' => $color,
                    'file' => $imagen,
                    'prioridad' => $prioridad,
                    'asignacion_docente_id' => $asignacion_docente_id, //id_aula_asig_materia
                    'estado_curso' => $estado_curso, //new
                    'modo_calificacion_id' => $modo_id,
                    'grupo' => $grupo
                );
                //}else{

                //}
                //busca el ultimo registro para el codigo de comunicado 
                //::::::::::::::::::::::::::::::::  CREA CODIGO :::::::::::::::::::::::::::::
                $codigo_mayor = $db->query("SELECT MAX(id_comunicado) as id_comunicado FROM ins_comunicados")->fetch_first();
                $id_anterior = $codigo_mayor['id_comunicado']; //id_comunicado mayor
                if (is_null($id_anterior)) {
                    $nuevo_codigo = "C-1";
                } else {
                    //recupera los datos del ultimo registro
                    $comunicado_mayor = $db->query("SELECT id_comunicado, codigo, nombre_evento FROM ins_comunicados WHERE id_comunicado = $id_anterior ")->fetch_first();
                    $codigo_anterior = $comunicado_mayor['codigo']; //codigo anterior
                    $separado = explode('-', $codigo_anterior);
                    $nuevo_codigo = "C-" . ($separado[1] + 1);
                }
                
                $nuevo_registro = array(
                    'codigo' => $nuevo_codigo,
                    'usuarios' => ',',
                    'estados' => '',
                    'persona_id' => $id_personastr,
                    'vista_personas_id' => ',',
                    'estado' => 'A',
                    'usuario_registro' => $id_user,
                    'fecha_registro' => Date('Y-m-d H:i:s'),
                    'usuario_modificacion' => $id_user,
                    'fecha_modificacion' => Date('Y-m-d H:i:s')
                );

                $instacia_union = array_merge_recursive($comunicados, $nuevo_registro); //une las dos instancias
                //var_dump($instacia_union);exit();
                // Crea el comunidados
                $id_comunidado = $db->insert('ins_comunicados', $instacia_union);

                // Guarda el proceso
                $id_comunicado = $db->insert('sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso' => date('H:i:s'),
                    'proceso' => 'c',
                    'nivel' => 'l',
                    'detalle' => 'Se creo comunidados con identificador numero ' . $id_comunidado . '.',
                    'direccion' => $_location,
                    'usuario_id' => $id_user
                ));
                $estado = true;
                $estresp = 1;

            }
        } else {
            echo 'deve enviar un titulo y fechaini obligagoriamente';
        }


        if ($estado) {
            $instacia_union['id_comunicado'] = $id_comunicado;
            echo json_encode(array('estado' => 's'));
        } else {
            echo json_encode(array('estado' => 'n'));
        }
    } else {
        // Devuelve los resultados
        echo json_encode(array('estado' => 'n login'));
    }
} else {
    // Devuelve los resultados
    echo json_encode(array('estado' => 'n post'));
}
