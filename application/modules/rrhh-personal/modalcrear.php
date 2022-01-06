<?php
// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
 $csrf = set_csrf();
?>
 
<style>
     @media (min-width: 992px){
    .modal-grande{
        width: 90% !important;
    }
     }
     @media (min-width: 768px){
    /*.modal-dialog*/    
    .modal-grande{
        width: 90% !important;
        margin: 30px auto;
    }
     }
     
     @media (min-width: 576px){
    .modal-grande {
        max-width: 90% !important;
        margin: 1.75rem auto;
     } }
</style> 

<!-- formularioo de horario VIEW-->
<form id="formhorario">
	<div class="modal fade  " id="modal_horario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg" role="document">
    		<div class="modal-content "><!--modal-grande-->
				<div class="modal-header">
				    <h5 class="modal-title" id="exampleModalLabel"><span id="modal_horario_titulo"></span>Horarios de personal <a href="#" onclick="abrir_crear_horario()" class="btn btn-success">Nuevo</a>
				      	<input type="hidden" name="id_componente1" id="id_componente1"> 
				      	<input type="hidden" name="id_horarios1" id="id_horarios1"> 
				    </h5>
				       
				    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
				      	<span aria-hidden="true">&times;</span>
				    </a>
				</div>
				<div class="modal-body"> 
					<div class="panel-body">
					  	<div class="row">
							<div class="col-sm-12 col-sm-offset-12 col-md-12 col-md-offset-3">
								<!--<form method="post" action="?/rh-horarios/guardar" autocomplete="off" id="formCrear" class="">-->
								<input type="hidden" name="<?= $csrf; ?>">
								
								<!--<div class="form-group">
									<label for="dias" class="control-label">Fecha inicio:</label>
									<input type="date" value="" name="fecha_inicio" id="fecha_inicio" class="form-control"> 
								</div>-->
							 
								<table id="table" class="table  table-condensed   horarios_table">
				                    <thead>
				                        <tr class="active">
				                            <th class="text-nowrap">#</th>
				                            <th class="text-nowrap">Días</th>
				                            <th class="text-nowrap">Entrada</th>
				                            <th class="text-nowrap">Salida</th>
				                            <th class="text-nowrap">Descripción</th> 
				                            <th class="text-nowrap">Opciones</th>

				                        </tr>
				                    </thead>

				                    <tbody>

				                    </tbody>
				                </table>
				                
								<!--<div class="form-group horarios_list">
				                
									<div class="checkbox">
				                      <label><input type="checkbox" value="">Lunes, martes miercoles</label>
				                    </div>
				                    <div class="checkbox">
				                      <label><input type="checkbox" value="">Option 2</label>
				                    </div>
				                     
								</div> -->
									
								<!--</form>-->
							</div>
					  	</div>
					</div>  
				    
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
						<span class="glyphicon glyphicon-floppy-disk"></span>
						<span>Cerrar</span>
						</button>
					<button type="reset" class="btn btn-default" id="btn_limpìar">
						<span class="glyphicon glyphicon-refresh"></span>
						<span>Restablecer</span>
					</button>
					<button type="submit" class="btn btn-primary" id="btn_guardar">
						<span class="glyphicon glyphicon-floppy-disk"></span>
						<span>Guardar</span>
					</button>
					<!--<button type="submit" class="btn btn-primary" id="btn_editar">
						<span class="glyphicon glyphicon-floppy-disk"></span>
						<span>Editar</span>
					</button>-->
		 		</div> 
	    	</div>
	  	</div>
	</div>
</form>

<!--FORMULARIO DE EDITAR O CREAR HORARIO-->
<form id="formCrear">
	<div class="modal fade  " id="modal_horario_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  	<div class="modal-dialog modal-xl" role="document">
	    	<div class="modal-content ">
				<div class="modal-header">
				    <h5 class="modal-title" id="exampleModalLabel"><span id="modal_horario_titulo"></span> Horarios de asistencia</h5>
			        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </a>
				</div>
				<div class="modal-body">
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-12 col-sm-offset-12 col-md-12 col-md-offset-3">
								<!--<form method="post" action="?/rh-horarios/guardar" autocomplete="off" id="formCrear" class="">-->
								<input type="hidden" name="<?= $csrf; ?>">
								<input type="hidden" name="id_componente" id="id_componente">
								<input type="hidden" name="id_asignacion" id="id_asignacion">
								<div class="form-group">
									<label for="dias" class="control-label">Días:</label>
									<select name="dias[]" id="dias" class="form-controlxxx" autofocus="autofocus" multiple="multiple" data-validation="required length" data-validation-allowing="," data-validation-length="max100">
										<option value="">Seleccionar</option>
										<option value="lun">Lunes</option>
										<option value="mar">Martes</option>
										<option value="mie">Miércoles</option>
										<option value="jue">Jueves</option>
										<option value="vie">Viernes</option>
										<option value="sab">Sábado</option>
										<option value="dom">Domingo</option>
									</select>
								</div>
								<div class="form-group control-group">
									<label for="entrada" class="control-label">Entrada:</label>
									<input type="text" value="" name="entrada" id="entrada" class="form-control" data-validation="required custom" data-validation-regexp="^(0[0-9]|1[0-9]|2[0123])\:([012345][0-9])\:([012345][0-9])$">
								</div>
								<div class="form-group control-group">
									<label for="salida" class="control-label">Salida:</label>
									<input type="text" value="" name="salida" id="salida" class="form-control" data-validation="required custom" data-validation-regexp="^(0[0-9]|1[0-9]|2[0123])\:([012345][0-9])\:([012345][0-9])$">
								</div>
								<div class="form-group ">
									<label for="descripcion" class="control-label">Descripción:</label>
									<textarea name="descripcion" id="descripcion" class="form-control" data-validation-allowing="-+/.,:;@#&'()_\n "></textarea>
								</div>
								<div class="form-group" style="display:none">
									<label for="descripcion" class="control-label">Actividad:</label>
									<select name="active" id="active" class="form-control">
									    <option value="s">Activo</option>
									    <option value="n">Inactivo</option>
									</select>
								</div> 
								<!--</form>-->
							</div>
						</div>
					</div> 
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
						<span class="glyphicon glyphicon-floppy-disk"></span>
						<span>Cerrar</span>
						</button>
					<button type="reset" class="btn btn-default btn_limpìar">
						<span class="glyphicon glyphicon-refresh"></span>
						<span>Restablecer</span>
					</button>
					<button type="submit" class="btn btn-primary btn_guardar">
						<span class="glyphicon glyphicon-floppy-disk"></span>
						<span>Guardar</span>
					</button>
					<button type="submit" class="btn btn-primary btn_editar">
						<span class="glyphicon glyphicon-floppy-disk"></span>
						<span>Editar</span>
					</button>
			 	</div>
		    </div>
	  	</div>
	</div>
</form>
 




<!--
<form id="formCrear1">
<div class="modal fade  " id="modal_crear1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content ">
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel"><span id="modal_horario_titulo"></span> Asignacion de horarios</h5>
        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </a>
</div>
<div class="modal-body"> 
<div class="panel-body">
  <div class="row">
		<div class="col-sm-12 col-sm-offset-12 col-md-12 col-md-offset-3">
		 
				<input type="hidden" name="<?= $csrf; ?>">
				<input type="hidden" name="id_componente" id="id_componente">
				<div class="form-group">
					<label for="dias" class="control-label">Fecha inicio:</label>
					<input type="date" value="" name="fecha_inicio" id="fecha_inicio" class="form-control"> 
				</div>
				 
				<div class="form-group control-group">
					<label for="salida" class="control-label">Fecha final:</label>
					<input type="date" value="" name="fecha_final" id="fecha_final" class="form-control"> 
				</div>
				<div class="form-group ">
					<label for="descripcion" class="control-label">Descripcion:</label>
					<textarea name="descripcion" id="descripcion" class="form-control"   data-validation-allowing="-+/.,:;@#&'()_\n "  ></textarea>
				</div> <div class="form-group horarios_list">
					<div class="checkbox">
                      <label><input type="checkbox" value="">Lunes, martes miercoles</label>
                    </div>
                    <div class="checkbox">
                      <label><input type="checkbox" value="">Option 2</label>
                    </div>
                    <div class="checkbox disabled">
                      <label><input type="checkbox" value="" disabled>Option 3</label>
</div>
				</div> 
				
			 
		</div>
  </div>
</div>  
    
</div>
<div class="modal-footer">
                
					<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
						<span class="glyphicon glyphicon-floppy-disk"></span>
						<span>Cerrar</span>
						</button>
					<button type="reset" class="btn btn-default" id="btn_limpìar">
						<span class="glyphicon glyphicon-refresh"></span>
						<span>Restablecer</span>
					</button>
					<button type="submit" class="btn btn-primary" id="btn_guardar">
						<span class="glyphicon glyphicon-floppy-disk"></span>
						<span>Guardar</span>
					</button>
					<button type="submit" class="btn btn-primary" id="btn_editar">
						<span class="glyphicon glyphicon-floppy-disk"></span>
						<span>Editar</span>
					</button>
	 </div>
 
    </div>
  </div>
</div>
</form>-->

 <!--MODAL VER-->
<div class="modal fade" id="modalver" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-xl" role="document">
    	<div class="modal-content ">
			<div class="modal-header">
			    <h5 class="modal-title" id="exampleModalLabel"><span id="modal_titulo"></span>  </h5>
		        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </a>
			</div>
			<div class="modal-body"> 
				<div class="row">
					<div class="col-sm-12 col-sm-offset-12 col-md-12 col-md-offset-3"> 
				        <div class="form-group v_dato1"> </div>
				        <div class="form-group v_dato2"> </div>
				        <div class="form-group v_dato3"> </div>
				        <div class="form-group v_dato4"> </div> 
					</div>
				</div> 
			</div>
			<div class="modal-footer">            
				<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
					<span class="glyphicon glyphicon-floppy-disk"></span>
					<span>Cerrar</span>
				 </button>
	 		</div>
    	</div>
  	</div>
</div>
 

<script src="<?= js; ?>/jquery.maskedinput.min.js"></script>  
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/js/jquery.validate.js"></script>
<script src="application/modules/generador-menus/generador-menu.js"></script>    
<script src="assets/themes/concept/assets/vendor/multi-select/js/jquery.multi-select.js"></script>

<script> 
$(function () {
	var $dias = $('#dias');
	var $entrada = $('#entrada');
	var $salida = $('#salida');
	///$.validate({
	//	modules: 'basic'
	//});
	$dias.selectize({
		maxOptions: 7,
		onInitialize: function () {
			$dias.show().addClass('selectize-translate');
		},
		onChange: function () {
			$dias.trigger('blur');
		},
		onBlur: function () {
			$dias.trigger('blur');
		}
	});
	
	$('form:first').on('reset', function () {
		$dias.get(0).selectize.clear();
	});
	$entrada.mask('99:99:99');
	$salida.mask('99:99:99');
});
     
var rutaproceso='?/rrhh-personal/procesos';
//lista horas de usuario

function listarHorario(){
    // alert($("#id_componente1").val()); 
    var componentes_id=$("#id_componente1").val();
    //var idhorarios=$("#id_horarios1").val(); 
    
    //alert(componentes_id);
    
    $('.horarios_table').find('tbody').html(' ');
    $.ajax({
    url: rutaproceso,
    type: 'POST',
    data:{
		'accion': 'listarhorarios',
        'componentes_id':componentes_id//,
        //'horarios_id':idhorarios
	},
    dataType: 'JSON',
    success: function(resp){
        //console.log('Listar horarios '+ resp); 
        $('.horarios_table').find('tbody').html('');
        var cc=1;
        for (var i = 0; i < resp.length; i++) {
        
	        var datos=resp[i]['id_horario']+'*'+resp[i]['dias']+ '*'+resp[i]['entrada']+ '*'+resp[i]['salida']+ '*'+resp[i]['descripcion']+ '*'+resp[i]['active'];
	        
	        var botones='';

	        //'<a href="#" data-toggle="tooltip" data-title="Ver horario" class="btn btn-outline-info btn-xs" onclick="abrir_ver('+"'"+datos+"'"+');"><span class="fa fa-eye" ></span></a>';
	        
	        botones+='<a href="#" class="btn btn-outline-warning btn-xs" onclick="abrir_editar_horarios('+"'"+datos+"'"+');"><span class="fa fa-edit"></span></a>';
	        var active='';
	        var activeback='';
	        if(resp[i]['active']=='s'){
	           	//active='btn-success';
	            botones+='<a href="#" data-toggle="tooltip" data-title="Eliminar horario" data-eliminar="true" class="btn btn-success btn-xs" onclick="abrir_actividad('+"'"+datos+"'"+');" title="Prender/apagar"><span class="fa fa-power-off"></span></a>';//acciones de la ultima columna
	           	}else{
	             	botones+='<a href="#" data-toggle="tooltip" data-title="Eliminar horario" data-eliminar="true" class="btn bg-dark  btn-xs" onclick="abrir_actividad('+"'"+datos+"'"+');" title="Prender/apagar"><span class="fa fa-power-off"></span></a>';//acciones de la ultima columna
	            	activeback='style="background: #c7c7cc;"';
	         	}         
	        	botones+='<a href="#" data-toggle="tooltip" data-title="Eliminar horario" data-eliminar="true" class="btn btn-outline-danger btn-xs" onclick="abrir_eliminar_horario('+resp[i]['id_horario']+');" ><span class="fa fa-trash-alt"></span></a>';//acciones de la ultima columna

	        	$('.horarios_table').find('tbody').append('<tr '+activeback+'><td><div class="checkbox"> <input type="hidden" checked value="'+resp[i]['id_horario']+'" name="id_horarios_array[]"></div>'+cc+'</td> <td>'+resp[i]['dias']+'</td> <td>'+resp[i]['entrada']+'</td><td>'+resp[i]['salida']+'</td>  <td>'+resp[i]['descripcion']+'</td><td>'+botones+'</td></tr>');
	            cc++;
	        }
	   	}
 	}); 
}
//abre modal crear muevo
function abrir_crear_horario(e){
    
    var idusuario=$("#id_componente1").val(); 
    $("#id_asignacion").val(idusuario);
    
    //$("#formCrear")[0].reset(); 
    $("#modal_horario_1").modal("show");
     //limpiamos valores de dias selectize
    $("#dias").data('selectize').setValue('');
    //alert('creando');
    $(".btn_editar").hide();
    $(".btn_guardar").show();
    $(".btn_limpìar").hide();
    listarHorario();
     return false;
}

//modal degundo editar
function abrir_editar_horarios(contenido){
	var d = contenido.split("*");
	var id_horario = d[0];// id_horario
	var dias = d[1];// dias 
	var inicio = d[2];// inicio 
	var final = d[3];// final 
	var comentario = d[4];// comentario 
	var diasArray = d[1].split(',');

	//alert('editar horario'+d[5]);
	//console.log(diasArray);
	$("#modal_horario_1").modal("show");
	$("#id_asignacion").val($("#id_componente1").val());//id_persona
	$("#id_componente").val(d[0]); 
	$("#dias").data('selectize').setValue(diasArray);    
	$("#entrada").val(d[2]);
	$("#salida").val(d[3]);
	$("#descripcion").val(d[4]);
	$("#active").val(d[5]);

	$(".btn_editar").show();
	$(".btn_guardar").hide();
	$(".btn_limpìar").hide();
	//$("#select_roles").
}
    
function abrir_eliminar_horario(id_horario){
	alert("sad");
	//preguntar si se eliminara?
    alertify.confirm('<span style="color:red">ELIMINAR HORARIO</span>','Debe estar con autorizacion de para esta accion, si lo tiene ¿desea eliminar?',function(){$.ajax({
            url: '?/rrhh-personal/procesos',
            type:'POST',
            data: {accion:'eliminar_horarios',
                'id_componente':id_horario},
            success: function(resp){
                // alert(resp);
                switch(resp){
                    case '1':// $("#modal_eliminar").modal("hide");
                    alertify.success('Se elimino el horario correctamente');
                    listarHorario(); break;
                    case '2': //$("#modal_eliminar").modal("hide");
                     alertify.error('No se pudo eliminar '+resp); 
                    break;
                }
                //$(this).parent().parent().remove();
            }
        }) ;
         //alertify.success('Eliminado')  
    }, function(){ 
        alertify.notify('No eliminado', 'custom');
        //alertify.notify('custom message.', 'custom', 20);
        //alertify.error('Cancel');
    })
}

function abrir_actividad(datos){
    var d = datos.split("*");
    var id_horario=d[0];
    var activ=d[5];
    //alert('aqui'+activ);
	//preguntar si se eliminara?
    alertify.confirm('<span style="color:red">DESABILITAR HORARIO</span>', 'Debe estar con autorizacion de para esta accion, afecta al control de asistencia de la persona actual ¿Desea cambiar?', function(){
        
        $.ajax({
            url: '?/rrhh-personal/procesos',
            type:'POST',
            data: {accion:'actividad_horarios',
                'id_componente':id_horario,
                'actividad':activ},
            success: function(resp){
                // alert(resp);
                switch(resp){
                    case '1':
	                    //$("#modal_eliminar").modal("hide");
	                    alertify.success('Se cambio el horario correctamente');
	                        
	                    listarHorario(); 
	                    break;
                    case '2':
	                    //$("#modal_eliminar").modal("hide");
	                     alertify.error('No se pudo cambiar '+resp); 
	                    break;
                }
                //$(this).parent().parent().remove();
            }
        }) ; 
    }, function(){ 
        alertify.notify('No eliminado', 'custom'); 
    });
    return false;
}

$("#formCrear").validate({
	rules: {dias: {required: true},
            entrada: {required: true}, 
            salida: {required: true}},
        errorClass: "help-inline",  
        errorElement: "span",
        highlight: highlight,
        unhighlight: unhighlight,
		messages: {dias: "Debe ingresar una hora.",
                   entrada: "Debe ingresar una hora.",
                  salida: "Debe ingresar una hora."},
	//una ves validado guardamos los datos en la DB
	submitHandler: function(form){
		 
		var datos = $("#formCrear").serialize();
        
		$.ajax({
			type: 'POST',
			url: "?/rrhh-personal/procesos",//"?/rh-horarios/guardar",
			data: datos+'&accion='+'guardar_horarios',
			success: function (resp) {
                //alert(resp);
				//console.log(resp);
				switch(resp){
					case '1': 
                           //listartabla(); //dataTable.ajax.reload();
                            listarHorario(); 
							$("#modal_horario_1").modal("hide");
							 
							alertify.success('Se REGISTRO el horario correctamente');
							break;
					case '2': //dataTable.ajax.reload();
							listarHorario();
                            $("#modal_horario_1").modal("hide"); 
							alertify.success('Se EDITO el horario correctamente'); 
							break;
                    case '11': //dataTable.ajax.reload();
							//$("#modal_familiar").modal("hide"); 
							alertify.warning('debe SELECCIONAR DIAS para guardar'); 
							break;
                    case '10':  
							alertify.warning('debe LLENAR los campos requeridos'); 
							break;
				}
			}
		});
	}
});  
</script>