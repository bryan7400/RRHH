<?php
// Verifica la peticion post
if (is_post()) {
    $id_gestion = $_gestion['id_gestion'];
    $accion = $_POST['accion']; 
    if($accion == "listar_tabla"){
        $feriados = $db->query("SELECT * 
                                FROM rrhh_firma_contrato
                                ORDER BY id_firma desc
                                ")->fetch();
        echo json_encode($feriados); 
    }
}
?>