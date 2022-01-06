<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */
$gestion=$_gestion['gestion'];
$documentos_actividad = "";
$archivos_permitidos = 0;
$nombre_dominio = escape($_institution['nombre_dominio']);
$permiso_subir = in_array('subir', $_views);
// Verifica la peticion post
if (is_post()) { 
	// Verifica la cadena csrf
	//if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
	if (isset($_POST['id_medico_estudiante'])) {
var_dump($_POST['estatura']);
		// Obtiene los datos
		$id_medico_estudiante = (isset($_POST['id_medico_estudiante'])) ? clear($_POST['id_medico_estudiante']) : 0;
		$estudiante_id = clear($_POST['estudiante_id']); 
		$categoria_medico = clear($_POST['categoria_medico']);
		$tipo_sangre = clear($_POST['tipo_sangre']);
		$estatura = (isset($_POST['estatura'])) ? clear($_POST['estatura']) : 0;
		
		$fecha_estatura = clear($_POST['fecha_estatura']);
		
		$peso = (isset($_POST['peso'])) ? clear($_POST['peso']) : 0;
		$fecha_peso = clear($_POST['fecha_peso']);
		$alergia = (isset($_POST['alergia'])) ? clear($_POST['alergia']) : 0;
		$vacuna =  (isset($_POST['vacuna'])) ? clear($_POST['vacuna']) : 0;
		
		
		
        // obtiene la gestion
		





			// Instancia el cliente
			$informacion_estudiante = array(
				'id_medico_estudiante' => $id_medico_estudiante,
				'estudiante_id' => $estudiante_id,
				'categoria_medico' => $categoria_medico,
				'tipo_sangre' => $tipo_sangre,
				'estatura' => $estatura,
				'fecha_estatura' => $fecha_estatura,
				'peso' => $peso,
				'fecha_peso' => $fecha_peso,
				'alergia' => $alergia,
				'vacuna' => $vacuna,

				'usuario_registro' => $_user['id_user'],
		        'fecha_registro' => date('Y-m-d'),
		        'usuario_modificacion' => $_user['id_user'],
		        'fecha_modificacion' => date('Y-m-d')
			);
			
			// Verifica si es creacion o modificacion
			if ($id_medico_estudiante > 0) {
				// Modifica el cliente
				$db->where('id_medico_estudiante', $id_medico_estudiante)->update('ins_registro_medico_estudiante', $informacion_estudiante);


				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el cliente con identificador número ' . $id_medico_estudiante . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				echo 1;
				// Redirecciona la pagina
				// redirect('?/cliente/ver/' . $id_medico_estudiante);
			} else {
				// Crea el cliente
				$id_medico_estudiante = $db->insert('ins_registro_medico_estudiante', $informacion_estudiante);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el cliente con identificador número ' . $id_medico_estudiante . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				echo 2;
				// Redirecciona la pagina
				// redirect('?/cliente/listar');
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	//	echo 3;
	// } else {
	// 	// Redirecciona la pagina
	// 	redirect('?/cliente/listar');
	// }
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>