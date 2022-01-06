<?php
// Obtiene la cadena csrf
$csrf = set_csrf();
// Obtiene los formatos para la fecha
$formato_textual = get_date_textual($_institution['formato']);
$formato_numeral = get_date_numeral($_institution['formato']);
// Obtiene el rango de fechas
$gestion = date('Y');
$gestion_base = date('Y-m-d');
//$gestion_base = ($gestion - 16) . date('-m-d');
$gestion_limite = ($gestion + 16) . date('-m-d');
// Obtiene fecha inicial
$fecha_inicial = (isset($params[0])) ? $params[0] : $gestion_base;
$fecha_inicial = (is_date($fecha_inicial)) ? $fecha_inicial : $gestion_base;
$fecha_inicial = date_encode($fecha_inicial);
// Obtiene fecha final
$fecha_final = (isset($params[1])) ? $params[1] : $gestion_limite;
$fecha_final = (is_date($fecha_final)) ? $fecha_final : $gestion_limite;
$fecha_final = date_encode($fecha_final);
// Obtiene los comunidados
//$comunidados = $db->select('z.*')->from('ins_comunicados z')->order_by('z.id_comunicado', 'asc')->fetch();
//obtiene los roles
$id_rol = $_user['rol_id'];
$roles = $db->query("SELECT * FROM sys_roles WHERE id_rol = $id_rol AND (rol = 'SUPERADMIN' OR rol = 'ADMINISTRADOR')")->fetch_first();
// Obtiene los permisos
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);

 $nombre_dominio = escape($_institution['nombre_dominio']);

?>
<?php require_once show_template('header-design'); ?>
<style>
.datepicker {z-index: 1151 !important;}
</style>
<link href="assets/themes/concept/assets/vendor/bootstrap-colorpicker/%40claviska/jquery-minicolors/jquery.minicolors.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<link href="assets/themes/concept/assets/vendor/datepicker/css/datepicker.css" rel="stylesheet">
<style>
tr:hover{
color: white;
background: #25d5f2;
}
.rowTablaDetalles:hover{
cursor: pointer;
}
</style>
<!--estado de notificaciones-->
<div class=" detalleMensajes row"  style="display:none">
  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
  <div class="card" style="width: 100%;">
    <div class="card-header">
      <div class="asidecom">
        <div class="aside-header">
          <button class="navbar-toggle" data-target=".aside-nav" data-toggle="collapse" type="button"><span class="icon"><i class="fas fa-caret-down"></i></span>
          </button><span class="title">Comunicado </span>
        </div>
        <div class="row">
          <div class="col-sm-8 hidden-xs">
            <div class="text-label">Para volver al listado de los comunicados hacer clic en el siguiente botón: </div>
          </div>
          <div class="col-xs-12 col-sm-4 text-right">
            <button class="btn btn-default" data-cambiar="true"><i class="glyphicon glyphicon-calendar"></i><span class="hidden-xs"> Cambiar</span></button>
            <a onclick="verlistadoComunic()" class="btn btn-success">Listado Comunicados</a>
          </div>
        </div>
        <hr>
      </div>
      <p>Información del comunicado</p>
      <div class="alert alert-success" role="alert">
        <h3 class="tituloDetalle"  data-id-comunic="" style="text-transform: uppercase;">Titulo</h3>
        <input type="hidden" id="id_comunicado_" value="0">
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
          <table class="table" id="tablaPersonal" style="width:100%">
            <thead class="table-active">
              <tr>
                <th width="5%">Nº</th>
                <th width="45%">Destinatario </th>
                <th width="25%">Estado</th>
                <th width="25%">Fecha leidos</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
    </div>
  </div>
  </div>
</div>
</div>
<!--mensajes-->
<div class=" inicioMensajes" data-formato="<?= strtoupper($formato_textual); ?>" data-mascara="<?= $formato_numeral; ?>" data-gestion="<?= date_decode($gestion_base, $_institution['formato']); ?>">
  <div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
              <h2>Comunicados y notificaciones </h2>
            </div>
            <hr>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
              <div class="btn-group">
                <div class="input-group">
                  <div class="input-group-append be-addon">
                    <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <a class="dropdown-item">Seleccionar acción</a>
                      <?php if ($permiso_crear) : ?>
                      <div class="dropdown-divider"></div>
                      <a onclick="abrir_crear();" class="dropdown-item"><i class="btn icon-people"></i> Crear Comunicados grupales</a>
                      <a onclick="abrir_crear22();" class="dropdown-item"><i class="btn icon-user-following"></i> Crear Comunicados Personales</a>
                      <?php endif ?>
                      <?php if ($permiso_imprimir) : ?>
                      <div class="dropdown-divider"></div>
                      <!-- <a href="?/s-comunicados/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Comunicados</a> -->
                      <?php endif ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12">
              <div class="aside-nav collapse p-2">
                <ul class="nav nav-pills">
                  <li><div class="text-label hidden-xs">Seleccione una opción: </div></li>
                  <?php if ($roles['rol'] != null && $roles['rol'] != '' && ($roles['rol'] == 'SUPERADMIN' || $roles['rol'] == 'ADMINISTRADOR' )): ?>
                  <li  onclick="vertabla(this,'tableComunicDocente_cont')">
                    <button type="button" class="btn btn-warning active" aria-pressed="true" style="margin: 3px">
                    <span class="icon"><i class="fas fa-fw fa-inbox"></i></span>Comunicados Docente
                    <span class="badge badge-light float-right badgeComDoc"> 0</span>
                    </button>
                  </li>
                  <?php endif ?>
                  <?php if ($roles['rol'] != null && $roles['rol'] != '' && ($roles['rol'] == 'SUPERADMIN' || $roles['rol'] == 'ADMINISTRADOR' )): ?>
                  <li onclick="vertabla(this,'tableComunicAdm_cont')">
                    <button type="button" class="btn btn-warning active" style="margin: 3px">
                    <span class="icon"><i class="fas fa-fw  fa-envelope"></i></span>Comunicados Grupales
                    <span class="badge badge-light float-right badgeComAdmin">0</span>
                    </button>
                  </li>
                  <?php endif ?>
                  <li onclick="vertabla(this,'tableComunicAdmPers_cont')">
                    <button type="button" class="btn btn-warning active" style="margin: 3px">
                    <span class="icon"><i class="fas fa-fw  fa-envelope"></i></span>Comunicados personales
                    <span class="badge badge-light float-right badgeComAdminpers">0</span>
                    </button>
                  </li>
                  <!--<li><a href="#"><span class="icon"><i class="fas fa-fw fa-trash"></i></span>Eliminados</a></li>-->
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <?php if ($roles['rol'] != null && $roles['rol'] != '' && ($roles['rol'] == 'SUPERADMIN' || $roles['rol'] == 'ADMINISTRADOR' )): ?>
            <div class="tableComunicDocente_cont tablecom">
              <!-- <h4>Comunicados Docente</h4>-->
              <table id="tableComunicDocente" class="table table-bordered" >
                <thead class="table-active ">
                  <tr>
                    <th width="5%">Nº</th>
                    <th width="10%">Enviado por</th>
                    <th width="65%">Comunicado </th>
                    <th width="5%">Doc Adjunto</th>
                    <th width="15%">Fecha</th>
                  </tr>
                </thead>
                <tbody >
                </tbody>
              </table>
            </div>
            <?php endif ?>
            <?php if ($roles['rol'] != null && $roles['rol'] != '' && ($roles['rol'] == 'SUPERADMIN' || $roles['rol'] == 'ADMINISTRADOR' )): ?>
            <div class="tableComunicAdm_cont tablecom "  style="display:none">
              <table id="tableComunicAdm" class="table table-bordered" style="width:100%">
                <thead>
                  <tr class="active">
                    <th class="text-nowrap">#bbcb</th>
                    <th class="text-nowrap">Enviado por</th>
                    <th class="text-nowrap">Comunicado</th>
                    <th class="text-condensed">Doc. Adjunto</th>
                    <th class="text-condensed">Tiempo de validez</th>
                    <th class="text-condensed">Usuarios enviados</th>
                    <?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
                    <th class="text-nowrap">Opciones</th>
                    <?php endif ?>
                  </tr>
                </thead>
                <tbody id="listado_gestion_escolar">
                </tbody>
                </table> <!--style="display:none"-->
              </div>
              <?php endif ?>
              <!--table-bordered table-condensed table-striped table-hover-->
              <div class="tableComunicAdmPers_cont tablecom" style="display:none">
                <table id="tableComunicAdmPers" class="table table-bordered" style="width:100%" >
                  <thead>
                    <tr class="active">
                      <th class="text-nowrap">#</th>
                      <th class="text-nowrap">Enviado por</th>
                      <th class="text-nowrap">Nombre del Comunicado</th>
                      
                      <th class="text-nowrap">Usuarios</th>
                      <th class="text-nowrap">Fecha a Terminar</th>
                      <?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
                      <th class="text-nowrap">Opciones</th>
                      <?php endif ?>
                    </tr>
                  </thead>
                  <tfoot>
                  <tr class="active">
                    <th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
                    <th class="text-nowrap text-middle">Enviado por</th>
                    <th class="text-nowrap text-middle">Nombre del Comunicado</th>
                    
                    <th class="text-nowrap text-middle">Usuarios</th>
                    <th class="text-nowrap text-middle">Fecha a Termina</th>
                    <?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
                    <th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
                    <?php endif ?>
                  </tr>
                  </tfoot>
                  <tbody id="listado_gestion_escolar">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class=" detalleComunicado row"  style="display:none">
    
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
                  <iframe id="documento" src="https://docs.google.com/gview?url=<?= comunicadoss; ?>/control_04092020.xlsx&embedded=true" style="width: 90%; height: 1000px">
<!--                   <iframe id="documento" src="https://docs.google.com/gview?url=<?= comunicadoss; ?> https://www.adobe.com/support/ovation/ts/docs/ovation_test_show.ppt&embedded=true"
                  style="width: 90%; height: 1000px">
 -->                  <p>Your browser does not support iframes.</p>
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
<!--modal fechas-->
<div id="modal_fecha" class="modal fade">
  <div class="modal-dialog">
    <form id="form_fecha" class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Cambiar fecha</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="inicial_fecha">Fecha inicial:</label>
              <input type="text" name="inicial" value="<?= ($fecha_inicial != $gestion_base) ? date_decode($fecha_inicial, $_institution['formato']) : ''; ?>" id="inicial_fecha" class="form-control" autocomplete="off" data-validation="date" data-validation-format="<?= $formato_textual; ?>" data-validation-optional="true">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="final_fecha">Fecha final:</label>
              <input type="text" name="final" value="<?= ($fecha_final != $gestion_limite) ? date_decode($fecha_final, $_institution['formato']) : ''; ?>" id="final_fecha" class="form-control" autocomplete="off" data-validation="date" data-validation-format="<?= $formato_textual; ?>" data-validation-optional="true">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-aceptar="true">
        <span class="glyphicon glyphicon-ok"></span>
        <span>Aceptar</span>
        </button>
        <button type="button" class="btn btn-default" data-cancelar="true">
        <span class="glyphicon glyphicon-remove"></span>
        <span>Cancelar</span>
        </button>
      </div>
    </form>
  </div>
</div>
<!-- librerias para full calendar -->
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/calendar.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/fullcalendar.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/es.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/jquery-ui.min.js"></script>
<!-- librerias para el color -->
<script src="assets/themes/concept/assets/vendor/bootstrap-colorpicker/%40claviska/jquery-minicolors/jquery.minicolors.min.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-select/js/bootstrap-select.js"></script>
<!--<script src="<?= themes; ?>/concept/assets/vendor/bootstrap-select/js/require.js"></script>-->
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/bootstrap-datetimepicker.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/educheck.js"></script>
<script src="<?= themes; ?>/concept/assets/libs/js/main-js.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.js"></script>
<script src="<?= themes; ?>/concept/assets/vendor/datepicker/js/datepicker.es.js"></script>
<?php require_once show_template('footer-design'); ?>
<?php
if($permiso_crear){
require_once ("crear.php");
require_once ("crear-comunic-personales.php");
}
?>
<script>
//ELIMINAR
$(function () {
<?php if ($permiso_eliminar) : ?>
$('[data-eliminar]').on('click', function (e) {
e.preventDefault();
var href = $(this).attr('href');
var csrf = '<?= $csrf; ?>';
alertify.confirm('Eliminando mensaje', '¿Está seguro que desea eliminar el gestion?',
function(){ alertify.success('Ok') ;
//if (result) {
//$.request(href, csrf);
$(location).attr('href',href);//'?/s-inscritos/expexcel/'+turno+'/'+nivel+'/'+aula+'/'+paralelo);
//}
}, function(){ alertify.error('Cancel')});
});
<?php endif ?>
})
$('#color_evento').minicolors({
theme: 'bootstrap'
});
var administrativo = "";
var profesor = "";
var tutor = "";
var estudiante = ""
var estilo = "";
var cont = 0;
var dataTableDoc = $('#tableComunicDocente').DataTable({
language: dataTableTraduccion,
stateSave:true,
"lengthChange": true,
"responsive": true
} );
var dataTableAdm = $('#tableComunicAdm').DataTable({
language: dataTableTraduccion,
stateSave:true,
"lengthChange": true,
"responsive": true
} );

var dataTableAdmpers = $('#tableComunicAdmPers').DataTable({
                      language: dataTableTraduccion,
                      stateSave:true,
                      "responsive": true
                      } );
var tablaPersonal = $('#tablaPersonal').DataTable({
                      language: dataTableTraduccion,
                      stateSave: true,
                      "lengthChange": true,
                      "responsive": true
                    } );

var formato = $('[data-formato]').attr('data-formato');
var mascara = $('[data-mascara]').attr('data-mascara');
var gestion = $('[data-gestion]').attr('data-gestion');
var $inicial_fecha = $('#inicial_fecha');
var $final_fecha = $('#final_fecha');
$inicial_fecha.datepicker({
language: 'es',
dateFormat: 'dd/mm/yyyy'
});
//$final_fecha.mask(mascara).datetimepicker({
$final_fecha.datepicker({
language: 'es',
dateFormat: 'dd/mm/yyyy'
});
var $form_fecha = $('#form_fecha');
var $modal_fecha = $('#modal_fecha');
$form_fecha.on('submit', function (e) {
e.preventDefault();
});
$modal_fecha.on('show.bs.modal', function () {
$form_fecha.trigger('reset');
});
$modal_fecha.on('shown.bs.modal', function () {
$modal_fecha.find('[data-aceptar]').focus();
});
$modal_fecha.find('[data-cancelar]').on('click', function () {
$modal_fecha.modal('hide');
});
$modal_fecha.find('[data-aceptar]').on('click', function () {
$form_fecha.submit();
});
$('[data-cambiar]').on('click', function () {
$('#modal_fecha').modal({
//backdrop: 'static'
});
});

$.validate({
  form: '#form_fecha',
  modules: 'date',
  onSuccess: function () {
    var inicial_fecha = $.trim($('#inicial_fecha').val());
    var final_fecha = $.trim($('#final_fecha').val());
    var vacio = gestion.replace(new RegExp('9', 'g'), '0');
    inicial_fecha = inicial_fecha.replace(new RegExp('\\.', 'g'), '-');
    inicial_fecha = inicial_fecha.replace(new RegExp('/', 'g'), '-');
    final_fecha = final_fecha.replace(new RegExp('\\.', 'g'), '-');
    final_fecha = final_fecha.replace(new RegExp('/', 'g'), '-');
    vacio = vacio.replace(new RegExp('\\.', 'g'), '-');
    vacio = vacio.replace(new RegExp('/', 'g'), '-');
    final_fecha = (final_fecha != '') ? ( final_fecha ) : '';
    inicial_fecha = (inicial_fecha != '') ? ( inicial_fecha) : ((final_fecha != '') ? (vacio) : '');
    var id_comunic = $("#id_comunicado_").val();
    $.ajax({
      url: '?/s-comunicados/procesos',
      type: 'POST',
      data: {'boton':'ver_vistos_recargar',
            'id_comunicado':id_comunic,
            'inicial_fecha': inicial_fecha,
            'final_fecha': final_fecha,
      },
      dataType: 'JSON',
      success: function(resp){
        $('#modal_fecha').modal('hide');
        //$('#tablaPersonal').find('tbody').html('');var html='';
        tablaPersonal.clear();
        var num = 0;
        for (var i = 0; i < resp['leidos'].length; i++) {
        num++;
         tablaPersonal.row.add( [
          num,      
          resp['leidos'][i]['primer_apellido']+ ' ' + resp['leidos'][i]['segundo_apellido']+ ' '+ resp['leidos'][i]['nombres'],
          '<span class="icon"><i class="fas fa-check" style="color:#00adff"></i><i class="fas fa-check" style="color:#00adff"></i></span>',
          resp['leidos'][i]['leido_fecha']
          ] ).draw( false );
        }
        for (var i = 0; i < resp['noleidos'].length; i++) {
        num++;
         tablaPersonal.row.add( [
          num,      
          resp['noleidos'][i]['primer_apellido']+ ' ' + resp['noleidos'][i]['segundo_apellido']+ ' '+ resp['noleidos'][i]['nombres'],
          '<span class="icon"><i class="fas fa-check" style="color:#c2c2c2"></i><i class="fas fa-check" style="color:#c2c2c2"></i></span>',
          ''
          ] ).draw( false );
        }
      }, error: function(e){
          console.log(e);
      }
    });
  }
});

listar_comunicados_tabla();

function listar_comunicados_tabla() {
var boton='';
$.ajax({
      url: '?/s-comunicados/procesos',
      type: 'POST',
      data: {'boton':'listar_comunicados'},
      dataType: 'JSON',
success: function(resp){
    var counterDoc=0;
    var counterAdm=0;
    var counterAdminpers=0;
    dataTableDoc.clear().draw();//limpia y actualisa la tabla
    dataTableAdm.clear().draw();//limpia y actualisa la tabla
    dataTableAdmpers.clear().draw();//limpia y actualisa la tabla
    for (var i = 0; i < resp.length; i++) {
      var star='';
      var html = '';
      var prioridad='';
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
//estadisticas LEIDOS
/*var estadisticaleidos = '<div class="progress" title="Leidos ' +
  resp[i]['cantLeidos'] + ', click para mas detalles"> <span class="fa fa-eye badge badge-warning" >' +
  resp[i]['cantLeidos'] + '</span><div class="progress-bar bg-default" style="width:' +
  resp[i]['cantLeidos'] + '0%" role="progressbar"></div> <span class="fa fa-eye-slash  badge badge-danger" style="    background-color: #dcdde2;color: #71748d;"></span></div>';*/
  
  //ARMAR ARCHIVOS
  var adjunto='';
  if(resp[i]['file']=='' || resp[i]['file']==null || resp[i]['file']==0){
    }else{
    var icon='';
    var extencion=resp[i]['file'].split(".");
    var imgs = "<?= imgs; ?>";
    if(extencion[1]=='docx'){
      icon='<i class="fa fa-file-word text-center" style="font-size: 35px;color:#0000ad;display:block;"></i><br> ';
    }else if(extencion[1]=='xlsx'){
      icon='<i class="fa fa-file-excel text-center" style="font-size: 35px;color:green"display:block;></i><br> ';
    }else if(extencion[1]=='jpg'){
      icon='<i class="fa fa-file-image text-center" style="font-size: 35px;color:orange;display:block;"></i><br> ';
    }
      adjunto='<a class="btn text-primary text-center active" href="files/<?=$nombre_dominio;?>/comunicados/'+resp[i]['file']+'" dowload="ADJ.'+extencion[1]+'"> '+icon+ resp[i]['file']+'</a>';
  }
  //ARMAR BOTONES
  var btnacciones = "";
  var contenido = resp[i]['id_comunicado'] + "*" + resp[i]['codigo']+ "*" + resp[i]['fecha_inicio']+ "*" + resp[i]['fecha_final']+ "*" + resp[i]['nombre_evento']+ "*" + resp[i]['descripcion']+ "*" + resp[i]['color']+ "*" + resp[i]['usuarios']+ "*" + resp[i]['estados']+ "*" + 0 +"*"+resp[i]['persona_id']+"*"+resp[i]['file']+"*"+resp[i]['prioridad'];
  //resp[i]['ids']
  var btneliminar="<?php if ($permiso_eliminar) : ?><a href='?/s-comunicados/eliminar/"+ resp[i]['id_comunicado'] +"' class='btn btn-danger btn-xs' data-eliminar='true'><span class='icon-trash'></span></a><?php endif ?>";
  
  // COMUNICADOS CRUPALES:::::: usuarios='2,3,4,5,6' persona_id='' grupo=0
  if(resp[i]['usuarios']!='' && resp[i]['persona_id']=='' && resp[i]['grupo']=='0'){
  var usuario = resp[i]['usuariosStr'].split(",");//en usuario nombre
  //var estados = resp[i]['estados'].split(",");
  var tamanio = usuario.length;
  html += '<ul type="square">';
    for (let i = 0; i < tamanio; i++) {
    //if(estados[i] == "SI"){
    html += '<li> '+ usuario[i] +'</li>';
    //}
        
    }
  html += '</ul>';
  var usuariosColapse='<div class="carxd-header" id="headingSeven">  <h5 class="mb-0">   <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSeven'+counterAdm+'" aria-expanded="false" aria-controls="collapseSeven'+counterAdm+'">    <span class="fas mr-3 fa-angle-down"></span>Usuarios ver</button>   </h5>  </div> <div id="collapseSeven'+counterAdm+'" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion3" style="">     <div class="card-body">'+html+'</div>  </div>';
  
  btnacciones+="<a href='#' class='btn btn-info btn-xs' onclick = 'abrir_ver("+'"'+contenido+'"'+");'><span class='icon-eye'></span></a>  &nbsp";
  btnacciones+="<?php if ($permiso_modificar) : ?>"+"<a href='#' class='btn btn-warning btn-xs' style='color:white' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note'></span></a>"+"<?php endif ?> &nbsp"+btneliminar;
  var coldatos='<div class="progress" title="Leidos '+resp[i]['cantLeidos']+', click para mas detalles"> <span class="fa fa-eye badge badge-info" >'+resp[i]['cantLeidos']+'</span><div class="progress-bar bg-info" style="width:'+resp[i]['cantLeidos']+'0%" role="progressbar"></div> <span class="fa fa-eye-slash  badge badge-danger" style="    background-color: #dcdde2;color: #71748d;">10+</span></div>';
  var useremisor = resp[i]['useremisor'];
  counterAdm++;
  
  //añadir filas
  dataTableAdm.row.add( [
      counterAdm,
      useremisor,
      '<div  onclick="leerComunicado('+resp[i]['id_comunicado']+",'"+resp[i]['nombre_evento']+"','"+resp[i]['descripcion']+"','"+resp[i]['file']+"'"+')" class="rowTablaDetalles">'+'<div class="alert alert-'+color+'"><strong class="from" style="text-transform: uppercase;">'+resp[i]["nombre_evento"]+ ' - ' + prioridad+'</strong> - <em class="msg">'+resp[i]['descripcion']+'</em></div></div>'+
      '<div onclick="verLeidos('+resp[i]['id_comunicado']+",'"+resp[i]['nombre_evento']+"'"+')" class=""></div>',//Info del comunicado: '+estadisticaleidos+'
      adjunto,
      resp[i]["fecha_inicio"]+' al '+  resp[i]["fecha_final"],
      usuariosColapse,
      btnacciones
  ] ).draw( false );//bars
  
  }else
  if(resp[i]['usuarios']=='' && resp[i]['persona_id']!='' && resp[i]['grupo']=='0'){
  //COMUNICADOS personles :::::: usuarios='' persona_id='2,3,44' grupo=0:::::::::::::::::::::::
  var cantPersonas=resp[i]['persona_id'].split(",").length;
  var cantLeidos=resp[i]['cantLeidos'];
  var porcent=cantLeidos*100/cantPersonas;
  //estadisticaleidos='<div class="progress" title="Leidos '+
    //cantLeidos+' de '+cantPersonas+'  personas, click para mas detalles"> <span class="fa fa-eye badge badge-warning" >'+
    //resp[i]['cantLeidos']+'</span><div class="progress-bar bg-default" style="width:'+
    //porcent+'%" role="progressbar"></div> <span class="fa fa-eye-slash  badge badge-danger" style="    background-color: #dcdde2;color: #71748d;"></span></div>';
    
    html='<span class="badge" style="background:'+resp[i]['color']+' ;color:#ffffff">Comunicado personal '+'</span>';
    btnacciones+="<?php if ($permiso_ver) : ?><a href='#' class='btn btn-info btn-xs' onclick = 'abrir_ver22("+'"'+contenido+'"'+");'><span class='icon-eye'></span></a><?php endif ?> &nbsp";
    btnacciones+="<?php if ($permiso_modificar) : ?>"+"<a href='#' class='btn  btn-xs' style='color:white;background:#0998b0;' onclick='abrir_editar22("+'"'+contenido+'"'+")'><span class='icon-note'></span></a>"+"<?php endif ?> &nbsp"+btneliminar;
    var coldatos= '<div onclick="leerComunicado('+resp[i]['id_comunicado']+",'"+resp[i]['nombre_evento']+"','"+resp[i]['descripcion']+"','"+resp[i]['file']+"'"+')" class="rowTablaDetalles">'+'<div class="alert alert-'+color+'"><strong class="from" style="text-transform: uppercase;">'+resp[i]['nombre_evento']+ ' - ' + prioridad+'</strong> - <em class="msg">'+resp[i]['descripcion']+'</em></div></div>'+
    '<div onclick="verLeidos('+resp[i]['id_comunicado']+",'"+resp[i]['nombre_evento']+"'"+')" class=""></div>';//Info del comunicado: '+estadisticaleidos+'
    var useremisor = resp[i]['useremisor'];
    //amar en tabla
    counterAdminpers++;
    dataTableAdmpers.row.add( [
      counterAdminpers,
      useremisor,
      coldatos,
      html+adjunto,
      resp[i]["fecha_inicio"]+' al '+  resp[i]["fecha_final"],
      btnacciones
    ] ).draw( false );
    //   }
    }else
    if(resp[i]['usuarios']=='' && resp[i]['persona_id']=='' && resp[i]['grupo']!='0'){
    if(resp[i]['grupo']=='t'){
    spantipo='<span class="badge badge-pill" style="background:#f0f0f8;"><span style="color: #808080;" class="icon-people"></span> Todos</span>';
    }else if(resp[i]['grupo']=='m'){
    spantipo='<span class="badge badge-pill " style="background:#ff5fef;color:#ffffff"><span style="color: #ffffff;" class="icon-user-female"></span> Niñas</span>';
    }else if(resp[i]['grupo']=='v'){
    spantipo='<span class="badge badge-pill" style="background:#5969ff;color:#ffffff"><span style="color: ffffff;" class="icon-user"></span> Niños</span>';
    }else if(resp[i]['grupo']=='selec'){
    spantipo='<span class="badge badge-pill " style="background:#ffc108"><span style="color: #000008;" class="icon-pin"></span> Seleccionados</span>';
    }
    var datosCol='<div onclick="leerComunicado('+resp[i]['id_comunicado']+",'"+resp[i]['nombre_evento']+"','"+resp[i]['descripcion']+"','"+resp[i]['file']+"'"+')" class="rowTablaDetalles">'+'<div class="alert alert-'+color+'"><strong class="from" style="text-transform: uppercase;">'+resp[i]['nombre_evento']+ ' - ' + prioridad+'</strong> - <em class="msg">'+spantipo+'  '+resp[i]['descripcion']+'</em></div></div>'+
    '<div onclick="verLeidos('+resp[i]['id_comunicado']+",'"+resp[i]['nombre_evento']+"'"+')" class=""></div>';//Info del comunicado: '+estadisticaleidos+'
    var useremisor = resp[i]['useremisor'];
    //armar en tabla
    counterDoc++;
    dataTableDoc.row.add( [
    counterDoc,
    useremisor,
    datosCol,
    adjunto,
    resp[i]["fecha_inicio"]
    ] ).draw( false );//bars
    }
    $('.badgeComAdmin').text(counterAdm);
    $('.badgeComAdminpers').text(counterAdminpers);
    $('.badgeComDoc').text(counterDoc);
    }
    }
    });
    }

function leerComunicado(id_comunicado,titulo,detalle,file){
    $(".detalleComunicado").show();
    $(".inicioMensajes").hide();
    $(".tituloDetalle").text(detalle);
    $("#comunicado_leer").text(titulo);
    $("#comunicado_descripcion").text(detalle);
    if (file !='' && file != null) {
    $("#documento").show();
    //var dir = "https://docs.google.com/gview?url=http://educheck.bo/educheckv1/"+"<?=imgs; ?>" +"/comunicados/"+file+"&embedded=true";
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
    $(".detalleComunicado").hide();
    $(".detalleMensajes").hide();
    $(".inicioMensajes").show();
    }
    function verLeidos(id_comunicado,detalle){
    $(".detalleMensajes").show();
    $(".inicioMensajes").hide();
    $(".tituloDetalle").text(detalle);
    $("#id_comunicado_").val(id_comunicado);
    $('#tablaPersonal').find('tbody').html('');
    $.ajax({
    url: '?/s-comunicados/procesos',
    type: 'POST',
    data: {'boton':'ver_vistos', 'id_comunicado':id_comunicado},
    dataType: 'JSON',
    success: function(resp){
      //console.log(resp)
      //$('#tablaPersonal').find('tbody').html('');var html='';
      tablaPersonal.clear();
      var num = 0;
        for (var i = 0; i < resp['leidos'].length; i++) {
        num++;
         tablaPersonal.row.add( [
          num,      
          resp['leidos'][i]['primer_apellido']+ ' ' + resp['leidos'][i]['segundo_apellido']+ ' '+ resp['leidos'][i]['nombres'],
          '<span class="icon"><i class="fas fa-check" style="color:#00adff"></i><i class="fas fa-check" style="color:#00adff"></i></span>',
          resp['leidos'][i]['leido_fecha']
          ] ).draw( false );
        }
        for (var i = 0; i < resp['noleidos'].length; i++) {
        num++;
         tablaPersonal.row.add( [
          num,      
          resp['noleidos'][i]['primer_apellido']+ ' ' + resp['noleidos'][i]['segundo_apellido']+ ' '+ resp['noleidos'][i]['nombres'],
          '<span class="icon"><i class="fas fa-check" style="color:#c2c2c2"></i><i class="fas fa-check" style="color:#c2c2c2"></i></span>',
          ''
          ] ).draw( false );
        }



    }
    });
    }
    function vertabla(obj,tablaver){
    $(obj).siblings().removeClass('active');
    $(obj).addClass('active');
    $('.tablecom').slideUp();
    $('.'+tablaver).slideDown();
    }
    function vermodalDetalles(){
    //alert('holaa');
    }
    <?php if ($permiso_ver) : ?>
    function abrir_ver(contenido){
      var d = contenido.split("*");
      var inicio = d[2].split(" ");
      var final = d[3].split(" ");
      var estados = d[8].split(",");
      var espacio = d[9].split(",");
      var cadena = [];
      console.log(cadena);
      contador = estados.length;
      for(var i = 0; i < contador; i++){
        if(estados[i] == "SI"){
        cadena.push(i+1);
        //.log("asd");
        }
      }
      $("#form_agregar_evento")[0].reset();
      $("#modal_agregar_evento").modal("show");
      $("#titulo_modal").text("Ver Comunicado");
      $("#id_comunicado").val(d[0]);
      $("#nombre_evento").val(d[4]);
      $("#descripcion_evento").val(d[5]);
      $("#color_evento").minicolors('value',d[6]);
      $("#fecha_inicio").val(inicio[0]);
      $("#hora_inicio").val(inicio[1]);
      $("#fecha_final").val(final[0]);
      $("#hora_final").val(final[1]);
      $('#select_roles').selectpicker('val', cadena);
      $("#btn_editar").hide();
      $("#btn_agregar").hide();
    }
    <?php endif ?>
    <?php if ($permiso_modificar) : ?>
    function abrir_editar(contenido){
      var d = contenido.split("*");
      var inicio =  d[2];// d[2].split(" ");
      var final = d[3];//  d[3].split(" ");
      //console.log(contenido);
      //console.log(d);
     // var estados = d[8].split(",");
      var espacio = d[9].split(",");//7
      var usuarios = d[7].split(",");
      //var cadena = [];
      //console.log("estados:"+estados);
      //contador = usuarios.length;
      //for(var i = 0; i < contador; i++){
        //if(estados[i] == "SI"){
       //   cadena.push(i+1);
        //}
      //}
      //console.log(cadena);
      $("#form_agregar_evento")[0].reset();
      $("#modal_agregar_evento").modal("show");
      //console.log(d);
      $("#titulo_modal").text("Ver Comunicado");
      $("#id_comunicado").val(d[0]);
      $("#nombre_evento").val(d[4]);
      $("#descripcion_evento").val(d[5]);
      $("#color_evento").minicolors('value',d[6]);
      $("#fecha_inicio").val(inicio);//inicio[0]);
      //$("#hora_inicio").val(inicio[1]);
      $("#fecha_final").val(final);//final[0]);
      //alert(d[12]);
      $("#prioridad").val(d[12]);
      // ids = {}
      $('#select_roles').selectpicker('val', usuarios);//cadena);

      //$('#select_roles :selected').each(function(i, selected){ 
        //cadena[i] = $(selected).val(); 
      //});


      $("#btn_editar").show();
      $("#btn_agregar").hide();
      //$("#select_roles").
    }
  function abrir_ver22(contenido){
    var d = contenido.split("*");
    var inicio = d[2];
    var final = d[3];
    var estados = d[8].split(",");
    var espacio = d[9].split(",");
    var cadena = [];
    contador = estados.length;
    for(var i = 0; i < contador; i++){
      if(estados[i] == "SI"){
        cadena.push(i+1);
      }
    }
    limpiar();
    $("#modal_agregar_personas_ev").modal("show");
    $("#titulo_modal_p").text("Ver Comunicado personal");
    $("#id_comunicado_p").val(d[0]);
    $("#nombre_evento_p").val(d[4]);
    $("#descripcion_evento_p").val(d[5]);
    $("#color_evento_p").minicolors('value',d[6]);
    $("#fecha_inicio_p").val(inicio);
    $("#fecha_final_p").val(final);
    $('#select_roles_p').selectpicker('val', cadena);
    //::::::::::::ADJUNTAR:::::::::::::::::
    var adjunto='';
    var icon='';
    var file=d[7];
    if(file=='' || file==null || file==0){
    adjunto='<a class="btn btn-" href="#" >Descargar<i class="icon-close"></i></a>';
    }else{
    var extencion=file.split(".");
    if(extencion[1]=='docx'){
      icon='<i class="fa fa-file-word" style="font-size: 50px;color:#0000ad;display:block;"></i>';
      }else if(extencion[1]=='xlsx'){
        icon='<i class="fa fa-file-excel" style="font-size: 48px;color:green"display:block;></i>';
      }else if(extencion[1]=='jpg'){
        icon='<i class="fa fa-file-image" style="font-size: 48px;color:orange;display:block;"></i>';
      }
      adjunto='<a class="btn btn-" href="files/<?=$nombre_dominio;?>/comunicados/'+file+'" dowload="ADJ.'+extencion[1]+'"> '+icon+'Descargar<i class="icon-arrow-down-circle"></i></a>';
    }
    //::::::::::::CARGAR PERSONAS:::::::::::::::::
    var arraypersonas=d[10].split(",");//10 strinf de personas
    for (var i=0;i<arraypersonas.length;i++) {
      var id_personas=arraypersonas[i];
      listar_a_tabla(id_personas);
    }
  //:::::::::::::::::::::::::::::::::::::::::::
  $("#btn_editar_p").hide();
  $("#btn_agregar_p").hide();
  $("#btn_limpìar_p").hide();
  }

  function abrir_editar22(contenido){
          var d = contenido.split("*");
          console.log(contenido);
          var inicio = d[2];// d[2].split(" ");
          var final = d[3];// d[3].split(" ");
          var estados = d[8].split(",");
          var espacio = d[9].split(",");
          var cadena = [];
          contador = estados.length;
          for(var i = 0; i < contador; i++){
              if(estados[i] == "SI"){
              cadena.push(i+1);
              }
          }
          limpiar();
          $("#modal_agregar_personas_ev").modal("show");
          $("#titulo_modal_p").text("Editar Comunicado personal");
          $("#id_comunicado_p").val(d[0]);
          $("#nombre_evento_p").val(d[4]);
          $("#descripcion_evento_p").val(d[5]);
          $("#color_evento_p").minicolors('value',d[6]);
          $("#fecha_inicio_p").val(inicio);
          $("#fecha_final_p").val(final);
          $("#prioridad_p").val(d[12]);
          $('#select_roles_p').selectpicker('val', cadena);
          //::::::::::::CARGAR PERSONAS:::::::::::::::::
          var arraypersonas=d[10].split(",");//10 strinf de personas
          //console.log('------------------------------'+arraypersonas);
          for (var i=0;i<arraypersonas.length;i++) {
            var id_personas=arraypersonas[i];
            listar_a_tabla(id_personas);
            }
            //:::::::archivos::::
            //alert(d[11]);
            var file=d[11];
            if(file=='' || file==null || file==0){
            //console.log('si es vacio'+resp[i]['file']+'-------');
            adjunto='<a class="btn btn-" href="#" >Sin archivo<i class="fa fa-times-square"></i></a>';
            }else{
            var extencion=file.split(".");
            if(extencion[1]=='docx'){
            icon='<i class="fa fa-file-word" style="font-size: 50px;color:#0000ad;display:block;"></i>';
            }else if(extencion[1]=='xlsx'){
            icon='<i class="fa fa-file-excel" style="font-size: 48px;color:green;display:block;"></i>';
            }else if(extencion[1]=='jpg'){
            icon='<i class="fa fa-file-image" style="font-size: 48px;color:orange;display:block;"></i>';
            }
            adjunto='<a class="btn btn-" href="files/<?=$nombre_dominio;?>/comunicados/'+file+'" dowload="ADJ.'+extencion[1]+'"  > '+icon+'Descargar<i class="icon-arrow-down-circle"></i></a>';
            //console.log('tiene datos'+adjunto+';;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;');
            }
            $('.descargarfile').html(adjunto);
            //:::::::::::::::::::::::::::::::::::::::::::
            $("#btn_editar_p").show();
            $("#btn_agregar_p").hide();
            $("#btn_limpìar_p").hide();
            //$("#select_roles").
        }
        <?php endif ?>
        <?php if ($permiso_crear) : ?>
        function abrir_crear(){
        limpiar();
        $("#modal_agregar_evento").modal("show");
        $('#form_agregar_evento').trigger("reset");
        $("#titulo_modal").text("Crear Comunicado");
        $("#id_comunicado").val(0);
        //$("#btn_agregar").show();
        $("#btn_editar").hide();
        $("#btn_agregar").show();
        $("#btn_limpìar").show();
        }
        function abrir_crear22(){
        limpiar();
        $("#modal_agregar_personas_ev").modal("show");
        $('#form_agregar_comunicado').trigger("reset");
        $("#titulo_modal_p").text("Crear Comunicado");
        $("#id_comunicado_p").val(0);
        //$("#btn_agregar").show();
        $("#btn_editar_p").hide();
        $("#btn_agregar_p").show();
        $("#btn_limpìar_p").show();
        }
        <?php endif ?>
        </script>