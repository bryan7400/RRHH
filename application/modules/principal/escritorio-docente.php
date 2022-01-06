<?php
	//var_dump($_user['rol_id']);die;
	$rol_id = $_user['rol_id'];
?>
<?php require_once show_template('header-design'); ?>
<?php //require_once show_template('header-full'); ?>
<style>
.modal-xl {
	width: calc(100% - 30px);
	margin: 15px;
}
</style>
<?php 
	if($rol_id == 1 || $rol_id == 3){
?>

<div class="panel-heading">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Escritorio</strong>
	</h3>
</div>
<div class="panel-body">
	<div class="row">
		<div class="col-sm-4 col-md-3"> 
			<div class="well text-center">
				<h4 class="margin-none">Bienvenido al sistema!</h4>
				<p>
					<strong><?= escape($_user['username']); ?></strong>
				</p>
				<p>
					<img src="<?= ($_user['avatar'] == '') ? imgs . '/avatar.jpg' : files . '/profiles/' . $_user['avatar']; ?>" class="img-circle cursor-pointer" data-toggle="modal" data-target="#modal_mostrar" data-modal-title="Avatar" width="128" height="128">
				</p>
				<p class="margin-none">
					<?php if ($_user['email'] != '') : ?>
					<strong><?= escape($_user['email']); ?></strong>
					<br>
					<?php endif ?>
					<span class="text-success">en línea</span>
				</p>
			</div>
			<div class="list-group">
				<?php if ($_user['rol_id'] == 1) : ?>
				<a href="#" class="list-group-item" target="_blank">
					<span class="glyphicon glyphicon-download-alt"></span>
					<!--span>Descargar Aplicacion <b> << Educheck >></b></span-->
					<span class="glyphicon glyphicon-phone pull-right"></span>
				</a>
				<a href="application/storages/video.mp4" download="Reporte2Mayo2010">
				Descargar Archivo
				</a>
				<?php endif ?>
				<a href="#" class="list-group-item" data-restablecer="true">
					<span class="glyphicon glyphicon-th-large"></span>
					<span>Restablecer filtros</span>
					<span class="glyphicon glyphicon-menu-right pull-right"></span>
				</a>
				<a href="?/perfil/mostrar" class="list-group-item">
					<span class="glyphicon glyphicon-user"></span>
					<span>Mostrar mi perfil</span>
					<span class="glyphicon glyphicon-menu-right pull-right"></span>
				</a>
				<a href="?/sitio/salir" class="list-group-item">
					<span class="glyphicon glyphicon-lock"></span>
					<span>Cerrar mi sesión</span>
					<span class="glyphicon glyphicon-menu-right pull-right"></span>
				</a>
			</div>
		</div>
		<div class="col-sm-8 col-md-9">
			
		</div>
	</div>
</div>

<?php
	}else{
		if($rol_id == 5){

?>	
	<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"> 
        <div class="page-header">
            <h2 class="pageheader-title">Menú Principal</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Menú</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="panel-body">
	<!--lista a todos los cursos asignados-->
	<div class="row">
		<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12" onclick="location.href='?/e-agenda/agenda'" style="cursor:pointer;">	
			<div class="card">
				<div class="card-body">
					<div class="text-center">
						<img width="150" height="150" src="<?=imgs.'/calendar.png'?>" alt="">
					</div><br>
					<div class="metric-value" align="center">
						<h1 class="mb-1">Calendario</h1>
					</div>
					<a href="" style="display:block; width:100%; height:100%"></a>
				</div>
				<div id="sparkline-revenue"></div>
			</div>
		</div>
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12" onclick="location.href='?/e-agenda/pensum'" style="cursor:pointer;">	
			<div class="card">
				<div class="card-body">
					<div class="text-center">
						<img width="150" height="150" src="<?=imgs.'/pensum.png'?>" alt="">
					</div><br>
					<div class="metric-value" align="center">
						<h1 class="mb-1">Pensum</h1>
					</div>
					<a href="" style="display:block; width:100%; height:100%"></a>
				</div>
				<div id="sparkline-revenue"></div>
			</div>
		</div>
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12" onclick="location.href='?/e-agenda/notas'" style="cursor:pointer;">	
			<div class="card">
				<div class="card-body">
					<div class="text-center">
						<img width="150" height="150" src="<?=imgs.'/calificaciones.png'?>" alt="">
					</div><br>
					<div class="metric-value" align="center">
						<h1 class="mb-1">Calificaciones</h1>
					</div>
					<a href="" style="display:block; width:100%; height:100%"></a>
				</div>
				<div id="sparkline-revenue"></div>
			</div>
		</div>
	</div>
</div>



<?php
		}
	}
?>

<!-- Modal mostrar inicio -->
<!-- <div id="modal_mostrar" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content loader-wrapper">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<img src="" class="img-responsive img-rounded" data-modal-image="">
			</div>
			<div id="loader_mostrar" class="loader-wrapper-backdrop">
				<span class="loader"></span>
			</div>
		</div>
	</div>
</div>-->











<!-- Modal mostrar fin -->

<?php require_once show_template('footer-design'); ?>
<script>
$(function () {
	$('[data-restablecer]').on('click', function (e) {
		e.preventDefault();
		bootbox.confirm('¿Está seguro que desea restablecer todos los filtros que configuró?', function (result) {
			if (result) {
				for (var storage in localStorage) {
					if (storage.match(/DataTables/) || storage.match(/DataFilters/)) {
						localStorage.removeItem(storage)
					}
				}
			}
		});
	});

	var $modal_mostrar = $('#modal_mostrar'), $loader_mostrar = $('#loader_mostrar'), size, title, image;

	$modal_mostrar.on('hidden.bs.modal', function () {
		$loader_mostrar.show();
		$modal_mostrar.find('.modal-dialog').attr('class', 'modal-dialog');
		$modal_mostrar.find('.modal-title').text('');
	}).on('show.bs.modal', function (e) {
		if ($('.modal:visible').size() != 0) { e.preventDefault(); }
		size = $(e.relatedTarget).attr('data-modal-size');
		title = $(e.relatedTarget).attr('data-modal-title');
		image = $(e.relatedTarget).attr('src');
		size = (size) ? 'modal-dialog ' + size : 'modal-dialog';
		title = (title) ? title : 'Imagen';
		$modal_mostrar.find('.modal-dialog').attr('class', size);
		$modal_mostrar.find('.modal-title').text(title);
		$modal_mostrar.find('[data-modal-image]').attr('src', image);
	}).on('shown.bs.modal', function () {
		$loader_mostrar.hide();
	});
});
</script>
