<?php
	$id_postulante = (isset($_params[0])) ? $_params[0] : 0;

	$gestion=$_gestion['id_gestion'];
	/*$estudiante = $db->select('e.*, a*')
					 ->from('vista_estudiantes e')
					 ->join('ins_inscripcion i','i.estudiante_id = e.id_estudiante')
					 ->join('vista_aula_paralelo a','a.id_aula_paralelo = i.aula_paralelo_id')
					 ->where('e.id_estudiante', $id_estudiante)
					 ->where('i.gestion_id', $id_estudiante)
					 ->order_by('z.id_estudiante', 'asc')->fetch_first();*/
	$postulante = $db->query("SELECT p.*
							  FROM per_postulacion p
							  WHERE id_postulacion='".$id_postulante."'
							  ")->fetch_first();
	//var_dump($estudiante);die;
	/*$estudiante = $db->query("SELECT 
							  FROM ")->fetch_first();*/
?>

<?php require_once show_template('header-design'); ?>

<!-- ============================================================== -->
<!-- pageheader  -->
<!-- ============================================================== -->
<style>
.card-body{
	color:#999;
}
.card-body B{
	color:#71748d;
}
</style>	

<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Postulante</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="?/rrhh-postulantes/listar" class="breadcrumb-link">Postulantes</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Datos del Postulante</li>
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
							<img src="assets/imgs/avatar.jpg" alt="User Avatar" class="rounded-circle user-avatar-xxl">
						</div>
						
						<div class="col col-xl-9 col-lg-8 col-md-6 col-sm-12">
							<div class="row">
								<div class="user-avatar-name">
									<h2 class="mb-1"><?= $postulante['paterno'] . " " . $postulante['materno'] . " " .  $postulante['nombre']; ?></h2>
								</div>
							</div>
							
							<?php // ($estudiante['rude'] == "") ? "Sin Rude" : $estudiante['rude']; ?>

							<br>
							
							<div class="row" style="margin-bottom: 1%;"> 
								<div class="col col-md-3 col-sm-3">
									<span><b>Fecha de Nacimiento:</b> <?= $postulante['fecha_nacimiento']; ?> </span>
								</div>
								<div class="col col-md-3 col-sm-3">
									<span><b>Estado Civil:</b> <?= $postulante['estado_civil']; ?> </span>
								</div>
								<div class="col col-md-3 col-sm-3">
									<span><b>C.I.:</b> <?= $postulante['ci']; ?> </span>
								</div>
								<div class="col col-md-3 col-sm-3">
									<span><b>Expirado:</b> <?= $postulante['expirado']; ?> </span>
								</div>
							</div>																	
							
							<div class="row" style="margin-bottom: 1%;"> 
								<div class="col col-md-3 col-sm-3">
									<span><b>Direccion:</b> <?= $postulante['direccion']; ?> </span>
								</div>
								<div class="col col-md-3 col-sm-3">
									<span><b>Nro:</b> <?= $postulante['nro_direccion']; ?> </span>
								</div>
								<div class="col col-md-3 col-sm-3">
									<span><b>Zona:</b> <?= $postulante['zona']; ?> </span>
								</div>
								<div class="col col-md-3 col-sm-3">
									<span><b>Ciudad:</b> <?= $postulante['ciudad']; ?> </span>
								</div>
							</div>																	
							
							<div class="row" style="margin-bottom: 1%;"> 
								<div class="col col-md-12">
									<span><b>Lugar de Nacimiento</b></span>
								</div>										
							</div>									
							<div class="row" style="margin-bottom: 1%;"> 
								<div class="col col-md-4 col-sm-4">
									<span><b>Localidad:</b> <?= $postulante['localidad']; ?> </span>
								</div>
								<div class="col col-md-4 col-sm-4">
									<span><b>Provincia:</b> <?= $postulante['provincia']; ?> </span>
								</div>
								<div class="col col-md-4 col-sm-4">
									<span><b>Departamento:</b> <?= $postulante['departamento']; ?> </span>
								</div>
							</div>																	
							<div class="row" style="margin-bottom: 1%;"> 
								<div class="col col-md-4 col-sm-4">
									<span><b>Telefono:</b> <?= $postulante['telefono']; ?> </span>
								</div>
								<div class="col col-md-4 col-sm-4">
									<span><b>Celular:</b> <?= $postulante['celular']; ?> </span>
								</div>
								<div class="col col-md-4 col-sm-4">
									<span><b>Email:</b> <?= $postulante['email']; ?> </span>
								</div>
							</div>																	

							<div class="row" style="margin-bottom: 1%;"> 
								<div class="col col-md-6 col-sm-6">
									<span><b>AFP a la que aporta:</b> <?= $postulante['afp']; ?> </span>
								</div>
								<div class="col col-md-6 col-sm-6">
									<span><b>Numero de NUA:</b> <?= $postulante['nua']; ?> </span>
								</div>
							</div>																	

							<div class="row" style="margin-bottom: 1%;"> 
								<div class="col col-md-6 col-sm-6">
									<span><b>Nombre completo del (la) cónyuge:</b> <?= $postulante['conyuge']; ?> </span>
								</div>
								<div class="col col-md-6 col-sm-6">
									<span><b>Fecha de Nacimiento:</b> <?= $postulante['fecha_nacimiento_c']; ?> </span>
								</div>
							</div>

							<br>
							
							
							<div class="row">		                        
		                        <div class="col-sm-2 floatt2">
		                            <b>Dependientes</b>
		                        </div>
		                        <div class="col-sm-4 floatt2">
		                            <b>Nombres y Apellidos</b>
		                        </div>
		                        <div class="col-sm-2 floatt2">
		                            <b>Fecha de nacimiento</b>
		                        </div>
		                        <div class="col-sm-2 floatt2">
		                            <b>Genero</b>
		                        </div>
		                        <div class="col-sm-2 floatt2">
		                            <b>Grado de Instruccion</b>
		                        </div>
		                        
		                        <div style="clear: both;"></div>
		                    </div>
                    
                    		<?php
							$dependiente = $db->query("SELECT d.*
													  FROM per_postulacion_dependiente d
													  WHERE postulante_id='".$postulante['id_postulacion']."'
													  ")->fetch();

							foreach ($dependiente as $nro => $dep){
							?>
								<div class="row">		                        
			                        <div class="col-sm-2 floatt2">
			                            Hijo <?= ($nro+1) ?>
			                        </div>
			                        <div class="col-sm-4 floatt2">
			                            <?= $dep["nombre"] ?>
			                        </div>
			                        <div class="col-sm-2 floatt2">
			                            <?= $dep["nombre"] ?>
			                        </div>
			                        <div class="col-sm-2 floatt2">
			                            <?= $dep["genero"] ?>
			                        </div>
			                        <div class="col-sm-2 floatt2">
			                            <?= $dep["grado"] ?>
			                        </div>
			                        
			                        <div style="clear: both;"></div>
			                    </div>
							<?php
							} 
							?>
                            

							<br>
							<div class="row">
								<div class="user-avatar-name">
									<h2 class="mb-1">B) DATOS DENOMINACIONALES (solo miembros de la IASD)</h2>
								</div>
							</div>

							<div class="row" style="margin-bottom: 1%;"> 
								<div class="col col-md-6 col-sm-6">
									<span><b>Fecha de bautismo:</b> <?= $postulante['fecha_bautismo']; ?> </span>
								</div>
								<div class="col col-md-6 col-sm-6">
									<span><b>Pastor oficiciante:</b> <?= $postulante['pastor']; ?> </span>
								</div>
							</div>
							<div class="row" style="margin-bottom: 1%;"> 
								<div class="col col-md-6 col-sm-6">
									<span><b>Iglesia/congrg./filial a la que se congrega:</b> <?= $postulante['iglesia']; ?> </span>
								</div>
								<div class="col col-md-6 col-sm-6">
									<span><b>Distrito:</b> <?= $postulante['distrito']; ?> </span>
								</div>
							</div>	


							<br>
							<div class="row">
								<div class="user-avatar-name">
									<h2 class="mb-1">C) DATOS PROFESIONALES (Solo para docentes de carrera)</h2>
								</div>
							</div>

							<div class="row" style="margin-bottom: 1%;"> 
								<div class="col col-md-12 col-sm-12">
									<span><b>Años de servicio de en la Educación Fiscal:</b></span>
								</div>
							</div>
							<div class="row" style="margin-bottom: 1%;"> 
								<div class="col col-md-6 col-sm-6">
									<span><b>Categoria del escalafón del Estado:</b> <?= $postulante['escalafon']; ?> </span>
								</div>
								<div class="col col-md-6 col-sm-6">
									<span><b>Fecha:</b> <?= $postulante['fecha_escalafon']; ?> </span>
								</div>
							</div>	
							<div class="row" style="margin-bottom: 1%;"> 
								<div class="col col-md-6 col-sm-6">
									<span><b>Unidad educativa fiscal o privada (actual):</b> <?= $postulante['unidad']; ?> </span>
								</div>
								<div class="col col-md-6 col-sm-6">
									<span><b>Turno:</b> <?= $postulante['turno']; ?> </span>
								</div>
							</div>	
							<div class="row" style="margin-bottom: 1%;"> 
								<div class="col col-md-6 col-sm-6">
									<span><b>Área o Asignatura (actual):</b> <?= $postulante['asignatura']; ?> </span>
								</div>
								<div class="col col-md-6 col-sm-6">
									<span><b>Periodos:</b> <?= $postulante['periodos']; ?> </span>
								</div>
							</div>	

                    		<br>
							<div class="row">
								<div class="user-avatar-name">
									<h2 class="mb-1">D) FORMACIÓN ACADEMICA Y FORMACIÓN CONTINUA</h2>
								</div>
							</div>

							<div class="row">		                        
		                        <div class="col-sm-2 floatt2">
		                            <b>Nivel</b>
		                        </div>
		                        <div class="col-sm-3 floatt2">
		                            <b>Area academica o especialidad</b>
		                        </div>
		                        <div class="col-sm-2 floatt2">
		                            <b>Fecha del titulo</b>
		                        </div>
		                        <div class="col-sm-2 floatt2">
		                            <b>Institucion</b>
		                        </div>
		                        <div class="col-sm-3 floatt2">
		                            <b>Observacion / Carga horaria</b>
		                        </div>
		                        
		                        <div style="clear: both;"></div>
		                    </div>
                    
							<?php
							$dependiente = $db->query("SELECT d.*
													  FROM per_postulacion_formacion d
													  WHERE postulante_id='".$postulante['id_postulacion']."'
													  ")->fetch();

							foreach ($dependiente as $nro => $dep){
							?>
								<div class="row">		                        
			                        <div class="col-sm-2 floatt2">
			                            <?= $dep["nivel"] ?>
			                        </div>
			                        <div class="col-sm-3 floatt2">
			                            <?= $dep["especialidad"] ?>
			                        </div>
			                        <div class="col-sm-2 floatt2">
			                            <?= $dep["fecha"] ?>
			                        </div>
			                        <div class="col-sm-2 floatt2">
			                            <?= $dep["institucion"] ?>
			                        </div>
			                        <div class="col-sm-3 floatt2">
			                            <?= $dep["observacion"] ?>
			                        </div>			                        
			                        <div style="clear: both;"></div>
			                    </div>
							<?php
							} 
							?>
                            







                    		<br>
							<div class="row">
								<div class="user-avatar-name">
									<h2 class="mb-1">E) OTROS CONOCIMIENTOS Y HABILIDADES ESPECÍFICAS</h2>
								</div>
							</div>
                    
                    		<div class="row">		                        
		                        <div class="col-sm-4 floatt2">
		                            <b>Item / Area</b>
		                        </div>
		                        <div class="col-sm-4 floatt2">
		                            <b>Descripcion del conocimiento / Habilidad</b>
		                        </div>
		                        <div class="col-sm-4 floatt2">
		                            <b>Institucion</b>
		                        </div>
		                        
		                        <div style="clear: both;"></div>
		                    </div>
                    
							<?php
							$dependiente = $db->query("SELECT d.*
													  FROM per_postulacion_conocimiento d
													  WHERE postulante_id='".$postulante['id_postulacion']."'
													  ")->fetch();

							foreach ($dependiente as $nro => $dep){
							?>
								<div class="row">		                        
			                        <div class="col-sm-4 floatt2">
			                            <?= $dep["item"] ?>
			                        </div>
			                        <div class="col-sm-4 floatt2">
			                            <?= $dep["habilidad"] ?>
			                        </div>
			                        <div class="col-sm-4 floatt2">
			                            <?= $dep["institucion"] ?>
			                        </div>
			                        <div style="clear: both;"></div>
			                    </div>
							<?php
							} 
							?>
                            
							<br>
							<br>
							<div class="row">		                        
			                        <div class="col-sm-4 floatt2">
			                            
			                        </div>
			                        <?php if ( $postulante['archivo_documento']) : ?>
			                        <div class="col-sm-4 floatt2">
			                            <a href="files/demoeducheck/rrhh/postulantes/<?= $postulante['archivo_documento']?>" class='btn btn-dark btn-lg'  role='button' download>Descargar Curriculum <i class='fa fa-download'></i></a>
			                        </div>

			                         <?php else : ?>


    <?php endif ?>
			                        <div class="col-sm-4 floatt2">
			                            
			                        </div>
			                        <div style="clear: both;"></div>
			                    </div>



							<br>
						</div>
					</div>									
				</div>
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



</script>