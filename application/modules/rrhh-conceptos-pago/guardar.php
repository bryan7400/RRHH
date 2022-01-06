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
			(isset($_POST['nombre_concepto_pago']) && isset($_POST['porcentajex']) && isset($_POST['montox']) )
		) {
			// Obtiene los datos
			$id_concepto_pago 		= (isset($_POST['id_concepto_pago'])) ? clear($_POST['id_concepto_pago']) : 0;
			$nombre_concepto_pago 	= (isset($_POST['nombre_concepto_pago'])) ? clear($_POST['nombre_concepto_pago']) : 0;
			$descripcion 			= (isset($_POST['descripcion'])) ? clear($_POST['descripcion']) : '';
			$porcentaje 			= (isset($_POST['porcentajex'])) ? clear($_POST['porcentajex']) : 0;
			$mes 					= (isset($_POST['mes'])) ? clear($_POST['mes']) : 0;
			$monto 					= (isset($_POST['montox'])) ? clear($_POST['montox']) : 0;
			$tipo 					= (isset($_POST['tipo'])) ? clear($_POST['tipo']) : 0;
			$tipo_fijo_porcentaje	= (isset($_POST['tipo_fijo_porcentaje'])) ? clear($_POST['tipo_fijo_porcentaje']) : 0;
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
					'detalle' => 'Se modificó el concepto pago con identificador número ' . $id_concepto_pago . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
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
					'detalle' => 'Se creó el concepto pago con identificador número ' . $id_concepto_pago . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
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