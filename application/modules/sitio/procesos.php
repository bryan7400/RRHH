<?php  
  
    //obtiene el valor del boton   
    $boton      = $_POST['boton']; 

    //obtiene la fecha actual del sistema
    $fecha_actual = Date('Y-m-d');

    //obtiene la gestion actual 
    //$gestion    = ($_POST['id_gestion']);  

    //obtiene el valor del boton 

    if($boton == "recuperar_datos"){

        $ci  = $_POST['ci_busqueda'];

        //var_dump($_POST);die;

        $cliente = $db->query("SELECT * FROM per_postulacion WHERE ci='$ci'")->fetch_first(); 
       

        echo json_encode($cliente); 
    }


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



    if ($boton == "listar_nacion") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;
    
        
    $nacionalidad  = $_POST['nacionalidad'];
    $familiar = $db->query("SELECT * FROM sys_departamentos  
    where piases_id ='$nacionalidad'
    order by nombre asc")->fetch();
    //$familiar = $db->select('z.*')->from('vista_estudiante_familiar z')->where('id_estudiante', $id_estudiante)->order_by('z.id_estudiante_familiar', 'asc')->fetch();
    echo json_encode($familiar);// order by nombre_aula asc
}


if ($boton == "listar_provincia") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;
    $departamento  = $_POST['departamento'];
    
     if ($departamento == "") {//||$_POST['pronvinselect']!=''||$_POST['pronvinselect']!=0
        $departnombre =0;

    } else {
         
         $departnombre =$departamento;
        $departlist = $db->query("SELECT * FROM sys_departamentos where nombre='$departamento'")->fetch_first();
        $departnombre = $departlist['id_departamento'];
    }

     $familiar = $db->query("SELECT * FROM sys_provincias where departamento_id='$departnombre'  order by nombre asc")->fetch();
    
 
    echo json_encode($familiar);// order by nombre_aula asc
}  

if ($boton == "listar_localidad") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;
    $provincia  = $_POST['provincia'];
    
     if ($provincia == "") {//||$_POST['pronvinselect']!=''||$_POST['pronvinselect']!=0
        $departnombre =0;

    } else {
         
         $departnombre =$provincia;
        $provincia_list = $db->query("SELECT * FROM sys_provincias where nombre='$provincia'")->fetch_first();
        $provincia_nombre = $provincia_list['id_provincia'];
    }

     $familiar = $db->query("SELECT * FROM sys_localidades where provincia_id='$provincia_nombre'  order by nombre asc")->fetch();
    
 
    echo json_encode($familiar);// order by nombre_aula asc
}  

?>