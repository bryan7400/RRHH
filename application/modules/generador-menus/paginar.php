<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion post
if (is_post()) {
	// Verifica la existencia de datos
	if (isset($_POST['busquedas']) && isset($_POST['columna']) && isset($_POST['orden']) && isset($_POST['tamanio']) && isset($_POST['pagina'])) {
		// Obtiene las datos
		$busquedas = clear($_POST['busquedas']);
		$columna = clear($_POST['columna']);
		$orden = clear($_POST['orden']);
		$tamanio = clear($_POST['tamanio']); 
		$pagina = clear($_POST['pagina']);

		// Desglosa la busquedas
		$busquedas = explode(',', $busquedas); 

		// Valida la informacion
		$columna = (in_array($columna, array('sm.id_menu', 'sm.menu', 'sm.icono', 'sm.ruta', 'sm.modulo'))) ? $columna : 'sm.id_menu';

		// Valida la informacion
		$orden = ($orden == 'asc' || $orden == 'desc') ? $orden : 'asc'; 

		// Inicia la consulta
        $consulta = "from sys_menus sm "; 

		// Complementa la consulta
		$consulta = "select count(*) " . $consulta . " where";

		// Asigna las busquedas
		foreach ($busquedas as $busqueda) {
		    $consulta = $consulta . " sm.menu like '%" . $busqueda . "%' or ";
		    $consulta = $consulta . " sm.icono like '%" . $busqueda . "%' or ";
		    $consulta = $consulta . " sm.ruta like '%" . $busqueda . "%' or ";
		    $consulta = $consulta . " sm.modulo like '%" . $busqueda . "%' or ";
		}

		// Limpia la cadena
		$consulta = substr($consulta, 0, -4);

		// Obtiene el numero total de registros
		$registros = $db->query($consulta)->fetch_first();
		$registros = intval(array_pop($registros));

		// Obtiene el tamanio
		$tamanio = ($tamanio > 0) ? $tamanio : 10;

		// Obtiene el numero de paginas
		$paginas = intval(ceil($registros / $tamanio));

		// Obtiene la pagina actual
		$pagina = ($pagina > 0) ? (($pagina > $paginas) ? $paginas : $pagina) : 1;

		// Obtiene el inicio de la paginacion
		$inicio = ($pagina - 1) * $tamanio;
		$inicio = ($inicio > 0) ? $inicio : 0;

		// Crea la variable par los datos
		set_variable(back(), array(
			'busquedas' => implode(',', $busquedas),
			'columna' => $columna,
			'orden' => $orden,
			'registros' => $registros,
			'tamanio' => intval($tamanio),
			'paginas' => $paginas,
			'pagina' => intval($pagina),
			'inicio' => $inicio
		));

		// Redirecciona la pagina
		redirect('?/generador-menus/principal');
	} else {
		// Error 400
		require_once bad_request();
		exit;
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>