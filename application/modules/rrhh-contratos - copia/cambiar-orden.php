<?php 

//var_dump($_POST);
$array_cursos = $_POST['miorden'];

$orden = 1;
foreach($array_cursos as $id_area_calificacion){
	$resultado_cursos = "UPDATE cal_area_calificacion SET orden = $orden WHERE id_area_calificacion = $id_area_calificacion";
	$db->query($resultado_cursos)->execute();
	$orden++;
}
echo "<p><span style='color: green;'>La lista ha sido cambiada.</span></p>";

?>