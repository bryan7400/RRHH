<?php
$id_user=$_user['id_user'];
$persona_id=$_user['persona_id'];
$rol_id=$_user['rol_id'];

require_once (libraries.'/Mobile-Detect/Mobile_Detect.php');

//if (is_post()) {
//    $id_gestion = $_gestion['id_gestion'];
   $accion = $_POST['accion'];           
    if($accion == "listar_notificaciones"){
         $com = array(); 
         $res = $db->query('select * from ins_comunicados where estado="A" ORDER BY fecha_registro DESC ')->fetch();
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

        $notificaciones_vistas = $db->query('select id_comunicado from ins_comunicados  WHERE id_comunicado NOT IN (SELECT comunicado_id FROM not_notificaciones) ')->fetch();
        //creacion del objeto para la deteccion del dispositivo del usuario
        $detect = new Mobile_Detect();
        $dispositivo = 'Desktop';
        if ($detect->isMobile()) {
            $dispositivo = "móvil";
        }
        if ($detect->isTablet()) {
            $dispositivo = "tablet";
        }
        if ($detect->isAndroidOS()) {
            $dispositivo = "Android";
        }
        if ($detect->isiOS()){
            $dispositivo = "iOS";
        }
        if ($notificaciones_vistas) {            
            foreach ($notificaciones_vistas as $key => $notificaciones_vista) {
                $id_comunicados = ($notificaciones_vista['id_comunicado'] != 0 && $notificaciones_vista['id_comunicado'] !='') ? $notificaciones_vista['id_comunicado']:0;
                $db->insert('not_notificaciones', array(
                    'persona_id' => $id_user, 
                    'visto_desde' => ($dispositivo == '')? "Dispositivo no reconocido":$dispositivo, 
                    'visto_fecha' => Date('Y-m-d H:i:s'), 
                    'leido_desde' => '',
                    'leido_fecha' => '0000-00-00 00:00:00',
                    'comunicado_id' => $id_comunicados
                ));
            }
        }
        echo json_encode($com);         
    }
    if($accion == "leer_notificaciones"){
        $id_comunicado = $_POST['id_comunicado'];
        $comunicados = $db->query("select * from ins_comunicados where id_comunicado=$id_comunicado")->fetch_first();
        $cadena_usuarios= $contador = "";
        if ($comunicados) {
            if ($comunicados['usuarios'] != '' && $comunicados['usuarios'] != 0) {
                $id_destinatario = explode(',' , $comunicados['usuarios']);
                $contador = sizeof($id_destinatario);
                $destinatario = "roles";
            }elseif ($comunicados['persona_id'] != '' && $comunicados['persona_id'] != 0) {
                $id_destinatario = explode(',' , $comunicados['persona_id']);
                $contador = sizeof($id_destinatario);
                $destinatario = "personas";
            }
            for($i = 0; $i < $contador; $i++){
                if ($destinatario == 'roles') {                    
                    $rol = $db->query("SELECT id_rol, rol FROM sys_roles WHERE id_rol = $id_destinatario[$i]")->fetch_first();
                    $cadena_usuarios = $cadena_usuarios . ", " . $rol['rol']; //contatena el nombre de los roles
                }elseif ($destinatario == 'personas') {
                    $rol = $db->query("SELECT id_persona, concat(nombre,' ', primer_apellido)as person FROM sys_personas WHERE id_persona = $id_destinatario[$i]")->fetch_first();
                    $cadena_usuarios = $cadena_usuarios . ", " . $rol['person']; //contatenar usuarios                    
                }
            }
            //datos a responder
            $cadena_limpia =  trim($cadena_usuarios, ','); //quita la primera coma
            //verificar desde que dispositivo se reliza la lectura del comunicado
            $detect = new Mobile_Detect();
            $dispositivo = 'Desktop';
            if ($detect->isMobile()) {
                $dispositivo = "móvil";
            }
            if ($detect->isTablet()) {
                $dispositivo = "tablet";
            }
            if ($detect->isAndroidOS()) {
                $dispositivo = "Android";
            }
            if ($detect->isiOS()){
                $dispositivo = "iOS";
            }

            $leer_comunicado = $db->query("select * from not_notificaciones where comunicado_id=$id_comunicado and persona_id=$id_user")->fetch_first();
            if ($leer_comunicado) {
                $fecha = Date('Y-m-d H:i:s');
                //$consulta = "update not_notificaciones set ='$dispositivo', leido_fecha='$fecha' where comunicado_id= '$id_comunicado'";
                //$id = $db->query($consulta);
                $datos = array('leido_desde' => $dispositivo,
                                'leido_fecha' => $fecha );
                $id = $db->where('comunicado_id',$id_comunicado)->update('not_notificaciones',$datos);
            }else{
                $id = $db->insert('not_notificaciones', array(
                    'persona_id' => $id_user, 
                    'visto_desde' => ($dispositivo == '')? "Dispositivo no reconocido.":$dispositivo, 
                    'visto_fecha' => Date('Y-m-d H:i:s'), 
                    'leido_desde' => ($dispositivo == '')? "Dispositivo no reconocido.":$dispositivo,
                    'leido_fecha' => Date('Y-m-d H:i:s'), 
                    'comunicado_id' => $id_comunicado
                ));
            }
            echo json_encode($cadena_limpia); 
        }
    }
//}
