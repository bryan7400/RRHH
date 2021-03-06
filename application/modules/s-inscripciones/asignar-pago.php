<?php
	$id_estudiante = (isset($_params[0])) ? $_params[0] : 0; 

	$id_gestion=$_gestion['id_gestion'];

	$estudiante = $db->query("SELECT e.*, a.*
							  FROM vista_estudiantes e
							  LEFT JOIN ins_inscripcion i ON i.estudiante_id = e.id_estudiante
							  LEFT JOIN vista_aula_paralelo a ON a.id_aula_paralelo = i.aula_paralelo_id
							  WHERE e.id_estudiante = $id_estudiante AND i.gestion_id = $id_gestion")->fetch_first();
	//var_dump($estudiante);die;
    $familiar = $db->select('e.*')
               ->from('vista_estudiante_familiar e')
			   ->where('e.id_estudiante', $estudiante['id_estudiante'])->fetch();
// Obtiene datos de la inscripcion para validar con los conceptos de pago
$id_aula_paralelo = 0;
$id_nivel_academico = 0;
$id_tipo_estudiante = 0; 
$id_turno = 0;
$inscripcion = $db->query("SELECT * FROM ins_inscripcion i WHERE i.estudiante_id = $id_estudiante AND i.gestion_id = $id_gestion")->fetch_first();
$id_aula_paralelo = $inscripcion['aula_paralelo_id'];
$id_nivel_academico = $inscripcion['nivel_academico_id'];
$id_tipo_estudiante = $inscripcion['tipo_estudiante_id'];
$id_turno = $inscripcion['turno_id'];
 
// Obtiene datos de los pagos
$pagos = $db->query("SELECT * 
FROM pen_pensiones p 
WHERE p.estado ='A' AND p.gestion_id = $id_gestion AND p.nombre_pension != 'RESERVA' AND p.aula_paralelo_id = $id_aula_paralelo
OR  p.estado ='A' AND p.gestion_id = $id_gestion AND p.nombre_pension != 'RESERVA' AND  p.nivel_academico_id = $id_nivel_academico AND p.tipo_estudiante_id = $id_tipo_estudiante AND p.turno_id = $id_turno
OR  p.estado ='A' AND p.nombre_pension != 'RESERVA' AND p.tipo_concepto LIKE 'GENERAL'
group by p.id_pensiones
ORDER BY p.nombre_pension")->fetch(); 

$id_inscripcion = $inscripcion['id_inscripcion'];

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
WHERE ppe.inscripcion_id = $id_inscripcion
AND pp.estado ='A'
GROUP BY ppe.detalle_pension_id")->fetch();

$cuotas=$db->query("SELECT *
        FROM pen_pensiones_estudiante ppe 
        INNER JOIN pen_pensiones_detalle ppd ON ppe.detalle_pension_id = ppd.id_pensiones_detalle
        INNER JOIN pen_pensiones pp ON ppd.pensiones_id = pp.id_pensiones
WHERE ppe.inscripcion_id = $id_inscripcion
AND pp.estado ='A'
GROUP BY pp.id_pensiones")->fetch();

//var_dump($cuotas);exit();
$auxiliar = array();

foreach ($pagos as $value) {
 
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
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Estudiantes</a></li>
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

<div class="" style="margin-left: -2%">
	<div class="dashboard-influence">
		<div class="container-fluid dashboard-content">
			<div class="card influencer-profile-data">
				<div class="card-body">
					<div class="form-row">
						<div class="col col-xl-3 col-lg-4 col-md-6 col-sm-12 text-center">
							<img src="<?= ($estudiante['foto'] == '') ? 'assets/imgs/avatar.jpg' : $estudiante['foto'] ?>" alt="User Avatar" class="rounded-circle user-avatar-xxl">
						</div>
						<div class="col col-xl-5 col-lg-4 col-md-6 col-sm-12">
							<div class="row">
								<div class="user-avatar-name">
									<h2 class="mb-1"><?= $estudiante['primer_apellido'] . " " . $estudiante['segundo_apellido'] . " " .  $estudiante['nombres']; ?></h2>
								</div>
							</div>							
							<!--div class="rating-star  d-inline-block">
								<i class="fa fa-fw fa-star"></i>
								<i class="fa fa-fw fa-star"></i>
								<i class="fa fa-fw fa-star"></i>
								<i class="fa fa-fw fa-star"></i>
								<i class="fa fa-fw fa-star"></i>
								<p class="d-inline-block text-dark"></p>
							</div-->
							<div class="row">
								<div class="user-avatar-address">
									<div class="row" style="margin-bottom: 1%;"> 
										<div class="col col-md-6 col-sm-6">
											<span> <b>Rude: </b> <?= ($estudiante['rude'] == "") ? "Sin Rude" : $estudiante['rude']; ?></span>
										</div>
										<div class="col col-md-6 col-sm-6">
											<span> <b> C??digo Estudiante: </b> <?= ($estudiante['codigo_estudiante'] == "") ? "Sin Codigo de Estudiate" : $estudiante['codigo_estudiante']; ?> </span>
										</div>
									</div>
									<div class="row" style="margin-bottom: 1%;">
										<div class="col col-md-6 col-sm-6">
											<span><b> Tipo de Documento: <?= ($estudiante['numero_documento'] == "") ? "Sin CI." : $estudiante['numero_documento']; ?></b></span>
										</div>
										<div class="col col-md-4 col-sm-6">
											<span><b>G??nero</b> <?= ($estudiante['genero'] == "v") ? "Var??n" : "Mujer"; ?> </span>
										</div>
									</div>
									<div class="row" style="margin-bottom: 1%;">
										<div class="col col-md-6 col-sm-6">
											<b>Fecha de Nacimiento</b> <?= ($estudiante['fecha_nacimiento'] == "") ? "Sin Fecha de Nacimiento" : $estudiante['fecha_nacimiento']; ?> </span>
										</div>
										<div class="col col-md-6 col-sm-6">
											<span class=""><b>Direcci??n: </b><?= ($estudiante['direccion'] == "") ? "Sin Direcci??n" : $estudiante['direccion']; ?></span>
										</div>
									</div>
									<div class="row" style="margin-bottom: 1%;">
										<div class="col col-md-6 col-sm-6">
											<span><b> Curso:</b> <?= ($estudiante['nombre_aula']=="") ? "Sin Curso" : $estudiante['nombre_aula']; ?> <?= ($estudiante['nombre_paralelo']=="") ? "Sin Paralelo" : $estudiante['nombre_paralelo']; ?></span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col col-xl-4 col-lg-4 col-md-6 col-sm-12">
							<!--div class="thumbnail hidden" data-print-code="true">
								<img class="barcode img-responsive" jsbarcode-format="code128" jsbarcode-value="<?= substr($estudiante['numero_documento'], 2); ?>" jsbarcode-displayValue="true" jsbarcode-width="2" jsbarcode-height="64" jsbarcode-margin="0" jsbarcode-textMargin="-3" jsbarcode-fontSize="20" jsbarcode-lineColor="#333">
							</div-->
							<div class="" align="center">
								<div class="" id="qr">
								</div>
								<div><?= escape($estudiante['codigo_estudiante']); ?></div>
							</div>
							
						</div>
					</div>									
					
					
				</div>
			</div>
		</div>
	</div>
    <div class="row" style="margin-top: -7%">
	    <div class="col-md-6">
			<div class="dashboard-influence">
				<div class="container-fluid dashboard-content">
					<div class="row">
						<!-- ============================================================== -->
						<!-- campaign activities   -->
						<!-- ============================================================== -->
						<div class="col-lg-12">
							<div class="section-block">
								<h3 class="section-title">Familiares</h3>
							</div>
							<div class="card">
								<div class="campaign-table table-responsive">
									<table class="table">
										<thead>
											<tr class="border-0">
												<th class="border-0">Nro.</th>
												<th class="border-0">Primer Apellido</th>
												<th class="border-0">Segundo Apellido</th>
												<th class="border-0">Nombres</th>
												<th class="border-0">Ocupaci??n</th>
												<th class="border-0">Direcci??n Oficina</th>
												<th class="border-0">Telef??no Oficina</th>
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
						</div>
						<!-- ============================================================== -->
						<!-- end campaign activities   -->
						<!-- ============================================================== -->
					</div>
				</div>										
			</div>
		</div>
	    <div class="col-md-6">
			<div class="dashboard-influence">
				<div class="container-fluid dashboard-content">
					
					<form id="form_pago" autocomplete="off">
						<div class="row">
							<!-- ============================================================== -->
							<!-- campaign activities   -->
							<!-- ============================================================== -->
							
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
									<div class="section-block">
										<h3 class="section-title">Conceptos de Pago</h3>
									</div>
									<div class="card">
										<div class="campaign-table table-responsive">
											<table class="table">
												<thead>
													<tr class="border-0">
														<th class="text-center border-0">#</th>
														<th class="text-center border-0">Tipo</th>
														<th class="text-center border-0">Conceto de Pago</th>
														<th class="text-center border-0">Descripci??n</th>
													</tr>
												</thead> 
												<tbody>
													    <?php if($cuotas_habilitados): ?>
													    	<?php $contador = 0; ?>
															<?php foreach ($cuotas_habilitados  as $key => $cuota): ?>
																<?php $contador = $contador + 1; ?>
																<tr>
																	<td class="text-center"><?= escape($key + 1); ?></td>
																	<td><?= escape($cuota['tipo_concepto']); ?></td>
																	<td><?= escape($cuota['nombre_pension']); ?><small> <b style="color:green"> CUOTA <?= escape($cuota['nro']); ?></b></small></td>
																	<td><?= escape($cuota['descripcion']); ?></td>
																</tr>
															<?php endforeach ?>
															<tr><td class="text-center" colspan="4">
																	<div class="alert alert-success" role="alert">
						                                                Su concepto de pago ya fue Asignado, ya puede Cobrar.
						                                            </div>
															</td></tr>
														<?php else : ?>
															<?php $contador = 0; ?>
															<?php foreach ($pagos as $key => $pago): ?>
																<?php $contador = $contador + 1; ?>
																<tr>
																	<td class="text-center">
																		<input type="checkbox" checked value="<?= escape($pago['id_pensiones']); ?>" name="id_pensiones[]" id="id_pensiones<?= $contador; ?>">
																	    <?= escape($key + 1); ?>
																		<input type="hidden" value="<?= escape($pago['tipo_concepto']); ?>" name="tipo_concepto[]">
																	</td>
																	<td><?= escape($pago['tipo_concepto']); ?></td>
																	<td><?= escape($pago['nombre_pension']); ?></td>
																	<td><?= escape($pago['descripcion']); ?></td>
																</tr>
															<?php endforeach ?>
															<tr><td class="text-center" colspan="4">
																<div class="alert alert-danger" role="alert">
					                                                Su concepto de pago no fue Asignado, debe asignar para Cobrar.
					                                            </div>
															</td></tr>
													    <?php endif ?>
												</tbody>
											</table>
										</div>
									</div>								
								</div>

                            <?php if($cuotas_habilitados): ?>
							<div class="row">
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-right">
									<a href='?/s-inscripciones/imprimir-contracto-servicio/<?=$id_estudiante?>' class='btn btn-xs btn-primary'>Imprimir Contrato</a>
									<a href='?/s-inscripciones/imprimir-poliza/<?=$id_estudiante?>' class='btn btn-xs btn-primary'>Imprimir Poliza</a>

								</div>
							</div>
							<?php endif ?>
							
							<!-- ============================================================== -->
							<!-- end campaign activities   -->
							<!-- ============================================================== -->
						</div>
                        <?php if($cuotas_habilitados): ?>
                        <?php else : ?>
							<div class="row">
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-right">
									<button type="submit" class="btn btn-primary form-control pull-right" id="btn_pago">Asignar Pagos a Estudiante</button>
								</div>
							</div>
						<?php endif ?>
						

                    </form>
				</div>										
			</div>
		</div>
	</div>
</div>
</div>
<?php require_once show_template('footer-design'); ?>

<script src="<?= js; ?>/JsBarcode.all.min.js"></script>
<script src="<?= js; ?>/qrcode.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/educheck.js"></script>

<script>
var id_estudiante = <?= $id_estudiante; ?>;
$(function () {
	//JsBarcode('.barcode').init();

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
				id_pensiones: "Debe seleccionar el tipo de estudiante.",
			},
			//una ves validado guardamos los datos en la DB
			submitHandler: function(form) {
				//console.log('ggggggggggggggggggggggggggggggggggg');
				var datos = $("#form_pago").serialize();
				//var id_estudiante = $("#id_documentos").val();
				//datos = datos + '&id_familiares='+ id_familiares;
				datos = datos + '&boton=' + 'guardar_concepto_pago' + '&id_estudiante=' + id_estudiante;
				console.log(datos);
				$.ajax({
					type: 'POST',
					url: "?/s-inscripciones/procesos",
					data: datos,
					//data: {'id_estudiante': id_estudiante,'boton': 'guardar_concepto_pago'},
					success: function(resp) {
						console.log(resp);
						switch (resp) {
							case '1': //dataTable.ajax.reload();
								//document.location.href="?/s-inscripciones/imprimir-pago";
								
								imprimir_pago(id_estudiante);
								alertify.success('Registro exitoso.');
								break;
							case '2': //dataTable.ajax.reload();
								alertify.success('Error, verifique la informaci??n');
								break;
						}
					}
				});
			}
		})
})
	function imprimir_pago(id) {
		//$.open('?/b-electronicas/imprimir/' + venta, true); 
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

</script>