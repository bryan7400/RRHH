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
		// Verifica la existencia de datos
		//	var_dump($_POST);exit();
		if(isset($_POST['id_asignacion2']) && isset($_POST['id_gondola2']) && isset($_POST['id_conductor2']) ) {
			// Obtiene los datos
			$id_ruta = $_POST['id_asignacion2'];
            $id_gondola = $_POST['id_gondola2'];
            $id_conductor = $_POST['id_conductor2'];
            
			//ver si existe la asignacion
            $id_asignacion = $db->select('*')
                        ->from('gon_conductor_gondola')
                        ->where('conductor_id', $id_conductor)
                        //->where('gondola_id', $id_gondola)
                        ->fetch_first();
			
            if($id_asignacion){
				//ya existe la asignacion
			 
				//$id=$query['conductor_id'];
				$id=$id_asignacion['id_conductor_gondola']; 
				
				//quitar anteriores asignaciones
				 // $db->delete()->from('gon_conductor_gondola')->where('id_conductor_gondola', $id)->limit(1)->execute();
              	//$db->where('id_conductor_gondola', $id)->update('gon_conductor_gondola', array('conductor_id' => '0'));

				//agregar a nueva asignacion
				$res = $db->where('id_conductor_gondola', $id)->update('gon_conductor_gondola', array('gondola_id' => $id_gondola));
                $id_asignacion = $id;
				//var_dump($id_asignacion);exit();
                //echo "1";            
            }
            else{
				//var_dump('else');exit();
           
                $id_asignacion=$db->insert('gon_conductor_gondola', array(
                    'conductor_id' => $id_conductor,
                    'gondola_id' => $id_gondola
                ));

				//ver si existe en una ruta esta asignacion
				
            /*    $res=$db->query("SELECT *
				 FROM gon_rutas rut  WHERE rut.conductor_gondola_id='11'
				 AND rut.estado='1'")->fetch();
				
				if($res){
                echo "5";
					
				}else{
                $db->where('id_ruta', $id_ruta)->update('gon_rutas', $gondolas);
                echo "1";
				}*/
					
            }
				
			if($id_conductor=''){
				echo "4";
			}else{
				
                $res=$db->query("SELECT *
				 FROM gon_rutas rut  WHERE rut.conductor_gondola_id='$id_asignacion'
				 AND rut.estado='1'")->fetch();
				
				if($res){
					$gondolas = array(
						'conductor_gondola_id' => '-1'
					);
					$db->where('id_ruta', $id_ruta)->update('gon_rutas', $gondolas);
					echo "5";
					
				}else{
					$gondolas = array(
						'conductor_gondola_id' => $id_asignacion
					);
					$db->where('id_ruta', $id_ruta)->update('gon_rutas', $gondolas);
					echo "1";
				}
			}
			
			//if

            //redirect('?/gon-gondolas/listar');

		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	/*} else {
		// Redirecciona la pagina
		redirect('?/gondolas/listar');
	}*/
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>