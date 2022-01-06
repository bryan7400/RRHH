<?php
//http://localhost/educhecka/?/sitio/app-p-listar-notificaciones-hijo
//usuario:tut
//contrasenia:admin
//id_estudiante:365
// genero:m  

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK (MARCO QUINO)
 */




//localhost/sitio/app-aprueba
// Define las cabeceras
header('Content-Type: application/json');

// Verifica la peticion post
 if (is_post()) {
	
	//var_dump($_POST);exit;

	// Verifica la existencia de datos
	if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
		//Obtiene los datos
		$usuario 				= clear($_POST['usuario']);
        $contrasenia 			= clear($_POST['contrasenia']);
		$estudiante_id 		= clear($_POST['id_estudiante']);//       
		$genero 		= clear($_POST['genero']);//       
		$aula_paralelo_id 		= clear($_POST['aula_paralelo_id']);//    
       
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
 
		
		// Verifica la existencia del usuario 
		if ($usuario) {
            //datos de estudiante
            
            
			//Consultamos las areas de calificacion 
			$comunicados = $db->query("SELECT mat.nombre_materia, concat(per.nombres,' ',per.primer_apellido,' ',per.segundo_apellido)as nombre_docente, com.* FROM ins_comunicados com
INNER JOIN int_aula_paralelo_asignacion_materia apam ON apam.id_aula_paralelo_asignacion_materia=com.aula_paralelo_asignacion_materia_id
 INNER JOIN pro_materia mat ON mat.id_materia=apam.materia_id
INNER JOIN per_asignaciones asi ON asi.id_asignacion=apam.asignacion_id
INNER JOIN sys_persona  per ON per.id_persona=asi.persona_id

                WHERE grupo != '0' 
                AND apam.aula_paralelo_id=$aula_paralelo_id
                AND com.estado='A' 
                ORDER BY com.fecha_inicio asc")->fetch();	
            $comunicadosEst=array();
            foreach ($comunicados as $key => $value) {
                //$nombre_evento=$value['nombre_evento'];
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
                    
                }
                    
                
                
                
                if($siagregar){
                    
                $estudiante =$value;
                //En caso de crear propio array
                //    array( 
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
               // exit();
			
			// Instancia el objeto que devolvera la web service			
			$respuesta = array(
				'estado' => 's',
				'comunicados' => $comunicadosEst					
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
