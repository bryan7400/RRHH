<?php

/**
 * SimplePHP - Simple Framework PHP
 * 
 * @package  SimplePHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Obtiene el id_movimiento
$id_movimiento = (sizeof($params) > 0) ? $params[0] : 0;

// Obtiene el egreso
$egreso = $db->from('caj_movimientos')->where('id_movimiento', $id_movimiento)->fetch_first();

// Verifica si el egreso existe
if ($egreso) {
	// Elimina el egreso
	$db->delete()->from('caj_movimientos')->where('id_movimiento', $id_movimiento)->limit(1)->execute();

	// Verifica si fue el egreso eliminado
	if ($db->affected_rows) {
		// Instancia variable de notificacion
		set_notification('success','Eliminación satisfactoria!','El registro fue eliminado correctamente.');
	}

	// Redirecciona a la pagina principal
	redirect('?/movimientos/egresos_listar');
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>