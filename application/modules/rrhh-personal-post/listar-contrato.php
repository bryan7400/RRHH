<?php  
$id_contrato = (isset($_params[0])) ? $_params[0] : 0;

require_once show_template('header-design');  

$res = $db->query(" SELECT *  
                    FROM per_asignaciones
                    WHERE id_asignacion='$id_contrato'
                    ")->fetch_first();

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

<form class="" id="form-menu" method="post" action="?/rrhh-personal/editar-documento" autocomplete="off">
                
    <input type="hidden" id="id"  name="id"  value="<?= $id_contrato ?>">
    <input type="hidden" id="txt" name="txt" value="">

    <div class="row"> 
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">         
                <div class="card-body">
                    <div class="modal-header">
                        <div class="centered">
                            <div id="editor-contratos">
                                <?php echo $res['documento']; ?>
                            </div>
                        </div>      
                      </div>
                      <div class="modal-footer">    
                        <button type="submit" class="btn btn-default" data-dismiss="modal" aria-label="Cerrarlose">
                            <span class="glyphicon glyphicon-floppy-disk"></span>
                            <span>Cerrar</span>
                            </button>
                        <a href="?/rrhh-personal/imprimir-contrato/<?php echo $id_contrato; ?>" target="_blank">    
                        <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="">
                            <span class="glyphicon glyphicon-floppy-disk"></span>
                            <span>Imprimir</span>
                            </button></a>
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

<link type="text/css" href="sample/css/sample.css" rel="stylesheet" media="screen" />
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
