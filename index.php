<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Nombre del proyecto
define('name_project', basename(__DIR__));

// Nombre de la aplicacion
define('name_app', 'application');

// Incluye el archivo de configuracion de variables
require_once name_app . '/configuration/init.php';

// Incluye el archivo con funciones extendidas generales
require_once name_app . '/core/function.php';

// Incluye el archivo base para el funcionamiento
require_once name_app . '/core/autoload.php';

?>