<?php      
     
    //obtiene el valor del boton    
    $boton = $_POST['boton'];

    //obtiene la fecha actual del sistema
    $fecha_actual = Date('Y-m-d');

    //obtiene la gestion actual 
    $id_gestion = $_gestion['id_gestion'];    

    if($boton == "listar_informacion"){ 
        //var_dump($_POST);exit();
        
        $id_asignacion = $_POST['id_asignacion']; 
        //var_dump($id_asignacion);exit();
        $consulta_informacion = "SELECT *
        FROM sys_persona p
        INNER JOIN sys_users su ON p.id_persona = su.persona_id
        INNER JOIN per_asignaciones a ON p.id_persona = a.persona_id
        INNER JOIN per_cargos c ON a.cargo_id = c.id_cargo
        WHERE a.estado = 'A'
        AND a.gestion_id = $id_gestion
        AND a.id_asignacion = $id_asignacion";
        $informacion = $db->query($consulta_informacion)->fetch_first();
        //var_dump($informacion);exit();

        echo json_encode($informacion);
    }

    if($boton == "listar_historial"){ 
        
        $id_asignacion = $_POST['id_asignacion']; 

        //var_dump($id_asignacion);exit();

        $consulta_historial = "SELECT *, mp.mes mes_cancelado, mp.monto monto_cancelado
        FROM sys_persona p
        INNER JOIN sys_users su ON p.id_persona = su.persona_id
        INNER JOIN per_asignaciones a ON p.id_persona = a.persona_id
        INNER JOIN rhh_movimiento_pago mp ON a.id_asignacion = mp.asignacion_id
        INNER JOIN rhh_concepto_pago cp ON mp.concepto_pago_id = cp.id_concepto_pago
        WHERE a.estado = 'A'
        AND a.gestion_id = $id_gestion
        AND a.id_asignacion = $id_asignacion
        ORDER BY cp.nombre_concepto_pago, mp.mes";
        $historial = $db->query($consulta_historial)->fetch();
        //var_dump($historial);exit();

        echo json_encode($historial);
    }
?>