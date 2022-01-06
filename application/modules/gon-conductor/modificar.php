<br/><br/><br/><?php

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los formatos
$formato_textual = get_date_textual($_format);
$formato_numeral = get_date_numeral($_format);

$id_gondola = (isset($_params[0])) ? $_params[0] : 0;
//$id_gondola = (isset($params[0])) ? $params[0] : 0;

$gondola = $db->select('*')->from('gon_gondolas')->where('id_gondola',$id_gondola)->fetch_first();
var_dump($id_gondola);

$gondolas = $db->select('z.tipo_gondola')->from('gon_gondolas z')->group_by('z.tipo_gondola')->order_by('z.tipo_gondola', 'asc')->fetch();

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);

?>
<?php require_once show_template('header-design'); ?>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Editar gondola</h2>
                <p class="pageheader-text"></p>
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gondolas</a></li>
                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">editar gondola</a></li>
                            <!--                            <li class="breadcrumb-item active" aria-current="page">Listar</li>-->
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- ============================================================== -->
        <!-- row -->
        <!-- ============================================================== -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <!-- <h5 class="card-header">Generador de menús</h5> -->
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
                                            <li class="dropdown-header visible-xs-block">Seleccionar acción</li>
                                            <li><a href="?/gon-gondolas/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar gondolas</a></li>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- ============================================================== -->
                    <!-- datos -->
                    <!-- ============================================================== -->

                    <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                        <form method="post" action="?/gon-gondolas/guardar" autocomplete="off" class="form-horizontal">
                            <input type="hidden" name="<?= $csrf; ?>">
                            <input type="hidden" value="<?= $gondola['id_gondola']; ?>" name="id_gondola">
                            <div class="form-group">
                                <label for="ruta" class="control-label">Nombre:</label>
                                <input type="text" value="<?= $gondola['nombre']; ?>" name="ruta" id="ruta" class="form-control" autofocus="autofocus" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max50">
                            </div>
                            <div class="form-group">
                                <label for="descripcion" class="control-label">descripcion:</label>
                                <textarea name="descripcion" id="descripcion" cols="30" rows="2" class="form-control"><?= $gondola['descripcion']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="capacidad" class="control-label">Capacidad:</label>
                                <input type="text" value="<?= $gondola['capacidad']; ?>" name="capacidad" id="capacidad" class="form-control" data-validation="required number">
                            </div>
                            <div class="form-group">
                                <label for="placa" class="control-label">Placa:</label>
                                <input type="text" value="<?= $gondola['placa'] ?>" name="placa" id="placa" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max50">
                            </div>
                            <div class="form-group">
                                <label for="tipo_gondola" class="control-label">Tipo gondola:</label>
                                <select name="tipo_gondola" id="tipo_gondola" class="form-control text-uppercase" data-validation="letternumber" data-validation-allowing="-+./& " data-validation-optional="true">
                                    <?php foreach ($gondolas as $gondola) {
                                        if($gondola['tipo_gondola'] == $gondolas['tipo_gondola']){ ?>
                                        <option value="<?= escape($gondola['tipo_gondola']); ?>" selected ><?= escape($gondola['tipo_gondola']); ?></option>
                                    <?php }else{ ?>
                                        <option value="<?= escape($gondola['tipo_gondola']); ?>"><?= escape($gondola['tipo_gondola']); ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <!--                            <div class="form-group">-->
                            <!--                                <label for="conductor_id" class="control-label">Conductor:</label>-->
                            <!--                                <input type="text" value="" name="conductor_id" id="conductor_id" class="form-control" data-validation="required number">-->
                            <!--                            </div>-->
                            <!--                            <div class="form-group">-->
                            <!--                                <label for="archivo_gondola_id" class="control-label">Archivo_gondola:</label>-->
                            <!--                                <input type="text" value="" name="archivo_gondola_id" id="archivo_gondola_id" class="form-control" data-validation="required number">-->
                            <!--                            </div>-->
                            <!--                            <div class="form-group">-->
                            <!--                                <label for="estado" class="control-label">Estado:</label>-->
                            <!--                                <input type="text" value="" name="estado" id="estado" class="form-control" data-validation="required number">-->
                            <!--                            </div>-->
                            <!--                            <div class="form-group">-->
                            <!--                                <label for="usuario_registro" class="control-label">Usuario registro:</label>-->
                            <!--                                <input type="text" value="" name="usuario_registro" id="usuario_registro" class="form-control" data-validation="required number">-->
                            <!--                            </div>-->
                            <!--                            <div class="form-group">-->
                            <!--                                <label for="fecha_registro" class="control-label">Fecha registro:</label>-->
                            <!--                                <input type="text" value="" name="fecha_registro" id="fecha_registro" class="form-control" data-validation="required custom" data-validation-regexp="^([12][0-9]{3})-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01]) (0[0-9]|1[0-9]|2[0123])\:([012345][0-9])\:([012345][0-9])$">-->
                            <!--                            </div>-->
                            <!--                            <div class="form-group">-->
                            <!--                                <label for="usuario_modificacion" class="control-label">Usuario modificacion:</label>-->
                            <!--                                <input type="text" value="" name="usuario_modificacion" id="usuario_modificacion" class="form-control" data-validation="required number">-->
                            <!--                            </div>-->
                            <!--                            <div class="form-group">-->
                            <!--                                <label for="fecha_modificacion" class="control-label">Fecha modificacion:</label>-->
                            <!--                                <input type="text" value="" name="fecha_modificacion" id="fecha_modificacion" class="form-control" data-validation="required custom" data-validation-regexp="^([12][0-9]{3})-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01]) (0[0-9]|1[0-9]|2[0123])\:([012345][0-9])\:([012345][0-9])$">-->
                            <!--                            </div>-->
                            <div class="form-group">
                                <button type="submit" class="btn btn-danger">
                                    <span class="glyphicon glyphicon-floppy-disk"></span>
                                    <span>Guardar</span>
                                </button>
                                <button type="reset" class="btn btn-default">
                                    <span class="glyphicon glyphicon-refresh"></span>
                                    <span>Restablecer</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= js; ?>/jquery.form-validator.min.js"></script>
    <script src="<?= js; ?>/jquery.form-validator.es.js"></script>
    <script src="<?= js; ?>/jquery.maskedinput.min.js"></script>
    <script src="<?= js; ?>/selectize.min.js"></script>
    <script>
        $(function () {
            $.validate({
                modules: 'basic'
            });

            $('#fecha_registro').mask('9999-99-99 99:99:99');

            $('#fecha_modificacion').mask('9999-99-99 99:99:99');

            var $tipo = $('#tipo_gondola');
            $tipo.selectize({
                persist: false,
                createOnBlur: true,
                create: true,
                onInitialize: function () {
                    $tipo.css({
                        display: 'block',
                        left: '-10000px',
                        opacity: '0',
                        position: 'absolute',
                        top: '-10000px'
                    });
                }
            });
        });
    </script>
<?php require_once show_template('footer-design'); ?>