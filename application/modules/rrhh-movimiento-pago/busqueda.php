<?php
    $concepto_pago = $db->select('z.*')->from('rhh_concepto_pago z')->where('estado', 'A')->order_by('z.id_concepto_pago', 'asc')->fetch();
    echo json_encode($concepto_pago);

?>