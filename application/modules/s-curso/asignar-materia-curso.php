<?php

// Obtiene los parametros 
// guardamos el id_aula (Curso ;))
$id_aula = (isset($_params[0])) ? $_params[0] : 0;

//$id_est=json_encode($id_estudiante);
// var_dump($id_est);exit();
// Obtiene la cadena csrf 
$csrf = set_csrf();

// Obtiene los estudiantes
//$estudiante = $db->select('z.*')->from('vista_estudiantes z')->where('id_estudiante',$id_estudiante)->fetch_first();
// Obtiene nicel académico
//$nivel = $db->select('z.*')->from('ins_nivel_academico z')->order_by('id_nivel_academico')->fetch();

// Obtiene los estudiantes
//$estud = $db->select('z.*,s.*')->from('ins_estudiante z')->join('sys_persona s','z.persona_id=s.id_persona')->order_by('z.id_estudiante', 'asc')->fetch_first();
//var_dump($estudiante);exit();

$materia = $db->from('pro_materia')->order_by('nombre_materia', 'asc')->fetch();

// Obtiene los permisos  
$permiso_listar = in_array('listar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);
$permiso_crear_familiar = in_array('crear-familiar', $_views);

?>
<?php require_once show_template('header-design'); ?>
<!--link rel="stylesheet" href="assets/themes/concept/assets/vendor/jquery/jquery-ui.css"-->
<link rel="stylesheet" href="assets/themes/concept/assets/vendor/multi-select/css/multi-select.css">

<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Asignar Materias a un Curso</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestion</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Registros iniciales</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Cursos</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Listar</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Asignar materias a curso</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- end pageheader -->
<!-- ============================================================== -->
<div class="row">
    <!-- ============================================================== -->
    <!-- validation form -->
    <!-- ============================================================== -->
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            
            <div class="card-header">
                <!-- <h5 class="card-header">Generador de menús</h5> -->
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                        <div class="text-label hidden-xs">Seleccionar acción:</div>
                        <!-- <div class="text-label visible-xs-block">Acciones:</div> -->
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
                        <div class="btn-group">
                             <div class="input-group">
                                <div class="input-group-append be-addon">
                                    <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item">Seleccionar acción</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="?/s-curso-paralelo/principal" class="dropdown-item">Listar Curso/Paralelo</a>
                                        <a href="?/s-curso-paralelo/crear" class="dropdown-item">Crear Curso/Paralelo</a>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form class="" id="form-menu" method="post" action="?/s-curso/guardar-aula-materia" autocomplete="off">
                    <input type="hidden" name="<?= $csrf; ?>">
                    <div class="row">
                        <input type="hidden" value="<?= $id_aula; ?>" name="aula_id" id="aula_id" class="form-control" autofocus="autofocus" data-validation="required number">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                           <div class="form-group">
                                <label for="paralelo_id" class="control-label">Materias :</label>
                                <div class="card">
                                    <div class="card-body">
                                       <select multiple="multiple" id="my-select" name="my-select[]" style="position: absolute; left: -9999px;">
                                            <?php foreach ($materia as $elemento) : ?>
                                            <option value="<?= $elemento['id_materia']; ?>"><?= escape($elemento['nombre_materia']); ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
					    </div>
                    </div>
                    <div class="row">
                    	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                        	<div class="form-group text-center">
								<button type="submit" class="btn btn-primary">
									<i class="icon-check"></i>
									<span>Registrar</span>
								</button>
								<button type="reset" class="btn btn-light">
									<i class="icon-arrow-left-circle"></i>
									<span>Restablecer</span>
								</button>
							</div>
						</div>
                    </div>
                </form> 
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end validation form -->
    <!-- ============================================================== -->
</div>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/js/jquery.validate.js"></script>
<script src="application/modules/generador-menus/generador-menu.js"></script>    
<script src="assets/themes/concept/assets/vendor/multi-select/js/jquery.multi-select.js"></script>
    
    <script>

    var aMatElegidos = new Array();

    $('#my-select').multiSelect({
        afterSelect: function(values) {            
            //alert("Select value: " + values);
            aMatElegidos.push(values)
            console.log(aMatElegidos);
            //console.log("hola luis");
        },
        afterDeselect: function(values) {
            var pos = aMatElegidos.indexOf(values);
            var aAuxMatElegidos = new Array();

            for(var i = 0; i < aMatElegidos.length; i++ ){
                if(String(aMatElegidos[i]) == String(values)){
                    
                }else{
                    //console.log("i -> "+aMatElegidos[i]);
                    //console.log("value -> "+values);
                    aAuxMatElegidos.push(aMatElegidos[i]);
                }
            }
            aMatElegidos.splice(0,aMatElegidos.length);
            aMatElegidos = aAuxMatElegidos.slice();
            console.log(aMatElegidos);    
        }       
    });
  
    </script>
    <script>
    $('#keep-order').multiSelect({ keepOrder: true });
    </script>
    <script>
    $('#public-methods').multiSelect();
    $('#select-all').click(function() {
        $('#public-methods').multiSelect('select_all');
        return false;
    });
    $('#deselect-all').click(function() {
        $('#public-methods').multiSelect('deselect_all');
        return false;
    });
    $('#select-100').click(function() {
        $('#public-methods').multiSelect('select', ['elem_0', 'elem_1'..., 'elem_99']);
        return false;
    });
    $('#deselect-100').click(function() {
        $('#public-methods').multiSelect('deselect', ['elem_0', 'elem_1'..., 'elem_99']);
        return false;
    });
    $('#refresh').on('click', function() {
        $('#public-methods').multiSelect('refresh');
        return false;
    });
    $('#add-option').on('click', function() {
        $('#public-methods').multiSelect('addOption', { value: 42, text: 'test 42', index: 0 });
        return false;
    });
    </script>
    <script>
    $('#optgroup').multiSelect({ selectableOptgroup: true });
    </script>
    <script>
    $('#disabled-attribute').multiSelect();
    </script>
    <script>
    $('#custom-headers').multiSelect({
        selectableHeader: "<div class='custom-header'>Selectable items</div>",
        selectionHeader: "<div class='custom-header'>Selection items</div>",
        selectableFooter: "<div class='custom-header'>Selectable footer</div>",
        selectionFooter: "<div class='custom-header'>Selection footer</div>"
    });
    </script>

