<?php
$boton = $_POST['boton'];

    if($boton == "listar_asistencia"){
        //$familiar = $db->select('z.*')->from('vista_familiar z')->order_by('z.id_familiar', 'asc')->fetch();
        $empleados = $db->query("SELECT	*
						FROM	per_asignaciones AS a
						INNER JOIN sys_persona AS p ON p.id_persona = a.persona_id
						INNER JOIN per_cargos AS c ON c.id_cargo = a.cargo_id
						WHERE a.estado = 'A'")->fetch();															


        echo json_encode($empleados); 
    }  

?>    