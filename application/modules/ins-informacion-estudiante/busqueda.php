<?php




 //var_dump($_POST);die;

if ($_POST['estudiante_id']=="") {


}else{
   

    $estudiante=$_POST['estudiante_id'];
    
    //var_dump($contratos);die;

    $informacion_estudiante = $db->query("SELECT * FROM ins_informacion_estudiante  WHERE estado = 'A' AND estudiante_id = '$estudiante' ORDER BY
         categoria_informacion ASC")->fetch();

    

    //var_dump($contratos);die;
    


    echo json_encode($informacion_estudiante);
    
}    



?>


