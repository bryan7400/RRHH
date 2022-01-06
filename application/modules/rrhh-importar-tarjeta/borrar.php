<?php 
require_once libraries . '/PHPExcel/Classes/PHPExcel.php';
$carpetaAdjunta=files."/archivos/";

if(isset($_POST['key']) && isset($_POST['valor'])){
	$key = $_POST['key'];
	$valor = $_POST['valor'];
	$nro    = isset($_POST['nro_hoja']) ? $_POST['nro_hoja']-1:2;
	$archivo = $carpetaAdjunta.$key;
	
	if ($valor == "si") {
		$inputFileType = PHPExcel_IOFactory::identify($archivo);
		$objReader     = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel   = $objReader->load($archivo);
		$sheet         = $objPHPExcel->getSheet($nro);
		$fecha        = $sheet->getCell("G3")->getValue();
	    $fechas       = explode('~', $fecha);
	    $db->delete()
	    	->from('per_asistencias')
	    	->where('fecha_asistencia >=',$fechas[0])
	    	->where('fecha_asistencia <=',$fechas[1])
	    	->execute();

	}
	unlink($archivo);
	echo json_encode(['success'=>'Yes']);
	//redirect('?/empleados/importar_asistencia');
} else {
	echo json_encode(['error'=>'No se pudo eliminar']);
}

?>
