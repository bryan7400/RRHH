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
if (sizeof($_params) == 3) {
	// Verifica el tipo de los parametros
	if (!is_numeric($_params[0]) || !is_date($_params[1]) || !is_date($_params[2])) {
		// Redirecciona la pagina
		redirect('?/rrhh-resumen-asistencia/mostrar/0/' . $fecha_inicial . '/' . $fecha_final);
	}
} else {
	// Redirecciona la pagina
	redirect('?/rrhh-resumen-asistencia/mostrar/0/' . $fecha_inicial . '/' . $fecha_final);
}

$moneda = $db->from('gen_monedas')->where('principal', 'S')->fetch_first();
$moneda = ($moneda) ? $moneda['codigo'] : '';

// Obtiene los parametros
$id_persona = intval($_params[0]);

// Obtiene la asignacion
$sqlAsig = "SELECT	*
			FROM	per_asignaciones AS a			
			WHERE a.estado = 'A' AND a.persona_id = $id_persona";

$asignacion = $db->query($sqlAsig)->fetch_first();
$id_asignacion = $asignacion['id_asignacion'];

//te lo coloca en el formato año-mes-dia 2019-01-01
$fecha_interior_inicial = date_encode($_params[1]);
//te lo coloca en el formato año-mes-ida 2019-01-31
$fecha_interior_final = date_encode($_params[2]);
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

// Obtiene la fecha puntero 2018-12-31
$fecha_actual = $fecha_exterior_inicial;

// Obtiene los empleados
//$empleados = $db->select('e.*, p.procedencia, c.cargo')->from('sys_empleados e')->join('gen_procedencias p', 'e.procedencia_id = p.id_procedencia', 'left')->join('per_cargos c', 'e.cargo_id = c.id_cargo', 'left')->where('e.activo', 's')->order_by('c.cargo asc, e.nombres asc, e.paterno asc, e.materno asc')->fetch();


$YEARS1=explode("-",$fecha_interior_inicial);
$YEARS2=explode("-",$fecha_interior_final);

$empleados = $db->query("SELECT	*
						FROM	per_asignaciones AS a
						INNER JOIN 	sys_persona AS p ON p.id_persona = a.persona_id
						INNER JOIN 	per_cargos AS c ON c.id_cargo = a.cargo_id
						WHERE 	a.estado = 'A'
								AND YEAR(a.fecha_inicio)<=".$YEARS1[0]."
		                        AND YEAR(a.fecha_final)>=".$YEARS2[0]."
						")->fetch();
// Obtiene el empleado
//$empleado = $db->select('e.*, p.procedencia, c.cargo')->from('sys_empleados e')->join('gen_procedencias p', 'e.procedencia_id = p.id_procedencia', 'left')->join('per_cargos c', 'e.cargo_id = c.id_cargo', 'left')->where('e.id_empleado', $id_empleado)->fetch_first();
$sqlEmpl = "SELECT	*
			FROM	per_asignaciones AS a
			INNER JOIN sys_persona AS p ON p.id_persona = a.persona_id
			INNER JOIN per_cargos AS c ON c.id_cargo = a.cargo_id
			WHERE a.estado = 'A' AND a.persona_id = $id_persona";

$empleado = $db->query($sqlEmpl)->fetch_first();

// var_dump($sqlEmpl);
// exit();						

// Obtiene los feriados
$feriados = $db->from('per_feriados')->between('fecha_feriado', $fecha_interior_inicial, $fecha_interior_final)->fetch();
$feriados = array_column($feriados, 'fecha_feriado');

// Obtiene el tamano de filas de relleno
$sqlFR = "	SELECT ifnull(max(a.tamano), 0) as tamano 
			from (
				select fecha_asistencia, count(fecha_asistencia) as tamano 
				from per_asistencias 
				where asignacion_id = '$id_asignacion' and fecha_asistencia between '$fecha_interior_inicial' and '$fecha_interior_final' 
				group by fecha_asistencia
				) a
		";

// var_dump($sqlFR);
// exit();
$tamano = $db->query($sqlFR)->fetch_first();

$tamano = $tamano['tamano'];

// Define variables
$faltas                 = 0;
$atrasos                = 0;
$atrasos_extraordinario = 0;
$total_asistencia       = 0;
$adelanto               = 0;

// Obtiene los permisos
$permiso_cambiar = true;
$permiso_entrar = true;
$permiso_salir = true;
$permiso_imprimir = true;

function convertirHoraMinutos($hora){
	$v_HorasPartes = explode(":", $hora);
	$minutosTotales = ($v_HorasPartes[0] * 60) + $v_HorasPartes[1];
	return $minutosTotales;
}

// Retorna el contrato de un empleado en una fecha
function contrato($db, $id_empleado, $fecha) {
    //$id_persona === id_empleado
	//$contrato = $db->query("select a.* from (select * from per_contratos where fecha_inicial <= '$fecha 23:59:59') a left join (select * from per_contratos where fecha_inicial <= '$fecha 23:59:59') b on a.empleado_id = b.empleado_id and a.fecha_inicial < b.fecha_inicial where b.fecha_inicial is null and a.empleado_id = '$id_empleado'")->fetch_first();
	$contrato = $db->query("SELECT * 
							FROM per_asignaciones as pa 
							WHERE pa.persona_id = '$id_empleado' AND pa.estado_contrato = 'VIGENTE'
							")->fetch_first();
	return $contrato;
}

// Retorna las asistencias de un empleado en una fecha
function asistencias($db, $id_asignacion, $fecha) {
	$asistencias = $db->from('per_asistencias')->where('asignacion_id', $id_asignacion)->where('fecha_asistencia', $fecha)->order_by('entrada', 'asc')->fetch();
	// echo "<pre>";
	// var_dump($asistencias);
	// echo "</pre>";
	// exit();
	return $asistencias;
}

// Retorna los horarios de un empleado en una fecha
function horarios($db, $id_empleado, $fecha) {
	$horarios = $db->query("SELECT a.horario_id as horarios 
							from (	select * 
								  	from per_asignaciones 
								  	where fecha_asignacion <= '$fecha 23:59:59'
								) a 
							left join (
									select * 
									from per_asignaciones 
									where fecha_asignacion <= '$fecha 23:59:59'
								) b on a.persona_id = b.persona_id and a.fecha_asignacion < b.fecha_asignacion 
							where b.fecha_asignacion is null and a.persona_id = '$id_empleado'
							")->fetch_first();
	
	// echo "<pre>";
	// var_dump($horarios);
	// echo "</pre>";
	// exit();

	$horarios = explode(',', $horarios['horarios']);
	$horarios = $db->from('per_horarios')->where_in('id_horario', $horarios)->fetch();
	// echo "<pre>";
	// var_dump($horarios);
	// echo "</pre>";
	// exit();
	$horarios = corregir_horarios($fecha, $horarios);
	
	return $horarios;
}

// Retorna el salario de un empleado en una fecha
function salario($db, $id_empleado, $fecha) {
	$salario = $db->query("select a.salario from (select * from per_salarios where fecha_salario <= '$fecha 23:59:59') a left join (select * from per_salarios where fecha_salario <= '$fecha 23:59:59') b on a.empleado_id = b.empleado_id and a.fecha_salario < b.fecha_salario where b.fecha_salario is null and a.empleado_id = '$id_empleado'")->fetch_first();
	return ($salario) ? $salario['salario'] : 0;
}

function adelantos($db, $id_asignacion, $fecha) {
	$adelanto = $db->query("SELECT SUM(monto) as monto FROM per_adelantos WHERE asignacion_id = '$id_asignacion' AND fecha_adelanto<='$fecha 23:59:59'")->fetch_first();
	return ($adelanto) ? $adelanto['monto'] : 0;
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

function obtenerFeriados($db,$fecha_interior_inicial,$fecha_interior_final){
    $feriados = $db->from('per_feriados')->between('fecha_feriado', $fecha_interior_inicial, $fecha_interior_final)->fetch();
    $feriados = array_column($feriados, 'fecha_feriado');// [0] => 2019-01-01
    return $feriados;
}

?>




<?php require_once show_template('header-design'); ?>
<!--link rel="stylesheet" href="assets/themes/concept/assets/vendor/cropper/dist/cropper.min.css"-->
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/cropper-mazter/css/cropper.css">
<link href="assets/themes/concept/assets/vendor/datepicker/css/datepicker.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/bootstrap-fileinput-master/css/fileinput.css" rel="stylesheet">
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">

<style>
	.text-10 {
		font-size: 10px;
	}
	.text-11 {
		font-size: 11px;
	}
	.text-12 {
		font-size: 12px;
	}
	.text-13 {
		font-size: 13px;
	}
	.text-14 {
		font-size: 14px;
	}
	.text-15 {
		font-size: 15px;
	}
	.opacity-none {
		opacity: 0;
	}
	@media (min-width: 768px) {
		.table-display > .tbody > .tr > .td,
		.table-display > .tbody > .tr > .th,
		.table-display > .tfoot > .tr > .td,
		.table-display > .tfoot > .tr > .th,
		.table-display > .thead > .tr > .td,
		.table-display > .thead > .tr > .th {
			vertical-align: top;
		}
		.table-display > .tbody > .tr > .td:first-child,
		.table-display > .tbody > .tr > .th:first-child,
		.table-display > .tfoot > .tr > .td:first-child,
		.table-display > .tfoot > .tr > .th:first-child,
		.table-display > .thead > .tr > .td:first-child,
		.table-display > .thead > .tr > .th:first-child {
			padding-right: 15px;
		}
	}
</style>
 
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="page-header">			
				<h2 class="pageheader-title" data-idtutor="">Resumen personal de asistencia <?=$id_asignacion?></h2>
				<p class="pageheader-text"></p>
				<div class="page-breadcrumb">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">RRHH</a></li>
							<li class="breadcrumb-item active" aria-current="page">Resumen de asistencia</li>
						</ol>
					</nav>
				</div>			
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- end pageheader -->
<!-- ============================================================== -->
<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
						<div class="text-label hidden-xs">Seleccione:</div>
					</div>
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
						<div class="btn-group">
							<div class="input-group">
								<div class="input-group-append be-addon">
									<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
										<div class="dropdown-menu dropdown-menu-right">
											<a class="dropdown-item">Seleccionar acción</a>
											<div class="dropdown-divider"></div>
											<!--<a href="#" data-toggle="modal" data-target="#modal_cambiar" data-backdrop="static" data-keyboard="false"  class="dropdown-item"><span class="glyphicon glyphicon-calendar"></span> Cambiar fecha</a>-->
											<a href="?/rrhh-resumen-asistencia/imprimir/<?= $id_empleado; ?>/<?= $_params[1]; ?>/<?= $_params[2]; ?>" target="_blank"  class="dropdown-item"><span class="glyphicon glyphicon-print"></span> Imprimir Asistencias</a>
											<!--<a href="?/rrhh-resumen-asistencia/pagar/<?= $id_empleado; ?>/<?= $_params[1]; ?>/<?= $_params[2]; ?>" target="_blank"  class="dropdown-item"><span class="glyphicon glyphicon-print"></span> Pagar</a>-->
										</div>	
								</div>
							</div>
						</div>
					</div>		
				</div>	
			</div>

			<div class="card-body">
				<div class="row">
					<?php if ($id_persona != 0) : ?>					
							<div class="col-sm-8 col-md-9">
								<div class="row">
									<div class="col-sm-6">
										<div class="well lead text-ellipsis hidden-xs">
										<h2 class="pageheader-title" data-idtutor="">Resumen personal de asistencia</h2>
										</div>
										<div class="well lead text-center visible-xs-block">							
										</div>
									</div>
									<div class="col-sm-6">
										<?php if ($permiso_cambiar) : ?>
										<p class="margin-top-bottom text-right">
											<a href="?/rrhh-resumen-asistencia/cambiar/antes/<?= $id_persona; ?>/<?= $fecha_interior_inicial; ?>" class="btn btn-outline-info" data-cambiar="true">
												<span class="glyphicon glyphicon-menu-left"></span>
												<span class="hidden-sm">Anterior</span>
											</a>
											<a href="?/rrhh-resumen-asistencia/cambiar/despues/<?= $id_persona; ?>/<?= $fecha_interior_final; ?>" class="btn btn-outline-info" data-cambiar="true">
												<span class="hidden-sm">Siguiente</span>
												<span class="glyphicon glyphicon-menu-right"></span>
											</a>
										</p>
										<?php endif ?>
									</div>
								</div>
								<br>
								<div class="table-responsive margin-bottom">
									<table class="table table-bordered table-condensed margin-none">
										<thead>
											<tr class="active">
												<th class="text-nowrap text-center">LUNES</th>
												<th class="text-nowrap text-center">MARTES</th>
												<th class="text-nowrap text-center">MIÉRCOLES</th>
												<th class="text-nowrap text-center">JUEVES</th>
												<th class="text-nowrap text-center">VIERNES</th>
												<th class="text-nowrap text-center">SÁBADO</th>
												<th class="text-nowrap text-center">DOMINGO</th>
											</tr>
										</thead>
										<tbody>
											<tr>
											<?php $numero = 1; ?>
											<?php while ($fecha_actual <= $fecha_exterior_final) : ?>
												<?php if ($fecha_interior_inicial <= $fecha_actual && $fecha_actual <= $fecha_interior_final) : ?>
												<?php $contrato = contrato($db, $id_persona, $fecha_actual); ?>
												<?php $asistencias = asistencias($db, $id_asignacion, $fecha_actual); ?>
											
												<?php $horarios = horarios($db, $id_persona, $fecha_actual); ?>
												<?php $salario = salario($db, $id_persona, $fecha_actual); ?>
												<?php $adelanto = adelantos($db, $id_asignacion, $fecha_actual); ?>
												<?php $presencias = array(); ?>

												<?php 
													if ($contrato && (
															($contrato['fecha_inicio'] <= $fecha_actual && $fecha_actual <= $contrato['fecha_final']) || 
															($contrato['fecha_inicio'] <= $fecha_actual && $contrato['fecha_final'] == '0000-00-00')
														)) : ?>
													<?php if ($fecha_actual <= $hoy) : ?>
														<?php if ($horarios) : ?>
															<?php if (!in_array($fecha_actual, $feriados)) : ?>
																<?php if ($asistencias) : ?>
																	<?php $total_asistencia++; ?>
																<td class="text-center success">
																	<b class="text-success">Asistencia</b>
																<?php else : ?>
																<?php $faltas = $faltas + 1; ?>
																<td class="text-center danger">
																	<b class="text-danger">Falta</b>
																	<p>
																		<a href="?/resumenes/faltar/<?= $id_persona; ?>/<?= $fecha_actual;?>" class="text-primary" data-toggle="tooltip" data-title="Modificar falta" data-faltar="true">Modificar falta</a>
																	</p>
																<?php endif ?>
															<?php else : ?>
															<td class="text-center warning">
																<b class="text-warning">Feriado</b>
															<?php endif ?>
														<?php else : ?>
															<td class="text-center warning">
															<b class="text-warning">Descanso</b>
														<?php endif ?>
													<?php else : ?>
													<td class="text-center info">
														<b class="text-info">Próximamente</b>
													<?php endif ?>
												<?php else : ?>
												<td class="text-center">
													<b>Sin contrato</b>
												<?php endif ?>

													<p class="margin-none lead" data-fecha="true">
														<b><?= upper(month_day($fecha_actual)); ?></b>
													</p>
												
														<?php if ($asistencias) : ?>
														
														<table class="table table-bordered table-condensed text-center text-12 margin-none">
															<thead>
																<tr class="active">
																	<th class="text-center">Turno</th>
																	<th class="text-center">Entrada</th>
																	<th class="text-center">Salida</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($asistencias as $nro => $asistencia) : ?>
																<?php array_push($presencias, array('entrada' => $asistencia['entrada'], 'salida' => $asistencia['salida'])); ?>
																<tr>
																	<td>
																		<span><?= $nro + 1; ?>°</span>
																	</td>
																	<td>
																		<a href="?/rrhh-resumen-asistencia/entrar/<?= $id_persona; ?>/<?= $asistencia['id_asistencia']; ?>" class="text-primary" data-toggle="tooltip" data-title="Modificar entrada" data-entrar="true"><?= substr($asistencia['entrada'], 11, -3); ?></a>														
																	</td>
																	<td>
																		<a href="?/rrhh-resumen-asistencia/salir/<?= $id_persona; ?>/<?= $asistencia['id_asistencia']; ?>" class="text-primary" data-toggle="tooltip"  data-title="Modificar salida" data-salir="true"><?= substr($asistencia['salida'], 11, -3); ?></a>
																	</td>
																</tr>
																<?php endforeach ?>
															</tbody>
														</table>
														<?php else : ?>
														<table class="table table-bordered table-condensed text-center text-12 margin-none opacity-none">
															<thead>
																<tr class="active"><td>0°</td><td>00:00</td><td>00:00</td></tr>
															</thead>
															<tbody>
																<?php for ($t = 0; $t < $tamano; $t = $t + 1) : ?>
																<tr><td colspan="4">&nbsp;</td></tr>
																<?php endfor ?>
															</tbody>
														</table>
														<?php endif ?>
													<?php if ($asistencias) : ?>
													<?php $resultados = obtener_resultados($horarios, $presencias); ?>
													<?php if (!in_array($fecha_actual, obtenerFeriados($db,$fecha_interior_inicial,$fecha_interior_final))) {?>
													<?php $atrasos = $atrasos + $resultados['horas_atraso']; ?>
													<?php } else { ?>
													<?php $resultados = obtener_resultados($horarios, $presencias); ?>
													<?php $trabajo_extraordinario = $atrasos_extraordinario + $resultados['horas_extra']; ?>
													<?php } ?>
													<u><b>Resultados</b></u>
													<div class="table-display text-left">
														<div class="tbody">
															<div class="tr">
																<div class="th">Total atrasos:</div>
																<div class="td text-primary"><?= convert_time($resultados['horas_atraso']); ?></div>
															</div>
															<div class="tr">
																<div class="th">Total abandonos:</div>
																<div class="td"><?= convert_time($resultados['horas_abandono']); ?></div>
															</div>
															<div class="tr">
																<div class="th">Total trabajadas:</div>
																<div class="td text-danger"><?= convert_time($resultados['horas_trabajo']); ?></div>
															</div>
															<div class="tr">
																<div class="th">Total extras:</div>
																<div class="td"><?= convert_time($resultados['horas_extra']); ?></div>
															</div>
															<div class="tr">
																<div class="th">Total descansos:</div>
																<div class="td"><?= convert_time($resultados['horas_descanso']); ?></div>
															</div>
														</div>
													</div>
											
													<?php else : ?>

													<?php endif ?>
													<!--<p><?php var_dump($horarios); ?></p>
													<p><?php var_dump($presencias); ?></p>-->
													<!--<p><?php echo($salario); ?></p>-->

												</td>
												<?php else : ?>
												<td></td>
												<?php endif ?>
												<?php if ($numero % 7 == 0) : ?>
													<?php if ($fecha_actual == $fecha_exterior_final) : ?>
													</tr>
													<?php else: ?>
													</tr><tr>
													<?php endif ?>
												<?php endif ?>
												<?php $fecha_actual = add_day($fecha_actual); ?>
												<?php $numero = $numero + 1; ?>
											<?php endwhile ?>
											<?php if ($fecha_actual == $fecha_exterior_final): ?>
											</tr>
											<?php endif ?>
										</tbody>
									</table>
								</div>					
							</div>
							<div class="col-sm-4 col-md-3">
								<div class="well text-center">
									<img src="<?= ($empleado['foto'] == '') ? files . '/profiles/personal/avatar.jpg' : files . '/profiles/personal/' . $empleado['foto'].".jpg"; ?>" width="255" height="255" class="rounded-circle">
									<p class="lead margin-top">
										<strong><?= escape($empleado['nombres'] . ' ' . $empleado['primer_apellido'] . ' ' . $empleado['segundo_apellido']); ?></strong>
									</p>
									<p class="lead margin-none"><?= escape($empleado['cargo']); ?></p>
								</div>
								<div class="alert alert-info">
									<div class="table-display">
										<div class="tbody">
											<div class="tr">
												<div class="th">
													<span class="lead">
														<u>Atrasos:</u>
													</span>
												</div>
												<div class="td">
													<span class="lead">
														<b><?= convert_time($atrasos); ?> (<?= convertirHoraMinutos(convert_time($atrasos)).' '.$moneda; ?>) </b>
													</span>
												</div>
											</div>
											<div class="tr">
												<div class="th">
													<span class="lead">
														<u>Faltas:</u>
													</span>
												</div>
												<div class="td">
													<span class="lead">
														<b><?= $faltas; ?></b>
													</span>
												</div>
											</div>
											<div class="tr">
												<div class="th">
													<span class="lead">
														<u>Asistencias:</u>
													</span>
												</div>
												<div class="td">
													<span class="lead">
														<b><?= $total_asistencia; ?></b>
													</span>
												</div>
											</div>
											<div class="tr">
												<div class="th">
													<span class="lead">
														<u>Salario:</u>
													</span>
												</div>
												<div class="td">
													<span class="lead">
														<b><?= $salario; ?></b>
													</span>
												</div>
											</div>
											<div class="tr">
												<div class="th">
													<span class="lead">
														<u>Adelantos:</u>
													</span>
												</div>
												<div class="td">
													<span class="lead">
														<b><?= $adelanto; ?></b>
													</span>
												</div>
											</div>
											<div class="tr">
												<div class="th">
													<span class="lead">
														<u>Extraordinarias:</u>
													</span>
												</div>
												<div class="td">
													<span class="lead">
														<b><?= isset($trabajo_extraordinario) ? convert_time($trabajo_extraordinario): 0; ?></b>
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div>
									<div>
										<div>
											<!--<h5 class="lead text-center">Lista de empleados</h5>-->
											<?php $estado = 0; ?>
											<?php //foreach ($empleados as $nro => $elemento) : ?>
											<?php //$empleado_instituciones = $db->query("SELECT * FROM sys_empleado_instituciones LEFT JOIN sys_instituciones ON id_institucion = institucion_id WHERE empleado_id=".$elemento['id_empleado'])->fetch(); ?>
											
										</div>
									</div>
								</div>
								
								<!-- listado de trabajadores -->
								<div class="row">
									<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-header">
												<div class="row">										
												</div>
											</div>
											<!-- ============================================================== -->
											<!-- datos --> 
											<!-- ============================================================== -->
											<div class="card-body">
												<?php if ($message = get_notification()) : ?>
												<div class="alert alert-<?= $message['type']; ?>">
													<button type="button" class="close" data-dismiss="alert">&times;</button>
													<strong><?= $message['title']; ?></strong>
													<p><?= $message['content']; ?></p>
												</div>
												<?php endif ?>

												<?php if ($empleados) : ?>
												<div class="table-responsive">
													<table id="table" class="table table-bordered table-condensed table-striped table-hover" width="100%">
														<thead>
															<tr class="active">
																<th class="text-nowrap">#</th>								
																<th class="text-nowrap">Nombre completo</th>							
															</tr>
														</thead>
														<tfoot>
															<tr class="active">
																<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>								
																<th class="text-nowrap text-middle">Nombre completo</th>														
															</tr>
														</tfoot>
														<tbody>
															<?php foreach ($empleados as $nro => $empleado) : ?>
															<?php //$asistencias = asistencias($db, $empleado['persona_id'], $fecha) ?>
															<?php //$nro_asistencias = sizeof($asistencias); ?>
															<?php //$sucursales = $db->query("SELECT nombre from sys_empleado_instituciones ei LEFT JOIN sys_instituciones i ON i.id_institucion=ei.institucion_id WHERE empleado_id=".$empleado['id_empleado'])->fetch();?>
															<tr>
																<th class="text-nowrap"><?= $nro + 1; ?></th>
																<td class="text-nowrap">
																<?php $persona = explode(' ', $empleado['nombres']); ?>
																<?php $persona = ($empleado['primer_apellido'] != '') ? escape($persona[0] . ' ' . $empleado['segundo_apellido']) : escape($empleado['nombres'] . ' ' . $empleado['segundo_apellido']); ?>
																<div class="col-xs-4 col-lg-3 text-center margin-bottom">
																	<a href="?/rrhh-resumen-asistencia/mostrar/<?= $empleado['persona_id']; ?>/<?= $_params[1]; ?>/<?= $_params[2]; ?>" data-toggle="tooltip" data-title="<?= $persona; ?>">
																		<img src="<?= ($empleado['foto'] == '') ?  files . '/profiles/personal/avatar.jpg' : files . '/profiles/personal/' . $empleado['foto'].".jpg"; ?>" width="60" height="60" class="img-circle">
																	</a>
																	<strong class="text-ellipsis"><?= escape($persona); ?></strong>
																</div>							
															</tr>
															<?php endforeach ?>
														</tbody>
													</table>
												</div>
												<?php else : ?>
													<div class="alert alert-info margin-none">
														<strong>Atención!</strong>
														<ul>
															<li>No se registraron asistencias de empleados en esta fecha.</li>
															<li>Verifique que la fecha se válida.</li>
														</ul>
													</div>
												<?php endif ?>
											</div>
											<!-- ============================================================== -->
											<!-- end datos -->
											<!-- ============================================================== -->
										</div>
									</div>
								</div>
							</div>
						</div>	
					<?php else : ?>
						<div class="list-group">
							<?php foreach ($empleados as $nro => $elemento) : ?>
								<?php //$empleado_instituciones = $db->query("SELECT * FROM sys_empleado_instituciones LEFT JOIN sys_instituciones ON id_institucion = institucion_id WHERE empleado_id=".$elemento['id_empleado'])->fetch(); ?>
								<a href="?/rrhh-resumen-asistencia/mostrar/<?= $elemento['persona_id']; ?>/<?= $_params[1]; ?>/<?= $_params[2]; ?>" class="list-group-item">
									<div class="media margin-none">
										<div class="media-left">
											<img src="<?= ($elemento['foto'] == '') ? files . '/profiles/personal/avatar.jpg' : files . '/profiles/personal/' . $elemento['foto'].".jpg"; ?>" class="rounded-circle" width="64">
										</div>
										<div class="media-body">
											<span class="glyphicon glyphicon-menu-right pull-right"></span>
											<h4 class="media-heading"><?= escape($elemento['nombres'] . ' ' . $elemento['primer_apellido'] . ' ' . $elemento['segundo_apellido']); ?></h4>
											<span><?= escape($elemento['cargo']); ?></span>
										</div>
									</div>
								</a>
							<?php endforeach ?>
						</div>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>		
</div>



<!-- Modal cambiar inicio -->
<div id="modal_cambiar" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/rrhh-resumen-asistencia/mostrar/<?= $id_empleado; ?>" id="form_cambiar" class="modal-content loader-wrapper" autocomplete="off">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Cambiar fecha</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="inicial_cambiar" class="control-label">Fecha inicial:</label>
					<input type="text" value="<?= date_decode($fecha_interior_inicial, $_institution['formato']); ?>" name="inicial" id="inicial_cambiar" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
				</div>
				<div class="form-group">
					<label for="final_cambiar" class="control-label">Fecha final:</label>
					<input type="text" value="<?= date_decode($fecha_interior_final, $_institution['formato']); ?>" name="final" id="final_cambiar" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">
					<span class="glyphicon glyphicon-share-alt"></span>
					<span>Cambiar</span>
				</button>
				<button type="reset" class="btn btn-default">
					<span class="glyphicon glyphicon-refresh"></span>
					<span>Restablecer</span>
				</button>
			</div>
			<div id="loader_cambiar" class="loader-wrapper-backdrop hidden">
				<span class="loader"></span>
			</div>
		</form>
	</div>
</div>
<!-- Modal cambiar fin -->

<!-- Modal entrar inicio -->
<?php if ($permiso_entrar) : ?>
<div id="modal_entrar" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/rrhh-resumen-asistencia/entrar" id="form_entrar" class="modal-content loader-wrapper" autocomplete="off">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="entrada_entrar" class="control-label">Entrada:</label>
					<input type="text" value="" name="entrada" id="entrada_entrar" class="form-control">
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">
					<span class="glyphicon glyphicon-floppy-disk"></span>
					<span>Guardar</span>
				</button>
				<button type="reset" class="btn btn-default">
					<span class="glyphicon glyphicon-refresh"></span>
					<span>Restablecer</span>
				</button>
			</div>
			<!-- <div id="loader_entrar" class="loader-wrapper-backdrop hidden">
				<span class="loader"></span>
			</div> -->
		</form>
	</div>
</div>
<?php endif ?>
<!-- Modal entrar fin -->

<!-- Modal salir inicio -->
<?php if ($permiso_salir) : ?>
<div id="modal_salir" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/rrhh-resumen-asistencia/salir" id="form_salir" class="modal-content loader-wrapper" autocomplete="off">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="salida_salir" class="control-label">Salida:</label>
					<input type="text" value="" name="salida" id="salida_salir" class="form-control" >
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">
					<span class="glyphicon glyphicon-floppy-disk"></span>
					<span>Guardar</span>
				</button>
				<button type="reset" class="btn btn-default">
					<span class="glyphicon glyphicon-refresh"></span>
					<span>Restablecer</span>
				</button>
			</div>
			<!-- <div id="loader_salir" class="loader-wrapper-backdrop hidden">
				<span class="loader"></span>
			</div> -->
		</form>
	</div>
</div>
<?php endif ?>
<!-- Modal salir fin -->

<div id="modal_faltar" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/empleados/faltar" id="form_faltar" class="modal-content loader-wrapper" autocomplete="off">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Modificar falta</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="entrada_faltar" class="control-label">Entrada:</label>
					<input type="text" value="" name="entrada" id="entrada_faltar" class="form-control" data-validation="required">
				</div>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="salida_faltar" class="control-label">Salida:</label>
					<input type="text" value="" name="salida" id="salida_faltar" class="form-control" data-validation="required">
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">
					<span class="glyphicon glyphicon-floppy-disk"></span>
					<span>Guardar</span>
				</button>
				<button type="reset" class="btn btn-default">
					<span class="glyphicon glyphicon-refresh"></span>
					<span>Restablecer</span>
				</button>
			</div>
			<!-- <div id="loader_entrar" class="loader-wrapper-backdrop hidden">
				<span class="loader"></span>
			</div> -->
		</form>
	</div>
</div>

<!-- <script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/bootstrap-datetimepicker.min.js"></script>
<script src="<?= js; ?>/Chart.bundle.js"></script> -->

<script src="<?= themes; ?>/concept/assets/vendor/cropper-mazter/js/cropper.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/cropper-mazter/js/imagenes.js"></script>
<!--script src="<?= themes; ?>/concept/assets/vendor/cropper-mazter/js/main.js"></script-->
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.es.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-fileinput-master/js/fileinput.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-fileinput-master/js/es.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/educheck.js"></script>

<?php require_once show_template('footer-design'); ?>

<script>
$(function () {
	var $modal_cambiar = $('#modal_cambiar'), $form_cambiar = $('#form_cambiar'), $loader_cambiar = $('#loader_cambiar'), $inicial_cambiar = $('#inicial_cambiar'), $final_cambiar = $('#final_cambiar');
	$("#form_cambiar").validate({
					rules: {},
					errorClass: "help-inline",
					errorElement: "span",
					highlight: highlight,
					unhighlight: unhighlight,
					messages: {},
					//una ves validado guardamos los datos en la DB
					submitHandler: function(form) {
						$loader_cambiar.removeClass('hidden');
						var direccion_cambiar = $.trim($form_cambiar.attr('action')), inicial_cambiar = $.trim($inicial_cambiar.val()), final_cambiar = $.trim($final_cambiar.val());
						inicial_cambiar = inicial_cambiar.replace(new RegExp('/', 'g'), '-');
						final_cambiar = final_cambiar.replace(new RegExp('/', 'g'), '-');
						window.location = direccion_cambiar + '/' + inicial_cambiar + '/' + final_cambiar;
					}
				});

	// $inicial_cambiar.datetimepicker({
	// 	format: '<?= strtoupper($formato_textual); ?>'
	// });

	// $final_cambiar.datetimepicker({
	// 	format: '<?= strtoupper($formato_textual); ?>'
	// });

	$form_cambiar.on('submit', function (e) {
		e.preventDefault();
	});

	$modal_cambiar.on('hidden.bs.modal', function () {
		$form_cambiar.trigger('reset');
	}).on('show.bs.modal', function (e) {
		if ($('.modal:visible').size() != 0) { e.preventDefault(); }
	});

	<?php if ($permiso_cambiar) : ?>
	$('[data-cambiar]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var csrf = '';
		window.location= href;
		//$.request(href, csrf);
	});
	<?php endif ?>

	<?php if (false) : ?>
	var fechas = [];
	var horarios = [];
	var asistencias = [];

	$('td.success').each(function () {
		fechas.push($.trim($(this).find('[data-fecha]').text()));
		horarios.push(0);
		asistencias.push(Math.round(Math.random() * (50 + 50) - 50));
	});

	asistencias[6] = NaN;
	asistencias[12] = NaN;
	asistencias[18] = NaN;
	asistencias[24] = NaN;
	
	<?php endif ?>

	<?php if ($permiso_entrar) : ?>
	var $modal_entrar = $('#modal_entrar'), $form_entrar = $('#form_entrar'), $loader_entrar = $('#loader_entrar'), $empleado_entrar = $('#empleado_entrar'), $entrada_entrar = $('#entrada_entrar');

	$("#form_entrar").validate({
					rules: {},
					errorClass: "help-inline",
					errorElement: "span",
					highlight: highlight,
					unhighlight: unhighlight,
					messages: {},
					//una ves validado guardamos los datos en la DB
					submitHandler: function(form) {
						console.log("Holaaaaaa llegaaaa");
						//$loader_entrar.removeClass('hidden');
						$loader_salir.removeClass('hidden');
					}
				});

	$('[data-entrar]').on('click', function (e) {
		e.preventDefault();
		//console.log("Holaaaaaa");
		var href = $(this).attr('href');
		console.log(href);		
		$form_entrar.attr('action', href);
		$modal_entrar.modal({
			backdrop: 'static',
			keyboard: false
		});
	});

	// $modal_entrar.on('hidden.bs.modal', function () {
	// 	$form_entrar.trigger('reset');
	// }).on('show.bs.modal', function (e) {
	// 	if ($('.modal:visible').size() != 0) { e.preventDefault(); }
	// }).on('shown.bs.modal', function () {
	// 	$form_entrar.find('.form-control:nth(1)').focus();
	// });
	<?php endif ?>

	<?php if ($permiso_salir) : ?>
	var $modal_salir = $('#modal_salir'), $form_salir = $('#form_salir'), $empleado_salir = $('#empleado_salir'), $salida_salir = $('#salida_salir');

	$("#form_salir").validate({
					rules: {},
					errorClass: "help-inline",
					errorElement: "span",
					highlight: highlight,
					unhighlight: unhighlight,
					messages: {},
					//una ves validado guardamos los datos en la DB
					submitHandler: function(form) {
						$loader_salir.removeClass('hidden');
					}
				});


	$('[data-salir]').on('click', function (e) {
		e.preventDefault();	
		var href = $(this).attr('href');
		console.log(href);		
		$form_salir.attr('action', href);
		$modal_salir.modal({
			backdrop: 'static',
			keyboard: false
		});
	});

	// $modal_salir.on('hidden.bs.modal', function () {
	// 	$form_salir.trigger('reset');
	// }).on('show.bs.modal', function (e) {
	// 	if ($('.modal:visible').size() != 0) { e.preventDefault(); }
	// }).on('shown.bs.modal', function () {
	// 	$form_salir.find('.form-control:nth(1)').focus();
	// });
	<?php endif ?>

	//===================================
	var $modal_faltar = $('#modal_faltar'), $form_faltar = $('#form_faltar'), $loader_faltar = $('#loader_faltar'), $empleado_faltar = $('#empleado_faltar'), $entrada_faltar = $('#entrada_faltar');
	
	$("#form_faltar").validate({
					rules: {},
					errorClass: "help-inline",
					errorElement: "span",
					highlight: highlight,
					unhighlight: unhighlight,
					messages: {},
					//una ves validado guardamos los datos en la DB
					submitHandler: function(form) {
						$loader_faltar.removeClass('hidden');
					}
				});

	$('[data-faltar]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		$form_faltar.attr('action', href);
		$modal_faltar.modal({
			backdrop: 'static',
			keyboard: false
		});
	});

	// $modal_faltar.on('hidden.bs.modal', function () {
	// 	$form_faltar.trigger('reset');
	// }).on('show.bs.modal', function (e) {
	// 	if ($('.modal:visible').size() != 0) { e.preventDefault(); }
	// }).on('shown.bs.modal', function () {
	// 	$form_faltar.find('.form-control:nth(1)').focus();
	// });

	<?php if (true) : ?>
    	
	var dataTable = $('#table').DataTable({
	language: dataTableTraduccion,
	searching: true,
	paging:true,
	"lengthChange": true, 
	"responsive": true
	});
	<?php endif ?>

});
</script>