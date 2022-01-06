<?php

// Obtiene la fecha de hoy
$hoy = now();

// Obtiene las fechas inicial y final
$fecha_inicial = str_replace('/', '-', first_month_day($hoy, $_institution['formato']));
$fecha_final = str_replace('/', '-', last_month_day($hoy, $_institution['formato']));

// Obtiene los formatos
$formato_textual = get_date_textual($_institution['formato']);
$formato_numeral = get_date_numeral($_institution['formato']);

// Obtiene la fecha de ayer
$ayer = remove_day($hoy);

// Verifica si existen los parametros
if (sizeof($params) == 3) {
	// Verifica el tipo de los parametros
	if (!is_numeric($params[0]) || !is_date($params[1]) || !is_date($params[2])) {
		// Redirecciona la pagina
		redirect('?/resumenes/imprimir/0/' . $fecha_inicial . '/' . $fecha_final);
	}
} else {
	// Redirecciona la pagina
	redirect('?/resumenes/imprimir/0/' . $fecha_inicial . '/' . $fecha_final);
}

// Obtiene los parametros
$id_empleado = intval($params[0]);
$fecha_interior_inicial = date_encode($params[1]);
$fecha_interior_final = date_encode($params[2]);

// Obtiene los dias inicial y final
$dia_inicial = date('N', strtotime($fecha_interior_inicial));
$dia_final = date('N', strtotime($fecha_interior_final));

// Obtiene los extremos inicial y final
$fecha_exterior_inicial = strtotime('-' . (intval($dia_inicial) - 1) . ' day', strtotime($fecha_interior_inicial));
$fecha_exterior_inicial = date('Y-m-d', $fecha_exterior_inicial);
$fecha_exterior_final = strtotime('+' . (7 - intval($dia_final)) . ' day', strtotime($fecha_interior_final));
$fecha_exterior_final = date('Y-m-d', $fecha_exterior_final);

// Obtiene la fecha puntero
$fecha_actual = $fecha_exterior_inicial;

// Obtiene los empleados
$empleados = $db->select('e.*, p.procedencia, c.cargo')->from('sys_empleados e')->join('gen_procedencias p', 'e.procedencia_id = p.id_procedencia', 'left')->join('per_cargos c', 'e.cargo_id = c.id_cargo', 'left')->where('e.activo', 's')->order_by('c.cargo asc, e.nombres asc, e.paterno asc, e.materno asc')->fetch();

// Obtiene el empleado
$empleado = $db->select('e.*, p.procedencia, c.cargo')->from('sys_empleados e')->join('gen_procedencias p', 'e.procedencia_id = p.id_procedencia', 'left')->join('per_cargos c', 'e.cargo_id = c.id_cargo', 'left')->where('e.id_empleado', $id_empleado)->fetch_first();

// Obtiene los feriados
$feriados = $db->from('per_feriados')->between('fecha_feriado', $fecha_interior_inicial, $fecha_interior_final)->fetch();
$feriados = array_column($feriados, 'fecha_feriado');

// Obtiene el tamano de filas de relleno
$tamano = $db->query("select ifnull(max(a.tamano), 0) as tamano from (select fecha_asistencia, count(fecha_asistencia) as tamano from per_asistencias where empleado_id = '$id_empleado' and fecha_asistencia between '$fecha_interior_inicial' and '$fecha_interior_final' group by fecha_asistencia) a")->fetch_first();
$tamano = $tamano['tamano'];

// Define variables
$faltas = 0;
$atrasos = 0;

// Obtiene los permisos
$permiso_cambiar = in_array('cambiar', $_views);

// Retorna el contrato de un empleado en una fecha
function contrato($db, $id_empleado, $fecha) {
	$contrato = $db->query("select a.* from (select * from per_contratos where fecha_contrato <= '$fecha 23:59:59') a left join (select * from per_contratos where fecha_contrato <= '$fecha 23:59:59') b on a.empleado_id = b.empleado_id and a.fecha_contrato < b.fecha_contrato where b.fecha_contrato is null and a.empleado_id = '$id_empleado'")->fetch_first();
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

// Retorna el salario de un empleado en una fecha
function salario($db, $id_empleado, $fecha) {
	$salario = $db->query("select a.salario from (select * from per_salarios where fecha_salario <= '$fecha 23:59:59') a left join (select * from per_salarios where fecha_salario <= '$fecha 23:59:59') b on a.empleado_id = b.empleado_id and a.fecha_salario < b.fecha_salario where b.fecha_salario is null and a.empleado_id = '$id_empleado'")->fetch_first();
	return ($salario) ? $salario['salario'] : 0;
}

// Corrige la fecha a formato datetime
function corregir_horarios($fecha, $horarios) {
	$dia = get_dayname($fecha);
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

// Retorna los resultados del dia
function obtener_resultados($horarios, $asistencias) {
	$segmentos = array_merge($horarios, $asistencias);
	$entradas = array_column($segmentos, 'entrada');
	$salidas = array_column($segmentos, 'salida');
	$segmentos = array_merge($entradas, $salidas);
	$segmentos = array_diff($segmentos, array('0000-00-00 00:00:00'));
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
	$estados = array();
	$tiempos = array();
	$horas_atraso = 0;
	$horas_abandono = 0;
	$horas_trabajo = 0;
	$horas_extra = 0;
	$horas_descanso = 0;
	foreach ($partes as $parte) {
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
		switch ($positivo . $negativo) {
			case '11':
				$segundos = difference($parte['entrada'], $parte['salida']);
				$horas_trabajo = $horas_trabajo + convert_seconds($segundos);
				array_push($estados, 't');
				array_push($tiempos, $segundos);
				break;
			case '10':
				$segundos = difference($parte['entrada'], $parte['salida']);
				array_push($estados, 'n');
				array_push($tiempos, $segundos);
				break;
			case '01':
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

?>
<?php require_once show_template('header-site'); ?>
<style>
body {
	margin: 60px;
}
.table th, .table td {
	border-style: solid;
	border-width: 1px;
	border-color: #222 !important;
	padding: 1px 6px !important;
}
</style>
<h1 class="text-center">Reporte de asistencia</h1>
<h2 class="text-center"><?= escape($empleado['nombres'] . ' ' . $empleado['paterno'] . ' ' . $empleado['materno']); ?></h2>
<table width="100%">
	<tr>
		<td width="80%">
			<table class="table table-condensed">
				<tr class="danger">
					<th><h4 class="margin-none text-center"><b>#</b></h4></th>
					<th><h4 class="margin-none text-center"><b>Día</b></h4></th>
					<th><h4 class="margin-none text-center"><b>Fecha</b></h4></th>
					<th><h4 class="margin-none text-center"><b>Estado</b></h4></th>
					<th><h4 class="margin-none text-center"><b>Atrasos</b></h4></th>
					<th><h4 class="margin-none text-center"><b>Observaciones</b></h4></th>
				</tr>
				<?php $numero = 1; ?>
				<?php while ($fecha_actual <= $fecha_exterior_final) : ?>
					<tr>
							<?php if ($fecha_interior_inicial <= $fecha_actual && $fecha_actual <= $fecha_interior_final) : ?>
							<?php $contrato = contrato($db, $id_empleado, $fecha_actual); ?>
							<?php $asistencias = asistencias($db, $id_empleado, $fecha_actual); ?>
							<?php $horarios = horarios($db, $id_empleado, $fecha_actual); ?>
							<?php $salario = salario($db, $id_empleado, $fecha_actual); ?>
							<?php $presencias = array(); ?>
							<td><?= $numero; ?></td>
							<td><?= upper(month_day($fecha_actual)); ?></td>
							<td><?= date_decode($fecha_actual, $_institution['formato']); ?></td>
							<td>
							<?php if ($contrato && (($contrato['fecha_inicial'] <= $fecha_actual && $fecha_actual <= $contrato['fecha_final']) || ($contrato['fecha_inicial'] <= $fecha_actual && $contrato['fecha_final'] == '0000-00-00'))) : ?>
								<?php if ($fecha_actual <= $hoy) : ?>
									<?php if ($horarios) : ?>
										<?php if (!in_array($fecha_actual, $feriados)) : ?>
											<?php if ($asistencias) : ?>
											<b>Asistencia</b>
											<?php else : ?>
											<?php $faltas = $faltas + 1; ?>
											<b>Falta</b>
											<?php endif ?>
										<?php else : ?>
										<b>Feriado</b>
										<?php endif ?>
									<?php else : ?>
									<b>Descanso</b>
									<?php endif ?>
								<?php else : ?>
								<b>Próximamente</b>
								<?php endif ?>
							<?php else : ?>
							<b>Sin contrato</b>
							<?php endif ?>
							</td>
									<?php if ($asistencias) : ?>
									<?php endif ?>
								<?php if ($asistencias) : ?>
									<?php foreach ($asistencias as $nro => $asistencia) : ?>
											<?php array_push($presencias, array('entrada' => $asistencia['entrada'], 'salida' => $asistencia['salida'])); ?>
											
											<?php endforeach ?>
								<?php $resultados = obtener_resultados($horarios, $presencias); ?>
								
								<?php $atrasos = $atrasos + $resultados['horas_atraso']; ?>
								<td>
									<?= convert_time($resultados['horas_atraso']); ?>
								</td>
								<td></td>
								<?php else : ?>
									<td></td>
									<td></td>
								<?php endif ?>
							<?php else : ?>
								<?php $numero = $numero - 1; ?>
							
							<?php endif ?>
							<?php if ($numero % 7 == 0) : ?>
								<?php if ($fecha_actual == $fecha_exterior_final) : ?>
								<?php else: ?>
								<?php endif ?>
							<?php endif ?>
							<?php $fecha_actual = add_day($fecha_actual); ?>
							<?php $numero = $numero + 1; ?>
					</tr>
				<?php endwhile ?>
				<?php if ($fecha_actual == $fecha_exterior_final): ?>
				
				<?php endif ?>
			</table>	
		</td>
		<td width="20%" style="vertical-align: top; padding-left: 15px;">
			<img src="<?= ($empleado['foto'] == '') ? imgs . '/avatar.jpg' : files . '/empleados/' . $empleado['foto']; ?>" class="img-responsive img-circle">
			<br>
			<h4 class="text-center"><b><?= escape($empleado['cargo']); ?></b></h4>
			<h4 class="text-center">
				<u>Total atrasos</u>
				<br>
				<b><?= convert_time($atrasos); ?></b>
			</h4>
			<h4 class="text-center">
				<u>Total faltas</u>
				<br>
				<b><?= $faltas; ?></b>
			</h4>
		</td>
	</tr>
</table>
<script>
	window.print();
</script>
<?php require_once show_template('footer-site'); ?>