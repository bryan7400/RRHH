<?php
// Verifica la peticion post
if (is_post()) {
	
	// Obtiene los parametros
	$id_empleado = (isset($params[0])) ? $params[0] : 0;
	
	// Obtiene el nombre de la foto
	$nombre_foto = $db->from('sys_empleados')->where('id_empleado', $id_empleado)->fetch_first();
	$nombre_foto = $nombre_foto['foto'];

	// Verifica si la antigua foto existe
	if ($nombre_foto != '') {
		// Elimina la foto
		file_delete(files . '/empleados/' . $nombre_foto);

		// Elimina la foto
		file_delete(files . '/empleados/small__' . $nombre_foto);
	}

	// Elimina la foto del empleado
	$db->where('id_empleado', $id_empleado)->update('sys_empleados', array('foto' => ''));

	// Guarda el proceso
	$db->insert('sys_procesos', array(
		'fecha_proceso' => date('Y-m-d'),
		'hora_proceso'  => date('H:i:s'),
		'proceso'       => 'u',
		'nivel'         => 'l',
		'detalle'       => 'Se modificó la foto del empleado con identificador número ' . $id_empleado . '.',
		'direccion'     => $_location,
		'usuario_id'    => $_user['id_user']
	));

	$_SESSION[temporary] = array(
		'alert'   => 'success',
		'title'   => 'Modificación exitosa!',
		'message' => 'El registro se modificó satisfactoriamente.'
	);

	// Redirecciona la pagina
	redirect(back());
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>