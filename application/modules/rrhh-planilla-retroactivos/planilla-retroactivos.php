<?php
$permiso_imprimir     = in_array('modalcontrato', $_views);

$ANIO = (isset($_params[0])) ? $_params[0] : 0;
?>
<?php  require_once show_template('header-design');  ?>
 
<!--cabecera-->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">PLANILLA DE RETROACTIVOS - 
                <?= $ANIO ?>                
            </h2>            
        </div>
    </div>
</div>

<!--cuerpo card table--> 
<div class="row"> 
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">            
            <div class="card-header">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-right">
                        <div class="text-label hidden-xs">Seleccionar acción:</div>
                        <div class="btn-group">
                            <div class="input-group">
                                <div class="input-group-append be-addon">
                                    <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item">Seleccionar acción</a>
                                        
                                        <?php if ($permiso_imprimir) : ?>
                                            <div class="dropdown-divider"></div>
                                            <a href="?/rrhh-planilla-retroactivos/imprimir-planilla/<?= $ANIO ?>" target="_blank" class="dropdown-item" > 
                                                <span class="fa fa-plus"> </span> Imprimir Planilla de Retroactivos
                                            </a>
                                            <!--div class="dropdown-divider"></div>
                                            <a href="?/rrhh-planilla-pagos/ver-planilla-interna/<?= $ANIO ?>/<?= $MES ?>" target="_blank" class="dropdown-item" > 
                                                <span class="fa fa-plus"> </span> Ver Planilla
                                            </a-->
                                        <?php endif ?>  
                                        
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>    
                </div>
            </div>
            <div class="card-body">
                <input type="hidden" name="<?= $csrf; ?>">
 
                <form class="" id="form-menu" method="post" action="?/s-curso-paralelo/guardar" autocomplete="off">
                    <input type="hidden" name="<?= $csrf; ?>">
                    
                    <?php //if ($horarios) : ?>
                	<table id="table" class="table table-bordered table-condensed table-striped table-hover">
                		<thead>
                			<tr class="active">
                				<th class="text-nowrap">#</th>
                				<th class="text-nowrap">Nombre</th>
                				<th class="text-nowrap">Cargo</th>
                                <th class="text-nowrap">Fecha Inicio</th>
                                <th class="text-nowrap">Sueldo Base</th>
                                <th class="text-nowrap">Incremento</th>
                                <th class="text-nowrap">Monto</th>
                                <th class="text-nowrap">Operaciones</th>             
                			</tr>
                		</thead>                		 
                		<tbody> 
                            <?php 
                            $nro=0;
                            $resultados = $db->query("SELECT pp.* , p.*, a.sueldo_total, a.fecha_inicio
                                                      FROM rrhh_retroactivos pp 
                                                      LEFT JOIN per_asignaciones as a ON id_asignacion=asignacion_id
                                                      LEFT JOIN sys_persona as p ON id_persona=persona_id
                                                      WHERE anio='".$ANIO."' 
                                                      ")->fetch();
                            foreach ($resultados as $resultado) { 
                                $nro++;
                                ?>
                                <tr>
                                    <td><?= $nro ?></td>    
                                    <td><?= $resultado['nombres']." ".$resultado['primer_apellido']." ".$resultado['segundo_apellido'] ?></td>    
                                    <td><?= $resultado['cargo'] ?></td>    
                                    <td><?= $resultado['fecha_inicio'] ?></td>    
                                    <td><?= $resultado['sueldo_total'] ?></td>    
                                    <td><?= $resultado['incremento'] ?>%</td>    
                                    <td><?= $resultado['sueldo'] ?></td>    
                                    <td>
                                        <a href="?/rrhh-planilla-retroactivos/imprimir-boleta-interna/<?= $resultado['id_retroactivo'] ?>" target="_blank" class="btn btn-success btn-xs"><span class="fa fa-file"></span> Boleta</a>
                                    </td>    
                                </tr>    
                            <?php
                            } 
                            ?>
                		</tbody>
                	</table>
                </form> 
            </div>
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
<!--<script src="application/modules/generador-menus/generador-menu.js"></script>    -->
<script>   
var dataTable = $('#table').DataTable({    
    "lengthChange": true,
    "responsive": true
});        
</script> 
<style>
    .ajs-message.ajs-custom { color: #31708f;  background-color: #d9edf7;  border-color: #31708f; }
</style>