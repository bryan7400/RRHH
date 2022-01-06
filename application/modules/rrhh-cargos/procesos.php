<?php
// Verifica la peticion post
if (is_post()) {
    $accion = $_POST['accion']; 
     
    if($accion == "recuperar_datos"){
        $id_externo = $_POST['id_componente']; 
        $cargo = $db->query("
                        SELECT * 
                        FROM per_cargos 
                        WHERE id_cargo='$id_externo'
                        ")->fetch_first();

        echo json_encode($cargo); 
    }
    
    if($accion == "eliminar_personal"){
        $id_externo = $_POST['id_componente']; 
                
        $esta=$db->query("  UPDATE per_cargos 
                            SET estado = 'I' 
                            WHERE id_cargo = '".$id_externo."'
                        ")->execute();
        
        if ($esta){
            registrarProceso('Se eliminó el cargo con identificador número ' ,$id_externo, $db, $_location, $_user['id_user']);
            echo 1;//'Eliminado Correctamente.';
        }else{
            echo 2;//'No se pudo eliminar';
        }
    }
}
function registrarProceso($detalle,$id_ext,$db,$_location,$user)
{
    $db->insert('sys_procesos', array(
                'fecha_proceso' => date('Y-m-d'),
                'hora_proceso'  => date('H:i:s'),
                'proceso'       => 'u',//$pros
                'nivel'         => 'l',//$niv
                'detalle'       => $detalle . $id_ext . '.',
                'direccion'     => $_location,
                'usuario_id'    => $user
                )); 
}

?>