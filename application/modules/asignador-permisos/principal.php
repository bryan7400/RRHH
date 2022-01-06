<?php
 
// Obtiene los roles
$roles = $db->get('sys_roles');

?>
<?php require_once show_template('header-design'); ?>
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Asignación de Permisos </h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Desarrollo</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Asignador de Permisos</a></li>
                        <!-- <li class="breadcrumb-item active" aria-current="page">Listar</li> -->
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
    <!-- ============================================================== -->
    <!-- row -->
    <!-- ============================================================== -->
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <!-- <h5 class="card-header">Generador de menús</h5> -->
            <div class="card-header">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                        <div class="text-label hidden-xs">Seleccione el rol al que desea asignarle los permisos:</div>
                    </div>
                    <!-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
                        <div class="btn-group">
                             <div class="input-group">
                                <div class="input-group-append be-addon">
                                    <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item">Seleccionar acción</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="?/s-gestion-escolar/crear" class="dropdown-item">Crear Gestión</a>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div> -->
                </div>
            </div>
            
            <div class="card-body">
                <!-- ============================================================== -->
                <!-- datos --> 
                <!-- ============================================================== -->
            

				<?php if ($roles) : ?>
				<div class="list-group">
					<?php foreach ($roles as $nro => $rol) : ?>
					<a href="?/asignador-permisos/asignar/<?= $rol['id_rol']; ?>" class="list-group-item">
						<strong class="list-group-item-heading"><?= escape($rol['rol']); ?></strong>
						<p class="list-group-item-text"><?= ($rol['descripcion'] == '') ? 'No asignado' : escape($rol['descripcion']); ?></p>
					</a>
					<?php endforeach ?>
				</div>
				<?php else : ?>
				<div class="alert alert-info">
					<strong>Atención!</strong>
					<ul>
						<li>No existen roles registrados en la base de datos.</li>
						<li>En consecuencia no puede realizar la asignación de permisos.</li>
					</ul>
				</div>
				<?php endif ?>
                <!-- ============================================================== -->
                <!-- end datos -->
                <!-- ============================================================== -->
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- end row -->
<!-- ============================================================== --> 

<?php require_once show_template('footer-design'); ?>