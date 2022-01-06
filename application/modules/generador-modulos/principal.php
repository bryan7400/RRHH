<?php

// Obtiene las tablas prohibidas
$prohibidos = json_decode(@file_get_contents(storages . '/modulos.json'), true);

// Obtiene las tablas permitidas
$tablas = $db->query('show tables from ' . database)->fetch();

// Define el conjunto de modulos
$modulos = array();

// Obtiene los modulos disponibles
foreach ($tablas as $nro => $tabla) { array_push($modulos, $tabla['Tables_in_' . database]); }

// Obtiene los modulos disponibles
$tablas = array_diff($modulos, $prohibidos);

?>
<?php require_once show_template('header-design'); ?>
<div class="panel-heading">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Generador de módulos</strong>
	</h3>
</div>
<div class="panel-body">
	<div class="row">
		<div class="col-xs-12">
			<div class="text-label">Seleccione el módulo que desea crear:</div>
		</div>
	</div>
	<hr>
	<?php if ($message = get_notification()) : ?>
	<div class="alert alert-<?= $message['type']; ?>">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong><?= $message['title']; ?></strong>
		<p><?= $message['content']; ?></p>
	</div>
	<?php endif ?>
	<?php if ($tablas) : ?>
	<div class="list-group">
		<?php foreach ($tablas as $nro => $tabla) : ?>
		<a href="?/generador-modulos/generar/<?= str_replace('_', '-', $tabla); ?>" class="list-group-item">
			<span class="glyphicon glyphicon-menu-right pull-right"></span>
			<strong class="list-group-item-heading text-capitalize">Módulo &mdash; <?= substr($tabla, 4); ?></strong>
			<p class="list-group-item-text">Generar el módulo <em class="text-danger"><?= substr($tabla, 4); ?></em> con las operaciones listar, crear, modificar, ver, eliminar y/o imprimir</p>
		</a>
		<?php endforeach ?>
	</div>
	<?php else : ?>
	<div class="alert alert-danger">
		<strong>Base de datos vacia!</strong>
		<p>Para poder generar módulos, debe existir al menos una tabla en la base de datos.</p>
		<ul>
			<li>Para crear tablas debe ingresar a phpyadmin o su cliente mysql favorito.</li>
		</ul>
	</div>
	<?php endif ?>
</div>
<?php require_once show_template('footer-design'); ?>