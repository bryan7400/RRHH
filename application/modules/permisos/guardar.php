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
		if (isset($_POST['id_rol'])) {
			// Obtiene los datos del rol
			$id_rol = $_POST['id_rol'];
			$estados = (isset($_POST['estados']) ? $_POST['estados'] : array());
			$archivos = (isset($_POST['archivos']) ? $_POST['archivos'] : array());

			// Elimina todos los permisos del rol
			$db->delete()->from('sys_permisos')->where('rol_id', $id_rol)->execute();

			// Recorre todos los estados marcados
			foreach ($estados as $indice => $estado) {
				// Instancia el permiso
				$permiso = array(
					'rol_id' => clear($id_rol),
					'menu_id' => clear($estados[$indice]),
					'archivos' => clear($archivos[$indice])
				);
				
				// Guarda el permiso
				$db->insert('sys_permisos', $permiso);
			}

			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso' => date('H:i:s'),
				'proceso' => 'u',
				'nivel' => 'h',
				'detalle' => 'Se modificó los permisos del rol con identificador número ' . $id_rol . '.',
				'direccion' => $_location,
				'usuario_id' => $_user['id_user']
			));

			// Crea la notificacion
			set_notification('success', 'Modificación exitosa!', 'Los permisos asignados al rol se modificaron satisfactoriamente.');

			// Redirecciona la pagina
			redirect('?/permisos/listar');
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	} else {
		// Redirecciona la pagina
		redirect('?/permisos/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>