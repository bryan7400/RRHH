<?php 
//var_dump('expression');exit();
error_reporting (E_ALL ^ E_NOTICE);
error_reporting(0);
$documentos_actividad = "";
if (is_post()) {  
    if (isset($_POST['paterno']) && isset($_POST['materno']) && isset($_POST['nombre'])) {
        $paterno = $_POST['paterno']; 
        $materno = $_POST['materno'];
        $nombre = $_POST['nombre'];
        

        $localidad = $_POST['localidad'];
        $provincia = $_POST['provincia'];
        $departamento = $_POST['departamento'];
        $fecha_nacimiento = date_encode($_POST['fecha_nacimiento']);
        $estado_civil = $_POST['estado_civil'];
        
        $ci = $_POST['ci'];
        $expirado = $_POST['expirado'];
        $direccion = $_POST['direccion'];
        $nro_direccion = $_POST['nro_direccion'];
        $zona = $_POST['zona'];
        $ciudad = $_POST['ciudad'];
        $telefono = $_POST['telefono']; 
        $celular = $_POST['celular'];
        $email = $_POST['email'];
        $genero = $_POST['genero'];
        
        $afp = $_POST['afp'];
        $nua = $_POST['nua'];
        $conyuge = $_POST['conyuge']; 
        $fecha_nacimiento_c = date_encode($_POST['fecha_nacimiento_c']);
        $xstring=explode("/",$_POST['fecha_nacimiento_c']);
        if($fecha_nacimiento_c=="--" || count($xstring)!=3){
            $fecha_nacimiento_c = "0000-00-00";
        }

        $fecha_bautismo = date_encode($_POST['fecha_bautismo']);
        $xstring=explode("/",$_POST['fecha_bautismo']);
        if($fecha_bautismo=="--" || count($xstring)!=3){
            $fecha_bautismo = "0000-00-00";
        }
        $pastor = $_POST['pastor'];
        $iglesia = $_POST['iglesia'];
        $distrito = $_POST['distrito'];
        
        $escalafon = $_POST['escalafon'];
        $fecha_escalafon = date_encode($_POST['fecha_escalafon']);
        $xstring=explode("/",$_POST['fecha_escalafon']);
        if($fecha_escalafon=="--" || count($xstring)!=3){
            $fecha_escalafon = "0000-00-00";
        }
        $unidad = $_POST['unidad'];
        
        $turno = $_POST['turno'];
        $asignatura = $_POST['asignatura'];
        $periodos = $_POST['periodos'];
        $nacionalidad = $_POST['nacionalidad'];

        $tipo_postulacion=$_POST['tipo_postulacion'];
        $estado='A';






        $id_postulacion = (isset($_POST['id_postulacion'])) ? clear($_POST['id_postulacion']) : 0;
        
        $nombre_archivo_documento = isset($_FILES["archivo_documento"]["name"]) ? ($_FILES["archivo_documento"]["name"]) : false;

        $archivo_documento_nombre = clear($_POST['archivo_documento_nombre']);


        


        if (($nombre_archivo_documento != '') || ($archivo_documento_nombre != '')) {

        if ($nombre_archivo_documento != ''){
            $formatos_permitidos =  array('pdf', 'docx');
            $archivo_documento = $_FILES['archivo_documento']["name"];
            $extension = pathinfo($nombre_archivo_documento, PATHINFO_EXTENSION);
            $extension = strtolower($extension);

            if (!in_array($extension, $formatos_permitidos)) {
                $archivo_documentos_permitidos = 1;
            } else {
                $output_dir = 'files/' . $nombre_dominio . '/rrhh/postulantes/';
                $imagen =  date('dmY_His') . '_' . '.' . pathinfo($nombre_archivo_documento, PATHINFO_EXTENSION);;
                if (!move_uploaded_file($_FILES['archivo_documento']["tmp_name"], $output_dir . $imagen)) {
                    $msg = 'No pudo subir el archivo_documento';
                } else {
                    $documentos_actividad = $documentos_actividad . $imagen . "@";
                }
            }

            if ($archivo_documento_nombre != ''){
                unlink('files/' . $nombre_dominio . '/rrhh/postulantes/' .$archivo_documento_nombre);
            } else {
                
            }

            $archivo_documento = clear($imagen);
            



        } else {


                $archivo_documento = clear($_POST['archivo_documento_nombre']);
            }

}









        $postulacion=array(
            'nombre' => strtoupper($nombre),
            'paterno' => strtoupper($paterno),
            'materno' => strtoupper($materno),
            'localidad' => strtoupper($localidad),
            'provincia' => strtoupper($provincia),

            'departamento' => strtoupper($departamento),
            'fecha_nacimiento' => $fecha_nacimiento,
            'estado_civil' => $estado_civil,
            'ci' => $ci,
            'expirado' => strtoupper($expirado),
            
            'direccion' => strtoupper($direccion),
            'nro_direccion' => $nro_direccion,
            'zona' => strtoupper($zona),            
            'ciudad' => strtoupper($ciudad),
            'telefono' => $telefono,

            'celular' => $celular,
            'email' => $email,
            'afp' => $afp,
            'nua' => $nua,
            'conyuge' => $conyuge,

            'fecha_nacimiento_c' => $fecha_nacimiento_c,
            'fecha_bautismo' => $fecha_bautismo,
            'pastor' => strtoupper($pastor),
            'iglesia' => strtoupper($iglesia),
            'distrito' => strtoupper($distrito),            
    
            'escalafon' => strtoupper($escalafon),
            'fecha_escalafon' => $fecha_escalafon,
            'unidad' => strtoupper($unidad),            
            'turno' => $turno,
            'asignatura' => strtoupper($asignatura),
    
            'periodos' => $periodos,
            'cargo_id' => strtoupper($tipo_postulacion),
            'fecha_registro' => date("Y-m-d H:i:s"),
            'estado' => $estado,
            'personal' => 'I',

            'genero' => $genero,            
            'cuenta_bancaria' => '',            
            'cns' => '',
            'archivo_documento' => $archivo_documento,            
            'nacionalidad' => $genero
            
        );
       
       



        if ($id_postulacion > 0) {
                // Modifica el postulante
                $postulacion_db = $db->where('id_postulacion', $id_postulacion)->update('per_postulacion', $postulacion);
                

                /*
                $dbdependiente->where('id_postulacion', $id_postulacion)->update('rrhh_contrato', $contrato);
                $dbformacion->where('id_postulacion', $id_postulacion)->update('rrhh_contrato', $contrato);
                $dbconocimiento->where('id_postulacion', $id_postulacion)->update('rrhh_contrato', $contrato);

                */
                // Guarda el proceso
                
                
                // Crea la notificacion
                //set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
                
                // Redirecciona la pagina
                // redirect('?/postulante/ver/' . $id_postulacion);

                $idrespuesta = array(
                'id_postulacion' => $id_postulacion                
                );
                echo json_encode($idrespuesta);


            } else {
                // Crea el postulante
                $postulacion_db_insert = $db->insert('per_postulacion', $postulacion);

                
        for($i=1;$i<=10;$i++){
            if($_POST['dependiente'.$i] && $_POST['fecha_nacimiento_d'.$i] && $_POST['genero'.$i] && $_POST['grado'.$i]){
            
                $nombre=$_POST['dependiente'.$i]; 
                $fecha_nacimiento=date_encode($_POST['fecha_nacimiento_d'.$i]); 
                $genero=$_POST['genero'.$i]; 
                $grado=$_POST['grado'.$i];
            
                $dependiente= array(
                    'nombre' => strtoupper($nombre),
                    'fecha_nacimiento' => $fecha_nacimiento,
                    'genero' => $genero,
                    'grado' => $grado,
                    'postulante_id' => $postulacion_db_insert
                );

                $dbdepend = $db->insert('per_postulacion_dependiente', $dependiente);
            }
        }
       
        for($i=1;$i<=50;$i++){
            if($_POST['nivel_t'.$i] && $_POST['especialidad_t'.$i] && $_POST['fecha_nacimiento_t'.$i] && $_POST['institucion_t'.$i] && $_POST['observacion_t'.$i]){
            
                $nivel=$_POST['nivel_t'.$i];
                $especialidad=$_POST['especialidad_t'.$i];
                $fecha=date_encode($_POST['fecha_nacimiento_t'.$i]);
                $institucion=$_POST['institucion_t'.$i];
                $observacion=$_POST['observacion_t'.$i];
            
                $formacion = array(
                    'nivel'=>strtoupper($nivel),
                    'especialidad'=>strtoupper($especialidad),                    
                    'fecha'=>$fecha,
                    'institucion'=>strtoupper($institucion),
                    'observacion'=>strtoupper($observacion),
                    'postulante_id' => $postulacion_db_insert
                );

                $dbforma = $db->insert('per_postulacion_formacion', $formacion);
            }
        }
       
        for($i=1;$i<=50;$i++){
            if($_POST['item'.$i] && $_POST['habilidad'.$i] && $_POST['institucion'.$i]){
            
                $item=$_POST['item'.$i]; 
                $habilidad=$_POST['habilidad'.$i]; 
                $institucion=$_POST['institucion'.$i]; 
            
                $conocimiento= array(
                    'item' => $item,
                    'habilidad' => strtoupper($habilidad),
                    'institucion' => strtoupper($institucion),
                    'postulante_id' => $postulacion_db_insert
                );

                $dbconocimi = $db->insert('per_postulacion_conocimiento', $conocimiento);
            }
        }







                // Guarda el proceso
                $db->insert('sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso' => date('H:i:s'),
                    'proceso' => 'c',
                    'nivel' => 'l',
                    'detalle' => 'Se creó el postulante con identificador número ' . $id_postulacion . '.',
                    'direccion' => $_location,
                    'usuario_id' => $postulacion_db_insert
                ));
                
                // Crea la notificacion
                //set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
                
                // Redirecciona la pagina
                // redirect('?/postulante/listar');



                $idrespuesta = array(
                'id_postulacion' => $postulacion_db_insert                
                );
                echo json_encode($idrespuesta); 
            }





        // for($i=1;$i<=50;$i++){
        //     if($_POST['fecha_inicio'.$i] && $_POST['fecha_salida'.$i] && $_POST['motivo_retiro'.$i] && $_POST['cargo'.$i]  && $_POST['institucion'.$i]){
            
        //         $item=$_POST['fecha_inicio'.$i]; 
        //         $habilidad=$_POST['fecha_salida'.$i];
        //         $item=$_POST['motivo_retiro'.$i]; 
        //         $habilidad=$_POST['cargo'.$i];  
        //         $institucion=$_POST['institucion'.$i]; 
            
        //         $db->insert('per_postulacion_experiencia', array(
        //             'fecha_inicio' => $item,
        //             'fecha_salida' => $item,
        //             'motivo' => strtoupper($habilidad),
        //             'institucion' => strtoupper($institucion),
        //             'postulante_id' => $id
        //         ));
        //     }
        // }

        //echo json_encode($id);


        

        //header("Location: ?/sitio/ingresar/".$id);
        //redirect(index_private_docente);
        //redirect(index_private);
        
    } else {
        // Redirecciona al modulo index
        redirect('?/sitio/postular');
    }
} else {
    // Redirecciona al modulo index
    redirect('?/sitio/postular');
}

?>