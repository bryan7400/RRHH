<?php
 
// Obtiene los parametros
$id_xxx = (isset($_params[0])) ? $_params[0] : 0;
//var_dump($id_xxx);exit();

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php'; 

// Verifica si existen los parametros

	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');

	// Adiciona la pagina
	$pdf->AddPage(); 
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, '', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'POSTULACION', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', 8);
	


	$res = $db->query(" SELECT *
                  FROM per_postulacion
                  WHERE id_postulacion='".$id_xxx."'
                  ")->fetch_first();


    

    //foreach ($res as $rxx) { 
	//}


	// Define el contenido de la tabla
	$body = '';
	$body .= '<table cellpadding="">';		
	$body .= '<tr>';		
	$body .= '<th width="100%" align="left" border="0.5px" color="#ffffff" bgcolor="#888888"><br><br><b> A) DATOS PERSONALES</b><br></th>';
	$body .= '</tr>';
	$body .= '<tr>';
	$body .= '<th width="33%" align="left"><br><br><b>Apellidos y Nombres:</b> '.$res['paterno'].' '.$res['materno'].' '.$res['nombre'].'<br> </th>';
	// $body .= '<th width="33%" align="left"><br><br><b>Apellido Materno:</b> '.$res['materno'].'<br> </th>';
	// $body .= '<th width="34%" align="left"><br><br><b>Nombre:</b> '.$res['nombre'].'<br> </th>';
	$body .= '</tr>';
	
	$body .= '<tr>';
	$body .= '<th width="25%" align="left"><br><br><b>Lugar de nacimiento:</b> '.$res['nacionalidad'].'<br> </th>';
	$body .= '<th width="25%" align="left"><br><br><b>Localidad:</b> '.$res['localidad'].'<br> </th>';
	$body .= '<th width="25%" align="left"><br><br><b>Provincia:</b> '.$res['provincia'].'<br> </th>';
	$body .= '<th width="25%" align="left"><br><br><b>Departamento:</b> '.$res['departamento'].'<br> </th>';
	$body .= '</tr>';
	
	$rss=explode("-",$res['fecha_nacimiento']);
	//$rss2=$rss[2]."/".$rss[1]."/".$rss[0];

	$body .= '<tr>';
	$body .= '<th width="25%" align="left"><br><br><b>Fecha de Nacimiento:</b> <br> </th>';
	$body .= '<th width="25%" align="left"><br><br><b>Estado Civil:</b> '.$res['estado_civil'].'<br> </th>';
	$body .= '<th width="25%" align="left"><br><br><b>C.I.:</b> '.$res['ci'].'<br> </th>';
	$body .= '<th width="25%" align="left"><br><br><b>Expirado:</b> '.$res['expirado'].'<br> </th>';
	$body .= '</tr>';

	$body .= '<tr>';
	$body .= '<th width="25%" align="left"><br><br><b>Dirección C/Av.:</b> '.$res['direccion'].'<br> </th>';
	$body .= '<th width="25%" align="left"><br><br><b>Nº:</b> '.$res['nro_direccion'].'<br> </th>';
	$body .= '<th width="25%" align="left"><br><br><b>Zona:</b> '.$res['zona'].'<br> </th>';
	$body .= '<th width="25%" align="left"><br><br><b>Ciudad:</b> '.$res['ciudad'].'<br> </th>';
	$body .= '</tr>';

	$body .= '<tr>';
	$body .= '<th width="25%" align="left"><br><br><b>Teléfono:</b> '.$res['telefono'].'<br> </th>';
	$body .= '<th width="25%" align="left"><br><br><b>Celular:</b> '.$res['celular'].'<br> </th>';
	$body .= '<th width="25%" align="left"><br><br><b>Email:</b> '.$res['email'].'<br> </th>';
	// $body .= '<th width="25%" align="left"><br><br><b>Genero:</b> '.$res['departamento'].'<br> </th>';
	$body .= '</tr>';

	$body .= '<tr>';
	$body .= '<th width="50%" align="left"><br><br><b>AFP a la que aporta:</b> '.$res['afp'].'<br> </th>';
	$body .= '<th width="50%" align="left"><br><br><b>Número de NUA:</b> '.$res['nua'].'<br> </th>';
	$body .= '</tr>';

	$rss=explode("-",$res['fecha_nacimiento_c']);
	//$rss2=$rss[2]."/".$rss[1]."/".$rss[0];

	$body .= '<tr>';
	$body .= '<th width="50%" align="left"><br><br><b>Nombre completo del (la) cónyuge:</b> '.$res['conyuge'].'<br> </th>';
	$body .= '<th width="50%" align="left"><br><br><b>Fecha de Nacimiento:</b> <br> </th>';
	$body .= '</tr>';

	$body .= '</table>';

	$body .= '<br>';
	$body .= '<br>';

/********************************************************************/

	$body .= '<table cellpadding="">';
	$body .= '<tr>';
	$body .= '<th width="15%" align="CENTER" border="0.5px"><b><br> DEPENDIENTES<br></B></th>';
	$body .= '<th width="40%" align="CENTER" border="0.5px"><b><br> NOMBRES Y APELLIDOS<br></B></th>';
	$body .= '<th width="15%" align="center" border="0.5px"><b><br> FECHA DE NACIMIENTO<br></B></th>';
	$body .= '<th width="15%" align="center" border="0.5px"><b><br> GENERO<br></B></th>';
	$body .= '<th width="15%" align="CENTER" border="0.5px"><b><br> GRADO DE INSTRUCCION<br></B></th>';	
	$body .= '</tr>';

	$dep = $db->query(" SELECT *
                  FROM per_postulacion_dependiente
                  WHERE postulante_id='".$res['id_postulacion']."'
                  ")->fetch();

	$nrox=0;
    foreach ($dep as $rxx) { 
		$nrox++;
	
		$rss=explode("-",$rxx['fecha_nacimiento']);
		$rss2=$rss[2]."/".$rss[1]."/".$rss[0];
		
		if($rxx['genero']=="v"){
			$genero="Hombre";
		}
		else{
			$genero="Mujer";
		}
		
		$body .= '<tr>';
		$body .= '<th width="15%" align="CENTER" border="0.5px"><br><br> '.$nrox.'<br></th>';
		$body .= '<th width="40%" align="CENTER" border="0.5px"><br><br> '.$rxx['nombre'].'<br></th>';
		$body .= '<th width="15%" align="center" border="0.5px"><br><br> '.$rss2.'<br></th>';
		$body .= '<th width="15%" align="center" border="0.5px"><br><br> '.$genero.'<br></th>';
		$body .= '<th width="15%" align="CENTER" border="0.5px"><br><br> '.$rxx['grado'].'<br></th>';	
		$body .= '</tr>';
	}
	if($nrox==0){
		$body .= '<tr>';
		$body .= '<th width="100%" align="CENTER" border="0.5px"><br><br> No tiene dependientes<br></th>';
		$body .= '</tr>';
	}
	$body .= '</table>';

	$body .= '<br><br><br>';

	$body .= '<table>';
	$body .= '<tr>';
	$body .= '<th width="100%" align="left" border="0.5px" color="#ffffff" bgcolor="#888888"><br><br><b> B) DATOS DENOMINACINALES ( solo miembros de la IASD)</b><br></th>';
	$body .= '</tr>';
	$body .= '<tr>';
	$body .= '<th width="50%" align="left"><br><br><b>Fecha de bautismo:</b> '.$res['fecha_bautismo'].'<br> </th>';
	$body .= '<th width="50%" align="left"><br><br><b>Pastor oficiciante:</b> '.$res['pastor'].'<br> </th>';
	$body .= '</tr>';
	$body .= '<tr>';
	$body .= '<th width="50%" align="left"><br><br><b>Iglesia/congrg./filial a la que se congrega:</b> '.$res['iglesia'].'<br> </th>';
	$body .= '<th width="50%" align="left"><br><br><b>Distrito:</b> '.$res['distrito'].'<br> </th>';
	$body .= '</tr>';
	$body .= '</table>';
	
	$body .= '<br><br><br>';

	$body .= '<table>';
	$body .= '<tr>';
	$body .= '<th width="100%" align="left" border="0.5px" color="#ffffff" bgcolor="#888888"><br><br><b> C) DATOS PROFESIONALES (Solo para docentes de carrera)</b><br></th>';
	$body .= '</tr>';
	$body .= '<tr>';
	$body .= '<th width="100%" align="left"><br><br>Años de servicio de en la Educación Fiscal<br></th>';
	$body .= '</tr>';
	$body .= '<tr>';
	$body .= '<th width="50%" align="left"><br><br><b>Categoria del escalafón del Estado:</b> '.$res['escalafon'].'<br> </th>';
	$body .= '<th width="50%" align="left"><br><br><b>Fecha:</b> '.$res['fecha_escalafon'].'<br> </th>';
	$body .= '</tr>';
	$body .= '<tr>';
	$body .= '<th width="50%" align="left"><br><br><b>Unidad educativa fiscal o privada (actual):</b> '.$res['unidad'].'<br> </th>';
	$body .= '<th width="50%" align="left"><br><br><b>Turno:</b> '.$res['turno'].'<br> </th>';
	$body .= '</tr>';
	$body .= '<tr>';
	$body .= '<th width="50%" align="left"><br><br><b>Área o Asignatura (actual):</b> '.$res['asignatura'].'<br> </th>';
	$body .= '<th width="50%" align="left"><br><br><b>Periodos:</b> '.$res['periodos'].'<br> </th>';
	$body .= '</tr>';
	$body .= '</table>';
	
	$body .= '<br><br><br>';

	$body .= '<table>';
	$body .= '<tr>';
	$body .= '<th width="100%" align="left" border="0.5px" color="#ffffff" bgcolor="#888888"><br><br><b> D) FORMACIÓN ACADEMICA Y FORMACIÓN CONTINUA</b><br></th>';
	$body .= '</tr>';
	$body .= '</table>';

	$body .= '<table cellpadding="">';		
	
	$body .= '<tr>';
	$body .= '<th width="15%" align="CENTER" border="0.5px"><b><br> NIVEL<br></B></th>';
	$body .= '<th width="40%" align="CENTER" border="0.5px"><b><br> ÁREA ACADÉMICA O ESPECIALIDAD<br></B></th>';
	$body .= '<th width="15%" align="center" border="0.5px"><b><br> FECHA DEL TÍTULO<br></B></th>';
	$body .= '<th width="15%" align="center" border="0.5px"><b><br> INSTITUCION<br></B></th>';
	$body .= '<th width="15%" align="CENTER" border="0.5px"><b><br> OBSERVACIÓN/CARGA HORARIA<br></B></th>';	
	$body .= '</tr>';

	$dep = $db->query(" SELECT *
                  FROM per_postulacion_formacion
                  WHERE postulante_id='".$res['id_postulacion']."'
                  ")->fetch();

	$nrox=0;
    foreach ($dep as $rxx) { 
		$nrox++;
	
		$rss=explode("-",$rxx['fecha']);
		$rss2=$rss[2]."/".$rss[1]."/".$rss[0];
		
		$body .= '<tr>';
		$body .= '<th width="15%" align="CENTER" border="0.5px"><br><br> '.$rxx['nivel'].'<br></th>';
		$body .= '<th width="40%" align="CENTER" border="0.5px"><br><br> '.$rxx['especialidad'].'<br></th>';
		$body .= '<th width="15%" align="center" border="0.5px"><br><br> '.$rss2.'<br></th>';
		$body .= '<th width="15%" align="center" border="0.5px"><br><br> '.$rxx['institucion'].'<br></th>';
		$body .= '<th width="15%" align="CENTER" border="0.5px"><br><br> '.$rxx['observacion'].'<br></th>';	
		$body .= '</tr>';
	}
	if($nrox==0){
		$body .= '<tr>';
		$body .= '<th width="100%" align="CENTER" border="0.5px"><br><br> Sin detalles<br></th>';
		$body .= '</tr>';
	}
	$body .= '</table>';

	$body .= '<br><br><br>';

	$body .= '<table>';
	$body .= '<tr>';
	$body .= '<th width="100%" align="left" border="0.5px" color="#ffffff" bgcolor="#888888"><br><br><b> E) OTROS CONOCIMIENTOS Y HABILIDADES ESPECÍFICAS</b><br></th>';
	$body .= '</tr>';
	$body .= '</table>';

	$body .= '<table>';
	$body .= '<tr>';
	$body .= '<th width="30%" align="CENTER" border="0.5px"><b><br> ITEM/ÁREA<br></B></th>';
	$body .= '<th width="40%" align="CENTER" border="0.5px"><b><br> DESCRIPCIÓN DEL CONOCIMIENTO/HABILIDAD<br></B></th>';
	$body .= '<th width="30%" align="center" border="0.5px"><b><br> INSTITUCIÓN<br></B></th>';
	$body .= '</tr>';

	$dep = $db->query(" SELECT *
                  FROM per_postulacion_conocimiento
                  WHERE postulante_id='".$res['id_postulacion']."'
                  ")->fetch();

	$nrox=0;
    foreach ($dep as $rxx) { 
		$nrox++;
		$body .= '<tr>';
		$body .= '<th width="30%" align="CENTER" border="0.5px"><br><br> '.$rxx['item'].'<br></th>';
		$body .= '<th width="40%" align="center" border="0.5px"><br><br> '.$rxx['habilidad'].'<br></th>';
		$body .= '<th width="30%" align="center" border="0.5px"><br><br> '.$rxx['institucion'].'<br></th>';
		$body .= '</tr>';
	}
	if($nrox==0){
		$body .= '<tr>';
		$body .= '<th width="100%" align="CENTER" border="0.5px"><br><br> Sin detalles<br></th>';
		$body .= '</tr>';
	}
	$body .= '</table>';

	$body .= '<br><br><br>';

	$body .= '<table>';
	$body .= '<tr>';
	$body .= '<th width="100%" align="left" border="0.5px" color="#ffffff" bgcolor="#888888"><br><br><b> F) EXPERIENCIA LABORAL </b><br></th>';
	$body .= '</tr>';
	$body .= '</table>';

	$body .= '<table>';
	$body .= '<tr>';
	$body .= '<th width="15%" align="center" border="0.5px"><b><br> FECHA INGRESO<br></B></th>';
	$body .= '<th width="15%" align="center" border="0.5px"><b><br> FECHA SALIDA <br></B></th>';
	$body .= '<th width="30%" align="center" border="0.5px"><b><br> MOTIVO RETIRO<br></B></th>';
	$body .= '<th width="20%" align="center" border="0.5px"><b><br> CARGO<br></B></th>';
	$body .= '<th width="20%" align="center" border="0.5px"><b><br> INSTITUCIÓN<br></B></th>';
	$body .= '</tr>';

	$exp = $db->query(" SELECT *
                  FROM per_postulacion_experiencia
                  WHERE postulante_id='".$res['id_postulacion']."'
                  ")->fetch();

	$nrox=0;

    foreach ($exp as $rxx) { 
		$nrox++;
		$body .= '<tr>';
		$body .= '<th width="15%" align="CENTER" border="0.5px"><br><br> '.$rxx['fecha_ingreso'].'<br></th>';
		$body .= '<th width="15%" align="center" border="0.5px"><br><br> '.$rxx['fecha_salida'].'<br></th>';
		$body .= '<th width="30%" align="CENTER" border="0.5px"><br><br> '.$rxx['motivo_retiro'].'<br></th>';
		$body .= '<th width="20%" align="center" border="0.5px"><br><br> '.$rxx['cargo'].'<br></th>';
		$body .= '<th width="20%" align="center" border="0.5px"><br><br> '.$rxx['institucion'].'<br></th>';
		$body .= '</tr>';
	}
	if($nrox==0){
		$body .= '<tr>';
		$body .= '<th width="100%" align="CENTER" border="0.5px"><br><br> Sin detalles<br></th>';
		$body .= '</tr>';
	}
	$body .= '</table>';
	$body .= '<br><br><br>';
	$body .= '<table>';
	$body .= '<tr>';
	$body .= '<th width="100%" align="left" border="0" color="#000"><br>
	<b><i>La información que proporcioné en este formulario y sus respectivas HOJAS RESPALDO, constituyen declaración jurada, y tendrá tal valor sólo a los  					
efectos administrativos que la Unidad Educativa Santa Maria determine.</i></b></th>';
	$body .= '</tr>';
	$body .= '</table>';
	$body .= '<br><br><br>';
	$dia = date('d');
	$mes = date('m');
	$anio= date('Y');
	$body .= '<table>';
	$body .= '<tr>';
	$body .= '<th width="100%" align="left" border="0" color="#000"><br>
	<b><i>En tal constancia firmo:…………………………………,a los        '.intval($dia).'         del mes de                  ';
	
	switch($mes){
		case "1": case "01":	$body .= 'Enero';	break;
		case "2": case "02":	$body .= 'Febrero';	break;
		case "3": case "03":	$body .= 'Marzo';	break;
		case "4": case "04":	$body .= 'Abril';	break;
		case "5": case "05":	$body .= 'Mayo';	break;
		case "6": case "06":	$body .= 'Junio';	break;

		case "7": case "07":	$body .= 'Julio';	break;
		case "8": case "08":	$body .= 'Agosto';	break;
		case "9": case "09":	$body .= 'Septiembre';	break;
		case "10":  $body .= 'Octure';	break;
		case "11": 	$body .= 'Noviembre';	break;
		case "12": 	$body .= 'Diciembre';	break;
	}

	$body .= ' del año  '.$anio.'.	.</b></i></th>';
	$body .= '</tr>';
	$body .= '</table>';


		






/*
  `cargo_id` int(11) NOT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `estado` enum('A','I') NOT NULL,
  `personal` enum('A','I') DEFAULT NULL,
  `genero` enum('v','m') DEFAULT NULL,
  `cuenta_bancaria` varchar(20) DEFAULT NULL,
  `cns` varchar(20) DEFAULT NULL,
*/

	
	// Imprime la tabla
	$pdf->writeHTML($body, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'concepto_pago_' . date('Y-m-d_H-i-s') . '.pdf';

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>