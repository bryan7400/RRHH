<?php 
require_once libraries . '/PHPExcel/Classes/PHPExcel.php';

// var_dump($_FILES);
// var_dump($_POST);
//exit();

// Obtiene el id de la gestion actual
$id_gestion   = $_gestion['id_gestion'];
$fecha_actual = Date('Y-m-d H:i:s');

if (!empty($_FILES['archivo']) && !empty($_POST['nro_hoja'])) {
	
	$ruta=explode("..",files);
	$archivo = $_SERVER["DOCUMENT_ROOT"].$ruta[1]."/archivos/". $_FILES["archivo"]["name"];
	
	$nro    = $_POST['nro_hoja']-1;
	
	if (move_uploaded_file($_FILES['archivo']['tmp_name'], $archivo)) {

		//echo "ingreso 1";

		$inputFileType = PHPExcel_IOFactory::identify($archivo);

		//echo "ingreso 2";

	    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	    //Cargando la hoja de calculo

		//echo "ingreso 3";

	    $objPHPExcel = $objReader->load($archivo);
	    //Asignar hoja de calculo activa

		//echo "ingreso 4";

	    $sheet = $objPHPExcel->setActiveSheetIndex($nro);

		//echo "ingreso 5";

	    $highestRow = $sheet->getHighestRow();

	    $list = ["A", "B", "C", "D","E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "AA", "AB", "AC", "AD", "AE","AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", "AN", "A0", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ"];

	    $columna      = 1;
		$i = 1;	
		$fecha_hora = "";
		$marcacion = array();
		
		//echo $sheet->getCell("B".$i)->getValue();
		while($sheet->getCell("B".$i)->getValue() != null){
			
			$codigo_empleado  = $sheet->getCell("A".$i)->getValue(); 
			//echo "<br>";
			
			$fecha_hora_excel = $sheet->getCell("B".$i)->getValue();
		
			$UNIX_DATE        = ($fecha_hora_excel - 25569) * 86400;
			//$fecha_hora       = gmdate("d-m-Y H:i:s", $UNIX_DATE);
			$fecha_hora       = gmdate("Y-m-d H:i:s", $UNIX_DATE);			
			$aFechaHora = explode(" ", $fecha_hora);
			$marcacion [$codigo_empleado][$aFechaHora[0]][] = $aFechaHora[1]; 
			$i++;

			$k = $aFechaHora[0];
			$h = $aFechaHora[0]." ".$aFechaHora[1];

			//Ya obtenido el Docente o empleado harmamos todos los horarios asignados **
			$empleado_asignacion = $db->query("	SELECT * 
												FROM per_asignaciones 
												WHERE codigo='$codigo_empleado' AND gestion_id = $id_gestion
												")->fetch_first();

			$asistencia_bd = $db->query("	SELECT * 
										FROM per_asistencias 
										WHERE salida = '0000-00-00 00:00:00'
										ORDER BY id_asistencia DESC
										")->fetch_first();

			if($asistencia_bd){
				$asistencia = array(
					'salida' => $h
				);			
				$db->where('id_asistencia', $asistencia_bd['id_asistencia'])->update('per_asistencias', $asistencia);			
			}else{
				$asistencia = array(
					'asignacion_id'    => $empleado_asignacion['id_asignacion'],
					'fecha_asistencia' => $k,
					'entrada'          => $h,
					'salida'           => '0000-00-00 00:00:00',
					'estado'           => 'p'
				);			
				$db->insert('per_asistencias', $asistencia);	
			}
		}
		echo json_encode(['uploaded' => $archivo]);
	} else {
		echo "error al mover el archivo";
	}
} else {
	echo json_encode(['error'=>'Introduzca número de hoja.']);
}

// Funcion para obtener el dia de una fecha cualquiera
function saber_dia($nombredia) {
	//$dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
	$dias = array('dom','lun','mar','mie','jue','vie','sab','dom');
	$fecha = $dias[date('N', strtotime($nombredia))];
	return  $fecha;
}
?>