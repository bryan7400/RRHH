<?php
$id_user=$_user['id_user'];
$persona_id=$_user['persona_id'];
$rol_id=$_user['rol_id'];
//if (is_post()) {
//    $id_gestion = $_gestion['id_gestion'];
   $accion = $_POST['accion'];      
     
    if($accion == "listar_notificaciones"){
         $com = array(); 
         $res = $db->query('select * from ins_comunicados where estado="A"')->fetch();
        foreach($res as $row){
            $array_personas=explode(',',$row['persona_id']);
            $array_usuarios=explode(',',$row['usuarios']);
            $estados=explode(',',$row['estados']);            
            //armamos solo los q tienen permiso
            $roles_ok = array();//2,5,3 //los q tienen permiso
            foreach ($estados as $i=>$row2) {
                if($estados[$i]=='SI'){
                   array_push($roles_ok, $array_usuarios[$i]);  
                }
            }           
            if(in_array($rol_id,$roles_ok )||in_array($persona_id,$array_personas)){
                array_push($com, $row);     
            }       
        }

      // var_dump($com);exit();
        echo json_encode($com); 
        
    }
//}
