<?php

// Obtiene los parametros
$id_ing = (isset($params[0])) ? $params[0] : 0;
$id_egreso = explode('-', $id_ing);

// Recupera la variable
$variable = get_variable('ingreso');
$formato_textual = get_date_textual($_institution['formato']);

$fecha_inicial = $variable['fecha_inicial'];
$fecha_final = $variable['fecha_final'];

$empleados = $db->from("sys_empleados")->where('activo','s')->fetch();
$sucursales = $db->from("sys_instituciones")->fetch();
$pagos     = [];
$montoTotal = 0;


// Obtiene la fecha de hoy
$hoy = now();

// Obtiene las fechas inicial y final
/*$fecha_inicial = str_replace('/', '-', first_month_day($hoy, $_format));
$fecha_final = str_replace('/', '-', last_month_day($hoy, $_format));*/

?>
<?php require_once show_template('header-sidebar'); ?>
<style type="text/css">
.verticalText {
	writing-mode: vertical-lr;
	transform: rotate(180deg);
}
#tabla td,th{
	text-align:center;
}
</style>
<div class="panel-heading">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Planilla de sueldos</strong>
	</h3>
</div>
<div class="panel-body">
	<div class="row">
		<div class="col-xs-6">
			<div class="text-label">
				<strong class="text-primary text-ellipsis animated flash">
					<span class="badge"><?= sizeof($pagos); ?></span>
					<u class="text-uppercase">Resultados encontrados</u>
				</strong>
			</div>
		</div>

		<div class="col-xs-6 text-right">
			<div class="btn-group">
				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
					<span class="glyphicon glyphicon-menu-hamburger"></span>
					<span class="hidden-xs">Acciones</span>
				</button>
				<ul class="dropdown-menu dropdown-menu-right">
					<li>
						<a href="?/ganadero/imprimir" data-imprimir="true">
							<span class="glyphicon glyphicon-print"></span>
							<span>Imprimir pdf</span>
						</a>
					</li>
					<li>
						<a href="?/ganadero/exportar" data-exportar="true">
							<span class="glyphicon glyphicon-export"></span>
							<span>Exportar excel</span>
						</a>
					</li>
				</ul>

			</div>
		</div>
	</div>
	<hr>
	<form method="post" action="?/pagos/buscar" autocomplete="off" class="margin-none" id="form_buscar">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="empleado">Trabajador:</label>
					<select class="form-control" name="empleado" id="empleado">
						<option value="" selected="selected">Seleccionar</option>
						<?php foreach ($empleados as $empleado) : ?>
							<option value="<?= escape($empleado['id_empleado']); ?>"><?= escape($empleado['nombres'].' '.$empleado['paterno']); ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="sucursal_id">Sucursal:</label>
					<select class="form-control" name="sucursal" id="sucursal">
						<option value="" selected="selected">Seleccionar</option>
						<?php foreach ($sucursales as $sucursal) : ?>
							<option value="<?= escape($sucursal['id_institucion']); ?>"><?= escape($sucursal['nombre']); ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="fecha_inicial">Fecha inicial:</label>
					<input type="text" value="" name="fecha_inicial" id="fecha_inicial" class="form-control">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="fecha_final">Fecha final:</label>
					<input type="text" value="" name="fecha_final" id="fecha_final" class="form-control">
				</div>
			</div>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary">
				<span class="glyphicon glyphicon-search"></span>
				<span>Buscar</span>
			</button>
		</div>
	</form>
	<?php if (true) : ?>
	<ul class="list-group">
		<?php $saldo = 0;?>
		<div class="table-responsive margin-none" id="lista">
		
		</div>
	</ul>
	<?php else : ?>
	<div class="alert alert-info">
		<strong>Atención!</strong>
		<ul>
			<li>No existen coincidencias, asegurese de escribir los datos correctos.</li>
		</ul>
	</div>
	<?php endif ?>
</div>

<script src="<?= js; ?>/bootstrap-datetimepicker.min.js"></script>
<script src="<?= js; ?>/jquery.dataTables.min.js"></script>
<script src="<?= js; ?>/jquery.dataFilters.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/bootstrap-notify.min.js"></script>
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script>
$(function () {

	$("#empleado").selectize();
	$("#sucursal").selectize();
	//buscar();
	$imprimir = $('[data-imprimir]')
	$imprimir.on('click', function (e) {
		e.preventDefault();
		var fecha_inicio = $("#fecha_inicio").text(),
			fecha_fin    = $("#fecha_fin").text(),
			$table = $('#tabla');
		var thead=`<thead>
	  				<tr>
	  					<th rowspan="2"><p style="writing-mode: vertical-lr;transform: rotate(180deg);">Código<p></th>
	  					<th>DÍAS TRABAJADOS 30 DIAS<br>Hrs. TRABAJADOS 240 Hrs.</th>
	  					<th colspan="4">EXTRA Hrs*2</th>
	  					<th colspan="2">PLANILLA DE SUELDOS<br>`+fecha_inicio+` `+fecha_fin+`</th>
	  					<th colspan="4">INTERNOS</th>
	  					<th rowspan="2">LÍQUIDO PAGABLE</th>
	  					<th rowspan="2" width="10%">Firma</th>
	  				</tr>
	  				<tr>
						<th>NOMBRES Y APELLIDOS</th>
						<th><p style="writing-mode: vertical-lr;transform: rotate(180deg);">ASISTENCIA<p></th>
						<th><p style="writing-mode: vertical-lr;transform: rotate(180deg);">FALTAS</p></th>
						<th><p style="writing-mode: vertical-lr;transform: rotate(180deg);">ATRAZOS</p></th>
						<th><p style="writing-mode: vertical-lr;transform: rotate(180deg);">EXTRAS</p></th>
						<th>SUELDO BÁSICO</th>
						<th>TOTAL INGRESO</th>
						<th><p style="writing-mode: vertical-lr;transform: rotate(180deg);">FALTAS</p></th>
						<th><p style="writing-mode: vertical-lr;transform: rotate(180deg);">ATRASOS</p></th>
						<th><p style="writing-mode: vertical-lr;transform: rotate(180deg);">ADELANTOS</p></th>
						<th>TOTAL DESC.</th>
	  				</tr>
	  			</thead>`,tbody="";
		$table.find('tbody').find('tr').each(function (i) {
			tbody += '<tr>'
			$(this).children(':visible').each(function (j) {
				text = $(this).text();
				text = text.replace(new RegExp('<\\s*([a-z]+).*?>', 'g'), '<$1>');
				text = text.replace(new RegExp('\\t', 'g'), '');
				text = text.replace(new RegExp('\\n', 'g'), ' ');
				tbody += '<td>' + $.trim(text) + '</td>';
			});
			tbody += '</tr>';
		});
		content = `<div>
			<style>
			table{
				border-collapse:collapse
			}
			</style>
			<h1 align="center">Planilla de sueldos</h1>
			<table border="1" cellspacing="2" cellpadding="2" style="font-size:14px;font-family:Helvetica, Arial, sans-serif;margin: 0 auto;">`+
				thead + tbody
			+`</table>
		</div>`;
		//content = '<!doctype html><html lang="en"><head><meta charset="utf-8"><title>' + document.title.toLowerCase() + '</title><style>body{font-family:Helvetica, Arial, sans-serif;font-size:10px;margin:0;}h1{margin:0;margin-bottom:15px;text-align:center;text-decoration:underline;}table{border-collapse:collapse;color:#000;font-size:10px;width:100%;}th{background-color:#eee;border:1px solid #444;font-size:12px;padding:1px;}td{border:1px solid #444;padding:1px;vertical-align:top;}h2,h3{margin:0;margin-bottom:10px;text-align:center;}</style></head><body><h1>' + document.title + '</h1><table cellpadding="0" cellspacing="0">' + thead + tbody + '</table></body></html>';
		//content = '<!doctype html><html lang="en"><head><meta charset="utf-8"><title>' + document.title.toLowerCase() + '</title></head><body><h1>' + document.title + '</h1><table cellpadding="0" cellspacing="0">' + thead + tbody + '</table></body></html>';
		preview = window.open('', '_blank');
		preview.document.write(content);
		preview.document.close();
		preview.focus();
		preview.print();
		preview.close();
	});
	var $exportar = $('[data-exportar]');
	$exportar.on('click', function (e) {
		var fecha_inicio = $("#fecha_inicio").text(),
			fecha_fin = $("#fecha_fin").text(),
			$table    = $('#tabla');
		var thead =`<thead>
	  				<tr>
	  					<th rowspan="2"><p style="mso-rotate:90;">Código<p></th>
	  					<th>DÍAS TRABAJADOS 30 DIAS<br>Hrs. TRABAJADOS 240 Hrs.</th>
	  					<th colspan="4">EXTRA Hrs*2</th>
	  					<th colspan="2">PLANILLA DE SUELDOS<br>`+fecha_inicio+` `+fecha_fin+`</th>
	  					<th colspan="4">INTERNOS</th>
	  					<th rowspan="2">LÍQUIDO PAGABLE</th>
	  					<th rowspan="2"><p style="writing-mode: vertical-lr;transform: rotate(180deg);">Firma<p></th>
	  				</tr>
	  				<tr>
						<th>NOMBRES Y APELLIDOS</th>
						<th><p style="mso-rotate:90;">ASISTENCIA<p></th>
						<th><p style="mso-rotate:90;">FALTAS</p></th>
						<th><p style="mso-rotate:90;">ATRAZOS</p></th>
						<th><p style="mso-rotate:90;">EXTRAS</p></th>
						<th>SUELDO BÁSICO</th>
						<th>TOTAL INGRESO</th>
						<th><p style="mso-rotate:90;">FALTAS</p></th>
						<th><p style="mso-rotate:90;">ATRASOS</p></th>
						<th><p clstyle="mso-rotate:90;">ADELANTOS</p></th>
						<th>TOTAL DESC.</th>
	  				</tr>
	  			</thead>`,tbody="";

	  			$table.find('tbody').find('tr').each(function (i) {
					tbody += '<tr>'
					$(this).children(':visible').each(function (j) {
						text = $(this).text();
						text = text.replace(new RegExp('\\t', 'g'), '');
						text = text.replace(new RegExp('\\n', 'g'), ' ');
						tbody += '<td>' + $.trim(text) + '</td>';
					});
					tbody += '</tr>';
				});
		content = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>' + document.title + '</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table border="1">' + thead + tbody + '</table></body></html>';
		$(this).attr('download', document.title.toLowerCase() + '.xls');
		$(this).attr('href', 'data:application/vnd.ms-excel;base64,' + $.base64.encode(content));
	});
	
	$("#form_buscar").on('submit',function(e){
		e.preventDefault();
		buscar(this);
	});

	function buscar(elemento = ''){
		if(elemento != ''){
			data = $(elemento).serialize();
		} else {
			data= {
				empleado:0,
				fecha_inicial:'<?= $fecha_inicial; ?>',
				fecha_final:'<?= $fecha_final?>'
			}
		}

		$('#loader').fadeIn(100);

		$.ajax({
			url: '?/empleados/buscar',
			type: 'POST',
			dataType:'json',
			data: data
		})
		.done(function(data) {
			if(data.success){
				var registros = eval(data.empleados);
				fecha_inicial = data.fecha_inicial,
				fecha_final = data.fecha_final,
				importe = 0,total = 0,total_confeccion = 0,html='<div class="alert alert-danger">No se encontraron resultados</div>';
			
			if(registros.length > 0){
				html =`<table class="table" border="1" id="tabla">
				  			<thead>
				  				<tr>
				  					<th rowspan="2"><p class="verticalText">Código<p></th>
				  					<th>DÍAS TRABAJADOS 30 DIAS<br>Hrs. TRABAJADOS 240 Hrs.</th>
				  					<th colspan="4">EXTRA Hrs*2</th>
				  					<th colspan="2">PLANILLA DE SUELDOS<br><span id="fecha_inicio">Desde: `+fecha_inicial+`</span><span id="fecha_fin"> Hasta: `+fecha_final+`</span></th>
				  					<th colspan="4">INTERNOS</th>
				  					<th rowspan="2">LÍQUIDO PAGABLE</th>
				  					<th rowspan="2">Firma</th>
				  				</tr>
				  				<tr>
									<th>NOMBRES Y APELLIDOS</th>
									<th><p class="verticalText">ASISTENCIA<p></th>
									<th><p class="verticalText">FALTAS</p></th>
									<th><p class="verticalText">ATRAZOS</p></th>
									<th><p class="verticalText">EXTRAS</p></th>
									<th>SUELDO BÁSICO</th>
									<th>TOTAL INGRESO</th>
									<th><p class="verticalText">FALTAS</p></th>
									<th><p class="verticalText">ATRASOS</p></th>
									<th><p class="verticalText">ADELANTOS</p></th>
									<th>TOTAL DESC.</th>
				  				</tr>
				  			</thead>
				  			
				  			<tbody>`;
				  for (var i = 0; i < registros.length; i++) {
				  	total+=parseFloat(registros[i]["liquido"]);
				  	 html +=`<tr>
				  	 			<td>`+registros[i]["codigo"]+`</td>
				  	 			<td>`+registros[i]["nombres"]+' '+registros[i]["paterno"]+`</td>
				  	 			<td>`+registros[i]["asistencia"]+`</td>
				  	 			<td>`+registros[i]["falta"]+`</td>
				  	 			<td>`+registros[i]["atraso"]+`</td>
				  	 			<td>`+registros[i]["extra"]+`</td>
				  	 			<td>`+registros[i]["salario"]+`</td>
				  	 			<td>`+registros[i]["total_ingreso"]+`</td>
				  	 			<td>`+registros[i]["total_faltas"]+`</td>
								<td>`+registros[i]["total_atrasos"]+`</td>
								<td>`+registros[i]["adelanto"]+`</td>
								<td>`+registros[i]["total_descuento"]+`</td>
								<td>`+registros[i]["liquido"]+`</td>
								<td>&nbsp;</td>
				  	 		</tr>`;
				 }
				 html+=`</tbody>
						<tfoot>
							<tr>
								<th style="text-align: right" colspan="12">Total</th>
								<th>`+total+`</th>
								<th>&nbsp;</th>
							</tr>
				  		</tfoot>
					 </table>`;
			}

			$("#total").text(total_confeccion+total);
			$("#lista").html(html);
			} else {
				$.notify({
					title:'<strong>Hubo un fallo en la consulta</strong>',
					message:'<div>Revise los datos de los empleados</div>'
				},{
					type:'danger'
				});
			}
			
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			$('#loader').fadeOut(100);
		});
	}

	$("#imprimir").on('click',function(e){
		e.preventDefault();
		var id_empleado = $("#empleado").val();
		var fecha_inicial = $("#fecha_inicial").val();
		var fecha_final = $("#fecha_final").val();
		if(fecha_inicial==''){
			fecha_inicial='0000-00-00';
		}
		if(fecha_final==''){
			fecha_final='0000-00-00';
		}
		window.open("?/pagos/imprimir/"+id_empleado+"/"+fecha_inicial+"/"+fecha_final, '_blank');
	});

	var $modal_precio = $('#modal_precio');

	$modal_precio.find('[data-cancelar_precio]').on('click', function () {
		$modal_precio.modal('hide');
	});

	$("#form_precio").on('submit',function(e){
        e.preventDefault();
	});

	$modal_precio.on('show.bs.modal',function(){
		$modal_precio.find('.form-control:first').focus();
	});

	$modal_precio.on('hidden.bs.modal', function () {
		$('#form_precio').trigger('reset');
	});

	

	$.validate({
		form: '#form_precio',
		modules: 'basic',
		onSuccess: function(){
			
			var id_egreso = $('#id_ser_h').val();
			var nuevoMonto = $('#nuevo_precio').val();
			var estado = $('#estado').val();
			
			$.ajax({
				type:'POST',
				dataType:'json',
				url:'?/creditos/cambia',
				data:{
					id_egreso:id_egreso,
					monto:parseFloat(nuevoMonto).toFixed(2),
					estado:estado
				}
			}).done(function(servicio){
				    window.setTimeout('location.reload()', 3000); 
				//var cell = table.cell($('[data-precio_servicio='+servicio.ingreso_id+']'));
				//cell.data(servicio.precio).draw();
				
				$.notify({
					title:'<strong>Actualizacion satisfactoria</strong>',
					message:'<div>El precio del servicio se actualizo correctamente.</div>'
				},{
					type:'success'
				});

				$('#modal_precio').modal('hide');
			});
		}
	});

	var table = $('#table').DataFilter({
		filter: true,
		name: 'ingresos',
		reports: 'excel|word|pdf|html'
	});



	var $fecha_inicial = $('#fecha_inicial'),$fecha_final = $('#fecha_final');
	$fecha_inicial.datetimepicker({
		format: '<?= strtoupper($formato_textual); ?>'
	});

	$fecha_final.datetimepicker({
		format: '<?= strtoupper($formato_textual); ?>'
	});
});
</script>
<?php require_once show_template('footer-sidebar'); ?>