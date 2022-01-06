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
		// Obtiene el nombre del imagen
		$logotipo = $db->from('sys_instituciones')->where('id_institucion', $_institution['id_institucion'])->fetch_first();
		$logotipo = $logotipo['logotipo'];

		// Verifica si existe el antiguo logo
		if ($logotipo != '') {
			// Elimina el logo
			file_delete(files . '/logos/' . $logotipo);
		}

		// Modifica la institucion
		$db->where('id_institucion', $_institution['id_institucion'])->update('sys_instituciones', array('logotipo' => ''));

		// Guarda el proceso
		$db->insert('sys_procesos', array(
			'fecha_proceso' => date('Y-m-d'),
			'hora_proceso' => date('H:i:s'),
			'proceso' => 'u',
			'nivel' => 'm',
			'detalle' => 'Se modificó el logotipo de la institución.',
			'direccion' => $_location,
			'usuario_id' => $_user['id_user']
		));

		// Crea la notificacion
		set_notification('success', 'Modificación exitosa!', 'El logotipo de la intitución se modificó satisfactoriamente.');

		// Redirecciona la pagina
		redirect('?/configuracion/principal');
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