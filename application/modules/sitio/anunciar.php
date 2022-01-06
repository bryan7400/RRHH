<?php

// Obtiene los carteles
$carteles = $db->select('c.multimedia, c.numero as numero_cartel, c.duracion, c.transicion, c.logotipo as logotipo_cartel, c.sitio as sitio_cartel, c.tiempo as tiempo_cartel, c.activo as activo_cartel, e.empresa, e.sitio as sitio_empresa, e.logotipo as logotipo_empresa, e.aleatorio, e.activo as activo_empresa, e.numero as numero_empresa')->from('anu_carteles c')->join('anu_empresas e', 'c.empresa_id = e.id_empresa', 'left')->where('e.activo', 's')->where('c.activo', 's')->order_by('e.numero', 'asc')->order_by('c.numero', 'asc')->fetch();

?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="mobile-web-app-capable" content="yes">
		<title>Anuncios</title>
		<link rel="stylesheet" href="<?= css; ?>/animate.min.css">
		<link rel="icon" type="image/png" href="<?= project; ?>/favicon.png">
		<style>
			body {
				background-color: #000;
				margin: 0;
				font-size: 10px;
			}
			* {
				box-sizing: border-box !important;
				outline: none !important;
			}
			.ad-container {
				left: 50%;
				height: 0;
				position: absolute;
				transform: translate(-50%, -50%);
				top: 50%;
				width: 0;
				overflow: hidden;
			}
			.ad-slider {
				background-position: center center;
				background-repeat: no-repeat;
				height: 100%;
				left: 0;
				position: absolute;
				top: 0;
				width: 100%;
				z-index: 1000;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-ms-background-size: cover;
				-o-background-size: cover;
				background-size: cover;
			}
			.ad-slider > * {
				display: none;
				margin: 0;
				padding: 0;
				position: absolute;
			}
			<?php foreach ($carteles as $nro => $cartel) : ?>
			.ad-slider-<?= $nro; ?> {
				background-color: #fff;
			}
			.ad-slider-<?= $nro; ?> > .ad-advertising {
				width: 100%;
				left: 0%;
				top: 0%;
				height: 100%;
			}
			.ad-slider-<?= $nro; ?> > .ad-time {
				background-color: #fff;
				width: 50px;
				left: 10%;
				bottom: 5%;
				height: 50px;
				border-radius: 50%;
				text-align: center;
				font-size: 2em;
				font-family: 'arial';
				line-height: 50px;
				font-weight: bold;
				color: #333;
			}
			.ad-slider-<?= $nro; ?> > .ad-qr {
				background-color: #fff;
				padding: 5px;
				width: 20%;
				right: 10%;
				bottom: 5%;
				height: 11.4%;
			}
			.ad-slider-<?= $nro; ?> > .ad-qr img {
				width: 100%;
			}
			.ad-slider-<?= $nro; ?> > .ad-image {
				width: 50%;
				left: 25%;
				top: 5%;
			}
			<?php endforeach ?>
		</style>
	</head>
	<body>
		<div class="ad-container">
			<?php foreach ($carteles as $nro => $cartel) : ?>
				<?php $extension = explode('.', $cartel['multimedia']); ?>
				<?php $extension = end($extension); ?>
				<?php if ($extension == 'jpg') : ?>
				<div class="ad-slider ad-slider-<?= $nro; ?>" data-duration="<?= $cartel['duracion']; ?>" data-transition="<?= $cartel['transicion']; ?>" data-format="jpg">
					<img src="<?= files . '/carteles/' . $cartel['multimedia']; ?>" class="ad-advertising">
					<?php if ($cartel['sitio_cartel'] == 's') : ?>
					<div class="ad-qr" data-sitio-empresa="<?= $cartel['sitio_empresa']; ?>"></div>
					<?php endif ?>
					<?php if ($cartel['logotipo_cartel'] == 's') : ?>
					<img src="<?= files . '/empresas/' . $cartel['logotipo_empresa']; ?>" class="ad-image">
					<?php endif ?>
					<?php if ($cartel['tiempo_cartel'] == 's') : ?>
					<div class="ad-time"><?= escape($cartel['duracion']); ?></div>
					<?php endif ?>
				</div>
				<?php elseif ($extension == 'mp4') : ?>
				<div class="ad-slider ad-slider-<?= $nro; ?>" data-duration="<?= $cartel['duracion']; ?>" data-transition="<?= $cartel['transicion']; ?>" data-format="mp4">
					<video class="ad-advertising" loop>
						<source src="<?= files . '/carteles/' . $cartel['multimedia']; ?>" type="video/mp4">
					</video>
					<?php if ($cartel['sitio_cartel'] == 's') : ?>
					<div class="ad-qr" data-sitio-empresa="<?= $cartel['sitio_empresa']; ?>"></div>
					<?php endif ?>
					<?php if ($cartel['logotipo_cartel'] == 's') : ?>
					<img src="<?= files . '/empresas/' . $cartel['logotipo_empresa']; ?>" class="ad-image">
					<?php endif ?>
					<?php if ($cartel['tiempo_cartel'] == 's') : ?>
					<div class="ad-time"><?= escape($cartel['duracion']); ?></div>
					<?php endif ?>
				</div>
				<?php else : ?>
				<div class="ad-slider ad-slider-<?= $nro; ?>" data-duration="<?= $cartel['duracion']; ?>" data-transition="<?= $cartel['transicion']; ?>" data-format="jpg">
					<img src="<?= imgs . '/multimedia.jpg'; ?>" class="ad-advertising">
					<?php if ($cartel['sitio_cartel'] == 's') : ?>
					<div class="ad-qr" data-sitio-empresa="<?= $cartel['sitio_empresa']; ?>"></div>
					<?php endif ?>
					<?php if ($cartel['logotipo_cartel'] == 's') : ?>
					<img src="<?= files . '/empresas/' . $cartel['logotipo_empresa']; ?>" class="ad-image">
					<?php endif ?>
					<?php if ($cartel['tiempo_cartel'] == 's') : ?>
					<div class="ad-time"><?= escape($cartel['duracion']); ?></div>
					<?php endif ?>
				</div>
				<?php endif ?>
			<?php endforeach ?>
		</div>
		<script src="<?= js; ?>/jquery.min.js"></script>
		<script src="<?= js; ?>/animo.min.js"></script>
		<script src="<?= js; ?>/buzz.min.js"></script>
		<script src="<?= js; ?>/qrcode.min.js"></script>
		<script src="<?= js; ?>/socket.io.min.js"></script>
		<script>
		var websocket = io('<?= nodejs_server; ?>');

		$(function () {
			var soundx = new buzz.sound('<?= media; ?>/soundx.mp3');
			var soundy = new buzz.sound('<?= media; ?>/soundy.mp3');
			var height, width, ratio = 16 / 9;

			buzz.defaults.duration = 1000;

			$(window).on('resize mousemove', function () {
				width = $(window).outerWidth();
				height = $(window).outerHeight();
				if (ratio < height / width) {
					height = width * ratio;
				} else {
					width = height / ratio;
				}
				$('.ad-container').css({
					'height': height,
					'width': width
				});
			}).trigger('resize');

			$('.ad-qr').each(function () {
				var $this = $(this);
				var sitio = $this.attr('data-sitio-empresa');
				new QRCode($this.get(0), sitio);
			});


			var start = 1, end = $('.ad-slider').size(), time = 1;

			

			var slider = function () {
				var x, y;
				x = start - 1;
				if (start < end) {
					start = start + 1;
				} else {
					start = 1;
				}
				y = (x - 1 < 0) ? end - 1 : x - 1;
				var $adslider = $('.ad-slider'), $adsliderx = $('.ad-slider:eq(' + x + ')'), $adslidery = $('.ad-slider:eq(' + y + ')'), duration = $adsliderx.attr('data-duration'), transition = $adsliderx.attr('data-transition'), format = $adsliderx.attr('data-format');
				
				/*var contador = duration;
				var segundero = function () {
					contador = contador - 1;
					$adsliderx.find('.ad-time').text(contador);
				};


				setInterval(function () {
					segundero();
				}, 1000);

				clearInterval(segundero);*/
				
				setTimeout(function() {
					slider();
					if (format == 'mp4') {
						$adsliderx.find('.ad-advertising').get(0).pause();
						$adsliderx.find('.ad-advertising').get(0).currentTime = 0;
						$adsliderx.find('.ad-advertising').get(0).load();
					}
				}, duration * 1000);
				$adslider.css('z-index', 1000);
				$adslidery.css('z-index', 1001);
				$adsliderx.css('z-index', 1002);
				$adslidery.children().hide();
				$adsliderx.find('.ad-advertising').css('display', 'inline-block');
				if (format == 'mp4') {
					$adsliderx.find('.ad-advertising').get(0).play();
				}
				$adsliderx.find('.ad-advertising').animo({
					animation: transition,
					duration: 1
				}, function () {
					if ($adsliderx.find('.ad-time').size() == 1) {
						$adsliderx.find('.ad-time').css('display', 'inline-block');
						$adsliderx.find('.ad-time').animo({
							animation: transition,
							duration: 1
						});
					}
					if ($adsliderx.find('.ad-qr').size() == 1) {
						$adsliderx.find('.ad-qr').css('display', 'inline-block');
						$adsliderx.find('.ad-qr').animo({
							animation: transition,
							duration: 1
						});
					}
					if ($adsliderx.find('.ad-image').size() == 1) {
						$adsliderx.find('.ad-image').css('display', 'inline-block');
						$adsliderx.find('.ad-image').animo({
							animation: transition,
							duration: 1
						});
					}
				});
			}
			slider();
		});

		websocket.on('actualizar sitio', function () {
			window.location.reload();
		});
		</script>
	</body>
</html>