<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion post
if (is_post()) {
	// Verifica la cadena csrf
	if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
		if (isset($_POST['tabla']) && isset($_POST['tabla_plural']) && isset($_POST['tabla_singular']) && isset($_POST['alias_plural']) && isset($_POST['alias_singular']) && isset($_POST['sintaxis']) && isset($_POST['archivos']) && isset($_POST['campos']) && isset($_POST['etiquetas']) && isset($_POST['tipos']) && isset($_POST['tamanos']) && isset($_POST['formularios']) && isset($_POST['tablas']) && isset($_POST['validaciones'])) {
			// Obtiene los datos
			$_tabla = clear($_POST['tabla']);
			$_tabla_plural = clear($_POST['tabla_plural']);
			$_tabla_singular = clear($_POST['tabla_singular']);
			$_alias_plural = clear($_POST['alias_plural']);
			$_alias_singular = clear($_POST['alias_singular']);
			$_sintaxis = clear($_POST['sintaxis']);
			$_archivos = $_POST['archivos'];

			// Obtiene los arrays
			$campos = clear_value($_POST, 'campos');
			$etiquetas = clear_value($_POST, 'etiquetas');
			$tipos = clear_value($_POST, 'tipos');
			$tamanos = clear_value($_POST, 'tamanos');
			$formularios = clear_value($_POST, 'formularios');
			$tablas = clear_value($_POST, 'tablas');
			$claves = clear_value($_POST, 'claves');
			$valores = clear_value($_POST, 'valores');
			$validaciones = clear_value($_POST, 'validaciones');
			
			// Define la matriz de datos
			$_elementos = array();

			// Recorre los campos
			foreach ($campos as $indice => $campo) {
				// Obtiene valores
				$etiqueta = clear_value($etiquetas, $indice);
				$tipo = clear_value($tipos, $indice);
				$tamano = clear_value($tamanos, $indice);
				$formulario = clear_value($formularios, $indice);
				$tabla = clear_value($tablas, $indice);
				$clave = clear_value($claves, $indice);
				$valor = clear_value($valores, $indice);
				$validacion = clear_value($validaciones, $indice);

				// Almacena los campos en un array
				$elemento = array();
				array_push($elemento, $campo);
				array_push($elemento, $etiqueta);
				array_push($elemento, $tipo);
				array_push($elemento, $tamano);
				array_push($elemento, $formulario);
				array_push($elemento, $tabla);
				array_push($elemento, $clave);
				array_push($elemento, $valor);
				array_push($elemento, $validacion);
				
				// Almacena los arrays en una matriz
				array_push($_elementos, $elemento);
			}

			// Define un nombre arbitrario para el modulo en caso de no existir uno
			$_tabla_plural = ($_tabla_plural != '') ? $_tabla_plural : random_string(20);
			
			// Define el directorio del modulo
			$_directorio = modules . '/' . $_tabla_plural;

			// Define los generos
			$_generos = array(
				'el' => array('masculino' => 'el', 'femenino' => 'la'),
				'los' => array('masculino' => 'los', 'femenino' => 'las'),
				'un' => array('masculino' => 'un', 'femenino' => 'una'),
				'unos' => array('masculino' => 'unos', 'femenino' => 'unas'),
				'del' => array('masculino' => 'del', 'femenino' => 'de la'),
				'nuevo' => array('masculino' => 'nuevo', 'femenino' => 'nueva'),
				'nuevos' => array('masculino' => 'nuevos', 'femenino' => 'nuevas'),
				'registrados' => array('masculino' => 'registrados', 'femenino' => 'registradas')
			);

			// Verifica si el directorio existe
			if (is_dir($_directorio)) {
				// Elimina el directorio y todo su contenido
				eliminar_directorio($_directorio);
			}

			// Crea el directorio
			mkdir($_directorio, 0777, true);

			// Ejecuta la funcion crear_archivo_listar 
			if (in_array('listar', $_archivos)) {
				crear_archivo_listar($_directorio, $_tabla, $_tabla_plural, $_tabla_singular, $_alias_plural, $_alias_singular, $_sintaxis, $_elementos, $_generos);
			}

			// Ejecuta la funcion crear_archivo_crear 
			if (in_array('crear', $_archivos)) {
				crear_archivo_crear($_directorio, $_tabla, $_tabla_plural, $_tabla_singular, $_alias_plural, $_alias_singular, $_sintaxis, $_elementos, $_generos);
			}

			// Ejecuta la funcion crear_archivo_modificar 
			if (in_array('modificar', $_archivos)) {
				crear_archivo_modificar($_directorio, $_tabla, $_tabla_plural, $_tabla_singular, $_alias_plural, $_alias_singular, $_sintaxis, $_elementos, $_generos);
			}

			// Ejecuta la funcion crear_archivo_ver 
			if (in_array('ver', $_archivos)) {
				crear_archivo_ver($_directorio, $_tabla, $_tabla_plural, $_tabla_singular, $_alias_plural, $_alias_singular, $_sintaxis, $_elementos, $_generos);
			}

			// Ejecuta la funcion crear_archivo_imprimir 
			if (in_array('imprimir', $_archivos)) {
				crear_archivo_imprimir($_directorio, $_tabla, $_tabla_plural, $_tabla_singular, $_alias_plural, $_alias_singular, $_sintaxis, $_elementos, $_generos);
			}

			// Ejecuta la funcion crear_archivo_eliminar 
			if (in_array('eliminar', $_archivos)) {
				crear_archivo_eliminar($_directorio, $_tabla, $_tabla_plural, $_tabla_singular, $_alias_plural, $_alias_singular, $_sintaxis, $_elementos, $_generos);
			}

			// Ejecuta la funcion crear_archivo_guardar 
			if (in_array('crear', $_archivos) || in_array('modificar', $_archivos)) {
				crear_archivo_guardar($_directorio, $_tabla, $_tabla_plural, $_tabla_singular, $_alias_plural, $_alias_singular, $_sintaxis, $_elementos, $_generos);
			}

			// Crea la notificacion
			set_notification('success', 'Creación exitosa!', 'El módulo ' . $_tabla_plural . ' se creó satisfactoriamente.');

			// Redirecciona la pagina
			redirect('?/generador-modulos/principal');
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	} else {
		// Redirecciona la pagina
		redirect('?/generador-modulos/principal');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

function crear_archivo_listar($directorio, $tabla, $tabla_plural, $tabla_singular, $alias_plural, $alias_singular, $sintaxis, $elementos, $generos) {
	// Genera el codigo php
	$lineas = "<?php\n";
	$lineas .= "\n";
	$lineas .= "// Obtiene la cadena csrf\n";
	$lineas .= "\$csrf = set_csrf();\n";
	$lineas .= "\n";
	$lineas .= "// Obtiene " . $generos['los'][$sintaxis] . " $tabla_plural\n";
	$lineas .= formar_consultas($tabla, $tabla_plural, $elementos);
	$lineas .= "\n";
	$lineas .= "// Obtiene los permisos\n";
	$lineas .= "\$permiso_crear = in_array('crear', \$_views);\n";
	$lineas .= "\$permiso_ver = in_array('ver', \$_views);\n";
	$lineas .= "\$permiso_modificar = in_array('modificar', \$_views);\n";
	$lineas .= "\$permiso_eliminar = in_array('eliminar', \$_views);\n";
	$lineas .= "\$permiso_imprimir = in_array('imprimir', \$_views);\n";
	$lineas .= "\n";
	$lineas .= "?>\n";
	$lineas .= "<?php require_once show_template('header-full'); ?>\n";
	$lineas .= "<div class=\"panel-heading\">\n";
	$lineas .= "\t<h3 class=\"panel-title\" data-header=\"true\">\n";
	$lineas .= "\t\t<span class=\"glyphicon glyphicon-option-vertical\"></span>\n";
	$lineas .= "\t\t<strong>" . capitalize($alias_plural) . "</strong>\n";
	$lineas .= "\t</h3>\n";
	$lineas .= "</div>\n";
	$lineas .= "<div class=\"panel-body\">\n";
	$lineas .= "\t<?php if (\$permiso_crear || \$permiso_imprimir) : ?>\n";
	$lineas .= "\t<div class=\"row\">\n";
	$lineas .= "\t\t<div class=\"col-xs-6\">\n";
	$lineas .= "\t\t\t<div class=\"text-label hidden-xs\">Seleccionar acción:</div>\n";
	$lineas .= "\t\t\t<div class=\"text-label visible-xs-block\">Acciones:</div>\n";
	$lineas .= "\t\t</div>\n";
	$lineas .= "\t\t<div class=\"col-xs-6 text-right\">\n";
	$lineas .= "\t\t\t<div class=\"btn-group\">\n";
	$lineas .= "\t\t\t\t<button type=\"button\" class=\"btn btn-danger dropdown-toggle\" data-toggle=\"dropdown\">\n";
	$lineas .= "\t\t\t\t\t<span class=\"glyphicon glyphicon-menu-hamburger\"></span>\n";
	$lineas .= "\t\t\t\t\t<span class=\"hidden-xs\">Acciones</span>\n";
	$lineas .= "\t\t\t\t</button>\n";
	$lineas .= "\t\t\t\t<ul class=\"dropdown-menu dropdown-menu-right\">\n";
	$lineas .= "\t\t\t\t\t<li class=\"dropdown-header visible-xs-block\">Seleccionar acción</li>\n";
	$lineas .= "\t\t\t\t\t<?php if (\$permiso_crear) : ?>\n";
	$lineas .= "\t\t\t\t\t<li><a href=\"?/$tabla_plural/crear\"><span class=\"glyphicon glyphicon-plus\"></span> Crear $alias_singular</a></li>\n";
	$lineas .= "\t\t\t\t\t<?php endif ?>\n";
	$lineas .= "\t\t\t\t\t<?php if (\$permiso_imprimir) : ?>\n";
	$lineas .= "\t\t\t\t\t<li><a href=\"?/$tabla_plural/imprimir\" target=\"_blank\"><span class=\"glyphicon glyphicon-print\"></span> Imprimir $alias_plural</a></li>\n";
	$lineas .= "\t\t\t\t\t<?php endif ?>\n";
	$lineas .= "\t\t\t\t</ul>\n";
	$lineas .= "\t\t\t</div>\n";
	$lineas .= "\t\t</div>\n";
	$lineas .= "\t</div>\n";
	$lineas .= "\t<hr>\n";
	$lineas .= "\t<?php endif ?>\n";
	$lineas .= "\t<?php if (\$message = get_notification()) : ?>\n";
	$lineas .= "\t<div class=\"alert alert-<?= \$message['type']; ?>\">\n";
	$lineas .= "\t\t<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>\n";
	$lineas .= "\t\t<strong><?= \$message['title']; ?></strong>\n";
	$lineas .= "\t\t<p><?= \$message['content']; ?></p>\n";
	$lineas .= "\t</div>\n";
	$lineas .= "\t<?php endif ?>\n";
	$lineas .= "\t<?php if (\$$tabla_plural) : ?>\n";
	$lineas .= "\t<table id=\"table\" class=\"table table-bordered table-condensed table-striped table-hover\">\n";
	$lineas .= "\t\t<thead>\n";
	$lineas .= "\t\t\t<tr class=\"active\">\n";
	$lineas .= formar_cabeceras($elementos, true);
	$lineas .= "\t\t\t\t<?php if (\$permiso_ver || \$permiso_modificar || \$permiso_eliminar) : ?>\n";
	$lineas .= "\t\t\t\t<th class=\"text-nowrap\">Opciones</th>\n";
	$lineas .= "\t\t\t\t<?php endif ?>\n";
	$lineas .= "\t\t\t</tr>\n";
	$lineas .= "\t\t</thead>\n";
	$lineas .= "\t\t<tfoot>\n";
	$lineas .= "\t\t\t<tr class=\"active\">\n";
	$lineas .= formar_cabeceras($elementos, false);
	$lineas .= "\t\t\t\t<?php if (\$permiso_ver || \$permiso_modificar || \$permiso_eliminar) : ?>\n";
	$lineas .= "\t\t\t\t<th class=\"text-nowrap text-middle\" data-datafilter-filter=\"false\">Opciones</th>\n";
	$lineas .= "\t\t\t\t<?php endif ?>\n";
	$lineas .= "\t\t\t</tr>\n";
	$lineas .= "\t\t</tfoot>\n";
	$lineas .= "\t\t<tbody>\n";
	$lineas .= "\t\t\t<?php foreach (\$$tabla_plural as \$nro => \$$tabla_singular) : ?>\n";
	$lineas .= "\t\t\t<tr>\n";
	$lineas .= formar_celdas($tabla_singular, $elementos);
	$lineas .= "\t\t\t\t<?php if (\$permiso_ver || \$permiso_modificar || \$permiso_eliminar) : ?>\n";
	$lineas .= "\t\t\t\t<td class=\"text-nowrap\">\n";
	$lineas .= "\t\t\t\t\t<?php if (\$permiso_ver) : ?>\n";
	$lineas .= "\t\t\t\t\t<a href=\"?/$tabla_plural/ver/<?= \$$tabla_singular" . "['" . $elementos[0][0] . "']; ?>\" data-toggle=\"tooltip\" data-title=\"Ver $alias_singular\"><span class=\"glyphicon glyphicon-search\"></span></a>\n";
	$lineas .= "\t\t\t\t\t<?php endif ?>\n";
	$lineas .= "\t\t\t\t\t<?php if (\$permiso_modificar) : ?>\n";
	$lineas .= "\t\t\t\t\t<a href=\"?/$tabla_plural/modificar/<?= \$$tabla_singular" . "['" . $elementos[0][0] . "']; ?>\" data-toggle=\"tooltip\" data-title=\"Modificar $alias_singular\"><span class=\"glyphicon glyphicon-edit\"></span></a>\n";
	$lineas .= "\t\t\t\t\t<?php endif ?>\n";
	$lineas .= "\t\t\t\t\t<?php if (\$permiso_eliminar) : ?>\n";
	$lineas .= "\t\t\t\t\t<a href=\"?/$tabla_plural/eliminar/<?= \$$tabla_singular" . "['" . $elementos[0][0] . "']; ?>\" data-toggle=\"tooltip\" data-title=\"Eliminar $alias_singular\" data-eliminar=\"true\"><span class=\"glyphicon glyphicon-trash\"></span></a>\n";
	$lineas .= "\t\t\t\t\t<?php endif ?>\n";
	$lineas .= "\t\t\t\t</td>\n";
	$lineas .= "\t\t\t\t<?php endif ?>\n";
	$lineas .= "\t\t\t</tr>\n";
	$lineas .= "\t\t\t<?php endforeach ?>\n";
	$lineas .= "\t\t</tbody>\n";
	$lineas .= "\t</table>\n";
	$lineas .= "\t<?php else : ?>\n";
	$lineas .= "\t<div class=\"alert alert-info\">\n";
	$lineas .= "\t\t<strong>Atención!</strong>\n";
	$lineas .= "\t\t<ul>\n";
	$lineas .= "\t\t\t<li>No existen $alias_plural " . $generos['registrados'][$sintaxis] . " en la base de datos.</li>\n";
	$lineas .= "\t\t\t<li>Para crear " . $generos['nuevos'][$sintaxis] . " $alias_plural debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>\n";
	$lineas .= "\t\t</ul>\n";
	$lineas .= "\t</div>\n";
	$lineas .= "\t<?php endif ?>\n";
	$lineas .= "</div>\n";
	$lineas .= "<script src=\"<?= js; ?>/jquery.dataTables.min.js\"></script>\n";
	$lineas .= "<script src=\"<?= js; ?>/dataTables.bootstrap.min.js\"></script>\n";
	$lineas .= "<script src=\"<?= js; ?>/jquery.base64.js\"></script>\n";
	$lineas .= "<script src=\"<?= js; ?>/pdfmake.min.js\"></script>\n";
	$lineas .= "<script src=\"<?= js; ?>/vfs_fonts.js\"></script>\n";
	$lineas .= "<script src=\"<?= js; ?>/jquery.dataFilters.min.js\"></script>\n";
	$lineas .= "<script>\n";
	$lineas .= "\$(function () {\n";
	$lineas .= "\t<?php if (\$permiso_crear) : ?>\n";
	$lineas .= "\t\$(window).bind('keydown', function (e) {\n";
	$lineas .= "\t\tif (e.altKey || e.metaKey) {\n";
	$lineas .= "\t\t\tswitch (String.fromCharCode(e.which).toLowerCase()) {\n";
	$lineas .= "\t\t\t\tcase 'n':\n";
	$lineas .= "\t\t\t\t\te.preventDefault();\n";
	$lineas .= "\t\t\t\t\twindow.location = '?/$tabla_plural/crear';\n";
	$lineas .= "\t\t\t\tbreak;\n";
	$lineas .= "\t\t\t}\n";
	$lineas .= "\t\t}\n";
	$lineas .= "\t});\n";
	$lineas .= "\t<?php endif ?>\n";
	$lineas .= "\t\n";
	$lineas .= "\t<?php if (\$permiso_eliminar) : ?>\n";
	$lineas .= "\t\$('[data-eliminar]').on('click', function (e) {\n";
	$lineas .= "\t\te.preventDefault();\n";
	$lineas .= "\t\tvar href = \$(this).attr('href');\n";
	$lineas .= "\t\tvar csrf = '<?= \$csrf; ?>';\n";
	$lineas .= "\t\tbootbox.confirm('¿Está seguro que desea eliminar " . $generos['el'][$sintaxis] . " $alias_singular?', function (result) {\n";
	$lineas .= "\t\t\tif (result) {\n";
	$lineas .= "\t\t\t\t\$.request(href, csrf);\n";
	$lineas .= "\t\t\t}\n";
	$lineas .= "\t\t});\n";
	$lineas .= "\t});\n";
	$lineas .= "\t<?php endif ?>\n";
	$lineas .= "\t\n";
	$lineas .= "\t<?php if (\$$tabla_plural) : ?>\n";
	$lineas .= "\t\$('#table').DataFilter({\n";
	$lineas .= "\t\tfilter: true,\n";
	$lineas .= "\t\tname: '$tabla_plural',\n";
	$lineas .= "\t\treports: '<?= (\$permiso_imprimir) ? \"excel|word|pdf|html\" : \"\"; ?>'\n";
	$lineas .= "\t});\n";
	$lineas .= "\t<?php endif ?>\n";
	$lineas .= "});\n";
	$lineas .= "</script>\n";
	$lineas .= "<?php require_once show_template('footer-full'); ?>";

	// Crea el archivo en el directorio
	$archivo = fopen($directorio . '/' . 'listar.php', 'x');
	fwrite($archivo, $lineas);
	fclose($archivo);
}

function crear_archivo_crear($directorio, $tabla, $tabla_plural, $tabla_singular, $alias_plural, $alias_singular, $sintaxis, $elementos, $generos) {
	// Genera el codigo php
	$lineas = "<?php\n";
	$lineas .= "\n";
	$lineas .= "// Obtiene la cadena csrf\n";
	$lineas .= "\$csrf = set_csrf();\n";
	$lineas .= formar_variables($elementos);
	$lineas .= formar_modelos($elementos);
	$lineas .= "\n";
	$lineas .= "// Obtiene los permisos\n";
	$lineas .= "\$permiso_listar = in_array('listar', \$_views);\n";
	$lineas .= "\n";
	$lineas .= "?>\n";
	$lineas .= "<?php require_once show_template('header-full'); ?>\n";
	$lineas .= "<div class=\"panel-heading\">\n";
	$lineas .= "\t<h3 class=\"panel-title\" data-header=\"true\">\n";
	$lineas .= "\t\t<span class=\"glyphicon glyphicon-option-vertical\"></span>\n";
	$lineas .= "\t\t<strong>Crear $alias_singular</strong>\n";
	$lineas .= "\t</h3>\n";
	$lineas .= "</div>\n";
	$lineas .= "<div class=\"panel-body\">\n";
	$lineas .= "\t<?php if (\$permiso_listar) : ?>\n";
	$lineas .= "\t<div class=\"row\">\n";
	$lineas .= "\t\t<div class=\"col-xs-6\">\n";
	$lineas .= "\t\t\t<div class=\"text-label hidden-xs\">Seleccionar acción:</div>\n";
	$lineas .= "\t\t\t<div class=\"text-label visible-xs-block\">Acciones:</div>\n";
	$lineas .= "\t\t</div>\n";
	$lineas .= "\t\t<div class=\"col-xs-6 text-right\">\n";
	$lineas .= "\t\t\t<div class=\"btn-group\">\n";
	$lineas .= "\t\t\t\t<button type=\"button\" class=\"btn btn-danger dropdown-toggle\" data-toggle=\"dropdown\">\n";
	$lineas .= "\t\t\t\t\t<span class=\"glyphicon glyphicon-menu-hamburger\"></span>\n";
	$lineas .= "\t\t\t\t\t<span class=\"hidden-xs\">Acciones</span>\n";
	$lineas .= "\t\t\t\t</button>\n";
	$lineas .= "\t\t\t\t<ul class=\"dropdown-menu dropdown-menu-right\">\n";
	$lineas .= "\t\t\t\t\t<li class=\"dropdown-header visible-xs-block\">Seleccionar acción</li>\n";
	$lineas .= "\t\t\t\t\t<li><a href=\"?/$tabla_plural/listar\"><span class=\"glyphicon glyphicon-list-alt\"></span> Listar $tabla_plural</a></li>\n";
	$lineas .= "\t\t\t\t</ul>\n";
	$lineas .= "\t\t\t</div>\n";
	$lineas .= "\t\t</div>\n";
	$lineas .= "\t</div>\n";
	$lineas .= "\t<hr>\n";
	$lineas .= "\t<?php endif ?>\n";
	$lineas .= "\t<div class=\"row\">\n";
	$lineas .= "\t\t<div class=\"col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3\">\n";
	$lineas .= "\t\t\t<form method=\"post\" action=\"?/$tabla_plural/guardar\" autocomplete=\"off\">\n";
	$lineas .= "\t\t\t\t<input type=\"hidden\" name=\"<?= \$csrf; ?>\">\n";
	$lineas .= formar_campos_crear($tabla_singular, $elementos);
	$lineas .= "\t\t\t\t<div class=\"form-group\">\n";
	$lineas .= "\t\t\t\t\t<button type=\"submit\" class=\"btn btn-danger\">\n";
	$lineas .= "\t\t\t\t\t\t<span class=\"glyphicon glyphicon-floppy-disk\"></span>\n";
	$lineas .= "\t\t\t\t\t\t<span>Guardar</span>\n";
	$lineas .= "\t\t\t\t\t</button>\n";
	$lineas .= "\t\t\t\t\t<button type=\"reset\" class=\"btn btn-default\">\n";
	$lineas .= "\t\t\t\t\t\t<span class=\"glyphicon glyphicon-refresh\"></span>\n";
	$lineas .= "\t\t\t\t\t\t<span>Restablecer</span>\n";
	$lineas .= "\t\t\t\t\t</button>\n";
	$lineas .= "\t\t\t\t</div>\n";
	$lineas .= "\t\t\t</form>\n";
	$lineas .= "\t\t</div>\n";
	$lineas .= "\t</div>\n";
	$lineas .= "</div>\n";
	$lineas .= "<script src=\"<?= js; ?>/jquery.form-validator.min.js\"></script>\n";
	$lineas .= "<script src=\"<?= js; ?>/jquery.form-validator.es.js\"></script>\n";
	$lineas .= formar_scripts($elementos);
	$lineas .= "<script>\n";
	$lineas .= "\$(function () {\n";
	$lineas .= "\t\$.validate({\n";
	$lineas .= "\t\tmodules: 'basic'\n";
	$lineas .= "\t});\n";
	$lineas .= formar_procesos($elementos);
	$lineas .= "});\n";
	$lineas .= "</script>\n";
	$lineas .= "<?php require_once show_template('footer-full'); ?>";

	// Crea el archivo en el directorio
	$archivo = fopen($directorio . '/' . 'crear.php', 'x');
	fwrite($archivo, $lineas);
	fclose($archivo);
}

function crear_archivo_modificar($directorio, $tabla, $tabla_plural, $tabla_singular, $alias_plural, $alias_singular, $sintaxis, $elementos, $generos) {
	// Genera el codigo php
	$lineas = "<?php\n";
	$lineas .= "\n";
	$lineas .= "// Obtiene los parametros\n";
	$lineas .= "\$" . $elementos[0][0] . " = (isset(\$_params[0])) ? \$_params[0] : 0;\n";
	$lineas .= "\n";
	$lineas .= "// Obtiene la cadena csrf\n";
	$lineas .= "\$csrf = set_csrf();\n";
	$lineas .= formar_variables($elementos);
	$lineas .= "\n";
	$lineas .= "// Obtiene " . $generos['el'][$sintaxis] . " $tabla_singular\n";
	$lineas .= formar_consulta($tabla, $tabla_singular, $elementos);
	$lineas .= "\n";
	$lineas .= "// Ejecuta un error 404 si no existe " . $generos['el'][$sintaxis] . " $tabla_singular\n";
	$lineas .= "if (!\$$tabla_singular) { require_once not_found(); exit; }\n";
	$lineas .= formar_modelos($elementos);
	$lineas .= "\n";
	$lineas .= "// Obtiene los permisos\n";
	$lineas .= "\$permiso_listar = in_array('listar', \$_views);\n";
	$lineas .= "\$permiso_crear = in_array('crear', \$_views);\n";
	$lineas .= "\$permiso_ver = in_array('ver', \$_views);\n";
	$lineas .= "\$permiso_eliminar = in_array('eliminar', \$_views);\n";
	$lineas .= "\$permiso_imprimir = in_array('imprimir', \$_views);\n";
	$lineas .= "\n";
	$lineas .= "?>\n";
	$lineas .= "<?php require_once show_template('header-full'); ?>\n";
	$lineas .= "<div class=\"panel-heading\">\n";
	$lineas .= "\t<h3 class=\"panel-title\" data-header=\"true\">\n";
	$lineas .= "\t\t<span class=\"glyphicon glyphicon-option-vertical\"></span>\n";
	$lineas .= "\t\t<strong>Modificar $alias_singular</strong>\n";
	$lineas .= "\t</h3>\n";
	$lineas .= "</div>\n";
	$lineas .= "<div class=\"panel-body\">\n";
	$lineas .= "\t<?php if (\$permiso_listar || \$permiso_crear || \$permiso_ver || \$permiso_eliminar || \$permiso_imprimir) : ?>\n";
	$lineas .= "\t<div class=\"row\">\n";
	$lineas .= "\t\t<div class=\"col-xs-6\">\n";
	$lineas .= "\t\t\t<div class=\"text-label hidden-xs\">Seleccionar acción:</div>\n";
	$lineas .= "\t\t\t<div class=\"text-label visible-xs-block\">Acciones:</div>\n";
	$lineas .= "\t\t</div>\n";
	$lineas .= "\t\t<div class=\"col-xs-6 text-right\">\n";
	$lineas .= "\t\t\t<div class=\"btn-group\">\n";
	$lineas .= "\t\t\t\t<button type=\"button\" class=\"btn btn-danger dropdown-toggle\" data-toggle=\"dropdown\">\n";
	$lineas .= "\t\t\t\t\t<span class=\"glyphicon glyphicon-menu-hamburger\"></span>\n";
	$lineas .= "\t\t\t\t\t<span class=\"hidden-xs\">Acciones</span>\n";
	$lineas .= "\t\t\t\t</button>\n";
	$lineas .= "\t\t\t\t<ul class=\"dropdown-menu dropdown-menu-right\">\n";
	$lineas .= "\t\t\t\t\t<li class=\"dropdown-header visible-xs-block\">Seleccionar acción</li>\n";
	$lineas .= "\t\t\t\t\t<?php if (\$permiso_listar) : ?>\n";
	$lineas .= "\t\t\t\t\t<li><a href=\"?/$tabla_plural/listar\"><span class=\"glyphicon glyphicon-list-alt\"></span> Listar $alias_plural</a></li>\n";
	$lineas .= "\t\t\t\t\t<?php endif ?>\n";
	$lineas .= "\t\t\t\t\t<?php if (\$permiso_crear) : ?>\n";
	$lineas .= "\t\t\t\t\t<li><a href=\"?/$tabla_plural/crear\"><span class=\"glyphicon glyphicon-plus\"></span> Crear $alias_singular</a></li>\n";
	$lineas .= "\t\t\t\t\t<?php endif ?>\n";
	$lineas .= "\t\t\t\t\t<?php if (\$permiso_ver) : ?>\n";
	$lineas .= "\t\t\t\t\t<li><a href=\"?/$tabla_plural/ver/<?= \$" . $elementos[0][0] . "; ?>\"><span class=\"glyphicon glyphicon-search\"></span> Ver $alias_singular</a></li>\n";
	$lineas .= "\t\t\t\t\t<?php endif ?>\n";
	$lineas .= "\t\t\t\t\t<?php if (\$permiso_eliminar) : ?>\n";
	$lineas .= "\t\t\t\t\t<li><a href=\"?/$tabla_plural/eliminar/<?= \$" . $elementos[0][0] . "; ?>\" data-eliminar=\"true\"><span class=\"glyphicon glyphicon-trash\"></span> Eliminar $alias_singular</a></li>\n";
	$lineas .= "\t\t\t\t\t<?php endif ?>\n";
	$lineas .= "\t\t\t\t\t<?php if (\$permiso_imprimir) : ?>\n";
	$lineas .= "\t\t\t\t\t<li><a href=\"?/$tabla_plural/imprimir/<?= \$" . $elementos[0][0] . "; ?>\" target=\"_blank\"><span class=\"glyphicon glyphicon-print\"></span> Imprimir $alias_singular</a></li>\n";
	$lineas .= "\t\t\t\t\t<?php endif ?>\n";
	$lineas .= "\t\t\t\t</ul>\n";
	$lineas .= "\t\t\t</div>\n";
	$lineas .= "\t\t</div>\n";
	$lineas .= "\t</div>\n";
	$lineas .= "\t<hr>\n";
	$lineas .= "\t<?php endif ?>\n";
	$lineas .= "\t<div class=\"row\">\n";
	$lineas .= "\t\t<div class=\"col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3\">\n";
	$lineas .= "\t\t\t<form method=\"post\" action=\"?/$tabla_plural/guardar\" autocomplete=\"off\">\n";
	$lineas .= "\t\t\t\t<input type=\"hidden\" name=\"<?= \$csrf; ?>\">\n";
	$lineas .= formar_campos_modificar($tabla_singular, $elementos);
	$lineas .= "\t\t\t\t<div class=\"form-group\">\n";
	$lineas .= "\t\t\t\t\t<button type=\"submit\" class=\"btn btn-danger\">\n";
	$lineas .= "\t\t\t\t\t\t<span class=\"glyphicon glyphicon-floppy-disk\"></span>\n";
	$lineas .= "\t\t\t\t\t\t<span>Guardar</span>\n";
	$lineas .= "\t\t\t\t\t</button>\n";
	$lineas .= "\t\t\t\t\t<button type=\"reset\" class=\"btn btn-default\">\n";
	$lineas .= "\t\t\t\t\t\t<span class=\"glyphicon glyphicon-refresh\"></span>\n";
	$lineas .= "\t\t\t\t\t\t<span>Restablecer</span>\n";
	$lineas .= "\t\t\t\t\t</button>\n";
	$lineas .= "\t\t\t\t</div>\n";
	$lineas .= "\t\t\t</form>\n";
	$lineas .= "\t\t</div>\n";
	$lineas .= "\t</div>\n";
	$lineas .= "</div>\n";
	$lineas .= "<script src=\"<?= js; ?>/jquery.form-validator.min.js\"></script>\n";
	$lineas .= "<script src=\"<?= js; ?>/jquery.form-validator.es.js\"></script>\n";
	$lineas .= formar_scripts($elementos);
	$lineas .= "<script>\n";
	$lineas .= "\$(function () {\n";
	$lineas .= "\t\$.validate({\n";
	$lineas .= "\t\tmodules: 'basic'\n";
	$lineas .= "\t});\n";
	$lineas .= formar_procesos($elementos);
	$lineas .= "\t\n";
	$lineas .= "\t<?php if (\$permiso_crear) : ?>\n";
	$lineas .= "\t\$(window).bind('keydown', function (e) {\n";
	$lineas .= "\t\tif (e.altKey || e.metaKey) {\n";
	$lineas .= "\t\t\tswitch (String.fromCharCode(e.which).toLowerCase()) {\n";
	$lineas .= "\t\t\t\tcase 'n':\n";
	$lineas .= "\t\t\t\t\te.preventDefault();\n";
	$lineas .= "\t\t\t\t\twindow.location = '?/$tabla_plural/crear';\n";
	$lineas .= "\t\t\t\tbreak;\n";
	$lineas .= "\t\t\t}\n";
	$lineas .= "\t\t}\n";
	$lineas .= "\t});\n";
	$lineas .= "\t<?php endif ?>\n";
	$lineas .= "\t\n";
	$lineas .= "\t<?php if (\$permiso_eliminar) : ?>\n";
	$lineas .= "\t\$('[data-eliminar]').on('click', function (e) {\n";
	$lineas .= "\t\te.preventDefault();\n";
	$lineas .= "\t\tvar href = \$(this).attr('href');\n";
	$lineas .= "\t\tvar csrf = '<?= \$csrf; ?>';\n";
	$lineas .= "\t\tbootbox.confirm('¿Está seguro que desea eliminar " . $generos['el'][$sintaxis] . " $alias_singular?', function (result) {\n";
	$lineas .= "\t\t\tif (result) {\n";
	$lineas .= "\t\t\t\t\$.request(href, csrf);\n";
	$lineas .= "\t\t\t}\n";
	$lineas .= "\t\t});\n";
	$lineas .= "\t});\n";
	$lineas .= "\t<?php endif ?>\n";
	$lineas .= "});\n";
	$lineas .= "</script>\n";
	$lineas .= "<?php require_once show_template('footer-full'); ?>";

	// Crea el archivo en el directorio
	$archivo = fopen($directorio . '/' . 'modificar.php', 'x');
	fwrite($archivo, $lineas);
	fclose($archivo);
}

function crear_archivo_guardar($directorio, $tabla, $tabla_plural, $tabla_singular, $alias_plural, $alias_singular, $sintaxis, $elementos, $generos) {
	// Genera el codigo php
	$lineas = "<?php\n";
	$lineas .= "\n";
	$lineas .= formar_comentarios();
	$lineas .= "// Verifica la peticion post\n";
	$lineas .= "if (is_post()) {\n";
	$lineas .= "\t// Verifica la cadena csrf\n";
	$lineas .= "\tif (isset(\$_POST[get_csrf()])) {\n";
	$lineas .= "\t\t// Verifica la existencia de datos\n";
	$lineas .= "\t\tif (" . formar_verificaciones($elementos) . ") {\n";
	$lineas .= "\t\t\t// Obtiene " . $generos['los'][$sintaxis] . " datos\n";
	$lineas .= formar_asignaciones($elementos);
	$lineas .= "\t\t\t\n";
	$lineas .= "\t\t\t// Instancia " . $generos['el'][$sintaxis] . " $tabla_singular\n";
	$lineas .= "\t\t\t\$$tabla_singular = array(\n";
	$lineas .= formar_atributos($elementos);
	$lineas .= "\t\t\t);\n";
	$lineas .= "\t\t\t\n";
	$lineas .= "\t\t\t// Verifica si es creacion o modificacion\n";
	$lineas .= "\t\t\tif (\$" . $elementos[0][0] . " > 0) {\n";
	$lineas .= "\t\t\t\t// Modifica " . $generos['el'][$sintaxis] . " $tabla_singular\n";
	$lineas .= "\t\t\t\t\$db->where('" . $elementos[0][0] . "', \$" . $elementos[0][0] . ")->update('$tabla', \$$tabla_singular);\n";
	$lineas .= "\t\t\t\t\n";
	$lineas .= "\t\t\t\t// Guarda el proceso\n";
	$lineas .= "\t\t\t\t\$db->insert('sys_procesos', array(\n";
	$lineas .= "\t\t\t\t\t'fecha_proceso' => date('Y-m-d'),\n";
	$lineas .= "\t\t\t\t\t'hora_proceso' => date('H:i:s'),\n";
	$lineas .= "\t\t\t\t\t'proceso' => 'u',\n";
	$lineas .= "\t\t\t\t\t'nivel' => 'l',\n";
	$lineas .= "\t\t\t\t\t'detalle' => 'Se modificó " . $generos['el'][$sintaxis] . " $alias_singular con identificador número ' . \$" . $elementos[0][0] . " . '.',\n";
	$lineas .= "\t\t\t\t\t'direccion' => \$_location,\n";
	$lineas .= "\t\t\t\t\t'usuario_id' => \$_user['id_user']\n";
	$lineas .= "\t\t\t\t));\n";
	$lineas .= "\t\t\t\t\n";
	$lineas .= "\t\t\t\t// Crea la notificacion\n";
	$lineas .= "\t\t\t\tset_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');\n";
	$lineas .= "\t\t\t\t\n";
	$lineas .= "\t\t\t\t// Redirecciona la pagina\n";
	$lineas .= "\t\t\t\tredirect('?/$tabla_plural/ver/' . \$" . $elementos[0][0] . ");\n";
	$lineas .= "\t\t\t} else {\n";
	$lineas .= "\t\t\t\t// Crea " . $generos['el'][$sintaxis] . " $tabla_singular\n";
	$lineas .= "\t\t\t\t\$" . $elementos[0][0] . " = \$db->insert('$tabla', \$$tabla_singular);\n";
	$lineas .= "\t\t\t\t\n";
	$lineas .= "\t\t\t\t// Guarda el proceso\n";
	$lineas .= "\t\t\t\t\$db->insert('sys_procesos', array(\n";
	$lineas .= "\t\t\t\t\t'fecha_proceso' => date('Y-m-d'),\n";
	$lineas .= "\t\t\t\t\t'hora_proceso' => date('H:i:s'),\n";
	$lineas .= "\t\t\t\t\t'proceso' => 'c',\n";
	$lineas .= "\t\t\t\t\t'nivel' => 'l',\n";
	$lineas .= "\t\t\t\t\t'detalle' => 'Se creó " . $generos['el'][$sintaxis] . " $alias_singular con identificador número ' . \$" . $elementos[0][0] . " . '.',\n";
	$lineas .= "\t\t\t\t\t'direccion' => \$_location,\n";
	$lineas .= "\t\t\t\t\t'usuario_id' => \$_user['id_user']\n";
	$lineas .= "\t\t\t\t));\n";
	$lineas .= "\t\t\t\t\n";
	$lineas .= "\t\t\t\t// Crea la notificacion\n";
	$lineas .= "\t\t\t\tset_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');\n";
	$lineas .= "\t\t\t\t\n";
	$lineas .= "\t\t\t\t// Redirecciona la pagina\n";
	$lineas .= "\t\t\t\tredirect('?/$tabla_plural/listar');\n";
	$lineas .= "\t\t\t}\n";
	$lineas .= "\t\t} else {\n";
	$lineas .= "\t\t\t// Error 400\n";
	$lineas .= "\t\t\trequire_once bad_request();\n";
	$lineas .= "\t\t\texit;\n";
	$lineas .= "\t\t}\n";
	$lineas .= "\t} else {\n";
	$lineas .= "\t\t// Redirecciona la pagina\n";
	$lineas .= "\t\tredirect('?/$tabla_plural/listar');\n";
	$lineas .= "\t}\n";
	$lineas .= "} else {\n";
	$lineas .= "\t// Error 404\n";
	$lineas .= "\trequire_once not_found();\n";
	$lineas .= "\texit;\n";
	$lineas .= "}\n";
	$lineas .= "\n";
	$lineas .= "?>";

	// Crea el archivo en el directorio
	$archivo = fopen($directorio . '/' . 'guardar.php', 'x');
	fwrite($archivo, $lineas);
	fclose($archivo);
}

function crear_archivo_ver($directorio, $tabla, $tabla_plural, $tabla_singular, $alias_plural, $alias_singular, $sintaxis, $elementos, $generos) {
	// Genera el codigo php
	$lineas = "<?php\n";
	$lineas .= "\n";
	$lineas .= "// Obtiene los parametros\n";
	$lineas .= "\$" . $elementos[0][0] . " = (isset(\$_params[0])) ? \$_params[0] : 0;\n";
	$lineas .= "\n";
	$lineas .= "// Obtiene la cadena csrf\n";
	$lineas .= "\$csrf = set_csrf();\n";
	$lineas .= "\n";
	$lineas .= "// Obtiene " . $generos['el'][$sintaxis] . " $tabla_singular\n";
	$lineas .= formar_consulta($tabla, $tabla_singular, $elementos);
	$lineas .= "\n";
	$lineas .= "// Ejecuta un error 404 si no existe " . $generos['el'][$sintaxis] . " $tabla_singular\n";
	$lineas .= "if (!\$$tabla_singular) { require_once not_found(); exit; }\n";
	$lineas .= "\n";
	$lineas .= "// Obtiene los permisos\n";
	$lineas .= "\$permiso_listar = in_array('listar', \$_views);\n";
	$lineas .= "\$permiso_crear = in_array('crear', \$_views);\n";
	$lineas .= "\$permiso_modificar = in_array('modificar', \$_views);\n";
	$lineas .= "\$permiso_eliminar = in_array('eliminar', \$_views);\n";
	$lineas .= "\$permiso_imprimir = in_array('imprimir', \$_views);\n";
	$lineas .= "\n";
	$lineas .= "?>\n";
	$lineas .= "<?php require_once show_template('header-full'); ?>\n";
	$lineas .= "<div class=\"panel-heading\">\n";
	$lineas .= "\t<h3 class=\"panel-title\" data-header=\"true\">\n";
	$lineas .= "\t\t<span class=\"glyphicon glyphicon-option-vertical\"></span>\n";
	$lineas .= "\t\t<strong>Ver $alias_singular</strong>\n";
	$lineas .= "\t</h3>\n";
	$lineas .= "</div>\n";
	$lineas .= "<div class=\"panel-body\">\n";
	$lineas .= "\t<?php if (\$permiso_listar || \$permiso_crear || \$permiso_modificar || \$permiso_eliminar || \$permiso_imprimir) : ?>\n";
	$lineas .= "\t<div class=\"row\">\n";
	$lineas .= "\t\t<div class=\"col-xs-6\">\n";
	$lineas .= "\t\t\t<div class=\"text-label hidden-xs\">Seleccionar acción:</div>\n";
	$lineas .= "\t\t\t<div class=\"text-label visible-xs-block\">Acciones:</div>\n";
	$lineas .= "\t\t</div>\n";
	$lineas .= "\t\t<div class=\"col-xs-6 text-right\">\n";
	$lineas .= "\t\t\t<div class=\"btn-group\">\n";
	$lineas .= "\t\t\t\t<button type=\"button\" class=\"btn btn-danger dropdown-toggle\" data-toggle=\"dropdown\">\n";
	$lineas .= "\t\t\t\t\t<span class=\"glyphicon glyphicon-menu-hamburger\"></span>\n";
	$lineas .= "\t\t\t\t\t<span class=\"hidden-xs\">Acciones</span>\n";
	$lineas .= "\t\t\t\t</button>\n";
	$lineas .= "\t\t\t\t<ul class=\"dropdown-menu dropdown-menu-right\">\n";
	$lineas .= "\t\t\t\t\t<li class=\"dropdown-header visible-xs-block\">Seleccionar acción</li>\n";
	$lineas .= "\t\t\t\t\t<?php if (\$permiso_listar) : ?>\n";
	$lineas .= "\t\t\t\t\t<li><a href=\"?/$tabla_plural/listar\"><span class=\"glyphicon glyphicon-list-alt\"></span> Listar $alias_plural</a></li>\n";
	$lineas .= "\t\t\t\t\t<?php endif ?>\n";
	$lineas .= "\t\t\t\t\t<?php if (\$permiso_crear) : ?>\n";
	$lineas .= "\t\t\t\t\t<li><a href=\"?/$tabla_plural/crear\"><span class=\"glyphicon glyphicon-plus\"></span> Crear $alias_singular</a></li>\n";
	$lineas .= "\t\t\t\t\t<?php endif ?>\n";
	$lineas .= "\t\t\t\t\t<?php if (\$permiso_modificar) : ?>\n";
	$lineas .= "\t\t\t\t\t<li><a href=\"?/$tabla_plural/modificar/<?= \$" . $elementos[0][0] . "; ?>\"><span class=\"glyphicon glyphicon-edit\"></span> Modificar $alias_singular</a></li>\n";
	$lineas .= "\t\t\t\t\t<?php endif ?>\n";
	$lineas .= "\t\t\t\t\t<?php if (\$permiso_eliminar) : ?>\n";
	$lineas .= "\t\t\t\t\t<li><a href=\"?/$tabla_plural/eliminar/<?= \$" . $elementos[0][0] . "; ?>\" data-eliminar=\"true\"><span class=\"glyphicon glyphicon-trash\"></span> Eliminar $alias_singular</a></li>\n";
	$lineas .= "\t\t\t\t\t<?php endif ?>\n";
	$lineas .= "\t\t\t\t\t<?php if (\$permiso_imprimir) : ?>\n";
	$lineas .= "\t\t\t\t\t<li><a href=\"?/$tabla_plural/imprimir/<?= \$" . $elementos[0][0] . "; ?>\" target=\"_blank\"><span class=\"glyphicon glyphicon-print\"></span> Imprimir $alias_singular</a></li>\n";
	$lineas .= "\t\t\t\t\t<?php endif ?>\n";
	$lineas .= "\t\t\t\t</ul>\n";
	$lineas .= "\t\t\t</div>\n";
	$lineas .= "\t\t</div>\n";
	$lineas .= "\t</div>\n";
	$lineas .= "\t<hr>\n";
	$lineas .= "\t<?php endif ?>\n";
	$lineas .= "\t<?php if (\$message = get_notification()) : ?>\n";
	$lineas .= "\t<div class=\"alert alert-<?= \$message['type']; ?>\">\n";
	$lineas .= "\t\t<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>\n";
	$lineas .= "\t\t<strong><?= \$message['title']; ?></strong>\n";
	$lineas .= "\t\t<p><?= \$message['content']; ?></p>\n";
	$lineas .= "\t</div>\n";
	$lineas .= "\t<?php endif ?>\n";
	$lineas .= "\t<div class=\"row\">\n";
	$lineas .= "\t\t<div class=\"col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3\">\n";
	$lineas .= "\t\t\t<div class=\"margin-bottom\">\n";
	$lineas .= formar_campos_ver($tabla_singular, $elementos);
	$lineas .= "\t\t\t</div>\n";
	$lineas .= "\t\t</div>\n";
	$lineas .= "\t</div>\n";
	$lineas .= "</div>\n";
	$lineas .= "<script>\n";
	$lineas .= "\$(function () {\n";
	$lineas .= "\t<?php if (\$permiso_crear) : ?>\n";
	$lineas .= "\t\$(window).bind('keydown', function (e) {\n";
	$lineas .= "\t\tif (e.altKey || e.metaKey) {\n";
	$lineas .= "\t\t\tswitch (String.fromCharCode(e.which).toLowerCase()) {\n";
	$lineas .= "\t\t\t\tcase 'n':\n";
	$lineas .= "\t\t\t\t\te.preventDefault();\n";
	$lineas .= "\t\t\t\t\twindow.location = '?/$tabla_plural/crear';\n";
	$lineas .= "\t\t\t\tbreak;\n";
	$lineas .= "\t\t\t}\n";
	$lineas .= "\t\t}\n";
	$lineas .= "\t});\n";
	$lineas .= "\t<?php endif ?>\n";
	$lineas .= "\t\n";
	$lineas .= "\t<?php if (\$permiso_eliminar) : ?>\n";
	$lineas .= "\t\$('[data-eliminar]').on('click', function (e) {\n";
	$lineas .= "\t\te.preventDefault();\n";
	$lineas .= "\t\tvar href = $(this).attr('href');\n";
	$lineas .= "\t\tvar csrf = '<?= \$csrf; ?>';\n";
	$lineas .= "\t\tbootbox.confirm('¿Está seguro que desea eliminar " . $generos['el'][$sintaxis] . " $alias_singular?', function (result) {\n";
	$lineas .= "\t\t\tif (result) {\n";
	$lineas .= "\t\t\t\t\$.request(href, csrf);\n";
	$lineas .= "\t\t\t}\n";
	$lineas .= "\t\t});\n";
	$lineas .= "\t});\n";
	$lineas .= "\t<?php endif ?>\n";
	$lineas .= "});\n";
	$lineas .= "</script>\n";
	$lineas .= "<?php require_once show_template('footer-full'); ?>";

	// Crea el archivo en el directorio
	$archivo = fopen($directorio . '/' . 'ver.php', 'x');
	fwrite($archivo, $lineas);
	fclose($archivo);
}

function crear_archivo_eliminar($directorio, $tabla, $tabla_plural, $tabla_singular, $alias_plural, $alias_singular, $sintaxis, $elementos, $generos) {
	// Genera el codigo php
	$lineas = "<?php\n";
	$lineas .= "\n";
	$lineas .= formar_comentarios();
	$lineas .= "// Verifica la peticion post\n";
	$lineas .= "if (is_post()) {\n";
	$lineas .= "\t// Verifica la cadena csrf\n";
	$lineas .= "\tif (isset(\$_POST[get_csrf()])) {\n";
	$lineas .= "\t\t// Obtiene los parametros\n";
	$lineas .= "\t\t\$" . $elementos[0][0] . " = (isset(\$_params[0])) ? \$_params[0] : 0;\n";
	$lineas .= "\t\t\n";
	$lineas .= "\t\t// Obtiene " . $generos['el'][$sintaxis] . " $tabla_singular\n";
	$lineas .= "\t\t\$$tabla_singular = \$db->from('$tabla')->where('" . $elementos[0][0] . "', \$" . $elementos[0][0] . ")->fetch_first();\n";
	$lineas .= "\t\t\n";
	$lineas .= "\t\t// Verifica si existe " . $generos['el'][$sintaxis] . " $tabla_singular\n";
	$lineas .= "\t\tif (\$$tabla_singular) {\n";
	$lineas .= "\t\t\t// Elimina " . $generos['el'][$sintaxis] . " $tabla_singular\n";
	$lineas .= "\t\t\t\$db->delete()->from('$tabla')->where('" . $elementos[0][0] . "', \$" . $elementos[0][0] . ")->limit(1)->execute();\n";
	$lineas .= "\t\t\t\n";
	$lineas .= "\t\t\t// Verifica la eliminacion\n";
	$lineas .= "\t\t\tif (\$db->affected_rows) {\n";
	$lineas .= "\t\t\t\t// Guarda el proceso\n";
	$lineas .= "\t\t\t\t\$db->insert('sys_procesos', array(\n";
	$lineas .= "\t\t\t\t\t'fecha_proceso' => date('Y-m-d'),\n";
	$lineas .= "\t\t\t\t\t'hora_proceso' => date('H:i:s'),\n";
	$lineas .= "\t\t\t\t\t'proceso' => 'd',\n";
	$lineas .= "\t\t\t\t\t'nivel' => 'm',\n";
	$lineas .= "\t\t\t\t\t'detalle' => 'Se eliminó " . $generos['el'][$sintaxis] . " $alias_singular con identificador número ' . \$" . $elementos[0][0] . " . '.',\n";
	$lineas .= "\t\t\t\t\t'direccion' => \$_location,\n";
	$lineas .= "\t\t\t\t\t'usuario_id' => \$_user['id_user']\n";
	$lineas .= "\t\t\t\t));\n";
	$lineas .= "\t\t\t\t\n";
	$lineas .= "\t\t\t\t// Crea la notificacion\n";
	$lineas .= "\t\t\t\tset_notification('success', 'Eliminación exitosa!', 'El registro se eliminó satisfactoriamente.');\n";
	$lineas .= "\t\t\t} else {\n";
	$lineas .= "\t\t\t\t// Crea la notificacion\n";
	$lineas .= "\t\t\t\tset_notification('danger', 'Eliminación fallida!', 'El registro no pudo ser eliminado.');\n";
	$lineas .= "\t\t\t}\n";
	$lineas .= "\t\t\t\n";
	$lineas .= "\t\t\t// Redirecciona la pagina\n";
	$lineas .= "\t\t\tredirect('?/$tabla_plural/listar');\n";
	$lineas .= "\t\t} else {\n";
	$lineas .= "\t\t\t// Error 400\n";
	$lineas .= "\t\t\trequire_once bad_request();\n";
	$lineas .= "\t\t\texit;\n";
	$lineas .= "\t\t}\n";
	$lineas .= "\t} else {\n";
	$lineas .= "\t\t// Redirecciona la pagina\n";
	$lineas .= "\t\tredirect('?/$tabla_plural/listar');\n";
	$lineas .= "\t}\n";
	$lineas .= "} else {\n";
	$lineas .= "\t// Error 404\n";
	$lineas .= "\trequire_once not_found();\n";
	$lineas .= "\texit;\n";
	$lineas .= "}\n";
	$lineas .= "\n";
	$lineas .= "?>";

	// Crea el archivo en el directorio
	$archivo = fopen($directorio . '/' . 'eliminar.php', 'x');
	fwrite($archivo, $lineas);
	fclose($archivo);
}

function crear_archivo_imprimir($directorio, $tabla, $tabla_plural, $tabla_singular, $alias_plural, $alias_singular, $sintaxis, $elementos, $generos) {
	// Genera el codigo php
	$lineas = "<?php\n";
	$lineas .= "\n";
	$lineas .= "// Obtiene los parametros\n";
	$lineas .= "\$" . $elementos[0][0] . " = (isset(\$_params[0])) ? \$_params[0] : 0;\n";
	$lineas .= "\n";
	$lineas .= "// Obtiene los permisos\n";
	$lineas .= "\$permiso_listar = in_array('listar', \$_views);\n";
	$lineas .= "\$permiso_ver = in_array('ver', \$_views);\n";
	$lineas .= "\n";
	$lineas .= "// Verifica si existen los parametros\n";
	$lineas .= "if (\$" . $elementos[0][0] . " == 0) {\n";
	$lineas .= "\t// Obtiene " . $generos['los'][$sintaxis] . " $tabla_plural\n";
	$lineas .= formar_consultas($tabla, $tabla_plural, $elementos, "\t");
	$lineas .= "\n";
	$lineas .= "\t// Ejecuta un error 404 si no existe " . $generos['los'][$sintaxis] . " $tabla_plural\n";
	$lineas .= "\tif (!\$permiso_listar) { require_once not_found(); exit; }\n";
	$lineas .= "} else {\n";
	$lineas .= "\t// Obtiene " . $generos['el'][$sintaxis] . " $tabla_singular\n";
	$lineas .= formar_consulta($tabla, $tabla_singular, $elementos, "\t");
	$lineas .= "\t\n";
	$lineas .= "\t// Ejecuta un error 404 si no existe " . $generos['el'][$sintaxis] . " $tabla_singular\n";
	$lineas .= "\tif (!\$$tabla_singular || !\$permiso_ver) { require_once not_found(); exit; }\n";
	$lineas .= "}\n";
	$lineas .= "\n";
	$lineas .= "// Importa la libreria para generar el reporte\n";
	$lineas .= "require_once libraries . '/tcpdf-class/tcpdf.php';\n";
	$lineas .= "\n";
	$lineas .= "// Verifica si existen los parametros\n";
	$lineas .= "if (\$" . $elementos[0][0] . " == 0) {\n";
	$lineas .= "\t// Asigna la orientacion de la pagina\n";
	$lineas .= formar_orientacion_imprimir($elementos);
	$lineas .= "\n";
	$lineas .= "\t// Adiciona la pagina\n";
	$lineas .= "\t\$pdf->AddPage();\n";
	$lineas .= "\t\n";
	$lineas .= "\t// Establece la fuente del titulo\n";
	$lineas .= "\t\$pdf->SetFont(\$font_name_main, 'BU', \$font_size_main);\n";
	$lineas .= "\t\n";
	$lineas .= "\t// Define el titulo del documento\n";
	$lineas .= "\t\$pdf->Cell(0, 15, '" . upper($alias_plural) . "', 0, true, 'C', false, '', 0, false, 'T', 'M');\n";
	$lineas .= "\t\n";
	$lineas .= "\t// Salto de linea\n";
	$lineas .= "\t\$pdf->Ln(15);\n";
	$lineas .= "\t\n";
	$lineas .= "\t// Establece la fuente del contenido\n";
	$lineas .= "\t\$pdf->SetFont(\$font_name_data, '', \$font_size_data);\n";
	$lineas .= "\t\n";
	$lineas .= "\t// Define el contenido de la tabla\n";
	$lineas .= "\t\$body = '';\n";
	$lineas .= "\t\n";
	$lineas .= "\t// Construye la estructura del contenido de la tabla\n";
	$lineas .= "\tforeach (\$$tabla_plural as \$nro => \$$tabla_singular) {\n";
	$lineas .= formar_celdas_imprimir($tabla_plural, $tabla_singular, $elementos);
	$lineas .= "\t}\n";
	$lineas .= "\t\n";
	$lineas .= "\t// Verifica el contenido de la tabla\n";
	$lineas .= "\t\$body = (\$body == '') ? '<tr class=\"last\"><td colspan=\"" . sizeof($elementos) . "\">No existen $alias_plural " . $generos['registrados'][$sintaxis] . " en la base de datos.</td></tr>' : \$body;\n";
	$lineas .= "\t\n";
	$lineas .= "\t// Define el formato de la tabla\n";
	$lineas .= "\t\$tabla = \$style;\n";
	$lineas .= "\t\$tabla .= '<table cellpadding=\"5\">';\n";
	$lineas .= "\t\$tabla .= '<tr class=\"first last\">';\n";
	$lineas .= formar_cabeceras_imprimir($elementos);
	$lineas .= "\t\$tabla .= '</tr>';\n";
	$lineas .= "\t\$tabla .= \$body;\n";
	$lineas .= "\t\$tabla .= '</table>';\n";
	$lineas .= "\t\n";
	$lineas .= "\t// Imprime la tabla\n";
	$lineas .= "\t\$pdf->writeHTML(\$tabla, true, false, false, false, '');\n";
	$lineas .= "\t\n";
	$lineas .= "\t// Genera el nombre del archivo\n";
	$lineas .= "\t\$nombre = '" . lower($tabla_plural) . "_' . date('Y-m-d_H-i-s') . '.pdf';\n";
	$lineas .= "} else {\n";
	$lineas .= "\t// Asigna la orientacion de la pagina\n";
	$lineas .= "\t\$pdf->SetPageOrientation('P');\n";
	$lineas .= "\t\n";
	$lineas .= "\t// Adiciona la pagina\n";
	$lineas .= "\t\$pdf->AddPage();\n";
	$lineas .= "\t\n";
	$lineas .= "\t// Establece la fuente del titulo\n";
	$lineas .= "\t\$pdf->SetFont(\$font_name_main, 'BU', \$font_size_main);\n";
	$lineas .= "\t\n";
	$lineas .= "\t// Define el titulo del documento\n";
	$lineas .= "\t\$pdf->Cell(0, 15, '" . upper($alias_singular) . " # ' . \$" . $elementos[0][0] . ", 0, true, 'C', false, '', 0, false, 'T', 'M');\n";
	$lineas .= "\t\n";
	$lineas .= "\t// Salto de linea\n";
	$lineas .= "\t\$pdf->Ln(15);\n";
	$lineas .= "\t\n";
	$lineas .= "\t// Establece la fuente del contenido\n";
	$lineas .= "\t\$pdf->SetFont(\$font_name_data, '', \$font_size_data);\n";
	$lineas .= "\t\n";
	$lineas .= "\t// Define las variables\n";
	$lineas .= formar_variables_imprimir($tabla_singular, $elementos);
	$lineas .= "\t\n";
	$lineas .= "\t// Construye la estructura de la tabla\n";
	$lineas .= "\t\$tabla = \$style;\n";
	$lineas .= "\t\$tabla .= '<table cellpadding=\"5\">';\n";
	$lineas .= formar_campos_imprimir($tabla_singular, $elementos);
	$lineas .= "\t\$tabla .= '</table>';\n";
	$lineas .= "\t\n";
	$lineas .= "\t// Imprime la tabla\n";
	$lineas .= "\t\$pdf->writeHTML(\$tabla, true, false, false, false, '');\n";
	$lineas .= "\t\n";
	$lineas .= "\t// Genera el nombre del archivo\n";
	$lineas .= "\t\$nombre = '" . lower($tabla_singular) . "_' . \$" . $elementos[0][0] . " . '_' . date('Y-m-d_H-i-s') . '.pdf';\n";
	$lineas .= "}\n";
	$lineas .= "\n";
	$lineas .= "// Cierra y devuelve el fichero pdf\n";
	$lineas .= "\$pdf->Output(\$nombre, 'I');\n";
	$lineas .= "\n";
	$lineas .= "?>";

	// Crea el archivo en el directorio
	$archivo = fopen($directorio . '/' . 'imprimir.php', 'x');
	fwrite($archivo, $lineas);
	fclose($archivo);
}

function formar_consultas($tabla, $tabla_plural, $elementos, $tabulacion = "") {
	$letras = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t");
	$posicion = 0;
	$lineas = "";
	$seleccion = "";
	$lineas .= "->from('$tabla z')";
	foreach ($elementos as $indice => $elemento) {
		if ($elemento[4] == "select-table") {
			$lineas .= "->join('" . $elemento[5] . " " . $letras[$posicion] . "', 'z." . $elemento[0] . " = " . $letras[$posicion] . "." . $elemento[6] . "', 'left')";
			$seleccion .= ", " . $letras[$posicion] . "." . $elemento[7] . " as " . substr($elemento[0], 0, -3);
			$posicion = $posicion + 1;
		}
	}
	$lineas = "\$$tabla_plural = \$db->select('z.*" . $seleccion . "')" . $lineas;
	$lineas .= "->order_by('z." . $elementos[0][0] . "', 'asc')";
	$lineas .= "->fetch();\n";
	return $tabulacion . $lineas;
}

function formar_cabeceras($elementos, $tipo = true) {
	$lineas = "";
	foreach ($elementos as $key => $elemento) {
		if ($tipo) {
			$lineas .= "\t\t\t\t<th class=\"text-nowrap\">" . capitalize($elemento[1]) . "</th>\n";
		} else {
			if (trim($elemento[1]) == "#") {
				$lineas .= "\t\t\t\t<th class=\"text-nowrap text-middle\" data-datafilter-filter=\"false\">" . capitalize($elemento[1]) . "</th>\n";
			} else {
				$lineas .= "\t\t\t\t<th class=\"text-nowrap text-middle\">" . capitalize($elemento[1]) . "</th>\n";
			}
		}
	}
	return $lineas;
}

function formar_celdas($tabla_singular, $elementos) {
	$lineas = "";
	foreach ($elementos as $key => $elemento) {
		switch ($elemento[4]) {
			case "select-table":
				$lineas .= "\t\t\t\t<td class=\"text-nowrap\"><?= escape(\$$tabla_singular" . "['" . substr($elemento[0], 0, -3) . "']); ?></td>\n";
				break;
			case "text-datemask":
				if ($elemento[8] == "required") {
					$lineas .= "\t\t\t\t<td class=\"text-nowrap\"><?= date_decode(\$$tabla_singular" . "['" . $elemento[0] . "'], \$_format); ?></td>\n";
				} else {
					$lineas .= "\t\t\t\t<td class=\"text-nowrap\"><?= (\$$tabla_singular" . "['" . $elemento[0] . "'] != '0000-00-00') ? date_decode(\$$tabla_singular" . "['" . $elemento[0] . "'], \$_format) : ''; ?></td>\n";
				}
				break;
			case "text-datepicker":
				if ($elemento[8] == "required") {
					$lineas .= "\t\t\t\t<td class=\"text-nowrap\"><?= date_decode(\$$tabla_singular" . "['" . $elemento[0] . "'], \$_format); ?></td>\n";
				} else {
					$lineas .= "\t\t\t\t<td class=\"text-nowrap\"><?= (\$$tabla_singular" . "['" . $elemento[0] . "'] != '0000-00-00') ? date_decode(\$$tabla_singular" . "['" . $elemento[0] . "'], \$_format) : ''; ?></td>\n";
				}
				break;
			case "text-phone":
				$lineas .= "\t\t\t\t<td class=\"text-nowrap\"><?= escape(str_replace(',', ' / ', \$$tabla_singular" . "['" . $elemento[0] . "'])); ?></td>\n";
				break;
			case "textarea-phone":
				$lineas .= "\t\t\t\t<td class=\"text-nowrap\"><?= escape(str_replace(',', ' / ', \$$tabla_singular" . "['" . $elemento[0] . "'])); ?></td>\n";
				break;
			default:
				if ($key == 0) {
					$lineas .= "\t\t\t\t<th class=\"text-nowrap\"><?= \$nro + 1; ?></th>\n";
				} else {
					$lineas .= "\t\t\t\t<td class=\"text-nowrap\"><?= escape(\$$tabla_singular" . "['" . $elemento[0] . "']); ?></td>\n";
				}
				break;
		}
	}
	return $lineas;
}

function formar_campos_crear($tabla_singular, $elementos) {
	$lineas = "";
	foreach ($elementos as $key => $elemento) {
		if ($key != 0) {
			$lineas .= "\t\t\t\t<div class=\"form-group\">\n";
			$lineas .= "\t\t\t\t\t<label for=\"" . $elemento[0] . "\" class=\"control-label\">" . capitalize($elemento[1]) . ":</label>\n";
			switch ($elemento[4]) {
				case "radio-collection":
					$opciones = explode(",", $elemento[3]);
					foreach ($opciones as $nro => $opcion) {
						$opcion = substr($opcion, 1, -1);
						$lineas .= "\t\t\t\t\t<div class=\"radio\">\n";
						$lineas .= "\t\t\t\t\t\t<label>\n";
						$lineas .= "\t\t\t\t\t\t\t<input type=\"radio\" value=\"" . $opcion . "\" name=\"" . $elemento[0] . "\"" . (($nro == 0) ? " id=\"" . $elemento[0] . "\"" : "") . (($nro == 0) ? " checked=\"checked\"" : "") . (($key == 1 && $nro == 0) ? " autofocus=\"autofocus\"" : "") . ">\n";
						$lineas .= "\t\t\t\t\t\t\t<span>" . $opcion . "</span>\n";
						$lineas .= "\t\t\t\t\t\t</label>\n";
						$lineas .= "\t\t\t\t\t</div>\n";
					}
					break;
				case "select-collection":
					$lineas .= "\t\t\t\t\t<select name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required" : "") . "\"" . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					$lineas .= "\t\t\t\t\t\t<option value=\"\" selected=\"selected\">Seleccionar</option>\n";
					$opciones = explode(",", $elemento[3]);
					foreach ($opciones as $opcion) {
						$opcion = substr($opcion, 1, -1);
						$lineas .= "\t\t\t\t\t\t<option value=\"" . $opcion . "\">" . $opcion . "</option>\n";
					}
					$lineas .= "\t\t\t\t\t</select>\n";
					break;
				case "select-table":
					$lineas .= "\t\t\t\t\t<select name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required" : "") . "\"" . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					$lineas .= "\t\t\t\t\t\t<option value=\"\" selected=\"selected\">Seleccionar</option>\n";
					$lineas .= "\t\t\t\t\t\t<?php foreach (\$" . substr($elemento[5], 4) . " as \$elemento) : ?>\n";
					$lineas .= "\t\t\t\t\t\t<option value=\"<?= \$elemento['" . $elemento[6] . "']; ?>\"><?= escape(\$elemento['" . $elemento[7] . "']); ?></option>\n";
					$lineas .= "\t\t\t\t\t\t<?php endforeach ?>\n";
					$lineas .= "\t\t\t\t\t</select>\n";
					break;
				case "text-address":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "letternumber" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\"-+/.,:;@#&'()_\\n \"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-all":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required" : "") . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-alphanumeric":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "alphanumeric" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\"-_ \"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-datemask":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "date\" data-validation-format=\"<?= \$formato_textual; ?>\"" . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-datepicker":
					$lineas .= "\t\t\t\t\t<div class=\"row\">\n";
					$lineas .= "\t\t\t\t\t\t<div class=\"col-xs-12\">\n";
					$lineas .= "\t\t\t\t\t\t\t<input type=\"text\" value=\"\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "date\" data-validation-format=\"<?= \$formato_textual; ?>\"" . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					$lineas .= "\t\t\t\t\t\t</div>\n";
					$lineas .= "\t\t\t\t\t</div>\n";
					break;
				case "text-datetimemask":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "custom\" data-validation-regexp=\"^([12][0-9]{3})-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01]) (0[0-9]|1[0-9]|2[0123])\:([012345][0-9])\:([012345][0-9])\$\"" . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-email":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "email" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-float":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "number" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\"float\"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-letter":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "letter" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\" \"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-letternumber":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "letternumber" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\"-/.#() \"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-number":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "number" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-phone":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "alphanumeric" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\"-+,() \"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-regexp":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "custom" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-regexp=\"^([a-z]+)\$\"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-timemask":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "custom\" data-validation-regexp=\"^(0[0-9]|1[0-9]|2[0123])\:([012345][0-9])\:([012345][0-9])\$\"" . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-url":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "url" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-yearmask":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "custom\" data-validation-regexp=\"^([12][0-9]{3})\$\"" . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "textarea-all":
					$lineas .= "\t\t\t\t\t<textarea name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . (($elemento[8] == "required") ? " data-validation=\"required\"" : " data-validation-optional=\"true\"") . "></textarea>\n";
					break;
				case "textarea-address":
					$lineas .= "\t\t\t\t\t<textarea name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "letternumber" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\"-+/.,:;@#&'()_\\n \"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . "></textarea>\n";
					break;
				case "textarea-alphanumeric":
					$lineas .= "\t\t\t\t\t<textarea name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "alphanumeric" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\"-_\\n \"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . "></textarea>\n";
					break;
				case "textarea-letter":
					$lineas .= "\t\t\t\t\t<textarea name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "letter" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\"\\n \"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . "></textarea>\n";
					break;
				case "textarea-letternumber":
					$lineas .= "\t\t\t\t\t<textarea name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "letternumber" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\"-/.#()\\n \"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . "></textarea>\n";
					break;
				case "textarea-phone":
					$lineas .= "\t\t\t\t\t<textarea name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "alphanumeric" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\"-+,()\\n \"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . "></textarea>\n";
					break;
				default:
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"\">\n";
					break;
			}
			$lineas .= "\t\t\t\t</div>\n";
		}
	}
	return $lineas;
}

function formar_modelos($elementos) {
	$lineas = "";
	foreach ($elementos as $key => $elemento) {
		if ($elemento[4] == "select-table") {
			$lineas .= "\n";
			$lineas .= "// Obtiene el modelo " . substr($elemento[5], 4) . "\n";
			$lineas .= "\$" . substr($elemento[5], 4) . " = \$db->from('" . $elemento[5] . "')->order_by('" . $elemento[7] . "', 'asc')->fetch();\n";
		}
	}
	return $lineas;
}

function formar_scripts($elementos) {
	$lineas = "";
	$scripts = array();
	foreach ($elementos as $key => $elemento) {
		switch ($elemento[4]) {
			case "text-datemask":
				array_push($scripts, "jquery.maskedinput.min.js");
				break;
			case "text-datepicker":
				array_push($scripts, "bootstrap-datetimepicker.min.js");
				break;
			case "text-datetimemask":
				array_push($scripts, "jquery.maskedinput.min.js");
				break;
			case "text-timemask":
				array_push($scripts, "jquery.maskedinput.min.js");
				break;
			case "text-yearmask":
				array_push($scripts, "jquery.maskedinput.min.js");
				break;
		}
	}
	$scripts = array_unique($scripts);
	foreach ($scripts as $key => $script) {
		$lineas .= "<script src=\"<?= js; ?>/$script\"></script>\n";
	}
	return $lineas;
}

function formar_variables($elementos) {
	$lineas = "";
	$variables = array();
	foreach ($elementos as $key => $elemento) {
		switch ($elemento[4]) {
			case "text-datemask":
				array_push($variables, "1");
				break;
			case "text-datepicker":
				array_push($variables, "1");
				break;
			case "text-datetimemask":
				array_push($variables, "1");
				break;
			case "text-timemask":
				array_push($variables, "1");
				break;
			case "text-yearmask":
				array_push($variables, "1");
				break;
		}
	}
	$variables = array_unique($variables);
	foreach ($variables as $key => $variable) {
		switch ($variable) {
			case "1":
				$lineas .= "\n";
				$lineas .= "// Obtiene los formatos\n";
				$lineas .= "\$formato_textual = get_date_textual(\$_format);\n";
				$lineas .= "\$formato_numeral = get_date_numeral(\$_format);\n";
				break;
		}
	}
	return $lineas;
}

function formar_procesos($elementos) {
	$lineas = "";
	foreach ($elementos as $key => $elemento) {
		switch ($elemento[4]) {
			case "text-datemask":
				$lineas .= "\n";
				$lineas .= "\t\$('#" . $elemento[0] . "').mask('<?= \$formato_numeral; ?>');\n";
				break;
			case "text-datepicker":
				$lineas .= "\n";
				$lineas .= "\t\$('#" . $elemento[0] . "').datetimepicker({\n";
				$lineas .= "\t\tformat: '<?= strtoupper(\$formato_textual); ?>'\n";
				$lineas .= "\t});\n";
				break;
			case "text-datetimemask":
				$lineas .= "\n";
				$lineas .= "\t\$('#" . $elemento[0] . "').mask('9999-99-99 99:99:99');\n";
				break;
			case "text-timemask":
				$lineas .= "\n";
				$lineas .= "\t\$('#" . $elemento[0] . "').mask('99:99:99');\n";
				break;
			case "text-yearmask":
				$lineas .= "\n";
				$lineas .= "\t\$('#" . $elemento[0] . "').mask('9999');\n";
				break;
		}
	}
	return $lineas;
}

function formar_comentarios() {
	$lineas = "/**\n";
	$lineas .= " * FunctionPHP - Framework Functional PHP\n";
	$lineas .= " * \n";
	$lineas .= " * @package  FunctionPHP\n";
	$lineas .= " * @author   Wilfredo Nina <wilnicho@hotmail.com>\n";
	$lineas .= " */\n";
	$lineas .= "\n";
	return $lineas;
}

function formar_verificaciones($elementos) {
	$lineas = "";
	foreach ($elementos as $key => $elemento) {
		if ($key != 0) {
			$lineas .= "isset(\$_POST['" . $elemento[0] . "']) && ";
		}
	}
	$lineas = substr($lineas, 0, -4);
	return $lineas;
}

function formar_asignaciones($elementos) {
	$lineas = "";
	foreach ($elementos as $key => $elemento) {
		if ($key == 0) {
			$lineas .= "\t\t\t\$" . $elemento[0] . " = (isset(\$_POST['" . $elemento[0] . "'])) ? clear(\$_POST['" . $elemento[0] . "']) : 0;\n";
		} else {
			$lineas .= "\t\t\t\$" . $elemento[0] . " = clear(\$_POST['" . $elemento[0] . "']);\n";
		}
	}
	return $lineas;
}

function formar_atributos($elementos) {
	$lineas = "";
	foreach ($elementos as $key => $elemento) {
		if ($key != 0) {
			switch ($elemento[4]) {
				case "text-datemask":
					$lineas .= "\t\t\t\t'" . $elemento[0] . "' => date_encode(\$" . $elemento[0] . "),\n";
					break;
				case "text-datepicker":
					$lineas .= "\t\t\t\t'" . $elemento[0] . "' => date_encode(\$" . $elemento[0] . "),\n";
					break;
				default:
					$lineas .= "\t\t\t\t'" . $elemento[0] . "' => \$" . $elemento[0] . ",\n";
					break;
			}
		}
	}
	$lineas = substr($lineas, 0, -2) . "\n";
	return $lineas;
}

function formar_consulta($tabla, $tabla_singular, $elementos, $tabulacion = "") {
	$letras = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t");
	$posicion = 0;
	$lineas = "";
	$seleccion = "";
	$lineas .= "->from('$tabla z')";
	foreach ($elementos as $indice => $elemento) {
		if ($elemento[4] == "select-table") {
			$lineas .= "->join('" . $elemento[5] . " " . $letras[$posicion] . "', 'z." . $elemento[0] . " = " . $letras[$posicion] . "." . $elemento[6] . "', 'left')";
			$seleccion .= ", " . $letras[$posicion] . "." . $elemento[7] . " as " . substr($elemento[0], 0, -3);
			$posicion = $posicion + 1;
		}
	}
	$lineas = "\$$tabla_singular = \$db->select('z.*" . $seleccion . "')" . $lineas;
	$lineas .= "->where('z." . $elementos[0][0] . "', \$" . $elementos[0][0] . ")";
	$lineas .= "->fetch_first();\n";
	return $tabulacion . $lineas;
}

function formar_campos_ver($tabla_singular, $elementos) {
	$lineas = "";
	foreach ($elementos as $key => $elemento) {
		if ($key != 0) {
			$lineas .= "\t\t\t\t<div class=\"form-group\">\n";
			$lineas .= "\t\t\t\t\t<label class=\"control-label\">" . capitalize($elemento[1]) . ":</label>\n";
			switch ($elemento[4]) {
				case "select-table":
					$lineas .= "\t\t\t\t\t<p class=\"form-control-static\"><?= escape(\$$tabla_singular" . "['" . substr($elemento[0], 0, -3) . "']); ?></p>\n";
					break;
				case "text-datemask":
					if ($elemento[8] == "required") {
						$lineas .= "\t\t\t\t\t<p class=\"form-control-static\"><?= date_decode(\$$tabla_singular" . "['" . $elemento[0] . "'], \$_format); ?></p>\n";
					} else {
						$lineas .= "\t\t\t\t\t<p class=\"form-control-static\"><?= (\$$tabla_singular" . "['" . $elemento[0] . "'] != '0000-00-00') ? date_decode(\$$tabla_singular" . "['" . $elemento[0] . "'], \$_format) : 'No asignado'; ?></p>\n";
					}
					break;
				case "text-datepicker":
					if ($elemento[8] == "required") {
						$lineas .= "\t\t\t\t\t<p class=\"form-control-static\"><?= date_decode(\$$tabla_singular" . "['" . $elemento[0] . "'], \$_format); ?></p>\n";
					} else {
						$lineas .= "\t\t\t\t\t<p class=\"form-control-static\"><?= (\$$tabla_singular" . "['" . $elemento[0] . "'] != '0000-00-00') ? date_decode(\$$tabla_singular" . "['" . $elemento[0] . "'], \$_format) : 'No asignado'; ?></p>\n";
					}
					break;
				case "text-phone":
					if ($elemento[8] == "required") {
						$lineas .= "\t\t\t\t\t<p class=\"form-control-static\"><?= escape(str_replace(',', ' / ', \$$tabla_singular" . "['" . $elemento[0] . "'])); ?></p>\n";
					} else {
						$lineas .= "\t\t\t\t\t<p class=\"form-control-static\"><?= (\$$tabla_singular" . "['" . $elemento[0] . "'] != '') ? escape(str_replace(',', ' / ', \$$tabla_singular" . "['" . $elemento[0] . "'])) : 'No asignado'; ?></p>\n";
					}
					break;
				case "textarea-phone":
					if ($elemento[8] == "required") {
						$lineas .= "\t\t\t\t\t<p class=\"form-control-static\"><?= escape(str_replace(',', ' / ', \$$tabla_singular" . "['" . $elemento[0] . "'])); ?></p>\n";
					} else {
						$lineas .= "\t\t\t\t\t<p class=\"form-control-static\"><?= (\$$tabla_singular" . "['" . $elemento[0] . "'] != '') ? escape(str_replace(',', ' / ', \$$tabla_singular" . "['" . $elemento[0] . "'])) : 'No asignado'; ?></p>\n";
					}
					break;
				default:
					if ($elemento[8] == "required") {
						$lineas .= "\t\t\t\t\t<p class=\"form-control-static\"><?= escape(\$$tabla_singular" . "['" . $elemento[0] . "']); ?></p>\n";
					} else {
						$lineas .= "\t\t\t\t\t<p class=\"form-control-static\"><?= (\$$tabla_singular" . "['" . $elemento[0] . "'] != '') ? escape(\$$tabla_singular" . "['" . $elemento[0] . "']) : 'No asignado'; ?></p>\n";
					}
					break;
			}

			$lineas .= "\t\t\t\t</div>\n";
		}
	}
	return $lineas;
}

function formar_campos_modificar($tabla_singular, $elementos) {
	$lineas = "";
	foreach ($elementos as $key => $elemento) {
		if ($key != 0) {
			$lineas .= "\t\t\t\t<div class=\"form-group\">\n";
			$lineas .= "\t\t\t\t\t<label for=\"" . $elemento[0] . "\" class=\"control-label\">" . capitalize($elemento[1]) . ":</label>\n";
			switch ($elemento[4]) {
				case "radio-collection":
					$opciones = explode(",", $elemento[3]);
					foreach ($opciones as $nro => $opcion) {
						$opcion = substr($opcion, 1, -1);
						$lineas .= "\t\t\t\t\t<div class=\"radio\">\n";
						$lineas .= "\t\t\t\t\t\t<label>\n";
						$lineas .= "\t\t\t\t\t\t\t<input type=\"radio\" value=\"" . $opcion . "\" name=\"" . $elemento[0] . "\"<?= (\$$tabla_singular" . "['" . $elemento[0] . "'] == '" . $opcion . "') ? ' checked=\"checked\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . "' : ''; ?>>\n";
						$lineas .= "\t\t\t\t\t\t\t<span>" . $opcion . "</span>\n";
						$lineas .= "\t\t\t\t\t\t</label>\n";
						$lineas .= "\t\t\t\t\t</div>\n";
					}
					break;
				case "select-collection":
					$lineas .= "\t\t\t\t\t<select name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required" : "") . "\"" . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					$lineas .= "\t\t\t\t\t\t<option value=\"\">Seleccionar</option>\n";
					$opciones = explode(",", $elemento[3]);
					foreach ($opciones as $opcion) {
						$opcion = substr($opcion, 1, -1);
						$lineas .= "\t\t\t\t\t\t<option value=\"" . $opcion . "\"<?= (\$$tabla_singular" . "['" . $elemento[0] . "'] == '" . $opcion . "') ? ' selected=\"selected\"' : ''; ?>>" . $opcion . "</option>\n";
					}
					$lineas .= "\t\t\t\t\t</select>\n";
					break;
				case "select-table":
					$lineas .= "\t\t\t\t\t<select name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required" : "") . "\"" . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					$lineas .= "\t\t\t\t\t\t<option value=\"\">Seleccionar</option>\n";
					$lineas .= "\t\t\t\t\t\t<?php foreach (\$" . substr($elemento[5], 4) . " as \$elemento) : ?>\n";
					$lineas .= "\t\t\t\t\t\t<?php if (\$elemento['" . $elemento[6] . "'] == \$" . $tabla_singular . "['" . $elemento[0] . "']) : ?>\n";
					$lineas .= "\t\t\t\t\t\t<option value=\"<?= \$elemento['" . $elemento[6] . "']; ?>\" selected=\"selected\"><?= escape(\$elemento['" . $elemento[7] . "']); ?></option>\n";
					$lineas .= "\t\t\t\t\t\t<?php else : ?>\n";
					$lineas .= "\t\t\t\t\t\t<option value=\"<?= \$elemento['" . $elemento[6] . "']; ?>\"><?= escape(\$elemento['" . $elemento[7] . "']); ?></option>\n";
					$lineas .= "\t\t\t\t\t\t<?php endif ?>\n";
					$lineas .= "\t\t\t\t\t\t<?php endforeach ?>\n";
					$lineas .= "\t\t\t\t\t</select>\n";
					break;
				case "text-address":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"<?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?>\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "letternumber" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\"-+/.,:;@#&'()_\\n \"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-all":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"<?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?>\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . (($elemento[8] == "required") ? " data-validation=\"required\"" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-alphanumeric":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"<?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?>\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "alphanumeric" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\"-_ \"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-datemask":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"<?= date_decode(\$$tabla_singular" . "['" . $elemento[0] . "'], \$_format); ?>\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "date\" data-validation-format=\"<?= \$formato_textual; ?>\"" . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-datepicker":
					$lineas .= "\t\t\t\t\t<div class=\"row\">\n";
					$lineas .= "\t\t\t\t\t\t<div class=\"col-xs-12\">\n";
					$lineas .= "\t\t\t\t\t\t\t<input type=\"text\" value=\"<?= date_decode(\$$tabla_singular" . "['" . $elemento[0] . "'], \$_format); ?>\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "date\" data-validation-format=\"<?= \$formato_textual; ?>\"" . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					$lineas .= "\t\t\t\t\t\t</div>\n";
					$lineas .= "\t\t\t\t\t</div>\n";
					break;
				case "text-datetimemask":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"<?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?>\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "custom\" data-validation-regexp=\"^([12][0-9]{3})-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01]) (0[0-9]|1[0-9]|2[0123])\:([012345][0-9])\:([012345][0-9])\$\"" . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-email":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"<?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?>\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "email" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-float":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"<?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?>\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "number" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\"float\"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-letter":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"<?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?>\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "letter" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\" \"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-letternumber":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"<?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?>\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "letternumber" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\"-/.#() \"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-number":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"<?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?>\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "number" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-phone":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"<?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?>\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "alphanumeric" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\"-+,() \"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-regexp":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"<?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?>\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "custom" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-regexp=\"^([a-z]+)\$\"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-timemask":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"<?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?>\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "custom\" data-validation-regexp=\"^(0[0-9]|1[0-9]|2[0123])\:([012345][0-9])\:([012345][0-9])\$\"" . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-url":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"<?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?>\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "url" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "text-yearmask":
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"<?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?>\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "custom\" data-validation-regexp=\"^([12][0-9]{3})\$\"" . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . ">\n";
					break;
				case "textarea-all":
					$lineas .= "\t\t\t\t\t<textarea name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . (($elemento[8] == "required") ? " data-validation=\"required\"" : " data-validation-optional=\"true\"") . "><?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?></textarea>\n";
					break;
				case "textarea-address":
					$lineas .= "\t\t\t\t\t<textarea name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "letternumber" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\"-+/.,:;@#&'()_\\n \"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . "><?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?></textarea>\n";
					break;
				case "textarea-alphanumeric":
					$lineas .= "\t\t\t\t\t<textarea name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "alphanumeric" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\"-_\\n \"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . "><?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?></textarea>\n";
					break;
				case "textarea-letter":
					$lineas .= "\t\t\t\t\t<textarea name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "letter" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\"\\n \"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . "><?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?></textarea>\n";
					break;
				case "textarea-letternumber":
					$lineas .= "\t\t\t\t\t<textarea name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "letternumber" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\"-/.#()\\n \"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . "><?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?></textarea>\n";
					break;
				case "textarea-phone":
					$lineas .= "\t\t\t\t\t<textarea name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"" . (($elemento[8] == "required") ? "required " : "") . "alphanumeric" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " length" : "") . "\" data-validation-allowing=\"-+,()\\n \"" . (($elemento[2] == "char" || $elemento[2] == "varchar") ? " data-validation-length=\"max" . $elemento[3] . "\"" : "") . (($elemento[8] == "required") ? "" : " data-validation-optional=\"true\"") . "><?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?></textarea>\n";
					break;
				default:
					$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"<?= \$$tabla_singular" . "['" . $elemento[0] . "']; ?>\" name=\"" . $elemento[0] . "\" id=\"" . $elemento[0] . "\" class=\"form-control\"" . (($key == 1) ? " autofocus=\"autofocus\"" : "") . " data-validation=\"\">\n";
					break;
			}
			if ($key == 1) {
				$lineas .= "\t\t\t\t\t<input type=\"text\" value=\"<?= \$" . $elementos[0][0] . "; ?>\" name=\"" . $elementos[0][0] . "\" id=\"" . $elementos[0][0] . "\" class=\"translate\" tabindex=\"-1\" data-validation=\"required number\" data-validation-error-msg=\"El campo no es válido\">\n";
			}
			$lineas .= "\t\t\t\t</div>\n";
		}
	}
	return $lineas;
}

function formar_orientacion_imprimir($elementos) {
	if (sizeof($elementos) <= 6) {
		return "\t\$pdf->SetPageOrientation('P');\n";
	} else {
		return "\t\$pdf->SetPageOrientation('L');\n";
	}
}

function formar_cabeceras_imprimir($elementos) {
	$total = 0;
	foreach ($elementos as $key => $elemento) {
		if ($key != 0) {
			$total += strlen($elemento[1]);
		}
	}
	$lineas = "";
	foreach ($elementos as $key => $elemento) {
		if ($key == 0) {
			$lineas .= "\t\$tabla .= '<th width=\"6%\">" . capitalize($elemento[1]) . "</th>';\n";
		} else {
			$tamano = strlen($elemento[1]);
			$ancho = number_format(($tamano * 94) / $total, 2);
			$lineas .= "\t\$tabla .= '<th width=\"" . $ancho . "%\">" . capitalize($elemento[1]) . "</th>';\n";
		}
	}
	return $lineas;
}

function formar_celdas_imprimir($tabla_plural, $tabla_singular, $elementos) {
	$lineas = "\t\t\$body .= '<tr class=\"' . ((\$nro % 2 == 0) ? 'even' : 'odd') . ((isset(\$$tabla_plural" . "[\$nro + 1])) ? '' : ' last') . '\">';\n";
	foreach ($elementos as $key => $elemento) {
		switch ($elemento[4]) {
			case "select-table":
				$lineas .= "\t\t\$body .= '<td>' . escape(\$$tabla_singular" . "['" . substr($elemento[0], 0, -3) . "']) . '</td>';\n";
				break;
			case "text-datemask":
				if ($elemento[8] == "required") {
					$lineas .= "\t\t\$body .= '<td>' . date_decode(\$$tabla_singular" . "['" . $elemento[0] . "'], \$_format) . '</td>';\n";
				} else {
					$lineas .= "\t\t\$body .= '<td>' . ((\$$tabla_singular" . "['" . $elemento[0] . "'] != '0000-00-00') ? date_decode(\$$tabla_singular" . "['" . $elemento[0] . "'], \$_format) : '') . '</td>';\n";
				}
				break;
			case "text-datepicker":
				if ($elemento[8] == "required") {
					$lineas .= "\t\t\$body .= '<td>' . date_decode(\$$tabla_singular" . "['" . $elemento[0] . "'], \$_format) . '</td>';\n";
				} else {
					$lineas .= "\t\t\$body .= '<td>' . ((\$$tabla_singular" . "['" . $elemento[0] . "'] != '0000-00-00') ? date_decode(\$$tabla_singular" . "['" . $elemento[0] . "'], \$_format) : '') . '</td>';\n";
				}
				break;
			case "text-phone":
				$lineas .= "\t\t\$body .= '<td>' . escape(str_replace(',', ' / ', \$$tabla_singular" . "['" . $elemento[0] . "'])) . '</td>';\n";
				break;
			case "textarea-phone":
				$lineas .= "\t\t\$body .= '<td>' . escape(str_replace(',', ' / ', \$$tabla_singular" . "['" . $elemento[0] . "'])) . '</td>';\n";
				break;
			default:
				if ($key == 0) {
					$lineas .= "\t\t\$body .= '<td>' . (\$nro + 1) . '</td>';\n";
				} else {
					$lineas .= "\t\t\$body .= '<td>' . escape(\$$tabla_singular" . "['" . $elemento[0] . "']) . '</td>';\n";
				}
				break;
		}
	}
	$lineas .= "\t\t\$body .= '</tr>';\n";
	return $lineas;
}

function formar_variables_imprimir($tabla_singular, $elementos) {
	$lineas = "";
	foreach ($elementos as $key => $elemento) {
		if ($key != 0) {
			switch ($elemento[4]) {
				case "select-table":
					$lineas .= "\t\$valor_" . $elemento[0] . " = escape(\$$tabla_singular" . "['" . substr($elemento[0], 0, -3) . "']);\n";
					break;
				case "text-datemask":
					$lineas .= "\t\$valor_" . $elemento[0] . " = date_decode(\$$tabla_singular" . "['" . $elemento[0] . "'], \$_format);\n";
					break;
				case "text-datepicker":
					if ($elemento[8] == "required") {
						$lineas .= "\t\$valor_" . $elemento[0] . " = date_decode(\$$tabla_singular" . "['" . $elemento[0] . "'], \$_format);\n";
					} else {
						$lineas .= "\t\$valor_" . $elemento[0] . " = (\$$tabla_singular" . "['" . $elemento[0] . "'] != '0000-00-00') ? date_decode(\$$tabla_singular" . "['" . $elemento[0] . "'], \$_format) : 'No asignado';\n";
					}
					break;
				case "text-phone":
					if ($elemento[8] == "required") {
						$lineas .= "\t\$valor_" . $elemento[0] . " = escape(str_replace(',', ' / ', \$$tabla_singular" . "['" . $elemento[0] . "']));\n";
					} else {
						$lineas .= "\t\$valor_" . $elemento[0] . " = (\$$tabla_singular" . "['" . $elemento[0] . "'] != '') ? escape(str_replace(',', ' / ', \$$tabla_singular" . "['" . $elemento[0] . "'])) : 'No asignado';\n";
					}
					break;
				case "textarea-phone":
					if ($elemento[8] == "required") {
						$lineas .= "\t\$valor_" . $elemento[0] . " = escape(str_replace(',', ' / ', \$$tabla_singular" . "['" . $elemento[0] . "']));\n";
					} else {
						$lineas .= "\t\$valor_" . $elemento[0] . " = (\$$tabla_singular" . "['" . $elemento[0] . "'] != '') ? escape(str_replace(',', ' / ', \$$tabla_singular" . "['" . $elemento[0] . "'])) : 'No asignado';\n";
					}
					break;
				default:
					if ($elemento[8] == "required") {
						$lineas .= "\t\$valor_" . $elemento[0] . " = escape(\$$tabla_singular" . "['" . $elemento[0] . "']);\n";
					} else {
						$lineas .= "\t\$valor_" . $elemento[0] . " = (\$$tabla_singular" . "['" . $elemento[0] . "'] != '') ? escape(\$$tabla_singular" . "['" . $elemento[0] . "']) : 'No asignado';\n";
					}
					break;
			}
		}
	}
	return $lineas;
}

function formar_campos_imprimir($tabla_singular, $elementos) {
	$lineas = "";
	foreach ($elementos as $key => $elemento) {
		$clase = "";
		if ($key != 0) {
			if ($key == 1) {
				if (isset($elementos[$key + 1])) {
					$clase = " class=\"first\"";
				} else {
					$clase = " class=\"first last\"";
				}
			} else {
				if (!isset($elementos[$key + 1])) {
					$clase = " class=\"last\"";
				}
			}
			$lineas .= "\t\$tabla .= '<tr" . $clase . "><th class=\"left\">" . capitalize($elemento[1]) . ":</th><td class=\"right\">' . \$valor_" . $elemento[0] . " . '</td></tr>';\n";
		}
	}
	return $lineas;
}

function eliminar_directorio($directorio) {
	$archivos = array_diff(scandir($directorio), array(".", ".."));
	foreach ($archivos as $archivo) {
		$archivo = $directorio . "/" . $archivo;
		(is_dir($archivo)) ? eliminar_directorio($archivo) : unlink($archivo);
	}
	return rmdir($directorio);
}

function clear_value($variable, $indice) {
	return (isset($variable[$indice])) ? $variable[$indice] : "";
}

?>