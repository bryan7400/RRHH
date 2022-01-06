<?php

$fecha_inicio = isset($_POST['fecha_inicio_filtro']) ? $_POST['fecha_inicio_filtro'] : "";
    $fecha_final = isset($_POST['fecha_final_filtro']) ? $_POST['fecha_final_filtro'] : "";
if ($_POST['fecha_inicio_filtro']=="") {
    /*$postulacion = $db->select('z.*, c.*')
                ->from('per_postulacion z')
                ->join('per_cargos c', 'c.id_cargo=z.cargo_id', 'left')
                ->where('z.estado', 'A')
                ->order_by('z.id_postulacion', 'asc')
                ->fetch();



                */
    $postulacion = $db->query("SELECT * FROM per_postulacion z 
        LEFT JOIN per_cargos c ON c.id_cargo=z.cargo_id
        AND z.estado = 'A' 
        ORDER BY z.id_postulacion ASC")->fetch();            
    //var_dump($contratos);die;
    echo json_encode($postulacion);

    
}else{
    
//var_dump($_POST);die;
    

        $postulacion = $db->query("SELECT * FROM per_postulacion z 
        LEFT JOIN per_cargos c ON c.id_cargo=z.cargo_id
        WHERE  date(z.fecha_registro) BETWEEN '$fecha_inicio'AND '$fecha_final'
        AND z.estado = 'A'
        ORDER BY id_postulacion ASC")->fetch();



        echo json_encode($postulacion);
}    
    
   
?>