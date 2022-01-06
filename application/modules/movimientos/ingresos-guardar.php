<?php

/**
 * SimplePHP - Simple Framework PHP
 * 
 * @package  SimplePHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica si es una peticion post
if (is_post()) {
	// Verifica la existencia de los datos enviados
	if (isset($_POST['id_movimiento']) && isset($_POST['fecha_movimiento']) && isset($_POST['hora_movimiento']) && isset($_POST['nro_comprobante']) && isset($_POST['concepto']) && isset($_POST['monto']) && isset($_POST['observacion'])) {

		// Obtiene las datos del egreso
		$id_movimiento = trim($_POST['id_movimiento']);
		$fecha_movimiento = trim($_POST['fecha_movimiento']);
		$hora_movimiento = trim($_POST['hora_movimiento']);
		$nro_comprobante = trim($_POST['nro_comprobante']);
		$concepto = trim($_POST['concepto']);
		$monto = trim($_POST['monto']);
		$observacion = trim($_POST['observacion']);
		$id_persona = $_user['persona_id'];

		// Obtiene el numero de comprobante
		$asignacion = $db->query("select a.id_asignacion from per_asignaciones a where a.persona_id = $id_persona")->fetch_first();
		
		// Instancia el egreso
		$egreso = array(
			'fecha_movimiento' => date_encode($fecha_movimiento),
			'hora_movimiento' => $hora_movimiento,
			'nro_comprobante' => $nro_comprobante,
			'tipo' => 'i',
			'concepto' => $concepto,
			'monto' => $monto,
			'observacion' => $observacion
		);
		
		// Verifica si es creacion o modificacion
		if ($id_movimiento > 0) {
			// Genera la condicion
			$condicion = array('id_movimiento' => $id_movimiento);
			
			// Actualiza la informacion
			$db->where($condicion)->update('caj_movimientos', $egreso);
			
			// Instancia la variable de notificacion
			set_notification('success','Actualización satisfactoria!','El registro se actualizó correctamente.');
		} else {
			// Inserta campo
			$egreso['asignacion_id'] = $asignacion['id_asignacion'];

			// Guarda la informacion
			$db->insert('caj_movimientos', $egreso);
			// Instancia la variable de notificacion
			set_notification('success','Adición satisfactoria!','El registro se guardó correctamente.');
		}
		
		// Redirecciona a la pagina principal
		redirect('?/movimientos/ingresos-listar');
	} else {
		// Error 401
		require_once bad_request();
		exit;
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>