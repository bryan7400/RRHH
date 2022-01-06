<?php 
/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK (MARIBEL JORGE LUIS)
 */

// Define las cabeceras
header('Content-Type: application/json');

// Verifica la peticion post
 if (is_post()) {       

        //Obtiene los datos
 
        $usuario              = clear($_POST['usuario']);
        $contrasenia          = clear($_POST['contrasenia']);
     /*   $id_profesor          = clear($_POST['id_profesor']);
        $id_aula_paralelo     = clear($_POST['id_aula_paralelo']);
        $id_profesor_materia  = clear($_POST['id_profesor_materia']);
        $id_modo_calificacion = clear($_POST['id_modo_calificacion']);
        $id_area_calificacion = clear($_POST['id_area_calificacion']);
        
        $id_actividad         = clear($_POST['id_actividad']);
        $id_estudiante        = clear($_POST['id_estudiante']);
        $nota                 = clear($_POST['nota']);*/
     
        $estudiante_id = isset($_POST['id_estudiante'])?$_POST['id_estudiante']:0;
        $actividad_mat_id = isset($_POST['actividad_mat_id'])?$_POST['actividad_mat_id']:0;
        $nota = isset($_POST['nota'])?$_POST['nota']:0;
        
        //valores de prueba 
            // $usuario     = "martha"; 
            // $contrasenia = "martha2019";  
            // $id_user     = 5;
            // $id_aula_paralelo = 8;
            // $id_profesor_materia = 1;
            // $id_modo_calificacion = 1;
            // $id_area_calificacion = 1;
            // $id_actividad=1; 
         
        // Encripta la contraseña para compararla en la base de datos
        $usuario = md5($usuario);
        $contrasenia = encrypt($contrasenia);

        // Obtiene los datos del usuario
        $usuario = $db->select('gestion_id, persona_id')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
        $id_gestion = $usuario['gestion_id'];
   
        // Verifica la existencia del usuario 
        if ($usuario) {
             if(is_numeric($nota) && $nota>=0 && $nota<=100){
         //BUSCAR NOTA CON EST Y ACTIVIDAD
            $horario = $db->from('cal_estudiante_actividad_nota')->where('estudiante_id',$estudiante_id)->where('actividad_materia_modo_area_id',$actividad_mat_id)->fetch_first();
            if($horario){ 
                //SI SE ENCUENTRA ACTUALIZAR
                $datos = array(  
                        'nota_cuantitativa' => $nota  
                    ); 
                $db->where('id_estudiante_actividad_nota', $horario['id_estudiante_actividad_nota'])->update('cal_estudiante_actividad_nota', $datos);
 
            }else{
                //SI NO SE ENCUANTRA CREAR
                $db->insert('cal_estudiante_actividad_nota', array(
                        'estudiante_id' => $estudiante_id,
                        'actividad_materia_modo_area_id' => $actividad_mat_id, 
                        'nota_cuantitativa' => $nota 
                 ));

                  // $estado= 'crear';
                }
             }else{
                 echo json_encode(array('estado' => 'Deve ser un valor entre 0-100'.$id_estudiante));
                exit();
             }//la nota no es valida
            
             
                //$con ++;
            // }
            // if($cont == $con){
            //     //cambia el estado de confirmado en la tabla cal_actividad_materia_modo_area
            //     $db->query("UPDATE cal_actividad_materia_modo_area SET confirmado = 'S' WHERE id_actividad_materia_modo_area = '".$id_actividad."'")->execute();
            //     if ($db->affected_rows) {
            //         echo 1; //se cambio el estado de la actividad a calificado
            //     }else{
            //         echo 2; //No se pudo cambiar el estado de la columna confirmado
            //     }
            // }else{
            //     echo 3; //el numero de filas registrados con coincide con el numero de estudiantes
            // }
            // Instancia el objeto
            if($db){

                $respuesta = array(
                    'estado'        => 's',
                    'id_estudiante' => $estudiante_id,
                    'nota'          => $nota//,
                    //'estado' => $estado
                );
                // Devuelve los resultados
                echo json_encode($respuesta);

            }else {
                // Devuelve los resultados
                echo json_encode(array('estado' => 'no se ha registrado nota'.$id_estudiante));
            }

        }else {
            // Devuelve los resultados
            echo json_encode(array('estado' => 'n login'));
        }
 }else{
     // Devuelve los resultados
    echo json_encode(array('estado' => 'n post'));
 }

 ?>