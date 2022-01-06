<?php
    $resultados = $db->query("SELECT anio, count(asignacion_id)as nro  
                            FROM rrhh_retroactivos 
                            GROUP BY anio
                            ")->fetch();
    echo json_encode($resultados); 
?>