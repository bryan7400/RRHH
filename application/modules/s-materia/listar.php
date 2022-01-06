<?php 
// Obtiene la cadena csrf
$csrf = set_csrf(); 

//dominio
$nombre_dominio = escape($_institution['nombre_dominio']);

// Obtiene el id de la gestion actual
$id_gestion=$_gestion['id_gestion'];

// Obtiene los paralelo
$materia = $db->select('z.*')->from('pro_materia z')->order_by('z.id_materia', 'asc')->fetch();

// Obtener los niveles academicos por gestion
$niveles_academicos = $db->select('na.*')->from('ins_nivel_academico na')->where('na.gestion_id',$id_gestion)->where('na.estado','A')->order_by('na.id_nivel_academico', 'asc')->fetch();
 
$campos_academicos = $db->query("SELECT *
									FROM pro_campo
									WHERE estado = 'A' AND gestion_id = $id_gestion
									ORDER BY orden_campo ASC ")->fetch();


//var_dump($niveles_academicos);

// Obtiene los permisos
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_editar = in_array('editar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);
?>
<?php require_once show_template('header-design'); ?>
 
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<link href="assets/themes/concept/assets/vendor/bootstrap-colorpicker/%40claviska/jquery-minicolors/jquery.minicolors.css" rel="stylesheet">

<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"> 
        <div class="page-header">
            <h2 class="pageheader-title">Materias</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Configuración</a></li>
						<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Materia</a></li>
                        <!--li class="breadcrumb-item active" aria-current="page">Listar</li-->
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- end pageheader -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!-- row -->
<!-- ============================================================== -->
<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
						<div class="text-label hidden-xs">Seleccione:</div>
					</div>
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
						<div class="btn-group">
								<div class="input-group">
								<div class="input-group-append be-addon">
									<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
									<div class="dropdown-menu">
										<a class="dropdown-item">Seleccionar acción</a>
										<?php if ($permiso_crear) : ?>
										<div class="dropdown-divider"></div>
										<a href="#" onclick="abrir_crear();" class="dropdown-item">Crear Materia</a>
										<?php endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-materia/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Materia</a>
										<?php endif ?>
									</div>
								</div>
							</div>
						</div> 
					</div>
				</div>
			</div>
			<!-- ============================================================== -->
			<!-- datos --> 
			<!-- ============================================================== -->
			<div class="card-body">
		
				<?php if ($message = get_notification()) : ?>
				<div class="alert alert-<?= $message['type']; ?>">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong><?= $message['title']; ?></strong>
					<p><?= $message['content']; ?></p>
				</div>
				<?php endif ?>

				<?php if ($materia) : ?>
				<div class="table-responsive">
				<table id="table" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
					<thead>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<th class="text-nowrap">Imagen</th>
							<th class="text-nowrap">Nombre materia</th>
							<th class="text-nowrap">Código materia</th>
							<th class="text-nowrap">Descripción</th>
							<th class="text-nowrap">Campo</th>
							<th class="text-nowrap">Color</th>
							<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
							<th class="text-nowrap">Opciones</th>
							<?php endif ?>
						</tr>
					</thead>
					<tfoot>
						<tr class="active">
							<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
							<th class="text-nowrap">Imagen</th>
							<th class="text-nowrap text-middle">Nombre materia</th>
							<th class="text-nowrap text-middle">Código materia</th>
							<th class="text-nowrap text-middle">Descripción</th>
							<th class="text-nowrap text-middle">Campo</th>
							<th class="text-nowrap text-middle">Color</th>
							<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
							<th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
							<?php endif ?>
						</tr>
					</tfoot>
					<tbody>
					</tbody>
				</table>
				</div>
				<?php else : ?>

				<div class="alert alert-info">
					<strong>Atención!</strong>
					<ul>
						<li>No existen materias registrados en la base de datos.</li>
						<li>Para crear nuevos materias debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
					</ul>
				</div>
				<?php endif ?>
			</div>
			<!-- ============================================================== -->
			<!-- end datos -->
			<!-- ============================================================== -->
		</div>
	</div>
</div>
<!-- ============================================================== -->
<!-- row -->
<!-- ============================================================== -->
<!--modal para eliminar-->

<div class="modal fade" tabindex="-1" role="dialog" id="modal_eliminar">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
	  	<input type="hidden" id="materia_eliminar">
        <p>¿Esta seguro de eliminar el paralelo <span id="texto_materia"></span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn_eliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>
 <div class="modal fade" tabindex="-1" role="dialog" id="modal_newimg">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
	  	<input type="hidden" id="materia_id">
        <p>Cargar nueva imagen </p>
	  	<input type="file" id="img_materia" class="form-control">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn_guardar" onclick="guardarImagen()">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!--script src="<?= js; ?>/jquery.dataTables.min.js"></script>
<script src="<?= js; ?>/dataTables.bootstrap.min.js"></script-->
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<!--script src="<?= js; ?>/jquery.dataFilters.min.js"></script-->
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/educheck.js"></script>

<!-- ---------color--------- -->
<script src="assets/themes/concept/assets/vendor/bootstrap-colorpicker/%40claviska/jquery-minicolors/jquery.minicolors.min.js"></script>
<style>
    .circulo_color{ 
    border-radius: 50%;
    width: 3em;
    height: 3em;
    text-align: center;
    align-items: center; 
    display: grid;
    }
    .trimg{
         
        position:relative; 
    }
  
</style>


<?php require_once show_template('footer-design'); ?>
<?php 
	if($permiso_editar){
		require_once ("editar.php");
	}
?>
<script>

    var nombre_dominio = "<?=$nombre_dominio?>";



 function cargarimagenmodal(idmat){
    // alert('aqui'); 
     $("#modal_newimg").modal("show");
     $("#materia_id").val(idmat);
 }
    
function guardarImagen(){
    alert('guardar a bd');   
  // $("#modal_newimg").modal("show");
}
    
function abrir_editar(contenido){
	//console.log(contenido);
	$("#form_materia")[0].reset();
	$("#modal_materia").modal("show");
	$("#titulo_materia").text("Editar ");
	$('#table tbody').off();
	
	var d = contenido.split("*");
	//cargamos con los check seleccionados
	if(d[3] != ""){
		var aCheck = d[3].split(",");
		for(var i = 0 ; i < aCheck.length ; i++){
			console.log(aCheck[i]);
			//$('#'+aCheck[i])[0].checked = true;
		}
	}	
	
    $("#id_materia").val(d[0]);
	$("#nombre_materia").val(d[1]);
	$("#descripcion").val(d[2]);
	 
    $("#color").minicolors('value',d[5]);
	$("#id_campo").val(d[6]);
	$("#codigo_materia").val(d[7]);
	$("#btn_nuevo").hide();
	$("#btn_editar").show();
}


<?php if ($permiso_crear) : ?>
function abrir_crear(){
	$("#modal_materia").modal("show");
	$("#titulo_materia").text("Crear ");
    $("#id_materia").val('');
	$("#form_materia")[0].reset();
	$("#btn_editar").hide();
	$("#btn_nuevo").show();
}
<?php endif ?>
/*var columns=[
	{data: 'id_materia'},
	{data: 'imagen_materia'},
	{data: 'nombre_materia'},
	{data: 'descripcion'},
	{data: 'color_materia'}
	
];*/
var cont = 0;

var dataTable = $('#table').DataTable({
  language: dataTableTraduccion,  
  stateSave:true,
  "lengthChange": true,
  "responsive": true
  } );  

  listar_materias();
function listar_materias() {
    $.ajax({
        url: '?/s-materia/busqueda',
        type: 'POST',
        data: '',
        dataType: 'JSON',
        success: function(resp){ 
        var counter=1;
        dataTable.clear().draw();//limpia y actualisa la tabla
	    for (var i = 0; i < resp.length; i++) { 
            
            var contenido = resp[i]['id_materia'] + "*" + resp[i]['nombre_materia']+ "*" + resp[i]['descripcion']+ "*"+ resp[i]['nivel_academico_id'] +"*"+ resp[i]['imagen_materia'] +"*"+ resp[i]['color_materia']+"*" +resp[i]['campo_id']+"*" +resp[i]['cod_materia'];
            ///BOTONES DE OPCION
			var result = "";
			result+="<?php if ($permiso_ver) : ?><a href='?/s-materia/ver/" + resp[i]['id_materia'] + "' class='btn btn-info btn-xs'><span class='icon-eye'></span></a><?php endif ?> &nbsp"+"<?php if ($permiso_editar) : ?><a href='#' class='btn btn-warning btn-xs' style='color:white' onclick='abrir_editar("+'"'+contenido+'"'+")'><span class='icon-note'></span></a><?php endif ?> &nbsp" +"<?php if ($permiso_eliminar) : ?><a href='#' class='btn btn-danger btn-xs' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash'></span></a><?php endif ?>";
            
            //IMAGEN
            var imagen = "";
            //var foto = "imgs . '/avatar.jpg'";
            if(resp[i]['imagen_materia'] == ""){
                imagen = "<div class='trimg'><img src='files/logos/logo-defecto-institucion.png' class='img-rounded cursor-pointer' data-toggle='modal' data-target='#modal_mostrar' data-modal-size='modal-md' data-modal-title='Imagen' width='64' height='64'></div>";
            }else{
                imagen = "<div  class='trimg'><img src='files/"+nombre_dominio+"/profiles/materias/" + resp[i]['imagen_materia'] + ".jpg' class='img-rounded cursor-pointer' data-toggle='modal' data-target='#modal_mostrar' data-modal-size='modal-md' data-modal-title='Imagen' width='64' height='64'> </div>";
                //foto = "files/profiles/materias/" + row['imagen_materia'];
            }
            //imagen += "<img src='"+ foto +"' class='img-rounded cursor-pointer' data-toggle='modal' data-target='#modal_mostrar' data-modal-size='modal-md' data-modal-title='Imagen' width='64' height='64'>";
            var color=resp[i]["color_materia"];
            //*alert(color);
             dataTable.row.add( [
                        counter,
                        imagen,
                        resp[i]["nombre_materia"],
                        resp[i]["cod_materia"],
                        resp[i]["descripcion"],
                        resp[i]["nombre_campo"] ,
                        "<div class='circulo_color' style='background:"+resp[i]["color_materia"]+"'><span style='display:none'> "+resp[i]["color_materia"]+"</span></div>",
                        //resp[i]["usuarios"],
                        //html,
                        result
                    ] ).draw( false );//bars
               counter++;
              }
 
	 }
	 });
	}    
    

<?php if ($permiso_ver) : ?>
function ver(contenido){
	var d = contenido.split("*");
	$("#materia_ver").modal("show");
	$("#nombre_materia_ver").text(d[1]);
	$("#descripcion_ver").text(d[2]);
}
<?php endif ?> 

<?php if ($permiso_eliminar) : ?>
function abrir_eliminar(contenido){
	$("#modal_eliminar").modal("show");
	var d = contenido.split("*");
	$("#materia_eliminar").val(d[0]);
	$("#texto_materia").text(d[1]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
$("#btn_eliminar").on('click', function(){
	//alert($("#gestion_eliminar").val())
	id_materia = $("#materia_eliminar").val();
	$.ajax({
		url: '?/s-materia/eliminar',
		type:'POST',
		data: {'id_materia':id_materia},
		success: function(resp){
			//alert(resp)
			switch(resp){
				case '1': $("#modal_eliminar").modal("hide");
							//dataTable.ajax.reload();
							alertify.success('Se elimino la materia correctamente');
                     listar_materias();
                    break;
				case '2': $("#modal_eliminar").modal("hide");
							alertify.error('No se pudo eliminar ');
							break;
			}
		}
	})
})
    
    
<?php endif ?>
</script>
