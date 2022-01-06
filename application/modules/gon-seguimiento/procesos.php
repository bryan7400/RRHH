<?php   
    
    //obtiene el valor del boton    
    $boton = $_POST['boton'];
    //obtiene la fecha actual del sistema
    $fecha_actual = Date('Y-m-d');
    //obtiene la gestion actual 
    $id_gestion = $_gestion['id_gestion'];   

    //obtiene el valor del boton 
    if($boton == 'listar_todo_rutas'){ 
/*		$rutas = $db->query('SELECT  eser.id_user,tut.*,con.categoria,con.lentes,per.nombres,per.*,gon.nombre as nombre_gondola,gon.placa from gon_rutas tut
INNER JOIN gon_conductor_gondola cg ON cg.id_conductor_gondola= tut.conductor_gondola_id
INNER JOIN gon_conductor con ON con.id_conductor=cg.conductor_id 
INNER JOIN gon_gondolas gon ON gon.id_gondola=cg.gondola_id 
INNER JOIN sys_persona per ON per.id_persona=con.persona_id 
INNER JOIN sys_users eser ON eser.persona_id =per.id_persona
GROUP BY tut.id_ruta ')->fetch();*/

$rutas = $db->query('SELECT   eser.id_user,
z.*,con.categoria,con.lentes,per.nombres,per.*,gon.nombre as nombre_gondola,gon.placa 
from gon_rutas z
INNER JOIN gon_conductor_gondola cg ON cg.id_conductor_gondola= z.conductor_gondola_id
INNER JOIN gon_conductor con ON con.id_conductor=cg.conductor_id 
INNER JOIN gon_gondolas gon ON gon.id_gondola=cg.gondola_id 
 INNER JOIN per_asignaciones asi on asi.id_asignacion = con.asignacion_id
 INNER JOIN sys_persona per on asi.persona_id = per.id_persona 
 left JOIN sys_users eser ON eser.persona_id =per.id_persona
GROUP BY z.id_ruta')->fetch();


 		echo (json_encode($rutas)); 
	}
    if($boton == 'listar_ruta'){ 
		$id_ruta = $_POST['id_ruta'];
		$rutas = $db->select('z.*')->from('gon_rutas z')->where('z.id_ruta', $id_ruta)->fetch_first();
 		echo (json_encode($rutas)); 
	}
    if($boton == 'ver_comunicado'){ 
		$id= $_POST['id'];
		$rutas = $db->select('z.*')->from('ins_comunicados z')->where('z.id_comunicado', $id)->fetch_first();
 		echo (json_encode($rutas)); 
	}
  
	if($boton == 'listar_area_ruta'){ 
		$id_ruta = $_POST['id_ruta'];
		$rutas = $db->select('z.*')//, COUNT(a.id_punto) as puntos')
					->from('gon_rutas z')
				   // ->join('gon_puntos a','z.id_ruta = a.ruta_id')
					->where('z.id_ruta', $id_ruta)
					//->group_by('id_ruta')
					->fetch_first(); 
		echo (json_encode($rutas)); 
	}

    if($boton == 'listar_puntos_estud'){ 
		require configuration . '/poligono.php';
		$id_ruta = $_POST['id_ruta'];
		// Obtiene el rutas
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
		//AREAS
		$rutas = $db->select('z.*')//, COUNT(a.id_punto) as puntos')
					->from('gon_rutas z')
				   // ->join('gon_puntos a','z.id_ruta = a.ruta_id')
					->where('z.id_ruta', $id_ruta)
					//->group_by('id_ruta')
					->fetch_first();
		$coordenadas=trim($rutas['coordenadas'],'*');
		//poligono
		$polygon = explode('*',$coordenadas);
		foreach ($polygon as $nro => $poly) {
			$aux = explode(',',$poly);
			$aux2 = (round($aux[0],6)-0.000044).','.(round($aux[1],6)+0.00003);
			//var_dump($aux2);exit();
			$polygon[$nro] = str_replace(',', ' ', $aux2);
		}
		$polygon[0] = str_replace(',', ' ', $polygon[$nro]);
		$pointLocation = new pointLocation();
		//;:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
		// ARMAR PUNTOS
		$puntos = $db->query(' SELECT 
		  (select count(ins.id_inscripcion) from ins_inscripcion ins  where pun.id_punto = ins.punto_id)AS asig_count
		  ,pun.*
		  FROM gon_puntos pun')->fetch();
		//$puntos = $db->select('*')->from('gon_puntos')->fetch();
		$t_clientes = '';$nombres = '';$total = 0;

		//var_dump($puntos);exit();
		$puntosDentro=array();
		foreach($puntos as $row){
			//$aux2 = explode(',',$cliente['ubicacion']);
			$aux3 = $row['latitud'];//$aux2[0] + 0.00005;
			$aux4 = $row['longitud'];//$aux2[1] - 0.00003;
			$point = $aux3.' '.$aux4;
			$punto = $pointLocation->pointInPolygon($point, $polygon);
			if($punto == 'dentro'){
				//$coordenad = $t_clientes.'*'.$aux3.','.$aux4;
				//$coordenad = $coordenad.'*'.$punto1['latitud'].','.$punto1['longitud'];
				array_push($puntosDentro,$row);
				//$t_clientes = $t_clientes.'*'.$aux3.'||'.$aux4.'||'.$row['nombre_lugar'].'||'.$row['id_punto'].'||'.$row['asig_count'];
				//$nombres = $nombres.'*'.$row['nombre_lugar'];
				$total = $total + 1;
			}
		}


		//::::::::::::::::::::::::::::
		// ARMAR area
		//$id_ruta = 2;//clear($_POST['id_ruta']);
			////$puntos = $db->query('SELECT pun.*
			//	 FROM gon_puntos pun
			//	 INNER JOIN gon_rutas rut ON rut.id_ruta = pun.ruta_id
			//	 WHERE pun.ruta_id='.$id_ruta)->fetch();
			//$id_ruta
			$est = $db->query("SELECT est.id_estudiante,per.nombres,per.primer_apellido,per.segundo_apellido,per.genero,ins.punto_id
			,est.codigo_credencial ,ifnull(asi.gondola_id,0)AS gondola_id,ifnull(asi.conductor_id,0)AS conductor_id,ifnull(asi.json_asistencia,'')AS json_asistencia
			FROM ins_inscripcion ins
				INNER JOIN ins_estudiante est ON est.id_estudiante=ins.estudiante_id
				INNER JOIN sys_persona per ON per.id_persona=est.persona_id
			  left JOIN gon_asistencia_estudiante asi ON asi.estudiante_id=est.id_estudiante 
				WHERE  ins.gestion_id=$id_gestion AND ins.estado='A'
				")->fetch();//  AND  asi.json_asistencia LIKE '%2021-04-21%'
			
			
			//$t_clientes = '';$nombres = '';$total = 0;
			 
			 $puntosRe=array();
			foreach ($puntosDentro as $i => $row1){
			//foreach ($puntos as $i => $row1){
				$id_punto=$row1['id_punto'];
				
				$estudiantes=array();
				
				foreach ($est as $i => $row2) { 
					//ver si esta dentro del poligono
					if($id_punto==$row2['punto_id']){
						if($row2['json_asistencia']!=''){
							$asistenciastr=$row2['json_asistencia'];
			 				//$jsonDesc=json_decode($asistenciastr,true);
							$row2['json_asistencia']=json_decode($asistenciastr,true);
							//var_dump($row2);exit();
						}
						 array_push($estudiantes,$row2);
					}
				}
				
				$row1['estudiantes']=$estudiantes;
				  array_push($puntosRe,$row1);
			}
			//$puntosRe;
//:::::::::::::::::::::::::::::::::::::::::::::::
 		echo (json_encode($puntosRe)); 
	}

	if($boton == "listar_comunicados"){
          $id_user = $_POST['id_user'];//1057
		    $id_ruta = $_POST['id_ruta'];//9
    $sql = "SELECT     su.username, z.* 
    from ins_comunicados z     
	 LEFT JOIN sys_users su ON su.id_user=z.usuario_registro 
	 
    where z.estado='A'
	 AND z.usuario_registro='$id_user'
	 AND z.asignacion_docente_id='$id_ruta'
	 " ;// order by z.id_comunicado  desc
	//var_dump($sql);exit();
  
    $comunicados=$db->query($sql)->fetch();
    /*
	$com = array(); 
    $cadena_usuarios = ""; 
    //armando el nuevo array con los roles respectivos
    foreach ($comunicados as $key => $comunicado) {
      $cadena_limpia = '';
      //captura los roles del comunicado
      $id = trim($comunicado['usuarios'],',');
     
      //$persona_id = $comunicado['persona_id'];
      if($id==''||$id==','){
          $cadena_limpia='Sin usuarios';
      }else{
          //$usuarios = explode(',', $id);//conveirto en array []
          //$contador = count($usuarios); //cuenta la cantidad de usuarios /
          

          for($i = 0; $i < $contador; $i++){
              
            $rol = $db->query("SELECT id_rol, rol FROM sys_roles WHERE id_rol = $usuarios[$i]")->fetch_first();
            $cadena_usuarios = $cadena_usuarios . "," . $rol['rol']; //contatena el nombre de los roles
          }
          $cadena_limpia =  '';//trim($cadena_usuarios, ','); //quita la primera coma
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
    }*/
    echo json_encode($comunicados);//$com);
  }
 
 

 