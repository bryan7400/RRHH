<?php
    $feriados = $db->query("SELECT anio, mes, count(asignacion_id)as nro  
                            FROM rrhh_planilla_pago 
                            GROUP BY anio, mes
                            ")->fetch();
    echo json_encode($feriados); 
?>