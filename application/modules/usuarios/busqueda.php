<?php

    // Obtiene la gestion actual
    $id_gestion = $_gestion['id_gestion'];

    // Obtiene los usuarios
    $usuarios = $db->select('u.*, r.id_rol, r.rol, CONCAT(r.id_rol,"@",r.rol) rols , CONCAT(p.nombres," ", p.primer_apellido," ", p.segundo_apellido) persona, CONCAT(u.logout_at,"@",u.login_at) actividad, u.logout_at, u.login_at')
    ->from('sys_users u')
    ->join('sys_roles r', 'u.rol_id = r.id_rol', 'inner')
    ->join('sys_persona p', 'u.persona_id = p.id_persona', 'inner')
    ->where('u.visible', 's')
    ->where('u.estado', 'A')->fetch();
    //->where('u.gestion_id', $id_gestion)
    //var_dump($usuarios);die;
    $auxiliar = array(); 
    foreach($usuarios as $key => $value){
        $respuesta =''; $codigo_u ='';

        if ($value['logout_at'] == '0000-00-00 00:00:00'){
            if ($value['login_at'] == '0000-00-00 00:00:00'){
                $respuesta = 'NO';
            }else{
                $respuesta = moment($value['login_at']);
            }                 
        }else{
            $respuesta = 'NO';
        }

        if($value['id_rol']==5){
          $codigo_u = $value['codigo_u'];
        }else{
          $codigo_u = '';
        }
        
        $array = (array) [
                      'id_user'     => $value['id_user'],
                      'avatar'      => $value['avatar'],
                      'username'    => $value['username'],
                      'email'       => $value['email'],
                      'rol'         => $value['rol'],
                      'active'      => $value['active'],
                      'actividad'   => $respuesta,
                      'persona'     => $value['persona'],
                      'codigo'      => $codigo_u,
                     ];
        array_push($auxiliar, $array);
    }
    //var_dump($auxiliar);die;
    echo json_encode($auxiliar);
?>