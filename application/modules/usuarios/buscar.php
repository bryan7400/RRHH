<?php   

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion ajax
if (is_ajax()) {

	    // Verifica la existencia de datos
        $id = (isset($_POST['id'])) ? clear($_POST['id']) : 0;

        // Obtiene los formatos  
        $consulta="SELECT ifnull(count(u.persona_id),0) contador
				FROM sys_users u
				WHERE u.persona_id=$id";
		$val=$db->query($consulta)->fetch_first();
    
	    //$resultado = $db->query($consulta)->fetch_first(); 
	    if($val['contador'] == 0){
	    	$res = 0;
             echo json_encode($res);
	    }else{
	    	$res =1;
	    	echo json_encode($res);
	    }
        

} else {
	// Error 404 
	require_once not_found(); 
	exit;
}

?>