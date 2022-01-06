<?php

// Obtiene los parametros
$id_familiar = (isset($_params[0])) ? $_params[0] : 0;

//obtiene la gestion actual
$id_gestion = $_gestion['id_gestion'];

// Obtiene el familiar
 $_familiar =  $db->query("SELECT *
                            FROM ins_familiar AS sf
                            INNER JOIN sys_persona AS sp ON sp.id_persona = sf.persona_id
                            WHERE sf.id_familiar = $id_familiar")->fetch_first();
//   echo $familiar['segundo_apellido'];
// // var_dump($familiar);
//  exit();


?>
<?php require_once show_template('header-design'); ?>
<!-- ============================================================== -->
<!-- pageheader  -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Perfil Familiar</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Inscripciones</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Perfil Familiar</li>
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
	<div class="dashboard-influence"">
		<div class="container-fluid dashboard-content">
			<div class="card influencer-profile-data">
				<div class="card-body">
					<div class="row">
						<div class="col col-xl-3 col-lg-4 col-md-6 col-sm-12 text-center">
							<img src="<?= ($_familiar['foto'] == '') ? 'assets/imgs/avatar.jpg' : $_familiar['foto'] ?>" alt="User Avatar" class="rounded-circle user-avatar-xxl">
						</div>
						<div class="col col-xl-5 col-lg-4 col-md-6 col-sm-12">
							<div class="row">
								<div class="user-avatar-name">
									<h2 class="mb-1"><?= $_familiar['primer_apellido'] . " " . $_familiar['segundo_apellido'] . " " .  $_familiar['nombres']; ?></h2>
								</div>
							</div>
							<div class="row">
								<div class="user-avatar-address">
									<div class="row" style="margin-bottom: 1%;"> 
										<div class="col col-md-6 col-sm-6">
											<span> <b> Código Familiar: </b> <?= ($_familiar['codigo_familia'] == "") ? "Sin Codigo de Familia" : $_familiar['codigo_familia']; ?> </span>
										</div>
									</div>
									<div class="row" style="margin-bottom: 1%;">
										<div class="col col-md-6 col-sm-6">
											<span><b> Número de Documento: </b> <?= ($_familiar['numero_documento'] == "") ? "Sin CI." : $_familiar['numero_documento']; ?></span>
										</div>
										<div class="col col-md-4 col-sm-6">
											<span><b>Género</b> <?= ($_familiar['genero'] == "v") ? "Varón" : "Mujer"; ?> </span>
										</div>
									</div>
									<div class="row" style="margin-bottom: 1%;">
										<div class="col col-md-6 col-sm-6">
											<b>Fecha de Nacimiento</b> <?= ($_familiar['fecha_nacimiento'] == "") ? "Sin Fecha de Nacimiento" : $_familiar['fecha_nacimiento']; ?> </span>
										</div>
										<div class="col col-md-6 col-sm-6">
											<span class=""><b>Dirección: </b><?= ($_familiar['direccion'] == "") ? "Sin Dirección" : $_familiar['direccion']; ?></span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col col-xl-4 col-lg-4 col-md-6 col-sm-12">
							<!--div class="thumbnail hidden" data-print-code="true">
								<img class="barcode img-responsive" jsbarcode-format="code128" jsbarcode-value="<?= substr($_familiar['numero_documento'], 2); ?>" jsbarcode-displayValue="true" jsbarcode-width="2" jsbarcode-height="64" jsbarcode-margin="0" jsbarcode-textMargin="-3" jsbarcode-fontSize="20" jsbarcode-lineColor="#333">
							</div-->
							<div class="" align="center">
								<div class="" id="qr">
								</div>
								<div><?= escape($_familiar['codigo_familia']); ?></div>
							</div>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>

<br><br>


	<div class="dashboard-influence" style="margin-top: -10%">
		<div class="container-fluid dashboard-content">

			<div class="row">
				<!-- ============================================================== -->
				<!-- campaign activities   -->
				<!-- ============================================================== -->
				<div class="col-lg-12">
					<div class="section-block">
						<h3 class="section-title">Estudiantes a Cargo</h3>
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
										<th class="border-0">Curso</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$estudiantes = $db->query("SELECT sp.primer_apellido, sp.segundo_apellido, sp.nombres, CONCAT(ia.nombre_aula ,' ', ip.nombre_paralelo,' - ' ,ina.nombre_nivel, ' - TURNO ', it.nombre_turno) AS curso
                                                                    FROM ins_familiar AS sf
                                                                    INNER JOIN ins_estudiante_familiar AS ief ON ief.familiar_id = sf.id_familiar
                                                                    INNER JOIN ins_estudiante AS ie ON ie.id_estudiante = ief.estudiante_id
                                                                    INNER JOIN ins_inscripcion AS ii ON ii.estudiante_id = ie.id_estudiante
                                                                    INNER JOIN ins_aula_paralelo AS iap ON iap.id_aula_paralelo = ii.aula_paralelo_id
                                                                    INNER JOIN ins_aula AS ia ON ia.id_aula = iap.aula_id
                                                                    INNER JOIN ins_nivel_academico AS ina ON ina.id_nivel_academico = ia.nivel_academico_id
                                                                    INNER JOIN ins_paralelo AS ip ON ip.id_paralelo = iap.paralelo_id
                                                                    INNER JOIN ins_turno AS it ON it.id_turno = iap.turno_id
                                                                    INNER JOIN sys_persona AS sp ON sp.id_persona = ie.persona_id
                                                                    WHERE sf.id_familiar = $id_familiar AND ia.gestion_id = $id_gestion AND ia.estado = 'A' AND iap.estado = 'A' AND ii.estado = 'A' AND ii.gestion_id = $id_gestion")->fetch();
										foreach ($estudiantes as $key => $estudiante) {
			
									?>
										<tr>
											<td><?= escape($key + 1); ?></td>
											<td><?= escape($estudiante['primer_apellido']); ?></td>
											<td><?= escape($estudiante['segundo_apellido']); ?></td>
											<td><?= escape($estudiante['nombres']); ?></td>
											<td><?= escape($estudiante['curso']); ?></td>
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
			
		</div>										
	</div>
</div>

<script src="<?= js; ?>/JsBarcode.all.min.js"></script>
<script src="<?= js; ?>/qrcode.min.js"></script>

<script>
$(function () {
	JsBarcode('.barcode').init();
});
var qrcode = new QRCode('qr',{
	text: "<?= $_familiar['codigo_familia']; ?>",
	
    width: 150,
    height: 150,
    colorDark : "#000000",
    colorLight : "#ffffff",
    correctLevel : QRCode.CorrectLevel.H
})
</script>