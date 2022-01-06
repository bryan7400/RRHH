<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Obtiene los permisos
$_menus = $db->select('m.modulo, group_concat(p.archivos) as archivos')->from('sys_permisos p')->join('sys_menus m', 'p.menu_id = m.id_menu')->where(array('p.rol_id' => $_SESSION[user]['rol_id'], 'm.id_menu != ' => 0,  'p.archivos != ' => ''))->group_by('m.modulo')->fetch();

// Define el estado de autorizacion de un modulo
$_is_module = false;

// Define el grupo de archivos de un modulo
$_views = '';

// Recorre y verifica si tiene acceso al modulo 
foreach ($_menus as $_menu) {
	if ($_menu['modulo'] == $_module) {
		$_is_module = true;
		$_views = $_menu['archivos'];
		break;
	}
}

// Desglosa las carpetas accesibles sin permisos  
$_folder_allow = explode(',', folder_allow);

// Verifica si tiene acceso al modulo
if (!$_is_module && !in_array($_module, $_folder_allow)) {
	// Error 401
	require_once bad_request();
	exit;
} else {
	// Obtiene las vistas
	$_views = explode(',', $_views);

	// Verifica si tiene acceso a la vista
	if (!in_array($_file, $_views) && !in_array($_module, $_folder_allow)) {
		// Error 401
		require_once bad_request();
		exit;
	}
}

// Recupera la variable
$_modules = modules;

// Obtiene datos de la empresa $_institution = palabra reservada
$_institution = $db->from('sys_instituciones')->fetch_first();

// Obtiene los  datos del usuario $_user = palabra reservada
// $_user = $db->select('u.*, r.rol')->from('sys_users u')->join('sys_roles r', 'u.rol_id = r.id_rol', 'left')->where('u.id_user', $_SESSION[user]['id_user'])->fetch_first();
$_user = $db->select('u.*, r.*, p.*')->from('sys_users u')->join('sys_roles r', 'u.rol_id = r.id_rol', 'left')->join('sys_persona p', 'u.persona_id = p.id_persona', 'left')->where('u.id_user', $_SESSION[user]['id_user'])->fetch_first();

//obtiene el año actual
$anio_actual = date('Y');
//var_dump($_SESSION[gestion]['id_gestion']);exit();

//obtiene los datos de la gestion actual
$_gestion = $db->select('z.id_gestion, z.gestion')->from('ins_gestion z')->where('id_gestion', $_SESSION[gest]['id_gestion'])->fetch_first();

//obtiene los datos del modo de calificacion actual
$fecha_actual = Date('Y-m-d');
$_modo_calificacion = $db->select('mc.id_modo_calificacion, mc.fecha_inicio, mc.fecha_final, mc.descripcion')->from('cal_modo_calificacion mc')->where('mc.fecha_inicio <=', $fecha_actual)->where('mc.fecha_final >=', $fecha_actual)->where('mc.gestion_id', $_gestion['id_gestion'])->fetch_first();

// Obtiene los datos de la terminal
$_terminal = $db->from('inv_terminales')->where('identificador', $_SESSION[locale])->fetch_first();

?>