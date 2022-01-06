<?php
$formato_textual = get_date_textual($_institution['formato']);
error_reporting(E_ALL);

function convertirHoraMinutos($hora){
	$v_HorasPartes = explode(":", $hora);
	$minutosTotales = ($v_HorasPartes[0] * 60) + $v_HorasPartes[1];
	return $minutosTotales;
}

function contrato($db, $id_empleado, $fecha) {
    $contrato = $db->query("select a.* from (select * from per_contratos where fecha_inicial <= '$fecha 23:59:59') a left join (select * from per_contratos where fecha_inicial <= '$fecha 23:59:59') b on a.empleado_id = b.empleado_id and a.fecha_inicial < b.fecha_inicial where b.fecha_inicial is null and a.empleado_id = '$id_empleado'")->fetch_first();
    return $contrato;
}

// Retorna las asistencias de un empleado en una fecha
function asistencias($db, $id_empleado, $fecha) {
    $asistencias = $db->from('per_asistencias')->where('empleado_id', $id_empleado)->where('fecha_asistencia', $fecha)->order_by('entrada', 'asc')->fetch();
    return $asistencias;
}

// Retorna los horarios de un empleado en una fecha
function horarios($db, $id_empleado, $fecha) {
    $horarios = $db->query("select a.horario_id as horarios from (select * from per_asignaciones where fecha_asignacion <= '$fecha 23:59:59') a left join (select * from per_asignaciones where fecha_asignacion <= '$fecha 23:59:59') b on a.empleado_id = b.empleado_id and a.fecha_asignacion < b.fecha_asignacion where b.fecha_asignacion is null and a.empleado_id = '$id_empleado'")->fetch_first();
    
    $horarios = explode(',', $horarios['horarios']);
    $horarios = $db->from('per_horarios')->where_in('id_horario', $horarios)->fetch();

    $horarios = corregir_horarios($fecha, $horarios);
    return $horarios;
}

function horarios2($db, $id_empleado, $fecha) {
    $horarios = $db->query("select a.horario_id as horarios from (select * from per_asignaciones where fecha_asignacion <= '$fecha 23:59:59') a left join (select * from per_asignaciones where fecha_asignacion <= '$fecha 23:59:59') b on a.empleado_id = b.empleado_id and a.fecha_asignacion < b.fecha_asignacion where b.fecha_asignacion is null and a.empleado_id = '$id_empleado'")->fetch_first();
    
    $horarios = explode(',', $horarios['horarios']);
    $horarios = $db->from('per_horarios')->where_in('id_horario', $horarios)->fetch();
    return $horarios;
}

// Retorna el salario de un empleado en una fecha
function salario($db, $id_empleado, $fecha) {
    $salario = $db->query("select a.salario from (select * from per_salarios where fecha_salario <= '$fecha 23:59:59') a left join (select * from per_salarios where fecha_salario <= '$fecha 23:59:59') b on a.empleado_id = b.empleado_id and a.fecha_salario < b.fecha_salario where b.fecha_salario is null and a.empleado_id = '$id_empleado'")->fetch_first();

    return ($salario) ? $salario['salario'] : 0;
}

function obtenerNombreDia($date){
    $days = array(1 => 'lun', 2 => 'mar', 3 => 'mie', 4 => 'jue', 5 => 'vie', 6 => 'sab', 7 => 'dom');
    return $days[date('N', strtotime($date))];
}

function adelantos($db, $id_empleado, $fecha) {
	$adelanto = $db->query("SELECT SUM(monto) as monto FROM per_adelantos WHERE empleado_id = '$id_empleado' AND fecha_adelanto<='$fecha 23:59:59'")->fetch_first();
	return ($adelanto) ? $adelanto['monto'] : 0;
}

// Corrige la fecha a formato datetime
function corregir_horarios($fecha, $horarios) {
    $dia = obtenerNombreDia($fecha);
    $nuevos = array();
    if ($horarios) {
        foreach ($horarios as $nro => $horario) {
            $dias = explode(',', $horario['dias']);
            if (in_array($dia, $dias)) {
                $fecha_inicial = $fecha;
                $entrada = $horario['entrada'];
                $salida = $horario['salida'];
                if ($salida < $entrada || $salida == '00:00:00') {
                    $fecha_final = add_day($fecha_inicial);
                    $fecha_entrada = $fecha_inicial . ' ' . $entrada;
                    $fecha_salida = $fecha_final . ' ' . $salida;               
                } else {
                    $fecha_entrada = $fecha_inicial . ' ' . $entrada;
                    $fecha_salida = $fecha_inicial . ' ' . $salida;
                }
                array_push($nuevos, array('entrada' => $fecha_entrada, 'salida' => $fecha_salida));
            } 
        }
    }
    return $nuevos; 
}

function obtenerFeriados($db,$fecha_interior_inicial,$fecha_interior_final){
    $feriados = $db->from('per_feriados')->between('fecha_feriado', $fecha_interior_inicial, $fecha_interior_final)->fetch();
    $feriados = array_column($feriados, 'fecha_feriado');// [0] => 2019-01-01
    return $feriados;
}

function laburable($db,$fecha,$horarios){
    $dia = obtenerNombreDia($fecha);
    $nuevos = array();
    if ($horarios) {
        foreach ($horarios as $nro => $horario) {
            $dias = explode(',', $horario['dias']);

            if (in_array($dia, $dias)) {
                return true;
            }
        }
    }
    return false;
}

function obtener_resultados($horarios, $asistencias) {
    
    $segmentos = array_merge($horarios, $asistencias);
    $entradas = array_column($segmentos, 'entrada');
    $salidas = array_column($segmentos, 'salida');
    $segmentos = array_merge($entradas, $salidas);
    $segmentos = array_diff($segmentos, array('0000-00-00 00:00:00'));
    // Elimina valores duplicados de un array
    $segmentos = array_unique($segmentos);
    sort($segmentos);
    $partes = array();
    $entrada = array_shift($segmentos);
    while ($segmentos) {
        $salida = array_shift($segmentos);
        $parte = array(
            'entrada' => $entrada,
            'salida' => $salida
        );
        array_push($partes, $parte);
        $entrada = $salida;
    }

    $estados        = array();
    $tiempos        = array();
    $horas_atraso   = 0;
    $horas_abandono = 0;
    $horas_trabajo  = 0;
    $horas_extra    = 0;
    $horas_descanso = 0;
    foreach ($partes as $parte) {
        //echo "entrada: ".$parte['entrada']." salida: ".$parte['salida']."<br>";
        $positivo = 0;
        $negativo = 0;
        $segundos = 0;
        foreach ($horarios as $horario) {
            if ($parte['entrada'] >= $horario['entrada'] && $parte['salida'] <= $horario['salida']) {
                $positivo = 1;
                break;
            }
        }
        foreach ($asistencias as $asistencia) {
            if ($parte['entrada'] >= $asistencia['entrada'] && $parte['salida'] <= $asistencia['salida']) {
                $negativo = 1;
                break;
            }
        }
        //echo $parte['entrada']." al ".$parte['salida'].") ".$positivo . $negativo."<br>";
        switch ($positivo . $negativo) {
            case '11': // horas trabajadas 04:24:00 ($segundos)
                $segundos = difference($parte['entrada'], $parte['salida']);
                $horas_trabajo = $horas_trabajo + convert_seconds($segundos);
                array_push($estados, 't');
                array_push($tiempos, $segundos);
                break;
            case '10': // horas de atraso 00:06:00 ($segundo)
                $segundos = difference($parte['entrada'], $parte['salida']);
                array_push($estados, 'n');
                array_push($tiempos, $segundos);
                break;
            case '01': // horas extra 00:05:00($segundo)
                $segundos = difference($parte['entrada'], $parte['salida']);
                $horas_extra = $horas_extra + convert_seconds($segundos);
                array_push($estados, 'e');
                array_push($tiempos, $segundos);
                break;
            case '00':
                $segundos = difference($parte['entrada'], $parte['salida']);
                $horas_descanso = $horas_descanso + convert_seconds($segundos);
                array_push($estados, 'd');
                array_push($tiempos, $segundos);
                break;
        }
    }
    
    array_unshift($estados, 'd');
    array_push($estados, 'd');
    array_unshift($tiempos, '00:00:00');
    array_push($tiempos, '00:00:00');
    $posicion = null;
    $grupo = null;
    $nn = strpos(implode('', $estados), 'nnx');
    $dd = strpos(implode('', $estados), 'ddx');
    if ($nn > 0) {
        $posicion = $nn;
    }
    if ($dd > 0) {
        $posicion = $dd;
    }
    if ($posicion == null) {
        $posicion = 1000000;
    }
    foreach ($estados as $nro => $estado) {
        if ($estado == 'n') {
            $grupo = $estados[$nro - 1] . $estados[$nro] . $estados[$nro + 1];
            switch ($grupo) {
                case 'dnt':
                    $horas_atraso = $horas_atraso + convert_seconds($tiempos[$nro]);
                    break;
                case 'dnn':
                    $horas_atraso = $horas_atraso + convert_seconds($tiempos[$nro]);
                    break;
                case 'tnd':
                    $horas_abandono = $horas_abandono + convert_seconds($tiempos[$nro]);
                    break;
                case 'tnt':
                    $horas_abandono = $horas_abandono + convert_seconds($tiempos[$nro]);
                    break;
                case 'nnd':
                    $horas_abandono = $horas_abandono + convert_seconds($tiempos[$nro]);
                    break;
                case 'dnd':
                    if ($nro < $posicion) {
                        $horas_atraso = $horas_atraso + convert_seconds($tiempos[$nro]);
                    } else {
                        $horas_abandono = $horas_abandono + convert_seconds($tiempos[$nro]);
                    }
                    break;
            }
        }
    }
    $resultados = array(
        'horas_atraso' => $horas_atraso,
        'horas_abandono' => $horas_abandono,
        'horas_trabajo' => $horas_trabajo,
        'horas_extra' => $horas_extra,
        'horas_descanso' => $horas_descanso
    );
    return $resultados;
}

$id_empleado            = clear($_POST['empleado']);
$id_sucursal            = $_POST['sucursal'];
$fecha_interior_inicial = date_encode($_POST['fecha_inicial']);
$fecha_interior_final   = date_encode($_POST['fecha_final']);


//$id_empleado=2;
$hoy = now();
		
$db->select("e.id_empleado,e.codigo,e.nombres,e.paterno,e.materno,GROUP_CONCAT(ei.institucion_id)")
            ->from('sys_empleados e')
            ->join('sys_empleado_instituciones ei', 'ei.empleado_id = e.id_empleado', 'left')
            ->group_by("e.id_empleado");

if (!empty($id_empleado)) {
	$db->where('e.id_empleado', $id_empleado);
}

if (!empty($id_sucursal)) {
    $db->where('ei.institucion_id',$id_sucursal);
}

$empleados = $db->where('activo','s')->order_by('cargo_id, paterno, materno,nombres')->fetch();
try {
    $lista_empleados=[];
    foreach ($empleados as $empleado) {
        $total_asistencia       = 0;
        $total_extraordinario   = 0;
        $total_faltas           = 0;
        $atrasos                = 0;
        $extras                 = 0;
        $atrasos_extraordinario = 0;
        $salario = [];

        for($f = $fecha_interior_inicial;$f <= $fecha_interior_final;$f = date("Y-m-d", strtotime($f ."+ 1 days"))) {
            
            //if ($f >= $fecha_interior_inicial  && $f <= $fecha_interior_final) :
                $contrato    = contrato($db, $empleado['id_empleado'], $f);
                $asistencias = asistencias($db, $empleado['id_empleado'], $f);//[0] => Array ( [id_asistencia] => 1 [empleado_id] => 2 [fecha_asistencia] => 2019-01-01 [entrada] => 2019-01-01 00:00:00 [salida] => 2019-01-01 00:00:00 [estado] => p ) )
                $horarios    = horarios($db, $empleado['id_empleado'], $f);
                $horarios2   = horarios2($db, $empleado['id_empleado'], $f);
                
                $salario     = salario($db, $empleado['id_empleado'], $f);
                $adelanto    = adelantos($db, $empleado['id_empleado'], $f);
                $presencias  = array();
         
                if ($contrato && $f >= (($contrato['fecha_inicial'] && $f <= $contrato['fecha_final']) || ($f >= $contrato['fecha_inicial'] && $contrato['fecha_final'] == '0000-00-00'))) :
                    if (!in_array($f, obtenerFeriados($db,$fecha_interior_inicial,$fecha_interior_final))) {
                        
                        if (laburable($db,$f,$horarios2)) {
                            if ($asistencias) {
                                $sw = 0;
                                foreach ($asistencias as $key => $asistencia) {
                                    array_push($presencias, array('entrada' => $asistencia['entrada'], 'salida' => $asistencia['salida']));
                                }
                                
                                $total_asistencia++;
                                $resultados = obtener_resultados($horarios, $presencias);
                                $atrasos = $atrasos + $resultados['horas_atraso'];
                                $extras = $extras + $resultados['horas_extra'];
                            } else {
                                $total_faltas++;
                            }
                        } 
                    } else {
                        $sw = 0;
                        foreach ($asistencias as $key => $asistencia) {
                            array_push($presencias, array('entrada' => $asistencia['entrada'], 'salida' => $asistencia['salida']));
                            if (date('H:i:s',strtotime($asistencia['entrada'])) != '00:00:00' || date('H:i:s',strtotime($asistencia['salida'])) != '00:00:00') {
                               $sw=1;
                            } else {
                                $sw = 0;
                            }
                        }
                        if ( $sw == 1) {
                            $resultados = obtener_resultados($horarios, $presencias);
                            $total_extraordinario++;
                            $atrasos_extraordinario = $atrasos_extraordinario + $resultados['horas_atraso'];
                        } 
                    }
                endif;
            //endif;
        }

        $aux['id_empleado']      = $empleado['id_empleado'];
        $aux['codigo']           = $empleado['codigo'];
        $aux['nombres']          = $empleado['nombres'];
        $aux['paterno']          = $empleado['paterno'];
        $aux['asistencia']       = $total_asistencia;
        $aux['falta']            = $total_faltas;
        $aux['atraso']           = convert_time($atrasos);
        $aux['extra']            = convert_time($extras);
        $aux['salario']          = $salario;
        $aux['total_ingreso']    = $salario;
        $aux['total_faltas']     = number_format(($salario/22)*$aux['falta'], 2, '.', "");
        $aux['total_atrasos']    = convertirHoraMinutos($aux['atraso']);
        $aux['adelanto']         = !is_null($adelanto) ? $adelanto : 0;
        $aux['total_descuento']  = number_format($aux['total_faltas'] + $aux['total_atrasos'] + $adelanto,2,'.',"");
        $aux['liquido']          = number_format(($aux['salario'] - $aux['total_descuento']),2,'.',"");
        $aux['fecha_asistencia'] = $fecha_interior_inicial;
        $lista_empleados[]       = $aux;
    }
    $response = ['success'=>true,'empleados' => $lista_empleados,'fecha_inicial'=>$fecha_interior_inicial,'fecha_final'=>$fecha_interior_final];
    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(['success' => false,'msg'=>$e->getMessage()]);
}





?>