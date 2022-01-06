<?php

    
    $contratos = $db->query("SELECT * FROM ins_permisos WHERE estado = 'A'")->fetch();
    //var_dump($contratos);die;

 $informacion_estudiante = $db->query("SELECT *,pers.nombres as nombre_familiar,
  group_concat(c.nombre_materia) AS nombre_materias
  FROM ins_permisos iper
INNER JOIN ins_estudiante  e ON e.id_estudiante=iper.estudiante_id
INNER JOIN sys_persona  per ON per.id_persona=e.persona_id
INNER JOIN sys_users su ON su.persona_id = per.id_persona

INNER JOIN ins_familiar ifa ON ifa.id_familiar = iper.familiar_id

INNER JOIN sys_persona  pers ON pers.id_persona=ifa.persona_id




INNER JOIN ext_curso_asignacion eca ON eca.id_curso_asignacion = iper.materia_id
INNER JOIN ext_curso ec ON ec.id_curso = eca.curso_id
left join pro_materia c on find_in_set(c.id_materia, iper.materia_id)
WHERE iper.estado = 'A'
group by
    iper.id_permiso 
  ")->fetch(); 


   foreach ($informacion_estudiante as $key => $value) {


      //Tu variable $perfil['experiencia']
$data = $value['materia_id'];

//Divide una cadena.
$cadena = explode(",", $data);      

//Recorrer array
$materias = ''; 

$extras = ''; 
foreach ($cadena as $valor) {    

  if (strpos($valor, 'e') !== false) {
   $trimmed = rtrim($valor, "e");


   $materias_extra = $db->query("SELECT * FROM ext_curso WHERE id_curso='$trimmed'")->fetch_first();

   $extras .= $materias_extra['nombre_curso'].',';
}else{


    $materias_ = $db->query("SELECT * FROM pro_materia WHERE id_materia='$valor'")->fetch_first();

   $materias .= $materias_['nombre_materia'].',';

}

   

     
}
     $value['nombre_materias']=$extras.$materias;
      // code...
    }
     // code...
   


    echo json_encode($informacion_estudiante);
        



?>