<?php
    //require_once("s-gestion-escolar/controlador_padre.php");

    //var_dump($_POST);die;
    /*procesar_data_table();
    var_dump(procesar_data_table());die;
    $resultado = listar_todo();
    procesar_retorno($resultado);
    json($retorno);*/
    $id_gestion=$_gestion['id_gestion'];
    $areas = $db->query("SELECT * FROM cal_area_calificacion as cac INNER JOIN ins_gestion as ig ON ig.id_gestion = cac.gestion_id WHERE cac.gestion_id = $id_gestion AND cac.estado = 'A' ORDER BY cac.orden ASC")->fetch();
    //var_dump($areas);die;
    echo json_encode($areas); 
   
?>