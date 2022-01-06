<?php
	$id_estudiante = (isset($_params[0])) ? $_params[0] : 0;
	//var_dump($id_estudiante);exit();
	$gestion=$_gestion['id_gestion'];
	/*$estudiante = $db->select('e.*, a*')
					 ->from('vista_estudiantes e')
					 ->join('ins_inscripcion i','i.estudiante_id = e.id_estudiante')
					 ->join('vista_aula_paralelo a','a.id_aula_paralelo = i.aula_paralelo_id')
					 ->where('e.id_estudiante', $id_estudiante)
					 ->where('i.gestion_id', $id_estudiante)
					 ->order_by('z.id_estudiante', 'asc')->fetch_first();*/
	$estudiante = $db->query("SELECT e.*, a.*
							  FROM vista_estudiantes e
							  LEFT JOIN ins_inscripcion i ON i.estudiante_id = e.id_estudiante
							  LEFT JOIN vista_aula_paralelo a ON a.id_aula_paralelo = i.aula_paralelo_id
							  WHERE e.id_estudiante = $id_estudiante AND i.gestion_id = $gestion")->fetch_first();
	//var_dump($estudiante);die;
	/*$estudiante = $db->query("SELECT 
							  FROM ")->fetch_first();*/
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
														
							<div class="row">
								<div class="user-avatar-address">
									<div class="row" style="margin-bottom: 1%;"> 
										<div class="col col-md-6 col-sm-6">
											<span> <b>Rude: </b> <?= ($estudiante['rude'] == "") ? "Sin Rude" : $estudiante['rude']; ?></span>
										</div>
										<div class="col col-md-6 col-sm-6">
											<span> <b> Código Estudiante: </b> <?= ($estudiante['codigo_estudiante'] == "") ? "Sin Codigo de Estudiate" : $estudiante['codigo_estudiante']; ?> </span>
										</div>
									</div>
									<div class="row" style="margin-bottom: 1%;">
										<div class="col col-md-6 col-sm-6">
											<span><b> Tipo de Documento: <?= ($estudiante['numero_documento'] == "") ? "Sin CI." : $estudiante['numero_documento']; ?></b></span>
										</div>
										<div class="col col-md-4 col-sm-6">
											<span><b>Género</b> <?= ($estudiante['genero'] == "v") ? "Varón" : "Mujer"; ?> </span>
										</div>
									</div>
									<div class="row" style="margin-bottom: 1%;">
										<div class="col col-md-6 col-sm-6">
											<b>Fecha de Nacimiento</b> <?= ($estudiante['fecha_nacimiento'] == "") ? "Sin Fecha de Nacimiento" : $estudiante['fecha_nacimiento']; ?> </span>
										</div>
										<div class="col col-md-6 col-sm-6">
											<span class=""><b>Dirección: </b><?= ($estudiante['direccion'] == "") ? "Sin Dirección" : $estudiante['direccion']; ?></span>
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

	<div class="dashboard-influence" style="margin-top: -7%">
		<div class="container-fluid dashboard-content">
			<!-- ============================================================== -->
			<!-- widgets   -->
			<!-- ============================================================== -->
			<div class="row">
				<!-- ============================================================== -->
				<!-- four widgets   -->
				<!-- ============================================================== -->
				<!-- ============================================================== -->
				<!-- total views   -->
				<!-- ============================================================== -->
				<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
					<div class="card">
						<div class="card-body">
							<div class="d-inline-block">
								<h5 class="text-muted">Asistencia</h5>
								<h2 class="mb-0"> 1</h2>
							</div>
							<div class="float-right icon-circle-medium  icon-box-lg  bg-info-light mt-1">
								<i class="fas fa-clipboard-check fa-fw fa-sm text-info"></i>
							</div>
						</div>
					</div>
				</div>
				<!-- ============================================================== -->
				<!-- end total views   -->
				<!-- ============================================================== -->
				<!-- ============================================================== -->
				<!-- total followers   -->
				<!-- ============================================================== -->
				<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
					<div class="card">
						<div class="card-body">
							<div class="d-inline-block">
								<h5 class="text-muted">Atrasos</h5>
								<h2 class="mb-0"> 2</h2>
							</div>
							<div class="float-right icon-circle-medium  icon-box-lg  bg-primary-light mt-1">
								<i class="fa fa-user fa-fw fa-sm text-primary"></i>
							</div>
						</div>
					</div>
				</div>
				<!-- ============================================================== -->
				<!-- end total followers   -->
				<!-- ============================================================== -->
				<!-- ============================================================== -->
				<!-- partnerships   -->
				<!-- ============================================================== -->
				<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
					<div class="card">
						<div class="card-body">
							<div class="d-inline-block">
								<h5 class="text-muted">Faltas</h5>
								<h2 class="mb-0">14</h2>
							</div>
							<div class="float-right icon-circle-medium  icon-box-lg  bg-secondary-light mt-1">
								<i class="fa fa-handshake fa-fw fa-sm text-secondary"></i>
							</div>
						</div>
					</div>
				</div>
				<!-- ============================================================== -->
				<!-- end partnerships   -->
				<!-- ============================================================== -->
				<!-- ============================================================== -->
				<!-- total earned   -->
				<!-- ============================================================== -->
				<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
					<div class="card">
						<div class="card-body">
							<div class="d-inline-block">
								<h5 class="text-muted">Permisos</h5>
								<h2 class="mb-0"> 9</h2>
							</div>
							<div class="float-right icon-circle-medium  icon-box-lg  bg-brand-light mt-1">
								<i class="fa fa-money-bill-alt fa-fw fa-sm text-brand"></i>
							</div>
						</div>
					</div>
				</div>
				<!-- ============================================================== -->
				<!-- end total earned   -->
				<!-- ============================================================== -->
			</div>
			<!-- ============================================================== -->
			<!-- end widgets   -->
			<!-- ============================================================== -->

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
										<th class="border-0">Ocupación</th>
										<th class="border-0">Dirección Oficina</th>
										<th class="border-0">Telefóno Oficina</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$familiar = $db->select('e.*')
										->from('vista_estudiante_familiar e')
										->where('e.id_estudiante', $estudiante['id_estudiante'])->fetch();
										foreach ($familiar as $key => $familia) {
			
									?>
										<tr>
											<td><?= escape($key + 1); ?></td>
											<td><?= escape($familia['primer_apellido']); ?></td>
											<td><?= escape($familia['segundo_apellido']); ?></td>
											<td><?= escape($familia['nombres']); ?></td>
											<td><?= escape($familia['profesion']); ?></td>
											<td><?= escape($familia['direccion_oficina']); ?></td>
											<td><?= escape($familia['telefono_oficina']); ?></td>

										</tr>

									<?php
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- ============================================================== -->
				<!-- end campaign activities   -->
				<!-- ============================================================== -->
			</div>

			<!-- ============================================================== -->
			<!-- influencer profile  -->
			<!-- ============================================================== -->
			<div class="row">
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
					<div class="row">
						<!-- ============================================================== -->
						<!-- followers by gender   -->
						<!-- ============================================================== -->
						<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
							<div class="card">
								<h5 class="card-header">Followers by Gender</h5>
								<div class="card-body">
									<div id="gender_donut" style="height: 230px;"></div>
								</div>
								<div class="card-footer p-0 bg-white d-flex">
									<div class="card-footer-item card-footer-item-bordered w-50">
										<h2 class="mb-0"> 60% </h2>
										<p>Female </p>
									</div>
									<div class="card-footer-item card-footer-item-bordered">
										<h2 class="mb-0">40% </h2>
										<p>Male </p>
									</div>
								</div>
							</div>
						</div>
						<!-- ============================================================== -->
						<!-- end followers by gender  -->
						<!-- ============================================================== -->

						<!-- ============================================================== -->
						<!-- followers by age   -->
						<!-- ============================================================== -->
						<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
							<div class="card">
								<h5 class="card-header">Followers by Age</h5>
								<div class="card-body">
									<div class="mb-3">
										<div class="d-inline-block">
											<h4 class="mb-0">15 - 20</h4>
										</div>
										<div class="progress mt-2 float-right progress-md">
											<div class="progress-bar bg-secondary" role="progressbar" style="width: 45%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</div>
									<div class="mb-3">
										<div class="d-inline-block">
											<h4 class="mb-0">20 - 25</h4>
										</div>
										<div class="progress mt-2 float-right progress-md">
											<div class="progress-bar bg-secondary" role="progressbar" style="width: 55%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</div>
									<div class="mb-3">
										<div class="d-inline-block">
											<h4 class="mb-0">25 - 30</h4>
										</div>
										<div class="progress mt-2 float-right progress-md">
											<div class="progress-bar bg-secondary" role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</div>
									<div class="mb-3">
										<div class="d-inline-block">
											<h4 class="mb-0">30 - 35</h4>
										</div>
										<div class="progress mt-2 float-right progress-md">
											<div class="progress-bar bg-secondary" role="progressbar" style="width: 35%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</div>
									<div class="mb-3">
										<div class="d-inline-block">
											<h4 class="mb-0">35 - 40</h4>
										</div>
										<div class="progress mt-2 float-right progress-md">
											<div class="progress-bar bg-secondary" role="progressbar" style="width: 21%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</div>
									<div class="mb-3">
										<div class="d-inline-block">
											<h4 class="mb-0">45 - 50</h4>
										</div>
										<div class="progress mt-2 float-right progress-md">
											<div class="progress-bar bg-secondary" role="progressbar" style="width: 85%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</div>
									<div class="mb-3">
										<div class="d-inline-block">
											<h4 class="mb-0">50 - 55</h4>
										</div>
										<div class="progress mt-2 float-right progress-md">
											<div class="progress-bar bg-secondary" role="progressbar" style="width: 25%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- ============================================================== -->
						<!-- end followers by age   -->
						<!-- ============================================================== -->

						<!-- ============================================================== -->
						<!-- followers by locations   -->
						<!-- ============================================================== -->
						<div class="col-xl-5 col-lg-12 col-md-6 col-sm-12 col-12">
							<div class="card">
								<h5 class="card-header">Top Folllowes by Locations </h5>
								<div class="card-body">
									<canvas id="chartjs_bar_horizontal"></canvas>
								</div>
							</div>
						</div>
						<!-- ============================================================== -->
						<!-- end followers by locations  -->
						<!-- ============================================================== -->
					</div>
				</div>
				<!-- ============================================================== -->
				<!-- end influencer profile  -->
				<!-- ============================================================== -->
			</div>
		</div>										
	</div>
</div>





</div>
<?php require_once show_template('footer-design'); ?>

<script src="<?= js; ?>/JsBarcode.all.min.js"></script>
<script src="<?= js; ?>/qrcode.min.js"></script>
<script>
$(function () {
	JsBarcode('.barcode').init();
	
})

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