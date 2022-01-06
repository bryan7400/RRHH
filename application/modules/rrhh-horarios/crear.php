<?php
// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
 $csrf = set_csrf();



?>
 
 <?php $conceptos_pago = $db->query("SELECT * 
 									FROM rhh_concepto_pago 
 									WHERE estado = 'A' AND gestion_id='".$_gestion['id_gestion']."'
 									ORDER BY nombre_concepto_pago ASC
 									")->fetch();// AND gestion_id=1 
 									?>
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/multi-select/css/multi-select.css">
<form id="formCrear">
<div class="modal fade  " id="modal_horario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
				<div class="form-group">
					<label for="dias" class="control-label">Días:</label>
					<select name="dias[]" id="dias" class="form-controlxxxx" autofocus="autofocus" multiple="multiple" data-validation="required length" data-validation-allowing="," data-validation-length="max100">
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
				<label for="" style="background:black;color:#fff;display:block;padding-left:1em">Horario de ingreso y salida</label>
				<div class="row">
				<div class="col-6 form-group control-group">
					<label for="entrada" class="control-label">Entrada:</label>
					<input type="time" value="" name="entrada" id="entrada" class="form-control"  >
					<!-- imput validado con segiundos <input type="text" value="" name="entrada" id="entrada" class="form-control" data-validation="required custom" data-validation-regexp="^(0[0-9]|1[0-9]|2[0123])\:([012345][0-9])\:([012345][0-9])$">-->
				</div>
				<div class="col-6 form-group control-group">
					<label for="salida" class="control-label">Salida:</label>
					<input type="time" value="" name="salida" id="salida" class="form-control"  >
				</div>
				
				</div>
				<label for="" style="background:black;color:#fff;display:block;padding-left:1em">Horario valido de .... hasta ....</label>
				<div class="row">
				<div class="col-6 form-group control-group">
					<label for="entrada" class="control-label">Fecha inicio:</label>
					<input type="date" value="" name="fecha_inicio" id="fecha_inicio" class="form-control"  > 
				</div>
				<div class="col-6 form-group control-group">
					<label for="salida" class="control-label">Fecha final:</label>
					<input type="date" value="" name="fecha_fin" id="fecha_fin" class="form-control"  >
				</div>
				</div>
				<div class="form-group">
					<label for="aplicadoa" class="control-label">Aplicado a:</label>
					<select name="aplicadoa[]" id="aplicadoa" class="form-controlxxxx" autofocus="autofocus" multiple="multiple" data-validation="required length" data-validation-allowing="," data-validation-length="max100">
					<?php //$nivel_docente = $db->query("SELECT * FROM ins_nivel_academico WHERE estado = 'A' AND gestion_id=1 ORDER BY id_nivel_academico ASC")->fetch();?>
					<?php //var_dump('$nivel_docente');exit();// $contador=0;?>
						<option value="">Seleccionar</option>
						<option value="Admi">Administrativos</option>
                       
                        <?php foreach ($nivel_docente as $k => $val): ?>
                        
						<?= '<option value="'.$val['id_nivel_academico'].'">Docentes de  '.$val['nombre_nivel'].'</option>';?>
						 
			 
		                <?php endforeach ?>
						<!--<option value="jue">Administrat</option>-->
						    
						<?php// $contador=0;?>
                       
						<?php// $contador++;?>
						
					</select>
				</div>
				<div class="form-group ">
					<label for="descripcion" class="control-label">Descripción:</label>
					<textarea name="descripcion" id="descripcion" class="form-control"   data-validation-allowing="-+/.,:;@#&'()_\n "  ></textarea>
				</div> 
				<div class="form-group row">
					<label for="aplicadoa" class="control-label col-12">Concepto de descuento por inasistencia o atraso</label>
				<div class="col-8">
				
					<select name="concepto_pago" id="concepto_pago" class="form-control" >
				  
						<option value="0">Ninguno</option> 
                        <?php foreach ($conceptos_pago as $k => $val): ?>
                        
						<?= '<option value="'.$val['id_concepto_pago'].'">  '.$val['nombre_concepto_pago'].'</option>';?> 
		                <?php endforeach ?>
					  
					</select>
				    
				</div>
				<!--<div class="col-4">-->
                <a href="?/rrhh-conceptos-pago/listar" class="btn btn-link col-4 p-1 ">Crear nuevo concepto </a> 
			<!--	</div>-->
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
			
		<button type="reset" class="btn btn-default" id="btn_limpìar">
			<span class="glyphicon glyphicon-refresh"></span>
			<span>Restablecer</span>
		</button>
		<?php  if ($permiso_crear) : ?>
		<button type="submit" class="btn btn-primary" id="btn_guardar">
			<span class="glyphicon glyphicon-floppy-disk"></span>
			<span>Guardar</span>
		</button>
		<?php  endif ?> 
		<?php  if ($permiso_modificar) : ?> 
		<button type="submit" class="btn btn-primary" id="btn_editar">
			<span class="glyphicon glyphicon-floppy-disk"></span>
			<span>Editar</span>
		</button>
		<?php  endif ?> 
	 </div>
    </div>
  </div>
</div>
</form>
 
<script src="<?= js; ?>/jquery.maskedinput.min.js"></script>  
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/js/jquery.validate.js"></script>
<script src="application/modules/generador-menus/generador-menu.js"></script>    
<script src="assets/themes/concept/assets/vendor/multi-select/js/jquery.multi-select.js"></script>

<!--lib reloj-->
<!--<script src="assets/vendor/jquery/jquery-3.3.1.min.js"></script>-->
<link rel="stylesheet" href="application\modules\s-horarios-new/lib/gijgo.css"> 
<script src="application\modules\s-horarios-new/lib/gijgo.min.js"></script>


<script>
    $('#entrada').timepicker();
    $('#salida').timepicker();
    //$('#fecha_inicio').datepicker({ format: 'yyyy-mm-dd' });
    $('#fecha_fin').datepicker({ format: 'yyyy-mm-dd' });
    
   /* var today, datepicker;
    today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
    datepicker = $('#fecha_inicio').datepicker({
        minDate: today,
        format: 'yyyy-mm-dd'
    });*/
    
    $('#fecha_inicio').datepicker({
        minDate: function() {
            var date = new Date();
            date.setDate(date.getDate()-1);
            return new Date(date.getFullYear(), date.getMonth(), date.getDate());
        },
        
        format: 'yyyy-mm-dd'
     });
    
$(function () {
    
	var $dias = $('#dias'), $entrada = $('#entrada'), $salida = $('#salida'), $aplicadoa = $('#aplicadoa');

	/*$.validate({
		modules: 'basic'
	});*/

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
    $aplicadoa.selectize({
		maxOptions: 7,
		onInitialize: function () {
			$aplicadoa.show().addClass('selectize-translate');
		},
		onChange: function () {
			$aplicadoa.trigger('blur');
		},
		onBlur: function () {
			$aplicadoa.trigger('blur');
		}
	});
	
	$('form:first').on('reset', function () {
		$dias.get(0).selectize.clear();
	});

	//$entrada.mask('99:99:99');
	//$salida.mask('99:99:99');
});
    
    
    
    
  
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
		 
        //alert($dias[0].selectize());
		var datos = $("#formCrear").serialize();
        
		//console.log(datos);
        
 
		$.ajax({
			type: 'POST',
			url: "?/rrhh-horarios/procesos",//"?/rh-horarios/guardar",
			data: datos+'&accion='+'guardar_horarios',
			success: function (resp) {
                //alert(resp);
				//console.log(resp);
				switch(resp){
					case '1': 
                           listartabla(); //dataTable.ajax.reload();
							$("#modal_horario").modal("hide");
							 
							alertify.success('Se REGISTRO el horario correctamente');
							break;
					case '2': //dataTable.ajax.reload();
							listartabla();$("#modal_horario").modal("hide"); 
							alertify.success('Se EDITO el horario correctamente'); 
							break;
                    case '11': //dataTable.ajax.reload();
							//$("#modal_familiar").modal("hide"); 
							alertify.warning('deve SELECCIONAR DIAS para guardar'); 
							break;
                    case '10':  
							alertify.warning('deve LLENAR los campos requeridos'); 
							break;
				}
			}
		});
	}
});  
    
    
</script>
<?php //require_once show_template('footer-sidebar'); ?>