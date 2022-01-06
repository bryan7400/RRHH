<?php
$formato_textual = get_date_textual($_institution['formato']);
$formato_numeral = get_date_numeral($_institution['formato']);
//
    $boton = $_POST['boton'];
    if($boton == "listar_roles"){
        $roles = $db->query("SELECT * FROM sys_roles WHERE id_rol != 1 ")->fetch();
        echo json_encode($roles);
    }
  
  if($boton == "listar_comunicados"){
      
    $comunicados = $db->query("SELECT     su.username, z.* 
    from ins_comunicados z LEFT JOIN sys_users su ON su.id_user=z.usuario_registro 
    where z.estado='A' order by z.id_comunicado  desc")->fetch();
    
    //$comunicados = $db->query("SELECT (SELECT COUNT(comunicado_id) FROM not_notificaciones noti WHERE noti.comunicado_id=z.id_comunicado)AS cantLeidos,
    //su.username, z.* from ins_comunicados z LEFT JOIN sys_users su ON su.id_user=z.usuario_registro 
    //where z.estado='A' order by z.id_comunicado  desc")->fetch();
    
    //$comunicados = $db->query("SELECT (SELECT COUNT(comunicado_id) FROM not_notificaciones noti WHERE noti.comunicado_id=z.id_comunicado)AS cantLeidos,z.* from ins_comunicados z where z.estado='A' order by z.id_comunicado  desc")->fetch();
      
    $com = array(); 
    $cadena_usuarios = ""; 
    //armando el nuevo array con los roles respectivos
    foreach ($comunicados as $key => $comunicado) {
      $cadena_limpia = '';
      //captura los roles del comunicado
      $id = trim($comunicado['usuarios'],',');
     
      $persona_id = $comunicado['persona_id'];
      if($id==''||$id==','){
          $cadena_limpia='Sin usuarios';
      }else{
          $usuarios = explode(',', $id);//conveirto en array []
          $contador = count($usuarios); //cuenta la cantidad de usuarios /
          

          for($i = 0; $i < $contador; $i++){
              
            $rol = $db->query("SELECT id_rol, rol FROM sys_roles WHERE id_rol = $usuarios[$i]")->fetch_first();
            $cadena_usuarios = $cadena_usuarios . "," . $rol['rol']; //contatena el nombre de los roles
          }
          $cadena_limpia =  trim($cadena_usuarios, ','); //quita la primera coma
      }
      $id_emisorcomunicado = $comunicado['usuario_registro'];
      $fecha_inicio = explode(' ', $comunicado['fecha_inicio']);
      $fecha_final = explode(' ', $comunicado['fecha_final']);


      //$array['cantLeidos'] = $comunicado['cantLeidos'];
      $array['id_comunicado'] = $comunicado['id_comunicado'];
      $array['codigo'] = $comunicado['codigo'];
      $array['fecha_inicio'] = $comunicado['fecha_inicio'];//$fecha_inicio[1] . ' ' . date_decode($fecha_inicio[0], $_institution['formato']);
      $array['fecha_final'] = $comunicado['fecha_final'];//$fecha_final[1] . ' ' . date_decode($fecha_final[0], $_institution['formato']);
      $array['nombre_evento'] = $comunicado['nombre_evento'];
      $array['descripcion'] = $comunicado['descripcion'];
      $array['color'] = $comunicado['color'];
      $array['usuariosStr'] = $cadena_limpia;
      $array['usuarios'] = trim($comunicado['usuarios'],',');//$comunicado['usuarios'];//
      $array['estados'] = $comunicado['estados'];
      $array['persona_id'] = trim($comunicado['persona_id'],',');
      $array['estado'] = $comunicado['estado'];
      $array['file'] = $comunicado['file'];
      $array['prioridad'] = $comunicado['prioridad'];
      $array['aula_paralelo_asignacion_materia_id'] = $comunicado['aula_paralelo_asignacion_materia_id'];
      $array['modo_calificacion_id'] = $comunicado['modo_calificacion_id'];
      $array['grupo'] = $comunicado['grupo'];
      $array['useremisor'] = $comunicado['username'];
      array_push($com, $array); //agrega la nueva fila en el array
      $cadena_usuarios = "";
    }
    echo json_encode($com);
  }

    if($boton == "listar_rol"){
        $id_rol = $_POST['id_rol'];
        $rol = $db->query("SELECT id_rol, ifnull(rol, '')  FROM sys_roles WHERE id_rol != 1 AND id_rol = $id_rol")->fetch_first();
        
        echo json_encode($rol);
    }

    if($boton == "agregar_evento"){
        //var_dump($_POST);die;
        $nombre_evento = trim($_POST['nombre_evento']);
        $descripcion = trim($_POST['descripcion']);
        $color = trim($_POST['color']);
        $fecha_inicio = $_POST['fecha_inicio'] ." ". $_POST['hora_inicio'];
        $fecha_final = $_POST['fecha_final'] ." ". $_POST['hora_final'];
        $roles = $_POST['roles'];

        //busca el ultimo registro para el codigo de comunicado 
        $codigo_mayor = $db->query("SELECT MAX(id_comunicado) as id_comunicado FROM ins_comunicados")->fetch_first();
        $id_anterior = $codigo_mayor['id_comunicado'];//id_comunicado mayor

        if(is_null($id_anterior)){
            //$id_anterior = 1;
            $nuevo_codigo = "C-1";            
        }else{
            $where = "";
             //recupera los datos del ultimo registro
            $comunicado_mayor = $db->query("SELECT id_comunicado, codigo, nombre_evento FROM ins_comunicados WHERE id_comunicado = $id_anterior ")->fetch_first();
            $codigo_anterior = $comunicado_mayor['codigo']; //codigo anterior
            $separado = explode('-', $codigo_anterior);
            $nuevo_codigo = "C-" . $separado[1] + 1;
        }

        $busqueda = $db->query("SELECT * FROM sys_roles WHERE id_rol != 1")->fetch(); //busca a todos los roles
        
        $cadena_usuarios = "";
        $cadena_estados = "";
        foreach ($busqueda as $key => $bus) {
            $id_rol = $bus['id_rol'];
            $rol = $bus['rol'];
            $cadena_usuarios = $cadena_usuarios . "," . $id_rol;
            if (in_array($id_rol, $roles)) {
                $cadena_estados = $cadena_estados . "," . "SI";
            }else{
                $cadena_estados = $cadena_estados . "," . "NO";
            }
        }

        $cadena_estados = trim($cadena_estados, ',');
        $cadena_usuarios = trim($cadena_usuarios, ',');
        //var_dump($cadena_limpia);
        //var_dump($cadena);die;
        $evento = array('codigo'=> $nuevo_codigo,
                        'fecha_inicio'=> $fecha_inicio,
                        'fecha_final'=> $fecha_final,
                        'nombre_evento'=> $nombre_evento,
                        'descripcion'=> $descripcion,
                        'color'=> $color,
                        'usuarios'=> $cadena_usuarios, 
                        'estados'=> $cadena_estados);

        //var_dump($cadena_estados);
        //var_dump($evento);
        $respuesta = $db->insert('ins_comunicados', $evento);

        if($respuesta){
            echo 1;
        }else{
            echo 2;
        }

        /*$nombre_evento = trim($_POST['nombre_evento']);
        $descripcion = trim($_POST['descripcion']);
        $color = trim($_POST['color']);
        $fecha_inicio = $_POST['fecha_inicio'] ." ". $_POST['hora_inicio'];
        $fecha_final = $_POST['fecha_final'] ." ". $_POST['hora_final'];
        $roles = $_POST['roles'];
        
        $cadena_roles = "";
        
        $cad_roles = explode(",", $cadena_roles);
        $dos = 2;
        $tres = 3;
        $cuatro = 4;
        $cinco = 5;

        $valor_uno = "";
        $valor_dos = "";
        $valor_tres = "";
        $valor_cuatro = "";
        $valor_roles = "";
        //var_dump($roles);die;
        $cadena = "";
        foreach ($roles as $key => $rol) {
            switch ($rol) {
                case $dos : $valor_uno = 2 . ":" . $rol ; break;
                case $tres : $valor_dos = 3 . ":" . $rol ; break;
                case $cuatro : $valor_tres = 4 . ":" . $rol ; break;
                case $cinco : $valor_cuatro = 5 . ":" . $rol ; break;
            }
        }

        if( empty($valor_uno)){
            $valor_uno = 2 . ":" . 0;
        }

        if( empty($valor_dos)){
            $valor_dos = 3 . ":" . 0;
        }

        if( empty($valor_tres)){
            $valor_tres = 4 . ":" . 0;
        }

        if( empty($valor_cuatro)){
            $valor_cuatro = 5 . ":" . 0;
        }

        $valor_roles = $valor_uno . "," . $valor_dos . "," . $valor_tres . "," . $valor_cuatro;

        //var_dump($cadena);die;
        $evento = array('fecha_inicio'=> $fecha_inicio,
                        'fecha_final'=> $fecha_final,
                        'nombre_evento'=> $nombre_evento,
                        'descripcion'=> $descripcion,
                        'color'=> $color,
                        'usuarios'=> $valor_roles);

        $respuesta = $db->insert('ins_comunicados', $evento);

        if($respuesta){
            echo 1;
        }else{
            echo 2;
        }*/
    }


    if($boton == "listar_usuarios"){
        //var_dump($boton);
        $valores = isset($_POST['valores'])?$_POST['valores']:'';
        $sql='';
        if($valores){
            $sql.=' and(rol_id='.$valores[0];        
        for($i=1; $i<count($valores); $i++)
        {
            $sql.=' OR rol_id='.$valores[$i];
        }       
             $sql.=')';
        }      
        $personas = $db->query("SELECT pe.*,us.id_user FROM sys_persona pe,sys_users us WHERE pe.id_persona=us.persona_id ".$sql. " GROUP BY pe.id_persona")->fetch();     
        echo json_encode($personas);
    } 

    if($boton == "agregar_usuario"){
        $id_user = isset($_POST['id_user'])?$_POST['id_user']:0;
        // var_dump($id_user);exit();
        $personas = $db->query("SELECT pe.*,us.id_user FROM sys_persona pe,sys_users us WHERE pe.id_persona=us.persona_id and us.id_user=".$id_user." GROUP BY pe.id_persona")->fetch();
        
        echo json_encode($personas);
    }

if($boton == "ver_vistos"){        
  $id_comunicado = isset($_POST['id_comunicado'])?$_POST['id_comunicado']:0;
  //obtener array de id leidos_________________________________________________
  $leidossql = $db->query("SELECT * FROM not_notificaciones noti INNER JOIN sys_persona per  ON per.id_persona=noti.persona_id WHERE noti.comunicado_id=$id_comunicado  GROUP BY per.id_persona")->fetch();
  $strpers='';
    
    foreach($leidossql as $rows){
          $strpers.=$rows['persona_id'].',';
    }
    $strpers=substr($strpers,0,-1);//trim('A,A,',',');='A,A'
    $arrpers=explode(',',$strpers);
    //recorrer los receptores________________________________________________________
    $com = $db->query("SELECT * FROM ins_comunicados com WHERE com.id_comunicado=$id_comunicado")->fetch_first(); 
    $leidos=array();
    $noleidos=array();
  if($com['usuarios']!='' && $com['persona_id']=='' && $com['grupo']=='0'){//grupal
       //recorrer el destino de usuarios
       $arrusers=explode(',',$com['usuarios']);
       $arrestados=explode(',',$com['estados']);
       $wheres='';$est=true;
       if($arrestados){
            foreach($arrestados as $key=>$rows){
                if($rows=='SI'){
                    if($est){
                        $wheres.='and('; $est=false;
                    }else{ 
                        $wheres.=' or ';
                    }
                   $wheres.=' us.rol_id='.$arrusers[$key];
               }
           } 
           $wheres.=')';
       }
             
    $sql="SELECT per.* FROM   sys_persona per INNER JOIN sys_users us ON us.persona_id=per.id_persona WHERE us.gestion_id=1 ".$wheres . " GROUP BY per.id_persona";  

    $personas = $db->query($sql)->fetch();

     foreach($personas as $rows){
         if(in_array($rows['id_persona'],$arrpers)){            
             array_push($leidos,$rows);
         }else{
             array_push($noleidos,$rows);
         }
     }//for fin $personas             
  }else if($com['usuarios']=='' && $com['persona_id']!='' && $com['grupo']=='0'){//personal
      $persona_id=explode(',',$com['persona_id']);
       $wheres='';$est=true;
       if(count($persona_id)>0){
            foreach($persona_id as $key=>$rows){
                if($est){
                        $wheres.='WHERE ';$est=false;
                    }else{ 
                        $wheres.=' or ';
                    }

                   $wheres.=' per.id_persona='.$rows;
              }  
       }     
      $sql="SELECT per.* FROM   sys_persona per $wheres  GROUP BY per.id_persona"; 
      $personas = $db->query($sql)->fetch();
  
      foreach($personas as $rows){
             if(in_array($rows,$arrpers)){                
                 array_push($leidos,$rows);
             }else{
                 array_push($noleidos,$rows);
             }
      }                                 
  }else if($com['usuarios']=='' && $com['persona_id']=='' && $com['grupo']!='0'){//docente            
        if($com['grupo']=='v' || $com['grupo']=='m'){
                 $genero=$com['grupo'];
                 $aula_aisg=$com['aula_paralelo_asignacion_materia_id'];
                  $personas = $db->query("SELECT per.* FROM int_aula_paralelo_asignacion_materia apam 
                    INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo=apam.aula_paralelo_id
                     INNER JOIN ins_inscripcion ins ON ins.aula_paralelo_id=ap.id_aula_paralelo 
                     INNER JOIN ins_estudiante est ON est.id_estudiante=ins.estudiante_id
                     INNER JOIN sys_persona per ON per.id_persona=est.persona_id
                    WHERE apam.id_aula_paralelo_asignacion_materia=$aula_aisg AND per.genero='$genero'  GROUP BY per.id_persona")->fetch();
          foreach($personas as $rows){
                 if(in_array($rows['id_persona'],$arrpers)){                        
                     array_push($leidos,$rows);
                 }else{
                     array_push($noleidos,$rows);
                 }                                            
          }//for fin $personas
        }//tipo m y v
    }//comunicado tipo docente
    $datos=array('leidos'=>$leidossql,
                  'noleidos'=>$noleidos);                
  echo json_encode($datos);
}

if($boton == "ver_vistos_recargar"){        
  $id_comunicado = isset($_POST['id_comunicado'])?$_POST['id_comunicado']:0;
  $inicial_fecha = ($_POST['inicial_fecha'] != '' && $_POST['inicial_fecha'])? date_encode($_POST['inicial_fecha']):'';
  $final_fecha = ($_POST['final_fecha'] != '' && $_POST['final_fecha'])? date_encode($_POST['final_fecha']):'';
  $ver_visto = 'no';

  if ($inicial_fecha) {
    $final_fecha =  ($final_fecha == '')? $inicial_fecha. ' 23:59:59': $final_fecha.' 23:59:59';
    $inicial_fecha = $inicial_fecha.' 00:00:00';
    //obtener array de id leidos_________________________________________________
    $leidossql = $db->query("SELECT * FROM not_notificaciones noti INNER JOIN sys_persona per  ON per.id_persona=noti.persona_id WHERE noti.comunicado_id=$id_comunicado AND noti.leido_fecha BETWEEN '$inicial_fecha' AND '$final_fecha' GROUP BY per.id_persona")->fetch();
    $strpers='';
    
    foreach($leidossql as $rows){
          $strpers.=$rows['persona_id'].',';
    }
    $strpers=substr($strpers,0,-1);//trim('A,A,',',');='A,A'
    $arrpers=explode(',',$strpers);
    //recorrer los receptores________________________________________________________
    $com = $db->query("SELECT * FROM ins_comunicados com WHERE com.id_comunicado=$id_comunicado")->fetch_first(); 
    $leidos=array();
    $noleidos=array();
  if($com['usuarios']!='' && $com['persona_id']=='' && $com['grupo']=='0' && $ver_visto == 'si'){//grupal
       //recorrer el destino de usuarios
       $arrusers=explode(',',$com['usuarios']);
       $arrestados=explode(',',$com['estados']);
       $wheres='';$est=true;
       if($arrestados){
            foreach($arrestados as $key=>$rows){
                if($rows=='SI'){
                    if($est){
                        $wheres.='and('; $est=false;
                    }else{ 
                        $wheres.=' or ';
                    }
                   $wheres.=' us.rol_id='.$arrusers[$key];
               }
           } 
           $wheres.=')';
       }
             
    $sql="SELECT per.* FROM   sys_persona per INNER JOIN sys_users us ON us.persona_id=per.id_persona WHERE us.gestion_id=1 ".$wheres . " GROUP BY per.id_persona";  

    $personas = $db->query($sql)->fetch();

     foreach($personas as $rows){
         if(in_array($rows['id_persona'],$arrpers)){            
             array_push($leidos,$rows);
         }else{
             array_push($noleidos,$rows);
         }
     }//for fin $personas             
  }else if($com['usuarios']=='' && $com['persona_id']!='' && $com['grupo']=='0' && $ver_visto == 'si'){//personal
      $persona_id=explode(',',$com['persona_id']);
       $wheres='';$est=true;
       if(count($persona_id)>0){
            foreach($persona_id as $key=>$rows){
                if($est){
                        $wheres.='WHERE ';$est=false;
                    }else{ 
                        $wheres.=' or ';
                    }

                   $wheres.=' per.id_persona='.$rows;
              }  
       }     
      $sql="SELECT per.* FROM   sys_persona per $wheres GROUP BY per.id_persona"; 
      $personas = $db->query($sql)->fetch();
  
      foreach($personas as $rows){
             if(in_array($rows,$arrpers)){                
                 array_push($leidos,$rows);
             }else{
                 array_push($noleidos,$rows);
             }
      }                                 
  }else if($com['usuarios']=='' && $com['persona_id']=='' && $com['grupo']!='0' && $ver_visto == 'si'){//docente            
        if($com['grupo']=='v' || $com['grupo']=='m'){
                 $genero=$com['grupo'];
                 $aula_aisg=$com['aula_paralelo_asignacion_materia_id'];
                  $personas = $db->query("SELECT per.* FROM int_aula_paralelo_asignacion_materia apam 
                    INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo=apam.aula_paralelo_id
                     INNER JOIN ins_inscripcion ins ON ins.aula_paralelo_id=ap.id_aula_paralelo 
                     INNER JOIN ins_estudiante est ON est.id_estudiante=ins.estudiante_id
                     INNER JOIN sys_persona per ON per.id_persona=est.persona_id
                    WHERE apam.id_aula_paralelo_asignacion_materia=$aula_aisg AND per.genero='$genero' GROUP BY per.id_persona")->fetch();
          foreach($personas as $rows){
                 if(in_array($rows['id_persona'],$arrpers)){                        
                     array_push($leidos,$rows);
                 }else{
                     array_push($noleidos,$rows);
                 }                                            
          }//for fin $personas
        }//tipo m y v
    }//comunicado tipo docente
    $datos=array('leidos'=>$leidossql,
                  'noleidos'=>$noleidos);                
  echo json_encode($datos);
}else{

}
}


?>