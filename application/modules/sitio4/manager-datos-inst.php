<?php
//http://localhost/PROYECTOS/32%20CHECKCODE/educhecka/?/sitio/app-p-listar-hijos
//http://localhost/PROYECTOS/32 CHECKCODE/5manager/?/sitio/manager-datos-inst
/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK (MARCO QUINO)
 */


//echo('hola');exit();

//localhost/sitio/app-aprueba
// Define las cabeceras/
header('Access-Control-Allow-Origin: *');
//Content-Type: application/jsonp');
//echo 'hola';exit();
// Verifica la peticion post
 if (is_post()) {
	
	 //var_dump($_POST);exit;

	// Verifica la existencia de datos
	if (isset($_POST['var1']) && isset($_POST['var2'])) {
		$id_aula 			= clear($_POST['id_aula']);
        $id_nivel_academico = clear($_POST['id_nivel_academico']);
        $precio = clear($_POST['pres']);
        //obtiene el a���o actual
		 $anio_actual = Date('Y');
        
        
			//Consultamos   noo where ins.id_inscripcion_institucion=$id
            $_gestion = $db->select('z.id_gestion, z.gestion')->from('ins_gestion z')->where('gestion', $anio_actual)->fetch_first();	
            $id_gestion=$_gestion['id_gestion'];
        
			$ress = $db->query("SELECT ins.nombre,ins.razon_social,ins.direccion,ins.telefono,ins.correo,ins.latlng FROM sys_instituciones ins")->fetch();	
         $estadTurnos = array();
        //for turnos
        $turnos = $db->query("SELECT * FROM  `ins_turno` WHERE estado='A' and gestion_id=$id_gestion;")->fetch();
        
        foreach($turnos as $rowt){
            
       $id_turno= $rowt['id_turno'];

		$cap = $db->query("SELECT IFNULL(COUNT(i.aula_paralelo_id),0) AS contador , ap.capacidad, IFNULL(ap.capacidad-COUNT(i.aula_paralelo_id),ap.capacidad) vacantes,
            p.nombre_paralelo, a.nombre_aula, na.nombre_nivel,t.id_turno,t.nombre_turno
            FROM ins_aula_paralelo ap 
            INNER JOIN  ins_paralelo p ON p.id_paralelo=ap.paralelo_id
             INNER JOIN  ins_turno t ON t.id_turno=ap.turno_id
            INNER JOIN  ins_aula a ON a.id_aula=ap.aula_id
            INNER JOIN  ins_nivel_academico na ON a.nivel_academico_id=na.id_nivel_academico
            INNER JOIN  ins_inscripcion i ON ap.id_aula_paralelo=i.aula_paralelo_id
            WHERE i.gestion_id = $id_gestion AND a.id_aula=$id_aula AND na.id_nivel_academico=$id_nivel_academico AND ap.turno_id=$id_turno
            GROUP BY i.aula_paralelo_id
            ORDER BY na.id_nivel_academico, a.id_aula, p.nombre_paralelo ASC")->fetch();
        $ins=0; $capacidad=0; $vac=0;$mens=0;$nombre_turno='';$turno_id=0;
        foreach($cap as $row){
        $ins=$ins+$row['contador'];
        $capacidad=$capacidad+$row['capacidad'];
        $nombre_turno=$row['nombre_turno'];
        $turno_id=$row['id_turno'];  
        }
            if($turno_id!=0){
                
            $estad = array(
				'capacidad' => $capacidad,					
				'inscritos' => $ins,					
				'vacantes' =>  ($capacidad-$ins),
				'nombre_paralelo' => $row['nombre_paralelo'],
				/*'nombre_aula' => $row['nombre_aula'], 
				'nombre_nivel' => $row['nombre_nivel'], */
				'nombre_turno' => $nombre_turno, 
				'turno_id' => $turno_id
			);
            array_push($estadTurnos,$estad);
            }
         }
        //var_dump($estadTurnos);exit();
        
        
        if($precio!='n'){
            //buscar la mensualidad
			$resMensualidad = $db->query("SELECT  avg(pd.monto)AS mes FROM pen_pensiones p INNER JOIN pen_pensiones_detalle pd ON p.id_pensiones = pd.id_pensiones_detalle WHERE p.estado ='A' AND p.gestion_id = $id_gestion AND p.nombre_pension != 'RESERVA' AND p.nombre_pension = 'MENSUALIDAD' AND  p.nivel_academico_id = $id_nivel_academico GROUP BY p.nivel_academico_id ORDER BY p.nombre_pension")->fetch_first();
            $mens='Menusalidad: '.intval($resMensualidad['mes']).' Bs.(aprox)'; 
        }else{
          $mens='N/D'; 
        }
			// Instancia el objeto que devolvera la web service			
			$respuesta = array(
				'estado' => 's',
				'datos' => $ress,					
				'turnosdatos' => $estadTurnos,					
				'Mensualidad' => $mens					
			);
        /*$respuesta = array(
				'estado' => 's',
				'datos' => $ress,					
				'capacidad' => $capacidad,					
				'inscritos' => $ins,					
				'vacantes' => $vac,					
				'Mensualidad' => $mens					
			);*/

			// Devuelve los resultados
			echo json_encode($respuesta);
		 
	} else {
	// 	// Devuelve los resultados
	   echo json_encode(array('estado' => 'n usuario'));
	}
 } else {
// 	// Devuelve los resultados
  echo json_encode(array('estado' => 'npost'));
 }
