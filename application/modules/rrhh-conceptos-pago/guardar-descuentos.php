<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

//var_dump($_POST);die;

// Verifica la peticion post
if (is_post()) {//
	// Verifica la cadena csrf
	///if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
		if (
			(isset($_POST['nombre_concepto_descuento']) && isset($_POST['porcentaje_descuentox']) && isset($_POST['monto_descuentox']) )
		) {
			// Obtiene los datos
			$id_concepto_pago 		= (isset($_POST['id_concepto_descuento'])) ? clear($_POST['id_concepto_descuento']) : 0;
			$nombre_concepto_pago 	= (isset($_POST['nombre_concepto_descuento'])) ? clear($_POST['nombre_concepto_descuento']) : 0;
			$descripcion 			= (isset($_POST['descripcion_descuento'])) ? clear($_POST['descripcion_descuento']) : '';
			$porcentaje 			= (isset($_POST['porcentaje_descuentox'])) ? clear($_POST['porcentaje_descuentox']) : 0;
			$mes 					= (isset($_POST['mes_descuento'])) ? clear($_POST['mes_descuento']) : 0;
			$monto 					= (isset($_POST['monto_descuentox'])) ? clear($_POST['monto_descuentox']) : 0;
			$tipo 					= (isset($_POST['tipo_descuento'])) ? clear($_POST['tipo_descuento']) : 0;
			$tipo_fijo_porcentaje	= (isset($_POST['tipo_fijo_porcentaje_descuento'])) ? clear($_POST['tipo_fijo_porcentaje_descuento']) : 0;
            $id_gestion 			= $_gestion['id_gestion'];
			
			// Instancia el concepto_pago
			$concepto_pago = array(
				'nombre_concepto_pago' 	=> $nombre_concepto_pago,
				'mes' 					=> $mes,
				'porcentaje' 			=> $porcentaje,
				'monto' 				=> $monto,
				'estado' 				=> "A",
				
			 	'usuario_registro' 	 	=> $_user['id_user'],
			 	'fecha_registro' 	 	=> date('Y-m-d'),
			 	'usuario_modificacion' 	=> $_user['id_user'],
			 	'fecha_modificacion' 	=> date('Y-m-d'),
			
				'descripcion' 			=> $descripcion,
				'tipo' 					=> $tipo,
				'fijo_porcentaje'		=> $tipo_fijo_porcentaje,
				'gestion_id' 			=> $id_gestion
			);
			
			// Verifica si es creacion o modificacion
			if ($id_concepto_pago > 0) {
				// Modifica el concepto_pago
				$db->where('id_concepto_pago', $id_concepto_pago)->update('rhh_concepto_pago', $concepto_pago);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modific?? el concepto pago con identificador n??mero ' . $id_concepto_pago . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Modificaci??n exitosa!', 'El registro se modific?? satisfactoriamente.');
				
				// Redirecciona la pagina
				//redirect('?/rrhh-conceptos-pago/ver/' . $id_concepto_pago);
				echo 1;
			} else {
				// Crea el concepto_pago
				$id_concepto_pago = $db->insert('rhh_concepto_pago', $concepto_pago);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se cre?? el concepto pago con identificador n??mero ' . $id_concepto_pago . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Creaci??n exitosa!', 'El registro se cre?? satisfactoriamente.');
				
				// Redirecciona la pagina
				//redirect('?/rrhh-conceptos-pago/listar');
				echo 2;
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	/*} else {
		// Redirecciona la pagina
		//redirect('?/rrhh-concepto-pago/listar');
		echo 3;
	}*/
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>