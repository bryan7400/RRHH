<?php

// Obtiene la cadena csrf 
$csrf = set_csrf();

?>
<?php require_once show_template('header-design'); ?>
<link rel="stylesheet" href="<?= css; ?>/jquery.guillotine.min.css">
<style>
@media (min-width: 768px) {
	.table-display > .tbody > .tr > .td,
	.table-display > .tbody > .tr > .th,
	.table-display > .tfoot > .tr > .td,
	.table-display > .tfoot > .tr > .th,
	.table-display > .thead > .tr > .td,
	.table-display > .thead > .tr > .th {
		padding-bottom: 15px;
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
<div class="panel-heading">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Perfil de usuario</strong>
	</h3>
</div>
<div class="panel-body">
	<?php if ($message = get_notification()) : ?>
	<div class="alert alert-<?= $message['type']; ?>">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong><?= $message['title']; ?></strong>
		<p><?= $message['content']; ?></p>
	</div>
	<?php endif ?>
	<div class="row">
		<div class="col-sm-4 col-md-3">
			<img src="<?= ($_user['avatar'] == '') ? imgs . '/avatar.jpg' : files . '/profiles/' . $_user['avatar']; ?>" class="img-responsive thumbnail cursor-pointer" data-toggle="modal" data-target="#modal_mostrar" data-modal-title="Avatar">
			<div class="list-group">
				<a href="#" class="list-group-item text-ellipsis" data-toggle="modal" data-target="#modal_subir" data-backdrop="static" data-keyboard="false">
					<span class="glyphicon glyphicon-picture"></span>
					<span>Subir avatar</span>
				</a>
				<a href="#" class="list-group-item text-ellipsis" data-toggle="modal" data-target="#modal_cambiar" data-backdrop="static" data-keyboard="false">
					<span class="glyphicon glyphicon-lock"></span>
					<span>Cambiar contraseña</span>
				</a>
				<a href="#" class="list-group-item text-ellipsis" data-toggle="modal" data-target="#modal_general" data-backdrop="static" data-keyboard="false">
					<span class="glyphicon glyphicon-user"></span>
					<span>Modificar información general</span>
				</a>
				<a href="?/perfil/eliminar" class="list-group-item text-ellipsis" data-eliminar="true">
					<span class="glyphicon glyphicon-eye-close"></span>
					<span>Eliminar avatar</span>
				</a>
			</div>
		</div>
		<div class="col-sm-8 col-md-9">
			<div class="well text-justify">
				<h4 class="margin-none"><?= escape($_user['username']); ?></h4>
				<p class="margin-none"><?= escape($_user['rol']); ?></p>
			</div>
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#general" data-toggle="tab">
						<span class="glyphicon glyphicon-user"></span>
						<span class="hidden-xs">Información general</span>
					</a>
				</li>
			</ul>
			<div class="tab-content">
				<div id="general" class="tab-pane fade in active">
					<p class="lead"><strong>Información general</strong></p>
					<hr>
					<div class="table-display">
						<div class="tbody">
							<div class="tr">
								<div class="th">Nombre de usuario:</div>
								<div class="td"><?= escape($_user['username']); ?></div>
							</div>
							<div class="tr">
								<div class="th">Correo electrónico:</div>
								<div class="td"><?= ($_user['email'] != '') ? escape($_user['email']) : 'No asignado'; ?></div>
							</div>
							<div class="tr">
								<div class="th">Rol de usuario:</div>
								<div class="td"><?= escape($_user['rol']); ?></div>
							</div>
							<div class="tr">
								<div class="th">Usuario activo:</div>
								<div class="td"><?= ($_user['active'] == 's') ? 'Si' : 'No'; ?></div>
							</div>
							<?php $fecha = explode(' ', $_user['login_at']) ?>
							<div class="tr">
								<div class="th">Último inicio de sesión:</div>
								<div class="td"><?= date_decode($fecha[0], $_format) . ' &mdash; ' . $fecha[1]; ?></div>
							</div>
							<div class="tr">
								<div class="th">Tiempo de actividad:</div>
								<div class="td"><?= moment($_user['login_at']); ?></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal subir inicio -->
<div id="modal_subir" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/perfil/subir" enctype="multipart/form-data" id="form_subir" class="modal-content loader-wrapper" autocomplete="off">
			<input type="hidden" name="<?= $csrf; ?>">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Subir avatar</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="avatar_subir" class="control-label">Avatar:</label>
					<input type="file" name="avatar" id="avatar_subir" class="form-control" data-validation="required mime size dimension" data-validation-allowing="jpg, png" data-validation-max-size="4M" data-validation-dimension="max1920">
					<input type="text" value="" name="data" id="data_subir" class="translate" tabindex="-1" data-validation="required" data-validation-error-msg="El campo no es válido">
				</div>
				<div class="row" data-guillotine-element="container">
					<div class="col-sm-7">
						<div class="thumbnail">
							<img id="image_subir" src="">
						</div>
					</div>
					<div class="col-sm-5">
						<div class="list-group margin-none">
							<a href="#" class="list-group-item" data-guillotine-action="fit">
								<span class="glyphicon glyphicon-fullscreen"></span>
								<span>Tamaño completo</span>
							</a>
							<a href="#" class="list-group-item" data-guillotine-action="center">
								<span class="glyphicon glyphicon-align-center"></span>
								<span>Centrar imagen</span>
							</a>
							<a href="#" class="list-group-item" data-guillotine-action="zoomIn">
								<span class="glyphicon glyphicon-zoom-in"></span>
								<span>Aumentar tamaño</span>
							</a>
							<a href="#" class="list-group-item" data-guillotine-action="zoomOut">
								<span class="glyphicon glyphicon-zoom-out"></span>
								<span>Reducir tamaño</span>
							</a>
							<a href="#" class="list-group-item" data-guillotine-action="rotateLeft">
								<span class="glyphicon glyphicon-menu-left"></span>
								<span>Girar a izquierda</span>
							</a>
							<a href="#" class="list-group-item" data-guillotine-action="rotateRight">
								<span class="glyphicon glyphicon-menu-right"></span>
								<span>Girar a derecha</span>
							</a>
							<a href="#" class="list-group-item active" data-guillotine-action="getData">
								<span class="glyphicon glyphicon-floppy-disk"></span>
								<span>Guardar cambios</span>
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default">
					<span class="glyphicon glyphicon-search"></span>
					<span>Visualizar</span>
				</button>
			</div>
			<div id="loader_subir" class="loader-wrapper-backdrop hidden">
				<span class="loader"></span>
			</div>
		</form>
	</div>
</div>
<!-- Modal subir fin -->

<!-- Modal cambiar inicio -->
<div id="modal_cambiar" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/perfil/cambiar" id="form_cambiar" class="modal-content loader-wrapper" autocomplete="off">
			<input type="hidden" name="<?= $csrf; ?>">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Cambiar contraseña</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="password_confirmation" class="control-label">Contraseña:</label>
					<input type="password" name="password_confirmation" id="password_confirmation" class="form-control" data-validation="required length strength" data-validation-length="5-30" data-validation-strength="2">
				</div>
				<div class="form-group">
					<label for="password" class="control-label">Confirmar contraseña:</label>
					<input type="password" name="password" id="password" class="form-control" data-validation="confirmation">
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-danger">
					<span class="glyphicon glyphicon-floppy-disk"></span>
					<span>Guardar</span>
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

<!-- Modal general inicio -->
<div id="modal_general" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/perfil/general" id="form_general" class="modal-content loader-wrapper" autocomplete="off">
			<input type="hidden" name="<?= $csrf; ?>">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Modificar información general</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="username" class="control-label">Nombre de usuario:</label>
					<input type="text" name="username" value="<?= $_user['username']; ?>" id="username" class="form-control" data-validation="required alphanumeric" data-validation-allowing="_">
				</div>
				<div class="form-group">
					<label for="email" class="control-label">Correo electrónico:</label>
					<input type="text" name="email" value="<?= $_user['email']; ?>" id="email" class="form-control" data-validation="email" data-validation-optional="true">
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-danger">
					<span class="glyphicon glyphicon-floppy-disk"></span>
					<span>Guardar</span>
				</button>
				<button type="reset" class="btn btn-default">
					<span class="glyphicon glyphicon-refresh"></span>
					<span>Restablecer</span>
				</button>
			</div>
			<div id="loader_general" class="loader-wrapper-backdrop hidden">
				<span class="loader"></span>
			</div>
		</form>
	</div>
</div>
<!-- Modal general fin -->

<!-- Modal mostrar inicio -->
<div id="modal_mostrar" class="modal fade" tabindex="-1">
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
</div>
<!-- Modal mostrar fin -->

<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.guillotine.min.js"></script>
<script>
$(function () {
	var $modal_subir = $('#modal_subir'), $form_subir = $('#form_subir'), $loader_subir = $('#loader_subir'), $element_subir = $modal_subir.find('[data-guillotine-element="container"]'), $action_subir = $modal_subir.find('[data-guillotine-action]'), $image_subir = $('#image_subir'), $avatar_subir = $('#avatar_subir'), $data_subir = $('#data_subir');

	$.validate({
		form: '#form_subir',
		modules: 'file',
		onSuccess: function () {
			$loader_subir.removeClass('hidden');
		}
	});

	$modal_subir.on('hidden.bs.modal', function () {
		$form_subir.trigger('reset');
		$element_subir.hide();
	}).on('show.bs.modal', function (e) {
		if ($('.modal:visible').size() != 0) { e.preventDefault(); }
	});

	$avatar_subir.on('validation', function (e, valid) {
		if (valid) {
			var input = $(this).get(0);
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function (e) {
					$image_subir.attr('src', e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			}
		} else {
			$element_subir.hide();
		}
	}).on('change', function () {
		$element_subir.hide();
	});

	$image_subir.on('load', function () {
		$image_subir.guillotine('remove');
		$image_subir.guillotine({
			width: 720,
			height: 720
		});
		$image_subir.guillotine('fit');
		$element_subir.show();
	});

	$action_subir.on('click', function (e) {
		e.preventDefault();
		var data, scale, action = $(this).attr('data-guillotine-action');
		if (action != 'getData') {
			if (action == 'zoomIn') {
				data = $image_subir.guillotine('getData');
				scale = data.scale;
				if (scale <= 2) {
					$image_subir.guillotine(action);
				}
			} else {
				$image_subir.guillotine(action);
			}
		} else {
			data = $image_subir.guillotine(action);
			data = JSON.stringify(data);
			$data_subir.val(data);
			$form_subir.submit();
		}
	});

	$modal_subir.trigger('hidden.bs.modal');

	var $modal_cambiar = $('#modal_cambiar'), $form_cambiar = $('#form_cambiar'), $loader_cambiar = $('#loader_cambiar');

	$.validate({
		form: '#form_cambiar',
		modules: 'security',
		onSuccess: function () {
			$loader_cambiar.removeClass('hidden');
		}
	});

	$modal_cambiar.on('hidden.bs.modal', function () {
		$form_cambiar.trigger('reset');
	}).on('show.bs.modal', function (e) {
		if ($('.modal:visible').size() != 0) { e.preventDefault(); }
	}).on('shown.bs.modal', function () {
		$form_cambiar.find('.form-control:nth(0)').focus();
	});

	var $modal_general = $('#modal_general'), $form_general = $('#form_general'), $loader_general = $('#loader_general');

	$.validate({
		form: '#form_general',
		onSuccess: function () {
			$loader_general.removeClass('hidden');
		}
	});

	$modal_general.on('hidden.bs.modal', function () {
		$form_general.trigger('reset');
	}).on('show.bs.modal', function (e) {
		if ($('.modal:visible').size() != 0) { e.preventDefault(); }
	}).on('shown.bs.modal', function () {
		$form_general.find('.form-control:nth(0)').focus();
	});

	$('[data-eliminar]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var csrf = '<?= $csrf; ?>';
		bootbox.confirm('¿Está seguro que desea eliminar su avatar?', function (result) {
			if (result) {
				$.request(href, csrf);
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
<?php require_once show_template('footer-design'); ?>