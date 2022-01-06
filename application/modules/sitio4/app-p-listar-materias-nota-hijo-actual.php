<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK (MARIBEL MARCO LUIS)
 */

// Define las cabeceras
header('Content-Type: application/json');

// Verifica la peticion post
if (is_post()) {

	// Verifica la existencia de datos
	if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
		//Obtiene los datos
		$usuario 		  = clear($_POST['usuario']);
		$contrasenia 	  = clear($_POST['contrasenia']);
		$id_aula_paralelo = clear($_POST['id_aula_paralelo']);
		$id_estudiante    = clear($_POST['id_estudiante']);
		$id_gestion       = clear($_POST['id_gestion']);


		// obtiene el ponderado
		$ponderado_calificacion = $_institution['ponderado_calificacion']; // A : nota actual de todas las area  P: todas las notas se manejan por el 100

		//obtenemos la fecha de hoy
		$hoy = date('Y-m-d');

		// Obtenemos el modo calificacion
		$sql_modo_calificacion = "SELECT * FROM cal_modo_calificacion WHERE gestion_id ='$id_gestion' and fecha_inicio <= '$hoy' and fecha_final >= '$hoy' AND estado = 'A'";
		$modos_calificacion = $db->query($sql_modo_calificacion)->fetch_first();
		$id_modo_calificacion = ((isset($modos_calificacion['id_modo_calificacion']) ? $modos_calificacion['id_modo_calificacion'] : "0"));

		// Extraemos las areas de calificacion
		$sql_areas_calificacion = "SELECT *
		                            FROM cal_area_calificacion as cac
		                            WHERE cac.estado = 'A' AND cac.gestion_id = $id_gestion
		                            ORDER BY orden ASC";
		$res_areas_calificacion = $db->query($sql_areas_calificacion)->fetch();

		$usuario    = md5($usuario);
		$contrasenia = encrypt($contrasenia);
		// Obtiene los datos del usuario
		$usuario = $db->select('id_user')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();

		if ($usuario) {
			//Obtenemos todos los estudiantes del curso
			$sql_estudiantes_aula_paralelo = "SELECT ie.id_estudiante, ia.nombre_aula, ip.nombre_paralelo, ina.nombre_nivel, ina.descripcion, iir.nro_rude, CONCAT(p.primer_apellido,' ',p.segundo_apellido,' ', p.nombres)AS nombre_estudiante, ii.aula_paralelo_id
                                FROM ins_inscripcion AS ii
                                INNER JOIN ins_inscripcion_rude AS iir ON iir.ins_estudiante_id = ii.estudiante_id
                                INNER JOIN ins_aula_paralelo AS iap ON iap.id_aula_paralelo = ii.aula_paralelo_id
                                INNER JOIN ins_aula AS ia ON ia.id_aula = iap.aula_id
                                INNER JOIN ins_nivel_academico AS ina ON ina.id_nivel_academico = ia.nivel_academico_id
                                INNER JOIN ins_paralelo AS ip ON ip.id_paralelo = iap.paralelo_id
                                INNER JOIN ins_estudiante AS ie ON ie.id_estudiante = ii.estudiante_id
                                INNER JOIN sys_persona AS p ON p.id_persona = ie.persona_id
                                WHERE ii.estado = 'A' AND ii.gestion_id = $id_gestion AND iap.estado = 'A' AND ii.aula_paralelo_id = $id_aula_paralelo";

			$res_estudiantes_aula_paralelo = $db->query($sql_estudiantes_aula_paralelo)->fetch();


			//Busacamos las materias regulares que tiene el estudiantes
			$sql_materia_regular = "SELECT pad.id_asignacion_docente , pm.id_materia, pm.cod_materia, pm.nombre_materia, pm.icono_materia, pm.orden, pm.imagen_materia, pm.color_materia, CONCAT(sp.nombres,' ',sp.primer_apellido,' ',sp.segundo_apellido ) AS nombre_docente
										FROM pro_asignacion_docente AS pad
										INNER JOIN pro_materia AS pm ON pm.id_materia = pad.materia_id
										INNER JOIN per_asignaciones AS pa ON pa.id_asignacion = pad.asignacion_id
										INNER JOIN sys_persona AS sp ON sp.id_persona = pa.persona_id
										WHERE pad.aula_paralelo_id = $id_aula_paralelo AND	pad.gestion_id = $id_gestion AND pad.estado_docente = 'A' AND pm.estado = 'A' AND pm.campo_area = 'NO'
										ORDER BY pm.orden ASC";

			$res_materia_regular = $db->query($sql_materia_regular)->fetch();

			//buscamos todas actividades de el aula paralelo
			$sql_actividades_aula_paralelo = "SELECT *
                                    			FROM  tem_asesor_curso_actividad AS taca
                                    			INNER JOIN pro_asignacion_docente AS pac ON pac.id_asignacion_docente = taca.asignacion_docente_id
                                    			WHERE pac.estado_docente = 'A' AND pac.aula_paralelo_id = $id_aula_paralelo AND taca.estado_actividad = 'A' AND taca.estado_cartilla = 'SI' AND taca.tipo_actividad <> 'REUNION' AND taca.estado_curso <> 'E'";

			$res_actividades_aula_paralelo = $db->query($sql_actividades_aula_paralelo)->fetch();

			//Armamos el array de las actividades separados por las materias, modo calificacion  y area calificacion
			$aActividadesAulaParalelo = array();
			foreach ($res_actividades_aula_paralelo as $aap => $actividades) {
				$aActividadesAulaParalelo[$actividades['asignacion_docente_id']][$actividades['modo_calificacion_id']][$actividades['area_calificacion_id']][] = $actividades['id_asesor_curso_actividad'];
			}


			//Sacamos todas las notas de los estudiantes que presentaron sus actividades
			$sql_estudiantes_actividades_notas = "SELECT ie.id_estudiante, CONCAT(p.primer_apellido,' ',p.segundo_apellido,' ', p.nombres)AS nombre_estudiante, ii.aula_paralelo_id, teca.asesor_curso_actividad_id, teca.nota
													FROM ins_inscripcion AS ii
													INNER JOIN ins_inscripcion_rude AS iir ON iir.ins_estudiante_id = ii.estudiante_id
													INNER JOIN ins_aula_paralelo AS iap ON iap.id_aula_paralelo = ii.aula_paralelo_id                                
													INNER JOIN ins_estudiante AS ie ON ie.id_estudiante = ii.estudiante_id
													INNER JOIN sys_persona AS p ON p.id_persona = ie.persona_id
													INNER JOIN tem_estudiante_curso_actividad AS teca ON teca.estudiante_id = ii.estudiante_id
													WHERE ii.estado = 'A' AND ii.gestion_id = $id_gestion AND iap.estado = 'A' AND ii.aula_paralelo_id = $id_aula_paralelo";
			$res_estudiantes_actividades_notas = $db->query($sql_estudiantes_actividades_notas)->fetch();

			//Armamos el array de las actividades separados por las materias, modo calificacion  y area calificacion
			$aActividadesEstudianteNota = array();
			foreach ($res_estudiantes_actividades_notas as $ean => $actividades_notas) {
				$aActividadesEstudianteNota[$actividades_notas['id_estudiante']][$actividades_notas['asesor_curso_actividad_id']] = $actividades_notas['nota'];
			}


			$aMaterias_hijo = array();
			$iMaterias_hijo = 0;

			$_nro_estudiantes = count($res_estudiantes_aula_paralelo);

			$suma_nota_promedio_curso = 0;
			foreach ($res_materia_regular as $key_materias => $value_materias) {
				//Obtenemos el id de la materia
				$id_asignacion_docente = $value_materias['id_asignacion_docente'];

				//Recorremos todos los estudiantes para poder sacar si nota en las diferentes areas
				foreach ($res_estudiantes_aula_paralelo as $eap => $estudiantes_aula_paralelo) {

					$_id_estudiante = $estudiantes_aula_paralelo['id_estudiante']; // este es el id estudiante que recorrermos por curso

					$_nota_obtenida_x_modo = 0;
					//recorremos las areas para distingir todas las tareas y sacar la nota promedio
					foreach ($res_areas_calificacion as $ac => $areas_calificacion) {

						//obtenemos el porderado por area
						$ponderado_x_area = $areas_calificacion['ponderado'];

						$_nota_obtenida_x_area = 0;

						//Obtenemos las actividades segun el modo y area de calificacion
						//Preguntamos si existe actividades en el modo y area de calificacion
						if (isset($aActividadesAulaParalelo[$id_asignacion_docente][$id_modo_calificacion][$areas_calificacion['id_area_calificacion']])) {
							$actividades_materia_regular = $aActividadesAulaParalelo[$id_asignacion_docente][$id_modo_calificacion][$areas_calificacion['id_area_calificacion']];

							$_cantidad_actividad_area = count($actividades_materia_regular);
							$_suma_nota_x_area = 0;
							foreach ($actividades_materia_regular as $item_actividad) {
								if (isset($aActividadesEstudianteNota[$_id_estudiante][$item_actividad])) {
									$_suma_nota_x_area = $_suma_nota_x_area + $aActividadesEstudianteNota[$_id_estudiante][$item_actividad];
								}
							}


							//preguntamos si es porderado o no
							if ($ponderado_calificacion == "A") {
								$_nota_obtenida_x_area = round(($_suma_nota_x_area / $_cantidad_actividad_area), 0);
							} else if ($ponderado_calificacion == "P") {
								//1ro sacamos el promedio
								$_nota_obtenida_x_area_parcial = round(($_suma_nota_x_area / $_cantidad_actividad_area), 0);
								$_nota_obtenida_x_area = round((($_nota_obtenida_x_area_parcial * $ponderado_x_area) / 100), 0);
							}
						}
						$_nota_obtenida_x_modo = $_nota_obtenida_x_modo + $_nota_obtenida_x_area;
					}

					//Sumamos las notas de todos los estudiantes
					$suma_nota_promedio_curso = $suma_nota_promedio_curso + $_nota_obtenida_x_modo;

					if ($id_estudiante == $_id_estudiante) {
						$aMaterias_hijo[$iMaterias_hijo]["id_asignacion_docente"]   = $value_materias['id_asignacion_docente'];
						$aMaterias_hijo[$iMaterias_hijo]["tipo_curso"]              = "N";
						$aMaterias_hijo[$iMaterias_hijo]["nombres_docente"]         = $value_materias['nombre_docente'];
						$aMaterias_hijo[$iMaterias_hijo]["nombre_materia"]          = $value_materias['nombre_materia'];
						$aMaterias_hijo[$iMaterias_hijo]["nota_materia_estudiante"] = $_nota_obtenida_x_modo;
					}
				}

				//Guardamos el promedio de nota obtenido con todos los estudiantes
				$aMaterias_hijo[$iMaterias_hijo]["nota_materia_promedio"]   = round(($suma_nota_promedio_curso / $_nro_estudiantes), 0);
				$iMaterias_hijo++;
				$suma_nota_promedio_curso = 0;
			}

			// echo json_encode($aMaterias_hijo);
			// exit();

			/********************************************************************************** */
			//Buscamos las area a las que esta inscrito que  son las materias extracurriculares
			/********************************************************************************** */
			$sql_extracurriculares = "SELECT eca.id_curso_asignacion, ec.id_curso, ec.nombre_curso, CONCAT(sp.nombres,' ',sp.primer_apellido,' ',sp.segundo_apellido ) AS nombre_docente
										FROM ext_curso_inscripcion AS eci
										INNER JOIN ext_curso_asignacion AS eca ON eca.id_curso_asignacion = eci.curso_asignacion_id
										INNER JOIN ext_curso AS ec ON ec.id_curso = eca.curso_id
										INNER JOIN per_asignaciones AS pa ON pa.id_asignacion = eca.asignacion_id
										INNER JOIN sys_persona AS sp ON sp.id_persona = pa.persona_id	
										WHERE eci.estado = 'A' AND eci.gestion_id = $id_gestion AND eca.estado = 'A' AND eca.gestion_id = $id_gestion AND estudiante_id = $id_estudiante";
			$res_extracurriculares = $db->query($sql_extracurriculares)->fetch();

			//buscamos todas actividades de el aula paralelo
			$sql_actividades_ext_curso = "SELECT eca.id_curso_asignacion, ec.nombre_curso, taca.id_asesor_curso_actividad, taca.nombre_actividad , taca.estado_curso, taca.modo_calificacion_id, taca.area_calificacion_id
											FROM ext_curso_inscripcion AS eci
											INNER JOIN ext_curso_asignacion AS eca ON eca.id_curso_asignacion = eci.curso_asignacion_id
											INNER JOIN ext_curso AS ec ON ec.id_curso = eca.curso_id
											INNER JOIN tem_asesor_curso_actividad AS taca ON taca.asignacion_docente_id = eci.id_curso_inscripcion
											WHERE eci.estado = 'A' AND eci.gestion_id = $id_gestion AND eca.estado = 'A' AND eca.gestion_id = $id_gestion AND estudiante_id = $id_estudiante  AND taca.estado_curso = 'E' AND taca.presentar_actividad = 'SI' AND taca.estado_actividad = 'A'";

			$res_actividades_ext_curso = $db->query($sql_actividades_ext_curso)->fetch();

			//Armamos el array de las actividades separados por las materias, modo calificacion  y area calificacion
			$aActividadesExtraCurso = array();
			foreach ($res_actividades_ext_curso as $aec => $actividades_extra) {
				$aActividadesExtraCurso[$actividades_extra['id_curso_asignacion']][$actividades_extra['modo_calificacion_id']][$actividades_extra['area_calificacion_id']][] = $actividades_extra['id_asesor_curso_actividad'];
			}


			//Buscamos las notas de las actividades enviadas por los estudiantes
			$sql_estudiantes_actividades_notas_extracurriculares = "SELECT teca.asesor_curso_actividad_id, ie.id_estudiante, CONCAT(sp.primer_apellido,' ',sp.segundo_apellido,' ',sp.nombres)AS nombre_completo, teca.id_estudiante_curso_actividad,teca.estudiante_id, teca.nota
										FROM ext_curso_inscripcion AS eci
										INNER JOIN ext_curso_asignacion AS eca ON eca.id_curso_asignacion = eci.curso_asignacion_id
										INNER JOIN ext_curso AS ec ON ec.id_curso = eca.curso_id
										INNER JOIN ins_estudiante AS ie ON ie.id_estudiante = eci.estudiante_id
										INNER JOIN sys_persona AS sp ON sp.id_persona = ie.persona_id
										INNER JOIN tem_asesor_curso_actividad AS taca ON taca.asignacion_docente_id = eca.id_curso_asignacion
										INNER JOIN tem_estudiante_curso_actividad AS teca ON teca.estudiante_id = ie.id_estudiante
										WHERE eci.estado = 'A' AND eci.gestion_id =  $id_gestion AND eca.estado = 'A' AND eca.gestion_id = $id_gestion AND taca.presentar_actividad = 'SI' AND taca.estado_actividad = 'A' AND taca.estado_curso = 'E'";
			$res_estudiantes_actividades_notas_extracurriculares = $db->query($sql_estudiantes_actividades_notas_extracurriculares)->fetch();

			//Armamos el array de las actividades extracurriculares separados por las materias, modo calificacion  y area calificacion
			$aActividadesEstudianteNotaExtracurriculares = array();
			foreach ($res_estudiantes_actividades_notas_extracurriculares as $eane => $actividades_notas_extracurriculares) {
				$aActividadesEstudianteNotaExtracurriculares[$actividades_notas_extracurriculares['id_estudiante']][$actividades_notas_extracurriculares['asesor_curso_actividad_id']] = $actividades_notas_extracurriculares['nota'];
			}


			//Recorremos los extracurriculares
			$suma_nota_promedio_curso = 0;
			foreach ($res_extracurriculares as $key_materias_ext => $value_materias_ext) {
				//Obtenemos el id de la materia extracurricular
				$id_curso_asignacion = $value_materias_ext['id_curso_asignacion'];

				//Recorremos todos los estudiantes para poder sacar si nota en las diferentes areas
				$sql_estudiantes_extracurricular = "SELECT ie.id_estudiante, eca.id_curso_asignacion, ec.id_curso, ec.nombre_curso, CONCAT(sp.nombres,' ',sp.primer_apellido,' ',sp.segundo_apellido ) AS nombre_docente
														FROM ext_curso_inscripcion AS eci
														INNER JOIN ext_curso_asignacion AS eca ON eca.id_curso_asignacion = eci.curso_asignacion_id
														INNER JOIN ext_curso AS ec ON ec.id_curso = eca.curso_id
														INNER JOIN ins_estudiante AS ie ON ie.id_estudiante = eci.estudiante_id	
														INNER JOIN sys_persona AS sp ON sp.id_persona = ie.persona_id	
														WHERE eci.estado = 'A' AND eci.gestion_id = $id_gestion AND eca.estado = 'A' AND eca.gestion_id = $id_gestion AND eca.id_curso_asignacion = $id_curso_asignacion";
				$res_estudiantes_extracurricular = $db->query($sql_estudiantes_extracurricular)->fetch();
				$_nro_estudiantes_extra = count($res_estudiantes_extracurricular);

				foreach ($res_estudiantes_extracurricular as $ree => $estudiantes_extra) {

					$_id_estudiante = $estudiantes_extra['id_estudiante']; // este es el id estudiante que recorrermos por curso

					$_nota_obtenida_x_modo = 0;
					//recorremos las areas para distingir todas las tareas y sacar la nota promedio
					foreach ($res_areas_calificacion as $ac => $areas_calificacion) {

						//obtenemos el porderado por area
						$ponderado_x_area = $areas_calificacion['ponderado'];
						$_nota_obtenida_x_area = 0;
						//Obtenemos las actividades segun el modo y area de calificacion
						//Preguntamos si existe actividades en el modo y area de calificacion
						if (isset($aActividadesExtraCurso[$id_curso_asignacion][$id_modo_calificacion][$areas_calificacion['id_area_calificacion']])) {

							$actividades_materia_extracurricular = $aActividadesExtraCurso[$id_curso_asignacion][$id_modo_calificacion][$areas_calificacion['id_area_calificacion']];

							$_cantidad_actividad_area = count($actividades_materia_extracurricular);
							$_suma_nota_x_area = 0;
							foreach ($actividades_materia_extracurricular as $item_actividad) {
								if (isset($aActividadesEstudianteNotaExtracurriculares[$_id_estudiante][$item_actividad])) {
									$_suma_nota_x_area = $_suma_nota_x_area + $aActividadesEstudianteNotaExtracurriculares[$_id_estudiante][$item_actividad];
								}
							}

							//preguntamos si es porderado o no
							if ($ponderado_calificacion == "A") {
								$_nota_obtenida_x_area = round(($_suma_nota_x_area / $_cantidad_actividad_area), 0);
							} else if ($ponderado_calificacion == "P") {
								//1ro sacamos el promedio
								$_nota_obtenida_x_area_parcial = round(($_suma_nota_x_area / $_cantidad_actividad_area), 0);
								$_nota_obtenida_x_area = round((($_nota_obtenida_x_area_parcial * $ponderado_x_area) / 100), 0);
							}
						}
						$_nota_obtenida_x_modo = $_nota_obtenida_x_modo + $_nota_obtenida_x_area;
					}

					//Sumamos las notas de todos los estudiantes
					$suma_nota_promedio_curso = $suma_nota_promedio_curso + $_nota_obtenida_x_modo;

					if ($id_estudiante == $_id_estudiante) {
						$aMaterias_hijo[$iMaterias_hijo]["id_asignacion_docente"]   = $value_materias_ext['id_curso_asignacion'];
						$aMaterias_hijo[$iMaterias_hijo]["tipo_curso"]              = "E";
						$aMaterias_hijo[$iMaterias_hijo]["nombres_docente"]         = $value_materias_ext['nombre_docente'];
						$aMaterias_hijo[$iMaterias_hijo]["nombre_materia"]          = $value_materias_ext['nombre_curso'];
						$aMaterias_hijo[$iMaterias_hijo]["nota_materia_estudiante"] = $_nota_obtenida_x_modo;
					}
				}
				//Guardamos el promedio de nota obtenido con todos los estudiantes
				$aMaterias_hijo[$iMaterias_hijo]["nota_materia_promedio"]   = round(($suma_nota_promedio_curso / $_nro_estudiantes_extra), 0);
				$iMaterias_hijo++;
				$suma_nota_promedio_curso = 0;
			}

			/********************************************************************************** */
			//Buscamos las area a las que esta inscrito el estudiante
			/********************************************************************************** */
			


			//Busacamos las materias de tipo area que tiene que tiene el estudiantes
			$sql_materia_area = "SELECT pad.id_asignacion_docente, pm.id_materia, pm.nombre_materia, pm.descripcion, pm.icono_materia, pm.orden, pm.imagen_materia, pm.color_materia, CONCAT(sp.nombres,' ',sp.primer_apellido,' ',sp.segundo_apellido ) AS nombre_docente
									FROM ins_inscripcion AS ii
									INNER JOIN pro_materia AS pm ON pm.descripcion = ii.area
									INNER JOIN pro_asignacion_docente AS pad ON pad.materia_id = pm.id_materia
									INNER JOIN per_asignaciones a ON a.id_asignacion= pad.asignacion_id
									INNER JOIN sys_persona sp ON sp.id_persona=a.persona_id
									WHERE ii.estudiante_id = $id_estudiante AND ii.gestion_id = $id_gestion AND ii.area != '' AND ii.aula_paralelo_id = $id_aula_paralelo AND pad.estado_docente = 'A' AND pad.aula_paralelo_id = $id_aula_paralelo";

			$res_materia_area = $db->query($sql_materia_area)->fetch();

			//buscamos todas actividades de el aula paralelo
			$sql_actividades_aula_paralelo = "SELECT *
                                    			FROM  tem_asesor_curso_actividad AS taca
                                    			INNER JOIN pro_asignacion_docente AS pac ON pac.id_asignacion_docente = taca.asignacion_docente_id
                                    			WHERE pac.estado_docente = 'A' AND pac.aula_paralelo_id = $id_aula_paralelo AND taca.estado_actividad = 'A' AND taca.presentar_actividad = 'SI' AND taca.estado_curso = 'E'";

			$res_actividades_aula_paralelo = $db->query($sql_actividades_aula_paralelo)->fetch();

			//Armamos el array de las actividades separados por las materias, modo calificacion  y area calificacion
			$aActividadesAulaParalelo = array();
			foreach ($res_actividades_aula_paralelo as $aap => $actividades) {
				$aActividadesAulaParalelo[$actividades['asignacion_docente_id']][$actividades['modo_calificacion_id']][$actividades['area_calificacion_id']][] = $actividades['id_asesor_curso_actividad'];
			}


			//Sacamos todas las notas de los estudiantes que presentaron sus actividades
			$sql_estudiantes_actividades_notas = "SELECT ie.id_estudiante, CONCAT(p.primer_apellido,' ',p.segundo_apellido,' ', p.nombres)AS nombre_estudiante, ii.aula_paralelo_id, teca.asesor_curso_actividad_id, teca.nota
													FROM ins_inscripcion AS ii
													INNER JOIN ins_inscripcion_rude AS iir ON iir.ins_estudiante_id = ii.estudiante_id
													INNER JOIN ins_aula_paralelo AS iap ON iap.id_aula_paralelo = ii.aula_paralelo_id                                
													INNER JOIN ins_estudiante AS ie ON ie.id_estudiante = ii.estudiante_id
													INNER JOIN sys_persona AS p ON p.id_persona = ie.persona_id
													INNER JOIN tem_estudiante_curso_actividad AS teca ON teca.estudiante_id = ii.estudiante_id
													WHERE ii.estado = 'A' AND ii.gestion_id = $id_gestion AND iap.estado = 'A' AND ii.aula_paralelo_id = $id_aula_paralelo";
			$res_estudiantes_actividades_notas = $db->query($sql_estudiantes_actividades_notas)->fetch();

			//Armamos el array de las actividades separados por las materias, modo calificacion  y area calificacion
			$aActividadesEstudianteNota = array();
			foreach ($res_estudiantes_actividades_notas as $ean => $actividades_notas) {
				$aActividadesEstudianteNota[$actividades_notas['id_estudiante']][$actividades_notas['asesor_curso_actividad_id']] = $actividades_notas['nota'];
			}
		

			$suma_nota_promedio_curso = 0;
			foreach ($res_materia_area as $key_materias => $value_materias) {
				//Obtenemos el id de la materia
				$id_asignacion_docente = $value_materias['id_asignacion_docente'];

				$_area = $value_materias['descripcion'];
				//Listamos a los estudiantes de la materia
				//Obtenemos todos los estudiantes del curso
				$res_estudiantes_aula_paralelo = $db->query("SELECT e.id_estudiante, CONCAT(p.primer_apellido,' ',p.segundo_apellido,' ',p.nombres) AS nombre_completo
													FROM ins_inscripcion AS i
													INNER JOIN ins_estudiante AS e ON e.id_estudiante = i.estudiante_id
													INNER JOIN sys_persona AS p ON p.id_persona = e.persona_id 
													WHERE i.aula_paralelo_id = '$id_aula_paralelo' AND i.estado = 'A' AND i.gestion_id = '$id_gestion' AND area= '$_area'
													ORDER BY p.primer_apellido ASC")->fetch();
				$_nro_estudiantes = count($res_estudiantes_aula_paralelo);

				//Recorremos todos los estudiantes para poder sacar si nota en las diferentes areas
				foreach ($res_estudiantes_aula_paralelo as $eap => $estudiantes_aula_paralelo) {

					$_id_estudiante = $estudiantes_aula_paralelo['id_estudiante']; // este es el id estudiante que recorrermos por curso

					$_nota_obtenida_x_modo = 0;
					//recorremos las areas para distingir todas las tareas y sacar la nota promedio
					foreach ($res_areas_calificacion as $ac => $areas_calificacion) {

						//obtenemos el porderado por area
						$ponderado_x_area = $areas_calificacion['ponderado'];

						$_nota_obtenida_x_area = 0;

						//Obtenemos las actividades segun el modo y area de calificacion
						//Preguntamos si existe actividades en el modo y area de calificacion
						if (isset($aActividadesAulaParalelo[$id_asignacion_docente][$id_modo_calificacion][$areas_calificacion['id_area_calificacion']])) {
							$actividades_materia_regular = $aActividadesAulaParalelo[$id_asignacion_docente][$id_modo_calificacion][$areas_calificacion['id_area_calificacion']];

							$_cantidad_actividad_area = count($actividades_materia_regular);
							$_suma_nota_x_area = 0;
							foreach ($actividades_materia_regular as $item_actividad) {
								if (isset($aActividadesEstudianteNota[$_id_estudiante][$item_actividad])) {
									$_suma_nota_x_area = $_suma_nota_x_area + $aActividadesEstudianteNota[$_id_estudiante][$item_actividad];
								}
							}


							//preguntamos si es porderado o no
							if ($ponderado_calificacion == "A") {
								$_nota_obtenida_x_area = round(($_suma_nota_x_area / $_cantidad_actividad_area), 0);
							} else if ($ponderado_calificacion == "P") {
								//1ro sacamos el promedio
								$_nota_obtenida_x_area_parcial = round(($_suma_nota_x_area / $_cantidad_actividad_area), 0);
								$_nota_obtenida_x_area = round((($_nota_obtenida_x_area_parcial * $ponderado_x_area) / 100), 0);
							}
						}
						$_nota_obtenida_x_modo = $_nota_obtenida_x_modo + $_nota_obtenida_x_area;
					}

					//Sumamos las notas de todos los estudiantes
					$suma_nota_promedio_curso = $suma_nota_promedio_curso + $_nota_obtenida_x_modo;

					if ($id_estudiante == $_id_estudiante) {
						$aMaterias_hijo[$iMaterias_hijo]["id_asignacion_docente"]   = $value_materias['id_asignacion_docente'];
						$aMaterias_hijo[$iMaterias_hijo]["tipo_curso"]              = "A";
						$aMaterias_hijo[$iMaterias_hijo]["nombres_docente"]         = $value_materias['nombre_docente'];
						$aMaterias_hijo[$iMaterias_hijo]["nombre_materia"]          = $value_materias['nombre_materia'];
						$aMaterias_hijo[$iMaterias_hijo]["nota_materia_estudiante"] = $_nota_obtenida_x_modo;
					}
				}

				//Guardamos el promedio de nota obtenido con todos los estudiantes
				$aMaterias_hijo[$iMaterias_hijo]["nota_materia_promedio"]   = round(($suma_nota_promedio_curso / $_nro_estudiantes), 0);
				$iMaterias_hijo++;
				$suma_nota_promedio_curso = 0;
			}

			/****************** */
			/*   Fin de area    */
			/****************** */

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
