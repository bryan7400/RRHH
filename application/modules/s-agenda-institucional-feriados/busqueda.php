<?php

    
    $agendas = $db->query("SELECT * FROM  ins_agenda_institucional WHERE estado = 'A'")->fetch();
    //var_dump($agendas);die;
    echo json_encode($agendas);
   
?>