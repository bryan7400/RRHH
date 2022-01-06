<?php 

$id_gestion = $_gestion['id_gestion'];
$a_areacalificacion = $db->query("SELECT * FROM cal_area_calificacion as cac INNER JOIN ins_gestion as ig ON ig.id_gestion = cac.gestion_id WHERE cac.gestion_id = $id_gestion AND cac.estado = 'A' ORDER BY cac.orden ASC")->fetch();

?>

<style>
ul{
padding: 0px;
margin: 0px;
}
#mi_lista li{
color: #fff;
background-color: #007bff;
border-color: #007bff;
margin: 0 0 3px;
padding: 10px;
list-style: none;
cursor:pointer;
}
</style>

<!--modal para modal_ordenar_area-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_ordenar_area">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
	  	<div id="mensaje"></div>
            <ul id="mi_lista">
			<?php
		
			foreach($a_areacalificacion as $area_calificacion){
				?>
				<li id="miorden_<?php echo $area_calificacion['id_area_calificacion']; ?>">
					<?php
					echo $area_calificacion['id_area_calificacion'] . " - ";
					echo $area_calificacion['descripcion'];
					?>
				</li>
				<?php
			}
			?>
		</ul>
      
        
      </div>
    </div>
  </div>
</div>

<!--solo para este caso de ordenar traemos esta libreria-->
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script>
$(document).ready(function () {
    $(function () {
                    $("#mi_lista").sortable({update: function () {
							var ordem_atual = $(this).sortable("serialize");
							$.post("?/s-area-calificacion/cambiar-orden", ordem_atual, function (retorno) {
								//Imprimir resultado 
								$("#mensaje").html(retorno);
								//Muestra mensaje
								$("#mensaje").slideDown('slow');
								RetirarMensaje();
							});
						}
                    });
                });
				
// Elimina mensajes despues de un determiando periodo de tiempo 1900 milissegundos
	function RetirarMensaje(){
					setTimeout( function (){
						$("#mensaje").slideUp('slow', function(){});
					}, 1900);
				}
            });
		</script>
