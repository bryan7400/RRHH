<?php

    
    $contratos = $db->query("SELECT * FROM rrhh_contrato WHERE estado = 'A'")->fetch();
    //var_dump($contratos);die;
    echo json_encode($contratos);
   
?>