<?php   
    
    //obtiene el valor del boton    
    $boton = $_POST['boton'];
    //obtiene la fecha actual del sistema
    $fecha_actual = Date('Y-m-d');
    //obtiene la gestion actual 
    //$id_gestion = $_gestion['id_gestion'];   

    //obtiene el valor del boton 
    if($boton == 'listar_personal'){
		
		$rutas = $db->query("SELECT car.cargo,per.nombres,per.primer_apellido,per.segundo_apellido, asi.* 
		FROM per_asignaciones asi
		INNER JOIN per_cargos car ON	car.id_cargo=asi.cargo_id
		inner JOIN sys_persona per ON per.id_persona=asi.persona_id
		WHERE asi.estado='A'
		and car.cargo='CONDUCTOR' ")->fetch();
		
 		echo (json_encode($rutas)); 
		
	}



