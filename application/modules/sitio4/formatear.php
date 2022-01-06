<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica si el proyecto esta en desarrollo
if (environment == 'development') {
	// Formatea la base de datos
	$db->query("truncate table sys_roles")->execute();
	$db->query("truncate table sys_users")->execute();
	$db->query("truncate table sys_menus")->execute();
	$db->query("truncate table sys_permisos")->execute();
	$db->query("truncate table sys_procesos")->execute();
	$db->query("insert into sys_roles (rol, descripcion) values ('Superusuario', 'Usuario con acceso total del sistema')")->execute();
	$db->query("insert into sys_users (username, password, active, visible, rol_id) values ('admin', '" . encrypt('admin') . "', 's', 'n', 1)")->execute();
	$db->query("insert into sys_menus (menu, icono, ruta, modulo, antecesor_id) values ('Administración', 'glyphicon glyphicon-dashboard', '', '', 0), ('Administración de procesos', 'halflings halflings-roundabout', '?/procesos/listar', 'procesos', 1), ('Asignación de permisos', 'glyphicon glyphicon-lock', '?/permisos/listar', 'permisos', 1), ('Configuración general', 'glyphicon glyphicon-cog', '?/configuracion/principal', 'configuracion', 1), ('Roles', 'halflings halflings-cluster', '?/roles/listar', 'roles', 1), ('Usuarios', 'glyphicon glyphicon-user', '?/usuarios/listar', 'usuarios', 1)")->execute();
	$db->query("insert into sys_permisos (rol_id, menu_id, archivos) values (1, 1, ''), (1, 2, 'listar'), (1, 3, 'asignar,guardar,listar'), (1, 4, 'eliminar,informacion,presentacion,principal,subir'), (1, 5, 'crear,eliminar,guardar,imprimir,listar,modificar,ver'), (1, 6, 'bloquear,cambiar,crear,desbloquear,eliminar,guardar,imprimir,listar,modificar,subir,suprimir,validar-crear,validar-modificar,ver')")->execute();
	$db->query("update sys_instituciones set nombre = 'Inventarios', sigla = 'SW', lema = 'Inventarios', razon_social = 'Inventarios', nit = '', propietario = 'Propietario', direccion = 'Dirección', telefono = '', correo = 'inventarios@dominio.com', logotipo = '', informacion = 'Inventarios', formato = 'Y-m-d', icono = '', reloj = 'n', tema = 'bootstrap' where `id_institucion` = 1")->execute();
}

// Redirecciona la pagina
redirect(index_public);

?>