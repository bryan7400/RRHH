<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK (MARIBEL JORGE LUIS)
 */
//tutot per 606 id fami 279 hija 795 idest 406
// Define las cabeceras
header('Content-Type: application/json');

// Verifica la peticion post
if (is_post()) {

    //var_dump($_POST);exit;

    // Verifica la existencia de datos
    if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
        //Obtiene los datos
        $usuario                = clear($_POST['usuario']);
        $contrasenia            = clear($_POST['contrasenia']);
        //$id_aula_paralelo       = clear($_POST['id_aula_paralelo']);
        //$id_profesor_materia	= clear($_POST['id_aula_paralelo']);
        $id_estudiante          = clear($_POST['id_estudiante']);
        //$id_modo_calificacion   = clear($_POST['id_modo_calificacion']);

        //$id_persona = 44
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

            $general = $db->query("SELECT *
                        FROM ins_inscripcion i
                        INNER JOIN ins_estudiante e ON i.estudiante_id = e.id_estudiante
                        INNER JOIN sys_persona sp ON e.persona_id = sp.id_persona
                        INNER JOIN ins_nivel_academico na ON i.nivel_academico_id = na.id_nivel_academico
                        INNER JOIN ins_tipo_estudiante te ON i.tipo_estudiante_id = te.id_tipo_estudiante
                        INNER JOIN ins_aula_paralelo ap ON i.aula_paralelo_id = ap.id_aula_paralelo
                        INNER JOIN ins_aula a ON ap.aula_id = a.id_aula
                        INNER JOIN ins_paralelo pp ON ap.paralelo_id = pp.id_paralelo
                        INNER JOIN ins_turno t ON ap.turno_id = t.id_turno
                        WHERE e.id_estudiante = $id_estudiante")->fetch_first();

            $id_inscripcion = $general['id_inscripcion'];

            $detalles = $db->query("SELECT
                                g.gestion, g.id_gestion, 
                                i.id_inscripcion, i.estudiante_id, i.tipo_estudiante_id, i.nivel_academico_id, i.aula_paralelo_id, i.gestion_id,
                                ppe.id_pensiones_estudiante, ppe.descuento_bs, ppe.estado_concepto_estudiante, ppe.monto, ppe.fecha_final, ppe.mora_dia, IFNULL(ppe.nit_ci,'') nit_ci, IFNULL(ppe.nombre_cliente,'') nombre_cliente, ppe.tipo_concepto, ppe.tipo_documento, ppe.descuento_porcentaje, ppe.compromiso,
                                ppd.id_pensiones_detalle, ppd.nro, ppd.estado_detalle,
                                pp.id_pensiones, pp.nombre_pension, pp.orden, pp.descripcion,
                                IFNULL(pc.nombre_compromiso,'') nombre_compromiso, IFNULL(pc.estado_compromiso,'') estado_compromiso, IFNULL(pc.nro_compromiso,'') nro_compromiso, IFNULL(pc.fecha_limite,'') fecha_limite, IFNULL(pc.observacion,'') observacion,
                                IFNULL(p.monto_cancelado,0) monto_cancelado, p.fecha_general,
                                IFNULL(pa.monto_adelanto,0) monto_adelanto, pa.fecha_adelanto
                                FROM ins_inscripcion i
                                INNER JOIN ins_gestion g ON i.gestion_id = g.id_gestion
                                INNER JOIN pen_pensiones_estudiante ppe ON i.id_inscripcion = ppe.inscripcion_id
                                INNER JOIN pen_pensiones_detalle ppd ON ppe.detalle_pension_id = ppd.id_pensiones_detalle
                                INNER JOIN pen_pensiones pp ON ppd.pensiones_id = pp.id_pensiones
                                INNER JOIN pen_usuario_habilitado puh ON pp.id_pensiones = puh.pensiones_id 
                                LEFT JOIN pen_compromiso pc ON ppe.id_pensiones_estudiante = pc.id_compromiso
                                LEFT JOIN (
                                    SELECT  IFNULL(SUM(ped.monto),0) monto_cancelado,pe.inscripcion_id,pe.detalle_pension_id, pe.fecha_inicio, pe.id_pensiones_estudiante id_pensiones_estudiante_, peg.fecha_general
                                    FROM pen_pensiones_estudiante pe
                                    INNER JOIN pen_pensiones_estudiante_detalle ped ON pe.id_pensiones_estudiante=ped.pensiones_estudiante_id
                                    INNER JOIN pen_pensiones_estudiante_general peg ON ped.general_id=peg.id_general
                                    WHERE pe.inscripcion_id = $id_inscripcion
                                    GROUP BY pe.id_pensiones_estudiante, ped.id_pensiones_estudiante_detalle, pe.detalle_pension_id 
                                ) p ON ppe.id_pensiones_estudiante=p.id_pensiones_estudiante_
                                LEFT JOIN (
                                SELECT  IFNULL(SUM(ped.monto),0) monto_adelanto,pe.inscripcion_id,pe.detalle_pension_id, pe.fecha_inicio, pe.id_pensiones_estudiante id_pensiones_estudiante_, peg.fecha_adelanto
                                FROM pen_pensiones_estudiante pe
                                INNER JOIN pen_adelantos_estudiante_detalle ped ON pe.id_pensiones_estudiante=ped.pensiones_estudiante_id
                                INNER JOIN pen_adelantos_estudiante_general peg ON ped.adelanto_id=peg.id_adelanto
                                WHERE pe.inscripcion_id = $id_inscripcion
                                GROUP BY pe.id_pensiones_estudiante, ped.id_adelanto_estudiante_detalle, pe.detalle_pension_id
                                ) pa ON ppe.id_pensiones_estudiante=pa.id_pensiones_estudiante_  
                                WHERE i.estudiante_id = $id_estudiante
                                AND p.monto_cancelado IS NULL
                                OR i.estudiante_id    = $id_estudiante
                                GROUP BY ppe.id_pensiones_estudiante
                                ORDER BY pp.orden, ppd.fecha_final ASC")->fetch();

            // Devuelve los resultados
            $respuesta = array(
				'estado' => 's',
				'pagos' => $detalles 
			);
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
