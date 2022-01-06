<?php

// Obtiene los parametros
$id_empleado = (isset($_params[0])) ? $_params[0] : 0;

//var_dump($id_empleado);exit();
// Obtiene la fecha de hoy con un tiempo de reinicio de 3 horas
$hoy = get_date();

// Obtiene el empleado
$empleado = $db->from('per_empleados')->where('id_empleado', $id_empleado)->where('activo', 's')->where('tarjeta !=', '')->fetch_first();

// Verifica si el empleado existe para registrar los cambios en su asistencia
if ($empleado) {
	// Obtiene la ultima asistencia
	$asistencia = $db->from('per_asistencias')->where('empleado_id', $id_empleado)->where('fecha_asistencia', $hoy)->order_by('entrada', 'desc')->fetch_first();

	// Verifica si existe la asistencia
	if ($asistencia) {
		// Verifica si es entrada o salida
		if ($asistencia['salida'] == '0000-00-00 00:00:00') {
			// Instancia la salida
			$salida = array('salida' => date('Y-m-d H:i:s'));

			// Modifica la asistencia
			$db->where('id_asistencia', $asistencia['id_asistencia'])->update('per_asistencias', $salida);
		} else {
			// Instancia la entrada
			$entrada = array(
				'fecha_asistencia' => $hoy,
				'entrada' => date('Y-m-d H:i:s'),
				'empleado_id' => $id_empleado
			);

			// Crea la asistencia
			$id_entrada = $db->insert('per_asistencias', $entrada);
		}
	} else {
		// Instancia la entrada
		$entrada = array(
			'fecha_asistencia' => $hoy,
			'entrada' => date('Y-m-d H:i:s'),
			'empleado_id' => $id_empleado
		);

		// Crea la entrada
		$id_entrada = $db->insert('per_asistencias', $entrada);
	}
}

// Obtiene a los empleados que estan fuera de la institucion
$exteriores = $db->query("SELECT *
FROM (SELECT e.id_empleado, e.nombres, e.paterno, e.materno, e.foto, IFNULL(a.entrada, '0000-00-00 00:00:00') AS entrada, IFNULL(a.salida, '0000-00-00 00:00:00') AS salida
	  FROM per_empleados e
	  LEFT JOIN (SELECT *
                 FROM (SELECT *
					   FROM per_asistencias
					   WHERE fecha_asistencia = '$hoy'
					   ORDER BY entrada DESC, id_asistencia DESC) a
				 GROUP BY a.empleado_id) a ON e.id_empleado = a.empleado_id
      WHERE e.activo = 's' AND e.tarjeta != '') e
WHERE e.entrada = '0000-00-00 00:00:00' OR e.salida != '0000-00-00 00:00:00'
ORDER BY e.salida DESC, e.nombres ASC, e.paterno ASC LIMIT 7")->fetch();

// Obtiene a los empleados que estan dentro de la institucion
$interiores = $db->query("SELECT *
FROM (SELECT e.id_empleado, e.nombres, e.paterno, e.materno, e.foto, IFNULL(a.entrada, '0000-00-00 00:00:00') AS entrada, IFNULL(a.salida, '0000-00-00 00:00:00') AS salida
	  FROM per_empleados e
	  LEFT JOIN (SELECT *
                 FROM (SELECT *
					   FROM per_asistencias
					   WHERE fecha_asistencia = '$hoy'
					   ORDER BY entrada DESC, id_asistencia DESC) a
				 GROUP BY a.empleado_id) a ON e.id_empleado = a.empleado_id
      WHERE e.activo = 's' AND e.tarjeta != '') e
WHERE e.salida = '0000-00-00 00:00:00' AND e.entrada != '0000-00-00 00:00:00'
ORDER BY e.entrada DESC LIMIT 7")->fetch();

// Define los nombres de los dias
$dias = array('domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado');

?>
<?php require_once show_template('header-design'); ?>
<link rel="stylesheet" href="<?= css; ?>/bootstrap.min.css">
<link rel="stylesheet" href="<?= themes . '/bootstrap'; ?>/style.min.css">
<link rel="stylesheet" href="<?= css; ?>/bootstrap-grid.min.css">
<link rel="stylesheet" href="<?= css; ?>/animate.min.css">
<style>
body {
	margin: 15px auto;
	overflow: hidden;
}
.page-container {
	/*background: url("<?= imgs . '/background.jpg'; ?>") center center / cover no-repeat;*/
	background-position: center center;
	height: 100%;
	overflow-y: auto;
	overflow-x: hidden;
	position: absolute;
	top: 0;
	width: 100%;
	z-index: 0;
}
.page-header {
	padding: 0 15px;
	position: fixed;
	top: 0;
	width: 100%;
	z-index: 1000;
}
.page-footer {
	padding: 0 15px;
	position: fixed;
	bottom: 0;
	width: 100%;
	z-index: 1000;
}
.page-body {
	display: table;
	height: 100%;
	width: 100%;
}
.page-content {
	display: table-cell;
	vertical-align: middle;
}
.img-bordered {
	border: 5px solid #fff;
}
.text-muted {
	color: #000;
}
.text-success {
	color: #61bc6d;
}
.text-warning {
	color: #faa026;
}
.text-danger {
	color: #e24939;
}
.text-xl {
	font-size: 10em;
}
.text-lg {
	font-size: 7em;
}
.text-md {
	font-size: 5em;
}
.text-sm {
	font-size: 3em;
}
.text-xs {
	font-size: 2em;
}
.text-center {
	text-align: center;
}
.margin-none {
	margin: 0;
}
.margin-top {
	margin-top: 15px;
}
.margin-bottom {
	margin-bottom: 15px;
}
.padding-top {
	padding-top: 15px;
}
.padding-bottom {
	padding-bottom: 15px;
}
@media (max-width: 767px) {
	.page-header {
		bottom: auto;
		padding: auto 15px;
		position: relative;
		top: auto;
		width: auto;
		z-index: 1000;
	}
	.page-footer {
		bottom: auto;
		padding: auto 15px;
		position: relative;
		top: auto;
		width: auto;
		z-index: 1000;
	}
}
</style>
<div class="page-container">
	<div class="page-header">
		<div class="row align-items-center">
			<div class="col-xl-8 offset-xl-2 col-lg-6 offset-lg-3 col-md-8 offset-md-2">
				<h1 class="text-muted text-md text-center margin-none padding-top animated slideInDown">
					<strong class="text-capitalize" data-datetime="day"><?= $dias[date('N')]; ?></strong>
					<strong data-datetime="date"><?= date_decode(date('Y-m-d'), $_format); ?></strong>
				</h1>
			</div>
		</div>
	</div>
	<div class="page-body">
		<div class="page-content">
			<div class="container-fluid">
				<div class="row align-items-center">
					<div class="col-xl-2 col-lg-3 col-md-2 animated slideInLeft">
						<h3 class="text-center margin-none"><strong>Fuera</strong></h3>
						<?php foreach ($exteriores as $nro => $exterior) : ?>
						<div class="text-center">
							<center><img src="<?= ($exterior['foto'] == '') ? imgs . '/avatar.jpg' : files . '/empleados/small__' . $exterior['foto']; ?>" class="img-responsive img-circle" width="72" height="72"></center>
							<strong><?= escape($exterior['nombres'] . ' ' . $exterior['paterno'] . ' ' . $exterior['materno']); ?></strong>
							<br>
							<span><?= escape($exterior['salida']); ?></span>
						</div>
						<?php endforeach ?>
					</div>
					<div class="col-xl-8 col-lg-6 col-md-8">
						<div class="text-center animated zoomIn">
							<?php if ($empleado) : ?>
							<h1 class="text-muted text-sm margin-none"><strong><?= escape($empleado['nombres'] . ' ' . $empleado['paterno'] . ' ' . $empleado['materno']); ?></strong></h1>
							<!--<h3 class="text-muted margin-none">Registrado el <?= date_decode($empleado['fecha_registro'], $_format); ?></h3>-->
							<div class="margin-top">
								<center>
									<img src="<?= ($empleado['foto'] == '') ? imgs . '/avatar.jpg' : files . '/empleados/' . $empleado['foto']; ?>" class="img-responsive img-circle" width="512" height="512">
								</center>
							</div>
							<!--<h1 class="text-success text-sm margin-bottom animated infinite flash"><strong>Entrada</strong></h1>-->
							<?php else : ?>
							<!-- <div class="margin-top">
								<center>
									<img src="<?= imgs . '/logo-color.png'; ?>" class="img-responsive" width="256">
								</center>
							</div> -->
							<center><h1 class="text-muted text-sm margin-bottom animated infinite flash"><strong>Digite su PIN!</strong></h1></center>

							<div class="row"><br></div>


							<div class="row">
								<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
									<form method="post" action="" id="obtener" autocomplete="off">
										<input type="hidden" name="<?= $csrf; ?>">
						                
						                <div class="col-sm-3">
											<div class="form-group">
												<input type="text" value="" name="uno" id="uno" autofocus = "autofocus" class="pin form-control text-center" data-validation="length alphanumeric" data-validation-length="1" maxlength="1">
											</div>
						                </div>
						                
						                <div class="col-sm-3">
											<div class="form-group">
												<input type="text" value="" name="dos" id="dos" autofocus = "autofocus" class="pin form-control text-center" data-validation="length alphanumeric" data-validation-length="1" maxlength="1">
											</div>
						                </div>
						                
						                <div class="col-sm-3">
											<div class="form-group">
												<input type="text" value="" name="tres" id="tres" autofocus = "autofocus" class="pin form-control text-center" data-validation="length alphanumeric" data-validation-length="1" maxlength="1">
											</div>
						                </div>
						                
						                <div class="col-sm-3">
											<div class="form-group">
												<input type="text" value="" name="cuatro" id="cuatro" autofocus = "autofocus" class="pin form-control text-center" data-validation="length alphanumeric" data-validation-length="1" maxlength="1">
											</div>
						                </div>

						                <button type="submit" class="hidden" id="digitar"></button>


						               <!--  <div class="col-sm-12">
											<div class="form-group">
												<label for="monto" class="control-label">PIN:</label>
												<input type="password" value="" name="monto" id="monto" class="form-control" data-validation="length alphanumeric" data-validation-length="4">
											</div>
										</div>

										<div class="form-group">
											<button type="submit" class="btn btn-danger">
												<span class="glyphicon glyphicon-floppy-disk"></span>
												<span>Guardar</span>
											</button>
											<button type="reset" class="btn btn-default">
												<span class="glyphicon glyphicon-refresh"></span>
												<span>Restablecer</span>
											</button>
										</div> -->
									</form>
								</div>
							</div>

							<?php endif ?>
						</div>
					</div>
					<div class="col-xl-2 col-lg-3 col-md-2 animated slideInRight">
						<h3 class="text-center margin-none"><strong>Dentro</strong></h3>
						<?php foreach ($interiores as $nro => $interior) : ?>
						<div class="text-center">
							<center><img src="<?= ($interior['foto'] == '') ? imgs . '/avatar.jpg' : files . '/empleados/small__' . $interior['foto']; ?>" class="img-responsive img-circle" width="72" height="72"></center>
							<strong><?= escape($interior['nombres'] . ' ' . $interior['paterno'] . ' ' . $interior['materno']); ?></strong>
							<br>
							<span><?= escape($interior['entrada']); ?></span>
						</div>
						<?php endforeach ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="page-footer">
		<div class="row align-items-center">
			<div class="col-xl-8 offset-xl-2 col-lg-6 offset-lg-3 col-md-8 offset-md-2">
				<h1 class="text-muted text-lg text-center margin-none padding-bottom animated fadeIn">
					<strong data-datetime="time"><?= date('H:i:s'); ?></strong>
				</h1>
			</div>
		</div>
	</div>
</div>
<script src="<?= js; ?>/moment.min.js"></script>
<script src="<?= js; ?>/buzz.min.js"></script>
<script src="<?= js; ?>/socket.io.min.js"></script>
<script src="<?= js; ?>/screenfull.min.js"></script>
<script>
//var websocket = io('<?= nodejs_server; ?>');
var tiempo = 5, names = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
$(function () {
		$(".pin").keyup(function(){
	    var uno = $(this).val().length;
	    //console.log(uno);
	    if(uno==1){
           $("#dos").focus();
           var dos=$("#dos").val().length;
           if(dos==1){
               $("#tres").focus();
               var tres=$("#tres").val().length;
	           if(tres==1){
	              $("#cuatro").focus();
	              var cuatro=$("#cuatro").val().length;
		          if(cuatro==1){
		          	 $("#digitar").click();

		              //$("#cuatro").blur();
		              //var dos=$("#cuatro").val().length;
			      }
		       }
	       }
	    }
    });
});
$(function () {
	var wellcome = new buzz.sound('<?= media; ?>/wellcome.mp3');
	var success = new buzz.sound('<?= media; ?>/success.mp3');
	var error = new buzz.sound('<?= media; ?>/error.mp3');
	var date, time, day, hours, minutes, seconds, thours, tminutes, tseconds;

	$.ajax({
		type: 'POST',
		dataType: 'json',
		url: '?/sitio/reloj'
	}).done(function (datetime) {
		date = datetime.date;
		day = parseInt(datetime.day);
		hours = parseInt(datetime.hours);
		minutes = parseInt(datetime.minutes);
		seconds = parseInt(datetime.seconds);
	}).fail(function () {
		date = moment().format('<?= upper(get_date_textual($_format)); ?>');
		day = moment().format('e');
		hours = parseInt(moment().format('H'));
		minutes = parseInt(moment().format('mm'));
		seconds = parseInt(moment().format('ss'));
	});

	setInterval(function () {
		if (seconds < 59) {
			seconds = seconds + 1;
		} else {
			seconds = 0;
			if (minutes < 59) {
				minutes = minutes + 1;
			} else {
				minutes = 0;
				hours = (hours < 23) ? hours + 1 : 0;
			}
		}
		tseconds = (seconds < 10) ? '0' + seconds : seconds;
		tminutes = (minutes < 10) ? '0' + minutes : minutes;
		thours = (hours < 10) ? '0' + hours : hours;
		time = thours + ':' + tminutes + ':' + tseconds
		$('[data-datetime="day"]').text(names[day]);
		$('[data-datetime="date"]').text(date);
		$('[data-datetime="time"]').text(time);
	}, 1000);

	<?php if ($empleado) : ?>
	setInterval(function () {
		window.location = '?/principal/control-empleados';
	}, 5000);
	<?php endif ?>

	//success.stop().play();

	/*<?php if ($empleado) : ?>
		<?php if ($estado == 'success') : ?>
		success.stop().play();
		<?php elseif ($estado == 'warning') : ?>
		success.stop().play();
		<?php else : ?>
		error.stop().play();
		<?php endif ?>
	<?php endif ?>*/

	<?php if (true) : ?>
	/*setInterval(function () {
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: '?/principal/control-empleados-obtener'
		}).done(function (id) {
			window.location = '?/sitio/control-empleados/' + id;
			//websocket.emit('ingresar empleado', id);
		}).fail(function () {
			window.location = '?/sitio/control-empleados';
			//websocket.emit('ingresar empleado', 0);
		});
	}, 500);*/
	<?php endif ?>

	/*$(document).on('click', function (e) {
		e.preventDefault();
		screenfull.request();
	}).on('dblclick', function (e) {
		e.preventDefault();
		screenfull.exit();
	});*/

	$('#obtener').on('submit', function (e) {
		e.preventDefault();
		//var pin = '12ma';
		var uno = $('#uno').val();
		var dos = $('#dos').val();
		var tres = $('#tres').val();
		var cuatro = $('#cuatro').val();
		var pin=uno+dos+tres+cuatro;
		//var form = $('#obtener').serializeArray();
		//console.log(pin);
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: '?/principal/control-empleados-obtener',
		    //data: form,
			data: {
				pin: pin
			}
		}).done(function (id) {
			console.log(id);
			window.location = '?/principal/control-empleados/' + id;
		}).fail(function () {
			window.location = '?/principal/control-empleados';
		});
	});
	// $('#obtener').on('submit', function (e) {
	// 	e.preventDefault();
        
	// 	var pin = 'sai12npfaq2v9u12';
	// 	//var form = $('#obtener').serializeArray();
	// 	//console.log(pin);
	// 	$.ajax({
	// 		type: 'post',
	// 		dataType: 'json',
	// 		url: '?/principal/control-empleados-obtener',
	// 	    //data: form,
	// 		data: {
	// 			pin: pin
	// 		}
	// 	}).done(function (id) {
	// 		console.log(id);
	// 		window.location = '?/principal/control-empleados/' + id;
	// 	}).fail(function () {
	// 		window.location = '?/principal/control-empleados';
	// 	});
	// });
});
</script>
<?php require_once show_template('footer-design'); ?>