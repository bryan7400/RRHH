<?php 

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene las gestiones
$gestiones = $db->select('z.id_gestion, z.gestion')->from('ins_gestion z')->where('z.estado','A')->order_by('z.gestion', 'asc')->fetch();

?>
<!doctype html>
<html lang="en"> 
<head><meta charset="gb18030">
    <!-- Required meta tags -->
    
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <link rel="icon" type="image/png" href="<?= project; ?>/icono.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="assets/themes/concept/assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/themes/concept/assets/libs/css/style.css">
    <link rel="stylesheet" href="assets/themes/concept/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
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
    </style>
</head>
<body>
<!-- <body background="fondo.jpg" style="background-repeat: no-repeat; top: 0; left: 0; width: 100%; height: 100%"> -->
    <!-- ============================================================== -->
    <!-- login page  -->
    <!-- ============================================================== -->
    <div class="splash-container">
        <div class="card">
            <div class="card-header text-center"><a href="#"><img class="logo-img" src="assets/imgs/logo-color.png" width="200px" alt="logo"></a><span class="splash-description"><small>Por favor ingrese su información de usuario.</small></span></div>
            <div class="card-body">
                <?php if ($message = get_notification()) : ?>
                  <div class="alert alert-<?= $message['type']; ?>">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong><?= $message['title']; ?></strong>
                    <p><?= $message['content']; ?></p>
                  </div>
                <?php endif ?>
                <form  method="post" action="?/<?= site; ?>/autenticar">
                    <input type="hidden" name="<?= $csrf; ?>">
                    <div class="form-group">
                        <input type="hidden" name="locale" value="">
                        <input class="form-control form-control-lg" name="username" id="username" type="text" placeholder="Usuario" autocomplete="off" autofocus="autofocus">
                    </div>
                    <!--<div class="form-group">-->
                    <!--    <input class="form-control form-control-lg" name="password" id="password" type="password" placeholder="Contraseña" autocomplete="off">-->
                    <!--</div>-->
                    <div class="input-group mb-3">
                        <input class="form-control form-control-lg" name="password" id="password" type="password" placeholder="Contraseña" autocomplete="off">
                        <div class="input-group-append" ><span class="input-group-text"><i class="fa fa-eye" id="mostrar"></i></span></div>
                    </div>
                    <div class="form-group">
                        <label class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox"><span class="custom-control-label">Recordar</span>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Iniciar Sesión</button>
                
            </div>
            <div class="card-footer bg-white p-0">
                <div class="card-footer-item card-footer-item-bordered">
                    <select class="form-control form-control-lg" name="gestion" id="gestion">
                        <?php foreach ($gestiones as $gestion) : ?>
                                <option value="<?= escape($gestion['id_gestion']); ?>" <?php if($gestion['gestion'] == date('Y')){ ?>selected="selected"<?php } ?>><?= escape($gestion['gestion']); ?></option>
                        <?php endforeach ?>
                    </select></div>
                <div class="card-footer-item card-footer-item-bordered">
                    <a href="#" class="footer-link">Olvide mi Contraseña</a>
                </div>
            </div> 
            </form>
            <a href="?/sitio/postular">
                <button type="button" class="btn btn-primary btn-lg btn-block">Haz la postulación!!!</button>
            </a>
        </div>
    </div>
  
    <!-- ============================================================== -->
    <!-- end login page  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="assets/themes/concept/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="assets/themes/concept/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <script>
    $(document).ready( function(){
       $('#mostrar').click(function(){
          if($(this).hasClass('fa-eye'))
          {
          $('#password').removeAttr('type');
          $('#mostrar').addClass('fa-eye-slash').removeClass('fa-eye');
          }
          else
          {
          //Establecemos el atributo y valor
          $('#password').attr('type','password');
          $('#mostrar').addClass('fa-eye').removeClass('fa-eye-slash');
          }
       });
    });
    </script>
</body>
 
</html>