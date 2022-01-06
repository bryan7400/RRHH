<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  WEB SERVICES
 * @author  MARCO ANTONIO QUINO CHOQUETA
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
        $usuario = md5($usuario);
		$contrasenia = encrypt($contrasenia);
        //var_dump($_gestion);exit();
        
        
        //$anio_actual = //Date('Y'); 
		//$_gestion = $db->select('z.id_gestion, z.gestion')->from('ins_gestion z')->where('gestion', $anio_actual)->fetch_first();
        //var_dump($_gestion);exit();
		$id_gestion = clear($_POST['id_gestion']);
        
        $usuario = $db->select('persona_id,id_user, username, email, avatar, rol_id, visible')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
		// Verifica la existencia del usuario
		if ($usuario) {
			// Obtener Rol 
            $id_persona = $usuario['persona_id'];
            $materias = $db->query("SELECT	pad.id_asignacion_docente, CONCAT(ia.nombre_aula,' ', ip.nombre_paralelo,' ', ina.nombre_nivel) as curso, pm.nombre_materia AS descripcion, pm.icono_materia as imagen, pm.campo_area as tipo, CONCAT('NO') as extra, ina.tipo_calificacion
            						FROM  per_asignaciones AS pa 
            						INNER	JOIN	pro_asignacion_docente AS pad ON pad.asignacion_id = pa.id_asignacion
            						INNER	JOIN	pro_materia AS pm ON pm.id_materia = pad.materia_id
            						INNER JOIN	ins_aula_paralelo AS iap ON iap.id_aula_paralelo = pad.aula_paralelo_id
            						INNER JOIN  ins_aula AS ia ON ia.id_aula = iap.aula_id
            						INNER JOIN  ins_nivel_academico AS	ina ON ina.id_nivel_academico = ia.nivel_academico_id 
            						INNER JOIN  ins_paralelo AS ip ON ip.id_paralelo = iap.paralelo_id
            						WHERE pa.persona_id = $id_persona  AND pa.estado = 'A' AND pad.gestion_id = $id_gestion")->fetch();


            $escuelas_diciplinas = $db->query("SELECT ca.id_curso_asignacion as id_asignacion_docente, c.nombre_curso AS curso , ca.observaciones as descripcion, ec.imagen_curso as imagen
        										FROM ext_curso_asignacion AS ca
        										INNER JOIN ext_curso AS ec ON ec.id_curso = ca.curso_id
        										INNER JOIN per_asignaciones AS a ON a.id_asignacion = ca.asignacion_id
        										INNER JOIN sys_persona AS p ON p.id_persona = a.persona_id
        										INNER JOIN ext_curso AS c ON c.id_curso = ca.curso_id
        										WHERE p.id_persona = $id_persona AND c.estado = 'A' AND ca.gestion_id = $id_gestion")->fetch();

            if (count($escuelas_diciplinas) > 0) {
            	$nro = count($materias);
            	//$nro++;
            	foreach ($escuelas_diciplinas as $key => $value) {
            		$materias[$nro]['id_asignacion_docente'] = $value['id_asignacion_docente'] . "";
            		$materias[$nro]['curso'] = $value['curso'];
            		$materias[$nro]['descripcion'] = $value['descripcion'];
            		$materias[$nro]['imagen'] = $value['imagen'];
            		$materias[$nro]['tipo']  = "EXTRA";
            		$materias[$nro]['extra']  = "SI";
            		$materias[$nro]['tipo_calificacion']  = "CUANTITATIVO";
            		$nro++;
            	}
            }
    
            if (count($materias) == 0) {
            	$materias = $escuelas_diciplinas;
            }
            
			$respuesta = array ("estado" =>"s","materias"=>$materias); 
			// Devuelve los resultados
			echo json_encode($respuesta);
		} else {
			// Devuelve los resultados
			echo json_encode(array('estado' => 'no tiene usuario asignado'));
		}
	} else {
		// Devuelve los resultados
		echo json_encode(array('estado' => 'no hay datos'));
	}
} else {
	// Devuelve los resultados
	echo json_encode(array('estado' => 'n'));
}

?>