<br/><br/><br/><?php


$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_editar = in_array('editar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views); 
$permiso_contrato  = in_array('editar', $_views);


$id_ext = (isset($_params[0])) ? $_params[0] : 0;
//$id_gondola = (isset($params[0])) ? $params[0] : 0;

$postulante = $db->select('*')->from('per_postulacion')->where('id_postulacion',$id_ext)->fetch_first();

$persona = $db->select('*')->from('sys_persona')->where('postulante_id',$id_ext)->fetch_first();
$postulacion = $db->query(" SELECT *
                            FROM sys_persona
                            WHERE (postulante_id=0 OR postulante_id IS NULL)
                                AND
                                (
                                    (
                                        primer_apellido='".$postulante['paterno']."' AND
                                        segundo_apellido='".$postulante['materno']."' AND
                                        nombres='".$postulante['nombre']."'
                                    )
                                    OR
                                    (
                                        numero_documento='".$postulante['ci']."' AND
                                        numero_documento>0 AND
                                        numero_documento IS NOT NULL
                                    )
                                )
                            ")->fetch();

?>
<?php require_once show_template('header-design'); ?>

<style>
    .btn{
        font-family:'Circular Std Medium';
    }
</style>    
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Busqueda de datos</h2>
                <p class="pageheader-text"></p>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- ============================================================== -->
        <!-- row -->
        <!-- ============================================================== -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="?/gon-gondolas/guardar" autocomplete="off" class="form-horizontal">
                        
                        <table>
                            <tr>
                                <th>
                                    <b>Nombre del Postulante:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
                                </th>
                                <td>
                                    <?php echo $postulante['paterno']." ".$postulante['materno']." ".$postulante['nombre']; ?>
                                </td>

                                <td style="text-align: center; padding: 0 25px;" rowspan="3">
                                    <a onclick="adicional_personal('<?= $postulante['id_postulacion']; ?>');" data-toggle="tooltip" data-title="Ingresar al Personal" class="btn btn-success" style="color:#fff;"> Ingresar al Personal</a>
                                </td>
                                
                                </tr><tr>
                                <th>
                                    <b>Fecha Nacimiento:</b>
                                </th><td>
                                    <?php 
                                    $v=explode("-",$postulante['fecha_nacimiento']); 
                                    echo $v[2]."/".$v[1]."/".$v[0]; 
                                    ?>
                                </td></tr><tr><th>
                                    <b>CI:</b>
                                </th><td>
                                    <?php 
                                    echo $postulante['ci']." ".$postulante['expirado']; 
                                    ?>
                                </td>
                            </tr>
                            <tr>                            
                        </table>

                        <br>
                        <br>
                        
                        <table id="table" class="table table-bordered table-condensed table-striped table-hover">
                            <thead>
                            <tr class="active">
                                <th class="text-nowrap">#</th>
                                <th class="text-nowrap">Nombre</th>
                                <th class="text-nowrap">Fecha de nacimiento</th>
                                <th class="text-nowrap">CI</th>
                                <th class="text-nowrap">Estado</th>
                                <th class="text-nowrap">Opciones</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr class="active">
                                <th class="text-nowrap text-middle">#</th>
                                <th class="text-nowrap text-middle">Nombre</th>
                                <th class="text-nowrap text-middle">Fecha de nacimiento</th>
                                <th class="text-nowrap text-middle">CI</th>
                                <th class="text-nowrap text-middle">Estado</th>
                                <th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php foreach ($postulacion as $nro => $postulante) : ?>
                                <tr>
                                    <th class="text-nowrap"><?= $nro + 1; ?></th>
                                    <td class="text-nowrap"><?= escape($postulante['primer_apellido'])." ".escape($postulante['segundo_apellido'])." ".escape($postulante['nombres']); ?></td>
                                    <td class="text-nowrap"><?= escape($postulante['fecha_nacimiento']); ?></td>
                                    <td class="text-nowrap"><?= escape($postulante['numero_documento']); ?></td>
                                    <td class="text-nowrap"></td>
                                        <td class="text-nowrap">
                                            <a onclick="modificar_personal('<?= $postulante['id_persona']; ?>','<?= $id_ext ?>');" data-toggle="tooltip" data-title="Ingresar al Personal" class="btn btn-success" style="color:#fff;"><span class='icon-plus'></span></a>
                                        </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </form>                    
                </div>
            </div>
        </div>
    </div>
    <script src="<?= js; ?>/jquery.form-validator.min.js"></script>
    <script src="<?= js; ?>/jquery.form-validator.es.js"></script>
    <script src="<?= js; ?>/jquery.maskedinput.min.js"></script>
    <script src="<?= js; ?>/selectize.min.js"></script>
    


    <script src="<?= js; ?>/jquery.base64.js"></script>
    <script src="<?= js; ?>/pdfmake.min.js"></script>
    <script src="<?= js; ?>/vfs_fonts.js"></script>
    <!-- <script src="<?= js; ?>/jquery.dataFilters.min.js"></script> -->
    <script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
    <script src="<?= js; ?>/selectize.min.js"></script>
    <script src="<?= js; ?>/jquery.validate.js"></script>
    <script src="<?= js; ?>/matrix.form_validation.js"></script>
    <script src="<?= js; ?>/bootbox.min.js"></script>
    <script src="<?= js; ?>/educheck.js"></script>




    <script>
    function adicional_personal(id){
        bootbox.confirm('¿Está seguro de ingresarlo al personal del colegio?', function (result) {
            if(result){
                
                

                
                url="?/rrhh-postulantes/guardar-personal/"+id;
                window.location = url;



            }
        });
    }
    function modificar_personal(id, pos){
        bootbox.confirm('¿Está seguro de ingresarlo al personal del colegio?', function (result) {
            if(result){
                url="?/rrhh-postulantes/guardar-personal/"+id+"/"+pos;
                window.location = url;
            }
        });
    }



<?php if ($permiso_crear) : ?>
function asignar_postulacion(){
    
    


    

  var id_postulacion = <?php echo $postulante['id_postulacion']; ?>;
  
   <?php $newDate = date("d-m-Y", strtotime($postulante['fecha_registro'])); ?>;
 var  fecha_registro = <?php echo $newDate ?>;
 var  cargo_id = <?php echo $postulante['cargo_id']; ?>;
 var  id_persona = <?php echo $persona['id_persona']; ?>;



    $.ajax({
        url: '?/rrhh-postulantes/procesos',
        type:'POST',
       data:{
            'boton': 'asignar_postulacion',
            'id_postulacion': id_postulacion,
            'fecha_registro': fecha_registro,
            'id_persona': id_persona,
            'cargo_id': cargo_id
            },
        success: function(resp){
            //alert(resp)
            switch(resp){
                case '1': $("#modal_eliminar").modal("hide");
                            
                            alertify.success('Se Agrego el personal correctamente');break;
                case '2': $("#modal_eliminar").modal("hide");
                            alertify.error('No se pudo asignar');
                            break;
            }
        }
    })


}
<?php endif ?>




    </script>
<?php require_once show_template('footer-design'); ?>