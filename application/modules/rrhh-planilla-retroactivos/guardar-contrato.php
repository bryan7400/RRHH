<?php 
set_time_limit(600);

// Verifica la peticion post
if (is_post()) {
    if(isset($_POST['anio']) ){
        
        $anio = (isset($_POST['anio'])) ? clear($_POST['anio']) : 0;
        echo $incremento1 = (isset($_POST['incremento1'])) ? clear($_POST['incremento1']) : 0;
        echo $incremento2 = (isset($_POST['incremento2'])) ? clear($_POST['incremento2']) : 0;

        $config = $db->query("  SELECT *
                                FROM rhh_configuracion
                                INNER JOIN  ins_gestion ON id_gestion=gestion_id 
                                WHERE gestion='".$anio."'
                                ")->fetch_first();     
        
        $persona = $db->query(" SELECT *
                                FROM rrhh_retroactivos
                                WHERE anio='".$anio."'
                                ")->fetch_first();     
        
        if($persona){ // si existe algun registro ya no se vuelve a crear
            redirect('?/rrhh-planilla-retroactivos/planilla-retroactivos/'.$anio.'');
        }
        else{            
            $res = $db->query(" SELECT a.*, c.*
                                FROM per_asignaciones as a 
                                INNER JOIN per_cargos c ON c.id_cargo=a.cargo_id 
                                WHERE (
                                        (YEAR(fecha_inicio)<".$anio.") OR
                                        (YEAR(fecha_inicio)=".$anio." AND MONTH(fecha_inicio)<=5)
                                    )
                                    AND 
                                    (
                                        (YEAR(fecha_final)>".$anio.") OR
                                        (YEAR(fecha_final)=".$anio." AND MONTH(fecha_final)>=5)
                                    )
                                    AND 
                                    a.estado='A'
                                ")->fetch();
            
            $nro=0;
            foreach ($res as $rxx) { 
                            
                //echo $id_persona = $rxx['persona_id'];
                //echo "<br>";
                
                $tiempo_trabajado=0;
                $fecha_actual= $anio."-05-01";

                $fechaInicio=$rxx['fecha_inicio'];                
                $vector=explode("-",$fechaInicio);

                if( intval($vector[0])<$anio ){
                    $tiempo=4;
                }
                else{
                    $tiempo=4-intval($vector[1]);
                    $tiempo=$tiempo+((30-intval($vector[2])+1)/30);
                }

                //echo $config['basico'];

                if( $rxx['sueldo_total']==$config['basico'] ){
                    $basico=$config['basico'];
                    $incremento=$incremento1;
                    $sueldo=$basico*$incremento*$tiempo/100;
                }
                else{
                    $basico=$rxx['sueldo_total'];
                    $incremento=$incremento2;
                    $sueldo=$basico*$incremento*$tiempo/100;
                }

                $resultado = array(
                    'anio'     => $anio,
                    'asignacion_id'=> $rxx['id_asignacion'],
                    'cargo'    => $rxx['cargo'],
                    'sueldo'    => $sueldo,
                    'incremento' => $incremento
                );            
                $db->insert('rrhh_retroactivos', $resultado);
            }
            redirect('?/rrhh-planilla-retroactivos/planilla-retroactivos/'.$anio.'');
        }
    } else {
        // Redirecciona la pagina
        redirect('?/rrhh-planilla-retroactivos/listar');
    }
} else {
    // Error 404
    require_once not_found();
    exit;
}

?>