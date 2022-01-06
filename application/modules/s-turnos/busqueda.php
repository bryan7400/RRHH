<?php
    
    // Obtiene el id de la gestion actual
    $id_gestion=$_gestion['id_gestion'];
    //var_dump($_POST);die;
    $sql_turnos = "SELECT * FROM ins_turno WHERE gestion_id = $id_gestion AND estado = 'A' ORDER BY orden ASC";
        $turnos = $db->query($sql_turnos)->fetch();
    echo json_encode($turnos);
    
?>