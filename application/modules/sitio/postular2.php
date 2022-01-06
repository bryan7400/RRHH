

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
                <div class="col-sm-4 floatt">
                        <div class="form-group">
                            <font style="color:red">*</font>Buscar por C.I.
                            <input class="form-control form-control-lg" name="ci_busqueda" id="ci_busqueda" type="text" placeholder="" onkeypress="rellenar();" onkeyup="editar();" autocomplete="off" autofocus="autofocus" data-validation="required">
                        </div>
                    </div>
<form id="form_gestion" name="form_gestion" method="post">
                <input id="id_postulacion" name="id_postulacion" type="text" class="form-control">
                    <div style="clear: both;"></div>
                <?php if ($message = get_notification()) : ?>
                  <div class="alert alert-<?= $message['type']; ?>">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong><?= $message['title']; ?></strong>
                    <p><?= $message['content']; ?></p>
                  </div>
                <?php endif ?>
                <!--  action = "?/sitio/postulacion" -->
                
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
                        

                        <font style="color:red">*</font> Nacionalidad
                        <select name="nacionalidad" id="nacionalidad" class="form-control form-control-lg" onchange="listar_nacion();" required title="Seleccione una opcion" >
        <option value="" selected="selected">Seleccionar</option>

        <?php 
        $paises = $db->select('z.*')
                        ->from('sys_paises z')
                        ->order_by('z.nombre', 'asc')
                        ->fetch();

        foreach ($paises as $paise) { ?>
            <option value="<?= escape($paise['nombre']); ?>"><?= escape($paise['nombre']); ?></option>
        <?php } ?>
    </select>
                    </div>

                    <div class="col-sm-3 floatt">
                        


                        <font style="color:red">*</font> Departamento
                        <select name="departamento" id="departamento" class="form-control form-control-lg" onchange="listar_provincia();"  required title="Seleccione una opcion" >
            <option value="0">Seleccionar</option>
        </select>


                    </div>
                    <div class="col-sm-3 floatt">
                        


                        <font style="color:red">*</font> Provincia
                        <select name="provincia" onchange="listar_localidad();" id="provincia" class="form-control form-control-lg"  required title="Seleccione una opcion" >
            <option value="0">Seleccionar </option>
        </select>


                    </div>
                    <div class="col-sm-3 floatt">
                        

                        <font style="color:red">*</font> Localidad
                        <select name="localidad" id="localidad" class="form-control form-control-lg"  required title="Seleccione una opcion" >
        <option value="" selected="selected">Seleccionar</option>
        
                    </div>

                    <div class="col-sm-3 floatt">
                        

                        <font style="color:red">*</font> Provincia
                        <select name="localidadaaaa" id="localidadaaa" class="form-control form-control-lg"  required title="Seleccione una opcion" >
       
        
                    </div>
                    
                    
                    <div style="clear: both;"></div>
                    
                    <div class="col-sm-3 floatt">
                        <div class="form-group">
                            <font style="color:red">*</font> Fecha de Nacimiento
                            <input type='text' 
autocomplete="off" class='datepicker-here form-control' id="fecha_nacimiento" name="fecha_nacimiento" data-validation="required"/>
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
                            <input class="form-control form-control-lg" name="ci" id="ci" type="text" placeholder=""  autocomplete="off" autofocus="autofocus" data-validation="required">
                        </div>
                    </div>
                    <div class="col-sm-3 floatt">
                        

                            <div class="form-group">
                            <font style="color:red">*</font>  Expedido en
                            <select class="form-control form-control-lg" name="expirado" id="expirado" required title="Seleccione una opcion">
                        <option value="" selected="selected">Seleccionar</option>
                        <option value="LP" >LP</option>
                        <option value="PT" >PT</option>
                        <option value="OR" >OR</option>
                        <option value="CB" >CB</option>
                        <option value="CH" >CH</option>
                        <option value="TJ" >TJ</option>
                        <option value="BN" >BN</option>
                        <option value="PA" >PA</option>
                        <option value="SC" >SC</option>
                            </select>
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
                                <option value="" selected>Seleccionar</option>
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
                                <select class="form-control form-control-lg dependiente_div<?php echo $i; ?>" name="genero<?php echo $i; ?>" id="genero<?php echo $i; ?>" type="text" placeholder="" autocomplete="off" autofocus="autofocus" <?php if($i!=1){ ?> style="display:none;" <?php } ?> onchange="Habilitar(<?php echo $i+1; ?>);"> 


                                 <option value="" selected>Seleccionar</option>
                                <option value="v">Masculino</option>
                                <option value="m">Femenino</option>
                            </select>

                                
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



<div class="col-sm-6 floatt">
        <div class="form-row form-group">
          

          <label for="archivo_documento" class="control-label">Archivo:</label>
          <input  type="file" name="archivo_documento" id="archivo_documento" class="form-control" data-validation="required mime size dimension" data-validation-allowing="jpg, png" data-validation-max-size="4M" data-validation-dimension="max1920" required="Seleccione una imagen" accept=".pdf,.application/pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required title="Seleccione un archivo" >
          
          
        </div>      
        <div class="form-group">
             
          <input type="hidden" type="text" value="" name="archivo_documento_nombre" id="archivo_documento_nombre" class="form-control" autofocus="autofocus" data-validation="required letter length" data-validation-allowing=" " data-validation-length="max100">
           <input type="hidden" type="text" value="" name="documento" id="documento" class="form-control" autofocus="autofocus" data-validation="required letter length" data-validation-allowing=" " data-validation-length="max100">
          
          
        </div>  
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
                        if (respuesta) {

                            //alert("en camino");

                    var factura_recibo = $('#factura_recibo').val();
                    //console.log(datos);
                    //console.log('*****************************************************************************');

                    var formData = new FormData($("#form_gestion")[0]);
                    $.ajax({
                            type: 'POST',
                            url: "?/sitio/postular-guardar",
                            data: formData,
                            cache: false,
                              contentType: false,
                              processData: false,
                            success: function (data) {

                                    console.log(data['id_postulacion']);
                                    window.open('?/sitio/imprimir/'+data['id_postulacion'], "_blank");

                                if (data) {    
                                    alertify.success('La Postulación fue exitosa...!!!, Gracias por su postulación');
                                    // Liampiar e impresion
                                    $("#form_gestion")[0].reset();                                
                                    $("#postulacion").hide();
                                    $("#mensaje").show();

                                    imprimir_postulacion(data['id_postulacion']);



                                } else {
                                    $('#loader').fadeOut(100);
                                    alertify.danger('Ocurrió un problema en el proceso');
                                }


                                switch(data['id_postulacion']){

                  case '1':
                            
                          
                            alertify.success('Se registro el Contrato correctamente');
                            break;
                  case '2': 
                             
                            alertify.success('Se editó el Contrato correctamente'); 
                            break;
                }



                            }
                    });
                }
            
            });
            }
        });

    });


     
    


function listar_nacion() {
     //turno = $("#turno option:selected").val()
     // alert('list nivel');
        $.ajax({
            url: '?/sitio/procesos',
            type: 'POST',
            data: {
                'boton': 'listar_nacion'//,
                //'turno': turno
            },
            dataType: 'JSON',
            success: function(resp){
            console.log('Listar aula'+ resp); 
               // alert('ejemplo');
                //alert(resp[0]['id_catalogo_detalle']); 
                //console.log(resp);
                
                $("#departamento").html("");
                $("#departamento").append('<option value="' + 0 + '">Seleccionar departamento</option>');
                for (var i = 0; i < resp.length; i++) {
                   // if(contN<1){
                    //$("#departamento").append('<option selected value="' + resp[i]["id_departamento_academico"] + '">' + resp[i]["nombre_departamento"]+'</option>');
                   // }else{
                        $("#departamento").append('<option  value="' + resp[i]["nombre"] + '">' + resp[i]["nombre"]+'</option>');
                   // }contT++;
                }
                   
                
            }
        });
        
    }

    function listar_provincia() {
     
     departamento = $("#departamento option:selected").val()
     // alert('list nivel');
        $.ajax({
            url: '?/sitio/procesos',
            type: 'POST',
            data: {
                'departamento': departamento,
                'boton': 'listar_provincia',
            },
            dataType: 'JSON',
            success: function(resp){
            console.log('Listar aula'+ resp); 
               // alert('ejemplo');
                //alert(resp[0]['id_catalogo_detalle']); 
                //console.log(resp);
                
                $("#provincia").html("");
                $("#provincia").append('<option value="' + 0 + '">Seleccionar provincia</option>');
                for (var i = 0; i < resp.length; i++) {
                   // if(contN<1){
                    //$("#provincia").append('<option selected value="' + resp[i]["id_provincia_academico"] + '">' + resp[i]["nombre_provincia"]+'</option>');
                   // }else{
                        $("#provincia").append('<option  value="' + resp[i]["nombre"] + '">' + resp[i]["nombre"]+'</option>');
                   // }contT++;
                }     

            }
        });
        
    }  

function listar_localidad() {
     //turno = $("#turno option:selected").val()
     // alert('list nivel');
     provincia = $("#provincia option:selected").val()
        $.ajax({
            url: '?/sitio/procesos',
            type: 'POST',
            data: {
                'provincia': provincia,
                'boton': 'listar_localidad',//,
                //'turno': turno
            },
            dataType: 'JSON',
            success: function(resp){
            console.log('Listar aula'+ resp); 
               // alert('ejemplo');
                //alert(resp[0]['id_catalogo_detalle']); 
                //console.log(resp);
                
                $("#localidad").html("");
                $("#localidad").append('<option value="' + 0 + '">Seleccionar localidad</option>');
                for (var i = 0; i < resp.length; i++) {
                   // if(contN<1){
                    //$("#localidad").append('<option selected value="' + resp[i]["id_localidad_academico"] + '">' + resp[i]["nombre_localidad"]+'</option>');
                   // }else{
                        $("#localidad").append('<option  value="' + resp[i]["nombre"] + '">' + resp[i]["nombre"]+'</option>');
                   // }contT++;
                }
                   

            }
        });
        
    }  

    function imprimir_postulacion(id_post) {
        window.open('?/sitio/imprimir/'+id_post, "_blank");
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



  




    function editar(){
    var ci_busqueda=$("#ci_busqueda").val();
 
        var num1= document.getElementById('ci_busqueda').value;
    document.getElementById('ci').value = num1;    

    $.ajax({
        url: '?/sitio/procesos',
        type: 'POST',
        data:{
            'boton': 'recuperar_datos',
            'ci_busqueda':ci_busqueda 
            },
        dataType: 'JSON',
        success: function(resp){    


    
    if(resp == null ){
              //$("#fecha_inicio").val(moment(fecha_marcada).format('DD-MM-YYYY HH:mm'));

    
    $('#form_gestion')[0].reset();
    var num1= document.getElementById('ci_busqueda').value;
    document.getElementById('ci').value = num1;
    }else{

    document.getElementById("id_postulacion").value = resp["id_postulacion"];
    document.getElementById("paterno").value = resp["paterno"];
    document.getElementById("materno").value = resp["materno"]; 
    document.getElementById("nombre").value = resp["nombre"]; 
    
    $('select[id="nacionalidad"]').val(resp["nacionalidad"]);
    $('select[id="departamento"]').val(resp["departamento"]); 
    //window.document.getElementById('nacionalidad').selectedIndex = resp["departamento"];

    //document.getElementById("nacionalidad").value = resp["nacionalidad"]; 
    document.getElementById("departamento").value = resp["departamento"]; 
    document.getElementById("provincia").value = resp["provincia"]; 
    document.getElementById("localidad").value = resp["localidad"]; 
    document.getElementById("fecha_nacimiento").value = resp["fecha_nacimiento"];  
    document.getElementById("estado_civil").value = resp["estado_civil"];
    document.getElementById("expirado").value = resp["expirado"]; 
    document.getElementById("direccion").value = resp["direccion"]; 
    document.getElementById("nro_direccion").value = resp["nro_direccion"]; 
    document.getElementById("zona").value = resp["zona"]; 
    document.getElementById("ciudad").value = resp["ciudad"]; 
    document.getElementById("telefono").value = resp["telefono"]; 
    document.getElementById("celular").value = resp["celular"]; 
    document.getElementById("email").value = resp["email"]; 

    document.getElementById("genero").value = resp["genero"]; 
    document.getElementById("afp").value = resp["afp"]; 
    document.getElementById("nua").value = resp["nua"]; 
    document.getElementById("conyuge").value = resp["conyuge"]; 


    $("#archivo_documento_nombre").val(resp["archivo_documento"]);
    $('#archivo_documento').attr('src', "files/demoeducheck/rrhh/postulantes" + resp["archivo_documento"]);

    document.getElementById("fecha_nacimiento_c").value = resp["fecha_nacimiento_c"]; 
    document.getElementById("pastor").value = resp["pastor"]; 
    document.getElementById("iglesia").value = resp["iglesia"]; 
    document.getElementById("distrito").value = resp["distrito"]; 
    document.getElementById("escalafon").value = resp["escalafon"]; 
    document.getElementById("fecha_escalafon").value = resp["fecha_escalafon"]; 
    document.getElementById("unidad").value = resp["unidad"]; 
    document.getElementById("asignatura").value = resp["asignatura"]; 
    document.getElementById("periodos").value = resp["periodos"]; 

    document.getElementById("nivel_t").value = resp["nivel_t"]; 
    document.getElementById("especialidad_t").value = resp["especialidad_t"]; 
    document.getElementById("fecha_nacimiento_t").value = resp["fecha_nacimiento_t"]; 
    document.getElementById("institucion_t").value = resp["institucion_t"]; 

    document.getElementById("observacion_t").value = resp["observacion_t"]; 
    document.getElementById("item").value = resp["item"]; 
    document.getElementById("habilidad").value = resp["habilidad"]; 
    document.getElementById("institucion").value = resp["institucion"]; 
    document.getElementById("fecha_ingreso").value = resp["fecha_ingreso"]; 
    document.getElementById("fecha_salida").value = resp["fecha_salida"]; 
    document.getElementById("motivo_retiro").value = resp["motivo_retiro"]; 
    document.getElementById("institución").value = resp["institución"];
            }
    
    
    


    
    
    


        }
    });
}
    

    </script>
</body>
</html>