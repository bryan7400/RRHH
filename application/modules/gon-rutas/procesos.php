    <?php
    
$id_gestion = $_gestion['id_gestion'];
    $boton = $_POST['proceso'];
    if($boton == "listar_estudiantes"){
		$sql="SELECT ins.id_inscripcion,est.id_estudiante, per.nombres,per.primer_apellido,per.segundo_apellido,per.genero,ins.punto_id FROM ins_inscripcion ins
		INNER JOIN ins_estudiante est ON est.id_estudiante=ins.estudiante_id
		INNER JOIN sys_persona per ON per.id_persona=est.persona_id
		WHERE  ins.gestion_id=$id_gestion AND ins.estado='A'";
		//var_dump($sql);exit();
        $roles = $db->query($sql)->fetch();
        echo json_encode($roles);
    }

 if($boton == "asignar_estudiante"){
	 //var_dump($_POST);exit();
	     $id_punto = $_POST['id_punto'];
	     $tipo = $_POST['tipo'];
	     
	 
	 if (isset($_POST['tipo']) && isset($_POST['id_punto'])) {
            // Obtiene los datos
            if($tipo=='remove'){
				$id_inscripcion = $_POST['radioEstudaintes'];
				$db->where('id_inscripcion',$id_inscripcion)->update('ins_inscripcion', array('punto_id' => 0));
				echo  json_encode(array('estado' => 'c'));
			}else  if($tipo=='add'){
				$id_inscripcion = $_POST['selEstudaintes']; 

                $db->where('id_inscripcion',$id_inscripcion)->update('ins_inscripcion', array('punto_id' => $id_punto)); 
                echo  json_encode(array('estado' => 's'));
			}
        } else {
            // Error 400
            require_once bad_request();
	 }
    }

