<?php 
set_time_limit(600);

 /**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */
// Verifica la peticion post

//echo "zdhf";

if (is_post()) {
    if(isset($_POST['anio']) && isset($_POST['mes']) ){
        
        $anio = (isset($_POST['anio'])) ? clear($_POST['anio']) : 0;
        $mes = (isset($_POST['mes'])) ? clear($_POST['mes']) : 0;
        
        $persona = $db->query(" SELECT *
                                FROM rrhh_planilla_pago
                                WHERE mes='".$mes."' AND anio='".$anio."'
                                ")->fetch_first();     

        switch($mes){
            case "1":   $mesx="01";     $dayx="31";   break;
            case "2":   $mesx="02";     $dayx="28";     break;
            case "3":   $mesx="03";     $dayx="31";     break;
            case "4":   $mesx="04";     $dayx="30";     break;
            case "5":   $mesx="05";     $dayx="31";     break;
            case "6":   $mesx="06";     $dayx="30";     break;
            case "7":   $mesx="07";     $dayx="31";     break;
            case "8":   $mesx="08";     $dayx="31";     break;
            case "9":   $mesx="09";     $dayx="30";     break;
            case "10":   $mesx="10";     $dayx="31";     break;
            case "11":   $mesx="11";     $dayx="30";     break;
            case "12":   $mesx="12";     $dayx="31";     break;
        }

        $fecha_interior_inicial = $anio."-".$mesx."-01";
        $fecha_interior_final = $anio."-".$mesx."-".$dayx;

        // Obtiene el dia inicial 2 (lun=1,mar=2.....)
        $dia_inicial = date('N', strtotime($fecha_interior_inicial));
        //Obtiene el dia final 4 (lun=1, mar=2,mie=3,jue=4....)
        $dia_final = date('N', strtotime($fecha_interior_final));
        // fecha inicial externo 2018-12-31
        $fecha_exterior_inicial = strtotime('-' . (intval($dia_inicial) - 1) . ' day', strtotime($fecha_interior_inicial));
        $fecha_exterior_inicial = date('Y-m-d', $fecha_exterior_inicial);
        //fecha final externo 2019-02-3
        $fecha_exterior_final = strtotime('+' . (7 - intval($dia_final)) . ' day', strtotime($fecha_interior_final));
        $fecha_exterior_final = date('Y-m-d', $fecha_exterior_final);

        $feriadosBD =  $db->from('per_feriados')
                        ->between('fecha_feriado', $fecha_interior_inicial, $fecha_interior_final)
                        ->fetch();

        $feriados = array_column($feriadosBD, 'fecha_feriado');

        

        $configBD =  $db->from('rhh_configuracion')
                        ->fetch_first();

        $rango_horario = $configBD['rango_horario'];
        $atraso_time = $configBD['tiempo_atraso'];
        $atraso_perc = $configBD['porc_atraso'];
        $falta_perc = $configBD['porc_falta'];



        //echo "999";



        if($persona){ // si existe algun registro ya no se vuelve a crear
            redirect('?/rrhh-planilla-pagos/planilla-mensual/'.$anio.'/'.$mes.'');
        }
        else{            
            $res = $db->query(" SELECT a.*
                                FROM per_asignaciones as a 
                              
                                WHERE (
                                        (YEAR(fecha_inicio)<".$anio.") OR
                                        (YEAR(fecha_inicio)=".$anio." AND MONTH(fecha_inicio)<=".$mes.")
                                    )
                                    AND 
                                    (
                                        (YEAR(fecha_final)>".$anio.") OR
                                        (YEAR(fecha_final)=".$anio." AND MONTH(fecha_final)>=".$mes.")
                                    )
                                    AND 
                                    estado='A'
                                ")->fetch();
            
            $nro=0;
            foreach ($res as $rxx) { 
                            
                $id_persona = $rxx['persona_id'];
                //echo "<br>";
                $atraso_acumulado=0;

                $faltas = 0; 
                $total_asistencia=0;

                $hoy=date("Y-m-d");

                $fecha_actual= $anio."-".$mesx."-01";

                //echo "<br>";
                //echo "persona:".$id_persona;
                
                while ($fecha_actual <= $fecha_exterior_final){
                
                    //echo "<br>";
                    //echo $fecha_actual;
                                           
                    if ($fecha_interior_inicial <= $fecha_actual && $fecha_actual <= $fecha_interior_final){
                        

                        $fecha = $fecha_actual; //5 agosto de 2004 por ejemplo 
                        $fechats = strtotime($fecha); //a timestamp
                        //el parametro w en la funcion date indica que queremos el dia de la semana
                        //lo devuelve en numero 0 domingo, 1 lunes,....
                        switch (date('w', $fechats)){
                            case 0: $dey="dom"; break;
                            case 1: $dey="lun"; break;
                            case 2: $dey="mar"; break;
                            case 3: $dey="mie"; break;
                            case 4: $dey="jue"; break;
                            case 5: $dey="vie"; break;
                            case 6: $dey="sab"; break;
                        }  

                        $contrato = contrato($db, $id_persona, $fecha_actual);
                        $asistencias = asistencias($db, $id_persona, $fecha_actual);                            
                        $horarios = horarios($db, $id_persona, $fecha_actual,$dey); 
                        $salario = salario($db, $id_persona, $fecha_actual); 
                        $adelanto = adelantos($db, $id_persona, $fecha_actual); 
                        $presencias = array(); 

                        if  ($contrato && 
                            (
                                ($contrato['fecha_inicio'] <= $fecha_actual && $fecha_actual <= $contrato['fecha_final']) || 
                                ($contrato['fecha_inicio'] <= $fecha_actual && $contrato['fecha_final'] == '0000-00-00')
                            )
                            ){




                            


                            if ($fecha_actual <= $hoy){
                                if ($horarios){
                                    if (!in_array($fecha_actual, $feriados)){
                                        if ($asistencias){
                                            foreach ($horarios as $rcc) { 
                                                //echo $rcc['entrada']." ___ ".$rcc['salida']." ___ ".$rcc['dias'];

                                                $hh_hor=explode(":",$rcc['entrada']);
                                                $hh_horario_inicio=intval($hh_hor[0])*60+intval($hh_hor[1]);

                                                $hh_hor=explode(":",$rcc['salida']);
                                                $hh_horario_salida=intval($hh_hor[0])*60+intval($hh_hor[1]);
                                                
                                                $hh_horario_inicio_rango=$hh_horario_inicio-$rango_horario;
                                                $hh_horario_salida_rango=$hh_horario_salida+$rango_horario;
                                                
                                                $fecha = $fecha_actual; //5 agosto de 2004 por ejemplo 
                                                $fechats = strtotime($fecha); //a timestamp
                                                //el parametro w en la funcion date indica que queremos el dia de la semana
                                                //lo devuelve en numero 0 domingo, 1 lunes,....
                                                switch (date('w', $fechats)){
                                                    case 0: $dey="dom"; break;
                                                    case 1: $dey="lun"; break;
                                                    case 2: $dey="mar"; break;
                                                    case 3: $dey="mie"; break;
                                                    case 4: $dey="jue"; break;
                                                    case 5: $dey="vie"; break;
                                                    case 6: $dey="sab"; break;
                                                }  
                                                $ArrayH=explode(",",$rcc['dias']);

                                                for($iix=0;$iix<count($ArrayH);$iix++){
                                                    
                                                    
                                                    if($ArrayH[$iix]==$dey){
                                                        $total_asistencia++; 
                                                        //echo '<b class="text-success">Asistencia</b>';

                                                        foreach ($asistencias as $a_res) {                                     
                                                            $hh_ing=explode(" ",$a_res['entrada']);
                                                            $hh_ing222=explode(":",$hh_ing[1]);
                                                            $hh_ingreso=intval($hh_ing222[0])*60+intval($hh_ing222[1]);
                                                            //echo $hh_ingreso.",,,";

                                                            $hh_sal=explode(" ",$a_res['salida']);
                                                            $hh_sal222=explode(":",$hh_sal[1]);
                                                            $hh_salida=intval($hh_sal222[0])*60+intval($hh_sal222[1]);
                                                            //echo $hh_salida.",,,";

                                                            //echo $hh_horario_inicio.",,,";
                                                            //echo $hh_horario_salida.",,,";

                                                            //echo $hh_horario_inicio_rango.",,,";
                                                            //echo $hh_horario_salida_rango.",,,";

                                                            if($hh_horario_inicio_rango<=$hh_ingreso && $hh_ingreso<$hh_horario_salida){
                                                                if($hh_horario_inicio<$hh_ingreso){
                                                                    $atraso=$hh_ingreso-$hh_horario_inicio;
                                                                    echo "atraso de ".$atraso;
                                                                    $atraso_acumulado=$atraso_acumulado+$atraso;
                                                                }
                                                            }
                                                            //echo "---".$a_res['salida'];
                                                            else{ 
                                                                $faltas++; 
                                                                ?>
                                                                <b class="text-danger">Falta</b>
                                                            <?php 
                                                                //echo $hh_horario_inicio_rango."<=".$hh_ingreso." - - - ".$hh_ingreso."<".$hh_horario_salida;
                                                            }
                                                        }
                                                    }
                                                    else{
                                                        
                                                    }
                                                }
                                            }
                                        }else{ 
                                            $faltas++; 
                                            ?>
                                            <b class="text-danger">Falta</b>
                                        <?php 
                                        }
                                    }else{ 
                                    ?>
                                            <b class="text-warning">Feriado</b>
                                    <?php 
                                    }
                                }else{ 
                                ?>
                                    <b class="text-warning">Descanso</b>
                                <?php 
                                } 
                            }else{
                            ?>
                                    <b class="text-info">Próximamente</b>
                            <?php 
                            } 
                        }else{ 
                        ?>
                            <b>Sin contrato</b>
                        <?php 
                        }                        
                    }    
                    $fecha_actual = add_day($fecha_actual); 
                }


                $atraso=floor($atraso_acumulado/$atraso_time);
                


                $atrasoT=$atraso_perc*$atraso*$rxx['sueldo_por_hora'];
                



                $faltasT=$falta_perc*$faltas*$rxx['sueldo_por_hora'];
                
                $resultado = array(
                    'mes' => $mes,
                    'anio' => $anio,
                    'asignacion_id' => $rxx['id_asignacion'],
                    'cargo' => $rxx['cargo_id'],
                    'sueldo_basico' => $rxx['sueldo_total'],
                    'dias_laborales' => 30,
                    'faltas'=> $faltasT,
                    'atrasos' => $atrasoT,
                    'nro_falta'=> $faltas,
                    'nro_atraso' => $atraso_acumulado,
                    'extras' => 0
                );            
                $db->insert('rrhh_planilla_pago', $resultado);

                //echo "1";
                //redirect('?/rrhh-planilla-pagos/planilla-mensual/'.$anio.'/'.$mes.'');

                /*
                echo "<br>Nro de Faltas: ".$faltas;
                echo "<br>Nro de Asistencia: ".$total_asistencia;
                echo "<br>";
                */                
            }
            redirect('?/rrhh-planilla-pagos/planilla-mensual/'.$anio.'/'.$mes.'');            
        }
    } else {
        // Redirecciona la pagina
        redirect('?/rrhh-planilla-pagos/listar');
    }
} else {
    // Error 404
    require_once not_found();
    exit;
}

function convertirHoraMinutos($hora){
    $v_HorasPartes = explode(":", $hora);
    $minutosTotales = ($v_HorasPartes[0] * 60) + $v_HorasPartes[1];
    return $minutosTotales;
}

// Retorna el contrato de un empleado en una fecha
function contrato($db, $id_empleado, $fecha) {
    $contrato = $db->query("SELECT * 
                            FROM per_asignaciones 
                            WHERE fecha_inicio <= '$fecha' AND fecha_final >= '$fecha' AND persona_id = '$id_empleado'
                            ")->fetch_first();
    return $contrato;
}

// Retorna las asistencias de un empleado en una fecha
function asistencias($db, $id_empleado, $fecha) {
    $asistencias =   $db->from('per_asistencias')
                        ->where('asignacion_id', $id_empleado)
                        ->where('fecha_asistencia', $fecha)
                        ->order_by('entrada', 'asc')
                        ->fetch();
    
    return $asistencias;
}

// Retorna los horarios de un empleado en una fecha
function horarios($db, $id_empleado, $fecha, $day) {
    $horarios = $db->query("select horario_id as horarios 
                            from per_asignaciones 
                            where 
                                (
                                    (fecha_inicio <= '$fecha' AND fecha_final >= '$fecha') 
                                    OR 
                                    (fecha_inicio <= '$fecha' AND fecha_final is null) 
                                ) 
                                AND persona_id = '$id_empleado'")->fetch_first();
    
    // echo "<pre>";
    // var_dump($horarios);
    // echo "</pre>";
    // exit();

    $horarios222 = explode(',', $horarios['horarios']);
    $horarios333 =  $db->from('per_horarios')
                    ->where('dias LIKE', "%".$day."%")
                    ->where_in('id_horario', $horarios222)
                    ->fetch();
    /*
        $horarios333 =  $db->query('SELECT * 
                                FROM per_horarios
                                WHERE id_horario IN("'.$horarios222[0].'")
                                AND dias LIKE "%'.$day.'%"')
                    ->fetch();
    
    */
    // echo "<pre>";
    // var_dump($horarios);
    // echo "</pre>";
    // exit();
    //$horarios = corregir_horarios($fecha, $horarios);
    
    return $horarios333;
}

// Retorna el salario de un empleado en una fecha
function salario($db, $id_empleado, $fecha) {
    $salario = $db->query(" select a.salario 
                            from (
                                select * 
                                from per_salarios 
                                where fecha_salario <= '$fecha 23:59:59'
                            ) a 
                            left join (
                                select * 
                                from per_salarios 
                                where fecha_salario <= '$fecha 23:59:59'
                            ) b on a.empleado_id = b.empleado_id AND a.fecha_salario < b.fecha_salario 
                            where b.fecha_salario is null and a.empleado_id = '$id_empleado'")->fetch_first();

    return ($salario) ? $salario['salario'] : 0;
}

function adelantos($db, $id_empleado, $fecha) {
    $adelanto = $db->query("SELECT SUM(monto) as monto 
                            FROM per_adelantos 
                            WHERE asignacion_id = '$id_empleado' AND fecha_adelanto<='$fecha 23:59:59'
                            ")->fetch_first();
    return ($adelanto) ? $adelanto['monto'] : 0;
}
?>