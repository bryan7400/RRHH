<?php

// echo "<pre>";
// var_dump($_FILES);
// var_dump($_POST);
// echo "</pre>";
// exit();

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion post
if (true) {
    
    // echo "<pre>";
    // var_dump($_FILES);
    // var_dump($_POST);
    // echo "</pre>";
    // exit();

    
    // Recogemos las variables
    $id_familiar      = (isset($_POST['id_familiar'])) ? clear($_POST['id_familiar']) : 0;
	$nombres          = (isset($_POST['nombres'])) ? clear($_POST['nombres']) : "";
	$primer_apellido  = (isset($_POST['primer_apellido'])) ? clear($_POST['primer_apellido']) : "";
	$segundo_apellido = (isset($_POST['segundo_apellido'])) ? clear($_POST['segundo_apellido']) : "";
	$tipo_documento   = (isset($_POST['tipo_documento'])) ? clear($_POST['tipo_documento']) : 1;
	$numero_documento = (isset($_POST['numero_documento'])) ? clear($_POST['numero_documento']) : "";
	$expedido         = (isset($_POST['expedido'])) ? clear($_POST['expedido']) : "LP";
	$complemento      = (isset($_POST['complemento'])) ? clear($_POST['complemento']) : "";
	$nit              = (isset($_POST['nit'])) ? clear($_POST['nit']) : 0;
	$genero           = (isset($_POST['genero'])) ? clear($_POST['genero']) : "v";
	$fecha_nacimiento = (isset($_POST['fecha_nacimiento'])) ? clear($_POST['fecha_nacimiento']) : "";
	$idioma_frecuente = (isset($_POST['idioma_frecuente'])) ? clear($_POST['idioma_frecuente']) : "";
	$correo_electronico  = (isset($_POST['correo_electronico'])) ? clear($_POST['correo_electronico']) : "";
	$telefono         = (isset($_POST['telefono'])) ? clear($_POST['telefono']) : "";
	$referencia_telefono = (isset($_POST['referencia_telefono'])) ? clear($_POST['referencia_telefono']) : "";
	$profesion        = (isset($_POST['profesion'])) ? clear($_POST['profesion']) : "";
	$direccion        = (isset($_POST['direccion'])) ? clear($_POST['direccion']) : "";
	$grado_instruccion   = (isset($_POST['grado_instruccion'])) ? clear($_POST['grado_instruccion']) : "";
	$parentesco       = (isset($_POST['parentesco'])) ? clear($_POST['parentesco']) : "";
	
	    
    //Preguntamos que el familiar ya no exista en la base de datos
    $sql_persona = "SELECT * 
                        FROM sys_persona AS sp
                        WHERE sp.numero_documento = $numero_documento ";
    if($complemento != ""){
        $sql_persona .= "AND sp.complemento = $complemento";   
    }                    
                        
    $res_persona = $db->query($sql_persona)->fetch();
    
	// Verifica la existencia de datos
	if (count($res_persona) == 0) {
	    // Instaciamos la persona
	    $_persona = array(
            'nombres' => $nombres,
            'primer_apellido' => $primer_apellido,
            'segundo_apellido' => $segundo_apellido,
            'tipo_documento' => $tipo_documento,
            'numero_documento' => $numero_documento,
            'complemento' => $complemento,
            'expedido' => $expedido,
            'genero' => $genero,
            'fecha_nacimiento' => $fecha_nacimiento,
            'direccion' => "",
            'foto' => "",
            'nit' => $nit,
            'postulante_id' => 0,
            'contacto' => "",
            'celular' => $telefono,
            'email' => $correo_electronico 
        );
		
		// Verifica si es creacion o modificacion
		if ($id_familiar > 0) {
			// Modifica el familiar
			// Instancia el familiar
			$familiar = array(
				'profesion' => $profesion,
				'direccion_oficina' => $direccion,
				'telefono_oficina' => "",
				'parentesco' => $parentesco,
				'idioma_frecuente' => $idioma_frecuente,
				'email' => $correo_electronico,
				'grado_instruccion' => $grado_instruccion,
				'nombres_telefono_oficina' => $referencia_telefono,
				'usuario_modificacion' => $_user['id_user'],
				'fecha_modificacion' => date('Y-m-d')
			);
			
			$db->where('id_familiar', $id_familiar)->update('ins_familiar', $familiar);
			
			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso' => date('H:i:s'),
				'proceso' => 'u',
				'nivel' => 'l',
				'detalle' => 'Se modificó el familiar con identificador número ' . $id_familiar . '.',
				'direccion' => $_location,
				'usuario_id' => $_user['id_user']
			));
			
		    echo "2";
		} else {
		    
		    $id_persona = $db->insert('sys_persona', $_persona);
		    $familiar = array(
				'profesion' => $profesion,
				'direccion_oficina' => $direccion,
				'telefono_oficina' => "",
				'parentesco' => $parentesco,
				'idioma_frecuente' => $idioma_frecuente,
				'email' => $correo_electronico,
				'grado_instruccion' => $grado_instruccion,
				'persona_id' => $id_persona,
				'nombres_telefono_oficina' => $referencia_telefono,
				'estado' => "A",
				'usuario_registro' =>  $_user['id_user'],
				'fecha_registro' => date('Y-m-d'),
				'usuario_modificacion' => "0",
				'fecha_modificacion' => "0000-00-00"
		    );

			// Crea el familiar
			$id_familiar = $db->insert('ins_familiar', $familiar);
			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso' => date('H:i:s'),
				'proceso' => 'c',
				'nivel' => 'l',
				'detalle' => 'Se creó el familiar con identificador número ' . $id_familiar . '.',
				'direccion' => $_location,
				'usuario_id' => $_user['id_user']
			));
			
		    echo "1";
		}
	} else {
	    echo "0"; //ya se encuentra la persona con su numero de documento
		exit;
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>