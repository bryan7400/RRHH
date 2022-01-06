				<div class="panel-footer text-center"><?= credits; ?></div>
			</div>
		</div>

		<script>

		$(function () { 

			<?php if ($_clock == 's') : ?>
			var date, time, hours, minutes, seconds, tseconds, tminutes, thours;

			$.ajax({
				type: 'post',
				dataType: 'json',
				url: '?/principal/reloj'
			}).done(function (datetime) {

				date = datetime.date;
				hours = parseInt(datetime.hours);
				minutes = parseInt(datetime.minutes);
				seconds = parseInt(datetime.seconds);

			}).fail(function () {

				date = moment().format('YYYY-MM-DD');
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
						if (hours < 23) {
							hours = hours + 1;
						} else {
							hours = 0;
						}
					}
				}

				tseconds = (seconds < 10) ? '0' + seconds : seconds;
				tminutes = (minutes < 10) ? '0' + minutes : minutes;
				thours = (hours < 10) ? '0' + hours : hours;
				time = thours + ':' + tminutes + ':' + tseconds

				$('[data-datetime="date"]').text(date);
				$('[data-datetime="time"]').text(time);

			}, 1000);
			<?php endif ?>

			$('[data-toggle="tooltip"]').tooltip({
				container: 'body',
				trigger: 'hover'
			});

			document.title = ($('[data-header]:first').size() > 0) ? (($.trim($('[data-header]:first').text()) == '') ? document.title : $.trim($('[data-header]:first').text())) : document.title;
			
			<?php if (environment == 'production') : ?>
			//$(document).on('contextmenu selectstart dragstart', function (e) { e.preventDefault(); });

			$('body').css({ cursor: 'default' });

			$('#loader').hide();

			$('#navbar').animo({ animation: 'fadeInDown', duration: 0.5 });

			$('#panel').animo({ animation: 'fadeInRight', duration: 0.5 });
			<?php endif ?>
			
		});
		</script>		
	</body>
</html>