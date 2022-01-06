<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion post
if (is_post()) {
	// Verifica la cadena csrf
	//if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos id_asignacion
 
		if (isset($_POST['hora_inicio']) && isset($_POST['hora_fin'])) {
			// Obtiene los datos
			//$id_aula = (isset($_POST['id_aula'])) ? clear($_POST['id_aula']) : 0;
			//$aula_paralelo_id = clear($_POST['aula_paralelo_id']);
			//$profesor_materia_id = isset(($_POST['id_docente']))?($_POST['id_docente']):0;
            $tipoAc = clear($_POST['tipoAc']); //new
            $hora_inicio = clear($_POST['hora_inicio']); //horaInicio
            $hora_fin = clear($_POST['hora_fin']); //hora_fin
            $turno_id = clear($_POST['turno_sel']); //si
            $fecha_actual = date('Y-m-d H:i:s');
            
			$horario_id =  isset(($_POST['horario_id']))?($_POST['horario_id']):0;//solo en edit?
			$descanso =  isset(($_POST['descanso']))?$_POST['descanso']:'';//solo en edit?
        $validator=true;
        if (isset($_POST['descanso']) && $_POST['descanso'] == '1'){
              $descanso='descanso';
            if($tipoAc=='edit'){
              $versiidhora=$db->query("SELECT * FROM ins_horario_profesor_materia WHERE estado='A' AND horario_dia_id=".$horario_id)->fetch();
                if(!$versiidhora) {
                   $validator=true; 
                }else{
                    $validator=false; 
                } 
            }
            
        }else
              $descanso='';
          
           
			// Verifica si es creacion o modificacion
			if ($tipoAc=='edit') {
                //verificar que el horaio no contenga materias
               
                    
                if($validator) {//validar si no existia en descando otro igual
                    //si no tien datos
                       // Instancia el aula
                    $aula = array(

                        'hora_ini' => $hora_inicio,
                        'hora_fin' => $hora_fin,
                        'turno_id' => $turno_id,
                        'complemento'=>$descanso,
                    'usuario_modificacion' => $_user['id_user'],
                    'fecha_modificacion' => $fecha_actual

                        //'usuario_modificacion' => $_user['id_user'],

                    );

                    // Modifica el aula
                    $db->where('id_horario_dia', $horario_id)->update('ins_horario_dia', $aula);

                    $db->insert('sys_procesos', array(
                        'fecha_proceso' => date('Y-m-d'),
                        'hora_proceso' => date('H:i:s'),
                        'proceso' => 'u',
                        'nivel' => 'l',
                        'detalle' => 'Se modificó horario con identificador número ' . $horario_id . '.',
                        'direccion' => $_location,
                        'usuario_id' => $_user['id_user']
                    ));

                    echo 2;
                }else{
                   echo 4;//en dato editado a descanso con datos 
                }

                
                
                
                
             
			} else 
            
            if ($tipoAc=='new')
            {
                //verificar que no exista  el usuario
             //$result = $db->query("SELECT * FROM ins_horario_profesor_materia   WHERE curso_paralelo_id=$aula_paralelo_id AND profesor_materia_id=$profesor_materia_id AND 'estado'!='I'")->fetch();
              //verificar que no exista otro con el mismo horario y dia
              $result = $db->query("SELECT * FROM ins_horario_dia   WHERE hora_ini='$hora_inicio' AND turno_id='$turno_id' AND estado!='I'")->fetch();
                
               // $result = $db->query("SELECT * FROM ins_horario_profesor_materia")->where($where)->fetch();
           //$num=$db->affected_row;
            //echo 'num filas afect'.$num;
            //echo '---'.$aula_paralelo_id.' ---'.$profesor_materia_id;
            if(!$result){
           // if($db->affected_row==0){
               $aula = array(  
                    'hora_ini' => $hora_inicio,
                    'hora_fin' => $hora_fin,
                    'complemento'=>$descanso,
                    'estado' => 'A',
                    'usuario_registro' => $_user['id_user'],
                    'fecha_registro'=>$fecha_actual,
                    'usuario_modificacion' => $_user['id_user'],
                    'fecha_modificacion' => $fecha_actual,
                    'turno_id' => $turno_id 
                   // 'dia_semana_id'=>$dia
                 

                );
 
                //verificar que no exista
               
                   $id_aula = $db->insert('ins_horario_dia', $aula);

                    // Guarda el proceso
                    $db->insert('sys_procesos', array(
                        'fecha_proceso' => date('Y-m-d'),
                        'hora_proceso' => date('H:i:s'),
                        'proceso' => 'c',
                        'nivel' => 'l',
                        'detalle' => 'Se creó el horarios con identificador número ' . $turno_id . '.',
                        'direccion' => $_location,
                        'usuario_id' => $_user['id_user']
                    ));

                    // Crea la notificacion
                    //set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');

                    // Redirecciona la pagina
                    //redirect('?/aula/listar');
                    echo 1;
              
            } else{
                   echo 3;
            } 
                
			}
                
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	//} else {
		// Redirecciona la pagina
		//redirect('?/aula/listar');
	//}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>