<?php
/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK (MARIBEL JORGE LUIS)
 */

// Define las cabeceras
header('Content-Type: application/json');

// Verifica la peticion post
 if (is_post()) {
    
	// Verifica la existencia de datos
	if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
		//Obtiene los datos
		$usuario 				= clear($_POST['usuario']);
        $contrasenia 			= clear($_POST['contrasenia']);
		$id_aula_paralelo 		= clear($_POST['id_aula_paralelo']);
		//$id_profesor_materia	= clear($_POST['id_aula_paralelo']);
		$id_estudiante_elegido  = clear($_POST['id_estudiante']);
		//$id_gestion = $_gestion['id_gestion'];
		$id_gestion = "1";

		$fecha                  = $_POST['fecha'];
        
        //$id_persona = 44
		// Encripta la contraseña para compararla en la base de datos
		$usuario    = md5($usuario);
		$contrasenia = encrypt($contrasenia);
		
		$respuesta = $db->query("SELECT *
									FROM cal_modo_calificacion
									WHERE fecha_final > '$fecha' AND estado = 'A' AND gestion_id = $id_gestion")->fetch_first();
		$id_modo_calificacion = $respuesta['id_modo_calificacion'];		
		
		// Obtiene los datos del usuario
		$usuario = $db->select('id_user')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
		
		// Verifica la existencia del usuario 
		if ($usuario) {

			//Consultamos las areas de calificacion 
			$resAreaCal = $db->query("SELECT * FROM cal_area_calificacion")->fetch();
			$aAreaCalificacion = array();
			
			//Armamos el array de Areas de calificacion con su ponderado
			$i = 1;
			foreach ($resAreaCal as $key => $value) {
				$aAreaCalificacion[$i]["id"]		= $value['id_area_calificacion'];
				$aAreaCalificacion[$i]["nombre"]	= $value['descripcion'];
				$aAreaCalificacion[$i]["ponderado"]	= $value['ponderado'];
				$i++;								
			}			
			//fin areas de calificacion	

			// Obtiene las materias de mis hijos
			$materias_hijo = $db->query("SELECT per.nombres, CONCAT(per.primer_apellido, ' ', per.segundo_apellido) AS apellidos, mat.nombre_materia, pm.id_profesor_materia, appm.aula_paralelo_id, appm.id_aula_paralelo_profesor_materia  
											FROM int_aula_paralelo_profesor_materia AS appm
											INNER JOIN pro_profesor_materia AS pm ON pm.id_profesor_materia = appm.profesor_materia_id
											INNER JOIN pro_materia AS mat ON mat.id_materia = pm.materia_id AND mat.estado = 'A'
											INNER JOIN pro_profesor AS pro ON pro.id_profesor = pm.profesor_id
											INNER JOIN sys_persona AS per ON per.id_persona = pro.persona_id
											WHERE appm.aula_paralelo_id = $id_aula_paralelo")->fetch();
			//var_dump($materias_hijo);exit;								
			
			$aMaterias_hijo = array();
			$iMaterias_hijo = 0;
			foreach ($materias_hijo as $keymaterias => $value_materias) {
				//Obtenemos el id de la materia
				$id_profesor_materia = $value_materias['id_profesor_materia'];				 
			

				//Obtenemos todas las notas de las areas de calificacion y segun modo calificacion
				$respuesta = $db->query("SELECT * 
									FROM vista_estudiante_actividad_nota
									WHERE profesor_materia_id = $id_profesor_materia AND aula_paralelo_id = $id_aula_paralelo AND modo_calificacion_id = $id_modo_calificacion")->fetch();

				//Consultamos los estudiantes de un aula y paralelo
				$respuesta_estudiante = $db->query("SELECT *
													FROM vista_estudiante_aula
													WHERE id_aula_paralelo = $id_aula_paralelo")->fetch();			
				//var_dump($respuesta_estudiante); exit;

				$e = 1;
				$aEstudiantes = array();
				foreach ($respuesta_estudiante as $key => $value_estudiante) {
					$aEstudiantes[$e]["id_aula_paralelo"]  = $value_estudiante['id_aula_paralelo'];
					$aEstudiantes[$e]["id_estudiante"]     = $value_estudiante['id_estudiante'];
					$aEstudiantes[$e]["nombre_estudiante"] = $value_estudiante['apellidos']." ".$value_estudiante['nombres'];
					$e++;
				}
				//Fin Terminar el armado del array estudiante

				//var_dump($aEstudiantes); exit;

				//Armamos el array para las notas de los estudiantes y sus actividades
				$aNotasEstudiante = array();
				
				$cac = 1;
				foreach ($aEstudiantes as $key => $value) {
					//$value['nombre_estudiante']);
					$id_estudiante = $value['id_estudiante'];
					$id_aula_paralelo = $value['id_aula_paralelo'];
							
					foreach ($aAreaCalificacion as $i => $valor) {
						$area_calificacion_id = $aAreaCalificacion[$i]['id'];
						/*$sqlConsulta = "SELECT * FROM vista_actividad_materia_modo_area WHERE profesor_materia_id = $id_profesor_materia AND aula_paralelo_id = $id_aula_paralelo AND modo_calificacion_id = $id_modo_calificacion AND area_calificacion_id = $area_calificacion_id;";
						$resConsulta = $conexion->query($sqlConsulta);*/
						$resSql = "SELECT * 
									FROM vista_actividad_materia_modo_area 
									WHERE profesor_materia_id = $id_profesor_materia AND 
											aula_paralelo_id = $id_aula_paralelo AND 
											modo_calificacion_id = $id_modo_calificacion AND 
											area_calificacion_id = $area_calificacion_id";

						//var_dump($resSql);
						$resConsulta = $db->query($resSql)->fetch();
						//var_dump($resConsulta);
						foreach ($resConsulta as $key => $fila_actividad) {
							//Preguntamos la nota que le corresponda al estudiante en la actividad correspondiente
							$var = $fila_actividad['id_actividad_materia_modo_area'];	
							$resConsulta2 = $db->query("SELECT * FROM vista_estudiante_actividad_nota AS vean WHERE vean.id_actividad_materia_modo_area = $var AND vean.estudiante_id = $id_estudiante;")->fetch_first();
							if($resConsulta2 != null){
								$aNotasEstudiante [$id_estudiante][$aAreaCalificacion[$i]['nombre']."/".$aAreaCalificacion[$i]['ponderado']][$fila_actividad['id_actividad_materia_modo_area']]=$resConsulta2['nota'] ;
							}else{
								$aNotasEstudiante [$id_estudiante][$aAreaCalificacion[$i]['nombre']."/".$aAreaCalificacion[$i]['ponderado']][$fila_actividad['id_actividad_materia_modo_area']]="0" ;
							}
							//Armamos las cabeceras para un mejor detalle
							//$aCabeceraActividades [$aAreaCalificacion[$i]['nombre']."/".$aAreaCalificacion[$i]['ponderado']][$fila_actividad['id_actividad_materia_modo_area']]= [$fila_actividad['nombre_actividad']];	
						}                					
					}							
				}
				//Fin de armar un array de las notas de los estudiantes por cada actividad 
				
				//var_dump($aNotasEstudiante);				

				//Llenamos las filas de tal manera que se liste los estudiantes y se muestres las notas por cada actividad
				$respuesta_estudiante = $db->query("SELECT *
													FROM vista_estudiante_aula
													WHERE id_aula_paralelo = $id_aula_paralelo")->fetch();
				//var_dump($respuesta_estudiante); exit;
				//Variables para el promedio del curso de la materia 
				
				//preguntamos que el array no este vacio
				if(!empty($aNotasEstudiante)){
					$nota_materia_estudiante = 0;
					$nota_materia_promedio   = 0;
					$nota_estudiante_materia_promedio   = 0;     
													
					foreach ($respuesta_estudiante as $key => $fila_estudiante){
						//$td = 3;
						//$cont_areas = 1;
						$suma_areas_calificacion_est = 0;					
						$id_estudiante = $fila_estudiante['id_estudiante'];
						//$nro_estudiante++;
						//var_dump($aNotasEstudiante[$id_estudiante]);exit;				
						//colocar las notas por estudiante por bimestre			
						foreach ($aNotasEstudiante[$id_estudiante] as $key => $value) {
							$ponderado = explode("/",$key);
							$ponderado_area = $ponderado[1];
							$suma = 0;
							$nro = sizeof($value);
														
							foreach ($value as $k => $val) {
								$suma +=$val;
								//$td ++;						
							}
							$suma_areas_calificacion_est += round(round($suma/$nro)*$ponderado_area/100);
							//$td ++;	
						}
						if($id_estudiante_elegido == $id_estudiante){
							//Aca encontramos la nota del estudiante
							$nota_materia_estudiante = $suma_areas_calificacion_est;
						}
						//Aca sumamos todas las notas de los estudiantes para luego promediarlos
						$nota_estudiante_materia_promedio = $nota_estudiante_materia_promedio + $suma_areas_calificacion_est;												
					}
					//Aca sacamos el prmedio del curso en la materia
					$nota_materia_promedio = round($nota_estudiante_materia_promedio/sizeof($respuesta_estudiante));
					
					//Armamos el objeto estudiante materia nota
					$aMaterias_hijo [$iMaterias_hijo]["nombres"]= $value_materias['nombres'];
					$aMaterias_hijo [$iMaterias_hijo]["apellidos"]= $value_materias['apellidos'];
					$aMaterias_hijo [$iMaterias_hijo]["nombre_materia"]= $value_materias['nombre_materia'];
					$aMaterias_hijo [$iMaterias_hijo]["id_profesor_materia"]= $value_materias['id_profesor_materia'];
					$aMaterias_hijo [$iMaterias_hijo]["aula_paralelo_id"]= $value_materias['aula_paralelo_id'];
					$aMaterias_hijo [$iMaterias_hijo]["id_aula_paralelo_profesor_materia"]= $value_materias['id_aula_paralelo_profesor_materia'];
					$aMaterias_hijo [$iMaterias_hijo]["nota_materia_estudiante"]= $nota_materia_estudiante;
					$aMaterias_hijo [$iMaterias_hijo]["nota_materia_promedio"]= $nota_materia_promedio;
					
					//var_dump("Nota > ".$nota_materia_promedio);
				}else{					
					//Aca entra solo por que no exite notas registradas sobre las actividades
					//Armamos el objeto estudiante materia nota
					$aMaterias_hijo [$iMaterias_hijo]["nombres"]= $value_materias['nombres'];
					$aMaterias_hijo [$iMaterias_hijo]["apellidos"]= $value_materias['apellidos'];
					$aMaterias_hijo [$iMaterias_hijo]["nombre_materia"]= $value_materias['nombre_materia'];
					$aMaterias_hijo [$iMaterias_hijo]["id_profesor_materia"]= $value_materias['id_profesor_materia'];
					$aMaterias_hijo [$iMaterias_hijo]["aula_paralelo_id"]= $value_materias['aula_paralelo_id'];
					$aMaterias_hijo [$iMaterias_hijo]["id_aula_paralelo_profesor_materia"]= $value_materias['id_aula_paralelo_profesor_materia'];
					$aMaterias_hijo [$iMaterias_hijo]["nota_materia_estudiante"]= 0;
					$aMaterias_hijo [$iMaterias_hijo]["nota_materia_promedio"]= 0;	
				}
				$iMaterias_hijo++;	
			}//fin de foreach de las materias por hijo

			// Instancia el objeto que devolvera la web service			
			$respuesta = array(
				'estado' => 's',
				'materias_hijo' => $aMaterias_hijo					
			);

			// Devuelve los resultados
			echo json_encode($respuesta);
		} else {
			// Devuelve los resultados
			echo json_encode(array('estado' => 'n'));
		}
	} else {
	// 	// Devuelve los resultados
	   echo json_encode(array('estado' => 'n usuario'));
	}
} else {
// 	// Devuelve los resultados
    echo json_encode(array('estado' => 'npost'));
}
