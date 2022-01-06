 <?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com> 
 */

// Verifica la peticion post
if (is_post()) { 
	//var_dump($_POST);exit();
	// Verifica la cadena csrf
	// if (isset($_POST[get_csrf()])) { 
		// Verifica la existencia de datos
		if (isset($_POST['id_tutor']) && isset($_POST['estudiantes']) && isset($_POST['pensiones']) && isset($_POST['montos']) && isset($_POST['inscripciones']) ) { 

			// Importa la libreria para el codigo de control
			require_once libraries . '/controlcode-class/ControlCode.php';
			require_once libraries . '/numbertoletter-class/NumberToLetterConverter.php';
			
			// Obtiene los datos
		    $familiar_id = $_POST['id_tutor'];
	        $id_gestion = $_gestion['id_gestion']; 
	        $nit_ci = trim($_POST['nit_ci']); 
		    $nombre_cliente = trim($_POST['nombre_cliente']); 
			$nro_registros = trim($_POST['nro_registros']);
			$monto_total = trim($_POST['monto_total']);
			$almacen_id = trim($_POST['almacen_id']); 
			$tipo_pago = trim($_POST['tipo_pago']); 
            $inscripciones = (isset($_POST['inscripciones'])) ? $_POST['inscripciones'] : array();
	        $estudiantes = (isset($_POST['estudiantes'])) ? $_POST['estudiantes'] : array();
	        $pensiones = (isset($_POST['pensiones'])) ? $_POST['pensiones'] : array();
	        $montos = (isset($_POST['montos'])) ? $_POST['montos'] : array();

			// Obtiene la fecha de hoy
			$hoy = date('Y-m-d');

			// Obtiene la dosificacion del periodo actual
			$dosificacion = $db->from('inv_dosificaciones')->where('fecha_registro <=', $hoy)->where('fecha_limite >=', $hoy)->where('activo', 'S')->fetch_first();

			// Verifica si la dosificación existe
			if ($dosificacion) { 

				// Obtiene los datos para el codigo de control
				$nro_autorizacion = $dosificacion['nro_autorizacion'];
				$nro_factura = intval($dosificacion['nro_facturas']) + 1;
				$nit_ci = $nit_ci;
				$fecha = date('Ymd');
				$total = round($monto_total, 0);
				$llave_dosificacion = base64_decode($dosificacion['llave_dosificacion']);

				// Genera el codigo de control
				$codigo_control = new ControlCode();
				$codigo_control = $codigo_control->generate($nro_autorizacion, $nro_factura, $nit_ci, $fecha, $total, $llave_dosificacion);

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
					'fecha_general' => date('Y-m-d'),
					'hora_general' => date('H:i:s'),
					'tipo' => 'Venta',
					'provisionado' => 'N',
					'descripcion' => 'Venta de productos',
					'nro_factura' => $nro_factura,
					'nro_autorizacion' => $nro_autorizacion,
					'codigo_control' => $codigo_control,
					'fecha_limite' => $dosificacion['fecha_limite'],
					'monto_total' => $monto_total,
					'acuenta_total' => 0,
					'tipo_pago' => 'CONTADO',
					// 'descuento_porcentaje' => $descuento_porcentaje,
					// 'descuento_bs' => $descuento_bs,
					// 'monto_total_descuento' => $total_importe_descuento,
					'nit_ci' => $nit_ci,
					'nombre_cliente' => mb_strtoupper($nombre_cliente, 'UTF-8'),
					'nro_registros' => $nro_registros,
					'dosificacion_id' => $dosificacion['id_dosificacion'],
					'almacen_id' => $almacen_id,
					'empleado_id' => $_user['id_user'],
					'familiar_id' => $familiar_id
				);

				// Guarda la informacion
				$general_id = $db->insert('pen_pensiones_estudiante_general', $pago_factura);

				// Recorre los productos
				foreach ($pensiones as $nro => $elemento) {

					// Forma el detalle para pen_pensiones_estudiante
					$pago = array(
						'pension_id' => $pensiones[$nro],
						'inscripcion_id' => $inscripciones[$nro],
						'cancelado' => 'SI',
						'fecha_cancelado' =>  date('Y-m-d')
					);
					// Guarda la informacion
					$pensiones_estudiante_id = $db->insert('pen_pensiones_estudiante', $pago);

					if($pensiones_estudiante_id){

						// Forma el detalle
						$detalle = array(
							'monto' => $montos[$nro],
							'descuento' => 0,
							'tipo_pago' => $tipo_pago,
							'general_id' => $general_id,
							'pensiones_estudiante_id' => $pensiones_estudiante_id,
							'usuario_registro' => $_user['id_user'],
							'fecha_registro' => date('Y-m-d H:i:s')
						);

						// Genera los subtotales
						// $subtotales[$nro] = number_format($precios[$nro] * $cantidades[$nro], 2, '.', '');

						// Guarda la informacion
						$db->insert('pen_pensiones_estudiante_detalle', $detalle);

					}
				}
			
				// Actualiza la informacion
				$db->where('id_dosificacion', $dosificacion['id_dosificacion'])->update('inv_dosificaciones', array('nro_facturas' => $nro_factura));
				
				// Instancia la respuesta
				/*$respuesta = array(
					'papel_ancho' => 10,
					'papel_alto' => 30,
					'papel_limite' => 576,
					'empresa_nombre' => $_institution['nombre'],
					'empresa_sucursal' => 'SUCURSAL Nº 1',
					'empresa_direccion' => $_institution['direccion'],
					'empresa_telefono' => 'TELÉFONO ' . $_institution['telefono'],
					'empresa_ciudad' => 'LA PAZ - BOLIVIA',
					'empresa_actividad' => $_institution['razon_social'],
					'empresa_nit' => $_institution['nit'],
					'factura_titulo' => 'F  A  C  T  U  R  A',
					'factura_numero' => $venta['nro_factura'],
					'factura_autorizacion' => $venta['nro_autorizacion'],
					'factura_fecha' => date_decode($venta['fecha_egreso'], 'd/m/Y'),
					'factura_hora' => substr($venta['hora_egreso'], 0, 5),
					'factura_codigo' => $venta['codigo_control'],
					'factura_limite' => date_decode($venta['fecha_limite'], 'd/m/Y'),
					'factura_autenticidad' => '"ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS. EL USO ILÍCITO DE ÉSTA SERÁ SANCIONADO DE ACUERDO A LEY"',
					'factura_leyenda' => 'Ley Nº 453: "' . $dosificacion['leyenda'] . '".',
					'cliente_nit' => $venta['nit_ci'],
					'cliente_nombre' => $venta['nombre_cliente'],
					'venta_titulos' => array('CANTIDAD', 'DETALLE', 'P. UNIT.', 'SUBTOTAL', 'TOTAL'),
					'venta_cantidades' => $cantidades,
					'venta_detalles' => $nombres,
					'venta_precios' => $precios,
					'venta_subtotales' => $subtotales,
					'venta_total_numeral' => $venta['monto_total'],
					'venta_total_literal' => $monto_literal,
					'venta_total_decimal' => $monto_decimal . '/100',
					'venta_moneda' => $moneda,
					'importe_base' => '0',
					'importe_ice' => '0',
					'importe_venta' => '0',
					'importe_credito' => '0',
					'importe_descuento' => '0',
					'impresora' => $_terminal['impresora']
				);*/

				// Envia respuesta
				echo json_encode($general_id);
			} else {
				// Envia respuesta
				echo 'error';
			}
	
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	// } else {
	// Redirecciona la pagina
	// redirect('?/s-pago-pensiones/pagar');
	// }
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>