<?php
 
    // Obtiene el id de la gestion actual
    $id_gestion=$_gestion['id_gestion'];

    // Obtiene los modo calificacion
    $modos = $db->select('z.*,g.gestion,(SELECT GROUP_CONCAT( area_calificacion_id SEPARATOR  "|" ) FROM cal_modo_calificacion_area_calificaion 
                                            WHERE modo_calificacion_id= z.id_modo_calificacion AND estado="A" 
                                            ORDER BY area_calificacion_id ASC)AS modos_calificacion')->from('cal_modo_calificacion z')->join('ins_gestion g','g.id_gestion=z.gestion_id')->where('g.id_gestion', $id_gestion)->where('z.estado', 'A')->order_by('z.id_modo_calificacion', 'asc')->fetch();

    echo json_encode($modos); 
    //var_dump($gestiones);die;
?>