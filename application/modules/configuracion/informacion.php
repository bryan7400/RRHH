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
		if (isset($_POST['nombre']) && isset($_POST['sigla']) && isset($_POST['lema']) && isset($_POST['razon_social']) && isset($_POST['nit']) && isset($_POST['propietario']) && isset($_POST['direccion']) && isset($_POST['telefono']) && isset($_POST['correo'])) {
			// Obtiene los datos de la institucion
			$id_institucion = clear($_institution['id_institucion']);
			$nombre        = clear($_POST['nombre']);
			$sigla         = clear($_POST['sigla']);
			$lema          = clear($_POST['lema']);
			$codigo_sie    = clear($_POST['codigo_sie']);
			$razon_social  = clear($_POST['razon_social']);
			$nit           = clear($_POST['nit']);
			$propietario   = clear($_POST['propietario']);
			$direccion     = clear($_POST['direccion']);
			$telefono      = clear($_POST['telefono']);
			$correo        = clear($_POST['correo']);

			// Instancia la institucion
			$institucion = array(
				'nombre' => $nombre,
				'sigla' => $sigla,
				'lema' => $lema,
				'codigo_sie' => $codigo_sie,
				'razon_social' => $razon_social,
				'nit' => $nit,
				'propietario' => $propietario,
				'direccion' => $direccion,
				'telefono' => $telefono,
				'correo' => $correo
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