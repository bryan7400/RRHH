<?php

//$estudiantes = $db->select('z.*')->from('vista_estudiantes z')->order_by('z.id_estudiante', 'asc')->fetch(); echo json_encode($estudiantes);
$gestion = $_gestion['id_gestion'];

$consulta = "SELECT i.id_inscripcion, e.id_estudiante, i.aula_paralelo_id, a.nombre_aula, p.nombre_paralelo, CONCAT(sp.primer_apellido,' ', sp.segundo_apellido,' ', sp.nombres)nombre_completo, sp.primer_apellido, sp.segundo_apellido, sp.nombres, i.tipo_estudiante_id, te.nombre_tipo_estudiante, i.nivel_academico_id, na.nombre_nivel, i.gestion_id, i.usuario_registro, u.username
    , sp.foto, iir.nro_rude, e.codigo_estudiante, sp.numero_documento, CONCAT(a.nombre_aula,' ',p.nombre_paralelo,' ',na.nombre_nivel,'<br>',t.nombre_turno) curso, sp.genero, i.estado_inscripcion
    , ap.corresponde_area, pp.contador , f.* , i.estado_estudiante 
    FROM ins_inscripcion i
        INNER JOIN ins_inscripcion_rude iir ON iir.ins_estudiante_id = i.estudiante_id
        INNER JOIN sys_users u ON u.id_user = i.usuario_registro	
        INNER JOIN ins_estudiante e ON i.estudiante_id=e.id_estudiante
        INNER JOIN sys_persona sp ON e.persona_id=sp.id_persona
        LEFT JOIN 
        (SELECT GROUP_CONCAT( CONCAT(pp.nombres,' ', pp.primer_apellido,' ', pp.segundo_apellido) SEPARATOR ' | ') AS nombres_familiar,
        GROUP_CONCAT(f.telefono_oficina SEPARATOR ' | ') AS contacto, ef.estudiante_id
        FROM ins_familiar f 
        INNER JOIN sys_persona pp ON f.persona_id=pp.id_persona
        INNER JOIN ins_estudiante_familiar ef ON ef.familiar_id=f.id_familiar  AND f.estado = 'A'
        GROUP BY ef.estudiante_id
        ) f ON e.id_estudiante=f.estudiante_id
        INNER JOIN ins_aula_paralelo ap ON i.aula_paralelo_id = ap.id_aula_paralelo
        INNER JOIN ins_paralelo p ON ap.paralelo_id = p.id_paralelo
        INNER JOIN ins_aula a ON ap.aula_id = a.id_aula
        INNER JOIN ins_nivel_academico na ON a.nivel_academico_id=na.id_nivel_academico
        INNER JOIN ins_turno t ON ap.turno_id = t.id_turno
        INNER JOIN ins_tipo_estudiante te ON i.tipo_estudiante_id= te.id_tipo_estudiante
        LEFT JOIN ( SELECT IFNULL(COUNT(*),0) contador, pe.inscripcion_id
            FROM pen_pensiones_estudiante pe
            INNER JOIN pen_pensiones_estudiante_detalle ped ON pe.id_pensiones_estudiante=ped.pensiones_estudiante_id
            INNER JOIN pen_pensiones_estudiante_general peg ON ped.general_id= peg.id_general
            WHERE peg.documento_pago = 'FACTURA'
            OR peg.documento_pago = 'TALONARIO'
            GROUP BY pe.inscripcion_id
        ) pp ON i.id_inscripcion = pp.inscripcion_id
        WHERE i.gestion_id = $gestion 
        AND i.estado = 'A'
    GROUP BY e.id_estudiante
    ORDER BY i.id_inscripcion DESC";
    $inscritos = $db->query($consulta)->fetch();
echo json_encode($inscritos);


