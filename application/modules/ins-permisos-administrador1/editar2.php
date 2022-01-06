<?php
/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */
// Verifica la peticion post
//if (is_post()) {

    if (isset($_POST['id']) && isset($_POST['txt']) ) {
        $id_contrato = (isset($_POST['id'])) ? clear($_POST['id']) : 0;
        $txt = $_POST['txt'];
        
        // Instancia el gondolas
        if($id_contrato > 0){
            $res = array(
                'documento' => $txt
            );
            $db->where('id_contrato', $id_contrato)->update('rrhh_contrato', $res);

            redirect('?/rrhh-contratos/listar-documento/' . $id_contrato);

        }
        else{
        }
    } else {
        // Error 400
        require_once bad_request();
        exit;    
    }

/*} else {
    // Error 404
    require_once not_found();
    exit;
}*/

?>