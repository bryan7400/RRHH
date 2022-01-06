<?php
$id_contrato = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();  
$gestion=$_gestion['id_gestion'];
// Obtiene los contratos
$contrato = $db->query("SELECT * FROM rrhh_contrato 
                        WHERE id_contrato='$id_contrato'")
                        ->fetch_first();
// Obtiene los permisos 
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_editar = in_array('editar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views); 
$permiso_contrato  = in_array('editar', $_views);



require_once show_template('header-design');  



?>
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">PERSONAL</h2>
            <p></p>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">RRHH</a></li>
                        <!--<li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Registros iniciales</a></li>-->
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Lista de personal </a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!--cuerpo card table--> 

<?php //echo "df".$res['id_asignacion']." - ".$id_contrato; ?>

<form class="" id="form-menu" method="post" action="?/rrhh-contratos/editar2" autocomplete="off">
                
    <input type="hidden" id="id"  name="id"  value="<?= $id_contrato ?>">
    <input type="hidden" id="txt" name="txt" value="">

    <div class="row"> 
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">         
                <div class="card-body">
                    <div class="modal-header">
                        <div class="centered">
                            <div id="editor-contratos">
<?php echo $contrato['documento']; ?>
                            </div>
                        </div>      
                      </div>
                      <div class="modal-footer">    
                        <a href="?/rrhh-contratos/listar" target="_blank">    
                        <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Cerrarlose">
                            <span class="glyphicon glyphicon-floppy-disk"></span>
                            <span>Cerrar</span>
                        </button>
                        </a>
                        <a href="?/rrhh-contratos/imprimir/<?php echo $id_contrato; ?>" target="_blank">    
                            <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="">
                                <span class="glyphicon glyphicon-floppy-disk"></span>
                                <span>Imprimir</span>
                            </button>
                        </a>
                        <button type="submit" class="btn btn-primary" id="btn_guardar" onclick="copy();">
                            <span class="glyphicon glyphicon-floppy-disk"></span>
                            <span>Guardar</span>
                        </button>                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


<style>
    .ajs-message.ajs-custom { 
        color: #31708f;  
        background-color: #d9edf7;  
        border-color: #31708f; 
    }
</style>

<script src="<?= js ?>/ckeditor.js"></script>

<script>
    ClassicEditor
    .create( document.querySelector('#editor-contratos'), {
    } )
    .then( editor => {
        window.editor_contratos = editor_contratos;
    } )
    .catch( err => {
        console.error( err.stack );
    } );
    function copy(){
        txt=$('.ck-editor__editable').html();
        $('#txt').val(txt);
    }
</script>
