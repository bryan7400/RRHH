<?php
 
// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los formatos
$formato_textual = get_date_textual($_format);
$formato_numeral = get_date_numeral($_format);

// Obtiene la fecha de hoy
$hoy = now();

// Obtiene la fecha de ayer
$ayer = remove_day($hoy);

// Obtiene los empleados
$empleados = $db->query("select e.*, timestampdiff(year, e.fecha_nacimiento, curdate()) as edad, p.procedencia, c.cargo, ifnull(t.salario, 0) as salario, ifnull(a.horario_id, 0) as horario_id, d.alta, d.baja, ifnull(f.fecha_inicial, '0000-00-00') as fecha_contratacion, ifnull(f.fecha_final, '0000-00-00') as fecha_finalizacion from sys_empleados e left join gen_procedencias p on e.procedencia_id = p.id_procedencia left join per_cargos c on e.cargo_id = c.id_cargo left join (select empleado_id, min(fecha_asistencia) as alta, max(fecha_asistencia) as baja from per_asistencias group by empleado_id) d on e.id_empleado = d.empleado_id left join (select a.* from per_salarios a left join per_salarios b on a.empleado_id = b.empleado_id and a.fecha_salario < b.fecha_salario where b.fecha_salario is null) t on e.id_empleado = t.empleado_id left join (select a.* from per_asignaciones a left join per_asignaciones b on a.empleado_id = b.empleado_id and a.fecha_asignacion < b.fecha_asignacion where b.fecha_asignacion is null) a on e.id_empleado = a.empleado_id left join (select a.* from per_contratos a left join per_contratos b on a.empleado_id = b.empleado_id and a.fecha_contrato < b.fecha_contrato where b.fecha_contrato is null) f on e.id_empleado = f.empleado_id order by e.activo asc, c.cargo asc, e.nombres asc, e.paterno asc, e.materno asc")->fetch();

// Obtiene los horarios
$horarios = $db->from('per_horarios')->order_by('entrada')->fetch();

// Obtiene la moneda principal 
$moneda = $db->from('gen_monedas')->where('principal', 's')->fetch_first();
$moneda = ($moneda) ? '(' . $moneda['codigo'] . ')' : '';

// Obtiene los permisos
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_bloquear = in_array('bloquear', $_views);
$permiso_desbloquear = in_array('desbloquear', $_views); 
$permiso_fijar = in_array('fijar', $_views);
$permiso_asignar = in_array('asignar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);
$permiso_pin = in_array('pin', $_views);

// Obtiene las horas
function horas($db, $horario_id) {
	$horario_id = explode(',', $horario_id);
	$horas = $db->from('per_horarios')->where_in('id_horario', $horario_id)->fetch();
	return $horas;
}

?>
<?php require_once show_template('header-design'); ?>
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Lista de Empleados</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Recursos Humanos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lista de Empleados</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- row -->
<!-- ============================================================== -->
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
	<div class="card">
		<!-- <h5 class="card-header">Generador de menús</h5> --> 
		<div class="card-header">
			<div class="row">
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
					<div class="text-label hidden-xs">Seleccione:</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
					<div class="btn-group">
							<div class="input-group">
							<div class="input-group-append be-addon">
								<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
								<div class="dropdown-menu">
									<a class="dropdown-item">Seleccionar acción</a>
									<?php if ($permiso_crear) : ?>
									<div class="dropdown-divider"></div>
									<!--a href="#" onclick="abrir_crear();" class="dropdown-item">Registrar Estudiante</a-->
									<a href="?/rrhh-empleados/crear" class="dropdown-item">Nuevo Empleado</a>
									<?php endif ?>  
									<?php if ($permiso_imprimir) : ?>
									<div class="dropdown-divider"></div>
									<a href="?/rrhh-empleados/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
									<?php endif ?>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</div>
		<div class="card-body">
		<!-- ============================================================== -->
		<!-- datos --> 
		<!-- ============================================================== -->
		<?php if ($message = get_notification()) : ?>
		<div class="alert alert-<?= $message['type']; ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong><?= $message['title']; ?></strong>
			<p><?= $message['content']; ?></p>
		</div>
		<?php endif ?>
		<?php if ($empleados) : ?>
		<table id="table" class="table table-bordered table-condensed table-striped table-hover">
			<thead>
				<tr class="active">
					<th class="text-nowrap">#</th>
					<th class="text-nowrap">Foto</th>
					<th class="text-nowrap">Nombres</th>
					<th class="text-nowrap">Apellido paterno</th>
					<th class="text-nowrap">Apellido materno</th>
					<th class="text-nowrap">Género</th>
					<th class="text-nowrap">Fecha de nacimiento</th>
					<th class="text-nowrap">Edad</th>
					<th class="text-nowrap">Cédula de identidad</th>
					<th class="text-nowrap">Procedencia</th>
					<th class="text-nowrap">Dirección</th>
					<th class="text-nowrap">Teléfono</th>
					<th class="text-nowrap">Cargo</th>
					<th class="text-nowrap">Sucursal</th>
					<th class="text-nowrap">Salario</th>
					<th class="text-nowrap">Horario</th>
					<th class="text-nowrap">Activo</th>
					<th class="text-nowrap">Fecha de contrato</th>
					<th class="text-nowrap">Fecha de retiro</th>
					<!-- <th class="text-nowrap">Primera asistencia</th>
					<th class="text-nowrap">Última asistencia</th> -->
					<th class="text-nowrap">Observación</th>
					<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar || $permiso_bloquear || $permiso_desbloquear || $permiso_fijar || $permiso_asignar) : ?>
					<th class="text-nowrap">Opciones</th>
					<?php endif ?>
					<?php if ($permiso_eliminar || $permiso_bloquear || $permiso_desbloquear || $permiso_fijar || $permiso_asignar) : ?>
					<!-- <th class="text-nowrap">
						<span class="hidden">Selección</span>
						<span class="glyphicon glyphicon-check"></span>
					</th> -->
					<?php endif ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($empleados as $nro => $empleado) : ?>
				<?php $horas = horas($db, $empleado['horario_id']); ?>
				<?php if ($empleado['activo'] == 'n') : ?>
				<tr class="primary">	
				<?php else : ?>
					<?php if ($empleado['fecha_finalizacion'] != '0000-00-00' && $empleado['fecha_finalizacion'] < $hoy): ?>
					<tr class="info">	
					<?php else: ?>
					<tr>
					<?php endif ?>
				<?php endif ?>
					<th class="text-nowrap"><?= $nro + 1; ?></th>
					<td class="text-nowrap"><img src="<?= ($empleado['foto'] == '') ? imgs . '/avatar.jpg' : files . '/empleados/small__' . $empleado['foto']; ?>" width="30" height="30" class="img-circle" data-toggle="lightbox" data-lightbox-image="<?= ($empleado['foto'] == '') ? imgs . '/avatar.jpg' : files . '/empleados/' . $empleado['foto']; ?>"></td>
					<td class="text-nowrap"><?= escape($empleado['nombres']); ?></td>
					<td class="text-nowrap"><?= escape($empleado['paterno']); ?></td>
					<td class="text-nowrap"><?= escape($empleado['materno']); ?></td>
					<td class="text-nowrap"><?= ($empleado['genero'] == 'm') ? 'Masculino' : 'Femenino'; ?></td>
					<td class="text-nowrap">
						<span class="hidden"><?= escape($empleado['fecha_nacimiento']); ?></span>
						<span><?= date_decode($empleado['fecha_nacimiento'], $_format); ?></span>
					</td>
					<td class="text-nowrap text-right"><?= escape($empleado['edad']); ?></td>
					<td class="text-nowrap text-right"><?= escape($empleado['ci']); ?></td>
					<td class="text-nowrap"><?= escape($empleado['procedencia']); ?></td>
					<td class="width-md"><?= escape($empleado['direccion']); ?></td>
					<td class="text-nowrap"><?= escape(str_replace(',', ' / ', $empleado['telefono'])); ?></td>
					<td class="text-nowrap"><?= escape($empleado['cargo']); ?></td>
					<td class="text-nowrap"><?= escape($empleado['sucursal']); ?></td>
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
						<span class="text-primary">No asignado</span>
						<?php endif ?>
					</td>
					<td class="text-nowrap">
						<?php if ($empleado['activo'] == 's') : ?>
						<span class="text-success">Si</span>
						<?php else : ?>
						<span class="text-primary">No</span>
						<?php endif ?>
					</td>
					<td class="text-nowrap">
						<?php if ($empleado['fecha_contratacion'] == '0000-00-00') : ?>
						<span class="text-primary">No asignado</span>
						<?php else : ?>
						<span class="hidden"><?= escape($empleado['fecha_contratacion']); ?></span>
						<span><?= date_decode($empleado['fecha_contratacion'], $_format); ?></span>
						<?php endif ?>
					</td>
					<td class="text-nowrap">
						<?php if ($empleado['fecha_finalizacion'] == '0000-00-00') : ?>
						<span class="text-primary">No asignado</span>
						<?php else : ?>
						<span class="hidden"><?= escape($empleado['fecha_finalizacion']); ?></span>
						<span><?= date_decode($empleado['fecha_finalizacion'], $_format); ?></span>
						<?php endif ?>
					</td>
	<!-- 				<td class="text-nowrap">
						<span class="hidden"><?= escape($empleado['alta']); ?></span>
						<span class="text-success"><?= date_decode($empleado['alta'], $_format); ?></span>
					</td>
					<td class="text-nowrap">
						<?php if ($empleado['baja'] < $ayer) : ?>
						<span class="hidden"><?= escape($empleado['baja']); ?></span>
						<span class="text-primary"><?= date_decode($empleado['baja'], $_format); ?></span>
						<?php endif ?>
					</td> -->
					<td class="width-md"><?= escape($empleado['observacion']); ?></td>
					<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar || $permiso_bloquear || $permiso_desbloquear || $permiso_fijar || $permiso_asignar) : ?>
					<td class="text-nowrap">
						<?php if ($permiso_ver) : ?>
						<a href="?/empleados/ver/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Ver empleado"><span class="glyphicon glyphicon-search" style="color: #c4332f;"></span></a>
						<?php endif ?>
						<?php if ($permiso_modificar) : ?>
						<a href="?/empleados/modificar/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Modificar empleado"><span class="glyphicon glyphicon-edit" style="color: #c4332f;"></span></a>
						<?php endif ?>
						<?php if ($permiso_eliminar) : ?>
						<a href="?/empleados/eliminar/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Eliminar empleado" data-eliminar="true"><span class="glyphicon glyphicon-trash" style="color: #c4332f;"></span></a>
						<?php endif ?>
						<?php if ($permiso_bloquear) : ?>
	<!-- 					<a href="?/empleados/bloquear/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Bloquear empleado" data-bloquear="true"><span class="glyphicon glyphicon-remove-circle" style="color: #c4332f;"></span></a>
	-->					<?php endif ?>
						<?php if ($permiso_desbloquear) : ?>
	<!-- 					<a href="?/empleados/desbloquear/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Desbloquear empleado" data-desbloquear="true"><span class="glyphicon glyphicon-ok-circle" style="color: #c4332f;"></span></a>
	-->					<?php endif ?>
						<?php if ($permiso_fijar) : ?>
						<a href="?/empleados/fijar/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Fijar salario" data-fijar="true"><span class="glyphicon glyphicon-usd" style="color: #c4332f;"></span></a>
						<?php endif ?>
						<?php if ($permiso_asignar) : ?>
						<a href="?/empleados/asignar/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Asignar horario" data-asignar="true"><span class="glyphicon glyphicon-time" style="color: #c4332f;"></span></a>
						<?php endif ?>
						<?php if ($permiso_pin) : ?>
						<a href="?/empleados/pin/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Fijar pin" data-pin="true"><span class="glyphicon glyphicon-barcode" style="color: #c4332f;"></span></a>
						<?php endif ?>
					</td>
					<?php endif ?>
					<?php if ($permiso_eliminar || $permiso_bloquear || $permiso_desbloquear || $permiso_fijar || $permiso_asignar) : ?>
					<!-- <td class="text-nowrap">
						<input type="checkbox" data-toggle="tooltip" data-title="Seleccionar" data-seleccionar="<?= $empleado['id_empleado']; ?>">
					</td> -->
					<?php endif ?>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
		<?php else : ?>
		<div class="alert alert-info">
			<strong>Atención!</strong>
			<ul>
				<li>No existen empleados registrados en la base de datos.</li>
				<li>Para crear nuevos empleados debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
			</ul>
		</div>
		<?php endif ?>
			<!-- ============================================================== -->
			<!-- end datos -->
			<!-- ============================================================== -->
			</div>
		</div>
</div>

<div class="panel-body">
	<?php if ($permiso_crear || $permiso_eliminar || $permiso_bloquear || $permiso_desbloquear || $permiso_fijar || $permiso_asignar || $permiso_imprimir) : ?>
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
					<?php if ($permiso_crear) : ?>
					<li><a href="?/empleados/crear"><span class="glyphicon glyphicon-plus"></span> Crear empleado</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<li><a href="?/empleados/eliminar" data-grupo-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar empleados</a></li>
					<?php endif ?>
					<?php if ($permiso_bloquear) : ?>
<!-- 					<li><a href="?/empleados/bloquear" data-grupo-bloquear="true"><span class="glyphicon glyphicon-remove-circle"></span> Bloquear empleados</a></li>
 -->					<?php endif ?>
					<?php if ($permiso_desbloquear) : ?>
<!-- 					<li><a href="?/empleados/desbloquear" data-grupo-desbloquear="true"><span class="glyphicon glyphicon-ok-circle"></span> Desbloquear empleados</a></li>
 -->					<?php endif ?>
					<?php if ($permiso_fijar) : ?>
					<li><a href="?/empleados/fijar" data-grupo-fijar="true"><span class="glyphicon glyphicon-usd"></span> Fijar salarios</a></li>
					<?php endif ?>
					<?php if ($permiso_asignar) : ?>
					<li><a href="?/empleados/asignar" data-grupo-asignar="true"><span class="glyphicon glyphicon-time"></span> Asignar horarios</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/empleados/imprimir" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir empleados</a></li>
					<?php endif ?>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	<?php endif ?>
	<?php if ($message = get_notification()) : ?>
	<div class="alert alert-<?= $message['type']; ?>">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong><?= $message['title']; ?></strong>
		<p><?= $message['content']; ?></p>
	</div>
	<?php endif ?>
	<?php if ($empleados) : ?>
	<table id="table" class="table table-bordered table-condensed table-striped table-hover">
		<thead>
			<tr class="active">
				<th class="text-nowrap">#</th>
				<th class="text-nowrap">Foto</th>
				<th class="text-nowrap">Nombres</th>
				<th class="text-nowrap">Apellido paterno</th>
				<th class="text-nowrap">Apellido materno</th>
				<th class="text-nowrap">Género</th>
				<th class="text-nowrap">Fecha de nacimiento</th>
				<th class="text-nowrap">Edad</th>
				<th class="text-nowrap">Cédula de identidad</th>
				<th class="text-nowrap">Procedencia</th>
				<th class="text-nowrap">Dirección</th>
				<th class="text-nowrap">Teléfono</th>
				<th class="text-nowrap">Cargo</th>
				<th class="text-nowrap">Sucursal</th>
				<th class="text-nowrap">Salario</th>
				<th class="text-nowrap">Horario</th>
				<th class="text-nowrap">Activo</th>
				<th class="text-nowrap">Fecha de contrato</th>
				<th class="text-nowrap">Fecha de retiro</th>
				<!-- <th class="text-nowrap">Primera asistencia</th>
				<th class="text-nowrap">Última asistencia</th> -->
				<th class="text-nowrap">Observación</th>
				<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar || $permiso_bloquear || $permiso_desbloquear || $permiso_fijar || $permiso_asignar) : ?>
				<th class="text-nowrap">Opciones</th>
				<?php endif ?>
				<?php if ($permiso_eliminar || $permiso_bloquear || $permiso_desbloquear || $permiso_fijar || $permiso_asignar) : ?>
				<!-- <th class="text-nowrap">
					<span class="hidden">Selección</span>
					<span class="glyphicon glyphicon-check"></span>
				</th> -->
				<?php endif ?>
			</tr>
		</thead>
<!-- 		<tfoot>
			<tr class="active">
				<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
				<th class="text-nowrap text-middle" data-datafilter-filter="false">Foto</th>
				<th class="text-nowrap text-middle">Nombres</th>
				<th class="text-nowrap text-middle">Apellido paterno</th>
				<th class="text-nowrap text-middle">Apellido materno</th>
				<th class="text-nowrap text-middle">Género</th>
				<th class="text-nowrap text-middle" data-datafilter-visible="false">Fecha de nacimiento</th>
				<th class="text-nowrap text-middle">Edad</th>
				<th class="text-nowrap text-middle" data-datafilter-visible="false">Cédula de identidad</th>
				<th class="text-nowrap text-middle" data-datafilter-visible="false">Procedencia</th>
				<th class="text-nowrap text-middle" data-datafilter-visible="false">Dirección</th>
				<th class="text-nowrap text-middle">Teléfono</th>
				<th class="text-nowrap text-middle">Cargo</th>
				<th class="text-nowrap text-middle">Sucursal</th>
				<th class="text-nowrap text-middle">Salario</th>
				<th class="text-nowrap text-middle">Horario</th>
				<th class="text-nowrap text-middle">Activo</th>
				<th class="text-nowrap text-middle" data-datafilter-visible="false">Fecha de contrato</th>
				<th class="text-nowrap text-middle" data-datafilter-visible="false">Fecha de retiro</th>
				<th class="text-nowrap text-middle">Primera asistencia</th>
				<th class="text-nowrap text-middle">Última asistencia</th>
				<th class="text-nowrap text-middle" data-datafilter-visible="false">Observación</th>
				<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar || $permiso_bloquear || $permiso_desbloquear || $permiso_fijar || $permiso_asignar) : ?>
				<th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
				<?php endif ?>
				<?php if ($permiso_eliminar || $permiso_bloquear || $permiso_desbloquear || $permiso_fijar || $permiso_asignar) : ?>
				<th class="text-nowrap text-middle" data-datafilter-filter="false">
					<span class="hidden">Selección</span>
					<input type="checkbox" data-toggle="tooltip" data-title="Seleccionar todo" data-grupo-seleccionar="true">
				</th>
				<?php endif ?>
			</tr>
		</tfoot> -->
		<tbody>
			<?php foreach ($empleados as $nro => $empleado) : ?>
			<?php $horas = horas($db, $empleado['horario_id']); ?>
			<?php if ($empleado['activo'] == 'n') : ?>
			<tr class="primary">	
			<?php else : ?>
				<?php if ($empleado['fecha_finalizacion'] != '0000-00-00' && $empleado['fecha_finalizacion'] < $hoy): ?>
				<tr class="info">	
				<?php else: ?>
				<tr>
				<?php endif ?>
			<?php endif ?>
				<th class="text-nowrap"><?= $nro + 1; ?></th>
				<td class="text-nowrap"><img src="<?= ($empleado['foto'] == '') ? imgs . '/avatar.jpg' : files . '/empleados/small__' . $empleado['foto']; ?>" width="30" height="30" class="img-circle" data-toggle="lightbox" data-lightbox-image="<?= ($empleado['foto'] == '') ? imgs . '/avatar.jpg' : files . '/empleados/' . $empleado['foto']; ?>"></td>
				<td class="text-nowrap"><?= escape($empleado['nombres']); ?></td>
				<td class="text-nowrap"><?= escape($empleado['paterno']); ?></td>
				<td class="text-nowrap"><?= escape($empleado['materno']); ?></td>
				<td class="text-nowrap"><?= ($empleado['genero'] == 'm') ? 'Masculino' : 'Femenino'; ?></td>
				<td class="text-nowrap">
					<span class="hidden"><?= escape($empleado['fecha_nacimiento']); ?></span>
					<span><?= date_decode($empleado['fecha_nacimiento'], $_format); ?></span>
				</td>
				<td class="text-nowrap text-right"><?= escape($empleado['edad']); ?></td>
				<td class="text-nowrap text-right"><?= escape($empleado['ci']); ?></td>
				<td class="text-nowrap"><?= escape($empleado['procedencia']); ?></td>
				<td class="width-md"><?= escape($empleado['direccion']); ?></td>
				<td class="text-nowrap"><?= escape(str_replace(',', ' / ', $empleado['telefono'])); ?></td>
				<td class="text-nowrap"><?= escape($empleado['cargo']); ?></td>
				<td class="text-nowrap"><?= escape($empleado['sucursal']); ?></td>
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
					<span class="text-primary">No asignado</span>
					<?php endif ?>
				</td>
				<td class="text-nowrap">
					<?php if ($empleado['activo'] == 's') : ?>
					<span class="text-success">Si</span>
					<?php else : ?>
					<span class="text-primary">No</span>
					<?php endif ?>
				</td>
				<td class="text-nowrap">
					<?php if ($empleado['fecha_contratacion'] == '0000-00-00') : ?>
					<span class="text-primary">No asignado</span>
					<?php else : ?>
					<span class="hidden"><?= escape($empleado['fecha_contratacion']); ?></span>
					<span><?= date_decode($empleado['fecha_contratacion'], $_format); ?></span>
					<?php endif ?>
				</td>
				<td class="text-nowrap">
					<?php if ($empleado['fecha_finalizacion'] == '0000-00-00') : ?>
					<span class="text-primary">No asignado</span>
					<?php else : ?>
					<span class="hidden"><?= escape($empleado['fecha_finalizacion']); ?></span>
					<span><?= date_decode($empleado['fecha_finalizacion'], $_format); ?></span>
					<?php endif ?>
				</td>
<!-- 				<td class="text-nowrap">
					<span class="hidden"><?= escape($empleado['alta']); ?></span>
					<span class="text-success"><?= date_decode($empleado['alta'], $_format); ?></span>
				</td>
				<td class="text-nowrap">
					<?php if ($empleado['baja'] < $ayer) : ?>
					<span class="hidden"><?= escape($empleado['baja']); ?></span>
					<span class="text-primary"><?= date_decode($empleado['baja'], $_format); ?></span>
					<?php endif ?>
				</td> -->
				<td class="width-md"><?= escape($empleado['observacion']); ?></td>
				<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar || $permiso_bloquear || $permiso_desbloquear || $permiso_fijar || $permiso_asignar) : ?>
				<td class="text-nowrap">
					<?php if ($permiso_ver) : ?>
					<a href="?/empleados/ver/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Ver empleado"><span class="glyphicon glyphicon-search" style="color: #c4332f;"></span></a>
					<?php endif ?>
					<?php if ($permiso_modificar) : ?>
					<a href="?/empleados/modificar/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Modificar empleado"><span class="glyphicon glyphicon-edit" style="color: #c4332f;"></span></a>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<a href="?/empleados/eliminar/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Eliminar empleado" data-eliminar="true"><span class="glyphicon glyphicon-trash" style="color: #c4332f;"></span></a>
					<?php endif ?>
					<?php if ($permiso_bloquear) : ?>
<!-- 					<a href="?/empleados/bloquear/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Bloquear empleado" data-bloquear="true"><span class="glyphicon glyphicon-remove-circle" style="color: #c4332f;"></span></a>
 -->					<?php endif ?>
					<?php if ($permiso_desbloquear) : ?>
<!-- 					<a href="?/empleados/desbloquear/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Desbloquear empleado" data-desbloquear="true"><span class="glyphicon glyphicon-ok-circle" style="color: #c4332f;"></span></a>
 -->					<?php endif ?>
					<?php if ($permiso_fijar) : ?>
					<a href="?/empleados/fijar/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Fijar salario" data-fijar="true"><span class="glyphicon glyphicon-usd" style="color: #c4332f;"></span></a>
					<?php endif ?>
					<?php if ($permiso_asignar) : ?>
					<a href="?/empleados/asignar/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Asignar horario" data-asignar="true"><span class="glyphicon glyphicon-time" style="color: #c4332f;"></span></a>
					<?php endif ?>
					<?php if ($permiso_pin) : ?>
					<a href="?/empleados/pin/<?= $empleado['id_empleado']; ?>" data-toggle="tooltip" data-title="Fijar pin" data-pin="true"><span class="glyphicon glyphicon-barcode" style="color: #c4332f;"></span></a>
					<?php endif ?>
				</td>
				<?php endif ?>
				<?php if ($permiso_eliminar || $permiso_bloquear || $permiso_desbloquear || $permiso_fijar || $permiso_asignar) : ?>
				<!-- <td class="text-nowrap">
					<input type="checkbox" data-toggle="tooltip" data-title="Seleccionar" data-seleccionar="<?= $empleado['id_empleado']; ?>">
				</td> -->
				<?php endif ?>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	<?php else : ?>
	<div class="alert alert-info">
		<strong>Atención!</strong>
		<ul>
			<li>No existen empleados registrados en la base de datos.</li>
			<li>Para crear nuevos empleados debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
		</ul>
	</div>
	<?php endif ?>
</div>

<!-- Modal fijar inicio -->
<?php if ($permiso_fijar) : ?>
<div id="modal_fijar" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/empleados/fijar" id="form_fijar" class="modal-content loader-wrapper" autocomplete="off">
			<input type="hidden" name="<?= $csrf; ?>">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Fijar salarios</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="fecha_salario_fijar" class="control-label">Fecha de modificación:</label>
					<input type="text" value="<?= date_decode($hoy, $_format); ?>" name="fecha_salario" id="fecha_salario_fijar" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
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
			<input type="hidden" name="<?= $csrf; ?>">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Asignar horarios</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="fecha_asignacion_asignar" class="control-label">Fecha de modificación:</label>
					<input type="text" value="<?= date_decode($hoy, $_format); ?>" name="fecha_asignacion" id="fecha_asignacion_asignar" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
				</div>
				<div class="form-group">
					<label for="horario_id" class="control-label">Turnos disponibles:</label>
					<?php foreach ($horarios as $nro => $horario) : ?>
					<div class="checkbox">
						<label>
							<input type="checkbox" value="<?= $horario['id_horario']; ?>" name="horario_id[]" id="horario_id" data-validation="checkbox_group" data-validation-qty="min1" data-validation-error-msg="Debe seleccionar al menos 1 turno">
							<samp class="text-primary"><b><?= substr($horario['entrada'], 0, -3); ?></b></samp>
							<span class="text-muted">&mdash;</span>
							<samp class="text-primary"><b><?= substr($horario['salida'], 0, -3); ?></b></samp>
							<span class="text-muted">&nbsp;</span>
							<span class="text-muted" data-toggle="tooltip" data-title="<?= $horario['descripcion']; ?>" data-placement="right">[<?= str_replace(',', ' - ', $horario['dias']); ?>]</span>
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

<!-- Modal pin inicio -->
<?php if ($permiso_pin) : ?>
<div id="modal_pin" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/empleados/pin" id="form_pin" class="modal-content loader-wrapper" autocomplete="off">
			<input type="hidden" name="<?= $csrf; ?>">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Fijar pin</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="tarjeta" class="control-label">Nuevo pin:</label>
					<input type="text" value="" name="tarjeta" id="tarjeta" class="form-control" data-validation="required" maxlength="4">
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
			<div id="loader_pin" class="loader-wrapper-backdrop hidden">
				<span class="loader"></span>
			</div>
		</form>
	</div>
</div>
<?php endif ?>
<!-- Modal pin fin -->


<script src="<?= js; ?>/jquery.dataTables.min.js"></script>
<script src="<?= js; ?>/dataTables.bootstrap.min.js"></script>
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<script src="<?= js; ?>/jquery.dataFilters.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/jquery.maskedinput.min.js"></script>
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
		var csrf = '<?= $csrf; ?>';
		bootbox.confirm('¿Está seguro que desea eliminar el empleado?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});

	$('[data-grupo-eliminar]').on('click', function (e) {
		e.preventDefault();
		var id_empleado = $(this).attr('data-grupo-eliminar');
		var href = $(this).attr('href') + '/' + id_empleado;
		var csrf = '<?= $csrf; ?>';
		if (id_empleado != 'true') {
			bootbox.confirm('¿Está seguro que desea eliminar a los empleados seleccionados?', function (result) {
				if (result) { $.request(href, csrf); }
			});
		} else {
			bootbox.alert('Para continuar con el proceso debe seleccionar al menos un empleado.');
		}
	});
	<?php endif ?>

	<?php if ($permiso_bloquear) : ?>
	$('[data-bloquear]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var csrf = '<?= $csrf; ?>';
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
		var csrf = '<?= $csrf; ?>';
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
		var csrf = '<?= $csrf; ?>';
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
		var csrf = '<?= $csrf; ?>';
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

	<?php if ($empleados) : ?>
	$('#table').on('search.dt order.dt page.dt length.dt', function () {
		$('[data-grupo-seleccionar]').prop('checked', false).trigger('change');
	}).DataFilter({
		filter: true,
		name: 'empleados',
		reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>',
		values: {
			stateSave: true
		}
	});
	<?php endif ?>



	<?php if ($permiso_pin) : ?>
	var $modal_pin = $('#modal_pin'), $form_pin = $('#form_pin'), $loader_pin = $('#loader_pin'), $tarjeta = $('#tarjeta');

	$.validate({
		form: '#form_pin',
		modules: 'basic',
		onSuccess: function () {
			$loader_pin.removeClass('hidden');
		}
	});

	//$tarjeta.mask('<?= $formato_numeral; ?>');

	$('[data-pin]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		$form_pin.attr('action', href);
		$modal_pin.modal({
			backdrop: 'static',
			keyboard: false
		});
	});

	$('[data-grupo-pin]').on('click', function (e) {
		e.preventDefault();
		var id_empleado = $(this).attr('data-grupo-pin');
		var href = $(this).attr('href') + '/' + id_empleado;
		if (id_empleado != 'true') {
			$form_pin.attr('action', href);
			$modal_pin.modal({
				backdrop: 'static',
				keyboard: false
			});
		} else {
			bootbox.alert('Para continuar con el proceso debe seleccionar al menos un empleado.');
		}
	});

	$modal_pin.on('hidden.bs.modal', function () {
		$form_pin.trigger('reset');
	}).on('show.bs.modal', function (e) {
		if ($('.modal:visible').size() != 0) { e.preventDefault(); }
	}).on('shown.bs.modal', function () {
		$form_pin.find('.form-control:nth(1)').focus();
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-design'); ?>