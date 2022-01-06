<?php
/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */
// Verifica la peticion post
if (is_post()) {
    // Verifica la cadena csrf
    //if (isset($_POST[get_csrf()])) {
    if (true) {
        // Verifica la existencia de datos
        //if(isset($_POST['id_asignacion']) && isset($_POST['nombre']) && isset($_POST['ci']) && isset($_POST['cargo']) ){
        if(true){
            // Obtiene los datos
            
            $id = $_POST['id_asignacion'];
            $cargo = clear($_POST['cargo']);
            $ci = $_POST['ci'];
            $nombre = clear($_POST['nombre']);
            
            $res = array(
                'asignacion_id' => $id,
                'nombre' => $nombre,
                'cargo' => $cargo,
                'ci' => $ci
            );
            
            $id_gondola = $db->insert('rrhh_firma_contrato', $res);
    


            echo "1";



        } else {
            // Error 400
            require_once bad_request();
            exit;    
        }
    } else {
        // Redirecciona la pagina
        redirect('?/rrhh-firma-contratos/listar');
    }
} else {
    // Error 404
    require_once not_found();
    exit;
}
?>