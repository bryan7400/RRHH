<?php 

$rol_actual = $_user['rol_id'];
$id_user = $_user['id_user']; 
             
 $nombre_dominio = escape($_institution['nombre_dominio']);
$persona_actual = $_user['persona_id'];

//::::::LISTAR SI ES PAPA::::::::::::::::::::::::::::::::::::::::::::
$hijos='';$esttutor='';$familiar=array();
if($rol_actual=='6'){
    
    $id_padre=isset($_user['id_persona'])?$_user['id_persona']:0;

    $familiar = $db->query("SELECT us.id_user,us.username,per.id_persona,per.genero,per.nombres
            from ins_familiar fa 
            INNER JOIN ins_estudiante_familiar ef on ef.familiar_id=fa.id_familiar
            INNER JOIN ins_estudiante est ON est.id_estudiante=ef.estudiante_id
            INNER JOIN sys_users us ON est.persona_id=us.persona_id
            INNER JOIN sys_persona per ON per.id_persona=est.persona_id
             WHERE fa.persona_id=".$id_padre)->fetch();

 
    //ver si en usurarios esta el hijo
    foreach($familiar as $hijo){
        $hijo=$hijo['id_user'];
        $hijos.=" com.persona_id LIKE '%,$hijo,%' OR ";
          
    }
    //para que reciba not cuando se envie a rol hijos,si es padre recibe el de rol estudiantes
     $esttutor=" or com.usuarios LIKE '%,5,%' ";
}
 

//:::::::::: listar comunicados y marcarlos como leidos ::::::::::::::::::::
//restringir en caso de papas u otro
 $comunicados = $db->query("SELECT com.*,su.username 
FROM ins_comunicados com 
 
 LEFT JOIN sys_users su ON su.id_user=com.usuario_registro 
 
        WHERE (com.persona_id LIKE '%,$id_user,%' OR ".$hijos."
         com.usuarios LIKE '%,$rol_actual,%' ".$esttutor." )AND 
          
         com.estado='A' 
         ORDER BY com.fecha_inicio desc")->fetch();//OR
    // com.grupo='t' OR com.grupo='$genero' docentes:  and  $sqlAsignacionesdocente and com.`asignacion_docente_id`=$asigancion_docente_id  com.fecha_final>=date_sub(CURDATE(), INTERVAL 2 DAY) and
  
 $com = array(); 
      foreach($comunicados as $val){
             $grupo=$val['grupo'];//tipo todos,si
             $vista_personas_id=$val['vista_personas_id'];
             $usuarios=$val['usuarios'];
             $persona_id=$val['persona_id'];
             $agregado=false;
             if($grupo=='t' || $grupo=='m' || $grupo=='v'){
                 //array_push($com,$val);
                 $agregado=true;
             }else{
                 
                 $arr_p=explode(',',$persona_id); 
                 $arr_u=explode(',',$usuarios);
             
                 if(in_array($id_user,$arr_p) || in_array($rol_actual,$arr_u) ){ 
                    
                     $agregado=true;
                 }
                 //si es papa y el rol es estudiante 
                 if($rol_actual=='6'){
                     if(in_array('5',$arr_u)){
                           $agregado=true;
                     }
                 }
                 
                 //sisus hijo
                 foreach($familiar as $hijo){
                   if(in_array($hijo['id_user'],$arr_p)){
                       $agregado=true;
                      
                   }
                 }
             }
           
          
        if($agregado){
            //if()
              array_push($com,$val);
            
             //MARCAR COMO LEIDOs
              $arr_v=explode(',',$vista_personas_id);
            if(!in_array($id_user,$arr_v)){
                $id_comunicado=$val['id_comunicado'];
                $sqlp="UPDATE ins_comunicados
                SET vista_personas_id = CONCAT(vista_personas_id,'$id_user,')
                where id_comunicado= $id_comunicado;";

                $leido=$db->query($sqlp)->execute();  
            }
            //:::::::::::::::::::::::::::::::::::::::::::::
            /*$id_emisorcomunicado = $comunicado['usuario_registro'];
              $fecha_inicio = explode(' ', $comunicado['fecha_inicio']);
              $fecha_final = explode(' ', $comunicado['fecha_final']);

              $array['cantLeidos'] = $comunicado['cantLeidos'];
              $array['id_comunicado'] = $comunicado['id_comunicado'];
              $array['codigo'] = $comunicado['codigo'];
              $array['fecha_inicio'] = $fecha_inicio[1] . ' ' . date_decode($fecha_inicio[0], $_institution['formato']);
              $array['fecha_final'] = $fecha_final[1] . ' ' . date_decode($fecha_final[0], $_institution['formato']);
              $array['nombre_evento'] = $comunicado['nombre_evento'];
              $array['descripcion'] = $comunicado['descripcion'];
              $array['color'] = $comunicado['color'];
              //$array['usuariosStr'] = $cadena_limpia;
              $array['usuarios'] = $comunicado['usuarios'];
              $array['estados'] = $comunicado['estados'];
              $array['persona_id'] = $comunicado['persona_id'];
              $array['estado'] = $comunicado['estado'];
              $array['file'] = $comunicado['file'];
              $array['prioridad'] = $comunicado['prioridad'];
              $array['aula_paralelo_asignacion_materia_id'] = $comunicado['aula_paralelo_asignacion_materia_id'];
              $array['modo_calificacion_id'] = $comunicado['modo_calificacion_id'];
              $array['grupo'] = $comunicado['grupo'];
              $array['useremisor'] = $comunicado['username'];
              array_push($com, $array); //agrega la nueva fila en el array
              $cadena_usuarios = "";*/
              
              //:::::::::::::::::::::::::::::::::::::
          }
     }
     




/*
$comunicados = $db->query("SELECT (SELECT COUNT(comunicado_id) 
FROM not_notificaciones noti WHERE noti.comunicado_id=z.id_comunicado)AS cantLeidos,
su.username, z.* from ins_comunicados z LEFT JOIN sys_users su ON su.id_user=z.usuario_registro 
where z.estado='A' order by z.id_comunicado  desc")->fetch(); 

    $com = array(); 
    $cadena_usuarios = ""; 
    //armando el nuevo array con los roles respectivos
    foreach ($comunicados as $key => $comunicado) {
      $cadena_limpia = '';
      //captura los roles del comunicado
      $id = $comunicado['usuarios'];
      $est = $comunicado['estados'];
      $persona_id = $comunicado['persona_id'];

      if($persona_id!=''){
          $cadena_limpia='Sin usuarios';
      }else{
          $ests     = explode(',', $est);//conveirto en array []
          $usuarios = explode(',', $id);//conveirto en array []
          $contador = sizeof($usuarios); //cuenta la cantidad de usuarios /

          for($i = 0; $i < $contador; $i++){
            if($usuarios[$i]==$rol_actual){// && $ests[$i]=='SI'
              $id_emisorcomunicado = $comunicado['usuario_registro'];
              $fecha_inicio = explode(' ', $comunicado['fecha_inicio']);
              $fecha_final = explode(' ', $comunicado['fecha_final']);

              $array['cantLeidos'] = $comunicado['cantLeidos'];
              $array['id_comunicado'] = $comunicado['id_comunicado'];
              $array['codigo'] = $comunicado['codigo'];
              $array['fecha_inicio'] = $fecha_inicio[1] . ' ' . date_decode($fecha_inicio[0], $_institution['formato']);
              $array['fecha_final'] = $fecha_final[1] . ' ' . date_decode($fecha_final[0], $_institution['formato']);
              $array['nombre_evento'] = $comunicado['nombre_evento'];
              $array['descripcion'] = $comunicado['descripcion'];
              $array['color'] = $comunicado['color'];
              //$array['usuariosStr'] = $cadena_limpia;
              $array['usuarios'] = $comunicado['usuarios'];
              $array['estados'] = $comunicado['estados'];
              $array['persona_id'] = $comunicado['persona_id'];
              $array['estado'] = $comunicado['estado'];
              $array['file'] = $comunicado['file'];
              $array['prioridad'] = $comunicado['prioridad'];
              $array['aula_paralelo_asignacion_materia_id'] = $comunicado['aula_paralelo_asignacion_materia_id'];
              $array['modo_calificacion_id'] = $comunicado['modo_calificacion_id'];
              $array['grupo'] = $comunicado['grupo'];
              $array['useremisor'] = $comunicado['username'];
              array_push($com, $array); //agrega la nueva fila en el array
              $cadena_usuarios = "";
            
            }

          }

      }
      
    }*/
?>
<?php require_once show_template('header-design');
?>
<style>
tr:hover{
color: white;
background: #25d5f2; 

}
.rowTablaDetalles:hover{
cursor: pointer;

}
</style>
<div class=" inicioMensajes row">
<div class="col-xl-8 col-lg-8 col-md-7 col-sm-7 col-12 ">
<div class="card">
    <div class="card-body">
        <div class="main-contentX container-fluid p-0">
            <div class="email-inbox-header">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="email-title"><span class="icon"><i class="fas fa-inbox"></i></span> Comunicados <span class="new-messages"></span> </div>
                    </div>
                    <div class="col-lg-6">
                        <!-- <div class="email-search">
                            <div class="input-group input-search">
                                <button class="btn btn-info">Imprimir</button>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
            <table class="table" id="TablaComunicados">
                <thead>
                    <tr>
                        <th width="4%">#</th>
                        <th width="4%">Prioridad</th>
                        <th width="15%">Remitente</th>
                        <th width="40%">Comunicado</th>
                        <th width="20%">Archivo Adjunto</th>
                        <th width="17%">Fecha de Validez</th>
                    </tr>
                </thead>
            <tbody>
            <?php foreach ($com as $key => $value): ?>
                <?php 
                   $priori = '';
                   if($value['prioridad']==1){
                    $priori = "<font class='text-primary'>BAJA</font>";
                   }else if($value['prioridad']==2){
                    $priori = "<font class='text-warning'>MEDIA</font>";
                   }else if($value['prioridad']==3){
                     $priori = "<font class='text-danger'>ALTA</font>";
                   }
                ?>
               <tr>
                    <td><?= $key+1; ?></td>
                    <td><?= $priori;?> </td>
                    <td><?php //if($NOMBREhiJO==''){
                    echo $value['username'];
                    //}else{echo $NOMBREhiJO;}?> </td>
                    <td><div onclick="leerComunicado(1,'Titulo','Reunion de maestros','archivo')" class="rowTablaDetalles"><span class="from"><b><?= $value['nombre_evento']?></b></span>
                    <p class="msg"><i><?= $value['descripcion']?></i></p></div></td>
                    <td><a href="files/<?=$nombre_dominio;?>/comunicados/<?= $value['file']?>"><span class="icon"><i class="fas fa-paperclip"> <?= $value['file']?> </i></span></a></td>
                    <td><?= $value['fecha_inicio']?> a <?= $value['fecha_final']?></td>
                </tr>
                <!-- <tr>
                    <td>99</td>
                    <td><div onclick="leerComunicado(1,'Titulo','Reunion de maestros','archivo')" class="rowTablaDetalles"><span class="from">Comunicados</span>
                    <p class="msg">La bandeja de comunicados se encuentra vacia.</p></div></td>
                    <td><span class="icon"><i class="fas fa-paperclip"></i></span></td>
                    <td>28 jul</td>
                    <td>28 jul</td>
                </tr> -->
            <?php endforeach ?>
                
            </tbody>
        </table>
    </div>

</div>
</div>
</div>

<div class="col-xl-4 col-lg-4 col-md-5 col-sm-5 col-12">
<div class="card">
<div class="card-body">
    <aside class="page-asideX">
        <div class="aside-content">
            <br>
            <!-- <div class="aside-header">
                <button class="navbar-toggle" data-target=".aside-nav" data-toggle="collapse" type="button"><span class="icon"><i class="fas fa-caret-down"></i></span></button><span class="title">Comunicados</span>
                <p class="description">Estados de notificaciones</p>
            </div> -->
            <!-- <div class="aside-compose"><a class="btn btn-lg btn-secondary btn-block" href="#">Comunicados</a></div> -->
            <div class="aside-nav collapse">
                <span class="title">Tipos de Comunicados</span>
                <ul class="nav">
                    <li class="active"><a href="#"><span class="icon"><i class="fas fa-fw fa-inbox"></i></span>Comunicados Docente<span class="badge badge-primary float-right">8</span></a></li>
                    <li><a href="#"><span class="icon"><i class="fas fa-fw  fa-envelope"></i></span>Comunicados Administrativos<span class="badge badge-secondary float-right">4</span></a></li>
                    <li><a href="#"><span class="icon"><i class="fas fa-fw fa-trash"></i></span>Eliminados</a></li>
                    </ul><span class="title">Comunicados según importancia</span>
                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="#"><i class="m-r-10 mdi mdi-label text-danger"></i>
                        Alta </a></li>
                        <li><a href="#">
                        <i class="m-r-10 mdi mdi-label text-warning"></i> Media   </a></li>
                        <li><a href="#"> <i class="m-r-10 mdi mdi-label text-primary"></i>
                        Baja </a></li>
                    </ul>
                </div>
            </div>
        </aside>
    </div>
</div>
</div>
</div>

<div class=" detalleMensajes row"  style="display:none">
    
    <div class="card" style="width: 100%;">        
        <div class="card-body">
            <aside class="page-asideX">
                <div class="aside-content">
                    <div class="aside-header">
                        <button class="navbar-toggle" data-target=".aside-nav" data-toggle="collapse" type="button"><span class="icon"><i class="fas fa-caret-down"></i></span>
                        </button><span class="title">Comunicado </span>                        
                    </div>
                    <div class="row">
                        <div class="col-sm-8 hidden-xs">
                            <div class="text-label">Para volver al listado de los comunicados hacer clic en el siguiente botón: </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 text-right">
                            <div class="aside-compose"><a onclick="verlistadoComunic()" class="btn btn-md btn-success btn-block text-light">Listado Comunicados</a></div>
                        </div>
                    </div>                    
                    <hr>    
                    <div class="aside-nav collapse">
                        <div class="notification-info">
                            <div class="notification-list-user-img">
                                <img src="<?= imgs ?>/avatar.jpg" alt="" class="user-avatar-md rounded-circle">&nbsp;&nbsp;&nbsp;&nbsp;
                            </div>
                        </div>
                        <span class="title" id="comunicado_leer" data-id-comunicado=""> Titulo comunicado</span>
                        <ul class="nav nav-pills nav-stacked">
                            <li id="comunicado_descripcion"><i class="m-r-10 mdi mdi-label text-secondary"></i>
                            Descripción </li>
                            <li>
                                <a href="#"><i class="m-r-10 mdi mdi-label text-primary"></i>Archivo Adjunto   </a>
                                <iframe id="documento" src="https://docs.google.com/gview?url=https://www.adobe.com/support/ovation/ts/docs/ovation_test_show.ppt&embedded=true"
                                style="width: 90%; height: 1000px">
                                    <p>Your browser does not support iframes.</p>
                                </iframe>
                            </li>
                            <li><a href="#"> 
                                <i class="m-r-10 mdi mdi-label text-brand"></i>
                                <i id="destinatario"></i> </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
<script>
var dataTableCom = $('#TablaComunicados').DataTable({
    language: dataTableTraduccion,
    stateSave:true,
    "lengthChange": true,
    "responsive": true
} );

listar_paralelos_tabla();

function listar_paralelos_tabla() {
  var boton='';
  $.ajax({
    url: '?/s-notificaciones/procesos',
    type: 'POST',
    data: {'accion':'listar_notificaciones'},
    dataType: 'JSON',
    success: function(resp){
        //console.log(resp);
        var counterDoc=0;
        var counterAdm=0;
        var counterAdminpers=0;
        dataTableCom.clear().draw();

for (var i = 0; i < resp.length; i++) {
    var star='';
    var html = '';
    var prioridad='';
    var color = "";
    if(resp[i]['prioridad']==1){
    prioridad='REGULAR';
    color = "primary";
    }else if(resp[i]['prioridad']==2){
    prioridad='IMPORTANTE';
    color = "warning";
    }else if(resp[i]['prioridad']==3){
    prioridad='URGENTE';
    color = "danger";
    }

    var estadisticaleidos = '<div class="progress" title="Comunicado, click para mas detalles.">'+
            ' <span class="fa fa-envelope badge badge-'+color+'" > ' + 
                resp[i]['nombre_evento'].toUpperCase() + ' - ' + prioridad + '</span>'+
                '<div class="progress-bar bg-info" style="width:' + resp[i]['cantLeidos'] + '0%" role="progressbar"></div>' +
            ' <span class="fa fa-eye  badge badge-danger" style=" background-color: #dcdde2;color: #71748d;"></span></div>';
    
    var adjunto='';
    if(resp[i]['file']!='' || resp[i]['file']!=null || resp[i]['file']!=0){
        var icon='';
        var extencion=resp[i]['file'].split(".");
        var imgs = "<?= imgs; ?>";
        //se verifica tipo de archivo para <i>
        if(extencion[1]=='docx' || extencion[1]=='doc'){
            icon='<i class="fa fa-file-word badge" style="font-size: 50px;color:#0000ad;display:block;"></i>';
        }else if(extencion[1]=='xlsx' || extencion[1]=='xls'){
            icon='<i class="fa fa-file-excel badge" style="font-size: 48px;color:green"display:block;></i>';
        }else if(extencion[1]=='jpg'){
            icon='<i class="fa fa-file-image badge" style="font-size: 48px;color:orange;display:block;"></i>';
        }
        //se valida si existe un archivo adjunto
        adjunto = (extencion[1] == '' || extencion[1] == null ) ? "" : '<a class="btn text-primary" href="files/<?=$nombre_dominio;?>/comunicados/'+resp[i]['file']+'" dowload="ADJ.'+extencion[1]+'"> '+icon+ resp[i]['file']+'</a>';
    }

    var btnacciones = "";
    //contenido
    var contenido = resp[i]['id_comunicado'] + "*" + resp[i]['codigo']+ "*" + resp[i]['fecha_inicio']+ "*" + resp[i]['fecha_final']+ "*" + resp[i]['nombre_evento']+ "*" + resp[i]['descripcion']+ "*" + resp[i]['color']+ "*" + resp[i]['usuarios']+ "*" + resp[i]['estados']+ "*" + resp[i]['ids']+"*"+resp[i]['persona_id']+"*"+resp[i]['file']+"*"+resp[i]['prioridad'];
    <?php if (true) : ?>
        var btneliminar="<a href='?/s-comunicados/eliminar/"+ resp[i]['id_comunicado'] +"' class='btn btn-danger btn-xs' data-eliminar='true'><span class='icon-trash'></span></a>";
    <?php endif ?>

    if(true){
        //console.log("otros");
        if(resp[i]['grupo']=='t'){
            spantipo='<span class="badge badge-pill" style="background:#f0f0f8;"><span style="color: #808080;" class="icon-people"></span> Todos</span>';
        }else if(resp[i]['grupo']=='m'){
            spantipo='<span class="badge badge-pill " style="background:#ff5fef;color:#ffffff"><span style="color: #ffffff;" class="icon-user-female"></span> Niñas</span>';
        }else if(resp[i]['grupo']=='v'){
            spantipo='<span class="badge badge-pill" style="background:#5969ff;color:#ffffff"><span style="color: ffffff;" class="icon-user"></span> Niños</span>';
        }else if(resp[i]['grupo']=='selec'){
            spantipo='<span class="badge badge-pill " style="background:#ffc108"><span style="color: #000008;" class="icon-pin"></span> Seleccionados</span>';
        }
    
        var datosCol='<div onclick="leerComunicado('+resp[i]['id_comunicado']+",'"+resp[i]['nombre_evento']+"','"+resp[i]['descripcion']+"','"+resp[i]['file']+"'"+')" class="rowTablaDetalles">'+estadisticaleidos+'<span class="from"><h4>'+resp[i]['nombre_evento']+'</h4></span>    <p class="msg">'+resp[i]['descripcion']+'</p><p class="text-primary"> Fecha publicación: '+ resp[i]["fecha_registro"] + '</p></div>';

        counterDoc++;
        
        dataTableCom.row.add( [
            counterDoc,
            datosCol,
            adjunto,
            resp[i]["fecha_inicio"]+ ' al ' + resp[i]["fecha_final"]
        ] ).draw( false );
    }
  }
},
error: function(e){
    console.log(e);
}
});
}


function leerComunicado(id_comunicado,titulo,detalle,file){
    $(".detalleMensajes").show();
    $(".inicioMensajes").hide();
    $(".tituloDetalle").text(detalle);
    $("#comunicado_leer").text(titulo);
    $("#comunicado_descripcion").text(detalle);
    if (file !='' && file != null) {
        $("#documento").show();
       // var dir = "https://docs.google.com/gview?url=http://educheck.bo/educheckv1/"+"<?=imgs; ?>" +"/comunicados/"+file+"&embedded=true";
    //$('#documento').attr('src', dir);
    }else{
        $("#documento").hide();    
    }
    $.ajax({
        url: '?/s-notificaciones/procesos',
        type: 'POST',
        data: {'accion':'leer_notificaciones','id_comunicado':id_comunicado},
        dataType: 'JSON',
        success: function(resp){
            $("#destinatario").text("DESTINATARIO(S): "+resp);
        },
        error: function(e){
            console.log(e);
        }
    })
}

function verlistadoComunic(){
$(".detalleMensajes").hide();
$(".inicioMensajes").show();
}
</script>
