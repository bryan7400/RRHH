<?php
// Verifica la peticion post
if (is_post()) {

    $id_gestion     = $_gestion['id_gestion'];
    $accion         = $_POST['accion']; 
    $nombre_dominio = escape($_institution['nombre_dominio']); 
    

if($accion == "listar_cursos0"){

    //cursos asignacis    
    $cursosasigando = $db->query("SELECT (SELECT COUNT(ci.curso_asignacion_id) 
    FROM ext_curso_inscripcion ci WHERE ci.curso_asignacion_id =ca.id_curso_asignacion)as inscritos,
    per.nombres,per.primer_apellido,per.segundo_apellido,per.foto,
    cur.*, ca.*,cat.*, pre.*, pre.nombre AS nombre_pre,pre.descripcion AS desc_pre,pre.tipo AS tipo_pre ,
    ca.estado AS estadoasig , cur.estado AS estadocurso  FROM ext_curso cur  
    INNER JOIN ext_categoria cat ON cat.id_categoria=cur.categoria_id
    left JOIN ext_contenido_prerequisito pre ON pre.curso_id=cur.id_curso
    INNER JOIN  ext_curso_asignacion ca ON ca.curso_id=cur.id_curso
    INNER JOIN per_asignaciones asi ON asi.id_asignacion=ca.asignacion_id
    INNER JOIN sys_persona per ON per.id_persona=asi.persona_id
    WHERE cur.estado='A'  GROUP BY cur.id_curso")->fetch();
    // GROUP BY cur.id_curso borrar si no da
    //cursos sin asignacion
    $cursosnoasig = $db->query("SELECT (0)as inscritos,
    null as nombres,null as primer_apellido,null as segundo_apellido,null as foto,
    cat.*,cur.*, ca.*, pre.*, pre.nombre AS nombre_pre,pre.descripcion AS desc_pre,pre.tipo AS tipo_pre ,
    ca.estado AS estadoasig , cur.estado AS estadocurso  FROM ext_curso cur  
    INNER JOIN ext_categoria cat ON cat.id_categoria=cur.categoria_id
    left JOIN ext_contenido_prerequisito pre ON pre.curso_id=cur.id_curso
    LEFT JOIN  ext_curso_asignacion ca ON ca.curso_id=cur.id_curso 
    WHERE ca.id_curso_asignacion IS NULL ")->fetch();
            
    $cursos=array_merge($cursosasigando,$cursosnoasig);
            
    $arraySinAsig=array();
    $arrayAsig=array();
    foreach($cursos as $rows){
            
                
            if($rows['estadocurso']=='A'){
                
                if($rows['estadoasig']=='A' && $rows['estadoasig']!=null){
                    $arm=array(
                'inscritos'=>$rows['inscritos'],
                'nombres'=>$rows['nombres'],
                'primer_apellido'=>$rows['primer_apellido'],
                'segundo_apellido'=>$rows['segundo_apellido'],
                'foto'=>$rows['foto'],
                'id_categoria'=>$rows['id_categoria'],
                'categoria'=>$rows['categoria'],
                'descripcion_cat'=>$rows['descripcion_cat'],
                'imagen'=>$rows['imagen'],
                'id_curso'=>$rows['id_curso'],
                'categoria_id'=>$rows['categoria_id'],
                'nombre_curso'=>$rows['nombre_curso'],
                'imagen_curso'=>$rows['imagen_curso'],
                'cupo_minimo_curso'=>$rows['cupo_minimo_curso'],
                'objetivo_curso'=>$rows['objetivo_curso'],
                'descripcion_curso'=>$rows['descripcion_curso'],
                    
                'id_curso_asignacion'=>$rows['id_curso_asignacion'],
                'curso_id'=>$rows['curso_id'],
                'asignacion_id'=>$rows['asignacion_id'],
                'horario_dia'=>$rows['horario_dia'],
                'cupo'=>$rows['cupo'],
                'fecha_inicio'=>$rows['fecha_inicio'],
                'fecha_fin'=>$rows['fecha_fin'],
                'modulo'=>$rows['modulo'],
                'duracion'=>$rows['duracion'],
                'certificado'=>$rows['certificado'],
                'carga_horaria'=>$rows['carga_horaria'],
                'periodo'=>$rows['periodo'],
                'ambiente'=>$rows['ambiente'],
                'fecha_inscripcion_inicio'=>$rows['fecha_inscripcion_inicio'],
                'fecha_inscripcion_fin'=>$rows['fecha_inscripcion_fin'],
                'observaciones'=>$rows['observaciones'],
                'id_contenido_prerequisito'=>$rows['id_contenido_prerequisito'],
                'nombre'=>$rows['nombre'],
                'descripcion'=>$rows['descripcion'],
                'tipo'=>$rows['tipo'],
                'curso_id'=>$rows['curso_id'],
                'nombre_pre'=>$rows['nombre_pre'],
                'desc_pre'=>$rows['desc_pre'],
                'tipo_pre'=>$rows['tipo_pre'],
                'estadoasig'=>$rows['estadoasig'],
                'estadocurso'=>$rows['estadocurso'] 
                );
                    array_push($arrayAsig,$arm);
                    //echo 'ACTIVO :::: ASIG'.$rows['id_curso_asignacion'].'||'.$rows['ambiente'].'||'.$rows['observaciones'].'||'.$rows['estadoasig'].'-------CURSO'.$rows['id_curso'].'||'.$rows['nombre_curso'].'||'.$rows['estado'].'<br><br>';
                }else{
                    $arm=array(
                'inscritos'=>$rows['inscritos'],
                'nombres'=>$rows['nombres'],
                'primer_apellido'=>$rows['primer_apellido'],
                'segundo_apellido'=>$rows['segundo_apellido'],
                'foto'=>$rows['foto'],
                'id_categoria'=>$rows['id_categoria'],
                'categoria'=>$rows['categoria'],
                'descripcion_cat'=>$rows['descripcion_cat'],
                'imagen'=>$rows['imagen'],
                'id_curso'=>$rows['id_curso'],
                'categoria_id'=>$rows['categoria_id'],
                'nombre_curso'=>$rows['nombre_curso'],
                'imagen_curso'=>$rows['imagen_curso'],
                'cupo_minimo_curso'=>$rows['cupo_minimo_curso'],
                'objetivo_curso'=>$rows['objetivo_curso'],
                'descripcion_curso'=>$rows['descripcion_curso'],
                    
                'id_curso_asignacion'=>null,
                'curso_id'=>null,
                'asignacion_id'=>null,
                'horario_dia'=>null,
                'cupo'=>$rows['cupo'],
                'fecha_inicio'=>$rows['fecha_inicio'],
                'fecha_fin'=>$rows['fecha_fin'],
                'modulo'=>$rows['modulo'],
                'duracion'=>$rows['duracion'],
                'certificado'=>$rows['certificado'],
                'carga_horaria'=>$rows['carga_horaria'],
                'periodo'=>$rows['periodo'],
                'ambiente'=>$rows['ambiente'],
                'fecha_inscripcion_inicio'=>$rows['fecha_inscripcion_inicio'],
                'fecha_inscripcion_fin'=>$rows['fecha_inscripcion_fin'],
                'observaciones'=>$rows['observaciones'],
                'id_contenido_prerequisito'=>$rows['id_contenido_prerequisito'],
                'nombre'=>$rows['nombre'],
                'descripcion'=>$rows['descripcion'],
                'tipo'=>$rows['tipo'],
                'curso_id'=>$rows['curso_id'],
                'nombre_pre'=>$rows['nombre_pre'],
                'desc_pre'=>$rows['desc_pre'],
                'tipo_pre'=>$rows['tipo_pre'],
                'estadoasig'=>$rows['estadoasig'],
                'estadocurso'=>$rows['estadocurso'] 
                );
                    array_push($arraySinAsig,$arm);
                     //echo 'INACTIVO ::::: ASIG'.$rows['id_curso_asignacion'].'||'.$rows['ambiente'].'||'.$rows['observaciones'].'||'.$rows['estadoasig'].'-------CURSO'.$rows['id_curso'].'||'.$rows['nombre_curso'].'||'.$rows['estado'].'<br><br>';
                }     
            }
    }     
    //var_dump($arrayAsig);
    //  exit();
    $arrayfin=array_merge($arrayAsig,$arraySinAsig);

    echo json_encode($arrayfin); 
}
    
if($accion == "listar_cursos"){
        
      
   //cursos asignacis    
   $cursos = $db->query("SELECT cur.*,cate.*, cur.estado AS estadocurso FROM  ext_curso cur INNER JOIN ext_categoria cate ON cate.id_categoria=cur.categoria_id WHERE cur.estado='A' ")->fetch(); 
   //AND cur.gestion_id = $id_gestion    pre.*, pre.nombre AS nombre_pre,pre.descripcion AS desc_pre,pre.tipo AS tipo_pre  , left JOIN ext_contenido_prerequisito pre ON pre.curso_id=cur.id_curso
   $resFinal=array(); 

   foreach($cursos as $rows1){

    $id_curso=$rows1['id_curso'];
    //var_dump($rows1);exit();
       
    $asigacion = $db->query("SELECT   (SELECT COUNT(ci.curso_asignacion_id) FROM ext_curso_inscripcion ci WHERE ci.curso_asignacion_id =asi.id_curso_asignacion)as inscritos,
    per.nombres,per.primer_apellido,per.segundo_apellido,per.foto, asi.*,asi.estado AS estadoasig 
    FROM ext_curso_asignacion asi   
    INNER JOIN per_asignaciones asid ON asid.id_asignacion=asi.asignacion_id
    INNER JOIN sys_persona per ON per.id_persona=asid.persona_id
    WHERE asi.estado='A' AND asi.gestion_id=$id_gestion   AND asi.curso_id=$id_curso ORDER BY asi.habilitado asc")->fetch(); //
       
    if($asigacion){
        
     foreach($asigacion as $rows2){
          //$resultado1=$rows2['id_curso_asignacion'].'--NO a';
         //$arm=array(  'id_curso'=>$id_curso, 'id_curso_asignacion'=>$rows2['id_curso_asignacion'],  'habilitado'=>$rows2['id_curso_asignacion']);
         $arm=array(
            'inscritos'=>$rows2['inscritos'],
            'nombres'=>$rows2['nombres'],
            'primer_apellido'=>$rows2['primer_apellido'],
            'segundo_apellido'=>$rows2['segundo_apellido'],
            'foto'=>$rows2['foto'],
             
            'id_categoria'=>$rows1['id_categoria'],
            'categoria'=>$rows1['categoria'],
            'descripcion_cat'=>$rows1['descripcion_cat'],
            'imagen'=>$rows1['imagen'],
             
            'id_curso'=>$rows1['id_curso'],
            'categoria_id'=>$rows1['categoria_id'],
            'nombre_curso'=>$rows1['nombre_curso'],
            'imagen_curso'=>$rows1['imagen_curso'],
            'cupo_minimo_curso'=>$rows1['cupo_minimo_curso'],
            'objetivo_curso'=>$rows1['objetivo_curso'],
            'descripcion_curso'=>$rows1['descripcion_curso'],
                
            'id_curso_asignacion'=>$rows2['id_curso_asignacion'],
            'curso_id'=>$rows2['curso_id'],
            'asignacion_id'=>$rows2['asignacion_id'],
            'horario_dia'=>$rows2['horario_dia'],
            'cupo'=>$rows2['cupo'],
            'fecha_inicio'=>$rows2['fecha_inicio'],
            'fecha_fin'=>$rows2['fecha_fin'],
            'modulo'=>$rows2['modulo'],
            'duracion'=>$rows2['duracion'],
            'certificado'=>$rows2['certificado'],
            'carga_horaria'=>$rows2['carga_horaria'],
            'periodo'=>$rows2['periodo'],
            'ambiente'=>$rows2['ambiente'],
            'fecha_inscripcion_inicio'=>$rows2['fecha_inscripcion_inicio'],
            'fecha_inscripcion_fin'=>$rows2['fecha_inscripcion_fin'],
            'observaciones'=>$rows2['observaciones'],
            'pension_id'=>$rows2['pension_id'],
             
            //'id_contenido_prerequisito'=>$rows1['id_contenido_prerequisito'],
            //'nombre'=>$rows1['nombre'],
            //'descripcion'=>$rows1['descripcion'],
            //'tipo'=>$rows1['tipo'],
            //'curso_id'=>$rows1['curso_id'],
            //'nombre_pre'=>$rows1['nombre_pre'],
            //'desc_pre'=>$rows1['desc_pre'],
            //'tipo_pre'=>$rows1['tipo_pre']// ,
            // 'estadoasig'=>$rows['estadoasig'],
            // 'estadocurso'=>$rows['estadocurso'] 
            );
         if($rows2['habilitado']=='SI'){
             //$resultado1=$rows2['id_curso_asignacion'].'--SI abilitado';
             //$arm=array(  'id_curso'=>$id_curso,'id_curso_asignacion'=>$rows2['id_curso_asignacion']  );
             break;
         }      
     }
    }else{
         //$resultado1='SIN asigancion';
        //$arm=array( 'id_curso'=>$id_curso, 'id_curso_asignacion'=>null  );
         $arm=array(
            //'inscritos'=>$rows2['inscritos'],
            //'nombres'=>$rows2['nombres'],
            //'primer_apellido'=>$rows2['primer_apellido'],
            //'segundo_apellido'=>$rows2['segundo_apellido'],
            //'foto'=>$rows2['foto'],
             
            'id_categoria'=>$rows1['id_categoria'],
            'categoria'=>$rows1['categoria'],
            'descripcion_cat'=>$rows1['descripcion_cat'],
            'imagen'=>$rows1['imagen'],
             
            'id_curso'=>$rows1['id_curso'],
            'categoria_id'=>$rows1['categoria_id'],
            'nombre_curso'=>$rows1['nombre_curso'],
            'imagen_curso'=>$rows1['imagen_curso'],
            'cupo_minimo_curso'=>$rows1['cupo_minimo_curso'],
            'objetivo_curso'=>$rows1['objetivo_curso'],
            'descripcion_curso'=>$rows1['descripcion_curso'],
                
            'id_curso_asignacion'=>null,
            'curso_id'=>null,
            'asignacion_id'=>null
            );
    }
       
       array_push($resFinal,$arm);
        //echo('Curso:'.$id_curso.' asignacion:'.$resultado1.'<br>');
   }     
    //var_dump($resFinal); 
    //exit();    
 
    echo json_encode($resFinal); 
}
 
if($accion == "listar_categorias"){
    $categorias = $db->query("SELECT (SELECT count(cur.categoria_id) FROM ext_curso cur WHERE cur.estado='A' and cur.categoria_id=cat.id_categoria)AS ncategorias, cat.* FROM ext_categoria cat WHERE cat.estado='A'")->fetch();
        echo json_encode($categorias); 
}
    
if($accion == "listar_requisitos"){
		$is_curso = $_POST['id_curso']; 
        $categorias = $db->query("  SELECT prer.* FROM  ext_contenido_prerequisito prer WHERE prer.estado='A' AND prer.curso_id=$is_curso")->fetch();
        echo json_encode($categorias); 
}
        
if($accion == "listar_inscritos"){
        $is_asig = $_POST['id']; 
        $inscritos  = $db->query("SELECT e.id_estudiante, p.*,au.nombre_aula,pa.nombre_paralelo,ni.nombre_nivel, ci.*
        FROM ext_curso_asignacion AS ca
        INNER JOIN ext_curso_inscripcion AS ci ON ci.curso_asignacion_id = ca.id_curso_asignacion   
        INNER JOIN ins_estudiante AS e ON e.id_estudiante = ci.estudiante_id
        INNER JOIN sys_persona AS p ON p.id_persona = e.persona_id 
        
        INNER JOIN ins_inscripcion insc ON insc.estudiante_id=e.id_estudiante
					INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo=insc.aula_paralelo_id
					INNER JOIN ins_aula au ON au.id_aula=ap.aula_id
					INNER JOIN ins_paralelo pa ON pa.id_paralelo=ap.paralelo_id
					INNER JOIN ins_nivel_academico ni ON ni.id_nivel_academico=au.nivel_academico_id
					
        WHERE ca.id_curso_asignacion = $is_asig AND ci.estado = 'A'  
        GROUP BY ci.id_curso_inscripcion
        
       
        ORDER BY p.nombres ASC ")->fetch();
          
         echo json_encode($inscritos); 
} 
    
if ($accion == "listar_docente") { 
    $consulta="SELECT  pro.*,pe.*,ca.* FROM per_asignaciones pro INNER JOIN sys_persona pe ON pe.id_persona=pro.persona_id
     INNER JOIN per_cargos ca ON ca.id_cargo=pro.cargo_id 
     WHERE  pro.cargo_id=1 AND pro.estado='A'
     AND
     pro.gestion_id=$id_gestion AND
     ca.estado='A'
     ORDER BY pe.primer_apellido ASC";// ORDER BY p.primer_apellido ASC
    //$consulta="SELECT  pro.*,pe.* FROM pro_profesor pro INNER JOIN sys_persona pe ON  pe.id_persona=pro.persona_id ORDER BY pe.primer_apellido ASC"; // ORDER BY p.primer_apellido ASC

        $inscritos = $db->query($consulta)->fetch();

        echo json_encode($inscritos); 

}
    

if ($accion == "listar_concepto_pago") { 

        $consulta="SELECT p.id_pensiones, p.nombre_pension, p.codigo_concepto, p.nro_cuota, p.tipo_documento, pd.cuota
        FROM  pen_pensiones p
        INNER JOIN (
        SELECT pd.pensiones_id, pd.cuota
        FROM pen_pensiones_detalle pd
        GROUP BY pd.pensiones_id
        )pd ON p.id_pensiones=pd.pensiones_id
        WHERE p.estado = 'A'
        AND p.tipo_concepto_pago = 'E'
        AND p.gestion_id = '$id_gestion'
        GROUP BY p.id_pensiones";// ORDER BY p.primer_apellido ASC
            //$consulta="SELECT  pro.*,pe.* FROM pro_profesor pro INNER JOIN sys_persona pe ON  pe.id_persona=pro.persona_id ORDER BY pe.primer_apellido ASC"; // ORDER BY p.primer_apellido ASC

        $conceptos = $db->query($consulta)->fetch();

        $auxiliar = array();

        foreach($conceptos as $val){
            $array = array(
                    'id_pensiones'         => $val['id_pensiones'],
                    'nombre_pension'        => $val['nombre_pension'],
                    'codigo_concepto'       => $val['codigo_concepto'],
                    'monto'                 => $val['cuota'], 
                    'nro_cuota'             => $val['nro_cuota'],
                    'documento'             => $val['tipo_documento'], 
            );
            array_push($auxiliar, $array);
        }

        echo json_encode($auxiliar); 

}

if ($accion == "listar_estudiantes") {
        $consulta="SELECT est.id_estudiante,per.* FROM ins_inscripcion ins
            INNER JOIN  ins_estudiante est ON est.id_estudiante=ins.estudiante_id
            INNER JOIN sys_persona per ON per.id_persona=est.persona_id
            WHERE ins.estado='A' AND ins.gestion_id=$id_gestion 
         ORDER BY per.primer_apellido ASC"; 
    
        $filas = $db->query($consulta)->fetch();

        echo json_encode($filas); 
         
}

if($accion == "listar_cursos_asignaciones"){

    $id_curso=$_POST['id_curso'];
    //var_dump($rows1);exit();
       
    $asigacion = $db->query("SELECT   (SELECT COUNT(ci.curso_asignacion_id) 
    FROM ext_curso_inscripcion ci WHERE ci.estado='A' AND ci.curso_asignacion_id =asi.id_curso_asignacion)as inscritos,
    per.nombres,per.primer_apellido,per.segundo_apellido,per.foto,
    asi.*,asi.estado AS estadoasig, pen.nombre_pension FROM ext_curso_asignacion asi   
    INNER JOIN per_asignaciones asid ON asid.id_asignacion=asi.asignacion_id
    INNER JOIN sys_persona per ON per.id_persona=asid.persona_id
    INNER JOIN pen_pensiones pen ON pen.id_pensiones=asi.pension_id
    WHERE asi.estado='A' AND asi.gestion_id=$id_gestion  AND asi.curso_id=$id_curso ORDER BY asi.habilitado asc")->fetch(); 

     echo json_encode($asigacion); 
}

if($accion == "guardar_categoria"){
    /*id_cat guardar_categoria nombre descripcion file*/
    if (isset($_POST['nombre'])) {
    // Obtiene los datos
    $nombre =  $_POST['nombre'];
    $descripcion = (isset($_POST['descripcion'])) ? clear($_POST['descripcion']) :''; 
        
    //$file = (isset($_POST['file'])) ? clear($_POST['file']) :0;
     
    $id_cat = (isset($_POST['id_cat'])) ? clear($_POST['id_cat']) :false;
      
    $nombre_archivo = isset($_FILES['file']['name'])?($_FILES['file']['name']):false;
        
    if($nombre_archivo && $nombre_archivo!=''){
        $tipo_archivo = $_FILES['file']['type'];
        $tamano_archivo = $_FILES['file']['size'];
        
        if ($tamano_archivo > 10000000) {//max 10 megas
        // if (!((strpos($tipo_archivo, "gif") || strpos($tipo_archivo, "jpg") || strpos($tipo_archivo, "png") || strpos($tipo_archivo, "jpeg") || strpos($tipo_archivo, "docx") || strpos($tipo_archivo, "xlsx") || strpos($tipo_archivo, "pptx")|| strpos($tipo_archivo, "pdf")|| strpos($tipo_archivo, "ppt")|| strpos($tipo_archivo, "pptx")|| strpos($tipo_archivo, "xls")|| strpos($tipo_archivo, "doc")|| strpos($tipo_archivo, "plain")) && ($tamano_archivo < 10000000))) {
            //10megas?
            echo 5;//el tipo de archivo no es permitido intente con un word o pdf
            ///eroor
            exit();
        }else{
               
            if ($nombre_archivo !='') {
                //se borra el archivo del servidor para poner el nuevo al actualizar
                if($id_cat){//si es actualisar
                    $bucarfile = $db->from('ext_categoria')->where('id_categoria',$id_cat)->fetch_first();
                    //QUEAHACER EN CASO DE NO ENCONTRAR MO FILE
                    $file = $bucarfile["imagen"];
                    if($file!='' && $file!=null && $file!='0'){
                        
                        $delete_dir = "files/categoriaCurso/".$file;
                        //var_dump($delete_dir);exit();
                        try{
                            unlink($delete_dir);
        
                        }catch(Exception $e){
                            echo 'el archivo cambio de ubicacion.';
                        }
                    }
                }
                
                $output_dir = "files/categoriaCurso/";
                //$archivo = 'F'."-".date('dmYHis').'.'.pathinfo($nombre_archivo, PATHINFO_EXTENSION);//dmY_His
                 
                //$archivo = date('dmYHis')."-".$nombre_archivo;
                $archivo = 'I'."-".date('dmYHis').'.'.pathinfo($nombre_archivo, PATHINFO_EXTENSION);//dmY_His
                
                if (!move_uploaded_file($_FILES['file']["tmp_name"],$output_dir.$archivo)) {
                        $msg = 'No pudo subir el archivo';
                }
            } 
        }
    }else{
        $archivo=NULL; 
    } 
    // Verifica si es creacion o modificacion
    if ($id_cat) {
        $img=array();
        $dat = array(
    	'categoria' => $nombre,
    	'descripcion_cat' => $descripcion,
    	//'imagen' => $archivo, //no enviar en caso de no tener imagen
    	'estado' => 'A'
        );
        
        if($archivo!=NULL && $archivo!=''){
            $img=array('imagen' => $archivo);
          }
          $datos=array_merge($dat,$img);

    	// Modifica el horario
    	$db->where('id_categoria', $id_cat)->update('ext_categoria', $datos);
    	
    	// Guarda el proceso
    	$db->insert('sys_procesos', array(
    		'fecha_proceso' => date('Y-m-d'),
    		'hora_proceso' => date('H:i:s'),
    		'proceso' => 'u',
    		'nivel' => 'l',
    		'detalle' => 'Se modificó el kardex de eehh con identificador número ' . $id_cat . '.',
    		'direccion' => $_location,
    		'usuario_id' => $_user['id_user']
    	));
    	
    	 
    	// Redirecciona la pagina
    	//redirect('?/rh-horarios/ver/' . $id_horario);
        echo 2;
    } else {
    	// Crea el horario
        $datos = array(
    	'categoria' => $nombre,
    	'descripcion_cat' => $descripcion,
    	'imagen' => $archivo, 
    	'estado' => 'A'
    );
    	$id_horario = $db->insert('ext_categoria', $datos);
         
        
    	// Guarda el proceso
    	$db->insert('sys_procesos', array(
    		'fecha_proceso' => date('Y-m-d'),
    		'hora_proceso'  => date('H:i:s'),
    		'proceso'       => 'c',
    		'nivel'         => 'l',
    		'detalle'       => 'Se creó categoria de curso con identificador número ' . $id_horario . '.',
    		'direccion'     => $_location,
    		'usuario_id'    => $_user['id_user']
    	));
    	
    	 
        echo 1;
		}
	} else {
		 echo 10; 
	}
    
}

if($accion == "guardar_curso"){
    if (isset($_POST['nombre'])) {
		// Obtiene los datos  
       $nombre = (isset($_POST['nombre'])) ? clear($_POST['nombre']) :0;
       $cupo = (isset($_POST['cupo'])) ? clear($_POST['cupo']) :0;
       $objetivo = (isset($_POST['objetivo'])) ? clear($_POST['objetivo']) :'';
       $descripcion = (isset($_POST['descripcion'])) ? clear($_POST['descripcion']) :'';
       $categoria = (isset($_POST['categoria'])) ? clear($_POST['categoria']) :0;
       $id_componente = (isset($_POST['id_curso'])) ? clear($_POST['id_curso']) :false;
       //$file = (isset($_POST['file'])) ? clear($_POST['file']) :0;
		 //___archivos
            $nombre_archivo = isset($_FILES['file']['name'])?($_FILES['file']['name']):false;
            $archivo='';

            if($nombre_archivo && $nombre_archivo!=''){
                $tipo_archivo = $_FILES['file']['type'];
                $tamano_archivo = $_FILES['file']['size'];
                //ya 
                if ($tamano_archivo > 10000000) {
                    // if (!((strpos($tipo_archivo, "gif") || strpos($tipo_archivo, "jpg") || strpos($tipo_archivo, "png") || strpos($tipo_archivo, "jpeg") || strpos($tipo_archivo, "docx") || strpos($tipo_archivo, "xlsx") || strpos($tipo_archivo, "pptx")|| strpos($tipo_archivo, "pdf")|| strpos($tipo_archivo, "ppt")|| strpos($tipo_archivo, "pptx")|| strpos($tipo_archivo, "xls")|| strpos($tipo_archivo, "doc")|| strpos($tipo_archivo, "plain")) && ($tamano_archivo < 10000000))) {
                    //10megas?
                    //echo 5;//el tipo de archivo no es permitido intente con un word o pdf
                    $resultado=array('estado'=>5,'id_curso'=>0);
    				echo json_encode($resultado);
                    exit();
                }else{
                       
                    if ($nombre_archivo !='') {
                        //se borra el archivo del servidor para poner el nuevo al actualizar 
                        if($id_componente){//si es actualisar
                            $bucarfile = $db->from('ext_curso')->where('id_curso',$id_componente)->fetch_first();
                            //QUEAHACER EN CASO DE NO ENCONTRAR MO FILE
                            $file = $bucarfile["imagen_curso"];
                           //var_dump($file);   
                            if($file!="" && $file!=null && $file!='0'){ 
                                //ELIMINANDO LA IMAGEN  
                                $delete_dir = "files/".$nombre_dominio."/cursoextracurricular/".$file;  
                                try{
                                    unlink($delete_dir); 
                                    //echo 'borrado';
                                }catch(Exception $e){
                                    echo 'el archivo cambio de ubicacion.';
                                }
                                
                            }//else{
                             //   echo 'no ingreso a eliiminado';
                            //}
                               // exit();
                        }
                        
                        $output_dir = "files/".$nombre_dominio."/cursoextracurricular/";
                         $archivo = 'C'."-".date('dmYHis').'.'.pathinfo($nombre_archivo, PATHINFO_EXTENSION);//dmY_His
                        //$archivo = date('dmYHis')."-".$nombre_archivo;
                        
                        
                        if (!move_uploaded_file($_FILES['file']["tmp_name"],$output_dir.$archivo)) {
                            $msg = 'No pudo subir el archivo';
                        }
                    } 
                }
            }else{
                $archivo=NULL; 
            }
          
        // Verifica si es creacion o modificacion
		if ($id_componente) {
            
            
             $img=array();
             $dat = array(
			'categoria_id' => $categoria, 
			'nombre_curso' => $nombre,
			'cupo_minimo_curso' => $cupo,
			'objetivo_curso' =>$objetivo,  
			'descripcion_curso' => $descripcion, 
            'usuario_modificacion'=> $_user['id_user'],
            'fecha_modificacion'=> date('Y-m-d') 
            ); 
            if($archivo!=NULL && $archivo!=''){
              $img=array('imagen_curso' => $archivo);
            }
                 $datos=array_merge($dat,$img);
            
		  // Modifica el horario
			$db->where('id_curso', $id_componente)->update('ext_curso', $datos);
			
			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso' => date('H:i:s'),
				'proceso' => 'u',
				'nivel' => 'l',
				'detalle' => 'Se modificó el kardex de eehh con identificador número ' . $id_componente . '.',
				'direccion' => $_location,
				'usuario_id' => $_user['id_user']
			));
			
			 
			//  echo 2;
			$resultado=array('estado'=>2,'id_curso'=>$id_componente);
			echo json_encode($resultado);
		}
          else {
			// Crea el horario
            $datos = array(
			'categoria_id' => $categoria, 
			'nombre_curso' => $nombre,
			'imagen_curso' => $archivo,
			'cupo_minimo_curso' => $cupo,
			'objetivo_curso' =>$objetivo,  
			'descripcion_curso' => $descripcion, 
                
			'gestion_id' => $id_gestion,//cambiar
			'estado' => 'A',
            'usuario_modificacion'=> $_user['id_user'],
            'fecha_modificacion'=> date('Y-m-d'),
            'usuario_registro'=> $_user['id_user'],
            'fecha_registro'=> date('Y-m-d')
		);
			$id_horario = $db->insert('ext_curso', $datos);
              
			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso'  => date('H:i:s'),
				'proceso'       => 'c',
				'nivel'         => 'l',
				'detalle'       => 'Se creó el kardex con identificador número ' . $id_horario . '.',
				'direccion'     => $_location,
				'usuario_id'    => $_user['id_user']
			));
			 $resultado=array('estado'=>1,'id_curso'=>$id_horario);
			echo json_encode($resultado);
           // echo 1;
              
		}
        } else {
		 //echo 10; 
			$resultado=array('estado'=>10,'id_curso'=>0);
			echo json_encode($resultado);
	}
        
}
    
if($accion == "guardar_asignacion"){

    //var_dump($_POST);exit();
    if (isset($_POST['fechaini'])) {
		// Obtiene los datos 
       $fechaini = (isset($_POST['fechaini'])) ? clear($_POST['fechaini']) :''; 
       $fechafin = (isset($_POST['fechafin'])) ? clear($_POST['fechafin']) :''; 
       $horaini = (isset($_POST['horaini'])) ? clear($_POST['horaini']) :''; 
       $id_pension = (isset($_POST['id_pension'])) ? clear($_POST['id_pension']) :''; 
       $duracion = (isset($_POST['duracion']) and $_POST['duracion']!='') ? clear($_POST['duracion']) :0; 
       $ambiente = (isset($_POST['ambiente'])) ? clear($_POST['ambiente']) :''; 
       $periodo = (isset($_POST['periodo'])) ? clear($_POST['periodo']) :''; 
       $cupo = (isset($_POST['cupo']) and $_POST['cupo']!='') ? clear($_POST['cupo']) :0; 
       $modulo = (isset($_POST['modulo']) and $_POST['modulo']!='') ? clear($_POST['modulo']) :0; 
       $certificado = (isset($_POST['certificado'])) ? clear($_POST['certificado']) :''; 
       $cargaHoraria = (isset($_POST['cargaHoraria']) and $_POST['cargaHoraria']!='') ? clear($_POST['cargaHoraria']) :0; 
       $fechainscripini = (isset($_POST['fechainscripini'])  and $_POST['fechainscripini']!='') ? clear($_POST['fechainscripini']) :'0000-00-00'; 
       
       $fechainscripfin = (isset($_POST['fechainscripfin']) ) ? (($_POST['fechainscripini']!='')?$_POST['fechainscripini']:"0000-00-00"):'0000-00-00'; 
       $observaciones = (isset($_POST['observaciones'])) ? clear($_POST['observaciones']) :''; 
       $curso_id = (isset($_POST['id_curso'])) ? clear($_POST['id_curso']) :0; 
       $asignacion_id = (isset($_POST['id_docente'])) ? clear($_POST['id_docente']) :0; 
             
       $id_asigcurso = (isset($_POST['id_asigcurso'])) ? clear($_POST['id_asigcurso']) :false;
      
        $datosComunes=array(
            //'curso_id' => $curso_id,
			'pension_id' => $id_pension,
            'asignacion_id' => $asignacion_id,
			'horario_dia' => $horaini,
			'cupo' => $cupo,
			'fecha_inicio' => $fechaini,
			'fecha_fin' => $fechafin,
			'modulo' => $modulo,
			'duracion' => $duracion,
			'certificado' => $certificado,
			'carga_horaria' => $cargaHoraria,
			'periodo' => $periodo,
			'ambiente' => $ambiente,
			'fecha_inscripcion_inicio' => $fechainscripini,
			'fecha_inscripcion_fin' => $fechainscripfin,
			'observaciones' => $observaciones,
			//'estado' => 'A',
            'usuario_modificacion'=> $_user['id_user'],
            'fecha_modificacion'=> date('Y-m-d'),
            //'usuario_registro'=> $_user['id_user'],
            //'fecha_registro'=> date('Y-m-d')
        );  
        //var_dump($datosComunes);exit();
        if ($id_asigcurso) {
           
			// Modifica el horario
			$db->where('id_curso_asignacion', $id_asigcurso)->update('ext_curso_asignacion', $datosComunes);
			
			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso' => date('H:i:s'),
				'proceso' => 'u',
				'nivel' => 'l',
				'detalle' => 'Se modificó el curso extracurricular de eehh con identificador número ' . $id_asigcurso . '.',
				'direccion' => $_location,
				'usuario_id' => $_user['id_user']
			));
			
			 
			// Redirecciona la pagina
			//redirect('?/rh-horarios/ver/' . $id_horario);
            echo 2;
		}else {
			// Crea el horario

            $datosNew = array( 
			 'curso_id' => $curso_id, 
			'estado' => 'A', 
			'gestion_id' => $id_gestion, 
            'usuario_registro'=> $_user['id_user'],
            'fecha_registro'=> date('Y-m-d')
		);
            $datos=array_merge($datosComunes,$datosNew);
			$id_dato_agregado = $db->insert('ext_curso_asignacion', $datos);
             
            
			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso'  => date('H:i:s'),
				'proceso'       => 'c',
				'nivel'         => 'l',
				'detalle'       => 'Se creó asignacion de curso con identificador número ' . $id_dato_agregado . '.',
				'direccion'     => $_location,
				'usuario_id'    => $_user['id_user']
			));
			
			 
            echo 1;
		}
    } else {
		 echo 10; 
	}
        
}
    
if($accion == "guardar_requisito"){
    if (isset($_POST['nombre']) && isset($_POST['id_curso'])) {
		// Obtiene los datos 
       $nombre = (isset($_POST['nombre'])) ? clear($_POST['nombre']) :''; 
       $tipo = (isset($_POST['tipo'])) ? clear($_POST['tipo']) :''; 
       $descripcion = (isset($_POST['descripcion'])) ? clear($_POST['descripcion']) :''; 
        $id_curso = (isset($_POST['id_curso'])) ? clear($_POST['id_curso']) :0; 
         
       $id_componente = (isset($_POST['id_requisito'])) ? clear($_POST['id_requisito']) :false;
      
            if ($id_componente) {
            $datos = array( 
			'nombre' => $nombre,
			'tipo' => $tipo,
			'descripcion' => $descripcion,
			'curso_id' => $id_curso 
		);
			// Modifica el horario
			$db->where('id_contenido_prerequisito', $id_componente)->update('ext_contenido_prerequisito', $datos);
			
			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso' => date('H:i:s'),
				'proceso' => 'u',
				'nivel' => 'l',
				'detalle' => 'Se modificó el kardex de eehh con identificador número ' . $id_componente . '.',
				'direccion' => $_location,
				'usuario_id' => $_user['id_user']
			));
			// Redirecciona la pagina
			//redirect('?/rh-horarios/ver/' . $id_horario);
            echo 2;
		}
             else {
			// Crea el horario

            $datos = array( 
			'nombre' => $nombre,
			'tipo' => $tipo,
			'descripcion' => $descripcion,
			 'curso_id' => $id_curso   
		);
			$id_dato_agregado = $db->insert('ext_contenido_prerequisito', $datos);
             
            
			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso'  => date('H:i:s'),
				'proceso'       => 'c',
				'nivel'         => 'l',
				'detalle'       => 'Se creó contenido prerequisito con identificador número ' . $id_dato_agregado . '.',
				'direccion'     => $_location,
				'usuario_id'    => $_user['id_user']
			));
			
			 
            echo 1;
		}
    } else {
		 echo 10; 
	}
        
}
    
if($accion == "guardar_inscripcion"){

    if (isset($_POST['id_estudiante']) && isset($_POST['id_asignacion'])) {

        //var_dump($_POST);exit();

        // Obtiene los datos 
        $id_estudiante = (isset($_POST['id_estudiante'])) ? clear($_POST['id_estudiante']) :0; 
        $id_asignacion = (isset($_POST['id_asignacion'])) ? clear($_POST['id_asignacion']) :0; 
        $id_pensiones  = (isset($_POST['id_pensiones'])) ? clear($_POST['id_pensiones']) :0; 
        $obs           = (isset($_POST['obs'])) ? clear($_POST['obs']) :''; 
        $tipo          = (isset($_POST['tipo'])) ? clear($_POST['tipo']) :'';
        $id_componente = (isset($_POST['id_inscribir'])) ? clear($_POST['id_inscribir']) :false; //id_inscrìpcion_curso_extracurricular

        $sql = "SELECT i.estudiante_id, i.id_inscripcion, h.id_historial
        FROM ins_inscripcion i 
        INNER JOIN ins_inscripcion_historial h ON i.id_inscripcion = h.inscripcion_id
        WHERE i.estudiante_id = $id_estudiante
        AND i.gestion_id = $id_gestion
        AND i.estado = 'A'
        AND h.estado = 'A'
        AND (i.estado_inscripcion = 'INSCRITO' OR i.estado_inscripcion = 'INCORPORADO' OR i.estado_inscripcion = 'REPITENTE') 
        ORDER BY h.id_historial DESC"; 
        $inscripcion = $db->query($sql)->fetch_first(); 

        $pagos = $db->query("SELECT * FROM pen_pensiones p inner join pen_pensiones_detalle pd on p.id_pensiones=pd.pensiones_id where p.id_pensiones=$id_pensiones  and p.tipo_concepto_pago = 'E' ORDER BY pd.nro")->fetch();
        //$pagos_concepto = $pagos;
        //var_dump($pagos);exit();
   
         
        if ($id_componente > 0 ) { //si existe en curso extra Editar

            $datos = array( 
    			'tipo_inscripcion'   => $tipo,
    			'observacion'        => $obs, 
    			'estado'             => 'A',
    			'usuario_modificacion'   => $_user['id_user'],
                'fecha_modificacion'     => date('Y-m-d H:i:s'),
    		);

			// Modifica el horario
			$db->where('id_curso_inscripcion', $id_componente)->update('ext_curso_inscripcion', $datos); 

			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso'  => date('H:i:s'),
				'proceso'       => 'u',
				'nivel'         => 'l',
				'detalle' => 'Se modificó la inscripcion de curso extracurricular con identificador número ' . $id_componente . ' y estudiante ' . $id_estudiante . '.',
				'direccion' => $_location,
				'usuario_id' => $_user['id_user']
			)); 

            echo 2;

		} else { //no existe en curso extra insertar

			// Crea el nueva inscripcion
            $est = $db->query("SELECT * FROM ext_curso_inscripcion cin WHERE cin.curso_asignacion_id=$id_asignacion AND cin.estudiante_id=$id_estudiante")->fetch_first();  

            if($est){ 

                //si existe es repetido
                echo 12;

            } else {

                $asig= $db->query("SELECT (SELECT COUNT(ci.curso_asignacion_id)  FROM ext_curso_inscripcion ci WHERE ci.estado='A' AND ci.curso_asignacion_id =asi.id_curso_asignacion)AS ninscritos, asi.cupo	FROM ext_curso_asignacion asi WHERE asi.id_curso_asignacion=$id_asignacion")->fetch_first(); 

                if($asig){

                    if($asig['ninscritos']>=$asig['cupo']){//4>3

                        //no inscribir
                        echo 11;

                    }else{

                        //ar_dump('expression');exit();

                        //inscribir
                        $datos = array( 
                            'tipo_inscripcion'      => $tipo,
                            'observacion'           => $obs,
                            'estudiante_id'         => $id_estudiante,
                            'curso_asignacion_id'   => $id_asignacion,   
                            'estado'                => 'A', 
                            'gestion_id'            => $id_gestion,
                            'usuario_modificacion'  => $_user['id_user'],
                            'fecha_modificacion'    => date('Y-m-d H:i:s'),
                            'usuario_registro'      => $_user['id_user'],
                            'fecha_registro'        => date('Y-m-d H:i:s')
                        );
                        //var_dump($datos);exit();
                        $id_dato_agregado = $db->insert('ext_curso_inscripcion', $datos);
                        //var_dump($id_dato_agregado);exit();

                        if($id_dato_agregado > 0){

                            //var_dump($pagos_concepto);exit();

                            foreach ($pagos as $value){                                

                                $detalle_estudiante = array(
                                    'detalle_pension_id'    => $value['id_pensiones_detalle'],
                                    'inscripcion_id'        => $id_dato_agregado,//$inscripcion['id_inscripcion'],
                                    'historial_id'          => 0,//$inscripcion['id_historial'],
                                    'tipo_concepto'         => $value['tipo_concepto'],
                                    'fecha_registro'        => date('Y-m-d H:i:s'),
                                    'fecha_modificacion'    => '0000-00-00 00:00:00',
                                    'usuario_registro'      => $_user['id_user'],
                                    'usuario_modificacion'  => 0,
                                    'cuota'                 => $value['cuota'],
                                    'descuento_porcentaje'  => $value['descuento_porcentaje'],
                                    'descuento_bs' => $value['descuento_bs'],
                                    'monto'        => $value['monto'],
                                    'mora_dia'     => $value['mora_dia'],
                                    'fecha_inicio' => $value['fecha_inicio'],
                                    'fecha_final'  => $value['fecha_final'],
                                    'tipo_pen_estudiante'  => 'E',
                                );
                                //var_dump($datos);exit();
                                //var_dump($detalle_estudiante);
                                $id_pensiones_estudiante = $db->insert('pen_pensiones_estudiante', $detalle_estudiante);
                            }

                            // Guarda el proceso
                            $db->insert('sys_procesos', array(
                                'fecha_proceso' => date('Y-m-d'),
                                'hora_proceso'  => date('H:i:s'),
                                'proceso'       => 'c',
                                'nivel'         => 'l',
                                'detalle'       => 'Se creó inscripcion a curso con identificador número ' . $id_dato_agregado . '.',
                                'direccion'     => $_location,
                                'usuario_id'    => $_user['id_user']
                            )); 
                            echo 1;

                        }else{

                            echo 0;

                        }
                        
                    }
                }     
            }
	    }
    } else {
		 echo 10; 
	}
            
    }
            
    if($accion == "eliminar_curso"){
            
            $id_componente = $_POST['id_componente']; 
             
            // verificamos su exite el id en tabla
            $rows = $db->from('ext_curso')->where('id_curso',$id_componente)->fetch_first();  
             
            //en caso de si eliminarar en caso de no dara mensaje de error
            if($rows){
                 //ELIMINANDO LA IMAGEN
                $file = $rows["imagen_curso"]; 
                $delete_dir = "files/".$nombre_dominio."/cursoextracurricular/".$file;  
                try{
                    unlink($delete_dir); 
                }catch(Exception $e){
                    echo 'el archivo cambio de ubicacion.';
                }
               //CAMBIANDO EL ESTADO A INACTIVO
              $esta=$db->query("UPDATE ext_curso SET estado = 'I', usuario_modificacion = '".$_user['id_user']."', fecha_modificacion = '".date('Y-m-d')."' WHERE id_curso = '".$id_componente."'")->execute();
                
                //VERIFICANDO SI SE ELIMINO
                if ($esta){
                    registrarProceso('Se eliminó curso exracurricular con identificador número ' ,$id_componente ,$db,$_location,$_user['id_user']);
                    echo 1;//'Eliminado Correctamente.';
                }else{
                    echo 2;//'No se pudo eliminar';
                }
            }
    }
             
    if($accion == "eliminar_asignacion"){
            
            $id_componente = $_POST['id_componente']; 
             
            // verificamos su exite el id en tabla
            $rows = $db->from('ext_curso_asignacion')->where('id_curso_asignacion',$id_componente)->fetch_first();  
             
            //en caso de si eliminarar en caso de no dara mensaje de error
            if($rows){
                  
               //CAMBIANDO EL ESTADO A INACTIVO
              $esta=$db->query("UPDATE ext_curso_asignacion SET estado = 'I', usuario_modificacion = '".$_user['id_user']."', fecha_modificacion = '".date('Y-m-d')."' WHERE id_curso_asignacion = '".$id_componente."'")->execute();
                
                //VERIFICANDO SI SE ELIMINO
                if ($esta){
                    registrarProceso('Se eliminó asignacion de curso extracurricular con identificador número ' ,$id_componente ,$db,$_location,$_user['id_user']);
                    echo 1;//'Eliminado Correctamente.';
                }else{
                    echo 2;//'No se pudo eliminar';
                }
            }
    }
              
    if($accion == "eliminar_inscripcion"){
            
            $id_componente = $_POST['id_componente']; 
             
            // verificamos su exite el id en tabla
            $rows = $db->from('ext_curso_inscripcion')->where('id_curso_inscripcion',$id_componente)->fetch_first();  
             
            //en caso de si eliminarar en caso de no dara mensaje de error
            if($rows){
                  
               //CAMBIANDO EL ESTADO A INACTIVO
              $esta=$db->query("UPDATE ext_curso_inscripcion SET estado = 'I', usuario_modificacion = '".$_user['id_user']."', fecha_modificacion = '".date('Y-m-d')."' WHERE id_curso_inscripcion = '".$id_componente."'")->execute();
                
                //VERIFICANDO SI SE ELIMINO
                if ($esta){
                    registrarProceso('Se eliminó inscripcion de estudainte  de curso extracurricular con identificador número ' ,$id_componente ,$db,$_location,$_user['id_user']);
                    echo 1;//'Eliminado Correctamente.';
                }else{
                    echo 2;//'No se pudo eliminar';
                }
            }
    }
              
    if($accion == "culminar_asignacion"){
            
            $id_componente = $_POST['id_componente']; 
             
            // verificamos su exite el id en tabla
            $rows = $db->from('ext_curso_asignacion')->where('id_curso_asignacion',$id_componente)->fetch_first();  
             
            //en caso de si eliminarar en caso de no dara mensaje de error
            if($rows){
                  
               //CAMBIANDO EL ESTADO A INACTIVO
              $esta=$db->query("UPDATE ext_curso_asignacion SET habilitado = 'NO', usuario_modificacion = '".$_user['id_user']."', fecha_modificacion = '".date('Y-m-d')."' WHERE id_curso_asignacion = '".$id_componente."'")->execute();
                
                //VERIFICANDO SI SE ELIMINO
                if ($esta){
                    registrarProceso('Se culmino asignacion de curso extracurricular con identificador número ' ,$id_componente ,$db,$_location,$_user['id_user']);
                    echo 1;//'Eliminado Correctamente.';
                }else{
                    echo 2;//'No se pudo eliminar';
                }
            }
    }
            
    if($accion == "eliminar_requisito"){
            $id_componente = $_POST['id_componente']; 
            //echo $id_componente;
             
            // verificamos su exite el id en tabla
            $rows = $db->from('ext_contenido_prerequisito')->where('id_contenido_prerequisito',$id_componente)->fetch_first();  
             
            //en caso de si eliminarar en caso de no dara mensaje de error
            if($rows){
                  
               //CAMBIANDO EL ESTADO A INACTIVO
              $esta=$db->query("UPDATE ext_contenido_prerequisito SET estado = 'I'  WHERE id_contenido_prerequisito = '".$id_componente."'")->execute();
                
                //VERIFICANDO SI SE ELIMINO
                if ($esta){
                    registrarProceso('Se elimino prerequisito de curso extracurricular con identificador número ' ,$id_componente ,$db,$_location,$_user['id_user']);
                    echo 1;//'Eliminado Correctamente.';
                }else{
                    echo 2;//'No se pudo eliminar';
                }
            }
    }
               
    if($accion == "eliminar_categoria"){
            $id_componente = $_POST['id_componente']; 
            //echo $id_componente;
             
            // verificamos su exite el id en tabla
            $rows = $db->query("SELECT (SELECT count(cur.categoria_id) FROM ext_curso cur WHERE cur.estado='A' and cur.categoria_id=cat.id_categoria)AS ncategorias, cat.* FROM ext_categoria cat WHERE cat.estado='A' AND id_categoria =".$id_componente)->fetch_first();  
             
            //var_dump($rows['ncategorias']);exit();
            if($rows){
                if($rows['ncategorias']>0 || $rows['ncategorias']!='0'){
                    echo 3;//'tiene datos';
                }else{
                    //echo 'No tiene datos';
                   //CAMBIANDO EL ESTADO A INACTIVO
                  $esta=$db->query("UPDATE ext_categoria SET estado = 'I'  WHERE id_categoria = '".$id_componente."'")->execute();

                    //VERIFICANDO SI SE ELIMINO
                    if ($esta){
                        registrarProceso('Se elimino prerequisito de curso extracurricular con identificador número ' ,$id_componente ,$db,$_location,$_user['id_user']);
                        echo 1;//'Eliminado Correctamente.';
                    }else{

                        echo 2;//'No se pudo eliminar';
                    }
                }
            //en caso de si eliminarar en caso de no dara mensaje de error
            }
    }
            
    //anteriores:::::::::::::::::::::::::::::::::::::::::::::::::::
    if($accion == "guardar_kardex"){
            
          if (isset($_POST['fecha_felicitacion'])) {
    		// Obtiene los datos
           $tipoFelSanc = (isset($_POST['tipoFelSanc'])) ? clear($_POST['tipoFelSanc']) :'';
           $id_persona = (isset($_POST['id_persona'])) ? clear($_POST['id_persona']) :'';
           $fecha = (isset($_POST['fecha_felicitacion'])) ? clear($_POST['fecha_felicitacion']) :0;
           $concepto = (isset($_POST['concepto'])) ? clear($_POST['concepto']) :0;
           $observacion = (isset($_POST['descripcion'])) ? clear($_POST['descripcion']) :0;
           $tipo = (isset($_POST['tipo_f'])) ? clear($_POST['tipo_f']) :0;
           //$file = (isset($_POST['file'])) ? clear($_POST['file']) :0;
    		    
           $id_kardex = (isset($_POST['id_kardex'])) ? clear($_POST['id_kardex']) :false;
              
    		$nombre_archivo = isset($_FILES['file']['name'])?($_FILES['file']['name']):false;
                if($nombre_archivo && $nombre_archivo!=''){
                    $tipo_archivo = $_FILES['file']['type'];
                    $tamano_archivo = $_FILES['file']['size'];
                    //ya 
                    if ($tamano_archivo > 10000000) {
                   // if (!((strpos($tipo_archivo, "gif") || strpos($tipo_archivo, "jpg") || strpos($tipo_archivo, "png") || strpos($tipo_archivo, "jpeg") || strpos($tipo_archivo, "docx") || strpos($tipo_archivo, "xlsx") || strpos($tipo_archivo, "pptx")|| strpos($tipo_archivo, "pdf")|| strpos($tipo_archivo, "ppt")|| strpos($tipo_archivo, "pptx")|| strpos($tipo_archivo, "xls")|| strpos($tipo_archivo, "doc")|| strpos($tipo_archivo, "plain")) && ($tamano_archivo < 10000000))) {
                        //10megas?
                        echo 5;//el tipo de archivo no es permitido intente con un word o pdf
                        ///eroor
                        exit();
                    }else{
                           
                        if ($nombre_archivo !='') {
                            //se borra el archivo del servidor para poner el nuevo al actualizar
                            if($id_kardex){//si es actualisar
                                $bucarfile = $db->from('per_kardex_personal')->where('id_kardex',$id_kardex)->fetch_first();
                                //QUEAHACER EN CASO DE NO ENCONTRAR MO FILE
                                $file = $bucarfile["adjunto_kardex"];
                                if($file=='' || $file==null || $file==0){
                                    
                                }
                                $delete_dir = "files/cardexPersonal/".$file;
                                //var_dump($delete_dir);exit();
                                try{
                                    unlink($delete_dir);
                
                                }catch(Exception $e){
                                    echo 'el archivo cambio de ubicacion.';
                                }
                            }
                            
                                $output_dir = "files/cardexPersonal/";
                                //$archivo = 'F'."-".date('dmYHis').'.'.pathinfo($nombre_archivo, PATHINFO_EXTENSION);//dmY_His
                                $archivo = date('dmYHis')."-".$nombre_archivo;
                                if (!move_uploaded_file($_FILES['file']["tmp_name"],$output_dir.$archivo)) {
                                    $msg = 'No pudo subir el archivo';
                                }
                        } 
                    }
                }else{
                    $archivo=NULL; 
                } 
    		// Verifica si es creacion o modificacion
    		if ($id_kardex) {
                $kardex = array(
    			'fecha_kardex' => $fecha,
    			'concepto_kardex' => $concepto,
    			'observacion_kardex' => $observacion,
    			//'tipo_kardex' => $tipoFelSanc,//el tipo Felic o Sanc no es editable
    			'tipo_ev_kardex' => $tipo, 
    			'adjunto_kardex' =>$archivo,  
    			//'estado' => 'A',// solo al eliminar
                'usuario_modificacion'=> $_user['id_user'],
                'fecha_modificacion'=> date('Y-m-d')
    		);
    			// Modifica el horario
    			$db->where('id_kardex', $id_kardex)->update('per_kardex_personal', $kardex);
    			
    			// Guarda el proceso
    			$db->insert('sys_procesos', array(
    				'fecha_proceso' => date('Y-m-d'),
    				'hora_proceso' => date('H:i:s'),
    				'proceso' => 'u',
    				'nivel' => 'l',
    				'detalle' => 'Se modificó el kardex de eehh con identificador número ' . $id_kardex . '.',
    				'direccion' => $_location,
    				'usuario_id' => $_user['id_user']
    			));
    			
    			 
    			// Redirecciona la pagina
    			//redirect('?/rh-horarios/ver/' . $id_horario);
                echo 2;
    		}
              else {
    			// Crea el horario
                $kardex = array(
    			'fecha_kardex' => $fecha,
    			'concepto_kardex' => $concepto,
    			'observacion_kardex' => $observacion,
    			'tipo_kardex' => $tipoFelSanc,
    			'tipo_ev_kardex' => $tipo,
    			'adjunto_kardex' =>$archivo,  
    			'persona_id' => $id_persona, 
    			'gestion_id' => $id_gestion,//cambiar
    			'estado' => 'A',
                'usuario_modificacion'=> $_user['id_user'],
                'fecha_modificacion'=> date('Y-m-d'),
                'usuario_registro'=> $_user['id_user'],
                'fecha_registro'=> date('Y-m-d')
    		);
    			$id_horario = $db->insert('per_kardex_personal', $kardex);
                 
                /*if(!isset($horarios_ids['horario_id'])|| $horarios_ids['horario_id']!=''){
                $horario_id = $horarios_ids['horario_id'].','.$id_horario; 
                }else{ 
    			$horario_id = $id_horario;
                }*/
                
                /*$horario = array(
    			'horario_id' => $horario_id, 
                'usuario_modificacion'=> $_user['id_user'],
                'fecha_modificacion'=> date('Y-m-d')
    		  );*/
                
               /* $db->where('id_asignacion', $id_asignacion)->update('per_asignaciones', $horario);*/
           
    			// Guarda el proceso
    			$db->insert('sys_procesos', array(
    				'fecha_proceso' => date('Y-m-d'),
    				'hora_proceso'  => date('H:i:s'),
    				'proceso'       => 'c',
    				'nivel'         => 'l',
    				'detalle'       => 'Se creó el kardex con identificador número ' . $id_horario . '.',
    				'direccion'     => $_location,
    				'usuario_id'    => $_user['id_user']
    			));
    			
    			 
                echo 1;
    		}
    	} else {
    		 echo 10; 
    	}
    }
        
    if($accion == "listar_reg_kardex"){  
         $persona=isset($_POST['idpersona'])?$_POST['idpersona']:0;
           
            $feriados = $db->query("SELECT * FROM per_kardex_personal kp WHERE kp.gestion_id=$id_gestion  AND kp.estado='A' AND persona_id=$persona")->fetch();// kp.tipo_kardex='felicitacion' AND
         
     
            
             echo json_encode($feriados); 
    }
        
    if($accion == "eliminar_kardex"){
            
        $id_componente = $_POST['id_componente']; 

        // verificamos su exite el id en tabla
        $regKardex = $db->from('per_kardex_personal')->where('id_kardex',$id_componente)->fetch_first();  
        // $bucarfile = $db->from('per_kardex_personal')->where('id_kardex',$id_kardex)->fetch_first();
        //en caso de si eliminarar en caso de no dara mensaje de error
        if($regKardex){
                 //QUEAHACER EN CASO DE NO ENCONTRAR MO FILE
            $file = $regKardex["adjunto_kardex"];
            if($file=='' || $file==null || $file==0){

            }
            $delete_dir = "files/cardexPersonal/".$file;
            //var_dump($delete_dir);exit();
            try{
                unlink($delete_dir);

            }catch(Exception $e){
                echo 'el archivo cambio de ubicacion.';
            }
            
            
            
            //NOELIMINAR $db->delete()->from('per_horarios')->where('id_horario', $id_horario)->limit(1)->execute(); 
          $esta=$db->query("UPDATE per_kardex_personal SET estado = 'I', usuario_modificacion = '".$_user['id_user']."', fecha_modificacion = '".date('Y-m-d')."' WHERE id_kardex = '".$id_componente."'")->execute();
            
            if ($esta){//$db->affected_rows) {
                    //registrarProceso('Se eliminó el horario con identificador número ' . $id_horario . '.',$db);
                    registrarProceso('Se eliminó kardex personal con identificador número ' ,$id_componente ,$db,$_location,$_user['id_user']);
                    echo 1;//'Eliminado Correctamente.';
            }else{
                    echo 2;//'No se pudo eliminar';
            }
        }
    }
   
        
}else {
	// Error 404
	require_once not_found();
	exit;
}

 

function validar($horarios){ 
        if (!$horarios)  {
            // Error 400 
            require_once bad_request();
            exit; 
         }
            return true; 
} 

function registrarProceso($detalle,$id_horario,$db,$_location,$user){//,$pros,$niv){
        $db->insert('sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso'  => date('H:i:s'),
                    'proceso'       => 'u',//$pros
                    'nivel'         => 'l',//$niv
                    'detalle'       => $detalle . $id_horario . '.',
                    'direccion'     => $_location,
                    'usuario_id'    => $user
                )); 
}
    // registrarProceso('Se eliminó el horario con identificador número ' . $id_horario . '.');//,'u','l');//u y l proceso y nivel con uso?


