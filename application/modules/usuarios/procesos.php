<?php  
  
    //obtiene el valor del boton   
    $boton      = $_POST['boton'];

    //obtiene la fecha actual del sistema
    $fecha_actual = Date('Y-m-d');

    //obtiene la gestion actual 
    $id_gestion = ($_POST['id_gestion'])-1;
    $gestion    = ($_POST['id_gestion']);  

    //obtiene el valor del boton 
    if($boton == 'btn_copiar_modo_calificacion' && $id_gestion > 0){

        // Obtiene los modo calificacion
        $modos = $db->select('z.*,g.gestion')
                    ->from('cal_modo_calificacion z')
                    ->join('ins_gestion g','g.id_gestion=z.gestion_id')
                    ->where('g.id_gestion', $id_gestion)
                    ->where('z.estado', 'A')
                    ->order_by('z.id_modo_calificacion', 'asc')->fetch();
        foreach ($modos as $value) {

            // Instancia 
            $data = array(
                'fecha_inicio'          => $value['fecha_inicio'],
                'fecha_final'           => $value['fecha_final'],
                'descripcion'           => $value['descripcion'],
                'gestion_id'            => $gestion,
                'usuario_registro'      => $_user['id_user'],
                'fecha_registro'        => date('Y-m-d H:i:s'),
                'usuario_modificacion'  => 0,
                'fecha_modificacion'    => '0000-00-00 00:00:00'
            );
            // Guarda modo de calificacion
            $id_modo = $db->insert('cal_modo_calificacion', $data);

            // Guarda el proceso
            $db->insert(
                    'sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso' => date('H:i:s'),
                    'proceso' => 'c',
                    'nivel' => 'l',
                    'detalle' => 'Se creó el modo de calificacion con identificador número ' . $id_modo . '.',
                    'direccion' => $_location,
                    'usuario_id' => $_user['id_user']
            ));           
        }            

        // Validacion
        if($id_modo > 0){

            echo 1;
        }else{

            echo 2;
        }
    }

    //obtiene el valor del boton 
    if($boton == 'btn_copiar_area_evaluacion' && $id_gestion > 0){

        // Obtiene los modo calificacion
        $areas = $db->select('z.*,g.gestion')
                    ->from('cal_area_calificacion z')
                    ->join('ins_gestion g','g.id_gestion=z.gestion_id')
                    ->where('g.id_gestion',$id_gestion)
                    ->order_by('z.id_area_calificacion', 'asc')->fetch();
             
        foreach ($areas as $value) {

            // Instancia 
            $data = array(
                'descripcion'           => $value['descripcion'],
                'ponderado'             => $value['ponderado'],
                'gestion_id'            => $gestion,
                'usuario_registro'      => $_user['id_user'],
                'fecha_registro'        => date('Y-m-d H:i:s'),
                'usuario_modificacion'  => 0,
                'fecha_modificacion'    => '0000-00-00 00:00:00',
                'imagen_area'           => '',
            );
            // Guarda modo de calificacion
            $id_area = $db->insert('cal_area_calificacion', $data);

            // Guarda el proceso
            $db->insert(
                    'sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso' => date('H:i:s'),
                    'proceso' => 'c',
                    'nivel' => 'l',
                    'detalle' => 'Se creó el modo de calificacion con identificador número ' . $id_area . '.',
                    'direccion' => $_location,
                    'usuario_id' => $_user['id_user']
            ));           
        }            

        // Validacion
        if($id_area > 0){

            echo 1;
        }else{

            echo 2;
        }
    }

    //obtiene el valor del boton 
    if($boton == 'btn_copiar_tipo_estudiante' && $id_gestion > 0){

        // Obtiene los modo calificacion
        $tipos = $db->select('z.*,g.gestion')
                    ->from('ins_tipo_estudiante z')
                    ->join('ins_gestion g','g.id_gestion=z.gestion_id')
                    ->where('g.id_gestion', $id_gestion)
                    ->where('z.estado', 'A')
                    ->order_by('z.id_tipo_estudiante', 'asc')->fetch();

        foreach ($tipos as $value) {

            // Instancia 
            $data = array(
                'nombre_tipo_estudiante'=> $value['nombre_tipo_estudiante'],
                'descripcion'           => $value['descripcion'],
                'gestion_id'            => $gestion,
                'usuario_registro'      => $_user['id_user'],
                'fecha_registro'        => date('Y-m-d H:i:s'),
                'usuario_modificacion'  => 0,
                'fecha_modificacion'    => '0000-00-00 00:00:00',
                'imagen_area'           => '',
            );
            // Guarda modo de calificacion
            $id_tipo = $db->insert('ins_tipo_estudiante', $data);

            // Guarda el proceso
            $db->insert(
                    'sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso' => date('H:i:s'),
                    'proceso' => 'c',
                    'nivel' => 'l',
                    'detalle' => 'Se creó el modo de calificacion con identificador número ' . $id_tipo . '.',
                    'direccion' => $_location,
                    'usuario_id' => $_user['id_user']
            ));           
        }            

        // Validacion
        if($id_tipo > 0){

            echo 1;
        }else{

            echo 2;
        }
    }

    //obtiene el valor del boton 
    if($boton == 'btn_copiar_nivel_academico' && $id_gestion > 0){

        // Obtiene los modo calificacion
        $niveles = $db->select('z.*,g.gestion')
                      ->from('ins_nivel_academico z')
                      ->join('ins_gestion g','g.id_gestion=z.gestion_id')
                      ->where('g.id_gestion', $id_gestion)
                      ->order_by('z.id_nivel_academico', 'asc')->fetch();

        foreach ($niveles as $value) {

            // Instancia 
            $data = array(
                'nombre_nivel'          => $value['nombre_nivel'],
                'descripcion'           => $value['descripcion'],
                'gestion_id'            => $gestion,
                'usuario_registro'      => $_user['id_user'],
                'fecha_registro'        => date('Y-m-d H:i:s'),
                'usuario_modificacion'  => 0,
                'fecha_modificacion'    => '0000-00-00 00:00:00'
            );
            // Guarda modo de calificacion
            $id_tipo = $db->insert('ins_nivel_academico', $data);

            // Guarda el proceso
            $db->insert(
                    'sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso' => date('H:i:s'),
                    'proceso' => 'c',
                    'nivel' => 'l',
                    'detalle' => 'Se creó el modo de calificacion con identificador número ' . $id_tipo . '.',
                    'direccion' => $_location,
                    'usuario_id' => $_user['id_user']
            ));           
        }            

        // Validacion
        if($id_tipo > 0){

            echo 1;
        }else{

            echo 2;
        }
    }

    //obtiene el valor del boton 
    if($boton == 'btn_copiar_turno' && $id_gestion > 0){

        // Obtiene los modo calificacion
        $turnos = $db->select('z.*,g.gestion')
                    ->from('ins_turno z')
                    ->join('ins_gestion g','g.id_gestion=z.gestion_id')
                    ->where('g.id_gestion', $id_gestion)
                    ->where('z.estado', 'A')
                    ->order_by('z.id_turno', 'asc')->fetch();

        foreach ($turnos as $value) {

            // Instancia 
            $data = array(
                'nombre_turno'=> $value['nombre_turno'],
                'descripcion'           => $value['descripcion'],
                'gestion_id'            => $gestion,
                'usuario_registro'      => $_user['id_user'],
                'fecha_registro'        => date('Y-m-d H:i:s'),
                'usuario_modificacion'  => 0,
                'fecha_modificacion'    => '0000-00-00 00:00:00',
                'hora_inicio'           => $value['hora_inicio'],
                'hora_final'           => $value['hora_final'],
            );
            // Guarda modo de calificacion
            $id_turno = $db->insert('ins_turno', $data);

            // Guarda el proceso
            $db->insert(
                    'sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso' => date('H:i:s'),
                    'proceso' => 'c',
                    'nivel' => 'l',
                    'detalle' => 'Se creó el modo de calificacion con identificador número ' . $id_turno . '.',
                    'direccion' => $_location,
                    'usuario_id' => $_user['id_user']
            ));           
        }            

        // Validacion
        if($id_turno > 0){

            echo 1;
        }else{

            echo 2;
        }
    }
?>