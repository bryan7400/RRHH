<?php
// Almacena los permisos en variables
$permiso_listar = in_array('egresos-listar', $_views);

// Obtiene la moneda oficial
$moneda = $db->from('gen_monedas')->where('principal', 'S')->fetch_first();
$moneda = ($moneda) ? '(' . $moneda['codigo'] . ')' : '';

// Obtiene el numero de comprobante
$comprobante = $db->query("select ifnull(COUNT(nro_comprobante), 0) + 1 as nro_comprobante from caj_movimientos where tipo = 'e'")->fetch_first();
$nro_comprobante = $comprobante['nro_comprobante'];

?>
<?php require_once show_template('header-design'); ?>
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row"> 
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"> 
        <div class="page-header">
            <h2 class="pageheader-title">Crear nuevo egreso</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Caja</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Crear Asignación</li>
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
                                        <?php if ($permiso_listar) : ?> 
                                        <div class="dropdown-divider"></div>
                                        <a href="?/movimientos/egresos-listar" class="dropdown-item">Listar</a>
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
	            <div class="row">
				    <div class="col-sm-2 col-md-2 "></div>
					<div class="col-sm-8 col-md-8 ">
						<form method="post" action="?/movimientos/egresos-guardar" id="formulario" autocomplete="off">

							<input type="hidden" name="<?= $csrf; ?>">                      

							<div class="form-group">
								<label for="nro_comprobante" class="col-md-3 control-label">Número comprobante:</label>
								<div class="col-md-9">
									<input type="hidden" value="0" name="id_movimiento" data-validation="required">
									<input type="hidden" value="<?= date($_institution['formato']); ?>" name="fecha_movimiento" data-validation="required">
									<input type="hidden" value="<?= date('H:i:s'); ?>" name="hora_movimiento" data-validation="required">
									<input type="text" value="<?= $nro_comprobante; ?>" name="nro_comprobante" readonly id="nro_comprobante" class="form-control" data-validation="required number">
								</div>
							</div>
							<div class="form-group">
								<label for="concepto" class="col-md-3 control-label">Por concepto de:</label>
								<div class="col-md-9">
									<textarea name="concepto" id="concepto" class="form-control" data-validation="required letternumber" data-validation-allowing="+-/.,:;#()\n " autofocus="autofocus"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="monto" class="col-md-3 control-label">Monto <?= $moneda; ?>:</label>
								<div class="col-md-9">
									<input type="text" value="" name="monto" id="monto" class="form-control" data-validation="required number" data-validation-allowing="float">
								</div>
							</div>
							<div class="form-group">
								<label for="observacion" class="col-md-3 control-label">Observación:</label>
								<div class="col-md-9">
									<textarea name="observacion" id="observacion" class="form-control" data-validation="letternumber" data-validation-allowing="+-/.,:;#()\n " data-validation-optional="true"></textarea>
								</div>
							</div>
			 
							<div class="form-group text-center">
								<button type="submit" class="btn btn-primary">
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
</div> 
<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script>
$(function () {
	$.validate({
		modules: 'basic'
	});
});
</script>
<?php require_once show_template('footer-design'); ?>