<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */
//var_dump($_POST);
 

// Verifica la peticion post
if (is_post()) {
    
        // Verifica la existencia de datos
		if (isset($_POST['id_modo_calificacion']) && isset($_POST['vector'])) {
            
			// Obtiene los datos
			$id_modo_calificacion = (isset($_POST['id_modo_calificacion'])) ? clear($_POST['id_modo_calificacion']) : 0;
      
				// instancia para crear +
            //iniciar todo en I
             $editar2 = array(
                        'estado' =>'I'
                 );
            $db->where('modo_calificacion_id', $id_modo_calificacion)->update('cal_modo_calificacion_area_calificaion', $editar2);
            
            foreach($_POST['vector'] as $d=>$row){
                $area_calificacion_id=$_POST['vector'][$d]; 
                 //$id_modo_calificacion=1 fijo
                //$area_calificacion_id=1 //,2,3,4
                //ver si existe 
                $result = $db->query("SELECT *FROM cal_modo_calificacion_area_calificaion WHERE modo_calificacion_id= $id_modo_calificacion  and area_calificacion_id= $area_calificacion_id")->fetch();
                
                if($result){
               //editar
 
                 $result_area_calific = $db->query("SELECT *FROM cal_modo_calificacion_area_calificaion WHERE modo_calificacion_id= $id_modo_calificacion")->fetch();
               
                    
                //$result_area_calific= 
                    //id_modo_calificacion=1,2,3,4,23,24,25,...
                    //modo_sal_id=1                                      //area_cal_id=1,2,3,4,5
                    
                 //$id_modo_calificacion=1 fijo
                //$area_calificacion_id=1 //,2,3,4
                    
                //recorremos el recorrer con id actual 
                foreach($result as $d=>$row){
                    $id_modo_area_calificacion=$row['id_modo_calificacion_area_calificacion'];
                     //si area_calificacion_id 2=recorrido 2
                   
          
                    if($row['area_calificacion_id']==$area_calificacion_id){
                        $estado='A'; 
                    }else{
                        
                        $estado='I'; 
                    }
                    $editar2 = array(
                        'estado' =>$estado
                        );
                        $db->where('id_modo_calificacion_area_calificacion', $id_modo_area_calificacion)->update('cal_modo_calificacion_area_calificaion', $editar2);
                   //echo $editar2['estado'].'_____'; 
                       // echo 3;   
                    
                   }
                
                 $resp= 2;   
                }else{
                //var_dump($result);exit();
                /*no
                    //create*/
                    $crear = array(
                        'modo_calificacion_id' => $_POST['id_modo_calificacion'],
                        'area_calificacion_id' => $_POST['vector'][$d],
                        'estado'=>'A'
                    ); 
                    $id_modo_calificaciondb = $db->insert('cal_modo_calificacion_area_calificaion', $crear); 
                    // Guarda el proceso
                    $db->insert('sys_procesos', array(
                        'fecha_proceso' => date('Y-m-d'),
                        'hora_proceso' => date('H:i:s'),
                        'proceso' => 'c',
                        'nivel' => 'l',
                        'detalle' => 'Se creó el modo calificacion area calificacion con identificador número ' . $id_modo_calificaciondb . '.',
                        'direccion' => $_location,
                        'usuario_id' => $_user['id_user']
                    ));
                    $resp=1;
                }
                
   
                
                
				
            } 
            
            echo $resp;
				// Crea la union de instancias
				//$instacia_union = array_merge_recursive($modocalificacion, $crear);
				
				// Crea el modocalificacion
				// Crea la notificacion
				// set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
				// redirect('?/modocalificacion/listar');
				
			//}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	// } else {
	// 	// Redirecciona la pagina
	// 	redirect('?/modocalificacion/listar');
	// }
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>