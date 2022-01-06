<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */
$id_gestion = $_gestion['id_gestion'];
// Verifica la peticion post
if (is_post()) {
	// Verifica la cadena csrf
	//if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos id_asignacion
 
		if (isset($_POST['aula_paralelo_id']) && isset($_POST['id_docente'])) {
			// Obtiene los datos
			//$id_aula = (isset($_POST['id_aula'])) ? clear($_POST['id_aula']) : 0;
			$aula_paralelo_id = clear($_POST['aula_paralelo_id']);
			//$profesor_materia_id = isset(($_POST['id_docente']))?($_POST['id_docente']):0;
			$asignacion_id = isset(($_POST['id_docente']))?($_POST['id_docente']):0;//id_asiganacion
			$materia_id = isset(($_POST['id_materia']))?($_POST['id_materia']):0;
            //var_dump($materia_id);exit();
            $tipoAc = clear($_POST['tipoAc']); //new
            $horario_id = clear($_POST['horario']); //horaInicio
            //$hora_fin = clear($_POST['hora_fin']); //hora_fin
            $dia = clear($_POST['dia']); //hora_fin
            $fecha_actual = date('Y-m-d H:i:s');
            
			$id_horario_profesor_materia =  isset(($_POST['aula_par_prof_mat_id']))?($_POST['aula_par_prof_mat_id']):0;//solo en edit?
            
			$id_aula_asig_mat =  isset(($_POST['id_aula_asig_mat']))?($_POST['id_aula_asig_mat']):0;//solo en edit?
			$misma_materia =  isset(($_POST['misma_materia']))?($_POST['misma_materia']):0;//solo en edit?
 
            //var_dump($misma_materia);exit();
            //ver dubplicados
             $where = array(
                     'curso_paralelo_id' => $aula_paralelo_id,
                     
                    'estado'=>'A'
                );
    //verifica que no se ingrese en horario de descanso
/*        */   
            $result = $db->query("SELECT  * FROM ins_horario_dia hd  WHERE hd.estado='A' AND  hd.complemento='descanso'  AND  hd.id_horario_dia=".$horario_id)->fetch();  
         
            if(!$result){
              
			// Verifica si es creacion o modificacion
			if ($tipoAc=='edit') {
                 //verificar que no exista otro con el mismo horario y dia
              $result = $db->query("SELECT * FROM ins_horario_profesor_materia hpm,
int_aula_paralelo_asignacion_materia apam
WHERE 
apam.`id_aula_paralelo_asignacion_materia`=hpm.`aula_paralelo_asignacion_materia_id` AND 
hpm.horario_dia_id='$horario_id' AND hpm.dia_semana_id='$dia' AND apam.aula_paralelo_id='$aula_paralelo_id' AND hpm.estado!='I' AND apam.estado!='I'")->fetch(); //$result = $db->query("SELECT * FROM ins_horario_profesor_materia   WHERE horario_dia_id='$horario_id' AND dia_semana_id='$dia' AND curso_paralelo_id='$aula_paralelo_id' AND   estado!='I'")->fetch();
                 
                
             //o caso de ser el mismo id origen
            //tiene dato o es misma materia
              if(!$result || $misma_materia==1 ){ 
                    $datosEdit = array(  
                        'usuario_modificacion' => $_user['id_user'],
                       'fecha_modificacion'=>$fecha_actual,
                        'horario_dia_id'=>$horario_id,
                        'dia_semana_id'=>$dia 
                    ); 
                    $db->where('id_horario_profesor_materia', $id_horario_profesor_materia)->update('ins_horario_profesor_materia', $datosEdit);
                  //obtener el id de asiganacion
                //var_dump($db);exit();
                  $datosEdit2 =  array(  
                        'usuario_modificacion' => $_user['id_user'],
                       'fecha_modificacion'=>$fecha_actual,
                        //'aula_paralelo_id'=>$horario_id,
                        //'materia_id'=>$dia,
                        'asignacion_id'=>$asignacion_id,//,
                        //'profesor_id'=>$profesor_id,
                         'materia_id'=>$materia_id  
                    ); 
                    $db->where('id_aula_paralelo_asignacion_materia', $id_aula_asig_mat)->update('int_aula_paralelo_asignacion_materia', $datosEdit2);
                  
                  /* $datosEdit = array(  
                        'usuario_modificacion' => $_user['id_user'],
                       'fecha_modificacion'=>$fecha_actual,
                        'horario_dia_id'=>$horario_id,
                        'dia_semana_id'=>$dia,
                        'profesor_id'=>$profesor_id,
                        'materia_id'=>$materia_id  
                    ); 
                    $db->where('id_horario_profesor_materia', $id_horario_profesor_materia)->update('ins_horario_profesor_materia', $datosEdit);
                  */
                  
                    //echo 'idaula..'.$aula_paralelo_id.' profesor:'.$profesor_materia_id;
                    // Guarda el proceso
                    $db->insert('sys_procesos', array(
                        'fecha_proceso' => date('Y-m-d'),
                        'hora_proceso' => date('H:i:s'),
                        'proceso' => 'u',
                        'nivel' => 'l',
                        'detalle' => 'Se modificó la sigancion de horario_paralelo_materiacon identificador número ' . $id_horario_profesor_materia . '.',
                        'direccion' => $_location,
                        'usuario_id' => $_user['id_user']
                    ));

                    // Crea la notificacion
                    //set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');

                    // Redirecciona la pagina
                    //redirect('?/aula/ver/' . $id_aula);
                  
                    echo 2;//editado con exito
                } else{
                    echo 3;//dato ya existente en el mismo horario
                }   
			} else 
            
            if ($tipoAc=='new')
            {
          
              //verificar que no exista otro con el mismo horario y dia
            //  $result = $db->query("SELECT * FROM ins_horario_profesor_materia   WHERE horario_dia_id='$horario_id' AND dia_semana_id='$dia' AND curso_paralelo_id=$aula_paralelo_id AND   estado!='I'")->fetch();  
            $result = $db->query("SELECT * FROM ins_horario_profesor_materia  hpm 
                INNER JOIN int_aula_paralelo_asignacion_materia ap on  ap.`id_aula_paralelo_asignacion_materia`=hpm.`aula_paralelo_asignacion_materia_id`

                WHERE hpm.horario_dia_id='$horario_id' AND hpm.dia_semana_id='$dia' 
                AND ap.aula_paralelo_id=$aula_paralelo_id AND   ap.estado!='I' AND   hpm.estado!='I'
                ")->fetch(); 
          //buscar si encustra devuelve el id, si no crear
            if(!$result){
                //ACCESO A NUEVOS HORARIOS Y ASIGANAIONES
                
               //si existe el mismo (prof) en el mismo curso y materia
                //(quiza el podria cambiar? )
                
                //VER SI EXISTE LOS DATOS EN AULA_ASIGNACION 
               $colsultaasig = $db->query_first("SELECT * FROM int_aula_paralelo_asignacion_materia   WHERE aula_paralelo_id='$aula_paralelo_id' AND asignacion_id='$asignacion_id' AND materia_id=$materia_id AND estado!='I'")->fetch();//retorna un array
                
                //OBTENEMOS LA ASIGANCION ACTUAL
               //$asignacion_id=isset($colsultaasig[0]['asignacion_id'])?$colsultaasig[0]['asignacion_id']:false;
                //-------------------
                //OBTENEMOS EL AULA_ASIGANCION ACTUAL
               $aula_asignacion_id=isset($colsultaasig[0]['id_aula_paralelo_asignacion_materia'])?$colsultaasig[0]['id_aula_paralelo_asignacion_materia']:false;
                
               if(!$colsultaasig){//ver que no hay el mismo
                // EN CASO DE SER UNICO AGREGAR NUEVO AULA ASIGANCION  
               $asignacion_materia = array(
                    'aula_paralelo_id' => $aula_paralelo_id, 
                    'asignacion_id'=>$asignacion_id, 
                    'materia_id'=>$materia_id, 
                    'estado' => 'A',
                    //'profesor_materia_id' => se eliminara,
                    'gestion_id' => $id_gestion, 
                    'usuario_registro' => $_user['id_user'],
                    'fecha_registro'=>$fecha_actual, 'usuario_modificacion' => $_user['id_user'],
                    'fecha_modificacion'=>$fecha_actual//,
                    //'horario_dia_id'=>$horario_id,
                    //'dia_semana_id'=>$dia,
                );
  
                $aula_asignacion_id = $db->insert('int_aula_paralelo_asignacion_materia', $asignacion_materia);//retorna un id
                //$siexiste=$re; 
              // } else{
                //edit
                   
               }
                
                //GREGAR HORARIO EN HORARIOS
               $horario_prof = array(  
                    //'curso_paralelo_id' => $aula_paralelo_id, 
                    //'profesor_materia_id' => se eliminara,
                    'horario_dia_id'=>$horario_id,
                    'dia_semana_id'=>$dia,  
                    'aula_paralelo_asignacion_materia_id'=>$aula_asignacion_id,
                   
                    'gestion_id' => $id_gestion, 
                    'estado' => 'A',
                    'usuario_registro' => $_user['id_user'],
                    'fecha_registro'=>$fecha_actual, 'usuario_modificacion' => $_user['id_user'],
                    'fecha_modificacion'=>$fecha_actual,
                   
                    //'profesor_id'=>$profesor_id,
                    //'materia_id'=>$materia_id
 
                );
  
                   $id_aula = $db->insert('ins_horario_profesor_materia', $horario_prof);
                
                
                

                    // Guarda el proceso
                    $db->insert('sys_procesos', array(
                        'fecha_proceso' => date('Y-m-d'),
                        'hora_proceso' => date('H:i:s'),
                        'proceso' => 'c',
                        'nivel' => 'l',
                        'detalle' => 'Se creó el horarios con identificador número ' . $id_aula . '.',
                        'direccion' => $_location,
                        'usuario_id' => $_user['id_user']
                    ));
 
                    echo 1;//guradado con exitop
              
            }  else{
                  echo 3;//horario ya ocupado repetido
            }   
                
			}
            }else{
                echo 4;//horario en descanso warning
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