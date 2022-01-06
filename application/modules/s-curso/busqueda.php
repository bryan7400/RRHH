<?php

    $id_gestion=$_gestion['id_gestion']; 

    $sql_cursos = "SELECT ia.id_aula, ia.nombre_aula, ia.descripcion, ina.id_nivel_academico,ina.nombre_nivel, ia.orden
                    FROM ins_aula AS ia
                    INNER JOIN ins_nivel_academico AS ina ON ina.id_nivel_academico = ia.nivel_academico_id
                    WHERE ia.gestion_id = $id_gestion AND ia.estado = 'A' AND ina.estado = 'A'
                    ORDER	BY ina.orden_nivel ASC, ia.orden ASC, ia.id_aula ASC";
    $cursos = $db->query($sql_cursos)->fetch();
    
    echo json_encode($cursos); 
  
?>