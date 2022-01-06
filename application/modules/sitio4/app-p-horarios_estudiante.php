<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK Maribel Marco Luis
 */


header('Content-Type: application/json');

// Verifica la peticion post
 if (is_post()) {
	

	// Verifica la existencia de datos
	if (isset($_POST['usuario']) && isset($_POST['contrasenia']) && isset($_POST['id_gestion'])) {
		//Obtiene los datos
		$usuario 				= clear($_POST['usuario']);
        $contrasenia 			= clear($_POST['contrasenia']);
		$id_gestion     		= clear($_POST['id_gestion']);
		$mes     		= clear($_POST['mes']);  




		$aula=isset($_POST['aula'])?$_POST['aula']:0;//turno
    $turno=isset($_POST['turno'])?$_POST['turno']:0;//turno
    $nivel=isset($_POST['nivel'])?$_POST['nivel']:0;//turno
    $paralelo=isset($_POST['paralelo'])?$_POST['paralelo']:0;//paralelo
   $hora_id=isset($_POST['hora_inicio'])?$_POST['hora_inicio']:0;//     


       
		// Encripta la contraseña para compararla en la base de datos
		$usuario    = md5($usuario);
		$contrasenia = encrypt($contrasenia);
		
		
		// Obtiene los datos del usuario
		$usuario = $db->select('*')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();

        $id_persona = 	$usuario['persona_id'];

		//obtiene los datos del modo de calificacion actual
		$fecha_actual = Date('Y-m-d');
		
		// Verifica la existencia del usuario 
		if ($usuario) {

			//Consultamos las areas de calificacion 
					


		$array_enero = array();	

$feriados = $db->query("SELECT * FROM ins_agenda_institucional WHERE estado = 'A'")->fetch();
			
foreach ($feriados as $nro => $feriado){


$month_ini = date("m",strtotime($feriado['fecha_inicio'])); 
$month_fin = date("m",strtotime($feriado['fecha_final'])); 

		
	if ($month_ini == 1 ){




			

			$feriados_nombres = array(
				
				'id_agenda' => $feriado["id_agenda"],
				
				'titulo' => $feriado["titulo"],
				'fecha' => $feriado["fecha_inicio"],
				
					// code...
			
								
			);


			array_push($array_enero, $feriados_nombres);


			

	}

	

		
}



$array_febrero = array();	


			
foreach ($feriados as $nro => $feriado){


$month_ini = date("m",strtotime($feriado['fecha_inicio'])); 
$month_fin = date("m",strtotime($feriado['fecha_final'])); 

		
	if ($month_ini == 2){




			

			$feriados_nombres = array(
				
				'id_agenda' => $feriado["id_agenda"],
				
				'titulo' => $feriado["titulo"],
				'fecha' => $feriado["fecha_inicio"],
				
					// code...
			
								
			);


			array_push($array_febrero, $feriados_nombres);


			

	}

	


}


		
$array_marzo = array();

foreach ($feriados as $nro => $feriado){


$month_ini = date("m",strtotime($feriado['fecha_inicio'])); 
$month_fin = date("m",strtotime($feriado['fecha_final'])); 

		
	if ($month_ini == 3){




			

			$feriados_nombres = array(
				
				'id_agenda' => $feriado["id_agenda"],
				
				'titulo' => $feriado["titulo"],
				'fecha' => $feriado["fecha_inicio"],
				
					// code...
			
								
			);


			array_push($array_marzo, $feriados_nombres);


			

	}

	


}



$array_abril = array();

foreach ($feriados as $nro => $feriado){


$month_ini = date("m",strtotime($feriado['fecha_inicio'])); 
$month_fin = date("m",strtotime($feriado['fecha_final'])); 

		
	if ($month_ini == 4){




			

			$feriados_nombres = array(
				
				'id_agenda' => $feriado["id_agenda"],
				
				'titulo' => $feriado["titulo"],
				'fecha' => $feriado["fecha_inicio"],
				
					// code...
			
								
			);


			array_push($array_abril, $feriados_nombres);


			

	}

	


}




$array_mayo = array();

foreach ($feriados as $nro => $feriado){


$month_ini = date("m",strtotime($feriado['fecha_inicio'])); 
$month_fin = date("m",strtotime($feriado['fecha_final'])); 

		
	if ($month_ini == 5){




			

			$feriados_nombres = array(
				
				'id_agenda' => $feriado["id_agenda"],
				
				'titulo' => $feriado["titulo"],
				'fecha' => $feriado["fecha_inicio"],
				
					// code...
			
								
			);


			array_push($array_mayo, $feriados_nombres);


			

	}

	


}




$array_junio = array();

foreach ($feriados as $nro => $feriado){


$month_ini = date("m",strtotime($feriado['fecha_inicio'])); 
$month_fin = date("m",strtotime($feriado['fecha_final'])); 

		
	if ($month_ini == 6){




			

			$feriados_nombres = array(
				
				'id_agenda' => $feriado["id_agenda"],
				
				'titulo' => $feriado["titulo"],
				'fecha' => $feriado["fecha_inicio"],
				
					// code...
			
								
			);


			array_push($array_junio, $feriados_nombres);


			

	}

	


}





$array_julio = array();

foreach ($feriados as $nro => $feriado){


$month_ini = date("m",strtotime($feriado['fecha_inicio'])); 
$month_fin = date("m",strtotime($feriado['fecha_final'])); 

		
	if ($month_ini == 7){




			

			$feriados_nombres = array(
				
				'id_agenda' => $feriado["id_agenda"],
				
				'titulo' => $feriado["titulo"],
				'fecha' => $feriado["fecha_inicio"],
				
					// code...
			
								
			);


			array_push($array_julio, $feriados_nombres);


			

	}

	


}



$array_agosto = array();

foreach ($feriados as $nro => $feriado){


$month_ini = date("m",strtotime($feriado['fecha_inicio'])); 
$month_fin = date("m",strtotime($feriado['fecha_final'])); 

		
	if ($month_ini == 8){




			

			$feriados_nombres = array(
				
				'id_agenda' => $feriado["id_agenda"],
				
				'titulo' => $feriado["titulo"],
				'fecha' => $feriado["fecha_inicio"],
				
					// code...
			
								
			);


			array_push($array_agosto, $feriados_nombres);


			

	}

	


}



$array_septiembre = array();

foreach ($feriados as $nro => $feriado){


$month_ini = date("m",strtotime($feriado['fecha_inicio'])); 
$month_fin = date("m",strtotime($feriado['fecha_final'])); 

		
	if ($month_ini == 9){




			

			$feriados_nombres = array(
				
				'id_agenda' => $feriado["id_agenda"],
				
				'titulo' => $feriado["titulo"],
				'fecha' => $feriado["fecha_inicio"],
				
					// code...
			
								
			);


			array_push($array_septiembre, $feriados_nombres);


			

	}

	


}



$array_octubre = array();

foreach ($feriados as $nro => $feriado){


$month_ini = date("m",strtotime($feriado['fecha_inicio'])); 
$month_fin = date("m",strtotime($feriado['fecha_final'])); 

		
	if ($month_ini == 10){




			

			$feriados_nombres = array(
				
				'id_agenda' => $feriado["id_agenda"],
				
				'titulo' => $feriado["titulo"],
				'fecha' => $feriado["fecha_inicio"],
				
					// code...
			
								
			);


			array_push($array_octubre, $feriados_nombres);


			

	}

	


}



$array_noviembre = array();

foreach ($feriados as $nro => $feriado){


$month_ini = date("m",strtotime($feriado['fecha_inicio'])); 
$month_fin = date("m",strtotime($feriado['fecha_final'])); 

		
	if ($month_ini == 11){




			

			$feriados_nombres = array(
				
				'id_agenda' => $feriado["id_agenda"],
				
				'titulo' => $feriado["titulo"],
				'fecha' => $feriado["fecha_inicio"],
				
					// code...
			
								
			);


			array_push($array_noviembre, $feriados_nombres);


			

	}

	


}



$array_diciembre = array();

foreach ($feriados as $nro => $feriado){


$month_ini = date("m",strtotime($feriado['fecha_inicio'])); 
$month_fin = date("m",strtotime($feriado['fecha_final'])); 

		
	if ($month_ini == 12){




			

			$feriados_nombres = array(
				
				'id_agenda' => $feriado["id_agenda"],
				
				'titulo' => $feriado["titulo"],
				'fecha' => $feriado["fecha_inicio"],
				
					// code...
			
								
			);


			array_push($array_diciembre, $feriados_nombres);


			

	}

}




$agenda = array(
				'Mes' => "Enero",
				'Actividad' => $array_enero					
			);

$agenda2 = array(
				'Mes' => "Febrero",
				'Actividad' => $array_febrero					
			);

$agenda3 = array(
				'Mes' => "Marzo",
				'Actividad' => $array_marzo					
			);


$agenda4 = array(
				'Mes' => "Abril",
				'Actividad' => $array_abril					
			);

$agenda5 = array(
				'Mes' => "Mayo",
				'Actividad' => $array_mayo					
			);
	
$agenda6 = array(
				'Mes' => "Junio",
				'Actividad' => $array_junio					
			);


$agenda7 = array(
				'Mes' => "Julio",
				'Actividad' => $array_julio					
			);

$agenda8 = array(
				'Mes' => "Agosto",
				'Actividad' => $array_agosto					
			);

$agenda9 = array(
				'Mes' => "Septiembre",
				'Actividad' => $array_septiembre					
			);


$agenda10 = array(
				'Mes' => "Octubre",
				'Actividad' => $array_octubre					
			);

$agenda11 = array(
				'Mes' => "Noviembre",
				'Actividad' => $array_noviembre					
			);

$agenda12 = array(
				'Mes' => "Diciembre",
				'Actividad' => $array_diciembre					
			);






$array_final = array();	
array_push($array_final, $agenda, $agenda2, $agenda3, $agenda4, $agenda5, $agenda6, $agenda7, $agenda8, $agenda9, $agenda10, $agenda11, $agenda12);

		if ($feriados=="") {
				$respuesta = array(
				'estado' => 'n',
				'error' => $feriados
					// code...
			
								
			);
		}else{

			$respuesta = array(
				'Actividades' => $agenda,
					// code...
			
								
			);
			$respuesta2 = array(
				'estado' => 's',
				'Agenda' => $array_final				
					// code...
			
								
			);	

		}	






		 $sql="";
    if($aula){
        $sql.=" and  b.paralelo_id=$aula";
    }
    if($aula){
        $sql.=" and  apam.aula_paralelo_id=$aula";
    }
 
    

$consulta="
SELECT d.*, hd.*, z.*, m.*,hd.`complemento`,apam.*,

    (SELECT GROUP_CONCAT(CONCAT(per.nombres ,' ', per.primer_apellido,' ', per.segundo_apellido)
	SEPARATOR ' | ')AS nombres_completo  
    FROM  per_asignaciones asig,sys_persona  per
	WHERE 
	asig.persona_id=per.id_persona AND
	apam.asignacion_id=asig.id_asignacion 
	)AS nombres_doc,
	
    (SELECT GROUP_CONCAT(CONCAT(SUBSTRING(per.nombres, 1, 1),' ', SUBSTRING(per.primer_apellido, 1, 1),' ', SUBSTRING(per.segundo_apellido, 1, 1))
	SEPARATOR ' | ')AS nombres_completo  
    FROM  per_asignaciones asig,sys_persona  per
	WHERE 
	asig.persona_id=per.id_persona AND
	apam.asignacion_id=asig.id_asignacion  
	)AS iniciales


  FROM ins_horario_profesor_materia z,
  
	ins_aula_paralelo b,
	ins_aula c,
	ins_nivel_academico d,
	ins_paralelo e,
	ins_turno tu,
	pro_materia m,
	ins_horario_dia hd,
	ins_gestion ge,
	int_aula_paralelo_asignacion_materia apam
WHERE apam.aula_paralelo_id=b.id_aula_paralelo AND 
	tu.id_turno=b.turno_id AND
	b.aula_id=c.id_aula AND
	c.nivel_academico_id= d.id_nivel_academico AND
	b.paralelo_id= e.id_paralelo   AND
	apam.`materia_id`=m.`id_materia` AND
	z.`horario_dia_id`=hd.`id_horario_dia` AND 
	apam.`id_aula_paralelo_asignacion_materia`=z.`aula_paralelo_asignacion_materia_id` AND 
	 
	z.estado='A'AND 
	b.estado='A' AND
	ge.estado='A'AND 
	c.estado='A'AND 
	e.estado_paralelo='A' AND 
	tu.estado='A'AND 
	d.estado='A'AND
	hd.estado='A'AND
	m.estado='A' AND
	apam.estado='A'
	 
     ".$sql." AND
   z.gestion_id=$id_gestion 
	ORDER BY z.`horario_dia_id` 
 ";   

 $inscritos = $db->query($consulta)->fetch();

			

			echo json_encode($inscritos);


			// Devuelve los resultados
			
		} else {
			// Devuelve los resultados
			echo json_encode(array('estado' => 'n'));
		}
	} else {
	// 	// Devuelve los resultados
	   echo json_encode(array('estado' => 'nfp'));
	}
} else {
// 	// Devuelve los resultados
    echo json_encode(array('estado' => 'np'));
}
