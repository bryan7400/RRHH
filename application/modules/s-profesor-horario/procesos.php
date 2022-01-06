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
    $familiar = $db->query("SELECT * FROM ins_turno  order by id_turno asc ")->fetch();
    echo json_encode($familiar);// order by nombre_aula asc
}
/*if ($boton == "listar_paralelos") {
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
}*/
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

//LISTADO PRINCIPAL paralelos con oopciones de ver y nuevo
if ($boton == "listar_paralelos_T") {
    //Obtiene HORARI EN VISTA HTML
 
    
    $turno=isset($_POST['turno'])?$_POST['turno']:0;
    $nivel=isset($_POST['nivel'])?$_POST['nivel']:0;
    $aula=isset($_POST['aula'])?$_POST['aula']:0;
    
    $sql="";
    if($turno){
        $sql.=" and b.turno_id=$turno";
       // $sql.=" and ins_aula_paralelo.turno_id=$turno";
    } 
    if($aula){
        $sql.=" and  b.aula_id=$aula";
    } 
    if($nivel){
        $sql.=" and  d.id_nivel_academico=$nivel";
    }
   
    $familiar = $db->query("SELECT ins_turno.nombre_turno,ins_turno.id_turno,b.id_aula_paralelo,c.nombre_aula,e.nombre_paralelo,
    d.*, 
     (SELECT COUNT(z.id_horario_profesor_materia)AS nmaterias 
    FROM ins_horario_profesor_materia z,int_aula_paralelo_asignacion_materia apar 
    WHERE z.aula_paralelo_asignacion_materia_id=apar.id_aula_paralelo_asignacion_materia 
    AND apar.aula_paralelo_id=b.id_aula_paralelo  AND
        z.estado='A' and  z.gestion_id=$id_gestion)AS inscritos
   
    FROM ins_aula_paralelo b ,ins_aula c,ins_nivel_academico d,ins_paralelo e,ins_turno
 
    WHERE
    ins_turno.id_turno=b.turno_id AND
    b.aula_id=c.id_aula AND
    c.nivel_academico_id= d.id_nivel_academico AND
    b.estado='A' AND
    b.paralelo_id= e.id_paralelo 
    AND c.gestion_id=$id_gestion
    AND d.gestion_id=$id_gestion
    AND ins_turno.gestion_id=$id_gestion
    
    
    ".$sql)->fetch(); 
    
    //$familiar = $db->query("SELECT ins_turno.nombre_turno,b.id_aula_paralelo,c.nombre_aula,e.nombre_paralelo,d.nombre_nivel FROM ins_aula_paralelo b ,ins_aula c,ins_nivel_academico d,ins_paralelo e,ins_turno  WHERE ins_turno.id_turno=b.turno_id AND  b.aula_id=c.id_aula AND  c.nivel_academico_id= d.id_nivel_academico AND   b.estado='A' and   b.paralelo_id= e.id_paralelo ".$sql)->fetch();
    
     
    echo json_encode($familiar);
   
}



 
if ($boton == "listar_paralelos_val_ap") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;
    if (isset($_POST['aula'])) {
        if($_POST['aula']!='' || $_POST['aula']!=0)
        $sql ="WHERE ap.aula_id=".$_POST['aula'];
        else
         $sql = "";   
    } else {
        $sql = "";
    }
    //$familiar = $db->select('z.*')->from('vista_estudiante_familiar z')->where('id_estudiante', $id_estudiante)->order_by('z.id_estudiante_familiar', 'asc')->fetch();
    $familiar = $db->query("SELECT p.*,ap.* FROM ins_inscripcion i
 INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo=i.aula_paralelo_id
  INNER JOIN ins_paralelo p ON p.id_paralelo=ap.paralelo_id  
  INNER JOIN ins_aula au ON au.id_aula=ap.aula_id 
  ".$sql."
   GROUP BY p.nombre_paralelo")->fetch();
    echo json_encode($familiar);// order by nombre_aula asc
}

if ($boton == "listar_paralelos_asignados") {
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
    $paralelo=isset($_POST['paralelo'])?$_POST['paralelo']:0;//turno
    $dia=isset($_POST['dia'])?$_POST['dia']:0;//turno
   // $todo=isset($_POST['todo'])?$_POST['todo']:false;//turno
    
    $sql="";
    if($turno){
        $sql.=" and b.turno_id=$turno";
       // $sql.=" and ins_aula_paralelo.turno_id=$turno";
    } 
    if($aula){
        $sql.=" and  b.aula_id=$aula";
    }
    if($paralelo){
        $sql.=" AND e.id_paralelo=$paralelo";
    }
    if($dia){
        $sql.=" AND z.dia_semana_id=$dia";
    }
   
    $familiar = $db->query("
SELECT  j.hora_ini,
        j.hora_fin, 
        ins_turno.nombre_turno,
        c.nombre_aula,
        e.nombre_paralelo,
        d.nombre_nivel,
        h.nombres,
        h.primer_apellido,
        i.nombre_materia,
        z.profesor_materia_id,
        z.id_horario_profesor_materia,
        z.curso_paralelo_id,
        s.nombre_dia,
        e.id_paralelo,
        g.codigo_profesor,
        z.horario_dia_id
  FROM ins_horario_profesor_materia z,
  
	ins_aula_paralelo b,
	ins_aula c,
	ins_nivel_academico d,
	ins_paralelo e,
	pro_profesor_materia f,
    pro_profesor g,
    sys_persona h,
    pro_materia i,
    ins_turno,
    ins_dia_semana s,
    ins_horario_dia j
WHERE z.curso_paralelo_id=b.id_aula_paralelo AND 
	ins_turno.id_turno=b.turno_id AND
	b.aula_id=c.id_aula AND 
	c.nivel_academico_id= d.id_nivel_academico AND
    b.paralelo_id= e.id_paralelo AND 
        
    z.profesor_materia_id=f.id_profesor_materia AND
    f.profesor_id= g.id_profesor AND
    g.persona_id=h.id_persona AND
    f.materia_id=i.id_materia and
    
    z.dia_semana_id=s.id_dia_semana AND  z.horario_dia_id=j.id_horario_dia AND   
     z.estado='A' ".$sql)->fetch();
 
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
    'usuario_registro' => '1',
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


if ($boton == "listar_asignacion_docente_materia") {
    //agregar una materia id=0 en 
/*    $familiar = $db->query("SELECT * FROM pro_profesor WHERE codigo_profesor='P-0'")->fetch();
     if (!$familiar) {
       $persona = array(  
                    'id_persona' => 0, 
                    'nombres' => 'vacantes'        
        );
 
       $id_persona = $db->insert('sys_persona', $persona);
         
       $aula = array(  
                    'id_profesor' => 0, 
                    'codigo_profesor' => 'P-0',
                    'persona_id' => $id_persona  
        );
 
       $id_aula = $db->insert('pro_profesor', $aula);
     }*/
    
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


//no funciona?
/*if ($boton == "listar_horario") {
   // $horario = $db->query("SELECT * FROM 
    //ins_horario_dia WHERE  estado='A'  ORDER BY turno_id ASC,hora_ini ASC")->fetch();
    //echo json_encode($horario);// order by nombre_aula asc
}*/
//---------------------------------------
//horarios de periodos
if ($boton == "listar_horarios") {
   
    //Obtiene los estudiantes 
    //$aula=isset($_POST['aula'])?$_POST['aula']:0;//turno
    //$turno=isset($_POST['turno'])?$_POST['turno']:0;//turno
    //$nivel=isset($_POST['nivel'])?$_POST['nivel']:0;//turno
    //$paralelo=isset($_POST['paralelo'])?$_POST['paralelo']:0;//turno
    
    //$sql=""; 
    //if($turno){
    //    $sql.=" and turno_id=$turno"; 
    //}
  
 //horarios exitentes
// $consulta="
 
$consulta="SELECT * FROM ins_horario_dia hd,ins_turno tu WHERE hd.estado='A' AND 
 hd.turno_id=tu.id_turno  ORDER BY hora_ini ASC"; 
//$consulta=" SELECT  * FROM ins_horario_dia hd,ins_turno tu where hd.estado='A' AND  hd.turno_id=tu.id_turno  ORDER BY hora_ini ASC";//".$sql." ORDER BY p.primer_apellido ASC
    $inscritos = $db->query($consulta)->fetch();
    echo json_encode($inscritos); 
 
} 
//horario tabla

if ($boton == "listar_cursos_horario") {
    
   $aula=isset($_POST['aula'])?$_POST['aula']:0;//turno
    $turno=isset($_POST['turno'])?$_POST['turno']:0;//turno
    $nivel=isset($_POST['nivel'])?$_POST['nivel']:0;//turno
    $paralelo=isset($_POST['paralelo'])?$_POST['paralelo']:0;//paralelo
   $hora_id=isset($_POST['hora_inicio'])?$_POST['hora_inicio']:0;//turno
    //filtrar en este horario:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    $sql="";
    if($paralelo){
        $sql.=" and  b.paralelo_id=$paralelo";
    }
    if($aula){
        $sql.=" and  z.curso_paralelo_id=$aula";
    }
  /*  if($paralelo){
        $sql.=" and  e.id_paralelo=$paralelo";
    }
   */
    //$hora_inicio = $_POST['hora_inicio'];
    //$hora_fin = $_POST['hora_fin'];
  
    //horarios exitentes
    $consulta="
SELECT  k.hora_ini,
        k.hora_fin,
        ins_turno.nombre_turno,
        c.nombre_aula,
        e.nombre_paralelo,
        d.nombre_nivel,
        (SELECT GROUP_CONCAT( CONCAT(sp.nombres ,' ', sp.primer_apellido,' ', sp.segundo_apellido) SEPARATOR ' | ') AS nombres_completo  
        FROM pro_profesor pp,sys_persona sp
        WHERE pp.persona_id=sp.id_persona AND
	pp.id_profesor=z.profesor_id
	)AS nombre_completo ,
        (SELECT pp.codigo_profesor  
        FROM pro_profesor pp 
        WHERE  
	pp.id_profesor=z.profesor_id
	)AS codigo_profesor ,
        i.nombre_materia AS nombre_materia,
        
        z.id_horario_profesor_materia,
        z.curso_paralelo_id,
        z.dia_semana_id AS dia_semana_id, 
        z.curso_paralelo_id,
        z.horario_dia_id,
        z.profesor_id,
        z.materia_id
  FROM ins_horario_profesor_materia z,
  
	ins_aula_paralelo b,
	ins_aula c,
	ins_nivel_academico d,
	ins_paralelo e, 
    pro_materia i,
    ins_turno,
    ins_horario_dia k
WHERE z.curso_paralelo_id=b.id_aula_paralelo AND 
	ins_turno.id_turno=b.turno_id AND
	b.aula_id=c.id_aula AND 
	c.nivel_academico_id= d.id_nivel_academico AND
    b.paralelo_id= e.id_paralelo AND 
        
     
    z.horario_dia_id=k.id_horario_dia AND
    z.materia_id=i.id_materia   AND z.estado='A' and
    z.horario_dia_id='$hora_id'   AND
   z.gestion_id=$id_gestion
    ".$sql." ORDER BY k.hora_ini asc";
    $inscritos = $db->query($consulta)->fetch();   
    //imprime horarios sin los docentes de 1d 0 
   /* $consulta="
SELECT  k.hora_ini,
        k.hora_fin,
        ins_turno.nombre_turno,
        c.nombre_aula,
        e.nombre_paralelo,
        d.nombre_nivel,
        h.nombres,
        h.primer_apellido,
        i.nombre_materia AS nombre_materia,
        
        z.id_horario_profesor_materia,
        z.curso_paralelo_id,
        z.dia_semana_id AS dia_semana_id,
        g.codigo_profesor,
        z.curso_paralelo_id,
        z.horario_dia_id,
        z.profesor_id,
        z.materia_id
  FROM ins_horario_profesor_materia z,
  
	ins_aula_paralelo b,
	ins_aula c,
	ins_nivel_academico d,
	ins_paralelo e,
	
	 
    pro_profesor g,
    sys_persona h,
    pro_materia i,
    ins_turno,
    ins_horario_dia k
WHERE z.curso_paralelo_id=b.id_aula_paralelo AND 
	ins_turno.id_turno=b.turno_id AND
	b.aula_id=c.id_aula AND 
	c.nivel_academico_id= d.id_nivel_academico AND
    b.paralelo_id= e.id_paralelo AND 
        
    
    z.profesor_id= g.id_profesor AND
    g.persona_id=h.id_persona AND
    z.horario_dia_id=k.id_horario_dia AND
    z.materia_id=i.id_materia   and z.estado='A' and
    z.horario_dia_id='$hora_id'   AND
   z.gestion_id=$id_gestion
    ".$sql." ORDER BY k.hora_ini asc";
    $inscritos = $db->query($consulta)->fetch();*/
     
    // echo $hora_inicio;  
     echo json_encode($inscritos); 
 
}
//lista horas para tabla de horario visual
if ($boton == "listar_horarios_new") {
   
    $aula=isset($_POST['aula'])?$_POST['aula']:0;//turno
    $turno=isset($_POST['turno'])?$_POST['turno']:0;//turno
    //$nivel=isset($_POST['nivel'])?$_POST['nivel']:0;//turno
    $paralelo=isset($_POST['paralelo'])?$_POST['paralelo']:0;//paralelo
   //$hora_id=isset($_POST['hora_inicio'])?$_POST['hora_inicio']:0;//turno 
    
    $sql="";
    /*if($paralelo){
        $sql.=" and  b.paralelo_id=$paralelo";
    }*/
    /*if($aula){
        $sql.=" and  z.curso_paralelo_id=$aula";
    }*/
    /*if($turno){
        $sql.=" and  z.curso_paralelo_id=$turno";
    }  */
    $consultaho="
        SELECT hd.* 
          FROM  
            ins_horario_dia hd
         WHERE hd.estado='A' AND hd.turno_id=".$turno."
         ORDER BY hd.hora_ini
         ";
/*    $consultaho="
SELECT hd.*,z.*,m.`nombre_materia`,hd.`complemento`,per.`nombres`
  FROM ins_horario_profesor_materia z,
  
	ins_aula_paralelo b,
	ins_aula c,
	ins_nivel_academico d,
	ins_paralelo e,
	ins_turno,
	`pro_materia` m,
	`ins_horario_dia` hd,
	`pro_profesor` pro,
	`sys_persona` per
WHERE z.curso_paralelo_id=b.id_aula_paralelo AND 
	ins_turno.id_turno=b.turno_id AND
	b.aula_id=c.id_aula AND
	c.nivel_academico_id= d.id_nivel_academico AND
	b.paralelo_id= e.id_paralelo   AND
	z.`materia_id`=m.`id_materia` AND
	z.`horario_dia_id`=hd.`id_horario_dia` AND
	z.`profesor_id`=pro.`id_profesor` AND
	pro.`persona_id`=per.`id_persona` AND
	
	z.estado='A'  	
	GROUP BY z.`horario_dia_id`
	ORDER BY z.`horario_dia_id` 
 "; */   
 
    $horar = $db->query($consultaho)->fetch();   
 
    echo json_encode($horar); 
 
} 
//datos de horario de un curso
if ($boton == "listar_cursos_horario_new") {
    
   $aula=isset($_POST['aula'])?$_POST['aula']:0;//turno
    $turno=isset($_POST['turno'])?$_POST['turno']:0;//turno
    $nivel=isset($_POST['nivel'])?$_POST['nivel']:0;//turno
    $paralelo=isset($_POST['paralelo'])?$_POST['paralelo']:0;//paralelo
   $hora_id=isset($_POST['hora_inicio'])?$_POST['hora_inicio']:0;//turno
    //filtrar en este horario:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    $sql="";
    if($paralelo){
        $sql.=" and  b.paralelo_id=$paralelo";
    }
    if($aula){
        $sql.=" and  apam.aula_paralelo_id=$aula";
    }
 
    

$consulta="
SELECT d.*, hd.*, z.*, m.*,hd.`complemento`,apam.*,

    (SELECT GROUP_CONCAT(CONCAT(per.nombres ,' ', per.primer_apellido,' ', per.segundo_apellido)
	SEPARATOR ' | ')AS nombres_completo  
    FROM  per_asignaciones asig,sys_persona  per
	WHERE 
	asig.persona_id=per.id_persona AND
	apam.asignacion_id=asig.id_asignacion 
	)AS nombres_doc,
	
    (SELECT GROUP_CONCAT(CONCAT(SUBSTRING(per.nombres, 1, 1),' ', SUBSTRING(per.primer_apellido, 1, 1),' ', SUBSTRING(per.segundo_apellido, 1, 1))
	SEPARATOR ' | ')AS nombres_completo  
    FROM  per_asignaciones asig,sys_persona  per
	WHERE 
	asig.persona_id=per.id_persona AND
	apam.asignacion_id=asig.id_asignacion  
	)AS iniciales


  FROM ins_horario_profesor_materia z,
  
	ins_aula_paralelo b,
	ins_aula c,
	ins_nivel_academico d,
	ins_paralelo e,
	ins_turno tu,
	pro_materia m,
	ins_horario_dia hd,
	ins_gestion ge,
	int_aula_paralelo_asignacion_materia apam
WHERE apam.aula_paralelo_id=b.id_aula_paralelo AND 
	tu.id_turno=b.turno_id AND
	b.aula_id=c.id_aula AND
	c.nivel_academico_id= d.id_nivel_academico AND
	b.paralelo_id= e.id_paralelo   AND
	apam.`materia_id`=m.`id_materia` AND
	z.`horario_dia_id`=hd.`id_horario_dia` AND 
	apam.`id_aula_paralelo_asignacion_materia`=z.`aula_paralelo_asignacion_materia_id` AND 
	 
	z.estado='A'AND 
	b.estado='A' AND
	ge.estado='A'AND 
	c.estado='A'AND 
	e.estado_paralelo='A' AND 
	tu.estado='A'AND 
	d.estado='A'AND
	hd.estado='A'AND
	m.estado='A' AND
	apam.estado='A'
	 
     ".$sql." AND
   z.gestion_id=$id_gestion 
	ORDER BY z.`horario_dia_id` 
 ";   
    
/*$consulta="
SELECT d.*, hd.*, z.*, m.*,hd.`complemento`,

    (SELECT GROUP_CONCAT(CONCAT(per.nombres ,' ', per.primer_apellido,' ', per.segundo_apellido)
	SEPARATOR ' | ')AS nombres_completo  
    FROM  pro_profesor pro,sys_persona  per
	WHERE 
	pro.persona_id=per.id_persona AND
	z.profesor_id=pro.id_profesor 
	)AS nombres_doc,
	
    (SELECT GROUP_CONCAT(CONCAT(SUBSTRING(per.nombres, 1, 1),' ', SUBSTRING(per.primer_apellido, 1, 1),' ', SUBSTRING(per.segundo_apellido, 1, 1))
	SEPARATOR ' | ')AS nombres_completo  
    FROM  pro_profesor pro,sys_persona  per
	WHERE 
	pro.persona_id=per.id_persona AND
	z.profesor_id=pro.id_profesor 
	)AS iniciales


  FROM ins_horario_profesor_materia z,
  
	ins_aula_paralelo b,
	ins_aula c,
	ins_nivel_academico d,
	ins_paralelo e,
	ins_turno tu,
	pro_materia m,
	ins_horario_dia hd,
	ins_gestion ge
WHERE z.curso_paralelo_id=b.id_aula_paralelo AND 
	tu.id_turno=b.turno_id AND
	b.aula_id=c.id_aula AND
	c.nivel_academico_id= d.id_nivel_academico AND
	b.paralelo_id= e.id_paralelo   AND
	z.`materia_id`=m.`id_materia` AND
	z.`horario_dia_id`=hd.`id_horario_dia` AND 
	 
	z.estado='A'AND 
	b.estado='A' AND
	ge.estado='A'AND 
	c.estado='A'AND 
	e.estado_paralelo='A' AND 
	tu.estado='A'AND 
	d.estado='A'AND
	hd.estado='A'AND
	m.estado='A'
	 
     ".$sql." AND
   z.gestion_id=$id_gestion 
	ORDER BY z.`horario_dia_id` 
 ";   */
    /*$consulta="
SELECT hd.*,z.*,m.`nombre_materia`,hd.`complemento`,per.`nombres`
  FROM ins_horario_profesor_materia z,
  
	ins_aula_paralelo b,
	ins_aula c,
	ins_nivel_academico d,
	ins_paralelo e,
	ins_turno,
	`pro_materia` m,
	`ins_horario_dia` hd,
	`pro_profesor` pro,
	`sys_persona` per
WHERE z.curso_paralelo_id=b.id_aula_paralelo AND 
	ins_turno.id_turno=b.turno_id AND
	b.aula_id=c.id_aula AND
	c.nivel_academico_id= d.id_nivel_academico AND
	b.paralelo_id= e.id_paralelo   AND
	z.`materia_id`=m.`id_materia` AND
	z.`horario_dia_id`=hd.`id_horario_dia` AND
	z.`profesor_id`=pro.`id_profesor` AND
	pro.`persona_id`=per.`id_persona` AND
	
	z.estado='A' ".$sql."
	ORDER BY z.`horario_dia_id` 
 ";*/
      $inscritos = $db->query($consulta)->fetch();
        
   // }
    ////,(SELECT ma.nombre_materia FROM `pro_materia` ma WHERE ma.id_materia=z.`materia_id` )AS lunes  
    //imprime horarios sin los docentes de 1d 0 
   
     
    // echo $hora_inicio;  
     echo json_encode($inscritos); 
 
}
//HORARIO HTML DOCENTES
if ($boton == "listar_docente_horario") {
    
   //$aula=isset($_POST['aula'])?$_POST['aula']:0;//turno
    //$turno=isset($_POST['turno'])?$_POST['turno']:0;//turno
    //$nivel=isset($_POST['nivel'])?$_POST['nivel']:0;//turno
    //$paralelo=isset($_POST['paralelo'])?$_POST['paralelo']:0;//paralelo
   $hora_id=isset($_POST['hora_inicio'])?$_POST['hora_inicio']:0;//turno
    
   $id_docente=isset($_POST['id_docente'])?$_POST['id_docente']:0;//turno
    //filtrar en este horario:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    $sql="";
   /* if($paralelo){
        $sql.=" and  b.paralelo_id=$paralelo";
    }*/
    if($id_docente){
        $sql.=" AND apam.`asignacion_id`=$id_docente";
    }
 
    //imprime horarios sin los docentes de 1d 0 
    $consulta="

SELECT  k.hora_ini,
        k.hora_fin,
        tu.nombre_turno,
        tu.id_turno,
        c.nombre_aula,
        e.nombre_paralelo,
        d.nombre_nivel,
        h.nombres,
        h.primer_apellido,
        i.nombre_materia AS nombre_materia,  
        apam.*,
        z.*
  FROM ins_horario_profesor_materia z 
  
   INNER JOIN int_aula_paralelo_asignacion_materia apam ON z.`aula_paralelo_asignacion_materia_id`=apam.`id_aula_paralelo_asignacion_materia` 
   INNER JOIN `per_asignaciones` asi ON asi.`id_asignacion`=apam.`asignacion_id` 
	INNER JOIN ins_aula_paralelo b ON apam.`aula_paralelo_id`=b.id_aula_paralelo 
  INNER JOIN ins_aula c ON c.`id_aula`=b.`aula_id`
  INNER JOIN ins_nivel_academico d ON d.`id_nivel_academico`=c.nivel_academico_id
   INNER JOIN ins_paralelo e ON b.paralelo_id= e.id_paralelo 
	INNER JOIN ins_turno tu ON tu.id_turno=b.turno_id
	
	  INNER JOIN sys_persona h ON asi.`persona_id`=h.id_persona
    INNER JOIN pro_materia i ON apam.`materia_id`=i.id_materia 
   INNER JOIN ins_horario_dia k ON z.horario_dia_id=k.id_horario_dia 
    INNER JOIN ins_gestion ge ON z.gestion_id=ge.id_gestion 
   
WHERE   
   z.`estado`='A' AND b.estado='A' AND c.estado='A' AND e.estado_paralelo='A' 
AND d.estado='A' AND tu.estado='A' AND ge.estado='A' AND k.estado='A' 
AND i.estado='A' AND z.horario_dia_id='$hora_id'
 AND apam.gestion_id=$id_gestion  AND z.gestion_id=$id_gestion  ".$sql." ORDER BY k.hora_ini ASC";
 
    
    $inscritos = $db->query($consulta)->fetch();
     
    // echo $hora_inicio;  
     echo json_encode($inscritos); 
/*  CONSULTA ANTERIOR A CAMNIO DER ASIGNACIONES  $consulta="
SELECT  k.hora_ini,
        k.hora_fin,
        tu.nombre_turno,
        tu.id_turno,
        c.nombre_aula,
        e.nombre_paralelo,
        d.nombre_nivel,
        h.nombres,
        h.primer_apellido,
        i.nombre_materia AS nombre_materia, 
        z.id_horario_profesor_materia,
        z.curso_paralelo_id,
        z.dia_semana_id AS dia_semana_id,
        g.codigo_profesor,
        z.curso_paralelo_id,
        z.horario_dia_id,
        z.profesor_id,
        z.materia_id
  FROM ins_horario_profesor_materia z,
        ins_aula_paralelo b,
        ins_aula c,
        ins_nivel_academico d,
        ins_paralelo e,
        pro_profesor g,
        sys_persona h,
        pro_materia i,
        ins_turno tu,
        ins_horario_dia k,
        ins_gestion ge

WHERE z.curso_paralelo_id=b.id_aula_paralelo AND 
	tu.id_turno=b.turno_id AND
	b.aula_id=c.id_aula AND 
	c.nivel_academico_id= d.id_nivel_academico AND
    b.paralelo_id= e.id_paralelo AND 
        
    
    z.profesor_id= g.id_profesor AND
    g.persona_id=h.id_persona AND
    z.horario_dia_id=k.id_horario_dia AND
    z.materia_id=i.id_materia   AND   
    z.gestion_id=ge.id_gestion   AND 
    z.materia_id=i.id_materia  and
        z.estado='A' AND 
    b.estado='A' AND
    c.estado='A' AND
    e.estado_paralelo='A' AND
    d.estado='A' AND
    tu.estado='A' AND
    ge.estado='A' AND
    k.estado='A' AND
    i.estado='A' and
     
    z.horario_dia_id='$hora_id'   AND
   z.gestion_id=$id_gestion 
    ".$sql." ORDER BY k.hora_ini asc";*/
 
}
//listado para sele
if ($boton == "listar_docente") {
 //docente =id=1
$consulta="SELECT  pro.*,pe.*,ca.* FROM per_asignaciones pro INNER JOIN sys_persona pe ON pe.id_persona=pro.persona_id
 INNER JOIN per_cargos ca ON ca.id_cargo=pro.cargo_id 
 WHERE  pro.cargo_id=1 AND pro.estado='A'
 AND
 pro.`gestion_id`=$id_gestion AND
 ca.`estado`='A'
 ORDER BY pe.primer_apellido ASC";// ORDER BY p.primer_apellido ASC
//$consulta="SELECT  pro.*,pe.* FROM pro_profesor pro INNER JOIN sys_persona pe ON  pe.id_persona=pro.persona_id ORDER BY pe.primer_apellido ASC"; // ORDER BY p.primer_apellido ASC
    
    $inscritos = $db->query($consulta)->fetch();
       
    echo json_encode($inscritos); 
 
}
//listar del modal
if ($boton == "listar_materias") {
    $tipo_evaluacion=isset($_POST['tipo_evaluacion'])?$_POST['tipo_evaluacion']:0; //caulitativo
    
    $nivel=isset($_POST['nivel'])?$_POST['nivel']:0; //id
    
    
    $consulta="SELECT * FROM pro_materia mat
INNER JOIN ins_nivel_academico d ON d.`id_nivel_academico`=mat.nivel_academico_id
WHERE mat.`estado`='A' AND d.`gestion_id`=$id_gestion AND d.`estado`='A'
    ORDER BY  mat.nombre_materia ASC";
    
    $inscritos = $db->query($consulta)->fetch();
    //recorremos las materias
    $materias_nivel=array();
        foreach ($inscritos as $i => $row) {
            //el nivel sera cera0 cuando se a solicitud horario de docentes donde deve listar todas las materias
            if($nivel==0){
                array_push ( $materias_nivel , $row );
            }else{
                $niveles=$row['nivel_academico_id'];
                //recorremos sus niveles
                $nivArray=explode(',',$niveles);
                foreach ($nivArray as $row2) {
                //var_dump($nivel);exit();  
                        if($row2==$nivel){
                            //en caso de ser em nivel recibido los agregamos a nievo array

                            array_push ( $materias_nivel , $row );
                        }


                }//fin each
            }
        }
        
    //var_dump($materias_nivel);exit();   
    
       
    echo json_encode($materias_nivel); 
 
}
?>
