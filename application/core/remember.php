<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica si la variable de sesion existe
if (isset($_SESSION[user])){
	// Redirecciona al modulo index
	redirect(index_private);
} else {
	// Verifica si la cookie ha caducado
	if (isset($_COOKIE[remember])) {
		// Obtiene los parametros
		$remember = explode('|', $_COOKIE[remember]);
		$username = $remember[0];
		$password = $remember[1];
		$locale = $remember[2];

		//obtiene el año actual
		$anio_actual = Date('Y');

		//obtiene los datos de la gestion actual
		$gestion = $db->select('z.*')->from('ins_gestion z')->where('gestion', $anio_actual);

		// Obtiene los datos del usuario
		$user = $db->select('id_user, rol_id')->from('sys_users')->open_where()->where('md5(username)', $username)->or_where('md5(email)', $username)->close_where()->where(array('password' => $password, 'active' => 's'))->fetch_first();

		// Verifica la existencia del usuario
		if ($user) {
			// Instancia la variable de sesion con los datos del usuario
			$_SESSION[user] = $user;

			// Instancia la variable de sesion con la ubicacion
			$_SESSION[locale] = $locale;

			// Instancia la variable de sesion con el tiempo de inicio de sesion
			$_SESSION[time] = time();

			//Instancia la vatiable de sision son los datos de la gestion actual
			$_SESSION[gestion] = $gestion;

			// Redirecciona a la pagina principal
			redirect(index_private);
		}
	}
}

?>