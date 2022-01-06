<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

/*
|--------------------------------------------------------------------------
| Redireciona a una pagina en especifica
|--------------------------------------------------------------------------
*/

function redirect($url) {
	header('Location: ' . $url);
	exit;
}

/*
|--------------------------------------------------------------------------
| Retorna la url de la pagina anterior
|--------------------------------------------------------------------------
*/

function back() {
	if (isset($_SERVER['HTTP_REFERER'])) {
		$back = $_SERVER['HTTP_REFERER'];
		$back = explode('?', $back);
		$back = '?' . $back[1];
		return $back;
	} else {
		return index_public;
	}
}

/*
|------------------------------------------------------------
| Crea una notificacion de error
|------------------------------------------------------------
*/

function set_notification($type = 'info', $title = 'title', $content = 'content') {
	$_SESSION[temporary] = array(
		'type' => $type,
		'title' => $title,
		'content' => $content
	);
}

/*
|------------------------------------------------------------
| Elimina y obtiene una notificacion de error
|------------------------------------------------------------
*/

function get_notification() {
	if (isset($_SESSION[temporary])) {
		$notification = $_SESSION[temporary];
		unset($_SESSION[temporary]);
	} else {
		$notification = array();
	}
	return $notification;
}

/*
|------------------------------------------------------------
| Crea una variable como parametro invisible
|------------------------------------------------------------
*/

function set_variable($name, $value) {
	$name = $name . '-' . name_project;
	$_SESSION[$name] = $value;
}

/*
|------------------------------------------------------------
| Elimina y obtiene una variable
|------------------------------------------------------------
*/

function get_variable($name) {
	$name = $name . '-' . name_project;
	if (isset($_SESSION[$name])) {
		$value = $_SESSION[$name];
	} else {
		$value = null;
	}
	return $value;
}

/*
|------------------------------------------------------------
| Elimina una variable como parametro invisible
|------------------------------------------------------------
*/

function unset_variable($name) {
	$name = $name . '-' . name_project;
	unset($_SESSION[$name]);
	return null;
}

/*
|------------------------------------------------------------
| Crea una cadena encriptada contra ataques csrf
|------------------------------------------------------------
*/

function set_csrf() {
	return $_SESSION[csrf] = encrypt(time());
}

/*
|------------------------------------------------------------
| Elimina y obtiene una cadena csrf
|------------------------------------------------------------
*/

function get_csrf() {
	if (isset($_SESSION[csrf])) {
		$csrf = $_SESSION[csrf];
		unset($_SESSION[csrf]);
	} else {
		$csrf = encrypt(mt_rand(1000, 2000));
	}
	return $csrf;
}

/*
|--------------------------------------------------------------------------
| Verifica si una peticion llego por el metodo post
|--------------------------------------------------------------------------
*/

function is_post() {
	return $_SERVER['REQUEST_METHOD'] == 'POST';
}

/*
|--------------------------------------------------------------------------
| Verifica si una peticion es por medio de ajax
|--------------------------------------------------------------------------
*/

function is_ajax() {
	return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

/*
|--------------------------------------------------------------------------
| Muestra la vista 404
|--------------------------------------------------------------------------
*/

function show_template($template) {
	return templates . '/' . $template . '.php';
}

/*
|--------------------------------------------------------------------------
| Muestra la vista 400 bad request
|--------------------------------------------------------------------------
*/

function bad_request() {
	return show_template('400');
}

/*
|--------------------------------------------------------------------------
| Muestra la vista 401 unauthorized
|--------------------------------------------------------------------------
*/

function unauthorized() {
	return show_template('401');
}

/*
|--------------------------------------------------------------------------
| Muestra la vista 404 not found
|--------------------------------------------------------------------------
*/

function not_found() {
	return show_template('404');
}

/*
|------------------------------------------------------------
| Retorna un texto encriptado bajo el algoritmo md5 y sha1
|------------------------------------------------------------
*/

function encrypt($text) {
	return sha1(secret . md5($text));
}

/*
|--------------------------------------------------------------------------
| Retorna el texto con los caracteres especiales escapados
|--------------------------------------------------------------------------
*/

function escape($text) {
	$text = htmlspecialchars($text, ENT_QUOTES);
	return $text;
}

/*
|------------------------------------------------------------
| Retorna un texto con los espacios limpios
|------------------------------------------------------------
*/

function clear($text) {
	$text = preg_replace('/\s+/', ' ', $text);
	$text = trim($text);
	$text = addslashes($text);
	return $text;
}

/*
|------------------------------------------------------------
| Retorna un texto convertido en mayusculas
|------------------------------------------------------------
*/

function upper($text) {
	$text = mb_strtoupper($text, 'UTF-8');
	return $text;
}

/*
|------------------------------------------------------------
| Retorna un texto convertido en minusculas
|------------------------------------------------------------
*/

function lower($text) {
	$text = mb_strtolower($text, 'UTF-8');
	return $text;
}

/*
|------------------------------------------------------------
| Retorna un texto convertido en minusculas excepto la primera
|------------------------------------------------------------
*/

function capitalize($text) {
	$text = upper(mb_substr($text, 0, 1, 'UTF-8')) . lower(mb_substr($text, 1, mb_strlen($text), 'UTF-8'));
	return $text;
}

/*
|--------------------------------------------------------------------------
| Retorna un string con caracteres aleatorios
|--------------------------------------------------------------------------
*/

function random_string($length = 6) {
	$text = '';
	$characters = '0123456789abcdefghijkmnopqrstuvwxyz';
	$nro = 0;
	while ($nro < $length) {
		$caracter = substr($characters, mt_rand(0, strlen($characters) - 1), 1);
		$text .= $caracter;
		$nro++;
	}
	return $text;
}

/*
|--------------------------------------------------------------------------
| Convierte una fecha al formato yyyy-mm-dd
|--------------------------------------------------------------------------
*/

function date_encode($date) {
	if (is_numeric(substr($date, 2, 1))) {
		$day = substr($date, 8, 2);
		$month = substr($date, 5, 2);
		$year = substr($date, 0, 4);
	} else {
		$day = substr($date, 0, 2);
		$month = substr($date, 3, 2);
		$year = substr($date, 6, 4);
	}
	return $year . '-' . $month . '-' . $day;
}

/*
|--------------------------------------------------------------------------
| Convierte una fecha al formato yyyy/mm/dd
|--------------------------------------------------------------------------
*/

function date_decode($date, $format = 'Y-m-d') {
	$format = ($format == '') ? 'Y-m-d' : $format;
	$date = explode('-', $date);
	$format = str_replace('Y', $date[0], $format);
	$format = str_replace('m', $date[1], $format);
	$format = str_replace('d', $date[2], $format);
	return $format;
}

/*
+--------------------------------------------------------------------------
| Convierte una fecha con hora al formato yyyy-mm-dd
+--------------------------------------------------------------------------
*/

function datetime_decode($date, $format = 'Y-m-d') {
	$format = ($format == '') ? 'Y-m-d' : $format;
	$unido = explode(' ', $date);
	$date = explode('/', $unido[0]);
	$format = str_replace('Y', $date[2], $format);
	$format = str_replace('m', $date[1], $format);
	$format = str_replace('d', $date[0], $format);
	$datetime = $format . " " . $unido[1] . ":00";
	return $datetime;
}


/*
|------------------------------------------------------------
| Verifica si un dato es de tipo hora y fecha
|------------------------------------------------------------
*/

function is_datetime($datetime) {
	if (preg_match('/^([12][0-9]{3})-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01]) (0[0-9]|1[0-9]|2[0123])\:([012345][0-9])\:([012345][0-9])$/u', $datetime)) {
		return true;
	} else {
		return false;
	}
}

/*
|------------------------------------------------------------
| Verifica si un dato es de tipo fecha
|------------------------------------------------------------
*/

function is_date($date) {
	if (preg_match('/^([12][0-9]{3})-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/', $date) || preg_match('/^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[012])-([12][0-9]{3})$/', $date)){
		$date = explode('-', $date);
		if (checkdate($date[1], $date[2], $date[0]) || checkdate($date[1], $date[0], $date[2])) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

/*
|--------------------------------------------------------------------------
| Retorna el formato numeral de una fecha
|--------------------------------------------------------------------------
*/

function get_date_numeral($format = 'Y-m-d') {
	$format = ($format == '') ? 'Y-m-d' : $format;
	$format = str_replace('Y', '9999', $format);
	$format = str_replace('m', '99', $format);
	$format = str_replace('d', '99', $format);
	return $format;
}

/*
|--------------------------------------------------------------------------
| Retorna el formato textual de una fecha
|--------------------------------------------------------------------------
*/

function get_date_textual($format = 'Y-m-d') {
	$format = ($format == '') ? 'Y-m-d' : $format;
	$format = str_replace('Y', 'yyyy', $format);
	$format = str_replace('m', 'mm', $format);
	$format = str_replace('d', 'dd', $format);
	return $format;
}

/*
|------------------------------------------------------------
| Retorna la fecha con un retraso en horas
|------------------------------------------------------------
*/

function get_date($time = 0) {
	$time = ($time > 0) ? $time : 0;
	$date = date('Y-m-d H:i:s');
	$date = strtotime('-' . $time . ' hour', strtotime($date));
	$date = date('Y-m-d', $date);
	return $date;
}

/*
|------------------------------------------------------------
| Retorna el tiempo transcurrido a partir de una fecha y hora
|------------------------------------------------------------
*/

function moment($date) {
	$response = null;
	if (is_datetime($date)) {
		$initial = date_create($date);
		$final = date_create('now');
		if ($initial < $final) {
			$interval = date_diff($initial, $final);
			if ($interval->y > 0) {
				$response = $interval->y . (($interval->y == 1) ? ' año' : ' años');
			} else {
				if ($interval->m > 0) {
					$response = $interval->m . (($interval->m == 1) ? ' mes' : ' meses');
				} else {
					if ($interval->d > 0) {
						$response = $interval->d . (($interval->d == 1) ? ' día' : ' días');
					} else {
						if ($interval->h > 0) {
							$response = $interval->h . (($interval->h == 1) ? ' hora' : ' horas');
						} else {
							if ($interval->i > 0) {
								$response = $interval->i . (($interval->i == 1) ? ' minuto' : ' minutos');
							} else {
								if ($interval->s > 0) {
									$response = $interval->s . (($interval->s == 1) ? ' segundo' : ' segundos');
								}
							}
						}
					}
				}
			}
		}
	}
	return $response;
}

/*
|------------------------------------------------------------
| Retorna la edad de acuerdo a una fecha
|------------------------------------------------------------
*/

function age($date) {
	$date = strtotime($date);
	$day = date('d');
	$month = date('m');
	$year = date('Y');
	$day_birth = date('d', $date);
	$month_birth = date('m', $date);
	$year_birth = date('Y', $date);
	if (($month_birth == $month) && ($day_birth > $day)) {
		$year = $year - 1;
	}
	if ($month_birth > $month) {
		$year = $year - 1;
	}
	$age = $year - $year_birth;
	return $age;
}

/*
|------------------------------------------------------------
| Retorna la fecha actual
|------------------------------------------------------------
*/

function now($format = 'Y-m-d') {
	return date($format);
}

/*
|------------------------------------------------------------
| Retorna la primera fecha del mes actual
|------------------------------------------------------------
*/

function first_month_day($date, $format = 'Y-m-d') {
	$year = date('Y', strtotime($date));
	$month = date('m', strtotime($date));
	return date($format, mktime(0, 0, 0, $month, 1, $year));
}

/*
|------------------------------------------------------------
| Retorna la ultima fecha del mes actual
|------------------------------------------------------------
*/

function last_month_day($date, $format = 'Y-m-d') {
	$year = date('Y', strtotime($date));
	$month = date('m', strtotime($date));
	$day = date('d', mktime(0, 0, 0, $month + 1, 0, $year));
	return date($format, mktime(0, 0, 0, $month, $day, $year));
}

/*
|------------------------------------------------------------
| Retorna una fecha con la suma de x dias
|------------------------------------------------------------
*/

function add_day($date, $day = 1) { 
	$date = strtotime('+' . $day . ' day', strtotime($date));
	return date('Y-m-d', $date);
}

/*
|------------------------------------------------------------
| Retorna una fecha con la resta de x dias
|------------------------------------------------------------
*/

function remove_day($date, $day = 1) { 
	$date = strtotime('-' . $day . ' day', strtotime($date));
	return date('Y-m-d', $date);
}

/*
|--------------------------------------------------------------------------
| Convierte una fecha al formato 0000/xxx/00
|--------------------------------------------------------------------------
*/

function date_literal($date, $format = 'Y-m-d') {
	$months = array(1 => 'ene', 2 => 'feb', 3 => 'mar', 4 => 'abr', 5 => 'may', 6 => 'jun', 7 => 'jul', 8 => 'ago', 9 => 'sep', 10 => 'oct', 11 => 'nov', 12 => 'dic');
	$format = ($format == '') ? 'Y-m-d' : $format;
	$date = explode('-', $date);
	$format = str_replace('d', $date[2], $format);
	$format = str_replace('m', $months[intval($date[1])], $format);
	$format = str_replace('Y', $date[0], $format);
	return $format;
}

/*
|--------------------------------------------------------------------------
| Convierte una fecha al formato xxx 0
|--------------------------------------------------------------------------
*/

function month_day($date) {
	$months = array(1 => 'ene', 2 => 'feb', 3 => 'mar', 4 => 'abr', 5 => 'may', 6 => 'jun', 7 => 'jul', 8 => 'ago', 9 => 'sep', 10 => 'oct', 11 => 'nov', 12 => 'dic');
	$date = explode('-', $date);
	return $months[intval($date[1])] . ' ' . intval($date[2]);
}

/*
|--------------------------------------------------------------------------
| Retorna el nombre del dia de una fecha
|-------------------------------------------------------------------------- 
*/

function get_day($date) {
	$days = array(1 => 'lunes', 2 => 'martes', 3 => 'miércoles', 4 => 'jueves', 5 => 'viernes', 6 => 'sábado', 7 => 'domingo');
	return $days[date('N', strtotime($date))];
}

/*
|--------------------------------------------------------------------------
| Retorna el nombre resumido del dia de una fecha
|--------------------------------------------------------------------------
*/

function get_dayname($date) {
	$days = array(1 => 'lun', 2 => 'mar', 3 => 'mie', 4 => 'jue', 5 => 'vie', 6 => 'sab', 7 => 'dom');
	return $days[date('N', strtotime($date))];
}

/*
|--------------------------------------------------------------------------
| Retorna el nombre del dia de una fecha
|--------------------------------------------------------------------------
*/

function get_date_literal($date) {
	$days = array(1 => 'lunes', 2 => 'martes', 3 => 'miércoles', 4 => 'jueves', 5 => 'viernes', 6 => 'sábado', 7 => 'domingo');
	$months = array(1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril', 5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto', 9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre');
	$day = $days[date('N', strtotime($date))];
	$date = explode('-', $date);
	return $day . ' ' . intval($date[2]) . ' de ' . $months[intval($date[1])] . ' de ' . intval($date[0]);
}

/*
|--------------------------------------------------------------------------
| Retorna la diferencias en tiempo de dos tiempos
|--------------------------------------------------------------------------
*/

function difference($start, $end) {
	$difference = strtotime($end) - strtotime($start);
	$hours = floor($difference / 3600);
	$minutes = floor(($difference / 60) % 60);
	$seconds = $difference % 60;
	$hours = ($hours < 10) ? '0' . $hours : $hours; 
	$minutes = ($minutes < 10) ? '0' . $minutes : $minutes; 
	$seconds = ($seconds < 10) ? '0' . $seconds : $seconds; 
	return $hours . ':' . $minutes . ':' . $seconds;
}

/*
|--------------------------------------------------------------------------
| Retorna el valor en segundos de un tiempo
|--------------------------------------------------------------------------
*/

function convert_seconds($time){
	list($hours, $minutes, $seconds) = explode(':', $time);
	$seconds = ($hours * 3600) + ($minutes * 60) + $seconds;
	return $seconds; 
}

/*
|--------------------------------------------------------------------------
| Retorna en tiempo los segundos
|--------------------------------------------------------------------------
*/
function convert_time($seconds) {
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds - ($hours * 3600)) / 60);
    $seconds = $seconds - ($hours * 3600) - ($minutes * 60);
    $hours = ($hours < 10) ? '0' . $hours : $hours; 
	$minutes = ($minutes < 10) ? '0' . $minutes : $minutes; 
	$seconds = ($seconds < 10) ? '0' . $seconds : $seconds; 
	return $hours . ':' . $minutes . ':' . $seconds;
	//return $hours . ':' . $minutes;
}

/*
|--------------------------------------------------------------------------
| Retorna un nuevo tamano redimencionado
|--------------------------------------------------------------------------
*/

function resize($image_width = 0, $image_height = 0, $length = 0, $dimention = false) {
	$ratio = $image_width / $image_height;
	if ($dimention) {
		$length = $length * $ratio;
	} else {
		$length = $length / $ratio;
	}
	return intval($length);
}

/*
|--------------------------------------------------------------------------
| Retorna un array con los directorios de una ubicacion
|--------------------------------------------------------------------------
*/

function get_image($url = '', $length = 40) {
	if (file_exists($url)) {
		$image = file_get_contents($url);
		$image = 'data:image/jpeg;base64,' . base64_encode($image);
		list($width, $height) = getimagesize($url);
		$width = resize($width, $height, $length, true);
		$height = $length;
		return array($image, $width, $height);
	} else {
		return array('data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/4QAiRXhpZgAATU0AKgAAAAgAAQESAAMAAAABAAEAAAAAAAD/2wBDAAIBAQIBAQICAgICAgICAwUDAwMDAwYEBAMFBwYHBwcGBwcICQsJCAgKCAcHCg0KCgsMDAwMBwkODw0MDgsMDAz/2wBDAQICAgMDAwYDAwYMCAcIDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAz/wAARCAABAAEDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD9hKKKK/Dz6g//2Q==', 120, 40);
	}
}

/*
|--------------------------------------------------------------------------
| Retorna un array con los directorios de una ubicacion
|--------------------------------------------------------------------------
*/

function get_directories($route) {
	if (is_dir($route)) {
		$array_directories = array();
		$directories = opendir($route);
		while ($directory = readdir($directories)) {
			if ($directory != '.' && $directory != '..' && is_dir($route . '/' . $directory)) {
				array_push($array_directories, $directory);
			}
		}
		closedir($directories);
		return $array_directories;
	} else {
		return false;
	}
}

/*
|--------------------------------------------------------------------------
| Retorna un array con los archivos de un directorio
|--------------------------------------------------------------------------
*/

function get_files($route) {
	if (is_dir($route)) {
		$array_files = array();
		$files = opendir($route);
		while ($file = readdir($files)) {
			if ($file != '.' && $file != '..' && !is_dir($route . '/' . $file)) {
				$extention = substr($file, -4);
				$file = substr($file, 0, -4);
				if ($file != 'index' && $extention == '.php') {
					$array_files[] = $file;
				}
			}
		}
		closedir($files);
		return $array_files;
	} else {
		return false;
	}
}

/*
|--------------------------------------------------------------------------
| Crea un archivo
|--------------------------------------------------------------------------
*/

function file_create($route) {
	if (!file_exists($route)) {
		$file = fopen($route, 'x');
		fclose($file);
	}
}

/*
|--------------------------------------------------------------------------
| Elimina un archivo
|--------------------------------------------------------------------------
*/

function file_delete($route) { 
	if (file_exists($route)) { 
		unlink($route);
	}
}

/*
|--------------------------------------------------------------------------
| Verifica si un menu tiene predecesores
|--------------------------------------------------------------------------
*/

function verificar_submenu($menus, $id) {
	foreach ($menus as $menu) {
		if ($menu['antecesor_id'] == $id) {
			return true;
		}
	}
	return false;
}

/*
|--------------------------------------------------------------------------
| Construye el menu
|--------------------------------------------------------------------------
*/


function construir_menu($menus, $antecesor = 0) {
	$html = '';
	foreach ($menus as $menu) {
		if ($menu['antecesor_id'] != null) {
			if ($menu['antecesor_id'] == $antecesor) {
				if (verificar_submenu($menus, $menu['id_menu'])) {
					if ($antecesor == 0) {
						$html .= '<li class="dropdown"><a href="#" data-toggle="dropdown"><span class="' . escape($menu['icono']) . '"></span><span class="hidden-sm"> ' . escape($menu['menu']) . '</span><span class="glyphicon glyphicon-menu-down visible-xs-inline pull-right"></span></a>';
						$html .= '<ul class="dropdown-menu">';
						$html .= '<li class="dropdown-header visible-sm-block">' . escape($menu['menu']) . '</li>';
					} else {
						$html .= '<li class="dropdown-submenu"><a href="#" data-toggle="dropdown"><span class="' . escape($menu['icono']) . '"></span> ' . escape($menu['menu']) . '<span class="glyphicon glyphicon-menu-down visible-xs-inline pull-right"></span></a>';
						$html .= '<ul class="dropdown-menu">';
					}
					$html .= construir_menu($menus, $menu['id_menu']);
					$html .= '</ul>';
				} else {
					if ($antecesor == 0) {
						$html .= '<li><a href="' . (($menu['ruta'] == '') ? '#' : escape($menu['ruta']) ) . '"><span class="' . escape($menu['icono']) . '"></span><span class="hidden-sm"> ' . escape($menu['menu']) . '</span></a>';
					} else {
						$html .= '<li><a href="' . (($menu['ruta'] == '') ? '#' : escape($menu['ruta']) ) . '"><span class="' . escape($menu['icono']) . '"></span> ' . escape($menu['menu']) . '</a>';
					}
				}
			}
		} else {
			$html = ''; 
			break;
		}
	}
	return $html;
}

function construir_menu_horizontal($menus, $antecesor = 0) {
	$html = '';
	foreach ($menus as $menu) {
		if ($menu['antecesor_id'] != null) {
			if ($menu['antecesor_id'] == $antecesor) {
				if (verificar_submenu($menus, $menu['id_menu'])) {
					if ($antecesor == 0) {
						$html .= '<li class="nav-item"><a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-' .escape($menu['id_menu']). '" aria-controls="submenu-1">
						          <i class="' .escape($menu['icono']). '"></i><span class="hidden-sm"> ' .escape($menu['menu']). '</span></a>';
						$html .= '<div id="submenu-' .escape($menu['id_menu']). '" class="collapse submenu" style="">'; 
						$html .= '<ul class="nav flex-column">';
						//$html .= '<li class="dropdown-header">' . escape($menu['menu']) . '</li>';
						//$html .= '</li>';
					} else {
						$html .= '<li class="nav-item"> 
							          <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-' .escape($menu['id_menu']). '" aria-controls="submenu-1">
							          <i class="' .escape($menu['icono']). '"></i><span class="hidden-sm"> ' .escape($menu['menu']). '</span></a>';
						$html .= '<div id="submenu-' .escape($menu['id_menu']). '" class="collapse submenu" style="">';
						$html .= '<ul class="nav flex-column">';
					}
					$html .= construir_menu_horizontal($menus, $menu['id_menu']);
					$html .= '</ul>';
					$html .= '</div>';
				} else {
					if ($antecesor == 0) {
						$html .= '<li class="nav-item">
						            <a class="nav-link" href="' . (($menu['ruta'] == '') ? '#' : escape($menu['ruta']) ) . '">
						            <span class="' . escape($menu['icono']) . '"></span><span class="hidden-sm"> ' . escape($menu['menu']) . '</span></a>';
					} else {
						$html .= '<li class="nav-item"><a class="nav-link" href="' . (($menu['ruta'] == '') ? '#' : escape($menu['ruta']) ) . '">
						                               <span class="' . escape($menu['icono']) . '"></span> ' . escape($menu['menu']) . '</a>';
					}
				}
			}
		} else {
			$html = '';
			break;
		}
	}
	return $html;
}
/*

                            <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-10" aria-controls="submenu-10"><i class="fas fa-f fa-folder"></i>Menu Level</a>
                                <div id="submenu-10" class="collapse submenu" style="">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Level 1</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-11" aria-controls="submenu-11">Level 2</a>
                                            <div id="submenu-11" class="collapse submenu" style="">
                                                <ul class="nav flex-column">
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#">Level 1</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#">Level 2</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Level 3</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
|--------------------------------------------------------------------------
| Retorna el menu ordenado
|--------------------------------------------------------------------------
*/

function ordenar_menu($menus, $antecesor = 0, $lista = array()) {
	foreach ($menus as $menu) {
		if ($menu['antecesor_id'] == $antecesor) {
			if (verificar_submenu($menus, $menu['id_menu'])) {
				$menu['antecesor'] = 1;
				array_push($lista, $menu);
				$lista = ordenar_menu($menus, $menu['id_menu'], $lista);
			} else {
				$menu['antecesor'] = 0;
				array_push($lista, $menu);
			}
		}
	}
	return $lista;
}

?>