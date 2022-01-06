<?php
//http://localhost/educhecka/?/sitio/app-p-listar-materias-nota-hijo


//app-amateriaprom.php
/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK (MARCO QUINO)
 */


/*//LISTADO DE NOTAS DE UN ESTUDIANTE
SELECT mat.nombre_materia,nota.*,areas.ponderado FROM cal_estudiante_actividad_nota nota
                INNER JOIN cal_actividad_materia_modo_area amm ON amm.id_actividad_materia_modo_area=nota.actividad_materia_modo_area_id
                INNER JOIN cal_modo_calificacion_area_calificaion modoarea ON modoarea.id_modo_calificacion_area_calificacion=amm.modo_calificacion_area_calificacion_id
                INNER JOIN cal_area_calificacion areas ON areas.id_area_calificacion=modoarea.area_calificacion_id
                
					 INNER JOIN int_aula_paralelo_asignacion_materia apam ON apam.id_aula_paralelo_asignacion_materia=amm.aula_paralelo_asignacion_materia_id
                INNER JOIN pro_materia mat ON mat.id_materia=apam.materia_id
 WHERE amm.aula_paralelo_asignacion_materia_id=32
  AND nota.estudiante_id=365
  AND modoarea.modo_calificacion_id=1*/
//localhost/sitio/app-aprueba
// Define las cabeceras
header('Content-Type: application/json');

// Verifica la peticion post
 if (is_post()) {
	
	//var_dump($_POST);exit;

	// Verifica la existencia de datos
	if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
		//Obtiene los datos
		$usuario 				= clear($_POST['usuario']);//
        $contrasenia 			= clear($_POST['contrasenia']);//
		$estudiante_id 		= clear($_POST['estudiante_id']); //365
		$modo_id 		= clear($_POST['modo_id']);   // 1   
		$aula_paralelo_id 		= clear($_POST['aula_paralelo_id']); //3      
       
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
		$fecha_actual = Date('Y-m-d');
		/*$_modo_calificacion = $db->select('mc.id_modo_calificacion, mc.fecha_inicio, mc.fecha_final, mc.descripcion')->from('cal_modo_calificacion mc')->where('mc.fecha_inicio <=', $fecha_actual)->where('mc.fecha_final >=', $fecha_actual)->where('mc.gestion_id', $_gestion['id_gestion'])->fetch_first();*/

		
		// Verifica la existencia del usuario 
		if ($usuario) {
            //for materias actividad_materia_modo_area_id
            
            //listado areas
            	$areas = $db->query("SELECT id_area_calificacion,descripcion,ponderado FROM cal_area_calificacion
                WHERE gestion_id=1 AND estado='A'")->fetch();
            //var_dump($areas);exit();ponderado id_area_calificacion
            
                
                $materias = $db->query("SELECT mat.*,per.*,asi.id_asignacion,apam.id_aula_paralelo_asignacion_materia FROM cal_estudiante_actividad_nota nota
                INNER JOIN cal_actividad_materia_modo_area amm ON amm.id_actividad_materia_modo_area=nota.actividad_materia_modo_area_id
                INNER JOIN int_aula_paralelo_asignacion_materia apam ON apam.id_aula_paralelo_asignacion_materia=amm.aula_paralelo_asignacion_materia_id
                INNER JOIN pro_materia mat ON mat.id_materia=apam.materia_id
                INNER JOIN per_asignaciones asi ON asi.id_asignacion=apam.asignacion_id
                INNER JOIN sys_persona  per ON per.id_persona=asi.persona_id

                WHERE apam.aula_paralelo_id=$aula_paralelo_id
                AND nota.estudiante_id=$estudiante_id  
                GROUP BY apam.id_aula_paralelo_asignacion_materia")->fetch();//apam.*,
           //var_dump($materias);//exit(); 
            $materias_notas=array();
            foreach ($materias as $key => $valuemat) {
                  $nombre_materia=$valuemat['nombre_materia'];
                 $id_materia_asig=$valuemat['id_aula_paralelo_asignacion_materia'];
                $id_asignacion_mat=$valuemat['id_aula_paralelo_asignacion_materia'];
               
                 $promcur = $db->query("SELECT AVG(nota.nota_cuantitativa)AS promCurso,mat.nombre_materia
                    FROM `cal_estudiante_actividad_nota` nota
                    INNER JOIN cal_actividad_materia_modo_area act ON act.`id_actividad_materia_modo_area`=nota.`actividad_materia_modo_area_id`
                    INNER JOIN `int_aula_paralelo_asignacion_materia` apam ON apam.`id_aula_paralelo_asignacion_materia`=act.`aula_paralelo_asignacion_materia_id`
                    INNER JOIN pro_materia mat ON mat.id_materia=apam.materia_id
                      INNER JOIN `cal_modo_calificacion_area_calificaion` modo ON modo.`id_modo_calificacion_area_calificacion`=act.`modo_calificacion_area_calificacion_id` 
                 WHERE apam.aula_paralelo_id=$aula_paralelo_id 
					  AND  modo.`modo_calificacion_id`=$modo_id   
					  AND apam.id_aula_paralelo_asignacion_materia=$id_materia_asig")->fetch_first();
                
                $promCurso=$promcur['promCurso'];
                //$Materia=$promcur['nombre_materia']
                
                $promedioArea=0;
                $promedioMateria=0;
                foreach ($areas as $key => $valueArea) {	
                $area_calificacion=$valueArea['id_area_calificacion'];
                //    var_dump($area_calificacion.'--------'.$valuemat['nombre_materia']);
                $ponderacion=$valueArea['ponderado'];
                //Consultamos las notas de cada area
                $notas = $db->query("SELECT apam.aula_paralelo_id,nota.estudiante_id,
                mat.nombre_materia,
                nota.`nota_cuantitativa`,
                nota.`nota_cualitativa`,nota.`estudiante_id`,act.`nombre_actividad`,act.`id_actividad_materia_modo_area`,apam.`id_aula_paralelo_asignacion_materia`,apam.`aula_paralelo_id`
                    ,apam.`asignacion_id`,apam.`materia_id`

                    FROM `cal_estudiante_actividad_nota` nota
                    INNER JOIN cal_actividad_materia_modo_area act ON act.`id_actividad_materia_modo_area`=nota.`actividad_materia_modo_area_id`
                    INNER JOIN `int_aula_paralelo_asignacion_materia` apam ON apam.`id_aula_paralelo_asignacion_materia`=act.`aula_paralelo_asignacion_materia_id`
                    INNER JOIN pro_materia mat ON mat.id_materia=apam.materia_id
                      INNER JOIN `cal_modo_calificacion_area_calificaion` modo ON modo.`id_modo_calificacion_area_calificacion`=act.`modo_calificacion_area_calificacion_id`

                 WHERE apam.aula_paralelo_id=$aula_paralelo_id AND  modo.`modo_calificacion_id`=$modo_id AND nota.estudiante_id=$estudiante_id AND modo.area_calificacion_id=$area_calificacion 
                    AND apam.id_aula_paralelo_asignacion_materia=$id_materia_asig")->fetch();
                   //var_dump($notas);//exit();
                  $cc=0; $sumaNotas=0;
                  foreach ($notas as $value) {
                    if($value['nota_cuantitativa']!=0 && $value['nota_cuantitativa']!=''){
                    $sumaNotas	= $sumaNotas+$value['nota_cuantitativa'];//diferente a 0
                    //echo('Nota'.$cc.':'.$value['nota_cuantitativa'].' -');
                    $cc++;
                    }
                        

                  }//fin notas
                  if($cc){
                       $promedioArea=(($sumaNotas/$cc)*$ponderacion/100);//ponderar
                    }else{
                       $promedioArea=0;
                    }
                   //var_dump('_______pro_ponderado('.$ponderacion.'):'.$promedioArea);
                    
                    $promedioMateria=$promedioMateria+$promedioArea;
                    
                }//fin areas
                
                // var_dump('------_________________________________Prom MAt_'.$nombre_materia.'----'.$promedioMateria);
                 // var_dump($nombre_materia.':'.$promedioMateria);
                
                 $notasMat = array( 
                    'estudiante_id' => $estudiante_id,//$estudiantes_cursos['estudiante_id'], 
                    'nombre_materia' => $nombre_materia, 
                    'promedioMateria' => round($promedioMateria),
                    'promedioCurso' => round($promCurso),
                    'id_asignacion' =>$valuemat['id_asignacion'],
                    'id_persona' =>$valuemat['id_persona'],
                    'nombres_docente' =>$valuemat['nombres'].' '.$valuemat['primer_apellido'].' '.$valuemat['segundo_apellido'],
                    //'primer_apellido' =>,
                    //'segundo_apellido' =>,
                    
                    'materiacolor' => $valuemat['color_materia'],
                    'materiaimagen' => $valuemat['imagen_materia']
                    
                );
                array_push($materias_notas,$notasMat);
                
                
            }//for materias
             //  
          // var_dump($materias_notas);    exit();   
                
                
                
                
//var_dump($promedio.' '.);

        //}//for areas
 
			
			// Instancia el objeto que devolvera la web service			
			$respuesta = array(
				'estado' => 's',
				'notasMateria' => $materias_notas					
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
