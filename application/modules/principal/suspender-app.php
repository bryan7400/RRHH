<?php   

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion ajax 
if (is_ajax()) {  
	 
    //Obtiene todas las pensiones registradas
	// $consulta = "SELECT 
	// i.id_inscripcion, i.estudiante_id,i.estudiante_id, i.gestion_id,
	// p.id_pensiones, p.nombre_pension, p.monto, p.mora_dia,p.fecha_inicio, p.fecha_final, p.gestion_id, p.nivel_academico_id, p.tipo_estudiante_id,
	// na.nombre_nivel,
	// te.nombre_tipo_estudiante,
	// IFNULL(0,0) cancelado,IFNULL(0,0) suma_acuenta,
	// IFNULL(p.monto-pp.monto_cancelado,p.monto) saldo,
	// IFNULL(pp.monto_cancelado,0) monto_cancelado,
	// pp.inscripcion_id,pp.pension_id, pp.fecha_cancelado, pp.id_pensiones_estudiante
	// FROM ins_inscripcion i
	// LEFT JOIN pen_pensiones p ON p.nivel_academico_id=i.nivel_academico_id
	// LEFT JOIN ins_nivel_academico na ON p.nivel_academico_id=na.id_nivel_academico
	// LEFT JOIN ins_tipo_estudiante te ON p.tipo_estudiante_id=te.id_tipo_estudiante
	// LEFT JOIN (SELECT  IFNULL(SUM(ped.monto),0) monto_cancelado,pe.inscripcion_id,pe.pension_id, pe.fecha_cancelado, pe.id_pensiones_estudiante
	//     FROM pen_pensiones_estudiante pe
	//     INNER JOIN pen_pensiones_estudiante_detalle ped ON pe.id_pensiones_estudiante=ped.pensiones_estudiante_id
	//     GROUP BY pe.pension_id
	// ) pp ON p.id_pensiones=pp.pension_id";

	$consulta="SELECT DISTINCT(i.id_inscripcion)id_inscripcion, i.estudiante_id, i.gestion_id,
	p.id_pensiones, p.nombre_pension, p.monto, p.mora_dia,p.fecha_inicio, p.fecha_final, p.gestion_id, p.nivel_academico_id, p.tipo_estudiante_id,
	IFNULL(0,0) cancelado,IFNULL(0,0) suma_acuenta,
	IFNULL(p.monto-pp.monto_cancelado,p.monto) saldo,
	IFNULL(pp.monto_cancelado,0) monto_cancelado,
	pp.inscripcion_id,pp.pension_id, pp.fecha_cancelado, pp.id_pensiones_estudiante
	FROM ins_inscripcion i
	INNER JOIN pen_pensiones p ON p.tipo_estudiante_id=i.tipo_estudiante_id
	INNER JOIN ins_nivel_academico na ON i.nivel_academico_id=p.nivel_academico_id
	LEFT JOIN (SELECT  IFNULL(SUM(ped.monto),0) monto_cancelado,pe.inscripcion_id,pe.pension_id, pe.fecha_cancelado, pe.id_pensiones_estudiante
	    FROM pen_pensiones_estudiante pe
	    INNER JOIN pen_pensiones_estudiante_detalle ped ON pe.id_pensiones_estudiante=ped.pensiones_estudiante_id
	    GROUP BY pe.pension_id
	) pp ON p.id_pensiones=pp.pension_id";

	$resultados = $db->query($consulta)->fetch();
    

	//Validaciones de saldos > 0
	foreach ($resultados as $value) {                      
	 // $value['id_inscripcion'];
	 // $value['fecha_final'];
	 // $value['estudiante_id'];
	 // $value['id_pensiones'];
	 // $value['cancelado'] ;
	 $fecha_pago= $value['fecha_cancelado'];

	   if($value['saldo'] > 0){

	   	 var_dump($resultados);exit();

	   //if($value['monto'] > $value['monto_cancelado']){
	        $saldo =0;
	        // if($value['cancelado'] == 'SI'){
	        //     $total = $value['monto'] + $value['mora_dia'];
	        //     $saldo = $value['monto'] - $value['monto_cancelado']; 
	        //     $cancelado = "SI";
	        // }else{
	            $fecha_actual = date('Y-m-d');
				$date1 = new DateTime($value['fecha_final']);
				$date2 = new DateTime($fecha_actual);

				if($fecha_actual>$value['fecha_final']){					
					$diff = $date1->diff($date2);
					$dias = $diff->days;
					//var_dump($dias);
				}else{
					$dias=0;
				}

	            if($dias >= 0){
	                $mora_dia = $dias * $value['mora_dia'];
	            }else{
	                $mora_dia = 0;
	            }
	            //var_dump($mora_dia);

	            if($mora_dia > 0){
	                $total = $value['monto'] + $mora_dia;
	                $saldo = $total - $value['monto_cancelado'];
	                //$cancelado = $saldo;
	            }elseif($mora_dia==0){
	                $total = $value['monto'];
	                $saldo = $total - $value['monto_cancelado'];
	                //$cancelado = $saldo;
	            }
	            //var_dump($value['fecha_final'].'<'.$fecha_actual);
	            if($fecha_actual>$value['fecha_final']){

	            	//var_dump($fecha_actual.'>'.$value['fecha_final']);
                    $suspendidos = array(
						'inscripcion_id' => $value['id_inscripcion'],
						'estado' => 'S',
						'fecha_final' => $value['fecha_final'],						
					);

	        	    $id=$suspendidos['inscripcion_id'];
				    var_dump($id.'id_inscripcion');
				    $consulta_app="SELECT* FROM app_permisos_menus WHERE inscripcion_id=$id";
				    $resultados_app = $db->query($consulta_app)->fetch_first();

				    if($resultados_app){
					    $habilitados = array(
							'estado' => 'P',				
						);
				       $db->where('inscripcion_id', $id)->update('app_permisos_menus', $habilitados);
				       header('Content-Type: application/json');
				       echo 'Resgistro ya existe';
				    }else{
				       $ins_id = $db->insert('app_permisos_menus', $suspendidos);
				       header('Content-Type: application/json');
				       echo '$ins_id';
				    } 
				      
				}else{}

            }else{
                //var_dump($fecha_actual.'<'.$value['fecha_final']);
            }

          //  }
    }

    //var_dump($suspendidos);exit();

	//Validaciones  por fechas limites de pago

	//var_dump($resultados);exit();
	
	// Define las cabeceras
	
	
	// Devuelve los resultados
	
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>