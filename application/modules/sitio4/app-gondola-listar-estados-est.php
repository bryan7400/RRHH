<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  WEB SERVICES
 * @author  EDUCHECK MARCO QUINO
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
		$id_gestion = $_gestion['id_gestion']; 
		//obtiene los datos del modo de calificacion actual
		$fecha_actual = Date('Y-m-d');
		$_modo_calificacion = $db->select('mc.id_modo_calificacion, mc.fecha_inicio, mc.fecha_final, mc.descripcion')->from('cal_modo_calificacion mc')->where('mc.fecha_inicio <=', $fecha_actual)->where('mc.fecha_final >=', $fecha_actual)->where('mc.gestion_id', $_gestion['id_gestion'])->fetch_first();



		// Obtiene los datos del usuario
		$usuario = $db->select('id_user, username, email, avatar, rol_id, visible')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
		// Verifica la existencia del usuario
		if ($usuario) {
			// Obtener Rol 
			//$rol = $db->select('rol')->from('sys_roles')->where('id_rol', $usuario['rol_id'])->fetch_first();
//:::::::::::::::datos de retorno:::::::::::::::::::::::::::::::::::
			// ARMAR PUNTOS
			$id_ruta = $_POST['id_ruta'];
		// Obtiene el rutas
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
		//AREAS
		$rutas = $db->select('z.*')//, COUNT(a.id_punto) as puntos')
					->from('gon_rutas z')
				   // ->join('gon_puntos a','z.id_ruta = a.ruta_id')
					->where('z.id_ruta', $id_ruta)
					//->group_by('id_ruta')
					->fetch_first();
			
		//$coordenadas=trim($rutas['coordenadas'],'*');
		//poligono
		//$polygon = explode('*',$coordenadas);
		//foreach ($polygon as $nro => $poly) {
		//	$aux = explode(',',$poly);
		//	$aux2 = (round($aux[0],6)-0.000044).','.(round($aux[1],6)+0.00003);
			//var_dump($aux2);exit();
		//	$polygon[$nro] = str_replace(',', ' ', $aux2);
		//}
		//$polygon[0] = str_replace(',', ' ', $polygon[$nro]);
		//$pointLocation = new pointLocation();
		//;:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
		// ARMAR PUNTOS
		$puntos = $db->query(' SELECT 
		  (select count(ins.id_inscripcion) from ins_inscripcion ins  where pun.id_punto = ins.punto_id)AS asig_count
		  ,pun.*
		  FROM gon_puntos pun  WHERE pun.ruta_id='.$id_ruta.' and pun.estado="1"')->fetch();
		//$puntos = $db->select('*')->from('gon_puntos')->fetch();
		$t_clientes = '';$nombres = '';$total = 0;
 
		//$puntosDentro=array();
		//foreach($puntos as $row){
			//$aux2 = explode(',',$cliente['ubicacion']);
		//	$aux3 = $row['latitud'];//$aux2[0] + 0.00005;
		//	$aux4 = $row['longitud'];//$aux2[1] - 0.00003;
		//	$point = $aux3.' '.$aux4;
			 
		//}
 
		//::::::::::::::::::::::::::::
 
			$est = $db->query("SELECT est.id_estudiante,per.nombres,per.primer_apellido,per.segundo_apellido,per.genero,ins.punto_id
			,est.codigo_credencial ,ifnull(asi.gondola_id,0)AS gondola_id,ifnull(asi.conductor_id,0)AS conductor_id,ifnull(asi.json_asistencia,'')AS json_asistencia
			FROM ins_inscripcion ins
				INNER JOIN ins_estudiante est ON est.id_estudiante=ins.estudiante_id
				INNER JOIN sys_persona per ON per.id_persona=est.persona_id
			  left JOIN gon_asistencia_estudiante asi ON asi.estudiante_id=est.id_estudiante 
				WHERE  ins.gestion_id=$id_gestion AND ins.estado='A'
				")->fetch();//  AND  asi.json_asistencia LIKE '%2021-04-21%'
			
			
			//$t_clientes = '';$nombres = '';$total = 0;
			 
			 $puntosRe=array();
			 //$estRe=array();
				$estudiantes=array();
			foreach ($puntos as $i => $row1){
			//foreach ($puntos as $i => $row1){
				$id_punto=$row1['id_punto'];
				
				
				foreach ($est as $i => $row2) {
					//ver si esta dentro del poligono
					if($id_punto==$row2['punto_id']){
						if($row2['json_asistencia']!=''){
							$asistenciastr=$row2['json_asistencia'];
			 				//$jsonDesc=json_decode($asistenciastr,true);
							$row2['json_asistencia']=json_decode($asistenciastr,true);
							//var_dump($row2);exit();
						}
						 array_push($estudiantes,$row2);
					}
				}
				
				$row1['estudiantes']=$estudiantes;
				  array_push($puntosRe,$row1);
			 
			}
			//$puntosRe;
//:::::::::::::::::::::::::::::::::::::::::::::::
 			//echo (json_encode($puntosRe)); 
			 
	 		//exit();

			$respuesta = array ("estado" =>"s","est"=>$estudiantes);

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