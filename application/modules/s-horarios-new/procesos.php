<?php

//var_dump($_POST);die;
//require_once libraries . '/phpexcel-2.1/controlador.php';



$boton = $_POST['boton'];
// Obtiene el id de la gestion actual
$id_gestion = $_gestion['id_gestion'];


if ($boton == "listar_turno") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;
    if (isset($_POST['id_estudiante'])) {
        $id_estudiante = $_POST['id_estudiante'];
    } else {
        $id_estudiante = "";
    }
    //$familiar = $db->select('z.*')->from('vista_estudiante_familiar z')->where('id_estudiante', $id_estudiante)->order_by('z.id_estudiante_familiar', 'asc')->fetch();
    $familiar = $db->query("SELECT * FROM ins_turno WHERE `estado`='A' AND `gestion_id`=$id_gestion ORDER BY hora_inicio ASC  ")->fetch();
    echo json_encode($familiar);// order by nombre_aula asc
}
if ($boton == "listar_paralelos") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;
    if (isset($_POST['id_estudiante'])) {
        $id_estudiante = $_POST['id_estudiante'];
    } else {
        $id_estudiante = "";
    }
    //$familiar = $db->select('z.*')->from('vista_estudiante_familiar z')->where('id_estudiante', $id_estudiante)->order_by('z.id_estudiante_familiar', 'asc')->fetch();
    $familiar = $db->query("SELECT * FROM ins_paralelo  order by nombre_paralelo asc ")->fetch();
    echo json_encode($familiar);// order by nombre_aula asc
}
if ($boton == "listar_nivel") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;
    if (isset($_POST['turno'])) {
        //$turno = $_POST['turno'];
        // $familiar = $db->query("SELECT * FROM ins_nivel_academico    order by nombre_nivel asc")->fetch();
        /*$familiar = $db->query("SELECT ins_nivel_academico.id_nivel_academico AS id_nivel_academico,ins_nivel_academico.nombre_nivel AS nombre_nivel
        
        FROM ins_aula_paralelo,ins_aula,ins_nivel_academico
        WHERE  ins_aula_paralelo.aula_id=ins_aula.id_aula
            AND ins_aula.nivel_academico_id=ins_nivel_academico.id_nivel_academico
            AND ins_aula_paralelo.turno_id=$turno
        GROUP BY nombre_nivel 
        ORDER BY nombre_nivel ASC")->fetch();*/
        
        
        //NIVEL ins_aula id_aula, nivel_academico_id
        
        
    } else {
        $turno = "";
    }
    $familiar = $db->query("SELECT * FROM ins_nivel_academico    order by nombre_nivel asc")->fetch();
    //$familiar = $db->select('z.*')->from('vista_estudiante_familiar z')->where('id_estudiante', $id_estudiante)->order_by('z.id_estudiante_familiar', 'asc')->fetch();
    echo json_encode($familiar);// order by nombre_aula asc
}

if ($boton == "listar_aulas") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;
    if (isset($_POST['id_estudiante'])) {
        $id_estudiante = $_POST['id_estudiante'];
         
    } else {
        $id_estudiante = "";
    }
    
     if (isset($_POST['nivel'])) {//||$_POST['nivel']!=''||$_POST['nivel']!=0
        $nivel = $_POST['nivel'];
         
    } else {
        $nivel = "";
        
    }
    
    if($nivel==''||$nivel==0){
          $familiar = $db->query("SELECT ins_aula.id_aula,ins_aula.nombre_aula as nombre_aula,ins_nivel_academico.nombre_nivel as nombre_nivel FROM ins_aula,ins_nivel_academico 
    where ins_aula.nivel_academico_id=ins_nivel_academico.id_nivel_academico 
  
    
    order by ins_aula.nivel_academico_id asc,nombre_aula asc")->fetch();
         }else{
              
         $familiar = $db->query("SELECT ins_aula.id_aula,ins_aula.nombre_aula as nombre_aula,ins_nivel_academico.nombre_nivel as nombre_nivel FROM ins_aula,ins_nivel_academico 
    where ins_aula.nivel_academico_id=ins_nivel_academico.id_nivel_academico 
    
    and ins_aula.nivel_academico_id=$nivel
    
    order by ins_aula.nivel_academico_id asc,nombre_aula asc")->fetch();
             
         }
 
    echo json_encode($familiar);// order by nombre_aula asc
} 

//listar horarios
if ($boton == "listar_horarios") {
  
  /*  if (isset($_POST['aula'])){ 
        $aula = $_POST['aula']; 
    } else {
        $aula = 0;
        echo 'esto es un error';
    }  
    $turno=isset($_POST['turno'])?$_POST['turno']:0; 
    
    $sql="";
    if($turno){
        $sql.=" and b.turno_id=$turno"; 
    } 
    if($aula){
        $sql.=" and  b.aula_id=$aula";
    }*/
   
    $familiar = $db->query("SELECT *, (SELECT COUNT(iaam.materia_id) FROM `ins_horario_profesor_materia` hpm 
INNER JOIN int_aula_paralelo_asignacion_materia iaam ON iaam.`id_aula_paralelo_asignacion_materia`=hpm.`aula_paralelo_asignacion_materia_id` 
WHERE hpm.horario_dia_id= ho.`id_horario_dia` AND hpm.`estado`='A' AND hpm.`gestion_id`=$id_gestion  AND
iaam.`estado`='A' AND iaam.`gestion_id`=$id_gestion )AS materias_cant 

FROM ins_horario_dia ho,ins_turno WHERE ho.estado='A'
AND ho.turno_id=ins_turno.id_turno AND ins_turno.`gestion_id`=$id_gestion AND ins_turno.`estado`='A'
ORDER BY ho.turno_id ASC,ho.hora_ini ASC")->fetch();   
/*12/03/2020 SELECT  *,
    (SELECT COUNT(materia_id) FROM `ins_horario_profesor_materia` hpm WHERE hpm.horario_dia_id= ins_horario_dia.`id_horario_dia` )AS materias_cant
    
  FROM ins_horario_dia,ins_turno
  WHERE  ins_horario_dia.estado='A' AND
    ins_horario_dia.turno_id=ins_turno.id_turno ORDER BY ins_horario_dia.turno_id ASC,ins_horario_dia.hora_ini ASC*/ 
 
    echo json_encode($familiar);
    
 
}
if ($boton == "listar_paralelos_T") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;
    if (isset($_POST['aula'])){//&&isset($_POST['turno'])&&isset($_POST['nivel'])) {
        $aula = $_POST['aula'];
        //$turno = $_POST['turno'];
        //$nivel = $_POST['nivel'];
    } else {
        $aula = 0;
        echo 'esto es un error';
    } 
    
    $turno=isset($_POST['turno'])?$_POST['turno']:0;//turno
   // $todo=isset($_POST['todo'])?$_POST['todo']:false;//turno
    
    $sql="";
    if($turno){
        $sql.=" and b.turno_id=$turno";
       // $sql.=" and ins_aula_paralelo.turno_id=$turno";
    } 
    if($aula){
        $sql.=" and  b.aula_id=$aula";
    }
   
   /* $familiar = $db->query("SELECT 
         
        h.nombres,
        h.primer_apellido,
        i.nombre_materia,
        f.id_profesor_materia
       
    FROM pro_profesor_materia f,
    pro_profesor g,
    sys_persona h,
     pro_materia i
    WHERE
    f.profesor_id= g.id_profesor AND
    f.materia_id= i.id_materia AND
    g.`persona_id`=h.`id_persona`".$sql)->fetch();*/
    
    /*nombre_turno
nombre_aula
nombre_paralelo
descripcion*/
    
    /*   $familiar = $db->query("SELECT  
        ins_turno.nombre_turno,
        c.nombre_aula,
        e.nombre_paralelo,
        d.descripcion,
        h.nombres,
        h.primer_apellido,
        i.nombre_materia,
        Z.profesor_materia_id,
        Z.id_horario_profesor_materia,
        Z.curso_paralelo_id
    FROM ins_horario_profesor_materia z,ins_aula_paralelo b ,ins_aula c,ins_nivel_academico d,ins_paralelo e,ins_turno,pro_profesor_materia f,
    pro_profesor g,
    sys_persona h,
     pro_materia i
    WHERE
    
    ins_turno.id_turno=b.turno_id AND
    b.aula_id=c.id_aula AND
    c.nivel_academico_id= d.id_nivel_academico AND
    z.profesor_materia_id=f.id_profesor_materia AND
    f.profesor_id= g.id_profesor AND
    g.persona_id=h.id_persona AND
    
    b.estado='A' AND
    b.paralelo_id= e.id_paralelo ".$sql)->fetch();*/   $familiar = $db->query("SELECT ins_turno.nombre_turno,b.id_aula_paralelo,c.nombre_aula,e.nombre_paralelo,d.nombre_nivel FROM ins_aula_paralelo b ,ins_aula c,ins_nivel_academico d,ins_paralelo e,ins_turno
    WHERE
    
    ins_turno.id_turno=b.turno_id AND
    b.aula_id=c.id_aula AND
    c.nivel_academico_id= d.id_nivel_academico AND
    b.estado='A' and
    b.paralelo_id= e.id_paralelo ".$sql)->fetch();
    
     
    echo json_encode($familiar);
   
}

if ($boton == "crear_curso_paralelo") {
   if (isset($_POST['aula'])&&isset($_POST['paralelo'])&&isset($_POST['Iparacidad'])) {
        $turno = $_POST['turno'];
        $aula = $_POST['aula'];
        $paralelo = $_POST['paralelo'];
        $Iparacidad = $_POST['Iparacidad'];
       
        //$familiar = $db->query("INSERT INTO `ins_aula_paralelo` (`id_aula_paralelo`, `aula_id`, `paralelo_id`, `capacidad`, `estado`, `usuario_registro`, `fecha_registro`, `usuario_modificacion`, `fecha_modificacion`) VALUES (NULL, '$aula', '$paralelo','$Iparacidad', 'A', '1', NOW(), '', NOW());")->fetch();
       //ver si no existe
       
       $sql = "SELECT * FROM ins_aula_paralelo WHERE aula_id='$aula' and paralelo_id='$paralelo'";
       $versiexiste=$db->query($sql);
       
     
       // $sql = "INSERT INTO ins_aula_paralelo (id_aula_paralelo, aula_id, paralelo_id, capacidad, estado, usuario_registro, fecha_registro, usuario_modificacion, fecha_modificacion) VALUES (NULL, '$aula', '$paralelo','$Iparacidad', 'A', '1', NOW(), '', NOW())";
       //$dato=$db->query($sql);
       
  if($versiexiste){
           
       $data = array(
    'id_aula_paralelo' => NULL,
    'aula_id' => $aula, 
    'paralelo_id' => $paralelo,
    'capacidad' => $Iparacidad,
    'estado' => 'A',
    'usuario_registro' =>$_user['id_user'],
    'fecha_registro' => 'NOW()',
    'usuario_modificacion' => '',
    'fecha_modificacion' => 'NOW()',
    'turno_id' => $turno
     );
    $dato = $db->insert('ins_aula_paralelo', $data) ;
}
       
       
       
       if ($dato) {
            echo 1;//$dato;//"Se guardo con exito22";
        } else {
            echo "Error: ".$sql . "<br>" . $db->error;
        }
       
       
       
       
    } else {
        
        echo 'No se envio los datos correctante';
    }
    
 
}

/*

if ($boton == "listar_asignacion_docente_materia") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;
    if (isset($_POST['id_estudiante'])) {
        $id_estudiante = $_POST['id_estudiante'];
    } else {
        $id_estudiante = "";
    } 
    $familiar = $db->query("SELECT f.id_profesor_materia,h.nombres,h.primer_apellido,i.nombre_materia FROM 
    pro_profesor_materia f,pro_profesor g,sys_persona h,pro_materia i
    WHERE
    f.profesor_id= g.id_profesor AND
    g.persona_id=h.id_persona AND
    f.materia_id=i.id_materia  order by h.nombres asc ")->fetch();
    echo json_encode($familiar);// order by nombre_aula asc
}
*/

?>
