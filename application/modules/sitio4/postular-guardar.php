<?php 
//var_dump('expression');exit();
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
        

        $id=$db->insert('per_postulacion', array(
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
            'nacionalidad' => $genero
            
        ));
       
                
        for($i=1;$i<=10;$i++){
            if($_POST['dependiente'.$i] && $_POST['fecha_nacimiento_d'.$i] && $_POST['genero'.$i] && $_POST['grado'.$i]){
            
                $nombre=$_POST['dependiente'.$i]; 
                $fecha_nacimiento=date_encode($_POST['fecha_nacimiento_d'.$i]); 
                $genero=$_POST['genero'.$i]; 
                $grado=$_POST['grado'.$i];
            
                $db->insert('per_postulacion_dependiente', array(
                    'nombre' => strtoupper($nombre),
                    'fecha_nacimiento' => $fecha_nacimiento,
                    'genero' => $genero,
                    'grado' => $grado,
                    'postulante_id' => $id
                ));
            }
        }
       
        for($i=1;$i<=50;$i++){
            if($_POST['nivel_t'.$i] && $_POST['especialidad_t'.$i] && $_POST['fecha_nacimiento_t'.$i] && $_POST['institucion_t'.$i] && $_POST['observacion_t'.$i]){
            
                $nivel=$_POST['nivel_t'.$i];
                $especialidad=$_POST['especialidad_t'.$i];
                $fecha=date_encode($_POST['fecha_nacimiento_t'.$i]);
                $institucion=$_POST['institucion_t'.$i];
                $observacion=$_POST['observacion_t'.$i];
            
                $db->insert('per_postulacion_formacion', array(
                    'nivel'=>strtoupper($nivel),
                    'especialidad'=>strtoupper($especialidad),                    
                    'fecha'=>$fecha,
                    'institucion'=>strtoupper($institucion),
                    'observacion'=>strtoupper($observacion),
                    'postulante_id' => $id
                ));
            }
        }
       
        for($i=1;$i<=50;$i++){
            if($_POST['item'.$i] && $_POST['habilidad'.$i] && $_POST['institucion'.$i]){
            
                $item=$_POST['item'.$i]; 
                $habilidad=$_POST['habilidad'.$i]; 
                $institucion=$_POST['institucion'.$i]; 
            
                $db->insert('per_postulacion_conocimiento', array(
                    'item' => $item,
                    'habilidad' => strtoupper($habilidad),
                    'institucion' => strtoupper($institucion),
                    'postulante_id' => $id
                ));
            }
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
        $postulante_id_ultimo = $db->from('per_postulacion')->where('ci', $ci )->where('paterno', $paterno )->where('email', $email )->fetch_first();

            redirect('?/sitio/imprimir/1');
        exit;

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