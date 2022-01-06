<?php

// Obtiene el menu de herramientas
$_herramientas = json_decode(@file_get_contents(storages . '/herramientas.json'), true);

// Obtiene los menus
$_menus = $db->select('m.*, p.archivos')->from('sys_permisos p')->join('sys_menus m', 'p.menu_id = m.id_menu')->where('p.rol_id', $_SESSION[user]['rol_id'])->where('m.id_menu != ', 0)->order_by('m.menu', 'asc')->fetch();

// Construye la barra de menus
$_menus = construir_menu($_menus); 

?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="mobile-web-app-capable" content="yes">
		<title><?= $_name; ?></title> 
		<link rel="stylesheet" href="<?= css; ?>/bootstrap.min.css">
		<link rel="stylesheet" href="<?= css; ?>/bootstrap-submenu.min.css">
		<link rel="stylesheet" href="<?= css; ?>/bootstrap-datetimepicker.min.css">
		<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">
		<link rel="stylesheet" href="<?= css; ?>/bootstrap-lightbox.min.css">
		<link rel="stylesheet" href="<?= css; ?>/bootstrap-loader.min.css">
		<link rel="stylesheet" href="<?= css; ?>/bootstrap-glyphicons.min.css">
		<link rel="stylesheet" href="<?= css; ?>/jquery.treegrid.min.css">
		<link rel="stylesheet" href="<?= css; ?>/dataTables.bootstrap.min.css">
		<link rel="stylesheet" href="<?= css; ?>/bootstrap-structured.min.css">
		<link rel="stylesheet" href="<?= themes . '/' . $_theme; ?>/style.min.css">
		<link rel="stylesheet" href="<?= css; ?>/animate.min.css">
		<!--[if lt IE 9]>
			<script src="<?= js; ?>/html5shiv.min.js"></script>
			<script src="<?= js; ?>/respond.min.js"></script>
		<![endif]--> 
		<script src="<?= js; ?>/jquery.min.js"></script>
		<script src="<?= js; ?>/modernizr.min.js"></script>
		<script src="<?= js; ?>/moment.min.js"></script>
		<script src="<?= js; ?>/moment.es.js"></script>
		<script src="<?= js; ?>/bootstrap.min.js"></script>
		<script src="<?= js; ?>/bootbox.min.js"></script>
		<script src="<?= js; ?>/bootstrap-submenu.min.js"></script>
		<script src="<?= js; ?>/bootstrap-lightbox.min.js"></script>
		<script src="<?= js; ?>/bootstrap-filestyle.min.js"></script>
		<script src="<?= js; ?>/jquery.nicescroll.min.js"></script> 
		<script src="<?= js; ?>/jquery.request.min.js"></script>
		<script src="<?= js; ?>/animo.min.js"></script>
		<script src="<?= js; ?>/annyang.min.js"></script>
		<script src="<?= js; ?>/docs.min.js"></script>
		<link rel="icon" type="image/png" href="<?= project; ?>/favicon.png">
	</head>
	<body>
		<?php if (environment == 'production') : ?>
		<div id="loader" class="loader-wrapper loader-wrapper-fixed">
			<div class="loader-wrapper-backdrop">
				<span class="loader"></span>
			</div>
		</div>
		<?php endif ?>
		<div id="navbar" class="navbar navbar-fixed-top navbar-inverse menu">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="icon-bar"></span> 
						<span class="icon-bar"></span>  
						<span class="icon-bar"></span> 
					</button>
					<!-- <a href="<?= index_private; ?>" class="navbar-brand">
						<?php if ($_icon != '') : ?>
						<span class="<?= $_icon; ?>"></span>
						<?php endif ?> 
						<span><?= $_name; ?></span>
					</a> -->
					<a href="<?= index_private; ?>" class="navbar-brand">
						<img src="<?= imgs . '/logo.png'; ?>" height="30" style="margin-top: -5px;">
					</a>
				</div>
				<div class="navbar-collapse collapse menu">
					<ul class="nav navbar-nav">
						<?php if (environment == 'development' && $_herramientas && $_user['rol_id'] == 1 && $_user['visible'] == 'n') : ?>
						<li class="dropdown">
							<a href="#" data-toggle="dropdown">
								<span class="glyphicon glyphicon-flash"></span>
								<span class="hidden-sm">Desarrollo</span>
								<span class="glyphicon glyphicon-menu-down visible-xs-inline pull-right"></span>
							</a>
							<ul class="dropdown-menu">
								<li class="dropdown-header visible-sm-block">Desarrollo</li>
								<?php foreach ($_herramientas as $_herramienta) : ?>
								<li>
									<a href="<?= $_herramienta['ruta']; ?>">
										<span class="<?= $_herramienta['icono']; ?>"></span>
										<span><?= $_herramienta['menu']; ?></span>
									</a>
								</li>
								<?php endforeach ?>
							</ul>
						</li>
						<?php endif ?>
						<?= $_menus; ?>
					</ul>
					<ul class="nav navbar-nav navbar-right hidden-xs"></ul>
					<ul class="nav navbar-nav navbar-right">
						<?php if ($_clock == 's') : ?>
						<li class="navbar-text hidden-xs hidden-md">
							<span class="glyphicon glyphicon-calendar"></span>
							<strong data-datetime="date"><?= date_decode(date('Y-m-d'), $_format); ?></strong>
						</li>
						<li class="navbar-text hidden-xs hidden-md">
							<span class="glyphicon glyphicon-time"></span>
							<strong data-datetime="time"><?= date('H:i:s'); ?></strong>
						</li>
						<?php endif ?>
						<li class="dropdown">
							<a href="#" data-toggle="dropdown">
								<img src="<?= ($_user['avatar'] == '') ? imgs . '/avatar.jpg' : files . '/profiles/' . $_user['avatar']; ?>" class="img-circle hidden-xs" width="20" height="20">
								<span class="glyphicon glyphicon-user visible-xs-inline"></span>
								<span class="text-capitalize hidden-sm hidden-md"><?= escape($_user['username']); ?></span>
								<span class="glyphicon glyphicon-menu-down visible-xs-inline pull-right"></span>
								<span class="glyphicon glyphicon-menu-down hidden-xs"></span>
							</a>
							<ul class="dropdown-menu">
								<li class="dropdown-header visible-sm-block visible-md-block">
									<span class="text-capitalize"><?= escape($_user['username']); ?></span>
								</li>
								<li>
									<a href="<?= index_private; ?>">
										<span class="glyphicon glyphicon-home"></span>
										<span>Página principal</span>
									</a>
								</li>
								<li>
									<a href="?/perfil/mostrar">
										<span class="glyphicon glyphicon-user"></span>
										<span>Perfil de usuario</span>
									</a>
								</li>
								<li>
									<a href="?/<?= site; ?>/salir">
										<span class="glyphicon glyphicon-log-out"></span>
										<span>Salir del sistema</span>
									</a>
								</li>
								<?php if ($_clock == 's') : ?>
								<li class="divider visible-xs-block visible-md-block"></li>
								<li class="dropdown-header visible-xs-block visible-md-block">
									<span data-datetime="date"><?= date_decode(date('Y-m-d'), $_format); ?></span>
									<span>&mdash;</span>
									<span data-datetime="time"><?= date('H:i:s'); ?></span>
								</li>
								<?php endif ?>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="container-fluid">