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
		if (isset($_POST['informacion']) && isset($_POST['formato']) && isset($_POST['reloj']) && isset($_POST['icono']) && isset($_POST['tema'])) {
			// Obtiene los datos de la institucion
			$id_institucion = clear($_institution['id_institucion']);
			$informacion = clear($_POST['informacion']);
			$formato = clear($_POST['formato']);
			$reloj = clear($_POST['reloj']);
			$icono = clear($_POST['icono']);
			$tema = clear($_POST['tema']);

			// Instancia la institucion
			$institucion = array(
				'informacion' => $informacion,
				'formato' => $formato,
				'reloj' => $reloj,
				'icono' => $icono,
				'tema' => $tema
			);

			// Modifica la institucion
			$db->where('id_institucion', $id_institucion)->update('sys_instituciones', $institucion);

			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso' => date('H:i:s'),
				'proceso' => 'u',
				'nivel' => 'h',
				'detalle' => 'Se modificó los datos de configuración del sistema.',
				'direccion' => $_location,
				'usuario_id' => $_user['id_user']
			));

			// Crea la notificacion
			set_notification('success', 'Modificación exitosa!', 'Los datos de configuración del sistema se modificaron satisfactoriamente.');

			// Redirecciona la pagina
			redirect('?/configuracion/principal');
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	} else {
		// Redirecciona la pagina
		redirect('?/configuracion/principal');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>