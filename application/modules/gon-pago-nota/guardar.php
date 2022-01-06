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
		if (isset($_POST['estudiantes']) && isset($_POST['pensiones']) && isset($_POST['montos']) && isset($_POST['inscripciones']) ) { 
			//var_dump($_POST);exit();

			// Importa la libreria para el codigo de control
			require_once libraries . '/controlcode-class/ControlCode.php';
			require_once libraries . '/numbertoletter-class/NumberToLetterConverter.php';
			
			// Obtiene los datos del pago
		    $id_estudiante 	= $_POST['id_estudiante'];
	        $id_gestion 	= $_gestion['id_gestion']; 
	        $nit_ci			= trim($_POST['nit_ci']); 
		    $nombre_cliente = trim($_POST['nombre_cliente']);  
			$telefono 		= trim($_POST['telefono']);
			$direccion 		= trim($_POST['direccion']);
			$observacion 	= trim($_POST['observacion']); 
			$nro_registros 	= trim($_POST['nro_registros']); 
			$monto_total 	= trim($_POST['monto_total']);
			$almacen_id 	= trim($_POST['almacen_id']); 
			$factura_recibo	= trim($_POST['factura_recibo']);
			$fecha_emision	= trim($_POST['fecha_emision']);
            
            // Obtiene los datos del detalle de pago
            $inscripciones 	= (isset($_POST['inscripciones'])) ? $_POST['inscripciones'] : array();
	        $estudiantes   	= (isset($_POST['estudiantes'])) ? $_POST['estudiantes'] : array();
	        $pensiones 		= (isset($_POST['pensiones'])) ? $_POST['pensiones'] : array();
	        $montos 		= (isset($_POST['montos'])) ? $_POST['montos'] : array();
			
			// Obtiene la fecha de hoy 
			$hoy = date('Y-m-d'); 

			// Verifica el tipo de documento de pago: Factura o
			if($factura_recibo =='FACTURA'){

				// Obtiene la dosificacion del periodo actual
				$dosificacion = $db->from('inv_dosificaciones')->where('fecha_registro <=', $hoy)->where('fecha_limite >=', $hoy)->where('activo', 'S')->fetch_first();
				//var_dump($dosificacion);exit();

				// Verifica si la dosificación existe 
				if ($dosificacion) { 

					// Obtiene los datos para el codigo de control
					$nro_autorizacion = $dosificacion['nro_autorizacion'];
					$nro_factura = intval($dosificacion['nro_facturas']) + 1;
					$nit_ci = $nit_ci;
					$fecha = date('Ymd');
					$total = round($monto_total, 0);
					$llave_dosificacion = base64_decode($dosificacion['llave_dosificacion']);
					//var_dump($llave_dosificacion);exit();

					// Genera el codigo de control
					$codigo_control = new ControlCode();
					$codigo_control = $codigo_control->generate($nro_autorizacion, $nro_factura, $nit_ci, $fecha, $total, $llave_dosificacion);
 

					//var_dump($codigo_control);exit();
					// Define la variable de subtotales
					$subtotales = array();

					// Obtiene la moneda
					$moneda = $db->from('inv_monedas')->where('oficial', 'S')->fetch_first();
					$moneda = ($moneda) ? $moneda['moneda'] : '';

					// Obtiene los datos del monto total
					$conversor = new NumberToLetterConverter();
					$monto_textual = explode('.', $monto_total);
					$monto_numeral = $monto_textual[0];
					$monto_decimal = $monto_textual[1];
					$monto_literal = ucfirst(strtolower(trim($conversor->to_word($monto_numeral))));

					// Instancia el pago
					$pago_factura = array(
						'fecha_general'    => date_encode($fecha_emision),
						'hora_general'     => date('H:i:s'),
						'tipo'             => 'COBRO',
						'provisionado'     => 'N',
						'descripcion'      => 'Pago de conceptos',
						'nro_factura'      => $nro_factura,
						'nro_autorizacion' => $nro_autorizacion,
						'codigo_control'   => $codigo_control,
						'fecha_limite'     => $dosificacion['fecha_limite'],
						'monto_total'      => $monto_total,
						'acuenta_total'    => 0,
						'tipo_pago'        => 'EFECTIVO',
						'nit_ci'           => $nit_ci,
						'nombre_cliente'   => mb_strtoupper($nombre_cliente, 'UTF-8'),
				        'telefono'         => $telefono,
						'direccion'        => $direccion,
						'observacion'      => $observacion,
						'nro_registros'    => $nro_registros,
						'dosificacion_id'  => $dosificacion['id_dosificacion'],
						'almacen_id'       => $almacen_id,
						'usuario_registro' => $_user['id_user'],
						'fecha_registro'   => date('Y-m-d H:i:s'),
						'estudiante_id'    => $id_estudiante,
						'documento_pago'   => 'FACTURA',
					    'gestion_id'       => 1,
					);
	                
					// Guarda la informacion del pago
					$general_id = $db->insert('pen_pensiones_estudiante_general', $pago_factura);
	                
	                // Valida si el pago para registrar el detalle de pago
					if($general_id){

						// Recorre los productos
						foreach ($pensiones as $nro => $elemento) {

							// Instancia del detalle de pago
							$detalle = array(
								'pensiones_estudiante_id' => $pensiones[$nro],
								'monto'                   => $montos[$nro],
								'general_id'              => $general_id,
								'descuento'               => 0
							);

							// Guarda la informacion
							$id_detalle = $db->insert('pen_pensiones_estudiante_detalle', $detalle);

							if($id_detalle){

								// Instancia del detalle de adelanto
								$adelanto = array(
									'estado_adelanto'       => 'CANCELADO',
							        'usuario_modificacion' => $_user['id_user'],
				                    'fecha_modificacion'   => date('Y-m-d H:i:s')
								);

								// Guarda la informacion
								$db->where('pensiones_estudiante_id', $pensiones[$nro])->update('pen_adelantos_estudiante_detalle', $adelanto);

							}
						}

						// Actualiza la informacion
						$db->where('id_dosificacion', $dosificacion['id_dosificacion'])->update('inv_dosificaciones', array('nro_facturas' => $nro_factura));
						
	                }

					// Envia respuesta
					echo json_encode($general_id);

				} else {

					// Envia respuesta
					echo 'error';
				}

            // Verifica el tipo de documento de pago: Recibo
			}else if($factura_recibo =='RECIBO'){
                
                // Obtiene el numero correlativo de la factura
				$nro_factura = $db->query("SELECT COUNT(id_general) + 1 AS nro_factura FROM pen_pensiones_estudiante_general WHERE documento_pago = 'NOTA' AND provisionado = 'S'")->fetch_first();
	            $nro_factura = $nro_factura['nro_factura'];

				// Define la variable de subtotales
				$subtotales = array();

				// Obtiene la moneda
				$moneda = $db->from('inv_monedas')->where('oficial', 'S')->fetch_first();
				$moneda = ($moneda) ? $moneda['moneda'] : '';

				// Obtiene los datos del monto total
				$conversor = new NumberToLetterConverter();
				$monto_textual = explode('.', $monto_total);
				$monto_numeral = $monto_textual[0];
				$monto_decimal = $monto_textual[1];
				$monto_literal = ucfirst(strtolower(trim($conversor->to_word($monto_numeral))));

				// Instancia el pago
				$pago_factura = array(
					'fecha_general'    => date_encode($fecha_emision),
					'hora_general'     => date('H:i:s'),
					'tipo'             => 'COBRO',
					'provisionado'     => 'S',
					'descripcion'      => 'Pago de pensiones con recibo',
					'nro_factura'      => $nro_factura,
					'nro_autorizacion' => '',
					'codigo_control'   => '',
					'fecha_limite'     => '0000-00-00',
					'monto_total'      => $monto_total,
					'acuenta_total'    => 0,
					'tipo_pago'        => 'EFECTIVO',
					'nit_ci'           => $nit_ci,
					'nombre_cliente'   => mb_strtoupper($nombre_cliente, 'UTF-8'),
				    'telefono'         => $telefono,
					'direccion'        => $direccion,
					'observacion'      => $observacion,
					'nro_registros'    => $nro_registros,
					'dosificacion_id'  => 0,
					'almacen_id'       => $almacen_id,
					'usuario_registro' => $_user['id_user'],
					'fecha_registro'   => date('Y-m-d H:i:s'),
					'estudiante_id'    => $id_estudiante,
					'documento_pago'   => 'NOTA',
					'gestion_id'       => 1,
				);

				// Guarda la informacion del pago
				$general_id = $db->insert('pen_pensiones_estudiante_general', $pago_factura);

                // Valida si el pago para registrar el detalle de pago
				if($general_id){

					// Recorre los productos
					foreach ($pensiones as $nro => $elemento) {

						// Instancia del detalle de pago
						$detalle = array(
							'pensiones_estudiante_id' => $pensiones[$nro],
							'monto'                   => $montos[$nro],
							'general_id'              => $general_id,
							'descuento'               => 0,
						);

						// Guarda la informacion
						$id_detalle = $db->insert('pen_pensiones_estudiante_detalle', $detalle);

						if($id_detalle){

							// Instancia del detalle de adelanto
							$adelanto = array(
								'estado_adelanto'       => 'CANCELADO',
						        'usuario_modificacion' => $_user['id_user'],
			                    'fecha_modificacion'   => date('Y-m-d H:i:s')
							);

							// Guarda la informacion
							$db->where('pensiones_estudiante_id', $pensiones[$nro])->update('pen_adelantos_estudiante_detalle', $adelanto);

						}

					}
                }

				// Envia respuesta
				echo json_encode($general_id);
		    }

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