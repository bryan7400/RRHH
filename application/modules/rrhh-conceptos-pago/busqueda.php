<?php
    $concepto_pago = $db->select('z.*, g.gestion')
    					->from('rhh_concepto_pago z')
    					->join('ins_gestion g', 'z.gestion_id=g.id_gestion','inner')
    					->where('z.estado', 'A')
    					->order_by('z.id_concepto_pago', 'asc')
    					->fetch();
    echo json_encode($concepto_pago);
 
?>