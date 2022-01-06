<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author  Maribel Jorge Luis
 */

//var_dump($_POST);
//exit();
// Verifica la peticion post
if (is_post()) {
	// Verifica la cadena csrf
	//if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
		if (isset($_POST['aula_id']) && isset($_POST['my-select'])) {
			// Obtiene los datos
            $id_aula   = (isset($_POST['aula_id'])) ? clear($_POST['aula_id']) : 0;
            //$id_aula   = $_POST['id_aula'];
			$aMaterias = $_POST['my-select'];
            /* echo("<pre>");
            var_dump($aMaterias);
            echo("</pre>");
            exit(); */   
            // Verifica si es creacion o modificacion
           // echo ("id_aula".$id_aula);
			if ($id_aula > 0) {
				// Modifica el aula
				$db->where('id_aula', $id_aula)->update('ins_aula',array('materia_id' => implode("@",$aMaterias)));
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el aula con identificador número ' . $id_aula . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
                //redirect('?/aula/ver/' . $id_aula);
                redirect('?/s-curso/listar');
				echo 2;
			} else {
                //No se encuentra el id_aula para poder asignar las materias				
				echo 1;
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	//} else {
		// Redirecciona la pagina
		//redirect('?/aula/listar');
	//}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>