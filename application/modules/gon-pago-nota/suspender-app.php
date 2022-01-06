<?php 

// Obtiene los parametros  cliente  tutor_id
$id_estudiante = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene los parametros 
$gestion=$_gestion['id_gestion'];  
 
// Obtiene la cadena csrf
$csrf = set_csrf();   

// Obtiene los estudiantes
$estudiante = $db->select('z.*')->from('vista_estudiantes z')->where('id_estudiante',$id_estudiante)->fetch_first();

// Obtiene nivel académico
$nivel = $db->select('z.*')->from('ins_nivel_academico z')->order_by('id_nivel_academico')->fetch();

// Obtiene roles
$roles = $db->select('z.*')->from('sys_roles z')->where('z.rol','Tutor')->or_where('z.rol','Estudiante')->order_by('z.rol')->fetch();

// Define el limite de filas
$limite_longitud = 200;

// Define el limite monetario
$limite_monetario = 10000000;
$limite_monetario = number_format($limite_monetario, 2, '.', '');  

// Obtiene los permisos  
$permiso_listar = in_array('listar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);
$permiso_crear_familiar = in_array('crear-familiar', $_views);

?>
<?php require_once show_template('header-design'); ?>
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">

<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/educheck.js"></script>
<script src="<?= js; ?>/jquery-ui-1.10.4.min.js"></script>
<script src="<?= js; ?>/bootstrap-notify.min.js"></script>
<script src="<?= js; ?>/bootbox.min.js"></script>

