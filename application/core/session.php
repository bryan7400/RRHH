<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica si la variable de sesion existe
if (!isset($_SESSION[user])){
	// Redirecciona al modulo index
	redirect(index_public);
} else {
	// Obtiene el tiempo actual de la sesion
	$tiempo_actual = time();

	// Obtiene el tiempo maximo de la sesion
	$tiempo_maximo = $_SESSION[time] + (60 * 60 * timesession);

	// Verifica si la sesion ha caducado
	if ($tiempo_actual > $tiempo_maximo) {
		// Verifica si la cookie ha caducado
		if (isset($_COOKIE[remember])) {
			// Reinicia el tiempo de inicio de sesion
			$_SESSION[time] = time();
		} else {
		 	// Redirecciona al modulo index
			redirect('?/sitio/salir');
		}
	} else {
		// Reinicia el tiempo de inicio de sesion
		$_SESSION[time] = time();
	}
} 

?>