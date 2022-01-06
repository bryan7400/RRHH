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


for ($i = 1; $i < 13; $i++) {

    $array_.$i = array();

foreach ($feriados as $nro => $feriado){


$month_ini = date("m",strtotime($feriado['fecha_inicio'])); 
$month_fin = date("m",strtotime($feriado['fecha_final'])); 

		
	if ($month_ini == $i){




			

			$feriados_nombres = array(
				
				'id_agenda' => $feriado["id_agenda"],
				
				'titulo' => $feriado["titulo"],
				'fecha' => $feriado["fecha_inicio"],
				
					// code...
			
								
			);


			array_push($array_.$i , $feriados_nombres);


			

	}

}

}







$mes1="Enero";
$mes2="Febrero";	
$mes3="Marzo";	
$mes4="Abril";	
$mes5="Mayo";	
$mes6="Junio";	
$mes7="Julio";	
$mes8="Agosto";
$mes9="Septiembre";	
$mes10="Octubre";	
$mes11="Noviembre";	
$mes12="Diciembre";	


for ($i = 1; $i < 13; $i++) {




$agenda.$i = array(
				

				'Mes' => $mes.$i,
				'Actividad' => $array_.$i,
					
				
					// code...
		
								
			);	

}

$merge = array_merge($agenda1, $agenda2, $agenda3, $agenda4, $agenda5, $agenda6, $agenda7, $agenda8, $agenda9, $agenda10, $agenda11, $agenda12);

$array_final = array();	
	array_push($array_final, $merge);

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
			

			echo json_encode($respuesta2);


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
