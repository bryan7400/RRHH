<?php

    
    $contratos = $db->query("SELECT * FROM ins_permisos WHERE estado = 'A'")->fetch();
    //var_dump($contratos);die;

 $informacion_estudiante = $db->query("SELECT *,pers.nombres as nombre_familiar ,iper.fecha_inicio as fecha_inicios ,
  group_concat(c.hora_ini, ' a ', c.hora_fin, ' ') AS horarios_materias
  FROM ins_permisos iper
INNER JOIN ins_estudiante  e ON e.id_estudiante=iper.estudiante_id
INNER JOIN sys_persona  per ON per.id_persona=e.persona_id
left JOIN sys_users su ON su.persona_id = per.id_persona

left JOIN ins_familiar ifa ON ifa.id_familiar = iper.familiar_id

left JOIN sys_persona  pers ON pers.id_persona=ifa.persona_id




left JOIN ext_curso_asignacion eca ON eca.id_curso_asignacion = iper.materia_id
left JOIN ext_curso ec ON ec.id_curso = eca.curso_id
left join ins_horario_dia c on find_in_set(c.id_horario_dia, iper.horarios_id)
WHERE iper.estado = 'A'
group by
    iper.id_permiso 
  ")->fetch(); 


$array_permisos = array();
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

   $extras .= $materias_extra['nombre_curso'].', ';
}else{


    $materias_ = $db->query("SELECT * FROM pro_materia WHERE id_materia='$valor'")->fetch_first();

   $materias .= $materias_['nombre_materia'].', ';

}

   

     
}
     $value['materia_id']=$materias.$extras;
      // code...
    




     $permisos = array(
    'id_permiso' => $value['id_permiso'],
    'estudiante_id' => $value['estudiante_id'],
    'horarios_materias' => $value['horarios_materias'],
    'familiar_id' => $value['familiar_id'],
    'contrato_id' => $value['contrato_id'],
    'materia_id' => $value['materia_id'],
    'categoria' => $value['categoria'],
    'horarios_id' => $value['horarios_id'],
    'username' => $value['username'],
    'motivo' => $value['motivo'],
    'archivo_documento' => $value['archivo_documento'],
    'fecha_inicio' => $value['fecha_inicios'],
    'fecha_final' => $value['fecha_final'],
    'tipo_permiso' => $value['tipo_permiso'],
    'seguimiento_permiso' => $value['seguimiento_permiso'],
    'grupo_permiso' => $value['grupo_permiso'],
    'estado' => $value['estado'],
    'persona_id' => $value['persona_id'],
    'nombres' => $value['nombres'],
    'primer_apellido' => $value['primer_apellido'],
    'segundo_apellido' => $value['segundo_apellido'],

    'numero_documento' => $value['numero_documento'],
    'horario_dia' => $value['horario_dia'],
    'fecha_fin' => $value['fecha_fin'],
    'id_curso' => $value['id_curso'],
    'nombre_curso' => $value['nombre_curso'],
    'nombre_familiar' => $value['nombre_familiar']

    );  

                    array_push($array_permisos, $permisos);
    
      // code...
    }
     // code...
   


    echo json_encode($array_permisos);
        



?>