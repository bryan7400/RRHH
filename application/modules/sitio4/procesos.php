<?php  
  
    //obtiene el valor del boton   
    $boton      = $_POST['boton']; 

    //obtiene la fecha actual del sistema
    $fecha_actual = Date('Y-m-d');

    //obtiene la gestion actual 
    //$gestion    = ($_POST['id_gestion']);  

    //obtiene el valor del boton 
    if($boton == 'btn_ci'){ 

        // Obtiene los clientes 
        $consulta = $db->query("SELECT COUNT(*) contador
        FROM per_postulacion p
        WHERE p.ci='1123456'")->fetch_first();       

        // Validacion
        if($consulta['contador'] > 0){
            echo 1;
        }else{
            echo 2;
        }
    }
?>