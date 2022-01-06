<?php
    $boton = $_POST['boton'];

    if($boton == "listar_familiares"){
        $familiar =  $db->query("SELECT *
                                    FROM ins_familiar AS sf
                                    INNER JOIN sys_persona AS sp ON sp.id_persona = sf.persona_id
                                    GROUP BY sf.id_familiar
                                    ORDER BY sp.primer_apellido ASC, sp.segundo_apellido ASC, sp.nombres ASC")->fetch();
        echo json_encode($familiar); 
    }

?>