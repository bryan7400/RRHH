


<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  WEB SERVICES
 * @author  EDUCHECK MARIBEL JORGE LUIS
 */

// Define las cabeceras
header('Content-Type: application/json');

// Verifica la peticion post
if (is_post()) {
	// Verifica la existencia de datos
	if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
		// Obtiene los datos
		$usuario = clear($_POST['usuario']);
		$contrasenia = clear($_POST['contrasenia']);
		$id_ruta = clear($_POST['id_ruta']);

		// Encripta la contraseña para compararla en la base de datos
		$usuario = $usuario;//md5($usuario);
		$contrasenia = $contrasenia;//encrypt($contrasenia);
 
		//obtiene el año actual
		$anio_actual = Date('Y');

		//obtiene los datos de la gestion actual
		$_gestion = $db->select('z.id_gestion, z.gestion')->from('ins_gestion z')->where('gestion', $anio_actual)->fetch_first();
		$gest_id=$_gestion['id_gestion'];
		//obtiene los datos del modo de calificacion actual
		$fecha_actual = Date('Y-m-d');
		 
		// Obtiene los datos del usuario
		$usuario = $db->select('id_user, username, email, avatar, rol_id, visible')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
		// Verifica la existencia del usuario
		if ($usuario) {
			// Obtener Rol 
			//$rol = $db->select('rol')->from('sys_roles')->where('id_rol', $usuario['rol_id'])->fetch_first();
//:::::::::::::::datos de retorno:::::::::::::::::::::::::::::::::::
			// ARMAR PUNTOS
			$puntos = $db->query('SELECT pun.*
				 FROM gon_puntos pun
				 INNER JOIN gon_rutas rut ON rut.id_ruta = pun.ruta_id
				 WHERE pun.ruta_id='.$id_ruta)->fetch();
			//$id_ruta
			$est = $db->query("SELECT est.id_estudiante,per.nombres,per.primer_apellido,per.segundo_apellido,per.genero,ins.punto_id,est.codigo_credencial FROM ins_inscripcion ins
				INNER JOIN ins_estudiante est ON est.id_estudiante=ins.estudiante_id
				INNER JOIN sys_persona per ON per.id_persona=est.persona_id
				WHERE  ins.gestion_id=$gest_id AND ins.estado='A'
				")->fetch();// AND ins.punto_id=1
			
			//$t_clientes = '';$nombres = '';$total = 0;
			$puntosEst=array();
			$puntosRe=array();
			$estudiantes=array();
			foreach ($puntos as $i => $row1) {
				$id_punto=$row1['id_punto'];
				
				foreach ($est as $i => $row2) { 
					if($id_punto==$row2['punto_id']){
						//$row1['estudiantes'][]=$row2;
						 array_push($estudiantes,$row2);
					}
				}
				
				//$row1['estudiantes']=$estudiantes;
				//  array_push($puntosRe,$row1);
			}
				 //unset($row);
			
			//exit();
			
			//puntos{
				//=estudaintes
			//}

			$respuesta = array ("estado" =>"s","rutaEst"=>$estudiantes);

			// Devuelve los resultados
			echo json_encode($respuesta);
		} else {
			// Devuelve los resultados
			echo json_encode(array('estado' => 'n'));
		}
	} else {
		// Devuelve los resultados
		echo json_encode(array('estado' => 'n'));
	}
} else {
	// Devuelve los resultados
	echo json_encode(array('estado' => 'n'));
}

?>