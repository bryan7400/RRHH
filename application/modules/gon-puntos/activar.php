<?php

/**
 * FunctionPHP - Framework Functional PHP
 *
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */
//var_dump($_POST);exit();
// Verifica la peticion post
if (is_post()) {
    // Verifica la cadena csrf
    if (true) {
        // Verifica la existencia de datos
        if (isset($_POST['alum']) && isset($_POST['punto'])) {
            // Obtiene los datos

            $alum = clear($_POST['alum']);
            $punto = clear($_POST['punto']);

                // Crea la asignacion

                $db->where('id_inscripcion',$alum)->update('ins_inscripcion', array('punto_id' => $punto));

                // Redirecciona la pagina
                echo json_encode(array('estado' => 's'));

        } else {
            // Error 400
            require_once bad_request();
            exit;
        }
    } else {
        // Redirecciona la pagina
        echo json_encode(array('estado' => 'n'));
//		redirect('?/puntos/listar');
    }
} else {
    // Error 404
    require_once not_found();
    exit;
}

?>