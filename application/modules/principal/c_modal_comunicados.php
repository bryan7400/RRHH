<form id="form_todos">
<div class="modal fade" id="modal_todos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">CREAR COMUNICADOS</h5>
				<span class="spantipo">success</span>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
                <input class="form-control" id="id_comunicado"  name="id_comunicado" placeholder="id_comunicado" type="hidden">
                <input class="form-control" id="accion"  name="accion" placeholder="accion" type="hidden">
				<input id="tipo" type="hidden" class="form-control" placeholder="tipo" name="tipo" >
				<input id="modo_id" type="hidden" class="form-control" placeholder="modo_id" name="modo_id" >
                <input id="aula_asig_mat_id" type="hidden" class="form-control" name="aula_asig_mat_id" placeholder="aula_asig_mat_id">
			</div>
			<div class="modal-body">
                 <div class="controls control-group lista_grupo">  
                <label for="id_persona" class="control-label">Seleccione personas:</label>           
                <select id="id_persona_p" name="id_persona" class="form-control" onchange="listar_a_tabla(0,0);">

                    <option value="">Buscar...</option>
  


                </select>
                 <div class="table-responsive">
				<table id="Tabla_personas" class="table table-bordered table-condensed table-striped table-hover" style="width:100%">
					<thead>
						<tr class="active">
							<th class="text-nowrap" style="display:none"></th>
							<th class="text-nowrap">#</th>
							<th class="text-nowrap">Nombres</th>
							  
							<!--<th class="text-nowrap">Documento</th> -->
							<th class="text-nowrap">Genero</th>
							<th class="text-nowrap">Quitar</th>
							 
						</tr>
					</thead> 					<tbody id="listado_gestion_escolar">
					</tbody>
				</table>
				<hr>
				</div>
                </div>	
				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Titulo: </label>
					<div class="controls">
						<input class="form-control" id="titulo" rows="3" name="titulo">
					</div>
				</div>

				<div class="control-group" style="margin-button:15px">
					<label class="control-label">Descripcion: </label>
					<div class="controls">
						<textarea class="form-control" id="descripcionE" rows="3" name="descripcion"></textarea>
					</div>
				</div>				

				<div class="row">
				<div class="control-group col-6" style="margin-button:15px">
					<label class="control-label">Fecha inicio: </label>
					<div class="controls">
					<input type='text' class='datepicker-here form-control'  id="fecha_ini" name="fecha_ini">
					 
					</div>
				</div>	
                <div class="control-group col-6" style="margin-button:15px">
					<label class="control-label">Fecha fin: </label>
					<div class="controls">
					<input type='text' class='datepicker-here form-control'  id="fecha_fin" name="fecha_fin">
					</div>
				</div>
				<div class="control-group col-4" style="margin-button:15px">
					<label class="control-label">Prioridad: </label>
					<div class="controls">
					  <select name="prioridad" id="prioridad" class='form-control'>
                        <option value="1"  >Baja (normal)</option>
                        <!--<option value="2" style="color:blue">Importante</option>-->
                        <option value="2" style="color:orange">Media</option>
                        <option value="3" style="color:red">Alta</option>
                    </select>
					</div>
				</div>
                    
				<!--<div class="control-group col-6" style="margin-button:15px">
					<label class="control-label">Color: </label>
					<div class="controls">
					<input type="color"  id="color" name="color">
					</div>
				</div>-->
				  <div class="control-group  col-8" style="margin-button:15px">
					<label class="control-label">Adjunto: </label>
					<div class="controls">
					<input type="file" class="form-control"  id="file" name="file">
					</div>
				</div>		
                   </div>
				
 
            	
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-light pull-left" data-dismiss="modal">Cancelar</button>
				<button type="submit" class="btn btn-primary pull-left">Registrar</button>
			</div>
		</div>
	</div>
</div>
</form>

<script>

var disabledDays = [0, 6];
$('#fecha_ini').datepicker({
    timepicker: true,
    language: 'es',
    position:'top left',
    dateFormat: 'yyyy-mm-dd', 
    //startDate: start,
    minHours: 8,
    maxHours: 19,
    timeFormat: "hh:ii:00",//hh:horas ii:minutos mm:seg
    
    /*onRenderCell: function (date, cellType) {
    if (cellType == 'day') {
            var day = date.getDay(),
                isDisabled = disabledDays.indexOf(day) != -1;

            return {
                disabled: isDisabled
            }
        }
    },*/
    onSelect: function(fd, d, picker){
        var fecha_marcada = moment(d).format('YYYY-MM-DD');
        var hoy = moment(new Date()).format('YYYY-MM-DD');

        if(fecha_marcada >= hoy){
          //$("#fecha_inicio").val(moment(fecha_marcada).format('DD-MM-YYYY HH:mm'));
        
        }else{
          alertify.error('No puede asignar tareas en una fecha pasada a la actual');
          $("#fecha_inicio").val("");
        }
        //console.log("asd");
    }
})

$('#fecha_fin').datepicker({
    timepicker: true,
    language: 'es',
    position:'top left',
    dateFormat: 'yyyy-mm-dd', 
    //startDate: start,
    minHours: 8,
    maxHours: 18,
    timeFormat: "hh:ii:00",//hh:horas ii:minutos mm:seg
    //timeFormat: "hh:ii",
    /*onRenderCell: function (date, cellType) {
    if (cellType == 'day') {
            var day = date.getDay(),
                isDisabled = disabledDays.indexOf(day) != -1;

            return {
                disabled: isDisabled
            }
        }
    },*/
    onSelect: function(fd, d, picker){
        var fecha_marcada = moment(d).format('YYYY-MM-DD');
        var hoy = moment(new Date()).format('YYYY-MM-DD');

        if(fecha_marcada >= hoy){

        }else{
            alertify.error('No puede asignar tareas en una fecha pasada a la actual');
            $("#fecha_inicio").val("");
        }
        
    }
})    
    
$("#form_todos").validate({
    rules : { 
		titulo:{required:true},
		//descripcion:{required:true},
        fecha_ini:{required:true}
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
        titulo: "Debe ingresar un motivo.",
		//descripcion: "Debe ingresar una felicitacion.",
		fecha_ini: "Debe ingresar la fecha."        
    },
    //una ves validado guardamos los datos en la DB
  	submitHandler: function(form){
      // alert('guardar comunicados');
      //var datos = $("#form_felicitacion").serialize();     
      //datos=datos+'&accion=guardar_comunicado_docente';
    if($('#tipo').val()=='selec'){
    if($(".id_personas").val()){
        //alert('enviar');
        $('.table-responsive').css('border','0px solid transparent');
        $('.msgtabla').remove(); 
    }else{
        //alert('aqui nnn');
         alertify.warning('Seleccione estudiantes');
        $('.table-responsive').css('border','1px solid red');
        $('.table-responsive').append('<span style="color:red" class="msgtabla">Busca nombres y agregalos al menos un destinatario</span>');
        
      return false;
    }
    }
 
      //var id_comunicado= $("#id_comunicado_p").val();
      //var nombre_evento=$("#nombre_evento_p").val();
      //var descripcion= $("#descripcion_evento_p").val();
      //var color= $("#color_evento_p").val();
      //var fecha_inicio= $("#fecha_inicio_p").val();
      //var fecha_final= $("#fecha_final_p").val();
      //var select_roles=$("#select_roles").val();
      //alert(nombre_evento);
    var form_data = new FormData($("#form_todos")[0]); 
    $.ajax({
          type: 'POST',
          url: "?/principal/procesos",
          data: form_data,
        cache: false,
        contentType: false,
        processData: false,
        datatype: 'text',
        
          success: function (resp) {
            console.log('comunicado personal creado 615:crear-comunic-per...php');
            var d=resp.split("*");
              resp=d[0];
            var personas_id=d[1];
            
            cont = 0;
            switch(resp){
              case '1': //dataTable.ajax.reload();
                        alertify.success('Se registro el comunicado personal correctamente');
                       listarComunicados(); $("#modal_todos").modal("hide"); 
                    // notificarfire(nombre_evento,descripcion,fecha_inicio,'select_roles',id_comunicado,personas_id); 
                   //limpiar();
                        break;
              case '2':
                    listarComunicados();
                    //dataTable.ajax.reload();
                    $("#modal_todos").modal("hide");
                    alertify.success('Se editó el comunicado correctamente'); 
                    // notificarfire(nombre_evento,descripcion,fecha_inicio,'select_roles',id_comunicado,personas_id); 
                   //limpiar();
                    break;
                    
                case '3':
                     
                        alertify.success('Deve seleccionar un nombre de la lista'); break;
                default:
                    alertify.success('Los nombres de los archivos no concuerdan'); 
                    listarComunicados(); 
                    $("#modal_todos").modal("hide");
                    break;
            }
            //pruebaa();
          }
          
    });

 
      
     return false; 
  }
})

</script> 
<!--_____________________________________________________________________-->
 <?php
 //obtiene los roles 
//$roles = $db->query("SELECT * FROM sys_roles WHERE rol != 'Superusuario'")->fetch();
?>
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/bootstrap-select/css/bootstrap-select.css">
 
<!--modal grande styles-->
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
    
 <script>
 
 
 function listar_est_curso(){
     //alert('aqui listar personas()');
	 id_aula_asignacion=$('#id_materia').val();
     
     id_bimestre=$('#bimestre').val();
    //var boton='';

		$.ajax({
		  url: '?/principal/procesos',
            type: 'POST',
            data: {
				'accion': 'listar_estud',
                'id_aula_asignacion':id_aula_asignacion,
                'id_bimestre':id_bimestre
			}, 
			dataType: 'JSON',
			success: function(resp){
              //console.log('hola como estas');
		      //console.log(resp); 
      
        var counter=1;
        //dataTable.clear().draw();//limpia y actualisa la tabla
        try{
            $('#id_persona_p').selectize()[0].selectize.destroy(); //queta selectice para aladir datos
            
        }catch(e){ }
                
                
                
    $('#id_persona_p').html('');
     $('#id_persona_p').append('<option value="">Buscar...</option>');
     // var IDs=[];          
                
        for (var i = 0; i <resp.length; i++) {
            console.log(resp[i]["nombres"]);  
            $('#id_persona_p').append('<option value="'+resp[i]["id_persona"]+'">'+resp[i]["nombres"]+' '+resp[i]["primer_apellido"]+' '+resp[i]["segundo_apellido"]+'</option>'); 
            counter++;
        }//fin for
                
        console.log('fin for');
				 
		 }
		}).done(function(){
            try{
            $('#id_persona_p').selectize();//luegode añador vuelve a asiganra selectice
                
            }catch(e){
                
            }
         //$('#id_persona').selectize(); 
    //$('#id_persona').selectize()[0].selectize.clear();
            
        });
 }

                

    
 var numEst=0; 
function listar_a_tabla(n,x){//x
    var id_persona=0;
   if(x>0){
      id_persona=x; 
    }else{ 
       id_persona=$('#id_persona_p').val();
        numEst++;//en caso de no recibir numero
        n=numEst; 
    }
   // console.log('aqui listar a listar_a_tabla()'+id_persona);
    //var id_persona=$(obj).val();
    //var nombres=$(obj).attr('nombres');//
    
    //try{//pone en blanco el selectice
    //$('#id_persona_p').data('selectize').setValue('');
        
    //}catch(e){} 
    
    
    $.ajax({
        type: 'POST', 
        url: "?/principal/procesos", 
        dataType: 'json', 
        data: {'id_persona': id_persona, 'accion': 'agregar_persona'},
        success: function (data) {
            html = ""; 
            console.log('success de estudainte');
            for(var i=0; i < data.length;i++){
                    var contenido = '';//data[i]['id_inscripcion'] + "*" + data[i]['codigo_estudiante'] + "*" +data[i]['primer_apellido'] + "*" +data[i]['segundo_apellido'] + "*" +data[i]['nombres'] + "*" +data[i]['numero_documento'];
                 
                var gen='';
                if(data[i]["genero"]=='v'){
                    gen=' Varon <span style="color: blue;" class="icon-user"></span>';

                }  else if(data[i]["genero"]=='m'){
                    gen='<span style="color: #ff0bec;" class="icon-user-female"></span> Mujer'; 
                }

               html += '<tr><td style="display:none"><input type="checkbox" class="id_personas" name="id_personas_array[]" value="'+data[i]["id_persona"]+'" checked></td><td class="text-center">'+n+'</td>'+  '<td class="text-justify">'+ data[i]['primer_apellido'] +' '+ data[i]['segundo_apellido'] +' '+ data[i]['nombres'] +'</td>'+  '<td>'+gen+'</td>'+        '<td class="text-center"><a class="btneliminarestudiante"><i class="fa fa-trash" style="color:#b61600"></i></a></td></tr>';
                 
            }

            $("#Tabla_personas").append(html);
            $('.table-responsive').css('border','0px solid transparent');
            //$('.msgtabla').remove(); +  '<td class="text-center">'+ data[i]['numero_documento'] +'</td>'
        }

    });

}  
//eliminar de lista
     $(document).on('click','.btneliminarestudiante', function(){

    console.log('eliminar');

    $(this).parent().parent().remove();
   // $(this).parent().parent().remove();

});
</script>
  