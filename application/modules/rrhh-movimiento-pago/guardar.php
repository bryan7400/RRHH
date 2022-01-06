<?php  

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

//var_dump($_POST);die;
 
// Verifica la peticion post
if (is_post()) {

	// Verifica la cadena csrf
	///if (isset($_POST[get_csrf()])) {

		// Verifica la existencia de datos
		if (isset($_POST['id_asignacion']) && isset($_POST['id_concepto']) && isset($_POST['mes']) && isset($_POST['gestion']) && isset($_POST['monto'])&& isset($_POST['fecha_pago'])) {

			// Obtiene los datos
			$id_movimiento 		= (isset($_POST['id_movimiento'])) ? clear($_POST['id_movimiento']) : 0;
			$asignacion_id 		= (isset($_POST['id_asignacion'])) ? clear($_POST['id_asignacion']) : 0;
			$concepto_pago_id 	= (isset($_POST['id_concepto'])) ? clear($_POST['id_concepto']) :; 0
			$mes 				= (isset($_POST['mes'])) ? clear($_POST['mes']) : 0;
			$gestion 			= (isset($_POST['gestion'])) ? clear($_POST['gestion']) : 0;
			$monto 				= (isset($_POST['monto'])) ? clear($_POST['monto']) : 0;
			$fecha_pago 		= (isset($_POST['fecha_pago'])) ? clear($_POST['fecha_pago']) : 0;
			$observacion 		= (isset($_POST['observacion'])) ? clear($_POST['observacion']) : '';
			$impresion 			= $_institution['impresion'];
			$nro = 0;
			// Instancia el movimiento
			$movimiento = array(
				'asignacion_id'		=> $asignacion_id,
				'concepto_pago_id' 	=> $concepto_pago_id,
				'mes'				=> $mes,
				'gestion_id' 		=> 1,
				'monto' 			=> $monto,
				'fecha_pago' 		=> date_encode($fecha_pago),
				'observacion' 		=> $observacion,
				'usuario_registro' 	=> $_user['id_user'],
				'fecha_registro' 	=> date('Y-m-d H:i:s'),
				'usuario_modificacion' 	=> 0,
				'fecha_modificacion' 	=> '0000-00-00 00:00:00'
			);
			
			// Verifica si es creacion o modificacion
			if ($id_movimiento > 0) {

				// Modifica el movimiento
				$db->where('id_movimiento', $id_movimiento)->update('rhh_movimiento_pago', $movimiento);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el concepto pago con identificador número ' . $id_movimiento . '.',
					'nro' => $nro,
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
                
                // Respuesta de edición
				// echo 1;
			} else {

				// Crea el movimiento de pago
				$id_movimiento = $db->insert('rhh_movimiento_pago', $movimiento);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el concepto pago con identificador número ' . $id_movimiento . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
                if($impresion=="pdf"){

					echo json_encode($id_movimiento);

				}else if( $impresion=="termica"){		

					echo json_encode($respuesta);
		        }

				// Respuesta de cración
				//echo 2;
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