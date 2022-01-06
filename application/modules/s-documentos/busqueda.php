<?php
    $documentos = $db->select('z.*')->from('ins_tipo_documentos z')
    ->where('z.estado', 'A')
    ->order_by('z.nombre', 'asc')->fetch();
    //var_dump($documentos);exit();
    echo json_encode($documentos);
?>