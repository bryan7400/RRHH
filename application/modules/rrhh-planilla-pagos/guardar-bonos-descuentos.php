<?php
/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */
// Verifica la peticion post
if (is_post()) {
    if(isset($_POST['mesX']) ){
                        
        $mes = (isset($_POST['mesX'])) ? $_POST['mesX'] : 0;
        $anio = (isset($_POST['anioX'])) ? $_POST['anioX'] : 0;

        $nro_lines = (isset($_POST['nro_lines'])) ? $_POST['nro_lines'] : 0;
        $nro_bonos = (isset($_POST['nro_bonos'])) ? $_POST['nro_bonos'] : 0;
        $nro_desc = (isset($_POST['nro_desc'])) ? $_POST['nro_desc'] : 0;

        $resuxx = $db->query("  SELECT *
                                FROM ins_gestion
                                WHERE gestion='$anio' 
                            ")->fetch_first();

        for($i=1;$i<=$nro_lines;$i++){        
            for($k=1;$k<=$nro_bonos;$k++){

                //echo $i." ".$k;

                $id_asignacion = (isset($_POST['id_persona_'.$i])) ? $_POST['id_persona_'.$i] : 0;
                $bono = (isset($_POST['bono2_'.$i.'_'.$k])) ? $_POST['bono2_'.$i.'_'.$k] : 0;
                $id_concepto=(isset($_POST['concepto_id_'.$i.'_'.$k])) ? $_POST['concepto_id_'.$i.'_'.$k] : 0;

                $rx2 = $db->query("  SELECT *
                                        FROM rhh_movimiento_pago
                                        WHERE asignacion_id='$id_asignacion' AND
                                            concepto_pago_id='$id_concepto' AND
                                            gestion_id= '".$resuxx['id_gestion']."' AND
                                            mes='$mes'
                                    ")->fetch_first();

                if($rx2){
                     $db->where('id_movimiento', $rx2['id_movimiento'])
                        ->update(
                            'rhh_movimiento_pago', array('monto' => $bono)
                        );            
                }
                else{
                    $resultado = array(
                        'asignacion_id'   =>$id_asignacion,
                        'concepto_pago_id'=>$id_concepto,
                        'gestion_id'=>$resuxx['id_gestion'],
                        'mes' =>$mes,
                        'monto'=>$bono,
                        
                        'fecha_pago'  =>date($anio.'-'.$mes.'-d H:i:s'),
                        'observacion' =>'',
                        'estado' =>'A',
                        'usuario_registro'=>$_user['id_user'],
                        'fecha_registro'=>date('Y-m-d H:i:s'),  
                        
                        'usuario_modificacion'=>'0',    
                        'fecha_modificacion'=>'0000-00-00 00:00:00',  
                        'nro'=>0,
                        'documento' => 'nota',
                        'tipo_pago'=> 'efectivo'
                    );            
                    $db->insert('rhh_movimiento_pago', $resultado);
                }
            }

            for($k=1;$k<=$nro_desc;$k++){

                //echo $i." ".$k;

                $id_asignacion = (isset($_POST['id_persona_'.$i])) ? $_POST['id_persona_'.$i] : 0;
                $bono = (isset($_POST['descuento2_'.$i.'_'.$k])) ? $_POST['descuento2_'.$i.'_'.$k] : 0;
                $id_concepto=(isset($_POST['descuento_id_'.$i.'_'.$k])) ? $_POST['descuento_id_'.$i.'_'.$k] : 0;

                $rx2 = $db->query("  SELECT *
                                        FROM rhh_movimiento_pago
                                        WHERE asignacion_id='$id_asignacion' AND
                                            concepto_pago_id='$id_concepto' AND
                                            gestion_id= '".$resuxx['id_gestion']."' AND
                                            mes='$mes'
                                    ")->fetch_first();

                if($rx2){
                     $db->where('id_movimiento', $rx2['id_movimiento'])
                        ->update(
                                    'rhh_movimiento_pago', array('monto' => $bono)
                                );            
                }
                else{                
                    $resultado = array(        
                        'asignacion_id'   =>$id_asignacion,
                        'concepto_pago_id'=>$id_concepto,
                        'gestion_id'=>$resuxx['id_gestion'],
                        'mes' =>$mes,
                        'monto'=>$bono,
                        
                        'fecha_pago'  =>date($anio.'-'.$mes.'-01 H:i:s'),
                        'observacion' =>'',
                        'estado' =>'A',
                        'usuario_registro'=>$_user['id_user'],    
                        'fecha_registro'=>date('Y-m-d H:i:s'),  
                        
                        'usuario_modificacion'=>'0',    
                        'fecha_modificacion'=>'0000-00-00 00:00:00',  
                        'nro'=>0,
                        'documento' => 'nota',
                        'tipo_pago'=> 'efectivo'
                    );            
                    $db->insert('rhh_movimiento_pago', $resultado);
                }
            }

            $id_asignacion = (isset($_POST['id_persona_'.$i])) ? $_POST['id_persona_'.$i] : 0;
            $bono = (isset($_POST['Mensualidad_'.$i])) ? $_POST['Mensualidad_'.$i] : 0;

            $id_concepto=-1; // Asignado especialmente para mensualidades o pensiones de los hijos

            $rx2 = $db->query("  SELECT *
                                    FROM rhh_movimiento_pago
                                    WHERE asignacion_id='$id_asignacion' AND
                                          concepto_pago_id='$id_concepto' AND
                                          gestion_id= '".$resuxx['id_gestion']."' AND
                                          mes='$mes'
                                ")->fetch_first();

            if($rx2){
                 $db->where('id_movimiento', $rx2['id_movimiento'])
                    ->update(
                        'rhh_movimiento_pago', array('monto' => $bono)
                    );            
                //echo "modificar".$bono." x ".$rx2['id_movimiento'];    
            }
            else{
                $resultado = array(
                    'asignacion_id'   =>$id_asignacion,
                    'concepto_pago_id'=>$id_concepto,
                    'gestion_id'=>$resuxx['id_gestion'],
                    'mes' =>$mes,
                    'monto'=>$bono,
                    
                    'fecha_pago'  =>date($anio.'-'.$mes.'-d H:i:s'),
                    'observacion' =>'',
                    'estado' =>'A',
                    'usuario_registro'=>$_user['id_user'],
                    'fecha_registro'=>date('Y-m-d H:i:s'),  
                    
                    'usuario_modificacion'=>'0',    
                    'fecha_modificacion'=>'0000-00-00 00:00:00',  
                    'nro'=>0,
                    'documento' => 'nota',
                    'tipo_pago'=> 'efectivo'
                );            
                $db->insert('rhh_movimiento_pago', $resultado);

                //echo "crear mensualidad paga";
            }

        }
        redirect('?/rrhh-planilla-pagos/ver-planilla-interna/'.$anio.'/'.$mes.'/1');
    } else {
        // Redirecciona la pagina
        redirect('?/rrhh-planilla-pagos/ver-planilla-interna/'.$anio.'/'.$mes.'');
    }
} else {
    // Error 404
    require_once not_found();
    exit;
}
?>