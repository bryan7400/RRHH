<html lang="en"> 
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Postulación</title>
    <link rel="icon" type="image/png" href="<?= project; ?>/icono.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="assets/themes/concept/assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/themes/concept/assets/libs/css/style.css">
    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <!--link rel="stylesheet" href="assets/themes/concept/assets/vendor/cropper-mazter/css/cropper.css"-->
    <link href="assets/themes/concept/assets/vendor/datepicker/css/datepicker.css" rel="stylesheet">
    <link href="assets/themes/concept/assets/vendor/bootstrap-fileinput-master/css/fileinput.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">
    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/alertify/css/alertify.min.css">
    <style>
        html,  
        body {
            height: 100%; 
            background-image: url("fondo.jpg");
            background-size: cover;
        }

        body {
            background-image: url("fondo.jpg"); 
            background-size: cover;
            display: -ms-flexbox; 
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            padding-top: 14%;
            padding-bottom: 40px;
        }
        .splash-container {
            max-width: 1200px;
            width: 100%;
            padding: 15px;
            margin: auto;
        }
        .floatt{
            float: left;
            margin-bottom: 10px;
        }
        .floatt2{
            float: left;
            padding: 0px;
            text-align: center;
        }
        .tittle{
            background-color: #000;
            color: #fff;
            font-weight: bold;
            padding: 15px;
        }
        .datepicker-here{
            padding-bottom: 12px;
            padding-top: 12px;
        }
        .help-block, 
        .form-error{
            color: #f00;
        }
    </style>
</head>

<body>
    <div class="splash-container" id="postulacion" style="display:true">
        <div class="card">
            <div class="card-header text-center">
                <a href="#">
                    <img class="logo-img" src="assets/imgs/<?= $_institution["logo_color"]; ?>" width="200px" alt="logo">
                </a>
                <span class="splash-description"><small STYLE="font-size:30px;">FORMULARIO DE POSTULACIÓN</small></span>
            </div>
            <div class="card-body">
                <?php if ($message = get_notification()) : ?>
                  <div class="alert alert-<?= $message['type']; ?>">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong><?= $message['title']; ?></strong>
                    <p><?= $message['content']; ?></p>
                  </div>
                <?php endif ?>
                <!--  action = "?/sitio/postulacion" -->
                <form id="form_gestion" name="form_gestion" method="post">
                    <div class="col-sm-4 floatt">
                        <div class="form-group">
                            Apellido Paterno
                            <input class="form-control form-control-lg" name="paterno" id="paterno" type="text" placeholder="" autocomplete="off" autofocus="autofocus" data-validation="required" data-validation-allowing="" data-validation-length="max50">
                        </div>
                    </div>
                    <div class="col-sm-4 floatt">
                        <div class="form-group">
                            <font style="color:red">*</font> Apellido Materno
                            <input class="form-control form-control-lg" name="materno" id="materno" type="text" placeholder="" autocomplete="off" autofocus="autofocus" data-validation="required">
                        </div>
                    </div>
                    <div class="col-sm-4 floatt">
                        <div class="form-group">
                            <font style="color:red">*</font> Nombres
                            <input class="form-control form-control-lg" name="nombre" id="nombre" type="text" placeholder="" autocomplete="off" autofocus="autofocus" data-validation="required">
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                    
                    <div class="form-group">
                        <span class="col-sm-12 floatt"><b>Lugar de Nacimiento</b></span>
                        <br>
                        <br>
                    </div>

                    <div class="col-sm-3 floatt">
                        <div class="form-group">
                            <font style="color:red">*</font> Nacionalidad
                            <input class="form-control form-control-lg" name="nacionalidad" id="nacionalidad" type="text" placeholder="" autocomplete="off" autofocus="autofocus" data-validation="required">
                        </div>
                    </div>
                    <div class="col-sm-3 floatt">
                        <div class="form-group">
                            <font style="color:red">*</font> Localidad
                            <input class="form-control form-control-lg" name="localidad" id="localidad" type="text" placeholder="" autocomplete="off" autofocus="autofocus" data-validation="required">
                        </div>
                    </div>
                    <div class="col-sm-3 floatt">
                        <div class="form-group">
                            <font style="color:red">*</font> Provincia
                            <input class="form-control form-control-lg" name="provincia" id="provincia" type="text" placeholder="" autocomplete="off" autofocus="autofocus" data-validation="required">
                        </div>
                    </div>
                    <div class="col-sm-3 floatt">
                        <div class="form-group">
                            <font style="color:red">*</font> Departamento
                            <input class="form-control form-control-lg" name="departamento" id="departamento" type="text" placeholder="" autocomplete="off" autofocus="autofocus" data-validation="required">
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                    
                    <div class="col-sm-3 floatt">
                        <div class="form-group">
                            <font style="color:red">*</font> Fecha de Nacimiento
                            <input type='text' class='datepicker-here form-control' id="fecha_nacimiento" name="fecha_nacimiento" data-validation="required"/>
                        </div>
                    </div>
                    <div class="col-sm-3 floatt">
                        <div class="form-group">
                            <font style="color:red">*</font>  Estado Civil
                            <!-- <input class="form-control form-control-lg" name="estado_civil" id="estado_civil" type="text" placeholder="" autocomplete="off" autofocus="autofocus"> -->
                            <select class="form-control form-control-lg" name="estado_civil" id="estado_civil">
                                <option value="">Seleccionar</option>
                                <option value="SOLTERO">Soltero/a</option>
                                <option value="CASADO">Casado/a</option>
                                <option value="VIUDO">Viudo/a</option>
                                <option value="CONVIVIENTE">Conviviente</option>
                                <option value="DIVORCIADO">Divorciado/a</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3 floatt">
                        <div class="form-group">
                            <font style="color:red">*</font> C.I.
                            <input class="form-control form-control-lg" name="ci" id="ci" type="text" placeholder="" autocomplete="off" autofocus="autofocus" data-validation="required">
                        </div>
                    </div>
                    <div class="col-sm-3 floatt">
                        <div class="form-group">
                            <font style="color:red">*</font> Expedido en
                            <input class="form-control form-control-lg" name="expirado" id="expirado" type="text" placeholder="" autocomplete="off" autofocus="autofocus" data-validation="required">
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                    
                    <div class="col-sm-3 floatt">
                        <div class="form-group">
                            <font style="color:red">*</font> Dirección
                            <input class="form-control form-control-lg" name="direccion" id="direccion" type="text" placeholder="" autocomplete="off" autofocus="autofocus" data-validation="required">
                        </div>
                    </div>
                    <div class="col-sm-3 floatt">
                        <div class="form-group">
                            Nro.
                            <input class="form-control form-control-lg" name="nro_direccion" id="nro_direccion" type="text" placeholder="" autocomplete="off" autofocus="autofocus">
                        </div>
                    </div>
                    <div class="col-sm-3 floatt">
                        <div class="form-group">
                            Zona
                            <input class="form-control form-control-lg" name="zona" id="zona" type="text" placeholder="" autocomplete="off" autofocus="autofocus">
                        </div>
                    </div>
                    <div class="col-sm-3 floatt">
                        <div class="form-group">
                            <font style="color:red">*</font> Ciudad
                            <input class="form-control form-control-lg" name="ciudad" id="ciudad" type="text" placeholder="" autocomplete="off" autofocus="autofocus" data-validation="required">
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                    
                    <div class="col-sm-3 floatt">
                        <div class="form-group">
                            Teléfono
                            <input class="form-control form-control-lg" name="telefono" id="telefono" type="text" placeholder="" autocomplete="off" autofocus="autofocus">
                        </div>
                    </div>
                    <div class="col-sm-3 floatt">
                        <div class="form-group">
                            <font style="color:red">*</font> Celular
                            <input class="form-control form-control-lg" name="celular" id="celular" type="text" placeholder="" autocomplete="off" autofocus="autofocus" data-validation="required">
                        </div>
                    </div>
                    <div class="col-sm-3 floatt">
                        <div class="form-group">
                            <font style="color:red">*</font> Email
                            <input class="form-control form-control-lg" name="email" id="email" type="email" placeholder="" autocomplete="off" autofocus="autofocus">
                        </div>
                    </div>
                    <div class="col-sm-3 floatt">
                        <div class="form-group">
                            <font style="color:red">*</font> Género
                            <!--input class="form-control form-control-lg" name="genero" id="genero" type="text" placeholder="" autocomplete="off" autofocus="autofocus"-->
                            <select class="form-control form-control-lg" name="genero" id="genero" >
                                <option value="">Seleccionar</option>
                                <option value="v">Masculino</option>
                                <option value="m">Femenino</option>
                            </select>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                    
                    <div class="form-group">
                        <div class="col-sm-6 floatt">
                            AFP a la que aporta
                            <input class="form-control form-control-lg" name="afp" id="afp" type="text" placeholder="" autocomplete="off" autofocus="autofocus">
                        </div>
                        <div class="col-sm-6 floatt">
                            Número de NUA
                            <input class="form-control form-control-lg" name="nua" id="nua" type="text" placeholder="" autocomplete="off" autofocus="autofocus">
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-6 floatt">
                            Nombre completo del (la) cónyuge
                            <input class="form-control form-control-lg" name="conyuge" id="conyuge" type="text" placeholder="" autocomplete="off" autofocus="autofocus">
                        </div>
                        <div class="col-sm-6 floatt">
                            Fecha de Nacimiento
                            <input type='text' class='datepicker-here form-control' id="fecha_nacimiento_c" name="fecha_nacimiento_c"/>
                        </div>
                        <div style="clear: both;"></div>
                    </div>

                    <div class="form-group">
                        <b>
                        <div class="col-sm-2 floatt2">
                            DEPENDIENTES
                        </div>
                        <div class="col-sm-4 floatt2">
                            NOMBRES Y APELLIDOS
                        </div>
                        <div class="col-sm-2 floatt2">
                            FECHA DE NACIMIENTO
                        </div>
                        <div class="col-sm-2 floatt2">
                            GENERO
                        </div>
                        <div class="col-sm-2 floatt2">
                            GRADO DE INSTRUCCIÓN
                        </div>
                        </b>
                        <div style="clear: both;"></div>
                    </div>
                    
                    <?php for($i=1;$i<=10;$i++){ ?>
                        <div class="form-group" style="margin: 0;">
                            <div class="col-sm-2 floatt2">
                                <input class="form-control form-control-lg dependiente_div<?php echo $i; ?>" name="username<?php echo $i; ?>" id="username<?php echo $i; ?>" type="text" placeholder="" autocomplete="off" autofocus="autofocus" value="Hijo <?php echo $i; ?>:" readonly <?php if($i!=1){ ?> style="display:none;" <?php } ?> onchange="Habilitar(<?php echo $i+1; ?>);">
                            </div>
                            <div class="col-sm-4 floatt2">
                                <input class="form-control form-control-lg dependiente_div<?php echo $i; ?>" name="dependiente<?php echo $i; ?>" id="dependiente<?php echo $i; ?>" type="text" placeholder="" autocomplete="off" autofocus="autofocus" <?php if($i!=1){ ?> style="display:none;" <?php } ?> onchange="Habilitar(<?php echo $i+1; ?>);">
                            </div>
                            <div class="col-sm-2 floatt2">
                                <input type='text' class='datepicker-here form-control dependiente_div<?php echo $i; ?>' id="fecha_nacimiento_d<?php echo $i; ?>" name="fecha_nacimiento_d<?php echo $i; ?>" <?php if($i!=1){ ?> style="display:none;" <?php } ?> onchange="Habilitar(<?php echo $i+1; ?>);" >
                            </div>
                            <div class="col-sm-2 floatt2">
                                <input class="form-control form-control-lg dependiente_div<?php echo $i; ?>" name="genero<?php echo $i; ?>" id="genero<?php echo $i; ?>" type="text" placeholder="" autocomplete="off" autofocus="autofocus" <?php if($i!=1){ ?> style="display:none;" <?php } ?> onchange="Habilitar(<?php echo $i+1; ?>);" >
                            </div>
                            <div class="col-sm-2 floatt2">
                                <input class="form-control form-control-lg dependiente_div<?php echo $i; ?>" name="grado<?php echo $i; ?>" id="grado<?php echo $i; ?>" type="text" placeholder="" autocomplete="off" autofocus="autofocus" <?php if($i!=1){ ?> style="display:none;" <?php } ?> onchange="Habilitar(<?php echo $i+1; ?>);">
                            </div>
                            <div style="clear: both;"></div>
                        </div>
                    <?php } ?>

                    <br>
                    
                    <div class="form-group">
                        <div class="col-sm-6 floatt">
                            <font style="color:red">*</font> Área de Postulación
                            
                            <select name="tipo_postulacion" id="tipo_postulacion" class="form-control form-control-lg text-uppercase" onchange="cargo();" data-validation="required">
                                <option value="" selected="selected">Buscar</option>
                                <?php 
                                $gondolas = $db->select('*')->from('per_cargos z')->order_by('z.cargo', 'asc')->fetch();
                                foreach ($gondolas as $gondola) { 
                                ?>
                                    <option value="<?= escape($gondola['id_cargo']); ?>"><?= escape($gondola['cargo']); ?></option>
                                <?php } ?>
                            </select>
                            
                        </div>
                        <div style="clear: both;"></div>
                    </div>   
                    <br>
                    <br>

                    <div class="AreaDocentes" style="display: none;">

                    <div class="form-group">
                    <div class="col-sm-12 tittle">
                    B) DATOS DENOMINACINALES ( solo miembros de la IASD)
                    </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-6 floatt">
                            Fecha de bautismo
                            <input type='text' class='datepicker-here form-control' id="fecha_bautismo" name="fecha_bautismo"/>
                        </div>
                        <div class="col-sm-6 floatt">
                            Pastor oficiciante
                            <input class="form-control form-control-lg" name="pastor" id="pastor" type="text" placeholder="" autocomplete="off" autofocus="autofocus">
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 floatt">
                            Iglesia/congrg./filial a la que se congrega
                            <input class="form-control form-control-lg" name="iglesia" id="iglesia" type="text" placeholder="" autocomplete="off" autofocus="autofocus">
                        </div>
                        <div class="col-sm-6 floatt">
                            Distrito
                            <input class="form-control form-control-lg" name="distrito" id="distrito" type="text" placeholder="" autocomplete="off" autofocus="autofocus">
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                                        
                    <div class="form-group">
                    <div class="col-sm-12 tittle">
                    C) DATOS PROFESIONALES (Solo para docentes de carrera)
                    </div>
                    </div>

                    <div class="form-group">
                        <span class="col-sm-12 floatt"><b>Años de servicio de en la Educación Fiscal</b></span>
                        <br>
                        <br>
                        
                        <div class="col-sm-6 floatt">
                            Categoría del escalafón del Estado
                            <input class="form-control form-control-lg" name="escalafon" id="escalafon" type="text" placeholder="" autocomplete="off" autofocus="autofocus">
                        </div>
                        <div class="col-sm-6 floatt">
                            Fecha
                            <input type='text' class='datepicker-here form-control' id="fecha_escalafon" name="fecha_escalafon"/>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 floatt">
                            Unidad educativa fiscal o privada (actual)
                            <input class="form-control form-control-lg" name="unidad" id="unidad" type="text" placeholder="" autocomplete="off" autofocus="autofocus">
                        </div>
                        <div class="col-sm-6 floatt">
                            Turno
                            <input class="form-control form-control-lg" name="turno" id="turno" type="text" placeholder="" autocomplete="off" autofocus="autofocus">
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 floatt">
                            Área o Asignatura (actual)
                            <input class="form-control form-control-lg" name="asignatura" id="asignatura" type="text" placeholder="" autocomplete="off" autofocus="autofocus">
                        </div>
                        <div class="col-sm-6 floatt">
                            Períodos
                            <input class="form-control form-control-lg" name="periodos" id="periodos" type="text" placeholder="" autocomplete="off" autofocus="autofocus">
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                                        
                    <div class="form-group">
                    <div class="col-sm-12 tittle">
                    D) FORMACIÓN ACADÉMICA Y FORMACIÓN CONTINUA
                    </div>
                    </div>

                    <div class="form-group">
                        <b>
                        <div class="col-sm-2 floatt2">
                            NIVEL
                        </div>
                        <div class="col-sm-4 floatt2">
                            ÁREA ACADÉMICA O ESPECIALIDAD
                        </div>
                        <div class="col-sm-2 floatt2"> 
                            FECHA DEL TÍTULO
                        </div>
                        <div class="col-sm-2 floatt2">
                            INSTITUCIÓN 
                        </div>
                        <div class="col-sm-2 floatt2">
                            OBSERVACIÓN/CARGA HORARIA
                        </div>
                        </b>
                        <div style="clear: both;"></div>
                    </div>
                    <?php for($i=1;$i<=50;$i++){ ?>
                    <div class="form-group" style="margin: 0;">
                        <div class="col-sm-2 floatt2">
                            <input class="form-control form-control-lg formacion_div<?php echo $i; ?>" name="nivel_t<?php echo $i; ?>" id="nivel_t<?php echo $i; ?>" type="text" placeholder="" autocomplete="off" autofocus="autofocus" <?php if($i!=1){ ?> style="display:none;" <?php } ?> onchange="Habilitar_formacion(<?php echo $i+1; ?>);">
                        </div>
                        <div class="col-sm-4 floatt2">
                            <input class="form-control form-control-lg formacion_div<?php echo $i; ?>" name="especialidad_t<?php echo $i; ?>" id="espacialidad_t<?php echo $i; ?>" type="text" placeholder="" autocomplete="off" autofocus="autofocus" <?php if($i!=1){ ?> style="display:none;" <?php } ?> onchange="Habilitar_formacion(<?php echo $i+1; ?>);">
                        </div>
                        <div class="col-sm-2 floatt2">
                            <input type='text' class='datepicker-here form-control formacion_div<?php echo $i; ?>' id="fecha_nacimiento_t<?php echo $i; ?>" name="fecha_nacimiento_t<?php echo $i; ?>" <?php if($i!=1){ ?> style="display:none;" <?php } ?> onchange="Habilitar_formacion(<?php echo $i+1; ?>);"/>
                        </div>
                        <div class="col-sm-2 floatt2">
                            <input class="form-control form-control-lg formacion_div<?php echo $i; ?>" name="institucion_t<?php echo $i; ?>" id="institucion_t<?php echo $i; ?>" type="text" placeholder="" autocomplete="off" autofocus="autofocus" <?php if($i!=1){ ?> style="display:none;" <?php } ?> onchange="Habilitar_formacion(<?php echo $i+1; ?>);">
                        </div>
                        <div class="col-sm-2 floatt2">
                            <input class="form-control form-control-lg formacion_div<?php echo $i; ?>" name="observacion_t<?php echo $i; ?>" id="observacion_t<?php echo $i; ?>" type="text" placeholder="" autocomplete="off" autofocus="autofocus" <?php if($i!=1){ ?> style="display:none;" <?php } ?> onchange="Habilitar_formacion(<?php echo $i+1; ?>);">
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <?php } ?>
                    
                    <br>
                    <br>

                    <div class="form-group">
                    <div class="col-sm-12 tittle">
                    E) OTROS CONOCIMIENTOS Y HABILIDADES ESPECÍFICAS
                    </div>
                    </div>

                    <div class="form-group">
                        <b>
                        <div class="col-sm-4 floatt2">
                            ITEM/ÁREA
                        </div>
                        <div class="col-sm-4 floatt2">
                            DESCRIPCIÓN DEL CONOCIMIENTO/HABILIDAD
                        </div>
                        <div class="col-sm-4 floatt2">
                            INSTITUCIÓN
                        </div>
                        </b>
                        <div style="clear: both;"></div>
                    </div>
                    <?php for($i=1;$i<=50;$i++){ ?>
                    <div class="form-group" style="margin: 0;">
                        <div class="col-sm-4 floatt2">
                            <input class="form-control form-control-lg conocimiento_div<?php echo $i; ?>" name="item<?php echo $i; ?>" id="item<?php echo $i; ?>" type="text" placeholder="" autocomplete="off" autofocus="autofocus" <?php if($i!=1){ ?> style="display:none;" <?php } ?> onchange="Habilitar_conocimiento(<?php echo $i+1; ?>);">
                        </div>
                        <div class="col-sm-4 floatt2">
                            <input class="form-control form-control-lg conocimiento_div<?php echo $i; ?>" name="habilidad<?php echo $i; ?>" id="habilidad<?php echo $i; ?>" type="text" placeholder="" autocomplete="off" autofocus="autofocus" <?php if($i!=1){ ?> style="display:none;" <?php } ?> onchange="Habilitar_conocimiento(<?php echo $i+1; ?>);">
                        </div>
                        <div class="col-sm-4 floatt2">
                            <input class="form-control form-control-lg conocimiento_div<?php echo $i; ?>" name="institucion<?php echo $i; ?>" id="institucion<?php echo $i; ?>" type="text" placeholder="" autocomplete="off" autofocus="autofocus" <?php if($i!=1){ ?> style="display:none;" <?php } ?> onchange="Habilitar_conocimiento(<?php echo $i+1; ?>);">
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <?php } ?>
                    
                    <br>
                    <br>

                    <div class="form-group">
                    <div class="col-sm-12 tittle">
                    F) EXPERIENCIA LABORAL                   
                    </div>
                    </div>

                    <div class="form-group">
                        <b>
                        <div class="col-sm-2 floatt2">
                            FECHA DE INGRESO 
                        </div>
                        <div class="col-sm-2 floatt2">
                            FECHA DE SALIDA
                        </div>
                        <div class="col-sm-3 floatt2">
                            MOTIVO DEL RETIRO
                        </div>
                        <div class="col-sm-2 floatt2">
                            CARGO
                        </div>
                        <div class="col-sm-3 floatt2">
                            INSTITUCIÓN
                        </div>
                        </b>
                        <div style="clear: both;"></div>
                    </div>
                    <?php for($i=1;$i<=50;$i++){ ?>
                    <div class="form-group" style="margin: 0;">
                        <div class="col-sm-2 floatt2">
                            <input class="form-control form-control-lg experiencia_div<?php echo $i; ?>" name="fecha_ingreso<?php echo $i; ?>" id="fecha_ingreso<?php echo $i; ?>" type="text" placeholder="" autocomplete="off" autofocus="autofocus" <?php if($i!=1){ ?> style="display:none;" <?php } ?> onchange="Habilitar_experiencia(<?php echo $i+1; ?>);">
                        </div>
                        <div class="col-sm-2 floatt2">
                            <input class="form-control form-control-lg experiencia_div<?php echo $i; ?>" name="fecha_salida<?php echo $i; ?>" id="fecha_salida<?php echo $i; ?>" type="text" placeholder="" autocomplete="off" autofocus="autofocus" <?php if($i!=1){ ?> style="display:none;" <?php } ?> onchange="Habilitar_experiencia(<?php echo $i+1; ?>);">
                        </div>
                        <div class="col-sm-3 floatt2">
                            <input class="form-control form-control-lg experiencia_div<?php echo $i; ?>" name="motivo_retiro<?php echo $i; ?>" id="motivo_retiro<?php echo $i; ?>" type="text" placeholder="" autocomplete="off" autofocus="autofocus" <?php if($i!=1){ ?> style="display:none;" <?php } ?> onchange="Habilitar_experiencia(<?php echo $i+1; ?>);">
                        </div>
                        <div class="col-sm-2 floatt2">
                            <input class="form-control form-control-lg experiencia_div<?php echo $i; ?>" name="cargo<?php echo $i; ?>" id="cargo<?php echo $i; ?>" type="text" placeholder="" autocomplete="off" autofocus="autofocus" <?php if($i!=1){ ?> style="display:none;" <?php } ?> onchange="Habilitar_experiencia(<?php echo $i+1; ?>);">
                        </div>
                        <div class="col-sm-3 floatt2">
                            <input class="form-control form-control-lg experiencia_div<?php echo $i; ?>" name="institución<?php echo $i; ?>" id="institución<?php echo $i; ?>" type="text" placeholder="" autocomplete="off" autofocus="autofocus" <?php if($i!=1){ ?> style="display:none;" <?php } ?> onchange="Habilitar_experiencia(<?php echo $i+1; ?>);">
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <?php } ?>
                    </div>
                    
                    <br>
                    <br>
                    
                    <div class="form-group" style="margin: 0;">
                        <div class="col-sm-6 floatt">                    
                            <button type="button" id="submit_button" class="btn btn-primary btn-lg btn-block" onclick="enviar();">Enviar</button>
                        </div>
                        <div class="col-sm-6 floatt">                    
                            <a href="?/sitio/ingresar">
                                <button type="button" class="btn btn-default btn-lg btn-block">Cancelar</button>
                            </a>
                        </div>
                    </div>            
                </div>
            </form>

        </div>
    </div>

    <div class="splash-container" id="mensaje" style="display:none">
        <div class="card">
            <div class="card-header text-center">
                <a href="#">
                    <img class="logo-img" src="assets/imgs/logo-color.png" width="200px" alt="logo">
                </a>
                <span class="splash-description"><small STYLE="font-size:30px;color:green;">POSTULACIÓN EXITOSA...!!!</small></span>
            </div>
            <div class="card-body">
                <div class="alert alert-warning" role="alert">
                    Nota.- Debes imprimir o guardar tu postulación, gracias por postularte en nuestra Institución nosotros nos comunicaremos con usted.
                </div>
            </div>
        </div>
    </div>

    <script src="assets/themes/concept/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="assets/themes/concept/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="assets/themes/concept/assets/vendor/alertify/js/alertify.min.js"></script>
    <!--script src="assets/themes/concept/assets/vendor/cropper-mazter/js/cropper.js"></script>
    <script src="assets/themes/concept/assets/vendor/cropper-mazter/js/imagenes.js"></script-->
    <script src="assets/themes/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
    <script src="assets/themes/concept/assets/vendor/datepicker/js/datepicker.js"></script>
    <script src="assets/themes/concept/assets/vendor/datepicker/js/datepicker.es.js"></script>
    <script src="assets/themes/concept/assets/vendor/bootstrap-fileinput-master/js/fileinput.js"></script>
    <script src="assets/themes/concept/assets/vendor/bootstrap-fileinput-master/js/es.js"></script>
    <script src="assets/js/bootbox.min.js"></script>
    <script src="assets/js/jquery.validate.js"></script>
    <script src="<?= js; ?>/vfs_fonts.js"></script>
    <script src="<?= js; ?>/educheck.js"></script>

    <script>
    
    $(function () {
        $("#cioo").on('blur', function() {
            var ci = $('#cioo').val();
            $.ajax({
                    type: 'POST',
                    url: "?/sitio/procesos",
                    data: {
                        'boton': 'btn_ci',
                        'ci': ci,
                    },
                    success: function (pago) {
                        if (pago==1) {  
                            alertify.warning('La postulación es por convocatoria, Usted ya se postuló.');
                            $("#ci").val('');
                        } else {
                            $('#loader').fadeOut(100);
                            alertify.success('Usted esta habilitado para postular.');
                        }
                    }
            });
        });

        $("#form_gestion").validate({
                rules: {
                    //paterno: {required: true},
                    materno: {required: true},
                    nombre: {required: true},
                    nacionalidad: {required: true},
                    localidad: {required: true},
                    provincia: {required: true},
                    departamento: {required: true},
                    fecha_nacimiento: {required: true},
                    estado_civil: {required: true},
                    ci: {required: true},
                    expirado: {required: true},
                    direccion: {required: true},
                    ciudad: {required: true},
                    celular: {required: true},
                    tipo_postulacion: {required: true},
                    email: {required: true}
                },
                errorClass: "help-inline",
                errorElement: "span",
                highlight: highlight,
                unhighlight: unhighlight,
                messages: {
                    materno: "Debe ingresar apellido materno.",
                    nombre:  "Debe ingresar nombre/s.",
                    nacionalidad: "Debe ingresar su nacionalidad.",
                    localidad: "Debe ingresar su localidad.",
                    provincia: "Debe ingresar su provincia.",
                    departamento: "Debe ingresar departamaneto.",
                    fecha_nacimiento: "Debe ingresar su fecha de nacimiento.",
                    estado_civil: "Debe ingresar su estado civil actual.",
                    ci: "Debe ingresar su cédula de identidad.",
                    expirado: "Debe ingresar el expedido.",
                    direccion: "Debe ingresar su dicrección.",
                    ciudad: "Debe ingresar ciudad.",
                    celular: "Debe ingresar su número de celular.",
                    tipo_postulacion: "Debe ingresar tipo de postulación.",
                    email: "Debe ingresar su correo electrónico."
                },
                //una ves validado guardamos los datos en la DB
                submitHandler: function(form){ 
                    bootbox.confirm('Esta seguro de generar su postulación?, esta seguro de su información?', function (respuesta) {

                            //alert("en camino");

                    var factura_recibo = $('#factura_recibo').val();
                    var datos = $("#form_gestion").serialize();
                    //console.log(datos);
                    //console.log('*****************************************************************************');
                    $.ajax({
                            type: 'POST',
                            url: "?/sitio/postular-guardar",
                            data: datos,
                            success: function (data) {
                                

                                //alert(data);

                window.open('?/sitio/imprimir/'+data['id_postulacion'], true);

                window.open('?/sitio/imprimir/1'+data['id_postulacion'], '_blank');


                window.location.href = '?/sitio/imprimir/1';

                                if (data) {    
                                    alertify.success('La Postulación fue exitosa...!!!, Gracias por su postulación');
                                    // Liampiar e impresion
                                    $("#form_gestion")[0].reset();                                
                                    $("#postulacion").hide();
                                    $("#mensaje").show(); 

                                    

                                } else {
                                    $('#loader').fadeOut(100);
                                    alertify.danger('Ocurrió un problema en el proceso');
                                }
                            }
                    });
                
            
            });
            }
        });

    });

    function imprimir_postulacion(id) { 
        //alert("imprimiendo... ?/sitio/imprimir/");
        window.open('?/sitio/imprimir/'+id, true);
    }

    $('#fecha_nacimiento').datepicker({
        language: 'es',
        position:'bottom left',
        onSelect: function(fd, d, picker){
            var fecha_marcada = moment(d).format('YYYY-MM-DD');
            var hoy = moment(new Date()).format('YYYY-MM-DD');

            if(fecha_marcada < hoy){
              //$("#fecha_inicio").val(moment(fecha_marcada).format('DD-MM-YYYY HH:mm'));
            
            }else{
              alertify.error('No puede una fecha mayor a la actual');
              $("#fecha_inicio").val("");
            }
        }
    })

    $('#fecha_nacimiento_c').datepicker({
        language: 'es',
        position:'bottom left',
        onSelect: function(fd, d, picker){
            var fecha_marcada = moment(d).format('YYYY-MM-DD');
            var hoy = moment(new Date()).format('YYYY-MM-DD');

            if(fecha_marcada < hoy){
              //$("#fecha_inicio").val(moment(fecha_marcada).format('DD-MM-YYYY HH:mm'));
            
            }else{
              alertify.error('No puede una fecha mayor a la actual');
              $("#fecha_inicio").val("");
            }
        }
    })

    <?php for($i=1;$i<=10;$i++){ ?>                    
    $('#fecha_nacimiento_d<?php echo $i; ?>').datepicker({
        language: 'es',
        position:'bottom left',
        onSelect: function(fd, d, picker){
            var fecha_marcada = moment(d).format('YYYY-MM-DD');
            var hoy = moment(new Date()).format('YYYY-MM-DD');

            if(fecha_marcada < hoy){
              //$("#fecha_inicio").val(moment(fecha_marcada).format('DD-MM-YYYY HH:mm'));
            
            }else{
              alertify.error('No puede una fecha mayor a la actual');
              $("#fecha_inicio").val("");
            }
        }
    })
    <?php } ?>
                    
    
    $('#fecha_nacimiento_t1').datepicker({
        language: 'es',
        position:'bottom left',
        onSelect: function(fd, d, picker){
            var fecha_marcada = moment(d).format('YYYY-MM-DD');
            var hoy = moment(new Date()).format('YYYY-MM-DD');

            if(fecha_marcada < hoy){
              //$("#fecha_inicio").val(moment(fecha_marcada).format('DD-MM-YYYY HH:mm'));
            
            }else{
              alertify.error('No puede una fecha mayor a la actual');
              $("#fecha_inicio").val("");
            }
        }
    })

    $('#fecha_nacimiento_t2').datepicker({
        language: 'es',
        position:'bottom left',
        onSelect: function(fd, d, picker){
            var fecha_marcada = moment(d).format('YYYY-MM-DD');
            var hoy = moment(new Date()).format('YYYY-MM-DD');

            if(fecha_marcada < hoy){
              //$("#fecha_inicio").val(moment(fecha_marcada).format('DD-MM-YYYY HH:mm'));
            
            }else{
                alertify.error('No puede una fecha mayor a la actual');
                $("#fecha_inicio").val("");
            }
        }
    })

    $('#fecha_nacimiento_t3').datepicker({
        language: 'es',
        position:'bottom left',
        onSelect: function(fd, d, picker){
            var fecha_marcada = moment(d).format('YYYY-MM-DD');
            var hoy = moment(new Date()).format('YYYY-MM-DD');

            if(fecha_marcada < hoy){
              //$("#fecha_inicio").val(moment(fecha_marcada).format('DD-MM-YYYY HH:mm'));
            
            }else{
              alertify.error('No puede una fecha mayor a la actual');
              $("#fecha_inicio").val("");
            }
        }
    })

    $('#fecha_bautismo').datepicker({
        language: 'es',
        position:'bottom left',
        onSelect: function(fd, d, picker){
            var fecha_marcada = moment(d).format('YYYY-MM-DD');
            var hoy = moment(new Date()).format('YYYY-MM-DD');

            if(fecha_marcada < hoy){
              //$("#fecha_inicio").val(moment(fecha_marcada).format('DD-MM-YYYY HH:mm'));
            
            }else{
              alertify.error('No puede una fecha mayor a la actual');
              $("#fecha_inicio").val("");
            }
        }
    })

    $('#fecha_escalafon').datepicker({
        language: 'es',
        position:'bottom left',
        onSelect: function(fd, d, picker){
            var fecha_marcada = moment(d).format('YYYY-MM-DD');
            var hoy = moment(new Date()).format('YYYY-MM-DD');

            if(fecha_marcada < hoy){
              //$("#fecha_inicio").val(moment(fecha_marcada).format('DD-MM-YYYY HH:mm'));
            
            }else{
              alertify.error('No puede una fecha mayor a la actual');
              $("#fecha_inicio").val("");
            }
        }
    })

    function Habilitar(nro){
        $('.dependiente_div'+nro).css({'display':'block'});
    }
    function Habilitar_formacion(nro){
        $('.formacion_div'+nro).css({'display':'block'});
    }
    function Habilitar_conocimiento(nro){
        $('.conocimiento_div'+nro).css({'display':'block'});
    }    
    function Habilitar_experiencia(nro){
        $('.experiencia_div'+nro).css({'display':'block'});
    }

    function cargo(){
        id=$("#tipo_postulacion").val();
        
        /*if(id==1){*/
            $(".AreaDocentes").css({'display':'block'});
        /*}
        else{
            $(".AreaDocentes").css({'display':'none'});
        }*/
    }
    function enviar(){
        $('#submit_button').attr("type","submit");
    }
    </script>
</body>
</html>