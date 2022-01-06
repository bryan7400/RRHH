 <!--COPIAR IDENTICO AL MODALCREAR Y SOLO CAMBIAR EL ID A "modalver"-->
 
 <!--agregar a cada id delante de su valor id= "v_-->
<form id="formver">
<div class="modal fade  " id="modalver" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
				<input type="hidden" name="id_horario" v_="v_id_horario">
				<div class="form-group">
					<label for="dias" class="control-label">Días:</label>
					<select name="dias[]" id="v_dias" class="form-controlxxxx" autofocus="autofocus" multiple="multiple" data-validation="required length" data-validation-allowing="," data-validation-length="max100">
						<option value="">Seleccionar</option>
						<option value="lun">Lunes</option>
						<option value="mar">Martes</option>
						<option value="mie">Miércoles</option>
						<option value="jue">Jueves</option>
						<option value="vie">Viernes</option>
						<option value="sab">Sábado</option>
						<option value="dom">Domingo</option>
					</select><!--<select name="dias[]" id="dias" class="form-controlxxxx" autofocus="autofocus" multiple="multiple" data-validation="required length" data-validation-allowing="," data-validation-length="max100">
						<option value="">Seleccionar</option>
						<option value="lun">Lunes</option>
						<option value="mar">Martes</option>
						<option value="mie">Miércoles</option>
						<option value="jue">Jueves</option>
						<option value="vie">Viernes</option>
						<option value="sab">Sábado</option>
						<option value="dom">Domingo</option>
					</select>-->
				</div>
				<div class="form-group control-group">
					<label for="entrada" class="control-label">Entrada:</label>
					<input type="text" value="" name="entrada" id="v_entrada" class="form-control" data-validation="required custom" data-validation-regexp="^(0[0-9]|1[0-9]|2[0123])\:([012345][0-9])\:([012345][0-9])$">
				</div>
				<div class="form-group control-group">
					<label for="salida" class="control-label">Salida:</label>
					<input type="text" value="" name="salida" id="v_salida" class="form-control" data-validation="required custom" data-validation-regexp="^(0[0-9]|1[0-9]|2[0123])\:([012345][0-9])\:([012345][0-9])$">
				</div>
				<div class="form-group ">
					<label for="descripcion" class="control-label">Descripción:</label>
					<textarea name="descripcion" id="v_descripcion" class="form-control"   data-validation-allowing="-+/.,:;@#&'()_\n "  ></textarea>
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
				 
	 </div>
 
   
    </div>
  </div>
</div>
</form>
 
 
 
<?php //require_once show_template('footer-sidebar'); ?>