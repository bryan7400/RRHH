<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK Maribel Marco Luis
 */

header('Content-Type: application/json');

// Verifica la peticion post
if (is_post()) {


    // Verifica la existencia de datos
    if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
        //Obtiene los datos
        $usuario            = clear($_POST['usuario']);
        $contrasenia        = clear($_POST['contrasenia']);
        $id_gestion         = clear($_POST['id_gestion']);
        $__id_aula_paralelo   = clear($_POST['id_aula_paralelo']);
        $__id_estudiante      = clear($_POST['id_estudiante']);

        $fecha_hoy          = date("Y-m-d");

        // Encripta la contrasenia para compararla en la base de datos
        $usuario    = md5($usuario);
        $contrasenia = encrypt($contrasenia);


        // Obtiene los datos del usuario
        $usuario = $db->select('*')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();

        $id_persona =     $usuario['persona_id'];

        // Verifica la existencia del usuario 
        if ($usuario) {

            //Obtener el nivel academico
           $res_nivel_academico_post = $db->query("SELECT ia.nivel_academico_id
                                                        FROM ins_aula_paralelo as iap
                                                        INNER JOIN ins_aula as ia ON ia.id_aula = iap.aula_id
                                                        WHERE iap.estado = 'A' AND ia.gestion_id = $id_gestion AND iap.id_aula_paralelo = $__id_aula_paralelo")->fetch_first();
            $__id_nivel_academico = $res_nivel_academico_post['nivel_academico_id'];

            

            $sql_nivel = "SELECT *
                  FROM ins_nivel_academico
                  WHERE estado = 'A' AND gestion_id = $id_gestion";
            $niveles = $db->query($sql_nivel)->fetch();

           

            $consulta_nivelsql = "SELECT ap.id_aula_paralelo, a.nombre_aula, p.nombre_paralelo, na.nombre_nivel, t.nombre_turno
                                    FROM ins_aula_paralelo ap 
                                    INNER JOIN ins_aula a ON ap.aula_id = a. id_aula
                                    INNER JOIN ins_nivel_academico na ON a.nivel_academico_id = na.id_nivel_academico
                                    INNER JOIN ins_turno t ON ap.turno_id = t.id_turno
                                    INNER JOIN ins_paralelo p ON ap.paralelo_id = p.id_paralelo ";

            $consulta_cursossql = "SELECT ap.id_aula_paralelo, a.nombre_aula, p.nombre_paralelo, na.nombre_nivel, t.nombre_turno
                                    FROM ins_aula_paralelo ap 
                                    INNER JOIN ins_aula a ON ap.aula_id = a. id_aula
                                    INNER JOIN ins_nivel_academico na ON a.nivel_academico_id = na.id_nivel_academico
                                    INNER JOIN ins_turno t ON ap.turno_id = t.id_turno
                                    INNER JOIN ins_paralelo p ON ap.paralelo_id = p.id_paralelo ";

            if ($__id_nivel_academico == 0) {
                $nivelsql = $db->query($consulta_nivelsql)->fetch();
                $cursossql = $db->query($consulta_cursossql)->fetch();
            } else if ($__id_nivel_academico > 0 && $__id_aula_paralelo > 0) {
                $consulta_nivelsql .= " WHERE na.id_nivel_academico = $__id_nivel_academico
                      AND ap.id_aula_paralelo = $__id_aula_paralelo ";
                $consulta_cursossql .= " WHERE na.id_nivel_academico = $__id_nivel_academico
                      AND ap.id_aula_paralelo = $__id_aula_paralelo ";
                $nivelsql = $db->query($consulta_nivelsql)->fetch_first();
                $cursossql = $db->query($consulta_cursossql)->fetch();
                $nivel_nombre = $nivelsql['nombre_nivel'];
                $curso_nombre = $nivelsql['nombre_aula'] . ' "' . $nivelsql['nombre_paralelo'] . '"'; //primaria';
                $turno_nombre = $nivelsql['nombre_turno'];
                
            } else if ($__id_nivel_academico > 0 && $__id_aula_paralelo == 0) {
                $consulta_nivelsql .= " WHERE na.id_nivel_academico = $__id_nivel_academico";
                $consulta_cursossql .= " WHERE na.id_nivel_academico = $__id_nivel_academico ";
                $nivelsql = $db->query($consulta_nivelsql)->fetch();
                $cursossql = $db->query($consulta_cursossql)->fetch();
            }

            

            /* consultamos los tipo de pensiones */
            $sql_pensiones = "SELECT pp.id_pensiones, ppd.id_pensiones_detalle, pp.codigo_concepto, pp.descripcion, pp.tipo_concepto, pp.nro_cuota, pp.orden, ppd.nro AS numero_cuota, ppd.monto, ppd.mes, pp.nivel_academico_id
                    FROM pen_pensiones AS pp
                    INNER JOIN pen_pensiones_detalle AS ppd ON ppd.pensiones_id = pp.id_pensiones
                    INNER JOIN pen_pensiones_estudiante pe ON ppd.id_pensiones_detalle = pe.detalle_pension_id
                    INNER JOIN ins_inscripcion i ON i.id_inscripcion=pe.inscripcion_id
                    WHERE pp.gestion_id = $id_gestion AND ppd.estado_detalle = 'A'";

            /* Consultamos los pagos y los conceptos */
            $consulta_pagos_realizados = "SELECT i.id_inscripcion, i.estudiante_id, CONCAT(sp.primer_apellido,' ',sp.segundo_apellido,' ',sp.nombres)AS nombre_estudiante, ppe.id_pensiones_estudiante, ppe.detalle_pension_id,ppe.monto, ppe.tipo_concepto, ppd.nro, ppd.cuota, ppd.mes, ppd.monto, pped.pensiones_estudiante_id, pped.monto, ppeg.nro_factura, ppeg.fecha_general, ppeg.hora_general
                                            FROM ins_inscripcion i
                                            INNER JOIN ins_estudiante e ON i.estudiante_id=e.id_estudiante
                                            INNER JOIN sys_persona sp ON e.persona_id=sp.id_persona
                                            INNER JOIN pen_pensiones_estudiante AS ppe ON ppe.inscripcion_id = i.id_inscripcion
                                            INNER JOIN pen_pensiones_detalle AS ppd ON ppd.id_pensiones_detalle = ppe.detalle_pension_id
                                            INNER JOIN pen_pensiones_estudiante_detalle AS pped ON pped.pensiones_estudiante_id = ppe.id_pensiones_estudiante
                                            INNER JOIN pen_pensiones_estudiante_general AS ppeg ON ppeg.id_general = pped.general_id
                                            WHERE i.estado = 'A' AND i.gestion_id = $id_gestion AND ppe.estado_pension_estudiante = 'A' AND ppeg.estado_factura = 'ACTIVO' AND ppeg.gestion_id = $id_gestion AND pped.estado = 'A'";

            if ($__id_nivel_academico == 0) {
                $sql_pensiones .= " GROUP BY ppd.nro,  pp.id_pensiones
                       ORDER BY pp.grupo, ppd.nro asc";

                $consulta_pagos_realizados .= " GROUP BY ppe.inscripcion_id,
                        i.estudiante_id ASC, ppe.detalle_pension_id ASC";

                $sql_estudiantes = "SELECT i.id_inscripcion, i.estudiante_id, CONCAT(sp.primer_apellido,' ',sp.segundo_apellido,' ',sp.nombres)AS nombre_estudiante
                        FROM ins_inscripcion i
                        INNER JOIN ins_estudiante e ON i.estudiante_id=e.id_estudiante
                        INNER JOIN sys_persona sp ON e.persona_id=sp.id_persona
                        WHERE i.estado = 'A' AND i.gestion_id = $id_gestion 
                        ORDER BY sp.primer_apellido ASC, sp.segundo_apellido ASC, sp.nombres ASC"; // AND i.aula_paralelo_id = $curso
                $res_estudiantes = $db->query($sql_estudiantes)->fetch();
            } else if ($__id_nivel_academico > 0 && $__id_aula_paralelo > 0) {

                $sql_pensiones .= "  AND pp.nivel_academico_id = '' OR pp.nivel_academico_id = $__id_nivel_academico
                      AND i.aula_paralelo_id = $__id_aula_paralelo
                      GROUP BY ppd.nro,  pp.id_pensiones
                      ORDER BY pp.grupo, ppd.nro asc";

                $consulta_pagos_realizados .= " AND i.nivel_academico_id = $__id_nivel_academico 
                      AND i.aula_paralelo_id = $__id_aula_paralelo                      
                      GROUP BY i.estudiante_id ASC, ppe.detalle_pension_id ASC";

                $sql_estudiantes = "SELECT i.id_inscripcion, i.estudiante_id, CONCAT(sp.primer_apellido,' ',sp.segundo_apellido,' ',sp.nombres)AS nombre_estudiante
                        FROM ins_inscripcion i
                        INNER JOIN ins_estudiante e ON i.estudiante_id=e.id_estudiante
                        INNER JOIN sys_persona sp ON e.persona_id=sp.id_persona
                        WHERE i.estado = 'A' AND i.gestion_id = $id_gestion AND i.nivel_academico_id = $__id_nivel_academico AND i.aula_paralelo_id = $__id_aula_paralelo
                        ORDER BY sp.primer_apellido ASC, sp.segundo_apellido ASC, sp.nombres ASC";

                $res_estudiantes = $db->query($sql_estudiantes)->fetch();
            } else if ($__id_nivel_academico > 0 && $__id_aula_paralelo == 0) {
                $sql_pensiones .= "  AND pp.nivel_academico_id = '' OR pp.nivel_academico_id = $__id_nivel_academico
                      GROUP BY ppd.nro,  pp.id_pensiones
                      ORDER BY pp.grupo, ppd.nro asc";

                $consulta_pagos_realizados .= " AND i.nivel_academico_id = $__id_nivel_academico                      
                      GROUP BY i.estudiante_id ASC, ppe.detalle_pension_id ASC";

                $sql_estudiantes = "SELECT i.id_inscripcion, i.estudiante_id, CONCAT(sp.primer_apellido,' ',sp.segundo_apellido,' ',sp.nombres)AS nombre_estudiante
                        FROM ins_inscripcion i
                        INNER JOIN ins_estudiante e ON i.estudiante_id=e.id_estudiante
                        INNER JOIN sys_persona sp ON e.persona_id=sp.id_persona
                        WHERE i.estado = 'A' AND i.gestion_id = $id_gestion AND i.nivel_academico_id = $__id_nivel_academico
                        ORDER BY sp.primer_apellido ASC, sp.segundo_apellido ASC, sp.nombres ASC";
                $res_estudiantes = $db->query($sql_estudiantes)->fetch();
            }

            $conceptos = $db->query($sql_pensiones)->fetch();
            $res_pago_realisados = $db->query($consulta_pagos_realizados)->fetch();

            // Armamos el array para poder sacar el solo a los estudiantes
            $aPagos = array();
            foreach ($res_pago_realisados as $km => $mensualidades) {
                $aPagos[$mensualidades['estudiante_id']][$mensualidades['detalle_pension_id']] = $mensualidades;
            }
            
            

            // Armamos un array para mostrar las mensualidades pagadas
            $aPagosEstudiantes = array();

            foreach ($res_estudiantes as $ke => $estudiante_) {
                $id_estudiante = $estudiante_['estudiante_id'];
                $aPagosEstudiantes[$estudiante_['estudiante_id']]['nombre_estudiante'] = $estudiante_['nombre_estudiante'];
                //verificamos todas las mensualidades asignadas
                foreach ($conceptos as $kc => $concepto_) {
                    $id_pensiones_detalle = $concepto_['id_pensiones_detalle'];
                    if (isset($aPagos[$id_estudiante][$id_pensiones_detalle]['monto'])) {
                        $aPagosEstudiantes[$estudiante_['estudiante_id']]['pagos'][] = $aPagos[$id_estudiante][$id_pensiones_detalle]['monto'];
                    } else {
                        $aPagosEstudiantes[$estudiante_['estudiante_id']]['pagos'][] = 0;
                    }
                }
            }


            $sql_estudiantes_actuales = "SELECT ie.id_estudiante, ia.nombre_aula, ip.nombre_paralelo, ina.nombre_nivel, ina.descripcion, iir.nro_rude, CONCAT(p.primer_apellido,' ',p.segundo_apellido,' ', p.nombres)AS nombre_estudiante, ii.aula_paralelo_id
                                FROM ins_inscripcion AS ii
                                INNER JOIN ins_inscripcion_rude AS iir ON iir.ins_estudiante_id = ii.estudiante_id
                                INNER JOIN ins_aula_paralelo AS iap ON iap.id_aula_paralelo = ii.aula_paralelo_id
                                INNER JOIN ins_aula AS ia ON ia.id_aula = iap.aula_id
                                INNER JOIN ins_nivel_academico AS ina ON ina.id_nivel_academico = ia.nivel_academico_id
                                INNER JOIN ins_paralelo AS ip ON ip.id_paralelo = iap.paralelo_id
                                INNER JOIN ins_estudiante AS ie ON ie.id_estudiante = ii.estudiante_id
                                INNER JOIN sys_persona AS p ON p.id_persona = ie.persona_id
                                WHERE ii.estado = 'A' AND ii.gestion_id = $id_gestion AND iap.estado = 'A'
                                ORDER BY ina.id_nivel_academico ASC, iap.id_aula_paralelo ASC, p.primer_apellido ASC, p.segundo_apellido ASC, p.nombres ASC";
            $res_estudiantes_actuales = $db->query($sql_estudiantes_actuales)->fetch();

            //Nos creamos un array para recuperar todos los estudiantes activos
            $aEstudiantesActuales = [];
            foreach ($res_estudiantes_actuales as $e => $valor) {
                $aEstudiantesActuales[$valor['aula_paralelo_id']][$valor['id_estudiante']] = $valor;
            }

            // echo "<pre>";
            // var_dump($aEstudiantesActuales);
            // echo "</pre>";
            // exit();

            //Recorremos el nivel
            $sql_nivel = "SELECT *
                  FROM ins_nivel_academico
                  WHERE estado = 'A' AND gestion_id = $id_gestion";

            if ($__id_nivel_academico > 0) {
                $sql_nivel .= " AND id_nivel_academico = " . $__id_nivel_academico;
            }


            $res_nivel = $db->query($sql_nivel)->fetch();

            //Array para armar la tabla
            $aTablaMensualidades = array();

            foreach ($res_nivel as $kn => $nivel_) {

                $_id_nivel_academico = $nivel_['id_nivel_academico'];

                $nombre_nivel_academico = $nivel_['nombre_nivel'];

                $sql_aulas_paralelos = "SELECT * 
                              FROM ins_aula as a
                              INNER JOIN ins_aula_paralelo as ap ON ap.aula_id = a.id_aula
                              INNER JOIN ins_paralelo AS p ON p.id_paralelo = ap.paralelo_id
                              WHERE a.gestion_id = $id_gestion AND a.nivel_academico_id = $_id_nivel_academico AND ap.estado = 'A'";

                if ($__id_aula_paralelo > 0) {
                    $sql_aulas_paralelos .= " AND ap.id_aula_paralelo = " . $__id_aula_paralelo;
                }

                $res_aulas_paralelos = $db->query($sql_aulas_paralelos)->fetch();

                //Buscamos a los estudiantes
                foreach ($res_aulas_paralelos as $kap => $aula_paralelo) {

                    //Descripcion del aula paralelo    
                    $nombre_aula_paralelo = $aula_paralelo['nombre_aula'] . " " . $aula_paralelo['descripcion'];
                    $etiqueta_del_curso = $nombre_nivel_academico . ' ' . $nombre_aula_paralelo;
                    $_id_aula_paralelo = $aula_paralelo['id_aula_paralelo'];

                    $aTablaMensualidades[$_id_nivel_academico][$_id_aula_paralelo]['curso'] = $etiqueta_del_curso;


                    //Sacamos los conceptos de pago por nivel y si curso
                    $consulta_concepto = "SELECT pd.id_pensiones_detalle, p.nombre_pension, p.codigo_concepto, pe.monto, pe.fecha_final, pe.inscripcion_id, p.codigo_concepto, pd.nro, p.orden
                              FROM pen_pensiones p
                              INNER JOIN pen_pensiones_detalle pd ON p.id_pensiones=pd.pensiones_id
                              INNER JOIN pen_pensiones_estudiante pe ON pd.id_pensiones_detalle = pe.detalle_pension_id
                              INNER JOIN ins_inscripcion i ON i.id_inscripcion=pe.inscripcion_id
                              WHERE p.gestion_id = $id_gestion AND i.nivel_academico_id = $_id_nivel_academico OR i.nivel_academico_id = ''
                              AND i.aula_paralelo_id = $_id_aula_paralelo
                              GROUP BY pd.nro, p.nombre_pension
                              ORDER BY p.grupo, pd.nro asc";

                    $conceptos = $db->query($consulta_concepto)->fetch();

                    //Armamos los conceptos
                    foreach ($conceptos as  $rowact) {
                        $aTablaMensualidades[$_id_nivel_academico][$_id_aula_paralelo]['conceptos'][] = $rowact['nombre_pension'] . ' ' . $rowact['nro'].'*'.$rowact['fecha_final'].'*'.$rowact['id_pensiones_detalle'];
                    }
                    $aTablaMensualidades[$_id_nivel_academico][$_id_aula_paralelo]['conceptos'][] =  "TOTAL*0*0";

                    //sacamos uno a uno a los estudiantes por paralelo
                    $aEstudiantesCurso = $aEstudiantesActuales[$_id_aula_paralelo];

                    if (isset($aEstudiantesCurso)) {

                        $suma_mensualidades = 0;
                        $c = 1;

                        foreach ($aEstudiantesCurso as $ke => $estudiante_) {
                            //$id_estudiante = $estudiante_['estudiante_id'];
                            $id_estudiante = $ke;

                            $aTablaMensualidades[$_id_nivel_academico][$_id_aula_paralelo]['estudiante'][$id_estudiante]['nro'] =  $c;
                            $aTablaMensualidades[$_id_nivel_academico][$_id_aula_paralelo]['estudiante'][$id_estudiante]['nombre_estudiante'] =  $estudiante_['nombre_estudiante'];

                            //verificamos todas las mensualidades asignadas
                            foreach ($conceptos as $kc => $concepto_) {
                                $id_pensiones_detalle = $concepto_['id_pensiones_detalle'];
                                if (isset($aPagos[$id_estudiante][$id_pensiones_detalle]['monto'])) {
                                    
                                    //Fecha des pago y monto
                                    $_monto = $aPagos[$id_estudiante][$id_pensiones_detalle]['monto'];
                                    $_fecha_monto = $aPagos[$id_estudiante][$id_pensiones_detalle]['fecha_general'];
                                    $_fecha_monto = date("d/m/Y", strtotime($_fecha_monto));
                                    $_hora_monto  = $aPagos[$id_estudiante][$id_pensiones_detalle]['hora_general'];
                                    
                                    //$_monto = $aPagos[$id_estudiante][$id_pensiones_detalle]['monto'];
                                    $suma_mensualidades = $suma_mensualidades +  $_monto;
                                    $aTablaMensualidades[$_id_nivel_academico][$_id_aula_paralelo]['estudiante'][$id_estudiante]['pagos'][] = number_format($_monto, 2)."*". $_fecha_monto.' '.$_hora_monto;
                                } else {
                                    $_monto = 0;
                                    $suma_mensualidades = $suma_mensualidades + 0;
                                    $aTablaMensualidades[$_id_nivel_academico][$_id_aula_paralelo]['estudiante'][$id_estudiante]['pagos'][] =  number_format($_monto, 2)."*". "";
                                    //$aTablaMensualidades[$_id_nivel_academico][$_id_aula_paralelo]['estudiante'][$id_estudiante]['pagos'][] =  "NO*NO";
                                }
                            }
                            $aTablaMensualidades[$_id_nivel_academico][$_id_aula_paralelo]['estudiante'][$id_estudiante]['pagos'][] =  number_format($suma_mensualidades, 2);
                            $suma_mensualidades = 0;
                            $c++;
                        }
                    } // foreach aula_paralelo
                    //Bajamos mas celdas para poder ver el listado del otro curso
                } //foreach nivel
            }


            // echo "<pre>";
            // echo json_encode($aTablaMensualidades); 
            // echo "<pre>";
            // exit();
         
            $etiqueta_mensualidades = $aTablaMensualidades[$__id_nivel_academico][$__id_aula_paralelo]['conceptos'];

            // obtenemos las mensualidades asignadas
            $sql_mis_mensualidades = "SELECT ii.estudiante_id, iih.tipo_estudiante_id, ite.nombre_tipo_estudiante, ite.monto_beca, ite.descuento_beca, ppe.detalle_pension_id
                                        FROM ins_inscripcion AS ii 
                                        INNER JOIN ins_inscripcion_historial AS iih ON iih.inscripcion_id = ii.id_inscripcion
                                        INNER JOIN ins_tipo_estudiante AS ite ON ite.id_tipo_estudiante = ii.tipo_estudiante_id
                                        INNER JOIN pen_pensiones_estudiante AS ppe ON ppe.inscripcion_id = ii.id_inscripcion AND ppe.historial_id = iih.id_historial
                                        INNER JOIN pen_pensiones_detalle AS ppd ON ppd.id_pensiones_detalle = ppe.detalle_pension_id
                                        INNER JOIN pen_pensiones AS pp ON pp.id_pensiones = ppd.pensiones_id
                                        WHERE ii.estudiante_id = $__id_estudiante AND ii.estado = 'A' AND ii.gestion_id = $id_gestion AND ppe.estado_pension_estudiante = 'A'";
            $res_mis_mensualidades = $db->query($sql_mis_mensualidades)->fetch();

            //Armamos un array de consultas
            $aMisMensualidades = array();
            foreach ($res_mis_mensualidades as $key => $value) {
                $aMisMensualidades [$value['detalle_pension_id']] = $value;
            }    

            $aMensualidades = array();
            $indice = 0;
            foreach ($etiqueta_mensualidades as $ke => $etiqueta) {                

                //Fecha limite de la mensualidad
                $afecha_mensualidad = explode("*", $etiqueta);
                $fecha_mensualidad  = $afecha_mensualidad[1];
                $__id_pension_detalle = $afecha_mensualidad[2];

                //Verficamos los conceptos de pago que tiene asignado

                if(isset($aMisMensualidades[$__id_pension_detalle])){
                    //Fecha del pago
                    $afecha_pago  = explode("*",$aTablaMensualidades[$__id_nivel_academico][$__id_aula_paralelo]['estudiante'][$__id_estudiante]['pagos'][$ke]);
                    $monto_pago   = $afecha_pago[0];
                    $fecha_pago   = isset($afecha_pago[1])?$afecha_pago[1]:"0000-00-00";
                    
                    $aMensualidades[$indice]['mensualidad']  = $afecha_mensualidad[0];
                    $aMensualidades[$indice]['pago']         = $monto_pago;
                    $aMensualidades[$indice]['fecha_pago']   = $fecha_pago; 
                    $aMensualidades[$indice]['fecha_limite'] = $fecha_mensualidad;

                    //Preguntamosque si cancelo o tiene deuda                
                    if($fecha_hoy >= $fecha_mensualidad){
                        if($monto_pago > 0){
                            $aMensualidades[$indice]['estado_pago'] = "CANCELADO";
                        }else{
                            $aMensualidades[$indice]['estado_pago'] = "PENDIENTE";
                        }                    
                    }else{
                        $aMensualidades[$indice]['estado_pago'] = "";
                    }
                    $indice++; 
                }               
            }

            // Instancia el objeto que devolvera la web service			
            $respuesta = array(
                'estado' => 's',
                'mensualidades' => $aMensualidades
            );

            // Devuelve los resultados
            echo json_encode($respuesta);
        } else {
            // Devuelve los resultados
            echo json_encode(array('estado' => 'n'));
        }
    } else {
        // 	// Devuelve los resultados
        echo json_encode(array('estado' => 'nfp'));
    }
} else {
    // 	// Devuelve los resultados
    echo json_encode(array('estado' => 'np'));
}
