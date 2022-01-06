<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  WEB SERVICES
 * @author  EDUCHECK MARIBEL MARCO LUIS
 */

// Define las cabeceras
header('Content-Type: application/json');

// Verifica la peticion post
if (is_post()) {
	// Verifica la existencia de datos
	$sql_institucion    ="SELECT *
						FROM sys_instituciones";
	$res_institucion = $db->query($sql_institucion)->fetch_first();


	//$respuesta = array ("estado" =>"s","usuario"=>$usuario);
	// Devuelve los resultados
	echo json_encode($res_institucion);
	
} else {
	// Devuelve los resultados
	echo json_encode(array('estado' => 'n'));
}

?>