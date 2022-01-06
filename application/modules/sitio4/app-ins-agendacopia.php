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
					


		$array_final = array();	

$feriados = $db->query("SELECT * FROM ins_agenda_institucional WHERE estado = 'A'")->fetch();
			
foreach ($feriados as $nro => $feriado){


$month_ini = date("m",strtotime($feriado['fecha_inicio'])); 
$month_fin = date("m",strtotime($feriado['fecha_final'])); 

		
	if ($month_ini == $mes || $month_fin== $mes){



			switch ($mes) {
			    case 1:
			        $mes_nombre= "Enero";
			        break;
			    case 2:
			        $mes_nombre= "Febrero";
			        break;
			    case 3:
			        $mes_nombre= "Marzo";
			        break;
			    case 4:
			        $mes_nombre= "Abril";
			        break;
			    case 5:
			        $mes_nombre= "Mayo";
			        break;
			    case 6:
			        $mes_nombre= "Junio";
			        break;
			    case 7:
			        $mes_nombre= "Julio";
			        break;
			    case 8:
			        $mes_nombre= "Agosto";
			        break;
			    case 9:
			        $mes_nombre= "Septiembre";
			        break;
			    case 10:
			        $mes_nombre= "
			        octubre";
			        break;
			    case 11:
			        $mes_nombre= "Noviembre";
			        break;
			    case 12:
			        $mes_nombre= "Diciembre";
			        break;
			    
			}

			$agenda = array(
				
				
				'Agenda' => $mes_nombre,
				
					// code...
			
								
			);	

			$feriados_nombres = array(
				
				'id_agenda' => $feriado["id_agenda"],
				'mes' => $mes_nombre,
				'titulo' => $feriado["titulo"],
				'fecha' => $feriado["fecha_inicio"],
				
					// code...
			
								
			);


			array_push($array_final, $feriados_nombres);


			array_push($array_final, $feriados_nombres);

	}

	

		
}


		


		if ($feriados=="") {
				$respuesta = array(
				'estado' => 'n',
				'error' => $feriados
					// code...
			
								
			);
		}else{

			$respuesta = array(
				'estado' => 's',
				'Agenda' => $array_final
					// code...
			
								
			);


		}	
			

			echo json_encode($respuesta);


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
