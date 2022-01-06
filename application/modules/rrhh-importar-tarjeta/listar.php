<?php
$formato_textual = get_date_textual($_institution['formato']);
$formato_numeral = get_date_numeral($_institution['formato']);

// Obtiene la fecha de hoy
$hoy = now();

// Obtiene la fecha de ayer
$ayer = remove_day($hoy);

// Obtiene los empleados
$empleados = $db->query("select e.*, timestampdiff(year, e.fecha_nacimiento, curdate()) as edad, p.procedencia, c.cargo, ifnull(t.salario, 0) as salario, ifnull(a.horario_id, 0) as horario_id, d.alta, d.baja, ifnull(f.fecha_inicial, '0000-00-00') as fecha_contratacion, ifnull(f.fecha_final, '0000-00-00') as fecha_finalizacion from sys_empleados e left join gen_procedencias p on e.procedencia_id = p.id_procedencia left join per_cargos c on e.cargo_id = c.id_cargo left join (select empleado_id, min(fecha_asistencia) as alta, max(fecha_asistencia) as baja from per_asistencias group by empleado_id) d on e.id_empleado = d.empleado_id left join (select a.* from per_salarios a left join per_salarios b on a.empleado_id = b.empleado_id and a.fecha_salario < b.fecha_salario where b.fecha_salario is null) t on e.id_empleado = t.empleado_id left join (select a.* from per_asignaciones a left join per_asignaciones b on a.empleado_id = b.empleado_id and a.fecha_asignacion < b.fecha_asignacion where b.fecha_asignacion is null) a on e.id_empleado = a.empleado_id left join (select a.* from per_contratos a left join per_contratos b on a.empleado_id = b.empleado_id and a.fecha_contrato < b.fecha_contrato where b.fecha_contrato is null) f on e.id_empleado = f.empleado_id order by e.activo asc, c.cargo asc, e.nombres asc, e.paterno asc, e.materno asc")->fetch();

// Obtiene los horarios
$horarios = $db->from('per_horarios')->order_by('entrada')->fetch();

$moneda = $db->from('gen_monedas')->where('principal', 's')->fetch_first();
$moneda = ($moneda) ? '(' . $moneda['codigo'] . ')' : '';

// Almacena los permisos en variables
$permiso_crear       = in_array('crear', $_views);
$permiso_editar      = in_array('modificar', $_views);
$permiso_ver         = in_array('ver', $_views);
$permiso_eliminar    = in_array('eliminar', $_views);
$permiso_imprimir    = in_array('imprimir', $_views);

$permiso_bloquear    = in_array('bloquear', $_views);
$permiso_desbloquear = in_array('desbloquear', $_views);
$permiso_fijar       = in_array('fijar', $_views);
$permiso_asignar     = in_array('asignar', $_views);
$permiso_imprimir    = in_array('imprimir', $_views);

// Obtiene las horas
function horas($db, $horario_id) {
	$horario_id = explode(',', $horario_id);
	$horas = $db->from('per_horarios')->where_in('id_horario', $horario_id)->fetch();
	return $horas;
}
?>
<?php require_once show_template('header-sidebar'); ?>
<div class="panel-heading">
	<h3 class="panel-title">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<b>Empleados</b>
	</h3>
</div>
<div class="panel-body">
	<?php if (($permiso_crear || $permiso_imprimir) && ($permiso_crear || $empleados)) { ?>
	<div class="row">
		<div class="col-sm-8 hidden-xs">
			<div class="text-label">Para agregar nuevos empleados hacer clic en el siguiente botón: </div>
		</div>
		<div class="col-xs-12 col-sm-4 text-right">
			<?php if ($permiso_imprimir) { ?>
			<a href="?/empleados/imprimir" target="_blank" class="btn btn-info">
				<span class="glyphicon glyphicon-print"></span>
				<span class="hidden-xs">Imprimir</span>
			</a>
			<?php } ?>
			<?php if ($permiso_crear) { ?>
			<a href="?/empleados/crear" class="btn btn-primary">
				<span class="glyphicon glyphicon-plus"></span>
				<span>Nuevo</span>
			</a>
			<?php } ?>
		</div>
	</div>
	<hr>
	<?php } ?>
	<?php if ($empleados) { ?>
	<table id="table" class="table table-bordered table-condensed table-restructured table-striped table-hover">
		<thead>
			<tr class="active">
				<th class="text-nowrap text-middle width-collapse">#</th>
				<th class="text-nowrap">Foto</th>
				<th class="text-nowrap text-middle width-collapse">Código</th>
				<th class="text-nowrap text-middle width-collapse">Nombres</th>
				<th class="text-nowrap text-middle width-collapse">Apellido paterno</th>
				<th class="text-nowrap text-middle width-collapse">Apellido materno</th>
				<th class="text-nowrap text-middle width-collapse">Género</th>
				<th class="text-nowrap text-middle width-collapse">Fecha de nacimiento</th>
				<th class="text-nowrap text-middle width-collapse">Teléfono</th>
				<th class="text-nowrap text-middle">Cargo</th>
				<th class="text-nowrap">Sucursal</th>
				<th class="text-nowrap">Salario</th>
				<th class="text-nowrap">Horario</th>
				<th class="text-nowrap">Activo</th>
				<th class="text-nowrap">Primera asistencia</th>
				<th class="text-nowrap">Última asistencia</th>
				<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) { ?>
				<th class="text-nowrap text-middle width-collapse">Opciones</th>
				<?php } ?>
			</tr>
		</thead>
		<tfoot>
			<tr class="active">
				<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
				<th class="text-nowrap text-middle" data-datafilter-filter="false">Foto</th>
				<th class="text-nowrap text-middle" data-datafilter-filter="true">Código</th>
				<th class="text-nowrap text-middle" data-datafilter-filter="true">Nombres</th>
				<th class="text-nowrap text-middle" data-datafilter-filter="true">Apellido paterno</th>
				<th class="text-nowrap text-middle" data-datafilter-filter="true">Apellido materno</th>
				<th class="text-nowrap text-middle" data-datafilter-filter="true">Género</th>
				<th class="text-nowrap text-middle" data-datafilter-filter="true">Fecha de nacimiento</th>
				<th class="text-nowrap text-middle" data-datafilter-filter="true">Teléfono</th>
				<th class="text-nowrap text-middle" data-datafilter-filter="true">Cargo</th>
				<th class="text-nowrap text-middle">Sucursal</th>
				<th class="text-nowrap text-middle">Salario</th>
				<th class="text-nowrap">Horario</th>
				<th class="text-nowrap text-middle">Activo</th>
				<th class="text-nowrap text-middle">Primera asistencia</th>
				<th class="text-nowrap text-middle">Última asistencia</th>
				<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) { ?>
				<th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
				<?php } ?>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($empleados as $nro => $empleado) { ?>
			<?php $sucursales = $db->query("SELECT nombre from sys_empleado_instituciones ei LEFT JOIN sys_instituciones i ON i.id_institucion=ei.institucion_id WHERE empleado_id=".$empleado['id_empleado'])->fetch();?>
			<?php $horas = horas($db, $empleado['horario_id']); ?>
			<?php if ($empleado['activo'] == 'n') : ?>
			<tr class="danger">	
			<?php else : ?>
			<tr>
			<?php endif ?>
				<th class="text-nowrap text-middle"><?= $nro + 1; ?></th>
				<td class="text-nowrap"><img src="<?= ($empleado['foto'] == '') ? imgs . '/avatar.jpg' : files . '/empleados/small__' . $empleado['foto']; ?>" width="30" height="30" class="img-circle" data-toggle="lightbox" data-lightbox-image="<?= ($empleado['foto'] == '') ? imgs . '/avatar.jpg' : files . '/empleados/' . $empleado['foto']; ?>"></td>
				<td class="text-nowrap text-middle"><?= escape($empleado['codigo']); ?></td>
				<td class="text-nowrap text-middle"><?= escape($empleado['nombres']); ?></td>
				<td class="text-nowrap text-middle"><?= escape($empleado['paterno']); ?></td>
				<td class="text-nowrap text-middle"><?= escape($empleado['materno']); ?></td>
				<td class="text-nowrap text-middle"><?= escape($empleado['genero']); ?></td>
				<td class="text-nowrap text-middle"><?= date_decode(escape($empleado['fecha_nacimiento']), $_institution['formato']); ?></td>
				<td class="text-nowrap text-middle"><?= str_replace(',', ' / ', escape($empleado['telefono'])); ?></td>
				<td class=""><?= escape($empleado['cargo']); ?></td>
				<td class="text-nowrap">
					<?php if ($sucursales) : ?>
						<?php foreach ($sucursales as $sucursal) : ?>
						<samp class="text-primary"><?= $sucursal['nombre']; ?></samp>
						<br>
						<?php endforeach ?>
					<?php else : ?>
					<span class="text-danger">No asignado</span>
					<?php endif ?>

				</td>
				<td class="text-nowrap text-right"><?= escape($empleado['salario']); ?></td>
				<td class="text-nowrap">
					<?php if ($horas) : ?>
						<?php foreach ($horas as $hora) : ?>
						<samp class="text-primary"><?= substr($hora['entrada'], 0, -3); ?></samp>
						<span>a</span>
						<samp class="text-primary"><?= substr($hora['salida'], 0, -3); ?></samp>
						<br>
						<?php endforeach ?>
					<?php else : ?>
					<span class="text-danger">No asignado</span>
					<?php endif ?>
				</td>
				<td class="text-nowrap">
					<?php if ($empleado['activo'] == 's') : ?>
					<span class="text-success">Si</span>
					<?php else : ?>
					<span class="text-danger">No</span>
					<?php endif ?>
				</td>

				<td class="text-nowrap">
					<span class="hidden"><?= isset($empleado['alta']) ? escape($empleado['alta']): 'No asignado' ?></span>
					<span class="text-success"><?= isset($empleado['alta']) ? date_decode($empleado['alta'], $_institution['formato']):'No asignado'; ?></span>
				</td>
				<td class="text-nowrap">
					<?php if ($empleado['baja'] < $ayer) : ?>
					<span class="hidden"><?= escape($empleado['baja']); ?></span>
					<span class="text-danger"><?= isset($empleado['baja']) ? date_decode($empleado['baja'], $_institution['formato']):'No asiganado'; ?></span>
					<?php endif ?>
				</td>

				<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) { ?>
				<td class="text-nowrap">
					<?php if ($permiso_ver) : ?>
					<a href="?/empleados/ver/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Ver empleado" title=""><span class="glyphicon glyphicon-search"></span></a>
					<?php endif ?>
					<?php if ($permiso_editar) : ?>
					<a href="?/empleados/modificar/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Modificar empleado" title=""><span class="glyphicon glyphicon-edit"></span></a>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<a href="?/empleados/eliminar/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Eliminar empleado" title="" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span></a>
					<?php endif ?>
					<?php if ($permiso_bloquear) : ?>
					<a href="?/empleados/bloquear/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Bloquear empleado" title="" data-bloquear="true"><span class="glyphicon glyphicon-remove-circle"></span></a>
					<?php endif ?>
					<?php if ($permiso_desbloquear) : ?>
					<a href="?/empleados/desbloquear/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Desbloquear empleado" title="" data-desbloquear="true"><span class="glyphicon glyphicon-ok-circle"></span></a>
					<?php endif ?>
					<?php if ($permiso_fijar) : ?>
					<a href="?/empleados/fijar/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Fijar salario" title="" data-fijar="true"><span class="glyphicon glyphicon-usd"></span></a>
					<?php endif ?>
					<?php if ($permiso_asignar) : ?>
					<a href="?/empleados/asignar/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" title="Asignar horario" data-asignar="true"><span class="glyphicon glyphicon-time"></span></a>
					<?php endif ?>
				</td>
				<?php } ?>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php } else { ?>
	<div class="alert alert-danger">
		<strong>Advertencia!</strong>
		<p>No existen empleados registrados en la base de datos, para crear nuevos empleados hacer clic en el botón nuevo o presionar las teclas <kbd>alt + n</kbd>.</p>
	</div>
	<?php } ?>
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

<script src="<?= js; ?>/jquery.dataTables.min.js"></script>
<script src="<?= js; ?>/dataTables.bootstrap.min.js"></script>
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<script src="<?= js; ?>/jquery.dataFilters.min.js"></script>
<script src="<?= js; ?>/FileSaver.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/jquery.maskedinput.min.js"></script>
<script>
$(function () {
	<?php if ($permiso_eliminar) { ?>
	$('[data-eliminar]').on('click', function (e) {
		e.preventDefault();
		var url = $(this).attr('href');
		bootbox.confirm('¿Está seguro que desea eliminar el empleado?', function (result) {
			if(result){
				window.location = url;
			}
		});
	});
	<?php } ?>
	
	<?php if ($permiso_crear) { ?>
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
	<?php } ?>

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

	$('[data-grupo-bloquear]').on('click', function (e) {
		e.preventDefault();
		var id_empleado = $(this).attr('data-grupo-bloquear');
		var href = $(this).attr('href') + '/' + id_empleado;
		var csrf = '';
		if (id_empleado != 'true') {
			bootbox.confirm('¿Está seguro que desea bloquear a los empleados seleccionados?', function (result) {
				if (result) { $.request(href, csrf); }
			});
		} else {
			bootbox.alert('Para continuar con el proceso debe seleccionar al menos un empleado.');
		}
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

	$('[data-grupo-desbloquear]').on('click', function (e) {
		e.preventDefault();
		var id_empleado = $(this).attr('data-grupo-desbloquear');
		var href = $(this).attr('href') + '/' + id_empleado;
		var csrf = '';
		if (id_empleado != 'true') {
			bootbox.confirm('¿Está seguro que desea desbloquear a los empleados seleccionados?', function (result) {
				if (result) { $.request(href, csrf); }
			});
		} else {
			bootbox.alert('Para continuar con el proceso debe seleccionar al menos un empleado.');
		}
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

	$('[data-grupo-fijar]').on('click', function (e) {
		e.preventDefault();
		var id_empleado = $(this).attr('data-grupo-fijar');
		var href = $(this).attr('href') + '/' + id_empleado;
		if (id_empleado != 'true') {
			$form_fijar.attr('action', href);
			$modal_fijar.modal({
				backdrop: 'static',
				keyboard: false
			});
		} else {
			bootbox.alert('Para continuar con el proceso debe seleccionar al menos un empleado.');
		}
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

	$('[data-grupo-asignar]').on('click', function (e) {
		e.preventDefault();
		var id_empleado = $(this).attr('data-grupo-asignar');
		var href = $(this).attr('href') + '/' + id_empleado;
		if (id_empleado != 'true') {
			$form_asignar.attr('action', href);
			$modal_asignar.modal({
				backdrop: 'static',
				keyboard: false
			});
		} else {
			bootbox.alert('Para continuar con el proceso debe seleccionar al menos un empleado.');
		}
	});

	$modal_asignar.on('hidden.bs.modal', function () {
		$form_asignar.trigger('reset');
	}).on('show.bs.modal', function (e) {
		if ($('.modal:visible').size() != 0) { e.preventDefault(); }
	}).on('shown.bs.modal', function () {
		$form_asignar.find('.form-control:nth(1)').focus();
	});
	<?php endif ?>

	<?php if ($permiso_eliminar || $permiso_bloquear || $permiso_desbloquear || $permiso_fijar || $permiso_asignar) : ?>
	$('[data-seleccionar]').on('change', function () {
		var empleados = [];
		var todos = $('[data-seleccionar]').size();
		var check = 0;
		$('[data-seleccionar]:checked').each(function () {
			empleados.push($(this).attr('data-seleccionar'));
			check = check + 1;
		});
		switch (check) {
			case 0:
				$('[data-grupo-seleccionar]').prop({
					checked: false,
					indeterminate: false
				});
				break;
			case todos:
				$('[data-grupo-seleccionar]').prop({
					checked: true,
					indeterminate: false
				});
				break;
			default:
				$('[data-grupo-seleccionar]').prop({
					checked: false,
					indeterminate: true
				});
				break;
		}
		empleados = empleados.join('-');
		empleados = (empleados != '') ? empleados : 'true';
		$('[data-grupo-eliminar]').attr('data-grupo-eliminar', empleados);
		$('[data-grupo-bloquear]').attr('data-grupo-bloquear', empleados);
		$('[data-grupo-desbloquear]').attr('data-grupo-desbloquear', empleados);
		$('[data-grupo-fijar]').attr('data-grupo-fijar', empleados);
		$('[data-grupo-asignar]').attr('data-grupo-asignar', empleados);
	});

	$('[data-grupo-seleccionar]').on('change', function () {
		var checked = $(this).prop('checked');
		$('[data-seleccionar]:visible').prop('checked', checked).trigger('change');
	});
	<?php endif ?>
	
	<?php if ($empleados) { ?>
	var table = $('#table').DataFilter({
		filter: true,
		name: 'empleados',
		reports: 'xls|doc|pdf|html'
	});

	$('#states_0').find(':radio[value="hide"]').trigger('click');
	<?php } ?>


});
</script>
<?php require_once show_template('footer-sidebar'); ?>