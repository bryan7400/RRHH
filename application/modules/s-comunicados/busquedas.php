<?php
    $comunicados = $db->select('z.*')->from('ins_comunicados z')->where('estado', 'A')->order_by('z.id_comunicado', 'desc')->fetch();

    $com = array(); 
    $cadena_usuarios = "";
//var_dump($comunicados);exit();
   // if($comunicados['usuarios']==''){
        
   // echo json_encode($comunicados); 
   // }else{
    //armando el nuevo array con los roles respectivos
    foreach ($comunicados as $key => $comunicado) {
        $cadena_limpia = '';
        //captura los roles del comunicado
        $id = $comunicado['usuarios'];
        $persona_id = $comunicado['persona_id'];
        if($id==''){
            $cadena_limpia='Sin usuarios';
        }else{
            $usuarios = explode(',', $id);//conveirto en array []
            $contador = sizeof($usuarios); //cuenta la cantidad de usuarios
            //$persona_id = explode(',', $persona_id);//
            //$cont_persona_id = sizeof($persona_id); //

            for($i = 0; $i < $contador; $i++){
                $rol = $db->query("SELECT id_rol, rol FROM sys_roles WHERE id_rol = $usuarios[$i]")->fetch_first();
                $cadena_usuarios =  $cadena_usuarios . "," . $rol['rol']; //contatena el nombre de los roles
            }
            $cadena_limpia =  trim($cadena_usuarios, ','); //quita la primera coma
        }
           // var_dump($cadena_usuarios);exit();
        //

        

        $array['id_comunicado'] = $comunicado['id_comunicado'];
        $array['codigo'] = $comunicado['codigo'];
        $array['fecha_inicio'] = $comunicado['fecha_inicio'];
        $array['fecha_final'] = $comunicado['fecha_final'];
        $array['nombre_evento'] = $comunicado['nombre_evento'];
        $array['descripcion'] = $comunicado['descripcion'];
        $array['color'] = $comunicado['color'];
        $array['usuarios'] = $cadena_limpia;
        $array['estados'] = $comunicado['estados'];
        $array['estado'] = $comunicado['estado'];
        $array['ids'] = $comunicado['usuarios'];
        $array['persona_id'] = $comunicado['persona_id'];
        $array['file'] = $comunicado['file'];
        $array['prioridad'] = $comunicado['prioridad'];
        array_push($com, $array); //agrega la nueva fila en el array
        $cadena_usuarios = "";
    }
    echo json_encode($com); 
        
   // }
    //

?>