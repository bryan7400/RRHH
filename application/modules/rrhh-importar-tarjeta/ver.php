<?php

// Obtiene los parametros
$id_empleado = (isset($params[0])) ? $params[0] : 0;

// Obtiene los formatos
$formato_textual = get_date_textual($_institution['formato']);
$formato_numeral = get_date_numeral($_institution['formato']);

// Obtiene la fecha de hoy
$hoy = now();

// Obtiene el empleado
$empleado = $db->query("select e.*, timestampdiff(year, e.fecha_nacimiento, curdate()) as edad, p.procedencia, p.codigo codigo_procedencia, c.cargo, ifnull(t.salario, 0) as salario, ifnull(a.horario_id, 0) as horario_id, d.alta, d.baja, ifnull(f.fecha_inicial, '0000-00-00') as fecha_contratacion, ifnull(f.fecha_final, '0000-00-00') as fecha_finalizacion from sys_empleados e left join gen_procedencias p on e.procedencia_id = p.id_procedencia left join per_cargos c on e.cargo_id = c.id_cargo left join (select empleado_id, min(fecha_asistencia) as alta, max(fecha_asistencia) as baja from per_asistencias group by empleado_id) d on e.id_empleado = d.empleado_id left join (select a.* from per_salarios a left join per_salarios b on a.empleado_id = b.empleado_id and a.fecha_salario < b.fecha_salario where b.fecha_salario is null) t on e.id_empleado = t.empleado_id left join (select a.* from per_asignaciones a left join per_asignaciones b on a.empleado_id = b.empleado_id and a.fecha_asignacion < b.fecha_asignacion where b.fecha_asignacion is null) a on e.id_empleado = a.empleado_id left join (select a.* from per_contratos a left join per_contratos b on a.empleado_id = b.empleado_id and a.fecha_contrato < b.fecha_contrato where b.fecha_contrato is null) f on e.id_empleado = f.empleado_id where e.id_empleado = '$id_empleado' order by e.activo asc, c.cargo asc, e.nombres asc, e.paterno asc, e.materno asc")->fetch_first();

$sucursales = $db->query("SELECT nombre from sys_empleado_instituciones ei LEFT JOIN sys_instituciones i ON i.id_institucion=ei.institucion_id WHERE empleado_id=".$empleado['id_empleado'])->fetch();

// Ejecuta un error 404 si no existe el empleado
if (!$empleado) { require_once not_found(); exit; }

// Obtiene los horarios
$horarios = $db->from('per_horarios')->order_by('entrada')->fetch();

// Obtiene la moneda principal
$moneda = $db->from('gen_monedas')->where('principal', 's')->fetch_first();
$moneda = ($moneda) ? '(' . $moneda['codigo'] . ')' : '';

// Obtiene los permisos
$permiso_listar      = in_array('listar', $_views);
$permiso_crear       = in_array('crear', $_views);
$permiso_modificar   = in_array('modificar', $_views);
$permiso_eliminar    = in_array('eliminar', $_views);
$permiso_bloquear    = in_array('bloquear', $_views);
$permiso_desbloquear = in_array('desbloquear', $_views);
$permiso_fijar       = in_array('fijar', $_views);
$permiso_asignar     = in_array('asignar', $_views);
$permiso_imprimir    = in_array('imprimir', $_views);
$permiso_tomar       = in_array('tomar', $_views);
$permiso_subir       = in_array('subir', $_views);
$permiso_suprimir    = in_array('suprimir', $_views);
$permiso_contratar   = in_array('contratar', $_views);
$permiso_retirar     = in_array('retirar', $_views);
$permiso_cambiar     = in_array('cambiar', $_views);

// Obtiene las horas
function horas($db, $horario_id) {
	$horario_id = explode(',', $horario_id);
	$horas = $db->from('per_horarios')->where_in('id_horario', $horario_id)->fetch();
	return $horas;
}

?>
<?php require_once show_template('header-sidebar'); ?>
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
		<strong>Ver empleado</strong>
	</h3>
</div>
<div class="panel-body">
	<?php if ($permiso_listar || $permiso_crear || $permiso_modificar || $permiso_eliminar || $permiso_bloquear || $permiso_desbloquear || $permiso_fijar || $permiso_asignar || $permiso_imprimir) : ?>
	<div class="row">
		<div class="col-xs-6">
			<div class="text-label hidden-xs">Seleccionar acción:</div>
			<div class="text-label visible-xs-block">Acciones:</div>
		</div>
		<div class="col-xs-6 text-right">
			<div class="btn-group">
				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
					<span class="glyphicon glyphicon-menu-hamburger"></span>
					<span class="hidden-xs">Acciones</span>
				</button>
				<ul class="dropdown-menu dropdown-menu-right">
					<li class="dropdown-header visible-xs-block">Seleccionar acción</li>
					<?php if ($permiso_listar) : ?>
					<li><a href="?/empleados/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar empleados</a></li>
					<?php endif ?>
					<?php if ($permiso_crear) : ?>
					<li><a href="?/empleados/crear"><span class="glyphicon glyphicon-plus"></span> Crear empleado</a></li>
					<?php endif ?>
					<?php if ($permiso_modificar) : ?>
					<li><a href="?/empleados/modificar/<?= $id_empleado; ?>"><span class="glyphicon glyphicon-edit"></span> Modificar empleado</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<li><a href="?/empleados/eliminar/<?= $id_empleado; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar empleado</a></li>
					<?php endif ?>
					<?php if ($permiso_bloquear) : ?>
					<li><a href="?/empleados/bloquear/<?= $id_empleado; ?>" data-bloquear="true"><span class="glyphicon glyphicon-remove-circle"></span> Bloquear empleado</a></li>
					<?php endif ?>
					<?php if ($permiso_desbloquear) : ?>
					<li><a href="?/empleados/desbloquear/<?= $id_empleado; ?>" data-desbloquear="true"><span class="glyphicon glyphicon-ok-circle"></span> Desbloquear empleado</a></li>
					<?php endif ?>
					<?php if ($permiso_fijar) : ?>
					<li><a href="?/empleados/fijar/<?= $id_empleado; ?>" data-fijar="true"><span class="glyphicon glyphicon-usd"></span> Fijar salario</a></li>
					<?php endif ?>
					<?php if ($permiso_asignar) : ?>
					<li><a href="?/empleados/asignar/<?= $id_empleado; ?>" data-asignar="true"><span class="glyphicon glyphicon-time"></span> Asignar horario</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/empleados/imprimir/<?= $id_empleado; ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir empleado</a></li>
					<?php endif ?>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	<?php endif ?>
	<?php if (isset($_SESSION[temporary])) { ?>
	<div class="alert alert-<?= $_SESSION[temporary]['alert']; ?>">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong><?= $_SESSION[temporary]['title']; ?></strong>
		<p><?= $_SESSION[temporary]['message']; ?></p>
	</div>
	<?php unset($_SESSION[temporary]); ?>
	<?php } ?>
	<div class="row">
		<div class="col-sm-3">
			<a href="#" class="thumbnail" data-toggle="lightbox" data-lightbox-image="<?= ($empleado['foto'] == '') ? imgs . '/avatar.jpg' : files . '/empleados/' . $empleado['foto']; ?>">
				<img src="<?= ($empleado['foto'] == '') ? imgs . '/avatar.jpg' : files . '/empleados/' . $empleado['foto']; ?>">
			</a>
			<?php if ($permiso_tomar || $permiso_subir || $permiso_suprimir || $permiso_contratar) : ?>
			<div class="list-group">
				<?php if ($permiso_tomar) : ?>
				<a href="#" class="list-group-item text-ellipsis" data-tomar="true">
					<span class="glyphicon glyphicon-camera"></span>
					<span>Tomar foto</span>
				</a>
				<?php endif ?>
				<?php if ($permiso_subir) : ?>
				<a href="#" class="list-group-item text-ellipsis" data-toggle="modal" data-target="#modal_subir" data-backdrop="static" data-keyboard="false">
					<span class="glyphicon glyphicon-picture"></span>
					<span>Subir foto</span>
				</a>
				<?php endif ?>
				<?php if ($permiso_suprimir) : ?>
				<a href="?/empleados/suprimir/<?= $id_empleado; ?>" class="list-group-item text-ellipsis" data-suprimir="true">
					<span class="glyphicon glyphicon-eye-close"></span>
					<span>Eliminar foto</span>
				</a>
				<?php endif ?>
				<?php if ($permiso_contratar) : ?>
					<?php if ($empleado['fecha_finalizacion'] == '0000-00-00') : ?>
						<?php if ($empleado['fecha_contratacion'] == '0000-00-00') : ?>
						<a href="?/empleados/contratar/<?= $id_empleado; ?>" class="list-group-item text-ellipsis" data-contratar="true">
							<span class="glyphicon glyphicon-file"></span>
							<span>Contratar empleado</span>
						</a>
						<?php else : ?>
						<a href="?/empleados/retirar/<?= $id_empleado; ?>" class="list-group-item text-ellipsis" data-retirar="true">
							<span class="halflings halflings-door"></span>
							<span>Retirar empleado</span>
						</a>
						<?php endif ?>
					<?php else : ?>
						<?php if ($hoy > $empleado['fecha_finalizacion']) : ?>
						<a href="?/empleados/contratar/<?= $id_empleado; ?>" class="list-group-item text-ellipsis" data-contratar="true">
							<span class="glyphicon glyphicon-file"></span>
							<span>Contratar empleado</span>
						</a>
						<?php endif ?>
					<?php endif ?>
				<?php endif ?>
			</div>
			<?php endif ?>
		</div>
		<div class="col-sm-6">
			<div class="well">
				<p class="lead margin-none"><strong>Datos personales</strong></p>
				<hr>
				<?php $horas = horas($db, $empleado['horario_id']); ?>
				<div class="table-display">
					<div class="tbody">
						<div class="tr">
							<div class="th">Código:</div>
							<div class="td"><?= escape($empleado['codigo']); ?></div>
						</div>
						<div class="tr">
							<div class="th">Nombres y apellidos:</div>
							<div class="td"><?= escape($empleado['nombres'] . ' ' . $empleado['paterno'] . ' ' . $empleado['materno']); ?></div>
						</div>
						<div class="tr">
							<div class="th">Género:</div>
							<div class="td"><?= ($empleado['genero'] == 'm') ? 'Masculino' : 'Femenino'; ?></div>
						</div>
						<div class="tr">
							<div class="th">Fecha de nacimiento:</div>
							<div class="td"><?= date_decode($empleado['fecha_nacimiento'], $_institution['formato']); ?></div>
						</div>
						<div class="tr">
							<div class="th">Edad:</div>
							<div class="td">
								<span><?= escape($empleado['edad']); ?></span>
								<span>años</span>
							</div>
						</div>
						<div class="tr">
							<div class="th">Cédula de identidad:</div>
							<div class="td"><?= escape($empleado['ci']); ?> <?= escape($empleado['codigo_procedencia']); ?></div>
						</div>
						<div class="tr">
							<div class="th">Dirección:</div>
							<div class="td"><?= escape($empleado['direccion']); ?></div>
						</div>
						<div class="tr">
							<div class="th">Teléfono:</div>
							<div class="td"><?= escape(str_replace(',', ' / ', $empleado['telefono'])); ?></div>
						</div>
						<div class="tr">
							<div class="th">Observación:</div>
							<div class="td"><?= ($empleado['observacion'] != '') ? escape($empleado['observacion']) : 'No asignado'; ?></div>
						</div>
					</div>
				</div>
			</div>
			<div class="well">
				<p class="lead margin-none"><strong>Datos laborales</strong></p>
				<hr>
				<div class="table-display">
					<div class="tbody">
						<div class="tr">
							<div class="th">Sucursales:</div>
							<div class="td">
								<?php if ($sucursales) : ?>
									<?php foreach ($sucursales as $sucursal) : ?>
									<samp class="text-primary"><?= $sucursal['nombre']; ?></samp>
									<br>
									<?php endforeach ?>
								<?php else : ?>
								<span class="text-danger">No asignado</span>
								<?php endif ?>
							</div>
						</div>
						<div class="tr">
							<div class="th">Cargo:</div>
							<div class="td"><?= escape($empleado['cargo']); ?></div>
						</div>
						<div class="tr">
							<div class="th">Fecha de contrato:</div>
							<div class="td"><?= ($empleado['fecha_contratacion'] == '0000-00-00') ? 'No asignado' : date_decode($empleado['fecha_contratacion'], $_institution['formato']); ?></div>
						</div>
						<div class="tr">
							<div class="th">Fecha de retiro:</div>
							<div class="td"><?= ($empleado['fecha_finalizacion'] == '0000-00-00') ? 'No asignado' : date_decode($empleado['fecha_finalizacion'], $_institution['formato']); ?></div>
						</div>
						<div class="tr">
							<div class="th">Primera asistencia:</div>
							<div class="td"><?= isset($empleado['alta']) ? date_decode($empleado['alta'], $_institution['formato']) :'No asignado'; ?></div>
						</div>
						<div class="tr">
							<div class="th">Última asistencia:</div>
							<div class="td"><?= isset($empleado['baja']) ? date_decode($empleado['baja'], $_institution['formato']) :'No asignado'; ?></div>
						</div>
						<div class="tr">
							<div class="th">Activo:</div>
							<div class="td"><?= ($empleado['activo'] == 's') ? 'Si' : 'No'; ?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-3">
			<?php if ($empleado['activo'] == 'n') : ?>
			<div class="progress">
				<div class="progress-bar progress-bar-danger progress-bar-striped" style="width: 100%;"></div>
			</div>
			<?php else : ?>
			<div class="progress">
				<div class="progress-bar progress-bar-success progress-bar-striped" style="width: 100%;"></div>
			</div>
			<?php endif ?>
			<div class="well text-center">
				<p class="lead margin-none">
					<strong>Salario</strong>
					<strong><?= $moneda; ?></strong>
				</p>
				<hr>
				<p class="lead margin-none text-primary"><strong><?= escape($empleado['salario']); ?></strong></p>
			</div>
			<div class="well text-center">
				<p class="lead margin-none"><strong>Horario</strong></p>
				<?php if ($horas) : ?>
					<?php foreach ($horas as $hora) : ?>
					<hr>
					<span class="text-capitalize"><?= str_replace(',', ', ', $hora['dias']); ?></span>
					<p class="lead margin-none">
						<strong><span class="text-primary"><?= substr($hora['entrada'], 0, -3); ?></span></strong>
						<span>a</span>
						<strong><span class="text-primary"><?= substr($hora['salida'], 0, -3); ?></span></strong>
					</p>
					<?php endforeach ?>
				<?php else : ?>
				<hr>
				<p class="margin-none">No asignado</p>
				<?php endif ?>
			</div>
			<?php if ($permiso_cambiar) : ?>
			<p class="text-right">
				<a href="?/empleados/cambiar/antes/<?= $id_empleado; ?>" class="btn btn-default" data-cambiar="true">
					<span class="glyphicon glyphicon-menu-left"></span>
					<span class="hidden-sm">Anterior</span>
				</a>
				<a href="?/empleados/cambiar/despues/<?= $id_empleado; ?>" class="btn btn-default" data-cambiar="true">
					<span class="hidden-sm">Siguiente</span>
					<span class="glyphicon glyphicon-menu-right"></span>
				</a>
			</p>
			<?php endif ?>
		</div>
	</div>
</div>

<!-- Modal fijar inicio -->
<?php if ($permiso_fijar) : ?>
<div id="modal_fijar" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/empleados/fijar" id="form_fijar" class="modal-content loader-wrapper" autocomplete="off">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Fijar salarios</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="fecha_salario_fijar" class="control-label">Fecha de modificación:</label>
					<input type="text" value="<?= date_decode($hoy, $_institution['formato']); ?>" name="fecha_salario" id="fecha_salario_fijar" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
				</div>
				<div class="form-group">
					<label for="salario_fijar" class="control-label">Nuevo salario <?= $moneda; ?>:</label>
					<input type="text" value="" name="salario" id="salario_fijar" class="form-control" data-validation="required number" data-validation-allowing="float">
				</div>
				<div class="form-group">
					<label for="observacion_fijar" class="control-label">Observación:</label>
					<textarea name="observacion" id="observacion_fijar" class="form-control" data-validation="letternumber" data-validation-allowing="-+/.,:;@#&'()_\n " data-validation-optional="true"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">
					<span class="glyphicon glyphicon-floppy-disk"></span>
					<span>Guardar</span>
				</button>
				<button type="reset" class="btn btn-default">
					<span class="glyphicon glyphicon-refresh"></span>
					<span>Restablecer</span>
				</button>
			</div>
			<div id="loader_fijar" class="loader-wrapper-backdrop hidden">
				<span class="loader"></span>
			</div>
		</form>
	</div>
</div>
<?php endif ?>
<!-- Modal fijar fin -->

<!-- Modal asignar inicio -->
<?php if ($permiso_asignar) : ?>
<div id="modal_asignar" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/empleados/asignar" id="form_asignar" class="modal-content loader-wrapper" autocomplete="off">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Asignar horarios</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="fecha_asignacion_asignar" class="control-label">Fecha de modificación:</label>
					<input type="text" value="<?= date_decode($hoy, $_institution['formato']); ?>" name="fecha_asignacion" id="fecha_asignacion_asignar" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
				</div>
				<div class="form-group">
					<label for="horario_id" class="control-label">Turnos disponibles:</label>
					<?php foreach ($horarios as $nro => $horario) : ?>
					<div class="checkbox">
						<label>
							<input type="checkbox" value="<?= $horario['id_horario']; ?>" name="horario_id[]" data-validation="checkbox_group" data-validation-qty="min1" data-validation-error-msg="Debe seleccionar al menos 1 turno">
							<samp class="text-primary"><b><?= substr($horario['entrada'], 0, -3); ?></b></samp>
							<span class="text-muted">&mdash;</span>
							<samp class="text-primary"><b><?= substr($horario['salida'], 0, -3); ?></b></samp>
							<span class="text-muted">&nbsp;</span>
							<span class="text-muted" data-toggle="tooltip" data-title="<?= $horario['descripcion']; ?>" title="" data-placement="right">[<?= str_replace(',', ' - ', $horario['dias']); ?>]</span>
						</label>
					</div>
					<?php endforeach ?>
				</div>
				<div class="form-group">
					<label for="observacion_asignar" class="control-label">Observación:</label>
					<textarea name="observacion" id="observacion_asignar" class="form-control" data-validation="letternumber" data-validation-allowing="-+/.,:;@#&'()_\n " data-validation-optional="true"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">
					<span class="glyphicon glyphicon-floppy-disk"></span>
					<span>Guardar</span>
				</button>
				<button type="reset" class="btn btn-default">
					<span class="glyphicon glyphicon-refresh"></span>
					<span>Restablecer</span>
				</button>
			</div>
			<div id="loader_asignar" class="loader-wrapper-backdrop hidden">
				<span class="loader"></span>
			</div>
		</form>
	</div>
</div>
<?php endif ?>
<!-- Modal asignar fin -->

<!-- Modal tomar inicio -->
<?php if ($permiso_tomar) : ?>
<div id="modal_tomar" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/empleados/tomar" id="form_tomar" class="modal-content loader-wrapper" autocomplete="off">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Tomar foto</h4>
			</div>
			<div class="modal-body">
				<div class="row" data-guillotine-element="container">
					<div class="col-sm-7">
						<div class="thumbnail">
							<img id="image_tomar" src="">
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
								<span>Centrar foto</span>
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
				<div class="form-group">
					<input type="text" value="<?= $id_empleado; ?>" name="id_empleado" id="id_empleado_tomar" class="translate" tabindex="-1" data-validation="required number" data-validation-error-msg="El campo no es válido">
					<input type="text" value="" name="foto" id="foto_tomar" class="translate" tabindex="-1" data-validation="required" data-validation-error-msg="El campo no es válido">
					<input type="text" value="" name="data" id="data_tomar" class="translate" tabindex="-1" data-validation="required" data-validation-error-msg="El campo no es válido">
				</div>
			</div>
			<div id="loader_tomar" class="loader-wrapper-backdrop hidden">
				<span class="loader"></span>
			</div>
		</form>
	</div>
</div>
<?php endif ?>
<!-- Modal tomar fin -->

<!-- Modal subir inicio -->
<?php if ($permiso_subir) : ?>
<div id="modal_subir" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/empleados/subir" enctype="multipart/form-data" id="form_subir" class="modal-content loader-wrapper" autocomplete="off">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Subir foto</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="foto_subir" class="control-label">Foto:</label>
					<input type="file" name="foto" id="foto_subir" class="form-control" data-validation="required mime size" data-validation-allowing="jpg, png" data-validation-max-size="4M">
					<input type="text" value="<?= $id_empleado; ?>" name="id_empleado" id="id_empleado_subir" class="translate" tabindex="-1" data-validation="required number" data-validation-error-msg="El campo no es válido">
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
								<span>Centrar foto</span>
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
<?php endif ?>
<!-- Modal subir fin -->

<!-- Modal contratar inicio -->
<?php if ($permiso_contratar) : ?>
<div id="modal_contratar" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/empleados/contratar" id="form_contratar" class="modal-content loader-wrapper" autocomplete="off">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Contratar empleado</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="fecha_contrato_contratar" class="control-label">Fecha de contrato:</label>
					<input type="text" value="<?= date_decode($hoy, $_institution['formato']); ?>" name="fecha_contrato" id="fecha_contrato_contratar" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
				</div>
				<div class="form-group">
					<label for="fecha_inicial_contratar" class="control-label">Fecha de inicio:</label>
					<input type="text" value="<?= date_decode($hoy, $_institution['formato']); ?>" name="fecha_inicial" id="fecha_inicial_contratar" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
				</div>
				<div class="form-group">
					<label for="fecha_final_contratar" class="control-label">Fecha de retiro:</label>
					<input type="text" value="" name="fecha_final" id="fecha_final_contratar" class="form-control" data-validation="date" data-validation-format="<?= $formato_textual; ?>" data-validation-optional="true">
				</div>
				<div class="form-group">
					<label for="observacion_inicial_contratar" class="control-label">Observación sobre el contrato:</label>
					<textarea name="observacion_inicial" id="observacion_inicial_contratar" class="form-control" data-validation="letternumber" data-validation-allowing="-+/.,:;@#&'()_\n " data-validation-optional="true"></textarea>
				</div>
				<div class="form-group">
					<label for="observacion_final_contratar" class="control-label">Observación sobre el retiro:</label>
					<textarea name="observacion_final" id="observacion_final_contratar" class="form-control" data-validation="letternumber" data-validation-allowing="-+/.,:;@#&'()_\n " data-validation-optional="true"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">
					<span class="glyphicon glyphicon-floppy-disk"></span>
					<span>Guardar</span>
				</button>
				<button type="reset" class="btn btn-default">
					<span class="glyphicon glyphicon-refresh"></span>
					<span>Restablecer</span>
				</button>
			</div>
			<div id="loader_contratar" class="loader-wrapper-backdrop hidden">
				<span class="loader"></span>
			</div>
		</form>
	</div>
</div>
<?php endif ?>
<!-- Modal contratar fin -->

<!-- Modal retirar inicio -->
<?php if ($permiso_retirar) : ?>
<div id="modal_retirar" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/empleados/retirar" id="form_retirar" class="modal-content loader-wrapper" autocomplete="off">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Retirar empleado</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="fecha_final_retirar" class="control-label">Fecha de retiro:</label>
					<input type="text" value="<?= date_decode($hoy, $_institution['formato']); ?>" name="fecha_final" id="fecha_final_retirar" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
				</div>
				<div class="form-group">
					<label for="observacion_final_retirar" class="control-label">Observación sobre el retiro:</label>
					<textarea name="observacion_final" id="observacion_final_retirar" class="form-control" data-validation="letternumber" data-validation-allowing="-+/.,:;@#&'()_\n " data-validation-optional="true"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">
					<span class="glyphicon glyphicon-floppy-disk"></span>
					<span>Guardar</span>
				</button>
				<button type="reset" class="btn btn-default">
					<span class="glyphicon glyphicon-refresh"></span>
					<span>Restablecer</span>
				</button>
			</div>
			<div id="loader_retirar" class="loader-wrapper-backdrop hidden">
				<span class="loader"></span>
			</div>
		</form>
	</div>
</div>
<?php endif ?>
<!-- Modal retirar fin -->

<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/jquery.maskedinput.min.js"></script>
<script src="<?= js; ?>/jquery.guillotine.min.js"></script>
<script>
$(function () {
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/empleados/crear';
				break;
			}
		}
	});
	<?php endif ?>
	
	<?php if ($permiso_eliminar) : ?>
	$('[data-eliminar]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var csrf = '';
		bootbox.confirm('¿Está seguro que desea eliminar el empleado?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>

	<?php if ($permiso_bloquear) : ?>
	$('[data-bloquear]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var csrf = '';
		bootbox.confirm('¿Está seguro que desea bloquear el empleado?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>

	<?php if ($permiso_desbloquear) : ?>
	$('[data-desbloquear]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var csrf = '';
		bootbox.confirm('¿Está seguro que desea desbloquear el empleado?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>

	<?php if ($permiso_fijar) : ?>
	var $modal_fijar = $('#modal_fijar'), $form_fijar = $('#form_fijar'), $loader_fijar = $('#loader_fijar'), $fecha_salario_fijar = $('#fecha_salario_fijar');

	$.validate({
		form: '#form_fijar',
		modules: 'basic',
		onSuccess: function () {
			$loader_fijar.removeClass('hidden');
		}
	});

	$fecha_salario_fijar.mask('<?= $formato_numeral; ?>');

	$('[data-fijar]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		$form_fijar.attr('action', href);
		$modal_fijar.modal({
			backdrop: 'static',
			keyboard: false
		});
	});

	$modal_fijar.on('hidden.bs.modal', function () {
		$form_fijar.trigger('reset');
	}).on('show.bs.modal', function (e) {
		if ($('.modal:visible').size() != 0) { e.preventDefault(); }
	}).on('shown.bs.modal', function () {
		$form_fijar.find('.form-control:nth(1)').focus();
	});
	<?php endif ?>

	<?php if ($permiso_asignar) : ?>
	var $modal_asignar = $('#modal_asignar'), $form_asignar = $('#form_asignar'), $loader_asignar = $('#loader_asignar'), $fecha_asignacion_asignar = $('#fecha_asignacion_asignar');

	$.validate({
		form: '#form_asignar',
		modules: 'basic',
		onSuccess: function () {
			$loader_asignar.removeClass('hidden');
		}
	});

	$fecha_asignacion_asignar.mask('<?= $formato_numeral; ?>');

	$('[data-asignar]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		$form_asignar.attr('action', href);
		$modal_asignar.modal({
			backdrop: 'static',
			keyboard: false
		});
	});

	$modal_asignar.on('hidden.bs.modal', function () {
		$form_asignar.trigger('reset');
	}).on('show.bs.modal', function (e) {
		if ($('.modal:visible').size() != 0) { e.preventDefault(); }
	}).on('shown.bs.modal', function () {
		$form_asignar.find('.form-control:nth(1)').focus();
	});
	<?php endif ?>

	<?php if ($permiso_tomar) : ?>
	var $modal_tomar = $('#modal_tomar'), $form_tomar = $('#form_tomar'), $loader_tomar = $('#loader_tomar'), $element_tomar = $modal_tomar.find('[data-guillotine-element="container"]'), $action_tomar = $modal_tomar.find('[data-guillotine-action]'), $image_tomar = $('#image_tomar'), $foto_tomar = $('#foto_tomar'), $data_tomar = $('#data_tomar');

	$('[data-tomar]').on('click', function (e) {
		e.preventDefault();
		var width, height, left, top, popup;
		width = 640;
		height = 480;
		left = ($(window).width() / 2) - (width / 2);
		top = ($(window).height() / 2 ) - (height / 2);
		popup = window.open ('<?= app_camera; ?>', 'popup', 'width=' + width + ', height=' + height + ', top=' + top + ', left=' + left + ', directories=0, titlebar=0, toolbar=0, location=0, status=0, menubar=0, scrollbars=no, resizable=no');
	});
	
	window.addEventListener('message', function (e) {
		if (e.origin == '<?= ip_local; ?>') {
			$image_tomar.attr('src', e.data);
			$modal_tomar.modal({
				backdrop: 'static',
				keyboard: false
			});
		}
	}, true);

	$.validate({
		form: '#form_tomar',
		onSuccess: function () {
			$loader_tomar.removeClass('hidden');
		}
	});

	$modal_tomar.on('hidden.bs.modal', function () {
		$form_tomar.trigger('reset');
		$element_tomar.hide();
	}).on('show.bs.modal', function (e) {
		if ($('.modal:visible').size() != 0) { e.preventDefault(); }
	});

	$image_tomar.on('load', function () {
		$image_tomar.guillotine('remove');
		$image_tomar.guillotine({
			width: 650,
			height: 650
		});
		$image_tomar.guillotine('fit');
		$element_tomar.show();
	});

	$action_tomar.on('click', function (e) {
		e.preventDefault();
		var data, scale, action = $(this).attr('data-guillotine-action');
		if (action != 'getData') {
			if (action == 'zoomIn') {
				data = $image_tomar.guillotine('getData');
				scale = data.scale;
				if (scale <= 2) {
					$image_tomar.guillotine(action);
				}
			} else {
				$image_tomar.guillotine(action);
			}
		} else {
			data = $image_tomar.guillotine(action);
			data = JSON.stringify(data);
			$foto_tomar.val($image_tomar.attr('src'));
			$data_tomar.val(data);
			$form_tomar.submit();
		}
	});
	<?php endif ?>

	<?php if ($permiso_subir) : ?>
	var $modal_subir = $('#modal_subir'), $form_subir = $('#form_subir'), $loader_subir = $('#loader_subir'), $element_subir = $modal_subir.find('[data-guillotine-element="container"]'), $action_subir = $modal_subir.find('[data-guillotine-action]'), $image_subir = $('#image_subir'), $foto_subir = $('#foto_subir'), $data_subir = $('#data_subir');

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

	$foto_subir.on('validation', function (e, valid) {
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
			width: 650,
			height: 650
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
	<?php endif ?>

	<?php if ($permiso_suprimir) : ?>
	$('[data-suprimir]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var csrf = '';
		bootbox.confirm('¿Está seguro que desea eliminar la foto del empleado?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>

	<?php if ($permiso_contratar) : ?>
	var $modal_contratar = $('#modal_contratar'), $form_contratar = $('#form_contratar'), $loader_contratar = $('#loader_contratar'), $fecha_contrato_contratar = $('#fecha_contrato_contratar'), $fecha_inicial_contratar = $('#fecha_inicial_contratar'), $fecha_final_contratar = $('#fecha_final_contratar');

	$.validate({
		form: '#form_contratar',
		modules: 'basic',
		onSuccess: function () {
			$loader_contratar.removeClass('hidden');
		}
	});

	$fecha_contrato_contratar.mask('<?= $formato_numeral; ?>');
	$fecha_inicial_contratar.mask('<?= $formato_numeral; ?>');
	$fecha_final_contratar.mask('<?= $formato_numeral; ?>');

	$('[data-contratar]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		$form_contratar.attr('action', href);
		$modal_contratar.modal({
			backdrop: 'static',
			keyboard: false
		});
	});

	$modal_contratar.on('hidden.bs.modal', function () {
		$form_contratar.trigger('reset');
	}).on('show.bs.modal', function (e) {
		if ($('.modal:visible').size() != 0) { e.preventDefault(); }
	}).on('shown.bs.modal', function () {
		$form_contratar.find('.form-control:nth(2)').focus();
	});
	<?php endif ?>

	<?php if ($permiso_retirar) : ?>
	var $modal_retirar = $('#modal_retirar'), $form_retirar = $('#form_retirar'), $loader_retirar = $('#loader_retirar'), $fecha_final_retirar = $('#fecha_final_retirar');

	$.validate({
		form: '#form_retirar',
		modules: 'basic',
		onSuccess: function () {
			$loader_retirar.removeClass('hidden');
		}
	});

	$fecha_final_retirar.mask('<?= $formato_numeral; ?>');

	$('[data-retirar]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		$form_retirar.attr('action', href);
		$modal_retirar.modal({
			backdrop: 'static',
			keyboard: false
		});
	});

	$modal_retirar.on('hidden.bs.modal', function () {
		$form_retirar.trigger('reset');
	}).on('show.bs.modal', function (e) {
		if ($('.modal:visible').size() != 0) { e.preventDefault(); }
	}).on('shown.bs.modal', function () {
		$form_retirar.find('.form-control:nth(1)').focus();
	});
	<?php endif ?>

	<?php if ($permiso_cambiar) : ?>
	$('[data-cambiar]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var csrf = '';
		$.request(href, csrf);
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-sidebar'); ?>