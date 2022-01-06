<?php

$id_estudiante = isset($_POST['id_estudiante'])?$_POST['id_estudiante']:0;
$carpeta_adjunta= "files/profiles/documento-estudiante/";  //ruta para guardar los archivos
// Contar envían por el plugin
$imagenes =count(isset($_FILES['img_documentos']['name'])?$_FILES['img_documentos']['name']:0);

for($i = 0; $i < $imagenes; $i++) {
	$cadena = "";
	$nombre_documento = "";
	$cadena = $id_estudiante . "-" . $_FILES['img_documentos']['name'][$i];
	$nombre = md5(secret . random_string() . $cadena); //encripta el nombre de la imagen a md5

	// identifica el tipo de archivo
	switch($_FILES['img_documentos']['type'][$i]){
		case 'image/jpeg': $nombre_documento = $nombre . ".jpeg"; break;
		case 'image/jpg': $nombre_documento = $nombre . ".jpg"; break;
		case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document': $nombre_documento = $nombre . ".docx"; break;
		case 'application/pdf': $nombre_documento = $nombre . ".pdf"; break;
	}

	//instancia documentos
	$documento = array('nombre_documento'=> $nombre_documento,
					   'estudiante_id'=> $id_estudiante,
					   'estado'=>'A',
					   'usuario_registro'=> $_user['id_user'],
					   'fecha_registro'=> Date('Y-m-d H:i:s'));

	//agregar el registro
	$id_documento = $db->insert('ins_documentos', $documento);
	// El nombre y nombre temporal del archivo que vamos para adjuntar
	$nombre_temporal=isset($_FILES['img_documentos']['tmp_name'][$i])?$_FILES['img_documentos']['tmp_name'][$i]:null;
	
	$ruta_archivo = $carpeta_adjunta . $nombre_documento; //arma la ruta del archivo
	move_uploaded_file($nombre_temporal,$ruta_archivo); //mueve el archivo de su ruta temporal a su nueva ruta
	
	$info_imagenes_subidas[$i]=array("caption"=>"$nombre","height"=>"120px","url"=>"borrar.php","key"=>$nombre);
	$imagenes_subidas[$i]="<img  height='120px'  src='$ruta_archivo' class='file-preview-image'>";
}

$arr = array("file_id"=>0,"overwriteInitial"=>true,"initialPreviewConfig"=>$info_imagenes_subidas,
			 "initialPreview"=>$imagenes_subidas);
echo json_encode($arr);

?>