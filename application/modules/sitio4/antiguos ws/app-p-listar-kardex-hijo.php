<?php
//http://localhost/educhecka/?/sitio/app-p-listar-kardex-hijo
// http://localhost/educhecka/?/sitio/app-p-listar-kardex-hijo

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK (MARCO QUINO)
 */

 
header('Content-Type: application/json'); 
// Verifica la peticion post
 if (is_post()) {
 
	// Verifica la existencia de datos
	if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
		//Obtiene los datos
		$usuario 				= clear($_POST['usuario']);
        $contrasenia 			= clear($_POST['contrasenia']);
		//$estudiante_id 		= clear($_POST['id_estudiante']);//       
		//$genero 		= clear($_POST['genero']);//       
		//$aula_paralelo_id 		= clear($_POST['aula_paralelo_id']);//       
		$id_inscripcion = $_POST['id_inscripcion'];
        $fecha=clear($_POST['fecha']);
		$fecha = new DateTime($fecha);//'2020-03-16'
        $fechaRes =$fecha->format('Y-m-d');
       //var_dump($fechaRes);exit();
		// Encripta la contraseña para compararla en la base de datos
		$usuario    = md5($usuario);
		$contrasenia = encrypt($contrasenia);
		
		//obtiene el año actual
		$anio_actual = Date('Y');

		//obtiene los datos de la gestion actual
		$_gestion = $db->select('z.id_gestion, z.gestion')->from('ins_gestion z')->where('gestion', $anio_actual)->fetch_first();	
		
		// Obtiene los datos del usuario
		$usuario = $db->select('id_user')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();

		//obtiene los datos del modo de calificacion actual
		//$fecha_actual = Date('Y-m-d');
		 
		// Verifica la existencia del usuario 
		if ($usuario) {
             
			//Consultamos las areas de calificacion 
       
            $sql ="SELECT mat.nombre_materia,concat(per.nombres,' ',per.primer_apellido,' ',per.segundo_apellido)as nombre_docente, fe.fecha_felicitacion AS fecha ,fe.id_felicitaciones AS id_evento,fe.motivo,ar.inscripcion_id,1 AS tipo,(fe.descripcion) AS descripcion
            FROM arc_felicitaciones fe
            INNER JOIN arc_archivo ar ON ar.id_archivo= fe.archivo_id
            INNER JOIN int_aula_paralelo_asignacion_materia apam ON apam.id_aula_paralelo_asignacion_materia=fe.profesor_materia_id
             INNER JOIN pro_materia mat ON mat.id_materia=apam.materia_id
				INNER JOIN per_asignaciones asi ON asi.id_asignacion=apam.asignacion_id
				INNER JOIN sys_persona  per ON per.id_persona=asi.persona_id
           WHERE ar.inscripcion_id = $id_inscripcion and fe.fecha_felicitacion='$fechaRes'
            
            UNION ALL

             SELECT mat.nombre_materia,concat(per.nombres,' ',per.primer_apellido,' ',per.segundo_apellido)as nombre_docente,sa.fecha_sancion AS fecha,id_sancion AS id_evento,motivo,ar.inscripcion_id,3 AS tipo,
            CONCAT('Disas suspencion:',sa.dias_suspencion,' - para el:',sa.fecha_traer_tutor)  AS descripcion
            FROM arc_sanciones sa
            INNER JOIN arc_archivo ar ON ar.id_archivo= sa.archivo_id
            INNER JOIN int_aula_paralelo_asignacion_materia apam ON apam.id_aula_paralelo_asignacion_materia=sa.profesor_materia_id
             INNER JOIN pro_materia mat ON mat.id_materia=apam.materia_id
				INNER JOIN per_asignaciones asi ON asi.id_asignacion=apam.asignacion_id
				INNER JOIN sys_persona  per ON per.id_persona=asi.persona_id
            WHERE ar.inscripcion_id = $id_inscripcion and sa.fecha_sancion='$fechaRes'
            
            UNION ALL
            
            
            SELECT mat.nombre_materia,concat(per.nombres,' ',per.primer_apellido,' ',per.segundo_apellido)as nombre_docente, ci.fecha_envio AS fecha,id_citacion AS id_evento,motivo,ar.inscripcion_id,2 AS tipo,CONCAT ('El tutor debe apersonarse para la fecha: ' ,ci.fecha_asistencia) AS descripcion
            FROM arc_citaciones ci
            INNER JOIN arc_archivo ar ON ar.id_archivo= ci.archivo_id
            INNER JOIN int_aula_paralelo_asignacion_materia apam ON apam.id_aula_paralelo_asignacion_materia=ci.profesor_materia_id
             INNER JOIN pro_materia mat ON mat.id_materia=apam.materia_id
				INNER JOIN per_asignaciones asi ON asi.id_asignacion=apam.asignacion_id
				INNER JOIN sys_persona  per ON per.id_persona=asi.persona_id
            WHERE ar.inscripcion_id = $id_inscripcion and ci.fecha_envio='$fechaRes'
            ORDER BY fecha ASC ";
            
			$kardex = $db->query($sql)->fetch();	
            
          
            
            /*
            $comunicadosEst=array();
            foreach ($comunicados as $key => $value) { 
                $tipo=$value['grupo'];
                
                $siagregar=false;
                if($tipo=='selec'){
                     $persona_ids=$value['persona_id'];
                   $arr_persona= explode(",", $persona_ids);
                    foreach ($arr_persona as $key => $row) { 
                        
                         if($row==$estudiante_id){
                           // var_dump('::::::'.$row);
                             $siagregar=true;
                         }
                    }
                    
                    
                }else if($tipo=='t'){//todos
                    //todos
                    $siagregar=true;
                }else{
                     
                    if($genero==$value['grupo']){//$value['grupo']
                        $siagregar=true;
                        
                        
                    } 
                    //var_dump($nombre_evento.' '.$tipo);
                }
                    
                
                
                
                if($siagregar){
                    
                $estudiante =$value;//array( 
                  //  $value//
                   // 'estudiante_id' => $estudiante_id,
                    // 'nombre_evento' => $value['nombre_evento'],
                    // 'nombre_evento' => $value['nombre_evento'] 
                    //'promedioMateria' => $promedioMateria,
                    //'promedioCurso' => $promCurso,
                    //'materiacolor' => $valuemat['color_materia'],
                    //'materiaimagen' => $valuemat['imagen_materia']
                    
                //);
                array_push($comunicadosEst,$estudiante);
                }
             
               // var_dump($comunicadosEst);
            }
              */
			// Instancia el objeto que devolvera la web service			
			$respuesta = array(
				'estado' => 's',
				'conducta' => $kardex					
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
