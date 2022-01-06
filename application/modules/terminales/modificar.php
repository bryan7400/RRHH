<?php

$csrf = set_csrf();
// Obtiene los equipos
$equipos=[];

$id_terminal = (isset($_params[0])) ? $_params[0] : 0;

$terminal = $db->select('t.*, c.categoria as categoria,a.*')->from('pel_terminales t')->join('pel_categorias c', 't.categoria_id = c.id_categoria', 'left')->join('pel_almacenes a', 't.almacen_id = a.id_almacen', 'left')->where('t.id_terminal', $id_terminal)->fetch_first();

//$equipos = $db->query("select e.*, s.sucursal, s.nro_sucursal, ifnull(f.fallos, 0) as fallos from act_equipos e left join (select equipo_id, count(equipo_id) as fallos from act_fallos where solucionado = 'n' group by equipo_id) f on f.equipo_id = e.id_equipo left join gen_sucursales s on s.id_sucursal = e.sucursal_id order by e.sucursal_id asc, e.codigo asc")->fetch();

$categorias = $db->select('*')->from('pel_categorias')->fetch();
$departamentos= array('LA PAZ'=>'LA PAZ','ORURO'=>'ORURO','POTOSI'=>'POTOSI','COCHABAMBA'=>'COCHABAMBA','SANTA CRUZ'=>'SANTA CRUZ','BENI'=>'BENI','PANDO'=>'PANDO','TARIJA'=>'TARIJA','CHUQUISACA'=>'CHUQUISACA');


//  Obtiene los fallos de un equipo
function fallos($db, $id_equipo) {
	$fallos = $db->query("SELECT * FROM act_fallos WHERE equipo_id = '$id_equipo' and solucionado = 'n'")->fetch();
	return $fallos;
}


?>
<?php require_once show_template('header-sidebar'); ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title" data-header="true">
			<span class="glyphicon glyphicon-option-vertical"></span>
			<strong>Terminales</strong>
		</h3>
	</div>
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="active" id="li-terminal">
				<a href="#informaciones" data-toggle="tab" id="terminal">
					<span class="glyphicon glyphicon-home"></span>
					<span class="hidden-xs">Modificar terminal</span>
				</a>
			</li>
			<li id="li-tienda" class="disabled">
				<a href="#presentaciones" id="tienda">
					<span class="glyphicon glyphicon-facetime-video"></span>
					<span class="hidden-xs">Modificación de almacén</span>
				</a>
			</li>
		</ul>
		<form method="POST" action="?/terminales/teral_guardar" id="form">
			<input type="hidden" name="<?= $csrf; ?>">
			<input type="text" value="<?= $id_terminal; ?>" name="id_terminal" id="id_terminal" class="translate" tabindex="-1">
			<div class="tab-content">
				<div id="informaciones" class="tab-pane fade in active">
					<p class="lead"><strong>Modificar terminal</strong></p>
					<hr>
					<div class="form-group">
						<label for="categoria_id" class="control-label">Categoría:</label>
						<select name="categoria" id="categoria_id" class="form-control" data-validation="required">
							<?php foreach ($categorias as $elemento) : ?>
							<?php if ($elemento['id_categoria'] == $terminal['categoria_id']) : ?>
							<option value="<?= escape($elemento['categoria']) . '|' . $elemento['id_categoria'] ?>" selected="selected"><?= escape($elemento['categoria']); ?></option>
							<?php else : ?>
							<option value="<?= escape($elemento['categoria']) . '|' . $elemento['id_categoria'] ?>"><?= escape($elemento['categoria']); ?></option>
							<?php endif ?>
							<?php endforeach ?>
						</select>
					</div>
					<div class="form-group">
						<label for="codigo_terminal" class="control-label">Código:</label>
						<input type="text" value="<?= $terminal['codigo_terminal']; ?>" name="codigo_terminal" id="codigo_terminal" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max45">
					</div>
					<div class="row">
						<div class="form-group col-md-6">
							<label for="referencia_minima" class="control-label">Referencia mínima:</label>
							<input type="number" value="<?= $terminal['referencia_minima']; ?>" name="referencia_minima" id="referencia_minima" class="form-control" data-validation="required number" data-validation-optional="true">
						</div>
						<div class="form-group col-md-6">
							<label for="referencia_maxima" class="control-label">Referencia máxima:</label>
							<input type="number" value="<?= $terminal['referencia_maxima']; ?>" name="referencia_maxima" id="referencia_maxima" class="form-control" data-validation="required number">
						</div>
					</div>
					<div class="form-group">
						<label for="valor_moneda" class="control-label">Valor de la moneda:</label>
						<input type="text" value="<?= $terminal['valor_moneda']; ?>" name="valor_moneda" id="valor_moneda" class="form-control" data-validation="required letternumber" data-validation-allowing="-/.#() ">
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary" id="next">
							<span class="glyphicon glyphicon-floppy-disk"></span>
							<span>Siguiente</span>
						</button>
					</div>
				</div>

				<div id="presentaciones" class="tab-pane fade">
					<p class="lead"><strong>Asignación de almacén</strong></p>
					<hr>
				
					<div class="form-group">
						<label for="almacen" class="control-label">Nombre del almacén:</label>
						<input type="text" value="<?= $terminal['almacen']; ?>" name="almacen" id="almacen" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max45">
					</div>
					<div class="form-group">
						<label for="direccion" class="control-label">Dirección:</label>
						<input type="text" value="<?= $terminal['direccion']; ?>" name="direccion" id="direccion" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max45">
					</div>
					<div class="form-group">
						<label for="departamento" class="control-label">Departamento:</label>
						<select class="form-control" name="departamento" data-validation="required">
							<?php foreach ($departamentos as $departamento) : ?>
								<?php if ($departamento == $terminal['departamento']) : ?>
									<option value="<?= $departamento; ?>" selected="selected"><?= $departamento ?></option>
								<?php else: ?>
									<option value="<?= $departamento; ?>"><?= $departamento ?></option>
								<?php endif; ?>
							<?php endforeach;?>
						</select>
					</div>
					<!-- <div class="form-group">
						<label for="departamento" class="control-label">Departamento:</label>
						<input type="text" value="" name="departamento" id="departamento" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max45">
					</div> -->
					<div class="form-group">
						<label for="encargado" class="control-label">Encargado:</label>
						<input type="text" value="<?= $terminal['encargado']; ?>" name="encargado" id="encargado" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max45">
					</div>
					<div class="form-group">
						<label for="telefono" class="control-label">Telefono:</label>
						<input type="text" value="<?= $terminal['telefono']; ?>" name="telefono" id="telefono" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max45">
					</div>
					<div class="form-group">
						<label for="porcentaje" class="control-label">Porcentaje a la tienda:</label>
						<input type="text" value="<?= $terminal['porcentaje']; ?>" name="porcentaje" id="porcentaje" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max45">
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary" id='guardar'>
							<span class="glyphicon glyphicon-floppy-disk"></span>
							<span>Guardar</span>
						</button>
						<button type="reset" class="btn btn-default">
							<span class="glyphicon glyphicon-refresh"></span>
							<span>Restablecer</span>
						</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script>
$(function () {

	$("#next").on("click",function(){
		$.validate({
			modules: 'basic',
			onSuccess: function() {
				$('#tienda').attr('data-toggle','tab');
				$("#tienda").click();
				
				$("#li-terminal").attr('class','disabled');
				$('#terminal').removeAttr('data-toggle');
				return false;
			}
		});
	});

	$("#guardar").on("click",function(){
		$.validate({
			modules: 'basic',
		});
	});


	$('#categoria_id').selectize({
		create: true,
		persist: false
	});

	/*var $modal_reportar = $('#modal_reportar'), $form_reportar = $('#form_reportar'), $codigo_reportar = $('#codigo_reportar'), $id_equipo_reportar = $('#id_equipo_reportar'), $contenido_reportar = $('#contenido_reportar'), $observacion = $('#observacion');

	$modal_reportar.on('hidden.bs.modal', function () {
		$(this).find('form').trigger('reset');
	}).on('show.bs.modal', function (e) {
		if ($('.modal:visible').size() != 0) { e.preventDefault(); }
		var id_equipo = $(e.relatedTarget).attr('data-id-equipo');
		var codigo = $(e.relatedTarget).attr('data-codigo');
		$codigo_reportar.text(codigo);
		$id_equipo_reportar.attr('value', id_equipo);
		var contenido = $('[data-contenido="' + id_equipo + '"]').html();
		$('[data-toggle="tooltip"]').tooltip();
		$contenido_reportar.html(contenido);
	}).on('shown.bs.modal', function () {
		$(this).find('.form-control:first').focus();
	});

	$observacion.selectize({
		create: true,
		maxOptions: 7,
		persist: false,
		onInitialize: function () {
			$observacion.show().addClass('selectize-translate');
		},
		onChange: function () {
			$observacion.trigger('blur');
		},
		onBlur: function () {
			$observacion.trigger('blur');
		}
	});

	$form_reportar.on('reset', function () {
		//$observacion.get(0).selectize.clearOptions();
	});*/
});
</script>
<?php require_once show_template('footer-full'); ?>