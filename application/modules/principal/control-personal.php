<?php

// Obtiene los parametros
$id_empleado = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene el empleado
$empleado = $db->from('per_empleados e')->where('e.id_empleado', $id_empleado)->fetch_first();
var_dump($empleado);

?>
<?php require_once show_template('header-site'); ?>
<link rel="stylesheet" href="<?= css; ?>/animate.min.css">
<style>
body{
	font-family: 'Roboto', Helvetica, Arial, sans-serif;
	margin: 0;
	overflow: hidden;
}
.page-container {
	/*background: url("<?= imgs . '/background.jpg'; ?>") center center / cover no-repeat;*/
	background-position: center center;
	height: 100%;
	overflow-y: auto;
	overflow-x: hidden;
	position: fixed;
	top: 0px;
	width: 100%;
	z-index: 0;
}
.page-container-header {
	padding: 60px;
	position: absolute;
	top: 0;
	width: 100%;
}
.page-container-footer {
	padding: 15px;
	position: absolute;
	bottom: 0;
	width: 100%;
}
.page-panel {
	display: table;
	height: 100%;
	width: 100%;
}
.page-body {
	display: table-cell;
	vertical-align: middle;
}
.img-bordered {
	border: 5px solid #fff;
}
.icon-container {
	position: relative;
	display: inline-block;
}
.icon {
	position: absolute;
	display: inline-block;
	top: auto;
	right: 0;
	bottom: 0;
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
.text-lg {
	font-size: 10em;
}
.text-md {
	font-size: 6.5em;
}
.text-sm {
	font-size: 4em;
}
</style>
<div class="page-container">
	<div class="page-panel">
		<div class="page-body">
			<div class="container-fluid animated zoomIn">
				<div class="row">
					<div class="col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8">
						<div class="text-center">
							<div class="margin-top visible-xs-block">
								<h1 class="text-muted text-center">
									<strong data-datetime="date"><?= date_decode(date('Y-m-d'), $_format); ?></strong>
									<strong data-datetime="time"><?= date('H:i:s'); ?></strong>
								</h1>
								<center>
									<img src="<?= imgs . '/logo-color.png'; ?>" class="img-responsive cursor-pointer" width="300">
								</center>
							</div>
							<div class="well">
								<h1>AQUÍ ME QUEDO</h1>
							</div>




	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<form method="post" action="?/gasto/guardar" autocomplete="off">
				<input type="hidden" name="<?= $csrf; ?>">
                
                <div class="col-sm-3">
					<div class="form-group">
						<input type="text" value="" name="uno" id="uno" autofocus = "autofocus" class="pin form-control" data-validation="length alphanumeric" data-validation-length="1">
					</div>
                </div>
                
                <div class="col-sm-3">
					<div class="form-group">
						<input type="text" value="" name="dos" id="dos" autofocus = "autofocus" class="pin form-control" data-validation="length alphanumeric" data-validation-length="1">
					</div>
                </div>
                
                <div class="col-sm-3">
					<div class="form-group">
						<input type="text" value="" name="tres" id="tres" autofocus = "autofocus" class="pin form-control" data-validation="length alphanumeric" data-validation-length="1">
					</div>
                </div>
                
                <div class="col-sm-3">
					<div class="form-group">
						<input type="text" value="" name="cuatro" id="cuatro" autofocus = "autofocus" class="pin form-control" data-validation="length alphanumeric" data-validation-length="1">
					</div>
                </div>


                <div class="col-sm-12">
					<div class="form-group">
						<label for="monto" class="control-label">PIN:</label>
						<input type="password" value="" name="monto" id="monto" class="form-control" data-validation="length alphanumeric" data-validation-length="4">
					</div>
				</div>

<!-- 				<div class="form-group">
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











						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="page-container-header hidden-xs">
	<div class="row">
		<div class="col-sm-3 col-md-2">
			<img src="<?= imgs . '/logo-color.png'; ?>" class="img-responsive cursor-pointer">
		</div>
		<div class="col-sm-9 col-md-10">
			<h1 class="text-muted text-right">
				<strong data-datetime="date"><?= date_decode(date('Y-m-d'), $_format); ?></strong>
				<strong data-datetime="time"><?= date('H:i:s'); ?></strong>
			</h1>
		</div>
	</div>
</div>
<script src="<?= js; ?>/moment.min.js"></script>
<script src="<?= js; ?>/buzz.min.js"></script>
<script src="<?= js; ?>/socket.io.min.js"></script>
<script>
//var websocket = io('<?= nodejs_server; ?>');
var tiempo = 5;
$(function () {
		$(".pin").keyup(function(){
	    var uno = $(this).val().length;
	    console.log(uno);
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
		              $("#cuatro").blur();
		              //var dos=$("#cuatro").val().length;
			      }
		       }
	       }
	    }



	    // $.ajax({
	    //   url:"?/prueba/buscar", 
	    //   method:"POST",  
	    //   data:{id:id},  
	    //   dataType:"json",
	    //   success: function(resultado){	      	
	    //   	console.log(resultado);
	    //   }
	    // })
    });
});
$(function () {
	/*websocket.on('ingresar empleado', function (id) {
		if (id == 0) {
			window.location = '?/sitio/control-empleados';
		} else {
			window.location = '?/sitio/control-empleados/' + id;
		}
	});*/

	$('.cursor-pointer').on('click', function (e) {
		e.preventDefault();
		window.location = '?/sitio/control-empleados';
	});

	var wellcome = new buzz.sound('<?= media; ?>/wellcome.mp3');
	var success = new buzz.sound('<?= media; ?>/success.mp3');
	var error = new buzz.sound('<?= media; ?>/error.mp3');
	var date, time, hours, minutes, seconds, thours, tminutes, tseconds;

	$.ajax({
		type: 'POST',
		dataType: 'json',
		url: '?/sitio/reloj'
	}).done(function (datetime) {
		date = datetime.date;
		hours = parseInt(datetime.hours);
		minutes = parseInt(datetime.minutes);
		seconds = parseInt(datetime.seconds);
	}).fail(function () {
		date = moment().format('<?= upper(get_date_textual($_format)); ?>');
		hours = parseInt(moment().format('H'));
		minutes = parseInt(moment().format('mm'));
		seconds = parseInt(moment().format('ss'));
		console.log(date, hours, minutes, seconds);
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
		$('[data-datetime="date"]').text(date);
		$('[data-datetime="time"]').text(time);
	}, 1000);

	<?php if ($empleado) : ?>
	/*setInterval(function () {
		if (tiempo < 1) {
			window.location = '?/sitio/control-empleados';
		} else {
			tiempo = tiempo - 1;
		}
	}, 1000);*/
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
			url: '?/sitio/control-empleados-escuchar',
			data: {
				archivo: 'control-empleados-reading'
			}
		}).done(function (data) {
			if (data) {
				$.ajax({
					type: 'post',
					dataType: 'json',
					url: '?/sitio/control-empleados-obtener'
				}).done(function (id) {
					websocket.emit('ingresar empleado', id);
				}).fail(function () {
					websocket.emit('ingresar empleado', 0);
				});
			}
		});
	}, 500);*/
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-site'); ?>