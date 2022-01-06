<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion post
if (is_post()) {
	// Verifica la cadena csrf
	if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
		if (isset($_POST['tipo_contrato_id']) && isset($_POST['horario']) && isset($_POST['cargo_id']) && isset($_POST['sueldo_base']) && isset($_POST['fecha_inicio']) && isset($_POST['fecha_final']) && isset($_POST['forma_pago']) && isset($_POST['entidad_financiera_id']) && isset($_POST['concepto_pago_id'])) {
			// Obtiene los datos
			$id_contrato = (isset($_POST['id_contrato'])) ? clear($_POST['id_contrato']) : 0;
			$tipo_contrato_id = clear($_POST['tipo_contrato_id']);
			$horario = clear($_POST['horario']);
			$cargo_id = clear($_POST['cargo_id']);
			$sueldo_base = clear($_POST['sueldo_base']);
			$fecha_inicio = clear($_POST['fecha_inicio']);
			$fecha_final = clear($_POST['fecha_final']);
			$forma_pago = clear($_POST['forma_pago']);
			$entidad_financiera_id = clear($_POST['entidad_financiera_id']);
			$concepto_pago_id = clear($_POST['concepto_pago_id']);
			
			// Instancia el contratos
			$contratos = array(
				'tipo_contrato_id' => $tipo_contrato_id,
				'horario' => $horario,
				'cargo_id' => $cargo_id,
				'sueldo_base' => $sueldo_base,
				'fecha_inicio' => date_encode($fecha_inicio),
				'fecha_final' => $fecha_final,
				'forma_pago' => $forma_pago,
				'entidad_financiera_id' => $entidad_financiera_id,
				'concepto_pago_id' => $concepto_pago_id
			);
			
			// Verifica si es creacion o modificacion
			if ($id_contrato > 0) {
				// Modifica el contratos
				$db->where('id_contrato', $id_contrato)->update('rhh_contratos', $contratos);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el contratos con identificador número ' . $id_contrato . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				redirect('?/contratos/ver/' . $id_contrato);
			} else {
				// Crea el contratos
				$id_contrato = $db->insert('rhh_contratos', $contratos);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el contratos con identificador número ' . $id_contrato . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
				redirect('?/contratos/listar');
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	} else {
		// Redirecciona la pagina
		redirect('?/contratos/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>