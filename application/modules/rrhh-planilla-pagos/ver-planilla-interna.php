<?php  
$ANIO = (isset($_params[0])) ? $_params[0] : 0;
$MES = (isset($_params[1])) ? $_params[1] : 0;

        $configBD =  $db->from('rhh_configuracion')
                        ->fetch_first();

        $basico = $configBD['basico'];
        $bono_antiguedad_2 = $configBD['bono_antiguedad_5'];
        $bono_antiguedad_5 = $configBD['bono_antiguedad_8'];
        $bono_antiguedad_8 = $configBD['bono_antiguedad_11'];
        $bono_antiguedad_11 = $configBD['bono_antiguedad_15'];
        $bono_antiguedad_15 = $configBD['bono_antiguedad_20'];
        $bono_antiguedad_20 = $configBD['bono_antiguedad_25'];
        $bono_antiguedad_25 = $configBD['bono_antiguedad_mas'];

?>
<?php  require_once show_template('header-design');  ?>

<style>
.table td, .table th {
    padding: 5px;
}
.table td input, .table th  input{
    width:100%; 
    text-align:right;
    border:1px solid #ccc;
    color: #71748d;
}
</style>
 
<!--cabecera-->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">PLANILLA DE SUELDOS</h2>
            <p></p>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">RRHH</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Lista de Planilla de Sueldos </a></li>                        
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!--cuerpo card table--> 
<div class="row"> 
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        



            <div class="card-header">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-right">
                        <div class="btn-group">
                            <div class="input-group">
                                <div class="input-group-append be-addon">
                                    <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item">Seleccionar acción</a>
                                        
                                        <div class="dropdown-divider"></div>
                                        <a href="?/rrhh-planilla-pagos/listar" class="dropdown-item" > 
                                            <span class="fa fa-undo"> </span> Regresar
                                        </a>
                                                                              
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>    
                </div>
            </div>
            










        <form class="" id="form-menu" method="post" action="?/rrhh-planilla-pagos/guardar-bonos-descuentos" autocomplete="off">
            


            <input type="hidden" value="<?= $MES ?>" id="mesX" name="mesX">
            <input type="hidden" value="<?= $ANIO ?>" id="anioX" name="anioX">
                                    


            <div class="card">            
                <div class="card-body" style="overflow: auto;">
                    <input type="hidden" name="<?= $csrf; ?>">
     
                    <div style="float: left; left; padding: 0; overflow: auto;">
                    <table id="table" class="table table-bordered table-condensed table-striped table-hover">
                        <thead>
                            <tr class="active">
                                <th class="text-nowrap">#</th>
                                <th class="text-nowrap">Codigo</th>
                                <th class="text-nowrap">Apellidos y Nombres</th>
                                <th class="text-nowrap">Carnet</th>
                                <th class="text-nowrap">Nro Cta</th>
                                
                                <th class="text-nowrap">Nacimiento</th>
                                <th class="text-nowrap">Cargo</th>
                                <th class="text-nowrap">Item CNS</th>                                
                                <th class="text-nowrap">Haber<br>Basico</th>
                                <th class="text-nowrap">Bono de<br>Antiguedad</th>
                                
                                <?php
                                $resuxx = $db->query("  SELECT nombre_concepto_pago
                                                        FROM rhh_concepto_pago cp 
                                                        JOIN rhh_movimiento_pago mp ON concepto_pago_id=id_concepto_pago
                                                        WHERE   YEAR(fecha_pago)='".$ANIO."' AND MONTH(fecha_pago)='".$MES."'
                                                                AND id_concepto_pago!='1' AND tipo='BONO'

                                                        UNION 

                                                        SELECT nombre_concepto_pago
                                                        FROM rhh_concepto_pago cp2 
                                                        LEFT JOIN ins_gestion as g2 ON id_gestion=cp2.gestion_id

                                                        WHERE   gestion='".$ANIO."' AND cp2.mes='".$MES."'
                                                                AND id_concepto_pago!='1' AND tipo='BONO'

                                                        GROUP BY id_concepto_pago    
                                                     ")->fetch();
                                foreach ($resuxx as $resxx) { 
                                    $nxmex=str_replace(" ", "<br>", $resxx['nombre_concepto_pago']);
                                    echo '<th class="text-nowrap" style="text-align:center;">'.$nxmex.'</th>';                                    
                                }
                                ?>
                                
                                <th class="text-nowrap" style="background-color: #ccc;">Total<br>Ganado</th>
                                <th class="text-nowrap">Aportes<br>AFPs</th>

                                <th class="text-nowrap">Atrasos</th>
                                <th class="text-nowrap">Faltas</th>
                                <th class="text-nowrap">No Registro</th>

                                <th class="text-nowrap">Adelantos</th>
                                
                                <?php
                                $resuxx = $db->query("  SELECT nombre_concepto_pago
                                                        FROM rhh_concepto_pago cp 
                                                        JOIN rhh_movimiento_pago mp ON concepto_pago_id=id_concepto_pago
                                                        WHERE   YEAR(fecha_pago)='".$ANIO."' AND MONTH(fecha_pago)='".$MES."'
                                                                AND id_concepto_pago!='1' AND tipo='DESCUENTO'

                                                        UNION 

                                                        SELECT nombre_concepto_pago
                                                        FROM rhh_concepto_pago cp2 
                                                        LEFT JOIN ins_gestion as g2 ON id_gestion=cp2.gestion_id

                                                        WHERE   gestion='".$ANIO."' AND cp2.mes='".$MES."'
                                                                AND id_concepto_pago!='1' AND tipo='DESCUENTO'

                                                        GROUP BY id_concepto_pago  
                                                     ")->fetch();
                                foreach ($resuxx as $resxx) { 
                                    $nxmex=str_replace(" ", "<br>", $resxx['nombre_concepto_pago']);
                                    echo '<th class="text-nowrap" style="text-align:center;">'.$nxmex.'</th>';
                                }
                                ?>

                                <th class="text-nowrap" style="text-align: center;">PAGO DE<BR>MENSUALIDAD</th>
                                

                                <th class="text-nowrap" style="background-color: #ccc;">Total<br>Descuentos</th>
                                <th class="text-nowrap">Liquido<br>Pagable</th>
                                <th class="text-nowrap">Observaciones</th>
                                <th class="text-nowrap">Nro Boleta</th>             
                            </tr>
                        </thead>                         
                        <tbody> 
                            <tr class="active">                                
                                <th class="text-nowrap" colspan="10"></th>

                                <?php
                                $resuxx = $db->query("  SELECT nombre_concepto_pago, id_concepto_pago
                                                        FROM rhh_concepto_pago cp 
                                                        JOIN rhh_movimiento_pago mp ON concepto_pago_id=id_concepto_pago
                                                        WHERE   YEAR(fecha_pago)='".$ANIO."' AND MONTH(fecha_pago)='".$MES."'
                                                                AND id_concepto_pago!='1' AND tipo='BONO'

                                                        UNION 

                                                        SELECT nombre_concepto_pago, id_concepto_pago
                                                        FROM rhh_concepto_pago cp2 
                                                        LEFT JOIN ins_gestion as g2 ON id_gestion=cp2.gestion_id

                                                        WHERE   gestion='".$ANIO."' AND cp2.mes='".$MES."'
                                                                AND id_concepto_pago!='1' AND tipo='BONO'

                                                        GROUP BY id_concepto_pago  
                                                     ")->fetch();
                                foreach ($resuxx as $resxx) { 
                                    echo '<th class="text-nowrap">';                                    
                                    echo '<input type="text" value="" id="bono_'.$resxx['id_concepto_pago'].'" onchange="valorBono(\''.$resxx['id_concepto_pago'].'\');">';
                                    //echo '<input type="text" value="'.$resxx['nombre_concepto_pago'].'">';
                                    echo '</th>';                                    
                                }
                                ?>

                                <th class="text-nowrap" style="background-color: #ccc;"></th>
                                <th class="text-nowrap" colspan="5"></th>

                                <?php
                                $resuxx = $db->query("  SELECT nombre_concepto_pago, id_concepto_pago
                                                        FROM rhh_concepto_pago cp 
                                                        JOIN rhh_movimiento_pago mp ON concepto_pago_id=id_concepto_pago
                                                        WHERE   YEAR(fecha_pago)='".$ANIO."' AND MONTH(fecha_pago)='".$MES."'
                                                                AND id_concepto_pago!='1' AND tipo='DESCUENTO'
                                                
                                                        UNION 
                                                
                                                        SELECT nombre_concepto_pago, id_concepto_pago
                                                        FROM rhh_concepto_pago cp2 
                                                        LEFT JOIN ins_gestion as g2 ON id_gestion=cp2.gestion_id
                                                      
                                                        WHERE   gestion='".$ANIO."' AND cp2.mes='".$MES."'
                                                                AND id_concepto_pago!='1' AND tipo='DESCUENTO'
                                                
                                                        GROUP BY id_concepto_pago    
                                                     ")->fetch();
                                foreach ($resuxx as $resxx) { 
                                    echo '<th class="text-nowrap">';                                    
                                    echo '<input type="text" value="" id="bono_'.$resxx['id_concepto_pago'].'" onchange="valorBono(\''.$resxx['id_concepto_pago'].'\');">';
                                    //echo '<input type="text" value="'.$resxx['nombre_concepto_pago'].'">';
                                    echo '</th>';                                    
                                }
                                ?>

                                <th class="text-nowrap"></th>
                                <th class="text-nowrap" style="background-color: #ccc;"></th>
                                <th class="text-nowrap" colspan="3"></th>
                            </tr>

                            <?php 
                            $nro=0;
                            $resultados = $db->query("SELECT pp.* , p.*, c.cargo, a.sueldo_total, pos.*, a.*
                                                      FROM rrhh_planilla_pago pp 
                                                      LEFT JOIN per_asignaciones as a ON id_asignacion=asignacion_id
                                                      LEFT JOIN sys_persona as p ON id_persona=persona_id
                                                      LEFT JOIN per_postulacion as pos ON id_postulacion=postulante_id
                                                      LEFT JOIN per_cargos as c ON id_cargo=a.cargo_id
                                                      WHERE anio='".$ANIO."' AND mes='".$MES."'
                                                      ")->fetch();
                            foreach ($resultados as $resultado) { 
                                $nro++;
                                $fn = explode("-",$resultado['fecha_nacimiento']); 
                                if(count($fn)>=2){                               
                                    $fecha_nacimiento = $fn[2]."/".$fn[1]."/".$fn[0];                                
                                }
                                
                                $adelantos=0;
                                $resuxx = $db->query("SELECT pp.*
                                                          FROM rhh_movimiento_pago pp 
                                                          WHERE asignacion_id='".$resultado['id_asignacion']."' AND concepto_pago_id='1' 
                                                                AND YEAR(fecha_pago)='".$ANIO."' AND MONTH(fecha_pago)='".$MES."'
                                                          ")->fetch();
                                foreach ($resuxx as $resxx) { 
                                    $adelantos=$adelantos+$resxx['monto'];
                                }

                                $bonos=$resultado['sueldo_total'];        
                                ?>
                                    <tr>
                                        <td><?= $nro ?></td>    
                                        
                                        <input type="hidden" name="id_persona_<?= $nro; ?>" value="<?= $resultado['id_asignacion']; ?>">

                                        <td><?= $resultado['id_persona'] ?></td>    
                                        <td><?= $resultado['nombres']." ".$resultado['primer_apellido']." ".$resultado['segundo_apellido'] ?></td>    
                                        <td><?= $resultado['numero_documento'].$resultado['expedido'] ?></td>    
                                        <td><?= $resultado['cuenta_bancaria'] ?></td>    
                                        
                                        <td><?= $fecha_nacimiento ?></td>    
                                        <td><?= $resultado['cargo'] ?></td>    
                                        <td><?= $resultado['cns'] ?></td>    
                                        <td style="text-align: right;" id="sb_<?= $nro; ?>"><?= number_format($resultado['sueldo_total'],2,'.','') ?></td>    

                                        <!--Bono antiguedad-->
                                        <td style="text-align: right;" id="bono1_<?= $nro; ?>">
                                            <?php 
                                                $xx=explode("-",$resultado['fecha_inicio']); 
                                                
                                                $yy=intval($xx[0]); 
                                                $zz=intval($xx[1]); 
                                                $kk=intval($xx[2]); 

                                                if( ($ANIO-$yy>25) || ($ANIO-$yy>=25 && $MES-$zz>0) || ($ANIO-$yy>=25 && $MES-$zz>=0 && $kk==1) ){
                                                    $bono_antiguedad=($bono_antiguedad_25*$basico*3)/100;
                                                }
                                                else{
                                                    if( ($ANIO-$yy>20) || ($ANIO-$yy>=20 && $MES-$zz>0) || ($ANIO-$yy>=20 && $MES-$zz>=0 && $kk==1) ){
                                                        $bono_antiguedad=($bono_antiguedad_20*$basico*3)/100;
                                                    }
                                                    else{
                                                        if( ($ANIO-$yy>15) || ($ANIO-$yy>=15 && $MES-$zz>0) || ($ANIO-$yy>=15 && $MES-$zz>=0 && $kk==1) ){
                                                            $bono_antiguedad=($bono_antiguedad_15*$basico*3)/100;
                                                        }
                                                        else{
                                                            if( ($ANIO-$yy>11) || ($ANIO-$yy>=11 && $MES-$zz>0) || ($ANIO-$yy>=11 && $MES-$zz>=0 && $kk==1) ){
                                                                $bono_antiguedad=($bono_antiguedad_11*$basico*3)/100;
                                                            }
                                                            else{
                                                                if( ($ANIO-$yy>8) || ($ANIO-$yy>=8 && $MES-$zz>0) || ($ANIO-$yy>=8 && $MES-$zz>=0 && $kk==1) ){
                                                                    $bono_antiguedad=($bono_antiguedad_8*$basico*3)/100;
                                                                }
                                                                else{
                                                                    if( ($ANIO-$yy>5) || ($ANIO-$yy>=5 && $MES-$zz>0) || ($ANIO-$yy>=5 && $MES-$zz>=0 && $kk==1) ){
                                                                        $bono_antiguedad=($bono_antiguedad_5*$basico*3)/100;
                                                                    }
                                                                    else{
                                                                        if( ($ANIO-$yy>2) || ($ANIO-$yy>=2 && $MES-$zz>0) || ($ANIO-$yy>=2 && $MES-$zz>=0 && $kk==1) ){
                                                                            $bono_antiguedad=($bono_antiguedad_2*$basico*3)/100;
                                                                        }
                                                                        else{
                                                                            $bono_antiguedad=0;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                echo number_format($bono_antiguedad,2,'.','');
                                                $bonos=$bonos+$bono_antiguedad;
                                            ?>   
                                        </td>
                                        <!--Bono antiguedad-->
                                        
                                        <?php
                                        $afp=0.1271*($resultado['sueldo_total']+$bono_antiguedad);

                                        $resuxx = $db->query("  SELECT nombre_concepto_pago, id_concepto_pago
                                                                FROM rhh_concepto_pago cp 
                                                                JOIN rhh_movimiento_pago mp ON concepto_pago_id=id_concepto_pago
                                                                WHERE   YEAR(fecha_pago)='".$ANIO."' AND MONTH(fecha_pago)='".$MES."'
                                                                        AND id_concepto_pago!='1' AND tipo='BONO'
                                                        
                                                                UNION 
                                                        
                                                                SELECT nombre_concepto_pago, id_concepto_pago
                                                                FROM rhh_concepto_pago cp2 
                                                                LEFT JOIN ins_gestion as g2 ON id_gestion=cp2.gestion_id                                                              
                                                                WHERE   gestion='".$ANIO."' AND cp2.mes='".$MES."'
                                                                        AND id_concepto_pago!='1' AND tipo='BONO'
                                                        
                                                                GROUP BY id_concepto_pago   
                                                     ")->fetch();
                                        
                                        $nrie=0;
                                        foreach ($resuxx as $resxx) { 
                                            $nrie++;
                                            $montoX=0;
                                            $resuyy = $db->query("SELECT pp.*
                                                          FROM rhh_movimiento_pago pp 
                                                          WHERE asignacion_id='".$resultado['id_asignacion']."' AND concepto_pago_id='".$resxx['id_concepto_pago']."' 
                                                                AND YEAR(fecha_pago)='".$ANIO."' AND MONTH(fecha_pago)='".$MES."'                                                
                                                         ")->fetch();
                                            foreach ($resuyy as $resyy) { 
                                                $montoX=$montoX+$resyy['monto'];
                                            }
                                            echo '<td class="text-nowrap">';
                                            echo '<input type="hidden" value="'.$resxx['id_concepto_pago'].'" name="concepto_id_'.$nro.'_'.$nrie.'">';
                                            echo '<input type="text" value="'.number_format($montoX,2,'.','').'" class="bono_'.$resxx['id_concepto_pago'].'" id="bono2_'.$nro.'_'.$nrie.'" name="bono2_'.$nro.'_'.$nrie.'" onchange="calcularLinea('.$nro.')">';
                                            echo '</td>';
                                            $bonos+=$montoX;
                                        }
                                        ?>

                                        <td style="text-align: right; background-color: #ccc;" id="bonoX_<?= $nro; ?>"><?= number_format($bonos,2,'.','') ?></td>    
                                        <td style="text-align: right;" id="descuento1_<?= $nro; ?>"><?= number_format($afp,2,'.','') ?></td>    
                                        
                                        <td style="text-align: right;" id="descuento2_<?= $nro; ?>"><?= number_format($resultado['atrasos'],2,'.','') ?></td>    
                                        <td style="text-align: right;" id="descuento3_<?= $nro; ?>"><?= number_format($resultado['faltas'],2,'.','') ?></td>    
                                        
                                        <td style="text-align: right;" id="descuento4_<?= $nro; ?>">0.00</td>    
                                        
                                        <td style="text-align: right;" id="descuento5_<?= $nro; ?>"><?= number_format($adelantos,2,'.','') ?></td>
                                        
                                        <?php
                                        $resuxx = $db->query("  SELECT nombre_concepto_pago, id_concepto_pago
                                                                FROM rhh_concepto_pago cp 
                                                                JOIN rhh_movimiento_pago mp ON concepto_pago_id=id_concepto_pago
                                                                WHERE   YEAR(fecha_pago)='".$ANIO."' AND MONTH(fecha_pago)='".$MES."'
                                                                        AND id_concepto_pago!='1' AND tipo='DESCUENTO'
                                                        
                                                                UNION 
                                                        
                                                                SELECT nombre_concepto_pago, id_concepto_pago
                                                                FROM rhh_concepto_pago cp2 
                                                                LEFT JOIN ins_gestion as g2 ON id_gestion=cp2.gestion_id                                                              
                                                                WHERE   gestion='".$ANIO."' AND cp2.mes='".$MES."'
                                                                        AND id_concepto_pago!='1' AND tipo='DESCUENTO'
                                                        
                                                                GROUP BY id_concepto_pago    
                                                     ")->fetch();
                                        

                                        $total_descuentos=$afp+$resultado['atrasos']+$resultado['faltas']+$adelantos;
                                
                                        $nrye=0;
                                        foreach ($resuxx as $resxx) { 
                                            $nrye++;
                                            $montoX=0;
                                            $resuyy = $db->query("SELECT pp.*
                                                          FROM rhh_movimiento_pago pp 
                                                          WHERE asignacion_id='".$resultado['id_asignacion']."' AND concepto_pago_id='".$resxx['id_concepto_pago']."' 
                                                                AND YEAR(fecha_pago)='".$ANIO."' AND MONTH(fecha_pago)='".$MES."'                                                
                                                         ")->fetch();
                                            foreach ($resuyy as $resyy) { 
                                                $montoX=$montoX+$resyy['monto'];
                                            }
                                            echo '<td class="text-nowrap">';
                                            
                                            echo '<input type="hidden" value="'.$resxx['id_concepto_pago'].'" name="descuento_id_'.$nro.'_'.$nrye.'">';
                                            
                                            echo '<input type="text" value="'.number_format($montoX,2,'.','').'" id="descuentoY_'.$nro.'_'.$nrye.'" class="bono_'.$resxx['id_concepto_pago'].'" name="descuento2_'.$nro.'_'.$nrye.'" onchange="calcularLinea('.$nro.')">';
                                            
                                            echo '</td>';
                                            $total_descuentos=$total_descuentos+$montoX;
                                        }
                                        
                                        

                                        $montoX=0;
                                        $resuyy = $db->query("SELECT pp.*
                                                      FROM rhh_movimiento_pago pp 
                                                      WHERE asignacion_id='".$resultado['id_asignacion']."' AND concepto_pago_id='-1' 
                                                            AND YEAR(fecha_pago)='".$ANIO."' AND MONTH(fecha_pago)='".$MES."'                                                
                                                     ")->fetch();
                                        foreach ($resuyy as $resyy) { 
                                            $montoX=$montoX+$resyy['monto'];
                                        }
                                    
                                        if($montoX==0){
                                            $query_nro_hijos = $db->query("SELECT count(id_estudiante) as nro_hijos
                                                          FROM ins_familiar f
                                                          INNER JOIN ins_estudiante_familiar ef ON id_familiar=familiar_id
                                                          INNER JOIN ins_estudiante e ON id_estudiante=estudiante_id
                                                          WHERE f.persona_id='".$resultado['id_persona']."'              
                                                         ")->fetch_first();

                                            $mensualidad=$query_nro_hijos["nro_hijos"]*100;
                                        }
                                        else{
                                            $mensualidad=$montoX;
                                        }
                                        ?>

                                        <td style="text-align: right;">
                                            <input type="text" value="<?= number_format($mensualidad,2,'.','') ?>" name="Mensualidad_<?= $nro; ?>" id="Mensualidad_<?= $nro; ?>" onchange="calcularLinea('<?php echo $nro; ?>')">
                                        </td>                                    

                                        <?php
                                        $total_descuentos=$total_descuentos+$mensualidad;
                                        ?>

                                        <td style="text-align: right; background-color: #CCC;" id="descuentoX_<?= $nro; ?>"><?= number_format($total_descuentos,2,'.','') ?></td>
                                        <td style="text-align: right;" id="totalX_<?= $nro; ?>"><?= number_format($resultado['sueldo_total']-$total_descuentos,2,'.','') ?></td>    
                                        <td></td>    
                                        <td><?= $resultado['id_planilla'] ?></td>    
                                    </tr>    
                                <?php
                            } 
                            ?>

                            <input type="hidden" value="<?= $nro ?>" name="nro_lines">
                            <input type="hidden" value="<?= $nrie ?>" name="nro_bonos">
                            <input type="hidden" value="<?= $nrye ?>" name="nro_desc">

                        </tbody>
                    </table>
                </div>
            </div>
        </form> 

        <div class="modal-footer">    
            <!--button type="submit" class="btn btn-default" data-dismiss="modal" aria-label="Cerrarlose">
                <span class="glyphicon glyphicon-floppy-disk"></span>
                <span>Cerrar</span>
            </button-->
            <a href="?/rrhh-planilla-pagos/imprimir-planilla/<?php echo $ANIO."/".$MES; ?>" target="_blank">    
                <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="">
                    <span class="glyphicon glyphicon-floppy-disk"></span>
                    <span>Imprimir</span>
                </button>
            </a>
            <button type="submit" class="btn btn-primary" id="btn_guardar">
                <span class="glyphicon glyphicon-floppy-disk"></span>
                <span>Guardar</span>
            </button>                    
        </div>
    </div>
</div>

<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/js/jquery.validate.js"></script>
<script src="<?= js; ?>/jquery.maskedinput.min.js"></script>
<!--libs-->
<script src="<?= js; ?>/selectize.min.js"></script>
 
<script src="assets/themes/concept/assets/vendor/multi-select/js/jquery.multi-select.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>

<script>
function valorBono(x){
    y=$("#bono_"+x).val();
    y = parseFloat(y).toFixed(2);            
    $(".bono_"+x).val(y);
    
    bb=<?php echo $nro; ?>;
    
    for(i=1;i<=bb;i++){
        calcularLinea(i);
    }
}
function calcularLinea(i){
    cc=<?php echo $nrie; ?>;
    dd=<?php echo $nrye; ?>;

    acum= parseFloat($("#sb_"+i).html());
    acum+= parseFloat($("#bono1_"+i).html());

    for(k=1;k<=cc;k++){
        acum+= parseFloat($("#bono2_"+i+"_"+k).val());
    }

    $("#bonoX_"+i).html(acum.toFixed(2));

    desc= acum*0.1271;

    $("#descuento1_"+i).html(desc.toFixed(2));
    desc+= parseFloat($("#descuento2_"+i).html());
    desc+= parseFloat($("#descuento3_"+i).html());
    desc+= parseFloat($("#descuento4_"+i).html());
    desc+= parseFloat($("#descuento5_"+i).html());

    for(k=1;k<=dd;k++){
        desc+= parseFloat($("#descuentoY_"+i+"_"+k).val());
    }

    desc+=parseFloat($("#Mensualidad_"+i).val());

    $("#descuentoX_"+i).html(desc.toFixed(2)); 
    $("#totalX_"+i).html( (acum-desc).toFixed(2) ); 
}
function copy(i){
    
}
</script>