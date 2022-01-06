<?php  

$nombre_dominio = escape($_institution['nombre_dominio']);

$id_estudiante = (isset($_params[0])) ? $_params[0] : 0;  
$id_estudiante_editar = (isset($_params[0])) ? $_params[0] : 0;
$id_gestion	=	$_gestion['id_gestion'];


// Ficha con los datos de inscripcion del estudiante por gestion
$estudiante = $db->query("SELECT *
FROM ins_estudiante e 
INNER JOIN sys_persona sp ON e.persona_id = sp.id_persona
LEFT JOIN catalogo_detalle cd ON sp.tipo_documento = cd.id_catalogo_detalle
INNER JOIN ins_inscripcion i ON e.id_estudiante = i.estudiante_id
INNER JOIN ins_aula_paralelo ap ON i.aula_paralelo_id = ap.id_aula_paralelo
INNER JOIN ins_aula a ON ap.aula_id = a.id_aula
INNER JOIN ins_paralelo p ON ap.paralelo_id = p.id_paralelo
INNER JOIN ins_turno t ON ap.turno_id = t.id_turno
INNER JOIN ins_nivel_academico na ON a.nivel_academico_id = na.id_nivel_academico
INNER JOIN ins_tipo_estudiante te ON i.tipo_estudiante_id = te.id_tipo_estudiante
WHERE i.gestion_id  = $id_gestion
AND e.id_estudiante = $id_estudiante
AND i.estado = 'A'")->fetch_first(); 

// Listado de familiares del estudiante
$familiar = $db->select('e.*')->from('vista_estudiante_familiar e')->where('e.id_estudiante', $estudiante['id_estudiante'])->fetch();

// Obtiene turno
$turnos = $db->select('*')->from('ins_turno')->where(array('estado' => 'A', 'gestion_id' => $id_gestion))->fetch();

// Obtiene nivel academico
$niveles = $db->select('z.*')->from('ins_nivel_academico z')->where(array('estado' => 'A', 'gestion_id' => $id_gestion))->order_by('id_nivel_academico')->fetch();

// Obtiene tipo de estudiante
$tipo_estudiantes = $db->select('z.*')->from('ins_tipo_estudiante z')->where(array('estado' => 'A', 'gestion_id' => $id_gestion))->order_by('id_tipo_estudiante')->fetch();
//var_dump($tipo_estudiantes);exit(); 

// Obtiene datos de la inscripcion para validar con los conceptos de pago
$id_aula_paralelo = 0;
$id_nivel_academico = 0; 
$id_tipo_estudiante = 0; 
$id_turno = 0;
$id_aula_paralelo = $estudiante['aula_paralelo_id'];
$id_nivel_academico = $estudiante['nivel_academico_id'];
$id_tipo_estudiante = $estudiante['tipo_estudiante_id'];
$id_turno = $estudiante['turno_id'];  
$id_inscripcion = $estudiante['id_inscripcion']; 

// Obtiene datos de los pagos
// $pagos = $db->query("SELECT * 
// FROM pen_pensiones p 
// WHERE p.estado ='A' AND p.gestion_id = $id_gestion AND p.nombre_pension != 'RESERVA' AND p.aula_paralelo_id = $id_aula_paralelo
// OR  p.estado ='A' AND p.gestion_id = $id_gestion AND p.nombre_pension != 'RESERVA' AND  p.nivel_academico_id = $id_nivel_academico AND p.tipo_estudiante_id = $id_tipo_estudiante AND p.turno_id = $id_turno
// OR  p.estado ='A' AND p.nombre_pension != 'RESERVA' AND p.tipo_concepto LIKE 'GENERAL'
// group by p.id_pensiones
// ORDER BY p.nombre_pension")->fetch(); 

$pagos = $db->query("SELECT *
FROM pen_pensiones p 
INNER JOIN pen_concepto c ON p.concepto_id = c.id_concepto
WHERE p.estado ='A' AND p.gestion_id = $id_gestion 
group by p.id_pensiones
ORDER BY p.nombre_pension")->fetch();


$pagos_asignar = $db->query("SELECT *
FROM pen_pensiones p 
INNER JOIN pen_concepto c ON p.concepto_id = c.id_concepto
/*INNER JOIN pen_pensiones_detalle ppd ON p.id_pensiones = ppd.pensiones_id*/
WHERE p.estado ='A' AND p.gestion_id = $id_gestion 
group by p.id_pensiones
ORDER BY p.nombre_pension")->fetch();

//var_dump($pagos);exit();
$auxiliar_p = array();
$auxiliar_pp = array();
$array0 = '';
$array1 = '';
$array2 = '';
$array3 = '';
$a = 0;
foreach($pagos as $val){

	if($val['tipo_concepto']=='GRUPAL'){
		$turno = explode(",", $val['turno_id']);
		//echo($val['turno_id'].'turno');
		$contador   = count($turno);
		$array0 = '';
		for($i=0;$i<$contador;$i++){
				if($turno[$i]==$id_turno){
					$array0 = $val['id_pensiones'];
				}
		}

		$nivel      = explode(",", $val['nivel_academico_id']);
		//echo($val['nivel_academico_id'].'nivel');
		$contador1  = count($nivel);
		$array1 = '';
		for($j=0;$j<$contador1;$j++){
				if($nivel[$j]==$id_nivel_academico){
					$a = $id_nivel_academico;
					$array1 = $val['id_pensiones'];
				}
		}
	
		$tipo_estudiante      = explode(",", $val['tipo_estudiante_id']);
		//echo($val['tipo_estudiante_id'].'tipo');
		$contador2  = count($tipo_estudiante);
		$array2 = '';
		for($k=0;$k<$contador2;$k++){
				if($tipo_estudiante[$k]==$id_tipo_estudiante){
					$array2 = $val['id_pensiones'];
				}
		}
		if($array0>0 && $array0==$array1&&$array0==$array2){
			array_push($auxiliar_p, $array1);
			//var_dump('si'); var_dump($array1);
		}else{
			//var_dump($a);
			 //var_dump('no');
		}
		//echo '<br>';
		
	}else if($val['tipo_concepto']=='GRUPAL2'){

		$curso = explode(",", $val['aula_paralelo_id']);
		$contador   = count($curso);
		$array3 = '';
		for($i=0;$i<$contador;$i++){
				if($curso[$i]==$id_aula_paralelo ){
					$array3= $val['id_pensiones'];
				}
		}
		array_push($auxiliar_p, $array3);
		//var_dump($array3);
	}else if($val['tipo_concepto']=='INDIVIDUAL'){
		//No aplica ya que requiere primero la inscripcion 
	}else if($val['tipo_concepto']=='GENERAL'){
		$id_pension = $val['id_pensiones'];
		array_push($auxiliar_p, $id_pension);
	}


	//var_dump($array);
	$arraynew = array(
				'id_pensioness' 	=> $auxiliar_p,
				'tipo_concepto'	 	=> $val['tipo_concepto'],
	);
	array_push($auxiliar_pp, $arraynew);
}
$nue = array_filter($auxiliar_p);
$nuevo_array = array_values($nue);
//var_dump($nuevo_array);
//exit();

//$id_inscripcion = $estudiante['id_inscripcion'];
$validar = $db->query("SELECT IFNULL(count(*),0) contador
	FROM pen_pensiones_estudiante ppe 
	INNER JOIN pen_pensiones_detalle ppd ON ppe.detalle_pension_id = ppd.id_pensiones_detalle
	INNER JOIN pen_pensiones pp ON ppd.pensiones_id = pp.id_pensiones
	WHERE ppe.inscripcion_id = $id_inscripcion
	AND pp.estado ='A'
	GROUP BY pp.id_pensiones")->fetch_first();

$cuotas_habilitados=$db->query("SELECT *
	FROM pen_pensiones_estudiante ppe 
	INNER JOIN pen_pensiones_detalle ppd ON ppe.detalle_pension_id = ppd.id_pensiones_detalle
	INNER JOIN pen_pensiones pp ON ppd.pensiones_id = pp.id_pensiones
	INNER JOIN pen_concepto c ON pp.concepto_id = c.id_concepto
	WHERE ppe.inscripcion_id = $id_inscripcion
	AND pp.estado ='A'
	GROUP BY ppe.detalle_pension_id")->fetch();

$cuotas_habilitados_historial=$db->query("SELECT ih.estudiante_id, ih.inscripcion_id, ih.id_historial, 
ppe.id_pensiones_estudiante, ppe.fecha_final,ppe.cuota, ppe.monto, IFNULL(ped.monto,0) monto_cancelado,
ppd.nro, 
pp.id_pensiones, pp.tipo_concepto, pp.nombre_pension, pp.codigo_concepto, pp.descripcion, c.nombre_concepto,
te.descuento_beca, te.nombre_tipo_estudiante
FROM pen_pensiones_estudiante ppe 
INNER JOIN ins_inscripcion_historial ih ON ppe.historial_id = ih.id_historial
INNER JOIN ins_tipo_estudiante te ON ih.tipo_estudiante_id = te.id_tipo_estudiante
INNER JOIN pen_pensiones_detalle ppd ON ppe.detalle_pension_id = ppd.id_pensiones_detalle
INNER JOIN pen_pensiones pp ON ppd.pensiones_id = pp.id_pensiones
INNER JOIN pen_concepto c ON pp.concepto_id = c.id_concepto
LEFT JOIN pen_pensiones_estudiante_detalle ped ON ppe.id_pensiones_estudiante = ped.pensiones_estudiante_id
WHERE ppe.inscripcion_id = $id_inscripcion
AND pp.estado ='A'
GROUP BY ppe.detalle_pension_id
ORDER BY ppd.nro ASC")->fetch();

$cuotas=$db->query("SELECT *
	FROM pen_pensiones_estudiante ppe 
	INNER JOIN pen_pensiones_detalle ppd ON ppe.detalle_pension_id = ppd.id_pensiones_detalle
	INNER JOIN pen_pensiones pp ON ppd.pensiones_id = pp.id_pensiones
	WHERE ppe.inscripcion_id = $id_inscripcion
	AND pp.estado ='A'
	GROUP BY pp.id_pensiones")->fetch();

//var_dump($cuotas);exit();
$auxiliar = array();

foreach ($pagos as $value){
	foreach ($cuotas_habilitados as $val) {
		if($value['id_pensiones'] == $val['id_pensiones']){
            //var_dump($val);exit();
			$array = (array) [
			    'id_pensiones'   => $value['id_pensiones'],
			    'nombre_pension' => $value['nombre_pension'],
			    'descripcion'    => $value['descripcion'],
			    'tipo_concepto'  => $value['tipo_concepto'],
			];
			array_push($auxiliar, $array);
		}else{
		}
	}
}
//var_dump($auxiliar);exit();

$auxiliar_asignado = array();
foreach ($pagos as $value) {
	foreach ($cuotas as $val) {
		if($value['id_pensiones'] != $val['id_pensiones']){
			$array = (array) [
			    'id_pensiones'   => $value['id_pensiones'],
			    'nombre_pension' => $value['nombre_pension'],
			    'descripcion'    => $value['descripcion'],
			    'tipo_concepto'  => $value['tipo_concepto'],
			];
			array_push($auxiliar_asignado, $array);
		}else{
			//$array = (array) [];
			//$auxiliar_asignado='';
		}
	}
}
 //var_dump($auxiliar_asignado);exit();

$auxiliar_asignar = array();
foreach ($pagos as $value) {
 
	foreach ($auxiliar_asignado as $val) {

		if($value['id_pensiones'] == $val['id_pensiones']){
			$array = (array) [
			    'id_pensiones'   => $value['id_pensiones'],
			    'nombre_pension' => $value['nombre_pension'],
			    'descripcion'    => $value['descripcion'],
			    'tipo_concepto'  => $value['tipo_concepto'],
			];
			array_push($auxiliar_asignar, $array);
		}else{
			//$array = (array) [];
			//$auxiliar_asignar='';
		}
	}
}
//var_dump($auxiliar_asignar);exit();


$contador = count($nuevo_array);
//var_dump($contador);
//exit();
$auxiliar_por_asignar = array();
//var_dump($pagos);
for ($a=0; $a<$contador; $a++) {
	//var_dump($nue[$i]);exit();
	foreach ($pagos_asignar as $value) {
	//var_dump($value);
		if($nuevo_array[$a] == $value['id_pensiones']){
			$arraypa = (array) [
			    'id_pensiones'   => $value['id_pensiones'],
			    'nombre_pension' => $value['nombre_pension'],
			    'nombre_concepto' => $value['nombre_concepto'],
			    'descripcion'    => $value['descripcion'],
			    'tipo_concepto'  => $value['tipo_concepto'],
			    'fecha_final' => '--',//$value['fecha_final'],
			    'cuota'    => '--',//$value['cuota'],
			    'acuenta'  => '--',//0,
			    'saldo'  => '--',//$value['cuota'],
			];
			array_push($auxiliar_por_asignar, $arraypa);
		}else{
			//$array = (array) [];
			//$auxiliar_asignar='';
			//var_dump($nue[$i].$value['id_pensiones']);
		}
	}
}
//var_dump($auxiliar_por_asignar);
//exit();
?>

<?php require_once show_template('header-design'); ?>

<!-- ============================================================== -->
<!-- pageheader  -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Perfil Estudiante</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Inscripción</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Perfil Estudiante</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- pageheader  -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Perfil de Estudiante y Familiares -->
<!-- ============================================================== -->
<div class="row">
	<div class="card-body" style="background: #FFF">
		<div class="row">
			<div class="col-xl-6 col-lg-6">
				<div class="form-row">
					<div class="col-3 col-xl-3 col-lg-3 col-md-3 col-sm-3 text-center">
						<div class="user-avatar-name">
							<?php if($estudiante['genero'] == "m"):?>
								<img src="<?= ($estudiante['foto'] == '') ? 'files/'.$nombre_dominio.'/profiles/avatar_m.jpg' : $estudiante['foto'] ?>" alt="User Avatar" class="rounded-circle user-avatar-xxl">
							<?php else: ?>
								<img src="<?= ($estudiante['foto'] == '') ? 'files/'.$nombre_dominio.'/profiles/avatar_v.jpg' : $estudiante['foto'] ?>" alt="User Avatar" class="rounded-circle user-avatar-xxl">
							<?php endif?>
						</div>
					</div>
					<div class="col-6 col-xl-6 col-lg-6 col-md-6 col-sm-6">
						<div class="row">
							<div class="user-avatar-name">
								<h2 class="mb-1"><?= $estudiante['primer_apellido'] . " " . $estudiante['segundo_apellido'] . " " .  $estudiante['nombres']; ?></h2>
							</div>
						</div>	
						<div class="row">
							<div class="user-avatar-address">
								<div class="row" style="margin-bottom: 1%;"> 
									<div class="col col-md-12 col-sm-12">
										<span> <b>RUDE: </b> <?= ($estudiante['rude'] == "") ? "Sin Rude" : $estudiante['rude']; ?></span>
									</div>
									<!-- <div class="col col-md-12 col-sm-12">
										<span><b> Código Estudiante: </b> <?= ($estudiante['codigo_estudiante'] == "") ? "Sin Codigo de Estudiate" : $estudiante['codigo_estudiante']; ?> </span>
									</div> -->
								</div>
								<div class="row" style="margin-bottom: 1%;">
									<div class="col col-md-12 col-sm-12">
										<span><b> Número Documento: </b> <?= ($estudiante['numero_documento'] == "") ? "Sin CI." : $estudiante['numero_documento']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											  <b> Tipo Documento: </b> <?= ($estudiante['nombre_catalogo_detalle'] == "") ? "N/T" : $estudiante['nombre_catalogo_detalle']; ?></span>
									</div>
									<div class="col col-md-12 col-sm-12">
										<span><b>Género:</b> <?= ($estudiante['genero'] == "v") ? "Varón" : "Mujer"; ?> </span>
									</div>
								</div>
								<div class="row" style="margin-bottom: 1%;">
									<div class="col col-md-12 col-sm-12">
										<span class=""><b>Fecha de Nacimiento</b> <?= ($estudiante['fecha_nacimiento'] == "") ? "Sin Fecha de Nacimiento" : $estudiante['fecha_nacimiento']; ?> </span>
									</div>
									<div class="col col-md-12 col-sm-12">
										<span class=""><b>Dirección Domicilio: </b><?= ($estudiante['direccion'] == "") ? "Sin Dirección" : $estudiante['direccion']; ?></span>
									</div>
								</div>
								<div class="row" style="margin-bottom: 1%;">
									<div class="col col-md-12 col-sm-12">
										<span><b>Turno: </b> <?= ($estudiante['nombre_turno']=="") ? "Sin Nivel" : $estudiante['nombre_turno']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<b> Nivel Académico: </b> <?= ($estudiante['nombre_nivel']=="") ? "Sin Nivel" : $estudiante['nombre_nivel']; ?> </span>
									</div>
								</div>
								<div class="row" style="margin-bottom: 1%;">
									<div class="col col-md-12 col-sm-12">
										<span><b> Curso:</b> <?= ($estudiante['nombre_aula']=="") ? "Sin Curso" : $estudiante['nombre_aula']; ?> <?= ($estudiante['nombre_paralelo']=="") ? "Sin Paralelo" : $estudiante['nombre_paralelo']; ?> </span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-3 col-xl-3 col-lg-3 col-md-3 col-sm-3">
						<div class="text-center">
							<div class="" id="qr"></div>
							<div><?= escape($estudiante['codigo_estudiante']); ?></div>
						</div>
					</div>
				</div>
			</div>	

			<div class="col-xl-6 col-lg-6"> 
				<!-- ============================================================== -->
				<!-- Familiares -->
				<!-- ============================================================== -->
				<div class="col col-xl-12 col-lg-12 col-md-12 col-sm-12">
					<div class="section-block">
						<h3 class="section-title">Familiares</h3>
					</div> 
					<div class="table-responsive">
						<table id="table" class="table table-bordered table-condensed table-hover" style="width:100%">
							<thead>
								<tr>
									<th class="text-center">Nro.</th>
									<th class="text-center">Primer&nbsp;Apellido</th>
									<th class="text-center">Segundo&nbsp;Apellido</th>
									<th class="text-center">Nombres</th>
									<th class="text-center">Ocupación</th>
									<th class="text-center">Dirección&nbsp;Oficina</th>
									<th class="text-center">Telefóno&nbsp;Oficina</th>
								</tr>
							</thead> 
							<tbody>
								<?php foreach ($familiar as $key => $familia): ?>
									<tr>
										<td><?= escape($key + 1); ?></td>
										<td><?= escape($familia['primer_apellido']); ?></td>
										<td><?= escape($familia['segundo_apellido']); ?></td>
										<td><?= escape($familia['nombres']); ?></td>
										<td><?= escape($familia['profesion']); ?></td>
										<td><?= escape($familia['direccion_oficina']); ?></td>
										<td><?= escape($familia['telefono_oficina']); ?></td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
				</div>
				<!-- ============================================================== -->
				<!-- end Familiares -->
				<!-- ============================================================== -->
			</div>							
		</div>
	</div>
	<!-- ============================================================== -->
	<!-- end Perfil de Estudiante y Familiares -->
	<!-- ============================================================== -->
	<!-- ============================================================== -->
	<!-- Modificar inscripción -->
	<!-- ============================================================== -->
   <!--  <div class="row"> -->
		<div class="col-4 col-xl-4 col-lg-4 col-md-4 col-sm-12">
			<br>
			<!-- <div class="row"> -->
			
			<form id="form_inscripcion_editar" autocomplete="off">
				<div class="card">
					<div class="card-header">
						<div class="section-block">
							<h3 class="section-title">Actualizar Información de Inscripción y Pago</h3>
						</div>
					</div>
					<div class="card-body">
						<div class="row"  style="background: #fff">							
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<!-- <div class="section-block">
									<h3 class="section-title">Actualizar Información de Inscripción y Pago</h3>
								</div> -->
								<div class="">
									<div class="campaign-table">
										<div class="form-row">
											<div class="col-md-6 mb-3">
												<label  for="tipo_documento">Tipo de Estudiante:</label>                                                
												<select name="tipo_estudiante" id="tipo_estudiante" class="form-control">
													<option value="<?= $estudiante['id_tipo_estudiante']; ?>" selected="selected"><?= escape($estudiante['nombre_tipo_estudiante']); ?></option>
													<?php foreach ($tipo_estudiantes as $value) : ?>
														<option value="<?= $value['id_tipo_estudiante']; ?>"><?= escape($value['nombre_tipo_estudiante']); ?></option>
													<?php endforeach ?>
												</select>
											</div> 
											<div class="col-md-6 mb-3">
												<label  for="turno">Turno:</label>
												<select name="turno" id="turno" class="form-control" onchange="listar_niveles();">
													<option value="<?= $estudiante['id_turno']; ?>" selected="selected"><?= escape($estudiante['nombre_turno']); ?></option>
													<?php foreach ($turnos as $value) : ?>
													<option value="<?= $value['id_turno']; ?>"><?= escape($value['nombre_turno']); ?></option>
													<?php endforeach ?>
												</select>
											</div> 
										</div>
										<div class="form-row">
											<div class="col-md-6 mb-3">
												<label  for="tipo_documento">Nivel Académico:</label>
												<select name="nivel_academico" id="nivel_academico" class="form-control" onchange="listar_curso_nivel();">
													<option value="<?= $estudiante['id_nivel_academico']; ?>" selected="selected"><?= escape($estudiante['nombre_nivel']); ?></option>
												</select>
											</div> 
											<div class="col-md-6 mb-3">
												<label  for="tipo_documento">Curso:</label>
												<select name="select_curso" id="select_curso" onchange="listar_vacantes();" class="form-control">
													<option value="" selected="selected">Seleccionar</option>
												</select>
											</div> 
										</div> 
										<div class="form-row">
											<div class="col-md-6 mb-3">
												<label for="vacante">Vacantes:</label>
												<input name="vacante" id="vacante" class="form-control" disabled="disabled">
												<span class="text-primary">Inscritos: <span class="text-info" id="inscritos"></span> Cupo: <span class="text-info" id="cupo"></span></span>
											</div>

											<div class="col-md-6 mb-3">
												<label  for="fecha_inicio">Fecha de Inicio de Cambio:</label>
												<input type="date" name="fecha_inicio" id="fecha_inicio"  class="form-control">
											</div> 
										</div> 
									</div>
									
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer" style="background: #fff">
						<div class="form-row text-right">
							<div class="col-md-12 mb-0">
								<button type="submit" class="btn btn-warning pull-center text-white" id="btn_inscripcion">Actualizar Información</button>
							</div>
						</div>
					</div>
				</div>
            </form>									
		<!-- </div> -->
</div>
	    <div class="col-8 col-xl-8 col-lg-8 col-md-8 col-sm-12">
	    	<!-- <div class="row"> -->
			<br>
			<form id="form_pago" autocomplete="off">
				<div class="card">
					<div class="card-header">
						<div class="section-block">
							<h3 class="section-title"><label></label> Detalle e Historial de Pagos Asignados </h3>
						</div> 
					</div>
					<div class="card-body">
						<div class="row" style="background: #fff">						
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<!-- <div class="section-block">
									<h3 class="section-title"><label></label> Detalle de Pagos Asignados </h3>
								</div>  -->
								<table id="table" class="table table-bordered"> 
									<thead>
										<tr class="">
											<th class="text-center">#</th>
											<th class="text-center">Tipo/Grupo</th>
											<th class="text-center">Concepto Pago</th>
											<th class="text-center">Descripción</th>														
											<th class="text-center">Fecha Límite</th>
											<th class="text-center">Cuota (Bs.)</th>
											<th class="text-center">Tipo Estudiante </th>
											<th class="text-center">Monto Total (Bs.)</th>
											<th class="text-center">Acuenta (Bs.)</th>
											<th class="text-center">Saldo (Bs.)</th>
										</tr>
									</thead> 
									<tbody>
										<?php if($cuotas_habilitados): ?>
											<?php $contador = 0; ?>
											<?php foreach ($cuotas_habilitados_historial  as $key => $cuota): ?>
												<?php $contador = $contador + 1; ?>
												<?php $monto_total_cuota = 0;
								                      $monto_total_porcentaje = ($cuota['monto']*$cuota['descuento_beca'])/100;
								                      $monto_descuento_redondeo = round($monto_total_porcentaje, 2);
                                                      $monto_total_cuota = $cuota['monto']-$monto_descuento_redondeo; 

                                                      $saldo_total_cuota =0;
                                                      $saldo_total_cuota =$monto_total_cuota - $cuota['monto_cancelado'];

                                                ?>
												<tr>
													<td class="text-center"><?= escape($key + 1); ?></td>
													<td class="text-justify"><font size="2px"><?= escape($cuota['tipo_concepto']); ?></font></td>
													<td class="text-justify"><font size="2px"><?= escape($cuota['nombre_concepto']); ?></font><small> <b class="text-warning"> CUOTA <?= escape($cuota['nro']); ?></b></small></td>
													<td class="text-justify"><font size="1px"><?= escape($cuota['descripcion']); ?></font></td>
													<td class="text-center"><font size="2px"><?= date_decode($cuota['fecha_final'], $_format); ?></font></td>
													<td class="text-right"><?= escape($cuota['cuota']); ?></td>
													<td class="text-justify text-black"><?= escape($cuota['nombre_tipo_estudiante']); ?> (<?= escape($cuota['descuento_beca']); ?>)</td>
													<td class="text-right text-primary"><?= escape(number_format($monto_total_cuota, 2, '.', ' ')); ?></td>
													<td class="text-right text-success"><?= escape($cuota['monto_cancelado']); ?></td>
													<td class="text-right text-danger"><?= escape(number_format($saldo_total_cuota, 2, '.', ' ')); ?></td>
												</tr>
											<?php endforeach ?>
											<tr>
												<td class="text-center" colspan="10">
													<div class="alert alert-success" role="alert">
														Su concepto de pago ya fue Asignado, ya puede Cobrar.
													</div>
												</td>
											</tr>
										<?php else : ?>
											<?php $contador = 0; ?>
											<?php //foreach ($pagos as $key => $pago): ?>
											<?php foreach ($auxiliar_por_asignar as $key => $pago): ?>
												<?php $contador = $contador + 1; ?>
												<tr>
													<td class="text-center">
														<input type="checkbox" checked value="<?= escape($pago['id_pensiones']); ?>" name="id_pensiones[]" id="id_pensiones<?= $contador; ?>">&nbsp;&nbsp;<?= escape($key + 1); ?>
														<input type="hidden" value="<?= escape($pago['tipo_concepto']); ?>" name="tipo_concepto[]">
													</td>
													<td><?= escape($pago['tipo_concepto']); ?></td>
													<td><?= escape($pago['nombre_concepto']); ?></td>
													<td><?= escape($pago['descripcion']); ?></td>
													<td><?= escape($pago['fecha_final']); ?></td>
													<td><?= escape($pago['cuota']); ?></td>
													<td>--</td>
													<td>--</td>
													<td><?= escape($pago['acuenta']); ?></td>
													<td class="text-danger"><?= escape($pago['saldo']); ?></td>
												</tr>
											<?php endforeach ?>
											<tr>
												<td class="text-center" colspan="10">
													<div class="alert alert-danger" role="alert">
														Su concepto de pago no fue Asignado, debe asignar para Cobrar.
													</div>
												</td>
											</tr>
										<?php endif ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<?php if($cuotas_habilitados): ?>
						<div class="card-footer" style="background: #fff">
							<div class="form-row text-right">
								<div class="col-md-12 mb-0">
								<a href='?/s-pago-computarizado/imprimir/<?=$id_estudiante?>' target="_black" class='btn btn-dark'>Imprimir Historial Pagos</a>
								<a href='?/s-inscripciones/imprimir-contracto-servicio/<?=$id_estudiante?>' class='btn btn-light'>Imprimir Contrato</a>
								<a href='?/s-inscripciones/imprimir-poliza/<?=$id_estudiante?>' class='btn btn-light'>Imprimir Poliza</a>
								</div>
							</div>
						</div>
					<?php else : ?>
						<div class="card-footer" style="background: #fff">
							<div class="form-row text-right">
								<div class="col-md-12 mb-0">
									<button type="submit" class="btn btn-danger pull-center text-white" id="btn_asignar_pago">Asignar Pagos a Estudiante</button>
								</div>
							</div>
						</div>
					<?php endif ?>										
				</div>
			</form>
		<!-- </div> -->
	</div>
	<!-- </div> -->
	<!-- ============================================================== -->
	<!-- end Modificar inscripción -->
	<!-- ============================================================== -->
</div>

<?php require_once show_template('footer-design'); ?>
<script src="<?= js; ?>/JsBarcode.all.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/qrcode.min.js"></script>
<script>
//Variables para el cambio de paralelo
var id_estudiante 		= 0;
var id_tipo_estudiante 	= 0;
var id_turno 			= 0;
var id_nivel_academico 	= 0;
var id_curso 			= 0;
var id_inscripcion 		= 0;
var id_estudiante_editar= <?= $id_estudiante_editar; ?>;
var id_estudiante 		= <?= $id_estudiante; ?>;
var id_inscripcion 		= <?= $id_inscripcion; ?>;
var id_aula_paralelo 	= <?= $id_aula_paralelo; ?>;

cargar_vacantes(id_aula_paralelo);
$(function () {
	JsBarcode('.barcode').init();
	$("#form_pago").validate({
		rules: {
			id_pensiones: {
				required: true
			},
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight: highlight,
		unhighlight: unhighlight,
		messages: {
			id_pensiones: "Debe seleccionar conceptos de pagos a asignar.",
		},
		// Una ves validado guardamos los datos en la DB
		submitHandler: function(form){
			var datos = $("#form_pago").serialize();
			datos = datos + '&boton=' + 'guardar_concepto_pago' + '&id_estudiante=' + id_estudiante;
			console.log(datos);
			$.ajax({
				type: 'POST',
				url: "?/s-inscripciones/procesos-pago",
				data: datos,
				success: function(resp){
					console.log(resp);
					switch (resp){
						case '1':
							//document.location.href="?/s-inscripciones/imprimir-pago";
							imprimir_pago(id_estudiante);
							alertify.success('Registro de asignación exitoso.');
							break;
						case '2':
							alertify.success('Error, verifique la información e intente de nuevo, si el error persiste comuniquese con el Administrador.');
							break;
					}
				}
			});
		}
	})
})



function imprimir_pago(id) {
	window.location.reload();
	window.open('?/s-inscripciones/imprimir-pago/' + id, true);
}
var qrcode = new QRCode('qr',{
	text: "<?= $estudiante['codigo_estudiante']; ?>",
	//imagePath: "assets/imgs/avatar.jpg",
    width: 150,
    height: 150,
    colorDark : "#000000",
    colorLight : "#ffffff",
    correctLevel : QRCode.CorrectLevel.H
})

$(function() {
	JsBarcode('.barcode').init();
	datos_estudiante(id_estudiante_editar);
	$("#form_inscripcion_editar").validate({
		rules: {
			tipo_estudiante: {
				required: true
			},
			turno: {
				required: true
			},
			nivel_academico: {
				required: true
			},
			select_curso: {
				required: true
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight: highlight,
		unhighlight: unhighlight,
		messages: {
			tipo_estudiante: "Debe seleccionar el tipo de estudiante.",
			turno: "Debe seleccionar un turno para la inscripción.",
			nivel_academico: "Debe seleccionar el nivel académico.",
			select_curso: "Debe seleccionar el curso."
		},
		// Una ves validado guardamos los datos en la DB
		submitHandler: function(form){

			var fechh = $('#fecha_inicio').val();
			//console.log(fechh+'hgggggggggggggggggggggggggggggggggggg');

			if (id_tipo_estudiante == $("#tipo_estudiante option:selected").val() && id_turno == $("#turno option:selected").val() && id_nivel_academico == $("#nivel_academico option:selected").val() && id_curso == $("#select_curso option:selected").val() && $('#fecha_inicio').val()=='') {
				alertify.warning('No se realizo ningun cambioo');
			}else{
				var datos = $("#form_inscripcion_editar").serialize();
				datos = datos + '&a_id_tipo_estudiante='+id_tipo_estudiante;
				datos = datos + '&a_id_turno='+id_turno;
				datos = datos + '&a_id_nivel_academico='+id_nivel_academico;
				datos = datos + '&a_id_curso='+id_curso;
				datos = datos + '&inscripcion_id='+id_inscripcion;
				datos = datos + '&boton=' + 'guardar_inscripcion_editar';
				$.ajax({
					type: 'POST',
					url: "?/s-inscripciones/procesos-pago",
					data: datos,
					dataType: 'json',
					success: function(resp){
						console.log(resp);
						switch (resp['estado']){
							case 1:
								//alertify.success('Se edito correctamente inscripción');
								// window.open('?/s-inscripciones/editar-inscripcion-pago/' + id_estudiante, true);
								// window.close();

								window.location.replace('?/s-inscripciones/editar-inscripcion-pago/' + id_estudiante);

								break;
							case 2:
								alertify.error('No se pudo editar la inscripción, intente de nuevo, si el error persiste comuniquese con el Administrador.');
								$('#personales-tab').tab('show');
								break;
						}
					}
				});
			}
        }
    })
});

//Funciones de los metodos

function datos_estudiante(id_estudiante_editar) {
	//console.log("Hola Luis");	
	$.ajax({
		url: '?/s-inscripciones/procesos',
		type: 'POST',
		data: {
			'id_estudiante': id_estudiante_editar,
			'boton': 'datos_estudiante'
		},
		dataType: 'JSON',
		success: function(resp) {
			console.log(resp);
			id_estudiante = resp['datos_personales']['id_estudiante'];
			id_inscripcion_rude = resp['datos_personales']['id_ins_inscripcion_rude'];

			//Preguntamos los familiares
			a_id_familiar = resp['familiares'];
			//form Inscripcion Tab Inscripcion

			$("#tipo_estudiante").val(resp['datos_personales']['tipo_estudiante_id']);
			$("#turno").val(resp['datos_personales']['turno_id']);
			$("#nivel_academico").val(resp['datos_personales']['nivel_academico_id']);

			id_tipo_estudiante = resp['datos_personales']['tipo_estudiante_id'];
			id_turno = resp['datos_personales']['turno_id'];
			id_nivel_academico = resp['datos_personales']['nivel_academico_id'];
			id_curso = resp['datos_personales']['aula_paralelo_id'];

			//Cargamos el select de turnos
			cargar_select_turno(resp['datos_personales']['turno_id']);
			//Cargamos el select del Curso
			cargar_select_curso(resp['datos_personales']['aula_paralelo_id'], resp['datos_personales']['nivel_academico_id'], resp['datos_personales']['turno_id']);

			var imagen = $('#avatar');
			var url;
			if (resp['datos_personales']['foto']) {
				url = 'files/profiles/estudiantes/' + resp['datos_personales']['foto'] + '.jpg';
			} else {
				url = 'assets/imgs/avatar.jpg';
			}
			//imagen.src = url;
			$("#avatar").attr("src", url);
		}
	})
}
function cargar_select_curso(aula_paralelo_id, nivel_academico_id, turno_id) {
	nivel = nivel_academico_id;
	turno = turno_id;
	//alert(nivel);
	$.ajax({
		url: '?/s-inscripciones/procesos',
		type: 'POST',
		data: {
			'boton': 'listar_cursos_editar',
			'nivel': nivel,
			'turno': turno,
			'aula_paralelo_id': aula_paralelo_id                
		},
		dataType: 'JSON',
		success: function(resp) {
			//alert(resp[0]['id_catalogo_detalle']);
			//console.log(resp);
			$("#select_curso").html("");
			$("#select_curso").append('<option value="' + 0 + '">Seleccionar</option>');
			for (var i = 0; i < resp.length; i++) {
				if (resp[i]["id_aula_paralelo"] == aula_paralelo_id) {
					$("#select_curso").append('<option value="' + resp[i]["id_aula_paralelo"] + '" selected="selected">' + resp[i]["nombre_aula"] + ' ' + resp[i]["nombre_paralelo"] + '</option>');
				} else {
					$("#select_curso").append('<option value="' + resp[i]["id_aula_paralelo"] + '">' + resp[i]["nombre_aula"] + ' ' + resp[i]["nombre_paralelo"] + '</option>');
				}
			}
			//console.log(resp[0]);
		}
	});
}

function cargar_select_turno(turno_id) {
	//alert(turno_id);
	$.ajax({
		url: '?/s-inscripciones/procesos',
		type: 'POST',
		data: {
			'boton': 'listar_turnos'
		},
		dataType: 'JSON',
		success: function(resp) {
			$("#turno").html("");
			$("#turno").append('<option value="' + 0 + '">Seleccionar</option>');
			for (var i = 0; i < resp.length; i++) {
				if (resp[i]["id_turno"] == turno_id) {
					$("#turno").append('<option value="' + resp[i]["id_turno"] + '" selected="selected">' + resp[i]["nombre_turno"] + '</option>');
				} else {
					$("#turno").append('<option value="' + resp[i]["id_turno"] + '">' + resp[i]["nombre_turno"] + '</option>');
				}
			}
			//console.log(resp[0]);
		}
	});
}

function cargar_vacantes(id_aula_paralelo) {
	//alert(turno_id);
	$.ajax({
		url: '?/s-inscripciones/procesos-pago',
		type: 'POST',
		data: {
			'boton': 'listar_vacantes',
			'id_aula_paralelo': id_aula_paralelo
		},
		dataType: 'JSON',
		success: function(resp) {
			var vacante = parseInt(resp.cupo_total)-parseInt(resp.inscritos);
			$("#vacante").val(vacante);
			$("#inscritos").html(resp.inscritos);
			$("#cupo").html(resp.cupo_total);
		}
	});
}
function listar_niveles() {
	id_turno = $("#turno option:selected").val()
	//alert(nivel);
	$.ajax({
		url: '?/s-inscripciones/procesos-pago',
		type: 'POST',
		data: {
			'boton': 'listar_niveles',
			'id_turno': id_turno
		},
		dataType: 'JSON',
		success: function(resp) {
			$("#nivel_academico").html("");
			$("#nivel_academico").append('<option value="' + 0 + '">Seleccionar</option>');
			for (var i = 0; i < resp.length; i++) {
				$("#nivel_academico").append('<option value="' + resp[i]["id_nivel_academico"] + '">' + resp[i]["nombre_nivel"] + '</option>');
			}
		}
	});
}

function listar_vacantes() {
	id_aula_paralelo = $("#select_curso option:selected").val()
	//alert(nivel);
	$.ajax({
		url: '?/s-inscripciones/procesos-pago',
		type: 'POST',
		data: {
			'boton': 'listar_vacantes',
			'id_aula_paralelo': id_aula_paralelo
		},
		dataType: 'JSON',
		success: function(resp) {
			var vacante = parseInt(resp.cupo_total)-parseInt(resp.inscritos);
			$("#vacante").val(vacante);
			$("#inscritos").html(resp.inscritos);
			$("#cupo").html(resp.cupo_total);
		}
	});
}

function listar_curso_nivel() {
	nivel = $("#nivel_academico option:selected").val();
	turno = $("#turno option:selected").val();
	//alert(nivel);
	$.ajax({
		url: '?/s-inscripciones/procesos-pago',
		type: 'POST',
		data: {
			'boton': 'listar_cursos',
			'nivel': nivel,
			'turno': turno
		},
		dataType: 'JSON',
		success: function(resp) {
			//alert(resp[0]['id_catalogo_detalle']);
			//console.log(resp);
			$("#select_curso").html("");
			$("#select_curso").append('<option value="' + 0 + '">Seleccionar</option>');
			for (var i = 0; i < resp.length; i++) {
				$("#select_curso").append('<option value="' + resp[i]["id_aula_paralelo"] + '">' + resp[i]["nombre_aula"] + ' ' + resp[i]["nombre_paralelo"] + '  <small>(' + resp[i]["nombre_nivel"] + ' T/' + resp[i]["nombre_turno"] + ')</small></option>');
			}
			//console.log(resp[0]);
		}
	});
}
</script>