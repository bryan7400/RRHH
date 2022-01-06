<?php   
    
    //obtiene el valor del boton    
    $boton = $_POST['boton'];
    //obtiene la fecha actual del sistema
    $fecha_actual = Date('Y-m-d');
    //obtiene la gestion actual 
    $id_gestion = $_gestion['id_gestion'];   

    //obtiene el valor del boton 
    if($boton == 'listar_estudiantes'){
        $id_persona = $_POST['id_persona'];
        $consulta_familiar="SELECT*,CONCAT(ins.nombre_aula, ' ', ins.nombre_paralelo) as nombre_aula_paralelo
        FROM sys_persona p
        INNER JOIN ins_familiar f ON p.id_persona=f.persona_id
        INNER JOIN ins_estudiante_familiar ef ON f.id_familiar=ef.familiar_id
        INNER JOIN ins_inscripcion i ON ef.estudiante_id=i.estudiante_id
        INNER JOIN ins_estudiante e ON i.estudiante_id=e.id_estudiante
        INNER JOIN vista_inscripciones ins ON e.id_estudiante = ins.estudiante_id 
        INNER JOIN sys_persona sp ON e.persona_id=sp.id_persona
        INNER JOIN gon_puntos gp ON gp.id_punto = i.punto_id
        INNER JOIN gon_rutas gr ON gr.id_ruta = gp.ruta_id
        WHERE p.id_persona=$id_persona
        AND i.gestion_id=$id_gestion";
        $respuesta = $db->query($consulta_familiar)->fetch();
  
        if($respuesta){ 
             echo json_encode($respuesta);
        }else{
            $consulta_estudiante="SELECT familiar_id
                FROM sys_persona p
                INNER JOIN ins_estudiante e ON p.id_persona=e.persona_id
                INNER JOIN ins_estudiante_familiar ef ON e.id_estudiante=ef.estudiante_id
                WHERE p.id_persona=$id_persona
                LIMIT 1";
            $respuesta2 = $db->query($consulta_estudiante)->fetch_first();
            $id=$respuesta2['familiar_id'];
            if($id){
                $consulta="SELECT*,CONCAT(ins.nombre_aula, ' ', ins.nombre_paralelo) as nombre_aula_paralelo
                FROM sys_persona p
                INNER JOIN ins_familiar f ON p.id_persona=f.persona_id
                INNER JOIN ins_estudiante_familiar ef ON f.id_familiar=ef.familiar_id
                INNER JOIN ins_inscripcion i ON ef.estudiante_id=i.estudiante_id
                INNER JOIN ins_estudiante e ON i.estudiante_id=e.id_estudiante
                INNER JOIN vista_inscripciones ins ON e.id_estudiante = ins.estudiante_id
                INNER JOIN sys_persona sp ON e.persona_id=sp.id_persona
                WHERE f.id_familiar=$id
                AND i.gestion_id=$id_gestion";
                $respuesta3 = $db->query($consulta)->fetch();
                if($respuesta3){
                     echo json_encode($respuesta3);
                }else{
                    echo 0;
                }
            }else{
                echo 0; 
            }
        }
    }
    
    if($boton == "cargar_pagos_factura"){ 

        $id_estudiante      = $_POST['id_estudiante'];
        $consulta_pensiones = $fila = $db->query("SELECT * FROM vista_inscripciones WHERE estudiante_id = $id_estudiante")->fetch_first();
        $id_inscripcion     = $fila['id_inscripcion'];
        $id_tipo_estudiante = $fila['tipo_estudiante_id'];
        $id_usuario         = $_user['id_user'];
        
        // Obtiene los usuarios habilitados
        $usuario_habilitado = $db->query("SELECT * FROM pen_pensiones p WHERE p.estado = 'A' AND p.gestion_id = $id_gestion")->fetch_first();
        
        // Obtiene el listado de los conceptos de pago facturables
        $consulta_pension = "SELECT ifnull(count(*),0) contador
        FROM ins_inscripcion i
        INNER JOIN ins_gestion g ON i.gestion_id = g.id_gestion
        INNER JOIN pen_pensiones_estudiante ppe ON i.id_inscripcion = ppe.inscripcion_id
        INNER JOIN pen_pensiones_detalle ppd ON ppe.detalle_pension_id = ppd.id_pensiones_detalle
        INNER JOIN pen_pensiones pp ON ppd.pensiones_id = pp.id_pensiones
        INNER JOIN pen_usuario_habilitado puh ON pp.id_pensiones = puh.pensiones_id 
        LEFT JOIN pen_compromiso pc ON ppe.id_pensiones_estudiante = pc.id_compromiso
        LEFT JOIN (
                SELECT  IFNULL(SUM(ped.monto),0) monto_cancelado,pe.inscripcion_id,pe.detalle_pension_id, pe.fecha_inicio, pe.id_pensiones_estudiante id_pensiones_estudiante_
                FROM pen_pensiones_estudiante pe
                INNER JOIN pen_pensiones_estudiante_detalle ped ON pe.id_pensiones_estudiante=ped.pensiones_estudiante_id
                WHERE pe.inscripcion_id = $id_inscripcion
                GROUP BY pe.id_pensiones_estudiante, ped.id_pensiones_estudiante_detalle, pe.detalle_pension_id 
        ) p ON ppe.id_pensiones_estudiante=p.id_pensiones_estudiante_
        LEFT JOIN (
        SELECT  IFNULL(SUM(ped.monto),0) monto_adelanto,pe.inscripcion_id,pe.detalle_pension_id, pe.fecha_inicio, pe.id_pensiones_estudiante id_pensiones_estudiante_
        FROM pen_pensiones_estudiante pe
        INNER JOIN pen_adelantos_estudiante_detalle ped ON pe.id_pensiones_estudiante=ped.pensiones_estudiante_id
        WHERE pe.inscripcion_id = $id_inscripcion
        GROUP BY pe.id_pensiones_estudiante, ped.id_adelanto_estudiante_detalle, pe.detalle_pension_id
        ) pa ON ppe.id_pensiones_estudiante=pa.id_pensiones_estudiante_  
        WHERE i.estudiante_id = $id_estudiante
        AND pp.estado ='A'
        AND pp.tipo_documento = 'FACTURA'
        AND p.monto_cancelado IS NULL
        OR i.estudiante_id    = $id_estudiante
        AND p.monto_cancelado < ppe.monto
        AND pp.estado ='A'
        AND pp.tipo_documento = 'FACTURA'
        AND ppe.fecha_final >= '$fecha_actual'
        ORDER BY pp.orden, ppd.fecha_final ASC";
        //var_dump($consulta_pension);exit();
        $respuesta_pension = $db->query($consulta_pension)->fetch_first();

        if($respuesta_pension){
            $respuesta=$respuesta_pension;
        }else{
            $respuesta=0;
        }
        // Respuesta
        echo json_encode($respuesta);
    }

    if($boton == "cargar_pagos_recibo"){ 

        $id_estudiante      = $_POST['id_estudiante'];
        $consulta_pensiones = $fila = $db->query("SELECT * FROM vista_inscripciones WHERE estudiante_id = $id_estudiante")->fetch_first();
        $id_inscripcion     = $fila['id_inscripcion'];
        $id_tipo_estudiante = $fila['tipo_estudiante_id'];
        $id_usuario         = $_user['id_user'];
        
        // Obtiene los usuarios habilitados
        $usuario_habilitado = $db->query("SELECT * FROM pen_pensiones p WHERE p.estado = 'A' AND p.gestion_id = $id_gestion")->fetch_first();
        
        // Obtiene el listado de los conceptos de pago facturables
        $consulta_pension = "SELECT
        puh.usuario_id,
        g.gestion, g.id_gestion, 
        i.id_inscripcion, i.estudiante_id, i.tipo_estudiante_id, i.nivel_academico_id, i.aula_paralelo_id, i.gestion_id,
        ppe.id_pensiones_estudiante, ppe.descuento_bs, ppe.estado_concepto_estudiante, ppe.monto, ppe.fecha_final, ppe.mora_dia, IFNULL(ppe.nit_ci,'') nit_ci, IFNULL(ppe.nombre_cliente,'') nombre_cliente, ppe.tipo_concepto, ppe.tipo_documento, ppe.descuento_porcentaje, ppe.compromiso,
        ppd.id_pensiones_detalle, ppd.nro, ppd.estado_detalle,
        pp.id_pensiones, pp.nombre_pension, pp.orden, pp.descripcion,
        IFNULL(pc.nombre_compromiso,'') nombre_compromiso, IFNULL(pc.estado_compromiso,'') estado_compromiso, IFNULL(pc.nro_compromiso,'') nro_compromiso, IFNULL(pc.fecha_limite,'') fecha_limite, IFNULL(pc.observacion,'') observacion,
        IFNULL(p.monto_cancelado,0) monto_cancelado,
        IFNULL(pa.monto_adelanto,0) monto_adelanto
        FROM ins_inscripcion i
        INNER JOIN ins_gestion g ON i.gestion_id = g.id_gestion
        INNER JOIN pen_pensiones_estudiante ppe ON i.id_inscripcion = ppe.inscripcion_id
        INNER JOIN pen_pensiones_detalle ppd ON ppe.detalle_pension_id = ppd.id_pensiones_detalle
        INNER JOIN pen_pensiones pp ON ppd.pensiones_id = pp.id_pensiones
        INNER JOIN pen_usuario_habilitado puh ON pp.id_pensiones = puh.pensiones_id 
        LEFT JOIN pen_compromiso pc ON ppe.id_pensiones_estudiante = pc.id_compromiso
        LEFT JOIN (
                SELECT  IFNULL(SUM(ped.monto),0) monto_cancelado,pe.inscripcion_id,pe.detalle_pension_id, pe.fecha_inicio, pe.id_pensiones_estudiante id_pensiones_estudiante_
                FROM pen_pensiones_estudiante pe
                INNER JOIN pen_pensiones_estudiante_detalle ped ON pe.id_pensiones_estudiante=ped.pensiones_estudiante_id
                WHERE pe.inscripcion_id = $id_inscripcion
                GROUP BY pe.id_pensiones_estudiante, ped.id_pensiones_estudiante_detalle, pe.detalle_pension_id 
        ) p ON ppe.id_pensiones_estudiante=p.id_pensiones_estudiante_
        LEFT JOIN (
        SELECT  IFNULL(SUM(ped.monto),0) monto_adelanto,pe.inscripcion_id,pe.detalle_pension_id, pe.fecha_inicio, pe.id_pensiones_estudiante id_pensiones_estudiante_
        FROM pen_pensiones_estudiante pe
        INNER JOIN pen_adelantos_estudiante_detalle ped ON pe.id_pensiones_estudiante=ped.pensiones_estudiante_id
        WHERE pe.inscripcion_id = $id_inscripcion
        GROUP BY pe.id_pensiones_estudiante, ped.id_adelanto_estudiante_detalle, pe.detalle_pension_id
        ) pa ON ppe.id_pensiones_estudiante=pa.id_pensiones_estudiante_  
        WHERE i.estudiante_id = $id_estudiante
        AND puh.usuario_id = $id_usuario
        AND pp.estado ='A'
        AND pp.tipo_documento = 'RECIBO'
        AND p.monto_cancelado IS NULL
        OR i.estudiante_id    = $id_estudiante
        AND puh.usuario_id = $id_usuario
        AND p.monto_cancelado < ppe.monto
        AND pp.estado ='A'
        AND pp.tipo_documento = 'RECIBO'
        GROUP BY ppe.id_pensiones_estudiante
        ORDER BY pp.orden, ppd.fecha_final ASC";
        //var_dump($consulta_pension);exit();
        $respuesta_pension = $db->query($consulta_pension)->fetch();

        //AND puh.usuario_id    = $id_usuario
        
        // Respuesta
        echo json_encode($respuesta_pension);
    }

    if($boton == "listar_datos_factura"){ 
        //var_dump($_POST);exit();
        $id_estudiante = $_POST['id_estudiante'];
        $id_pension = $_POST['id_producto'];
        $id_inscripcion = $_POST['id_inscripcion']; 
        //var_dump($id_pension);exit();
        $consulta_datos = "SELECT 
        i.id_inscripcion, i.estudiante_id, i.gestion_id,
        p.id_pensiones, p.nombre_pension, pe.monto, pe.mora_dia,pe.fecha_inicio, pe.fecha_final, p.gestion_id, p.nivel_academico_id, p.tipo_estudiante_id,
        na.nombre_nivel,
        te.nombre_tipo_estudiante,
        IFNULL(0,0) cancelado,IFNULL(0,0) suma_acuenta, pe.id_pensiones_estudiante,
        per.nombres,per.primer_apellido,per.segundo_apellido,
        a.nombre_aula,
        par.nombre_paralelo,
        na.nombre_nivel,
        t.nombre_turno,
        pe.nombre_cliente, pe.nit_ci, p.tipo_documento,
        pd.nro
        FROM ins_inscripcion i
        INNER JOIN ins_estudiante e ON i.estudiante_id=e.id_estudiante
        INNER JOIN sys_persona per ON e.persona_id=per.id_persona
        INNER JOIN ins_aula_paralelo ap ON i.aula_paralelo_id=ap.id_aula_paralelo
        INNER JOIN ins_aula a ON ap.aula_id=a.id_aula
        INNER JOIN ins_paralelo par ON ap.paralelo_id=par.id_paralelo
        INNER JOIN ins_nivel_academico na ON a.nivel_academico_id =na.id_nivel_academico
        INNER JOIN ins_turno t ON i.turno_id =t.id_turno
        INNER JOIN ins_tipo_estudiante te ON i.tipo_estudiante_id = te.id_tipo_estudiante
        inner JOIN pen_pensiones_estudiante pe ON i.id_inscripcion=pe.inscripcion_id
        inner JOIN pen_pensiones_detalle pd ON pe.detalle_pension_id = pd.id_pensiones_detalle
        inner JOIN pen_pensiones p ON pd.pensiones_id = p.id_pensiones
        where i.estudiante_id=$id_estudiante
        and pe.id_pensiones_estudiante=$id_pension";
        //var_dump($consulta_datos);exit();
        $respuesta_datos = $db->query($consulta_datos)->fetch_first();

        //var_dump($respuesta_datos);exit();
        echo json_encode($respuesta_datos);
    } 

    if($boton == "listar_historial_pensiones"){ 
        
        $id_estudiante = $_POST['id_estudiante'];
        //var_dump($id_estudiante);exit();
        $consulta_pensiones = "SELECT *
                                FROM vista_inscripciones
                                WHERE gestion_id = $id_gestion AND estudiante_id = $id_estudiante";
        $fila = $db->query($consulta_pensiones)->fetch_first();
        $id_inscripcion = $fila['id_inscripcion'];
        $id_tipo_estudiante = $fila['tipo_estudiante_id'];
        $consulta_historial = "SELECT
        g.gestion, g.id_gestion, 
        i.id_inscripcion, i.estudiante_id, i.tipo_estudiante_id, i.nivel_academico_id, i.aula_paralelo_id, i.gestion_id,
        ppe.id_pensiones_estudiante, ppe.descuento_bs, ppe.estado_concepto_estudiante, ppe.monto, ppe.fecha_final, ppe.mora_dia, IFNULL(ppe.nit_ci,'') nit_ci, IFNULL(ppe.nombre_cliente,'') nombre_cliente, ppe.tipo_concepto, ppe.tipo_documento, ppe.descuento_porcentaje, ppe.compromiso,
        ppd.id_pensiones_detalle, ppd.nro, ppd.estado_detalle,
        pp.id_pensiones, pp.nombre_pension, pp.orden, pp.descripcion,
        IFNULL(pc.nombre_compromiso,'') nombre_compromiso, IFNULL(pc.estado_compromiso,'') estado_compromiso, IFNULL(pc.nro_compromiso,'') nro_compromiso, IFNULL(pc.fecha_limite,'') fecha_limite, IFNULL(pc.observacion,'') observacion,
        IFNULL(p.monto_cancelado,0) monto_cancelado, p.usuario_registro, p.documento_pago, p.fecha_general, p.hora_general, p.nro_factura, p.nombre_cliente, p.nit_ci, p.nombres, p.primer_apellido, p.segundo_apellido, p.username
        FROM ins_inscripcion i
        INNER JOIN ins_gestion g ON i.gestion_id = g.id_gestion
        INNER JOIN pen_pensiones_estudiante ppe ON i.id_inscripcion = ppe.inscripcion_id
        INNER JOIN pen_pensiones_detalle ppd ON ppe.detalle_pension_id = ppd.id_pensiones_detalle
        INNER JOIN pen_pensiones pp ON ppd.pensiones_id = pp.id_pensiones
        LEFT JOIN pen_compromiso pc ON ppe.id_pensiones_estudiante = pc.id_compromiso
        INNER JOIN (
                SELECT  IFNULL(SUM(ped.monto),0) monto_cancelado,pe.inscripcion_id,pe.detalle_pension_id, pe.fecha_inicio, pe.id_pensiones_estudiante id_pensiones_estudiante_,
                peg.usuario_registro, peg.documento_pago, peg.fecha_general, peg.hora_general, peg.nro_factura, peg.nombre_cliente, peg.nit_ci,
                sp.nombres, sp.primer_apellido, sp.segundo_apellido, u.username
                FROM pen_pensiones_estudiante pe
                INNER JOIN pen_pensiones_estudiante_detalle ped ON pe.id_pensiones_estudiante=ped.pensiones_estudiante_id
                INNER JOIN pen_pensiones_estudiante_general peg ON ped.general_id=peg.id_general
                INNER JOIN sys_users u ON peg.usuario_registro = u.id_user
                INNER JOIN sys_persona sp ON u.persona_id = sp.id_persona
                WHERE peg.estudiante_id = $id_estudiante
                GROUP BY pe.id_pensiones_estudiante, ped.id_pensiones_estudiante_detalle, pe.detalle_pension_id, peg.id_general 
        ) p ON ppe.id_pensiones_estudiante=p.id_pensiones_estudiante_ 
        WHERE i.estudiante_id = $id_estudiante
        GROUP BY ppe.id_pensiones_estudiante
        ORDER BY pp.orden, ppd.fecha_final ASC";
        $historial = $db->query($consulta_historial)->fetch();
        //var_dump($historial);exit();
        echo json_encode($historial);
    }

?>